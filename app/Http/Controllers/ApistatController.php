<?php

namespace App\Http\Controllers;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use App\Models\assurance;
use App\Models\taux;
use App\Models\societe;
use App\Models\patient;
use App\Models\chambre;
use App\Models\lit;
use App\Models\acte;
use App\Models\typeacte;
use App\Models\user;
use App\Models\role;
use App\Models\typemedecin;
use App\Models\consultation;
use App\Models\detailconsultation;
use App\Models\facture;
use App\Models\typeadmission;
use App\Models\natureadmission;
use App\Models\detailhopital;
use App\Models\produit;
use App\Models\soinshopital;
use App\Models\typesoins;
use App\Models\soinsinfirmier;
use App\Models\soinspatient;
use App\Models\sp_produit;
use App\Models\sp_soins;
use App\Models\examenpatient;
use App\Models\examen;
use App\Models\prelevement;
use App\Models\caisse;
use App\Models\historiquecaisse;
use App\Models\rdvpatient;

class ApistatController extends Controller
{
    public function statistique_reception()
    {
        $today = Carbon::today();

        $nbre_patient_day = consultation::whereDate('created_at', '=', $today)->count();

        $nbre_patient_assurer_day = consultation::join('patients', 'patients.id', '=', 'consultations.patient_id')->whereDate('consultations.created_at', '=', $today)->where('patients.assurer', '=', 'oui')->count();

        $nbre_patient_nassurer_day = consultation::join('patients', 'patients.id', '=', 'consultations.patient_id')->whereDate('consultations.created_at', '=', $today)->where('patients.assurer', '=', 'non')->count();

        // Get the total sum, ensuring it defaults to 0 if nothing is found
        $prix_cons_day = detailconsultation::whereDate('created_at', '=', $today)
            ->select(DB::raw('COALESCE(SUM(REPLACE(montant, ".", "") + 0), 0) as total_sum'))
            ->first();

        // If no result or null, default the sum to 0
        $total_sum = $prix_cons_day->total_sum ?? 0;

        return response()->json([
            'nbre_patient_day' => $nbre_patient_day,
            'nbre_patient_assurer_day' => $nbre_patient_assurer_day,
            'nbre_patient_nassurer_day' => $nbre_patient_nassurer_day,
            'prix_cons_day' => $total_sum
        ]);

    }

