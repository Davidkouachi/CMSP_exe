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
            Liste des assurances
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    <div class="row gx-3 ">
        <div class="col-xxl-12 col-sm-12">
            <div class="card mb-3 bg-3">
                <div class="card-body " style="background: rgba(0, 0, 0, 0.7);" >
                    <div class="py-4 px-3 text-white">
                        <h6>ASSURANCES</h6>
                        {{-- <h2>{{Auth::user()->sexe.'. '.Auth::user()->name}}</h2> --}}
                        <p>Récéption / Assurances</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gx-3" id="stat"></div>
    </div>
    <div class="row gx-3" >
        <div class="col-sm-12">
            <div class="card mb-3 mt-3">
                <div class="card-body" style="margin-top: -30px;">
                    <div class="custom-tabs-container">
                        <ul class="nav nav-tabs justify-content-center bg-primary bg-2" id="customTab4" role="tablist" style="background: rgba(0, 0, 0, 0.7);">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active text-white" id="tab-twoAAAN" data-bs-toggle="tab" href="#twoAAAN" role="tab" aria-controls="twoAAAN" aria-selected="false" tabindex="-1">
                                    <i class="ri-user-add-line me-2"></i>
                                    Nouvelle Assurance
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-white" id="tab-twoAAA" data-bs-toggle="tab" href="#twoAAA" role="tab" aria-controls="twoAAA" aria-selected="false" tabindex="-1">
                                    <i class="ri-contacts-line me-2"></i>
                                    Liste des Assurances
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-white" id="tab-twoRech" data-bs-toggle="tab" href="#twoRech" role="tab" aria-controls="twoRech" aria-selected="false" tabindex="-1">
                                    <i class="ri-search-2-line me-2"></i>
                                    Recherche
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane active show fade" id="twoAAAN" role="tabpanel" aria-labelledby="tab-twoAAAN">
                                <div class="card-header">
                                    <h5 class="card-title text-center">
                                        Formulaire Nouvelle Assurance
                                    </h5>
                                </div>
                                <div class="card-header">
                                    <div class="text-center">
                                        <a class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/assurance3.jpg')}}" class="img-7x rounded-circle border border-3">
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body" >
                                    <div class="row gx-3">
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Nom</label>
                                                <input type="text" class="form-control" id="nom_assurance_new" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input required type="email" class="form-control" id="email_assurance_new" placeholder="Saisie Obligatoire">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Contact</label>
                                                <input type="tel" class="form-control" id="tel_assurance_new" placeholder="Saisie Obligatoire" maxlength="10">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" >Fax</label>
                                                <input type="text" class="form-control" id="fax_assurance_new" placeholder="Facultatif">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Adresse</label>
                                                <input type="text" class="form-control" id="adresse_assurance_new" placeholder="Saisie Obligatoire">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Localisation</label>
                                                <input type="text" class="form-control" id="carte_assurance_new" placeholder="Saisie Obligatoire">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <input type="text" class="form-control" id="desc_assurance_new" placeholder="Facultatif">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button id="btn_eng_assurance" class="btn btn-success">
                                                    Enregistrer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="twoAAA" role="tabpanel" aria-labelledby="tab-twoAAA">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="card-title">
                                        Liste des Assurances
                                    </h5>
                                    <div class="d-flex">
                                        <a id="btn_refresh_tableP" class="btn btn-outline-info ms-auto">
                                            <i class="ri-loop-left-line"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="">
                                        <div class="table-responsive">
                                            <table id="Table_day" class="table align-middle table-hover m-0 truncate">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">N°</th>
                                                        <th scope="col">Assurance</th>
                                                        <th scope="col">Email</th>
                                                        <th scope="col">Contact</th>
                                                        <th scope="col">Fax</th>
                                                        <th scope="col">Adresse</th>
                                                        <th scope="col">Situation Géophraphique</th>
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
                            <div class="tab-pane fade" id="twoRech" role="tabpanel" aria-labelledby="tab-twoRech">
                                <div class="row gx-3">
                                    {{-- <div class="row gx-3 justify-content-center align-items-center" >
                                        <div class="col-xxl-4 col-lg-4 col-sm-6">
                                            <div class=" mb-1">
                                                <div class="card-body">
                                                    <div class="text-center">
                                                        <a class="d-flex align-items-center flex-column">
                                                            <img src="{{asset('assets/images/assurance3.jpg')}}" class="img-7x rounded-circle border border-3">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3 text-center">
                                                <label class="form-label">
                                                    Assurance
                                                </label>
                                                <div class="input-group">
                                                    <select class="form-select select2" id="assurance_id">
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mt-3" id="div_info_patient">
                                    </div> --}}
                                    <div class="error-container">
                                        <h4 class="mb-2 text-primary">Page en cours de dévéloppement...</h4>
                                        <h5 class="fw-light mb-4">
                                            Nous travaillons actuellement sur cette page pour vous offrir la meilleure expérience. 
                                            <br>
                                            Merci de votre patience !
                                        </h5>
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
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mise à jour</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateChambreForm">
                    <input type="hidden" id="Id">
                    <div class="row gx-3">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nomModif" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input required type="email" class="form-control" id="emailModif" placeholder="Saisie Obligatoire">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Contact</label>
                                <input type="tel" class="form-control" id="telModif" placeholder="Saisie Obligatoire" maxlength="10">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" >Fax</label>
                                <input type="text" class="form-control" id="faxModif" placeholder="Saisie Obligatoire">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Adresse</label>
                                <input type="text" class="form-control" id="adresseModif" placeholder="Facultatif">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Localisation</label>
                                <input type="text" class="form-control" id="carteModif" placeholder="Saisie Obligatoire">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <input type="text" class="form-control" id="descModif" placeholder="Facultatif">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="updateBtn">Mettre à jour</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Detail" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Détail Assurance
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal_detail"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="Detailexam" tabindex="-1" aria-modal="true" role="dialog">
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
                                        <div class="table-responsive" id="div_Tableexam" style="display: none;">
                                            <table class="table table-bordered" id="Tableexam">
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
                                        <div id="message_Tableexam" style="display: none;">
                                            <p class="text-center" >
                                                Aucun Produit utilisé pour le moment
                                            </p>
                                        </div>
                                        <div id="div_Table_loaderexam" style="display: none;">
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

