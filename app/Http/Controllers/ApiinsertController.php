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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
use App\Models\portecaisse;

class ApiinsertController extends Controller
{

    private function generateUniqueMatricule()
    {
        do {
            // Generate a random 9-digit number
            $matricule = random_int(100000, 999999); // Generates a number between 100000000 and 999999999
        } while (patient::where('matricule', $matricule)->exists()); // Ensure uniqueness

        // Return matricule with prefix
        return $matricule;
    }
    private function generateUniqueFacture()
    {
        do {
            // Generate a random 9-digit number
            $code = time().'_'.random_int(1000, 9999); // Generates a number between 100000000 and 999999999
        } while (facture::where('code', $code)->exists()); // Ensure uniqueness

        // Return matricule with prefix
        return $code;
    }
    private function formatWithPeriods($number) {
        return number_format($number, 0, '', '.');
    }
    private function generateUniqueMatriculeEmploye()
    {
        do {
            $matricule = random_int(100000, 999999);
        } while (DB::table('employes')->where('matricule', '=', 'P'.$matricule)->exists());

        return $matricule;
    }
    private function generateUniqueMatriculeMedecin()
    {
        do {
            $matricule = random_int(100000, 999999);
        } while (DB::table('medecin')->where('codemedecin', '=', 'MED'.$matricule)->exists());

        return $matricule;
    }
    private function generateUniqueMatriculeAssurance()
    {
        do {
            $matricule = random_int(100000, 999999);
        } while (DB::table('assurance')->where('codeassurance', '=', 'ASS'.$matricule)->exists());

        return $matricule;
    }
    private function generateUniqueMatriculePatient()
    {
        do {
            $matricule = random_int(100000, 999999);
        } while (DB::table('patient')->where('idenregistremetpatient', '=', 'P'.$matricule)->exists());

        return $matricule;
    }
    private function generateUniqueMatriculeDossierDC()
    {
        do {
            $matricule = random_int(100000, 999999);
        } while (DB::table('dossierpatient')->where('numdossier', '=', 'DC'.$matricule)->exists());

        return $matricule;
    }
    private function generateUniqueMatriculeSpecialite()
    {
        do {
            $matricule = random_int(100000, 999999);
        } while (DB::table('specialitemed')->where('codespecialitemed', '=', 'SP'.$matricule)->exists());

        return $matricule;
    }
    private function generateUniqueMatriculeCaisseEntrerSortie()
    {
        do {
            $matricule = random_int(100000, 999999);
        } while (DB::table('caisse')->where('nopiece', '=', $matricule)->exists());

        return $matricule;
    }
    private function generateUniqueMatriculeNumRecu()
    {
        do {
            // Génère une chaîne aléatoire de 6 caractères (majuscule, minuscule, chiffres)
            $matricule = Str::random(6);
        } while (DB::table('journal')->where('numrecu', '=', $matricule)->exists());

        return $matricule;
    }
    private function generateUniqueFactureCons()
    {
        do {
            $matricule = random_int(100000, 999999);
        } while (DB::table('consultation')->where('numfac', '=', 'FCE'.$matricule)->exists());

        return $matricule;
    }
    private function generateUniqueFactureSoinsAmbulatoire()
    {
        do {
            $matricule = random_int(100000, 999999);
        } while (DB::table('soins_medicaux')->where('numfac_soins', '=', 'FCS'.$matricule)->exists());

        return $matricule;
    }
    private function generateUniqueNumExamen()
    {
        do {
            $matricule = random_int(1000000, 9999999);
        } while (DB::table('examen')->where('numexam', '=', $matricule)->exists());

        return $matricule;
    }
    private function generateUniqueIdBiologie()
    {
        do {
            $matricule = random_int(1000000, 9999999);
        } while (DB::table('testlaboimagerie')->where('idtestlaboimagerie', '=', 'BIO-'.$matricule)->exists());

        return $matricule;
    }
    private function generateUniqueIdImagerie()
    {
        do {
            $matricule = random_int(1000000, 9999999);
        } while (DB::table('testlaboimagerie')->where('idtestlaboimagerie', '=', 'IMG-'.$matricule)->exists());

        return $matricule;
    }
    private function generateUniqueFactureExamen()
    {
        do {
            $matricule = random_int(1000000, 9999999);
        } while (DB::table('testlaboimagerie')->where('numfacbul', '=', 'FCB'.$matricule)->exists());

        return $matricule;
    }

























