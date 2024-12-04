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
            Point de la Caisse
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    <!-- Row starts -->
    <div class="row justify-content-center">
        {{-- <div class="col-xxl-4 col-lg-6 col-md-8 col-sm-8">
            <div class="card mb-3 h-100">
                <div class="card-header">
                    <h5 class="card-title">Point des encaissments</h5>
                </div>
                <div class="card-body" >
                    <div class="row gx-3">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    Caissier(ère)
                                </label>
                                <select class="form-select" id="caissier_id"></select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    Assurance
                                </label>
                                <select class="form-select" id="assurance_id"></select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    Date début
                                </label>
                                <input type="date" class="form-control" id="date1" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    Date Fin
                                </label>
                                <input type="date" class="form-control" id="date2" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-sm-12 d-flex justify-content-center">
                            <div class="mb-3 d-flex gap-2 justify-content-center">
                                <button id="btn_imp" class="btn btn-primary">
                                    <i class="ri-printer-line"></i>
                                    Imprimer
                                </button>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="col-xxl-4 col-lg-6 col-md-8 col-sm-8">
            <div class="card mb-3 h-100">
                <div class="card-header">
                    <h5 class="card-title text-center">Point des opérations de caisse</h5>
                </div>
                <div class="card-header">
                    <div class="text-center">
                        <a class="d-flex align-items-center flex-column">
                            <img src="{{asset('assets/images/pdf2.png')}}" class="img-7x">
                        </a>
                    </div>
                </div>
                <div class="card-body" >
                    <div class="row gx-3">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Type d'opération</label>
                                <select class="form-select select2" id="type_ope">
                                    <option value="tous">Tout</option>
                                    <option value="Entrer de Caisse">Entrer d'argent</option>
                                    <option value="Sortie de Caisse">Sortie d'argent</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    Caissier(ère)
                                </label>
                                <select class="form-select select2" id="caissier_id_ope"></select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    Date début
                                </label>
                                <input type="date" class="form-control" id="date1_ope" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    Date Fin
                                </label>
                                <input type="date" class="form-control" id="date2_ope" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-sm-12 d-flex justify-content-center">
                            <div class="mb-3 d-flex gap-2 justify-content-center">
                                <button id="btn_imp_ope" class="btn btn-primary">
                                    <i class="ri-printer-line"></i>
                                    Imprimer
                                </button>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Row ends -->
</div>

<script src="{{asset('assets/js/app/js/jspdfinvoicetemplate/dist/index.js')}}" ></script>
<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>
<script src="{{asset('jsPDF-AutoTable/dist/jspdf.plugin.autotable.min.js')}}"></script>

@include('select2')

<script>
    document.addEventListener("DOMContentLoaded", function() {

        select_caissier();
        // select_assurance();

        // document.getElementById("date1").addEventListener("change", datechange);
        document.getElementById("date1_ope").addEventListener("change", datechange_ope);
        // document.getElementById("btn_imp").addEventListener("click", imp_fac);
        document.getElementById("btn_imp_ope").addEventListener("click", imp_fac_ope);

        function select_caissier()
        {
            // const selectElement = document.getElementById('caissier_id');
            const selectElement_ope = document.getElementById('caissier_id_ope');

            // selectElement.innerHTML = '';
            selectElement_ope.innerHTML = '';

            // const defaultOption = document.createElement('option');
            // defaultOption.value = 'tous';
            // defaultOption.textContent = 'Tout';
            // selectElement.appendChild(defaultOption);

            const defaultOption_ope = document.createElement('option');
            defaultOption_ope.value = 'tous';
            defaultOption_ope.textContent = 'Tout';
            selectElement_ope.appendChild(defaultOption_ope);

            fetch('/api/list_caissier')
                .then(response => response.json())
                .then(data => {
                    const caissiers = data.caissier;
                    caissiers.forEach((item, index) => {
                        // const option = document.createElement('option');
                        // option.value = `${item.id}`;
                        // option.textContent = `${item.sexe}. ${item.name}`;
                        // selectElement.appendChild(option);

                        const option_ope = document.createElement('option');
                        option_ope.value = `${item.id}`;
                        option_ope.textContent = `${item.sexe}. ${item.name}`;
                        selectElement_ope.appendChild(option_ope);
                    });
                })
                .catch(error => console.error('Erreur lors du chargement des societes:', error));
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

        // function datechange()
        // {
        //     const date1Value = document.getElementById('date1').value;
        //     const date2 = document.getElementById('date2');

        //     date2.value = date1Value;
        //     date2.min = date1Value;
        // }

        function datechange_ope()
        {
            const date1Value = document.getElementById('date1_ope').value;
            const date2 = document.getElementById('date2_ope');

            date2.value = date1Value;
            date2.min = date1Value;
        }

        // function select_assurance()
        // {
        //     const selectElement = document.getElementById('assurance_id');

        //     selectElement.innerHTML = '';
        //     const defaultOption = document.createElement('option');
        //     defaultOption.value = 'tous';
        //     defaultOption.textContent = 'Tout';
        //     selectElement.appendChild(defaultOption);

        //     fetch('/api/assurance_select_patient_new')
        //         .then(response => response.json())
        //         .then(data => {
        //             data.forEach(assurance => {
        //                 const option = document.createElement('option');
        //                 option.value = assurance.id; // Ensure 'id' is the correct key
        //                 option.textContent = assurance.nom; // Ensure 'nom' is the correct key
        //                 selectElement.appendChild(option);
        //             });
        //         })
        //         .catch(error => console.error('Erreur lors du chargement des societes:', error));
        // }

        // function imp_fac()
        // {
        //     const caissier_id = document.getElementById('caissier_id');
        //     const assurance_id = document.getElementById('assurance_id');
        //     const date1 = document.getElementById('date1');
        //     const date2 = document.getElementById('date2');

        //     if (!date1.value.trim() || !date2.value.trim()) {
        //         showAlert('Alert', 'Veuillez saisir des dates S\'il vous plaît.','warning');
        //         return false; 
        //     }

        //     function isValidDate(dateString) {
        //         const regEx = /^\d{4}-\d{2}-\d{2}$/;
        //         if (!dateString.match(regEx)) return false;
        //         const date = new Date(dateString);
        //         const timestamp = date.getTime();
        //         if (typeof timestamp !== 'number' || isNaN(timestamp)) return false;
        //         return dateString === date.toISOString().split('T')[0];
        //     }

        //     if (!isValidDate(date1.value)) {
        //         showAlert('Erreur', 'La première date est invalide.', 'error');
        //         return false;
        //     }

        //     if (!isValidDate(date2.value)) {
        //         showAlert('Erreur', 'La deuxième date est invalide.', 'error');
        //         return false;
        //     }

        //     const startDate = new Date(date1.value);
        //     const endDate = new Date(date2.value);

        //     if (startDate > endDate) {
        //         showAlert('Erreur', 'La date de début ne peut pas être supérieur à la date de fin.', 'error');
        //         return false;
        //     }

        //     const oneYearInMs = 365 * 24 * 60 * 60 * 1000;
        //     if (endDate - startDate > oneYearInMs) {
        //         showAlert('Erreur', 'La plage de dates ne peut pas dépasser un an.', 'error');
        //         return false;
        //     }

        //     var preloader_ch = `
        //         <div id="preloader_ch">
        //             <div class="spinner_preloader_ch"></div>
        //         </div>
        //     `;
        //     // Add the preloader to the body
        //     document.body.insertAdjacentHTML('beforeend', preloader_ch);

        //     $.ajax({
        //         url: '/api/etat_fac_encaissement',
        //         method: 'GET',
        //         data: {
        //             caissier_id: caissier_id.value || null, 
        //             assurance_id: assurance_id.value || null, 
        //             date1: date1.value, 
        //             date2: date2.value,
        //         },
        //         success: function(response) {

        //             var preloader = document.getElementById('preloader_ch');
        //             if (preloader) {
        //                 preloader.remove();
        //             }

        //             const fac_cons = response.fac_cons || [];
        //             const fac_exam = response.fac_exam || [];
        //             const fac_soinsam = response.fac_soinsam || [];
        //             const fac_hopital = response.fac_hopital || [];
        //             const date1 = response.date1;
        //             const date2 = response.date2;

        //             if (response.donnee_0) {

        //                 showAlert('Informations', 'Aucune donnée n\'a été trouvée pour cette période','info');

        //             } else if (response.success) {

        //                 generatePDFInvoice(fac_cons,fac_exam,fac_soinsam,fac_hopital,date1,date2);

        //             } else {
        //                 showAlert('Informations', 'Aucune donnée n\'a été trouvée pour cette période','info');
        //             }

        //         },
        //         error: function() {

        //             var preloader = document.getElementById('preloader_ch');
        //             if (preloader) {
        //                 preloader.remove();
        //             }

        //             showAlert('Alert', ' Une erreur est survenue.','error');
        //         }
        //     });
        // }

        function imp_fac_ope()
        {
            const caissier_id = document.getElementById('caissier_id_ope');
            const type_ope = document.getElementById('type_ope');
            const date1 = document.getElementById('date1_ope');
            const date2 = document.getElementById('date2_ope');

            if (!date1.value.trim() || !date2.value.trim()) {
                showAlert('Alert', 'Veuillez saisir des dates S\'il vous plaît.','warning');
                return false; 
            }

            function isValidDate(dateString) {
                const regEx = /^\d{4}-\d{2}-\d{2}$/;
                if (!dateString.match(regEx)) return false;
                const date = new Date(dateString);
                const timestamp = date.getTime();
                if (typeof timestamp !== 'number' || isNaN(timestamp)) return false;
                return dateString === date.toISOString().split('T')[0];
            }

            if (!isValidDate(date1.value)) {
                showAlert('Erreur', 'La première date est invalide.', 'error');
                return false;
            }

            if (!isValidDate(date2.value)) {
                showAlert('Erreur', 'La deuxième date est invalide.', 'error');
                return false;
            }

            const startDate = new Date(date1.value);
            const endDate = new Date(date2.value);

            if (startDate > endDate) {
                showAlert('Erreur', 'La date de début ne peut pas être supérieur à la date de fin.', 'error');
                return false;
            }

            const oneYearInMs = 365 * 24 * 60 * 60 * 1000;
            if (endDate - startDate > oneYearInMs) {
                showAlert('Erreur', 'La plage de dates ne peut pas dépasser un an.', 'error');
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
                url: '/api/etat_fac_ope_caisse',
                method: 'GET',
                data: {
                    user_id: caissier_id.value || null,
                    typemvt: type_ope.value || null,
                    date1: date1.value, 
                    date2: date2.value,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) preloader.remove();

                    if (response.donnee_0) {

                        showAlert('Informations', 'Aucune donnée n\'a été trouvée pour cette période','info');

                    } else if (response.success) {

                        const trace = response.trace || [];
                        const total = response.total;
                        const date1 = response.date1;
                        const date2 = response.date2;

                        generatePDFInvoice_ope(trace,total,date1,date2);

                    } else {
                        showAlert('Informations', 'Aucune donnée n\'a été trouvée pour cette période','info');
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

        // function generatePDFInvoice(fac_cons,fac_exam,fac_soinsam,fac_hopital,date1,date2)
        // {

        //     const { jsPDF } = window.jspdf;
        //     const doc = new jsPDF({ orientation: 'l', unit: 'mm', format: 'a4' });

        //     const pdfFilename = "POINT DES ENCAISSEMENTS du" + formatDate(date1) + " au " + formatDate(date2);
        //     doc.setProperties({
        //         title: pdfFilename,
        //     });

        //     let yPos = 10;

        //     function drawSection(yPos) {

        //         rightMargin = 15;
        //         leftMargin = 15;
        //         pdfWidth = doc.internal.pageSize.getWidth();

        //         const logoSrc = "{{asset('assets/images/logo.png')}}";
        //         const logoWidth = 22;
        //         const logoHeight = 22;
        //         doc.addImage(logoSrc, 'PNG', leftMargin, yPos - 7, logoWidth, logoHeight);

        //         doc.setFontSize(10);
        //         doc.setTextColor(0, 0, 0);
        //         doc.setFont("Helvetica", "bold");

        //         const title = "ESPACE MEDICO SOCIAL LA PYRAMIDE DU COMPLEXE";
        //         const titleWidth = doc.getTextWidth(title);
        //         const titleX = (doc.internal.pageSize.getWidth() - titleWidth) / 2;
        //         doc.text(title, titleX, yPos);

        //         doc.setFont("Helvetica", "normal");
        //         const address = "Abidjan Yopougon Selmer, Non loin du complexe sportif Jesse-Jackson - 04 BP 1523";
        //         const addressWidth = doc.getTextWidth(address);
        //         const addressX = (doc.internal.pageSize.getWidth() - addressWidth) / 2;
        //         doc.text(address, addressX, (yPos + 5));

        //         const phone = "Tél.: 20 24 44 70 / 20 21 71 92 - Cel.: 01 01 01 63 43";
        //         const phoneWidth = doc.getTextWidth(phone);
        //         const phoneX = (doc.internal.pageSize.getWidth() - phoneWidth) / 2;
        //         doc.text(phone, phoneX, (yPos + 10));

        //         // Définir le style pour le texte
        //         doc.setFontSize(12);
        //         doc.setFont("Helvetica", "bold");
        //         doc.setLineWidth(0.5);
        //         doc.setTextColor(0, 0, 0);

        //         let titleR;

        //         if (formatDate(date1) === formatDate(date2)) {
        //             titleR = "Point des encaissments du "+formatDate(date1);
        //         }else{
        //             titleR = "Point des encaissments du "+formatDate(date1)+" au "+formatDate(date2);
        //         }

        //         const titleRWidth = doc.getTextWidth(titleR);
        //         const titleRX = (doc.internal.pageSize.getWidth() - titleRWidth) / 2;

        //         const paddingh = 5;  // Ajuster le padding en hauteur
        //         const paddingw = 5;  // Ajuster le padding en largeur

        //         const rectX = titleRX - paddingw;
        //         let rectY = yPos + 15; // Position initiale du rectangle
        //         const rectWidth = titleRWidth + (paddingw * 2);
        //         const rectHeight = 2 + (paddingh * 2);

        //         doc.setDrawColor(0, 0, 0);
        //         doc.rect(rectX, rectY, rectWidth, rectHeight);

        //         // Centrer le texte dans le rectangle
        //         const textY = rectY + (rectHeight / 2) + 2;  // Ajustement de la position Y du texte pour centrer verticalement
        //         doc.text(titleR, titleRX, textY);

        //         yPoss = (yPos + 40);
                
        //         let grandTotalAssurance = 0;
        //         let grandTotalPatient = 0;
        //         let grandTotalMontant = 0;

        //         const fac_global = [
        //             ...fac_cons.map(item => ({
        //                 ...item,
        //                 acte: 'Consultation',
        //             })),
        //             ...fac_exam.map(item => ({
        //                 ...item,
        //                 acte: 'Examen',
        //             })),
        //             ...fac_soinsam.map(item => ({
        //                 ...item,
        //                 acte: 'Soins Ambulatoire',
        //             })),
        //             ...fac_hopital.map(item => ({
        //                 ...item,
        //                 acte: 'Hospitalisation',
        //             })),
        //         ];

        //         if (fac_global.length > 0) {

        //             fac_global.sort((a, b) => new Date(b.date_payer) - new Date(a.created_at));
                            
        //             doc.autoTable({
        //                 startY: yPoss,
        //                 head: [['N°', 'Patient', 'Acte effectué', 'Montant Total', 'Part Assurance', 'Part assuré', 'Statut', 'Encaisser par', 'Date']],
        //                 body: fac_global.map((item, index) => [
        //                     index + 1,
        //                     item.patient || '',
        //                     item.acte,
        //                     item.montant + " Fcfa" || '' ,
        //                     item.part_assurance + " Fcfa" || '' ,
        //                     item.part_patient + " Fcfa" || '',
        //                     (item.statut_fac || ''),
        //                     item.user_sexe + ". "+item.user ,
        //                     formatDateHeure(item.created_at) || '',
        //                 ]),
        //                 theme: 'striped',
        //                 didParseCell: function (data) {
        //                     if (data.section === 'body' && data.column.index === 6) {
        //                         if (data.cell.raw.toLowerCase() === 'payer') {
        //                             data.cell.styles.textColor = [0, 128, 0];
        //                         } else {
        //                             data.cell.styles.textColor = [255, 0, 0];
        //                         }
        //                     }
        //                 }
        //             });

        //             const finalY = doc.autoTable.previous.finalY || yPoss + 10;
        //             yPoss = finalY + 10;

        //             const totalAssurance = fac_global.reduce((sum, item) => sum + parseInt(item.part_assurance.replace(/[^0-9]/g, '') || 0), 0);
        //             const totalPatient = fac_global.reduce((sum, item) => sum + parseInt(item.part_patient.replace(/[^0-9]/g, '') || 0), 0);
        //             const totalMontant = fac_global.reduce((sum, item) => sum + parseInt(item.montant.replace(/[^0-9]/g, '') || 0), 0);

        //             grandTotalAssurance += totalAssurance;
        //             grandTotalPatient += totalPatient;
        //             grandTotalMontant += totalMontant;

        //             const finalInfo = [
        //                 { label: "Montant Total", value: formatPrice(totalMontant) + " Fcfa" },
        //                 { label: "Total Assurance", value: formatPrice(totalAssurance) + " Fcfa" },
        //                 { label: "Total Patient", value: formatPrice(totalPatient) + " Fcfa" },
                        
        //             ];

        //             finalInfo.forEach(info => {
        //                 doc.setFontSize(11);
        //                 doc.setFont("Helvetica", "bold");
        //                 doc.text(info.label, leftMargin + 200, yPoss);
        //                 doc.setFont("Helvetica", "normal");
        //                 doc.text(": " + info.value, leftMargin + 235, yPoss);
        //                 yPoss += 7;
        //             });

        //         }

        //     }

        //     function addFooter() {
        //         // Add footer with current date and page number in X/Y format
        //         const pageCount = doc.internal.getNumberOfPages();
        //         const footerY = doc.internal.pageSize.getHeight() - 2; // 10 mm from the bottom

        //         for (let i = 1; i <= pageCount; i++) {
        //             doc.setPage(i);
        //             doc.setFontSize(8);
        //             doc.setTextColor(0, 0, 0);
        //             const pageText = `Page ${i} sur ${pageCount}`;
        //             const pageTextWidth = doc.getTextWidth(pageText);
        //             const centerX = (doc.internal.pageSize.getWidth() - pageTextWidth) / 2;
        //             doc.text(pageText, centerX, footerY);
        //             doc.text("Imprimé le : " + new Date().toLocaleDateString() + " à " + new Date().toLocaleTimeString(), 15, footerY); // Left-aligned
        //         }
        //     }

        //     drawSection(yPos);

        //     addFooter();

        //     doc.output('dataurlnewwindow');
        // }

        function generatePDFInvoice_ope(trace,total,date1,date2)
        {

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'l', unit: 'mm', format: 'a4' });

            let pdfFilename;

            if (formatDate(date1) === formatDate(date2)) {
                pdfFilename = "POINT DE OPERATION DE CAISSE du " + formatDate(date1);
            }else{
               pdfFilename = "POINT DE OPERATION DE CAISSE du " + formatDate(date1) + " au " + formatDate(date2); 
            }

            doc.setProperties({
                title: pdfFilename,
            });

            let yPos = 10;

            function drawSection(yPos) {

                rightMargin = 15;
                leftMargin = 15;
                pdfWidth = doc.internal.pageSize.getWidth();

                const logoSrc = "{{asset('assets/images/logo.png')}}";
                const logoWidth = 22;
                const logoHeight = 22;
                doc.addImage(logoSrc, 'PNG', leftMargin, yPos - 7, logoWidth, logoHeight);

                doc.setFontSize(10);
                doc.setTextColor(0, 0, 0);
                doc.setFont("Helvetica", "bold");

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

                let titleR;

                if (formatDate(date1) === formatDate(date2)) {
                    titleR = "Point des opérations caisse du "+formatDate(date1);
                }else{
                    titleR = "Point des opérations caisse du "+formatDate(date1)+" au "+formatDate(date2);
                }

                const titleRWidth = doc.getTextWidth(titleR);
                const titleRX = (doc.internal.pageSize.getWidth() - titleRWidth) / 2;

                const paddingh = 5;  // Ajuster le padding en hauteur
                const paddingw = 5;  // Ajuster le padding en largeur

                const rectX = titleRX - paddingw;
                let rectY = yPos + 15; // Position initiale du rectangle
                const rectWidth = titleRWidth + (paddingw * 2);
                const rectHeight = 2 + (paddingh * 2);

                doc.setDrawColor(0, 0, 0);
                doc.rect(rectX, rectY, rectWidth, rectHeight);

                // Centrer le texte dans le rectangle
                const textY = rectY + (rectHeight / 2) + 2;  // Ajustement de la position Y du texte pour centrer verticalement
                doc.text(titleR, titleRX, textY);

                yPoss = (yPos + 40);
                                
                // Calculate the total based on the transaction type
                const total = trace.reduce((sum, item) => {
                    const montant = parseInt(item.montant.replace(/[^0-9]/g, '') || 0);
                    return item.typemvt.toLowerCase() === 'entrer de caisse' ? sum + montant : sum - montant;
                }, 0);

                // Table with a footer row for total
                doc.autoTable({
                    startY: yPoss,
                    head: [['N°', 'Type de mouvement', 'Motifs', 'Montant', 'Créer par', 'Date d\'opération', 'Date de création']],
                    body: trace.map((item, index) => [
                        index + 1,
                        item.typemvt.toUpperCase(),
                        item.motif,
                        item.typemvt == 'Entrer de Caisse' ? '+ ' + item.montant + " Fcfa" : '- ' + item.montant + " Fcfa",
                        item.user_sexe + ". " + item.user,
                        item.date_ope == '' ? formatDate(item.created_at) : formatDate(item.date_ope),
                        formatDateHeure(item.created_at) || '',
                    ]),
                    theme: 'striped',
                    didParseCell: function (data) {
                        // Check if the section is 'body'
                        if (data.section === 'body') {
                            // Apply color based on the value in column index 1
                            if (data.column.index === 3) { // Apply color to index 3
                                if (data.row.cells[1].raw.toLowerCase() === 'entrer de caisse') {
                                    data.cell.styles.textColor = [0, 128, 0]; // Green color
                                } else {
                                    data.cell.styles.textColor = [255, 0, 0]; // Red color
                                }
                            }
                        }
                    },
                    // Footer row with the total
                    foot: [[
                        { content: 'Montant Total', colSpan: 3, styles: { halign: 'center', fontStyle: 'bold' } },
                        { content: formatPrice(total) + " Fcfa", styles: { fontStyle: 'bold' } },
                        '', '', ''
                    ]]
                });

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