<div class="modal fade" id="Detailhos" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Détail
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal_detailhos"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="Detail_produithos" tabindex="-1" aria-modal="true" role="dialog">
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
                                        <div class="table-responsive" id="div_Tablehos" style="display: none;">
                                            <table class="table table-bordered" id="Tablehos">
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
                                        <div id="message_Tablehos" style="display: none;">
                                            <p class="text-center" >
                                                Aucun Produit utilisé pour le moment
                                            </p>
                                        </div>
                                        <div id="div_Table_loaderhos" style="display: none;">
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

<div class="modal fade" id="Detail_produitsoinsam" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
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
                                        <div class="table-responsive" id="div_Tablesoinsamp" style="display: none;">
                                            <!-- Tableau Soins Infirmiers -->
                                            <table class="table table-bordered" id="Tablesoinsamp">
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

                                        <div class="table-responsive" id="div_TableProdsoinsamp" style="display: none;">
                                            <!-- Tableau Produits Utilisés -->
                                            <table class="table table-bordered" id="TableProdsoinsamp">
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

                                        <div id="message_Tablesoinsamp" style="display: none;">
                                            <p class="text-center" >
                                                Aucun détail pour le moment
                                            </p>
                                        </div>
                                        <div id="div_Table_loadersoinsamp" style="display: none;">
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

