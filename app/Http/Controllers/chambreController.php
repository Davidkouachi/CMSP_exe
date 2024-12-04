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

use App\Models\chambre;

class chambreController extends Controller
{
    public function chambre_new()
    {
        $today = Carbon::today();
        $chams = chambre::whereDate('created_at', $today)
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('infirmerie.nouveau.chambre',['chams' => $chams]);
    }

    public function insert_chambre(Request $request)
    {

    }

    public function update_chambre(Request $request, $id)
    {

    }
}
