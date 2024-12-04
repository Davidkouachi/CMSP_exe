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
use App\Models\role;
use App\Models\typeadmission;
use App\Models\natureadmission;
use App\Models\detailhopital;
use App\Models\facture;
use App\Models\soinsinfirmier;
use App\Models\typesoins;
use App\Models\examenpatient;
use App\Models\examen;
use App\Models\prelevement;
use App\Models\joursemaine;
use App\Models\rdvpatient;
use App\Models\programmemedecin;
use App\Models\caisse;
use App\Models\historiquecaisse;
use App\Models\user;
use App\Models\portecaisse;

class ApisearchController extends Controller
{
    public function rech_patient(Request $request)
    {
        $patient = patient::leftJoin('assurances', 'assurances.id', '=', 'patients.assurance_id')
                       ->leftJoin('tauxes', 'tauxes.id', '=', 'patients.taux_id')
                       ->leftJoin('societes', 'societes.id', '=', 'patients.societe_id')
                       ->where('patients.id', '=', $request->id)
                       ->select(
                            'patients.*', 
                            'assurances.nom as assurance', 
                            'tauxes.taux as taux', 
                            'societes.nom as societe')
                       ->first();

        if ($patient) {
            return response()->json(['success' => true, 'patient' => $patient]);
        }else{
            return response()->json(['existep' => true]);
        }

    }

    public function rech_patient_hos($code)
    {
        $patient = patient::leftJoin('assurances', 'assurances.id', '=', 'patients.assurance_id')
                       ->leftJoin('tauxes', 'tauxes.id', '=', 'patients.taux_id')
                       ->leftJoin('societes', 'societes.id', '=', 'patients.societe_id')
                       ->where('patients.matricule', '=', $code)
                       ->select(
                            'patients.*', 
                            'assurances.nom as assurance', 
                            'tauxes.taux as taux', 
                            'societes.nom as societe')
                       ->first();

        if ($patient) {

            $verf = patient::join('detailhopitals', 'detailhopitals.patient_id', '=', 'patients.id')
                                ->join('factures', 'factures.id', '=', 'detailhopitals.facture_id')
                                ->where('patients.matricule', '=', $code)
                                ->select('patients.*','factures.statut as statut')
                                ->first();

            if ($verf && $verf->statut == 'impayer') {
                return response()->json(['existe' => true]);
            }

            return response()->json(['success' => true, 'patient' => $patient]);

        }else{
            return response()->json(['existep' => true]);
        }

    }

    public function refresh_num_chambre()
    {
        do {
            // Generate a random 9-digit number
            $code = random_int(100, 999); // Generates a number between 100000000 and 999999999
        } while (chambre::where('code', $code)->exists()); // Ensure uniqueness

        if ($code) {
            return response()->json(['success' => true, 'code' => $code]);
        }else{
            return response()->json(['error' => true]);
        }
        
    }

    public function refresh_num_lit()
    {
        do {
            // Generate a random 9-digit number
            $code = random_int(100, 999); // Generates a number between 100000000 and 999999999
        } while (chambre::where('code', $code)->exists()); // Ensure uniqueness

        if ($code) {
            return response()->json(['success' => true, 'code' => $code]);
        }else{
            return response()->json(['error' => true]);
        }  
    }

    public function list_chambre_select()
    {
        $list = chambre::all(); // Fetch all chambres
        $availableList = [];    // Array to hold filtered chambres

        foreach ($list as $value) {
            $nbre = lit::where('chambre_id', '=', $value->id)->count(); // Count the number of lits 
            
            if ($nbre < $value->nbre_lit) {
                $availableList[] = $value;
            }
        }

        // Return either the filtered list or all chambres if none were available
        return response()->json(['list' => $availableList]);
    }

    public function select_acte()
    {
        $acte = acte::all();

        return response()->json(['acte' => $acte]); 
    }

    public function select_specialite()
    {
        $acte = acte::where('nom', '=', 'CONSULTATION')->first();

        $typeacte = typeacte::where('acte_id', '=', $acte->id )->select('id','nom')->get();

        return response()->json(['typeacte' => $typeacte]); 
    }

    public function select_typeacte($id)
    {
        $typeacte = typeacte::where('acte_id', '=', $id)->get();

        return response()->json(['typeacte' => $typeacte]); 
    }

