@extends('app')

@section('titre', 'Nouveau Produit')

@section('info_page')
<div class="app-hero-header d-flex align-items-center">
    <!-- Breadcrumb starts -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-bar-chart-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{route('index_accueil')}}">Espace Santé</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            Nouveau Produit
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">

    <div class="row justify-content-center" id="div_caisse_verf" style="display: none;">
        <div class="col-12">
            <div class="card mb-3 bg-3">
                <div class="rounded-2" style="background: rgba(0, 0, 0, 0.7);" >
                    <div class="card-body row gx-3 d-flex align-items-center justify-content-between">
                        <div class="col-12">
                            <div class="mb-1 text-center">
                                <a class="d-flex align-items-center flex-column">
                                    <img src="{{asset('assets/images/caisse.jpg')}}" class="img-7x rounded-circle border border-3">
                                </a>
                            </div>
                        </div>
                        <div class="col-12" id="btn_ouvert">
                            <div class="mb-1 text-center">
                                <button id="btn_ouvert_C" type="button" class="btn btn-success">
                                    Ouverture de Caisse
                                    <i class="ri-door-open-line"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-12" id="btn_fermer">
                            <div class="mb-1 text-center">
                                <button id="btn_fermer_C" type="button" class="btn btn-danger">
                                    Fermeture de Caisse
                                    <i class="ri-door-close-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="div_caisse" style="display: none;" >
        
        <div class="row gx-3" >
            <div class="col-sm-12">
                <div class="card mb-3 mt-3">
                    <div class="card-body" style="margin-top: -30px;">
                        <div class="custom-tabs-container">
                            <ul class="nav nav-tabs justify-content-center bg-primary bg-2" id="customTab4" role="tablist" style="background: rgba(0, 0, 0, 0.7);">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active text-white" id="tab-twoA1" data-bs-toggle="tab" href="#twoA1" role="tab" aria-controls="twoA1" aria-selected="false" tabindex="-1">
                                        <i class="ri-article-line me-2"></i>
                                        Consulation(s)
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-white" id="tab-twoA2" data-bs-toggle="tab" href="#twoA2" role="tab" aria-controls="twoA2" aria-selected="false" tabindex="-1">
                                        <i class="ri-article-line me-2"></i>
                                        Examen(s)
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-white" id="tab-twoA3" data-bs-toggle="tab" href="#twoA3" role="tab" aria-controls="twoA3" aria-selected="false" tabindex="-1">
                                        <i class="ri-article-line me-2"></i>
                                        Hospitalisation(s)
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-white" id="tab-twoA4" data-bs-toggle="tab" href="#twoA4" role="tab" aria-controls="twoA4" aria-selected="false" tabindex="-1">
                                        <i class="ri-article-line me-2"></i>
                                        Soins Ambulatoire(s)
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content" id="customTabContent">
                                <div class="tab-pane active show fade" id="twoA1" role="tabpanel" aria-labelledby="tab-twoA1">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h5 class="card-title">
                                            Consultation(s)
                                        </h5>
                                        <div class="d-flex" >
                                            <a id="btn_refresh_table_Cons" class="btn btn-outline-info ms-auto">
                                                <i class="ri-loop-left-line"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="">
                                            <div class="table-responsive">
                                                <table id="Table_day" class="table align-middle table-hover m-0 truncate Table_Cons">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">N°</th>
                                                            <th scope="col">Id facture</th>
                                                            <th scope="col">Nom et Prénoms</th>
                                                            <th scope="col">Contact</th>
                                                            <th scope="col">Part Assurance</th>
                                                            <th scope="col">Part Patient</th>
                                                            <th scope="col">Remise</th>
                                                            <th scope="col">Total</th>
                                                            <th scope="col">Date de création</th>
                                                            <th scope="col">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="twoA2" role="tabpanel" aria-labelledby="tab-twoA2"> 
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h5 class="card-title">
                                            Examen(s)
                                        </h5>
                                        <div class="d-flex" >
                                            <a id="btn_refresh_table_Exam" class="btn btn-outline-info ms-auto">
                                                <i class="ri-loop-left-line"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="">
                                            <div class="table-responsive">
                                                <table id="Table_day" class="table align-middle table-hover m-0 truncate Table_Exam">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">N°</th>
                                                            <th scope="col">Id facture</th>
                                                            <th scope="col">Type d'examen</th>
                                                            <th scope="col">Part Assurance</th>
                                                            <th scope="col">Part Patient</th>
                                                            <th scope="col">Prélevement</th>
                                                            <th scope="col">Montant Total</th>
                                                            <th scope="col">Montant a payer</th>
                                                            <th scope="col">Date de création</th>
                                                            <th scope="col">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="twoA3" role="tabpanel" aria-labelledby="tab-twoA3">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h5 class="card-title">
                                            Hospitalisation(s)
                                        </h5>
                                        <div class="d-flex" >
                                            <a id="btn_refresh_table_Hos" class="btn btn-outline-info ms-auto">
                                                <i class="ri-loop-left-line"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="">
                                            <div class="table-responsive">
                                                <table id="Table_day" class="table align-middle table-hover m-0 truncate Table_Hos">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">N°</th>
                                                            <th scope="col">Id facture</th>
                                                            <th scope="col">Montant Total</th>
                                                            <th scope="col">Montant Chambre</th>
                                                            <th scope="col">Montant Soins</th>
                                                            <th scope="col">Montant a payer</th>
                                                            <th scope="col">Part Assurance</th>
                                                            <th scope="col">Remise</th>
                                                            <th scope="col">Date de création</th>
                                                            <th scope="col">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="twoA4" role="tabpanel" aria-labelledby="tab-twoA4">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h5 class="card-title">
                                            Soins Amulatoire(s)
                                        </h5>
                                        <div class="d-flex" >
                                            <a id="btn_refresh_table_Soinsam" class="btn btn-outline-info ms-auto">
                                                <i class="ri-loop-left-line"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="">
                                            <div class="table-responsive">
                                                <table id="Table_day" class="table align-middle table-hover m-0 truncate Table_Soinsam">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">N°</th>
                                                            <th scope="col">Id facture</th>
                                                            <th scope="col">Montant Total</th>
                                                            <th scope="col">Montant Produit</th>
                                                            <th scope="col">Montant Soins</th>
                                                            <th scope="col">Montant a payer</th>
                                                            <th scope="col">Part Assurance</th>
                                                            <th scope="col">Remise</th>
                                                            <th scope="col">Date de création</th>
                                                            <th scope="col">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Detail_Cons" tabindex="-1" aria-modal="true" role="dialog" >
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalXlLabel">
                    Détail facture
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive" id="div_TableD_Cons" style="display: none;">
                                            <table class="table table-bordered" id="TableD_Cons">
                                                <thead>
                                                    <tr>
                                                        <th width="100px" >Code</th>
                                                        <th colspan="2">Description</th>
                                                        <th width="120px" >Part Assurance</th>
                                                        <th width="50px" >Taux</th>
                                                        <th width="100px" >Remise</th>
                                                        <th width="120px" >Part Patient</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div id="message_TableD_Cons" style="display: none;">
                                            <p class="text-center" >
                                                Aucune facture disponible
                                            </p>
                                        </div>
                                        <div id="div_Table_loaderD_Cons" style="display: none;">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                                                <strong>Chargement des données...</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="Caisse_Cons" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Caisse
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row gx-3">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">A payer</label>
                            <input readonly class="form-control" id="input_montant_payer_Cons">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Montant versé</label>
                            <div class="input-group">
                                <input type="tel" class="form-control" id="input_montant_verser_Cons" placeholder="Saisie Obligatoire">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Montant Remis</label>
                            <div class="input-group">
                                <input readonly type="tel" class="form-control" id="input_montant_remis_Cons" placeholder="Saisie Obligatoire">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="div_btn_valider_Cons" style="display: none;">
                <input type="hidden" id="id_code_fac_Cons">
                <button data-bs-dismiss="modal" class="btn btn-success" id="btn_valider_Cons" >
                    Validé
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Detail_Exam" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Examens Demandés
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive" id="div_TableP_Exam" style="display: none;">
                                            <table class="table table-bordered" id="TableP_Exam">
                                                <thead>
                                                    <tr>
                                                        <th>Examen</th>
                                                        <th>Cotation</th>
                                                        <th>Prix</th>
                                                        <th>Accepté ?</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div id="message_TableP_Exam" style="display: none;">
                                            <p class="text-center" >
                                                Aucun Produit utilisé pour le moment
                                            </p>
                                        </div>
                                        <div id="div_Table_loaderP_Exam" style="display: none;">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                                                <strong>Chargement des données...</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="Caisse_Exam" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Caisse
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row gx-3">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">A payer</label>
                            <input readonly class="form-control" id="input_montant_payer_Exam">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Montant versé</label>
                            <div class="input-group">
                                <input type="tel" class="form-control" id="input_montant_verser_Exam" placeholder="Saisie Obligatoire">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Montant Remis</label>
                            <div class="input-group">
                                <input readonly type="tel" class="form-control" id="input_montant_remis_Exam" placeholder="Saisie Obligatoire">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="div_btn_valider_Exam" style="display: none;">
                <input type="hidden" id="id_code_fac_Exam">
                <button data-bs-dismiss="modal" class="btn btn-success" id="btn_valider_Exam" >
                    Validé
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Detail_Hos" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >
                    Détails
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal_detail_Hos">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="Detail_produit_Hos" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Produit Pharmacie Utilisé
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive" id="div_TableP_Hos" style="display: none;">
                                            <table class="table table-bordered" id="TableP_Hos">
                                                <thead>
                                                    <tr>
                                                        <th>Produit utilisé</th>
                                                        <th style="width: 150px;" >Prix unitaire</th>
                                                        <th style="width: 50px;" >Quantité</th>
                                                        <th style="width: 150px;" >Prix</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div id="message_TableP_Hos" style="display: none;">
                                            <p class="text-center" >
                                                Aucun Produit utilisé pour le moment
                                            </p>
                                        </div>
                                        <div id="div_Table_loaderP_Hos" style="display: none;">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                                                <strong>Chargement des données...</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="Caisse_Hos" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Caisse
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row gx-3">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">A payer</label>
                            <input readonly class="form-control" id="input_montant_payer_Hos">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Montant versé</label>
                            <div class="input-group">
                                <input type="tel" class="form-control" id="input_montant_verser_Hos" placeholder="Saisie Obligatoire">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Montant Remis</label>
                            <div class="input-group">
                                <input readonly type="tel" class="form-control" id="input_montant_remis_Hos" placeholder="Saisie Obligatoire">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="div_btn_valider_Hos" style="display: none;">
                <input type="hidden" id="id_code_fac_Hos">
                <button data-bs-dismiss="modal" class="btn btn-success" id="btn_valider_Hos" >
                    Validé
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Detail_Soinsam" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Détails
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal_detail_Soinsam">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="Detail_produit_Soinsam" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >
                    Détail Soins Infirmiers et Produits Utilisés
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive" id="div_TableP_Soinsam" style="display: none;">
                                            <table class="table table-bordered" id="TableP_Soinsam">
                                                <thead>
                                                    <tr>
                                                        <th>Soins Infirmiers</th>
                                                        <th style="width: 250px;">Prix</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="table-responsive" id="div_TableProdP_Soinsam" style="display: none;">
                                            <table class="table table-bordered" id="TableProdP_Soinsam">
                                                <thead>
                                                    <tr>
                                                        <th>Produits Utilisés</th>
                                                        <th style="width: 200px;">Prix Unitaire</th>
                                                        <th style="width: 50px;" >Quantité</th>
                                                        <th style="width: 200px;">Prix Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div id="message_TableP_Soinsam" style="display: none;">
                                            <p class="text-center" >
                                                Aucun détail pour le moment
                                            </p>
                                        </div>
                                        <div id="div_Table_loaderP_Soinsam" style="display: none;">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                                                <strong>Chargement des données...</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="Caisse_Soinsam" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Caisse
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row gx-3">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">A payer</label>
                            <input readonly class="form-control" id="input_montant_payer_Soinsam">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Montant versé</label>
                            <div class="input-group">
                                <input type="tel" class="form-control" id="input_montant_verser_Soinsam" placeholder="Saisie Obligatoire">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Montant Remis</label>
                            <div class="input-group">
                                <input readonly type="tel" class="form-control" id="input_montant_remis_Soinsam" placeholder="Saisie Obligatoire">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="div_btn_valider_Soinsam" style="display: none;">
                <input type="hidden" id="id_code_fac_Soinsam">
                <button data-bs-dismiss="modal" class="btn btn-success" id="btn_valider_Soinsam" >
                    Validé
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('assets/js/app/js/jspdfinvoicetemplate/dist/index.js')}}" ></script>
<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>
<script src="{{asset('jsPDF-AutoTable/dist/jspdf.plugin.autotable.min.js')}}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        document.getElementById("btn_valider_Cons").addEventListener("click", payer_Cons);
        document.getElementById("btn_valider_Exam").addEventListener("click", payer_Exam);
        document.getElementById("btn_valider_Hos").addEventListener("click", payer_Hos);
        document.getElementById("btn_valider_Soinsam").addEventListener("click", payer_Soinsam);

        document.getElementById('input_montant_verser_Cons').addEventListener('input', function() 
        {
            // Nettoyer la valeur entrée en supprimant les caractères non numériques sauf le point
            const rawValue = this.value.replace(/[^0-9]/g, ''); // Supprimer tous les caractères non numériques
            
            // Ajouter des points pour les milliers
            const formattedValue = formatPrice(rawValue);
            
            // Mettre à jour la valeur du champ avec la valeur formatée
            this.value = formattedValue;

            // Convertir la valeur formatée en nombre pour les calculs
            const montantPayer = parseFloat(document.getElementById('input_montant_payer_Cons').value.replace(/\./g, '')) || 0;
            const montantVerser = parseFloat(rawValue) || 0;

            // Calculer le montant remis
            const montantRemis = montantVerser - montantPayer;
            document.getElementById('input_montant_remis_Cons').value = `${formatPrice(montantRemis)}`;

            const btnValider = document.getElementById('div_btn_valider_Cons');
            if (montantRemis >= 0) {
                btnValider.style.display = 'block';
            } else {
                btnValider.style.display = 'none';
            }
        });
        document.getElementById('input_montant_verser_Cons').addEventListener('keypress', function(event) 
        {
            // Permettre uniquement les chiffres et le point
            const key = event.key;
            if (isNaN(key)) {
                event.preventDefault();
            }
        });

        document.getElementById('input_montant_verser_Exam').addEventListener('input', function() 
        {
            // Nettoyer la valeur entrée en supprimant les caractères non numériques sauf le point
            const rawValue = this.value.replace(/[^0-9]/g, ''); // Supprimer tous les caractères non numériques
            
            // Ajouter des points pour les milliers
            const formattedValue = formatPrice(rawValue);
            
            // Mettre à jour la valeur du champ avec la valeur formatée
            this.value = formattedValue;

            // Convertir la valeur formatée en nombre pour les calculs
            const montantPayer = parseFloat(document.getElementById('input_montant_payer_Exam').value.replace(/\./g, '')) || 0;
            const montantVerser = parseFloat(rawValue) || 0;

            // Calculer le montant remis
            const montantRemis = montantVerser - montantPayer;
            document.getElementById('input_montant_remis_Exam').value = `${formatPrice(montantRemis)}`;

            const btnValider = document.getElementById('div_btn_valider_Exam');
            if (montantRemis >= 0) {
                btnValider.style.display = 'block';
            } else {
                btnValider.style.display = 'none';
            }
        });
        document.getElementById('input_montant_verser_Exam').addEventListener('keypress', function(event) 
        {
            // Permettre uniquement les chiffres et le point
            const key = event.key;
            if (isNaN(key)) {
                event.preventDefault();
            }
        });

        document.getElementById('input_montant_verser_Hos').addEventListener('input', function() 
        {
            // Nettoyer la valeur entrée en supprimant les caractères non numériques sauf le point
            const rawValue = this.value.replace(/[^0-9]/g, ''); // Supprimer tous les caractères non numériques
            
            // Ajouter des points pour les milliers
            const formattedValue = formatPrice(rawValue);
            
            // Mettre à jour la valeur du champ avec la valeur formatée
            this.value = formattedValue;

            // Convertir la valeur formatée en nombre pour les calculs
            const montantPayer = parseFloat(document.getElementById('input_montant_payer_Hos').value.replace(/\./g, '')) || 0;
            const montantVerser = parseFloat(rawValue) || 0;

            // Calculer le montant remis
            const montantRemis = montantVerser - montantPayer;
            document.getElementById('input_montant_remis_Hos').value = `${formatPrice(montantRemis)}`;

            const btnValider = document.getElementById('div_btn_valider_Hos');
            if (montantRemis >= 0) {
                btnValider.style.display = 'block';
            } else {
                btnValider.style.display = 'none';
            }
        });
        document.getElementById('input_montant_verser_Hos').addEventListener('keypress', function(event) 
        {
            // Permettre uniquement les chiffres et le point
            const key = event.key;
            if (isNaN(key)) {
                event.preventDefault();
            }
        });

        document.getElementById('input_montant_verser_Soinsam').addEventListener('input', function() 
        {
            // Nettoyer la valeur entrée en supprimant les caractères non numériques sauf le point
            const rawValue = this.value.replace(/[^0-9]/g, ''); // Supprimer tous les caractères non numériques
            
            // Ajouter des points pour les milliers
            const formattedValue = formatPrice(rawValue);
            
            // Mettre à jour la valeur du champ avec la valeur formatée
            this.value = formattedValue;

            // Convertir la valeur formatée en nombre pour les calculs
            const montantPayer = parseFloat(document.getElementById('input_montant_payer_Soinsam').value.replace(/\./g, '')) || 0;
            const montantVerser = parseFloat(rawValue) || 0;

            // Calculer le montant remis
            const montantRemis = montantVerser - montantPayer;
            document.getElementById('input_montant_remis_Soinsam').value = `${formatPrice(montantRemis)}`;

            const btnValider = document.getElementById('div_btn_valider_Soinsam');
            if (montantRemis >= 0) {
                btnValider.style.display = 'block';
            } else {
                btnValider.style.display = 'none';
            }
        });
        document.getElementById('input_montant_verser_Soinsam').addEventListener('keypress', function(event) 
        {
            // Permettre uniquement les chiffres et le point
            const key = event.key;
            if (isNaN(key)) {
                event.preventDefault();
            }
        });

        //-----------------------------------------------------------------------

        const table_cons = $('.Table_Cons').DataTable({

            processing: true,
            serverSide: false,
            ajax: {
                url: `/api/list_facture_inpayer`,
                type: 'GET',
                dataSrc: 'data',
            },
            columns: [
                { 
                    data: null, 
                    render: (data, type, row, meta) => meta.row + 1,
                    searchable: false,
                    orderable: false,
                },
                { 
                    data: 'code_fac', 
                    render: (data, type, row) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{asset('assets/images/facture.webp')}}" class="img-2x rounded-circle border border-1">
                        </a>
                        ${data}
                    </div>`,
                    searchable: true, 
                },
                { 
                    data: 'name',
                    searchable: true,
                },
                {
                    data: 'tel',
                    render: (data, type, row) => {
                        return data ? `+225 ${data}` : 'Néant';
                    },
                    searchable: true,
                },
                {
                    data: 'part_assurance',
                    render: (data, type, row) => {
                        const value = data ? formatPrice(data) : 0;
                        const color = 'text-warning';
                        return `<span class="${color}">${value} Fcfa</span>`;
                    },
                    searchable: true,
                },
                {
                    data: 'part_patient',
                    render: (data, type, row) => {
                        const value = data ? formatPrice(data) : 0;
                        const color = 'text-success';
                        return `<span class="${color}">${value} Fcfa</span>`;
                    },
                    searchable: true,
                },
                {
                    data: 'remise',
                    render: (data, type, row) => {
                        const value = data ? formatPrice(data) : 0;
                        const color = 'text-danger';
                        return `<span class="${color}">${value} Fcfa</span>`;
                    },
                    searchable: true,
                },
                {
                    data: 'montant',
                    render: (data, type, row) => {
                        const value = data ? formatPrice(data) : 0;
                        const color = 'text-primary';
                        return `<span class="${color}">${value} Fcfa</span>`;
                    },
                    searchable: true,
                },
                { 
                    data: 'created_at',
                    render: (data, type, row) => {
                        return data ? `${formatDateHeure(data)}` : 'Néant';
                    },
                    searchable: true,
                },
                {
                    data: null,
                    render: (data, type, row) => `
                        <div class="d-inline-flex gap-1" style="font-size:10px;">
                            <a class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#Caisse_Cons" id="paye_Cons"
                                data-code_fac="${row.code_fac}"
                                data-part_patient="${row.part_patient}"
                            >
                                <i class="ri-hand-coin-line"></i>
                            </a>
                            <a class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#Detail_Cons" id="detail_Cons"
                                data-id="${row.id}"
                            >
                                <i class="ri-eye-line"></i>
                            </a>
                            <a class="btn btn-outline-info btn-sm" id="printer_Cons"
                                data-id="${row.id}"
                            >
                                <i class="ri-printer-line"></i>
                            </a>
                        </div>
                    `,
                    searchable: false,
                    orderable: false,
                },
            ],
            ...dataTableConfig,
            initComplete: function(settings, json) {
                initCons();
            },
        });

        const table_exam = $('.Table_Exam').DataTable({

            processing: true,
            serverSide: false,
            ajax: {
                url: `/api/list_facture_examen`,
                type: 'GET',
                dataSrc: 'data',
            },
            columns: [
                { 
                    data: null, 
                    render: (data, type, row, meta) => meta.row + 1,
                    searchable: false,
                    orderable: false,
                },
                { 
                    data: 'code_fac', 
                    render: (data, type, row) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{asset('assets/images/facture.webp')}}" class="img-2x rounded-circle border border-1">
                        </a>
                        ${data}
                    </div>`,
                    searchable: true, 
                },
                { 
                    data: 'acte',
                    searchable: true,
                },
                {
                    data: 'part_assurance',
                    render: (data, type, row) => {
                        const value = data ? data : 0;
                        const color = 'text-warning';
                        return `<span class="${color}">${value} Fcfa</span>`;
                    },
                    searchable: true,
                },
                {
                    data: 'part_patient',
                    render: (data, type, row) => {
                        const value = data ? formatPrice(data) : 0;
                        const color = 'text-warning';
                        return `<span class="${color}">${value} Fcfa</span>`;
                    },
                    searchable: true,
                },
                {
                    data: 'prelevement',
                    render: (data, type, row) => {
                        const value = data ? data : 0;
                        const color = 'text-warning';
                        return `<span class="${color}">${value} Fcfa</span>`;
                    },
                    searchable: true,
                },
                {
                    data: 'montant',
                    render: (data, type, row) => {
                        const value = data ? data : 0;
                        const color = 'text-primary';
                        return `<span class="${color}">${value} Fcfa</span>`;
                    },
                    searchable: true,
                },
                {
                    data: 'total_patient',
                    render: (data, type, row) => {
                        const value = data ? formatPrice(data) : 0;
                        const color = 'text-success';
                        return `<span class="${color}">${value} Fcfa</span>`;
                    },
                    searchable: true,
                },
                { 
                    data: 'created_at',
                    render: (data, type, row) => {
                        return data ? `${formatDateHeure(data)}` : 'Néant';
                    },
                    searchable: true,
                },
                {
                    data: null,
                    render: (data, type, row) => `
                        <div class="d-inline-flex gap-1" style="font-size:10px;">
                            <a class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#Caisse_Exam" id="paye_Exam"
                                data-code_fac="${row.code_fac}"
                                data-total_patient="${row.total_patient}"
                            >
                                <i class="ri-hand-coin-line"></i>
                            </a>
                            <a class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#Detail_Exam" id="detail_Exam"
                                data-id="${row.id}"
                            >
                                <i class="ri-archive-2-line"></i>
                            </a>
                            <a class="btn btn-outline-info btn-sm" id="fiche_Exam"
                                data-id="${row.id}"
                            >
                                <i class="ri-file-line"></i>
                            </a>
                        </div>
                    `,
                    searchable: false,
                    orderable: false,
                },
            ],
            ...dataTableConfig,
            initComplete: function(settings, json) {
                initExam();
            },
        });

        const table_hos = $('.Table_Hos').DataTable({

            processing: true,
            serverSide: false,
            ajax: {
                url: `/api/list_facture_hos`,
                type: 'GET',
                dataSrc: 'data',
            },
            columns: [
                { 
                    data: null, 
                    render: (data, type, row, meta) => meta.row + 1,
                    searchable: false,
                    orderable: false,
                },
                { 
                    data: 'code_fac', 
                    render: (data, type, row) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{asset('assets/images/facture.webp')}}" class="img-2x rounded-circle border border-1">
                        </a>
                        ${data}
                    </div>`,
                    searchable: true, 
                },
                {
                    data: 'montant',
                    render: (data, type, row) => {
                        const value = data ? data : 0;
                        const color = 'text-primary';
                        return `<span class="${color}">${value} Fcfa</span>`;
                    },
                    searchable: true,
                },
                {
                    data: 'montant_chambre',
                    render: (data, type, row) => {
                        const value = data ? data : 0;
                        const color = 'text-warning';
                        return `<span class="${color}">${value} Fcfa</span>`;
                    },
                    searchable: true,
                },
                {
                    data: 'montant_soins',
                    render: (data, type, row) => {
                        const value = data ? data : 0;
                        const color = 'text-warning';
                        return `<span class="${color}">${value} Fcfa</span>`;
                    },
                    searchable: true,
                },
                {
                    data: 'part_patient',
                    render: (data, type, row) => {
                        const value = data ? data : 0;
                        const color = 'text-success';
                        return `<span class="${color}">${value} Fcfa</span>`;
                    },
                    searchable: true,
                },
                {
                    data: 'part_assurance',
                    render: (data, type, row) => {
                        const value = data ? data : 0;
                        const color = 'text-warning';
                        return `<span class="${color}">${value} Fcfa</span>`;
                    },
                    searchable: true,
                },
                {
                    data: 'remise',
                    render: (data, type, row) => {
                        const value = data ? data : 0;
                        const color = 'text-danger';
                        return `<span class="${color}">${value} Fcfa</span>`;
                    },
                    searchable: true,
                },
                { 
                    data: 'created_at',
                    render: (data, type, row) => {
                        return data ? `${formatDateHeure(data)}` : 'Néant';
                    },
                    searchable: true,
                },
                {
                    data: null,
                    render: (data, type, row) => `
                        <div class="d-inline-flex gap-1" style="font-size:10px;">
                            <a class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#Caisse_Hos" id="paye_Hos"
                                data-code_fac="${row.code_fac}"
                                data-part_patient="${row.part_patient}"
                            >
                                <i class="ri-hand-coin-line"></i>
                            </a>
                            <a class="btn btn-outline-warning btn-sm" id="detail_Hos" data-bs-toggle="modal" data-bs-target="#Detail_Hos"
                                data-id="${row.id}"
                            >
                                <i class="ri-eye-line"></i>
                            </a>
                            <a class="btn btn-outline-danger btn-sm" id="detail_produit_Hos" data-bs-toggle="modal" data-bs-target="#Detail_produit_Hos"
                                data-id="${row.id}"
                                data-montant_soins="${row.montant_soins}"
                            >
                                <i class="ri-archive-2-fill"></i>
                            </a>
                            <a class="btn btn-outline-info btn-sm" id="printer_Hos"
                                data-id="${row.id}"
                            >
                                <i class="ri-printer-line"></i>
                            </a>
                        </div>
                    `,
                    searchable: false,
                    orderable: false,
                },
            ],
            ...dataTableConfig,
            initComplete: function(settings, json) {
                initHos();
            },
        });

        const table_soinsam = $('.Table_Soinsam').DataTable({

            processing: true,
            serverSide: false,
            ajax: {
                url: `/api/list_facture_soinsam`,
                type: 'GET',
                dataSrc: 'data',
            },
            columns: [
                { 
                    data: null, 
                    render: (data, type, row, meta) => meta.row + 1,
                    searchable: false,
                    orderable: false,
                },
                { 
                    data: 'code_fac', 
                    render: (data, type, row) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{asset('assets/images/facture.webp')}}" class="img-2x rounded-circle border border-1">
                        </a>
                        ${data}
                    </div>`,
                    searchable: true, 
                },
                {
                    data: 'montant',
                    render: (data, type, row) => {
                        const value = data ? data : 0;
                        const color = 'text-primary';
                        return `<span class="${color}">${value} Fcfa</span>`;
                    },
                    searchable: true,
                },
                {
                    data: 'prototal',
                    render: (data, type, row) => {
                        const value = data ? formatPrice(data) : 0;
                        const color = 'text-warning';
                        return `<span class="${color}">${value} Fcfa</span>`;
                    },
                    searchable: true,
                },
                {
                    data: 'stotal',
                    render: (data, type, row) => {
                        const value = data ? formatPrice(data) : 0;
                        const color = 'text-warning';
                        return `<span class="${color}">${value} Fcfa</span>`;
                    },
                    searchable: true,
                },
                {
                    data: 'part_patient',
                    render: (data, type, row) => {
                        const value = data ? data : 0;
                        const color = 'text-success';
                        return `<span class="${color}">${value} Fcfa</span>`;
                    },
                    searchable: true,
                },
                {
                    data: 'part_assurance',
                    render: (data, type, row) => {
                        const value = data ? data : 0;
                        const color = 'text-warning';
                        return `<span class="${color}">${value} Fcfa</span>`;
                    },
                    searchable: true,
                },
                {
                    data: 'remise',
                    render: (data, type, row) => {
                        const value = data ? data : 0;
                        const color = 'text-danger';
                        return `<span class="${color}">${value} Fcfa</span>`;
                    },
                    searchable: true,
                },
                { 
                    data: 'created_at',
                    render: (data, type, row) => {
                        return data ? `${formatDateHeure(data)}` : 'Néant';
                    },
                    searchable: true,
                },
                {
                    data: null,
                    render: (data, type, row) => `
                        <div class="d-inline-flex gap-1" style="font-size:10px;">
                            <a class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#Caisse_Soinsam" 
                                id="paye_Soinsam"
                                data-code_fac="${row.code_fac}"
                                data-part_patient="${row.part_patient}"
                            >
                                <i class="ri-hand-coin-line"></i>
                            </a>
                            <a class="btn btn-outline-warning btn-sm" id="detail_Soinsam" data-bs-toggle="modal" data-bs-target="#Detail_Soinsam"
                                data-id="${row.id}"
                            >
                                <i class="ri-eye-line"></i>
                            </a>
                            <a class="btn btn-outline-danger btn-sm" id="detail_produit_Soinsam" data-bs-toggle="modal" data-bs-target="#Detail_produit_Soinsam"
                                data-id="${row.id}"
                                data-stotal="${row.stotal}"
                                data-prototal="${row.prototal}"
                            >
                                <i class="ri-archive-2-fill"></i>
                            </a>
                            <a class="btn btn-outline-info btn-sm" id="printer_Soinsam"
                                data-id="${row.id}"
                            >
                                <i class="ri-printer-line"></i>
                            </a>
                        </div>
                    `,
                    searchable: false,
                    orderable: false,
                },
            ],
            ...dataTableConfig,
            initComplete: function(settings, json) {
                initSoinsam();
            },
        });

        //-----------------------------------------------------------------------

        caisse_verf();

        document.getElementById("btn_ouvert_C").addEventListener("click", caisse_ouvert);
        document.getElementById("btn_fermer_C").addEventListener("click", caisse_fermer);

        function caisse_verf()
        {
            fetch('/api/verf_caisse')
                .then(response => response.json())
                .then(data => {
                    
                    if (data.caisse.statut == 'ouvert') {
                        document.getElementById('div_caisse').style.display = 'block';
                        document.getElementById('div_caisse_verf').style.display = 'block';
                        document.getElementById('btn_ouvert').style.display = 'none';
                        document.getElementById('btn_fermer').style.display = 'block';

                        // table_cons.ajax.reload(null, false);
                        // table_exam.ajax.reload(null, false);
                        // table_hos.ajax.reload(null, false);
                        // table_soinsam.ajax.reload(null, false);

                    }else{
                        document.getElementById('div_caisse').style.display = 'none';
                        document.getElementById('div_caisse_verf').style.display = 'block';
                        document.getElementById('btn_ouvert').style.display = 'block';
                        document.getElementById('btn_fermer').style.display = 'none';
                    }

                })
                .catch(error => console.error('Erreur lors du chargement des donnée caisse:', error));
        }

        function caisse_ouvert()
        {
            const auth_id = {{ Auth::user()->id }};

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: '/api/caisse_ouvert',
                method: 'GET',
                data: { 
                    auth_id: auth_id,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.success) {

                        document.getElementById('div_caisse').style.display = 'block';
                        document.getElementById('div_caisse_verf').style.display = 'block';
                        document.getElementById('btn_ouvert').style.display = 'none';
                        document.getElementById('btn_fermer').style.display = 'block';

                        table_cons.ajax.reload(null, false);
                        table_exam.ajax.reload(null, false);
                        table_hos.ajax.reload(null, false);
                        table_soinsam.ajax.reload(null, false);

                    } else if (response.error) {
                        showAlert('Alert', 'Une erreur est survenue lors de l\'ouverture de la caisse.','error');
                    }

                },
                error: function(xhr, status, error) {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    showAlert('Alert', 'Une erreur est survenue.','error');
                }
            });
        }

        function caisse_fermer()
        {
            const auth_id = {{ Auth::user()->id }};

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: '/api/caisse_fermer',
                method: 'GET',
                data: { 
                    auth_id: auth_id,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.success) {

                        document.getElementById('div_caisse').style.display = 'none';
                        document.getElementById('div_caisse_verf').style.display = 'block';
                        document.getElementById('btn_ouvert').style.display = 'block';
                        document.getElementById('btn_fermer').style.display = 'none';

                    } else if (response.error) {
                        showAlert('Alert', 'Une erreur est survenue lors de la fermeture de la caisse.','error');
                    }

                },
                error: function(xhr, status, error) {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    showAlert('Alert', 'Une erreur est survenue.','error');
                }
            });
        }

        function formatPrice(price) 
        {

            // Convert to float and round to the nearest whole number
            let number = Math.round(parseFloat(price));
            if (isNaN(number)) {
                return '';
            }

            // Format the number with dot as thousands separator
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function formatPriceT(price) 
        {

            // Convert to float and round to the nearest whole number
            let number = Math.round(parseInt(price));
            if (isNaN(number)) {
                return '';
            }

            // Format the number with dot as thousands separator
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function showAlert(title, message, type) 
        {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
            });
        }

        function formatDate(dateString) 
        {

            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
            const year = date.getFullYear();

            return `${day}/${month}/${year}`; // Format as dd/mm/yyyy
        }

        function formatDateHeure(dateString) 
        {

            const date = new Date(dateString);
                
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();

            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const seconds = String(date.getSeconds()).padStart(2, '0');

            return `${day}/${month}/${year} à ${hours}:${minutes}:${seconds}`;
        }

        function calculateDaysBetween(startDate, endDate) 
        {
            const start = new Date(startDate);
            const end = new Date(endDate);
            
            // Calcul de la différence en millisecondes
            const diffInMilliseconds = end - start;

            // Conversion en jours (millisecondes en secondes, minutes, heures, jours)
            let diffInDays = diffInMilliseconds / (1000 * 60 * 60 * 24);

            // Si la différence est inférieure ou égale à 0, on retourne 1 jour minimum
            return diffInDays <= 0 ? 1 : Math.round(diffInDays); 
        }

        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            // Optionnel : Recharger les données associées à l'onglet sélectionné
            const activeTab = $(e.target).attr('href'); // ID de l'onglet actif
            if (activeTab === '#twoA1') {
                // Charger les consultations
                table_cons.ajax.reload(null, false);
            } else if (activeTab === '#twoA2') {
                // Charger les examens
                table_exam.ajax.reload(null, false);
            } else if (activeTab === '#twoA3') {
                // Charger les hospitalisations
                table_hos.ajax.reload(null, false);
            } else if (activeTab === '#twoA4') {
                // Charger les soins ambulatoires
                table_soinsam.ajax.reload(null, false);
            }
        });

        //-----------------------------------------------------------------------

        function payer_Cons()
        {
            const auth_id = {{ Auth::user()->id }};
            const code_fac = document.getElementById("id_code_fac_Cons").value;
            const montant_verser = document.getElementById("input_montant_verser_Cons");
            const montant_remis = document.getElementById("input_montant_remis_Cons");

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            if(!montant_verser.value.trim() || !montant_remis.value.trim()){
                var preloader = document.getElementById('preloader_ch');
                if (preloader) {
                    preloader.remove();
                }
                showAlert('Alert', 'Impossible d\'éffectuée le paiement.','error');
                return false;
            }

            $.ajax({
                url: '/api/facture_payer/' + code_fac,
                method: 'GET',
                data: { 
                    montant_verser: montant_verser.value, 
                    montant_remis: montant_remis.value,
                    auth_id: auth_id,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    caisse_verf();

                    if (response.success) {

                        const patient = response.patient;
                        const typeacte = response.typeacte;
                        const user = response.user;
                        const consultation = response.consultation;

                        table_cons.ajax.reload(null, false);

                        generatePDFInvoice_Cons(patient, user, typeacte, consultation);

                        showAlert('Succès', 'Paiement éffectuée.','success');

                    } else if (response.error) {
                        showAlert('Alert', 'Une erreur est survenue lors du paiement.','error');
                    } else if (response.caisse_fermer) {
                        showAlert('Alert', 'La caisse est actuellement fermer, Veuillez ouvrir la caisse avant d\'éffectuer un encaissement.','info');
                    }

                },
                error: function(xhr, status, error) {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    showAlert('Alert', 'Une erreur est survenue lors du paiement.','error');
                }
            });
        }

        // $('.Table_Cons').on('draw.dt', function() {
        //     initCons();
        // });

        function initCons() {

            $('.Table_Cons').on('click', '#paye_Cons', function() {
                const code_fac = $(this).data('code_fac');
                const part_patient = $(this).data('part_patient');
                
                document.getElementById('input_montant_payer_Cons').value = `${formatPrice(part_patient || 0)} Fcfa`;
                document.getElementById('input_montant_verser_Cons').value = '';
                document.getElementById('input_montant_remis_Cons').value = '0 Fcfa';
                document.getElementById('id_code_fac_Cons').value = `${code_fac}`;
            });

            $('.Table_Cons').on('click', '#detail_Cons', function() {
                const id = $(this).data('id');

                const tableBodyD = document.querySelector('#TableD_Cons tbody');
                const messageDivD = document.getElementById('message_TableD_Cons');
                const tableDivD = document.getElementById('div_TableD_Cons');
                const loaderDivD = document.getElementById('div_Table_loaderD_Cons');

                messageDivD.style.display = 'none';
                tableDivD.style.display = 'none';
                loaderDivD.style.display = 'block';

                fetch(`/api/list_facture_inpayer_d/${id}`) // API endpoint
                    .then(response => response.json())
                    .then(data => {
                        // Access the 'chambre' array from the API response
                        const factureds = data.factured;
                        const Total = data.Total;

                        // Clear any existing rows in the table body
                        tableBodyD.innerHTML = '';

                        if (factureds.length > 0) {

                            loaderDivD.style.display = 'none';
                            messageDivD.style.display = 'none';
                            tableDivD.style.display = 'block';

                            // Loop through each item in the chambre array
                            factureds.forEach((item, index) => {
                                // Create a new row
                                const row = document.createElement('tr');
                                // Create and append cells to the row based on your table's structure
                                row.innerHTML = `
                                    <td><h6>C-${item.code}</h6></td>
                                    <td colspan="2" >
                                        <h6>${item.nom_acte}</h6>
                                        <p>
                                            ${item.nom_typeacte}
                                        </p>
                                    </td>
                                    <td>
                                        <h6 class="text-primary" >
                                            ${item.part_assurance} Fcfa
                                        </h6>
                                    </td>
                                    <td>
                                        <h6 class="text-primary" >
                                            ${item.taux ?? 0}%
                                        </h6>
                                    </td>
                                    <td>
                                        <h6 class="text-primary">
                                            ${item.remise ?? 0} Fcfa
                                        </h6>
                                    </td>
                                    <td>
                                        <h6 class="text-primary" >
                                            ${item.part_patient} Fcfa
                                        </h6>
                                    </td>
                                `;
                                // Append the row to the table body
                                tableBodyD.appendChild(row);

                            });

                            const row2 = document.createElement('tr');
                            row2.innerHTML = `
                                <td colspan="4">&nbsp;</td>
                                <td colspan="3" >
                                    <h5 class="mt-1 text-success">
                                        Total : ${formatPrice(Total.total_sum)} Fcfa
                                    </h5>
                                </td>
                            `;
                            tableBodyD.appendChild(row2);

                            const row3 = document.createElement('tr');
                            row3.innerHTML = `
                                <td colspan="7">
                                    <h6 class="text-danger">NOTES</h6>
                                    <p class="small m-0">
                                        We really appreciate your business and if
                                        there’s anything else we can do, please.
                                        let us know! Also, should you need us to
                                        add VAT or anything else to this order,
                                        it’s super easy since this is a template,
                                        so just ask!
                                    </p>
                                </td>
                            `;

                            tableBodyD.appendChild(row3);

                        } else {
                            loaderDivD.style.display = 'none';
                            messageDivD.style.display = 'block';
                            tableDivD.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                        loaderDivD.style.display = 'none';
                        messageDivD.style.display = 'block';
                        tableDivD.style.display = 'none';
                    });
            });

            $('.Table_Cons').on('click', '#printer_Cons', function() {

                var preloader_ch = `
                    <div id="preloader_ch">
                        <div class="spinner_preloader_ch"></div>
                    </div>
                `;
                // Add the preloader to the body
                document.body.insertAdjacentHTML('beforeend', preloader_ch);

                const id = $(this).data('id');

                fetch(`/api/facture_inpayer_cons/${id}`)
                    .then(response => response.json())
                    .then(data => {

                        const patient = data.patient;
                        const typeacte = data.typeacte;
                        const user = data.user;
                        const consultation = data.consultation;

                        var preloader = document.getElementById('preloader_ch');
                        if (preloader) {
                            preloader.remove();
                        }

                        generatePDFInvoice_Cons(patient, user, typeacte, consultation);
                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                    });
            });
        }

        $('#btn_refresh_table_Cons').on('click', function () {
            table_cons.ajax.reload(null, false); 
        });

        function generatePDFInvoice_Cons(patient, user, typeacte, consultation) 
        {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });

            const pdfFilename = "CONSULTATION Facture N°" + consultation.code_fac + " du " + formatDateHeure(consultation.created_at);
            doc.setProperties({
                title: pdfFilename,
            });

            yPos = 10;

            function formatDate(dateString) {
                const date = new Date(dateString);
                
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
                const year = date.getFullYear();
                
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                const seconds = String(date.getSeconds()).padStart(2, '0');
                
                return `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`; // Format as dd/mm/yyyy hh:mm:ss
            }


            function drawConsultationSection(yPos) {
                rightMargin = 15;
                leftMargin = 15;
                pdfWidth = doc.internal.pageSize.getWidth();

                if (consultation.statut_fac == 'payer') {
                    const titlea = "Payer";
                    doc.setFontSize(100);
                    doc.setTextColor(174, 255, 165);
                    doc.setFont("Helvetica", "bold");
                    doc.text(titlea, 120, yPos + 120, { align: 'center', angle: 40 });
                }else{
                    const titlea = "Impayer";
                    doc.setFontSize(100);
                    doc.setTextColor(252, 173, 159);
                    doc.setFont("Helvetica", "bold");
                    doc.text(titlea, 120, yPos + 120, { align: 'center', angle: 40 });
                }

                const logoSrc = "{{asset('assets/images/logo.png')}}";
                const logoWidth = 22;
                const logoHeight = 22;
                doc.addImage(logoSrc, 'PNG', leftMargin, yPos - 7, logoWidth, logoHeight);

                // Informations de l'entreprise
                doc.setFontSize(10);
                doc.setTextColor(0, 0, 0);
                doc.setFont("Helvetica", "bold");
                // Texte de l'entreprise
                const title = "ESPACE MEDICO SOCIAL LA PYRAMIDE DU COMPLEXE";
                const titleWidth = doc.getTextWidth(title);
                const titleX = (doc.internal.pageSize.getWidth() - titleWidth) / 2;
                doc.text(title, titleX, yPos);
                // Texte de l'adresse
                doc.setFont("Helvetica", "normal");
                const address = "Abidjan Yopougon Selmer, Non loin du complexe sportif Jesse-Jackson - 04 BP 1523";
                const addressWidth = doc.getTextWidth(address);
                const addressX = (doc.internal.pageSize.getWidth() - addressWidth) / 2;
                doc.text(address, addressX, (yPos + 5));
                // Texte du téléphone
                const phone = "Tél.: 20 24 44 70 / 20 21 71 92 - Cel.: 01 01 01 63 43";
                const phoneWidth = doc.getTextWidth(phone);
                const phoneX = (doc.internal.pageSize.getWidth() - phoneWidth) / 2;
                doc.text(phone, phoneX, (yPos + 10));
                doc.setFontSize(10);
                doc.setFont("Helvetica", "normal");
                const consultationDate = new Date(consultation.created_at);
                // Formatter la date et l'heure séparément
                const formattedDate = consultationDate.toLocaleDateString(); // Formater la date
                const formattedTime = consultationDate.toLocaleTimeString();
                doc.text("Date: " + formattedDate, 15, (yPos + 25));
                doc.text("Heure: " + formattedTime, 15, (yPos + 30));

                //Ligne de séparation
                doc.setFontSize(15);
                doc.setFont("Helvetica", "bold");
                doc.setLineWidth(0.5);
                doc.setTextColor(0, 0, 0);
                // doc.line(10, 35, 200, 35);

                let titleR;

                if (consultation.statut_fac === 'payer') {
                    titleR = "RECU DE PAIEMENT";
                } else {
                    titleR = "FACTURE CONSULTATION";
                }
                const titleRWidth = doc.getTextWidth(titleR);
                const titleRX = (doc.internal.pageSize.getWidth() - titleRWidth) / 2;
                // Définir le padding
                const paddingh = 0; // Padding vertical
                const paddingw = 15; // Padding horizontal
                // Calculer les dimensions du rectangle
                const rectX = titleRX - paddingw; // X du rectangle
                const rectY = (yPos + 18) - paddingh; // Y du rectangle
                const rectWidth = titleRWidth + (paddingw * 2); // Largeur du rectangle
                const rectHeight = 15 + (paddingh * 2); // Hauteur du rectangle
                // Définir la couleur pour le cadre (noir)
                doc.setDrawColor(0, 0, 0);
                doc.rect(rectX, rectY, rectWidth, rectHeight); // Dessiner le rectangle
                // Ajouter le texte centré en gras
                doc.setFontSize(15);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(0, 0, 0); // Couleur du texte rouge
                doc.text(titleR, titleRX, (yPos + 25)); // Positionner le texte
                const titleN = "N° "+ consultation.code_fac;
                doc.text(titleN, (doc.internal.pageSize.getWidth() - doc.getTextWidth(titleN)) / 2, (yPos + 31));

                doc.setFontSize(10);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(0, 0, 0);
                const numDossier = "N° Dossier : P-"+ patient.matricule;
                const numDossierWidth = doc.getTextWidth(numDossier);
                doc.text(numDossier, pdfWidth - rightMargin - numDossierWidth, yPos + 28);

                if (consultation.statut_fac == 'payer') {
                    doc.setFontSize(10);
                    doc.setFont("Helvetica", "bold");
                    doc.setTextColor(0, 0, 0);
                    const numDate = "Date de paiement : "+ formatDate(consultation.date_payer) ;
                    const numDateWidth = doc.getTextWidth(numDate);
                    doc.text(numDate, (doc.internal.pageSize.getWidth() - numDateWidth) / 2, yPos + 40);
                }                    

                yPoss = (yPos + 50);

                const patientInfo = [
                    { label: "Nom et Prénoms", value: patient.np },
                    { label: "Assurer", value: patient.assurer },
                    { label: "Age", value: patient.age+" an(s)" },
                    { label: "Domicile", value: patient.adresse },
                    { label: "Contact", value: "+225 "+patient.tel }
                ];

                if (patient.assurer == 'oui') {
                    patientInfo.push(
                        { label: "Assurance", value: patient.assurance },
                        { label: "Matricule", value: patient.matricule_assurance },
                    );
                }

                patientInfo.forEach(info => {
                    doc.setFontSize(9);
                    doc.setFont("Helvetica", "bold");
                    doc.text(info.label, leftMargin, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 35, yPoss);
                    yPoss += 7;
                });

                if (consultation.statut_fac == 'payer') {
                    yPoss = (yPos + 105);

                    const payerInfo = [
                        { label: "Montant Verser", value: (consultation.montant_verser || '0')+" Fcfa" },
                        { label: "Montant Remis", value: (consultation.montant_remis || '0')+" Fcfa" },
                        { label: "Reste a payé", value: (consultation.montant_restant || '0')+" Fcfa" },
                    ];

                    payerInfo.forEach(info => {
                        doc.setFontSize(9);
                        doc.setFont("Helvetica", "bold");
                        if (info.label === "Reste a payé") {
                            doc.setTextColor(0, 0, 0); // Red color
                        } else {
                            doc.setTextColor(0, 0, 0); // Default color for other values
                        }
                        doc.text(info.label, leftMargin, yPoss);
                        doc.setFont("Helvetica", "normal");
                        doc.text(": " + info.value, leftMargin + 35, yPoss);
                        yPoss += 7;
                    });
                }

                yPoss = (yPos + 50);

                const medecinInfo = [
                    { label: "N° Consultation", value: "C-"+consultation.code},
                    { label: "Medecin", value: "Dr. "+user.name },
                    { label: "Spécialité", value: typeacte.nom },
                    { label: "Prix Consultation", value: typeacte.prix+" Fcfa" },
                ];

                medecinInfo.forEach(info => {
                    doc.setFontSize(9);
                    doc.setFont("Helvetica", "bold");
                    doc.setTextColor(0, 0, 0);
                    doc.text(info.label, leftMargin + 100, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 140, yPoss);
                    yPoss += 7;
                });

                yPoss = (yPos + 90);

                const compteInfo = [
                    { label: "Montant Total", value: typeacte.prix + " Fcfa" },
                    ...(parseInt(consultation.part_assurance.replace(/[^0-9]/g, '')) > 0 
                        ? [{ label: "Part assurance", value: consultation.part_assurance + " Fcfa" }] 
                        : []),
                    { label: "Remise", value: consultation.remise + " Fcfa" },
                ];


                if (patient.taux !== null) {
                    compteInfo.push({ label: "Taux", value: patient.taux + "%" });
                }

                compteInfo.forEach(info => {
                    doc.setFontSize(9);
                    doc.setFont("Helvetica", "bold");
                    doc.setTextColor(0, 0, 0);
                    doc.text(info.label, leftMargin + 100, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 140, yPoss);
                    yPoss += 7;
                });

                yPoss += 1;

                doc.setFontSize(11);
                doc.setTextColor(0, 0, 0);
                doc.setFont("Helvetica", "bold");
                doc.text('Montant à payer', leftMargin + 100, yPoss);
                doc.setFont("Helvetica", "bold");
                doc.text(": "+consultation.part_patient+" Fcfa", leftMargin + 140, yPoss);

                if (patient.taux !== null) {
                    doc.setFontSize(8);
                    doc.setFont("Helvetica", "bold");
                    doc.setTextColor(0, 0, 0);
                    doc.text("Imprimer le "+new Date().toLocaleDateString()+" à "+new Date().toLocaleTimeString() , 5, yPoss + 16);
                }else{
                    doc.setFontSize(8);
                    doc.setFont("Helvetica", "bold");
                    doc.setTextColor(0, 0, 0);
                    doc.text("Imprimer le "+new Date().toLocaleDateString()+" à "+new Date().toLocaleTimeString() , 5, yPoss + 28);
                }

                // doc.setFontSize(10);
                // doc.setFont("Helvetica", "bold");
                // doc.setTextColor(0, 0, 0);
                // doc.text("Imprimer le "+new Date().toLocaleDateString()+" à "+new Date().toLocaleTimeString() , 5, yPoss + 15);
            }

            drawConsultationSection(yPos);

            doc.setFontSize(30);
            doc.setLineWidth(0.5);
            doc.setLineDashPattern([3, 3], 0);
            doc.line(0, (yPos + 137), 300, (yPos + 137));
            doc.setLineDashPattern([], 0);

            drawConsultationSection(yPos + 150);


            doc.output('dataurlnewwindow');
        }

        //-----------------------------------------------------------------------

        function payer_Exam()
        {
            const auth_id = {{ Auth::user()->id }};
            const code_fac = document.getElementById("id_code_fac_Exam").value;
            const montant_verser = document.getElementById("input_montant_verser_Exam");
            const montant_remis = document.getElementById("input_montant_remis_Exam");

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            if(!montant_verser.value.trim() || !montant_remis.value.trim()){
                var preloader = document.getElementById('preloader_ch');
                if (preloader) {
                    preloader.remove();
                }
                showAlert('Alert', 'Impossible d\'éffectuée le paiement.','error');
                return false;
            }

            $.ajax({
                url: '/api/facture_payer_examen/' + code_fac,
                method: 'GET',
                data: { 
                    montant_verser: montant_verser.value, 
                    montant_remis: montant_remis.value,
                    auth_id: auth_id,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    caisse_verf();

                    if (response.success) {

                        const examen = response.examen;
                        const facture = response.facture;
                        const patient = response.patient;
                        const acte = response.acte;
                        const examenpatient = response.examenpatient;

                        table_exam.ajax.reload(null, false);

                        generatePDFInvoice_Exam(examen, facture, patient, acte, examenpatient);

                        showAlert('Succès', 'Paiement éffectuée.','success');

                    } else if (response.error) {
                        showAlert('Alert', 'Une erreur est survenue lors du paiement, Veuillez ressayer.','error');
                    } else if (response.caisse_fermer) {
                        showAlert('Alert', 'La caisse est actuellement fermer, Veuillez ouvrir la caisse avant d\'éffectuer un encaissement.','info');
                    }

                },
                error: function(xhr, status, error) {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    showAlert('Alert', 'Une erreur est survenue lors du paiement.','error');
                }
            });
        }

        function initExam() {

            $('.Table_Exam').on('click', '#paye_Exam', function() {
                const code_fac = $(this).data('code_fac');
                const total_patient = $(this).data('total_patient');

                document.getElementById('input_montant_payer_Exam').value = `${formatPrice(total_patient)} Fcfa`;
                document.getElementById('input_montant_verser_Exam').value = '';
                document.getElementById('input_montant_remis_Exam').value = '0 Fcfa';
                document.getElementById('id_code_fac_Exam').value = `${code_fac}`;
            });

            $('.Table_Exam').on('click', '#detail_Exam', function() {
                const id = $(this).data('id');

                const tableBodyP = document.querySelector('#TableP_Exam tbody');
                const messageDivP = document.getElementById('message_TableP_Exam');
                const tableDivP = document.getElementById('div_TableP_Exam');
                const loaderDivP = document.getElementById('div_Table_loaderP_Exam');

                messageDivP.style.display = 'none';
                tableDivP.style.display = 'none';
                loaderDivP.style.display = 'block';

                fetch(`/api/list_facture_exam_d/${id}`) // API endpoint
                    .then(response => response.json())
                    .then(data => {
                        // Access the 'chambre' array from the API response
                        const factureds = data.factured;
                        const sumMontantEx = data.sumMontantEx;

                        // Clear any existing rows in the table body
                        tableBodyP.innerHTML = '';

                        if (factureds.length > 0) {

                            loaderDivP.style.display = 'none';
                            messageDivP.style.display = 'none';
                            tableDivP.style.display = 'block';

                            // Loop through each item in the chambre array
                            factureds.forEach((item, index) => {
                                // Create a new row
                                const row = document.createElement('tr');
                                // Create and append cells to the row based on your table's structure
                                row.innerHTML = `
                                    <td>
                                        <h6>${item.nom_ex}</h6>
                                    </td>
                                    <td>
                                        <h6>${item.cotation_ex}${item.valeur_ex}</h6>
                                    </td>
                                    <td>
                                        <h6>${item.prix_ex} Fcfa</h6>
                                    </td>
                                    <td>
                                        <h6>${item.accepte}</h6>
                                    </td>
                                    <td>
                                        <h6>${item.montant_ex} Fcfa</h6>
                                    </td>
                                `;
                                // Append the row to the table body
                                tableBodyP.appendChild(row);

                            });

                            const row2 = document.createElement('tr');
                            row2.innerHTML = `
                                <td colspan="3">&nbsp;</td>
                                <td colspan="2" >
                                    <h5 class="mt-4 text-success">
                                        Total : ${formatPriceT(sumMontantEx)} Fcfa
                                    </h5>
                                </td>
                            `;
                            tableBodyP.appendChild(row2);

                            const row3 = document.createElement('tr');
                            row3.innerHTML = `
                                <td colspan="5">
                                    <h6 class="text-danger">NOTE</h6>
                                    <p class="small m-0">
                                        Le Montant Total des examens  ajouter au montant du prélevement.
                                    </p>
                                </td>
                            `;

                            tableBodyP.appendChild(row3);

                        } else {
                            loaderDivP.style.display = 'none';
                            messageDivP.style.display = 'block';
                            tableDivP.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                        loaderDivD.style.display = 'none';
                        messageDivD.style.display = 'block';
                        tableDivD.style.display = 'none';
                    });
            });

            $('.Table_Exam').on('click', '#fiche_Exam', function() {

                var preloader_ch = `
                    <div id="preloader_ch">
                        <div class="spinner_preloader_ch"></div>
                    </div>
                `;
                // Add the preloader to the body
                document.body.insertAdjacentHTML('beforeend', preloader_ch);

                const id = $(this).data('id');

                fetch(`/api/detail_examen/${id}`) // API endpoint
                    .then(response => response.json())
                    .then(data => {
                        // Access the 'chambre' array from the API response
                        const examen = data.examen;
                        const facture = data.facture;
                        const patient = data.patient;
                        const acte = data.acte;
                        const examenpatient = data.examenpatient;

                        var preloader = document.getElementById('preloader_ch');
                        if (preloader) {
                            preloader.remove();
                        }

                        generatePDFInvoice_Exam(examen, facture, patient, acte, examenpatient);

                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                    });
            });
        }

        $('#btn_refresh_table_Exam').on('click', function () {
            table_exam.ajax.reload(null, false); 
        });

        function generatePDFInvoice_Exam(examen, facture, patient, acte, examenpatient) 
        {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });

            const pdfFilename = "EXAMEN Facture N°" + facture.code + " du " + formatDateHeure(facture.created_at);
            doc.setProperties({
                title: pdfFilename,
            });

            let yPos = 10;

            function drawConsultationSection(yPos) {
                rightMargin = 15;
                leftMargin = 15;
                pdfWidth = doc.internal.pageSize.getWidth();

                if (facture.statut == 'payer') {
                    const titlea = "Payer";
                    doc.setFontSize(100);
                    doc.setTextColor(174, 255, 165);
                    doc.setFont("Helvetica", "bold");
                    doc.text(titlea, 120, yPos + 120, { align: 'center', angle: 40 });
                }else{
                    const titlea = "Impayer";
                    doc.setFontSize(100);
                    doc.setTextColor(252, 173, 159);
                    doc.setFont("Helvetica", "bold");
                    doc.text(titlea, 120, yPos + 120, { align: 'center', angle: 40 });
                }

                const logoSrc = "{{asset('assets/images/logo.png')}}";
                const logoWidth = 22;
                const logoHeight = 22;
                doc.addImage(logoSrc, 'PNG', leftMargin, yPos - 7, logoWidth, logoHeight);

                // Informations de l'entreprise
                doc.setFontSize(10);
                doc.setTextColor(0, 0, 0);
                doc.setFont("Helvetica", "bold");
                // Texte de l'entreprise
                const title = "ESPACE MEDICO SOCIAL LA PYRAMIDE DU COMPLEXE";
                const titleWidth = doc.getTextWidth(title);
                const titleX = (doc.internal.pageSize.getWidth() - titleWidth) / 2;
                doc.text(title, titleX, yPos);
                // Texte de l'adresse
                doc.setFont("Helvetica", "normal");
                const address = "Abidjan Yopougon Selmer, Non loin du complexe sportif Jesse-Jackson - 04 BP 1523";
                const addressWidth = doc.getTextWidth(address);
                const addressX = (doc.internal.pageSize.getWidth() - addressWidth) / 2;
                doc.text(address, addressX, (yPos + 5));
                // Texte du téléphone
                const phone = "Tél.: 20 24 44 70 / 20 21 71 92 - Cel.: 01 01 01 63 43";
                const phoneWidth = doc.getTextWidth(phone);
                const phoneX = (doc.internal.pageSize.getWidth() - phoneWidth) / 2;
                doc.text(phone, phoneX, (yPos + 10));
                doc.setFontSize(10);
                doc.setFont("Helvetica", "normal");
                const examenDate = new Date(examen.created_at);
                // Formatter la date et l'heure séparément
                const formattedDate = examenDate.toLocaleDateString(); // Formater la date
                const formattedTime = examenDate.toLocaleTimeString();
                doc.text("Date: " + formattedDate, 15, (yPos + 25));
                doc.text("Heure: " + formattedTime, 15, (yPos + 30));

                //Ligne de séparation
                doc.setFontSize(15);
                doc.setFont("Helvetica", "bold");
                doc.setLineWidth(0.5);
                doc.setTextColor(0, 0, 0);
                // doc.line(10, 35, 200, 35); 
                let titleR;

                if (facture.statut == 'payer') {
                    titleR = "RECU DE PAIEMENT";
                } else {
                    titleR = "FACTURE EXAMEN";
                }

                const titleRWidth = doc.getTextWidth(titleR);
                const titleRX = (doc.internal.pageSize.getWidth() - titleRWidth) / 2;
                // Définir le padding
                const paddingh = 0; // Padding vertical
                const paddingw = 8; // Padding horizontal
                // Calculer les dimensions du rectangle
                const rectX = titleRX - paddingw; // X du rectangle
                const rectY = (yPos + 18) - paddingh; // Y du rectangle
                const rectWidth = titleRWidth + (paddingw * 2); // Largeur du rectangle
                const rectHeight = 15 + (paddingh * 2); // Hauteur du rectangle
                // Définir la couleur pour le cadre (noir)
                doc.setDrawColor(0, 0, 0);
                doc.rect(rectX, rectY, rectWidth, rectHeight); // Dessiner le rectangle
                // Ajouter le texte centré en gras
                doc.setFontSize(15);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(0, 0, 0); // Couleur du texte rouge
                doc.text(titleR, titleRX, (yPos + 25)); // Positionner le texte
                const titleN = "N° "+facture.code;
                doc.text(titleN, (doc.internal.pageSize.getWidth() - doc.getTextWidth(titleN)) / 2, (yPos + 31));

                doc.setFontSize(10);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(0, 0, 0);
                const numDossier = "N° Dossier : P-"+ patient.matricule;
                const numDossierWidth = doc.getTextWidth(numDossier);
                doc.text(numDossier, pdfWidth - rightMargin - numDossierWidth, yPos + 28);

                yPoss = (yPos + 50);

                const typeInfo = [
                    { label: "Type d'examen", value: acte.nom },
                    { label: "Prélevement", value: examen.prelevement +" Fcfa" },
                ];

                typeInfo.forEach(info => {
                    doc.setFontSize(8);
                    doc.setFont("Helvetica", "bold");
                    doc.text(info.label, leftMargin + 100, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 135, yPoss);
                    yPoss += 7;
                });

                yPoss = (yPos + 50);

                const patientInfo = [
                    { label: "Nom et Prénoms", value: patient.np },
                    { label: "Assurer", value: patient.assurer },
                    { label: "Age", value: patient.age+" an(s)" },
                    { label: "Domicile", value: patient.adresse },
                    { label: "Contact", value: "+225 "+patient.tel }
                ];

                if (patient.assurer == 'oui') {
                    patientInfo.push(
                        { label: "Assurance", value: patient.assurance },
                        { label: "Matricule", value: patient.matricule_assurance },
                    );
                }

                patientInfo.forEach(info => {
                    doc.setFontSize(8);
                    doc.setFont("Helvetica", "bold");
                    doc.text(info.label, leftMargin, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 35, yPoss);
                    yPoss += 7;
                });

                const donneeTables = examenpatient;
                let yPossT = yPoss + 5; // Initialisation de la position Y pour le tableau des soins

                const totalProduit = donneeTables.reduce((sum, item) => sum + parseInt(item.montant_ex.replace(/[^0-9]/g, '') || 0), 0);

                // Tableau dynamique pour les détails des soins infirmiers
                doc.autoTable({
                    startY: yPossT,
                    head: [['N°', 'Examen', 'Cotation', 'Prix', 'Accepté ?', 'Total']],
                    body: donneeTables.map((item, index) => [
                        index + 1,
                        item.nom_ex,
                        item.cotation_ex+""+item.valeur_ex,
                        item.prix_ex + " Fcfa",
                        item.accepte,
                        item.montant_ex + " Fcfa",
                    ]),
                    theme: 'striped',
                    foot: [[
                        { content: 'Totals', colSpan: 5, styles: { halign: 'center', fontStyle: 'bold' } },
                        { content: formatPrice(totalProduit) + " Fcfa", styles: { fontStyle: 'bold' } },
                    ]]
                });

                yPoss = doc.autoTable.previous.finalY || yPossT + 15;
                yPoss = yPoss + 15;

                if (yPoss + 35 > doc.internal.pageSize.height) {
                    doc.addPage();
                    yPoss = 20;
                }

                const compteInfo = [
                    { label: "Total", value: examen.montant+" Fcfa"},
                    { label: "Part assurance", value: examen.part_assurance+" Fcfa"},
                ];

                if (patient.taux !== null) {
                    compteInfo.push({ label: "Taux", value: patient.taux + "%" });
                }

                compteInfo.forEach(info => {
                    doc.setFontSize(9);
                    doc.setFont("Helvetica", "bold");
                    doc.text(info.label, leftMargin + 110, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 142, yPoss);
                    yPoss += 7;
                });

                doc.setFontSize(11);
                doc.setFont("Helvetica", "bold");
                doc.text('Montant à payer', leftMargin + 110, yPoss);
                doc.setFont("Helvetica", "bold");
                doc.text(": "+examen.part_patient+" Fcfa", leftMargin + 142, yPoss);

                    if (facture.statut == 'payer') {
                        const finalY = doc.autoTable.previous.finalY || yPossT + 10;

                        // Ajuster yPoss à la fin du tableau pour le placement des totaux
                        yPoss = finalY + 15;

                        // Déclarer finalInfo comme un tableau vide
                        const finalInfo = [];
                        
                        
                            finalInfo.push(
                                { label: "Montant Verser", value: facture.montant_verser },
                                { label: "Montant Remis", value: facture.montant_remis },
                                { label: "Reste a payer", value: facture.montant_restant },
                                );
                        

                        // Boucler à travers finalInfo pour afficher les informations
                        finalInfo.forEach(info => {
                            doc.setFontSize(9);
                            doc.setFont("Helvetica", "bold");
                            doc.text(info.label, leftMargin, yPoss);
                            doc.setFont("Helvetica", "normal");
                            doc.text(": " + info.value + " Fcfa", leftMargin + 30, yPoss);
                            yPoss += 7;
                        });
                    }

            }

            function addFooter() {
                // Add footer with current date and page number in X/Y format
                const pageCount = doc.internal.getNumberOfPages();
                const footerY = doc.internal.pageSize.getHeight() - 2; // 10 mm from the bottom

                for (let i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.setFontSize(8);
                    doc.setTextColor(0, 0, 0);
                    const pageText = `Page ${i} sur ${pageCount}`;
                    const pageTextWidth = doc.getTextWidth(pageText);
                    const centerX = (doc.internal.pageSize.getWidth() - pageTextWidth) / 2;
                    doc.text(pageText, centerX, footerY);
                    doc.text("Imprimé le : " + new Date().toLocaleDateString() + " à " + new Date().toLocaleTimeString(), 15, footerY); // Left-aligned
                }
            }

            drawConsultationSection(yPos);

            addFooter();

            doc.output('dataurlnewwindow');
        }

        //-----------------------------------------------------------------------

        function payer_Hos()
        {
            const auth_id = {{ Auth::user()->id }};
            const code_fac = document.getElementById("id_code_fac_Hos").value;
            const montant_verser = document.getElementById("input_montant_verser_Hos");
            const montant_remis = document.getElementById("input_montant_remis_Hos");

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            if(!montant_verser.value.trim() || !montant_remis.value.trim()){
                var preloader = document.getElementById('preloader_ch');
                if (preloader) {
                    preloader.remove();
                }
                showAlert('Alert', 'Impossible d\'éffectuée le paiement.','error');
                return false;
            }

            $.ajax({
                url: '/api/facture_payer_hos/' + code_fac,
                method: 'GET',
                data: { 
                    montant_verser: montant_verser.value, 
                    montant_remis: montant_remis.value,
                    auth_id: auth_id,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    caisse_verf();

                    if (response.success) {

                        showAlert('Succès', 'Paiement éffectuée.','success');

                        const hopital = response.hopital;
                        const facture = response.facture;
                        const patient = response.patient;
                        const nature = response.natureadmission;
                        const type = response.typeadmission;
                        const lit = response.lit;
                        const chambre = response.chambre;
                        const user = response.user;
                        const produit = response.produit;

                        table_hos.ajax.reload(null, false);

                        generatePDFInvoice_Hos(hopital, facture, patient, nature, type, lit, chambre, user, produit);

                    } else if (response.error) {
                        showAlert('Alert', 'Une erreur est survenue lors du paiement, Veuillez ressayer.','error');
                    } else if (response.caisse_fermer) {
                        showAlert('Alert', 'La caisse est actuellement fermer, Veuillez ouvrir la caisse avant d\'éffectuer un encaissement.','info');
                    }

                },
                error: function(xhr, status, error) {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    showAlert('Alert', 'Une erreur est survenue lors du paiement.','error');
                }
            });
        }

        function initHos() {

            $('.Table_Hos').on('click', '#paye_Hos', function() {
                const code_fac = $(this).data('code_fac');
                const part_patient = $(this).data('part_patient');

                document.getElementById('input_montant_payer_Hos').value = `${part_patient || 0} Fcfa`;
                document.getElementById('input_montant_verser_Hos').value = '';
                document.getElementById('input_montant_remis_Hos').value = '0 Fcfa';
                document.getElementById('id_code_fac_Hos').value = `${code_fac}`;
            });

            $('.Table_Hos').on('click', '#detail_Hos', function() {
                const id = $(this).data('id');

                fetch(`/api/detail_hos/${id}`) // API endpoint
                    .then(response => response.json())
                    .then(data => {
                        // Access the 'chambre' array from the API response
                        const modalD = document.getElementById('modal_detail_Hos');
                        modalD.innerHTML = '';

                        const hopital = data.hopital;
                        const facture = data.facture;
                        const patient = data.patient;
                        const nature = data.natureadmission;
                        const type = data.typeadmission;
                        const lit = data.lit;
                        const chambre = data.chambre;
                        const user = data.user;

                        const div = document.createElement('div');
                        div.innerHTML = `
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="">
                                        <div class="card-body">
                                            <div class="row justify-content-between">
                                                <div class="col-12 text-center">                  
                                                    <h6 class="fw-semibold">Docteur :</h6>
                                                    <p>${user.name}</p>
                                                    <h6 class="fw-semibold">Spécialité :</h6>
                                                    <p>${user.typeacte}</p>
                                                    <h6 class="fw-semibold">Chambre Occupé :</h6>
                                                    <p>CH-${chambre.code}</p>
                                                    <h6 class="fw-semibold">Lit Occupé :</h6>
                                                    <p>LIT-${lit.code}/${lit.type}</p>
                                                    <h6 class="fw-semibold">Prix :</h6>
                                                    <p>${chambre.prix} Fcfa</p>
                                                </div>
                                                <div class="col-12 text-center mt-4">
                                                    <h6 class="fw-semibold">Type d'admission :</h6>
                                                    <p>${type.nom}</p>
                                                    <h6 class="fw-semibold">Nature d'admission :</h6>
                                                    <p>${nature.nom}</p>
                                                    <h6 class="fw-semibold">Date d'entrer :</h6>
                                                    <p>${formatDate(hopital.date_debut)}</p>
                                                    <h6 class="fw-semibold">Date de sortie Probable :</h6>
                                                    <p>${formatDate(hopital.date_fin)}</p>
                                                    <h6 class="fw-semibold">Nombre de jours :</h6>
                                                    <p>${calculateDaysBetween(hopital.date_debut, hopital.date_fin)}</p>
                                                </div>
                                                <div class="col-12 text-center mt-4">
                                                    <h6 class="fw-semibold">N° Dossier :</h6>
                                                    <p>${patient.matricule}</p>
                                                    <h6 class="fw-semibold">Nom du patient :</h6>
                                                    <p>${patient.np}</p>
                                                    <h6 class="fw-semibold">contact :</h6>
                                                    <p>${patient.tel}</p>
                                                    <h6 class="fw-semibold">Assurer :</h6>
                                                    <p>${patient.assurer}</p>
                                                    ${patient.assurer === 'oui' ? `
                                                        <h6 class="fw-semibold">Taux :</h6>
                                                        <p>${patient.taux}%</p>

                                                        <h6 class="fw-semibold">Assurance :</h6>
                                                        <p>${patient.assurance}</p> 

                                                        <h6 class="fw-semibold">Matricule :</h6>
                                                        <p>${patient.matricule_assurance}</p>
                                                    ` : ''}
                                                </div>
                                                <div class="col-12 text-center mt-4">
                                                    <h6 class="fw-semibold">Part Patient :</h6>
                                                    <p>${hopital.part_patient} Fcfa</p>
                                                    <h6 class="fw-semibold">Part Assurance :</h6>
                                                    <p>${hopital.part_assurance} Fcfa</p>
                                                    <h6 class="fw-semibold">Remise :</h6>
                                                    <p>${hopital.remise ? hopital.remise : '0'} Fcfa</p>
                                                    <h6 class="fw-semibold">Montant Total :</h6>
                                                    <p>${hopital.montant} Fcfa</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                        modalD.appendChild(div);

                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                    });
            });

            $('.Table_Hos').on('click', '#detail_produit_Hos', function() {
                const id = $(this).data('id');
                const montant_soins = $(this).data('montant_soins');

                const tableBodyP = document.querySelector('#TableP_Hos tbody');
                const messageDivP = document.getElementById('message_TableP_Hos');
                const tableDivP = document.getElementById('div_TableP_Hos');
                const loaderDivP = document.getElementById('div_Table_loaderP_Hos');

                messageDivP.style.display = 'none';
                tableDivP.style.display = 'none';
                loaderDivP.style.display = 'block';

                fetch(`/api/list_facture_hos_d/${id}`) // API endpoint
                    .then(response => response.json())
                    .then(data => {
                        // Access the 'chambre' array from the API response
                        const factureds = data.factured;

                        // Clear any existing rows in the table body
                        tableBodyP.innerHTML = '';

                        if (factureds.length > 0) {

                            loaderDivP.style.display = 'none';
                            messageDivP.style.display = 'none';
                            tableDivP.style.display = 'block';

                            // Loop through each item in the chambre array
                            factureds.forEach((item, index) => {
                                // Create a new row
                                const row = document.createElement('tr');
                                // Create and append cells to the row based on your table's structure
                                row.innerHTML = `
                                    <td>
                                        <h6>${item.nom_produit}</h6>
                                    </td>
                                    <td>
                                        <h6>${item.prix_produit} Fcfa</h6>
                                    </td>
                                    <td>
                                        <h6>${item.quantite}</h6>
                                    </td>
                                    <td>
                                        <h6>${item.montant} Fcfa</h6>
                                    </td>
                                `;
                                // Append the row to the table body
                                tableBodyP.appendChild(row);

                            });

                            const row2 = document.createElement('tr');
                            row2.innerHTML = `
                                <td colspan="2">&nbsp;</td>
                                <td colspan="2" >
                                    <h5 class="mt-4 text-success">
                                        Total : ${montant_soins} Fcfa
                                    </h5>
                                </td>
                            `;
                            tableBodyP.appendChild(row2);

                            const row3 = document.createElement('tr');
                            row3.innerHTML = `
                                <td colspan="4">
                                    <h6 class="text-danger">NOTE</h6>
                                    <p class="small m-0">
                                        Le Montant Total des produits utilisés
                                        seront ajouter au montant total de la
                                        chambre occupé par le patient.
                                    </p>
                                </td>
                            `;

                            tableBodyP.appendChild(row3);

                        } else {
                            loaderDivP.style.display = 'none';
                            messageDivP.style.display = 'block';
                            tableDivP.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                        loaderDivD.style.display = 'none';
                        messageDivD.style.display = 'block';
                        tableDivD.style.display = 'none';
                    });
            });

            $('.Table_Hos').on('click', '#printer_Hos', function() {

                var preloader_ch = `
                    <div id="preloader_ch">
                        <div class="spinner_preloader_ch"></div>
                    </div>
                `;
                // Add the preloader to the body
                document.body.insertAdjacentHTML('beforeend', preloader_ch);

                const id = $(this).data('id');

                fetch(`/api/detail_hos/${id}`) // API endpoint
                    .then(response => response.json())
                    .then(data => {
                        // Access the 'chambre' array from the API response
                        const hopital = data.hopital;
                        const facture = data.facture;
                        const patient = data.patient;
                        const nature = data.natureadmission;
                        const type = data.typeadmission;
                        const lit = data.lit;
                        const chambre = data.chambre;
                        const user = data.user;
                        const produit = data.produit;

                        var preloader = document.getElementById('preloader_ch');
                        if (preloader) {
                            preloader.remove();
                        }

                        generatePDFInvoice_Hos(hopital, facture, patient, nature, type, lit, chambre, user, produit);

                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                    });
            });
        }

        $('#btn_refresh_table_Hos').on('click', function () {
            table_hos.ajax.reload(null, false); 
        });

        function generatePDFInvoice_Hos(hopital, facture, patient, nature, type, lit, chambre, user, produit) 
        {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });

            const pdfFilename = "HOSPITALISATION Facture N°" + facture.code + " du " + formatDateHeure(facture.created_at);
            doc.setProperties({
                title: pdfFilename,
            });

            let yPos = 10;

            function drawConsultationSection(yPos) {
                rightMargin = 15;
                leftMargin = 15;
                pdfWidth = doc.internal.pageSize.getWidth();

                if (facture.statut == 'payer') {
                    const titlea = "Payer";
                    doc.setFontSize(100);
                    doc.setTextColor(174, 255, 165);
                    doc.setFont("Helvetica", "bold");
                    doc.text(titlea, 120, yPos + 120, { align: 'center', angle: 40 });
                }else{
                    const titlea = "Impayer";
                    doc.setFontSize(100);
                    doc.setTextColor(252, 173, 159);
                    doc.setFont("Helvetica", "bold");
                    doc.text(titlea, 120, yPos + 120, { align: 'center', angle: 40 });
                }

                const logoSrc = "{{asset('assets/images/logo.png')}}";
                const logoWidth = 22;
                const logoHeight = 22;
                doc.addImage(logoSrc, 'PNG', leftMargin, yPos - 7, logoWidth, logoHeight);

                // Informations de l'entreprise
                doc.setFontSize(10);
                doc.setTextColor(0, 0, 0);
                doc.setFont("Helvetica", "bold");
                // Texte de l'entreprise
                const title = "ESPACE MEDICO SOCIAL LA PYRAMIDE DU COMPLEXE";
                const titleWidth = doc.getTextWidth(title);
                const titleX = (doc.internal.pageSize.getWidth() - titleWidth) / 2;
                doc.text(title, titleX, yPos);
                // Texte de l'adresse
                doc.setFont("Helvetica", "normal");
                const address = "Abidjan Yopougon Selmer, Non loin du complexe sportif Jesse-Jackson - 04 BP 1523";
                const addressWidth = doc.getTextWidth(address);
                const addressX = (doc.internal.pageSize.getWidth() - addressWidth) / 2;
                doc.text(address, addressX, (yPos + 5));
                // Texte du téléphone
                const phone = "Tél.: 20 24 44 70 / 20 21 71 92 - Cel.: 01 01 01 63 43";
                const phoneWidth = doc.getTextWidth(phone);
                const phoneX = (doc.internal.pageSize.getWidth() - phoneWidth) / 2;
                doc.text(phone, phoneX, (yPos + 10));
                doc.setFontSize(10);
                doc.setFont("Helvetica", "normal");
                const hopitalDate = new Date(hopital.created_at);
                // Formatter la date et l'heure séparément
                const formattedDate = hopitalDate.toLocaleDateString(); // Formater la date
                const formattedTime = hopitalDate.toLocaleTimeString();
                doc.text("Date: " + formattedDate, 15, (yPos + 25));
                doc.text("Heure: " + formattedTime, 15, (yPos + 30));

                //Ligne de séparation
                doc.setFontSize(15);
                doc.setFont("Helvetica", "bold");
                doc.setLineWidth(0.5);
                doc.setTextColor(0, 0, 0);
                // doc.line(10, 35, 200, 35);

                let titleR;

                if (facture.statut == 'payer') {
                    titleR = "RECU PAIEMENT";
                }else{
                    titleR = "FACTURE HOSPITALISATION";
                }

                const titleRWidth = doc.getTextWidth(titleR);
                const titleRX = (doc.internal.pageSize.getWidth() - titleRWidth) / 2;
                // Définir le padding
                const paddingh = 0; // Padding vertical
                const paddingw = 15; // Padding horizontal
                // Calculer les dimensions du rectangle
                const rectX = titleRX - paddingw; // X du rectangle
                const rectY = (yPos + 18) - paddingh; // Y du rectangle
                const rectWidth = titleRWidth + (paddingw * 2); // Largeur du rectangle
                const rectHeight = 15 + (paddingh * 2); // Hauteur du rectangle
                // Définir la couleur pour le cadre (noir)
                doc.setDrawColor(0, 0, 0);
                doc.rect(rectX, rectY, rectWidth, rectHeight); // Dessiner le rectangle
                // Ajouter le texte centré en gras
                doc.setFontSize(15);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(0, 0, 0); // Couleur du texte rouge
                doc.text(titleR, titleRX, (yPos + 25)); // Positionner le texte
                const titleN = "N° "+facture.code;
                doc.text(titleN, (doc.internal.pageSize.getWidth() - doc.getTextWidth(titleN)) / 2, (yPos + 31));

                doc.setFontSize(10);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(0, 0, 0);
                const numDossier = "N° Dossier : P-"+ patient.matricule;
                const numDossierWidth = doc.getTextWidth(numDossier);
                doc.text(numDossier, pdfWidth - rightMargin - numDossierWidth, yPos + 28);

                yPoss = (yPos + 50);

                const patientInfo = [
                    { label: "Nom et Prénoms", value: patient.np },
                    { label: "Assurer", value: patient.assurer },
                    { label: "Age", value: patient.age+" an(s)" },
                    { label: "Domicile", value: patient.adresse },
                    { label: "Contact", value: "+225 "+patient.tel },
                ];

                if (patient.assurer == 'oui') {
                    patientInfo.push(
                        { label: "Assurance", value: patient.assurance },
                        { label: "Matricule", value: patient.matricule_assurance },
                    );
                }

                patientInfo.push(
                    { label: "Lit Occupée", value: "LIT-"+lit.code+"/"+lit.type },
                    { label: "Type d'admission", value: type.nom },
                    { label: "Nature d'admission", value: nature.nom },
                );

                patientInfo.forEach(info => {
                    doc.setFontSize(8);
                    doc.setFont("Helvetica", "bold");
                    doc.text(info.label, leftMargin, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 35, yPoss);
                    yPoss += 7;
                });

                yPoss = (yPos + 50);

                const medecinInfo = [];

                if (hopital.num_bon && hopital.num_bon !== "") {
                    medecinInfo.push({ label: "N° prise en charge", value: hopital.num_bon });
                }

                medecinInfo.push(
                    { label: "Medecin", value: "Dr. "+user.name },
                    { label: "Spécialité", value: user.typeacte },
                    { label: "Date d'entrée le ", value: formatDate(hopital.date_debut) },
                    { label: "Date de sortie prévu le ", value: formatDate(hopital.date_fin) },
                    { label: "Nombre de jours ", value: calculateDaysBetween(hopital.date_debut, hopital.date_fin)+" Jour(s)" },
                    { label: "Prix Chambre ", value: chambre.prix+" Fcfa" },
                    { label: "Montant Total Chambre ", value: hopital.montant_chambre+" Fcfa" },
                    { label: "Montant Total ", value: hopital.montant+" Fcfa" },
                );

                medecinInfo.forEach(info => {
                    doc.setFontSize(8);
                    doc.setFont("Helvetica", "bold");
                    doc.text(info.label, leftMargin + 100, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 135, yPoss);
                    yPoss += 7;
                });

                yPoss = yPoss + 10;

                const donneeTable = produit;

                if ( donneeTable.length > 0) {

                    yPossT = yPoss;

                    const totalProduit = donneeTable.reduce((sum, item) => sum + parseInt(item.montant.replace(/[^0-9]/g, '') || 0), 0);

                    doc.autoTable({
                        startY: yPossT, // Ajustez cette valeur pour le placer correctement sur la page
                        head: [['N°','Nom du produit utilisé', 'Quantité', 'Prix Unitaire', 'Montant']], // En-têtes du tableau
                        body: donneeTable.map((item, index) => [
                            index + 1,
                            item.nom_produit, // Nom du produit
                            item.quantite, // Quantité
                            item.prix_produit+" Fcfa", // Prix unitaire
                            item.montant+" Fcfa", // Montant (quantité * prix unitaire)
                        ]), // Remplace les lignes par les données dynamiques
                        theme: 'striped', // Vous pouvez changer le thème en plain, grid, etc.
                        foot: [[
                            { content: 'Totals', colSpan: 4, styles: { halign: 'center', fontStyle: 'bold' } },
                            { content: formatPrice(totalProduit) + " Fcfa", styles: { fontStyle: 'bold' } },
                        ]]
                    });

                    const finalY = doc.autoTable.previous.finalY || yPossT + 10;
                    yPoss = finalY + 10;

                    if (yPoss + 60 > doc.internal.pageSize.height) {
                        doc.addPage();
                        yPoss = 20;
                    }

                    const finalInfo = [];    

                    finalInfo.push(
                        { label: "Montant Total", value: hopital.montant +" Fcfa" },
                        ...(hopital.part_assurance.replace(/[^0-9]/g, '') > 0 ? 
                            [{ label: "Part assurance", value: hopital.part_assurance + " Fcfa" }] 
                            : []),
                        { label: "Remise", value: hopital.remise ? hopital.remise + " Fcfa" : "0 Fcfa" },
                    );

                    if (patient.taux !== null) {
                        finalInfo.push({ label: "Taux", value: patient.taux + "%" });
                    }

                    // Boucler à travers finalInfo pour afficher les informations
                    finalInfo.forEach(info => {
                        doc.setFontSize(9);
                        doc.setFont("Helvetica", "bold");
                        doc.text(info.label, leftMargin + 110, yPoss);
                        doc.setFont("Helvetica", "normal");
                        doc.text(": " + info.value, leftMargin + 150, yPoss);
                        yPoss += 7;
                    });
                    doc.setFontSize(11);
                    doc.setFont("Helvetica", "bold");
                    doc.text('Montant à payer', leftMargin + 110, yPoss);
                    doc.setFont("Helvetica", "bold");
                    doc.text(": "+hopital.part_patient+" Fcfa", leftMargin + 150, yPoss);

                    if (facture.statut == 'payer') {

                        yPoss += 7;

                        const totalMontant = parseInt(hopital.part_patient.replace(/[^0-9]/g, ''));
                        const montantVerser = parseInt(facture.montant_verser.replace(/[^0-9]/g, ''));
                        const montantRemis = parseInt(facture.montant_remis.replace(/[^0-9]/g, ''));
                        const resteAPayer = Math.max(montantVerser - (totalMontant + montantRemis), 0);

                        doc.setFontSize(10);
                        doc.setFont("Helvetica", "bold");
                        doc.text('Montant Verser', leftMargin + 110, yPoss);
                        doc.setFont("Helvetica", "bold");
                        doc.text(": "+facture.montant_verser+" Fcfa", leftMargin + 150, yPoss);
                        yPoss += 7;

                        doc.setFontSize(10);
                        doc.setFont("Helvetica", "bold");
                        doc.text('Montant Remis', leftMargin + 110, yPoss);
                        doc.setFont("Helvetica", "bold");
                        doc.text(": "+facture.montant_remis+" Fcfa", leftMargin + 150, yPoss);
                        yPoss += 7;

                        doc.setFontSize(10);
                        doc.setFont("Helvetica", "bold");
                        doc.text('Reste à payer', leftMargin + 110, yPoss);
                        doc.setFont("Helvetica", "bold");
                        doc.text(": "+resteAPayer+" Fcfa", leftMargin + 150, yPoss);

                    }

                } else {

                    yPoss += 7;
                    // Déclarer finalInfo comme un tableau vide
                    const finalInfo = [];

                    // Ajouter l'entrée "Montant a payer"
                    finalInfo.push(
                        { label: "Montant Total", value: hopital.montant + " Fcfa" },
                        ...(hopital.part_assurance.replace(/[^0-9]/g, '') > 0 ? 
                            [{ label: "Part assurance", value: hopital.part_assurance + " Fcfa" }] 
                            : []),
                        { label: "Remise", value: hopital.remise ? hopital.remise + " Fcfa" : "0 Fcfa" }
                    );

                    if (patient.taux !== null) {
                        finalInfo.push({ label: "Taux", value: patient.taux + "%" });
                    }
                    // Boucler à travers finalInfo pour afficher les informations
                    finalInfo.forEach(info => {
                        doc.setFontSize(9);
                        doc.setFont("Helvetica", "bold");
                        doc.text(info.label, leftMargin + 110, yPoss);
                        doc.setFont("Helvetica", "normal");
                        doc.text(": " + info.value, leftMargin + 150, yPoss);
                        yPoss += 7;
                    });
                    doc.setFontSize(11);
                    doc.setFont("Helvetica", "bold");
                    doc.text('Montant à payer', leftMargin + 110, yPoss);
                    doc.setFont("Helvetica", "bold");
                    doc.text(": "+hopital.part_patient+" Fcfa", leftMargin + 150, yPoss);

                    if (facture.statut == 'payer') {

                        yPoss += 7;

                        const totalMontant = parseInt(hopital.part_patient.replace(/[^0-9]/g, ''));
                        const montantVerser = parseInt(facture.montant_verser.replace(/[^0-9]/g, ''));
                        const montantRemis = parseInt(facture.montant_remis.replace(/[^0-9]/g, ''));
                        // Calculate the remaining amount, ensuring it's always positive
                        const resteAPayer = Math.max(montantVerser - (totalMontant + montantRemis), 0);

                        doc.setFontSize(10);
                        doc.setFont("Helvetica", "bold");
                        doc.text('Montant Verser', leftMargin + 110, yPoss);
                        doc.setFont("Helvetica", "bold");
                        doc.text(": "+facture.montant_verser+" Fcfa", leftMargin + 150, yPoss);
                        yPoss += 7;

                        doc.setFontSize(10);
                        doc.setFont("Helvetica", "bold");
                        doc.text('Montant Remis', leftMargin + 110, yPoss);
                        doc.setFont("Helvetica", "bold");
                        doc.text(": "+facture.montant_remis+" Fcfa", leftMargin + 150, yPoss);
                        yPoss += 7;

                        doc.setFontSize(10);
                        doc.setFont("Helvetica", "bold");
                        doc.text('Reste à payer', leftMargin + 110, yPoss);
                        doc.setFont("Helvetica", "bold");
                        doc.text(": "+resteAPayer+" Fcfa", leftMargin + 150, yPoss);

                    }

                }

            }

            function addFooter() {
                // Add footer with current date and page number in X/Y format
                const pageCount = doc.internal.getNumberOfPages();
                const footerY = doc.internal.pageSize.getHeight() - 2; // 10 mm from the bottom

                for (let i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.setFontSize(8);
                    doc.setTextColor(0, 0, 0);
                    const pageText = `Page ${i} sur ${pageCount}`;
                    const pageTextWidth = doc.getTextWidth(pageText);
                    const centerX = (doc.internal.pageSize.getWidth() - pageTextWidth) / 2;
                    doc.text(pageText, centerX, footerY);
                    doc.text("Imprimé le : " + new Date().toLocaleDateString() + " à " + new Date().toLocaleTimeString(), 15, footerY); // Left-aligned
                }
            }

            drawConsultationSection(yPos);

            addFooter();

            doc.output('dataurlnewwindow');
        }

        //-----------------------------------------------------------------------

        function payer_Soinsam()
        {
            const auth_id = {{ Auth::user()->id }};
            const code_fac = document.getElementById("id_code_fac_Soinsam").value;
            const montant_verser = document.getElementById("input_montant_verser_Soinsam");
            const montant_remis = document.getElementById("input_montant_remis_Soinsam");

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            if(!montant_verser.value.trim() || !montant_remis.value.trim()){
                var preloader = document.getElementById('preloader_ch');
                if (preloader) {
                    preloader.remove();
                }
                showAlert("ALERT", "Impossible d\'éffectuée le paiement.", "warning");
                return false;
            }

            $.ajax({
                url: '/api/facture_payer_soinsam/' + code_fac,
                method: 'GET',
                data: { 
                    montant_verser: montant_verser.value, 
                    montant_remis: montant_remis.value,
                    auth_id: auth_id,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    caisse_verf();

                    if (response.success) {

                        const soinspatient = response.soinspatient;
                        const facture = response.facture;
                        const patient = response.patient;
                        const typesoins = response.typesoins;
                        const soins = response.soins;
                        const produit = response.produit;

                        showAlert("ALERT", "Paiement éffectuée.", "success");

                        table_soinsam.ajax.reload(null, false);

                        generatePDFInvoice_Soinsam(soinspatient, facture, patient, typesoins, soins, produit);

                    } else if (response.error) {
                        showAlert("ALERT", "Une erreur est survenue lors du paiement, Veuillez ressayer.", "error");
                    } else if (response.caisse_fermer) {
                        showAlert('Alert', 'La caisse est actuellement fermer, Veuillez ouvrir la caisse avant d\'éffectuer un encaissement.','info');
                    }

                },
                error: function(xhr, status, error) {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    showAlert("ALERT", "Une erreur est survenue lors du paiement.", "error");
                }
            });
        }

        function initSoinsam() {

            $('.Table_Soinsam').on('click', '#paye_Soinsam', function() {
                const code_fac = $(this).data('code_fac');
                const part_patient = $(this).data('part_patient');

                document.getElementById('input_montant_payer_Soinsam').value = `${part_patient || 0} Fcfa`;
                document.getElementById('input_montant_verser_Soinsam').value = '';
                document.getElementById('input_montant_remis_Soinsam').value = '0 Fcfa';
                document.getElementById('id_code_fac_Soinsam').value = `${code_fac}`;
            });

            $('.Table_Soinsam').on('click', '#detail_Soinsam', function() {
                const id = $(this).data('id');

                fetch(`/api/detail_soinam/${id}`) // API endpoint
                    .then(response => response.json())
                    .then(data => {
                        // Access the 'chambre' array from the API response
                        const modal = document.getElementById('modal_detail_Soinsam');
                        modal.innerHTML = '';

                        const soinspatient = data.soinspatient;
                        const facture = data.facture;
                        const patient = data.patient;
                        const typesoins = data.typesoins;
                        const soins = data.soins;
                        const produit = data.produit;

                        const div = document.createElement('div');
                        div.innerHTML = `
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="">
                                        <div class="card-body">
                                            <div class="row justify-content-between">
                                                <div class="col-12 text-center mt-4">
                                                    <h6 class="fw-semibold">Type de Soins :</h6>
                                                    <p>${typesoins.nom}</p>
                                                    <h6 class="fw-semibold">N° Dossier :</h6>
                                                    <p>${patient.matricule}</p>
                                                    <h6 class="fw-semibold">Nom du patient :</h6>
                                                    <p>${patient.np}</p>
                                                    <h6 class="fw-semibold">contact :</h6>
                                                    <p>${patient.tel}</p>
                                                    <h6 class="fw-semibold">Assurer :</h6>
                                                    <p>${patient.assurer}</p>
                                                    ${patient.assurer === 'oui' ? `
                                                        <h6 class="fw-semibold">Taux :</h6>
                                                        <p>${patient.taux}%</p>

                                                        <h6 class="fw-semibold">Assurance :</h6>
                                                        <p>${patient.assurance}</p> 

                                                        <h6 class="fw-semibold">Matricule :</h6>
                                                        <p>${patient.matricule_assurance}</p>
                                                    ` : ''}
                                                </div>
                                                <div class="col-12 text-center mt-4">
                                                    <h6 class="fw-semibold">Part Patient :</h6>
                                                    <p>${soinspatient.part_patient} Fcfa</p>
                                                    <h6 class="fw-semibold">Part Assurance :</h6>
                                                    <p>${soinspatient.part_assurance} Fcfa</p>
                                                    <h6 class="fw-semibold">Remise :</h6>
                                                    <p>${soinspatient.remise ? soinspatient.remise : '0'} Fcfa</p>
                                                    <h6 class="fw-semibold">Montant Total :</h6>
                                                    <p>${soinspatient.montant} Fcfa</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                        modal.appendChild(div);

                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                    });
            });

            $('.Table_Soinsam').on('click', '#detail_produit_Soinsam', function() {
                const id = $(this).data('id');
                const stotal = $(this).data('stotal');
                const prototal = $(this).data('prototal');

                const tableBodyP = document.querySelector('#TableP_Soinsam tbody');
                const tableBodyProdP=document.querySelector('#TableProdP_Soinsam tbody');
                const messageDivP = document.getElementById('message_TableP_Soinsam');
                const tableDivP = document.getElementById('div_TableP_Soinsam');
                const tableDivProdP = document.getElementById('div_TableProdP_Soinsam');
                const loaderDivP = document.getElementById('div_Table_loaderP_Soinsam');

                messageDivP.style.display = 'none';
                tableDivP.style.display = 'none';
                tableDivProdP.style.display = 'none';
                loaderDivP.style.display = 'block';

                fetch(`/api/detail_soinam/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        const soinspatient = data.soinspatient;
                        const soins = data.soins;
                        const produit = data.produit;

                        // Clear existing rows
                        tableBodyP.innerHTML = '';
                        tableBodyProdP.innerHTML = ''; // Pour les produits

                        if (soins.length > 0 || produits.length > 0) {

                            loaderDivP.style.display = 'none';
                            messageDivP.style.display = 'none';
                            tableDivP.style.display = 'block';
                            tableDivProdP.style.display = 'block';

                            // Remplir le tableau des soins
                            soins.forEach((item, index) => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td>
                                        <h6>${item.nom_si}</h6>
                                    </td>
                                    <td>
                                        <h6>${item.prix_si} Fcfa</h6>
                                    </td>
                                `;
                                tableBodyP.appendChild(row);
                            });

                            const rowTotalSoin = document.createElement('tr');
                            rowTotalSoin.innerHTML = `
                                <td >&nbsp;</td>
                                <td >
                                    <h5 class="mt-4 text-success">
                                        Total Soins : ${formatPriceT(stotal)} Fcfa
                                    </h5>
                                </td>
                            `;
                            tableBodyP.appendChild(rowTotalSoin);

                            // Remplir le tableau des produits
                            produit.forEach((item, index) => {
                                const rowProd = document.createElement('tr');
                                rowProd.innerHTML = `
                                    <td>
                                        <h6>${item.nom_pro}</h6>
                                    </td>
                                    <td>
                                        <h6>${item.prix_pro} Fcfa</h6>
                                    </td>
                                    <td>
                                        <h6>${item.quantite}</h6>
                                    </td>
                                    <td>
                                        <h6>${item.montant} Fcfa</h6>
                                    </td>
                                `;
                                tableBodyProdP.appendChild(rowProd);
                            });

                            const rowTotalProd = document.createElement('tr');
                            rowTotalProd.innerHTML = `
                                <td colspan="2" >&nbsp;</td>
                                <td colspan="2">
                                    <h5 class="mt-4 text-success">
                                        Total Produits : ${formatPriceT(prototal)} Fcfa
                                    </h5>
                                </td>
                            `;
                            tableBodyProdP.appendChild(rowTotalProd);

                            const rowNote = document.createElement('tr');
                            rowNote.innerHTML = `
                                <td colspan="4">
                                    <h6 class="text-danger">NOTE</h6>
                                    <p class="small m-0">
                                        Le Montant Total des produits utilisés
                                        seront ajoutés au montant total des soins.
                                    </p>
                                </td>
                            `;

                            tableBodyProdP.appendChild(rowNote);

                        } else {
                            loaderDivP.style.display = 'none';
                            messageDivP.style.display = 'block';
                            tableDivP.style.display = 'none';
                            tableDivProdP.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                        loaderDivP.style.display = 'none';
                        messageDivP.style.display = 'block';
                        tableDivP.style.display = 'none';
                        tableDivProdP.style.display = 'none';
                    });
            });

            $('.Table_Soinsam').on('click', '#printer_Soinsam', function() {

                var preloader_ch = `
                    <div id="preloader_ch">
                        <div class="spinner_preloader_ch"></div>
                    </div>
                `;
                // Add the preloader to the body
                document.body.insertAdjacentHTML('beforeend', preloader_ch);

                const id = $(this).data('id');

                fetch(`/api/detail_soinam/${id}`) // API endpoint
                    .then(response => response.json())
                    .then(data => {
                        // Access the 'chambre' array from the API response
                        const soinspatient = data.soinspatient;
                        const facture = data.facture;
                        const patient = data.patient;
                        const typesoins = data.typesoins;
                        const soins = data.soins;
                        const produit = data.produit;

                        var preloader = document.getElementById('preloader_ch');
                        if (preloader) {
                            preloader.remove();
                        }

                        generatePDFInvoice_Soinsam(soinspatient, facture, patient, typesoins, soins, produit);

                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                    });
            });
        }

        $('#btn_refresh_table_Soinsam').on('click', function () {
            table_soinsam.ajax.reload(null, false); 
        });

        function generatePDFInvoice_Soinsam(soinspatient, facture, patient, typesoins, soins, produit) 
        {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });

            const pdfFilename = "SOINS AMBULATOIRE Facture N°" + facture.code + " du " + formatDateHeure(facture.created_at);
            doc.setProperties({
                title: pdfFilename,
            });

            let yPos = 10;

            function drawConsultationSection(yPos) {
                rightMargin = 15;
                leftMargin = 15;
                pdfWidth = doc.internal.pageSize.getWidth();

                if (facture.statut == 'payer') {
                    const titlea = "Payer";
                    doc.setFontSize(100);
                    doc.setTextColor(174, 255, 165);
                    doc.setFont("Helvetica", "bold");
                    doc.text(titlea, 120, yPos + 120, { align: 'center', angle: 40 });
                }else{
                    const titlea = "Impayer";
                    doc.setFontSize(100);
                    doc.setTextColor(252, 173, 159);
                    doc.setFont("Helvetica", "bold");
                    doc.text(titlea, 120, yPos + 120, { align: 'center', angle: 40 });
                }

                const logoSrc = "{{asset('assets/images/logo.png')}}";
                const logoWidth = 22;
                const logoHeight = 22;
                doc.addImage(logoSrc, 'PNG', leftMargin, yPos - 7, logoWidth, logoHeight);

                // Informations de l'entreprise
                doc.setFontSize(10);
                doc.setTextColor(0, 0, 0);
                doc.setFont("Helvetica", "bold");
                // Texte de l'entreprise
                const title = "ESPACE MEDICO SOCIAL LA PYRAMIDE DU COMPLEXE";
                const titleWidth = doc.getTextWidth(title);
                const titleX = (doc.internal.pageSize.getWidth() - titleWidth) / 2;
                doc.text(title, titleX, yPos);
                // Texte de l'adresse
                doc.setFont("Helvetica", "normal");
                const address = "Abidjan Yopougon Selmer, Non loin du complexe sportif Jesse-Jackson - 04 BP 1523";
                const addressWidth = doc.getTextWidth(address);
                const addressX = (doc.internal.pageSize.getWidth() - addressWidth) / 2;
                doc.text(address, addressX, (yPos + 5));
                // Texte du téléphone
                const phone = "Tél.: 20 24 44 70 / 20 21 71 92 - Cel.: 01 01 01 63 43";
                const phoneWidth = doc.getTextWidth(phone);
                const phoneX = (doc.internal.pageSize.getWidth() - phoneWidth) / 2;
                doc.text(phone, phoneX, (yPos + 10));
                doc.setFontSize(10);
                doc.setFont("Helvetica", "normal");
                const spatientDate = new Date(soinspatient.created_at);
                // Formatter la date et l'heure séparément
                const formattedDate = spatientDate.toLocaleDateString(); // Formater la date
                const formattedTime = spatientDate.toLocaleTimeString();
                doc.text("Date: " + formattedDate, 15, (yPos + 25));
                doc.text("Heure: " + formattedTime, 15, (yPos + 30));

                //Ligne de séparation
                doc.setFontSize(15);
                doc.setFont("Helvetica", "bold");
                doc.setLineWidth(0.5);
                doc.setTextColor(0, 0, 0);
                // doc.line(10, 35, 200, 35); 

                let titleR;

                if (facture.statut == 'payer') {
                    titleR = "RECU DE PAIEMENT";
                }else{
                    titleR = "FACTURE SOINS AMBULATOIRES";
                }

                const titleRWidth = doc.getTextWidth(titleR);
                const titleRX = (doc.internal.pageSize.getWidth() - titleRWidth) / 2;
                // Définir le padding
                const paddingh = 0; // Padding vertical
                const paddingw = 8; // Padding horizontal
                // Calculer les dimensions du rectangle
                const rectX = titleRX - paddingw; // X du rectangle
                const rectY = (yPos + 18) - paddingh; // Y du rectangle
                const rectWidth = titleRWidth + (paddingw * 2); // Largeur du rectangle
                const rectHeight = 15 + (paddingh * 2); // Hauteur du rectangle
                // Définir la couleur pour le cadre (noir)
                doc.setDrawColor(0, 0, 0);
                doc.rect(rectX, rectY, rectWidth, rectHeight); // Dessiner le rectangle
                // Ajouter le texte centré en gras
                doc.setFontSize(15);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(0, 0, 0); // Couleur du texte rouge
                doc.text(titleR, titleRX, (yPos + 25)); // Positionner le texte
                const titleN = "N° "+facture.code;
                doc.text(titleN, (doc.internal.pageSize.getWidth() - doc.getTextWidth(titleN)) / 2, (yPos + 31));

                doc.setFontSize(10);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(0, 0, 0);
                const numDossier = "N° Dossier : P-"+ patient.matricule;
                const numDossierWidth = doc.getTextWidth(numDossier);
                doc.text(numDossier, pdfWidth - rightMargin - numDossierWidth, yPos + 28);

                yPoss = (yPos + 50);

                const patientInfo = [
                    { label: "Nom et Prénoms", value: patient.np },
                    { label: "Assurer", value: patient.assurer },
                    { label: "Age", value: patient.age+" an(s)" },
                    { label: "Domicile", value: patient.adresse },
                    { label: "Contact", value: "+225 "+patient.tel }
                ];

                if (patient.assurer == 'oui') {
                    patientInfo.push(
                        { label: "Assurance", value: patient.assurance },
                        { label: "Matricule", value: patient.matricule_assurance },
                    );
                }

                patientInfo.forEach(info => {
                    doc.setFontSize(8);
                    doc.setFont("Helvetica", "bold");
                    doc.text(info.label, leftMargin, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 35, yPoss);
                    yPoss += 7;
                });

                yPoss = (yPos + 50);

                const typeInfo = [
                    { label: "Type de Soins", value: typesoins.nom },
                    { label: "Soins Infirmiers", value: soins.length },
                    { label: "Produits Utilisés", value: produit.length },
                    { label: "Total", value: soinspatient.montant ? soinspatient.montant + " Fcfa" : "0 Fcfa" },
                    ...(soinspatient.part_assurance.replace(/[^0-9]/g, '') > 0 ? 
                        [{ label: "Part assurance", value: soinspatient.part_assurance + " Fcfa" }] 
                        : []),
                    { label: "Remise", value: soinspatient.remise ? soinspatient.remise + " Fcfa" : "0 Fcfa" }
                ];

                if (patient.taux !== null) {
                    typeInfo.push({ label: "Taux", value: patient.taux + "%" });
                }

                typeInfo.push({ label: "Montant à payer", value: soinspatient.part_patient +" Fcfa" });

                typeInfo.forEach(info => {
                    doc.setFontSize(8);
                    doc.setFont("Helvetica", "bold");
                    doc.text(info.label, leftMargin + 100, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 135, yPoss);
                    yPoss += 7;
                });

                const donneeTables = soins;
                let yPossT = yPoss + 5; // Initialisation de la position Y pour le tableau des soins

                const totalsi = donneeTables.reduce((sum, item) => sum + parseInt(item.prix_si.replace(/[^0-9]/g, '') || 0), 0);

                // Tableau dynamique pour les détails des soins infirmiers
                doc.autoTable({
                    startY: yPossT,
                    head: [['N°', 'Nom du Soins Infirmiers', 'Prix Unitaire']],
                    body: donneeTables.map((item, index) => [
                        index + 1,
                        item.nom_si,
                        item.prix_si + " Fcfa",
                    ]),
                    theme: 'striped',
                    foot: [[
                        { content: 'Totals', colSpan: 2, styles: { halign: 'center', fontStyle: 'bold' } },
                        { content: formatPrice(totalsi) + " Fcfa", styles: { fontStyle: 'bold' } },
                    ]]
                });

                // Récupérer la position Y de la dernière ligne du tableau
                yPoss = doc.autoTable.previous.finalY || yPossT + 10;
                yPoss = yPoss + 10;

                // Répéter le processus pour les produits
                const donneeTable = produit;
                yPossT = yPoss; // Ajuster la position Y pour le tableau des produits

                const totalsoins = donneeTable.reduce((sum, item) => sum + parseInt(item.montant.replace(/[^0-9]/g, '') || 0), 0);

                doc.autoTable({
                    startY: yPossT,
                    head: [['N°', 'Nom du produit utilisé', 'Quantité', 'Prix Unitaire', 'Montant']],
                    body: donneeTable.map((item, index) => [
                        index + 1,
                        item.nom_pro,
                        item.quantite_pro,
                        item.prix_pro + " Fcfa",
                        item.montant + " Fcfa",
                    ]),
                    theme: 'striped',
                    foot: [[
                        { content: 'Totals', colSpan: 4, styles: { halign: 'center', fontStyle: 'bold' } },
                        { content: formatPrice(totalsoins) + " Fcfa", styles: { fontStyle: 'bold' } },
                    ]]
                });

                if (facture.statut == 'payer') {
                    yPoss = doc.autoTable.previous.finalY || yPossT + 10;
                    yPoss = yPoss + 10;

                    if (yPoss + 30 > doc.internal.pageSize.height) {
                        doc.addPage();
                        yPoss = 20;
                    }
                    
                    const totalMontant = parseInt(soinspatient.part_patient.replace(/[^0-9]/g, ''));
                    const montantVerser = parseInt(facture.montant_verser.replace(/[^0-9]/g, ''));
                    const montantRemis = parseInt(facture.montant_remis.replace(/[^0-9]/g, ''));
                    const resteAPayer = Math.max(montantVerser - (totalMontant + montantRemis), 0);

                        doc.setFontSize(10);
                        doc.setFont("Helvetica", "bold");
                        doc.text('Montant Versé', leftMargin + 110, yPoss);
                        doc.setFont("Helvetica", "bold");
                        doc.text(": " + facture.montant_verser + " Fcfa", leftMargin + 150, yPoss);
                        yPoss += 7;

                        doc.setFontSize(10);
                        doc.setFont("Helvetica", "bold");
                        doc.text('Montant Remis', leftMargin + 110, yPoss);
                        doc.setFont("Helvetica", "bold");
                        doc.text(": " + facture.montant_remis + " Fcfa", leftMargin + 150, yPoss);
                        yPoss += 7;

                        // Display Reste à Payer
                        doc.setFontSize(10);
                        doc.setFont("Helvetica", "bold");
                        doc.text('Reste à Payer', leftMargin + 110, yPoss);
                        doc.setFont("Helvetica", "bold");
                        doc.text(": " + resteAPayer + " Fcfa", leftMargin + 150, yPoss);
                }

            }

            function addFooter() {
                // Add footer with current date and page number in X/Y format
                const pageCount = doc.internal.getNumberOfPages();
                const footerY = doc.internal.pageSize.getHeight() - 2; // 10 mm from the bottom

                for (let i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.setFontSize(8);
                    doc.setTextColor(0, 0, 0);
                    const pageText = `Page ${i} sur ${pageCount}`;
                    const pageTextWidth = doc.getTextWidth(pageText);
                    const centerX = (doc.internal.pageSize.getWidth() - pageTextWidth) / 2;
                    doc.text(pageText, centerX, footerY);
                    doc.text("Imprimé le : " + new Date().toLocaleDateString() + " à " + new Date().toLocaleTimeString(), 15, footerY); // Left-aligned
                }
            }

            drawConsultationSection(yPos);

            addFooter();

            doc.output('dataurlnewwindow');
        }

    });
</script>



@endsection