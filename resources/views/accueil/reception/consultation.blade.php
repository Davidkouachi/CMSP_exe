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
            Liste des consultations
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
                        <h5>Consultations.</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <div class="card-body ">
                    <ol class="breadcrumb justify-content-center align-items-center">
                        <li class="" style="display: block;" id="div_btn_affiche_stat">
                            <a class="btn btn-sm btn-warning" id="btn_affiche_stat">
                                Afficher les Statstiques
                                <i class="ri-eye-line" ></i>
                            </a>
                        </li>
                        <li class="" style="display: none;" id="div_btn_cache_stat">
                            <a class="btn btn-sm btn-danger" id="btn_cache_stat">
                                Cacher les Statstiques
                                <i class="ri-eye-off-line" ></i>
                            </a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-3" id="stat_consultation"></div>
    <div class="row gx-3">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title">
                        Liste des Conultations
                    </h5>
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
                            <table id="Table_day" class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">N°</th>
                                        <th scope="col">Code</th>
                                        <th scope="col">N° dossier</th>
                                        <th scope="col">Nom et Prénoms</th>
                                        <th scope="col">Contact</th>
                                        <th scope="col">Médecin</th>
                                        <th scope="col">Spécialité</th>
                                        <th scope="col">Prix</th>
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

<div class="modal fade" id="CDetail" tabindex="-1" aria-modal="true" role="dialog" >
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalXlLabel">
                    Détail facture
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="div_TableDC" style="display: none;" >
                    <div class="row">
                        <div class="col-xxl-3 col-sm-3 col-12">
                            <img height="100" width="100" src="{{asset('assets/images/facture.webp')}}" alt="Bootstrap Admin Dashboard" class="img-fluid">
                        </div>
                        <div class="col-sm-9 col-12">
                            <div class="text-end" id="fac_detail">
                                
                            </div>
                        </div>
                        <div class="col-12 mb-5"></div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div id="div_alert_tableDC" >
                        
                                            </div>
                                            <div class="table-responsive" >
                                                <table class="table " id="TableDC">
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="message_TableDC" style="display: none;">
                    <div class="col-12 " >
                        <div class="mb-3" >
                            <p class="text-center" >
                                Aucune facture disponible
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row" id="div_Table_loaderDC" style="display: none;">
                    <div class="col-12 " >
                        <div class="mb-3">
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

<script src="{{asset('assets/js/app/js/jspdfinvoicetemplate/dist/index.js')}}" ></script>
<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>

