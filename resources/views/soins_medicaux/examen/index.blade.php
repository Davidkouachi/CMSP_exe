@extends('app')

@section('titre', 'Acceuil')

@section('info_page')
<div class="app-hero-header d-flex align-items-center">
    <!-- Breadcrumb starts -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-bar-chart-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{route('index_accueil')}}">Espace Santé</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            Accueil
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    <div class="row gx-3">
        <div class="col-xxl-12 col-sm-12">
            <div class="card mb-3 bg-3">
                <div class="card-body" style="background: rgba(0, 0, 0, 0.7);">
                    <div class="py-4 px-3 text-white">
                        <h6>Bienvenue,</h6>
                        <h2>{{Auth::user()->sexe.'. '.Auth::user()->name}}</h2>
                        <h5>Les statistiques d'aujourd'hui.</h5>
                        <div class="mt-4 d-flex gap-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-box lg bg-info rounded-5 me-3">
                                    <i class="ri-walk-line fs-1"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h2 class="m-0 lh-1" id="nbre_analyse" ></h2>
                                    <p class="m-0">Analyse</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="icon-box lg bg-info rounded-5 me-3">
                                    <i class="ri-walk-line fs-1"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h2 class="m-0 lh-1" id="nbre_imagerie" ></h2>
                                    <p class="m-0">Imagerie</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-3" >
        <div class="col-sm-12">
            <div class="card mb-3">
                <div class="card-body" style="margin-top: -30px;">
                    <div class="custom-tabs-container">
                        <ul class="nav nav-tabs justify-content-center bg-primary bg-2" id="customTab4" role="tablist" style="background: rgba(0, 0, 0, 0.7);">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active text-white" id="tab-twoAAAN" data-bs-toggle="tab" href="#twoAAAN" role="tab" aria-controls="twoAAAN" aria-selected="false" tabindex="-1">
                                    <i class="ri-dossier-line me-2"></i>
                                    Demande d'examen
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-white" id="tab-twoAAA" data-bs-toggle="tab" href="#twoAAA" role="tab" aria-controls="twoAAA" aria-selected="false" tabindex="-1">
                                    <i class="ri-hand-sanitizer-line me-2"></i>
                                    Nouvel Examen
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-white" id="tab-oneAAAD" data-bs-toggle="tab" href="#oneAAAD" role="tab" aria-controls="oneAAAD" aria-selected="false" tabindex="-1">
                                    <i class="ri-health-book-line me-2"></i>
                                    Liste des demandes d'examens
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-white" id="tab-oneAAA" data-bs-toggle="tab" href="#oneAAA" role="tab" aria-controls="oneAAA" aria-selected="false" tabindex="-1">
                                    <i class="ri-folder-open-line me-2"></i>
                                    Liste des Examens
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-white" id="tab-oneAAAP" data-bs-toggle="tab" href="#oneAAAP" role="tab" aria-controls="oneAAAP" aria-selected="false" tabindex="-1">
                                    <i class="ri-syringe-line me-"></i>
                                    Prélévement
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane active show fade" id="twoAAAN" role="tabpanel" aria-labelledby="tab-twoAAAN" style="padding-bottom: 120px;" >
                                <div class="card-header">
                                    <h5 class="card-title text-center">
                                        Nouvelle Demande d'examen
                                    </h5>
                                </div>
                                <div class="card-header">
                                    <div class="text-center">
                                        <a class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/user8.png')}}" class="img-7x rounded-circle border border-1">
                                        </a>
                                    </div>
                                </div>
                                <div class="row gx-3 justify-content-center align-items-center mb-5">
                                    <div class="col-xxl-4 col-lg-4 col-sm-6">
                                        <div class="mb-3 text-center">
                                            <label class="form-label">Patient</label>
                                            <select class="form-select select2" id="patient_id"></select>
                                        </div>
                                    </div>
                                </div>
                                <div id="select_examen_div" style="display: none;">
                                    <div class="row gx-3 justify-content-center align-items-center mb-4">
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Type d'examen</label>
                                                <select class="form-select" id="typeacte_id_exd"></select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Medecin</label>
                                                <input type="text" class="form-control" id="medecin" autocomplete="off" placeholder="saisie obligatoire" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6" id="div_numcode" style="display: none;">
                                            <div class="mb-3">
                                                <label class="form-label">N° prise en charge</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">N°</span>
                                                    <input type="text" class="form-control" id="numcode" autocomplete="off" placeholder="facultatif">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="div_Examen" class="mb-3 p-2" style="display: none;" >
                                        <div class="card-header">
                                            <h5 class="card-title text-center">
                                                Choix des Examens
                                            </h5>
                                        </div>
                                        <div class="row gx-3 justify-content-center align-items-center">
                                            <div class="col-12">
                                                <div class="row gx-3 justify-content-center align-items-center">
                                                    <div class="col-12 mb-3 text-center">
                                                        <button type="button" id="add_select_examen" class="btn btn-info">
                                                            <i class="ri-sticky-note-add-line"></i>
                                                            Ajouter un Examen
                                                        </button>
                                                    </div>
                                                    <div class="col-12" id="contenu_examen">

                                                    </div>
                                                    <div class="row gx-3" id="div_btn_examen" style="display: none;">
                                                        <div class="col-xxl-5 col-lg-6 col-sm-6">
                                                            <div class="input-group mb-3">
                                                                <span class="input-group-text w-25">
                                                                    Prélevement
                                                                </span>
                                                                <input readonly type="tel" class="form-control" id="montant_pre_examen" placeholder="Taux de Couverture">
                                                                <span class="input-group-text w-25">Fcfa</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-xxl-5 col-lg-6 col-sm-6">
                                                            <div class="input-group mb-3">
                                                                <span class="input-group-text w-25">
                                                                    Taux
                                                                </span>
                                                                <input readonly type="tel" class="form-control" id="patient_taux" placeholder="Taux de Couverture">
                                                                <span class="input-group-text w-25">%</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-xxl-5 col-lg-6 col-sm-6">
                                                            <div class="input-group mb-3">
                                                                <span class="input-group-text w-25">
                                                                    Assurance
                                                                </span>
                                                                <input readonly type="tel" class="form-control" id="montant_assurance_examen" placeholder="Part Assurance">
                                                                <span class="input-group-text w-25">Fcfa</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-xxl-5 col-lg-6 col-sm-6">
                                                            <div class="input-group mb-3">
                                                                <span class="input-group-text w-25">
                                                                    Patient
                                                                </span>
                                                                <input readonly type="tel" class="form-control" id="montant_patient_examen" placeholder="Part Patient">
                                                                <span class="input-group-text w-25">Fcfa</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-xxl-5 col-lg-6 col-sm-6">
                                                            <div class="input-group mb-3">
                                                                <span class="input-group-text w-25">
                                                                    Total
                                                                </span>
                                                                <input readonly type="tel" class="form-control" id="montant_total_examen" placeholder="Montant Total">
                                                                <span class="input-group-text w-25">Fcfa</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 mb-3 text-center">
                                                            <button type="button" id="btn_eng_exd" class="btn btn-success">
                                                                Enregistrer
                                                                <i class="ri-send-plane-fill"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="twoAAA" role="tabpanel" aria-labelledby="tab-twoAAA">
                                <div class="card-header">
                                    <h5 class="card-title text-center">
                                        Nouvel Examen
                                    </h5>
                                </div>
                                <div class="card-header">
                                    <div class="text-center">
                                        <a class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/examen.jpg')}}" class="img-7x rounded-circle border border-1">
                                        </a>
                                    </div>
                                </div>
                                <div class="row gx-3 justify-content-center align-items-center">
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Type d'examen
                                            </label>
                                            <select class="form-select" id="acte_id_ex">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Nom
                                            </label>
                                            <input type="text" class="form-control" id="nom_ex" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Cotation
                                            </label>
                                            <input type="text" class="form-control" id="cotation_ex" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()" maxlength="1">
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Valeur
                                            </label>
                                            <input type="text" class="form-control" id="valeur_ex" placeholder="Saisie Obligatoire">
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Prix</label>
                                            <div class="input-group">
                                                <input type="tel" class="form-control" id="prix_ex" placeholder="Saisie Obligatoire">
                                                <span class="input-group-text">Fcfa</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Total</label>
                                            <div class="input-group">
                                                <input readonly type="tel" class="form-control" id="montant_ex" placeholder="Montant Total">
                                                <span class="input-group-text">Fcfa</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="mb-3">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button id="btn_eng_ex" class="btn btn-success">
                                                    Enregistrer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade " id="oneAAAD" role="tabpanel" aria-labelledby="tab-oneAAAD">
                                <div class="row gx-3" >
                                    <div class="col-12">
                                        <div class=" mb-3">
                                            <div class="card-header d-flex align-items-center justify-content-between">
                                                <h5 class="card-title">
                                                    Liste des Examens Demandées
                                                </h5>
                                                <div class="d-flex" >
                                                    <input type="text" id="searchInputd" placeholder="Recherche" class="form-control me-1">
                                                </div>
                                            </div>
                                            <div class="card-header d-flex align-items-center justify-content-between">
                                                <div class="w-100">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Du</span>
                                                        <input type="date" id="searchDate1" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d', strtotime('-2 months')) }}" max="{{ date('Y-m-d') }}">
                                                        <span class="input-group-text">au</span>
                                                        <input type="date" id="searchDate2" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                                                        <a id="btn_search_table" class="btn btn-outline-success ms-auto">
                                                            <i class="ri-search-2-line"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="">
                                                    <div class="table-responsive">
                                                        <table id="Table_day" class="table table-hover table-sm Table_examend">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">N°</th>
                                                                    <th scope="col">Type d'examen</th>
                                                                    <th scope="col">Patient</th>
                                                                    <th scope="col">Médecin</th>
                                                                    <th scope="col">Nombre d'examen</th>
                                                                    <th scope="col">Prélevement</th>
                                                                    <th scope="col">Montant Total</th>
                                                                    <th scope="col">Date de création</th>
                                                                    <th scope="col"></th>
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
                            <div class="tab-pane fade " id="oneAAA" role="tabpanel" aria-labelledby="tab-oneAAA">
                                <div class="row gx-3" >
                                    <div class="col-12">
                                        <div class=" mb-3">
                                            <div class="card-header d-flex align-items-center justify-content-between">
                                                <h5 class="card-title">
                                                    Liste des Examens
                                                </h5>
                                                <div class="d-flex" >
                                                    <a id="btn_refresh_tableE" class="btn btn-outline-info ms-auto">
                                                        <i class="ri-loop-left-line"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="">
                                                    <div class="table-responsive">
                                                        <table id="Table_day" class="table table-hover table-sm Table_examen">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">N°</th>
                                                                    <th scope="col">Type</th>
                                                                    <th scope="col">Examen</th>
                                                                    <th scope="col">Cotation & Valeur</th>
                                                                    <th scope="col">Prix</th>
                                                                    <th scope="col">Montant Total</th>
                                                                    <th scope="col">Date de création</th>
                                                                    <th scope="col"></th>
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
                            <div class="tab-pane fade" id="oneAAAP" role="tabpanel" aria-labelledby="tab-oneAAAP">
                                <div class="card-header">
                                    <h5 class="card-title text-center">
                                        Prélévement
                                    </h5>
                                </div>
                                <div class="row gx-3 justify-content-center align-items-center">
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Prix</label>
                                            <div class="input-group">
                                                <input type="tel" class="form-control" id="prix_preleve" placeholder="Saisie Obligatoire">
                                                <span class="input-group-text">Fcfa</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="mb-3">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button id="btn_eng_pre" class="btn btn-success">
                                                    Enregistrer
                                                </button>
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