<div class="modal fade" id="Detailsoinsam" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Détails
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal_detailsoinsamd" style="display: none;">
            </div>
            <div id="message_detailsoinsamd" style="display: none;">
                <p class="text-center" >
                    Aucun détail pour le moment
                </p>
            </div>
            <div id="div_detail_loadersoinsamd" style="display: none;">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                    <strong>Chargement des données...</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Mdelete" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delRowLabel">
                    Confirmation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Voulez-vous vraiment supprimé cette assurance ?
                <input type="hidden" id="Id_delete">
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-end gap-2">
                    <a class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Non</a>
                    <button id="deleteBtn" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Oui</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('assets/js/app/js/jspdfinvoicetemplate/dist/index.js')}}" ></script>
<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>
<script src="{{asset('assets/vendor/apex/apexcharts.min.js')}}"></script>

@include('select2')

<script>
    $(document).ready(function() {

        // select_assurance();

        $("#btn_eng_assurance").on("click", eng_assurance);
        $("#updateBtn").on("click", updatee);
        $("#deleteBtn").on("click", deletee);

        $('#btn_refresh_tableP').on('click', function () {
            $('#Table_day').DataTable().ajax.reload();
        });

        var inputs = ['tel_assurance_new', 'telModif',]; // Array of element IDs
        inputs.forEach(function(id) {
            var inputElement = document.getElementById(id); // Get each element by its ID

            // Allow only numeric input (and optionally some special keys like backspace or delete)
            inputElement.addEventListener('keypress', function(event) {
                const key = event.key;
                // Allow numeric keys, backspace, and delete
                if (!/[0-9]/.test(key) && key !== 'Backspace' && key !== 'Delete') {
                    event.preventDefault();
                }
            });

            // Alternatively, for more comprehensive input validation, use input event listener
            inputElement.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, ''); // Allow only numbers
            });
        });

        function formatPrice(price) {

            // Convert to float and round to the nearest whole number
            let number = Math.round(parseFloat(price));
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

        // function select_assurance() 
        // {
        //     const selectElement2 = $('#assurance_id');
        //     selectElement2.empty();
        //     selectElement2.append($('<option>', {
        //         value: '',
        //         text: 'Selectionner',
        //     }));

        //     $.ajax({
        //         url: '/api/assurance_select_patient_new',
        //         method: 'GET',
        //         success: function(response) {
        //             const data = response.assurance;

        //             data.forEach(function(item) {
        //                 selectElement2.append($('<option>', {
        //                     value: item.codeassurance,
        //                     text: item.libelleassurance,
        //                 }));
        //             });
        //         },
        //         error: function() {
        //             // showAlert('danger', 'Impossible de generer le code automatiquement');
        //         }
        //     });
        // }

        function eng_assurance()
        {
            var nom = document.getElementById("nom_assurance_new");
            var email = document.getElementById("email_assurance_new");
            var phone = document.getElementById("tel_assurance_new");
            var adresse = document.getElementById("adresse_assurance_new");
            var fax = document.getElementById("fax_assurance_new");
            var carte = document.getElementById("carte_assurance_new");
            var desc = document.getElementById("desc_assurance_new");

            if (!nom.value.trim() || !email.value.trim() || !phone.value.trim() || !carte.value.trim() || !carte.value.trim() || !adresse.value.trim()) {
                showAlert('Alert', 'Tous les champs obligatoires n\'ont pas été rempli.','warning');
                return false; 
            }

            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value.trim())) { 
                showAlert('Alert', 'Email incorrect.','warning');
                return false;
            }


            if (phone.value.length !== 10) {
                showAlert('Alert', 'Contact incomplet.','warning');
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
                url: '/api/assurance_new',
                method: 'GET',
                data: { 
                    nom: nom.value, 
                    email: email.value, 
                    tel: phone.value, 
                    desc: desc.value || null, 
                    fax: fax.value || null, 
                    adresse: adresse.value,
                    carte: carte.value,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    
                    if (response.tel_existe) {
                        showAlert('Alert', 'Ce numéro de contact appartient déjà a une assurance.','warning');
                    }else if (response.email_existe) {
                        showAlert('Alert', 'Ce email appartient déjà a une assurance.','warning');
                    }else if (response.nom_existe) {
                        showAlert('Alert', 'Cette assurance existe déjà.','warning');
                    }else if (response.fax_existe) {
                        showAlert('Alert', 'Ce fax appartient déjà a une assurance.','warning');
                    } else if (response.success) {

                        nom.value = '';
                        email.value = '';
                        phone.value = '';
                        desc.value = '';
                        fax.value = '';
                        adresse.value = '';
                        carte.value = '';

                        $('#Table_day').DataTable().ajax.reload();
                        select_assurance();

                        showAlert('Succès', response.message,'success');
                    } else if (response.error) {
                        showAlert('Alert', 'Une erreur est survenue lors de l\'enregistrement.','error');
                    }

                },
                error: function() {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert('Alert', 'Une erreur est survenue lors de l\'enregistrement.','error');
                }
            });
        }

        $('#Table_day').DataTable({

            processing: true,
            serverSide: false,
            ajax: {
                url: `/api/list_assurance_all`,
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
                    data: 'libelleassurance', 
                    render: (data, type, row) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{ asset('/assets/images/assurance3.jpg') }}" class="img-2x rounded-circle border border-1">
                        </a>
                        ${data}
                    </div>`,
                    searchable: true, 
                },
                {
                    data: 'emailassurance',
                    render: (data, type, row) => {
                        return data ? `${data}` : 'Néant';
                    },
                    searchable: true,
                },
                {
                    data: 'telassurance',
                    render: (data, type, row) => {
                        return data ? `${data}` : 'Néant';
                    },
                    searchable: true,
                },
                {
                    data: 'faxassurance',
                    render: (data, type, row) => {
                        return data ? `${data}` : 'Néant';
                    },
                    searchable: true,
                },
                {
                    data: 'adrassurance',
                    render: (data, type, row) => {
                        return data ? `${data}` : 'Néant';
                    },
                    searchable: true,
                },
                {
                    data: 'situationgeo',
                    render: (data, type, row) => {
                        if (data) {
                            return data.length > 15 ? `${data.substring(0, 15)}...` : data;
                        }
                        return 'Néant';
                    },
                    searchable: true,
                },
                {
                    data: null,
                    render: (data, type, row) => `
                        <div class="d-inline-flex gap-1" style="font-size:10px;">
                            <a class="btn btn-outline-warning btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#Detail" id="detail" 
                                data-id="${row.idassurance}" 
                                data-code="${row.codeassurance}" 
                                data-nom="${row.libelleassurance}" 
                                data-email="${row.emailassurance}" 
                                data-tel="${row.telassurance}"
                                data-adresse="${row.adrassurance}" 
                                data-fax="${row.faxassurance}"
                                data-carte="${row.situationgeo}"
                                data-description="${row.description}"
                            >
                                <i class="ri-eye-line"></i>
                            </a>
                            <a class="btn btn-outline-info btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#Mmodif" id="modif"
                                data-id="${row.idassurance}" 
                                data-code="${row.codeassurance}" 
                                data-nom="${row.libelleassurance}" 
                                data-email="${row.emailassurance}" 
                                data-tel="${row.telassurance}"
                                data-adresse="${row.adrassurance}" 
                                data-fax="${row.faxassurance}"
                                data-carte="${row.situationgeo}"
                                data-description="${row.description}"
                            >
                                <i class="ri-edit-box-line"></i>
                            </a>
                            <a class="btn btn-outline-danger btn-sm delete-btn" data-bs-toggle="modal" data-bs-target="#Mdelete" id="delete"
                                data-id="${row.idassurance}"
                            >
                                <i class="ri-delete-bin-line"></i>
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

            $('#Table_day').on('click', '#detail', function() {
                const row = {
                    id: $(this).data('id'),
                    code: $(this).data('code'),
                    nom: $(this).data('nom'),
                    email: $(this).data('email'),
                    tel: $(this).data('tel'),
                    adresse: $(this).data('adresse'),
                    fax: $(this).data('fax'),
                    carte: $(this).data('carte'),
                    description: $(this).data('description'),
                };

                const modal = document.getElementById('modal_detail');
                modal.innerHTML = '';

                const div = document.createElement('div');
                div.innerHTML = `
                    <div class="row gx-3">
                        <div class="col-12">
                            <div class=" mb-3">
                                <div class="card-body">
                                    <div class="text-center">
                                        <a class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/assurance3.jpg')}}" class="img-7x rounded-circle mb-3 border border-3">
                                            <h5>${row.nom}</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class=" mb-3">
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item active text-center" aria-current="true">
                                            Informations
                                        </li>
                                        <li class="list-group-item">
                                            Code : ${row.code}
                                        </li>
                                        <li class="list-group-item">
                                            Nom : ${row.nom}
                                        </li>
                                        <li class="list-group-item">
                                            Email : ${row.email !== null ? `${row.email}` : `Néant`}
                                        </li>
                                        <li class="list-group-item">
                                            Téléphone : ${row.tel !== null ? `${row.tel}` : `Néant`}
                                        </li>
                                        <li class="list-group-item">
                                            Fax : ${row.fax !== null ? `${row.fax}` : `Néant`}
                                        </li>
                                        <li class="list-group-item">
                                            Adresse : ${row.adresse !== null ? `${row.adresse}` : `Néant`}
                                        </li>
                                        <li class="list-group-item">
                                            Situation Géographique : ${row.carte !== null ? `${row.carte}` : `Néant`}
                                        </li>
                                        <li class="list-group-item">
                                            Description : ${row.description !== null ? `${row.description}` : `Néant`}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                modal.appendChild(div);
            });

            $('#Table_day').on('click', '#modif', function() {
                const id = $(this).data('id');
                const nom = $(this).data('nom');
                const email = $(this).data('email');
                const tel = $(this).data('tel');
                const desc = $(this).data('description');
                const adresse = $(this).data('adresse');
                const fax = $(this).data('fax');
                const carte = $(this).data('carte');

                $('#Id').val(id);
                $('#nomModif').val(nom);
                $('#emailModif').val(email);
                $('#adresseModif').val(adresse);
                $('#telModif').val(tel);
                $('#descModif').val(desc);
                $('#faxModif').val(fax);
                $('#carteModif').val(carte);
            });

            $('#Table_day').on('click', '#delete', function() {
                const id = $(this).data('id');
                $('#Id_delete').val(id);
            });
        }

        function updatee() {

            const id = document.getElementById('Id').value;
            const nom = document.getElementById('nomModif');
            const email = document.getElementById("emailModif");
            const adresse = document.getElementById("adresseModif");
            const fax = document.getElementById("faxModif");
            const tel = document.getElementById("telModif");
            const desc = document.getElementById("descModif");
            const carte = document.getElementById("carteModif");

            if(!nom.value.trim() || !email.value.trim() || !carte.value.trim() || !tel.value.trim() || !adresse.value.trim())
            {
                showAlert('Alert', 'Tous les champs obligatoires n\'ont pas été rempli.','warning');
                return false;
            }

            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value.trim())) { 
                showAlert('Alert', 'Email incorrect.','warning');
                return false;
            }


            if (tel.value.length !== 10) {
                showAlert('Alert', 'Contact incomplet.','warning');
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
                url: '/api/update_assurance/'+id,
                method: 'GET',
                data: { 
                    nom: nom.value,
                    email: email.value,
                    adresse: adresse.value || null,
                    fax: fax.value || null,
                    tel: tel.value,
                    desc: desc.value || null,
                    carte: carte.value,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.tel_existe) {
                        showAlert('Alert', 'Ce numéro de téléphone appartient déjà a une assurance.','warning');
                    }else if (response.email_existe) {
                        showAlert('Alert', 'Ce email appartient déjà a une assurance.','warning');
                    }else if (response.nom_existe) {
                        showAlert('Alert', 'Cette assurance existe déjà.','warning');
                    }else if (response.fax_existe) {
                        showAlert('Alert', 'Ce fax appartient déjà a une assurance.','warning');
                    } else if (response.success) {
                        select_assurance();
                        $('#Table_day').DataTable().ajax.reload();
                        showAlert('Succès', 'Opérationn éffectué.','success');
                    } else if (response.error) {
                        showAlert('Alert', 'Une erreur est survenue.','error');
                    }
                },
                error: function() {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    showAlert('Alert', 'Erreur lors de la mise à jour.','error');
                }
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

        function deletee() 
        {
            const id = $('#Id_delete').val().trim();

            var modal = bootstrap.Modal.getInstance($('#Mdelete')[0]);
            modal.hide();

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;

            $.ajax({
                url: '/api/delete_assurance/' + id,
                method: 'GET',  // Use 'POST' for data creation
                success: function(response) {
                    $('#preloader_ch').remove(); // Remove preloader

                    if (response.success) {
                        $('#Table_day').DataTable().ajax.reload();
                        showAlert('Succès', response.message, 'success');
                    } else if (response.error) {
                        showAlert('Erreur', response.message, 'error');
                    }
                },
                error: function() {
                    $('#preloader_ch').remove(); // Remove preloader

                    showAlert('Erreur', 'Erreur lors de la suppression.', 'error');
                }
            });
        }

        // $('#assurance_id').on('change', function() {

        //     const dynamicFields = document.getElementById("div_info_patient");
        //     dynamicFields.innerHTML = "";

        //     if (this.value == ''){
        //         showAlert('Alert', 'Veuillez selectionner une assurance s\'il vous plaît.','warning');
        //         return false;
        //     }

        //     const assurance_id = this.value;

        //     //--------------------------------------------------------------

        //     var charge = `
        //         <div class="d-flex justify-content-center align-items-center" id="laoder_stat">
        //             <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
        //             <strong>Chargement des données...</strong>
        //         </div>
        //     `;
        //     dynamicFields.innerHTML = charge;

        //     const url = `/api/assurance_stat/${assurance_id}`;

        //     fetch(url)
        //         .then(response => response.json())
        //         .then(data => {

        //             var loader = document.getElementById('laoder_stat');
        //             if (loader) loader.remove();

        //             //--------------------------------------------------

        //             const nbre_cons = data.nbre_cons;
        //             const nbre_hos = data.nbre_hos;
        //             const nbre_exam = data.nbre_exam;
        //             const nbre_soinsam = data.nbre_soinsam;
        //             const stats = data.data;

        //             //--------------------------------------------------

        //             var groupe1 = document.createElement("div");
        //             groupe1.className = "row gx-3";
        //             groupe1.innerHTML = `
        //                 <div class=" mb-0">
        //                     <div class="card-body">
        //                         <div class="card-header d-flex flex-column justify-content-center align-items-center">
        //                             <h5 class="card-title mb-3">
        //                                 Statistique des actes éffectués
        //                             </h5>
        //                         </div>
        //                     </div>
        //                 </div>
        //             `;
        //             dynamicFields.appendChild(groupe1);

        //             //--------------------------------------------------

        //             var groupe01 = document.createElement("div");
        //             groupe01.className = "row gx-3 stat_acte";
        //             dynamicFields.appendChild(groupe01);

        //             groupe01.innerHTML = '';
        //             const cardData_acte = [
        //                 { label: "Consultations", count: nbre_cons, icon: "ri-lungs-line", colorClass: "text-success", borderColor: "border-success", bgColor: "bg-success", mTotal : formatPrice(stats.m_cons.total_general), pTotal : formatPrice(stats.m_cons.total_payer), ipTotal : formatPrice(stats.m_cons.total_impayer), assurance : formatPrice(stats.m_cons.part_assurance), patient : formatPrice(stats.m_cons.part_patient)},
        //                 { label: "Examens", count: nbre_exam, icon: "ri-medicine-bottle-line", colorClass: "text-danger", borderColor: "border-danger", bgColor: "bg-danger", mTotal : formatPrice(stats.m_exam.total_general), pTotal : formatPrice(stats.m_exam.total_payer), ipTotal : formatPrice(stats.m_exam.total_impayer), assurance : formatPrice(stats.m_exam.part_assurance), patient : formatPrice(stats.m_exam.part_patient)},
        //                 { label: "Hospitalisations", count: nbre_hos, icon: "ri-hotel-bed-line", colorClass: "text-primary", borderColor: "border-primary", bgColor: "bg-primary", mTotal : formatPrice(stats.m_hos.total_general), pTotal : formatPrice(stats.m_hos.total_payer), ipTotal : formatPrice(stats.m_hos.total_impayer), assurance : formatPrice(stats.m_hos.part_assurance), patient : formatPrice(stats.m_hos.part_patient)},
        //                 { label: "Soins Ambulatoires", count: nbre_soinsam, icon: "ri-dossier-line", colorClass: "text-warning", borderColor: "border-warning", bgColor: "bg-warning", mTotal : formatPrice(stats.m_soinsam.total_general), pTotal : formatPrice(stats.m_soinsam.total_payer), ipTotal : formatPrice(stats.m_soinsam.total_impayer), assurance : formatPrice(stats.m_soinsam.part_assurance), patient : formatPrice(stats.m_soinsam.part_patient)},
        //             ];

        //             cardData_acte.forEach(card => {
        //                 const div = document.createElement('div');
        //                 div.className = "col-xl-3 col-sm-6 col-12";
        //                 div.innerHTML = `
        //                     <div class="border rounded-2 d-flex align-items-center flex-row p-2 mb-3">
        //                         <div class="card-body">
        //                             <div class="d-flex align-items-center">
        //                                 <div class="p-2 ${card.borderColor} rounded-circle me-3">
        //                                     <div class="icon-box md ${card.bgColor} rounded-5">
        //                                         <i class="${card.icon} fs-4 text-white"></i>
        //                                     </div>
        //                                 </div>
        //                                 <div class="d-flex flex-column">
        //                                     <h2 class="lh-1">${card.count}</h2>
        //                                     <p class="m-0">${card.label}</p>
        //                                 </div>
        //                             </div>
        //                             <div class="d-flex align-items-end justify-content-between mt-1">
        //                                 <a class="${card.colorClass}" href="javascript:void(0);">
        //                                     <span>Montant Total</span>
        //                                     <i class="ri-arrow-right-line ${card.colorClass} ms-1"></i>
        //                                 </a>
        //                                 <div class="text-end">
        //                                     <p class="mb-0 ${card.colorClass}">${card.mTotal} Fcfa</p>
        //                                 </div>
        //                             </div>
        //                             <div class="d-flex align-items-end justify-content-between mt-1">
        //                                 <a class="${card.colorClass}" href="javascript:void(0);">
        //                                     <span>Part Assurance</span>
        //                                     <i class="ri-arrow-right-line ${card.colorClass} ms-1"></i>
        //                                 </a>
        //                                 <div class="text-end">
        //                                     <p class="mb-0 ${card.colorClass}">${card.assurance} Fcfa</p>
        //                                 </div>
        //                             </div>
        //                             <div class="d-flex align-items-end justify-content-between mt-1">
        //                                 <a class="${card.colorClass}" href="javascript:void(0);">
        //                                     <span>Part Patient</span>
        //                                     <i class="ri-arrow-right-line ${card.colorClass} ms-1"></i>
        //                                 </a>
        //                                 <div class="text-end">
        //                                     <p class="mb-0 ${card.colorClass}">${card.patient} Fcfa</p>
        //                                 </div>
        //                             </div>
        //                         </div>
        //                     </div>
        //                 `;
        //                 groupe01.appendChild(div);
        //             });


        //         })
        //         .catch(error => {
        //             console.error('Erreur lors du chargement des données:', error);
        //         });
        // });

    });
</script>

@endsection
