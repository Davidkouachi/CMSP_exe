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
use App\Models\typemedecin;
use App\Models\user;
use App\Models\role;
use App\Models\consultation;
use App\Models\detailconsultation;
use App\Models\typeadmission;
use App\Models\natureadmission;
use App\Models\detailhopital;
use App\Models\facture;
use App\Models\produit;
use App\Models\soinshopital;
use App\Models\soinsinfirmier;
use App\Models\typesoins;
use App\Models\soinspatient;
use App\Models\sp_produit;
use App\Models\sp_soins;
use App\Models\examenpatient;
use App\Models\examen;
use App\Models\prelevement;

class ApilistfactureController extends Controller
{
    public function list_facture_inpayer($numfac)
    {

        $facture = DB::table('consultation')
            ->join('dossierpatient', 'consultation.idenregistremetpatient', '=', 'dossierpatient.idenregistremetpatient')
            ->join('garantie', 'consultation.codeacte', '=', 'garantie.codgaran')
            ->join('factures', 'consultation.numfac', '=', 'factures.numfac')
            ->where('garantie.codtypgar', '=', 'CONS')
            ->where('consultation.numfac', '=', $numfac)
            ->select(
                'consultation.idconsexterne as idconsexterne',
                'consultation.montant as montant',
                'consultation.date as date',
                'consultation.numfac as numfac',
                'dossierpatient.numdossier as numdossier',
                'dossierpatient.idenregistremetpatient as matricule_patient',
                'factures.remise as remise',
                'factures.montant_ass as part_assurance',
                'factures.montant_pat as part_patient',
                'factures.montantregle_pat as part_patient_regler'
            )
            ->first();

        if ($facture) {
            $facture->part_patient_regler = $facture->part_patient_regler ?? 0;

            $facture->part_patient_reste = max(0, $facture->part_patient - $facture->part_patient_regler);

            if ($facture->part_patient_reste === 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'La facture est déjà totalement réglée.'
                ], 200); // 200 car ce n'est pas une erreur technique
            }