<div class="modal fade" id="MmodifE" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mise à jour</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateForm">
                    <input type="hidden" id="Id_exM"> <!-- Hidden field for the room's ID -->
                    <div class="row gx-3 justify-content-center align-items-center">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    Type d'examen
                                </label>
                                <select class="form-select" id="acte_id_exM">
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    Nom
                                </label>
                                <input type="text" class="form-control" id="nom_exM" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    Cotation
                                </label>
                                <input type="text" class="form-control" id="cotation_exM" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()" maxlength="1">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    Valeur
                                </label>
                                <input type="text" class="form-control" id="valeur_exM" placeholder="Saisie Obligatoire">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Prix</label>
                                <div class="input-group">
                                    <input type="tel" class="form-control" id="prix_exM" placeholder="Saisie Obligatoire">
                                    <span class="input-group-text">Fcfa</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Total</label>
                                <div class="input-group">
                                    <input readonly type="tel" class="form-control" id="montant_exM" placeholder="Montant Total">
                                    <span class="input-group-text">Fcfa</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Fermer
                </button>
                <button type="button" class="btn btn-primary" id="updateBtnE">
                    Mettre à jour
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Detail" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">
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
                                        <div class="table-responsive" id="div_TableP" style="display: none;">
                                            <table class="table table-bordered" id="TableP">
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
                                        <div id="message_TableP" style="display: none;">
                                            <p class="text-center" >
                                                Aucun Produit utilisé pour le moment
                                            </p>
                                        </div>
                                        <div id="div_Table_loaderP" style="display: none;">
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

