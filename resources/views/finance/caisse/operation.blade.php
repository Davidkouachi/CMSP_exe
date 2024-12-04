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
            Opération de Caisse
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
                        <div class="mt-4 row gx-3">
                            <div class="d-flex align-items-center col-xxl-3 col-lg-4 col-sm-6 col-12 mb-3 ">
                                <div class="icon-box lg bg-success rounded-5 me-3">
                                    <i class="ri-safe-2-line fs-1"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h4 id="solde_caisse" class="m-0 lh-1"></h4>
                                    <p class="m-0">Solde Actuel</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-3">
        <div class="col-sm-12">
            <div class="card mb-3 mt-3">
                <div class="card-body" style="margin-top: -30px;">
                    <div class="custom-tabs-container">
                        <ul class="nav nav-tabs justify-content-center bg-primary bg-2" id="customTab4" role="tablist" style="background: rgba(0, 0, 0, 0.7);">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active text-white" id="tab-twoAAAN" data-bs-toggle="tab" href="#twoAAAN" role="tab" aria-controls="twoAAAN" aria-selected="false" tabindex="-1">
                                    <i class="ri-swap-3-line me-2"></i>
                                    Nouvelle Opération
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-white" id="tab-twoAAA" data-bs-toggle="tab" href="#twoAAA" role="tab" aria-controls="twoAAA" aria-selected="false" tabindex="-1">
                                    <i class="ri-upload-cloud-line me-2"></i>
                                    Historique Caisse
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-white" id="tab-tAAA" data-bs-toggle="tab" href="#tAAA" role="tab" aria-controls="tAAA" aria-selected="false" tabindex="-1">
                                    <i class="ri-upload-cloud-line me-2"></i>
                                    Bilan Journalier
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane active show fade" id="twoAAAN" role="tabpanel" aria-labelledby="tab-twoAAAN">
                                <div class="row justify-content-center" id="div_caisse_verf" style="display: none;">
                                    <div class="col-12">
                                        <div class="mb-3">
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
                                                        <button id="btn_ouvert_C" type="button" class="btn btn-outline-success">
                                                            Ouverture de Caisse
                                                            <i class="ri-door-open-line"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-12" id="btn_fermer">
                                                    <div class="mb-1 text-center">
                                                        <button id="btn_fermer_C" type="button" class="btn btn-outline-danger">
                                                            Fermeture de Caisse
                                                            <i class="ri-door-close-line"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="div_caisse" style="display: none;" >
                                    <div class="card-header">
                                        <h5 class="card-title">Formulaire Nouvelle Opération</h5>
                                    </div>
                                    <div class="card-body" >
                                        <div class="row gx-3">
                                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Type d'opération</label>
                                                    <select class="form-select" id="type_ope">
                                                        <option value="">Selectionner</option>
                                                        <option value="entrer">Entrer d'argent</option>
                                                        <option value="sortie">Sortie d'argent</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Montant</label>
                                                    <div class="input-group">
                                                        <input type="tel" class="form-control" id="montant_ope" placeholder="Saisie Obligatoire">
                                                        <span class="input-group-text">Fcfa</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">
                                                        Nom et Prénoms
                                                    </label>
                                                    <input type="text" class="form-control" placeholder="Saisie Obligatoire" id="nom_ope" oninput="this.value = this.value.toUpperCase()">
                                                </div>
                                            </div>
                                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">
                                                        Date de l'opération
                                                    </label>
                                                    <input type="date" class="form-control" id="date_ope" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class=" mb-3">
                                                    <label class="form-label" for="abc6">Motif</label>
                                                    <textarea style="resize: none;" class="form-control" id="libelle_ope" rows="3" oninput="this.value = this.value.toUpperCase()"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 mb-3">
                                                <div class="d-flex gap-2 justify-content-start">
                                                    <button id="btn_eng_ope" class="btn btn-success">
                                                        Enregistrer
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="twoAAA" role="tabpanel" aria-labelledby="tab-twoAAA">
                                <div class="card-header">
                                    <h5 class="card-title">Liste des Opérations de Caisse</h5>
                                </div>
                                <div class="card-header">
                                    <div class="row gx-3 mb-3">
                                        <div class="col-12">
                                            <div class=" mb-3">
                                                <div class="card-body">
                                                    <div class="row gx-3">
                                                        <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Du</label>
                                                                <input type="date" id="searchDate1" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Au</label>
                                                                <input type="date" id="searchDate2" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Type Mvt</label>
                                                                <select class="form-select me-1" id="statutTrace">
                                                                    <option selected value="tous">Tout</option>
                                                                    <option value="Entrer de Caisse">Entrer</option>
                                                                    <option value="Sortie de Caisse">Sortie</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Cassier(ère)</label>
                                                                <select class="form-select me-1 select2" id="userTrace"></select>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 text-center" >
                                                            <a id="btn_search_trace" class="btn btn-outline-success ms-auto">
                                                                <i class="ri-search-2-line"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body mb-3">
                                    <div class="">
                                        <div class="table-responsive">
                                            <table id="Table_day" class="table table-hover table-sm Table_OpC">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">N°</th>
                                                        <th scope="col">Créer par</th>
                                                        <th scope="col">Motif</th>
                                                        <th scope="col">Type de mouvement</th>
                                                        <th scope="col">Montant</th>
                                                        <th scope="col">Date d'opération</th>
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
                                <div class="card-header">
                                    <h5 class="card-title">Liste des ouvertures et fermetures de Caisse</h5>
                                </div>
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <div class="w-100">
                                        <div class="input-group">
                                            <span class="input-group-text">Du</span>
                                            <input type="date" id="searchDate1_ofc" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                                            <span class="input-group-text">au</span>
                                            <input type="date" id="searchDate2_ofc" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                                            <a id="btn_search_trace_ofc" class="btn btn-outline-success ms-auto">
                                                <i class="ri-search-2-line"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body mb-3">
                                    <div class="">
                                        <div class="table-responsive">
                                            <table id="Table_day" class="table table-hover table-sm Table_Ofc">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">N°</th>
                                                        <th scope="col">Motif</th>
                                                        <th scope="col">Solde Caisse</th>
                                                        <th scope="col">Auteur</th>
                                                        <th scope="col">Date de création</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tAAA" role="tabpanel" aria-labelledby="tab-tAAA">
                                <div class="row gx-3 justify-content-center align-items-center">
                                    <div class="col-xxl-4 col-sm-6">
                                        <div class="card-header">
                                            <h5 class="card-title">Bilan Journalier de la caisse</h5>
                                        </div>
                                        <div class="card-header d-flex align-items-center justify-content-between">
                                            <div class="w-100">
                                                <div class="input-group">
                                                    <span class="input-group-text">Date</span>
                                                    <input type="date" id="searchDate1_bj" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                                                    <a id="btn_search_trace_bj" class="btn btn-outline-success ms-auto">
                                                        <i class="ri-search-2-line"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="card-header">
                                                <h5 class="card-title">Bilan Journalier</h5>
                                            </div>
                                            <div class="scroll300">
                                                <div class="d-flex flex-column gap-2" id="historique_contenu">
                                                </div>
                                            </div>
                                            <div class="mt-3" id="historique_total">
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

