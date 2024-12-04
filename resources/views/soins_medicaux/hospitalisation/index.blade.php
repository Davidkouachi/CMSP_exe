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
                                    <h2 class="m-0 lh-1" id="nbre_hos"></h2>
                                    <p class="m-0">Hospitalisation</p>
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
                <div class="card-header" hidden >
                    <h5 class="card-title">Hospitalisation</h5>
                </div>
                <div class="card-body" style="margin-top: -30px;">
                    <div class="custom-tabs-container">
                        <ul class="nav nav-tabs justify-content-center bg-primary bg-2" id="customTab4" role="tablist" style="background: rgba(0, 0, 0, 0.7);">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active text-white" id="tab-twoAAA" data-bs-toggle="tab" href="#twoAAA" role="tab" aria-controls="twoAAA" aria-selected="false" tabindex="-1">
                                    <i class="ri-hotel-bed-line me-2"></i>
                                    Nouvelle admission
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-white" id="tab-oneAAA" data-bs-toggle="tab" href="#oneAAA" role="tab" aria-controls="oneAAA" aria-selected="false" tabindex="-1">
                                    <i class="ri-dossier-line me-2"></i>
                                    Liste
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link  text-white" id="tab-threeAAA" data-bs-toggle="tab" href="#threeAAA" role="tab" aria-controls="threeAAA" aria-selected="true">
                                    <i class="ri-list-settings-fill me-2"></i>
                                    Disponibilité Chambre & Lit
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane active show fade" id="twoAAA" role="tabpanel" aria-labelledby="tab-twoAAA">
                                <div class="card-header">
                                    <h5 class="card-title text-center">Nouvelle admission</h5>
                                </div>
                                <div class="card-header">
                                    <div class="text-center">
                                        <a class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/hospitalisation.jpg')}}" class="img-7x rounded-circle border border-1">
                                        </a>
                                    </div>
                                </div>
                                <div class="row gx-3 justify-content-center align-items-center mb-4">
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Patient</label>
                                            <select class="form-select select2" id="patient_id"></select>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6" id="div_numcode" style="display: none;">
                                        <div class="mb-3">
                                            <label class="form-label">N° prise en charge</label>
                                            <div class="input-group">
                                                <span class="input-group-text">N°</span>
                                                <input type="text" class="form-control" id="numcode">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Medecin</label>
                                            <select class="form-select select2" id="medecin_id"></select>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Type admission</label>
                                            <select class="form-select select2" id="id_typeadmission"></select>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Nature Admission</label>
                                            <select class="form-select select2" id="id_natureadmission">
                                                <option value="">Selectioner</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Chambre à occuper</label>
                                            <select class="form-select select2" id="id_chambre"></select>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Lit à occuper</label>
                                            <select class="form-select select2" id="id_lit">
                                                <option value="">Selectioner</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Date d'entrée
                                            </label>
                                            <input type="date" class="form-control" id="date_entrer" max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Date de sortie probable
                                            </label>
                                            <input type="date" class="form-control" id="date_sortie" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Nombre de jours
                                            </label>
                                            <input readonly type="tel" class="form-control" id="nbre_jour" value="1">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <button id="btn_calcul" class="btn btn-warning">
                                                Calculer le montant final
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row gx-3" id="div_calcul" style="display: none;">
                                        <div class="card-header text-center">
                                            <h5 class="card-title">Information Caisse</h5>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Taux</label>
                                                <div class="input-group">
                                                    <input readonly type="tel" class="form-control" id="patient_taux">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Prix de la chambre</label>
                                                <div class="input-group">
                                                    <input readonly type="tel" class="form-control" id="montant_chambre">
                                                    <span class="input-group-text">Fcfa</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Part Assurance</label>
                                                <div class="input-group">
                                                    <input readonly type="tel" class="form-control" id="montant_assurance">
                                                    <input type="hidden" class="form-control" id="montant_assurance_hidden">
                                                    <span class="input-group-text">Fcfa</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Part Patient</label>
                                                <div class="input-group">
                                                    <input readonly type="tel" class="form-control" id="montant_patient">
                                                    <input type="hidden" class="form-control" id="montant_patient_hidden">
                                                    <span class="input-group-text">Fcfa</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Montant Total</label>
                                                <div class="input-group">
                                                    <input readonly type="tel" class="form-control" id="montant_total">
                                                    <span class="input-group-text">Fcfa</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Remise</label>
                                                <div class="input-group">
                                                    <input type="tel" class="form-control" id="taux_remise">
                                                    <span class="input-group-text">Fcfa</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Application de la remise</label>
                                                <select class="form-select" id="appliq_remise">
                                                    <option selected value="patient">Patient</option>
                                                    <option value="assurance">Assurance</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button id="btn_eng_hosp" class="btn btn-success">
                                                    Enregistrer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="div_loader" style="display: none;">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                                            <strong>Calcul en cours...</strong>
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
                                                    List des hospitalisation
                                                </h5>
                                            </div>
                                            <div class="card-header d-flex align-items-center justify-content-between">
                                                <div class="w-100">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Du</span>
                                                        <input type="date" id="searchDate1" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d', strtotime('-2 months')) }}" max="{{ date('Y-m-d') }}">
                                                        <span class="input-group-text">au</span>
                                                        <input type="date" id="searchDate2" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                                                        <span class="input-group-text">Statut</span>
                                                        <select class="form-select me-1" id="statut">
                                                            <option selected value="tous">Tous</option>
                                                            <option value="Hospitaliser">Hospitaliser</option>
                                                            <option value="Liberé">Liberé</option>
                                                        </select>
                                                        <a id="btn_search_table" class="btn btn-outline-success ms-auto">
                                                            <i class="ri-search-2-line"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="">
                                                    <div class="table-responsive">
                                                        <table id="Table_day" class="table table-hover table-sm Table_hos">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">N°</th>
                                                                    <th scope="col">Type</th>
                                                                    {{-- <th scope="col">Nature</th> --}}
                                                                    <th scope="col">Patient</th>
                                                                    {{-- <th scope="col">Du</th> --}}
                                                                    {{-- <th scope="col">Au</th> --}}
                                                                    <th scope="col">Médecin</th>
                                                                    <th scope="col">Statut</th>
                                                                    <th scope="col">Montant Chambre</th>
                                                                    <th scope="col">Montant Soins</th>
                                                                    <th scope="col">Montant Total</th>
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
                            <div class="tab-pane fade" id="threeAAA" role="tabpanel" aria-labelledby="tab-threeAAA">
                                <div class="card-header">
                                    <h5 class="card-title text-center">Disponibilité des Lits</h5>
                                </div>
                                <div class="row gx-3" >
                                    <div class="col-12">
                                        <div class=" mb-3">
                                            <div class="card-header d-flex align-items-center justify-content-between">
                                                <a id="btn_refresh_table" class="btn btn-outline-info ms-auto">
                                                    <i class="ri-loop-left-line"></i>
                                                </a>
                                            </div>
                                            <div class="card-body">
                                                <div class="">
                                                    <div class="table-responsive">
                                                        <table id="Table_day" class="table table-hover table-sm Table_lit">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">N°</th>
                                                                    <th scope="col">N° Lit</th>
                                                                    <th scope="col">Statut</th>
                                                                    <th scope="col">N° Chambre</th>
                                                                    <th scope="col">Catégorie</th>
                                                                    <th scope="col">Prix</th>
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
    </div>