<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>
<script src="{{asset('jsPDF-AutoTable/dist/jspdf.plugin.autotable.min.js')}}"></script>

@include('select2')

<script>
    $(document).ready(function() {

        Statistique();
        select_patient();
        select_acte();
        montant_prelevement();
        Statistique();

        $("#btn_eng_ex").on("click", eng_ex);
        $("#updateBtnE").on("click", eng_exM);
        $("#btn_eng_pre").on("click", eng_pre);
        $("#add_select_examen").on("click", add_select);
        $("#btn_eng_exd").on("click", eng_exd);

        $('#searchDate1').on('change', function() {
            const date1 = $(this).val();
            
            if (date1) {
                $('#searchDate2').val(date1);
                $('#searchDate2').attr('min', date1);
            }
        });

        $('#searchDate2').on('change', function() {
            const date2 = $(this).val();
            const date1 = $('#searchDate1').val();

            if (date2 && date1 && new Date(date2) < new Date(date1)) {
                alert('La date de sortie probable ne peut pas être antérieure à la date d\'entrée.');
                $(this).val(date1);
            }
        });

        function allowOnlyLetters(event) {
            const key = event.key;
            if (!/^[a-zA-Z]+$/.test(key)) {
                event.preventDefault();
            }
        }

        function allowOnlyNumbers(event) {
            const key = event.key;
            if (isNaN(key)) {
                event.preventDefault();
            }
        }

        function formatPriceInput(event) {
            this.value = formatPrice(this.value);
        }

        ['cotation_ex', 'cotation_exM'].forEach(id => {
            document.getElementById(id).addEventListener('keypress', allowOnlyLetters);
        });

        ['prix_ex', 'prix_exM','prix_preleve'].forEach(id => {
            document.getElementById(id).addEventListener('input', formatPriceInput);
            document.getElementById(id).addEventListener('keypress', allowOnlyNumbers);
        });

        ['valeur_ex', 'valeur_exM'].forEach(id => {
            document.getElementById(id).addEventListener('keypress', allowOnlyNumbers);
        });

        var inputs = ['prix_ex','valeur_ex'];
        inputs.forEach(function(id) {
            var inputElement = document.getElementById(id);

            inputElement.addEventListener('input', function() {
                calcul_montant_ex();
            });
        });

        var inputs = ['prix_exM','valeur_exM'];
        inputs.forEach(function(id) {
            var inputElement = document.getElementById(id);

            inputElement.addEventListener('input', function() {
                calcul_montant_exM();
            });
        });

        const table_examen = $('.Table_examen').DataTable({

            processing: true,
            serverSide: false,
            ajax: function(data, callback) {
                
                $.ajax({
                    url: `/api/list_examen_all`,
                    type: 'GET',
                    success: function(response) {
                        callback({ data: response.data });
                    },
                    error: function() {
                        console.log('Error fetching data. Please check your API or network list_hopital.');
                    }
                });
            },
            columns: [
                { 
                    data: null, 
                    render: (data, type, row, meta) => meta.row + 1,
                    searchable: false,
                    orderable: false,
                },
                { 
                    data: 'acte', 
                    render: (data, type, row) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{ asset('/assets/images/examen.jpg') }}" class="img-2x rounded-circle border border-1">
                        </a>
                        ${data}
                    </div>`,
                    searchable: true, 
                },
                { 
                    data: 'nom',
                    searchable: true,
                },
                { 
                    data: null, 
                    render: (data, type, row) => `${row.cotation} ${row.valeur}`,
                    searchable: true, 
                },
                { 
                    data: 'prix', 
                    render: (data) => `${data} Fcfa`,
                    searchable: true, 
                },
                { 
                    data: 'montant', 
                    render: (data) => `${data} Fcfa`,
                    searchable: true, 
                },
                { 
                    data: 'created_at', 
                    render: (data) => `${formatDateHeure(data)}`,
                    searchable: true, 
                },
                {
                    data: null,
                    render: (data, type, row) => `
                        <div class="d-inline-flex gap-1" style="font-size:10px;">
                            <a class="btn btn-outline-info btn-sm rounded-5" data-bs-toggle="modal" data-bs-target="#MmodifE" id="modif"
                                data-id = ${row.id}
                                data-nom = ${row.nom}
                                data-cotation = ${row.cotation}
                                data-valeur = ${row.valeur}
                                data-prix = ${row.prix}
                                data-montant = ${row.montant}
                                data-acte_id = ${row.acte_id}
                            >
                                <i class="ri-edit-line"></i>
                            </a>
                        </div>
                    `,
                    searchable: false,
                    orderable: false,
                }
            ],
            ...dataTableConfig,
            initComplete: function(settings, json) {
                initializeRowEventListeners();
            },
        });

        function initializeRowEventListeners() {

            $('.Table_examen').on('click', '#modif', function() {
                const id = $(this).data('id');
                const nom = $(this).data('nom');
                const cotation = $(this).data('cotation');
                const valeur = $(this).data('valeur');
                const prix = $(this).data('prix');
                const montant = $(this).data('montant');
                const acte_id = $(this).data('acte_id');

                document.getElementById('Id_exM').value = id;
                document.getElementById("nom_exM").value = nom;
                document.getElementById("cotation_exM").value = cotation;
                document.getElementById("valeur_exM").value = valeur;
                document.getElementById("prix_exM").value = prix;
                document.getElementById("montant_exM").value = montant;

                const modifActeSelect = document.getElementById("acte_id_exM");
                const typeeOptions = modifActeSelect.options;

                for (let i = 0; i < typeeOptions.length; i++) {
                    if (String(typeeOptions[i].value) === String(acte_id)) {
                        typeeOptions[i].selected = true; 
                        break;
                    }
                }
            });
        }

        $('#btn_refresh_tableE').on('click', function() {
            table_examen.ajax.reload(null, false);
        });

        const table_examend = $('.Table_examend').DataTable({

            processing: true,
            serverSide: false,
            ajax: function(data, callback) {
                const date1 = $('#searchDate1').val();
                const date2 = $('#searchDate2').val();
                
                $.ajax({
                    url: `/api/list_examend_all/${date1}/${date2}`,
                    type: 'GET',
                    success: function(response) {
                        callback({ data: response.data });
                    },
                    error: function() {
                        console.log('Error fetching data. Please check your API or network list_examend_all.');
                    }
                });
            },
            columns: [
                { 
                    data: null, 
                    render: (data, type, row, meta) => meta.row + 1,
                    searchable: false,
                    orderable: false,
                },
                { 
                    data: 'acte', 
                    render: (data, type, row) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{ asset('/assets/images/examen.jpg') }}" class="img-2x rounded-circle border border-1">
                        </a>
                        ${data}
                    </div>`,
                    searchable: true, 
                },
                { 
                    data: 'patient',
                    searchable: true,
                },
                { 
                    data: 'medecin', 
                    render: (data) => `Dr. ${data}`,
                    searchable: true, 
                },
                { 
                    data: 'nbre',
                    searchable: true,
                },
                { 
                    data: 'prelevement', 
                    render: (data) => `${data} Fcfa`,
                    searchable: true, 
                },
                { 
                    data: 'montant', 
                    render: (data) => `${data} Fcfa`,
                    searchable: true, 
                },
                { 
                    data: 'created_at', 
                    render: (data) => `${formatDateHeure(data)}`,
                    searchable: true, 
                },
                {
                    data: null,
                    render: (data, type, row) => `
                        <div class="d-inline-flex gap-1" style="font-size:10px;">
                            <a class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#Detail" id="detail"
                                data-id="${row.id}"
                            >
                                <i class="ri-archive-2-line"></i>
                            </a>
                            <a class="btn btn-outline-info btn-sm" id="fiche"
                                data-id="${row.id}"
                            >
                                <i class="ri-file-line"></i>
                            </a>
                        </div>
                    `,
                    searchable: false,
                    orderable: false,
                }
            ],
            ...dataTableConfig,
            initComplete: function(settings, json) {
                initializeRowEventListenersExamend();
            },
        });

        function initializeRowEventListenersExamend() {

            $('.Table_examend').on('click', '#detail', function() {
                const id = $(this).data('id');

                const tableBodyP = document.querySelector('#TableP tbody');
                const messageDivP = document.getElementById('message_TableP');
                const tableDivP = document.getElementById('div_TableP');
                const loaderDivP = document.getElementById('div_Table_loaderP');

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

            $('.Table_examend').on('click', '#fiche', function() {
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

                        generatePDFInvoice(examen, facture, patient, acte, examenpatient);

                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                    });
            });
        }

        $('#btn_search_table').on('click', function() {
            table_examend.ajax.reload(null, false);
        });

        function formatPrice(input) {
            // Remove all non-numeric characters except the comma
            input = input.replace(/[^\d,]/g, '');

            // Convert comma to dot for proper float conversion
            input = input.replace(',', '.');

            // Convert to float and round to the nearest whole number
            let number = Math.round(parseInt(input));
            if (isNaN(number)) {
                return '';
            }

            // Format the number with dot as thousands separator
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function formatPriceT(price) {

            // Convert to float and round to the nearest whole number
            let number = Math.round(parseInt(price));
            if (isNaN(number)) {
                return '';
            }

            // Format the number with dot as thousands separator
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function showAlert(title, message, type) {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
            });
        }

        function formatDate(dateString) {

            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
            const year = date.getFullYear();

            return `${day}/${month}/${year}`; // Format as dd/mm/yyyy
        }

        function formatDateHeure(dateString) {

            const date = new Date(dateString);
                
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();

            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const seconds = String(date.getSeconds()).padStart(2, '0');

            return `${day}/${month}/${year} à ${hours}:${minutes}:${seconds}`;
        }

        let cachedExamens = {};

        function select_acte() {

            const selectElement = document.getElementById('acte_id_ex');
            const selectElementM = document.getElementById('acte_id_exM');
            const selectElementexd = document.getElementById('typeacte_id_exd');

            selectElement.innerHTML = '';
            selectElementM.innerHTML = '';
            selectElementexd.innerHTML = '';

            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Selectionner';
            selectElement.appendChild(defaultOption);

            const defaultOption2 = document.createElement('option');
            defaultOption2.value = '';
            defaultOption2.textContent = 'Selectionner';
            selectElementexd.appendChild(defaultOption2);

            $.ajax({
                url: '/api/list_acte_ex',
                method: 'GET',
                success: function(response) {
                    data = response.acte;
                    data.forEach(acte => {
                        const option1 = document.createElement('option');
                        option1.value = acte.id; // Ensure 'id' is the correct key
                        option1.textContent = acte.nom; // Ensure 'nom' is the correct key
                        selectElement.appendChild(option1);

                        const option2 = document.createElement('option');
                        option2.value = acte.id;
                        option2.textContent = acte.nom;
                        selectElementM.appendChild(option2);

                        const option3 = document.createElement('option');
                        option3.value = acte.id;
                        option3.textContent = acte.nom;
                        selectElementexd.appendChild(option3);
                    });
                },
                error: function() {
                    // showAlert('danger', 'Impossible de generer le code automatiquement');
                }
            });

            selectElementexd.addEventListener('change', function() {
                const id = this.value;
                if (id) {
                    // Vérifier si les données sont déjà en cache
                    if (cachedExamens[id]) {
                        afficherExamens(id); // Utiliser les données du cache
                    } else {
                        const url = '/api/select_examen/' + id;
                        fetch(url)
                            .then(response => response.json())
                            .then(data => {
                                // Stocker les données en cache
                                cachedExamens[id] = data.examen;
                                afficherExamens(id);
                            })
                            .catch(error => {
                                console.error('Erreur lors du chargement des données:', error);
                            });
                    }
                } else {
                    const contenuDiv = document.getElementById('contenu_examen');
                    contenuDiv.innerHTML = '';
                    document.getElementById('div_Examen').style.display = "none";
                }
            });
        }

        function add_select() {
            const contenuDiv = document.getElementById('contenu_examen');
            const id = document.getElementById('typeacte_id_exd').value;

            if (id === '') {
                showAlert("ALERT", "Selectionner un Type d'examen.", "warning");
                return false;
            }

            // Vérifier si les données sont en cache
            if (cachedExamens[id]) {
                // Utiliser les données en cache
                addSelectExamen(contenuDiv, cachedExamens[id]);
            } else {
                showAlert("Erreur", "Les examens ne sont pas disponibles. Veuillez sélectionner un type valide.", "danger");
            }
        }

        function afficherExamens(id) {
            const contenuDiv = document.getElementById('contenu_examen');
            contenuDiv.innerHTML = '';
            document.getElementById('montant_total_examen').value = '';
            document.getElementById('montant_patient_examen').value = '';
            document.getElementById('montant_assurance_examen').value = '';

            // Utiliser les examens en cache
            addSelectExamen(contenuDiv, cachedExamens[id]);

            document.getElementById('div_Examen').style.display = "block";
        }

        function montant_prelevement() {
            $.ajax({
                url: '/api/montant_prelevement',
                method: 'GET',
                success: function(response) {
                    data = response.prelevement;
                    document.getElementById('prix_preleve').value = data.prix;
                    document.getElementById('montant_pre_examen').value = data.prix;
                },
                error: function() {
                    // showAlert('danger', 'Impossible de generer le code automatiquement');
                }
            });
        }

        function calcul_montant_ex() {

            const valeur = parseInt(document.getElementById('valeur_ex').value.replace(/[^0-9]/g, '')) || 0;
            const prix = parseInt(document.getElementById('prix_ex').value.replace(/[^0-9]/g, '')) || 0;

            const total = valeur * prix; 

            document.getElementById('montant_ex').value = formatPriceT(total);
        }

        function calcul_montant_exM() {

            const valeur = parseInt(document.getElementById('valeur_exM').value.replace(/[^0-9]/g, '')) || 0;
            const prix = parseInt(document.getElementById('prix_exM').value.replace(/[^0-9]/g, '')) || 0;

            const total = valeur * prix; 

            document.getElementById('montant_exM').value = formatPriceT(total);
        }

        function eng_ex()
        {
            const id = document.getElementById("acte_id_ex");
            const nom = document.getElementById("nom_ex");
            const cotation = document.getElementById("cotation_ex");
            const valeur = document.getElementById("valeur_ex");
            const prix = document.getElementById("prix_ex");
            const montant = document.getElementById("montant_ex");

            if(!id.value.trim() || !nom.value.trim() || !cotation.value.trim() || !valeur.value.trim() || !prix.value.trim() || !montant.value.trim()){
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.','warning');
                return false;
            }

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: '/api/examen_new',
                method: 'GET',  // Use 'POST' for data creation
                data: { 
                    id: id.value, 
                    nom: nom.value, 
                    prix: prix.value ,
                    cotation: cotation.value, 
                    valeur: valeur.value, 
                    montant: montant.value ,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.existe) {
                        showAlert('Alert', 'Cet Examen existe déjà.','warning');
                    }else if (response.success) {

                        id.value = '';
                        nom.value = '';
                        prix.value = '';
                        cotation.value = '';
                        valeur.value = '';
                        montant.value = '';

                        table_examen.ajax.reload(null, false);

                        showAlert('Succès', 'Opération éffectuée.','success');
                    } else if (response.error) {
                        showAlert('Erreur', 'Une erreur est survenue lors de l\'enregistrement.','error');
                    }

                },
                error: function() {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert('Erreur', 'Une erreur est survenue lors de l\'enregistrement.','error');
                }
            });
        }

        function eng_exM()
        {
            const id = document.getElementById("Id_exM").value;
            const acte_id = document.getElementById("acte_id_exM");
            const nom = document.getElementById("nom_exM");
            const cotation = document.getElementById("cotation_exM");
            const valeur = document.getElementById("valeur_exM");
            const prix = document.getElementById("prix_exM");
            const montant = document.getElementById("montant_exM");

            if(!acte_id.value.trim() || !nom.value.trim() || !cotation.value.trim() || !valeur.value.trim() || !prix.value.trim() || !montant.value.trim()){
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.','warning');
                return false;
            }

            var modal = bootstrap.Modal.getInstance(document.getElementById('MmodifE'));
            modal.hide();

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: '/api/examen_Modif/'+id,
                method: 'GET',
                data: { 
                    acte_id: acte_id.value, 
                    nom: nom.value, 
                    prix: prix.value ,
                    cotation: cotation.value, 
                    valeur: valeur.value, 
                    montant: montant.value,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.existe) {

                        showAlert('Alert', 'Cet Examen existe déjà.','warning');

                    }else if (response.success) {

                        table_examen.ajax.reload(null, false);

                        showAlert('Succès', 'Mise à jour éffectuée.','success');
                    } else if (response.error) {

                        showAlert('Erreur', 'Une erreur est survenue','error');
                    }

                },
                error: function() {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert('Erreur', 'Une erreur est survenue lors de l\'enregistrement.','error');
                }
            });
        }

        function eng_pre()
        {
            const prix = document.getElementById("prix_preleve");

            if(!prix.value.trim()){
                showAlert('Alert', 'Saisie le montant du prélevement SVP.','warning');
                return false;
            }

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: '/api/prelevement_Modif',
                method: 'GET',
                data: { 
                    prix: prix.value,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.success) {

                        montant_prelevement();

                        showAlert('Succès', 'Mise à jour éffectuée.','success');
                    } else if (response.error) {

                        showAlert('Erreur', 'Une erreur est survenue','error');
                    }

                },
                error: function() {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert('Erreur', 'Une erreur est survenue lors de l\'enregistrement.','error');
                }
            });
        }

        function select_patient()
        {
            const selectElement = $('#patient_id');
            selectElement.empty();

            // Ajouter l'option par défaut
            const defaultOption = $('<option>', {
                value: '',
                text: 'Selectionner',
                'data-taux': 0,
                'data-assurer': 'non',
            });
            selectElement.append(defaultOption);

            $.ajax({
                url: '/api/name_patient_examen',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    data.name.forEach(item => {
                        const option = $('<option>', {
                            value: item.id,
                            text: item.np,
                            'data-taux': item.taux || 0,
                            'data-assurer': item.assurer,
                        });
                        selectElement.append(option);
                    });
                },
                error: function() {
                    console.error('Erreur lors du chargement des patients');
                }
            });
        }

        $('#patient_id').on('change', function() {
            document.getElementById('select_examen_div').style.display = "block";
            rech_dosier(); 
        });

        function rech_dosier() {
            const selectElement = $('#patient_id');

            if (selectElement.val() !== '') {
                const selectedOption = selectElement.find('option:selected'); // Obtenir l'option sélectionnée
                
                // Récupérer le taux depuis l'attribut data-taux
                const taux = selectedOption.data('taux') || 0;

                // Mettre à jour le champ patient_taux
                $('#patient_taux').val(taux);

                // Réinitialiser d'autres champs
                $('#numcode').val('');
                if (selectedOption.val()) {
                    const assurer = selectedOption.data('assurer'); // Récupérer data-assurer
                    if (assurer === 'oui') {
                        $('#div_numcode').show();
                    } else {
                        $('#div_numcode').hide();
                    }
                }

                // Réinitialiser le contenu examen et les champs associés
                $('#contenu_examen').html('');
                $('#div_btn_examen').hide();
                $('#div_Examen').hide();
                $('#typeacte_id_exd').val('');

                // Appeler la fonction pour mettre à jour le montant total
                updateMontantTotalExamen();
            }
        }

        function addSelectExamen(contenuDiv, examens) {

            const index = contenuDiv.childElementCount + 1;

            const patientTaux = document.getElementById('patient_taux').value;

            const div = document.createElement('div');
            div.className = 'mt-3 mb-3 border border-1 p-3 rounded-2';

            // Créer le groupe de contrôle contenant le select et le bouton supprimer
            div.innerHTML = `
                <div class="card-header">
                    <h5 class="card-title text-center Title_Examen">
                        EXAMEN ${index}
                    </h5>
                </div>
                <div class="row gx-3 mb-3 text-center input_group">
                    <div class="col-xxl-3 col-lg-3 col-md-3 col-sm-4">
                        <div class="mb-3">
                            <label class="form-label">Prise en Charge</label>
                            <select class="form-select examen-select-assurer">
                                ${patientTaux == 0 ? `
                                    <option selected value="oui">Non</option>
                                ` : `
                                    <option selected value="oui">Oui</option>
                                    <option value="non">Non</option>
                                `}
                            </select>
                        </div>
                    </div>
                    <div class="col-xxl-9 col-lg-9 col-md-9 col-sm-8">
                        <div class="mb-3">
                            <label class="form-label">Examen</label>
                            <select class="form-select examen-select">
                                <option value="">Selectionner</option>
                                ${examens.map(item => 
                                    `<option value="${item.id}" 
                                             data-cotation="${item.cotation}" 
                                             data-valeur="${item.valeur}" 
                                             data-prix="${item.prix}" 
                                             data-montant="${item.montant}"
                                             data-assurer="oui"
                                             data-montantr="${item.montant.replace(/\./g, '')}">
                                        ${item.nom}
                                    </option>`).join('')}
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="mb-3">
                            <label class="form-label">Cotation</label>
                            <input readonly type="tel" class="form-control cotation-field" placeholder="Cotation">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="mb-3">
                            <label class="form-label">Prix</label>
                            <div class="input-group mb-3">
                                <input readonly type="tel" class="form-control prix-field" placeholder="Prix">
                                <span class="input-group-text w-25">Fcfa</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="mb-3">
                            <label class="form-label">Montant Total</label>
                            <div class="input-group mb-3">
                                <input readonly type="tel" class="form-control montant-field" placeholder="Montant">
                                <span class="input-group-text w-25">Fcfa</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center" >
                        <button class="btn btn-outline-danger suppr-btn">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            `;

            // Ajouter l'élément dans le parent (contenu div)
            contenuDiv.appendChild(div);

            checkContenuExamen();

            // Ajouter un event listener pour le bouton supprimer
            div.querySelector('.suppr-btn').addEventListener('click', () => {
                div.remove(); // Supprimer l'élément div parent
                checkContenuExamen(); // Re-vérifier le contenu
                updateMontantTotalExamen(); // Mettre à jour le montant total après la suppression
                updateExamenIndexes(contenuDiv);
            });

            // Event listener pour le select 'examen-select-assurer'
            const assurerSelect = div.querySelector('.examen-select-assurer');
            assurerSelect.addEventListener('change', function() {
                const selectedValue = assurerSelect.value; // Obtenez la valeur sélectionnée
                const examenSelect = div.querySelector('.examen-select');
                const selectedOption = examenSelect.options[examenSelect.selectedIndex];

                if (selectedOption) {
                    selectedOption.setAttribute('data-assurer', selectedValue);
                }

                updateMontantTotalExamen();
            });


            // Event listener pour le select
            const examenSelect = div.querySelector('.examen-select');
            examenSelect.addEventListener('change', function() {
                const selectedOption = examenSelect.options[examenSelect.selectedIndex];

                // Mettre à jour les champs en fonction de l'examen sélectionné
                div.querySelector('.cotation-field').value = selectedOption.getAttribute('data-cotation')+selectedOption.getAttribute('data-valeur');
                div.querySelector('.prix-field').value = selectedOption.getAttribute('data-prix');
                div.querySelector('.montant-field').value = selectedOption.getAttribute('data-montant');

                updateMontantTotalExamen();
            });
        }

        function updateExamenIndexes(contenuDiv) {
            const headers = contenuDiv.querySelectorAll('.Title_Examen');
            headers.forEach((header, index) => {
                header.textContent = `EXAMEN ${index + 1}`;
            });
        }

        function updateMontantTotalExamen() {
            let montantTotal = 0;        // Montant total à payer
            let montantPatient = 0;      // Montant à la charge du patient
            let montantAssurance = 0;    // Montant à la charge de l'assurance
            const selects = document.querySelectorAll('.examen-select');
            
            const patientTaux = parseInt(document.getElementById('patient_taux').value) || 0;
            const preleve = parseInt(document.getElementById('montant_pre_examen').value.replace(/\./g, '')) || 0;

            selects.forEach(select => {

                const selectedOption = select.options[select.selectedIndex];
                const montant = selectedOption.getAttribute('data-montantr');
                const assurerSelect = select.closest('.input_group').querySelector('.examen-select-assurer');
                const assurance = assurerSelect.value;

                if (montant) {
                    let montantExamen = parseInt(montant);

                    // Appliquer une logique en fonction de l'assurance
                    if (assurance === 'non') {
                        // Si l'assurance est "non", le montant est entièrement à la charge du patient
                        montantPatient += montantExamen;
                    } else {
                        // Si l'assurance est "oui", appliquer le taux pour le montant de l'assurance
                        let montantCouvert = (montantExamen * patientTaux) / 100; // Montant couvert par l'assurance
                        montantAssurance += montantCouvert; // Ajoute au montant de l'assurance
                        montantPatient += (montantExamen - montantCouvert); // Montant restant à la charge du patient
                    }

                    // Montant total est la somme des montants du patient et de l'assurance
                    montantTotal += montantExamen; 
                }
            });

            // Ajouter le prélèvement au montant total une seule fois après la boucle
            montantTotal += preleve;

            let pre_ass = 0;
            pre_ass = (preleve * patientTaux) / 100;

            montantAssurance += pre_ass;
            montantPatient += (preleve - pre_ass);

            // Formater les montants avec des points
            const formatMontant = (montant) => montant.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            // Mettre à jour les champs avec les montants formatés
            document.getElementById('montant_total_examen').value = formatMontant(montantTotal);
            document.getElementById('montant_patient_examen').value = formatMontant(montantPatient);
            document.getElementById('montant_assurance_examen').value = formatMontant(montantAssurance);
        }

        function checkContenuExamen() {
            const contenuDiv = document.getElementById('contenu_examen');
            const divBtn = document.getElementById('div_btn_examen');
            
            // Si la div #contenu a un contenu, on affiche le bouton, sinon on le cache
            if (contenuDiv.innerHTML.trim() !== "") {
                divBtn.style.display = "block"; // Afficher le bouton
            } else {
                divBtn.style.display = "none";  // Cacher le bouton
            }
        }

        // function add_select() {
        //     const contenuDiv = document.getElementById('contenu_examen');
        //     const id = document.getElementById('typeacte_id_exd').value;

        //     if (id == '') {
        //         showAlert("ALERT", "Selectionner un Type d'examen.", "warning");
        //         return false;
        //     }

        //     const url = '/api/select_examen/' + id;
        //         fetch(url)
        //             .then(response => response.json())
        //             .then(data => {

        //                 const examens = data.examen;
                                                
        //                 addSelectExamen(contenuDiv, examens); // Ajouter le premier select
        //             })
        //             .catch(error => {
        //                 console.error('Erreur lors du chargement des données:', error);
        //             });
        // }

        function CalculMontant() {

            const patient_id = $('#patient_id').val();
            const typeacte_id_exd = $('#typeacte_id_exd').val();

            // 1. Vérifier si le matricule du patient est renseigné
            if (patient_id === '') {
                showAlert("ALERT", "Veuillez sélectionner un Patient.", "warning");
                return false;
            }

            // 2. Vérifier si un type de soins a été sélectionné
            if (typeacte_id_exd === '') {
                showAlert("ALERT", "Veuillez sélectionner un Type d'examen'.", "warning");
                return false;
            }

            const contenuDiv = document.getElementById('contenu_examen');
            if (contenuDiv.innerHTML.trim() == "") {
                showAlert("ALERT", 'Aucun examen n\'a été sélectionné.', "warning");
                return false;
            }

            let formIsValid = true;
            const selectionsExamen = [];

            // 3. Vérifier si tous les soins infirmiers ont été sélectionnés
            const examenSelects = document.querySelectorAll('.examen-select');
            const selectedExamenIds = new Set();

            examenSelects.forEach(item => {
                const selectedOption = item.options[item.selectedIndex];
                const idExamen = selectedOption.value;
                const montant = parseInt(selectedOption.dataset.montantr);

                if (!idExamen || isNaN(montant)) {
                    showAlert("ALERT", 'Aucun Soins Infirmier n\'a été sélectionné.', "warning");
                    formIsValid = false;
                    return false;
                }

                if (selectedExamenIds.has(idExamen)) {
                    showAlert("ALERT", 'Vous avez sélectionné le même Soins Infirmier plusieurs fois.', "warning");
                    formIsValid = false;
                    return false;
                }

                selectedExamenIds.add(idExamen);
                selectionsExamen.push({
                    id: idExamen,
                    montant: montant
                });
            });

            if (!formIsValid) {
                resetLoaderAndButton();
                return false;
            }

            return true;
        }

        function eng_exd() {

            try {
                const calculResult = CalculMontant();
                if (!calculResult) {
                    return false;
                }
            } catch (error) {
                showAlert("ERREUR","Veuillez bien vérifier les données saisies", "error");
                return false;
            }
            
            const selectionsExamen = [];
            const examenSelects = document.querySelectorAll('.examen-select');
            examenSelects.forEach(item => {

                const selectedOption = item.options[item.selectedIndex];
                const idExamen = selectedOption.value;
                // const montant = parseInt(selectedOption.dataset.prix);
                const accepte = selectedOption.dataset.assurer;

                selectionsExamen.push({
                    id: idExamen,
                    // montant: montant
                    accepte: accepte,
                });
            });

            const auth_id = {{ Auth::user()->id }};
            const patient_id = $('#patient_id').val();
            const typeacte_id_exd = $('#typeacte_id_exd').val();
            const medecin = $('#medecin').val();

            if (patient_id == '') {
                showAlert("ALERT", 'Veuillez sélectionner un Patient.', "warning");
                return false;
            }

            if (typeacte_id_exd == '') {
                showAlert("ALERT", 'Veuillez sélectionner un Type d\'examen.', "warning");
                return false;
            }

            if (medecin == '') {
                showAlert("ALERT", 'Veuillez saisie le nom du médecin.', "warning");
                return false;
            }

            var montant_assurance = document.getElementById('montant_assurance_examen').value;
            var montant_patient = document.getElementById('montant_patient_examen').value;
            var montant_total = document.getElementById('montant_total_examen').value;
            var montant_pre = document.getElementById('montant_pre_examen').value;
            var numcode = document.getElementById('numcode').value;
            // Validate monetary fields
            if (!montant_assurance || 
                !montant_total || 
                !montant_patient ||
                !montant_pre) {
                
                showAlert("ALERT", 'Vérifier les montants SVP.', "warning");
                return false; 
            }

            var montantAssuranceValue = parseFloat(montant_assurance);
            var montantTotalValue = parseFloat(montant_total);
            var montantPatientValue = parseFloat(montant_patient);
            var montantPreValue = parseFloat(montant_pre);

            if (isNaN(montantAssuranceValue) || 
                isNaN(montantTotalValue) || 
                isNaN(montantPatientValue) ||
                isNaN(montantPreValue) || 
                montantAssuranceValue < 0 || 
                montantTotalValue < 0 || 
                montantPatientValue < 0 ||
                montantPreValue < 0) {
                
                showAlert("ALERT", 'Vérifier les montants SVP (les montants ne doivent pas être négatifs).', "warning");
                return false;
            }

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: '/api/new_examend',
                method: 'GET',
                data:{
                    selectionsExamen: selectionsExamen,
                    montantA: montant_assurance,
                    montantT: montant_total,
                    montantP: montant_patient,
                    montant_pre: montant_pre,
                    patient_id: patient_id,
                    acte_id: typeacte_id_exd,
                    medecin: medecin,
                    numcode: numcode || null,
                    auth_id: auth_id,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    
                    if (response.success) {

                        $('#patient_id').val('').trigger('change');

                        document.getElementById('typeacte_id_exd').value = "";
                        document.getElementById('medecin').value = "";
                        document.getElementById('numcode').value = "";
                        document.getElementById('select_examen_div').style.display = "none";
                        document.getElementById('div_Examen').style.display = "none";
                        document.getElementById('div_numcode').style.display = "none";

                        table_examend.ajax.reload(null, false);   

                        showAlert("ALERT", 'Enregistrement éffectué', "success");

                        var newTab = new bootstrap.Tab(document.getElementById('tab-oneAAAD'));
                        newTab.show();

                    } else if (response.error) {
                        showAlert("ERREUR", 'Une erreur est survenue', "error");
                    } else if (response.json) {
                        showAlert("ERREUR", 'Invalid selections format', "error");
                    } else if (response.existe) {
                        showAlert("Alert", 'Ce numéro de prise en charge existe déjà', "warning");
                    }

                },
                error: function() {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert("ERREUR", 'Une erreur est survenue lors de l\'enregistrement', "error");
                }
            });
        };

        function generatePDFInvoice(examen, facture, patient, acte, examenpatient) {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });

            const pdfFilename = "Examen Facture N°" + facture.code + " du " + formatDateHeure(facture.created_at);
            doc.setProperties({
                title: pdfFilename,
            });

            let yPos = 10;

            function drawConsultationSection(yPos) {
                rightMargin = 15;
                leftMargin = 15;
                pdfWidth = doc.internal.pageSize.getWidth();

                const titlea = "Facture";
                doc.setFontSize(100);
                doc.setTextColor(242, 242, 242); // Gray color for background effect
                doc.setFont("Helvetica", "bold");
                doc.text(titlea, 120, yPos + 120, { align: 'center', angle: 40 });

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
                doc.setTextColor(255, 0, 0);
                // doc.line(10, 35, 200, 35); 
                const titleR = "FACTURE EXAMEN";
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
                doc.setTextColor(255, 0, 0); // Couleur du texte rouge
                doc.text(titleR, titleRX, (yPos + 25)); // Positionner le texte
                const titleN = "N° "+facture.code;
                doc.text(titleN, (doc.internal.pageSize.getWidth() - doc.getTextWidth(titleN)) / 2, (yPos + 31));

                doc.setFontSize(10);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(0, 0, 0);
                const numDossier = "N° Dossier : P-"+ patient.matricule;
                const numDossierWidth = doc.getTextWidth(numDossier);
                doc.text(numDossier, pdfWidth - rightMargin - numDossierWidth, yPos + 28);

                yPoss = (yPos + 40);

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

                yPoss = (yPos + 40);

                const typeInfo = [];

                if (examen.num_bon && examen.num_bon !== "") {
                    typeInfo.push({ label: "N° prise en charge", value: examen.num_bon });
                }

                typeInfo.push(
                    { label: "Type d'examen", value: acte.nom },
                );

                typeInfo.forEach(info => {
                    doc.setFontSize(8);
                    doc.setFont("Helvetica", "bold");
                    doc.text(info.label, leftMargin + 100, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 135, yPoss);
                    yPoss += 7;
                });

                yPoss += 30;

                const donneeTables = examenpatient;
                let yPossT = yPoss + 10; // Initialisation de la position Y pour le tableau des soins

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
                });

                yPoss = doc.autoTable.previous.finalY || yPossT + 10;
                yPoss = yPoss + 5;

                const compteInfo = [
                    { label: "Montant Total", value: examen.montant+" Fcfa"},
                    ...(examen.part_assurance.replace(/[^0-9]/g, '') > 0 ? 
                            [{ label: "Part assurance", value: examen.part_assurance + " Fcfa" }] 
                            : []),
                    { label: "Prélevement", value: examen.prelevement+ " Fcfa" }
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

        function Statistique() {

            const nbre_analyse = document.getElementById("nbre_analyse");
            const nbre_imagerie = document.getElementById("nbre_imagerie");

            $.ajax({
                url: '/api/statistique_examen',
                method: 'GET',
                success: function(response) {
                    // Set the text content of each element
                    nbre_analyse.textContent = response.nbre_analyse_day;
                    nbre_imagerie.textContent = response.nbre_imagerie_day;
                },
                error: function() {
                    // Set default values in case of an error
                    nbre_analyse.textContent = '0';
                    nbre_imagerie.textContent = '0';
                }
            });
        }

    });
</script>


@endsection