    public function statistique_caisse()
    {
        // Combine the queries into a single query to improve performance
        $stats = detailconsultation::select(DB::raw('
            COALESCE(SUM(REPLACE(montant, ".", "") + 0), 0) as part_total,
            COALESCE(SUM(REPLACE(part_patient, ".", "") + 0), 0) as part_patient,
            COALESCE(SUM(REPLACE(part_assurance, ".", "") + 0), 0) as part_assurance
        '))->first();

        $payer = facture::where('statut', '=', 'payer')->count();
        $impayer = facture::where('statut', '=', 'impayer')->count();

        // Return the results as JSON
        return response()->json([
            'part_total' => $stats->part_total ?? 0,
            'part_patient' => $stats->part_patient ?? 0,
            'part_assurance' => $stats->part_assurance ?? 0,
            'payer' => $payer ?? 0,
            'impayer' => $impayer ?? 0,
        ]);
    }

    public function statistique_reception_cons()
    {
        $today = Carbon::today();

        $typeacte = typeacte::join('actes', 'actes.id', '=', 'typeactes.acte_id')
                            ->where('actes.nom', '=', 'CONSULTATION')
                            ->select('typeactes.*')
                            ->get();

        foreach ($typeacte as $value) {
            $stats = detailconsultation::where('typeacte_id', '=', $value->id)
                        ->whereDate('created_at', '=', $today)
                        ->select(DB::raw('
                            COALESCE(SUM(REPLACE(montant, ".", "") + 0), 0) as part_total,
                            COALESCE(SUM(REPLACE(part_patient, ".", "") + 0), 0) as part_patient,
                            COALESCE(SUM(REPLACE(part_assurance, ".", "") + 0), 0) as part_assurance
                        '))
                        ->first();

            $nbre = detailconsultation::where('typeacte_id', '=', $value->id)->whereDate('created_at', '=', $today)->count();

            $value->part_patient = $stats->part_patient ?? 0;
            $value->part_assurance = $stats->part_assurance ?? 0;
            $value->total = $stats->part_total ?? 0;
            $value->nbre = $nbre ?? 0;

        }

        return response()->json(['typeacte' => $typeacte]);
    }

    public function statistique_cons()
    {
        $typeacte = typeacte::join('actes', 'actes.id', '=', 'typeactes.acte_id')
                            ->where('actes.nom', '=', 'CONSULTATION')
                            ->select('typeactes.*')
                            ->get();

        foreach ($typeacte as $value) {
            $stats = detailconsultation::where('typeacte_id', '=', $value->id)
                        ->select(DB::raw('
                            COALESCE(SUM(REPLACE(montant, ".", "") + 0), 0) as part_total,
                            COALESCE(SUM(REPLACE(part_patient, ".", "") + 0), 0) as part_patient,
                            COALESCE(SUM(REPLACE(part_assurance, ".", "") + 0), 0) as part_assurance
                        '))
                        ->first();

            $nbre = detailconsultation::where('typeacte_id', '=', $value->id)->count();

            $value->part_patient = $stats->part_patient;
            $value->part_assurance = $stats->part_assurance;
            $value->total = $stats->part_total;
            $value->nbre = $nbre;

        }

        return response()->json(['typeacte' => $typeacte]);
    }

    public function getWeeklyConsultations()
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        // Assuming your consultations are stored in a 'consultations' table
        // You can modify this query as needed based on your schema
        $weeklyCounts = [];
        
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $count = consultation::whereDate('created_at', $date)->count();
            $weeklyCounts[] = $count;
        }

        return response()->json($weeklyCounts);
    }

    public function getConsultationComparison()
    {
        $currentWeekCount = $this->getWeeklyConsultations()->getData();
        
        $lastWeekCount = consultation::whereBetween('created_at', [
            now()->subWeek()->startOfWeek(), 
            now()->subWeek()->endOfWeek()
        ])->count();

        $totalCurrentWeek = array_sum($currentWeekCount);

        if ($lastWeekCount > 0) {
            $percentageIncrease = (($totalCurrentWeek - $lastWeekCount) / $lastWeekCount) * 100;
        } else {
            $percentageIncrease = $totalCurrentWeek > 0 ? 100 : 0;
        }

        // Return the response with the comparison data
        return response()->json([
            'currentWeek' => $totalCurrentWeek,
            'lastWeek' => $lastWeekCount,
            'percentage' => round($percentageIncrease, 2),
        ]);
    }

    public function statistique_hos()
    {
        $today = Carbon::today();
        // Combine the queries into a single query to improve performance
        $stat_hos_day = detailhopital::whereDate('created_at', '=', $today)->count();

        // Return the results as JSON
        return response()->json([
            'stat_hos_day' => $stat_hos_day ?? 0,
        ]);
    }

    public function statistique_soinsam()
    {
        $today = Carbon::today();
        // Combine the queries into a single query to improve performance
        $stat_soinsam_day = soinspatient::whereDate('created_at', '=', $today)->count();

        // Return the results as JSON
        return response()->json([
            'stat_soinsam_day' => $stat_soinsam_day ?? 0,
        ]);
    }

    public function statistique_examen()
    {
        $today = Carbon::today();
        $ida = acte::where('nom', '=', 'ANALYSE')->first();
        $idi = acte::where('nom', '=', 'IMAGERIE')->first();

        $imagerie_day = examen::where('acte_id', '=', $idi->id)
                            ->whereDate('created_at', '=', $today)
                            ->count();

        $analyse_day = examen::where('acte_id', '=', $ida->id)
                            ->whereDate('created_at', '=', $today)
                            ->count();

        // Return the results as JSON
        return response()->json([
            'nbre_analyse_day' => $analyse_day ?? 0,
            'nbre_imagerie_day' => $imagerie_day ?? 0,
        ]);
    }

    public function stat_comp_acte($yearSelect)
    {
        $monthlyStats = [
            'consultations' => [
                'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0,
                'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
            ],
            'hospitalisations' => [
                'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0,
                'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
            ],
            'examens' => [
                'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0,
                'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
            ],
            'soins_ambulatoires' => [
                'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0,
                'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
            ]
        ];

        // 1. Consultations
        $consultations = consultation::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', $yearSelect)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        foreach ($consultations as $consultation) {
            $monthIndex = intval($consultation->month);
            $monthName = date('M', mktime(0, 0, 0, $monthIndex, 10));
            $monthlyStats['consultations'][$monthName] = $consultation->count;
        }

        // 2. Hospitalisations
        $hospitalisations = detailhopital::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', $yearSelect)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        foreach ($hospitalisations as $hospitalisation) {
            $monthIndex = intval($hospitalisation->month);
            $monthName = date('M', mktime(0, 0, 0, $monthIndex, 10));
            $monthlyStats['hospitalisations'][$monthName] = $hospitalisation->count;
        }

        // 3. Examens
        $examens = examen::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', $yearSelect)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        foreach ($examens as $examen) {
            $monthIndex = intval($examen->month);
            $monthName = date('M', mktime(0, 0, 0, $monthIndex, 10));
            $monthlyStats['examens'][$monthName] = $examen->count;
        }

        // 4. Soins Ambulatoires
        $soins = soinspatient::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', $yearSelect)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        foreach ($soins as $soin) {
            $monthIndex = intval($soin->month);
            $monthName = date('M', mktime(0, 0, 0, $monthIndex, 10));
            $monthlyStats['soins_ambulatoires'][$monthName] = $soin->count;
        }

        // Retourner les résultats sous forme de réponse JSON
        return response()->json(['monthlyStats' => $monthlyStats]);
    }

    public function stat_acte_mois($date1, $date2)
    {

        $startDate = Carbon::createFromFormat('Y-m-d', $date1)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $date2)->endOfDay(); 

        if (!$startDate || !$endDate) {
            return response()->json(['date_invalide' => 'Dates invalides']);
        }

        $consultations = consultation::whereBetween('created_at', [$startDate, $endDate])->count();
        $hospitalisations = detailhopital::whereBetween('created_at',[$startDate, $endDate])->count();
        $examens = examen::whereBetween('created_at', [$startDate, $endDate])->count();
        $soinsAmbulatoires = soinspatient::whereBetween('created_at',[$startDate, $endDate])->count();

