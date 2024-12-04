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

class assuranceController extends Controller
{
    public function assurance_new()
    {
        $today = Carbon::today();
        $ass = assurance::whereDate('created_at', $today)
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('assurance.nouveau.assurance',['ass' => $ass]);
    }

    public function insert_assurance(Request $request)
    {
        $verifications = [
            'tel' => $request->tel,
            'tel2' => $request->tel2,
            'email' => $request->email,
            'nom' => $request->nom,
            'fax' => $request->fax,
        ];

        $assuranceExist = assurance::where(function($query) use ($verifications) {
            $query->where('tel', $verifications['tel'])
                  ->orWhere('tel2', $verifications['tel2'])
                  ->orWhere('email', $verifications['email'])
                  ->orWhere('nom', $verifications['nom'])
                  ->orWhere('fax', $verifications['fax']);
        })->first();

        if ($assuranceExist) {
            if ($assuranceExist->tel === $verifications['tel'] || $assuranceExist->tel2 === $verifications['tel2']) {
                return back()->with('error', 'Ce numéro de téléphone appartient déjà à une assurance');
            } elseif ($assuranceExist->email === $verifications['email']) {
                return back()->with('error', 'L\'email appartient déjà à une assurance');
            } elseif ($assuranceExist->nom === $verifications['nom']) {
                return back()->with('error', $request->nom . ' existe déjà');
            } elseif ($assuranceExist->fax === $verifications['fax']) {
                return back()->with('error', 'Ce fax appartient déjà à une assurance');
            }
        }


        $add = new assurance();
        $add->nom = $request->nom;
        $add->email = $request->email;
        $add->tel = $request->tel;
        $add->tel2 = $request->tel2;
        $add->fax = $request->fax;
        $add->adresse = $request->adresse;
        $add->localisation = $request->localisation;

        if($add->save()){
            return redirect()->back()->with('success', 'Assurance enregsitrer');
        }else{
            return redirect()->back()->with('error', 'Echec de l\'enregsitrement de  l\'assurance ');
        }
    }

    public function update_assurance(Request $request, $id)
    {
        $verifications = [
            'tel' => $request->tel_modif,
            'tel2' => $request->tel2_modif,
            'email' => $request->email_modif,
            'nom' => $request->nom_modif,
            'fax' => $request->fax_modif,
        ];

        $assuranceExist = assurance::where(function($query) use ($verifications) {
            $query->where('tel', $verifications['tel'])
                  ->orWhere('tel2', $verifications['tel2'])
                  ->orWhere('email', $verifications['email'])
                  ->orWhere('nom', $verifications['nom'])
                  ->orWhere('fax', $verifications['fax']);
        })->where('id', '!=', $id)->first();

        if ($assuranceExist) {
            if ($assuranceExist->tel === $verifications['tel'] || $assuranceExist->tel2 === $verifications['tel2']) {
                return back()->with('error', 'Ce numéro de téléphone appartient déjà à une assurance');
            } elseif ($assuranceExist->email === $verifications['email']) {
                return back()->with('error', 'L\'email appartient déjà à une assurance');
            } elseif ($assuranceExist->nom === $verifications['nom']) {
                return back()->with('error', $request->nom . ' existe déjà');
            } elseif ($assuranceExist->fax === $verifications['fax']) {
                return back()->with('error', 'Ce fax appartient déjà à une assurance');
            }
        }

        $update = assurance::find($id);
        $update->nom = $request->nom_modif;
        $update->email = $request->email_modif;
        $update->tel = $request->tel_modif;
        $update->tel2 = $request->tel2_modif;
        $update->fax = $request->fax_modif;
        $update->adresse = $request->adresse_modif;
        $update->localisation = $request->localisation_modif;

        if($update->save()){
            return redirect()->back()->with('success', 'Assurance mise à jour');
        }else{
            return redirect()->back()->with('error', 'Echec de la mise à jour de l\'assurance ');
        }
    }
}
