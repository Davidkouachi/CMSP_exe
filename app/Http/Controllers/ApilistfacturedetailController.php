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

class ApilistfacturedetailController extends Controller
{
    public function list_facture_inpayer_d($id)
    {

        // $facture_cons = DB::table('consultation')
        //     ->join('patient', 'consultation.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
        //     ->join('dossierpatient', 'consultation.idenregistremetpatient', '=', 'dossierpatient.idenregistremetpatient')
        //     ->leftJoin('societeassure', 'patient.codesocieteassure', '=', 'societeassure.codesocieteassure')
        //     ->leftJoin('tauxcouvertureassure', 'patient.idtauxcouv', '=', 'tauxcouvertureassure.idtauxcouv')
        //     ->leftJoin('assurance', 'patient.codeassurance', '=', 'assurance.codeassurance')
        //     ->leftJoin('filiation', 'patient.codefiliation', '=', 'filiation.codefiliation')
        //     ->join('medecin', 'consultation.codemedecin', '=', 'medecin.codemedecin')
        //     ->join('specialitemed', 'medecin.codespecialitemed', '=', 'specialitemed.codespecialitemed')
        //     ->join('garantie', 'consultation.codeacte', '=', 'garantie.codgaran')
        //     ->join('factures', 'consultation.numfac', '=', 'factures.numfac')
        //     ->where('consultation.idconsexterne', '=', $id)
        //     ->select(
        //         'consultation.idconsexterne as idconsexterne',
        //         'consultation.idenregistremetpatient as idenregistremetpatient',
        //         'consultation.montant as montant',
        //         'consultation.date as date',
        //         'consultation.numfac as numfac',

        //         'consultation.numbon as numbon',
        //         'consultation.ticketmod as partpatient',
        //         'consultation.partassurance as partassurance',

        //         'dossierpatient.numdossier as numdossier',
        //         'patient.nomprenomspatient as nom_patient',
        //         'patient.telpatient as tel_patient',
        //         'patient.assure as assure',
        //         'patient.datenaispatient as datenais',
        //         'patient.telpatient as telpatient',
        //         'patient.matriculeassure as matriculeassure',
        //         'medecin.nomprenomsmed as nom_medecin',
        //         'medecin.contact as tel_medecin',
        //         'specialitemed.nomspecialite as specialite',
        //         'factures.remise as remise',
        //         'factures.montantregle_pat as montant_regle',
        //         'factures.montantreste_pat as montant_reste',
        //         'factures.datereglt_pat as date_regle',
        //         'factures.numrecu as numrecu',
        //         'societeassure.nomsocieteassure as societe',
        //         'assurance.libelleassurance as assurance',
        //         'tauxcouvertureassure.valeurtaux as taux',
        //         'filiation.libellefiliation as filiation',
        //     )
        //     ->first();

        return response()->json(['facture_cons' => $facture_cons]);
    }

    // public function list_facture_hos_d($id)
    // {
    //     $hos = soinshopital::find($id);

    //     $factured = soinshopital::join('produits', 'produits.id', '=', 'soinshopitals.produit_id')
    //                         ->where('soinshopitals.detailhopital_id', '=', $id)
    //                         ->select(
    //                             'soinshopitals.*',
    //                             'produits.nom as nom_produit',
    //                             'produits.prix as prix_produit',
    //                         )
    //                         ->orderBy('soinshopitals.created_at', 'desc')
    //                         ->get();

    //     return response()->json(['factured' => $factured]);
    // }

    public function list_facture_exam_d($id)
    {
        $factured = DB::table('detailtestlaboimagerie')
            ->where('idtestlaboimagerie', '=', $id)
            ->select(
                'detailtestlaboimagerie.denomination as examen',
                'detailtestlaboimagerie.resultat as resultat',
                'detailtestlaboimagerie.prix as prix',
            )
            ->get();

        $sumMontantEx = $factured->sum(function ($item) {
            // Retirer le point du montant et le convertir en entier
            $montantEx = str_replace('.', '', $item->prix);
            return (int) $montantEx;
        });

        return response()->json(['factured' => $factured, 'sumMontantEx' => $sumMontantEx]);
    }
}
