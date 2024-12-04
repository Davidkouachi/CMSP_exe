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
use Illuminate\Support\Facades\Log;

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
use App\Models\joursemaine;
use App\Models\rdvpatient;
use App\Models\programmemedecin;
use App\Models\depotfacture;
use App\Models\caisse;
use App\Models\historiquecaisse;

class ApipdfController extends Controller
{
    public function fiche_consultation($code)
    {
        $consultation = consultation::join('detailconsultations', 'detailconsultations.consultation_id', '=', 'consultations.id')
        ->join('factures', 'factures.id', '=', 'consultations.facture_id')
        ->where('consultations.code', '=', $code)
        ->select(
            'consultations.*',
            'detailconsultations.typeacte_id as typeacte_id',
            'detailconsultations.part_assurance as part_assurance',
            'detailconsultations.part_patient as part_patient',
            'detailconsultations.remise as remise',
            'factures.code as code_fac',
        )
        ->first();

        $patient = patient::leftjoin('assurances', 'assurances.id', '=', 'patients.assurance_id')->leftjoin('tauxes', 'tauxes.id', '=', 'patients.taux_id')
        ->where('patients.id', '=', $consultation->patient_id)
        ->select('patients.*', 'assurances.nom as assurance', 'tauxes.taux as taux')
        ->first();

        if ($patient) {
            $patient->age = $patient->datenais ? Carbon::parse($patient->datenais)->age : 0;
        }

        $user = user::find($consultation->user_id);

        $typeacte = typeacte::find($consultation->typeacte_id);

        return response()->json(['patient' => $patient, 'typeacte' => $typeacte, 'user' => $user, 'consultation' => $consultation]);
    }

    public function facture_inpayer_cons($id)
    {
        $consultation = consultation::join('detailconsultations', 'detailconsultations.consultation_id', '=', 'consultations.id')
        ->join('factures', 'factures.id', '=', 'consultations.facture_id')
        ->where('consultations.id', '=', $id)
        ->select(
            'consultations.*',
            'detailconsultations.typeacte_id as typeacte_id',
            'detailconsultations.part_assurance as part_assurance',
            'detailconsultations.part_patient as part_patient',
            'detailconsultations.remise as remise',
            'factures.code as code_fac',
            'factures.date_payer as date_paye',
            'factures.montant_verser as montant_verser',
            'factures.montant_remis as montant_remis',
            'factures.statut as statut_fac',
            'factures.date_payer as date_payer',
        )
        ->first();

        $total_amount = intval(str_replace('.', '', $consultation->montant_verser));
        $paid_amount = intval(str_replace('.', '', $consultation->part_patient));
        $remis_amount = intval(str_replace('.', '', $consultation->montant_remis));
        // Calculate the remaining amount
        $remaining_amount = $total_amount - ($paid_amount + $remis_amount);
        // Format the remaining amount with periods and assign to 'montant_restant'
        $consultation->montant_restant = $this->formatWithPeriods($remaining_amount);

        $patient = patient::leftjoin('assurances', 'assurances.id', '=', 'patients.assurance_id')->leftjoin('tauxes', 'tauxes.id', '=', 'patients.taux_id')
        ->where('patients.id', '=', $consultation->patient_id)
        ->select('patients.*', 'assurances.nom as assurance', 'tauxes.taux as taux')
        ->first();

        if ($patient) {
            $patient->age = Carbon::parse($patient->datenais)->age;
        }

        $user = user::find($consultation->user_id);

        $typeacte = typeacte::find($consultation->typeacte_id);

        return response()->json(['patient' => $patient, 'typeacte' => $typeacte, 'user' => $user, 'consultation' => $consultation]);
    }

    private function formatWithPeriods($number) {
        return number_format($number, 0, '', '.');
    }

