<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ApiinsertController;
use App\Http\Controllers\ApisearchController;
use App\Http\Controllers\ApilistController;
use App\Http\Controllers\ApiupdateController;
use App\Http\Controllers\ApideleteController;
use App\Http\Controllers\ApistatController;
use App\Http\Controllers\ApilistfactureController;
use App\Http\Controllers\ApilistfacturedetailController;
use App\Http\Controllers\ApiinsertfactureController;
use App\Http\Controllers\ApipdfController;
use App\Http\Controllers\ApihistoriqueController;
use App\Http\Controllers\authController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::get('/refresh-csrf', function() {
//     return response()->json(['csrf_token' => csrf_token()]);
// });

Route::middleware(['web'])->group(function () {

	// Login debut
	Route::post('/trait_login', [authController::class, 'trait_login']);
	// Login debut

	// update debut
	Route::put('/update_user/{matricule}', [ApiupdateController::class, 'update_user']);
	Route::put('/update_mdp/{matricule}', [ApiupdateController::class, 'update_mdp']);
	// update fin
});

Route::middleware(['web','statutchambre','dateRdv'])->group(function () {

	// recherche debut
	Route::get('/taux_select_patient_new', [ApiController::class, 'taux_select_patient_new']);
	Route::get('/societe_select_patient_new', [ApiController::class, 'societe_select_patient_new']);
	Route::get('/assurance_select_patient_new', [ApiController::class, 'assurance_select_patient_new']);
	Route::get('/filiation_select_patient_new', [ApiController::class, 'filiation_select_patient_new']);
	Route::get('/select_medecin', [ApiController::class, 'select_medecin']);
	Route::get('/select_assureur', [ApiController::class, 'select_assureur']);
	Route::get('/select_typegarantie', [ApiController::class, 'select_typegarantie']);
	Route::get('/select_garantie', [ApiController::class, 'select_garantie']);
	Route::get('/select_typesoins', [ApiController::class, 'select_typesoins']);
	Route::get('/select_category_medicine', [ApiController::class, 'select_category_medicine']);
	Route::get('/select_produit', [ApiController::class, 'select_produit']);
	Route::get('/select_type_examen', [ApiController::class, 'select_type_examen']);
	Route::get('/select_type_examend', [ApiController::class, 'select_type_examend']);
	Route::get('/prix_examen/{id}', [ApiController::class, 'prix_examen']);
	// recherche fin

	// insert debut
	Route::get('/societe_new', [ApiinsertController::class, 'societe_new']);
	Route::get('/assurance_new', [ApiinsertController::class, 'assurance_new']);
	Route::get('/patient_new', [ApiinsertController::class, 'patient_new']);
	Route::get('/chambre_new', [ApiinsertController::class, 'chambre_new']);
	Route::get('/lit_new', [ApiinsertController::class, 'lit_new']);
	Route::get('/motif_cons_new', [ApiinsertController::class, 'motif_cons_new']);
	Route::get('/typeacte_cons_new', [ApiinsertController::class, 'typeacte_cons_new']);
	Route::get('/new_medecin', [ApiinsertController::class, 'new_medecin']);
	Route::get('/new_consultation', [ApiinsertController::class, 'new_consultation']);
	Route::get('/new_typeadmission', [ApiinsertController::class, 'new_typeadmission']);
	Route::get('/new_natureadmission', [ApiinsertController::class, 'new_natureadmission']);
	Route::get('/hosp_new', [ApiinsertController::class, 'hosp_new']);
	Route::get('/new_produit', [ApiinsertController::class, 'new_produit']);
	Route::get('/add_soinshopital/{id}', [ApiinsertController::class, 'add_soinshopital']);
	Route::get('/new_typesoins', [ApiinsertController::class, 'new_typesoins']);
	Route::get('/new_soinsIn', [ApiinsertController::class, 'new_soinsIn']);
	Route::get('/new_soinsam', [ApiinsertController::class, 'new_soinsam']);
	Route::get('/examen_new', [ApiinsertController::class, 'examen_new']);
	Route::get('/new_examend', [ApiinsertController::class, 'new_examend']);
	Route::get('/new_horaire', [ApiinsertController::class, 'new_horaire']);
	Route::get('/new_rdv', [ApiinsertController::class, 'new_rdv']);
	Route::get('/specialite_new', [ApiinsertController::class, 'specialite_new']);
	Route::get('/new_depot_fac', [ApiinsertController::class, 'new_depot_fac']);
	Route::get('/paiement_depot_fac/{id}', [ApiinsertController::class, 'paiement_depot_fac']);
	Route::get('/ope_caisse_new', [ApiinsertController::class, 'ope_caisse_new']);
	Route::get('/new_user', [ApiinsertController::class, 'new_user']);
	Route::get('/caisse_ouvert', [ApiinsertController::class, 'caisse_ouvert']);
	Route::get('/caisse_fermer', [ApiinsertController::class, 'caisse_fermer']);
	Route::get('/assureur_new', [ApiinsertController::class, 'assureur_new']);
	Route::get('/type_garantie_new', [ApiinsertController::class, 'type_garantie_new']);
	Route::get('/garantie_new', [ApiinsertController::class, 'garantie_new']);
	Route::get('/tarif_new', [ApiinsertController::class, 'tarif_new']);
	// insert debut

	// search debut
	Route::get('/rech_patient', [ApisearchController::class, 'rech_patient']);
	Route::get('/rech_patient_hos/{code}', [ApisearchController::class, 'rech_patient_hos']);
	Route::get('/refresh_num_chambre', [ApisearchController::class, 'refresh_num_chambre']);
	Route::get('/refresh_num_lit', [ApisearchController::class, 'refresh_num_lit']);
	Route::get('/list_chambre_select', [ApisearchController::class, 'list_chambre_select']);
	Route::get('/select_specialite', [ApisearchController::class, 'select_specialite']);
	Route::get('/select_typeacte/{codeassurance}', [ApisearchController::class, 'select_typeacte']);
	Route::get('/name_patient_reception', [ApisearchController::class, 'name_patient_reception']);
	Route::get('/name_patient_examen', [ApisearchController::class, 'name_patient_examen']);
	Route::get('/name_patient', [ApisearchController::class, 'name_patient']);
	Route::get('/lit_select/{id}', [ApisearchController::class, 'lit_select']);
	Route::get('/natureadmission_select/{id}', [ApisearchController::class, 'natureadmission_select']);
	Route::get('/select_soinsIn/{id}', [ApisearchController::class, 'select_soinsIn']);
	Route::get('/list_acte_ex', [ApisearchController::class, 'list_acte_ex']);
	Route::get('/montant_prelevement', [ApisearchController::class, 'montant_prelevement']);
	Route::get('/select_examen/{id}/{codeassurance}/{periode}', [ApisearchController::class, 'select_examen']);
	Route::get('/select_specialite', [ApisearchController::class, 'select_specialite']);
	Route::get('/select_jour', [ApisearchController::class, 'select_jour']);
	Route::get('/montant_solde', [ApisearchController::class, 'montant_solde']);
	Route::get('/list_caissier', [ApisearchController::class, 'list_caissier']);
	Route::get('/select_profil', [ApisearchController::class, 'select_profil']);
	Route::get('/rech_hos_patient/{id}', [ApisearchController::class, 'rech_hos_patient']);
	Route::get('/verf_caisse', [ApisearchController::class, 'verf_caisse']);
	Route::get('/select_list_medecin', [ApisearchController::class, 'select_list_medecin']);
	Route::get('/select_typeadmission', [ApisearchController::class, 'select_typeadmission']);
	Route::get('/select_chambre', [ApisearchController::class, 'select_chambre']);

	Route::get('/select_civilite', [ApisearchController::class, 'select_civilite']);
	Route::get('/select_service', [ApisearchController::class, 'select_service']);
	Route::get('/select_contrat', [ApisearchController::class, 'select_contrat']);
	// search debut

	// liste day debut
	Route::get('/list_cons_day', [ApilistController::class, 'list_cons_day']);
	Route::get('/list_cons', [ApilistController::class, 'list_cons']);
	Route::get('/list_rdv_day', [ApilistController::class, 'list_rdv_day']);
	// liste day debut

	// update debut
	Route::get('/update_chambre/{id}', [ApiupdateController::class, 'update_chambre']);
	Route::get('/update_lit/{id}', [ApiupdateController::class, 'update_lit']);
	Route::get('/update_acte/{id}', [ApiupdateController::class, 'update_acte']);
	Route::get('/update_typeacte/{id}', [ApiupdateController::class, 'update_typeacte']);
	Route::get('/update_medecin/{matricule}', [ApiupdateController::class, 'update_medecin']);
	Route::get('/update_typeadmission/{id}', [ApiupdateController::class, 'update_typeadmission']);
	Route::get('/update_natureadmission/{id}', [ApiupdateController::class, 'update_natureadmission']);
	Route::get('/update_produit/{id}', [ApiupdateController::class, 'update_produit']);
	Route::get('/appro_produit/{id}', [ApiupdateController::class, 'appro_produit']);
	Route::get('/update_typesoins/{id}', [ApiupdateController::class, 'update_typesoins']);
	Route::get('/update_soinIn/{id}', [ApiupdateController::class, 'update_soinIn']);
	Route::get('/update_societe/{id}', [ApiupdateController::class, 'update_societe']);
	Route::get('/examen_Modif/{id}', [ApiupdateController::class, 'examen_Modif']);
	Route::get('/prelevement_Modif', [ApiupdateController::class, 'prelevement_Modif']);
	Route::get('/update_horaire/{id}', [ApiupdateController::class, 'update_horaire']);
	Route::get('/update_rdv/{id}', [ApiupdateController::class, 'update_rdv']);
	Route::get('/update_specialite/{code}', [ApiupdateController::class, 'update_specialite']);
	Route::get('/update_depot_fac/{id}', [ApiupdateController::class, 'update_depot_fac']);
	Route::get('/update_assurance/{id}', [ApiupdateController::class, 'update_assurance']);
	Route::get('/update_date_hos/{id}', [ApiupdateController::class, 'update_date_hos']);
	Route::get('/patient_modif/{matricule}', [ApiupdateController::class, 'patient_modif']);
	Route::get('/update_assureur/{id}', [ApiupdateController::class, 'update_assureur']);
	Route::get('/update_type_garantie/{code}', [ApiupdateController::class, 'update_type_garantie']);
	Route::get('/update_garantie/{code}', [ApiupdateController::class, 'update_garantie']);
	Route::get('/update_tarif/{id}', [ApiupdateController::class, 'update_tarif']);
	// update debut

	// delete debut
	Route::get('/delete_chambre/{id}', [ApideleteController::class, 'delete_chambre']);
	Route::get('/delete_lit/{id}', [ApideleteController::class, 'delete_lit']);
	Route::get('/delete_acte/{id}', [ApideleteController::class, 'delete_acte']);
	Route::get('/delete_typeacte/{id}', [ApideleteController::class, 'delete_typeacte']);
	Route::get('/delete_medecin/{matricule}', [ApideleteController::class, 'delete_medecin']);
	Route::get('/delete_typeadmission/{id}', [ApideleteController::class, 'delete_typeadmission']);
	Route::get('/delete_natureadmission/{id}', [ApideleteController::class, 'delete_natureadmission']);
	Route::get('/delete_typesoins/{id}', [ApideleteController::class, 'delete_typesoins']);
	Route::get('/delete_soinsIn/{id}', [ApideleteController::class, 'delete_soinsIn']);
	Route::get('/delete_societe/{id}', [ApideleteController::class, 'delete_societe']);
	Route::get('/delete_rdv/{id}', [ApideleteController::class, 'delete_rdv']);
	Route::get('/delete_specialite/{id}', [ApideleteController::class, 'delete_specialite']);
	Route::get('/delete_depotfacture/{id}', [ApideleteController::class, 'delete_depotfacture']);
	Route::get('/delete_Cons/{numfac}', [ApideleteController::class, 'delete_Cons']);
	Route::get('/delete_user/{matricule}', [ApideleteController::class, 'delete_user']);
	Route::get('/delete_assurance/{id}', [ApideleteController::class, 'delete_assurance']);
	Route::get('/delete_assureur/{id}', [ApideleteController::class, 'delete_assureur']);
	Route::get('/delete_tarif/{id}', [ApideleteController::class, 'delete_tarif']);
	// delete debut

	// liste debut
	Route::get('/list_acte', [ApilistController::class, 'list_acte']);
	Route::get('/list_typeacte', [ApilistController::class, 'list_typeacte']);
	Route::get('/list_user', [ApilistController::class, 'list_user']);
	Route::get('/list_medecin', [ApilistController::class, 'list_medecin']);
	Route::get('/list_chambre', [ApilistController::class, 'list_chambre']);
	Route::get('/list_lit', [ApilistController::class, 'list_lit']);
	Route::get('/list_typeadmission', [ApilistController::class, 'list_typeadmission']);
	Route::get('/list_natureadmission', [ApilistController::class, 'list_natureadmission']);
	Route::get('/list_hopital/{date1}/{date2}/{statut}', [ApilistController::class, 'list_hopital']);
	Route::get('/detail_hos/{id}', [ApilistController::class, 'detail_hos']);
	Route::get('/list_produit', [ApilistController::class, 'list_produit']);
	Route::get('/list_produit_all', [ApilistController::class, 'list_produit_all']);
	Route::get('/list_patient_all', [ApilistController::class, 'list_patient_all']);
	Route::get('/list_cons_all/{date1}/{date2}', [ApilistController::class, 'list_cons_all']);
	Route::get('/list_typesoins', [ApilistController::class, 'list_typesoins']);
	Route::get('/list_soinsIn', [ApilistController::class, 'list_soinsIn']);
	Route::get('/list_soinsam_all/{date1}/{date2}', [ApilistController::class, 'list_soinsam_all']);
	Route::get('/detail_soinam/{id}', [ApilistController::class, 'detail_soinam']);
	Route::get('/list_societe_all', [ApilistController::class, 'list_societe_all']);
	Route::get('/list_examen_all', [ApilistController::class, 'list_examen_all']);
	Route::get('/list_examend_all/{date1}/{date2}', [ApilistController::class, 'list_examend_all']);
	Route::get('/detail_examen/{id}', [ApilistController::class, 'detail_examen']);
	Route::get('/select_jours', [ApilistController::class, 'select_jours']);
	Route::get('/list_horaire/{medecin}/{specialite}/{jour}/{periode}', [ApilistController::class, 'list_horaire']);
	Route::get('/list_rdv', [ApilistController::class, 'list_rdv']);
	Route::get('/list_specialite', [ApilistController::class, 'list_specialite']);
	Route::get('/list_depotfacture', [ApilistController::class, 'list_depotfacture']);
	Route::get('/list_cons_patient/{id}', [ApilistController::class, 'list_cons_patient']);
	Route::get('/list_examend_patient/{id}', [ApilistController::class, 'list_examend_patient']);
	Route::get('/list_hopital_patient/{id}', [ApilistController::class, 'list_hopital_patient']);
	Route::get('/list_soinsam_patient/{id}', [ApilistController::class, 'list_soinsam_patient']);
	Route::get('/list_assurance_all', [ApilistController::class, 'list_assurance_all']);
	Route::get('/trace_operation/{date1}/{date2}', [ApilistController::class, 'trace_operation']);
	Route::get('/list_rdv_two_days', [ApilistController::class, 'list_rdv_two_days']);
	Route::get('/trace_ouvert_fermer/{date1}/{date2}', [ApilistController::class, 'trace_ouvert_fermer']);
	Route::get('/list_assureur_all', [ApilistController::class, 'list_assureur_all']);
	Route::get('/list_type_garantie', [ApilistController::class, 'list_type_garantie']);
	Route::get('/list_garantie', [ApilistController::class, 'list_garantie']);
	Route::get('/list_tarif', [ApilistController::class, 'list_tarif']);
	// liste debut

	// statistique debut
	Route::get('/statistique_reception', [ApistatController::class, 'statistique_reception']);
	Route::get('/statistique_caisse', [ApistatController::class, 'statistique_caisse']);
	Route::get('/statistique_reception_cons', [ApistatController::class, 'statistique_reception_cons']);
	Route::get('/statistique_cons', [ApistatController::class, 'statistique_cons']);
	Route::get('/getWeeklyConsultations', [ApistatController::class, 'getWeeklyConsultations']);
	Route::get('/getConsultationComparison', [ApistatController::class, 'getConsultationComparison']);
	Route::get('/statistique_hos', [ApistatController::class, 'statistique_hos']);
	Route::get('/statistique_soinsam', [ApistatController::class, 'statistique_soinsam']);
	Route::get('/statistique_examen', [ApistatController::class, 'statistique_examen']);
	Route::get('/stat_comp_acte/{yearSelect}', [ApistatController::class, 'stat_comp_acte']);
	Route::get('/stat_comp_ope/{yearSelect}', [ApistatController::class, 'stat_comp_ope']);
	Route::get('/stat_acte_mois/{date1}/{date2}', [ApistatController::class, 'stat_acte_mois']);
	Route::get('/statistique_patient', [ApistatController::class, 'statistique_patient']);
	Route::get('/patient_stat/{id}', [ApistatController::class, 'patient_stat']);
	Route::get('/assurance_stat/{id}', [ApistatController::class, 'assurance_stat']);
	Route::get('/count_rdv_two_day', [ApistatController::class, 'count_rdv_two_day']);
	Route::get('/stat_chiff_acte/{yearSelect}', [ApistatController::class, 'stat_chiff_acte']);
	// statistique fin

	// List facture debut
	Route::get('/list_facture_inpayer/{numfac}', [ApilistfactureController::class, 'list_facture_inpayer']);
	Route::get('/list_facture/{date1}/{date2}', [ApilistfactureController::class, 'list_facture']);
	Route::get('/list_facture_hos', [ApilistfactureController::class, 'list_facture_hos']);
	Route::get('/list_facture_hos_all/{date1}/{date2}/{statut}', [ApilistfactureController::class, 'list_facture_hos_all']);
	Route::get('/list_facture_soinsam/{numfac}', [ApilistfactureController::class, 'list_facture_soinsam']);
	Route::get('/list_facture_soinsam_all/{date1}/{date2}', [ApilistfactureController::class, 'list_facture_soinsam_all']);
	Route::get('/list_facture_examen/{numfac}', [ApilistfactureController::class, 'list_facture_examen']);
	Route::get('/list_facture_examen_all/{date1}/{date2}', [ApilistfactureController::class, 'list_facture_examen_all']);
	// List facture fin

	// List facture detail debut
	Route::get('/list_facture_inpayer_d/{id}', [ApilistfacturedetailController::class, 'list_facture_inpayer_d']);
	Route::get('/list_facture_hos_d/{id}', [ApilistfacturedetailController::class, 'list_facture_hos_d']);
	Route::get('/list_facture_exam_d/{id}', [ApilistfacturedetailController::class, 'list_facture_exam_d']);
	// List facture fin

	// paiement facture debut
	Route::get('/facture_payer/{numfac}', [ApiinsertfactureController::class, 'facture_payer']);
	Route::get('/facture_payer_hos/{code_fac}', [ApiinsertfactureController::class, 'facture_payer_hos']);
	Route::get('/facture_payer_soinsam/{numfac}', [ApiinsertfactureController::class, 'facture_payer_soinsam']);
	Route::get('/facture_payer_examen/{numfac}', [ApiinsertfactureController::class, 'facture_payer_examen']);
	// paiement facture fin

	// PDF debut
	Route::get('/fiche_consultation/{code}', [ApipdfController::class, 'fiche_consultation']);
	Route::get('/facture_inpayer_cons/{id}', [ApipdfController::class, 'facture_inpayer_cons']);

	Route::get('/imp_fac_soinam/{id}', [ApipdfController::class, 'imp_fac_soinam']);
	// PDF fin

	// Etat debut
	Route::get('/imp_fac_assurance', [ApipdfController::class, 'imp_fac_assurance']);
	Route::get('/imp_fac_assurance_bordo', [ApipdfController::class, 'imp_fac_assurance_bordo']);
	Route::get('/imp_fac_depot/{id}', [ApipdfController::class, 'imp_fac_depot']);
	Route::get('/imp_fac_depot_bordo/{id}', [ApipdfController::class, 'imp_fac_depot_bordo']);

	Route::get('/etat_fac_assurance', [ApipdfController::class, 'etat_fac_assurance']);
	Route::get('/etat_fac_encaissement', [ApipdfController::class, 'etat_fac_encaissement']);
	Route::get('/etat_fac_ope_caisse', [ApipdfController::class, 'etat_fac_ope_caisse']);
	Route::get('/etat_fac_acte', [ApipdfController::class, 'etat_fac_acte']);
	// Etat fin

	// Historique debut
	Route::get('/historique_caisse/{date}', [ApihistoriqueController::class, 'historique_caisse']);
	// Historique fin

});