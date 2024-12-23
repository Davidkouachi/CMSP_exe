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
use App\Models\joursemaine;
use App\Models\rdvpatient;
use App\Models\programmemedecin;
use App\Models\depotfacture;


class ApideleteController extends Controller
{
    public function delete_chambre($id)
    {
        $put = chambre::find($id);

        if ($put) {
            if ($put->delete()) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }
        }

        return response()->json(['error' => true]);
    }

    public function delete_lit($id)
    {
        $put = lit::find($id);

        if ($put) {
            if ($put->delete()) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }
        }

        return response()->json(['error' => true]);
    }

    public function delete_acte($id)
    {
        $put = acte::find($id);

        if ($put) {
            if ($put->delete()) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }
        }

        return response()->json(['error' => true]);
    }

    public function delete_typeacte($id)
    {
        $put = typeacte::find($id);

        if ($put) {
            if ($put->delete()) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }
        }

        return response()->json(['error' => true]);
    }

    public function delete_medecin($matricule)
    {
        DB::beginTransaction();

            try {

                $medecinDelete = DB::table('medecin')
                                ->where('codemedecin', '=', $matricule)
                                ->delete();

                if (!$medecinDelete === 0) {
                    throw new Exception('Erreur lors de la suppression dans la table medecin');
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function delete_typeadmission($id)
    {
        $put = typeadmission::find($id);

        if ($put) {
            if ($put->delete()) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }
        }

        return response()->json(['error' => true]);

    }

    public function delete_natureadmission($id)
    {
        $put = natureadmission::find($id);

        if ($put) {
            if ($put->delete()) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }
        }

        return response()->json(['error' => true]);

    }

    public function delete_typesoins($id)
    {
        $put = DB::table('typesoinsinfirmiers')->where('code_typesoins', '=', $id)->first();

        if ($put) {

            $delete = DB::table('typesoinsinfirmiers')->where('code_typesoins', '=', $id)->delete();

            if ($delete == 1) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }
        }

        return response()->json(['error' => true]);

    }

    public function delete_soinsIn($id)
    {
        $put = soinsinfirmier::find($id);

        if ($put) {
            if ($put->delete()) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }
        }

        return response()->json(['error' => true]);

    }

    public function delete_societe($id)
    {
        DB::beginTransaction();

            try {

                $societeDelete = DB::table('societeassure')
                                ->where('codesocieteassure', '=', $id)
                                ->delete();

                if (!$societeDelete === 0) {
                    throw new Exception('Erreur lors de la suppresion dans la table societeassure');
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function delete_rdv($id)
    {
        $put = DB::table('rdvpatients')->where('id', '=', $id)->first();

        if ($put) {

            $delete = DB::table('rdvpatients')->where('id', '=', $id)->delete();

            if ($delete == 1) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }
        }

        return response()->json(['error' => true]);

    }

    public function delete_specialite($id)
    {
        DB::beginTransaction();

            try {

                $specialiteDelete = DB::table('specialitemed')
                                ->where('codespecialitemed', '=', $id)
                                ->delete();

                if (!$specialiteDelete === 0) {
                    throw new Exception('Erreur lors de la suppresion dans la table specialitemed');
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function delete_depotfacture($id)
    {
        $put = depotfacture::find($id);

        if ($put) {
            if ($put->delete()) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }
        }

        return response()->json(['error' => true]);
    }

    public function delete_Cons($numfac)
    {
        $put = DB::table('consultation')->where('numfac', '=', $numfac)->exists();

        if (!$put) {
            return response()->json(['error' => true, 'message' => 'Consultation non trouvé']);
        }

        DB::beginTransaction();

        try {
            // Trouver la consultation associée
            $id_cons = DB::table('consultation')->where('numfac', '=', $numfac)->delete();
            if ($id_cons === 0) {
                return response()->json(['error' => true, 'message' => 'Consultation non trouvée']);
            }

            // Trouver la facture associée à la consultation
            $id_facture = DB::table('factures')->where('numfac', '=', $numfac)->delete();
            if ($id_facture === 0) {
                return response()->json(['error' => true, 'message' => 'Facture non trouvée']);
            }

            DB::commit(); // Validation de la transaction si tout s'est bien passé
            return response()->json(['success' => true, 'message' => 'Suppression effectuée avec succès']);
        } catch (Exception $e) {
            DB::rollBack(); // Annulation en cas d'exception
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    public function delete_user($matricule)
    {
        DB::beginTransaction();

            try {

                $userDelete = DB::table('users')
                                ->where('code_personnel', '=', $matricule)
                                ->delete();

                if (!$userDelete === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table users');
                }

                $employeDelete = DB::table('employes')
                                    ->where('matricule', '=', $matricule)
                                    ->delete();

                if ($employeDelete === 0) {
                    throw new Exception('Erreur lors de la suppression dans la table employes');
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function delete_assurance($id)
    {
        DB::beginTransaction();

            try {

                $assuranceDelete = DB::table('assurance')
                                ->where('idassurance', '=', $id)
                                ->delete();

                if (!$assuranceDelete === 0) {
                    throw new Exception('Erreur lors de la suppresion dans la table assurance');
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function delete_assureur($id)
    {
        DB::beginTransaction();

            try {

                $assureurDelete = DB::table('assureur')
                                ->where('codeassureur', '=', $id)
                                ->delete();

                if (!$assureurDelete === 0) {
                    throw new Exception('Erreur lors de la suppresion dans la table assureur');
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function delete_tarif($id)
    {
        DB::beginTransaction();

            try {

                $tarifDelete = DB::table('tarifs')
                                ->where('idtarif', '=', $id)
                                ->delete();

                if (!$tarifDelete === 0) {
                    throw new Exception('Erreur lors de la suppresion dans la table tarifs');
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

}