        $m_cons = consultation::join('detailconsultations', 'detailconsultations.consultation_id', '=', 'consultations.id')
        ->join('factures', 'factures.id', '=', 'consultations.facture_id')
        ->select(DB::raw('
            COALESCE(SUM(CASE WHEN factures.statut = "payer" THEN REPLACE(detailconsultations.montant, ".", "") + 0 ELSE 0 END), 0) as total_payer,
            COALESCE(SUM(CASE WHEN factures.statut = "impayer" THEN REPLACE(detailconsultations.montant, ".", "") + 0 ELSE 0 END), 0) as total_impayer,
            COALESCE(SUM(REPLACE(detailconsultations.montant, ".", "") + 0), 0) as total_general,
            COALESCE(SUM(REPLACE(detailconsultations.part_assurance, ".", "") + 0), 0) as part_assurance,
            COALESCE(SUM(REPLACE(detailconsultations.part_patient, ".", "") + 0), 0) as part_patient,
            COALESCE(SUM(REPLACE(detailconsultations.remise, ".", "") + 0), 0) as remise,
            COALESCE(SUM(CASE WHEN factures.statut = "payer" THEN REPLACE(detailconsultations.part_patient, ".", "") + 0 ELSE 0 END), 0) as part_patient_payer,
            COALESCE(SUM(CASE WHEN factures.statut = "impayer" THEN REPLACE(detailconsultations.part_patient, ".", "") + 0 ELSE 0 END), 0) as part_patient_impayer
        '))
        ->whereBetween('detailconsultations.created_at', [$startDate, $endDate])
        ->first();


        $m_hos = detailhopital::join('factures', 'factures.id', '=', 'detailhopitals.facture_id')
        ->select(DB::raw('
            COALESCE(SUM(CASE WHEN factures.statut = "payer" THEN REPLACE(detailhopitals.montant, ".", "") + 0 ELSE 0 END), 0) as total_payer,
            COALESCE(SUM(CASE WHEN factures.statut = "impayer" THEN REPLACE(detailhopitals.montant, ".", "") + 0 ELSE 0 END), 0) as total_impayer,
            COALESCE(SUM(REPLACE(detailhopitals.montant, ".", "") + 0), 0) as total_general,
            COALESCE(SUM(REPLACE(detailhopitals.part_assurance, ".", "") + 0), 0) as part_assurance,
            COALESCE(SUM(REPLACE(detailhopitals.part_patient, ".", "") + 0), 0) as part_patient,
            COALESCE(SUM(REPLACE(detailhopitals.remise, ".", "") + 0), 0) as remise,
            COALESCE(SUM(CASE WHEN factures.statut = "payer" THEN REPLACE(detailhopitals.part_patient, ".", "") + 0 ELSE 0 END), 0) as part_patient_payer,
            COALESCE(SUM(CASE WHEN factures.statut = "impayer" THEN REPLACE(detailhopitals.part_patient, ".", "") + 0 ELSE 0 END), 0) as part_patient_impayer
        '))
        ->whereBetween('detailhopitals.created_at', [$startDate, $endDate])
        ->first();

        $m_exam = examen::join('factures', 'factures.id', '=', 'examens.facture_id')
        ->select(DB::raw('
            COALESCE(SUM(CASE WHEN factures.statut = "payer" THEN REPLACE(examens.montant, ".", "") + 0 ELSE 0 END), 0) as total_payer,
            COALESCE(SUM(CASE WHEN factures.statut = "impayer" THEN REPLACE(examens.montant, ".", "") + 0 ELSE 0 END), 0) as total_impayer,
            COALESCE(SUM(REPLACE(examens.montant, ".", "") + 0), 0) as total_general,
            COALESCE(SUM(REPLACE(examens.part_assurance, ".", "") + 0), 0) as part_assurance,
            COALESCE(SUM(REPLACE(examens.part_patient, ".", "") + 0), 0) as part_patient,
            COALESCE(SUM(CASE WHEN factures.statut = "payer" THEN REPLACE(examens.part_patient, ".", "") + 0 ELSE 0 END), 0) as part_patient_payer,
            COALESCE(SUM(CASE WHEN factures.statut = "impayer" THEN REPLACE(examens.part_patient, ".", "") + 0 ELSE 0 END), 0) as part_patient_impayer
        '))
        ->whereBetween('examens.created_at', [$startDate, $endDate])
        ->first();

        $m_soinsam = soinspatient::join('factures', 'factures.id', '=', 'soinspatients.facture_id')
        ->select(DB::raw('
            COALESCE(SUM(CASE WHEN factures.statut = "payer" THEN REPLACE(soinspatients.montant, ".", "") + 0 ELSE 0 END), 0) as total_payer,
            COALESCE(SUM(CASE WHEN factures.statut = "impayer" THEN REPLACE(soinspatients.montant, ".", "") + 0 ELSE 0 END), 0) as total_impayer,
            COALESCE(SUM(REPLACE(soinspatients.montant, ".", "") + 0), 0) as total_general,
            COALESCE(SUM(REPLACE(soinspatients.part_assurance, ".", "") + 0), 0) as part_assurance,
            COALESCE(SUM(REPLACE(soinspatients.part_patient, ".", "") + 0), 0) as part_patient,
            COALESCE(SUM(REPLACE(soinspatients.remise, ".", "") + 0), 0) as remise,
            COALESCE(SUM(CASE WHEN factures.statut = "payer" THEN REPLACE(soinspatients.part_patient, ".", "") + 0 ELSE 0 END), 0) as part_patient_payer,
            COALESCE(SUM(CASE WHEN factures.statut = "impayer" THEN REPLACE(soinspatients.part_patient, ".", "") + 0 ELSE 0 END), 0) as part_patient_impayer
        '))
        ->whereBetween('soinspatients.created_at', [$startDate, $endDate])
        ->first();

        $data = [
            'cons' => $consultations ?? 0,
            'hos' => $hospitalisations ?? 0,
            'exam' => $examens ?? 0,
            'soinsam' => $soinsAmbulatoires ?? 0,
            'm_cons' => $m_cons,
            'm_hos' => $m_hos,
            'm_exam' => $m_exam,
            'm_soinsam' => $m_soinsam,
        ];

        // -------------------------------------------------


        $acte = acte::where('nom', '=', 'CONSULTATION')->first();

        $typeacte = typeacte::where('acte_id', '=', $acte->id)->select('typeactes.*')->get();

        foreach ($typeacte as $value) {

            $stats = detailconsultation::where('typeacte_id', '=', $value->id)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->select(DB::raw('
                            COALESCE(SUM(REPLACE(montant, ".", "") + 0), 0) as part_total,
                            COALESCE(SUM(REPLACE(part_patient, ".", "") + 0), 0) as part_patient,
                            COALESCE(SUM(REPLACE(part_assurance, ".", "") + 0), 0) as part_assurance,
                            COALESCE(SUM(REPLACE(remise, ".", "") + 0), 0) as remise
                        '))
                        ->first();

            $nbre = detailconsultation::where('typeacte_id', '=', $value->id)->whereBetween('created_at', [$startDate, $endDate])->count();

            if ($stats) {
                $value->part_patient = $stats->part_patient ?? 0;
                $value->part_assurance = $stats->part_assurance ?? 0;
                $value->total = $stats->part_total ?? 0;
                $value->remise = $stats->remise ?? 0;
                $value->nbre = $nbre ?? 0;
            } else {
                $value->part_patient = 0;
                $value->part_assurance = 0;
                $value->total = 0;
                $value->remise = 0;
                $value->nbre = 0;
            }

        }

        // -------------------------------------------------

        $fac_nbre = facture::whereBetween('created_at', [$startDate, $endDate])->count();

        $fac_total = $m_cons->total_general + $m_hos->total_general + $m_exam->total_general + $m_soinsam->total_general ;

        $fac_remise = $m_cons->remise + $m_hos->remise + $m_soinsam->remise ;

        $fac_impayer = $m_cons->total_impayer + $m_hos->total_impayer + $m_exam->total_impayer + $m_soinsam->total_impayer ;

        $fac_payer = $m_cons->total_payer + $m_hos->total_payer + $m_exam->total_payer + $m_soinsam->total_payer ;

        $fac_assurance = $m_cons->part_assurance + $m_hos->part_assurance + $m_exam->part_assurance + $m_soinsam->part_assurance ;

        $fac_patient = $m_cons->part_patient + $m_hos->part_patient + $m_exam->part_patient + $m_soinsam->part_patient ;

        $fac_patient_payer = $m_cons->part_patient_payer + $m_hos->part_patient_payer + $m_exam->part_patient_payer + $m_soinsam->part_patient_payer ;

        $fac_patient_impayer = $m_cons->part_patient_impayer + $m_hos->part_patient_impayer + $m_exam->part_patient_impayer + $m_soinsam->part_patient_impayer ;

        $fac_patient_total = $fac_patient_impayer + $fac_patient_payer;

        $dataCaisse = [
            'fac_nbre' => $fac_nbre ?? 0,
            'fac_total' => $fac_total ?? 0,
            'fac_impayer' => $fac_impayer ?? 0,
            'fac_payer' => $fac_payer ?? 0,
            'fac_assurance' => $fac_assurance ?? 0,
            'fac_patient' => $fac_patient ?? 0,
            'fac_patient_payer' => $fac_patient_payer ?? 0,
            'fac_patient_impayer' => $fac_patient_impayer ?? 0,
            'fac_patient_total' => $fac_patient_total ?? 0,
            'fac_remise' => $fac_remise ?? 0,
        ];

        return response()->json([
            'data' => $data,
            'dataCaisse' => $dataCaisse,
            'typeacte' => $typeacte,
        ]);
    }

    public function statistique_patient(Request $request)
    {
        $stat_h = patient::where('sexe', '=', 'M')->count();
        $stat_f = patient::where('sexe', '=', 'Mme')->count();
        $stat_a = patient::where('assurer', '=', 'oui')->count();
        $stat_an = patient::where('assurer', '=', 'non')->count();

        return response()->json([
            'stat_h' => $stat_h ?? 0,
            'stat_f' => $stat_f ?? 0,
            'stat_a' => $stat_a ?? 0,
            'stat_an' => $stat_an ?? 0,
        ]);
    }

    public function patient_stat($id)
    {
        $nbre_cons = consultation::where('patient_id', '=', $id)->count();
        $nbre_hos = detailhopital::where('patient_id', '=', $id)->count();
        $nbre_exam = examen::where('patient_id', '=', $id)->count();
        $nbre_soinsam = soinspatient::where('patient_id', '=', $id)->count();

        $m_cons = consultation::join('detailconsultations', 'detailconsultations.consultation_id', '=', 'consultations.id')
        ->join('factures', 'factures.id', '=', 'consultations.facture_id')
        ->where('consultations.patient_id', '=', $id)
        ->select(DB::raw('
            COALESCE(SUM(CASE WHEN factures.statut = "payer" THEN REPLACE(detailconsultations.montant, ".", "") + 0 ELSE 0 END), 0) as total_payer,
            COALESCE(SUM(CASE WHEN factures.statut = "impayer" THEN REPLACE(detailconsultations.montant, ".", "") + 0 ELSE 0 END), 0) as total_impayer,
            COALESCE(SUM(REPLACE(detailconsultations.montant, ".", "") + 0), 0) as total_general,
            COALESCE(SUM(REPLACE(detailconsultations.part_assurance, ".", "") + 0), 0) as part_assurance,
            COALESCE(SUM(REPLACE(detailconsultations.part_patient, ".", "") + 0), 0) as part_patient,
            COALESCE(SUM(REPLACE(detailconsultations.remise, ".", "") + 0), 0) as remise,
            COALESCE(SUM(CASE WHEN factures.statut = "payer" THEN REPLACE(detailconsultations.part_patient, ".", "") + 0 ELSE 0 END), 0) as part_patient_payer,
            COALESCE(SUM(CASE WHEN factures.statut = "impayer" THEN REPLACE(detailconsultations.part_patient, ".", "") + 0 ELSE 0 END), 0) as part_patient_impayer
        '))
        ->first();


        $m_hos = detailhopital::join('factures', 'factures.id', '=', 'detailhopitals.facture_id')
        ->where('detailhopitals.patient_id', '=', $id)
        ->select(DB::raw('
            COALESCE(SUM(CASE WHEN factures.statut = "payer" THEN REPLACE(detailhopitals.montant, ".", "") + 0 ELSE 0 END), 0) as total_payer,
            COALESCE(SUM(CASE WHEN factures.statut = "impayer" THEN REPLACE(detailhopitals.montant, ".", "") + 0 ELSE 0 END), 0) as total_impayer,
            COALESCE(SUM(REPLACE(detailhopitals.montant, ".", "") + 0), 0) as total_general,
            COALESCE(SUM(REPLACE(detailhopitals.part_assurance, ".", "") + 0), 0) as part_assurance,
            COALESCE(SUM(REPLACE(detailhopitals.part_patient, ".", "") + 0), 0) as part_patient,
            COALESCE(SUM(REPLACE(detailhopitals.remise, ".", "") + 0), 0) as remise,
            COALESCE(SUM(CASE WHEN factures.statut = "payer" THEN REPLACE(detailhopitals.part_patient, ".", "") + 0 ELSE 0 END), 0) as part_patient_payer,
            COALESCE(SUM(CASE WHEN factures.statut = "impayer" THEN REPLACE(detailhopitals.part_patient, ".", "") + 0 ELSE 0 END), 0) as part_patient_impayer
        '))
        ->first();

        $m_exam = examen::join('factures', 'factures.id', '=', 'examens.facture_id')
        ->where('examens.patient_id', '=', $id)
        ->select(DB::raw('
            COALESCE(SUM(CASE WHEN factures.statut = "payer" THEN REPLACE(examens.montant, ".", "") + 0 ELSE 0 END), 0) as total_payer,
            COALESCE(SUM(CASE WHEN factures.statut = "impayer" THEN REPLACE(examens.montant, ".", "") + 0 ELSE 0 END), 0) as total_impayer,
            COALESCE(SUM(REPLACE(examens.montant, ".", "") + 0), 0) as total_general,
            COALESCE(SUM(REPLACE(examens.part_assurance, ".", "") + 0), 0) as part_assurance,
            COALESCE(SUM(REPLACE(examens.part_patient, ".", "") + 0), 0) as part_patient,
            COALESCE(SUM(CASE WHEN factures.statut = "payer" THEN REPLACE(examens.part_patient, ".", "") + 0 ELSE 0 END), 0) as part_patient_payer,
            COALESCE(SUM(CASE WHEN factures.statut = "impayer" THEN REPLACE(examens.part_patient, ".", "") + 0 ELSE 0 END), 0) as part_patient_impayer
        '))
        ->first();

        $m_soinsam = soinspatient::join('factures', 'factures.id', '=', 'soinspatients.facture_id')
        ->where('soinspatients.patient_id', '=', $id)
        ->select(DB::raw('
            COALESCE(SUM(CASE WHEN factures.statut = "payer" THEN REPLACE(soinspatients.montant, ".", "") + 0 ELSE 0 END), 0) as total_payer,
            COALESCE(SUM(CASE WHEN factures.statut = "impayer" THEN REPLACE(soinspatients.montant, ".", "") + 0 ELSE 0 END), 0) as total_impayer,
            COALESCE(SUM(REPLACE(soinspatients.montant, ".", "") + 0), 0) as total_general,
            COALESCE(SUM(REPLACE(soinspatients.part_assurance, ".", "") + 0), 0) as part_assurance,
            COALESCE(SUM(REPLACE(soinspatients.part_patient, ".", "") + 0), 0) as part_patient,
            COALESCE(SUM(REPLACE(soinspatients.remise, ".", "") + 0), 0) as remise,
            COALESCE(SUM(CASE WHEN factures.statut = "payer" THEN REPLACE(soinspatients.part_patient, ".", "") + 0 ELSE 0 END), 0) as part_patient_payer,
            COALESCE(SUM(CASE WHEN factures.statut = "impayer" THEN REPLACE(soinspatients.part_patient, ".", "") + 0 ELSE 0 END), 0) as part_patient_impayer
        '))
        ->first();

        $data = [
            'm_cons' => $m_cons,
            'm_hos' => $m_hos,
            'm_exam' => $m_exam,
            'm_soinsam' => $m_soinsam,
        ];

        $fac_patient_payer = $m_cons->part_patient_payer + $m_hos->part_patient_payer + $m_exam->part_patient_payer + $m_soinsam->part_patient_payer ;

        $fac_patient_impayer = $m_cons->part_patient_impayer + $m_hos->part_patient_impayer + $m_exam->part_patient_impayer + $m_soinsam->part_patient_impayer ;

        $fac_patient_total = $fac_patient_impayer + $fac_patient_payer;

        return response()->json([
            'data' => $data,
            'nbre_cons' => $nbre_cons ?? 0,
            'nbre_hos' => $nbre_hos ?? 0,
            'nbre_exam' => $nbre_exam ?? 0,
            'nbre_soinsam' => $nbre_soinsam ?? 0,
            'fac_patient_payer' => $fac_patient_payer ?? 0,
            'fac_patient_impayer' => $fac_patient_impayer ?? 0,
            'fac_patient_total' => $fac_patient_total ?? 0,
        ]);
    }

    public function assurance_stat($id)
    {
        $nbre_cons = consultation::join('patients', 'patients.id', '=', 'consultations.patient_id')
                                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                                ->where('patients.assurer', '=', 'oui')
                                ->where('assurances.id', '=', $id)
                                ->count();

        $nbre_hos = detailhopital::join('patients', 'patients.id', '=', 'detailhopitals.patient_id')
                                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                                ->where('patients.assurer', '=', 'oui')
                                ->where('assurances.id', '=', $id)
                                ->count();

        $nbre_exam = examen::join('patients', 'patients.id', '=', 'examens.patient_id')
                                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                                ->where('patients.assurer', '=', 'oui')
                                ->where('assurances.id', '=', $id)
                                ->count();

        $nbre_soinsam = soinspatient::join('patients', 'patients.id', '=', 'soinspatients.patient_id')
                                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                                ->where('patients.assurer', '=', 'oui')
                                ->where('assurances.id', '=', $id)
                                ->count();

        $m_cons = consultation::join('detailconsultations', 'detailconsultations.consultation_id', '=', 'consultations.id')
        ->join('factures', 'factures.id', '=', 'consultations.facture_id')
        ->join('patients', 'patients.id', '=', 'consultations.patient_id')
        ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
        ->where('patients.assurer', '=', 'oui')
        ->where('assurances.id', '=', $id)
        ->select(DB::raw('
            COALESCE(SUM(CASE WHEN factures.statut = "payer" THEN REPLACE(detailconsultations.montant, ".", "") + 0 ELSE 0 END), 0) as total_payer,
            COALESCE(SUM(CASE WHEN factures.statut = "impayer" THEN REPLACE(detailconsultations.montant, ".", "") + 0 ELSE 0 END), 0) as total_impayer,
            COALESCE(SUM(REPLACE(detailconsultations.montant, ".", "") + 0), 0) as total_general,
            COALESCE(SUM(REPLACE(detailconsultations.part_assurance, ".", "") + 0), 0) as part_assurance,
            COALESCE(SUM(REPLACE(detailconsultations.part_patient, ".", "") + 0), 0) as part_patient,
            COALESCE(SUM(REPLACE(detailconsultations.remise, ".", "") + 0), 0) as remise,
            COALESCE(SUM(CASE WHEN factures.statut = "payer" THEN REPLACE(detailconsultations.part_patient, ".", "") + 0 ELSE 0 END), 0) as part_patient_payer,
            COALESCE(SUM(CASE WHEN factures.statut = "impayer" THEN REPLACE(detailconsultations.part_patient, ".", "") + 0 ELSE 0 END), 0) as part_patient_impayer
        '))
        ->first();


        $m_hos = detailhopital::join('factures', 'factures.id', '=', 'detailhopitals.facture_id')
        ->join('patients', 'patients.id', '=', 'detailhopitals.patient_id')
        ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
        ->where('patients.assurer', '=', 'oui')
        ->where('assurances.id', '=', $id)
        ->select(DB::raw('
            COALESCE(SUM(CASE WHEN factures.statut = "payer" THEN REPLACE(detailhopitals.montant, ".", "") + 0 ELSE 0 END), 0) as total_payer,
            COALESCE(SUM(CASE WHEN factures.statut = "impayer" THEN REPLACE(detailhopitals.montant, ".", "") + 0 ELSE 0 END), 0) as total_impayer,
            COALESCE(SUM(REPLACE(detailhopitals.montant, ".", "") + 0), 0) as total_general,
            COALESCE(SUM(REPLACE(detailhopitals.part_assurance, ".", "") + 0), 0) as part_assurance,
            COALESCE(SUM(REPLACE(detailhopitals.part_patient, ".", "") + 0), 0) as part_patient,
            COALESCE(SUM(REPLACE(detailhopitals.remise, ".", "") + 0), 0) as remise,
            COALESCE(SUM(CASE WHEN factures.statut = "payer" THEN REPLACE(detailhopitals.part_patient, ".", "") + 0 ELSE 0 END), 0) as part_patient_payer,
            COALESCE(SUM(CASE WHEN factures.statut = "impayer" THEN REPLACE(detailhopitals.part_patient, ".", "") + 0 ELSE 0 END), 0) as part_patient_impayer
        '))
        ->first();

        $m_exam = examen::join('factures', 'factures.id', '=', 'examens.facture_id')
        ->join('patients', 'patients.id', '=', 'examens.patient_id')
        ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
        ->where('patients.assurer', '=', 'oui')
        ->where('assurances.id', '=', $id)
        ->select(DB::raw('
            COALESCE(SUM(CASE WHEN factures.statut = "payer" THEN REPLACE(examens.montant, ".", "") + 0 ELSE 0 END), 0) as total_payer,
            COALESCE(SUM(CASE WHEN factures.statut = "impayer" THEN REPLACE(examens.montant, ".", "") + 0 ELSE 0 END), 0) as total_impayer,
            COALESCE(SUM(REPLACE(examens.montant, ".", "") + 0), 0) as total_general,
            COALESCE(SUM(REPLACE(examens.part_assurance, ".", "") + 0), 0) as part_assurance,
            COALESCE(SUM(REPLACE(examens.part_patient, ".", "") + 0), 0) as part_patient,
            COALESCE(SUM(CASE WHEN factures.statut = "payer" THEN REPLACE(examens.part_patient, ".", "") + 0 ELSE 0 END), 0) as part_patient_payer,
            COALESCE(SUM(CASE WHEN factures.statut = "impayer" THEN REPLACE(examens.part_patient, ".", "") + 0 ELSE 0 END), 0) as part_patient_impayer
        '))
        ->first();

        $m_soinsam = soinspatient::join('factures', 'factures.id', '=', 'soinspatients.facture_id')
        ->join('patients', 'patients.id', '=', 'soinspatients.patient_id')
        ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
        ->where('patients.assurer', '=', 'oui')
        ->where('assurances.id', '=', $id)
        ->select(DB::raw('
            COALESCE(SUM(CASE WHEN factures.statut = "payer" THEN REPLACE(soinspatients.montant, ".", "") + 0 ELSE 0 END), 0) as total_payer,
            COALESCE(SUM(CASE WHEN factures.statut = "impayer" THEN REPLACE(soinspatients.montant, ".", "") + 0 ELSE 0 END), 0) as total_impayer,
            COALESCE(SUM(REPLACE(soinspatients.montant, ".", "") + 0), 0) as total_general,
            COALESCE(SUM(REPLACE(soinspatients.part_assurance, ".", "") + 0), 0) as part_assurance,
            COALESCE(SUM(REPLACE(soinspatients.part_patient, ".", "") + 0), 0) as part_patient,
            COALESCE(SUM(REPLACE(soinspatients.remise, ".", "") + 0), 0) as remise,
            COALESCE(SUM(CASE WHEN factures.statut = "payer" THEN REPLACE(soinspatients.part_patient, ".", "") + 0 ELSE 0 END), 0) as part_patient_payer,
            COALESCE(SUM(CASE WHEN factures.statut = "impayer" THEN REPLACE(soinspatients.part_patient, ".", "") + 0 ELSE 0 END), 0) as part_patient_impayer
        '))
        ->first();

        $data = [
            'm_cons' => $m_cons,
            'm_hos' => $m_hos,
            'm_exam' => $m_exam,
            'm_soinsam' => $m_soinsam,
        ];

        return response()->json([
            'data' => $data,
            'nbre_cons' => $nbre_cons ?? 0,
            'nbre_hos' => $nbre_hos ?? 0,
            'nbre_exam' => $nbre_exam ?? 0,
            'nbre_soinsam' => $nbre_soinsam ?? 0,
        ]);
    }

    public function stat_comp_ope($yearSelect)
    {
        $monthlyStats = [
            'entrer' => [
                'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0,
                'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
            ],
            'sortie' => [
                'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0,
                'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
            ],
            'total' => [
                'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0,
                'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
            ]
        ];

        $totalG = 0;
        $total_entrer = 0;
        $total_sortie = 0;

        // 1. Consultations
        $entrer = historiquecaisse::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COALESCE(SUM(REPLACE(montant, ".", "") + 0), 0) as montant')
            )
            ->where('typemvt', '=', 'Entrer de Caisse')
            ->whereYear('created_at', $yearSelect)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        foreach ($entrer as $entre) {
            $monthIndex = intval($entre->month);
            $monthName = date('M', mktime(0, 0, 0, $monthIndex, 10));
            $monthlyStats['entrer'][$monthName] = $entre->montant;
            $total_entrer += $entre->montant;
        }

        // 2. Hospitalisations
        $sortie = historiquecaisse::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COALESCE(SUM(REPLACE(montant, ".", "") + 0), 0) as montant')
            )
            ->where('typemvt', '=', 'Sortie de Caisse')
            ->whereYear('created_at', $yearSelect)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        foreach ($sortie as $sorti) {
            $monthIndex = intval($sorti->month);
            $monthName = date('M', mktime(0, 0, 0, $monthIndex, 10));
            $monthlyStats['sortie'][$monthName] = $sorti->montant;
            $total_sortie += $sorti->montant;
        }

        $total = historiquecaisse::whereYear('created_at', $yearSelect)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('
                    COALESCE(SUM(IF(typemvt = "Entrer de Caisse", REPLACE(montant, ".", "") + 0, 0)), 0) as total_entrer,
                    COALESCE(SUM(IF(typemvt = "Sortie de Caisse", REPLACE(montant, ".", "") + 0, 0)), 0) as total_sortie
                ')
            )
            ->get();

        foreach ($total as $value) {
            $Gtotal = (int)$value->total_entrer - (int)$value->total_sortie;
            $monthIndex = intval($value->month);
            $monthName = date('M', mktime(0, 0, 0, $monthIndex, 10));
            $monthlyStats['total'][$monthName] = $Gtotal;
            $totalG += $Gtotal;
        }

        // Retourner les résultats sous forme de réponse JSON
        return response()->json([
            'monthlyStats' => $monthlyStats,
            'total_entrer' => $total_entrer,
            'total_sortie' => $total_sortie,
            'total' => $totalG,
        ]);
    }

    public function count_rdv_two_day()
    {
        $twoDaysLater = Carbon::today()->addDays(2);

        $nbre = rdvpatient::whereDate('date', '=', $twoDaysLater)->count();

        return response()->json([
            'nbre' => $nbre,
        ]);

    }

    public function stat_chiff_acte($yearSelect)
    {
        $monthlyStats = [
            'consultation' => [
                'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0,
                'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
            ],
            'examen' => [
                'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0,
                'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
            ],
            'hospitalisation' => [
                'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0,
                'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
            ],
            'soins ambulatoire' => [
                'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0,
                'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
            ],
        ];

        $consultation = DB::table('consultations')
        ->join('detailconsultations', 'detailconsultations.consultation_id', '=', 'consultations.id')
        ->join('factures', 'factures.id', '=', 'consultations.facture_id')
        ->where('factures.statut', 'payer')
        ->whereYear('consultations.created_at', $yearSelect)
        ->select(
            DB::raw('MONTH(consultations.created_at) as month'),
            DB::raw('COALESCE(SUM(REPLACE(detailconsultations.montant, ".", "") + 0), 0) as montant')
        )
        ->groupBy(DB::raw('MONTH(consultations.created_at)'))
        ->get();


        foreach ($consultation as $value) {
            $monthIndex = intval($value->month);
            $monthName = date('M', mktime(0, 0, 0, $monthIndex, 10));
            $monthlyStats['consultation'][$monthName] = $value->montant;
        }

        $examen = examen::join('factures', 'factures.id', '=', 'examens.facture_id')
        ->where('factures.statut', '=', 'payer')
        ->groupBy(DB::raw('MONTH(examens.created_at)'))
        ->whereYear('examens.created_at', $yearSelect)
        ->select(
            DB::raw('MONTH(examens.created_at) as month'),
            DB::raw('COALESCE(SUM(REPLACE(examens.montant, ".", "") + 0), 0) as montant')
        )
        ->get();

        foreach ($examen as $value) {
            $monthIndex = intval($value->month);
            $monthName = date('M', mktime(0, 0, 0, $monthIndex, 10));
            $monthlyStats['examen'][$monthName] = $value->montant;
        }

        $hos = detailhopital::join('factures', 'factures.id', '=', 'detailhopitals.facture_id')
        ->where('factures.statut', '=', 'payer')
        ->groupBy(DB::raw('MONTH(detailhopitals.created_at)'))
        ->whereYear('detailhopitals.created_at', $yearSelect)
        ->select(
            DB::raw('MONTH(detailhopitals.created_at) as month'),
            DB::raw('COALESCE(SUM(REPLACE(detailhopitals.montant, ".", "") + 0), 0) as montant')
        )
        ->get();

        foreach ($hos as $value) {
            $monthIndex = intval($value->month);
            $monthName = date('M', mktime(0, 0, 0, $monthIndex, 10));
            $monthlyStats['hospitalisation'][$monthName] = $value->montant;
        }

        $soinsam = soinspatient::join('factures', 'factures.id', '=', 'soinspatients.facture_id')
        ->where('factures.statut', '=', 'payer')
        ->groupBy(DB::raw('MONTH(soinspatients.created_at)'))
        ->whereYear('soinspatients.created_at', $yearSelect)
        ->select(
            DB::raw('MONTH(soinspatients.created_at) as month'),
            DB::raw('COALESCE(SUM(REPLACE(soinspatients.montant, ".", "") + 0), 0) as montant')
        )
        ->get();

        foreach ($soinsam as $value) {
            $monthIndex = intval($value->month);
            $monthName = date('M', mktime(0, 0, 0, $monthIndex, 10));
            $monthlyStats['soins ambulatoire'][$monthName] = $value->montant;
        }

        // Retourner les résultats sous forme de réponse JSON
        return response()->json([
            'monthlyStats' => $monthlyStats,
        ]);
    }

}
