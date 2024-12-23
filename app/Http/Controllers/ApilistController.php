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
use App\Models\joursemaine;
use App\Models\rdvpatient;
use App\Models\programmemedecin;
use App\Models\depotfacture;
use App\Models\caisse;
use App\Models\historiquecaisse;
use App\Models\portecaisse;

class ApilistController extends Controller
{
    private function formatWithPeriods($number) 
    {
        return number_format($number, 0, '', '.');
    }
    private function generateUniqueMatriculeDossierDC()
    {
        do {
            $matricule = random_int(100000, 999999);
        } while (DB::table('dossierpatient')->where('numdossier', '=', 'DC'.$matricule)->exists());

        return $matricule;
    }




















    public function list_chambre()
    {
        $chambre = DB::table('chambres')->select('chambres.*')->orderBy('chambres.created_at', 'desc')->get();

        return response()->json(['chambre' => $chambre]);
    }

    public function list_lit()
    {
        $lit = DB::table('lits')
            ->Join('chambres', 'chambres.id', '=', 'lits.chambre_id')
            ->orderBy('lits.created_at', 'desc')
            ->select('lits.*', 'chambres.prix as prix', 'chambres.code as code_ch')
            ->get();

        return response()->json(['data' => $lit]);
    }

    public function list_acte()
    {
        $acte = acte::all();

        return response()->json(['acte' => $acte]);
    }

    public function list_typeacte()
    {
        $typeacte = typeacte::join('actes', 'actes.id', '=', 'typeactes.acte_id')
                        ->orderBy('typeactes.created_at', 'desc')
                        ->select('typeactes.*', 'actes.nom as acte',)
                        ->get();

        return response()->json(['typeacte' => $typeacte]);
    }

    public function list_medecin()
    {

        $medecins = DB::table('medecin')
            ->join('specialitemed', 'specialitemed.codespecialitemed', '=', 'medecin.codespecialitemed')
            ->select('medecin.*','specialitemed.codespecialitemed as specialite_id','specialitemed.nomspecialite as specialite')
            ->orderBy('medecin.dateservice', 'desc')
            ->get();

        return response()->json([
            'data' => $medecins,
        ]);
    }

