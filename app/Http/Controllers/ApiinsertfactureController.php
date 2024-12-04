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
use App\Models\caisse;
use App\Models\historiquecaisse;

class ApiinsertfactureController extends Controller
{
    public function facture_payer(Request $request,$code_fac)
    {

        $caisse_verf = caisse::find(1);

        if ($caisse_verf->statut === 'fermer') {
            return response()->json(['caisse_fermer' => true]);
        }

        DB::beginTransaction();

        $fac = facture::where('code', '=', $code_fac)->first();

        try {

            if ($fac) {

                $fac->montant_verser = $request->montant_verser;
                $fac->montant_remis = $request->montant_remis;
                $fac->statut = 'payer';
                $fac->date_payer = Carbon::now();
                $fac->encaisser_id = $request->auth_id;

                if (!$fac->save()) {
                    throw new \Exception('Erreur');
                }

                $consultation = consultation::join('detailconsultations', 'detailconsultations.consultation_id', '=', 'consultations.id')
                ->join('factures', 'factures.id', '=', 'consultations.facture_id')
                ->where('factures.code', '=', $code_fac)
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

                $patient = patient::leftjoin('assurances', 'assurances.id', '=', 'patients.assurance_id')->leftjoin('tauxes', 'tauxes.id', '=', 'patients.taux_id')
                ->where('patients.id', '=', $consultation->patient_id)
                ->select('patients.*', 'assurances.nom as assurance', 'tauxes.taux as taux')
                ->first();

                if ($patient && $patient->datenais) {
                    $patient->age = Carbon::parse($patient->datenais)->age;
                }

                $user = user::find($consultation->user_id);

                $typeacte = typeacte::find($consultation->typeacte_id);

                //-----------------------------------------------

                $solde_caisse = caisse::find('1');

                $solde_caisse_sans_point = str_replace('.', '', $solde_caisse->solde);
                $part_patient_sans_point = str_replace('.', '', $consultation->part_patient);
                $solde_apres = (int)$solde_caisse_sans_point + (int)$part_patient_sans_point;

                $add_caisse = new historiquecaisse();
                $add_caisse->motif = 'ENCAISSEMENT CONSULTATION Facture N°'.$fac->code;
                $add_caisse->montant = $consultation->part_patient;
                $add_caisse->libelle = 'Encaissment CONSULTATION Facture N°'.$fac->code;
                $add_caisse->solde_avant = $solde_caisse->solde;
                $add_caisse->solde_apres = number_format($solde_apres, 0, '', '.');
                $add_caisse->typemvt = 'Entrer de Caisse';
                $add_caisse->creer_id = $request->auth_id;
                $today = Carbon::today();
                $add_caisse->date_ope = $today;
                
                if (!$add_caisse->save()) {
                    throw new \Exception('Erreur');
                }

                $solde_caisse->solde = number_format($solde_apres, 0, '', '.');

                if (!$solde_caisse->save()) {
                    throw new \Exception('Erreur');
                }

                //-----------------------------------------------

                DB::commit();
                return response()->json(['success' => true, 'patient' => $patient, 'typeacte' => $typeacte, 'user' => $user, 'consultation' => $consultation]);

            }else{
                throw new \Exception('Erreur');
            }
            
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true]);
        }
    }

    public function facture_payer_hos(Request $request,$code_fac)
    {

        $caisse_verf = caisse::find(1);

        if ($caisse_verf->statut === 'fermer') {
            return response()->json(['caisse_fermer' => true]);
        }

        DB::beginTransaction();

        $fac = facture::where('code', '=', $code_fac)->first();

        try {

            if ($fac) {

               $fac->montant_verser = $request->montant_verser;
               $fac->montant_remis = $request->montant_remis;
               $fac->statut = 'payer';
               $fac->date_payer = Carbon::now();
               $fac->encaisser_id = $request->auth_id;

                if (!$fac->save()) {
                    throw new \Exception('Erreur');
                }

                $hos = detailhopital::join('factures', 'factures.id', '=', 'detailhopitals.facture_id')
                ->where('factures.code', '=', $code_fac)
                ->select(
                    'detailhopitals.*',
                )
                ->first();
                $hos->statut = 'Liberé';
                if (!$hos->save()) {
                    throw new \Exception('Erreur');
                }

                $litm = lit::find($hos->lit_id);
                $litm->statut = 'disponible';
                if (!$litm->save()) {
                    throw new \Exception('Erreur');
                }



                // ------------------------------------------------------------

                $hopital = detailhopital::find($hos->id);

                $montant = str_replace('.', '', $hopital->part_patient);
                $montant_soins = str_replace('.', '', $hopital->montant_soins);

                // Additionner les montants
                $total = $montant + $montant_soins;

                // Remettre les points pour les milliers
                $total_formatted = number_format($total, 0, '', '.');

                $hopital->total_final = $total_formatted ;

                $facture = facture::find($hopital->facture_id);

                $patient = patient::leftjoin('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->leftjoin('tauxes', 'tauxes.id', '=', 'patients.taux_id')
                ->where('patients.id', '=', $hopital->patient_id)
                ->select('patients.*', 'assurances.nom as assurance', 'tauxes.taux as taux')
                ->first();

                if ($patient) {
                    $patient->age = $patient->datenais ? Carbon::parse($patient->datenais)->age : 0;
                }

                $natureadmission = natureadmission::find($hopital->natureadmission_id);
                $typeadmission = typeadmission::find($natureadmission->typeadmission_id);

                $lit = lit::find($hopital->lit_id);
                $chambre = chambre::find($lit->chambre_id);

                $user = user::join('typemedecins', 'typemedecins.user_id', '=', 'users.id')
                    ->join('typeactes', 'typeactes.id', '=', 'typemedecins.typeacte_id')
                    ->where('users.id', '=', $hopital->user_id)
                    ->select('users.*', 'typeactes.nom as typeacte')
                    ->first();

                $produit = soinshopital::join('produits', 'produits.id', '=', 'soinshopitals.produit_id')
                                    ->where('soinshopitals.detailhopital_id', '=', $hopital->id)
                                    ->select(
                                        'soinshopitals.*',
                                        'produits.nom as nom_produit',
                                        'produits.prix as prix_produit',
                                    )
                                    ->orderBy('soinshopitals.created_at', 'desc')
                                    ->get();

                //-----------------------------------------------

                $solde_caisse = caisse::find('1');

                $solde_caisse_sans_point = str_replace('.', '', $solde_caisse->solde);
                $part_patient_sans_point = str_replace('.', '', $hopital->part_patient);
                $solde_apres = (int)$solde_caisse_sans_point + (int)$part_patient_sans_point;

                $add_caisse = new historiquecaisse();
                $add_caisse->motif = 'ENCAISSEMENT HOSPITALISATION';
                $add_caisse->montant = $hopital->part_patient;
                $add_caisse->libelle = 'Encaissment HOSPITALISATION Facture N°'.$fac->code;
                $add_caisse->solde_avant = $solde_caisse->solde;
                $add_caisse->solde_apres = number_format($solde_apres, 0, '', '.');
                $add_caisse->typemvt = 'Entrer de Caisse';
                $add_caisse->creer_id = $request->auth_id;
                $today = Carbon::today();
                $add_caisse->date_ope = $today;
                
                if (!$add_caisse->save()) {
                    throw new \Exception('Erreur');
                }

                $solde_caisse->solde = number_format($solde_apres, 0, '', '.');

                if (!$solde_caisse->save()) {
                    throw new \Exception('Erreur');
                }

                //-----------------------------------------------

                DB::commit();
                return response()->json([
                    'success' => true,
                    'hopital' => $hopital,
                    'facture' => $facture,
                    'patient' => $patient,
                    'natureadmission' => $natureadmission,
                    'typeadmission' => $typeadmission,
                    'lit' => $lit,
                    'chambre' => $chambre,
                    'user' => $user,
                    'produit' => $produit,
                ]);

            }else{
                throw new \Exception('Erreur');
            }
            
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true]);
        }
    }

    public function facture_payer_soinsam(Request $request,$code_fac)
    {

        $caisse_verf = caisse::find(1);

        if ($caisse_verf->statut === 'fermer') {
            return response()->json(['caisse_fermer' => true]);
        }

        DB::beginTransaction();

        $fac = facture::where('code', '=', $code_fac)->first();

        try {

            if ($fac) {

               $fac->montant_verser = $request->montant_verser;
               $fac->montant_remis = $request->montant_remis;
               $fac->statut = 'payer';
               $fac->date_payer = Carbon::now();
               $fac->encaisser_id = $request->auth_id;

                if (!$fac->save()) {
                    throw new \Exception('Erreur');
                }

                $spatient = soinspatient::where('facture_id', '=', $fac->id)->first();
                $spatient->statut = 'terminé';

                if (!$spatient->save()) {
                    throw new \Exception('Erreur');
                }

                // ------------------------------------------------------------

                $soinspatient = soinspatient::find($spatient->id);

                if ($soinspatient) { // Vérifiez que le patient a bien été trouvé
                    // Total des produits
                    $produittotal = sp_produit::where('soinspatient_id', '=', $soinspatient->id)
                        ->select(DB::raw('COALESCE(SUM(REPLACE(montant, ".", "") + 0), 0) as total'))
                        ->first();

                    $soinspatient->prototal = $produittotal->total ?? 0;

                    // Total des soins
                    $soinstotal = sp_soins::where('soinspatient_id', '=', $soinspatient->id)
                        ->select(DB::raw('COALESCE(SUM(REPLACE(montant, ".", "") + 0), 0) as total'))
                        ->first();

                    $soinspatient->stotal = $soinstotal->total ?? 0;
                } 

                $facture = facture::find($soinspatient->facture_id);

                $patient = patient::leftjoin('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->leftjoin('tauxes', 'tauxes.id', '=', 'patients.taux_id')
                ->where('patients.id', '=', $soinspatient->patient_id)
                ->select('patients.*', 'assurances.nom as assurance', 'tauxes.taux as taux')
                ->first();

                if ($patient) {
                    $patient->age = $patient->datenais ? Carbon::parse($patient->datenais)->age : 0;
                }

                $typesoins = typesoins::find($soinspatient->typesoins_id);

                $soins = sp_soins::join('soinsinfirmiers', 'soinsinfirmiers.id', '=', 'sp_soins.soinsinfirmier_id')
                    ->where('sp_soins.soinspatient_id', '=', $soinspatient->id)
                    ->select('sp_soins.*', 'soinsinfirmiers.nom as nom_si', 'soinsinfirmiers.prix as prix_si')
                    ->get();


                // Récupération des produits avec les informations associées
                $produit = sp_produit::join('produits', 'produits.id', '=', 'sp_produits.produit_id')
                    ->where('sp_produits.soinspatient_id', '=', $soinspatient->id)
                    ->select('sp_produits.*', 'produits.nom as nom_pro', 'produits.prix as prix_pro', 'produits.quantite as quantite_pro')
                    ->get();

                //-----------------------------------------------

                $solde_caisse = caisse::find('1');

                $solde_caisse_sans_point = str_replace('.', '', $solde_caisse->solde);
                $part_patient_sans_point = str_replace('.', '', $soinspatient->part_patient);
                $solde_apres = (int)$solde_caisse_sans_point + (int)$part_patient_sans_point;

                $add_caisse = new historiquecaisse();
                $add_caisse->motif = 'ENCAISSEMENT SOINS AMBULATOIRE';
                $add_caisse->montant = $soinspatient->part_patient;
                $add_caisse->libelle = 'Encaissment SOINS AMBULATOIRE Facture N°'.$fac->code;
                $add_caisse->solde_avant = $solde_caisse->solde;
                $add_caisse->solde_apres = number_format($solde_apres, 0, '', '.');
                $add_caisse->typemvt = 'Entrer de Caisse';
                $add_caisse->creer_id = $request->auth_id;
                $today = Carbon::today();
                $add_caisse->date_ope = $today;
                
                if (!$add_caisse->save()) {
                    throw new \Exception('Erreur');
                }

                $solde_caisse->solde = number_format($solde_apres, 0, '', '.');

                if (!$solde_caisse->save()) {
                    throw new \Exception('Erreur');
                }

                //-----------------------------------------------

                DB::commit();
                return response()->json([
                    'success' => true,
                    'soinspatient' => $soinspatient,
                    'facture' => $facture,
                    'patient' => $patient,
                    'soins' => $soins,
                    'produit' => $produit,
                    'typesoins' => $typesoins,
                ]);

            }else{

                throw new \Exception('Erreur');
                
            }
            
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true]);
        }
    }

    public function facture_payer_examen(Request $request,$code_fac)
    {

        $caisse_verf = caisse::find(1);

        if ($caisse_verf->statut === 'fermer') {
            return response()->json(['caisse_fermer' => true]);
        }
        
        DB::beginTransaction();

        $fac = facture::where('code', '=', $code_fac)->first();

        try {

            if ($fac) {

               $fac->montant_verser = $request->montant_verser;
               $fac->montant_remis = $request->montant_remis;
               $fac->statut = 'payer';
               $fac->date_payer = Carbon::now();
               $fac->encaisser_id = $request->auth_id;

                if (!$fac->save()) {
                    throw new \Exception('Erreur');
                }

                $statut = examen::where('facture_id', '=', $fac->id)->first();
                $statut->statut = 'terminé';

                if (!$statut->save()) {
                    throw new \Exception('Erreur');
                }

                // ------------------------------------------------------------

                $examen = examen::find($statut->id);

                    $partPatient = str_replace('.', '', $examen->part_patient);
                    $prelevement = str_replace('.', '', $examen->prelevement);

                    // Conversion en entier
                    $partPatient = (int)$partPatient;
                    $prelevement = (int)$prelevement;

                    // Calcul de la somme
                    $examen->total_patient = $partPatient + $prelevement;

                $facture = facture::find($examen->facture_id);

                $total_amount = intval(str_replace('.', '', $facture->montant_verser));
                $remis_amount = intval(str_replace('.', '', $facture->montant_remis));
                $paid_amount = intval(str_replace('.', '', $examen->total_patient));

                $remaining_amount = $total_amount - ($paid_amount + $remis_amount);

                function formatWithPeriods($number) {
                return number_format($number, 0, '', '.');
                }

                $facture->montant_restant = formatWithPeriods($remaining_amount);

                $patient = patient::leftjoin('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->leftjoin('tauxes', 'tauxes.id', '=', 'patients.taux_id')
                ->where('patients.id', '=', $examen->patient_id)
                ->select('patients.*', 'assurances.nom as assurance', 'tauxes.taux as taux')
                ->first();

                if ($patient) {
                    $patient->age = $patient->datenais ? Carbon::parse($patient->datenais)->age : 0;
                }

                $acte = acte::find($examen->acte_id);

                $examenpatient = examenpatient::join('typeactes', 'typeactes.id', '=', 'examenpatients.typeacte_id')
                                    ->where('examenpatients.examen_id', '=', $examen->id)
                                    ->select(
                                        'examenpatients.*',
                                        'typeactes.nom as nom_ex',
                                        'typeactes.prix as prix_ex',
                                        'typeactes.cotation as cotation_ex',
                                        'typeactes.valeur as valeur_ex',
                                        'typeactes.montant as montant_ex',
                                    )
                                    ->orderBy('examenpatients.created_at', 'desc')
                                    ->get();

                //-----------------------------------------------

                $solde_caisse = caisse::find('1');

                $solde_caisse_sans_point = str_replace('.', '', $solde_caisse->solde);
                $part_patient_sans_point = str_replace('.', '', $examen->part_patient);
                $solde_apres = (int)$solde_caisse_sans_point + (int)$part_patient_sans_point;

                $add_caisse = new historiquecaisse();
                $add_caisse->motif = 'ENCAISSEMENT EXAMEN';
                $add_caisse->montant = $examen->part_patient;
                $add_caisse->libelle = 'Encaissment EXAMEN Facture N°'.$fac->code;
                $add_caisse->solde_avant = $solde_caisse->solde;
                $add_caisse->solde_apres = number_format($solde_apres, 0, '', '.');
                $add_caisse->typemvt = 'Entrer de Caisse';
                $add_caisse->creer_id = $request->auth_id;
                $today = Carbon::today();
                $add_caisse->date_ope = $today;
                
                if (!$add_caisse->save()) {
                    throw new \Exception('Erreur');
                }

                $solde_caisse->solde = number_format($solde_apres, 0, '', '.');

                if (!$solde_caisse->save()) {
                    throw new \Exception('Erreur');
                }

                //-----------------------------------------------

                DB::commit();
                return response()->json([
                    'success' => true,
                    'examen' => $examen,
                    'facture' => $facture,
                    'patient' => $patient,
                    'acte' => $acte,
                    'examenpatient' => $examenpatient,
                ]);

            }else{

                throw new \Exception('Erreur');
                
            }
            
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true]);
        }
    }
}