    public function societe_new(Request $request)
    {

        $verf = DB::table('societeassure')->where('nomsocieteassure', '=', $request->nom)->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        DB::beginTransaction();

            try {

                $societeInserted = DB::table('societeassure')->insert([
                    'nomsocieteassure' => $request->nom,
                    'codeassurance' => $request->codeassurance,
                    'codeassureur' => $request->assureur_id,
                ]);

                if ($societeInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table societeassure');
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }  
    }

    public function assurance_new(Request $request)
    {

        $verifications = [
            'nom' => $request->nom,
            'tel' => $request->tel,
            'email' => $request->email,
            'fax' => $request->fax ?? null,
        ];

        $Exist = DB::table('assurance')->where(function ($query) use ($verifications) {
            $query->where('libelleassurance', $verifications['nom'])
                    ->where('telassurance', $verifications['tel'])
                    ->where('emailassurance', $verifications['email'])
                    ->orWhere(function ($query) use ($verifications) {
                        if (!is_null($verifications['fax'])) {
                            $query->where('faxassurance', $verifications['fax']);
                        }
                    });
        })->first();

        if ($Exist) {
            if ($Exist->libelleassurance === $verifications['nom']) {
                return response()->json(['nom_existe' => true]);
            } elseif ($Exist->emailassurance === $verifications['email']) {
                return response()->json(['email_existe' => true]);
            } elseif ($Exist->telassurance === $verifications['tel']) {
                return response()->json(['tel_existe' => true]);
            } elseif (!is_null($verifications['fax']) && $Exist->faxassurance === $verifications['fax']) {
                return response()->json(['fax_existe' => true]);
            }
        }

        DB::beginTransaction();

            try {

                $matricule = $this->generateUniqueMatriculeAssurance();

                $assuranceInserted = DB::table('assurance')->insert([
                    'codeassurance' => 'ASS'.$matricule,
                    'libelleassurance' => $request->nom,
                    'telassurance' => $request->tel,
                    'faxassurance' => $request->fax,
                    'emailassurance' => $request->email,
                    'adrassurance' => $request->adresse,
                    'situationgeo' => $request->carte,
                    'description' => $request->desc,
                ]);

                if ($assuranceInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table assurance');
                }

                 // Valider la transaction
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function assureur_new(Request $request)
    {

        $verifications = [
            'nom' => $request->nom,
        ];

        $Exist = DB::table('assureur')->where(function ($query) use ($verifications) {
            $query->where('libelle_assureur', $verifications['nom']);
        })->first();

        if ($Exist) {
            if ($Exist->libelle_assureur === $verifications['nom']) {
                return response()->json(['nom_existe' => true]);
            }
        }

        DB::beginTransaction();

            try {

                $assureurInserted = DB::table('assureur')->insert([
                    'libelle_assureur' => $request->nom,
                ]);

                if ($assureurInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table assureur');
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function patient_new(Request $request)
    {

        DB::beginTransaction();

            try {

                $matricule = $this->generateUniqueMatriculePatient();

                if ($request->assurer === '0') {
                    $codeassurance = 'NONAS';
                    $codefiliation = 0;
                    $matriculeassure = null;
                    $codesocieteassure = 0;
                    $idtauxcouv = 0;
                } else if ($request->assurer === '1') {
                    $codeassurance = $request->assurance_id;
                    $codefiliation = $request->filiation;
                    $matriculeassure = $request->matricule_assurance;
                    $codesocieteassure = $request->societe_id;
                    $idtauxcouv = $request->taux_id;
                }

                $insertData_patient = [
                    'idenregistremetpatient' => 'P'.$matricule,
                    'idenregistrementhopital' => '1',
                    'numeroregistre' => '1',
                    'dateenregistrement' => now(),
                    'civilite' => '0',
                    'nompatient' => $request->nom,
                    'prenomspatient' => $request->prenom,
                    'nomprenomspatient' => $request->nom.' '.$request->prenom,
                    'datenaispatient' => $request->datenais,
                    'sexe' => $request->sexe,
                    'adressepatient' => null,
                    'assure' => $request->assurer,
                    'telpatient' => $request->tel,
                    'telpatient_2' => $request->tel2,
                    'telurgence_1' => $request->telu,
                    'telurgence_2' => $request->telu2,
                    'nomurgence' => $request->nomu,
                    'lieuderesidencepat' => $request->residence,
                    'codeassurance' => $codeassurance,
                    'codefiliation' => $codefiliation,
                    'matriculeassure' => $matriculeassure,
                    'codesocieteassure' => $codesocieteassure,
                    'idtauxcouv' => $idtauxcouv,
                ];

                $patientInserted = DB::table('patient')->insert($insertData_patient);

                if ($patientInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table patient');
                }

                $verf_dossierDC = DB::table('dossierpatient')
                ->where('idenregistremetpatient', '=', 'P'.$matricule)
                ->where('codetypedossier', '=', 'DC')
                ->exists();

                if (!$verf_dossierDC) {

                    $numdossier_new = $this->generateUniqueMatriculeDossierDC();

                    $dossierPatientInserted = DB::table('dossierpatient')->insert([
                        'numdossier' => 'DC'.$numdossier_new,
                        'idenregistremetpatient' => 'P'.$matricule,
                        'datecrea' => now(),
                        'codetypedossier' => 'DC',
                    ]);

                    if ($dossierPatientInserted === 0) {
                        throw new Exception('Erreur lors de l\'insertion dans la table dossierpatient');
                    }
                }

                
                DB::commit();
                return response()->json(['success' => true, 'id' => 'P'.$matricule]);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function chambre_new(Request $request)
    {
        $verf = DB::table('chambres')->where('code', '=', $request->num_chambre)->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $chambreInsert = DB::table('chambres')->insert([
            'code' => $request->num_chambre,
            'nbre_lit' => $request->nbre_lit,
            'prix' => str_replace('.', '', $request->prix),
            'statut' => 'indisponible',
            'created_at' => now(),
        ]);

        if ($chambreInsert == 1) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => true]);
    }

    public function lit_new(Request $request)
    {
        $verf = DB::table('lits')->where('code', '=', $request->num_lit)->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $nbre = DB::table('chambres')->where('id', '=', $request->chambre_id)->first();
        $count = DB::table('lits')->where('chambre_id', '=', $request->chambre_id)->count();

        if ($nbre->nbre_lit <= $count) {
            return response()->json(['nbre' => true]);
        }

        DB::beginTransaction();

        try {

            $litInsert = DB::table('lits')->insert([
                'code' => $request->num_lit,
                'type' => $request->type,
                'chambre_id' => $request->chambre_id,
                'statut' => 'disponible',
                'created_at' => now(),
            ]);

            if ($litInsert == 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table lits');
            }

            $updateData_chambre =[
                'statut' => 'disponible',
                'updated_at' => now(),
            ];

            $chambreUpdate = DB::table('chambres')
                                ->where('id', '=', $request->chambre_id)
                                ->update($updateData_chambre);

            if ($chambreUpdate == 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table chambres');
            }

            DB::commit();
            return response()->json(['success' => true]);
            
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true]);
        }
    }

    public function motif_cons_new(Request $request)
    {
        $verf = acte::where('nom', '=', $request->nom)->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $add = new acte();
        $add->nom = $request->nom;

        if($add->save()){
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => true]);
        }
    }

    public function typeacte_cons_new(Request $request)
    {
        $verf = typeacte::where('nom', '=', $request->nom)->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $add = new typeacte();
        $add->nom = $request->nom;
        $add->prix = $request->prix;
        $add->acte_id = $request->id;

        if($add->save()){
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => true]);
        }
    }

    public function new_medecin(Request $request)
    {
        $verifications = [
            'tel' => $request->tel ?? null,
            'email' => $request->email ?? null,
        ];

        $Exist = DB::table('medecin')->where(function ($query) use ($verifications) {
            $query->where('contact', $verifications['tel'])
                  ->orWhere(function ($query) use ($verifications) {
                      if (!is_null($verifications['email'])) {
                          $query->where('email', $verifications['email']);
                      }
                  });
        })->first();


        if ($Exist) {
            if ($Exist->tel === $verifications['tel']) {
                return response()->json(['tel_existe' => true]);
            } elseif ($Exist->email === $verifications['email']) {
                return response()->json(['email_existe' => true]);
            }
        }

        DB::beginTransaction();

            try {

                $matricule = $this->generateUniqueMatriculeMedecin();

                $specialite = DB::table('specialitemed')->where('codespecialitemed', '=', $request->specialite_id)->first();

                if (!$specialite) {
                    throw new Exception('Spécialité introuvable');
                }

                $medecinInserted = DB::table('medecin')->insert([
                    'codemedecin' => 'MED'.$matricule,
                    'titremed' => 'Dr',
                    'nommedecin' => $request->nom,
                    'prenomsmedecin' => $request->prenom,
                    'nomprenomsmed' => 'Dr '.$request->nom.' '.$request->prenom ,
                    'codespecialitemed' => $specialite->codespecialitemed,
                    'numordremed' => $request->num,
                    'contact' => $request->tel,
                    'dateservice' => $request->dateservice,
                    'email' => $request->email,
                ]);

                if ($medecinInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table employes');
                }

                 // Valider la transaction
                DB::commit();
                return response()->json(['success' => true]);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function new_consultation(Request $request)
    {

        DB::beginTransaction();

            try {

                $numfac = $this->generateUniqueFactureCons();

                $consultationInserted = DB::table('consultation')->insert([
                    'idenregistremetpatient' => $request->id_patient,
                    'numbon' => $request->mumcode,
                    'montant' => str_replace('.', '', $request->total),
                    'taux' => $request->patient_taux,
                    'ticketmod' => str_replace('.', '', $request->montant_patient),
                    'partassurance' => str_replace('.', '', $request->montant_assurance),
                    'codemedecin' => $request->user_id,
                    'codeacte' => $request->typeacte_id,
                    'regle' => 0,
                    'date' => now(),
                    'facimprime' => 0,
                    'numfac' => 'FCE'.$numfac,
                ]);

                if ($consultationInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table consultation');
                }

                // if ($request->appliq_remise == 'patient') {
                //     $type_remise = 1
                // }

                $facturesInserted = DB::table('factures')->insert([
                    'numfac' => 'FCE'.$numfac,
                    'idenregistremetpatient' => $request->id_patient,
                    'montanttotal' => str_replace('.', '', $request->total),
                    'remise' => str_replace('.', '', $request->taux_remise),
                    'type_remise' => 0,
                    'calcul_applique' => 0,
                    'taux_applique' => $request->patient_taux,
                    'montant_ass' => str_replace('.', '', $request->montant_assurance),
                    'montant_pat' => str_replace('.', '', $request->montant_patient),
                    'montantregle_ass' => 0,
                    'montantregle_pat' => 0,
                    'montantpat_verser' => 0,
                    'montantpat_remis' => 0,
                    'montantreste_ass' => str_replace('.', '', $request->montant_assurance),
                    'montantreste_pat' => str_replace('.', '', $request->montant_patient),
                    'solde_ass' => 0,
                    'solde_pat' => 0,
                    'codeassurance' => $request->codeassurance,
                    'datefacture' => now(),
                    'type_facture' => 1,
                    'timbre_fiscal' => 0,
                    'a_encaisser' => 0,
                ]);

                if ($facturesInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table factures');
                }

                // Valider la transaction
                DB::commit();
                return response()->json(['success' => true]);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function new_typeadmission(Request $request)
    {
        $verf = typeadmission::where('nom', '=', $request->nom)->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $add = new typeadmission();
        $add->nom = $request->nom;

        if($add->save()){
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => true]);
        }
    }

    public function new_natureadmission(Request $request)
    {
        $verf = natureadmission::where('nom', '=', $request->nom)->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $add = new natureadmission();
        $add->nom = $request->nom;
        $add->typeadmission_id = $request->id;

        if($add->save()){
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => true]);
        }
    }

