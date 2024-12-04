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
            Actes éffectués
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    <!-- Row starts -->
    <div class="row justify-content-center">
        <div class="col-xxl-4 col-lg-6 col-md-8 col-sm-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title text-center">Actes éffectués</h5>
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
                                <label class="form-label">
                                Actes
                                </label>
                                <select class="form-select select2" id="acte">
                                    <option value="tous">Tout</option>
                                    <option value="cons">Consultation</option>
                                    <option value="hos">Hospitalisation</option>
                                    <option value="exam">Examen</option>
                                    <option value="soinsam">Soins Ambulatoire</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12" style="display: none;" id="div_pres">
                            <div class="mb-3">
                                <label class="form-label">
                                Filtre Consultation
                                </label>
                                <select class="form-select select2" id="pres">
                                    <option value="tous">Tout</option>
                                    <option value="medecin">Medecin</option>
                                    <option value="specialite">Spécialité</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12" style="display: none;" id="div_medecin">
                            <div class="mb-3">
                                <label class="form-label">
                                Médecin
                                </label>
                                <select class="form-select select2" id="medecin_id"></select>
                            </div>
                        </div>
                        <div class="col-12" style="display: none;" id="div_specialite">
                            <div class="mb-3">
                                <label class="form-label">
                                Spécialité
                                </label>
                                <select class="form-select select2" id="specialite_id"></select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                Assurance
                                </label>
                                <select class="form-select select2" id="assurance_id"></select>
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
        </div>
    </div>
    <!-- Row ends -->
</div>

<script src="{{asset('assets/js/app/js/jspdfinvoicetemplate/dist/index.js')}}" ></script>
<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>
<script src="{{asset('jsPDF-AutoTable/dist/jspdf.plugin.autotable.min.js')}}"></script>

@include('select2')