<div class="modal fade" id="Detail" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-body" id="modal_detail"></div>
    </div>
</div>

<script src="{{asset('assets/js/app/js/jspdfinvoicetemplate/dist/index.js')}}" ></script>
<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>

@include('select2')

<script>
    $(document).ready(function() {

        caisse_verf();
        solde();
        select_user();
        historique();

        $("#btn_eng_ope").on("click", eng_ope);

        $("#btn_ouvert_C").on("click", caisse_ouvert);
        $("#btn_fermer_C").on("click", caisse_fermer);

        $("#btn_search_trace_bj").on("click", historique);

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
                        table_OpC.ajax.reload(null, false);
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

                        table_OpC.ajax.reload(null, false);
                        table_Ofc.ajax.reload(null, false);

                    } else if (response.error) {
                        showAlert('Alert', 'Une erreur est survenue lors de l\'ouverture de la caisse.', 'error');
                        console.log('message erreur controlleur : '+response.message);
                    }

                },
                error: function(xhr, status, error) {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    showAlert('Alert', 'Une erreur est survenue.','error');
                    let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Une erreur est survenue.';
                    console.log('message erreur controlleur : '+ errorMessage);
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

                        table_OpC.ajax.reload(null, false);
                        table_Ofc.ajax.reload(null, false);

                    } else if (response.error) {
                        showAlert('Alert','Une erreur est survenue lors de la fermeture de la caisse.','error');
                        console.log('message erreur controlleur : '+ response.message);
                    }

                },
                error: function(xhr, status, error) {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    showAlert('Alert', 'Une erreur est survenue.','error');
                    let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Une erreur est survenue.';
                    console.log('message erreur controlleur : '+ errorMessage);
                }
            });
        }

        var inputs = ['montant_ope'];
        inputs.forEach(function(id) {
            var inputElement = document.getElementById(id);

            inputElement.addEventListener('keypress', function(event) {
                const key = event.key;
                if (!/[0-9]/.test(key) && key !== 'Backspace' && key !== 'Delete') {
                    event.preventDefault();
                }
            });

            inputElement.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");;
            });
        });

        function showAlert(title, message, type) {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
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

        function select_user() {
            const selectElement = $('#userTrace');

            selectElement.empty();
            selectElement.append('<option selected value="tous">Tout</option>');

            $.ajax({
                url: '/api/list_caissier',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    const caissiers = data.caissier;

                    $.each(caissiers, function (index, item) {
                        const option = `<option value="${item.id}">${item.sexe}. ${item.name}</option>`;
                        selectElement.append(option);
                    });
                },
                error: function (xhr, status, error) {
                    console.error('Erreur lors du chargement des caissiers:', error);
                }
            });
        }

        function solde() 
        {

            const solde_caisse = document.getElementById("solde_caisse");

            $.ajax({
                url: '/api/montant_solde',
                method: 'GET',
                success: function(response) {
                    solde_caisse.textContent = response.solde.solde + ' Fcfa';
                },
                error: function() {
                    solde_caisse.textContent = '0 Fcfa';
                }
            });
        }

        function eng_ope()
        {
            const auth_id = {{ Auth::user()->id }};
            const type_ope = document.getElementById("type_ope");
            const montant_ope = document.getElementById("montant_ope");
            const nom_ope = document.getElementById("nom_ope");
            const libelle_ope = document.getElementById("libelle_ope");
            const date_ope = document.getElementById("date_ope");

            if(!type_ope.value.trim() || !montant_ope.value.trim() || !libelle_ope.value.trim() || !nom_ope.value.trim() || !date_ope.value.trim())
            {
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.', 'warning');
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
                url: '/api/ope_caisse_new',
                method: 'GET',
                data: {
                    auth_id: auth_id,
                    type_ope: type_ope.value,
                    montant_ope: montant_ope.value,
                    nom_ope: nom_ope.value,
                    libelle_ope: libelle_ope.value,
                    date_ope: date_ope.value,
                },
                success: function(response) {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    caisse_verf();

                    if (response.success) {

                        type_ope.value = '';
                        montant_ope.value = '';
                        nom_ope.value = '';
                        libelle_ope.value = '';
                        date_ope.value = '{{ date('Y-m-d') }}';

                        solde();
                        table_OpC.ajax.reload(null, false);
                        table_Ofc.ajax.reload(null, false);

                        showAlert('Succès', 'Opération éffectuée.', 'success');
                    } else if (response.error) {

                        showAlert('Alert', 'Echec de l\'opération.','error');
                    } else if (response.solde_negatif) {
                        
                        showAlert('Alert', 'Le montant de l\'opération est supérieur au montant actuel de la caisse.','warning');
                    } else if (response.caisse_fermer) {
                        showAlert('Alert', 'La caisse est actuellement fermer, Veuillez ouvrir la caisse avant d\'éffectuer un encaissement.','info');
                    }

                },
                error: function() {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    showAlert('Alert', 'Une erreur est survenue lors de l\'enregistrement.', 'error');
                }
            });
        }

        const table_OpC = $('.Table_OpC').DataTable({

            processing: false,
            serverSide: false,
            ajax: function(data, callback) {

                const date1 = $('#searchDate1').val();
                const date2 = $('#searchDate2').val();
                const typemvt = $('#statutTrace').val();
                const user_id = $('#userTrace').val();

                if (!date1.trim() || !date2.trim()) {
                    showAlert('Alert', 'Tous les champs sont obligatoires.','warning');
                    return false; 
                }

                const startDate = new Date(date1);
                const endDate = new Date(date2);

                if (startDate > endDate) {
                    showAlert('Erreur', 'La date de début ne peut pas être supérieur à la date de fin.', 'error');
                    return false;
                }

                const oneYearInMs = 365 * 24 * 60 * 60 * 1000;
                if (endDate - startDate > oneYearInMs) {
                    showAlert('Erreur', 'La plage de dates ne peut pas dépasser un an.', 'error');
                    return false;
                }
                
                $.ajax({
                    url: `/api/trace_operation/${date1}/${date2}/${typemvt}/${user_id}`,
                    type: 'GET',
                    success: function(response) {
                        callback({ data: response.data });
                    },
                    error: function() {
                        console.log('Error fetching data. Please check your API or network trace_operation.');
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
                    data: 'user',
                    render: function (data, type, row) { return `${row.user_sexe}. ${row.user}` },
                    searchable: true, 
                },
                { 
                    data: 'motif',
                    searchable: true,
                },
                { 
                    data: 'typemvt',
                    searchable: true, 
                },
                {
                    data: 'typemvt',
                    searchable: true,
                    render: function (data, type, row) {
                        if (data === 'Entrer de Caisse') {
                            return `<span class="fs-6 badge bg-success-subtle text-success">
                                        + ${row.montant} Fcfa
                                    </span>`;
                        } else {
                            return `<span class="fs-6 badge bg-danger-subtle text-danger">
                                        - ${row.montant} Fcfa
                                    </span>`;
                        }
                    }
                },
                {
                    data: 'date_ope',
                    searchable: false,
                    render: function (data, type, row) {
                        const date = data === '' ? row.created_at : data;
                        return `<td>${formatDate(date)}</td>`;
                    }
                },
                { 
                    data: 'created_at', 
                    render: formatDateHeure,
                    searchable: true, 
                },
                {
                    data: null,
                    render: (data, type, row) => `
                        <div class="d-inline-flex gap-1" style="font-size:10px;">
                            <a class="btn btn-outline-warning btn-sm rounded-5" 
                                data-bs-toggle="modal" 
                                data-bs-target="#Detail" 
                                id="detail"
                                data-typemvt="${row.typemvt}"
                                data-montant="${row.montant}"
                                data-motif="${row.motif}"
                                data-solde_avant="${row.solde_avant}"
                                data-solde_apres="${row.solde_apres}"
                                data-user_sexe="${row.user_sexe}"
                                data-user="${row.user}"
                                data-date_ope="${row.date_ope}"
                                data-created_at="${row.created_at}"
                                data-libelle="${row.libelle}">
                                <i class="ri-eye-line"></i>
                            </a>
                        </div>
                    `,
                    searchable: false,
                    orderable: false,
                }
            ],
            ...dataTableConfig,
            initComplete: function(settings, json) {
                initializeRowEventListenersTable_OpC();
            },
        });

        function initializeRowEventListenersTable_OpC() {

            $('.Table_OpC').on('click', '#detail', function() {
                const id = $(this).data('id');
                const typemvt = $(this).data('typemvt');
                const montant = $(this).data('montant');
                const motif = $(this).data('motif');
                const solde_avant = $(this).data('solde_avant');
                const solde_apres = $(this).data('solde_apres');
                const user_sexe = $(this).data('user_sexe');
                const user = $(this).data('user');

                const date_ope = $(this).data('date_ope');
                const created_at = $(this).data('created_at');
                const libelle = $(this).data('libelle');
                
                const modal = document.getElementById('modal_detail');
                modal.innerHTML = '';

                const div = document.createElement('div');
                div.innerHTML = `
                       <div class="row gx-3">
                            <div class="col-12">
                                <div class=" mb-3">
                                    <div class="card-body">
                                        <ul class="list-group">
                                            <li class="list-group-item active text-center" aria-current="true">
                                                Informations de l'operation
                                            </li>
                                            <li class="list-group-item">
                                                Type de Mouvement : ${typemvt}
                                            </li>
                                            <li class="list-group-item ${typemvt == 'Entrer de Caisse' ? 'text-success' : 'text-danger' }">
                                                Montant : ${typemvt == 'Entrer de Caisse' ? '+ '+montant : '- '+montant } Fcfa
                                            </li>
                                            <li class="list-group-item">
                                                Motif : ${motif}
                                            </li>
                                            <li class="list-group-item text-primary">
                                                Solde avant opération : ${solde_avant} Fcfa
                                            </li>
                                            <li class="list-group-item text-primary">
                                                Solde après opération : ${solde_apres} Fcfa
                                            </li>
                                            <li class="list-group-item">
                                                Créer par : ${user_sexe}. ${user} 
                                            </li>
                                            <li class="list-group-item">
                                                Date de l'opération : ${date_ope == `` ? `${formatDate(created_at)}` : `${formatDate(date_ope)}` }
                                            </li>
                                            <li class="list-group-item">
                                                Date de création : ${formatDateHeure(created_at)}
                                            </li>
                                            <li class="list-group-item">
                                                Libelle : ${libelle}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>     
                `;

                modal.appendChild(div);
            });

        }

        $('#btn_search_trace').on('click', function() {
            table_OpC.ajax.reload(null, false); 
        });

        const table_Ofc = $('.Table_Ofc').DataTable({

            processing: false,
            serverSide: false,
            ajax: function(data, callback) {

                const date1 = $('#searchDate1_ofc').val();
                const date2 = $('#searchDate2_ofc').val();

                if (!date1.trim() || !date2.trim()) {
                    showAlert('Alert', 'Tous les champs sont obligatoires.','warning');
                    return false; 
                }

                const startDate = new Date(date1);
                const endDate = new Date(date2);

                if (startDate > endDate) {
                    showAlert('Erreur', 'La date de début ne peut pas être supérieur à la date de fin.', 'error');
                    return false;
                }

                const oneYearInMs = 365 * 24 * 60 * 60 * 1000;
                if (endDate - startDate > oneYearInMs) {
                    showAlert('Erreur', 'La plage de dates ne peut pas dépasser un an.', 'error');
                    return false;
                }
                
                $.ajax({
                    url: `/api/trace_ouvert_fermer/${date1}/${date2}`,
                    type: 'GET',
                    success: function(response) {
                        callback({ data: response.data });
                    },
                    error: function() {
                        console.log('Error fetching data. Please check your API or network trace_ouvert_fermer.');
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
                    data: 'motif',
                    render: function(data, type, row) {
                        if (data === 'OUVERTURE DE CAISSE') {
                            return `<span class="fs-6 badge bg-success-subtle text-success">${data}</span>`;
                        } else {
                            return `<span class="fs-6 badge bg-danger-subtle text-danger">${data}</span>`;
                        }
                    },
                    searchable: true
                },
                {
                    data: 'montant',
                    render: function(data, type, row) {
                        return `${data} Fcfa`;
                    },
                    searchable: true
                },
                {
                    data: null, // Combine `user_sexe` and `user` fields
                    render: function(data, type, row) {
                        return `${row.user_sexe}. ${row.user}`;
                    },
                    searchable: false
                },
                {
                    data: 'created_at',
                    render: function(data, type, row) {
                        return formatDateHeure(data); // Utilise votre fonction de formatage
                    },
                    searchable: false
                }
            ],
            ...dataTableConfig,
        });

        $('#btn_search_trace_ofc').on('click', function() {
            table_Ofc.ajax.reload(null, false); 
        });

        function historique() {

            const date = document.getElementById('searchDate1_bj').value;

            const contenu_total = document.getElementById('historique_total');

            const contenu = document.getElementById('historique_contenu');
            contenu.innerHTML = '';

            var preloader = `
                <div class="d-flex justify-content-center align-items-center" id="laoder_stat">
                    <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                    <strong>Chargement des données...</strong>
                </div>
            `;
            contenu.innerHTML = preloader;

            const url = `/api/historique_caisse/${date}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const traces = data.trace || [] ;
                    const ofcs = data.ofc || [] ;
                    const total = data.total ;

                    const combinedData = [...traces, ...ofcs].sort((a, b) => new Date(a.created_at) - new Date(b.created_at));

                    contenu.innerHTML = '';

                    if (combinedData.length > 0) {

                        combinedData.forEach((item) => {
                            // Si typemvt n'existe pas, utiliser motif comme typemvt
                            const typemvt = item.typemvt || item.motif || 'Motif non spécifié';

                            let borderClass = '';
                            let iconClass = '';
                            let textClass = '';

                            if (typemvt === 'Entrer de Caisse') {
                                borderClass = 'border-success';
                                iconClass = 'ri-arrow-right-up-line';
                                textClass = 'text-success';
                            } else if (typemvt === 'Sortie de Caisse') {
                                borderClass = 'border-danger';
                                iconClass = 'ri-arrow-right-down-line';
                                textClass = 'text-danger';
                            } else if (typemvt === 'OUVERTURE DE CAISSE') {
                                borderClass = 'border-primary';
                                iconClass = 'ri-door-open-line';
                                textClass = 'text-primary';
                            } else if (typemvt === 'FERMETURE DE CAISSE') {
                                borderClass = 'border-warning';
                                iconClass = 'ri-door-closed-line';
                                textClass = 'text-warning';
                            }

                            const div = document.createElement('div');
                            div.className = "p-3 border rounded-2";
                            div.innerHTML = `
                                <div class="d-flex gap-3 mb-3">
                                    <i class="border ${borderClass} p-1 rounded-3 ${iconClass} fs-1 ${textClass} lh-1"></i>
                                    <div>
                                        <h5 class="${textClass}">
                                            ${
                                                typemvt === 'Entrer de Caisse'
                                                    ? '+ ' + item.montant + ' Fcfa'
                                                    : typemvt === 'Sortie de Caisse'
                                                    ? '- ' + item.montant + ' Fcfa'
                                                    : item.montant + ' Fcfa'
                                            }
                                        </h5>
                                        <p class="opacity-100 m-0">
                                            ${item.motif || 'Motif non spécifié'}.
                                        </p>
                                    </div>
                                </div>
                                <p>
                                    Créer par David kouachi à 23H50
                                </p>
                            `;
                            contenu.appendChild(div);
                        });


                        contenu_total.innerHTML = '';

                        const divT = `
                            <h5 class="card-title">Montant Total : ${formatPrice(total)} Fcfa</h5>   
                        `;
                        contenu_total.innerHTML = divT;


                    } else {
                        
                        var message = `
                            <div class="d-flex justify-content-center align-items-center" id="historique_message">
                                <strong>Aucune données n'a été trouvées</strong>
                            </div>
                        `;
                        contenu.innerHTML = message;
                        contenu_total.innerHTML = '';

                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données:', error);
                });
        }

    });
</script>

@endsection
