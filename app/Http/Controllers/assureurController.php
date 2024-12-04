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

use App\Models\assureur;

class assureurController extends Controller
{
    public function assureur_new()
    {
        $today = Carbon::today();
        $assus = assureur::whereDate('created_at', $today)
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('assurance.nouveau.assureur',['assus' => $assus]);
    }

    public function insert_assureur(Request $request)
    {

    }

    public function update_assureur(Request $request, $id)
    {

    }
}
