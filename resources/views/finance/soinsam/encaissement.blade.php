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
            Encaissement factures non-réglées
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">

    <div class="row justify-content-center" id="div_caisse_verf" style="display: none;">
        <div class="col-12">
            <div class="card mb-3">
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
        @include('finance.btnFacNonRegle')
        
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title">
                            Soins Amulatoires factures non-réglées
                        </h5>
                        <div class="d-flex" >
                            <input type="text" id="searchInput" placeholder="N° facture" class="form-control me-1" >
                            <a id="btn_refresh_table" class="btn btn-outline-info ms-auto">
                                <i class="ri-loop-left-line"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-outer" id="div_Table" style="display: none;">
                            <div class="table-responsive">
                                <table class="table align-middle table-hover m-0 truncate" id="Table">
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
                        <div id="message_Table" style="display: none;">
                            <p class="text-center" >
                                Aucune facture disponible
                            </p>
                        </div>
                        <div id="div_Table_loader" style="display: none;">
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                                <strong>Chargement des données...</strong>
                            </div>
                        </div>
                        <div id="pagination-controls" ></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="Detail" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">
                    Détails
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal_detail">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Detail_produit" tabindex="-1" aria-modal="true" role="dialog">
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
                                        <div class="table-responsive" id="div_TableP" style="display: none;">
                                            <!-- Tableau Soins Infirmiers -->
                                            <table class="table table-bordered" id="TableP">
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

                                        <div class="table-responsive" id="div_TableProdP" style="display: none;">
                                            <!-- Tableau Produits Utilisés -->
                                            <table class="table table-bordered" id="TableProdP">
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

                                        <div id="message_TableP" style="display: none;">
                                            <p class="text-center" >
                                                Aucun détail pour le moment
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

