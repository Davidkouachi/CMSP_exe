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
            Rendez-Vous
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
                        <h6>Bienvenue,</h6>
                        <h2>{{Auth::user()->sexe.'. '.Auth::user()->name}}</h2>
                        <h5>Rendez-Vous.</h5>
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
                                <a class="nav-link active text-white" id="tab-twoAAA" data-bs-toggle="tab" href="#twoAAA" role="tab" aria-controls="twoAAA" aria-selected="false" tabindex="-1">
                                    <i class="ri-contacts-line me-2"></i>
                                    Liste
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane fade active show" id="twoAAA" role="tabpanel" aria-labelledby="tab-twoAAA">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="card-title">Liste des Rendez-Vous dans 2 jours</h5>
                                    <div class="d-flex">
                                        <a id="btn_refresh_table_rdv" class="btn btn-outline-info ms-auto me-1">
                                            <i class="ri-loop-left-line"></i>
                                        </a>
                                        <a style="display: none;" id="btn_sms" class="btn btn-outline-warning ms-auto" data-bs-toggle="modal" data-bs-target="#Message">
                                            <i class="ri-mail-add-line"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-outer" id="div_Table_rdv" style="display: none;">
                                        <div class="table-responsive">
                                            <table class="table m-0 align-middle" id="Table_rdv">
                                                <thead>
                                                    <tr>
                                                        <th>N°</th>
                                                        <th>Patient</th>
                                                        <th>Contact</th>
                                                        <th>Médecin</th>
                                                        <th>Spécialité</th>
                                                        <th>Rdv prévu</th>
                                                        <th>Date de création</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div id="message_Table_rdv" style="display: none;">
                                        <p class="text-center">
                                            Aucun Rendez-Vous n'est prévu aujourd'hui
                                        </p>
                                    </div>
                                    <div id="div_Table_loader_rdv" style="display: none;">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                                            <strong>Chargement des données...</strong>
                                        </div>
                                    </div>
                                    <div id="pagination-controls_rdv"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Detail_motif" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-body" id="modal_Detail_motif"></div>
    </div>
</div>

<div class="modal fade" id="Modif_Rdv_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mise à jour</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateForm">
                    <input type="hidden" id="medecin_id_rdvM">
                    <div class="mb-3">
                        <label class="form-label">Médecin</label>
                        <input readonly type="text" class="form-control" id="medecin_rdvM">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Spécialité</label>
                        <input readonly type="text" class="form-control" id="specialite_rdvM">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Patient</label>
                        <input readonly type="text" class="form-control" id="patient_rdvM" placeholder="Saisie Obligatoire" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="datetime-local" class="form-control" id="date_rdvM" placeholder="Saisie Obligatoire" min="{{ date('Y-m-d\TH:i') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motif</label>
                        <textarea class="form-control" id="motif_rdvM" rows="3" style="resize: none;"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a class="btn btn-outline-danger" data-bs-dismiss="modal">Fermer</a>
                <button type="button" class="btn btn-success" id="btn_update_rdv">Enregistrer</button>
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
                Voulez-vous vraiment Annulé ce Rendez-Vous
                <input type="hidden" id="Iddelete">
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-end gap-2">
                    <a class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Non</a>
                    <button id="btn_delete_rdv" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Oui</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Message" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel">
                    Notification Rappel
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row gx-3">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea style="resize: none;" class="form-control" id="messageSms" rows="5">Cher Patient, Vous avez un RDV au Centre Medico-Social la Pyramide le ${date}. Arrivez 15 min en avance. Merci</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-bs-dismiss="modal" class="btn btn-success" id="senderSms">
                    <span class="me-1" >Envoyer</span>
                    <i class="ri-send-plane-fill"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('assets/js/app/js/jspdfinvoicetemplate/dist/index.js')}}" ></script>