    public function name_patient()
    {
        $name = patient::leftJoin('assurances', 'assurances.id', '=', 'patients.assurance_id')
                       ->leftJoin('tauxes', 'tauxes.id', '=', 'patients.taux_id')
                       ->leftJoin('societes', 'societes.id', '=', 'patients.societe_id')
                       ->select(
                            'patients.*', 
                            'assurances.nom as assurance', 
                            'tauxes.taux as taux', 
                            'societes.nom as societe')
                       ->get();
                       
        return response()->json(['name' => $name]);
    }

    public function name_patient_reception()
    {
        $name = Patient::select('id', 'np')->get();
                       
        return response()->json(['name' => $name]);
    }

    public function name_patient_examen()
    {
       $name = Patient::leftJoin('tauxes', 'tauxes.id', '=', 'patients.taux_id')
                        ->select(
                            'patients.id as id',
                            'patients.np as np',
                            'patients.assurer as assurer',
                            'tauxes.taux as taux',
                        )->get();
                       
        return response()->json(['name' => $name]); 
    }

    public function lit_select($id)
    {
        $lit = lit::join('chambres', 'chambres.id', '=', 'lits.chambre_id')
                ->where('lits.chambre_id', '=', $id)
                ->where('lits.statut', '=', 'disponible')
                ->select('lits.id as id','lits.code as code','lits.type as type', 'chambres.prix as prix')
                ->get();

        return response()->json(['lit' => $lit]); 
    }

    public function natureadmission_select($id)
    {
        $natureadmission = natureadmission::where('typeadmission_id', '=', $id)
                                            ->select('id','nom')->get();

        return response()->json(['natureadmission' => $natureadmission]); 
    }

    public function select_soinsIn($id)
    {
        $soinsin = soinsinfirmier::where('typesoins_id', '=', $id)->get();

        return response()->json(['soinsin' => $soinsin]); 
    }

    public function list_acte_ex()
    {
        $acte = acte::where('nom', '=', 'ANALYSE')->Orwhere('nom', '=', 'IMAGERIE')->get();

        return response()->json(['acte' => $acte]); 
    }

    public function montant_prelevement()
    {
        $prelevement = prelevement::where('code', '=', '1')->first();

        return response()->json(['prelevement' => $prelevement]); 
    }

    public function select_examen($id)
    {
        $examen = Typeacte::where('acte_id', '=', $id)->get();

        return response()->json(['examen' => $examen]); 
    }

    public function select_jour()
    {
        $rech = joursemaine::select('id','jour')->get();

        return response()->json(['rech' => $rech]); 
    }

    public function montant_solde()
    {
        $solde = caisse::find('1');

        return response()->json(['solde' => $solde]); 
    }

    public function list_caissier()
    {
        $caissier = user::select('id','name','sexe')->get();

        return response()->json(['caissier' => $caissier]); 
    }

    public function select_profil()
    {
        $profil = DB::table('profile')
                ->where('libprofile', '!=', 'MEDECIN')
                ->get();

        return response()->json(['profil' => $profil]); 
    }

    public function rech_hos_patient($id)
    {
        $hos = detailhopital::where('patient_id', '=', $id)
                            ->where('statut', '=', 'Hospitaliser')
                            ->count();

        return response()->json(['hos' => $hos]);
    }

    public function verf_caisse()
    {
        $caisse = caisse::find(1);

        return response()->json(['caisse' => $caisse]);
    }

    public function select_list_medecin()
    {

        $medecin = user::where('role', '=', 'MEDECIN')->select('name','id')->get();

        return response()->json(['medecin' => $medecin]);
    }

    public function select_typeadmission()
    {
        $typeadmission = typeadmission::select('nom','id')->get();

        return response()->json(['typeadmission' => $typeadmission]);
    }

    public function select_chambre()
    {
        $chambre = chambre::select('code','id')->get();

        return response()->json(['chambre' => $chambre]);
    }

    public function select_civilite()
    {
        $civilite = DB::table('civilite')->get();

        return response()->json(['civilite' => $civilite]); 
    }

    public function select_service()
    {
        $service = DB::table('service')->get();

        return response()->json(['service' => $service]); 
    }

    public function select_contrat()
    {
        $contrat = DB::table('contrat')->get();

        return response()->json(['contrat' => $contrat]); 
    }

}