    public function hosp_new(Request $request)
    {

        $patient = patient::leftjoin('assurances', 'assurances.id', '=', 'patients.assurance_id')
        ->where('patients.id', '=', $request->patient_id)
        ->select('patients.*', 'assurances.nom as assurance')
        ->first();

        if ($patient) {
            $patient->age = $patient->datenais ? Carbon::parse($patient->datenais)->age : 0;
        }

        if (!$patient) {
            return response()->json(['error' => true]);
        }

        $chambre = chambre::find($request->id_chambre);

        if (!$chambre) {
            return response()->json(['error' => true]);
        }

        $lit = lit::find($request->id_lit);

        if (!$lit) {
            return response()->json(['error' => true]);
        }

        $typeadmission = typeadmission::find($request->id_typeadmission);

        if (!$typeadmission) {
            return response()->json(['error' => true]);
        }

        $natureadmission = natureadmission::find($request->id_natureadmission);

        if (!$natureadmission) {
            return response()->json(['error' => true]);
        }
        
        $user = user::join('typemedecins', 'typemedecins.user_id', '=', 'users.id')
            ->join('typeactes', 'typeactes.id', '=', 'typemedecins.typeacte_id')
            ->where('users.id', '=', $request->medecin_id)
            ->select('users.*', 'typeactes.nom as typeacte')
            ->first();

        if (!$user) {
            return response()->json(['error' => true]);
        }

        $codeFac = $this->generateUniqueFacture();

        DB::beginTransaction();

        try {

            $fac = new facture();
            $fac->code = $codeFac;
            $fac->statut = 'impayer';
            $fac->acte = 'HOSPITALISATION';
            $fac->creer_id = $request->auth_id;

            if (!$fac->save()) {
                throw new \Exception('Erreur');
            }

            $add = new detailhopital();
            $add->statut = 'Hospitaliser';
            $add->num_bon = $request->numcode;
            $add->part_assurance = $request->montant_assurance;
            $add->part_patient = $request->montant_patient;
            $add->remise = $request->taux_remise;
            $add->montant = $request->montant_total;
            $add->montant_soins = '0';
            $add->montant_chambre = $request->montant_total;
            $add->date_debut = $request->date_entrer;
            $add->date_fin = $request->date_sortie;
            $add->natureadmission_id = $natureadmission->id;
            $add->facture_id = $fac->id;
            $add->patient_id = $patient->id;
            $add->lit_id = $lit->id;
            $add->user_id = $user->id;

            if (!$add->save()) {
                throw new \Exception('Erreur');
            }

            $lit->statut = 'indisponible';
            if (!$lit->save()) {
                throw new \Exception('Erreur');
            }

            $hopital = $add;
            $facture = $fac;

            DB::commit();
            return response()->json(['success' => true, 'patient' => $patient, 'chambre' => $chambre, 'user' => $user, 'hopital' => $hopital, 'lit' => $lit, 'typeadmission' => $typeadmission, 'natureadmission' => $natureadmission, 'facture' => $facture]);
            
        } catch (Exception $e) {

            DB::rollback();
            return response()->json(['error' => true]);
        }
    }