    public function imp_fac_assurance(Request $request)
    {

        $date1 = Carbon::createFromFormat('Y-m-d', $request->date1)->startOfDay();
        $date2 = Carbon::createFromFormat('Y-m-d', $request->date2)->endOfDay(); 

        $assurance = assurance::find($request->assurance_id);

        $societes = societe::all();
        $result = [];

        foreach ($societes as $key => $societe) {

            $fac_cons = consultation::join('patients', 'patients.id', '=', 'consultations.patient_id')
                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->join('societes', 'societes.id', '=', 'patients.societe_id')
                ->join('detailconsultations', 'detailconsultations.consultation_id', '=', 'consultations.id')
                ->where('patients.assurer', '=', 'oui')
                ->where('consultations.num_bon', '!=', null)
                ->where('consultations.assurance_utiliser', '=', 'oui')
                ->whereBetween(DB::raw('DATE(consultations.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $request->assurance_id)
                ->where('societes.id', '=', $societe->id)
                ->select(
                    'consultations.num_bon as num_bon',
                    'consultations.created_at as created_at',
                    'patients.np as patient',
                    'detailconsultations.part_assurance as part_assurance',
                    'detailconsultations.part_patient as part_patient',
                    'detailconsultations.remise as remise',
                    'detailconsultations.montant as montant',
                )
                ->get();

            foreach ($fac_cons as $value) {
                $patient = intval(str_replace('.', '', $value->part_patient));
                $remise = intval(str_replace('.', '', $value->remise));

                $total = $patient + $remise;
                $value->part_patient = $this->formatWithPeriods($total);
                // $value->acte = 'CONSULTATION';
            }

            $fac_exam = examen::join('patients', 'patients.id', '=', 'examens.patient_id')
                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->join('societes', 'societes.id', '=', 'patients.societe_id')
                ->where('patients.assurer', '=', 'oui')
                ->where('examens.num_bon', '!=', null)
                ->whereBetween(DB::raw('DATE(examens.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $request->assurance_id)
                ->where('societes.id', '=', $societe->id)
                ->select(
                    'examens.num_bon as num_bon',
                    'examens.created_at as created_at',
                    'patients.np as patient',
                    'examens.part_assurance as part_assurance',
                    'examens.part_patient as part_patient',
                    'examens.montant as montant',
                )
                ->get();

            $fac_soinsam = soinspatient::join('patients', 'patients.id', '=', 'soinspatients.patient_id')
                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->join('societes', 'societes.id', '=', 'patients.societe_id')
                ->where('patients.assurer', '=', 'oui')
                ->where('soinspatients.num_bon', '!=', null)
                ->where('soinspatients.assurance_utiliser', '=', 'oui')
                ->whereBetween(DB::raw('DATE(soinspatients.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $request->assurance_id)
                ->where('societes.id', '=', $societe->id)
                ->select(
                    'soinspatients.num_bon as num_bon',
                    'soinspatients.created_at as created_at',
                    'patients.np as patient',
                    'soinspatients.part_assurance as part_assurance',
                    'soinspatients.part_patient as part_patient',
                    'soinspatients.remise as remise',
                    'soinspatients.montant as montant',
                )
                ->get();

            foreach ($fac_soinsam as $value) {

                $patient = intval(str_replace('.', '', $value->part_patient));
                $remise = intval(str_replace('.', '', $value->remise));

                $total = $patient + $remise;

                $value->part_patient = $this->formatWithPeriods($total);
            }

            $fac_hopital = detailhopital::join('patients', 'patients.id', '=', 'detailhopitals.patient_id')
                ->leftjoin('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->leftjoin('societes', 'societes.id', '=', 'patients.societe_id')
                ->where('patients.assurer', '=', 'oui')
                ->where('detailhopitals.num_bon', '!=', null)
                ->whereBetween(DB::raw('DATE(detailhopitals.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $request->assurance_id)
                ->where('societes.id', '=', $societe->id)
                ->select(
                    'detailhopitals.num_bon as num_bon',
                    'detailhopitals.created_at as created_at',
                    'patients.np as patient',
                    'detailhopitals.part_assurance as part_assurance',
                    'detailhopitals.part_patient as part_patient',
                    'detailhopitals.remise as remise',
                    'detailhopitals.montant as montant',
                )
                ->get();

            foreach ($fac_hopital as $value) {

                $patient = intval(str_replace('.', '', $value->part_patient));
                $remise = intval(str_replace('.', '', $value->remise));

                $total = $patient + $remise;

                $value->part_patient = $this->formatWithPeriods($total);
            }

            if ($fac_cons->isNotEmpty() || $fac_exam->isNotEmpty() || $fac_soinsam->isNotEmpty() || $fac_hopital->isNotEmpty()) {
                $societe->fac_cons = $fac_cons;
                $societe->fac_exam = $fac_exam;
                $societe->fac_soinsam = $fac_soinsam;
                $societe->fac_hopital = $fac_hopital;
                $result[] = $societe;
            }
        }

        return response()->json([
            'societes' => $result,
            'assurance' => $assurance,
            'date1' => $date1,
            'date2' => $date2,
        ]);
    }

    public function imp_fac_assurance_bordo(Request $request)
    {
        $date1 = Carbon::createFromFormat('Y-m-d', $request->date1)->startOfDay();
        $date2 = Carbon::createFromFormat('Y-m-d', $request->date2)->endOfDay(); 

        $assurance = assurance::find($request->assurance_id);

        $societes = societe::all();
        $result = [];

        // Initialisation des totaux
        $total_patient = 0;
        $total_assurance = 0;
        $total_montant = 0;

        foreach ($societes as $key => $societe) {

            $fac_cons = consultation::join('patients', 'patients.id', '=', 'consultations.patient_id')
                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->join('societes', 'societes.id', '=', 'patients.societe_id')
                ->join('detailconsultations', 'detailconsultations.consultation_id', '=', 'consultations.id')
                ->where('patients.assurer', '=', 'oui')
                ->where('consultations.num_bon', '!=', null)
                ->where('consultations.assurance_utiliser', '=', 'oui')
                ->whereBetween(DB::raw('DATE(consultations.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $request->assurance_id)
                ->where('societes.id', '=', $societe->id)
                ->select(
                    'consultations.num_bon as num_bon',
                    'consultations.created_at as created_at',
                    'patients.np as patient',
                    'detailconsultations.part_assurance as part_assurance',
                    'detailconsultations.part_patient as part_patient',
                    'detailconsultations.remise as remise',
                    'detailconsultations.montant as montant',
                )
                ->get();

            foreach ($fac_cons as $value) {
                $patient = intval(str_replace('.', '', $value->part_patient));
                $remise = intval(str_replace('.', '', $value->remise));

                $total_patient += $patient + $remise;
                $total_assurance += intval(str_replace('.', '', $value->part_assurance));
                $total_montant += intval(str_replace('.', '', $value->montant));

                $value->part_patient = $this->formatWithPeriods($patient + $remise);
            }

            $fac_exam = examen::join('patients', 'patients.id', '=', 'examens.patient_id')
                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->join('societes', 'societes.id', '=', 'patients.societe_id')
                ->where('patients.assurer', '=', 'oui')
                ->where('examens.num_bon', '!=', null)
                ->whereBetween(DB::raw('DATE(examens.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $request->assurance_id)
                ->where('societes.id', '=', $societe->id)
                ->select(
                    'examens.num_bon as num_bon',
                    'examens.created_at as created_at',
                    'patients.np as patient',
                    'examens.part_assurance as part_assurance',
                    'examens.part_patient as part_patient',
                    'examens.montant as montant',
                )
                ->get();

            foreach ($fac_exam as $value) {
                $total_patient += intval(str_replace('.', '', $value->part_patient));
                $total_assurance += intval(str_replace('.', '', $value->part_assurance));
                $total_montant += intval(str_replace('.', '', $value->montant));
            }

            $fac_soinsam = soinspatient::join('patients', 'patients.id', '=', 'soinspatients.patient_id')
                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->join('societes', 'societes.id', '=', 'patients.societe_id')
                ->where('patients.assurer', '=', 'oui')
                ->where('soinspatients.num_bon', '!=', null)
                ->where('soinspatients.assurance_utiliser', '=', 'oui')
                ->whereBetween(DB::raw('DATE(soinspatients.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $request->assurance_id)
                ->where('societes.id', '=', $societe->id)
                ->select(
                    'soinspatients.num_bon as num_bon',
                    'soinspatients.created_at as created_at',
                    'patients.np as patient',
                    'soinspatients.part_assurance as part_assurance',
                    'soinspatients.part_patient as part_patient',
                    'soinspatients.remise as remise',
                    'soinspatients.montant as montant',
                )
                ->get();

            foreach ($fac_soinsam as $value) {
                $patient = intval(str_replace('.', '', $value->part_patient));
                $remise = intval(str_replace('.', '', $value->remise));

                $total_patient += $patient + $remise;
                $total_assurance += intval(str_replace('.', '', $value->part_assurance));
                $total_montant += intval(str_replace('.', '', $value->montant));
            }

            $fac_hopital = detailhopital::join('patients', 'patients.id', '=', 'detailhopitals.patient_id')
                ->leftjoin('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->leftjoin('societes', 'societes.id', '=', 'patients.societe_id')
                ->where('patients.assurer', '=', 'oui')
                ->where('detailhopitals.num_bon', '!=', null)
                ->whereBetween(DB::raw('DATE(detailhopitals.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $request->assurance_id)
                ->where('societes.id', '=', $societe->id)
                ->select(
                    'detailhopitals.num_bon as num_bon',
                    'detailhopitals.created_at as created_at',
                    'patients.np as patient',
                    'detailhopitals.part_assurance as part_assurance',
                    'detailhopitals.part_patient as part_patient',
                    'detailhopitals.remise as remise',
                    'detailhopitals.montant as montant',
                )
                ->get();

            foreach ($fac_hopital as $value) {
                $patient = intval(str_replace('.', '', $value->part_patient));
                $remise = intval(str_replace('.', '', $value->remise));

                $total_patient += $patient + $remise;
                $total_assurance += intval(str_replace('.', '', $value->part_assurance));
                $total_montant += intval(str_replace('.', '', $value->montant));
            }

            // Si la société a des données à afficher, on les ajoute dans le résultat
            if ($fac_cons->isNotEmpty() || $fac_exam->isNotEmpty() || $fac_soinsam->isNotEmpty() || $fac_hopital->isNotEmpty()) {
                
                $societe->fac_cons = $fac_cons;
                $societe->fac_exam = $fac_exam;
                $societe->fac_soinsam = $fac_soinsam;
                $societe->fac_hopital = $fac_hopital;

                $societe->total_patient = $this->formatWithPeriods($total_patient);
                $societe->total_assurance = $this->formatWithPeriods($total_assurance);
                $societe->total_montant = $this->formatWithPeriods($total_montant);

                $result[] = $societe;
            }
        }

        return response()->json([
            'societes' => $result,
            'assurance' => $assurance,
            'date1' => $date1,
            'date2' => $date2,
        ]);
    }

    public function imp_fac_depot($id)
    {
        $fac = depotfacture::find($id);

        $date1 = Carbon::createFromFormat('Y-m-d', $fac->date1)->startOfDay();
        $date2 = Carbon::createFromFormat('Y-m-d', $fac->date2)->endOfDay(); 

        $assurance = assurance::find($fac->assurance_id);

        $societes = societe::all();
        $result = [];

        foreach ($societes as $key => $societe) {

            $fac_cons = consultation::join('patients', 'patients.id', '=', 'consultations.patient_id')
                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->join('societes', 'societes.id', '=', 'patients.societe_id')
                ->join('detailconsultations', 'detailconsultations.consultation_id', '=', 'consultations.id')
                ->where('patients.assurer', '=', 'oui')
                ->where('consultations.num_bon', '!=', null)
                ->where('consultations.assurance_utiliser', '=', 'oui')
                ->whereBetween(DB::raw('DATE(consultations.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $assurance->id)
                ->where('societes.id', '=', $societe->id)
                ->select(
                    'consultations.num_bon as num_bon',
                    'consultations.created_at as created_at',
                    'patients.np as patient',
                    'detailconsultations.part_assurance as part_assurance',
                    'detailconsultations.part_patient as part_patient',
                    'detailconsultations.remise as remise',
                    'detailconsultations.montant as montant',
                )
                ->get();

            foreach ($fac_cons as $value) {
                $patient = intval(str_replace('.', '', $value->part_patient));
                $remise = intval(str_replace('.', '', $value->remise));

                $total = $patient + $remise;
                $value->part_patient = $this->formatWithPeriods($total);
            }

            $fac_exam = examen::join('patients', 'patients.id', '=', 'examens.patient_id')
                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->join('societes', 'societes.id', '=', 'patients.societe_id')
                ->where('patients.assurer', '=', 'oui')
                ->where('examens.num_bon', '!=', null)
                ->whereBetween(DB::raw('DATE(examens.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $assurance->id)
                ->where('societes.id', '=', $societe->id)
                ->select(
                    'examens.num_bon as num_bon',
                    'examens.created_at as created_at',
                    'patients.np as patient',
                    'examens.part_assurance as part_assurance',
                    'examens.part_patient as part_patient',
                    'examens.montant as montant',
                )
                ->get();

            $fac_soinsam = soinspatient::join('patients', 'patients.id', '=', 'soinspatients.patient_id')
                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->join('societes', 'societes.id', '=', 'patients.societe_id')
                ->where('patients.assurer', '=', 'oui')
                ->where('soinspatients.num_bon', '!=', null)
                ->where('soinspatients.assurance_utiliser', '=', 'oui')
                ->whereBetween(DB::raw('DATE(soinspatients.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $assurance->id)
                ->where('societes.id', '=', $societe->id)
                ->select(
                    'soinspatients.num_bon as num_bon',
                    'soinspatients.created_at as created_at',
                    'patients.np as patient',
                    'soinspatients.part_assurance as part_assurance',
                    'soinspatients.part_patient as part_patient',
                    'soinspatients.remise as remise',
                    'soinspatients.montant as montant',
                )
                ->get();

            foreach ($fac_soinsam as $value) {

                $patient = intval(str_replace('.', '', $value->part_patient));
                $remise = intval(str_replace('.', '', $value->remise));

                $total = $patient + $remise;

                $value->part_patient = $this->formatWithPeriods($total);
            }

            $fac_hopital = detailhopital::join('patients', 'patients.id', '=', 'detailhopitals.patient_id')
                ->leftjoin('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->leftjoin('societes', 'societes.id', '=', 'patients.societe_id')
                ->where('patients.assurer', '=', 'oui')
                ->where('detailhopitals.num_bon', '!=', null)
                ->whereBetween(DB::raw('DATE(detailhopitals.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $assurance->id)
                ->where('societes.id', '=', $societe->id)
                ->select(
                    'detailhopitals.num_bon as num_bon',
                    'detailhopitals.created_at as created_at',
                    'patients.np as patient',
                    'detailhopitals.part_assurance as part_assurance',
                    'detailhopitals.part_patient as part_patient',
                    'detailhopitals.remise as remise',
                    'detailhopitals.montant as montant',
                )
                ->get();

            foreach ($fac_hopital as $value) {

                $patient = intval(str_replace('.', '', $value->part_patient));
                $remise = intval(str_replace('.', '', $value->remise));

                $total = $patient + $remise;

                $value->part_patient = $this->formatWithPeriods($total);
            }

            if ($fac_cons->isNotEmpty() || $fac_exam->isNotEmpty() || $fac_soinsam->isNotEmpty() || $fac_hopital->isNotEmpty()) {
                $societe->fac_cons = $fac_cons;
                $societe->fac_exam = $fac_exam;
                $societe->fac_soinsam = $fac_soinsam;
                $societe->fac_hopital = $fac_hopital;
                $result[] = $societe;
            }
        }

        return response()->json([
            'societes' => $result,
            'assurance' => $assurance,
            'date1' => $date1,
            'date2' => $date2,
        ]);
    }

    public function imp_fac_depot_bordo($id)
    {
        $fac = depotfacture::find($id);

        $date1 = Carbon::createFromFormat('Y-m-d', $fac->date1)->startOfDay();
        $date2 = Carbon::createFromFormat('Y-m-d', $fac->date2)->endOfDay(); 

        $assurance = assurance::find($fac->assurance_id);

        $societes = societe::all();
        $result = [];

        foreach ($societes as $key => $societe) {

            $total_patient = 0;
            $total_assurance = 0;
            $total_montant = 0;

            $fac_cons = consultation::join('patients', 'patients.id', '=', 'consultations.patient_id')
                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->join('societes', 'societes.id', '=', 'patients.societe_id')
                ->join('detailconsultations', 'detailconsultations.consultation_id', '=', 'consultations.id')
                ->where('patients.assurer', '=', 'oui')
                ->where('consultations.num_bon', '!=', null)
                ->where('consultations.assurance_utiliser', '=', 'oui')
                ->whereBetween(DB::raw('DATE(consultations.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $assurance->id)
                ->where('societes.id', '=', $societe->id)
                ->select(
                    'consultations.num_bon as num_bon',
                    'consultations.created_at as created_at',
                    'patients.np as patient',
                    'detailconsultations.part_assurance as part_assurance',
                    'detailconsultations.part_patient as part_patient',
                    'detailconsultations.remise as remise',
                    'detailconsultations.montant as montant',
                )
                ->get();

            foreach ($fac_cons as $value) {
                $patient = intval(str_replace('.', '', $value->part_patient));
                $remise = intval(str_replace('.', '', $value->remise));

                $total_patient += $patient + $remise;
                $total_assurance += intval(str_replace('.', '', $value->part_assurance));
                $total_montant += intval(str_replace('.', '', $value->montant));

                $value->part_patient = $this->formatWithPeriods($patient + $remise);
            }

            $fac_exam = examen::join('patients', 'patients.id', '=', 'examens.patient_id')
                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->join('societes', 'societes.id', '=', 'patients.societe_id')
                ->where('patients.assurer', '=', 'oui')
                ->where('examens.num_bon', '!=', null)
                ->whereBetween(DB::raw('DATE(examens.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $assurance->id)
                ->where('societes.id', '=', $societe->id)
                ->select(
                    'examens.num_bon as num_bon',
                    'examens.created_at as created_at',
                    'patients.np as patient',
                    'examens.part_assurance as part_assurance',
                    'examens.part_patient as part_patient',
                    'examens.montant as montant',
                )
                ->get();

            foreach ($fac_exam as $value) {
                $total_patient += intval(str_replace('.', '', $value->part_patient));
                $total_assurance += intval(str_replace('.', '', $value->part_assurance));
                $total_montant += intval(str_replace('.', '', $value->montant));
            }

            $fac_soinsam = soinspatient::join('patients', 'patients.id', '=', 'soinspatients.patient_id')
                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->join('societes', 'societes.id', '=', 'patients.societe_id')
                ->where('patients.assurer', '=', 'oui')
                ->where('soinspatients.num_bon', '!=', null)
                ->where('soinspatients.assurance_utiliser', '=', 'oui')
                ->whereBetween(DB::raw('DATE(soinspatients.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $assurance->id)
                ->where('societes.id', '=', $societe->id)
                ->select(
                    'soinspatients.num_bon as num_bon',
                    'soinspatients.created_at as created_at',
                    'patients.np as patient',
                    'soinspatients.part_assurance as part_assurance',
                    'soinspatients.part_patient as part_patient',
                    'soinspatients.remise as remise',
                    'soinspatients.montant as montant',
                )
                ->get();

            foreach ($fac_soinsam as $value) {
                $patient = intval(str_replace('.', '', $value->part_patient));
                $remise = intval(str_replace('.', '', $value->remise));

                $total_patient += $patient + $remise;
                $total_assurance += intval(str_replace('.', '', $value->part_assurance));
                $total_montant += intval(str_replace('.', '', $value->montant));
            }

            $fac_hopital = detailhopital::join('patients', 'patients.id', '=', 'detailhopitals.patient_id')
                ->leftjoin('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->leftjoin('societes', 'societes.id', '=', 'patients.societe_id')
                ->where('patients.assurer', '=', 'oui')
                ->where('detailhopitals.num_bon', '!=', null)
                ->whereBetween(DB::raw('DATE(detailhopitals.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $assurance->id)
                ->where('societes.id', '=', $societe->id)
                ->select(
                    'detailhopitals.num_bon as num_bon',
                    'detailhopitals.created_at as created_at',
                    'patients.np as patient',
                    'detailhopitals.part_assurance as part_assurance',
                    'detailhopitals.part_patient as part_patient',
                    'detailhopitals.remise as remise',
                    'detailhopitals.montant as montant',
                )
                ->get();

            foreach ($fac_hopital as $value) {
                $patient = intval(str_replace('.', '', $value->part_patient));
                $remise = intval(str_replace('.', '', $value->remise));

                $total_patient += $patient + $remise;
                $total_assurance += intval(str_replace('.', '', $value->part_assurance));
                $total_montant += intval(str_replace('.', '', $value->montant));
            }

            // Si la société a des données à afficher, on les ajoute dans le résultat
            if ($fac_cons->isNotEmpty() || $fac_exam->isNotEmpty() || $fac_soinsam->isNotEmpty() || $fac_hopital->isNotEmpty()) {
                
                $societe->fac_cons = $fac_cons;
                $societe->fac_exam = $fac_exam;
                $societe->fac_soinsam = $fac_soinsam;
                $societe->fac_hopital = $fac_hopital;

                // Ajout des totaux dans l'objet société
                $societe->total_patient = $this->formatWithPeriods($total_patient);
                $societe->total_assurance = $this->formatWithPeriods($total_assurance);
                $societe->total_montant = $this->formatWithPeriods($total_montant);

                $result[] = $societe;
            }
        }

        return response()->json([
            'societes' => $result,
            'assurance' => $assurance,
            'date1' => $date1,
            'date2' => $date2,
            'date_payer' => $fac->date_payer,
            'statut' => $fac->statut,
            'type_paiement' => $fac->type_paiement,
            'num_cheque' => $fac->num_cheque,
        ]);
    }

    // -------------------------------------------------------------------------

    public function etat_fac_assurance(Request $request)
    {

        $date1 = Carbon::createFromFormat('Y-m-d', $request->date1)->startOfDay();
        $date2 = Carbon::createFromFormat('Y-m-d', $request->date2)->endOfDay();

        // Fonction pour récupérer les dépôts
        function getDepotResults($assurance_id, $type) {

            $query = depotfacture::selectRaw('MIN(DATE(depotfactures.date1)) as min_date, MAX(DATE(depotfactures.date2)) as max_date');

            if ($assurance_id != 'tous') {
                $query->where('depotfactures.assurance_id', '=', $assurance_id);
            }

            if ($type === 'fac_deposer') {
                $query->where(function($query) {
                    $query->where('statut', '=', 'non')->orWhere('statut', '=', 'oui');
                });
            } else if ($type === 'fac_deposer_regler') {
                $query->where('statut', '=', 'oui'); 
            } else if ($type === 'fac_deposer_non_regler') {
                $query->where('statut', '=', 'non');
            }

            return $query->first();
        }

        $depotResult = getDepotResults($request->assurance_id, $request->type);

        if ($depotResult) {

            if ($depotResult->min_date === null && $depotResult->max_date === null) {
                return response()->json([
                    'facture_non_trouve' => true,
                ]);
            }

            $minDate = Carbon::createFromFormat('Y-m-d', $depotResult->min_date)->startOfDay();
            $maxDate = Carbon::createFromFormat('Y-m-d', $depotResult->max_date)->endOfDay();

            $condition1 = ($date1 >= $minDate && $date1 <= $maxDate) && ($date2 >= $maxDate);
            $condition2 = ($date2 <= $maxDate && $date2 >= $minDate) && ($date1 <= $minDate);
            $condition3 = ($date1 >= $minDate && $date2 <= $maxDate);
            $condition4 = ($date1 <= $minDate && $date2 >= $maxDate);
            $condition5 = ($date1 < $minDate && $date2 > $minDate && $date2 < $maxDate);
            $condition6 = ($date1 > $minDate && $date1 < $maxDate && $date2 > $maxDate);

            if ($condition1) {
                $date1 = $date1;
                $date2 = $maxDate;
            } else if ($condition2) {
                $date1 = $date2;
                $date2 = $minDate;
            } else if ($condition3) {
                $date1 = $date1;
                $date2 = $date2;
            } else if ($condition4) {
                $date1 = $minDate;
                $date2 = $maxDate;
            } else if ($condition5) {
                $date1 = $date1;
                $date2 = $minDate;
            } else if ($condition6) {
                $date1 = $minDate;
                $date2 = $date2;
            } else {
                return response()->json([
                    'facture_non_trouve' => true,
                ]);
            }

        }else{
           return response()->json([
                'facture_non_trouve' => true,
            ]); 
        }

        if ($request->assurance_id === 'tous') {
            $assurance = null;
        }else{
            $assurance = assurance::find($request->assurance_id);
        }

        $societes = societe::all();
        $result = [];

        foreach ($societes as $key => $societe) {

            $fac_cons = consultation::join('patients', 'patients.id', '=', 'consultations.patient_id')
                ->join('factures', 'factures.id', '=', 'consultations.facture_id')
                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->join('societes', 'societes.id', '=', 'patients.societe_id')
                ->join('detailconsultations', 'detailconsultations.consultation_id', '=', 'consultations.id')
                ->where('patients.assurer', '=', 'oui')
                ->where('consultations.num_bon', '!=', null)
                ->where('consultations.assurance_utiliser', '=', 'oui')
                ->whereBetween(DB::raw('DATE(consultations.created_at)'), [$date1, $date2])
                ->where('societes.id', '=', $societe->id);

                if ($request->assurance_id != 'tous') {
                    $fac_cons->where('assurances.id', '=', $request->assurance_id);
                }

                $fac_cons = $fac_cons->select(
                    'consultations.num_bon as num_bon',
                    'consultations.created_at as created_at',
                    'patients.np as patient',
                    'detailconsultations.part_assurance as part_assurance',
                    'detailconsultations.part_patient as part_patient',
                    'detailconsultations.remise as remise',
                    'detailconsultations.montant as montant',
                    'assurances.nom as assurance',
                )
                ->get();

            foreach ($fac_cons as $value) {

                $patient = intval(str_replace('.', '', $value->part_patient));
                $remise = intval(str_replace('.', '', $value->remise));

                $total = $patient + $remise;
                $value->part_patient = $this->formatWithPeriods($total);
                // $value->acte = 'CONSULTATION';
            }

            $fac_exam = examen::join('patients', 'patients.id', '=', 'examens.patient_id')
                ->join('factures', 'factures.id', '=', 'examens.facture_id')
                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->join('societes', 'societes.id', '=', 'patients.societe_id')
                ->where('patients.assurer', '=', 'oui')
                ->where('examens.num_bon', '!=', null)
                ->whereBetween(DB::raw('DATE(examens.created_at)'), [$date1, $date2])
                ->where('societes.id', '=', $societe->id);

                if ($request->assurance_id != 'tous') {
                    $fac_exam->where('assurances.id', '=', $request->assurance_id);
                }

                $fac_exam = $fac_exam->select(
                    'examens.num_bon as num_bon',
                    'examens.created_at as created_at',
                    'patients.np as patient',
                    'examens.part_assurance as part_assurance',
                    'examens.part_patient as part_patient',
                    'examens.montant as montant',
                    'assurances.nom as assurance',
                )
                ->get();

            $fac_soinsam = soinspatient::join('patients', 'patients.id', '=', 'soinspatients.patient_id')
                ->join('factures', 'factures.id', '=', 'soinspatients.facture_id')
                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->join('societes', 'societes.id', '=', 'patients.societe_id')
                ->where('patients.assurer', '=', 'oui')
                ->where('soinspatients.num_bon', '!=', null)
                ->where('soinspatients.assurance_utiliser', '=', 'oui')
                ->whereBetween(DB::raw('DATE(soinspatients.created_at)'), [$date1, $date2])
                ->where('societes.id', '=', $societe->id);

                if ($request->assurance_id != 'tous') {
                    $fac_soinsam->where('assurances.id', '=', $request->assurance_id);
                }

                $fac_soinsam = $fac_soinsam->select(
                    'soinspatients.num_bon as num_bon',
                    'soinspatients.created_at as created_at',
                    'patients.np as patient',
                    'soinspatients.part_assurance as part_assurance',
                    'soinspatients.part_patient as part_patient',
                    'soinspatients.remise as remise',
                    'soinspatients.montant as montant',
                    'assurances.nom as assurance',
                )
                ->get();

            foreach ($fac_soinsam as $value) {

                $patient = intval(str_replace('.', '', $value->part_patient));
                $remise = intval(str_replace('.', '', $value->remise));

                $total = $patient + $remise;

                $value->part_patient = $this->formatWithPeriods($total);
            }

            $fac_hopital = detailhopital::join('patients', 'patients.id', '=', 'detailhopitals.patient_id')
                ->join('factures', 'factures.id', '=', 'detailhopitals.facture_id')
                ->leftjoin('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->leftjoin('societes', 'societes.id', '=', 'patients.societe_id')
                ->where('patients.assurer', '=', 'oui')
                ->where('detailhopitals.num_bon', '!=', null)
                ->whereBetween(DB::raw('DATE(detailhopitals.created_at)'), [$date1, $date2])
                ->where('societes.id', '=', $societe->id);

                if ($request->assurance_id != 'tous') {
                    $fac_hopital->where('assurances.id', '=', $request->assurance_id);
                }

                $fac_hopital = $fac_hopital->select(
                    'detailhopitals.num_bon as num_bon',
                    'detailhopitals.created_at as created_at',
                    'patients.np as patient',
                    'detailhopitals.part_assurance as part_assurance',
                    'detailhopitals.part_patient as part_patient',
                    'detailhopitals.remise as remise',
                    'detailhopitals.montant as montant',
                    'assurances.nom as assurance',
                )
                ->get();

            foreach ($fac_hopital as $value) {

                $patient = intval(str_replace('.', '', $value->part_patient));
                $remise = intval(str_replace('.', '', $value->remise));

                $total = $patient + $remise;

                $value->part_patient = $this->formatWithPeriods($total);
            }

            if ($fac_cons->isNotEmpty() || $fac_exam->isNotEmpty() || $fac_soinsam->isNotEmpty() || $fac_hopital->isNotEmpty()) {
                $societe->fac_cons = $fac_cons;
                $societe->fac_exam = $fac_exam;
                $societe->fac_soinsam = $fac_soinsam;
                $societe->fac_hopital = $fac_hopital;
                $result[] = $societe;
            }
        }

        return response()->json([
            'societes' => $result,
            'assurance' => $assurance ?? null,
            'date1' => $date1,
            'date2' => $date2,
            'type' => $request->type,
        ]);
    }

    public function etat_fac_encaissement(Request $request)
    {

        $date1 = Carbon::createFromFormat('Y-m-d', $request->date1)->startOfDay();
        $date2 = Carbon::createFromFormat('Y-m-d', $request->date2)->endOfDay(); 

            $fac_cons = consultation::join('patients', 'patients.id', '=', 'consultations.patient_id')
                ->leftjoin('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->leftjoin('societes', 'societes.id', '=', 'patients.societe_id')
                ->join('detailconsultations', 'detailconsultations.consultation_id', '=', 'consultations.id')
                ->join('factures', 'factures.id', '=', 'consultations.facture_id')
                ->join('users', 'users.id', '=', 'factures.encaisser_id')
                ->whereBetween(DB::raw('DATE(consultations.created_at)'), [$date1, $date2]);

                if ($request->assurance_id != 'tous') {
                    $fac_cons->where('patients.assurer', '=', 'oui')
                             ->where('assurances.id', '=', $request->assurance_id);
                }

                if ($request->caissier_id != 'tous') {
                    $fac_cons->where('users.id', '=', $request->caissier_id);
                }

                $fac_cons = $fac_cons->select(
                    'consultations.created_at as created_at',
                    'patients.np as patient',
                    'patients.matricule as code_patient',
                    'detailconsultations.part_assurance as part_assurance',
                    'detailconsultations.part_patient as part_patient',
                    'detailconsultations.remise as remise',
                    'detailconsultations.montant as montant',
                    'assurances.nom as assurance',
                    'factures.code as code_fac',
                    'factures.statut as statut_fac',
                    'factures.date_payer as date_payer',
                    'users.name as user',
                    'users.sexe as user_sexe',
                )
                ->get();

            foreach ($fac_cons as $value) {
                $patient = intval(str_replace('.', '', $value->part_patient));
                $remise = intval(str_replace('.', '', $value->remise));

                $total = $patient + $remise;
                $value->part_patient = $this->formatWithPeriods($total);
            }

            $fac_exam = examen::join('patients', 'patients.id', '=', 'examens.patient_id')
                ->leftjoin('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->leftjoin('societes', 'societes.id', '=', 'patients.societe_id')
                ->join('factures', 'factures.id', '=', 'examens.facture_id')
                ->join('users', 'users.id', '=', 'factures.encaisser_id')
                ->whereBetween(DB::raw('DATE(examens.created_at)'), [$date1, $date2]);

                if ($request->assurance_id != 'tous') {
                    $fac_exam->where('patients.assurer', '=', 'oui')
                             ->where('assurances.id', '=', $request->assurance_id);
                }

                if ($request->caissier_id != 'tous') {
                    $fac_exam->where('users.id', '=', $request->caissier_id);
                }

                $fac_exam = $fac_exam->select(
                    'examens.num_bon as num_bon',
                    'examens.created_at as created_at',
                    'patients.np as patient',
                    'examens.part_assurance as part_assurance',
                    'examens.part_patient as part_patient',
                    'examens.montant as montant',
                    'assurances.nom as assurance',
                    'factures.code as code_fac',
                    'factures.statut as statut_fac',
                    'factures.date_payer as date_payer',
                    'patients.matricule as code_patient',
                    'users.name as user',
                    'users.sexe as user_sexe',
                )
                ->get();

            $fac_soinsam = soinspatient::join('patients', 'patients.id', '=', 'soinspatients.patient_id')
                ->leftjoin('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->leftjoin('societes', 'societes.id', '=', 'patients.societe_id')
                ->join('factures', 'factures.id', '=', 'soinspatients.facture_id')
                ->join('users', 'users.id', '=', 'factures.encaisser_id')
                ->whereBetween(DB::raw('DATE(soinspatients.created_at)'), [$date1, $date2]);

                if ($request->assurance_id != 'tous') {
                    $fac_soinsam->where('patients.assurer', '=', 'oui')
                             ->where('assurances.id', '=', $request->assurance_id);
                }

                if ($request->caissier_id != 'tous') {
                    $fac_soinsam->where('users.id', '=', $request->caissier_id);
                }

                $fac_soinsam = $fac_soinsam->select(
                    'soinspatients.num_bon as num_bon',
                    'soinspatients.created_at as created_at',
                    'patients.np as patient',
                    'soinspatients.part_assurance as part_assurance',
                    'soinspatients.part_patient as part_patient',
                    'soinspatients.remise as remise',
                    'soinspatients.montant as montant',
                    'assurances.nom as assurance',
                    'factures.code as code_fac',
                    'factures.statut as statut_fac',
                    'factures.date_payer as date_payer',
                    'patients.matricule as code_patient',
                    'users.name as user',
                    'users.sexe as user_sexe',
                )
                ->get();

            foreach ($fac_soinsam as $value) {

                $patient = intval(str_replace('.', '', $value->part_patient));
                $remise = intval(str_replace('.', '', $value->remise));

                $total = $patient + $remise;

                $value->part_patient = $this->formatWithPeriods($total);
            }

            $fac_hopital = detailhopital::join('patients', 'patients.id', '=', 'detailhopitals.patient_id')
                ->leftjoin('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->leftjoin('societes', 'societes.id', '=', 'patients.societe_id')
                ->join('factures', 'factures.id', '=', 'detailhopitals.facture_id')
                ->join('users', 'users.id', '=', 'factures.encaisser_id')
                ->whereBetween(DB::raw('DATE(detailhopitals.created_at)'), [$date1, $date2]);

                if ($request->assurance_id != 'tous') {
                    $fac_hopital->where('patients.assurer', '=', 'oui')
                             ->where('assurances.id', '=', $request->assurance_id);
                }

                if ($request->caissier_id != 'tous') {
                    $fac_hopital->where('users.id', '=', $request->caissier_id);
                }

                $fac_hopital = $fac_hopital->select(
                    'detailhopitals.num_bon as num_bon',
                    'detailhopitals.created_at as created_at',
                    'patients.np as patient',
                    'detailhopitals.part_assurance as part_assurance',
                    'detailhopitals.part_patient as part_patient',
                    'detailhopitals.remise as remise',
                    'detailhopitals.montant as montant',
                    'assurances.nom as assurance',
                    'factures.code as code_fac',
                    'factures.statut as statut_fac',
                    'factures.date_payer as date_payer',
                    'patients.matricule as code_patient',
                    'users.name as user',
                    'users.sexe as user_sexe',
                )
                ->get();

            foreach ($fac_hopital as $value) {

                $patient = intval(str_replace('.', '', $value->part_patient));
                $remise = intval(str_replace('.', '', $value->remise));

                $total = $patient + $remise;

                $value->part_patient = $this->formatWithPeriods($total);
            }

            if (!$fac_cons->isNotEmpty() || !$fac_exam->isNotEmpty() || !$fac_soinsam->isNotEmpty() || !$fac_hopital->isNotEmpty()) {
                
                return response()->json(['donnee_0' => true]);
            }

        return response()->json([
            'success' => true,
            'fac_cons' => $fac_cons,
            'fac_exam' => $fac_exam,
            'fac_soinsam' => $fac_soinsam,
            'fac_hopital' => $fac_hopital,
            'date1' => $date1,
            'date2' => $date2,
        ]);
    }

    public function etat_fac_acte(Request $request)
    {
        $date1 = Carbon::createFromFormat('Y-m-d', $request->date1)->startOfDay();
        $date2 = Carbon::createFromFormat('Y-m-d', $request->date2)->endOfDay();

        $acte_cons = consultation::join('patients', 'patients.id', '=', 'consultations.patient_id')
            ->leftjoin('assurances', 'assurances.id', '=', 'patients.assurance_id')
            ->leftjoin('societes', 'societes.id', '=', 'patients.societe_id')
            ->join('detailconsultations', 'detailconsultations.consultation_id', '=', 'consultations.id')
            ->join('users', 'users.id', '=', 'consultations.user_id')
            ->join('typemedecins', 'typemedecins.user_id', '=', 'users.id')
            ->join('typeactes', 'typemedecins.typeacte_id', '=', 'typeactes.id')
            ->whereBetween(DB::raw('DATE(consultations.created_at)'), [$date1, $date2]);

            if ($request->assurance_id != 'tous') {
                $acte_cons->where('patients.assurer', '=', 'oui')
                        ->where('assurances.id', '=', $request->assurance_id);
            }

            if ($request->pres == 'medecin' && $request->medecin_id != 'tous') {
                $acte_cons->where('users.id', '=', $request->medecin_id);
            }

            if ($request->pres == 'specialite' && $request->specialite_id != 'tous') {
                $acte_cons->where('typeactes.id', '=', $request->specialite_id);
            }

            $acte_cons = $acte_cons->select(
                'consultations.*',
                'patients.np as patient',
                'patients.matricule as matricule_patient',
                'patients.datenais as datenais_patient',
                'detailconsultations.part_assurance as part_assurance',
                'detailconsultations.part_patient as part_patient',
                'detailconsultations.remise as remise',
                'detailconsultations.montant as montant',
                'assurances.nom as assurance',
                'typeactes.nom as specialite',
                'users.name as medecin',
            )
            ->get();

            foreach ($acte_cons as $value) {

                $value->age = $value->datenais_patient ? Carbon::parse($value->datenais_patient)->age : 0;
            }

        $acte_hop = detailhopital::join('patients', 'patients.id', '=', 'detailhopitals.patient_id')
            ->leftjoin('assurances', 'assurances.id', '=', 'patients.assurance_id')
            ->leftjoin('societes', 'societes.id', '=', 'patients.societe_id')
            ->whereBetween(DB::raw('DATE(detailhopitals.created_at)'), [$date1, $date2]);

            if ($request->assurance_id != 'tous') {
                $acte_hop->where('patients.assurer', '=', 'oui')
                        ->where('assurances.id', '=', $request->assurance_id);
            }

            $acte_hop = $acte_hop->select(
                'detailhopitals.*',
                'patients.np as patient',
                'patients.matricule as matricule_patient',
                'assurances.nom as assurance',
            )
            ->get();

        $acte_exam = examen::join('patients', 'patients.id', '=', 'examens.patient_id')
            ->leftjoin('assurances', 'assurances.id', '=', 'patients.assurance_id')
            ->leftjoin('societes', 'societes.id', '=', 'patients.societe_id')
            ->whereBetween(DB::raw('DATE(examens.created_at)'), [$date1, $date2]);

            if ($request->assurance_id != 'tous') {
                $acte_exam->where('patients.assurer', '=', 'oui')
                        ->where('assurances.id', '=', $request->assurance_id);
            }

            $acte_exam = $acte_exam->select(
                'examens.*',
                'patients.np as patient',
                'patients.matricule as matricule_patient',
                'assurances.nom as assurance',
            )
            ->get();

        $acte_soinsam = soinspatient::join('patients', 'patients.id', '=', 'soinspatients.patient_id')
                ->leftjoin('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->leftjoin('societes', 'societes.id', '=', 'patients.societe_id')
                ->whereBetween(DB::raw('DATE(soinspatients.created_at)'), [$date1, $date2]);

                if ($request->assurance_id != 'tous') {
                    $acte_soinsam->where('patients.assurer', '=', 'oui')
                            ->where('assurances.id', '=', $request->assurance_id);
                }

                $acte_soinsam = $acte_soinsam->select(
                    'soinspatients.*',
                    'patients.np as patient',
                    'patients.matricule as matricule_patient',
                    'assurances.nom as assurance',
                )
                ->get();

        if (!$acte_cons->count() && !$acte_hop->count() && !$acte_exam->count() && !$acte_soinsam->count() ) {
            return response()->json(['donnee_0' => true]);
        }

        if ($request->acte == 'cons') {

            if ($acte_cons->count() == 0 ) {
                return response()->json(['donnee_0' => true]);
            }

            $acte_exam = "";
            $acte_hop = "";
            $acte_soinsam = "";

        } else if ($request->acte == 'hos') {

            if ($acte_hop->count() == 0 ) {
                return response()->json(['donnee_0' => true]);
            }

            $acte_exam = "";
            $acte_cons = "";
            $acte_soinsam = "";

        } else if ($request->acte == 'exam') {

            if ($acte_exam->count() == 0 ) {
                return response()->json(['donnee_0' => true]);
            }

            $acte_hop = "";
            $acte_cons = "";
            $acte_soinsam = "";

        } else if ($request->acte == 'soinsam') {

            if ($acte_soinsam->count() == 0 ) {
                return response()->json(['donnee_0' => true]);
            }

            $acte_hop = "";
            $acte_cons = "";
            $acte_exam = "";

        }

        return response()->json([
            'success' => true,
            'acte_exam' => $acte_exam ?? 0,
            'acte_cons' => $acte_cons ?? 0,
            'acte_hop' => $acte_hop ?? 0,
            'acte_soinsam' => $acte_soinsam ?? 0,
            'date1' => $request->date1,
            'date2' => $request->date2,
        ]);

    }

    public function etat_fac_ope_caisse(Request $request)
    {
        
        $date1 = Carbon::createFromFormat('Y-m-d', $request->date1)->startOfDay();
        $date2 = Carbon::createFromFormat('Y-m-d', $request->date2)->endOfDay();

        $trace = historiquecaisse::join('users', 'users.id', '=', 'historiquecaisses.creer_id')
                                ->whereBetween('historiquecaisses.created_at', [$date1, $date2])
                                ->orderBy('historiquecaisses.created_at', 'desc');

        if ($request->typemvt !== 'tous') {
            $trace->where('historiquecaisses.typemvt', '=', $request->typemvt);
        }

        if ($request->user_id !== 'tous') {
            $trace->where('historiquecaisses.creer_id', '=', $request->user_id);
        }

        $trace = $trace->select(
            'historiquecaisses.*',
            'users.name as user',
            'users.sexe as user_sexe',
        )
        ->get();

        $total = 0;

        foreach ($trace as $value) {
            if ($value->typemvt === 'Entrer de Caisse') {
                $total += str_replace('.', '', $value->montant);
            }else{
                $total -= str_replace('.', '', $value->montant);
            }
        }

        if (!$trace->isNotEmpty()) {
            return response()->json(['donnee_0' => true]);
        }

        return response()->json([
            'success' => true,
            'trace' => $trace,
            'total' => $total,
            'date1' => $date1,
            'date2' => $date2,
        ]);

    }

}
