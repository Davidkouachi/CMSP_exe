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
use App\Models\user;
use App\Models\role;

class ApiController extends Controller
{
    public function taux_select_patient_new()
    {
        $taux = taux::select('id','taux')->get(); // Récupère toutes les assurances
        return response()->json($taux);
    }

    public function societe_select_patient_new()
    {
        $societe = societe::select('id','nom')->get(); // Récupère toutes les assurances
        return response()->json($societe);
    }

    public function assurance_select_patient_new()
    {
       $assurance = assurance::select('id','nom')->get(); // Récupère toutes les assurances
        return response()->json($assurance); 
    }

    public function select_medecin()
    {
        $role = role::where('nom', '=', 'MEDECIN')->first();

        $medecin = user::where('users.role_id', '=', $role->id)->select('id','name')->get();

        return response()->json($medecin);
    }
}
