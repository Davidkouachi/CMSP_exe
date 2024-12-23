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
use Exception;
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

class ApiupdateController extends Controller
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
            // Generate a random 9-digit number
            $matricule = random_int(100000, 999999); // Generates a number between 100000000 and 999999999
        } while (DB::table('employes')->where('matricule', '=', 'P'.$matricule)->exists()); // Ensure uniqueness

        // Return matricule with prefix
        return $matricule;
    }















    public function update_chambre(Request $request, $id)
    {

        $updateData_chambre =[
            'nbre_lit' => $request->nbre_lit,
            'prix' => str_replace('.', '', $request->prix),
            'updated_at' => now(),
        ];

        $chambreUpdate = DB::table('chambres')
                            ->where('id', '=', $id)
                            ->update($updateData_chambre);

        if ($chambreUpdate == 1) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => true]); 
    }

    public function update_lit(Request $request, $id)
    {

        $updateData_lit =[
            'type' => $request->typeLit,
            'updated_at' => now(),
        ];

        $litUpdate = DB::table('lits')
                            ->where('id', '=', $id)
                            ->update($updateData_lit);

        if ($litUpdate == 1) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => true]); 
    }

    public function update_acte(Request $request, $id)
    {
        $put = acte::find($id);

        if ($put) {
            $put->nom = $request->nom;

            if ($put->save()) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }

        }

        return response()->json(['error' => true]);
    }

    public function update_typeacte(Request $request, $id)
    {
        $put = typeacte::find($id);

        if ($put) {
            $put->nom = $request->type;
            $put->prix = $request->prix;
            $put->acte_id = $request->acte_id;

            if ($put->save()) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }

        }

        return response()->json(['error' => true]);
    }

    public function update_medecin(Request $request, $matricule)
    {
        $verifications = [
            'tel' => $request->tel,
            'email' => $request->email ?? null,
        ];

        $Exist = DB::table('medecin')->where(function ($query) use ($verifications) {
            $query->where('contact', $verifications['tel'])
                  ->orWhere(function ($query) use ($verifications) {
                      if (!is_null($verifications['email'])) {
                          $query->where('email', $verifications['email']);
                      }
                  });
        })->where('codemedecin', '!=', $matricule)->first();

        if ($Exist) {
            if ($Exist->contact === $verifications['tel']) {
                return response()->json(['tel_existe' => true]);
            } elseif ($Exist->email === $verifications['email']) {
                return response()->json(['email_existe' => true]);
            }
        }

        DB::beginTransaction();

            try {

                $specialite = DB::table('specialitemed')->where('codespecialitemed', '=', $request->specialite_id)->first();

                if (!$specialite) {
                    throw new Exception('Spécialité introuvable');
                }

                $updateData_medecin =[
                    'nommedecin' => $request->nom,
                    'prenomsmedecin' => $request->prenom,
                    'nomprenomsmed' => 'Dr '.$request->nom.' '.$request->prenom ,
                    'codespecialitemed' => $specialite->codespecialitemed,
                    'numordremed' => $request->num,
                    'contact' => $request->tel,
                    'dateservice' => $request->dateservice,
                    'email' => $request->email,
                    'updated_at' => now(),
                ];

                $medecinUpdate = DB::table('medecin')
                                    ->where('codemedecin', '=', $matricule)
                                    ->update($updateData_medecin);

                if ($medecinUpdate === 0) {
                    throw new Exception('Erreur lors de la mise à jour dans la table médecin');
                }

                 // Valider la transaction
                DB::commit();
                return response()->json(['success' => true]);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function update_typeadmission(Request $request, $id)
    {
        $put = typeadmission::find($id);

        if ($put) {
            $put->nom = $request->nom;

            if ($put->save()) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }

        }

        return response()->json(['error' => true]);
    }

    public function update_natureadmission(Request $request, $id)
    {
        $put = natureadmission::find($id);

        if ($put) {
            $put->nom = $request->nomModif;
            $put->typeadmission_id = $request->type_id;

            if ($put->save()) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }

        }

        return response()->json(['error' => true]);
    }

    public function update_produit(Request $request, $id)
    {
        $verf = DB::table('medicine')
            ->where('name', '=', $request->nom)
            ->where('medicine_id', '!=', $id)
            ->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $updateData_produit =[
            'name' => $request->nom,
            'medicine_category_id' => $request->categorie_id,
            'price' => str_replace('.', '', $request->prix),
            'status' => $request->quantite,
            'updated_at' => now(),
        ];

        $produitUpdate = DB::table('medicine')
                            ->where('medicine_id', '=', $id)
                            ->update($updateData_produit);

        if ($produitUpdate == 1) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => true]);
    }

    public function update_typesoins(Request $request, $id)
    {
        $updateData_typesoins =[
            'libelle_typesoins' => $request->nom,
            'updated_at' => now(),
        ];

        $typesoinsUpdate = DB::table('typesoinsinfirmiers')
                            ->where('code_typesoins', '=', $id)
                            ->update($updateData_typesoins);

        if ($typesoinsUpdate == 1) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => true]);
    }

    public function update_soinIn(Request $request, $id)
    {
        $verf = DB::table('soins_infirmier')
            ->where('libelle_soins', '=', $request->nom)
            ->where('code_soins', '!=', $id)
            ->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $updateData_soins =[
            'libelle_soins' => $request->nom,
            'price' => str_replace('.', '', $request->prix),
            'code_typesoins' => $request->typesoins,
            'updated_at' => now(),
        ];

        $soinsUpdate = DB::table('soins_infirmier')
                            ->where('code_soins', '=', $id)
                            ->update($updateData_soins);

        if ($soinsUpdate == 1) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => true]);
    }

    public function update_societe(Request $request, $id)
    {

        $verf = DB::table('societeassure')
                ->where('nomsocieteassure', '=', $request->nom)
                ->where('codesocieteassure', '!=', $id)
                ->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        DB::beginTransaction();

            try {

                $updateData_societe =[
                    'nomsocieteassure' => $request->nom,
                    'codeassurance' => $request->codeassurance,
                    'codeassureur' => $request->assureur_id,
                    'updated_at' => now(),
                ];

                $societeUpdate = DB::table('societeassure')
                                    ->where('codesocieteassure', '=', $id)
                                    ->update($updateData_societe);

                if ($societeUpdate === 0) {
                    throw new Exception('Erreur lors de la mise à jour dans la table societeassure');
                }

                // Valider la transaction
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function examen_Modif(Request $request, $id)
    {
        // Check if there's another typeacte with the same name and acte_id but a different ID
        $verf = typeacte::where('nom', '=', $request->nom)
                        ->where('acte_id', '=', $request->acte_id)  // Ensure you're using acte_id correctly
                        ->where('id', '!=', $id)  // Exclude the current typeacte
                        ->exists();

        if ($verf) {
            return response()->json(['existe' => true]);  // If a duplicate exists, return response
        }

        // Find the typeacte by ID
        $add = typeacte::find($id);

        // Check if the typeacte was found
        if (!$add) {
            return response()->json(['error' => 'Typeacte not found']);
        }

        // Update the typeacte with new values
        $add->nom = $request->nom;
        $add->cotation = $request->cotation;
        $add->valeur = $request->valeur;
        $add->prix = $request->prix;
        $add->montant = $request->montant;
        $add->acte_id = $request->acte_id;  // Corrected to use acte_id

        // Save and return success or error response
        if ($add->save()) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => true]);
    }

    public function prelevement_Modif(Request $request)
    {

        $updateData_prelevement =[
            'prix' => str_replace('.', '', $request->prix),
            'updated_at' => now(),
        ];

        $prelevementUpdate = DB::table('prelevements')
                            ->where('code', '=', 1)
                            ->update($updateData_prelevement);

        if ($prelevementUpdate == 1) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => true]); 
    }

    public function update_horaire($id)
    {
        $add = programmemedecin::find($id);
        $add->statut = 'non';

        $updateData_programme =[
            'statut' => 'non',
        ];

        $programmeUpdate = DB::table('programmemedecins')
                            ->where('id', '=', $id)
                            ->update($updateData_programme);

        if ($programmeUpdate === 1) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => true]);
    }

    public function update_rdv(Request $request, $id)
    {

        $dateSansHeure = Carbon::parse($request->date)->toDateString();

        $verf = DB::table('rdvpatients')
            ->where('patient_id', '=', $request->patient_id)
            ->where('codemedecin', '=', $request->medecin_id)
            // ->where('date', '=', $request->date)
            ->whereRaw('DATE(date) = ?', [$dateSansHeure])
            ->where('id', '!=', $id)
            ->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $updateData_rdv =[
            'date' => $request->date,
            'motif' => $request->motif,
            'tel' => $request->tel,
            'updated_at' => now(),
        ];

        $rdvUpdate = DB::table('rdvpatients')
                            ->where('id', '=', $id)
                            ->update($updateData_rdv);

        if ($rdvUpdate == 1) {
            return response()->json(['success' => true, 'tel' => $request->tel, 'date' => $request->date]);
        }

        return response()->json(['error' => true]);
    }

    public function update_specialite(Request $request, $code)
    {
        $Exist = DB::table('specialitemed')
            ->where(function ($query) use ($request) {
                $query->where('nomspecialite', '=', $request->nom)
                      ->orWhere('abrspecialite', '=', $request->abr);
            })
            ->where('codespecialitemed', '!=', $code)
            ->exists();

        if ($Exist) {
            return response()->json(['existe' => true, 'message' => 'Ce type ou cette abréviation existe déjà.']);
        }


        DB::beginTransaction();

            try {

                $updateData_specialite =[
                    'nomspecialite' => $request->nom,
                    'abrspecialite' => $request->abr,
                    'updated_at' => now(),
                ];

                $specialiteUpdate = DB::table('specialitemed')
                                    ->where('codespecialitemed', '=', $code)
                                    ->update($updateData_specialite);

                if ($specialiteUpdate === 0) {
                    throw new Exception('Erreur lors de la mise à jour dans la table specialitemed');
                }

                // Valider la transaction
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function update_depot_fac(Request $request, $id)
    {
        $date1 = $request->date1;
        $date2 = $request->date2;
        $date_depot = $request->date_depot;
        $assurance_id = $request->assurance_id;

        $verf = depotfacture::join('assurances', 'assurances.id', 'depotfactures.assurance_id')
            ->where('depotfactures.id', '!=', $id)
            ->where('depotfactures.assurance_id', $assurance_id)
            ->where(function ($query) use ($date1, $date2) {
                $query->whereBetween(DB::raw('DATE(depotfactures.date1)'), [$date1, $date2])
                      ->orWhereBetween(DB::raw('DATE(depotfactures.date2)'), [$date1, $date2]);
            })
            ->exists();

        if ($verf)
        {
            return response()->json(['existe' => true]);
        }

        $add = depotfacture::find($id);
        if (!$add) {
            return response()->json(['non_touve' => true]);
        }

        $add->assurance_id = $assurance_id;
        $add->date1 = $date1;
        $add->date2 = $date2;
        $add->date_depot = $date_depot;

        if ($add->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => true]);
        }
    }

    public function update_assurance(Request $request, $id)
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
        })->where('idassurance', '!=', $id)->first();

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

                $updateData_assurance =[
                    'libelleassurance' => $request->nom,
                    'telassurance' => $request->tel,
                    'faxassurance' => $request->fax,
                    'emailassurance' => $request->email,
                    'adrassurance' => $request->adresse,
                    'situationgeo' => $request->carte,
                    'description' => $request->desc,
                    'updated_at' => now(),
                ];

                $assuranceUpdate = DB::table('assurance')
                                    ->where('idassurance', '=', $id)
                                    ->update($updateData_assurance);

                if ($assuranceUpdate === 0) {
                    throw new Exception('Erreur lors de la mise à jour dans la table assurances');
                }

                // Valider la transaction
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function update_assureur(Request $request, $id)
    {

        $verifications = [
            'nom' => $request->nom,
        ];

        $Exist = DB::table('assureur')->where(function ($query) use ($verifications) {
            $query->where('libelle_assureur', $verifications['nom']);
        })->where('codeassureur', '!=', $id)->first();

        if ($Exist) {
            if ($Exist->libelle_assureur === $verifications['nom']) {
                return response()->json(['nom_existe' => true]);
            }
        }

        DB::beginTransaction();

            try {

                $updateData_assureur =[
                    'libelle_assureur' => $request->nom,
                    'updated_at' => now(),
                ];

                $assureurUpdate = DB::table('assureur')
                                    ->where('codeassureur', '=', $id)
                                    ->update($updateData_assureur);

                if ($assureurUpdate === 0) {
                    throw new Exception('Erreur lors de la mise à jour dans la table assureur');
                }

                // Valider la transaction
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function update_user(Request $request, $matricule)
    {
        $verifications = [
            'tel' => $request->tel,
            'tel2' => $request->tel2 ?? null, // Allow tel2 to be null
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
        })->where('matricule', '!=', $matricule)->first();

        // Return appropriate response based on existing data
        if ($Exist) {
            if ($Exist->tel === $verifications['tel'] || (!is_null($verifications['tel2']) && $Exist->tel2 === $verifications['tel2'])) {
                return response()->json(['tel_existe' => true]);
            } elseif ($Exist->email === $verifications['email']) {
                return response()->json(['email_existe' => true]);
            }
        }

        DB::beginTransaction();

            try {

                $profil = DB::table('profile')->where('idprofile', '=', $request->profil_id)->first();
                $service = DB::table('service')->where('code', '=', $request->service_id)->first();

                if (!$profil || !$service) {
                    throw new Exception('Profil ou Service introuvable');
                }

                Log::info($request->all());

                $updateData_employes =[
                    'typepiece' => $request->typepiece,
                    'civilite' => $request->civilite,
                    'nom' => $request->nom,
                    'prenom' => $request->prenom,
                    'nomprenom' => $request->nom.' '.$request->prenom,
                    'datenais' => $request->datenais,
                    'profession' => $service->libelle,
                    'niveau' => $request->niveau,
                    'diplome' => $request->diplome,
                    'residence' => $request->residence,
                    'cel' => $request->tel,
                    'contacturgence' => $request->tel2,
                    'email' => $request->email,
                    'service' => $request->service_id,
                    'typecontrat' => $request->contrat_id,
                    'datecontrat' => $request->date_debut,
                    'datefincontrat' => $request->date_fin,
                    'updated_at' => now(),
                ];

                $employeUpdate = DB::table('employes')
                                    ->where('matricule', '=', $matricule)
                                    ->update($updateData_employes);

                if ($employeUpdate === 0) {
                    throw new Exception('Erreur lors de la mise à jour dans la table employes');
                }

                $updateData_users = [
                    'login' => $request->login,  // Pour le login
                    'user_first_name' => $request->nom,
                    'user_last_name' => $request->prenom,
                    'tel' => $request->tel,
                    'user_profil_id' => $request->profil_id,
                    'email' => $request->email,
                    'updated_at' => now(),
                ];

                if ($request->password !== null) {
                    $updateData_users['password'] = bcrypt($request->password);
                }

                $userDelete = DB::table('users')
                                ->where('code_personnel', '=', $matricule)
                                ->update($updateData_users);



                if (!$userDelete === 0) {
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

    public function update_mdp(Request $request, $matricule)
    {
        $put = user::find($id);

        if ($put) {
            $put->password = bcrypt($request->mdp1);

            if ($put->save()) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }

        }

        return response()->json(['error' => true]);
    }

    public function update_date_hos(Request $request, $id)
    {
        $add = detailhopital::find($id);

        $nbre_j_ancien = floor(Carbon::parse($add->date_debut)->diffInRealDays($add->date_fin));
        $montant_chambre = str_replace('.', '', $add->montant_chambre);
        $prix_chambre = ((int)$montant_chambre / (int)$nbre_j_ancien);

        $nbre_j_new = floor(Carbon::parse($request->date1)->diffInRealDays($request->date2));
        $montant_chambre_new = ((int)$prix_chambre * (int)$nbre_j_new);

        $montant_new = str_replace('.', '', $add->montant_chambre) + str_replace('.', '', $add->montant_soins);

        $taux = (str_replace('.', '', $add->part_assurance)/str_replace('.', '', $add->montant));

        $part_assurance = $taux * $montant_new;
        $part_patient = $montant_new -$part_assurance; 

        $add->date_debut = $request->date1;
        $add->date_fin = $request->date2;
        $add->montant_chambre = $this->formatWithPeriods($montant_chambre_new);
        $add->montant = $this->formatWithPeriods($montant_new);

        if($add->save()){
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => true]);
        }
    }

    public function patient_modif(Request $request, $matricule)
    {

        DB::beginTransaction();

            try {

                $updateData_patient = [
                    'nompatient' => $request->nom,
                    'prenomspatient' => $request->prenom,
                    'nomprenomspatient' => $request->nom.' '.$request->prenom,
                    'datenaispatient' => $request->datenais,
                    'sexe' => $request->sexe,
                    'telpatient' => $request->tel,
                    'telpatient_2' => $request->tel2,
                    'telurgence_1' => $request->telu,
                    'telurgence_2' => $request->telu2,
                    'nomurgence' => $request->nomu,
                    'lieuderesidencepat' => $request->residence,
                    'updated_at' => now(),
                ];

                $patientUpdated = DB::table('patient')
                    ->where('idenregistremetpatient', '=', $matricule)
                    ->update($updateData_patient);

                if ($patientUpdated === 0) {
                    throw new Exception('Erreur lors de la mise à jour dans la table patient');
                }
                
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function update_type_garantie(Request $request, $code)
    {
        $Exist = DB::table('typgarantie')
            ->where('codtypgar', '!=', $code)
            ->Where('libtypgar', '=', $request->nom)
            ->exists();

        if ($Exist) {
            return response()->json(['existe' => true,'message' => 'Cet Type existe dèjà']);
        }

        DB::beginTransaction();

            try {

                $updateData_typegarantie =[
                    'libtypgar' => $request->nom,
                    'updated_at' => now(),
                ];

                $typegarantieUpdate = DB::table('typgarantie')
                                    ->where('codtypgar', '=', $code)
                                    ->update($updateData_typegarantie);

                if ($typegarantieUpdate === 0) {
                    throw new Exception('Erreur lors de la mise à jour dans la table typgarantie');
                }

                // Valider la transaction
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function update_garantie(Request $request, $code)
    {
        $Exist = DB::table('garantie')
            ->where('codgaran', '!=', $code)
            ->Where('libgaran', '=', $request->nom)
            ->exists();

        if ($Exist) {
            return response()->json(['existe' => true,'message' => 'Cette garantie existe dèjà']);
        }

        DB::beginTransaction();

            try {

                $updateData_garantie =[
                    'libgaran' => $request->nom,
                    'updated_at' => now(),
                ];

                $garantieUpdate = DB::table('garantie')
                                    ->where('codgaran', '=', $code)
                                    ->update($updateData_garantie);

                if ($garantieUpdate === 0) {
                    throw new Exception('Erreur lors de la mise à jour dans la table garantie');
                }

                // Valider la transaction
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function update_tarif(Request $request, $id)
    {

        DB::beginTransaction();

            try {

                $updateData_tarif =[
                    'montjour' => str_replace('.', '', $request->prixj),
                    'montnuit' => str_replace('.', '', $request->prixn),
                    'montferie' => str_replace('.', '', $request->prixf),
                    'updated_at' => now(),
                ];

                $tarifUpdate = DB::table('tarifs')
                                    ->where('idtarif', '=', $id)
                                    ->update($updateData_tarif);

                if ($tarifUpdate === 0) {
                    throw new Exception('Erreur lors de la mise à jour dans la table tarifs');
                }

                // Valider la transaction
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

}