<script>
    $(document).ready(function() {

        select_medecin();
        select_specialite();
        select_assurance();

        document.getElementById("date1").addEventListener("change", datechange);
        document.getElementById("btn_imp").addEventListener("click", imp_fac);

        $('#acte').on('select2:select', function() {
            change_div_cons();
        });

        $('#pres').on('select2:select', function() {
            change_pres();
        });

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

        function datechange()
        {
            const date1Value = document.getElementById('date1').value;
            const date2 = document.getElementById('date2');

            date2.value = date1Value;
            date2.min = date1Value;
        }

        function select_medecin()
        {
            const selectElement = document.getElementById('medecin_id');
            selectElement.innerHTML = '';
            const defaultOption = document.createElement('option');
            defaultOption.value = 'tous';
            defaultOption.textContent = 'Tout';
            selectElement.appendChild(defaultOption);

            fetch('/api/list_medecin')
                .then(response => response.json())
                .then(data => {
                    const medecins = data.medecin;
                    medecins.forEach((item, index) => {
                        const option = document.createElement('option');
                        option.value = `${item.id}`;
                        option.textContent = `Dr. ${item.name}`;
                        selectElement.appendChild(option);
                    });
                })
                .catch(error => console.error('Erreur lors du chargement des societes:', error));
        }

        function select_specialite() {

            const selectElement = document.getElementById('specialite_id');

            selectElement.innerHTML = '';
            const defaultOption = document.createElement('option');
            defaultOption.value = 'tous';
            defaultOption.textContent = 'Tout';
            selectElement.appendChild(defaultOption);

            const acteId = '1';

            $.ajax({
                    url: '/api/select_typeacte/' + acteId,
                    method: 'GET',
                    success: function(response) {
                        const data = response.typeacte; 

                        data.forEach(typeacte => {
                            const option = document.createElement('option');
                            option.value = typeacte.id;
                            option.textContent = typeacte.nom;
                            selectElement.appendChild(option);
                        });
                    },
                    error: function() {
                        console.error('Erreur lors du chargement des types d\'actes');
                    }
                });
        }

        function change_div_cons() {
            const select = $('#acte').val();
            $('#pres').val('tous').trigger('change'); // Reset 'pres' to 'tous' and trigger change

            if (select === "cons") {
                $('#div_pres').show();
            } else {
                $('#div_pres').hide();
            }

            $('#div_medecin').hide();
            $('#div_specialite').hide();
            $('#medecin_id').val('tous').trigger('change');
            $('#specialite_id').val('tous').trigger('change');
        }

        function change_pres() {
            const select = $('#pres').val();

            if (select === "medecin") {
                $('#div_medecin').show();
                $('#div_specialite').hide();
            } else if (select === "specialite") {
                $('#div_medecin').hide();
                $('#div_specialite').show();
            } else {
                $('#div_medecin').hide();
                $('#div_specialite').hide();
            }

            $('#medecin_id').val('tous').trigger('change');
            $('#specialite_id').val('tous').trigger('change');
        }

        function select_assurance()
        {
            const selectElement = document.getElementById('assurance_id');

            selectElement.innerHTML = '';
            const defaultOption = document.createElement('option');
            defaultOption.value = 'tous';
            defaultOption.textContent = 'Tout';
            selectElement.appendChild(defaultOption);

            fetch('/api/assurance_select_patient_new')
                .then(response => response.json())
                .then(data => {
                    data.forEach(assurance => {
                        const option = document.createElement('option');
                        option.value = assurance.id; // Ensure 'id' is the correct key
                        option.textContent = assurance.nom; // Ensure 'nom' is the correct key
                        selectElement.appendChild(option);
                    });
                })
                .catch(error => console.error('Erreur lors du chargement des societes:', error));
        }

        function imp_fac()
        {
            const acte = document.getElementById('acte');
            const pres = document.getElementById('pres');
            const medecin_id = document.getElementById('medecin_id');
            const specialite_id = document.getElementById('specialite_id');
            const assurance_id = document.getElementById('assurance_id');
            const date1 = document.getElementById('date1');
            const date2 = document.getElementById('date2');

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
                url: '/api/etat_fac_acte',
                method: 'GET',
                data: {
                    acte: acte.value || null, 
                    pres: pres.value || null,
                    medecin_id: medecin_id.value || null, 
                    specialite_id: specialite_id.value || null, 
                    assurance_id: assurance_id.value || null,
                    date1: date1.value, 
                    date2: date2.value,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.donnee_0) {

                        showAlert('Informations', 'Aucune donnée n\'a été trouvée pour cette période','info');

                    } else if (response.success) {

                        const acte_cons = response.acte_cons || [];
                        const acte_hop = response.acte_hop || [];
                        const acte_exam = response.acte_exam || [];
                        const acte_soinsam = response.acte_soinsam || [];
                        const date1 = response.date1;
                        const date2 = response.date2;

                        generatePDFInvoice(acte_cons,acte_hop,acte_exam,acte_soinsam,date1,date2);

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

        function generatePDFInvoice(acte_cons,acte_hop,acte_exam,acte_soinsam,date1,date2)
        {

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'l', unit: 'mm', format: 'a4' });

            const pdfFilename = "ACTE EFFECTUEE du" + formatDate(date1) + " au " + formatDate(date2);
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
                    titleR = "Actes éffectués le "+formatDate(date1);
                }else{
                    titleR = "Actes éffectués du "+formatDate(date1)+" au "+formatDate(date2);
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
                
                let grandTotalAssurance = 0;
                let grandTotalPatient = 0;
                let grandTotalMontant = 0;

                if (acte_cons.length > 0) {

                    doc.setFontSize(14);
                    doc.setFont("Helvetica", "bold");
                    doc.setTextColor(0, 0, 0);
                    const text_c = "Consultations";
                    const textWidth_c = doc.getTextWidth(text_c);
                    const pageWidth_c = doc.internal.pageSize.getWidth();
                    const centerX_c = (pageWidth_c - textWidth_c) / 2;
                    doc.text(text_c, centerX_c, yPoss);
                    const underlineY = yPoss + 2;
                    doc.setLineWidth(0.5);
                    doc.setDrawColor(0, 0, 0);
                    doc.line(centerX_c, underlineY, centerX_c + textWidth_c, underlineY);
                    yPoss += 5;

                    // Calculate totals
                    const totalAssurance = acte_cons.reduce((sum, item) => sum + parseInt(item.part_assurance.replace(/[^0-9]/g, '') || 0), 0);
                    const totalPatient = acte_cons.reduce((sum, item) => sum + parseInt(item.part_patient.replace(/[^0-9]/g, '') || 0), 0);
                    const totalMontant = acte_cons.reduce((sum, item) => sum + parseInt(item.montant.replace(/[^0-9]/g, '') || 0), 0);

                    grandTotalAssurance += totalAssurance;
                    grandTotalPatient += totalPatient;
                    grandTotalMontant += totalMontant;

                    // Table with a footer row for totals
                    doc.autoTable({
                        startY: yPoss,
                        head: [['N°', 'N° Dossier', 'Patient', 'Assurance', 'Médecin', 'Spécialité', 'Montant Total', 'Part Assurance', 'Part Assuré', 'Date']],
                        body: acte_cons.map((item, index) => [
                            index + 1,
                            "P-" + item.matricule_patient || '',
                            item.patient || '',
                            item.assurance || 'Néant',
                            "Dr. " + item.medecin || '',
                            item.specialite,
                            item.montant + " Fcfa" || '',
                            item.part_assurance + " Fcfa" || '',
                            item.part_patient + " Fcfa" || '',
                            formatDateHeure(item.created_at) || '',
                        ]),
                        theme: 'striped',
                        // Add a footer row for totals
                        foot: [[
                            { content: 'Totals', colSpan: 6, styles: { halign: 'center', fontStyle: 'bold' } },
                            { content: formatPrice(totalMontant) + " Fcfa", styles: { fontStyle: 'bold' } },
                            { content: formatPrice(totalAssurance) + " Fcfa", styles: { fontStyle: 'bold' } },
                            { content: formatPrice(totalPatient) + " Fcfa", styles: { fontStyle: 'bold' } },
                            ''
                        ]]
                    });

                    const finalY = doc.autoTable.previous.finalY || yPoss + 10;
                    yPoss = finalY + 10;

                    if (yPoss + 30 > doc.internal.pageSize.height) {
                        doc.addPage();
                        yPoss = 20;
                    }

                    if (acte_hop.length > 0) {
                        yPoss += 10;
                    }
                }


                if (acte_hop.length > 0) {

                    doc.setFontSize(14);
                    doc.setFont("Helvetica", "bold");
                    doc.setTextColor(0, 0, 0);
                    const text_hos = "Hospitalisations";
                    const textWidth_hos = doc.getTextWidth(text_hos);
                    const pageWidth_hos = doc.internal.pageSize.getWidth();
                    const centerX_hos = (pageWidth_hos - textWidth_hos) / 2;
                    doc.text(text_hos, centerX_hos, yPoss);
                    const underlineY = yPoss + 2;
                    doc.setLineWidth(0.5);
                    doc.setDrawColor(0, 0, 0);
                    doc.line(centerX_hos, underlineY, centerX_hos + textWidth_hos, underlineY);
                    yPoss += 5;

                    // Calculate totals
                    const totalAssurance = acte_hop.reduce((sum, item) => sum + parseInt(item.part_assurance.replace(/[^0-9]/g, '') || 0), 0);
                    const totalPatient = acte_hop.reduce((sum, item) => sum + parseInt(item.part_patient.replace(/[^0-9]/g, '') || 0), 0);
                    const totalMontant = acte_hop.reduce((sum, item) => sum + parseInt(item.montant.replace(/[^0-9]/g, '') || 0), 0);

                    grandTotalAssurance += totalAssurance;
                    grandTotalPatient += totalPatient;
                    grandTotalMontant += totalMontant;

                    // Table with a footer row for totals
                    doc.autoTable({
                        startY: yPoss,
                        head: [['N°', 'N° Dossier', 'Patient', 'Assurance', 'Montant Chambre', 'Montant Soins', 'Montant Total', 'Part Assurance', 'Part Assuré', 'Date']],
                        body: acte_hop.map((item, index) => [
                            index + 1,
                            "P-" + item.matricule_patient || '',
                            item.patient || '',
                            item.assurance || 'Néant',
                            item.montant_chambre + " Fcfa" || '',
                            item.montant_soins + " Fcfa" || '',
                            item.montant + " Fcfa" || '',
                            item.part_assurance + " Fcfa" || '',
                            item.part_patient + " Fcfa" || '',
                            formatDateHeure(item.created_at) || '',
                        ]),
                        theme: 'striped',
                        // Add a footer row for totals
                        foot: [[
                            { content: 'Totals', colSpan: 6, styles: { halign: 'center', fontStyle: 'bold' } },
                            { content: formatPrice(totalMontant) + " Fcfa", styles: { fontStyle: 'bold' } },
                            { content: formatPrice(totalAssurance) + " Fcfa", styles: { fontStyle: 'bold' } },
                            { content: formatPrice(totalPatient) + " Fcfa", styles: { fontStyle: 'bold' } },
                            ''
                        ]]
                    });

                    const finalY = doc.autoTable.previous.finalY || yPoss + 10;
                    yPoss = finalY + 10;

                    if (yPoss + 30 > doc.internal.pageSize.height) {
                        doc.addPage();
                        yPoss = 20;
                    }

                    if (acte_exam.length > 0) {
                        yPoss += 10;
                    }
                }


                if (acte_exam.length > 0) {

                    doc.setFontSize(14);
                    doc.setFont("Helvetica", "bold");
                    doc.setTextColor(0, 0, 0);
                    const text_exam = "Examens";
                    const textWidth_exam = doc.getTextWidth(text_exam);
                    const pageWidth_exam = doc.internal.pageSize.getWidth();
                    const centerX_exam = (pageWidth_exam - textWidth_exam) / 2;
                    doc.text(text_exam, centerX_exam, yPoss);
                    const underlineY = yPoss + 2;
                    doc.setLineWidth(0.5);
                    doc.setDrawColor(0, 0, 0);
                    doc.line(centerX_exam, underlineY, centerX_exam + textWidth_exam, underlineY);
                    yPoss += 5;

                    // Calculate totals
                    const totalAssurance = acte_exam.reduce((sum, item) => sum + parseInt(item.part_assurance.replace(/[^0-9]/g, '') || 0), 0);
                    const totalPatient = acte_exam.reduce((sum, item) => sum + parseInt(item.part_patient.replace(/[^0-9]/g, '') || 0), 0);
                    const totalMontant = acte_exam.reduce((sum, item) => sum + parseInt(item.montant.replace(/[^0-9]/g, '') || 0), 0);

                    grandTotalAssurance += totalAssurance;
                    grandTotalPatient += totalPatient;
                    grandTotalMontant += totalMontant;

                    // Table with a footer row for totals
                    doc.autoTable({
                        startY: yPoss,
                        head: [['N°', 'N° Dossier', 'Patient', 'Assurance', 'Montant Total', 'Prélevement', 'Part Assurance', 'Part Assuré', 'Date']],
                        body: acte_exam.map((item, index) => [
                            index + 1,
                            "P-" + item.matricule_patient || '',
                            item.patient || '',
                            item.assurance || 'Néant',
                            item.montant + " Fcfa" || '',
                            item.prelevement + " Fcfa" || '',
                            item.part_assurance + " Fcfa" || '',
                            item.part_patient + " Fcfa" || '',
                            formatDateHeure(item.created_at) || '',
                        ]),
                        theme: 'striped',
                        // Footer row for totals
                        foot: [[
                            { content: 'Totals', colSpan: 4, styles: { halign: 'center', fontStyle: 'bold' } },
                            { content: formatPrice(totalMontant) + " Fcfa", styles: { fontStyle: 'bold' } },
                            '',
                            { content: formatPrice(totalAssurance) + " Fcfa", styles: { fontStyle: 'bold' } },
                            { content: formatPrice(totalPatient) + " Fcfa", styles: { fontStyle: 'bold' } },
                            ''
                        ]]
                    });

                    const finalY = doc.autoTable.previous.finalY || yPoss + 10;
                    yPoss = finalY + 10;

                    if (yPoss + 30 > doc.internal.pageSize.height) {
                        doc.addPage();
                        yPoss = 20;
                    }

                    if (acte_soinsam.length > 0) {
                        yPoss += 10;
                    }
                }


                if (acte_soinsam.length > 0) {

                    doc.setFontSize(14);
                    doc.setFont("Helvetica", "bold");
                    doc.setTextColor(0, 0, 0);
                    const text_soinsam = "Soins Ambulatoires";
                    const textWidth_soinsam = doc.getTextWidth(text_soinsam);
                    const pageWidth_soinsam = doc.internal.pageSize.getWidth();
                    const centerX_soinsam = (pageWidth_soinsam - textWidth_soinsam) / 2;
                    doc.text(text_soinsam, centerX_soinsam, yPoss);
                    const underlineY = yPoss + 2;
                    doc.setLineWidth(0.5);
                    doc.setDrawColor(0, 0, 0);
                    doc.line(centerX_soinsam, underlineY, centerX_soinsam + textWidth_soinsam, underlineY);
                    yPoss += 5;

                    // Calculate totals
                    const totalAssurance = acte_soinsam.reduce((sum, item) => sum + parseInt(item.part_assurance.replace(/[^0-9]/g, '') || 0), 0);
                    const totalPatient = acte_soinsam.reduce((sum, item) => sum + parseInt(item.part_patient.replace(/[^0-9]/g, '') || 0), 0);
                    const totalMontant = acte_soinsam.reduce((sum, item) => sum + parseInt(item.montant.replace(/[^0-9]/g, '') || 0), 0);

                    grandTotalAssurance += totalAssurance;
                    grandTotalPatient += totalPatient;
                    grandTotalMontant += totalMontant;

                    // Table with a footer row for totals
                    doc.autoTable({
                        startY: yPoss,
                        head: [['N°', 'N° Dossier', 'Patient', 'Assurance', 'Montant Total', 'Part Assurance', 'Part Assuré', 'Date']],
                        body: acte_soinsam.map((item, index) => [
                            index + 1,
                            "P-" + item.matricule_patient || '',
                            item.patient || '',
                            item.assurance || 'Néant',
                            item.montant + " Fcfa" || '',
                            item.part_assurance + " Fcfa" || '',
                            item.part_patient + " Fcfa" || '',
                            formatDateHeure(item.created_at) || '',
                        ]),
                        theme: 'striped',
                        // Footer row for totals
                        foot: [[
                            { content: 'Totals', colSpan: 4, styles: { halign: 'center', fontStyle: 'bold' } },
                            { content: formatPrice(totalMontant) + " Fcfa", styles: { fontStyle: 'bold' } },
                            { content: formatPrice(totalAssurance) + " Fcfa", styles: { fontStyle: 'bold' } },
                            { content: formatPrice(totalPatient) + " Fcfa", styles: { fontStyle: 'bold' } },
                            ''
                        ]]
                    });

                    const finalY = doc.autoTable.previous.finalY || yPoss + 10;
                    yPoss = finalY + 10;

                    if (yPoss + 30 > doc.internal.pageSize.height) {
                        doc.addPage();
                        yPoss = 20;
                    }
                }


                const finalY = doc.autoTable.previous.finalY || yPoss + 20;
                yPoss = finalY + 20;

                if (yPoss + 40 > doc.internal.pageSize.height) {
                    doc.addPage();
                    yPoss = 20;
                }

                doc.setFontSize(14);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(0, 0, 0);
                doc.text("TOTAL DES ACTES", 15, yPoss);
                yPoss += 10;

                const grandTotalInfo = [
                    { label: "Total Assurance", value: formatPrice(grandTotalAssurance) + " Fcfa" },
                    { label: "Total Patient", value: formatPrice(grandTotalPatient) + " Fcfa" },
                    { label: "Montant Total", value: formatPrice(grandTotalMontant) + " Fcfa" },
                ];

                grandTotalInfo.forEach(info => {
                    doc.setFontSize(11);
                    doc.setFont("Helvetica", "bold");
                    doc.setTextColor(0, 0, 0);
                    doc.text(info.label, leftMargin, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 50, yPoss);
                    yPoss += 7;
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