<div class="modal fade" id="Caisse" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel">
                    Caisse
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row gx-3">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">A payer</label>
                            <input readonly class="form-control" id="input_montant_payer">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Montant versé</label>
                            <div class="input-group">
                                <input type="tel" class="form-control" id="input_montant_verser" placeholder="Saisie Obligatoire">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Montant Remis</label>
                            <div class="input-group">
                                <input readonly type="tel" class="form-control" id="input_montant_remis" placeholder="Saisie Obligatoire">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="div_btn_valider" style="display: none;">
                <input type="hidden" id="id_code_fac">
                <button data-bs-dismiss="modal" class="btn btn-success" id="btn_valider" >
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

        caisse_verf();

        document.getElementById("btn_refresh_table").addEventListener("click", list);
        document.getElementById("btn_valider").addEventListener("click", payer);

        document.getElementById("btn_ouvert_C").addEventListener("click", caisse_ouvert);
        document.getElementById("btn_fermer_C").addEventListener("click", caisse_fermer);

        // ->----------------------------

        document.getElementById('input_montant_verser').addEventListener('input', function() {
            // Nettoyer la valeur entrée en supprimant les caractères non numériques sauf le point
            const rawValue = this.value.replace(/[^0-9]/g, ''); // Supprimer tous les caractères non numériques
            
            // Ajouter des points pour les milliers
            const formattedValue = formatPrice(rawValue);
            
            // Mettre à jour la valeur du champ avec la valeur formatée
            this.value = formattedValue;

            // Convertir la valeur formatée en nombre pour les calculs
            const montantPayer = parseFloat(document.getElementById('input_montant_payer').value.replace(/\./g, '')) || 0;
            const montantVerser = parseFloat(rawValue) || 0;

            // Calculer le montant remis
            const montantRemis = montantVerser - montantPayer;
            document.getElementById('input_montant_remis').value = `${formatPrice(montantRemis)}`;

            const btnValider = document.getElementById('div_btn_valider');
            if (montantRemis >= 0) {
                btnValider.style.display = 'block';
            } else {
                btnValider.style.display = 'none';
            }
        });

        document.getElementById('input_montant_verser').addEventListener('keypress', function(event) {
            // Permettre uniquement les chiffres et le point
            const key = event.key;
            if (isNaN(key)) {
                event.preventDefault();
            }
        });
        
        // -----------------------------------------

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
                        list();
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

                        list();

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

        function showAlert(title, message, type) {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
            });
        }

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

        function payer()
        {
            const auth_id = {{ Auth::user()->id }};
            const code_fac = document.getElementById("id_code_fac").value;
            const montant_verser = document.getElementById("input_montant_verser");
            const montant_remis = document.getElementById("input_montant_remis");

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

                        list();

                        generatePDFInvoice(soinspatient, facture, patient, typesoins, soins, produit);

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

        function list(page = 1) {

            const tableBody = document.querySelector('#Table tbody');
            const messageDiv = document.getElementById('message_Table');
            const tableDiv = document.getElementById('div_Table');
            const loaderDiv = document.getElementById('div_Table_loader');
            const searchInput = document.getElementById('searchInput');

            let allFactures = []; // Array to hold all factures data fetched from API

            messageDiv.style.display = 'none';
            tableDiv.style.display = 'none';
            loaderDiv.style.display = 'block';

            // Fetch data from the API
            const url = `/api/list_facture_soinsam?page=${page}`;
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    allFactures = data.soinspatient || [];
                    const pagination = data.pagination || {};

                    const perPage = pagination.per_page || 10;
                    const currentPage = pagination.current_page || 1;

                    if (allFactures.length > 0) {

                        loaderDiv.style.display = 'none';
                        messageDiv.style.display = 'none';
                        tableDiv.style.display = 'block';

                        // Display rows function with optional filtering
                        function displayRows(filteredFactures) {
                            tableBody.innerHTML = ''; // Clear existing rows

                            filteredFactures.forEach((item, index) => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td>${((currentPage - 1) * perPage) + index + 1}</td>
                                    <td>
                                        <div class="d-flex align-items-center ">
                                            <a class="d-flex align-items-center flex-column me-2">
                                                <img src="{{asset('assets/images/facture.webp')}}" class="img-2x rounded-circle border border-1">
                                            </a>
                                            ${item.code_fac}
                                        </div>
                                    </td>
                                    <td class="text-warning">
                                        ${item.montant ?? 0} Fcfa
                                    </td>
                                    <td class="text-primary">
                                        ${formatPrice(item.prototal) ?? 0} Fcfa
                                    </td>
                                    <td class="text-primary">
                                        ${formatPrice(item.stotal) ?? 0} Fcfa
                                    </td>
                                    <td class="text-success">
                                        ${item.part_patient ?? 0} Fcfa
                                    </td>
                                    <td class="text-primary">
                                        ${item.part_assurance ?? 0} Fcfa
                                    </td>
                                    <td class="text-primary">
                                        ${item.remise ?? 0} Fcfa
                                    </td>
                                    <td>${formatDateHeure(item.created_at)}</td>
                                    <td>
                                        <div class="d-inline-flex gap-1">
                                            <a class="btn btn-outline-success btn-sm rounded-5" data-bs-toggle="modal" data-bs-target="#Caisse" id="paye-${item.id}">
                                                <i class="ri-hand-coin-line"></i>
                                            </a>
                                            <a class="btn btn-outline-warning btn-sm rounded-5" id="detail-${item.id}" data-bs-toggle="modal" data-bs-target="#Detail">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a class="btn btn-outline-danger btn-sm rounded-5" id="detail_produit-${item.id}" data-bs-toggle="modal" data-bs-target="#Detail_produit">
                                                <i class="ri-archive-2-fill"></i>
                                            </a>
                                            <a class="btn btn-outline-info btn-sm rounded-5" id="printer-${item.id}">
                                                <i class="ri-printer-line"></i>
                                            </a>
                                        </div>
                                    </td>
                                `;
                                tableBody.appendChild(row);

                                // Add event listeners
                                document.getElementById(`paye-${item.id}`).addEventListener('click', () => {
                                    const payer = document.getElementById('input_montant_payer');
                                    payer.value = `${item?.part_patient || 0} Fcfa`;

                                    const verser = document.getElementById('input_montant_verser');
                                    verser.value = '';
                                    document.getElementById('input_montant_remis').value = '0 Fcfa';

                                    document.getElementById('id_code_fac').value = `${item.code_fac}`;
                                });

                                document.getElementById(`detail-${item.id}`).addEventListener('click', () =>
                                {
                                    fetch(`/api/detail_soinam/${item.id}`) // API endpoint
                                        .then(response => response.json())
                                        .then(data => {
                                            // Access the 'chambre' array from the API response
                                            const modal = document.getElementById('modal_detail');
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

                                document.getElementById(`detail_produit-${item.id}`).addEventListener('click',()=>
                                {
                                    const tableBodyP = document.querySelector('#TableP tbody');
                                    const tableBodyProdP=document.querySelector('#TableProdP tbody');
                                    const messageDivP = document.getElementById('message_TableP');
                                    const tableDivP = document.getElementById('div_TableP');
                                    const tableDivProdP = document.getElementById('div_TableProdP');
                                    const loaderDivP = document.getElementById('div_Table_loaderP');

                                    messageDivP.style.display = 'none';
                                    tableDivP.style.display = 'none';
                                    tableDivProdP.style.display = 'none';
                                    loaderDivP.style.display = 'block';

                                    fetch(`/api/detail_soinam/${item.id}`)
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
                                                            Total Soins : ${formatPriceT(soinspatient.stotal)} Fcfa
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
                                                            Total Produits : ${formatPriceT(soinspatient.prototal)} Fcfa
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

                                document.getElementById(`printer-${item.id}`).addEventListener('click', () =>
                                {
                                    fetch(`/api/detail_soinam/${item.id}`) // API endpoint
                                        .then(response => response.json())
                                        .then(data => {
                                            // Access the 'chambre' array from the API response
                                            const soinspatient = data.soinspatient;
                                            const facture = data.facture;
                                            const patient = data.patient;
                                            const typesoins = data.typesoins;
                                            const soins = data.soins;
                                            const produit = data.produit;

                                            generatePDFInvoice(soinspatient, facture, patient, typesoins, soins, produit);

                                        })
                                        .catch(error => {
                                            console.error('Erreur lors du chargement des données:', error);
                                        });
                                });
                            });
                        }

                        // Update table with filtered factures
                        function applySearchFilter() {
                            const searchTerm = searchInput.value.toLowerCase();
                            const filteredFactures = allFactures.filter(facture =>
                                facture.code_fac.toLowerCase().includes(searchTerm)
                            );
                            displayRows(filteredFactures); // Display only filtered factures
                        }

                        searchInput.addEventListener('input', applySearchFilter);

                        displayRows(allFactures);

                        updatePaginationControls(pagination);

                    } else {
                        loaderDiv.style.display = 'none';
                        messageDiv.style.display = 'block';
                        tableDiv.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données:', error);
                    loaderDiv.style.display = 'none';
                    messageDiv.style.display = 'block';
                    tableDiv.style.display = 'none';
                });
        }

        function updatePaginationControls(pagination) {
            const paginationDiv = document.getElementById('pagination-controls');
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
                    list(pagination.current_page - 1);
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
                    list(i);
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
                    list(totalPages);
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
                    list(pagination.current_page + 1);
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

        function generatePDFInvoice(soinspatient, facture, patient, typesoins, soins, produit) {
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
                const titleR = "FACTURE SOINS AMBULATOIRES";
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