    public function new_produit(Request $request)
    {
        $verf = DB::table('medicine')->where('name', '=', $request->nom)->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $produitInserted = DB::table('medicine')->insert([
            'name' => $request->nom,
            'medicine_category_id' => $request->categorieid,
            'price' => str_replace('.', '', $request->prix),
            'status' => $request->quantite,
        ]);

        if ($produitInserted == 1) {
            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json(['error' => true]);
    }

    public function add_soinshopital(Request $request, $id)
    {

        $selections = $request->input('selections');

        // Vérifier si les sélections sont bien un tableau
        if (!is_array($selections) || empty($selections)) {
            return response()->json(['json' => true]);
        }

        $montantTotal = str_replace('.', '', $request->input('montantTotal'));

        DB::beginTransaction();

        try {

            foreach ($selections as $value) {

                $qu = produit::find($value['id']);
                if ($qu && $qu->quantite >= $value['quantite']) {
                    
                    $qu->quantite -= $value['quantite'];

                    if (!$qu->save()) {
                        throw new \Exception('Erreur lors de la mise à jour de la quantité du produit');
                    }

                }else{
                    throw new \Exception('Quantité insuffisante pour le produit : ' . $qu->nom);
                }

                $add = new soinshopital();
                $add->produit_id = $value['id'];
                $add->quantite = $value['quantite'];
                $add->montant = number_format($value['montant'], 0, ',', '.');
                $add->detailhopital_id = $id;

                if (!$add->save()) {
                    throw new \Exception('Erreur');
                }
            }

            $montantTotal = str_replace('.', '', $request->input('montantTotal'));

            $add2 = detailhopital::find($id);

            $montant_soins_ancien = str_replace('.', '', $add2->montant_soins);

            $montant_soins_nouveau = $montantTotal + $montant_soins_ancien;

            $formattedMontant = number_format($montant_soins_nouveau, 0, '', '.');

            $add2->montant_soins = $formattedMontant;

            $a_soins = str_replace('.', '', $add2->montant_soins);
            $a_chambre = str_replace('.', '', $add2->montant_chambre);

            $n_montant = $a_soins + $a_chambre;

            $add2->montant = number_format($n_montant, 0, '', '.');

            $rech_taux = patient::leftjoin('tauxes', 'tauxes.id', '=', 'patients.taux_id')
                            ->where('patients.id', '=', $add2->patient_id)
                            ->select(
                                'patients.*',
                                'tauxes.taux as taux',
                            )->first();

            if ($rech_taux->assurer === 'non') {
                $rech_taux->taux = 0;
            }

            $nMontant = str_replace('.', '', $add2->montant);

            $assurance = ($nMontant * $rech_taux->taux) / 100;

            $patient = $nMontant - $assurance;

            $formattedA = number_format($assurance, 0, '', '.');
            $formattedP = number_format($patient, 0, '', '.');

            $add2->part_assurance = $formattedA;
            $add2->part_patient = $formattedP;

            $remise = str_replace('.', '', $add2->remise);
            $r_patient = str_replace('.', '', $add2->part_patient);

            $nremise = $r_patient - $remise;

            $formattedr = number_format($nremise, 0, '', '.');

            $add2->part_patient = $formattedr;

            if (!$add2->save()) {
                throw new \Exception('Erreur lors de la mise à jour du montant total');
            }

            // Si tout s'est bien passé, on commit les changements
            DB::commit();

            return response()->json(['success' => true]);
            
        } catch (Exception $e) {

            DB::rollback();
            return response()->json(['error' => true]);
        }
    }

    public function new_typesoins(Request $request)
    {
        $verf = DB::table('typesoinsinfirmiers')->where('libelle_typesoins', '=', $request->nom)->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $typesoinsInserted = DB::table('typesoinsinfirmiers')->insert([
            'libelle_typesoins' => $request->nom,
        ]);

        if ($typesoinsInserted == 1) {
            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json(['error' => true]);
    }

    public function new_soinsIn(Request $request)
    {
        $verf = DB::table('soins_infirmier')->where('libelle_soins', '=', $request->nom)->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $soinsInserted = DB::table('soins_infirmier')->insert([
            'price' => str_replace('.', '', $request->prix),
            'libelle_soins' => $request->nom,
            'code_typesoins' => $request->typesoins,
        ]);

        if ($soinsInserted == 1) {
            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json(['error' => true]);
    }

    public function new_soinsam(Request $request)
    {

        $selectionsSoins = $request->input('selectionsSoins');
        if (!is_array($selectionsSoins) || empty($selectionsSoins)) {
            return response()->json(['json' => true]);
        }

        $selectionsProduits = $request->input('selectionsProduits');
        if (!is_array($selectionsProduits) || empty($selectionsProduits)) {
            return response()->json(['json' => true]);
        }

        $numfac = $this->generateUniqueFactureSoinsAmbulatoire();

        DB::beginTransaction();

        try {

            $taux = (str_replace('.', '', $request->montantAssurance) / str_replace('.', '', $request->montantTotal)) * 100;
            $tauxEntier = intval($taux);

            $soinsId = DB::table('soins_medicaux')->insertGetId([
                'idenregistremetpatient' => $request->patient_id,
                'taux_couverture' => $tauxEntier,
                'date_soin' => now(),
                'montant_total' => str_replace('.', '', $request->montantTotal),
                'ticket_moderateur' => str_replace('.', '', $request->montantPatient),
                'part_assurance' => str_replace('.', '', $request->montantAssurance),
                'numfac_soins' => 'FCS'.$numfac,
                'paid_status' => 0,
            ]);

            if (!$soinsId) {
                throw new Exception('Erreur lors de l\'insertion dans la table tarifs');
            }

            $patient = DB::table('patient')
                ->where('patient.idenregistremetpatient', '=', $request->patient_id)
                ->select('patient.codeassurance as codeassurance')
                ->first();

            $facturesInserted = DB::table('factures')->insert([
                'numfac' => 'FCS'.$numfac,
                'idenregistremetpatient' => $request->patient_id,
                'montanttotal' => str_replace('.', '', $request->montantTotal),
                'remise' => str_replace('.', '', $request->montantRemise),
                'type_remise' => 0,
                'calcul_applique' => 0,
                'taux_applique' => $tauxEntier,
                'montant_ass' => str_replace('.', '', $request->montantAssurance),
                'montant_pat' => str_replace('.', '', $request->montantPatient),
                'montantregle_ass' => 0,
                'montantregle_pat' => 0,
                'montantpat_verser' => 0,
                'montantpat_remis' => 0,
                'montantreste_ass' => str_replace('.', '', $request->montantAssurance),
                'montantreste_pat' => str_replace('.', '', $request->montantPatient),
                'solde_ass' => 0,
                'solde_pat' => 0,
                'codeassurance' => $patient->codeassurance,
                'datefacture' => now(),
                'type_facture' => 4,
                'timbre_fiscal' => 0,
                'a_encaisser' => 0,
            ]);

            if ($facturesInserted === 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table factures');
            }

            foreach ($selectionsSoins as $value) {

                $soins = DB::table('soins_infirmier')
                    ->where('code_soins','=',$value['id'])
                    ->select('soins_infirmier.*')
                    ->first();

                $soinsInsert = DB::table('soins_medicaux_itemsoins')->insert([
                    'id_soins' => $soinsId,
                    'code_soins' => $value['id'],
                    'qte' => 1,
                    'libelle_soins' => $soins->libelle_soins,
                    'price' => str_replace('.', '', $value['montant']),
                ]);

                if ($soinsInsert == 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table soins_medicaux_itemsoins');
                }

            }

            foreach ($selectionsProduits as $value) {

                $produit = DB::table('medicine')
                    ->where('medicine_id','=',$value['id'])
                    ->select('medicine.*')
                    ->first();

                $produitInsert = DB::table('soins_medicaux_itemmedics')->insert([
                    'id_soins' => $soinsId,
                    'medicine_id' => $value['id'],
                    'qte' => $value['quantite'],
                    'name' => $produit->name,
                    'price' => str_replace('.', '', $value['montant']),
                ]);

                if ($produitInsert == 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table soins_medicaux_itemmedics');
                }

                $qte_new = (int) $produit->status - (int) $value['quantite'];

                $updateData_produit =[
                    'status' => $qte_new,
                    'updated_at' => now(),
                ];

                $produitUpdate = DB::table('medicine')
                                    ->where('medicine_id', '=', $value['id'])
                                    ->update($updateData_produit);

                if ($produitUpdate == 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table medicine');
                }
            }

            DB::commit();
            return response()->json(['success' => true]);
            
        } catch (Exception $e) {

            DB::rollback();
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    public function examen_new(Request $request)
    {
        // Vérification de l'existence d'un enregistrement avec le même nom et acte_id
        $verf = typeacte::where('nom', '=', $request->nom)
                        ->where('acte_id', '=', $request->id)
                        ->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        // Ajouter un nouvel enregistrement si la combinaison (nom, acte_id) n'existe pas
        $add = new typeacte();
        $add->nom = $request->nom;
        $add->cotation = $request->cotation;
        $add->valeur = $request->valeur;
        $add->prix = $request->prix;
        $add->montant = $request->montant;
        $add->acte_id = $request->id;

        if ($add->save()) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => true]);
    }

    public function new_examend(Request $request)
    {

        Log::info($request->all());

        $selections = $request->input('selectionsExamen');
        if (!is_array($selections) || empty($selections)) {
            return response()->json(['json' => true]);
        }

        DB::beginTransaction();

        try {

            $taux = (str_replace('.', '', $request->montantA) / str_replace('.', '', $request->montantT)) * 100;
            $tauxEntier = intval($taux);

            $numfac = $this->generateUniqueFactureExamen();

            if ($request->acte_id == 'B') {
                $id = 'BIO-'.$this->generateUniqueIdBiologie();
                $type = 'analyse';
            } else {
                $id = 'IMG-'.$this->generateUniqueIdImagerie();
                $type = 'imagerie';
            }

            if (str_replace('.', '', $request->montantA) > 0) {
                $mode = 1;
            } else {
                $mode = 0;
            }

            $examenInsert = DB::table('testlaboimagerie')->insert([
                'idtestlaboimagerie' => $id,
                'idenregistremetpatient' => $request->patient_id,
                'codemedecin' => null,
                'typedemande' => $type,
                'renseigclini' => $request->rensg,
                'date' => now()->format('Y-m-d'),
                'heure' => now()->format('H:i:s'),
                'numfacbul' => 'FCB'.$numfac,
                'numbon' => $request->numcode,
                'medicin_traitant' => $request->medecin,
                'numhospit' => null,
                'prelevement' => str_replace('.', '', $request->montant_pre),
                'mode_patient' => $mode,
            ]);

            if ($examenInsert == 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table testlaboimagerie');
            }

            foreach ($selections as $value) {

                $detailInsert = DB::table('detailtestlaboimagerie')->insert([
                    'idtestlaboimagerie' => $id,
                    'numexam' => $value['id'],
                    'denomination' =>  $value['examen'],
                    'cotation' => $value['cotation'],
                    'resultat' => $value['accepte'],
                    'prix' => str_replace('.', '', $value['montant']),
                ]);

                if ($detailInsert == 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table detailtestlaboimagerie');
                }

            }

            $patient = DB::table('patient')
                ->where('patient.idenregistremetpatient', '=', $request->patient_id)
                ->select('patient.codeassurance as codeassurance')
                ->first();

            $facturesInserted = DB::table('factures')->insert([
                'numfac' => 'FCB'.$numfac,
                'idenregistremetpatient' => $request->patient_id,
                'montanttotal' => str_replace('.', '', $request->montantT),
                'remise' => 0,
                'type_remise' => 0,
                'calcul_applique' => 0,
                'taux_applique' => $tauxEntier,
                'montant_ass' => str_replace('.', '', $request->montantA),
                'montant_pat' => str_replace('.', '', $request->montantP),
                'montantregle_ass' => 0,
                'montantregle_pat' => 0,
                'montantpat_verser' => 0,
                'montantpat_remis' => 0,
                'montantreste_ass' => str_replace('.', '', $request->montantA),
                'montantreste_pat' => str_replace('.', '', $request->montantP),
                'solde_ass' => 0,
                'solde_pat' => 0,
                'codeassurance' => $patient->codeassurance,
                'datefacture' => now(),
                'type_facture' => 4,
                'timbre_fiscal' => 0,
                'a_encaisser' => 0,
            ]);

            if ($facturesInserted == 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table factures');
            }

            DB::commit();
            return response()->json(['success' => true]);
            
        } catch (Exception $e) {

            DB::rollback();
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    public function new_horaire(Request $request)
    {
        $selections = $request->input('selections');
        if (!is_array($selections) || empty($selections)) {
            return response()->json(['json' => true]);
        }

        $medecin = DB::table('medecin')->where('codemedecin', '=', $request->medecin_id)->first();

        if (!$medecin) {
            return response()->json(['error' => true]);
        }

        DB::beginTransaction();

        try {

            foreach ($selections as $value) {

                $horaireInserted = DB::table('programmemedecins')->insert([
                    'periode' => $value['periode'],
                    'statut' => 'oui',
                    'heure_debut' => $value['heure_debut'],
                    'heure_fin' => $value['heure_fin'],
                    'jour_id' => $value['jour_id'],
                    'codemedecin' => $medecin->codemedecin,
                    'created_at' => now(),
                ]);

                if ($horaireInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table programmemedecins');
                }

            }

            DB::commit();
            return response()->json(['success' => true]);
            
        } catch (Exception $e) {

            DB::rollback();
            return response()->json(['error' => true]);
        }
    }

    public function new_rdv(Request $request)
    {
        $medecin = DB::table('medecin')->where('codemedecin', '=', $request->medecin_id)->first();
        if (!$medecin) {
            return response()->json(['error' => true]);
        }

        $patient = DB::table('patient')->where('idenregistremetpatient', '=', $request->patient_id)->first();
        if (!$patient) {
            return response()->json(['error' => true]);
        }

        $verf = DB::table('rdvpatients')->where('patient_id', '=', $patient->idenregistremetpatient)
                        ->where('codemedecin', '=', $medecin->codemedecin)
                        ->where('date','=', $request->date)
                        ->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $rdvInserted = DB::table('rdvpatients')->insert([
            'codemedecin' => $medecin->codemedecin,
            'patient_id' => $patient->idenregistremetpatient,
            'date' => $request->date,
            'tel' => $request->tel,
            'motif' => $request->motif,
            'statut' => 'en attente',
            'created_at' => now(),
        ]);

        if ($rdvInserted == 1) {
            return response()->json([
                'success' => true,
                'tel' => $request->tel,
                'date' => $request->date
            ]);
        }

        return response()->json(['error' => true]);
    }

    public function specialite_new(Request $request)
    {

        $Exist = DB::table('specialitemed')
            ->where('nomspecialite', '=', $request->nom)
            ->orWhere('abrspecialite', '=', $request->abr)
            ->exists();

        if ($Exist) {
            return response()->json(['existe' => true,'message' => 'Cette spécialité existe déjà']);
        }

        DB::beginTransaction();

            try {

                $matricule = $this->generateUniqueMatriculeSpecialite();

                $specialiteInserted = DB::table('specialitemed')->insert([
                    'codespecialitemed' => 'SP'.$matricule,
                    'nomspecialite' => $request->nom,
                    'abrspecialite' => $request->abr,
                    'libellespecialite' => $request->nom,
                    'dateenregistre' => now(),
                ]);

                if ($specialiteInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table specialitemed');
                }

                 // Valider la transaction
                DB::commit();
                return response()->json(['success' => true]);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function new_depot_fac(Request $request)
    {
        $date1 = Carbon::createFromFormat('Y-m-d', $request->date1)->startOfDay();
        $date2 = Carbon::createFromFormat('Y-m-d', $request->date2)->endOfDay();

        $date_depot = $request->date_depot;
        $assurance_id = $request->assurance_id;

        $verf = depotfacture::join('assurances', 'assurances.id', 'depotfactures.assurance_id')
                ->where('depotfactures.assurance_id', '=', $assurance_id)
                ->where(function($query) use ($date1, $date2) {
                    $query->whereBetween(DB::raw('DATE(depotfactures.date1)'), [$date1, $date2])
                          ->orWhereBetween(DB::raw('DATE(depotfactures.date2)'), [$date1, $date2]);
                })
                ->exists();

        if ($verf)
        {
            return response()->json(['existe' => true]);
        }

        $societes = societe::all();

        $total_patient = 0;
        $total_assurance = 0;
        $total_montant = 0;

        foreach ($societes as $societe) {

            $fac_cons = consultation::join('patients', 'patients.id', '=', 'consultations.patient_id')
                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->join('societes', 'societes.id', '=', 'patients.societe_id')
                ->join('detailconsultations', 'detailconsultations.consultation_id', '=', 'consultations.id')
                ->where('patients.assurer', '=', 'oui')
                ->where('consultations.num_bon', '!=', null)
                ->whereBetween(DB::raw('DATE(detailconsultations.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $assurance_id)
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
                ->where('assurances.id', '=', $assurance_id)
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
                ->whereBetween(DB::raw('DATE(soinspatients.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $assurance_id)
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
                ->where('assurances.id', '=', $assurance_id)
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
            
        }

        if ($total_montant <= 0) {
            return response()->json(['montant_inferieur' => true]);
        }

        $add = new depotfacture();
        $add->assurance_id = $assurance_id;
        $add->date1 = $request->date1;
        $add->date2 = $request->date2;
        $add->date_depot = $date_depot;
        $add->statut = 'non';
        $add->creer_id = $request->auth_id;

        if ($add->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => true]);
        }
    }

    public function paiement_depot_fac(Request $request, $id)
    {
        $add = depotfacture::find($id);

        try {

            if (!$add) {
                return response()->json(['non_touve' => true]);
            }

            $add->date_payer = $request->date;
            $add->type_paiement = $request->type;
            $add->num_cheque = $request->cheque;
            $add->statut = 'oui';
            $add->encaisser_id = $request->auth_id;

            if (!$add->save()) {
                throw new \Exception('Erreur');
            }

            $assurance = assurance::find($add->assurance_id);

            $date1 = Carbon::createFromFormat('Y-m-d', $add->date1)->startOfDay();
            $date2 = Carbon::createFromFormat('Y-m-d', $add->date2)->endOfDay();

            $total_assurance = 0;

                $fac_cons = consultation::join('patients', 'patients.id', '=', 'consultations.patient_id')
                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->join('societes', 'societes.id', '=', 'patients.societe_id')
                ->join('detailconsultations', 'detailconsultations.consultation_id', '=', 'consultations.id')
                ->where('patients.assurer', '=', 'oui')
                ->where('consultations.num_bon', '!=', null)
                ->whereBetween(DB::raw('DATE(detailconsultations.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $assurance->id)
                ->select(
                    'detailconsultations.part_assurance as part_assurance',
                )
                ->get();

            foreach ($fac_cons as $value) {
                $total_assurance += intval(str_replace('.', '', $value->part_assurance));
            }

            $fac_exam = examen::join('patients', 'patients.id', '=', 'examens.patient_id')
                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->join('societes', 'societes.id', '=', 'patients.societe_id')
                ->where('patients.assurer', '=', 'oui')
                ->where('examens.num_bon', '!=', null)
                ->whereBetween(DB::raw('DATE(examens.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $assurance->id)
                ->select(
                    'examens.part_assurance as part_assurance',
                )
                ->get();

            foreach ($fac_exam as $value) {
                $total_assurance += intval(str_replace('.', '', $value->part_assurance));
            }

            $fac_soinsam = soinspatient::join('patients', 'patients.id', '=', 'soinspatients.patient_id')
                ->join('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->join('societes', 'societes.id', '=', 'patients.societe_id')
                ->where('patients.assurer', '=', 'oui')
                ->where('soinspatients.num_bon', '!=', null)
                ->whereBetween(DB::raw('DATE(soinspatients.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $assurance->id)
                ->select(
                    'soinspatients.part_assurance as part_assurance',
                )
                ->get();

            foreach ($fac_soinsam as $value) {
                $total_assurance += intval(str_replace('.', '', $value->part_assurance));
            }

            $fac_hopital = detailhopital::join('patients', 'patients.id', '=', 'detailhopitals.patient_id')
                ->leftjoin('assurances', 'assurances.id', '=', 'patients.assurance_id')
                ->leftjoin('societes', 'societes.id', '=', 'patients.societe_id')
                ->where('patients.assurer', '=', 'oui')
                ->where('detailhopitals.num_bon', '!=', null)
                ->whereBetween(DB::raw('DATE(detailhopitals.created_at)'), [$date1, $date2])
                ->where('assurances.id', '=', $assurance->id)
                ->select(
                    'detailhopitals.part_assurance as part_assurance',
                )
                ->get();

            foreach ($fac_hopital as $value) {
                $total_assurance += intval(str_replace('.', '', $value->part_assurance));
            }

            //-----------------------------------------------

                // $solde_caisse = caisse::find('1');

                // $solde_caisse_sans_point = str_replace('.', '', $solde_caisse->solde);
                // $part_patient_sans_point = $total_assurance;
                // $solde_apres = (int)$solde_caisse_sans_point + (int)$part_patient_sans_point;

                // $add_caisse = new historiquecaisse();
                // $add_caisse->motif = 'ENCAISSEMENT ASSURANCE';
                // $add_caisse->montant = $this->formatWithPeriods($total_assurance);
                // $add_caisse->libelle = 'Encaissment ASSURANCE '.$assurance->nom.' du '.$add->date1.' au '.$add->date2;
                // $add_caisse->solde_avant = $solde_caisse->solde;
                // $add_caisse->solde_apres = number_format($solde_apres, 0, '', '.');
                // $add_caisse->typemvt = 'Entrer de Caisse';
                // $add_caisse->creer_id = $request->auth_id;
                
                // if (!$add_caisse->save()) {
                //     throw new \Exception('Erreur');
                // }

                // $solde_caisse->solde = number_format($solde_apres, 0, '', '.');

                // if (!$solde_caisse->save()) {
                //     throw new \Exception('Erreur');
                // }

            //-----------------------------------------------

            DB::commit();
            return response()->json(['success' => true]);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true]);
        }
    }

    public function ope_caisse_new(Request $request)
    {

        $verf = DB::table('porte_caisses')->select('statut')->where('id', '=', 1)->first();

        if ($verf->statut === 'fermer') {
            return response()->json(['caisse_fermer' => true]);
        }

        DB::beginTransaction();

        try {

            $montant = DB::table('caisse')
                ->selectRaw("SUM(CASE WHEN type = 'entree' THEN montant ELSE -montant END) as solde")
                ->value('solde');

            // $montant_formate = number_format($montant, 0, ',', '.');

            $nopiece = $this->generateUniqueMatriculeCaisseEntrerSortie();

            $caisseInserted = DB::table('caisse')->insert([
                'nopiece' => $nopiece,
                'type' => $request->type_ope,
                'libelle' => $request->libelle_ope,
                'montant' => str_replace('.', '', $request->montant_ope),
                'dateop' => $request->date_ope,
                'datecreat' => now(),
                'login' => $request->login,
                'beneficiaire' => $request->bene_ope,
                'annule' => 0,
                'mail' => 0,
            ]);

            if ($caisseInserted === 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table caisse');
            }

            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            $numfac = substr(str_shuffle($characters), 0, 3);

            $num = $this->generateUniqueMatriculeNumRecu();

            if ($request->type_ope === 'entree') {
                $type_action = 0;
            } else if ($request->type_ope === 'sortie') {
                $type_action = 1;
            }

            $journalInserted = DB::table('journal')->insert([
                'idenregistremetpatient' => "Fac N°".$numfac,
                'date' => now(),
                'numrecu' => $num,
                'montant_recu' => str_replace('.', '', $request->montant_ope),
                'numjournal' => $numfac,
                'type_action' => $type_action,
            ]);

            if ($journalInserted === 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table journal');
            }

            $solde = $montant + str_replace('.', '', $request->montant_ope);

            $soldecaisseUpdated = DB::table('porte_caisses')
                ->where('id', '=', 1)
                ->update([
                    'montant' => $solde,
                    'updated_at' => now(),
                ]);

            if ($soldecaisseUpdated === 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table porte_caisses');
            }

            DB::commit();
            return response()->json(['success' => true]);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true,'message' => $e->getMessage()]);
        }
    }

    public function new_user(Request $request)
    {
        $verifications = [
            'tel' => $request->tel,
            'tel2' => $request->tel2,
            'email' => $request->email ?? null,
        ];

        $Exist = DB::table('employes')->where(function ($query) use ($verifications) {
            $query->where('cel', $verifications['tel'])
                  ->orWhere(function ($query) use ($verifications) {
                      if (!is_null($verifications['tel2'])) {
                          $query->where('contacturgence', $verifications['tel2']);
                      }
                  })
                  ->orWhere(function ($query) use ($verifications) {
                      if (!is_null($verifications['email'])) {
                          $query->where('email', $verifications['email']);
                      }
                  });
        })->first();


        if ($Exist) {
            if ($Exist->tel === $verifications['tel'] || (!is_null($verifications['tel2']) && $Exist->tel2 === $verifications['tel2'])) {
                return response()->json(['tel_existe' => true]);
            } elseif ($Exist->email === $verifications['email']) {
                return response()->json(['email_existe' => true]);
            }
        }

        DB::beginTransaction();

            try {

                $matricule = $this->generateUniqueMatriculeEmploye();

                $profil = DB::table('profile')->where('idprofile', '=', $request->profil_id)->first();
                $service = DB::table('service')->where('code', '=', $request->service_id)->first();

                if (!$profil || !$service) {
                    throw new Exception('Profil ou Service introuvable');
                }

                $employeInserted = DB::table('employes')->insert([
                    'matricule' => 'P'.$matricule,
                    'typepiece' => $request->typepiece,
                    'nopiece' => null,
                    'civilite' => $request->civilite,
                    'nom' => $request->nom,
                    'prenom' => $request->prenom,
                    'nomprenom' => $request->nom.' '.$request->prenom,
                    'datenais' => $request->datenais,
                    'profession' => $service->libelle,
                    'niveau' => $request->niveau,
                    'diplome' => $request->diplome,
                    'residence' => $request->residence,
                    'dateenregistre' => now()->format('Y-m-d'),
                    'cel' => $request->tel,
                    'contacturgence' => $request->tel2,
                    'email' => $request->email,
                    'service' => $request->service_id,
                    'typecontrat' => $request->contrat_id,
                    'datecontrat' => $request->date_debut,
                    'datefincontrat' => $request->date_fin,
                    'paye' => '0',

                ]);

                log::info($employeInserted);

                if ($employeInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table employes');
                }

                $userInserted = DB::table('users')->insert([
                    'api_token' => Null, 
                    'login' => $request->login,  //Pour le login
                    'user_first_name' => $request->nom,
                    'user_last_name' => $request->prenom,
                    'tel' => $request->tel,
                    'user_profil_id' => $request->profil_id,
                    'email' => $request->email,
                    'password' => password_hash($request->password, PASSWORD_BCRYPT),
                    'user_rights' => Null,
                    'user_make_date' => Null,
                    'user_revised_date' => Null,
                    'user_ip' => Null,
                    'user_history' => Null,
                    'user_logs' => Null,
                    'user_lang' => Null,
                    'user_photo' => Null,
                    'user_actif' => Null,
                    'user_actions' => Null,
                    'code_personnel' => 'P'.$matricule,
                    'photo' => Null,
                ]);

                if (!$userInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table users');
                }

                 // Valider la transaction
                DB::commit();
                return response()->json(['success' => true]);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function caisse_ouvert(Request $request)
    {
        DB::beginTransaction();

        try {

            $updateData_staut_caisse =[
                'statut' => 'ouvert',
            ];

            $statut_caisseUpdate = DB::table('porte_caisses')
                                ->where('id', '=', 1)
                                ->update($updateData_staut_caisse);

            if ($statut_caisseUpdate === 0) {
                throw new Exception('Erreur lors de la mise à jour dans la table porte_caisses');
            }

            $montant = DB::table('caisse')
                ->selectRaw("SUM(CASE WHEN type = 'entree' THEN montant ELSE -montant END) as solde")
                ->value('solde');

            $montant_formate = number_format($montant, 0, ',', '.');

            $op_caisseInserted = DB::table('caisse_resume')->insert([
                'datecaisse' => now(),
                'mtcaisse' => $montant,
                'action' => 0,
                'user' => $request->login,
                'heurecaisse' => date('H:i:s'),
            ]);

            if ($op_caisseInserted === 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table caisse_resume');
            }

            try {
               
                $mail = new PHPMailer(true);
                $mail->isHTML(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = env('MAIL_USERNAME');
                $mail->Password = env('MAIL_PASSWORD');
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                $mail->setFrom('emslp24@gmail.com', 'ESPACE MEDICO-SOCIAL LA PYRAMIDE');
                $mail->addAddress('davidkouachi01@gmail.com');
                $mail->Subject = 'ALERT !';
                $mail->Body = 'OUVERTURE DE LA CAISSE, Solde caisse : '. $montant_formate .' Fcfa';
                $mail->send();

            } catch (Exception $e) {
                
            }

            DB::commit();
            return response()->json(['success' => true]);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true,'message' => $e->getMessage()]);
        }
    }

    public function caisse_fermer(Request $request)
    {
        DB::beginTransaction();

        try {

            $updateData_staut_caisse =[
                'statut' => 'fermer',
            ];

            $statut_caisseUpdate = DB::table('porte_caisses')
                                ->where('id', '=', 1)
                                ->update($updateData_staut_caisse);

            if ($statut_caisseUpdate === 0) {
                throw new Exception('Erreur lors de la mise à jour dans la table porte_caisses');
            }

            $today = Carbon::today();
            $total = 0;
            $entries = 0;
            $exits = 0;

            $transactions = DB::table('caisse')->where('mail', '=', 0)->get();
            
            foreach ($transactions as $value) {
                if ($value->type === 'entree') {
                    $total += str_replace('.', '', $value->montant);
                    $entries += str_replace('.', '', $value->montant);
                } else {
                    $total -= str_replace('.', '', $value->montant);
                    $exits += str_replace('.', '', $value->montant);
                }
            }

            try {

                $mail = new PHPMailer(true);
                $mail->isHTML(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = env('MAIL_USERNAME');
                $mail->Password = env('MAIL_PASSWORD');
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                $mail->setFrom('emslp24@gmail.com', 'ESPACE MEDICO-SOCIAL LA PYRAMIDE');
                $mail->addAddress('davidkouachi01@gmail.com');
                $mail->Subject = 'ALERT !';

                $currentDateTime = Carbon::now()->format('d/m/Y');
                $totalFormatted = number_format($total, 0, ',', '.');
                $entriesFormatted = number_format($entries, 0, ',', '.');
                $exitsFormatted = number_format($exits, 0, ',', '.');

                $tableRows ="";

                foreach ($transactions as $transaction) {

                    $color = ($transaction->type === 'entree') ? 'green' : 'red';
                    $montant = number_format($transaction->montant, 0, '.', '.');

                    $montantFormatted = $transaction->type === 'entree' ? "+ {$montant} Fcfa" : "- {$montant} Fcfa";

                    $entryCell = $transaction->type === 'entree' ? "<td style='color: {$color};'>{$montantFormatted}</td><td></td>" : "<td></td><td style='color: {$color};'>{$montantFormatted}</td>";

                    $tableRows .= "<tr>
                        <td>{$transaction->libelle}</td>
                        {$entryCell}
                    </tr>";
                }

                $totaux ="
                    <tr>
                        <th>TOTAUX</th>
                        <th style='color:green;' >+ {$entriesFormatted} Fcfa</th>
                        <th style='color:red;' >- {$exitsFormatted} Fcfa</th>
                    </tr>
                ";

                $bilan = '
                    <th style="padding: 10px; text-align: center;">BILAN DES OPERATIONS</th>
                    <th colspan="2" style="padding: 10px; text-align: center;">' . $totalFormatted . ' Fcfa</th>
                ';

                $mail->Body = "
                    <h2>Fermeture de la caisse du {$currentDateTime} : {$totalFormatted} Fcfa</h2>
                    <h3>Ci-dessous toutes les opérations de la journée</h3>
                    <table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>
                        <thead style='background-color: #116aef; color: white;'>
                            <tr>
                                <th style='padding: 10px; text-align: center;'>OPERATION</th>
                                <th style='padding: 10px; text-align: center;'>ENTREES</th>
                                <th style='padding: 10px; text-align: center;'>SORTIES</th>
                            </tr>
                        </thead>
                        <tbody>
                            {$tableRows}
                        </tbody>
                        <tfoot>
                            {$totaux}
                            <tr style='background-color: #116aef; color: white;'>
                                {$bilan}
                            </tr>
                        </tfoot>
                    </table>
                ";

                $mail->send();

            } catch (Exception $e) {
                
            }

            $updateData_mail_envoyer =[
                'mail' => 1,
                'updated_at' => now(),
            ];

            $mail_envoyerUpdate = DB::table('caisse')
                                ->where('mail', '=', 0)
                                ->update($updateData_mail_envoyer);

            // if ($mail_envoyerUpdate === 0) {
            //     throw new Exception('Erreur lors de la mise à jour dans la table porte_caisses');
            // }

            $montant = DB::table('caisse')
                ->selectRaw("SUM(CASE WHEN type = 'entree' THEN montant ELSE -montant END) as solde")
                ->value('solde');

            $op_caisseInserted = DB::table('caisse_resume')->insert([
                'datecaisse' => date('Y-m-d'),
                'mtcaisse' => $montant,
                'action' => 2,
                'user' => $request->login,
                'heurecaisse' => date('H:i:s'),
            ]);

            if ($op_caisseInserted === 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table caisse_resume');
            }

            DB::commit();
            return response()->json(['success' => true]);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true,'message' => $e->getMessage()]);
        }
    }

    public function update_date_hos(Request $request, $id)
    {
        $add = detailhopital::find($id);

        $nbre_j_ancien = floor(Carbon::parse($add->date_debut)->diffInRealDays($add->date_fin));
        $montant_chambre = str_replace('.', '', $add->montant_chambre);
        $prix_chambre = ((int)$montant_chambre / (int)$nbre_j_ancien);

        $nbre_j_new = floor(Carbon::parse($request->date1)->diffInRealDays($request->date2));
        $montant_chambre_new = ((int)$prix_chambre * (int)$nbre_j_new);

        $add->date_debut = $request->date1;
        $add->date_fin = $request->date2;
        $add->montant_chambre = $this->formatWithPeriods($montant_chambre_new);

        if($add->save()){
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => true]);
        }
    }

    public function type_garantie_new(Request $request)
    {
        $Exist = DB::table('typgarantie')
            ->where('codtypgar', '=', $request->code)
            ->orWhere('libtypgar', '=', $request->nom)
            ->exists();

        if ($Exist) {
            return response()->json(['existe' => true,'message' => 'le Code ou le Type existe dèjà']);
        }

        DB::beginTransaction();

            try {

                $typegarantieInserted = DB::table('typgarantie')->insert([
                    'codtypgar' => $request->code,
                    'libtypgar' => $request->nom,
                ]);

                if ($typegarantieInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table typgarantie');
                }

                 // Valider la transaction
                DB::commit();
                return response()->json(['success' => true]);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function garantie_new(Request $request)
    {
        $Exist = DB::table('garantie')
            ->where('codgaran', '=', $request->code)
            ->orWhere('libgaran', '=', $request->nom)
            ->exists();

        if ($Exist) {
            return response()->json(['existe' => true,'message' => 'le Code ou cette garantie existe dèjà']);
        }

        DB::beginTransaction();

            try {

                $numExamen = $this->generateUniqueNumExamen();

                if ($request->code_type == 'EXAM') {

                    if ($request->type_examen == 'Y') {

                        $examnYInserted = DB::table('examen')->insert([
                            'numexam' => 'Y'.$numExamen,
                            'cot' => 0,
                            'denomination' => $request->nom,
                            'codgaran' => $request->code,
                            'codfamexam' => 'Y',
                            'fam_acte_bio' => null,
                            'prix' => 0,
                        ]);

                        if ($examnYInserted === 0) {
                            throw new Exception('Erreur lors de l\'insertion dans la table examenY');
                        }

                    } else if ($request->type_examen == 'Z' || $request->type_examen == 'B') {

                        if ($request->type_examen == 'Z') {
                            $num = 'Z'.$numExamen;
                            $cod = 'Z';
                        } else if ($request->type_examen == 'B') {
                            $num = 'B'.$numExamen;
                            $cod = 'B';
                        }
                        
                        $examnZBInserted = DB::table('examen')->insert([
                            'numexam' => $num,
                            'cot' => $request->cotation,
                            'denomination' => $request->nom,
                            'codgaran' => null,
                            'codfamexam' => $cod,
                            'fam_acte_bio' => null,
                            'prix' => 0,
                        ]);

                        if ($examnZBInserted === 0) {
                            throw new Exception('Erreur lors de l\'insertion dans la table examenY');
                        }
                    }
                }

                $garantieInserted = DB::table('garantie')->insert([
                    'codgaran' => $request->code,
                    'libgaran' => $request->nom,
                    'codtypgar' => $request->code_type,
                ]);

                if ($garantieInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table garantie');
                }

                 // Valider la transaction
                DB::commit();
                return response()->json(['success' => true]);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function tarif_new(Request $request)
    {

        if ($request->assurer == 0) {
            $codeassurance = 'NONAS';
        } else {
            $codeassurance = $request->assurance;
        }

        $Exist = DB::table('tarifs')
            ->where('codgaran', '=', $request->garantie)
            ->Where('codeassurance', '=', $codeassurance)
            ->exists();

        if ($Exist) {
            return response()->json(['existe' => true,'message' => 'le tarif de cette garantie existe dèjà']);
        }

        DB::beginTransaction();

            try {

                $tarifInserted = DB::table('tarifs')->insert([
                    'codgaran' => $request->garantie,
                    'montjour' => str_replace('.', '', $request->prixj),
                    'montnuit' => str_replace('.', '', $request->prixn),
                    'montferie' => str_replace('.', '', $request->prixf),
                    'codeassurance' => $codeassurance,
                ]);

                if ($tarifInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table tarifs');
                }

                DB::commit();
                return response()->json(['success' => true]);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

}