<script>
    $(document).ready(function() {

        $('#btn_search_table').on('click', function() {
            $('#Table_day').DataTable().ajax.reload();
        });

        $('#searchDate1').on('change', function() {
            const date1Value = $(this).val(); // Récupère la valeur de #searchDate1
            const $date2 = $('#searchDate2'); // Sélecteur pour #searchDate2

            if ($date2.val() && new Date(date1Value) > new Date($date2.val())) {
                showAlert('Erreur', 'La date de début ne peut pas être supérieure à la date de fin.', 'warning');
                $(this).val($date2.val()); // Définit la valeur de #searchDate1 égale à celle de #searchDate2
            } else {
                $date2.attr('min', date1Value); // Définit la date minimale pour #searchDate2
            }
        });

        $('#searchDate2').on('change', function() {
            const date1Value = $('#searchDate1').val(); // Récupère la valeur de #searchDate1
            const date2Value = $(this).val(); // Récupère la valeur de #searchDate2

            if (new Date(date1Value) > new Date(date2Value)) {
                showAlert('Erreur', 'La date de fin ne peut pas être inférieure à la date de début.', 'warning');
                $(this).val(date1Value); // Définit la valeur de #searchDate2 égale à celle de #searchDate1
            }
        });


        $('#btn_affiche_stat').on('click', function() {
            $('#div_btn_affiche_stat').hide(); // Masque le bouton d'affichage
            $('#div_btn_cache_stat').show();  // Affiche le bouton de cache
            Statistique_cons(); // Appelle la fonction Statistique_cons
        });

        $('#btn_cache_stat').on('click', function() {
            $('#div_btn_affiche_stat').show(); // Affiche le bouton d'affichage
            $('#div_btn_cache_stat').hide();  // Masque le bouton de cache
            $('#stat_consultation').empty(); // Vide le contenu de l'élément avec l'ID "stat_consultation"
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

        // ------------------------------------------------------------------

        function Statistique_cons() {

            const stat_consultation = document.getElementById("stat_consultation");

            const div = document.createElement('div');
            div.innerHTML = `
                <div class="d-flex justify-content-center align-items-center mb-3">
                    <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                    <strong>Chargement des données...</strong>
                </div>
            `;
            stat_consultation.appendChild(div);


            fetch('/api/statistique_cons') // API endpoint
                .then(response => response.json())
                .then(data => {

                    const typeactes = data.typeacte;
                    stat_consultation.innerHTML = '';

                    if (typeactes.length > 0) {

                        // Loop through each item in the chambre array
                        typeactes.forEach((item, index) => {
                            // Create a new row
                            const row = document.createElement('div');
                            row.className = "col-xl-3 col-sm-6 col-12";
                            // Create and append cells to the row based on your table's structure
                            row.innerHTML = `
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="p-2 border border-primary rounded-circle me-3">
                                                <div class="icon-box md bg-primary-subtle rounded-5">
                                                    <i class="ri-stethoscope-line fs-4 text-primary"></i>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <h5 class="lh-1">
                                                    ${item.nom}
                                                </h5>
                                                <p class="m-0">
                                                    ${item.nbre} Consultation(s)
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between mt-1">
                                            <div class="text-start">
                                                <p class="mb-0 text-primary">Part Assurance</p>
                                            </div>
                                            <div class="text-end">
                                                <p class="mb-0 text-primary">
                                                    ${formatPrice(item.part_assurance.toString())} Fcfa
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between mt-1">
                                            <div class="text-start">
                                                <p class="mb-0 text-primary">Part Patient</p>
                                            </div>
                                            <div class="text-end">
                                                <p class="mb-0 text-primary">
                                                    ${formatPrice(item.part_patient.toString())} Fcfa
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between mt-1">
                                            <div class="text-start">
                                                <p class="mb-0 text-primary">Montant Total</p>
                                            </div>
                                            <div class="text-end">
                                                <p class="mb-0 text-primary">
                                                    ${formatPrice(item.total.toString())} Fcfa
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            // Append the row to the table body
                            stat_consultation.appendChild(row);

                        });
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données:', error);
                });
        }

        // ------------------------------------------------------------------

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

        $('#Table_day').DataTable({

            processing: true,
            serverSide: false,
            ajax: function(data, callback) {
                const startDate = $('#searchDate1').val();
                const endDate = $('#searchDate2').val();
                
                $.ajax({
                    url: `/api/list_cons_all/${startDate}/${endDate}`,
                    type: 'GET',
                    success: function(response) {
                        callback({ data: response.data });
                    },
                    error: function() {
                        console.log('Error fetching data. Please check your API or network.');
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
                    searchable: true, 
                },
                { 
                    data: 'matricule',
                    searchable: true,
                },
                { 
                    data: 'name',
                    searchable: true, 
                },
                { 
                    data: 'tel', 
                    render: (data) => `+225 ${data}`,
                    searchable: true, 
                },
                { 
                    data: 'medecin',
                    searchable: true, 
                },
                { 
                    data: 'type_motif',
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
                            <a class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#CDetail" id="Cdetail" data-id="${row.id}">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <a class="btn btn-outline-warning btn-sm" id="Cfacture" data-code="${row.code}">
                                    <i class="ri-printer-line"></i>
                                </a>
                                <a class="btn btn-outline-info btn-sm" id="Cfiche" data-code="${row.code}">
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

            $('#Table_day').on('click', '#Cdetail', function() {
                const id = $(this).data('id');
                
                const tableBodyD = document.querySelector('#TableDC tbody');
                const messageDivD = document.getElementById('message_TableDC');
                const tableDivD = document.getElementById('div_TableDC');
                const loaderDivD = document.getElementById('div_Table_loaderDC');

                messageDivD.style.display = 'none';
                tableDivD.style.display = 'none';
                loaderDivD.style.display = 'block';

                fetch(`/api/list_facture_inpayer_d/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        const factureds = data.factured;
                        const total = data.Total;

                        const id = data.ID.code_fac;
                        const date_fac = data.ID.date_fac;
                        const statutValue = data.ID.statut;
                        const date_paye = data.ID.date_paye;

                        const fac_detail = document.getElementById('fac_detail');
                        fac_detail.innerHTML = '';

                        const para = document.createElement('p');
                        para.className = "mb-2";
                        para.innerHTML = `N° Facture - <span class="text-primary">${id}</span>`;
                        fac_detail.appendChild(para);

                        const date0 = document.createElement('p');
                        date0.className = "mb-2";
                        date0.innerHTML = `Date de création ${formatDateHeure(date_fac)}`;
                        fac_detail.appendChild(date0);

                        if (date_paye) {
                            const date = document.createElement('p');
                            date.className = "mb-2";
                            date.innerHTML = `Date de paiement ${formatDateHeure(date_paye)}`;
                            fac_detail.appendChild(date);
                        }

                        const statutElement = document.createElement('span');
                        if (statutValue === 'payer') {
                            statutElement.className = "badge bg-success";
                            statutElement.innerHTML = `Facture Réglée`;
                        } else {
                            statutElement.className = "badge bg-danger";
                            statutElement.innerHTML = `Facture Non Réglée`;
                        }
                        fac_detail.appendChild(statutElement);

                        tableBodyD.innerHTML = '';

                        if (factureds.length > 0) {
                            loaderDivD.style.display = 'none';
                            messageDivD.style.display = 'none';
                            tableDivD.style.display = 'block';

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
                                        <h6 class="text-success" >
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
                                        Total : ${formatPriceT(total.total_sum)} Fcfa
                                    </h5>
                                </td>
                            `;
                            tableBodyD.appendChild(row2);

                        } else {
                            tableDivD.style.display = 'none';
                            messageDivD.style.display = 'block';
                            loaderDivD.style.display = 'none';
                            messageDivD.innerHTML = '<p class="text-danger">Aucun détail trouvé.</p>';
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                    });
            });

            $('#Table_day').on('click', '#Cfacture', function() {
                const code = $(this).data('code');

                fetch(`/api/fiche_consultation/${code}`) // API endpoint
                .then(response => response.json())
                .then(data => {
                    // Access the 'chambre' array from the API response
                    const patient = data.patient;
                    const typeacte = data.typeacte;
                    const user = data.user;
                    const consultation = data.consultation;

                    generatePDFInvoice(patient, user, typeacte, consultation);

                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données:', error);
                });
            });

            $('#Table_day').on('click', '#Cfiche', function() {
                const code = $(this).data('code');

                fetch(`/api/fiche_consultation/${code}`) // API endpoint
                .then(response => response.json())
                .then(data => {
                    // Access the 'chambre' array from the API response
                    const patient = data.patient;
                    const typeacte = data.typeacte;
                    const user = data.user;
                    const consultation = data.consultation;

                    generatePDFficheCons(patient, user, typeacte, consultation);

                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données:', error);
                });
            });
        }

        function generatePDFficheCons(patient, user, typeacte, consultation) {

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            const pdfFilename = "Fiche Consultation N°" + consultation.code + " du " + formatDateHeure(consultation.created_at);
            doc.setProperties({
                title: pdfFilename,
            });

            yPos = 10;

            function drawConsultationSection(yPos) {
                rightMargin = 15;
                leftMargin = 15;
                pdfWidth = doc.internal.pageSize.getWidth();

                const titlea = "Fiche";
                doc.setFontSize(100);
                doc.setTextColor(242, 237, 237); // Gray color for background effect
                doc.setFont("Helvetica", "bold");
                doc.text(titlea, 120, yPos + 150, { align: 'center', angle: 40 });

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
                doc.setTextColor(255, 0, 0);
                // doc.line(10, 35, 200, 35); 
                const titleR = "FICHE DE CONSULTATION";
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
                const titleN = "N° "+ consultation.code;
                doc.text(titleN, (doc.internal.pageSize.getWidth() - doc.getTextWidth(titleN)) / 2, (yPos + 31));

                doc.setFontSize(10);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(0, 0, 0);
                const numDossier = "N° Dossier : P-"+ patient.matricule;
                const numDossierWidth = doc.getTextWidth(numDossier);
                doc.text(numDossier, pdfWidth - rightMargin - numDossierWidth, yPos + 25);

                yPoss = (yPos + 45);

                const patientInfo = [
                    { label: "Medecin", value: "Dr. "+ user.name },
                    { label: "Spécialité", value: typeacte.nom },
                    { label: "Nom et Prénoms", value: patient.np },
                    { label: "Assurer", value: patient.assurer },
                    { label: "Age", value: patient.age +" an(s)" },
                    { label: "Adresse", value: patient.adresse },
                    { label: "Contact", value: "+225 "+patient.tel },
                ];

                if (patient.assurer == 'oui') {
                    patientInfo.push(
                        { label: "Assurance", value: patient.assurance },
                        { label: "Matricule", value: patient.matricule_assurance },
                    );
                }

                patientInfo.forEach(info => {
                    doc.setFontSize(10);
                    doc.setFont("Helvetica", "bold");
                    doc.text(info.label, leftMargin, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 35, yPoss);
                    yPoss += 7;
                });

                doc.setFontSize(30);
                doc.setLineWidth(0.5);
                doc.line(10, yPoss, 200, yPoss);

                doc.setFontSize(15);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(255, 0, 0);
                const rendu = "Compte Rendu";
                const titleRr = doc.getTextWidth(rendu);
                const titlerendu = (doc.internal.pageSize.getWidth() - titleRr) / 2;
                doc.text(rendu, titlerendu, yPoss + 10);
                // Dessiner une ligne sous le texte pour le souligner
                const underlineY = yPoss + 12; // Ajustez cette valeur selon vos besoins
                doc.setDrawColor(255, 0, 0);
                doc.setLineWidth(0.5); // Épaisseur de la ligne
                doc.line(titlerendu, underlineY, titlerendu + titleRr, underlineY);

                yPoss += 25;
                hPoss = yPoss;
                doc.setTextColor(0, 0, 0);

                const pInfo1 = [
                    { label: "TA", value: "..........." },
                    { label: "Poids", value: "..........." },
                ];

                pInfo1.forEach(info => {
                    doc.setFontSize(10);
                    doc.setFont("Helvetica", "bold");
                    doc.text(info.label, leftMargin, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 15, yPoss);
                    yPoss += 10;
                });

                const pInfo2 = [
                    { label: "BD", value: "..........." },
                    { label: "Pouls", value: "..........." },
                ];

                pInfo2.forEach(info => {
                    doc.setFontSize(10);
                    doc.setFont("Helvetica", "bold");
                    doc.text(info.label, leftMargin + 40, yPoss - 20);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 55, yPoss - 20);
                    yPoss += 10;
                });

                const pInfo3 = [
                    { label: "BG", value: "..........." },
                    { label: "Taille", value: "..........." },
                ];

                pInfo3.forEach(info => {
                    doc.setFontSize(10);
                    doc.setFont("Helvetica", "bold");
                    doc.text(info.label, leftMargin + 75, yPoss - 40);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 90, yPoss - 40);
                    yPoss += 10;
                });

                const pInfo4 = [
                    { label: "Temp", value: "..........." },
                ];

                pInfo4.forEach(info => {
                    doc.setFontSize(10);
                    doc.setFont("Helvetica", "bold");
                    doc.text(info.label, leftMargin + 110, yPoss - 60);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 125, yPoss - 60);
                    yPoss += 10;
                });

                hPoss += 25;

                doc.setFontSize(15);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(225, 0, 0);
                const motif = "Motif";
                const titleRm = doc.getTextWidth(motif);
                const titlemotif = (doc.internal.pageSize.getWidth() - titleRm) / 2;
                doc.text(motif, titlemotif, hPoss);
                // Dessiner une ligne sous le texte pour le souligner
                const underlineYm = hPoss + 2; // Ajustez cette valeur selon vos besoins
                doc.setDrawColor(225, 0, 0);
                doc.setLineWidth(0.5); // Épaisseur de la ligne
                doc.line(titlemotif, underlineYm, titlemotif + titleRm, underlineYm);

                doc.setFontSize(8);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(0, 0, 0);
                doc.text("Imprimer le "+new Date().toLocaleDateString()+" à "+new Date().toLocaleTimeString() , 5, 295);

            }

            drawConsultationSection(yPos);

            doc.output('dataurlnewwindow');
        }

        function generatePDFInvoice(patient, user, typeacte, consultation) {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });

            const pdfFilename = "Consultation Facture N°" + consultation.code_fac + " du " + formatDateHeure(consultation.created_at);
            doc.setProperties({
                title: pdfFilename,
            });

            yPos = 10;

            function drawConsultationSection(yPos) {
                rightMargin = 15;
                leftMargin = 15;
                pdfWidth = doc.internal.pageSize.getWidth();

                const titlea = "Facture";
                doc.setFontSize(100);
                doc.setTextColor(242, 237, 237); // Gray color for background effect
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
                doc.setTextColor(255, 0, 0);
                // doc.line(10, 35, 200, 35); 
                const titleR = "FACTURE DE CONSULTATION";
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
                const titleN = "N° "+ consultation.code_fac;
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

                const medecinInfo = [
                    { label: "N° Consultation", value: "C-"+consultation.code},
                    { label: "Medecin", value: "Dr. "+user.name },
                    { label: "Spécialité", value: typeacte.nom },
                    { label: "Prix Consultation", value: typeacte.prix+" Fcfa" },
                ];

                medecinInfo.forEach(info => {
                    doc.setFontSize(8);
                    doc.setFont("Helvetica", "bold");
                    doc.text(info.label, leftMargin + 100, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 135, yPoss);
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
                    doc.text(": " + info.value, leftMargin + 135, yPoss);
                    yPoss += 7;
                });

                yPoss += 1;

                doc.setFontSize(11);
                doc.setTextColor(0, 0, 0);
                doc.setFont("Helvetica", "bold");
                doc.text('Montant à payer', leftMargin + 100, yPoss);
                doc.setFont("Helvetica", "bold");
                doc.text(": "+consultation.part_patient+" Fcfa", leftMargin + 135, yPoss);

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

    });
</script>

@endsection
