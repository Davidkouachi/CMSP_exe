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
        $taux = DB::table('tauxcouvertureassure')->select('idtauxcouv','valeurtaux')->orderBy('valeurtaux', 'asc')->get();

        return response()->json(['taux' => $taux]); 
    }

    public function societe_select_patient_new()
    {
        $societe = DB::table('societeassure')->select('codesocieteassure','nomsocieteassure')->get();

        return response()->json(['societe' => $societe]); 

    }

    public function assurance_select_patient_new()
    {
        $assurance = DB::table('assurance')->where('codeassurance', '!=', 'NONAS')->select('codeassurance','libelleassurance')->get();

        return response()->json(['assurance' => $assurance]); 
    }

    public function filiation_select_patient_new()
    {
        $filiation = DB::table('filiation')->select('codefiliation','libellefiliation')->get();

        return response()->json(['filiation' => $filiation]); 
    }

    public function select_medecin()
    {
        $role = role::where('nom', '=', 'MEDECIN')->first();

        $medecin = user::where('users.role_id', '=', $role->id)->select('id','name')->get();

        return response()->json($medecin);
    }

    public function select_assureur()
    {
        $assureur = DB::table('assureur')->select('codeassureur','libelle_assureur')->get();

        return response()->json(['assureur' => $assureur]); 
    }

    public function select_typegarantie()
    {
        $type = DB::table('typgarantie')->select('codtypgar','libtypgar')->get();

        return response()->json(['type' => $type]); 
    }

    public function select_garantie()
    {
        $garantie = DB::table('garantie')->select('codgaran','libgaran')->get();

        return response()->json(['garantie' => $garantie]); 
    }
}
