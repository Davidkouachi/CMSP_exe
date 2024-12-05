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
        $verifications = [
            'tel' => $request->tel,
            'tel2' => $request->tel2 ?? null, // Allow tel2 to be null
            'email' => $request->email ?? null,
            'nom' => $request->nom,
        ];

        $patientExist = patient::where(function($query) use ($verifications) {
            $query->where('tel', $verifications['tel'])
                  ->orWhere(function($query) use ($verifications) {
                      if (!is_null($verifications['tel2'])) {
                          $query->where('tel2', $verifications['tel2']);
                      }
                  })
                  ->orWhere(function($query) use ($verifications) {
                      if (!is_null($verifications['email'])) {
                          $query->where('email', $verifications['email']);
                      }
                  })
                  ->orWhere(function($query) use ($verifications) {
                      if (!is_null($verifications['nom'])) {
                          $query->where('np', $verifications['nom']);
                      }
                  });
        })->first();

        if ($patientExist) {
            if ($patientExist->tel === $verifications['tel'] || (!is_null($verifications['tel2']) && $patientExist->tel2 === $verifications['tel2'])) {
                return response()->json(['tel_existe' => true]);
            } elseif ($patientExist->email === $verifications['email']) {
                return response()->json(['email_existe' => true]);
            } elseif ($patientExist->nom === $verifications['nom']) {
                return response()->json(['nom_existe' => true]);
            }
        }

        // Generate a unique matricule
        $matricule = $this->generateUniqueMatricule();
        // Create a new record with the unique matricule
        $add = new patient();
        $add->matricule = $matricule;
        $add->np = $request->nom;
        $add->email = $request->email;
        $add->tel = $request->tel;
        $add->tel2 = $request->tel2;
        $add->assurer = $request->assurer;
        $add->adresse = $request->adresse;
        $add->datenais = $request->datenais;
        $add->sexe = $request->sexe;

        if($request->assurer === 'oui'){
            $add->assurance_id = $request->assurance_id;
            $add->taux_id = $request->taux_id;
            $add->societe_id = $request->societe_id;
            $add->filiation = $request->filiation;
            $add->matricule_assurance = $request->matricule_assurance;
        }

        if($add->save()){
            return response()->json(['success' => true, 'id' => $add->id, 'name' => $add->np]);
        } else {
            return response()->json(['error' => true]);
        }
    }

    public function chambre_new(Request $request)
    {
        $verf = chambre::where('code', '=', $request->num_chambre)->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $add = new chambre();
        $add->code = $request->num_chambre;
        $add->nbre_lit = $request->nbre_lit;
        $add->prix = $request->prix;
        $add->statut = 'indisponible';

        if($add->save()){
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => true]);
        }
    }

    public function lit_new(Request $request)
    {
        $verf = lit::where('code', '=', $request->num_lit)->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $nbre = chambre::find($request->chambre_id);
        $count = lit::where('chambre_id', '=', $request->chambre_id)->count();

        if ($nbre->nbre_lit <= $count) {
            return response()->json(['nbre' => true]);
        }
        
        $add = new lit();
        $add->code = $request->num_lit;
        $add->type = $request->type;
        $add->chambre_id = $request->chambre_id;
        $add->statut = 'disponible';

        DB::beginTransaction();

        try {

            if (!$add->save()) {
                return response()->json(['error' => true]);
            }

            $add2 = chambre::find($request->chambre_id);
            $add2->statut = 'disponible';

            if (!$add2->save()) {
                return response()->json(['error' => true]);
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

        $patient = patient::leftjoin('assurances', 'assurances.id', '=', 'patients.assurance_id')
        ->where('patients.id', '=', $request->id_patient)
        ->select('patients.*', 'assurances.nom as assurance')
        ->first();

        if ($patient) {
            $patient->age = $patient->datenais ? Carbon::parse($patient->datenais)->age : 0;
        }

        if (!$patient) {
            return response()->json(['error' => true]);
        }

        $typeacte = typeacte::find($request->typeacte_id);

        if (!$typeacte) {
            return response()->json(['error' => true]);
        }

        $acte = acte::find($request->acte_id);

        if (!$acte) {
            return response()->json(['error' => true]);
        }

        $user = user::find($request->user_id);

        if (!$user) {
            return response()->json(['error' => true]);
        }

        $code = $this->generateUniqueMatricule();

        $codeFac = $this->generateUniqueFacture();

        $today = Carbon::today();

        DB::beginTransaction();

        try {

            $fac = new facture();
            $fac->code = $codeFac;
            $fac->statut = 'impayer';
            $fac->acte = 'CONSULTATION';
            $fac->creer_id = $request->auth_id;

            if (!$fac->save()) {
                throw new \Exception('Erreur');
            }

            $add = new consultation();
            $add->patient_id = $patient->id; 
            $add->user_id = $user->id;
            $add->facture_id = $fac->id; 
            $add->matricule_patient = $patient->matricule;
            $add->code = $code;
            $add->num_bon = $request->mumcode;

            if ($patient->assurer === 'non') {
                $add->assurance_utiliser = 'non';
            }else{
                $add->assurance_utiliser = $request->assurance_utiliser;
            }

            if (!$add->save()) {
                throw new \Exception('Erreur');
            }

            $add2 = new detailconsultation();
            $add2->consultation_id = $add->id;
            $add2->typeacte_id = $typeacte->id;
            $add2->part_assurance = $request->montant_assurance;
            $add2->part_patient = $request->montant_patient;
            $add2->remise = $request->taux_remise;
            $add2->motif = $acte->nom;
            $add2->montant = $typeacte->prix;
            $add2->type_motif = $typeacte->nom;
            $add2->libelle = '';
            $add2->periode = $request->periode;
            $add2->appliq_remise = $request->appliq_remise;

            if (!$add2->save()) {
                throw new \Exception('Erreur');
            }

            DB::commit();
            return response()->json(['success' => true,'patient' => $patient,'typeacte' => $typeacte,'user' => $user,'consultation' => $add]);
            
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true]);
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
        $verf = produit::where('nom', '=', $request->nom)->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $add = new produit();
        $add->nom = $request->nom;
        $add->prix = $request->prix;
        $add->quantite = $request->quantite;

        if($add->save()){
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => true]);
        }
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
        $verf = typesoins::where('nom', '=', $request->nom)->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $add = new typesoins();
        $add->nom = $request->nom;

        if($add->save()){
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => true]);
        }
    }

    public function new_soinsIn(Request $request)
    {
        $add = new soinsinfirmier();

        $add->nom = $request->nom_soins;
        $add->prix = $request->prix;
        $add->typesoins_id = $request->typesoins_id;

        if ($add->save()) {
            return response()->json(['success' => true]);
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

        $typesoins = typesoins::find($request->typesoins_id);

        if (!$typesoins) {
            return response()->json(['error' => true]);
        }

        $code = $this->generateUniqueMatricule();

        $codeFac = $this->generateUniqueFacture();

        DB::beginTransaction();

        try {

            $fac = new facture();
            $fac->code = $codeFac;
            $fac->statut = 'impayer';
            $fac->acte = 'SOINS AMBULATOIRE';
            $fac->creer_id = $request->auth_id;

            if (!$fac->save()) {
                throw new \Exception('Erreur');
            }

            if ($request->numcode != null) {
                $verf = soinspatient::where('num_bon', '=', $request->numcode)->exists();

                if ($verf) {
                    return response()->json(['existe' => true]);
                }
            }

            $add = new soinspatient();
            $add->code = $code;
            $add->statut = 'en cours';
            $add->num_bon = $request->numcode;
            $add->part_patient = $request->montantPatient;
            $add->part_assurance = $request->montantAssurance;
            $add->remise = $request->montantRemise;
            $add->montant = $request->montantTotal;
            $add->libelle = '';
            $add->facture_id = $fac->id;
            $add->patient_id = $patient->id;
            $add->typesoins_id = $typesoins->id;

            if ($patient->assurer === 'non') {
                $add->assurance_utiliser = 'non';
            }else{
                $add->assurance_utiliser = $request->assurance_utiliser;
            }

            if (!$add->save()) {
                throw new \Exception('Erreur lors de la creation du soins patient');
            }

            foreach ($selectionsSoins as $value) {

                $adds = new sp_soins();
                $adds->soinsinfirmier_id = $value['id'];
                $adds->montant = number_format($value['montant'], 0, ',', '.');
                $adds->soinspatient_id = $add->id;

                if (!$adds->save()) {
                    throw new \Exception('Erreur');
                }
            }

            foreach ($selectionsProduits as $value) {

                $qu = produit::find($value['id']);
                if ($qu && $qu->quantite >= $value['quantite']) {
                    
                    $qu->quantite -= $value['quantite'];

                    if (!$qu->save()) {
                        throw new \Exception('Erreur lors de la mise à jour de la quantité du produit');
                    }

                }else{
                    throw new \Exception('Quantité insuffisante pour le produit : ' . $qu->nom);
                }

                $addp = new sp_produit();
                $addp->produit_id = $value['id'];
                $addp->quantite = $value['quantite'];
                $addp->montant = number_format($value['montant'], 0, ',', '.');
                $addp->soinspatient_id = $add->id;

                if (!$addp->save()) {
                    throw new \Exception('Erreur');
                }
            }

            DB::commit();
            return response()->json(['success' => true]);
            
        } catch (Exception $e) {

            DB::rollback();
            return response()->json(['error' => true]);
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
        $selections = $request->input('selectionsExamen');
        if (!is_array($selections) || empty($selections)) {
            return response()->json(['json' => true]);
        }

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

        $acte = acte::find($request->acte_id);

        if (!$acte) {
            return response()->json(['error' => true]);
        }

        $code = $this->generateUniqueMatricule();

        $codeFac = $this->generateUniqueFacture();

        DB::beginTransaction();

        try {

            $fac = new facture();
            $fac->code = $codeFac;
            $fac->statut = 'impayer';
            $fac->acte = 'EXAMEN';
            $fac->creer_id = $request->auth_id;

            if (!$fac->save()) {
                throw new \Exception('Erreur');
            }

            if ($request->numcode != null) {
                $verf = examen::where('num_bon', '=', $request->numcode)->exists();

                if ($verf) {
                    return response()->json(['existe' => true]);
                }
            }

            $add = new examen();
            $add->code = $code;
            $add->statut = 'en cours';
            $add->num_bon = $request->numcode;
            $add->part_patient = $request->montantP;
            $add->part_assurance = $request->montantA;
            $add->montant = $request->montantT;
            $add->libelle = '';
            $add->facture_id = $fac->id;
            $add->patient_id = $patient->id;
            $add->acte_id = $acte->id;
            $add->medecin = $request->medecin;
            $add->prelevement = $request->montant_pre;

            if (!$add->save()) {
                throw new \Exception('Erreur');
            }

            foreach ($selections as $value) {

                $adds = new examenpatient();
                $adds->typeacte_id = $value['id'];
                $adds->accepte = $value['accepte'];
                $adds->examen_id = $add->id;

                if (!$adds->save()) {
                    throw new \Exception('Erreur');
                }
            }

            DB::commit();
            return response()->json(['success' => true]);
            
        } catch (Exception $e) {

            DB::rollback();
            return response()->json(['error' => true]);
        }
    }

    public function new_horaire(Request $request)
    {
        $selections = $request->input('selections');
        if (!is_array($selections) || empty($selections)) {
            return response()->json(['json' => true]);
        }

        $user = user::find($request->medecin_id);

        if (!$user) {
            return response()->json(['error' => true]);
        }

        DB::beginTransaction();

        try {

            foreach ($selections as $value) {

                $adds = new programmemedecin();
                $adds->periode = $value['periode'];
                $adds->statut = 'oui';
                $adds->heure_debut = $value['heure_debut'];
                $adds->heure_fin = $value['heure_fin'];
                $adds->jour_id = $value['jour_id'];
                $adds->user_id = $user->id;

                if (!$adds->save()) {
                    throw new \Exception('Erreur');
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
        $user = user::find($request->medecin_id);
        if (!$user) {
            return response()->json(['error' => true]);
        }

        $patient = patient::find($request->patient_id);
        if (!$patient) {
            return response()->json(['error' => true]);
        }

        $verf = rdvpatient::where('patient_id', '=', $patient->id)
                        ->where('user_id', '=', $user->id)
                        ->where('date','=', $request->date)
                        ->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $add = new rdvpatient();
        $add->user_id = $user->id;
        $add->patient_id = $patient->id;
        $add->date = $request->date;
        $add->motif = $request->motif;
        $add->statut = 'en attente';

        if ($add->save()) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => true]);
    }

    public function specialite_new(Request $request)
    {
        $verf = typeacte::where('nom', '=', $request->nom)->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $acte = acte::where('nom', '=', 'CONSULTATION')->first();

        $add = new typeacte();
        $add->nom = $request->nom;
        $add->prix = $request->prix;
        $add->acte_id = $acte->id;

        if($add->save()){
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => true]);
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
        $solde_caisse = caisse::find('1');

        if ($solde_caisse->statut === 'fermer') {
            return response()->json(['caisse_fermer' => true]);
        }

        $solde_caisse_sans_point = str_replace('.', '', $solde_caisse->solde);
        $montant = str_replace('.', '', $request->montant_ope);

        if ($request->type_ope === 'entrer') {
            $solde_apres = (int)$solde_caisse_sans_point + (int)$montant;
        }else if ($request->type_ope === 'sortie') {
            $solde_apres = (int)$solde_caisse_sans_point - (int)$montant;
        }

        if ($solde_apres < 0) {
            return response()->json(['solde_negatif' => true]);
        }

        $add_caisse = new historiquecaisse();
        $add_caisse->motif = $request->libelle_ope;
        $add_caisse->montant = $this->formatWithPeriods($montant);

        if ($request->type_ope === 'entrer') {
            $add_caisse->libelle = $request->libelle_ope.'. Montant de l\'opération a été apporter par '.$request->nom_ope;
        }else if ($request->type_ope === 'sortie') {
            $add_caisse->libelle = $request->libelle_ope.'. Montant de l\'opération a été remis à '.$request->nom_ope;
        }
        
        $add_caisse->solde_avant = $solde_caisse->solde;
        $add_caisse->solde_apres = number_format($solde_apres, 0, '', '.');

        if ($request->type_ope === 'entrer') {
            $add_caisse->typemvt = 'Entrer de Caisse';
        }else if ($request->type_ope === 'sortie') {
            $add_caisse->typemvt = 'Sortie de Caisse';
        }

        $add_caisse->creer_id = $request->auth_id;
        $add_caisse->date_ope = $request->date_ope;

        try {
                
            if (!$add_caisse->save()) {
                throw new \Exception('Erreur');
            }

            $solde_caisse->solde = number_format($solde_apres, 0, '', '.');

            if (!$solde_caisse->save()) {
                throw new \Exception('Erreur');
            }

            DB::commit();
            return response()->json(['success' => true]);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true]);
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
            $caisse = caisse::find(1);

            $add = new portecaisse();
            $add->motif = 'OUVERTURE DE CAISSE';
            $add->montant = $caisse->solde;
            $add->creer_id = $request->auth_id;
                
            if (!$add->save()) {
                throw new \Exception('Erreur');
            }

            $caisse->statut = 'ouvert';

            if (!$caisse->save()) {
                throw new \Exception('Erreur');
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
                $mail->Body = 'OUVERTURE DE LA CAISSE, Solde de la caisse : '. $caisse->solde .' Fcfa';
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
            $caisse = caisse::find(1);

            $add = new portecaisse();
            $add->motif = 'FERMETURE DE CAISSE';
            $add->montant = $caisse->solde;
            $add->creer_id = $request->auth_id;

            $today = Carbon::today();
            $total = 0;
            $entries = 0;
            $exits = 0;

            $transactions = historiquecaisse::whereDate('created_at', '=', $today)->get();
            
            foreach ($transactions as $value) {
                if ($value->typemvt === 'Entrer de Caisse') {
                    $total += str_replace('.', '', $value->montant);
                    $entries += str_replace('.', '', $value->montant);
                } else {
                    $total -= str_replace('.', '', $value->montant);
                    $exits += str_replace('.', '', $value->montant);
                }
            }
        
            if (!$add->save()) {
                throw new \Exception('Erreur');
            }

            $caisse->statut = 'fermer';

            if (!$caisse->save()) {
                throw new \Exception('Erreur');
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
                    $color = ($transaction['typemvt'] === 'Entrer de Caisse') ? 'green' : 'red';
                    $montantFormatted = $transaction['typemvt'] === 'Entrer de Caisse' ? "+ {$transaction['montant']} Fcfa" : "- {$transaction['montant']} Fcfa";

                    $entryCell = $transaction['typemvt'] === 'Entrer de Caisse' ? "<td style='color: {$color};'>{$montantFormatted}</td><td></td>" : "<td></td><td style='color: {$color};'>{$montantFormatted}</td>";

                    $tableRows .= "<tr>
                        <td>{$transaction['motif']}</td>
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

}