</div>

<div class="modal fade" id="Detail" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">
                    Détails
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal_detail" style="display: none;"></div>
            <div id="message_detail" style="display: none;">
                <p class="text-center" >
                    Aucune données n'a été trouvé
                </p>
            </div>
            <div id="div_detail_loader" class="mt-3 mb-3" style="display: none;">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                    <strong>Chargement des données...</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Add" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >
                    Produits Pharmacie
                </h5>
                <button type="button" id="close_modal_produit" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal_add">
                <div class="row gx-3 justify-content-center align-items-center">
                    <div class="col-12 mb-3">
                        <button type="button" id="add_select" class="btn btn-info">
                            <i class="ri-sticky-note-add-line"></i>
                            Ajouter un Produit
                        </button>
                    </div>
                    <div class="col-12" id="contenu">

                    </div>
                    <div class="col-12" id="div_btn_pro">
                        <div class="input-group mb-3">
                            <input readonly type="tel" class="form-control" id="montant_total_produit" placeholder="Montant Total">
                            <span class="input-group-text">Fcfa</span>
                        </div>
                        <input type="hidden" id="id_hos_produit">
                        <button type="button" id="btn_eng_produit" class="btn btn-outline-success">
                            Enregistrer
                            <i class="ri-send-plane-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Detail_produit" tabindex="-1" role="dialog">
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
                                        <div class="table-responsive" id="div_TableP" style="display: none;">
                                            <table class="table table-bordered" id="TableP">
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