    public function list_cons_day()
    {
        $today = Carbon::today();

        $consultation = DB::table('consultation')
            ->join('patient', 'consultation.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
            ->leftjoin('dossierpatient', 'consultation.idenregistremetpatient', '=', 'dossierpatient.idenregistremetpatient')
            ->join('medecin', 'consultation.codemedecin', '=', 'medecin.codemedecin')
            ->join('specialitemed', 'medecin.codespecialitemed', '=', 'specialitemed.codespecialitemed')
            ->join('garantie', 'consultation.codeacte', '=', 'garantie.codgaran')
            ->where('garantie.codtypgar', '=', 'CONS')
            ->whereDate('consultation.date', $today)
            ->select(
                'consultation.idconsexterne as idconsexterne',
                'consultation.montant as montant',
                'consultation.date as date',
                'consultation.numfac as numfac',
                'consultation.regle as regle',
                'dossierpatient.numdossier as numdossier',
                'patient.nomprenomspatient as nom_patient',
                'patient.telpatient as tel_patient',
                'patient.assure as assure',
                'medecin.nomprenomsmed as nom_medecin',
                'specialitemed.nomspecialite as specialite',
            )
            ->orderBy('consultation.date', 'desc')
            ->get();

        return response()->json([
            'data' => $consultation,
        ]);
    }

    public function list_cons()
    {
        $consultationQuery = detailconsultation::join('consultations', 'consultations.id', '=', 'detailconsultations.consultation_id')
                                    ->leftJoin('users', 'users.id', '=', 'consultations.user_id')
                                    ->join('patients', 'patients.id', '=', 'consultations.patient_id')
                                    ->select(
                                        'detailconsultations.*',
                                        'consultations.code as code', 
                                        'patients.np as name', 
                                        'users.tel as tel', 
                                        'users.tel2 as tel2',
                                        'patients.matricule as matricule'
                                    )
                                    ->orderBy('detailconsultations.created_at', 'desc');

        $consultation = $consultationQuery->paginate(15);

        return response()->json([
            'consultation' => $consultation->items(), // Paginated data
            'pagination' => [
                'current_page' => $consultation->currentPage(),
                'last_page' => $consultation->lastPage(),
                'per_page' => $consultation->perPage(),
                'total' => $consultation->total(),
            ]
        ]);
    }

    public function list_typeadmission()
    {
        $typeadmission = typeadmission::orderBy('created_at', 'desc')->get();
        foreach ($typeadmission as $value) {
            $value->nbre = natureadmission::where('typeadmission_id', '=', $value->id)->count() ?: 0;
        }

        return response()->json(['typeadmission' => $typeadmission]);
    }

    public function list_natureadmission()
    {
        $natureadmission = natureadmission::join('typeadmissions', 'typeadmissions.id', '=', 'natureadmissions.typeadmission_id')->select('natureadmissions.*','typeadmissions.nom as typeadmission')->orderBy('natureadmissions.created_at', 'desc')->get();

        foreach ($natureadmission as $value) {
            $value->nbre = detailhopital::where('natureadmission_id', '=', $value->id)->count() ?: 0;
        }

        return response()->json([
            'data' => $natureadmission,
        ]);
    }

    public function list_hopital($date1, $date2,$statut)
    {
        $date1 = Carbon::parse($date1)->startOfDay();
        $date2 = Carbon::parse($date2)->endOfDay();
        
        $hopitalQuery = detailhopital::join('natureadmissions', 'natureadmissions.id', '=', 'detailhopitals.natureadmission_id')
                                ->join('typeadmissions', 'typeadmissions.id', '=', 'natureadmissions.typeadmission_id')
                                ->join('patients', 'patients.id', '=', 'detailhopitals.patient_id')
                                ->join('users', 'users.id', '=', 'detailhopitals.user_id')
                                ->join('factures', 'factures.id','=','detailhopitals.facture_id')
                                ->whereBetween('detailhopitals.created_at', [$date1, $date2])
                                ->select(
                                    'detailhopitals.*',
                                    'factures.statut as statut_fac',
                                    'natureadmissions.nom as nature',
                                    'typeadmissions.nom as type',
                                    'patients.np as patient',
                                    'users.name as medecin',
                                )->orderBy('detailhopitals.created_at', 'desc');

        if ($statut !== 'tous') {
            $hopitalQuery->where('detailhopitals.statut', '=', $statut);
        }

        $hopital = $hopitalQuery->get();

        return response()->json([
            'data' => $hopital,
        ]);
    }

    public function detail_hos($id)
    {
        $hopital = detailhopital::find($id);

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
        
        return response()->json([
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
    }

    public function list_produit()
    {
        // $add = DB::table('medicine')->select('medicine.*')->get();

        // foreach ($add as $value) {
        //     if ($value->status == null || $value->status == '' ) {
        //         $updateData_soins =[
        //             'status' => 0,
        //             'updated_at' => now(),
        //         ];

        //         $soinsUpdate = DB::table('medicine')
        //                             ->where('medicine_id', '=', $value->medicine_id)
        //                             ->update($updateData_soins);
        //     }
        // }

        $produits = DB::table('medicine')
            ->Join('medicine_category', 'medicine_category.medicine_category_id', '=', 'medicine.medicine_category_id')
            ->select('medicine.*', 'medicine_category.name as categorie')
            ->get();

        return response()->json([
            'data' => $produits,
        ]);
    }

    public function list_produit_all()
    {
        $produit = produit::orderBy('nom', 'asc')->get();

        return response()->json(['produit' => $produit]);
    }

    public function list_patient_all()
    {
        $patients = DB::table('patient')
            ->leftJoin('societeassure', 'patient.codesocieteassure', '=', 'societeassure.codesocieteassure')
            ->leftJoin('tauxcouvertureassure', 'patient.idtauxcouv', '=', 'tauxcouvertureassure.idtauxcouv')
            ->leftJoin('assurance', 'patient.codeassurance', '=', 'assurance.codeassurance')
            ->leftJoin('filiation', 'patient.codefiliation', '=', 'filiation.codefiliation')
            ->leftJoin('dossierpatient', 'patient.idenregistremetpatient', '=', 'dossierpatient.idenregistremetpatient')
            ->select(
                'patient.*', 
                'societeassure.nomsocieteassure as societe',
                'assurance.libelleassurance as assurance',
                'tauxcouvertureassure.valeurtaux as taux',
                'filiation.libellefiliation as filiation',
                'dossierpatient.numdossier as numdossier',
            )
            ->orderBy('patient.dateenregistrement','desc')
            ->get();

        foreach ($patients as $value) {

            DB::beginTransaction();

            try {

                $verf_dossierDC = DB::table('dossierpatient')
                ->where('idenregistremetpatient', '=', $value->idenregistremetpatient)
                ->where('codetypedossier', '=', 'DC')
                ->exists();

                if (!$verf_dossierDC) {

                    $numdossier_new = $this->generateUniqueMatriculeDossierDC();

                    $dossierPatientInserted = DB::table('dossierpatient')->insert([
                        'numdossier' => 'DC'.$numdossier_new,
                        'idenregistremetpatient' => $value->idenregistremetpatient,
                        'datecrea' => now(),
                        'codetypedossier' => 'DC',
                    ]);

                    if ($dossierPatientInserted === 0) {
                        throw new Exception('Erreur lors de l\'insertion dans la table dossierpatient');
                    }
                }

                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
            }
        }

        return response()->json([
            'data' => $patients,
        ]);
    }

    public function list_cons_all($date1,$date2)
    {
        $date1 = Carbon::parse($date1)->startOfDay();
        $date2 = Carbon::parse($date2)->endOfDay();

        $consultation = DB::table('consultation')
            ->join('patient', 'consultation.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
            ->leftjoin('dossierpatient', 'consultation.idenregistremetpatient', '=', 'dossierpatient.idenregistremetpatient')
            ->join('medecin', 'consultation.codemedecin', '=', 'medecin.codemedecin')
            ->join('specialitemed', 'medecin.codespecialitemed', '=', 'specialitemed.codespecialitemed')
            ->join('garantie', 'consultation.codeacte', '=', 'garantie.codgaran')
            ->where('garantie.codtypgar', '=', 'CONS')
            ->whereBetween('consultation.date', [$date1, $date2])
            ->select(
                'consultation.idconsexterne as idconsexterne',
                'consultation.montant as montant',
                'consultation.date as date',
                'consultation.numfac as numfac',
                'consultation.regle as regle',
                'dossierpatient.numdossier as numdossier',
                'patient.nomprenomspatient as nom_patient',
                'patient.telpatient as tel_patient',
                'patient.assure as assure',
                'medecin.nomprenomsmed as nom_medecin',
                'specialitemed.nomspecialite as specialite',
            )
            ->orderBy('consultation.date', 'desc')
            ->get();

        return response()->json([
            'data' => $consultation,
        ]);
    }

    public function list_typesoins()
    {
        $typesoins = DB::table('typesoinsinfirmiers')->select('typesoinsinfirmiers.*')->get();

        return response()->json(['typesoins' => $typesoins]);
    }

    public function list_soinsIn()
    {
        $soinsinQuery = DB::table('soins_infirmier')
            ->Join('typesoinsinfirmiers', 'typesoinsinfirmiers.code_typesoins', '=', 'soins_infirmier.code_typesoins')
            ->select('soins_infirmier.*', 'typesoinsinfirmiers.libelle_typesoins as typesoins')
            ->get();

        return response()->json([
            'data' => $soinsinQuery,
        ]);
    }

    public function list_soinsam_all($date1, $date2)
    {
        $date1 = Carbon::parse($date1)->startOfDay();
        $date2 = Carbon::parse($date2)->endOfDay();

        $spatient = DB::table('soins_medicaux')
            ->Join('patient', 'patient.idenregistremetpatient', '=', 'soins_medicaux.idenregistremetpatient')
            ->whereBetween('soins_medicaux.date_soin', [$date1, $date2])
            ->select(
                'soins_medicaux.*', 
                'patient.nomprenomspatient as patient'
            )
            ->orderBy('soins_medicaux.date_soin', 'desc')
            ->get();

        foreach ($spatient as $value) {
            $value->nbre_soins = DB::table('soins_medicaux_itemsoins')
                ->where('id_soins', '=', $value->id_soins)->count() ?: 0;

            $value->nbre_produit = DB::table('soins_medicaux_itemmedics')
                ->where('id_soins', '=', $value->id_soins)->count() ?: 0;
        }

        return response()->json([
            'data' => $spatient,
        ]);
    }

    public function detail_soinam($id)
    {
            
        $produittotal = DB::table('soins_medicaux_itemmedics')
            ->where('id_soins', '=', $id)
            ->select(DB::raw('COALESCE(SUM(REPLACE(price, ".", "") + 0), 0) as total'))
            ->first();

        // Total des soins
        $soinstotal = DB::table('soins_medicaux_itemsoins')
            ->where('id_soins', '=', $id)
            ->select(DB::raw('COALESCE(SUM(REPLACE(price, ".", "") + 0), 0) as total'))
            ->first();

        $soins = DB::table('soins_medicaux_itemsoins')
            ->where('id_soins', '=', $id)
            ->select('soins_medicaux_itemsoins.*')
            ->get();

        $produit = DB::table('soins_medicaux_itemmedics')
            ->join('medicine', 'medicine.medicine_id', '=', 'soins_medicaux_itemmedics.medicine_id')
            ->where('id_soins', '=', $id)
            ->select('soins_medicaux_itemmedics.*','medicine.price as priceu')
            ->get();

        $patient = DB::table('soins_medicaux')
            ->Join('factures', 'factures.numfac', '=', 'soins_medicaux.numfac_soins')
            ->Join('patient', 'patient.idenregistremetpatient', '=', 'soins_medicaux.idenregistremetpatient')
            ->leftjoin('dossierpatient', 'dossierpatient.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
            ->leftJoin('tauxcouvertureassure', 'patient.idtauxcouv', '=', 'tauxcouvertureassure.idtauxcouv')
            ->leftJoin('assurance', 'patient.codeassurance', '=', 'assurance.codeassurance')
            ->where('soins_medicaux.id_soins', '=', $id)
            ->select(
                'soins_medicaux.*',
                'dossierpatient.numdossier as numdossier',
                'patient.nomprenomspatient as nom_patient',
                'patient.telpatient as tel_patient',
                'patient.assure as assure',
                'patient.datenaispatient as datenais',
                'patient.telpatient as telpatient',
                'patient.matriculeassure as matriculeassure',
                'assurance.libelleassurance as assurance',
                'tauxcouvertureassure.valeurtaux as taux',
                'factures.montant_ass as part_assurance',
                'factures.montant_pat as part_patient',
                'factures.montantregle_pat as part_patient_regler',
                'factures.numrecu as numrecu',
                'factures.datereglt_pat as datereglt_pat',
                'factures.montantpat_verser as montant_verser',
                'factures.montantpat_remis as montant_remis',
                'factures.montantreste_pat as montant_restant',
            )
            ->first();

        $patient->nbre_soins = DB::table('soins_medicaux_itemsoins')
            ->where('id_soins', '=', $patient->id_soins)->count() ?: 0;

        $patient->nbre_produit = DB::table('soins_medicaux_itemmedics')
            ->where('id_soins', '=', $patient->id_soins)->count() ?: 0;

        $patient->prototal = $produittotal->total ?? 0;
        $patient->stotal = $soinstotal->total ?? 0;

        return response()->json([
            'patient' =>$patient,
            'soins' => $soins,
            'produit' => $produit,
        ]);

    }

    public function list_societe_all()
    {
        $societe = DB::table('societeassure')
            ->join('assurance', 'assurance.codeassurance', '=', 'societeassure.codeassurance')
            ->join('assureur', 'assureur.codeassureur', '=', 'societeassure.codeassureur')
            ->select(
                'societeassure.*',
                'assurance.codeassurance as codeassurance',
                'assurance.libelleassurance as assurance',
                'assureur.codeassureur as codeassureur',
                'assureur.libelle_assureur as assureur',
            )
            ->get();

        return response()->json([
            'data' => $societe,
        ]);
    }

    public function list_examen_all()
    {
        $examen = DB::table('examen')
            ->leftJoin('famille_examen', 'examen.codfamexam', '=', 'famille_examen.codfamexam')
            ->select(
                'examen.*',
                'famille_examen.nomfamexam as type',
            )
            ->orderBy('denomination', 'asc')
            ->get();

        return response()->json([
            'data' => $examen,
        ]);
    }

    public function list_examend_all($date1, $date2)
    {
        $date1 = Carbon::parse($date1)->startOfDay();
        $date2 = Carbon::parse($date2)->endOfDay();

        $examen = DB::table('testlaboimagerie')
            ->join('patient', 'patient.idenregistremetpatient', '=', 'testlaboimagerie.idenregistremetpatient')
            ->leftjoin('medecin', 'testlaboimagerie.codemedecin', '=', 'medecin.codemedecin')
            ->join('factures', 'testlaboimagerie.numfacbul', '=', 'factures.numfac')
            ->whereBetween('testlaboimagerie.date', [$date1, $date2])
            ->select(
                'testlaboimagerie.*',
                'patient.nomprenomspatient as patient',
                'patient.assure as assure',
                'medecin.nomprenomsmed as medecin',
                'factures.montanttotal as montant',
            )
            ->orderBy('testlaboimagerie.date', 'desc')
            ->get();

        foreach ($examen as $value) {

            $nbre = DB::table('detailtestlaboimagerie')
                    ->where('idtestlaboimagerie', '=', $value->idtestlaboimagerie)
                    ->count();

            $value->nbre =  $nbre ?? 0;
        }

        return response()->json([
            'data' => $examen,
        ]);
    }

    public function detail_examen($id)
    {

        $facture = DB::table('testlaboimagerie')
            ->join('patient', 'testlaboimagerie.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
            ->leftjoin('dossierpatient', 'patient.idenregistremetpatient', '=', 'dossierpatient.idenregistremetpatient')
            ->leftJoin('tauxcouvertureassure', 'patient.idtauxcouv', '=', 'tauxcouvertureassure.idtauxcouv')
            ->leftJoin('assurance', 'patient.codeassurance', '=', 'assurance.codeassurance')
            ->leftjoin('medecin', 'testlaboimagerie.codemedecin', '=', 'medecin.codemedecin')
            ->join('factures', 'testlaboimagerie.numfacbul', '=', 'factures.numfac')
            ->where('testlaboimagerie.idtestlaboimagerie', '=', $id)
            ->select(
                'testlaboimagerie.*',
                'dossierpatient.numdossier as numdossier',
                'patient.nomprenomspatient as nom_patient',
                'patient.telpatient as telpatient',
                'patient.assure as assure',
                'patient.datenaispatient as datenais',
                'patient.matriculeassure as matriculeassure',
                'medecin.nomprenomsmed as nom_medecin',
                'factures.remise as remise',
                'assurance.libelleassurance as assurance',
                'tauxcouvertureassure.valeurtaux as taux',
                'factures.montant_ass as part_assurance',
                'factures.montant_pat as part_patient',
                'factures.montanttotal as montant',
                'factures.montantregle_pat as part_patient_regler',
                'factures.numrecu as numrecu',
                'factures.datereglt_pat as datereglt_pat',
                'factures.montantpat_verser as montant_verser',
                'factures.montantpat_remis as montant_remis',
                'factures.montantreste_pat as montant_restant',
            )
            ->first();

        $examen = DB::table('detailtestlaboimagerie')
            ->where('idtestlaboimagerie', '=', $id)
            ->select(
                'detailtestlaboimagerie.denomination as examen',
                'detailtestlaboimagerie.resultat as resultat',
                'detailtestlaboimagerie.prix as prix',
            )
            ->get();

        $sumMontantEx = $examen->sum(function ($item) {
            $montantEx = str_replace('.', '', $item->prix);
            return (int) $montantEx;
        });

        
        return response()->json([
            'facture' => $facture,
            'examen' => $examen,
            'sumMontantEx' => $sumMontantEx,
        ]);
    }

    public function select_jours()
    {
        $jour = joursemaine::all();
        
        return response()->json([
            'jour' => $jour,
        ]);
    }

    public function list_horaire($medecin, $specialite, $jour, $periode)
    {
        $query = DB::table('medecin')
            ->join('specialitemed', 'medecin.codespecialitemed', '=', 'specialitemed.codespecialitemed');

        if ($medecin !== 'tout') {
            $query->where('medecin.codemedecin', '=', $medecin);
        }

        if ($specialite !== 'tout') {
            $query->where('specialitemed.codespecialitemed', '=', $specialite);
        }

        $medecins = $query->select('medecin.*', 'specialitemed.libellespecialite as specialité')->get();

        foreach ($medecins as $value) {
            $horairesQuery = DB::table('programmemedecins')
                ->join('joursemaines', 'joursemaines.id', '=', 'programmemedecins.jour_id')
                ->where('programmemedecins.codemedecin', '=', $value->codemedecin)
                ->where('programmemedecins.statut', '=', 'oui');

            // Filtrage par jour
            if ($jour !== 'tout') {
                $horairesQuery->where('joursemaines.id', '=', $jour);
            }

            // Filtrage par période (Matin/Soir)
            if ($periode !== 'tout') {
                $horairesQuery->where('programmemedecins.periode', '=', $periode);
            }

            $horaires = $horairesQuery->select('programmemedecins.*', 'joursemaines.jour as jour')->get();
            $value->horaires = $horaires;
        }

        return response()->json([
            'medecins' => $medecins,
        ]);
    }

    public function list_rdv()
    {
        $rdv = DB::table('rdvpatients')
            ->Join('patient', 'patient.idenregistremetpatient', '=', 'rdvpatients.patient_id')
            ->Join('medecin', 'medecin.codemedecin', '=', 'rdvpatients.codemedecin')
            ->join('specialitemed', 'medecin.codespecialitemed', '=', 'specialitemed.codespecialitemed')
            ->select(
                'rdvpatients.*', 
                'patient.nomprenomspatient as patient',
                'patient.telpatient as patient_tel',
                'medecin.nomprenomsmed as medecin',
                'specialitemed.nomspecialite as specialite'
            )
            ->orderBy('rdvpatients.created_at', 'desc')
            ->get();

        foreach ($rdv as $value) {
            $horaires = DB::table('programmemedecins')
                ->join('joursemaines', 'joursemaines.id', '=', 'programmemedecins.jour_id')
                ->where('programmemedecins.codemedecin', '=', $value->codemedecin)
                ->where('programmemedecins.statut', '=', 'oui')
                ->select('programmemedecins.*', 'joursemaines.jour as jour')
                ->get();

            $value->horaires = $horaires;
        }

        return response()->json([
            'data' => $rdv,
        ]);
    }

    public function list_rdv_day()
    {
        $today = Carbon::today();

        $rdv = DB::table('rdvpatients')
            ->Join('patient', 'patient.idenregistremetpatient', '=', 'rdvpatients.patient_id')
            ->Join('medecin', 'medecin.codemedecin', '=', 'rdvpatients.codemedecin')
            ->join('specialitemed', 'medecin.codespecialitemed', '=', 'specialitemed.codespecialitemed')
            ->whereDate('rdvpatients.date', '=', $today)
            ->select(
                'rdvpatients.*', 
                'patient.nomprenomspatient as patient',
                'patient.telpatient as patient_tel',
                'medecin.nomprenomsmed as medecin',
                'specialitemed.nomspecialite as specialite'
            )
            ->orderBy('rdvpatients.created_at', 'desc')
            ->get();

        foreach ($rdv as $value) {
            $horaires = DB::table('programmemedecins')
                ->join('joursemaines', 'joursemaines.id', '=', 'programmemedecins.jour_id')
                ->where('programmemedecins.codemedecin', '=', $value->codemedecin)
                ->where('programmemedecins.statut', '=', 'oui')
                ->select('programmemedecins.*', 'joursemaines.jour as jour')
                ->get();

            $value->horaires = $horaires;
        }

        return response()->json([
            'data' => $rdv,
        ]);

    }

    public function list_specialite()
    {

        $specialite = DB::table('specialitemed')
            ->select('codespecialitemed','nomspecialite', 'abrspecialite')
            ->get();

        return response()->json([
            'data' => $specialite,
        ]);
    }

    public function list_depotfacture(Request $request)
    {
        $total_patient = 0;
        $total_assurance = 0;
        $total_montant = 0;

        $depotQuery = depotfacture::join('assurances', 'assurances.id', '=', 'depotfactures.assurance_id')
                                ->select(
                                    'depotfactures.*',
                                    'assurances.nom as assurance',
                                )
                                ->orderBy('depotfactures.created_at', 'desc')
                                ->get();

        foreach ($depotQuery as $depo) {

            $date1 = Carbon::parse($depo->date1)->startOfDay();
            $date2 = Carbon::parse($depo->date2)->endOfDay();

            $fac_cons = consultation::join('patients', 'patients.id', '=', 'consultations.patient_id')
                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->join('societes', 'societes.id', '=', 'patients.societe_id')
                ->join('detailconsultations', 'detailconsultations.consultation_id', '=', 'consultations.id')
                ->where('patients.assurer', '=', 'oui')
                ->where('consultations.num_bon', '!=', null)
                ->whereBetween(DB::raw('DATE(consultations.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $depo->assurance_id)
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

            if ($fac_cons) {
                foreach ($fac_cons as $value) {
                    // Parse and ensure that invalid or missing values default to 0
                    $patient = intval(str_replace('.', '', $value->part_patient ?? 0));
                    $remise = intval(str_replace('.', '', $value->remise ?? 0));

                    // Calculate totals and ensure invalid values are counted as 0
                    $total_patient += $patient + $remise;
                    $total_assurance += intval(str_replace('.', '', $value->part_assurance ?? 0));
                    $total_montant += intval(str_replace('.', '', $value->montant ?? 0));
                }
            }else{
                $total_patient += 0;
                $total_assurance += 0;
                $total_montant += 0;
            }
            

            $fac_exam = examen::join('patients', 'patients.id', '=', 'examens.patient_id')
                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->join('societes', 'societes.id', '=', 'patients.societe_id')
                ->where('patients.assurer', '=', 'oui')
                ->where('examens.num_bon', '!=', null)
                ->whereBetween(DB::raw('DATE(examens.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $depo->assurance_id)
                ->select(
                    'examens.num_bon as num_bon',
                    'examens.created_at as created_at',
                    'patients.np as patient',
                    'examens.part_assurance as part_assurance',
                    'examens.part_patient as part_patient',
                    'examens.montant as montant',
                )
                ->get();

            if ($fac_exam) {
                foreach ($fac_exam as $value) {
                    $total_patient += intval(str_replace('.', '', $value->part_patient ?? 0));
                    $total_assurance += intval(str_replace('.', '', $value->part_assurance ?? 0));
                    $total_montant += intval(str_replace('.', '', $value->montant ?? 0));
                }
            }else{
                $total_patient += 0;
                $total_assurance += 0;
                $total_montant += 0;
            }

            $fac_soinsam = soinspatient::join('patients', 'patients.id', '=', 'soinspatients.patient_id')
                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->join('societes', 'societes.id', '=', 'patients.societe_id')
                ->where('patients.assurer', '=', 'oui')
                ->where('soinspatients.num_bon', '!=', null)
                ->whereBetween(DB::raw('DATE(soinspatients.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $depo->assurance_id)
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

            if ($fac_soinsam) {
                foreach ($fac_soinsam as $value) {
                    $patient = intval(str_replace('.', '', $value->part_patient ?? 0));
                    $remise = intval(str_replace('.', '', $value->remise ?? 0));

                    $total_patient += $patient + $remise;
                    $total_assurance += intval(str_replace('.', '', $value->part_assurance ?? 0));
                    $total_montant += intval(str_replace('.', '', $value->montant ?? 0));
                }
            }else{
                $total_patient += 0;
                $total_assurance += 0;
                $total_montant += 0;
            }

            $fac_hopital = detailhopital::join('patients', 'patients.id', '=', 'detailhopitals.patient_id')
                ->leftjoin('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->leftjoin('societes', 'societes.id', '=', 'patients.societe_id')
                ->where('patients.assurer', '=', 'oui')
                ->where('detailhopitals.num_bon', '!=', null)
                ->whereBetween(DB::raw('DATE(detailhopitals.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $depo->assurance_id)
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

            if ($fac_hopital) {
                foreach ($fac_hopital as $value) {
                    $patient = intval(str_replace('.', '', $value->part_patient ?? 0));
                    $remise = intval(str_replace('.', '', $value->remise ?? 0));

                    $total_patient += $patient + $remise;
                    $total_assurance += intval(str_replace('.', '', $value->part_assurance ?? 0));
                    $total_montant += intval(str_replace('.', '', $value->montant ?? 0));
                }
            }else{
                $total_patient += 0;
                $total_assurance += 0;
                $total_montant += 0;
            }

            $depo->part_patient = $this->formatWithPeriods($total_patient);
            $depo->part_assurance = $this->formatWithPeriods($total_assurance);
            $depo->total = $this->formatWithPeriods($total_montant); 
        }

        return response()->json([
            'data' => $depotQuery,
        ]);
    }

    public function list_cons_patient($id)
    {
        $consultationQuery = detailconsultation::join('consultations', 'consultations.id', '=', 'detailconsultations.consultation_id')
                                    ->leftJoin('users', 'users.id', '=', 'consultations.user_id')
                                    ->join('patients', 'patients.id', '=', 'consultations.patient_id')
                                    ->where('patients.id', '=', $id)
                                    ->select(
                                        'detailconsultations.*',
                                        'consultations.code as code', 
                                        'patients.np as name',
                                        'users.name as medecin',
                                        'users.tel as tel', 
                                        'users.tel2 as tel2',
                                        'patients.matricule as matricule'
                                    )
                                    ->orderBy('detailconsultations.created_at', 'desc');

        $consultation = $consultationQuery->paginate(15);

        return response()->json([
            'consultation' => $consultation->items(), // Paginated data
            'pagination' => [
                'current_page' => $consultation->currentPage(),
                'last_page' => $consultation->lastPage(),
                'per_page' => $consultation->perPage(),
                'total' => $consultation->total(),
            ]
        ]);
    }

    public function list_examend_patient($id)
    {
        $examenQuery = examen::join('patients', 'patients.id', '=', 'examens.patient_id')
                            ->join('actes', 'actes.id', '=', 'examens.acte_id')
                            ->where('patients.id', '=', $id)
                            ->select(
                                'examens.*',
                                'actes.nom as acte',
                                'patients.np as patient',
                            )
                            ->orderBy('created_at', 'desc');

        $examen = $examenQuery->paginate(15);

        foreach ($examen->items() as $value) {
            $nbre = examenpatient::where('examen_id', '=', $value->id)->count();
            $value->nbre =  $nbre ?? 0;
        }

        return response()->json([
            'examen' => $examen->items(),
            'pagination' => [
                'current_page' => $examen->currentPage(),
                'last_page' => $examen->lastPage(),
                'per_page' => $examen->perPage(),
                'total' => $examen->total(),
            ]
        ]);
    }

    public function list_hopital_patient($id)
    {
        $hopitalQuery = detailhopital::join('natureadmissions', 'natureadmissions.id', '=', 'detailhopitals.natureadmission_id')
                                ->join('typeadmissions', 'typeadmissions.id', '=', 'natureadmissions.typeadmission_id')
                                ->join('patients', 'patients.id', '=', 'detailhopitals.patient_id')
                                ->join('users', 'users.id', '=', 'detailhopitals.user_id')
                                ->join('factures', 'factures.id','=','detailhopitals.facture_id')
                                ->where('patients.id', '=', $id)
                                ->select(
                                    'detailhopitals.*',
                                    'factures.statut as statut_fac',
                                    'natureadmissions.nom as nature',
                                    'typeadmissions.nom as type',
                                    'patients.np as patient',
                                    'users.name as medecin',
                                )->orderBy('detailhopitals.created_at', 'desc');

        $hopital = $hopitalQuery->paginate(15);

        return response()->json([
            'hopital' => $hopital->items(), // Paginated data
            'pagination' => [
                'current_page' => $hopital->currentPage(),
                'last_page' => $hopital->lastPage(),
                'per_page' => $hopital->perPage(),
                'total' => $hopital->total(),
            ]
        ]);
    }

    public function list_soinsam_patient($id)
    {
        $spatientQuery = soinspatient::Join('patients', 'patients.id', '=', 'soinspatients.patient_id')
                       ->Join('typesoins', 'typesoins.id', '=', 'soinspatients.typesoins_id')
                       ->where('patients.id', '=', $id)
                       ->select(
                            'soinspatients.*', 
                            'patients.np as patient', 
                            'typesoins.nom as type')
                       ->orderBy('created_at', 'desc');

        $spatient = $spatientQuery->paginate(15);

        foreach ($spatient->items() as $value) {
            $value->nbre_soins = sp_soins::where('soinspatient_id', '=', $value->id)->count() ?: 0;
            $value->nbre_produit = sp_produit::where('soinspatient_id', '=', $value->id)->count() ?: 0;
        }

        return response()->json([
            'spatient' => $spatient->items(), // Paginated data
            'pagination' => [
                'current_page' => $spatient->currentPage(),
                'last_page' => $spatient->lastPage(),
                'per_page' => $spatient->perPage(),
                'total' => $spatient->total(),
            ]
        ]);
    }

    public function list_assureur_all()
    {
        $assureur = DB::table('assureur')->get();

        return response()->json([
            'data' => $assureur,
        ]);
    }

    public function list_assurance_all()
    {
        $assurance = DB::table('assurance')->where('codeassurance', '!=', 'NONAS')->get();

        return response()->json([
            'data' => $assurance,
        ]);
    }

    public function trace_operation($date1, $date2)
    {
        $date1 = Carbon::parse($date1)->startOfDay();
        $date2 = Carbon::parse($date2)->endOfDay();

        $trace = DB::table('caisse')
            ->whereBetween('datecreat', [$date1, $date2])
            ->orderBy('datecreat', 'desc')
            ->get();

        return response()->json([
            'data' => $trace,
        ]);
    }

    public function list_user(Request $request)
    {
        $users = DB::table('employes')
            ->join('users', 'users.code_personnel', '=', 'employes.matricule')
            ->join('contrat', 'contrat.code', '=', 'employes.typecontrat')
            ->select('employes.*','contrat.libelle as contrat','users.user_profil_id as user_profil_id','users.login as login')
            ->orderBy('employes.dateenregistre', 'desc')
            ->get();

        foreach ($users as $value) {
            $verf = DB::table('profile')->where('idprofile', '=', $value->user_profil_id)->first();

            if ($verf) {
                $value->profil = $verf->libprofile;
                $value->profil_id = $verf->idprofile;
            } else {
                $value->profil = Null;
                $value->profil_id = Null;
            }
        }

        return response()->json([
            'data' => $users,
        ]);
    }

    public function list_rdv_two_days()
    {
        $twoDaysLater = Carbon::today()->addDays(2);

        // $today = Carbon::today();
        // $twoDaysLater = Carbon::today()->addDays(2);

        $rdvQuery = DB::table('rdvpatients')
            ->Join('patient', 'patient.idenregistremetpatient', '=', 'rdvpatients.patient_id')
            ->Join('medecin', 'medecin.codemedecin', '=', 'rdvpatients.codemedecin')
            ->join('specialitemed', 'medecin.codespecialitemed', '=', 'specialitemed.codespecialitemed')
            ->whereDate('rdvpatients.date', '=', $twoDaysLater)
            ->select(
                'rdvpatients.*', 
                'patient.nomprenomspatient as patient',
                'medecin.nomprenomsmed as medecin',
                'specialitemed.nomspecialite as specialite'
            )
            ->orderBy('rdvpatients.created_at', 'desc');

        $rdv = $rdvQuery->paginate(15);

        return response()->json([
            'rdv' => $rdv->items(),
            'pagination' => [
                'current_page' => $rdv->currentPage(),
                'last_page' => $rdv->lastPage(),
                'per_page' => $rdv->perPage(),
                'total' => $rdv->total(),
            ]
        ]);
    }

    public function trace_ouvert_fermer($date1, $date2)
    {
        $date1 = Carbon::parse($date1)->startOfDay();
        $date2 = Carbon::parse($date2)->endOfDay();

        $trace = DB::table('caisse_resume')
            ->whereBetween('datecaisse', [$date1, $date2])
            ->orderBy('datecaisse', 'desc')
            ->get();

        return response()->json([
            'data' => $trace,
        ]);
    }

    public function list_type_garantie()
    {
        $type = DB::table('typgarantie')
            ->select('typgarantie.*')
            ->get();

        return response()->json([
            'data' => $type,
        ]);
    }

    public function list_garantie()
    {
        $garantie = DB::table('garantie')
            ->leftJoin('typgarantie', 'garantie.codtypgar', '=', 'typgarantie.codtypgar')
            ->select('garantie.*','typgarantie.libtypgar as type_garantie')
            ->get();

        return response()->json([
            'data' => $garantie,
        ]);
    }

    public function list_tarif(Request $request)
    {
        $tarifs = DB::table('tarifs')
            ->join('garantie', 'tarifs.codgaran', '=', 'garantie.codgaran')
            ->join('assurance', 'tarifs.codeassurance', '=', 'assurance.codeassurance')
            ->select(
                'tarifs.*',
                'garantie.libgaran as garantie',
                'assurance.libelleassurance as asurance',
            )
            ->orderBy('garantie.codgaran', 'asc')
            ->get();

        return response()->json([
            'data' => $tarifs,
        ]);
    }

}
