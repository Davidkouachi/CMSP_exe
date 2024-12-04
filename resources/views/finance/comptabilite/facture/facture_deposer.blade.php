@extends('app')

@section('titre', 'Nouvel Acte')

@section('info_page')
<div class="app-hero-header d-flex align-items-center">
    <!-- Breadcrumb starts -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-bar-chart-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{route('index_accueil')}}">Espace Santé</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            Factures
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
                        <h5>Factures Déposer</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title">
                        Liste des Dépôts
                    </h5>
                </div>
                {{-- <div class="card-header d-flex align-items-center justify-content-between" style="display: none;">
                    <div class="w-100">
                        <div class="input-group">
                            <span class="input-group-text">Du</span>
                            <input type="date" id="searchDate1" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                            <span class="input-group-text">au</span>
                            <input type="date" id="searchDate2" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                            <span class="input-group-text">Statut</span>
                            <select class="form-select me-1" id="statut">
                                <option selected value="tous">Tous</option>
                                <option value="oui">Réglée</option>
                                <option value="non">Non Réglée</option>
                            </select>
                            <a id="btn_search_table" class="btn btn-outline-success ms-auto">
                                <i class="ri-search-2-line"></i>
                            </a>
                        </div>
                    </div>
                </div> --}}
                <div class="card-body">
                    <div class="">
                        <div class="table-responsive">
                            <table id="Table_day" class="table truncate m-0 align-middle ">
                                <thead>
                                    <tr class="bg-primary" >
                                        <th scope="col">N°</th>
                                        <th scope="col">Assurance</th>
                                        <th scope="col">Du</th>
                                        <th scope="col">Au</th>
                                        <th scope="col">Date du dépôt</th>
                                        <th scope="col">Statut</th>
                                        <th scope="col">Part assurance</th>
                                        <th scope="col">Part patient</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">date de création</th>
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

<div class="modal fade" id="Mdelete" tabindex="-1" aria-labelledby="delRowLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delRowLabel">
                    Confirmation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Voulez-vous vraiment supprimé cet dépôt ?
                <input type="hidden" id="Iddelete">
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

<div class="modal fade" id="Paiement" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >
                    Détail paiement
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="IdPaiement">
                <div class="row gx-3">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Date du dépôt</label>
                            <input readonly type="date" class="form-control" id="date_depotP">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Date du paiement</label>
                            <input type="date" class="form-control" id="date_payer" max="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Type de payement</label>
                            <select class="form-select" id="type_payer">
                                <option value="">Selectionner</option>
                                <option value="virement">Par Virement Bancaire</option>
                                <option value="cheque">Par Chèque</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12" id="div_num_cheque" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Numéro du Chèque</label>
                            <div class="input-group">
                                <span class="input-group-text">N°</span>
                                <input type="tel" class="form-control" id="num_cheque_payer" placeholder="Saisie Obligatoire">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btn_paiement" >
                    Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Mmodif" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mise à jour</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="updateChambreForm">
                    <input type="hidden" id="IdModif">
                    <input type="hidden" id="assurance_idM">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Du</label>
                            <input type="date" class="form-control" id="date1M" max="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Au</label>
                            <input type="date" class="form-control" id="date2M" max="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Date de dépôt</label>
                            <input type="date" class="form-control" id="date_depotM" max="{{ date('Y-m-d') }}">
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

<script src="{{asset('assets/js/app/js/jspdfinvoicetemplate/dist/index.js')}}" ></script>
<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>
<script src="{{asset('jsPDF-AutoTable/dist/jspdf.plugin.autotable.min.js')}}"></script>