<div class="modal fade" id="Mmodif" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mise à jour</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="updateChambreForm">
                    <input type="hidden" id="IdModif">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Du</label>
                            <input readonly type="date" class="form-control" id="date1M" min="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Au</label>
                            <input type="date" class="form-control" id="date2M" min="{{ date('Y-m-d') }}">
                        </div>
                    </div>                  
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="updateBtn">Mettre à jour</button>
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
        select_medecin();
        select_chambre();
        select_typeadmission();
        select_produit();

        $("#id_chambre").on("change", select_lit);
        $("#id_typeadmission").on("change", select_natureadmission);
        $('#btn_calcul').on('click', calculAmounts);
        $('#btn_eng_hosp').on('click', eng_hosp);
        $("#updateBtn").on("click", updatee);

        $('#patient_id').on('change', function() {
            $('#div_calcul').hide();
            rech_dosier($(this).val()); 
        });

        $('#date_entrer').on('change', function() {
            const date1 = $(this).val(); // Récupérer la valeur de date_entrer
            
            if (date1) {
                // Mettre à jour la valeur et le min de date_sortie
                $('#date_sortie').val(date1);
                $('#date_sortie').attr('min', date1);
            }

            calculerJours();
        });

        $('#date_sortie').on('change', function() {
            const date2 = $(this).val(); // Récupérer la valeur de date_sortie
            const date1 = $('#date_entrer').val(); // Récupérer la valeur de date_entrer

            if (date2 && date1 && new Date(date2) < new Date(date1)) {
                alert('La date de sortie probable ne peut pas être antérieure à la date d\'entrée.');
                $(this).val(date1); // Réinitialiser la date_sortie à date_entrer
            }

            calculerJours();
        });

        $('#searchDate1').on('change', function() {
            const date1 = $(this).val(); // Récupérer la valeur de date_entrer
            
            if (date1) {
                // Mettre à jour la valeur et le min de date_sortie
                $('#searchDate2').val(date1);
                $('#searchDate2').attr('min', date1);
            }

        });

        $('#searchDate2').on('change', function() {
            const date2 = $(this).val(); // Récupérer la valeur de date_sortie
            const date1 = $('#searchDate1').val(); // Récupérer la valeur de date_entrer

            if (date2 && date1 && new Date(date2) < new Date(date1)) {
                alert('La date de sortie probable ne peut pas être antérieure à la date d\'entrée.');
                $(this).val(date1); // Réinitialiser la date_sortie à date_entrer
            }

        });

        const table_lit = $('.Table_lit').DataTable({

            processing: true,
            serverSide: false,
            ajax: function(data, callback) {
                
                $.ajax({
                    url: `/api/list_lit`,
                    type: 'GET',
                    success: function(response) {
                        callback({ data: response.data });
                    },
                    error: function() {
                        console.log('Error fetching data. Please check your API or network lit_hopital.');
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
                    data: 'code', 
                    render: (data, type, row) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{ asset('/assets/images/lit.avif') }}"  class="img-2x rounded-circle border border-1">
                        </a>
                        ${data}
                    </div>`,
                    searchable: true, 
                },
                { 
                    data: 'statut',
                    render: function(data) {
                        return data === 'indisponible' 
                            ? `<span class="badge bg-danger">${data}</span>` 
                            : `<span class="badge bg-success">${data}</span>`;
                    },
                    searchable: true
                },
                { 
                    data: 'code_ch', 
                    render: (data) => `CH-${data}`,
                    searchable: true, 
                },
                { 
                    data: 'type',
                    searchable: true, 
                },
                { 
                    data: 'prix', 
                    render: (data) => `${data} Fcfa`,
                    searchable: true, 
                },
            ],
            ...dataTableConfig,
        });

        $('#btn_refresh_table').on('click', function() {
            table_lit.ajax.reload(null, false);
        });

        const table_hos = $('.Table_hos').DataTable({

            processing: true,
            serverSide: false,
            ajax: function(data, callback) {
                const date1 = $('#searchDate1').val();
                const date2 = $('#searchDate2').val();
                const statut = $('#statut').val();
                
                $.ajax({
                    url: `/api/list_hopital/${date1}/${date2}/${statut}`,
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
                    data: 'type', 
                    render: (data, type, row) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{ asset('/assets/images/hospitalisation.jpg') }}" class="img-2x rounded-circle border border-1">
                        </a>
                        ${data}
                    </div>`,
                    searchable: true, 
                },
                // { 
                //     data: 'nature',
                //     searchable: true, 
                // },
                { 
                    data: 'patient',
                    searchable: true,
                },
                // { 
                //     data: 'date_debut', 
                //     render: (data) => `${formatDate(data)}`,
                //     searchable: true, 
                // },
                // { 
                //     data: 'date_fin', 
                //     render: (data) => `${formatDate(data)}`,
                //     searchable: true, 
                // },
                { 
                    data: 'medecin',
                    render: (data) => {
                        if (data) {
                            // Diviser les mots et récupérer les deux premiers
                            const words = data.split(' ');
                            return `Dr. ${words.slice(0, 1).join(' ')}`;
                        }
                        return ''; // Si `data` est vide ou invalide
                    },
                    searchable: true, 
                },
                { 
                    data: 'statut',
                    render: function(data) {
                        return data === 'Hospitaliser' 
                            ? `<span class="badge bg-danger">en cours</span>` 
                            : `<span class="badge bg-success">libéré</span>`;
                    },
                    searchable: true
                },
                { 
                    data: 'montant_chambre', 
                    render: (data) => `${data} Fcfa`,
                    searchable: true, 
                },
                { 
                    data: 'montant_soins', 
                    render: (data) => `${data} Fcfa`,
                    searchable: true, 
                },
                { 
                    data: 'montant', 
                    render: (data) => `${data} Fcfa`,
                    searchable: true, 
                },
                {
                    data: null,
                    render: (data, type, row) => `
                        <div class="d-inline-flex gap-1" style="font-size:10px;">
                            ${row.statut === 'Hospitaliser' 
                            ? ` <a class="btn btn-outline-success btn-sm" id="add" data-bs-toggle="modal" data-bs-target="#Add"
                                    data-id="${row.id}"
                                >
                                    <i class="ri-dossier-line"></i>
                                </a>
                                <a class="btn btn-outline-info btn-sm" id="modif" data-bs-toggle="modal" data-bs-target="#Mmodif"
                                    data-id="${row.id}"
                                    data-date_debut="${row.date_debut}"
                                    data-date_fin="${row.date_fin}"
                                >
                                    <i class="ri-edit-line"></i>
                                </a>` 
                            : ''}
                            <a class="btn btn-outline-danger btn-sm" id="detail_produit" data-bs-toggle="modal" data-bs-target="#Detail_produit"
                                data-id="${row.id}"
                                data-montant_soins="${row.montant_soins}"
                            >
                                <i class="ri-archive-2-fill"></i>
                            </a>
                            <a class="btn btn-outline-warning btn-sm" id="detail" data-bs-toggle="modal" data-bs-target="#Detail"
                                data-id="${row.id}"
                            >
                                <i class="ri-eye-line"></i>
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
                initializeRowEventListeners();
            },
        });

        function initializeRowEventListeners() {

            $('.Table_hos').on('click', '#add', function() {
                const id = $(this).data('id');
                
                fetch(`/api/list_produit_all`) // API endpoint pour récupérer la liste des produits
                    .then(response => response.json())
                    .then(data => {
                        
                        document.getElementById('id_hos_produit').value = id;
                        document.getElementById('montant_total_produit').value = "";

                        const produits = data.produit;

                        const contenuDiv = document.getElementById('contenu');
                        contenuDiv.innerHTML = '';
                        
                        addSelect(contenuDiv, produits);
                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                    });
            });

            $('.Table_hos').on('click', '#modif', function() {
                const id = $(this).data('id');
                const date_debut = $(this).data('date_debut');
                const date_fin = $(this).data('date_fin');

                document.getElementById('IdModif').value = `${id}`;
                document.getElementById('date1M').value = `${date_debut}`;
                document.getElementById('date2M').value = `${date_fin}`;
            });

            $('.Table_hos').on('click', '#detail_produit', function() {
                const id = $(this).data('id');
                const montant_soins = $(this).data('montant_soins');

                const tableBodyP = document.querySelector('#TableP tbody');
                const messageDivP = document.getElementById('message_TableP');
                const tableDivP = document.getElementById('div_TableP');
                const loaderDivP = document.getElementById('div_Table_loaderP');

                messageDivP.style.display = 'none';
                tableDivP.style.display = 'none';
                loaderDivP.style.display = 'block';

                fetch(`/api/list_facture_hos_d/${id}`) // API endpoint
                    .then(response => response.json())
                    .then(data => {

                        const factureds = data.factured;

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

            $('.Table_hos').on('click', '#detail', function() {
                const id = $(this).data('id');

                const modal = document.getElementById('modal_detail');
                const message = document.getElementById('message_detail');
                const loader = document.getElementById('div_detail_loader');

                modal.style.display = 'none';
                message.style.display = 'none';
                loader.style.display = 'block';

                fetch(`/api/detail_hos/${id}`) // API endpoint
                    .then(response => response.json())
                    .then(data => {
                        // Access the 'chambre' array from the API response
                        loader.style.display = 'none';
                        message.style.display = 'none';
                        modal.style.display = 'block';

                        modal.innerHTML = '';

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
                                                    ${hopital.num_bon != null ? `
                                                        <h6 class="fw-semibold">Numéro de prise en charge :</h6>
                                                        <p>${hopital.num_bon}</p>
                                                    ` : ''}
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

                        modal.appendChild(div);

                    })
                    .catch(error => {

                        loader.style.display = 'none';
                        message.style.display = 'block';
                        modal.style.display = 'none';

                        console.error('Erreur lors du chargement des données:', error);
                    });  
            });

            $('.Table_hos').on('click', '#fiche', function() {
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

                        generatePDFInvoice(hopital, facture, patient, nature, type, lit, chambre, user, produit);

                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                    });
            });
        }

        $('#btn_search_table').on('click', function() {
            table_hos.ajax.reload(null, false);
        });

        function select_patient()
        {
            const selectElement = $('#patient_id');
            selectElement.empty();

            // Ajouter l'option par défaut
            const defaultOption = $('<option>', {
                value: '',
                text: 'Selectionner'
            });
            selectElement.append(defaultOption);

            $.ajax({
                url: '/api/name_patient_reception',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    data.name.forEach(item => {
                        const option = $('<option>', {
                            value: item.id,
                            text: item.np
                        });
                        selectElement.append(option);
                    });
                },
                error: function() {
                    console.error('Erreur lors du chargement des patients');
                }
            });
        }

        function rech_dosier(id) {
            $.ajax({
                url: '/api/rech_patient',
                method: 'GET',
                data: { id: id },
                success: function(response) {

                    if (response.existep) {
                        showAlert('Alert', 'Ce patient n\'existe pas.', 'error');
                    } else if (response.success) {

                        const item = response.patient;

                        const $patient_taux = $('#patient_taux');
                        const $appliq_remise = $('#appliq_remise');
                        const $btn_eng_hosp = $('#btn_eng_hosp');
                        const $div_numcode = $('#div_numcode');
                        const $numcode = $('#numcode');

                        const url = '/api/rech_hos_patient/' + item.id;

                        $.ajax({
                            url: url,
                            method: 'GET',
                            success: function(data) {

                                if (data.hos >= 1) {
                                    showAlert('Alert', 'Ce patient est déjà hospitalisé.', 'info');
                                    select_patient();
                                    return false;
                                }

                                $patient_taux.val(item.taux ? item.taux : 0);

                                $numcode.val('');
                                if (item.assurer === 'oui') {
                                    $div_numcode.show(); // Afficher la section pour numéro d'assurance
                                } else {
                                    $div_numcode.hide(); // Masquer la section pour numéro d'assurance
                                }

                                // Gestion des options de remise
                                if ($patient_taux.val() == 0) {
                                    $appliq_remise.find('option[value="assurance"]').hide();
                                } else {
                                    $appliq_remise.find('option[value="assurance"]').show();
                                }
                            },
                            error: function() {
                                console.error('Erreur lors de la recherche d\'hospitalisation.');
                            }
                        });
                    }
                },
                error: function() {
                    showAlert('Alert', 'Une erreur est survenue lors de la recherche.', 'error');
                }
            });
        }

        function showAlert(title, message, type) {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
            });
        }

        function select_medecin() {
            const $selectElement = $('#medecin_id');
            $selectElement.empty(); // Clear existing options

            const defaultOption = $('<option>', { value: '', text: 'Selectionner' });
            $selectElement.append(defaultOption);

            $.ajax({
                url: '/api/select_medecin',
                method: 'GET',
                success: function(data) {
                    data.forEach(item => {
                        const option = $('<option>', {
                            value: item.id,
                            text: `Dr. ${item.name}`
                        });
                        $selectElement.append(option);
                    });
                },
                error: function() {
                    console.error('Erreur lors du chargement des médecins');
                }
            });
        }

        function select_typeadmission() {
            const $selectElement = $('#id_typeadmission');
            $selectElement.empty();

            const defaultOption = $('<option>', { value: '', text: 'Selectionner' });
            $selectElement.append(defaultOption);

            $.ajax({
                url: '/api/select_typeadmission',
                method: 'GET',
                success: function(data) {
                    data.typeadmission.forEach(item => {
                        const option = $('<option>', {
                            value: item.id,
                            text: item.nom
                        });
                        $selectElement.append(option);
                    });
                },
                error: function() {
                    console.error('Erreur lors du chargement des types d\'admission');
                }
            });
        }

        function select_chambre() {
            const $selectElement = $('#id_chambre');
            $selectElement.empty();

            const defaultOption = $('<option>', { value: '', text: 'Selectionner' });
            $selectElement.append(defaultOption);

            $.ajax({
                url: '/api/select_chambre',
                method: 'GET',
                success: function(data) {
                    data.chambre.forEach(item => {
                        const option = $('<option>', {
                            value: item.id,
                            text: `CH-${item.code}`
                        });
                        $selectElement.append(option);
                    });
                },
                error: function() {
                    console.error('Erreur lors du chargement des chambres');
                }
            });
        }

        function select_lit() {
            const $selectElement = $('#id_lit');
            $selectElement.empty();

            const defaultOption = $('<option>', { value: '', text: 'Selectionner' });
            $selectElement.append(defaultOption);

            const chambreId = $('#id_chambre').val();
            if (chambreId) {
                $.ajax({
                    url: '/api/lit_select/' + chambreId,
                    method: 'GET',
                    success: function(response) {
                        const data = response.lit;
                        if (data && data.length > 0) {
                            data.forEach(item => {
                                const option = $('<option>', {
                                    value: item.id,
                                    text: `Lit-${item.code}/${item.type}`,
                                    'data-prix': item.prix
                                });
                                $selectElement.append(option);
                            });

                            // Mettre à jour le montant de la chambre lors de la sélection
                            $selectElement.on('change', function() {
                                const selectedOption = $(this).find('option:selected');
                                const prix = selectedOption.data('prix');
                                $('#montant_chambre').val(prix || 0);
                            });
                        }
                    },
                    error: function() {
                        console.error('Erreur lors du chargement des lits');
                    }
                });
            }
        }

        function select_natureadmission() {
            const $selectElement = $('#id_natureadmission');
            $selectElement.empty();

            const defaultOption = $('<option>', { value: '', text: 'Selectionner' });
            $selectElement.append(defaultOption);

            const typeAdmissionId = $('#id_typeadmission').val();
            if (typeAdmissionId) {
                $.ajax({
                    url: '/api/natureadmission_select/' + typeAdmissionId,
                    method: 'GET',
                    success: function(response) {
                        const data = response.natureadmission;
                        if (data && data.length > 0) {
                            data.forEach(item => {
                                const option = $('<option>', {
                                    value: item.id,
                                    text: item.nom
                                });
                                $selectElement.append(option);
                            });
                        }
                    },
                    error: function() {
                        console.error('Erreur lors du chargement des natures d\'admission');
                    }
                });
            }
        }

        function calculAmounts() {
            // Show loader and hide other elements
            document.getElementById('div_loader').style.display = 'block';
            document.getElementById('div_calcul').style.display = 'none';
            document.getElementById('btn_calcul').style.display = 'none';

            document.getElementById('montant_assurance').value = formatPrice(document.getElementById('montant_assurance_hidden').value);
            document.getElementById('montant_patient').value = formatPrice(document.getElementById('montant_patient_hidden').value);
            document.getElementById('taux_remise').value = 0;

            // Get input elements
            const montant_assurance = document.getElementById('montant_assurance');
            const taux_remise = document.getElementById('taux_remise');
            const montant_total = document.getElementById('montant_total');
            const montant_patient = document.getElementById('montant_patient');

            const montant_patient_hidden = document.getElementById('montant_patient_hidden');
            const montant_assurance_hidden = document.getElementById('montant_assurance_hidden');

            // Initialize amounts
            let montantAssurance = 0;
            let remise = 0;
            let totalMontant = 0;
            let montantPatient = 0;

            const patient_taux = document.getElementById('patient_taux').value;
            const nbre_jour = document.getElementById('nbre_jour').value;
            const montant_chambre = document.getElementById('montant_chambre').value;

            // Validate inputs
            if (patient_taux === '' || nbre_jour === '' || montant_chambre === '') {

                showAlert('Alert', 'Veuillez remplir tous les champs SVP.','warning');

                document.getElementById('div_loader').style.display = 'none';
                document.getElementById('div_calcul').style.display = 'none';
                document.getElementById('btn_calcul').style.display = 'block';

                return false;
            }

            if (!nbre_jour || isNaN(nbre_jour) || nbre_jour < 0) {

                showAlert('Alert', 'Veuillez vérifier les dates saisies.','warning');

                document.getElementById('div_loader').style.display = 'none';
                document.getElementById('div_calcul').style.display = 'none';
                document.getElementById('btn_calcul').style.display = 'block';

                return false;
            }

            // Convert and validate values
            let prixFloat = parseInt(montant_chambre.replace(/[.,]/g, ''));
            let joursInt = parseInt(nbre_jour);

            if (isNaN(prixFloat) || isNaN(joursInt)) {
                console.error('Invalid price or number of days');
                montant_total.value = '';  // Clear the field if values are invalid
                return false;
            }

            // Calculate total price
            let prixTotal = prixFloat * joursInt;

            // Apply discount if available
            remise = parseInt(taux_remise.value) || 0;  // Get discount rate or default to 0
            if (remise > 0 && remise <= 100) {
                prixTotal -= (prixTotal * remise / 100);  // Apply discount
            }

            // Set total price
            montant_total.value = formatPrice(prixTotal.toString());

            // Validate insurance rate
            let tauxFloat = parseInt(patient_taux);
            if (isNaN(tauxFloat)) {
                tauxFloat = 0;  // Set to 0 if the rate is not valid
            }

            // Calculate insurance and patient portions
            if (tauxFloat === 0) {
                montant_assurance.value = '0';  // No insurance coverage
                montant_patient.value = formatPrice(prixTotal.toString());
                montant_patient_hidden.value = formatPrice(prixTotal.toString());
                montant_assurance_hidden.value = '0';
            } else {
                montantAssurance = (tauxFloat / 100) * prixTotal;
                montantPatient = prixTotal - montantAssurance;

                montant_assurance.value = formatPrice(montantAssurance.toString());
                montant_patient.value = formatPrice(montantPatient.toString());

                montant_patient_hidden.value = formatPrice(montantPatient.toString());
                montant_assurance_hidden.value = formatPrice(montantAssurance.toString());
            }

            document.getElementById('taux_remise').value = 0;
            // Show results
            document.getElementById('div_loader').style.display = 'none';
            document.getElementById('div_calcul').style.display = 'flex';
            document.getElementById('btn_calcul').style.display = 'block';
            document.getElementById('btn_eng_hosp').style.display='block';

            return false;
        }

        document.getElementById('taux_remise').addEventListener('input', function() {
            // Nettoyer la valeur entrée en supprimant les caractères non numériques sauf le point
            const rawValue = this.value.replace(/[^0-9]/g, ''); // Supprimer tous les caractères non numériques
            // Ajouter des points pour les milliers
            const formattedValue = formatPrice(rawValue);
            
            this.value = formattedValue;

            const appliq_remise = document.getElementById('appliq_remise').value;

            if (appliq_remise == 'patient') {
                // Convertir la valeur formatée en nombre pour les calculs
                const montant_patient = parseInt(document.getElementById('montant_patient_hidden').value.replace(/\./g, '')) || 0;
                const remise = parseInt(rawValue) || 0;

                // Calculer le montant remis
                const montantRemis = montant_patient - remise;
                document.getElementById('montant_patient').value = formatPriceT(montantRemis);
            }else{
                // Convertir la valeur formatée en nombre pour les calculs
                const montant_assurance = parseInt(document.getElementById('montant_assurance_hidden').value.replace(/\./g, '')) || 0;
                const remise = parseInt(rawValue) || 0;

                // Calculer le montant remis
                const montantRemis = montant_assurance - remise;
                document.getElementById('montant_assurance').value = formatPriceT(montantRemis);
            }
        });

        document.getElementById('appliq_remise').addEventListener('change', function() {

            document.getElementById('montant_assurance').value = formatPrice(document.getElementById('montant_assurance_hidden').value);
            document.getElementById('montant_patient').value = formatPrice(document.getElementById('montant_patient_hidden').value);

            const rawValue = document.getElementById('taux_remise').value.replace(/[^0-9]/g, ''); // 

            if (this.value == 'patient') {
                // Convertir la valeur formatée en nombre pour les calculs
                const montant_patient = parseFloat(document.getElementById('montant_patient_hidden').value.replace(/\./g, '')) || 0;
                const remise = parseFloat(rawValue) || 0;

                // Calculer le montant remis
                const montantRemis = montant_patient - remise;
                document.getElementById('montant_patient').value = formatPriceT(montantRemis);
            }else{
                // Convertir la valeur formatée en nombre pour les calculs
                const montant_assurance = parseFloat(document.getElementById('montant_assurance_hidden').value.replace(/\./g, '')) || 0;
                const remise = parseFloat(rawValue) || 0;

                // Calculer le montant remis
                const montantRemis = montant_assurance - remise;
                document.getElementById('montant_assurance').value = formatPriceT(montantRemis);
            }
        }); 

        function formatPrice(input) {
            // Remove all non-numeric characters except the comma
            input = input.replace(/[^\d,]/g, '');

            // Convert comma to dot for proper float conversion
            input = input.replace(',', '.');

            // Convert to float and round to the nearest whole number
            let number = Math.round(parseFloat(input));
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

        function calculerJours() {
            // Sélectionner les éléments des champs date
            const dateEntree = $('#date_entrer').val();
            const dateSortie = $('#date_sortie').val();
            const joursInput = $('#nbre_jour');

            const entreeValue = new Date(dateEntree);
            const sortieValue = new Date(dateSortie);

            // Vérifier si les deux dates sont valides
            if (!isNaN(entreeValue) && !isNaN(sortieValue)) {
                // Calcul de la différence en millisecondes
                const difference = sortieValue - entreeValue;
                // Convertir en jours (1 jour = 24*60*60*1000 millisecondes)
                let jours = difference / (1000 * 60 * 60 * 24);
                
                // Si jours est égal à 0, alors définir jours à 1
                jours = jours === 0 ? 1 : jours;

                // Mise à jour de la valeur du champ input
                joursInput.val(jours);
            }

            $('#div_calcul').hide();
            calculAmounts();
        }

        function eng_hosp() {
            const auth_id = {{ Auth::user()->id }};

            // Récupérer les valeurs des champs via jQuery
            const patient_id = $('#patient_id').val()?.trim();
            const medecin_id = $('#medecin_id').val()?.trim();
            const id_typeadmission = $('#id_typeadmission').val()?.trim();
            const id_natureadmission = $('#id_natureadmission').val()?.trim();
            const id_chambre = $('#id_chambre').val()?.trim();
            const id_lit = $('#id_lit').val()?.trim();
            const date_entrer = $('#date_entrer').val()?.trim();
            const date_sortie = $('#date_sortie').val()?.trim();
            const numcode = $('#numcode').val()?.trim();

            // Validation des champs obligatoires
            if (!patient_id || 
                !medecin_id || 
                !id_typeadmission || 
                !id_natureadmission || 
                !id_chambre || 
                !id_lit || 
                !date_entrer || 
                !date_sortie) {
                showAlert('Alert', 'Tous les champs sont obligatoires.', 'warning');
                return false;
            }

            // Validation des champs monétaires
            const montant_assurance = $('#montant_assurance').val()?.trim() || 0;
            const taux_remise = $('#taux_remise').val()?.trim() || 0;
            const montant_total = $('#montant_total').val()?.trim() || 0;
            const montant_patient = $('#montant_patient').val()?.trim() || 0;
            const patient_taux = $('#patient_taux').val()?.trim() || 0;
            const nbre_jour = $('#nbre_jour').val()?.trim() || 0;
            const montant_chambre = $('#montant_chambre').val()?.trim() || 0;

            if (montant_assurance < 0 || montant_total < 0 || montant_patient < 0 || montant_chambre < 0) {
                showAlert('Alert', 'Vérifier les montants SVP (les montants ne doivent pas être négatifs).', 'warning');
                return false;
            }

            if (nbre_jour <= 0 || isNaN(nbre_jour)) {
                showAlert('Alert', 'Veuillez vérifier les dates saisies.', 'warning');
                return false;
            }

            // Ajouter un préchargeur
            const preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>`;
            $('body').append(preloader_ch);

            // Requête AJAX
            $.ajax({
                url: '/api/hosp_new',
                method: 'GET', // Changer selon l'API
                data: {
                    patient_id,
                    medecin_id,
                    id_typeadmission,
                    id_natureadmission,
                    id_chambre,
                    id_lit,
                    date_entrer,
                    date_sortie,
                    montant_assurance,
                    taux_remise,
                    montant_total,
                    montant_patient,
                    patient_taux,
                    nbre_jour,
                    montant_chambre,
                    numcode,
                    auth_id,
                },
                success: function(response) {
                    // Supprimer le préchargeur
                    $('#preloader_ch').remove();

                    if (response.success) {
                        showAlert('Succès', 'Patient hospitalisé avec succès.', 'success');
                        reset(); // Réinitialiser les champs
                        const newConsultationTab = new bootstrap.Tab($('#tab-oneAAA')[0]);
                        newConsultationTab.show();
                        $('.Table_hos').DataTable().ajax.reload(null, false);
                        $('.Table_lit').DataTable().ajax.reload(null, false);
                    } else if (response.error) {
                        showAlert('Alert', response.error || 'Une erreur est survenue.', 'error');
                    }
                },
                error: function() {
                    // Supprimer le préchargeur en cas d'erreur
                    $('#preloader_ch').remove();
                    showAlert('Alert', 'Une erreur est survenue lors de l\'hospitalisation.', 'error');
                }
            });
        }

        function reset() {
            // Réinitialiser les champs texte et numériques
            select_patient();
            $('#medecin_id').val('').trigger('change');
            $('#id_typeadmission').val('').trigger('change');
            $('#id_natureadmission').val('').trigger('change');
            $('#id_chambre').val('').trigger('change');
            $('#id_lit').val('').trigger('change');

            // Réinitialiser les dates à aujourd'hui
            const today = new Date().toISOString().split('T')[0];
            $('#date_entrer').val(today);
            $('#date_sortie').val(today);

            // Réinitialiser les champs numériques et de montants
            $('#montant_assurance').val('');
            $('#taux_remise').val('');
            $('#montant_total').val('');
            $('#montant_patient').val('');
            $('#patient_taux').val('');
            $('#nbre_jour').val('');
            $('#montant_chambre').val('');
            $('#numcode').val('');

            // Réinitialiser la visibilité des divs et boutons
            $('#div_loader').hide();
            $('#div_calcul').hide();
            $('#btn_calcul').show();
            $('#div_numcode').hide();

            $('#nbre_jour').val('1');

            Statistique();
        }

        function updatee()
        {
            const id = document.getElementById('IdModif').value;
            const date1 = document.getElementById('date1M');
            const date2 = document.getElementById('date2M');

            if (!date1.value.trim() || !date2.value.trim()) {
                showAlert('Alert', 'Tous les champs sont obligatoires.','warning');
                return false; 
            }

            const startDate = new Date(date1.value);
            const endDate = new Date(date2.value);

            if (startDate > endDate) {
                showAlert('Erreur', 'La date de début ne peut pas être supérieur à la date de fin.', 'error');
                return false;
            }

            var modal = bootstrap.Modal.getInstance(document.getElementById('Mmodif'));
            modal.hide();

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: '/api/update_date_hos/'+id,
                method: 'GET',
                data: {
                    date1: date1.value, 
                    date2: date2.value,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.success) {

                        $('.Table_hos').DataTable().ajax.reload(null, false);

                        showAlert('Succès', 'Opération éffectuée','success');

                    } else if (response.error) {

                        showAlert('Informations', 'Echec de l\'opération','info');

                    }

                },
                error: function() {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert('Alert', ' Une erreur est survenue.','error');
                }
            });
        }

        let cachedProduitsHos = {};

        function select_produit()
        {
           $.ajax({
                url: '/api/list_produit_all',
                method: 'GET',
                success: function (data) {
                    cachedProduitsHos = data.produit;
                },
                error: function () {
                    console.error('Erreur lors du chargement des produits.');
                }
            }); 
        }

        function addSelect(parentDiv, produits) {
            const div = document.createElement('div');
            div.className = 'mb-3';

            // Créer le groupe de contrôle contenant le select et le bouton supprimer
            div.innerHTML = `
                <div class="input-group">
                    <select class="form-select produit-select w-50">
                        <option value="">Sélectionner</option>
                        ${produits.map(produit => `<option value="${produit.id}" data-prix="${produit.prix.replace(/\./g, '')}" data-quantite="${produit.quantite}">${produit.nom} / ${produit.quantite} / ${produit.prix} Fcfa</option>`).join('')}
                    </select>
                    <input type="tel" id="quantite_demande" class="form-control" placeholder="Quantité" value="1" maxlength="2">
                    <button class="btn btn-outline-danger suppr-btn">Supprimer</button>
                </div>
            `;

            // Ajouter l'élément dans le parent (contenu div)
            parentDiv.appendChild(div);

            checkContenu(); // Vérifier le contenu et gérer la visibilité du bouton enregistrer

            // Ajouter un event listener pour le bouton supprimer
            div.querySelector('.suppr-btn').addEventListener('click', () => {
                div.remove(); // Supprimer l'élément div parent
                checkContenu(); // Re-vérifier le contenu
                updateMontantTotal(); // Mettre à jour le montant total après la suppression
            });

            const quantiteInput = div.querySelector('#quantite_demande');
            const produitSelect = div.querySelector('.produit-select');

            // Validation pour n'accepter que des valeurs numériques
            quantiteInput.addEventListener('keypress', function(event) {
                const key = event.key;
                if (isNaN(key)) {
                    event.preventDefault();
                }
            });

            // Fonction pour mettre à jour le montant total
            function updateMontantTotal() {
                let montantTotal = 0;
                const selects = document.querySelectorAll('.produit-select');
                selects.forEach(select => {
                    const selectedOption = select.options[select.selectedIndex];
                    const prix = parseInt(selectedOption.dataset.prix);
                    const quantite = parseInt(select.parentElement.querySelector('#quantite_demande').value);
                    montantTotal += prix * quantite;
                });
                
                // Formater le montant total avec des points
                const montantTotalFormatted = montantTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                document.getElementById('montant_total_produit').value = montantTotalFormatted;
            }

            // Validation de la quantité saisie pour ne pas dépasser la quantité disponible
            produitSelect.addEventListener('change', function() {
                const selectedOption = produitSelect.options[produitSelect.selectedIndex];
                const quantiteDisponible = parseInt(selectedOption.dataset.quantite);
                
                // Réinitialiser la quantité demandée à 1
                quantiteInput.value = 1;

                // Si la quantité est supérieure à la quantité disponible, ajuster
                if (quantiteDisponible < 1) {
                    quantiteInput.value = 1; // S'assurer que la quantité ne soit pas négative
                }

                updateMontantTotal(); // Mettre à jour le montant total après changement de produit
            });

            // Vérification lors de la perte de focus
            quantiteInput.addEventListener('blur', function() {
                const selectedOption = produitSelect.options[produitSelect.selectedIndex];
                const quantiteDisponible = parseInt(selectedOption.dataset.quantite);
                
                if (parseInt(quantiteInput.value) > quantiteDisponible) {
                    showAlert('Alert', `La quantité demandée ne peut pas dépasser ${quantiteDisponible}.`,'warning');
                    quantiteInput.value = quantiteDisponible;
                }else if(quantiteInput.value == ''){
                    quantiteInput.value = 1;
                }

                if(!selectedOption.value == ''){
                    updateMontantTotal();
                } // Mettre à jour le montant total lors de la perte de focus
            });
        }

        document.getElementById('add_select').addEventListener('click', () => {
            const contenuDiv = document.getElementById('contenu');

            addSelect(contenuDiv, cachedProduitsHos);
        });

        function checkContenu() {
            const contenuDiv = document.getElementById('contenu');
            const divBtnPro = document.getElementById('div_btn_pro');
            
            // Si la div #contenu a un contenu, on affiche le bouton, sinon on le cache
            if (contenuDiv.innerHTML.trim() !== "") {
                divBtnPro.style.display = "block"; // Afficher le bouton
            } else {
                divBtnPro.style.display = "none";  // Cacher le bouton
            }
        }

        document.getElementById('btn_eng_produit').addEventListener('click', () => {
            const selections = [];
            const selects = document.querySelectorAll('.produit-select');
            let formIsValid = true;

            selects.forEach(select => {
                const selectedOption = select.options[select.selectedIndex];
                const idProduit = selectedOption.value; // ID du produit sélectionné
                const quantiteDemande = parseInt(select.parentElement.querySelector('#quantite_demande').value); // Quantité demandée
                const prix = parseInt(selectedOption.dataset.prix); // Prix du produit

                // Validation du produit et de la quantité
                if (!idProduit) {  // Si aucun produit n'est sélectionné
                    formIsValid = false;
                    showAlert('Alert', 'Veuillez sélectionner un produit.','warning');
                    return false;  // Stopper l'exécution si une erreur est trouvée
                }

                if (isNaN(quantiteDemande) || quantiteDemande <= 0) { // Si la quantité n'est pas valide
                    formIsValid = false;
                    showAlert('Alert', 'Veuillez entrer une quantité valide pour chaque produit.','warning');
                    return false;  // Stopper l'exécution si une erreur est trouvée
                }

                // Si un produit est sélectionné, ajoutez-le au tableau
                if (idProduit) {
                    selections.push({
                        id: idProduit,
                        quantite: quantiteDemande,
                        montant: prix * quantiteDemande // Calculer le montant
                    });
                }
            });

            if (!Array.isArray(selections) || selections.length === 0) {
                showAlert('Alert', 'Veuillez selectionner un produit.','warning');
                return;
            }

            if (!formIsValid) {
                showAlert('Alert', 'Veuillez selectionner un ou des produit(s).','warning');
                return; // Sortir de la fonction pour éviter le calcul
            }

            const montantTotal = document.getElementById('montant_total_produit').value;
            const id = document.getElementById('id_hos_produit').value;

            var modal = bootstrap.Modal.getInstance(document.getElementById('Add'));
            modal.hide();

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: '/api/add_soinshopital/'+ id,
                method: 'GET',
                data:{
                    selections: selections,
                    montantTotal: montantTotal,
                },
                success: function(response) {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    
                    if (response.success) {
                        showAlert('Succès', 'Produit Pharmacie ajouter.','success');
                    } else if (response.error) {
                        showAlert('Alert', 'Une erreur est survenue','error');
                    } else if (response.json) {
                        showAlert('Alert', 'Invalid selections format','error');
                    }

                    $('.Table_hos').DataTable().ajax.reload(null, false);
                },
                error: function() {
                    
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert('Alert', 'Une erreur est survenue lors de l\'enregistrement','error');
                }
            });
        });

        document.getElementById('close_modal_produit').addEventListener('click', () => {
            document.getElementById('montant_total_produit').value = "";
        });

        function formatDate(dateString) {
            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0');  // Ajoute un '0' si le jour est à un chiffre
            const month = String(date.getMonth() + 1).padStart(2, '0');  // Les mois sont indexés de 0, donc +1
            const year = date.getFullYear();
            
            return `${day}-${month}-${year}`;
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

        function calculateDaysBetween(startDate, endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            
            // Calcul de la différence en millisecondes
            const diffInMilliseconds = end - start;

            // Conversion en jours (millisecondes en secondes, minutes, heures, jours)
            let diffInDays = diffInMilliseconds / (1000 * 60 * 60 * 24);

            // Si la différence est inférieure ou égale à 0, on retourne 1 jour minimum
            return diffInDays <= 0 ? 1 : Math.round(diffInDays); 
        }

        function updatePaginationControls(pagination) {
            const paginationDiv = document.getElementById('pagination-controls-hos');
            paginationDiv.innerHTML = '';

            // Bootstrap pagination wrapper
            const paginationWrapper = document.createElement('ul');
            paginationWrapper.className = 'pagination justify-content-center';

            // Previous button
            if (pagination.current_page > 1) {
                const prevButton = document.createElement('li');
                prevButton.className = 'page-item';
                prevButton.innerHTML = `<a class="page-link" href="#">Precédent</a>`;
                prevButton.onclick = (event) => {
                    event.preventDefault(); // Empêche le défilement en haut de la page
                    list_hos(pagination.current_page - 1);
                };
                paginationWrapper.appendChild(prevButton);
            } else {
                // Disable the previous button if on the first page
                const prevButton = document.createElement('li');
                prevButton.className = 'page-item disabled';
                prevButton.innerHTML = `<a class="page-link" href="#">Precédent</a>`;
                paginationWrapper.appendChild(prevButton);
            }

            // Page number links (show a few around the current page)
            const totalPages = pagination.last_page;
            const currentPage = pagination.current_page;
            const maxVisiblePages = 5; // Max number of page links to display

            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

            // Adjust start page if end page exceeds the total pages
            if (endPage - startPage < maxVisiblePages - 1) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            // Loop through pages and create page links
            for (let i = startPage; i <= endPage; i++) {
                const pageItem = document.createElement('li');
                pageItem.className = `page-item ${i === currentPage ? 'active' : ''}`;
                pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                pageItem.onclick = (event) => {
                    event.preventDefault(); // Empêche le défilement en haut de la page
                    list_hos(i);
                };
                paginationWrapper.appendChild(pageItem);
            }

            // Ellipsis (...) if not all pages are shown
            if (endPage < totalPages) {
                const ellipsis = document.createElement('li');
                ellipsis.className = 'page-item disabled';
                ellipsis.innerHTML = `<a class="page-link" href="#">...</a>`;
                paginationWrapper.appendChild(ellipsis);

                // Add the last page link
                const lastPageItem = document.createElement('li');
                lastPageItem.className = `page-item`;
                lastPageItem.innerHTML = `<a class="page-link" href="#">${totalPages}</a>`;
                lastPageItem.onclick = (event) => {
                    event.preventDefault(); // Empêche le défilement en haut de la page
                    list_hos(totalPages);
                };
                paginationWrapper.appendChild(lastPageItem);
            }

            // Next button
            if (pagination.current_page < pagination.last_page) {
                const nextButton = document.createElement('li');
                nextButton.className = 'page-item';
                nextButton.innerHTML = `<a class="page-link" href="#">Suivant</a>`;
                nextButton.onclick = (event) => {
                    event.preventDefault(); // Empêche le défilement en haut de la page
                    list_hos(pagination.current_page + 1);
                };
                paginationWrapper.appendChild(nextButton);
            } else {
                // Disable the next button if on the last page
                const nextButton = document.createElement('li');
                nextButton.className = 'page-item disabled';
                nextButton.innerHTML = `<a class="page-link" href="#">Suivant</a>`;
                paginationWrapper.appendChild(nextButton);
            }

            // Append pagination controls to the DOM
            paginationDiv.appendChild(paginationWrapper);
        }

        function generatePDFInvoice(hopital, facture, patient, nature, type, lit, chambre, user, produit) {
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
                doc.setTextColor(255, 0, 0);
                // doc.line(10, 35, 200, 35); 
                const titleR = "FACTURE HOSPITALISATION";
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
                );

                medecinInfo.forEach(info => {
                    doc.setFontSize(8);
                    doc.setFont("Helvetica", "bold");
                    doc.text(info.label, leftMargin + 100, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 135, yPoss);
                    yPoss += 7;
                });

                yPoss = (yPos + 90);

                const typeInfo = [
                    { label: "Type d'admission", value: type.nom },
                    { label: "Nature d'admission", value: nature.nom },
                    { label: "Chambre Occupée", value: "CH-"+chambre.code },
                    { label: "Lit Occupée", value: "LIT-"+lit.code+"/"+lit.type },
                    { label: "Prix", value: chambre.prix+" Fcfa" },
                ];

                typeInfo.forEach(info => {
                    doc.setFontSize(8);
                    doc.setFont("Helvetica", "bold");
                    doc.text(info.label, leftMargin, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 35, yPoss);
                    yPoss += 7;
                });

                yPoss = (yPos + 98);

                const donneeTable = produit;
                // Using autoTable to add a dynamic table for hospital stay details
                if ( donneeTable.length > 0) {
                    yPossT = yPoss + 10;
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
                        // headStyles: { fillColor: [255, 0, 0] }, // En-tête en rouge si nécessaire
                    });

                    // Utiliser la position Y de la dernière ligne du tableau
                    const finalY = doc.autoTable.previous.finalY || yPossT + 10;

                    yPoss = finalY + 10;

                    const finalInfo = [];    

                    finalInfo.push(
                        { label: "Montant Total", value: hopital.montant +" Fcfa" },
                        { label: "Total Produit", value: hopital.montant_soins +" Fcfa" },
                        { label: "Total Chambre", value: hopital.montant_chambre +" Fcfa" },
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

        function Statistique() {
            const $nbreDay = $('#nbre_hos');

            $.ajax({
                url: '/api/statistique_hos',
                method: 'GET',
                success: function(response) {
                    // Mettre à jour le contenu texte de l'élément
                    $nbreDay.text(response.stat_hos_day);
                },
                error: function() {
                    // Mettre une valeur par défaut en cas d'erreur
                    $nbreDay.text('0');
                }
            });
        }

    });
</script>


@endsection