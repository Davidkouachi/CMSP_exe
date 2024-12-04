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

use App\Models\lit;
use App\Models\chambre;

class litController extends Controller
{
    public function lit_new()
    {
        return view('infirmerie.nouveau.lit');
    }

    public function insert_lit(Request $request)
    {

    }

    public function update_lit(Request $request, $id)
    {

    }
}
