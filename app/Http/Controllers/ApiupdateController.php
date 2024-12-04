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
use App\Models\joursemaine;
use App\Models\rdvpatient;
use App\Models\programmemedecin;
use App\Models\depotfacture;


class ApiupdateController extends Controller
{
    private function formatWithPeriods($number) {
        return number_format($number, 0, '', '.');
    }

    public function update_chambre(Request $request, $id)
    {
        $put = chambre::find($id);

        if ($put) {
            $put->nbre_lit = $request->nbre_lit;
            $put->prix = $request->prix;

            if ($put->save()) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }

        }

        return response()->json(['error' => true]);
    }

    public function update_lit(Request $request, $id)
    {
        $put = lit::find($id);

        if ($put) {
            $put->type = $request->typeLit;

            if ($put->save()) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }

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

    public function update_medecin(Request $request, $id)
    {
        $verifications = [
            'tel' => $request->tel,
            'tel2' => $request->tel2 ?? null, // Allow tel2 to be null
            'email' => $request->email ?? null,
            'nom' => $request->nom,
        ];

        // Check if the user exists except for the current user being updated
        $Exist = user::where(function($query) use ($verifications) {
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
                              $query->where('name', $verifications['nom']);
                          }
                      });
            })->where('id', '!=', $id)->first();

        // Return appropriate response based on existing data
        if ($Exist) {
            if ($Exist->tel === $verifications['tel'] || (!is_null($verifications['tel2']) && $Exist->tel2 === $verifications['tel2'])) {
                return response()->json(['tel_existe' => true]);
            } elseif ($Exist->email === $verifications['email']) {
                return response()->json(['email_existe' => true]);
            } elseif ($Exist->name === $verifications['nom']) {
                return response()->json(['nom_existe' => true]);
            }
        }

        DB::beginTransaction();

        try {
            // Update the user
            $user = user::find($id);
            $user->name = $request->nom;
            $user->email = $request->email;
            $user->sexe = $request->sexe;
            $user->tel = $request->tel;
            $user->tel2 = $request->tel2;
            $user->adresse = $request->adresse;

            if (!$user->save()) {
                throw new \Exception('Erreur lors de la mise à jour de l\'utilisateur.');
            }

            // Update the typeacte for the medecin
            $type = typemedecin::where('user_id', '=', $id)->first();
            $type->typeacte_id = $request->typeacte_id;

            if (!$type->save()) {
                throw new \Exception('Erreur lors de la mise à jour du typeacte.');
            }

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
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
        $put = produit::find($id);

        if ($put) {
            $put->nom = $request->nom;
            $put->prix = $request->prix;
            $put->quantite = $request->quantite;

            if ($put->save()) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }

        }

        return response()->json(['success' => true]);
    }

    public function update_typesoins(Request $request, $id)
    {
        $put = typesoins::find($id);

        if ($put) {
            $put->nom = $request->nom;

            if ($put->save()) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }

        }

        return response()->json(['success' => true]);
    }

    public function update_soinIn(Request $request, $id)
    {
        $put = soinsinfirmier::find($id);

        if ($put) {
            $put->nom = $request->nomModif;
            $put->prix = $request->prix;
            $put->typesoins_id = $request->typesoins_id;

            if ($put->save()) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }

        }

        return response()->json(['error' => true]);
    }

    public function update_societe(Request $request, $id)
    {
        $put = societe::find($id);

        if ($put) {

            $verifications = [
                'tel' => $request->tel,
                'tel2' => $request->tel2 ?? null,
                'email' => $request->email,
                'nom' => $request->nom,
                'fax' => $request->fax,
            ];

            $assuranceExist = societe::where('id', '!=', $id)
                                    ->where(function($query) use ($verifications) {
                                        $query->where('tel', $verifications['tel'])
                                            ->orWhere(function($query) use ($verifications) {
                                                if (!is_null($verifications['tel2'])) {
                                                    $query->where('tel2', $verifications['tel2']);
                                                }
                                            })
                                            ->orWhere('email', $verifications['email'])
                                            ->orWhere('nom', $verifications['nom'])
                                            ->orWhere('fax', $verifications['fax']);
                                    })->first();

            if ($assuranceExist ) {
                if ($assuranceExist->tel === $verifications['tel'] || (!is_null($verifications['tel2']) && $assuranceExist->tel2 === $verifications['tel2'])) {
                    return response()->json(['tel_existe' => true]);
                } elseif ($assuranceExist->email === $verifications['email']) {
                    return response()->json(['email_existe' => true]);
                } elseif ($assuranceExist->nom === $verifications['nom']) {
                    return response()->json(['nom_existe' => true]);
                } elseif ($assuranceExist->fax === $verifications['fax']) {
                    return response()->json(['fax_existe' => true]);
                }
            }

            $put->nom = $request->nom;
            $put->email = $request->email;
            $put->adresse = $request->adresse;
            $put->fax = $request->fax;
            $put->tel = $request->tel;
            $put->tel2 = $request->tel2;
            $put->sgeo = $request->sgeo;

            if ($put->save()) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }

        }

        return response()->json(['error' => true]);
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
        $add = prelevement::where('code', '=', '1')->first();
        $add->prix = $request->prix;

        if ($add->save()) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => true]);
    }

    public function update_horaire($id)
    {
        $add = programmemedecin::find($id);
        $add->statut = 'non';

        if ($add->save()) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => true]);
    }

    public function update_rdv(Request $request, $id)
    {
        $add = rdvpatient::find($id);

        $verf = rdvpatient::where('date', '=', $request->date)
                        ->where('patient_id', '=', $add->patient_id)
                        ->where('user_id', '=', $add->user_id)
                        ->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }
        
        $add->date = $request->date;
        $add->motif = $request->motif;

        if ($add->save()) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => true]);
    }

    public function update_specialite(Request $request, $id)
    {
        $put = typeacte::find($id);

        if ($put) {
            $put->nom = $request->nom;
            $put->prix = $request->prix;

            if ($put->save()) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }

        }

        return response()->json(['error' => true]);
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
        $put = assurance::find($id);

        if ($put) {

            $verifications = [
                'tel' => $request->tel,
                'tel2' => $request->tel2 ?? null,
                'email' => $request->email,
                'nom' => $request->nom,
                'fax' => $request->fax,
            ];

            $assuranceExist = assurance::where('id', '!=', $id)
                                    ->where(function($query) use ($verifications) {
                                        $query->where('tel', $verifications['tel'])
                                            ->orWhere(function($query) use ($verifications) {
                                                if (!is_null($verifications['tel2'])) {
                                                    $query->where('tel2', $verifications['tel2']);
                                                }
                                            })
                                            ->orWhere(function($query) use ($verifications) {
                                                if (!is_null($verifications['fax'])) {
                                                    $query->where('fax', $verifications['fax']);
                                                }
                                            })
                                            ->orWhere('email', $verifications['email'])
                                            ->orWhere('nom', $verifications['nom']);
                                    })->first();

            if ($assuranceExist ) {
                if ($assuranceExist->tel === $verifications['tel'] || (!is_null($verifications['tel2']) && $assuranceExist->tel2 === $verifications['tel2'])) {
                    return response()->json(['tel_existe' => true]);
                } elseif ($assuranceExist->email === $verifications['email']) {
                    return response()->json(['email_existe' => true]);
                } elseif ($assuranceExist->nom === $verifications['nom']) {
                    return response()->json(['nom_existe' => true]);
                } elseif ($assuranceExist->fax === $verifications['fax']) {
                    return response()->json(['fax_existe' => true]);
                }
            }

            $put->nom = $request->nom;
            $put->email = $request->email;
            $put->tel = $request->tel;
            $put->tel2 = $request->tel2;
            $put->fax = $request->fax;
            $put->adresse = $request->adresse;
            $put->carte = $request->carte;

            if($put->save()){
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }

        }

        return response()->json(['error' => true]);
    }

    public function update_user(Request $request, $id)
    {
        $verifications = [
            'tel' => $request->tel,
            'tel2' => $request->tel2 ?? null, // Allow tel2 to be null
            'email' => $request->email ?? null,
            'nom' => $request->nom,
        ];

        // Check if the user exists except for the current user being updated
        $Exist = user::where(function($query) use ($verifications) {
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
                              $query->where('name', $verifications['nom']);
                          }
                      });
            })->where('id', '!=', $id)->first();

        // Return appropriate response based on existing data
        if ($Exist) {
            if ($Exist->tel === $verifications['tel'] || (!is_null($verifications['tel2']) && $Exist->tel2 === $verifications['tel2'])) {
                return response()->json(['tel_existe' => true]);
            } elseif ($Exist->email === $verifications['email']) {
                return response()->json(['email_existe' => true]);
            } elseif ($Exist->name === $verifications['nom']) {
                return response()->json(['nom_existe' => true]);
            }
        }

        DB::beginTransaction();

        try {
            $role = role::find($request->role_id);
            // Update the user
            $user = user::find($id);
            $user->name = $request->nom;
            $user->email = $request->email;
            $user->sexe = $request->sexe;
            $user->tel = $request->tel;
            $user->tel2 = $request->tel2;
            $user->adresse = $request->adresse;
            $user->role_id = $role->id;
            $user->role = $role->nom;

            if (!$user->save()) {
                throw new \Exception('Erreur lors de la mise à jour de l\'utilisateur.');
            }

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    public function update_mdp(Request $request, $id)
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

    public function patient_modif(Request $request, $id)
    {
        $verifications = [
            'tel' => $request->tel,
            'tel2' => $request->tel2 ?? null, // Allow tel2 to be null
            'email' => $request->email ?? null,
            'nom' => $request->nom,
        ];

        $patientExist = patient::where('id', '!=', $id)->where(function($query) use ($verifications) {
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

        $add = patient::find($id);
        $add->np = $request->nom;
        $add->email = $request->email;
        $add->tel = $request->tel;
        $add->tel2 = $request->tel2;
        $add->adresse = $request->adresse;
        $add->datenais = $request->datenais;
        $add->sexe = $request->sexe;

        if($request->filiation !== null){
            $add->filiation = $request->filiation;
        }

        if($add->save()){
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => true]);
        }
    }

}