<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>
<script src="{{asset('assets/vendor/apex/apexcharts.min.js')}}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        list_rdv();

        document.getElementById("btn_update_rdv").addEventListener("click", update_rdv);
        document.getElementById("btn_refresh_table_rdv").addEventListener("click", list_rdv);
        document.getElementById("btn_delete_rdv").addEventListener("click", delete_rdv);

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

        const contacts = [];

        function list_rdv(page = 1) 
        {
            const tableBody = document.querySelector('#Table_rdv tbody');
            const messageDiv = document.getElementById('message_Table_rdv');
            const tableDiv = document.getElementById('div_Table_rdv');
            const loaderDiv = document.getElementById('div_Table_loader_rdv');

            messageDiv.style.display = 'none';
            tableDiv.style.display = 'none';
            loaderDiv.style.display = 'block';

            const url = `/api/list_rdv_two_days?page=${page}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const rdvs = data.rdv || [];
                    const pagination = data.pagination || {};
                    const perPage = pagination.per_page || 10;
                    const currentPage = pagination.current_page || 1;

                    tableBody.innerHTML = '';

                    if (rdvs.length > 0) {

                        document.getElementById('btn_sms').style.display = 'block';

                        loaderDiv.style.display = 'none';
                        messageDiv.style.display = 'none';
                        tableDiv.style.display = 'block';

                            rdvs.forEach((item, index) => {

                                contacts.push({
                                    nom: item.patient, // Nom du patient
                                    tel: item.patient_tel, // Numéro de téléphone
                                    date: formatDateHeure(item.date),     // Date du rendez-vous
                                });

                                let button = '';

                                if (item.statut == 'en attente') {
                                    button = `
                                        <a class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#Modif_Rdv_modal" id="modif-${item.id}">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <a class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#Mdelete" id="delete-${item.id}">
                                            <i class="ri-delete-bin-line"></i>
                                        </a>
                                    `;
                                }

                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td>${((currentPage - 1) * perPage) + index + 1}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a class="d-flex align-items-center flex-column me-2">
                                                <img src="/assets/images/rdv1.png" class="img-2x rounded-circle border border-1">
                                            </a>
                                            ${item.patient}
                                        </div>
                                    </td>
                                    <td>+225 ${item.patient_tel}</td>
                                    <td>Dr. ${item.medecin}</td>
                                    <td>${item.specialite}</td>
                                    <td>${formatDateHeure(item.date)}</td>
                                    <td>${formatDateHeure(item.created_at)}</td>
                                    <td>
                                        <div class="d-inline-flex gap-1">
                                            <a class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#Detail_motif" id="motif-${item.id}">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            ${button}
                                        </div>
                                    </td>
                                `;
                                tableBody.appendChild(row);

                                const deleteButton = document.getElementById(`delete-${item.id}`);
                                if (deleteButton) {
                                    deleteButton.addEventListener('click', () => {
                                        document.getElementById('Iddelete').value = item.id;
                                    });
                                }

                                const modifButton = document.getElementById(`modif-${item.id}`);
                                if (modifButton) {
                                    modifButton.addEventListener('click', () => {

                                        document.getElementById('medecin_id_rdvM').value = item.id;
                                        document.getElementById('date_rdvM').value = item.date; 
                                        document.getElementById('patient_rdvM').value = item.patient;
                                        document.getElementById('motif_rdvM').value = item.motif;
                                        document.getElementById('medecin_rdvM').value = item.medecin;
                                        document.getElementById('specialite_rdvM').value = item.specialite;

                                        const horairesData = item.horaires;
                                        const allowedDays = horairesData.map(horaire => horaire.jour);
                                        const heureDays = horairesData.reduce((map, horaire) => {
                                            const dayMapping = {
                                                'DIMANCHE': 0,
                                                'LUNDI': 1,
                                                'MARDI': 2,
                                                'MERCREDI': 3,
                                                'JEUDI': 4,
                                                'VENDREDI': 5,
                                                'SAMEDI': 6,
                                            };
                                            map[dayMapping[horaire.jour]] = horaire.heure_debut;
                                            return map;
                                        }, {});

                                        const dateInput = document.getElementById('date_rdvM');
                                        let previousDate = dateInput.value; // Track previous date

                                        $('#date_rdvM').on('change', function (event) {
                                            const selectedDate = new Date(event.target.value); // Date sélectionnée
                                            const selectedDay = selectedDate.getDay(); // Jour de la semaine

                                            const isValidDay = allowedDays.some(day => {
                                                const dayMapping = {
                                                    'DIMANCHE': 0,
                                                    'LUNDI': 1,
                                                    'MARDI': 2,
                                                    'MERCREDI': 3,
                                                    'JEUDI': 4,
                                                    'VENDREDI': 5,
                                                    'SAMEDI': 6
                                                };
                                                return dayMapping[day] === selectedDay;
                                            });

                                            if (!isValidDay) {
                                                // Restaurer une date valide
                                                $('#date_rdvM').val(date);

                                                showAlert("ALERT", 'Veuillez sélectionner un jour valide selon les horaires du médecin.', "info");
                                            } else {
                                                const selectedHour = heureDays[selectedDay];
                                                if (selectedHour) {
                                                    const formattedDate = selectedDate.toISOString().split('T')[0]; // yyyy-MM-dd
                                                    const formattedTime = selectedHour.slice(0, 5); // HH:mm
                                                    $('#date_rdvM').val(`${formattedDate}T${formattedTime}`); // yyyy-MM-ddTHH:mm
                                                } else {
                                                    showAlert("ALERT", "L'heure de début n'est pas définie pour ce jour.", "info");
                                                }
                                            }
                                        });

                                    });
                                }

                                document.getElementById(`motif-${item.id}`).addEventListener('click', () =>
                                {
                                    const modal = document.getElementById('modal_Detail_motif');
                                    modal.innerHTML = '';

                                    const div = document.createElement('div');
                                    div.innerHTML = `
                                           <div class="row gx-3">
                                                <div class="col-12">
                                                    <div class=" mb-3">
                                                        <div class="card-body">
                                                            <ul class="list-group">
                                                                <li class="list-group-item active text-center" aria-current="true">
                                                                    Motif
                                                                </li>
                                                                <li class="list-group-item">
                                                                    ${item.motif} 
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>     
                                    `;

                                    modal.appendChild(div);

                                });

                            });

                        updatePaginationControlsRdv(pagination);

                    } else {
                        document.getElementById('btn_sms').style.display = 'none';
                        tableDiv.style.display = 'none';
                        loaderDiv.style.display = 'none';
                        messageDiv.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données:', error);
                    loaderDiv.style.display = 'none';
                    tableDiv.style.display = 'none';
                    messageDiv.style.display = 'block';
                });
        }

        function updatePaginationControlsRdv(pagination) 
        {
            const paginationDiv = document.getElementById('pagination-controls_rdv');
            paginationDiv.innerHTML = '';

            // Bootstrap pagination wrapper
            const paginationWrapper = document.createElement('ul');
            paginationWrapper.className = 'pagination justify-content-center';

            // Previous button
            if (pagination.current_page > 1) {
                const prevButton = document.createElement('li');
                prevButton.className = 'page-item';
                prevButton.innerHTML = `<a class="page-link" href="#">Precédent</a>`;
                prevButton.onclick = () => list_rdv(pagination.current_page - 1);
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
                pageItem.onclick = () => list_rdv(i);
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
                lastPageItem.onclick = () => list_rdv(totalPages);
                paginationWrapper.appendChild(lastPageItem);
            }

            // Next button
            if (pagination.current_page < pagination.last_page) {
                const nextButton = document.createElement('li');
                nextButton.className = 'page-item';
                nextButton.innerHTML = `<a class="page-link" href="#">Suivant</a>`;
                nextButton.onclick = () => list_rdv(pagination.current_page + 1);
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

        function delete_rdv() 
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
                url: '/api/delete_rdv/'+id,
                method: 'GET',
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.success) {
                        list_rdv();
                        count_rdv_two_day();
                        showAlert('Succès', 'Rendez-Vous annulé.','success');
                    } else if (response.error) {
                        showAlert("ERREUR", 'Une erreur est survenue', "error");
                    }
                
                },
                error: function() {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert('Erreur', 'Erreur lors de la suppression.','error');
                }
            });
        }

        function update_rdv()
        {
            const id = document.getElementById('medecin_id_rdvM').value;
            const date_rdv = document.getElementById('date_rdvM');
            const motif_rdv = document.getElementById('motif_rdvM');

            if (!date_rdv.value.trim() || !motif_rdv.value.trim()) {
                showAlert("ALERT", 'Veuillez remplir tous les champs.', "warning");
                return false;
            }

            var modal = bootstrap.Modal.getInstance(document.getElementById('Modif_Rdv_modal'));
            modal.hide();

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: '/api/update_rdv/' + id,
                method: 'GET',
                data:{
                    date: date_rdv.value,
                    motif: motif_rdv.value,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    
                    if (response.success) {

                        list_rdv();
                        count_rdv_two_day();
                        showAlert("ALERT", 'Mise à jour éffectué', "success");

                    } else if (response.error) {
                        showAlert("ERREUR", 'Une erreur est survenue', "error");
                    } else if (response.existe) {
                        showAlert("Alert", 'Cet patient a déjà un RDV programmé a cette date', "info");
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
        }

        function count_rdv_two_day() 
        {

                fetch('/api/count_rdv_two_day')
                    .then(response => response.json())
                    .then(data => {
                        const nbre = data.nbre || 0;

                        document.getElementById('div_two_rdv').innerHTML = '';

                        if (nbre > 0) {

                            const div = `
                                <div class="sidebar-contact" style="background-color: red;">
                                    <a class="text-white" href="{{route('rdv_two_day')}}">
                                        <p class="fw-light mb-1 text-nowrap text-truncate">Rendez-Vous dans 2 jours</p>
                                        <h5 class="m-0 lh-1 text-nowrap text-truncate">${nbre}</h5>
                                        <i class="ri-calendar-schedule-line"></i>
                                    </a>
                                </div>
                            `;

                            document.getElementById('div_two_rdv').innerHTML = div;
                        }
                    })
                    .catch(error => console.error('Error fetching data:', error));
        }

        function smsSenderMultipleAsync(contacts, message) {

            const replacePlaceholders = (message, item) => {
                return message
                    .replace('${date}', item.date)
                    .replace('${nom}', item.nom);
            };
            
            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Ajouter le préchargeur au body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            const params = {
                username: '{{ env('API_SMS_USERNANME') }}',
                password: '{{ env('API_SMS_PASSWORD') }}',
                serviceid: '{{ env('API_SMS_SERVICEID') }}',
                sender: '{{ env('API_SMS_SENDER') }}',
            };

            const requests = contacts.map((item) => {

                const personalizedMessage = replacePlaceholders(message, item);

                const queryString = new URLSearchParams({
                    ...params,
                    msisdn: `+225${item.tel}`,
                    msg: personalizedMessage,
                }).toString();

                const url = `https://api-public-2.mtarget.fr/messages?${queryString}`;

                return fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then((data) => ({ item, success: true, data }))
                    .catch((error) => ({
                        item,
                        success: false,
                        error,
                    }));
            });

            Promise.all(requests).then((results) => {

                var preloader = document.getElementById('preloader_ch');
                if (preloader) {
                    preloader.remove();
                }

                const successCount = results.filter((result) => result.success).length;
                const failureCount = results.length - successCount;

                if (successCount > 0) {
                    console.log(`Succès : ${successCount} SMS envoyés avec succès.`);
                    showAlert('Succès', `${successCount}/${results.length} SMS envoyés avec succès`, 'success');
                }

                if (failureCount > 0) {
                    console.error(`Echec : ${failureCount} SMS n'ont pas été envoyés.`);
                    showAlert('Echec', `${failureCount}/${results.length} SMS n'ont pas été envoyés`, 'error');
                }

                if (results.length === 0 || failureCount === results.length) {
                    // Aucun SMS envoyé, probablement pas de connexion
                    console.error('Aucune connexion ou tous les SMS ont échoué.');
                    showAlert('Echec', 'Aucune connexion internet ou tous les envois ont échoué.', 'error');
                }

                console.log('Data:', JSON.stringify(results, null, 2));

            }).catch((error) => {
                // Gérer les erreurs globales (par exemple, problème réseau avant même de démarrer)
                var preloader = document.getElementById('preloader_ch');
                if (preloader) {
                    preloader.remove();
                }

                console.error('Erreur globale:', error);
                showAlert('Echec', 'Une erreur inattendue est survenue.', 'error');
            });
        }

        $('#senderSms').on('click', function () {

            const message = document.getElementById("messageSms").value;

            smsSenderMultipleAsync(contacts, message);
        });

    });
</script>

@endsection