<script>
    $(document).ready(function() {

        $("#btn_paiement").on("click", eng_paiement);
        $("#updateBtn").on("click", updatee);
        $("#deleteBtn").on("click", deletee);
        $("#type_payer").on("change", num_cheque);

        $('#btn_search_table').on('click', function () {
            $('#Table_day').DataTable().ajax.reload();
        });

        $('#num_cheque_payer').on('input', function()
        {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        function showAlert(title, message, type)
        {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
            });
        }

        function isValidDate(dateString) {
            const regEx = /^\d{4}-\d{2}-\d{2}$/;
            if (!dateString.match(regEx)) return false;
            const date = new Date(dateString);
            const timestamp = date.getTime();
            if (typeof timestamp !== 'number' || isNaN(timestamp)) return false;
            return dateString === date.toISOString().split('T')[0];
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

        function datechangeM() 
        {
            const date1Value = $('#date1M').val();
            const $date2 = $('#date2M');

            $date2.val(date1Value);
            $date2.attr('min', date1Value);
        }

        function num_cheque() {
            const paymentType = $('#type_payer').val();
            $('#num_cheque_payer').val("");

            if (paymentType === 'virement') {
                $('#div_num_cheque').hide();
            } else if (paymentType === 'cheque') {
                $('#div_num_cheque').show();
            } else {
                $('#div_num_cheque').hide();
            }
        }

        $('#Table_day').DataTable({

            processing: true,
            serverSide: false,
            ajax: {
                url: `/api/list_depotfacture`,
                type: 'GET',
                dataSrc: 'data',
                error: function(xhr, status, error) {
                    console.error("Erreur AJAX :", error);
                    console.error("Détails :", xhr.responseText);
                }
            },
            columns: [
                { 
                    data: null, 
                    render: (data, type, row, meta) => meta.row + 1,
                    searchable: false,
                    orderable: false,
                },
                { 
                    data: 'assurance', render: (data) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{ asset('/assets/images/depot_fac.jpg') }}" class="img-2x rounded-circle border border-1">
                        </a>
                        ${data}
                    </div>
                    `,
                    searchable: true,
                },
                { 
                    data: 'date1', 
                    render: formatDate, 
                    searchable: true,
                },
                { 
                    data: 'date2', 
                    render: formatDate, 
                    searchable: true,
                },
                { 
                    data: 'date_depot', 
                    render: formatDate, 
                    searchable: true,
                },
                { 
                    data: 'statut', 
                    render: (data) => `<span class="badge ${data === 'oui' ? 'bg-success' : 'bg-danger'}">${data === 'oui' ? 'Réglée' : 'Non Réglée'}</span>`,
                    searchable: true,
                },
                { 
                    data: 'part_assurance', 
                    render: (data) => `${data} Fcfa`, 
                    searchable: true, 
                },
                { 
                    data: 'part_patient', 
                    render: (data) => `${data} Fcfa`, 
                    searchable: true, 
                },
                { 
                    data: 'total', 
                    render: (data) => `${data} Fcfa`, 
                    searchable: true, 
                },
                { 
                    data: 'created_at', 
                    render: formatDateHeure,
                    searchable: true, 
                },
                {
                    data: null,
                    render: (data, type, row) => `
                        <div class="d-inline-flex gap-1">
                            ${row.statut === 'non' ? `<a class="btn btn-outline-success " data-bs-toggle="modal" data-bs-target="#Paiement" id="paiement" data-date_depot="${row.date_depot}" data-id="${row.id}"><i class="ri-inbox-archive-line"></i></a>` : ''}
                            <a class="btn btn-outline-warning " id="detail" data-id="${row.id}" ><i class="ri-eye-line"></i></a>
                            ${row.statut === 'non' ? `<a class="btn btn-outline-info " data-bs-toggle="modal" data-bs-target="#Mmodif" id="modif" data-date_depot="${row.date_depot}" data-date1="${row.date1}" data-date2="${row.date2}" data-id="${row.id}" data-assurance_id="${row.assurance_id}" ><i class="ri-edit-line"></i></a>` : ''}
                            ${row.statut === 'non' ? `<a class="btn btn-outline-danger " data-bs-toggle="modal" data-bs-target="#Mdelete" id="delete" data-id="${row.id}" ><i class="ri-delete-bin-line"></i></a>` : ''}
                            <a class="btn btn-outline-dark " id="printer" data-id="${row.id}" ><i class="ri-printer-line"></i></a>
                        </div>
                    `,
                    searchable: false,
                    orderable: false,
                }
            ],
            ...dataTableConfig, 
            initComplete: function(settings, json) {
                initializeRowEventListeners();
                dataTableConfigInit.call(this);
            },
        });

        function initializeRowEventListeners() {
            $('#Table_day').on('click', '#paiement', function() {
                const id = $(this).data('id');
                const date_depot = $(this).data('date_depot');
                
                document.getElementById('date_payer').value = "";
                document.getElementById('type_payer').value = "";
                document.getElementById('num_cheque_payer').value = "";
                document.getElementById('IdPaiement').value = id;
                document.getElementById('date_depotP').value = date_depot;
            });

            $('#Table_day').on('click', '#detail', function() {
                const id = $(this).data('id');
                
                var preloader_ch = `
                    <div id="preloader_ch">
                        <div class="spinner_preloader_ch"></div>
                    </div>
                `;

                document.body.insertAdjacentHTML('beforeend', preloader_ch);

                fetch(`/api/imp_fac_depot/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        var preloader = document.getElementById('preloader_ch');
                        if (preloader) preloader.remove();
                        const societes = data.societes;
                        const assurance = data.assurance;
                        const date1 = data.date1;
                        const date2 = data.date2;
                        if (societes.length > 0) {
                            generatePDFInvoice_Fac(societes, assurance, date1, date2);
                        } else {
                            showAlert('Informations', 'Aucune donnée trouvée pour cette période', 'info');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                    });
            });

            $('#Table_day').on('click', '#modif', function() {
                const id = $(this).data('id');
                const date_depot = $(this).data('date_depot');
                const date1 = $(this).data('date1');
                const date2 = $(this).data('date2');
                const assurance_id = $(this).data('assurance_id');

                document.getElementById('date1M').value = date1;
                document.getElementById('date2M').value = date2;
                document.getElementById('date_depotM').value = date_depot;
                document.getElementById('IdModif').value = id;
                document.getElementById('assurance_idM').value = assurance_id;
            });

            $('#Table_day').on('click', '#delete', function() {
                const id = $(this).data('id');
                
                document.getElementById('Iddelete').value = id;
            });

            $('#Table_day').on('click', '#printer', function() {
                const id = $(this).data('id');
                
                var preloader_ch = `
                    <div id="preloader_ch">
                        <div class="spinner_preloader_ch"></div>
                    </div>
                `;

                document.body.insertAdjacentHTML('beforeend', preloader_ch);

                fetch(`/api/imp_fac_depot_bordo/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        var preloader = document.getElementById('preloader_ch');
                        if (preloader) preloader.remove();
                        const societes = data.societes;
                        const assurance = data.assurance;
                        const date1 = data.date1;
                        const date2 = data.date2;
                        const statut = data.statut;
                        const date_paiement = formatDate(data.date_payer);
                        const type = data.type_paiement;
                        const cheque = data.num_cheque;
                        if (societes.length > 0) {
                            generatePDFInvoice_Fac_Bordo(societes, assurance, date1, date2, statut, date_paiement, type, cheque);
                        } else {
                            showAlert('Informations', 'Aucune donnée trouvée pour cette période', 'info');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                    });
            });
        }

        function eng_paiement() {
            
            const auth_id = {{ Auth::user()->id }};
            const id = $('#IdPaiement').val();
            const date_depot = $('#date_depotP').val();
            const date = $('#date_payer').val();
            const type = $('#type_payer').val();
            const cheque = $('#num_cheque_payer').val();

            if (!date.trim() || !type.trim()) {
                showAlert('Alert', 'Tous les champs sont obligatoires.', 'warning');
                return false;
            }

            if (type === 'cheque' && !cheque.trim()) {
                showAlert('Alert', 'Tous les champs sont obligatoires.', 'warning');
                return false;
            }

            if (!isValidDate(date)) {
                showAlert('Erreur', 'La date de paiement est invalide.', 'error');
                return false;
            }

            const pDate = new Date(date);
            const Datedepot = new Date(date_depot);

            if (pDate < Datedepot) {
                showAlert('Erreur', 'La date de paiement ne peut pas être inférieure à la date du dépôt.', 'error');
                return false;
            }

            const modal = bootstrap.Modal.getInstance(document.getElementById('Paiement'));
            modal.hide();

            const preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $('body').append(preloader_ch);

            $.ajax({
                url: '/api/paiement_depot_fac/' + id,
                method: 'GET',
                data: {
                    date: date,
                    type: type,
                    cheque: cheque || null,
                    auth_id: auth_id,
                },
                success: function(response) {
                    $('#preloader_ch').remove();

                    if (response.success) {
                        $('#date_payer').val('');
                        $('#type_payer').val('').trigger('change');
                        $('#num_cheque_payer').val('');

                        $('#Table_day').DataTable().ajax.reload();
                        showAlert('Succès', 'Opération effectuée', 'success');

                    } else if (response.error) {
                        showAlert('Informations', 'Échec de l\'opération', 'info');

                    } else if (response.non_touve) {
                        showAlert('Information', 'Échec de l\'opération: Dépôt introuvable.', 'info');
                    }
                },
                error: function() {
                    $('#preloader_ch').remove();
                    showAlert('Alert', 'Une erreur est survenue.', 'error');
                }
            });
        }

        function updatee()
        {
            const id = document.getElementById('IdModif').value;
            const assurance_id = document.getElementById('assurance_idM');
            const date1 = document.getElementById('date1M');
            const date2 = document.getElementById('date2M');
            const date_depot = document.getElementById('date_depotM');

            if (!date1.value.trim() || !date2.value.trim() || !date_depot.value.trim()) {
                showAlert('Alert', 'Tous les champs sont obligatoires.','warning');
                return false; 
            }

            if (!isValidDate(date1.value)) {
                showAlert('Erreur', 'La première date est invalide.', 'error');
                return false;
            }

            if (!isValidDate(date2.value)) {
                showAlert('Erreur', 'La deuxième date est invalide.', 'error');
                return false;
            }

            if (!isValidDate(date_depot.value)) {
                showAlert('Erreur', 'La deuxième date est invalide.', 'error');
                return false;
            }

            const today = new Date();
            today.setHours(0, 0, 0, 0);

            const startDate = new Date(date1.value);
            const endDate = new Date(date2.value);
            const Datedepot = new Date(date_depot.value);

            if (startDate.toISOString().slice(0, 10) === today.toISOString().slice(0, 10) || endDate.toISOString().slice(0, 10) === today.toISOString().slice(0, 10)) {
                showAlert('Erreur', 'La date de début ou la date de fin ne doit pas être égale à la date du jour.', 'error');
                return false;
            }

            if (startDate > endDate) {
                showAlert('Erreur', 'La date de début ne peut pas être supérieur à la date de fin.', 'error');
                return false;
            }

            if (endDate > Datedepot) {
                showAlert('Erreur', 'La date du dépôt ne peut pas être supérieur à la date de fin.', 'error');
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
                url: '/api/update_depot_fac/' + id,
                method: 'GET',
                data: {
                    date1: date1.value, 
                    date2: date2.value, 
                    date_depot: date_depot.value,
                    assurance_id: assurance_id.value,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.success) {

                        date1.value = "";
                        date2.value = "";
                        date_depot.value = "";

                        $('#Table_day').DataTable().ajax.reload();
                        showAlert('Succès', 'Opération éffectuée','success');

                    } else if (response.error) {

                        showAlert('Informations', 'Echec de l\'opération','info');

                    } else if (response.existe) {
                        
                        showAlert('Informations', 'L\'intervalle de dates choisi se trouve dans l\'intervalle de certaines factures qui ont déjà été déposées.', 'info');

                    } else if (response.non_touve) {
                        
                        showAlert('Informations', 'Echec de l\'opération: Dépôt introuvable.', 'info');

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

        function deletee()
        {
            const id = document.getElementById('Iddelete').value;

            var modal = bootstrap.Modal.getInstance(document.getElementById('Mdelete'));
            modal.hide();

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: '/api/delete_depotfacture/'+id,
                method: 'GET',
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.success) {
                        $('#Table_day').DataTable().ajax.reload();
                        showAlert('Succès', 'Opération éffectuée.','success');
                    } else if (response.error) {
                        showAlert('Erreur', 'Echec de l\'opération.','error');
                    }
                      
                },
                error: function() {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert('Erreur', 'Erreur est survenue.','error');
                }
            });
        }

        function generatePDFInvoice_Fac(societes,assurance,date1,date2) {

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'l', unit: 'mm', format: 'a4' });

            const pdfFilename = "FACTURES EMISES du " + formatDate(date1) + " au " + formatDate(date2);
            doc.setProperties({
                title: pdfFilename,
            });

            let yPos = 10;

            function drawSection(yPos) {

                rightMargin = 15;
                leftMargin = 15;
                pdfWidth = doc.internal.pageSize.getWidth();

                doc.setFontSize(10);
                doc.setTextColor(0, 0, 0);
                doc.setFont("Helvetica", "bold");

                const logoSrc = "{{asset('assets/images/logo.png')}}";
                const logoWidth = 22;
                const logoHeight = 22;
                doc.addImage(logoSrc, 'PNG', leftMargin, yPos - 7, logoWidth, logoHeight);

                const title = "ESPACE MEDICO SOCIAL LA PYRAMIDE DU COMPLEXE";
                const titleWidth = doc.getTextWidth(title);
                const titleX = (doc.internal.pageSize.getWidth() - titleWidth) / 2;
                doc.text(title, titleX, yPos);

                doc.setFont("Helvetica", "normal");
                const address = "Abidjan Yopougon Selmer, Non loin du complexe sportif Jesse-Jackson - 04 BP 1523";
                const addressWidth = doc.getTextWidth(address);
                const addressX = (doc.internal.pageSize.getWidth() - addressWidth) / 2;
                doc.text(address, addressX, (yPos + 5));

                const phone = "Tél.: 20 24 44 70 / 20 21 71 92 - Cel.: 01 01 01 63 43";
                const phoneWidth = doc.getTextWidth(phone);
                const phoneX = (doc.internal.pageSize.getWidth() - phoneWidth) / 2;
                doc.text(phone, phoneX, (yPos + 10));

                // Définir le style pour le texte
                doc.setFontSize(12);
                doc.setFont("Helvetica", "bold");
                doc.setLineWidth(0.5);
                doc.setTextColor(0, 0, 0);

                const titleR = "LISTE DES FACTURES PAR ASSURANCE : " + assurance.nom;
                const titleRWidth = doc.getTextWidth(titleR);
                const titleRX = (doc.internal.pageSize.getWidth() - titleRWidth) / 2;

                const paddingh = 5;  // Ajuster le padding en hauteur
                const paddingw = 5;  // Ajuster le padding en largeur

                const rectX = titleRX - paddingw;
                let rectY = yPos + 18; // Position initiale du rectangle
                const rectWidth = titleRWidth + (paddingw * 2);
                const rectHeight = 15 + (paddingh * 2);

                doc.setDrawColor(0, 0, 0);
                doc.rect(rectX, rectY, rectWidth, rectHeight);

                // Centrer le texte dans le rectangle
                const textY = rectY + (rectHeight / 2) - 2;  // Ajustement de la position Y du texte pour centrer verticalement
                doc.text(titleR, titleRX, textY);

                // Ajout de la date sous le titre avec un saut de ligne
                const dateText = "du : " + formatDate(date1) + " au " + formatDate(date2); // Assurez-vous que formatDate est une fonction qui formate la date comme vous le souhaitez
                const dateTextWidth = doc.getTextWidth(dateText);
                const dateTextX = (doc.internal.pageSize.getWidth() - dateTextWidth) / 2; // Centrer la date

                // Positionner la date sous le rectangle
                doc.text(dateText, dateTextX, textY + 10);  // Ajuster `+ 10` selon l'espace souhaité entre le titre et la date


                yPoss = (yPos + 40);

                let grandTotalAssurance = 0;
                let grandTotalPatient = 0;
                let grandTotalMontant = 0;

                if (societes.length > 0) {
                    societes.forEach((societe, indexSociete) => {
                        const fac_cons = societe.fac_cons || [];
                        const fac_exam = societe.fac_exam || [];
                        const fac_soinsam = societe.fac_soinsam || [];
                        const fac_hopital = societe.fac_hopital || [];

                        // Fusionner consultations, examens et soins ambulatoires dans un tableau unique
                        const fac_global = [
                            ...fac_cons.map(item => ({
                                ...item,
                                acte: 'Consultation',
                            })),
                            ...fac_exam.map(item => ({
                                ...item,
                                acte: 'Examen',
                            })),
                            ...fac_soinsam.map(item => ({
                                ...item,
                                acte: 'Soins Ambulatoire',
                            })),
                            ...fac_hopital.map(item => ({
                                ...item,
                                acte: 'Hospitalisation',
                            })),
                        ];

                        if (fac_global.length > 0) {
                            // Titre de la société
                            yPoss += 20;
                            doc.setFontSize(14);
                            doc.setFont("Helvetica", "bold");
                            doc.text("Société : " + societe.nom, 15, yPoss);
                            yPoss += 5;

                            // Calculer les totaux pour la société
                            const totalAssurance = fac_global.reduce((sum, item) => sum + parseInt(item.part_assurance.replace(/[^0-9]/g, '') || 0), 0);
                            const totalPatient = fac_global.reduce((sum, item) => sum + parseInt(item.part_patient.replace(/[^0-9]/g, '') || 0), 0);
                            const totalMontant = fac_global.reduce((sum, item) => sum + parseInt(item.montant.replace(/[^0-9]/g, '') || 0), 0);

                            // Ajouter les totaux de cette société aux grands totaux
                            grandTotalAssurance += totalAssurance;
                            grandTotalPatient += totalPatient;
                            grandTotalMontant += totalMontant;

                            // Générer le tableau unique pour consultations, examens et soins ambulatoires avec une ligne de pied
                            doc.autoTable({
                                startY: yPoss,
                                head: [['N°', 'Date', 'Numéro de Bon', 'Patient', 'Acte effectué', 'Montant Total', 'Part Assurance', 'Part assuré']],
                                body: fac_global.map((item, index) => [
                                    index + 1,
                                    formatDate(item.created_at) || '',
                                    item.num_bon || '',
                                    item.patient || '',
                                    item.acte,
                                    (item.montant || '') + " Fcfa",
                                    (item.part_assurance || '') + " Fcfa",
                                    (item.part_patient || '') + " Fcfa",
                                ]),
                                theme: 'striped',
                                // Footer row with the total values
                                foot: [[
                                    { content: 'Totals', colSpan: 5, styles: { halign: 'center', fontStyle: 'bold' } },
                                    { content: formatPrice(totalMontant) + " Fcfa", styles: { fontStyle: 'bold' } },
                                    { content: formatPrice(totalAssurance) + " Fcfa", styles: { fontStyle: 'bold' } },
                                    { content: formatPrice(totalPatient) + " Fcfa", styles: { fontStyle: 'bold' } },
                                    
                                ]]
                            });

                            const finalY = doc.autoTable.previous.finalY || yPoss + 10;
                            yPoss = finalY + 10;

                            if (indexSociete < societes.length - 1) {

                                if (yPoss + 30 > doc.internal.pageSize.height) {
                                    doc.addPage();
                                    yPoss = 20;
                                }
                            }
                            
                        }
                    });

                    const finalY = doc.autoTable.previous.finalY || yPoss + 20;
                    yPoss = finalY + 20;

                    if (yPoss + 40 > doc.internal.pageSize.height) {
                        doc.addPage();
                        yPoss = 20;
                    }

                    // Afficher les grands totaux sur cette page
                    doc.setFontSize(14);
                    doc.setFont("Helvetica", "bold");
                    doc.text("TOTAL DES FACTURES", 15, yPoss);
                    yPoss += 10;

                    const grandTotalInfo = [
                        { label: "Total Assurance", value: formatPrice(grandTotalAssurance) +" Fcfa" },
                        { label: "Total Patient", value: formatPrice(grandTotalPatient) + " Fcfa" },
                        { label: "Montant Total", value: formatPrice(grandTotalMontant) + " Fcfa" },
                    ];

                    // Afficher les grands totaux sur la nouvelle page
                    grandTotalInfo.forEach(info => {
                        doc.setFontSize(11);
                        doc.setFont("Helvetica", "bold");
                        doc.text(info.label, leftMargin, yPoss);
                        doc.setFont("Helvetica", "normal");
                        doc.text(": " + info.value, leftMargin + 50, yPoss);
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

            drawSection(yPos);

            addFooter();

            doc.output('dataurlnewwindow');
        }

        function generatePDFInvoice_Fac_Bordo(societes,assurance,date1,date2,statut,date_paiement,type,cheque) {

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });

            const pdfFilename = "BORDEREAUX DES FACTURES EMISES DEPOSER. Du " + formatDate(date1) + " au " + formatDate(date2);
            doc.setProperties({
                title: pdfFilename,
            });

            let yPos = 10;

            function drawSection(yPos) {

                rightMargin = 15;
                leftMargin = 15;
                pdfWidth = doc.internal.pageSize.getWidth();

                doc.setFontSize(10);
                doc.setTextColor(0, 0, 0);
                doc.setFont("Helvetica", "bold");

                const logoSrc = "{{asset('assets/images/logo.png')}}";
                const logoWidth = 22;
                const logoHeight = 22;
                doc.addImage(logoSrc, 'PNG', leftMargin, yPos - 7, logoWidth, logoHeight);

                const title = "ESPACE MEDICO SOCIAL LA PYRAMIDE DU COMPLEXE";
                const titleWidth = doc.getTextWidth(title);
                const titleX = (doc.internal.pageSize.getWidth() - titleWidth) / 2;
                doc.text(title, titleX, yPos);

                doc.setFont("Helvetica", "normal");
                const address = "Abidjan Yopougon Selmer, Non loin du complexe sportif Jesse-Jackson - 04 BP 1523";
                const addressWidth = doc.getTextWidth(address);
                const addressX = (doc.internal.pageSize.getWidth() - addressWidth) / 2;
                doc.text(address, addressX, (yPos + 5));

                const phone = "Tél.: 20 24 44 70 / 20 21 71 92 - Cel.: 01 01 01 63 43";
                const phoneWidth = doc.getTextWidth(phone);
                const phoneX = (doc.internal.pageSize.getWidth() - phoneWidth) / 2;
                doc.text(phone, phoneX, (yPos + 10));

                // Définir le style pour le texte
                doc.setFontSize(12);
                doc.setFont("Helvetica", "bold");
                doc.setLineWidth(0.5);
                doc.setTextColor(0, 0, 0);

                const titleR = "BORDEREAUX PAR ASSURANCE : " + assurance.nom;
                const titleRWidth = doc.getTextWidth(titleR);
                const titleRX = (doc.internal.pageSize.getWidth() - titleRWidth) / 2;

                const paddingh = 5;  // Ajuster le padding en hauteur
                const paddingw = 5;  // Ajuster le padding en largeur

                const rectX = titleRX - paddingw;
                let rectY = yPos + 18; // Position initiale du rectangle
                const rectWidth = titleRWidth + (paddingw * 2);
                const rectHeight = 15 + (paddingh * 2);

                doc.setDrawColor(0, 0, 0);
                doc.rect(rectX, rectY, rectWidth, rectHeight);

                // Centrer le texte dans le rectangle
                const textY = rectY + (rectHeight / 2) - 2;  // Ajustement de la position Y du texte pour centrer verticalement
                doc.text(titleR, titleRX, textY);

                // Ajout de la date sous le titre avec un saut de ligne
                const dateText = "du : " + formatDate(date1) + " au " + formatDate(date2); // Assurez-vous que formatDate est une fonction qui formate la date comme vous le souhaitez
                const dateTextWidth = doc.getTextWidth(dateText);
                const dateTextX = (doc.internal.pageSize.getWidth() - dateTextWidth) / 2; // Centrer la date
                // Positionner la date sous le rectangle
                doc.text(dateText, dateTextX, textY + 10);

                yPoss = (yPos + 50);

                const pageWidth = doc.internal.pageSize.getWidth();
                let text;
                if (statut === 'oui') {
                    if (type === 'virement') {
                        text = "Paiement effectué le " + date_paiement + " par Virement Bancaire";
                    } else if (type === 'cheque') {
                        text = "Paiement effectué le " + date_paiement + " par Chèque. N°" + cheque;
                    }
                } else {
                    text = "Paiement non effectué";
                }
                doc.setFontSize(12);
                doc.setFont("Helvetica", "bold");
                if (statut === 'oui') {
                    doc.setTextColor(0, 128, 0);
                } else {
                    doc.setTextColor(255, 0, 0);
                }
                const textWidth = doc.getTextWidth(text);
                const xPos = (pageWidth - textWidth) / 2;
                doc.text(text, xPos, yPoss);


                yPoss += 5;

                if (societes.length > 0) {

                    // Totals
                    const totalAssurance = societes.reduce((sum, item) => sum + parseInt(item.total_assurance.replace(/[^0-9]/g, '') || 0), 0);
                    const totalPatient = societes.reduce((sum, item) => sum + parseInt(item.total_patient.replace(/[^0-9]/g, '') || 0), 0);
                    const totalMontant = societes.reduce((sum, item) => sum + parseInt(item.total_montant.replace(/[^0-9]/g, '') || 0), 0);

                    doc.autoTable({
                        startY: yPoss,
                        head: [['N°', 'Société', 'Montant Total', 'Part Assurance', 'Part assuré']],
                        body: societes.map((item, index) => [
                            index + 1,
                            item.nom || '',
                            (item.total_montant || '') + " Fcfa",
                            (item.total_assurance || '') + " Fcfa",
                            (item.total_patient || '') + " Fcfa",
                        ]),
                        theme: 'striped',
                        foot: [[
                            { content: 'Totals', colSpan: 2, styles: { halign: 'center', fontStyle: 'bold' } },
                            { content: formatPrice(totalMontant) + " Fcfa", styles: { fontStyle: 'bold' } },
                            { content: formatPrice(totalAssurance) + " Fcfa", styles: { fontStyle: 'bold' } },
                            { content: formatPrice(totalPatient) + " Fcfa", styles: { fontStyle: 'bold' } },
                                    
                        ]]
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

            drawSection(yPos);

            addFooter();

            doc.output('dataurlnewwindow');
        }

    });
</script>

@endsection