            return response()->json([
                'status' => 'success',
                'data' => $facture
            ], 200);

        } else {
            // Aucun résultat trouvé
            return response()->json([
                'status' => 'error',
                'message' => 'Facture introuvable.'
            ], 404);
        }

    }


    public function list_facture($date1, $date2)
    {
        $date1 = Carbon::parse($date1)->startOfDay();
        $date2 = Carbon::parse($date2)->endOfDay();

        $facture = DB::table('consultation')
            ->join('patient', 'consultation.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
            ->join('dossierpatient', 'consultation.idenregistremetpatient', '=', 'dossierpatient.idenregistremetpatient')
            ->join('medecin', 'consultation.codemedecin', '=', 'medecin.codemedecin')
            ->join('specialitemed', 'medecin.codespecialitemed', '=', 'specialitemed.codespecialitemed')
            ->join('garantie', 'consultation.codeacte', '=', 'garantie.codgaran')
            ->join('factures', 'consultation.numfac', '=', 'factures.numfac')
            ->where('garantie.codtypgar', '=', 'CONS')
            ->whereBetween('consultation.date', [$date1, $date2])
            ->select(
                'consultation.idconsexterne as idconsexterne',
                'consultation.montant as montant',
                'consultation.date as date',
                'consultation.numfac as numfac',
                'consultation.regle as statut',
                'dossierpatient.numdossier as numdossier',
                'patient.nomprenomspatient as nom_patient',
                'patient.telpatient as tel_patient',
                'medecin.nomprenomsmed as nom_medecin',
                'specialitemed.nomspecialite as specialite',
                'factures.remise as remise',
                'factures.montant_ass as part_assurance',
                'factures.montant_pat as part_patient',
                'factures.montantregle_pat as part_patient_regler',
            )
            ->orderBy('consultation.date', 'desc')->get();

        foreach ($facture as $value) {
            if ($value->part_patient == $value->part_patient_regler) {
                $value->statut_regle = 'Oui';
            } else {
                $value->statut_regle = 'Non';
            }

            $value->part_patient_reste = abs($value->part_patient - $value->part_patient_regler);
        }

        return response()->json([
            'data' => $facture,
        ]);
    }

    public function list_facture_hos()
    {
        $hopital = detailhopital::join('factures', 'factures.id','=','detailhopitals.facture_id')
                                ->where('factures.statut', '=', 'impayer')
                                ->select(
                                    'detailhopitals.*',
                                    'factures.code as code_fac',
                                )->orderBy('detailhopitals.created_at', 'desc')
                                ->get();

        return response()->json([
            'data' => $hopital,
        ]);
    }

    public function list_facture_hos_all($date1, $date2,$statut)
    {
        $date1 = Carbon::parse($date1)->startOfDay();
        $date2 = Carbon::parse($date2)->endOfDay();

        $hopitalQuery = detailhopital::join('factures', 'factures.id','=','detailhopitals.facture_id')
                                ->whereBetween('factures.created_at', [$date1, $date2])
                                ->select(
                                    'detailhopitals.*',
                                    'factures.code as code_fac',
                                    'factures.statut as statut_fac',
                                )->orderBy('detailhopitals.created_at', 'desc');

        if ($statut !== 'tous') {
            $hopitalQuery->where('factures.statut', '=', $statut);
        }

        $hopital = $hopitalQuery->get();

        return response()->json([
            'data' => $hopital,
        ]);
    }

    public function list_facture_soinsam()
    {
        $soinspatient = soinspatient::join('factures', 'factures.id','=','soinspatients.facture_id')
                                ->where('factures.statut', '=', 'impayer')
                                ->select(
                                    'soinspatients.*',
                                    'factures.code as code_fac',
                                    'factures.statut as statut_fac',
                                )->orderBy('soinspatients.created_at', 'desc')
                                ->get();

        foreach ($soinspatient as $value) {
            $produittotal = sp_produit::where('soinspatient_id', '=', $value->id)
                ->select(DB::raw('COALESCE(SUM(REPLACE(montant, ".", "") + 0), 0) as total'))
                ->first();

            $value->prototal = $produittotal->total ?? 0;

            $soinstotal = sp_soins::where('soinspatient_id', '=', $value->id)
                ->select(DB::raw('COALESCE(SUM(REPLACE(montant, ".", "") + 0), 0) as total'))
                ->first();

            $value->stotal = $soinstotal->total ?? 0;
        }

        return response()->json([
            'data' => $soinspatient,
        ]);
    }

    public function list_facture_soinsam_all($date1, $date2,$statut)
    {
        $date1 = Carbon::parse($date1)->startOfDay();
        $date2 = Carbon::parse($date2)->endOfDay();

        $spatientQuery = soinspatient::join('factures', 'factures.id','=','soinspatients.facture_id')
                                ->whereBetween('factures.created_at', [$date1, $date2])
                                ->select(
                                    'soinspatients.*',
                                    'factures.code as code_fac',
                                    'factures.statut as statut_fac',
                                    'factures.montant_verser as montant_verser',
                                    'factures.montant_remis as montant_remis',
                                )->orderBy('soinspatients.created_at', 'desc');
        if ($statut !== 'tous') {
            $spatientQuery->where('factures.statut', '=', $statut);
        }

        $soinspatient = $spatientQuery->get();

        foreach ($soinspatient as $value) {
            $produittotal = sp_produit::where('soinspatient_id', '=', $value->id)
                ->select(DB::raw('COALESCE(SUM(REPLACE(montant, ".", "") + 0), 0) as total'))
                ->first();

            $value->prototal = $produittotal->total ?? 0;

            // Total des soins
            $soinstotal = sp_soins::where('soinspatient_id', '=', $value->id)
                ->select(DB::raw('COALESCE(SUM(REPLACE(montant, ".", "") + 0), 0) as total'))
                ->first();

            $value->stotal = $soinstotal->total ?? 0;

        }

        return response()->json([
            'data' => $soinspatient,
        ]);
    }

    public function list_facture_examen()
    {
        $examen = examen::join('factures', 'factures.id', '=', 'examens.facture_id')
                            ->join('actes', 'actes.id', '=', 'examens.acte_id')
                            ->where('factures.statut', '=', 'impayer')
                            ->select(
                                'examens.*',
                                'actes.nom as acte',
                                'factures.code as code_fac',
                                'factures.statut as statut_fac',
                            )
                            ->orderBy('created_at', 'desc')
                            ->get();

        foreach ($examen as $value) {
            $nbre = examenpatient::where('examen_id', '=', $value->id)->count();
            $value->nbre =  $nbre ?? 0;

            $partPatient = str_replace('.', '', $value->part_patient);
            $prelevement = str_replace('.', '', $value->prelevement);

            // Conversion en entier
            $partPatient = (int)$partPatient;
            $prelevement = (int)$prelevement;

            $value->total_patient = $partPatient;

            $value->part_patient = $partPatient - $prelevement;
        }

        return response()->json([
            'data' => $examen,
        ]);
    }

    public function list_facture_examen_all($date1, $date2,$statut)
    {
        $date1 = Carbon::parse($date1)->startOfDay();
        $date2 = Carbon::parse($date2)->endOfDay();
        
        $examenQuery = examen::join('factures', 'factures.id', '=', 'examens.facture_id')
                            ->join('actes', 'actes.id', '=', 'examens.acte_id')
                            ->whereBetween('factures.created_at', [$date1, $date2])
                            ->select(
                                'examens.*',
                                'actes.nom as acte',
                                'factures.code as code_fac',
                                'factures.statut as statut_fac',
                            )
                            ->orderBy('factures.created_at', 'desc');

        if ($statut !== 'tous') {
            $examenQuery->where('factures.statut', '=', $statut);
        }

        $examen = $examenQuery->get();

        foreach ($examen as $value) {
            $nbre = examenpatient::where('examen_id', '=', $value->id)->count();
            $value->nbre =  $nbre ?? 0;

            $partPatient = str_replace('.', '', $value->part_patient);
            $prelevement = str_replace('.', '', $value->prelevement);

            // Conversion en entier
            $partPatient = (int)$partPatient;
            $prelevement = (int)$prelevement;

            // Calcul de la somme
            $value->total_patient = $partPatient;

            $value->part_patient = $partPatient - $prelevement;
        }

        return response()->json([
            'data' => $examen,
        ]);
    }

}
