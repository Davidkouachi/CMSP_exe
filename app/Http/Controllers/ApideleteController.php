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

    public function delete_medecin($id)
    {
        $put1 = user::find($id);
        $put2 = typemedecin::where('user_id', '=', $id)->first();

        if ($put1 && $put2) {
            // Perform deletion
            $put2->delete(); // Delete related typemedecin entry
            $put1->delete(); // Delete user entry

            return response()->json(['success' => true]);
        }

        return response()->json(['error' => true]);
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
        $put = typesoins::find($id);

        if ($put) {
            if ($put->delete()) {
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
        $put = societe::find($id);

        if ($put) {
            if ($put->delete()) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }
        }

        return response()->json(['error' => true]);

    }

    public function delete_rdv($id)
    {
        $put = rdvpatient::find($id);

        if ($put) {
            if ($put->delete()) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }
        }

        return response()->json(['error' => true]);

    }

    public function delete_specialite($id)
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

    public function delete_Cons($id)
    {
        $put = detailconsultation::find($id);

        if (!$put) {
            return response()->json(['error' => true, 'message' => 'Detail consultation non trouvé']);
        }

        DB::beginTransaction();

        try {
            // Trouver la consultation associée
            $id_cons = consultation::find($put->consultation_id);
            if (!$id_cons) {
                return response()->json(['error' => true, 'message' => 'Consultation non trouvée']);
            }

            // Trouver la facture associée à la consultation
            $id_facture = facture::find($id_cons->facture_id);
            if (!$id_facture) {
                return response()->json(['error' => true, 'message' => 'Facture non trouvée']);
            }

            // Suppression dans l'ordre : détail consultation, consultation, puis facture
            if ($put->delete() && $id_cons->delete() && $id_facture->delete()) {
                DB::commit(); // Validation de la transaction si tout s'est bien passé
                return response()->json(['success' => true, 'message' => 'Suppression effectuée avec succès']);
            } else {
                DB::rollBack(); // Annulation en cas de problème
                return response()->json(['error' => true, 'message' => 'Erreur lors de la suppression']);
            }
        } catch (Exception $e) {
            DB::rollBack(); // Annulation en cas d'exception
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    public function delete_user($id)
    {
        $put = user::find($id);

        if ($put) {
            if ($put->delete()) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }
        }

        return response()->json(['error' => true]);
    }

}
