	<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AccueilController;
use App\Http\Controllers\assuranceController;
use App\Http\Controllers\assureurController;
use App\Http\Controllers\produitController;
use App\Http\Controllers\societeController;
use App\Http\Controllers\utilisateurController;
use App\Http\Controllers\authController;
use App\Http\Controllers\chambreController;
use App\Http\Controllers\litController;
use App\Http\Controllers\tauxController;
use App\Http\Controllers\typeproduitController;
use App\Http\Controllers\receptionController;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;


Route::get('/Login', [authController::class, 'login'])->name('login');
Route::post('/trait_login', [authController::class, 'trait_login'])->name('trait_login');
// Route::get('/refresh_csrf', [authController::class, 'refresh_csrf'])->name('refresh_csrf');

Route::get('/refresh-csrf', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

Route::middleware(['auth','statutchambre','dateRdv'])->group(function () {

	Route::get('/', [AccueilController::class, 'index_accueil'])->name('index_accueil');

	Route::get('/A propos', [AccueilController::class, 'index_propos'])->name('index_propos');

	// Route::get('/Reception', [receptionController::class,'index_reception'])->name('index_reception');

	Route::post('/trait_auth', [authController::class, 'trait_auth'])->name('trait_auth');
	Route::get('/deconnecter', [authController::class, 'deconnecter'])->name('deconnecter');
	Route::get('/Mot de passe oublié', [authController::class, 'mdp_oublie_email'])->name('mdp_oublie_email');

	Route::get('/Nouvelle Assurance', [assuranceController::class, 'assurance_new'])->name('assurance_new');
	Route::post('/insert assurance', [assuranceController::class, 'insert_assurance'])->name('insert_assurance');
	Route::post('/update assurance/{id}', [assuranceController::class, 'update_assurance'])->name('update_assurance');


	Route::get('/Nouvel Assureur', [assureurController::class, 'assureur_new'])->name('assureur_new');
	Route::post('/insert assureur', [assureurController::class, 'insert_assureur'])->name('insert_assureur');
	Route::post('/update assureur/{id}', [assureurController::class, 'update_assureur'])->name('update_assureur');

	Route::get('/Nouvel Chambre', [chambreController::class, 'chambre_new'])->name('chambre_new');
	Route::post('/insert chambre', [chambreController::class, 'insert_chambre'])->name('insert_chambre');
	Route::post('/update chambre/{id}', [chambreController::class, 'update_chambre'])->name('update_chambre');

	Route::get('/Nouvel Lit', [litController::class, 'lit_new'])->name('lit_new');
	Route::post('/insert lit', [litController::class, 'insert_lit'])->name('insert_lit');
	Route::post('/update lit/{id}', [litController::class, 'update_lit'])->name('update_lit');

	Route::get('/Société', [Controller::class, 'societe_liste'])->name('societe_liste');

	Route::get('/Assurances', [Controller::class, 'assurance_liste'])->name('assurance_liste');

	Route::get('/Nouvel Taux', [tauxController::class, 'taux_new'])->name('taux_new');
	Route::post('/insert taux', [tauxController::class, 'insert_taux'])->name('insert_taux');
	Route::post('/update taux/{id}', [tauxController::class, 'update_taux'])->name('update_taux');

	Route::get('/Nouvel Type Produit', [typeproduitController::class, 'typeproduit_new'])->name('typeproduit_new');
	Route::post('/insert typeproduit', [typeproduitController::class, 'insert_typeproduit'])->name('insert_typeproduit');
	Route::post('/update typeproduit/{id}', [typeproduitController::class, 'update_typeproduit'])->name('update_typeproduit');

	Route::get('/Facture Impayer', [Controller::class, 'facture_impayer'])->name('facture_impayer');
	Route::get('/Liste Facture', [Controller::class, 'facture_liste'])->name('facture_liste');

	Route::get('/Assureur', [Controller::class, 'assureur_liste'])->name('assureur_liste');

	Route::get('/Acte', [Controller::class, 'acte_new'])->name('acte_new');
	Route::get('/Type Acte', [Controller::class, 'typeacte_new'])->name('typeacte_new');
	Route::get('/Nouvel Medecin', [utilisateurController::class, 'medecin_new'])->name('medecin_new');
	// Route::get('/Consultation Caisse', [Controller::class, 'encaissement_cons'])->name('encaissement_cons');
	// Route::get('/Consultation Liste Caisse', [Controller::class, 'liste_caisse_cons'])->name('liste_caisse_cons');
	Route::get('/Consultation', [Controller::class,'consultation_liste'])->name('consultation_liste');

	Route::get('/Patient', [Controller::class,'patient_liste'])->name('patient_liste');
	Route::get('/Utilisateurs', [Controller::class,'utilisateur'])->name('utilisateur');

	Route::get('/Hospitalisation', [Controller::class, 'hospitalisation'])->name('hospitalisation');
	Route::get('/Type Admission', [Controller::class, 'typeadmission_new'])->name('typeadmission_new');
	Route::get('/Nature Admission', [Controller::class, 'natureadmission_new'])->name('natureadmission_new');
	// Route::get('/Hospitalisation Caisse', [Controller::class, 'encaissement_hos'])->name('encaissement_hos');
	// Route::get('/Hospitalisation Liste Caisse', [Controller::class, 'liste_caisse_hos'])->name('liste_caisse_hos');

	Route::get('/Produit Pharmacie', [Controller::class, 'produit_new'])->name('produit_new');

	Route::get('/Spécialité', [utilisateurController::class, 'specialite'])->name('specialite');

	Route::get('/Soins Ambulantoires', [Controller::class, 'soinsam'])->name('soinsam');
	// Route::get('/Soins Ambulatoire Caisse', [Controller::class, 'encaissement_soinsam'])->name('encaissement_soinsam');
	// Route::get('/Soins Ambulatoire Liste Caisse', [Controller::class, 'liste_caisse_soinsam'])->name('liste_caisse_soinsam');

	Route::get('/Type de soins',[Controller::class,'typesoins_new'])->name('typesoins_new');
	Route::get('/Soins Infirmiers', [Controller::class, 'soinsinfirmier_new'])->name('soinsinfirmier_new');

	Route::get('/Examens', [Controller::class, 'examen'])->name('examen');
	// Route::get('/Examens Caisse', [Controller::class, 'encaissement_examen'])->name('encaissement_examen');
	// Route::get('/Examens Liste Caisse', [Controller::class, 'liste_caisse_examen'])->name('liste_caisse_examen');

	Route::get('/Horaires Médecin', [Controller::class, 'horaire_medecin'])->name('horaire_medecin');
	Route::get('/Rendez-Vous', [Controller::class, 'rdv_two_day'])->name('rdv_two_day');

	Route::get('/Caisse', [Controller::class, 'caisse'])->name('caisse');
	Route::get('/Opération de Caisse', [Controller::class, 'operation_caisse'])->name('operation_caisse');
	Route::get('/Tableau de Bord Comptabilité', [Controller::class, 'comptable'])->name('comptable');
	Route::get('/Factures Emises', [Controller::class, 'facture_emise'])->name('facture_emise');
	Route::get('/Depôts de factures', [Controller::class, 'facture_depot'])->name('facture_depot');
	Route::get('/Factures', [Controller::class, 'facture_deposer'])->name('facture_deposer');

	Route::get('/Etats Actes', [Controller::class, 'etat_acte'])->name('etat_acte');
	Route::get('/Etats Factures', [Controller::class, 'etat_facture'])->name('etat_facture');
	Route::get('/Etats Caisses', [Controller::class, 'etat_caisse'])->name('etat_caisse');

// Route::middleware(['role:ADMINISTRATEUR'])->group(function () {

// });

// Route::middleware(['auth'])->group(function () {

// });

});

