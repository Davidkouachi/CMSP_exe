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
            Emissions des Factures
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
                    <h5 class="card-title text-center">Facture par assurance</h5>
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
                                <input type="date" class="form-control" id="date1"  max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    Date Fin
                                </label>
                                <input type="date" class="form-control" id="date2"  max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-sm-12 d-flex justify-content-between">
                            <div class="mb-3 d-flex gap-2 justify-content-start">
                                <button id="btn_imp" class="btn btn-primary">
                                    <i class="ri-printer-line"></i>
                                    Imprimer
                                </button>
                            </div>
                            <div class="mb-3 d-flex gap-2 justify-content-start">
                                <button id="btn_imp_bordo" class="btn btn-warning">
                                    <i class="ri-printer-line"></i>
                                    Bordereaux
                                </button>
                            </div>
                        </div>  
                    </div>
                    <!-- Row ends -->
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

        select_assurance();

        $("#date1").on("change", datechange);
        $("#btn_imp").on("click", imp_fac_assurance);
        $("#btn_imp_bordo").on("click", imp_fac_assurance_bordo);

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

        function isValidDate(dateString) {
            const regEx = /^\d{4}-\d{2}-\d{2}$/;
            if (!dateString.match(regEx)) return false;
            const date = new Date(dateString);
            return dateString === date.toISOString().split('T')[0];
        }

        function datechange() {
            const date1Value = $('#date1').val();
            const $date2 = $('#date2');

            $date2.val(date1Value);
            $date2.attr('min', date1Value);
        }

        function select_assurance() {
            const $selectElement = $('#assurance_id');
            $selectElement.empty();

            const defaultOption = $('<option>', { value: '', text: 'Selectionner' });
            $selectElement.append(defaultOption);

            $.getJSON('/api/assurance_select_patient_new', function(data) {
                $.each(data, function(index, assurance) {
                    const option = $('<option>', { value: assurance.id, text: assurance.nom });
                    $selectElement.append(option);
                });
            }).fail(function() {
                console.error('Erreur lors du chargement des societes');
            });
        }

        function imp_fac_assurance() {

            const assurance_id = $('#assurance_id').val().trim();
            const date1 = $('#date1').val().trim();
            const date2 = $('#date2').val().trim();

            if (!assurance_id || !date1 || !date2) {
                showAlert('Alert', 'Tous les champs sont obligatoires.', 'warning');
                return false;
            }

            if (!isValidDate(date1)) {
                showAlert('Erreur', 'La première date est invalide.', 'error');
                return false;
            }

            if (!isValidDate(date2)) {
                showAlert('Erreur', 'La deuxième date est invalide.', 'error');
                return false;
            }

            const startDate = new Date(date1);
            const endDate = new Date(date2);

            if (startDate > endDate) {
                showAlert('Erreur', 'La date de début ne peut pas être supérieur à la date de fin.', 'error');
                return false;
            }

            $('body').append('<div id="preloader_ch"><div class="spinner_preloader_ch"></div></div>');

            $.ajax({
                url: '/api/imp_fac_assurance',
                method: 'GET',
                data: { assurance_id, date1, date2 },
                success: function(response) {
                    $('#preloader_ch').remove();
                    const { societes, assurance } = response;
                    if (societes.length > 0) {
                        generatePDFInvoice_Fac_Assurance(societes, assurance, date1, date2);
                    } else {
                        showAlert('Informations', "Aucune facture n'a été trouvée pour cette période", 'info');
                    }
                },
                error: function() {
                    $('#preloader_ch').remove();
                    showAlert('Alert', 'Une erreur est survenue.', 'error');
                }
            });
        }

        function imp_fac_assurance_bordo() {
            const assurance_id = $('#assurance_id').val().trim();
            const date1 = $('#date1').val().trim();
            const date2 = $('#date2').val().trim();

            if (!assurance_id || !date1 || !date2) {
                showAlert('Alert', 'Tous les champs sont obligatoires.', 'warning');
                return false;
            }

            if (!isValidDate(date1)) {
                showAlert('Erreur', 'La première date est invalide.', 'error');
                return false;
            }

            if (!isValidDate(date2)) {
                showAlert('Erreur', 'La deuxième date est invalide.', 'error');
                return false;
            }

            const startDate = new Date(date1);
            const endDate = new Date(date2);

            if (startDate > endDate) {
                showAlert('Erreur', 'La date de début ne peut pas être supérieur à la date de fin.', 'error');
                return false;
            }

            $('body').append('<div id="preloader_ch"><div class="spinner_preloader_ch"></div></div>');

            $.ajax({
                url: '/api/imp_fac_assurance_bordo',
                method: 'GET',
                data: { assurance_id, date1, date2 },
                success: function(response) {
                    $('#preloader_ch').remove();
                    const { societes, assurance } = response;
                    if (societes.length > 0) {
                        generatePDFInvoice_Fac_Assurance_Bordo(societes, assurance, date1, date2);
                    } else {
                        showAlert('Informations', "Aucune facture n'a été trouvée pour cette période", 'info');
                    }
                },
                error: function() {
                    $('#preloader_ch').remove();
                    showAlert('Alert', 'Une erreur est survenue.', 'error');
                }
            });
        }

        function generatePDFInvoice_Fac_Assurance(societes,assurance,date1,date2) {

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'l', unit: 'mm', format: 'a4' });

            const pdfFilename = "FACTURE EMISE du " + formatDate(date1) + " au " + formatDate(date2);
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

        function generatePDFInvoice_Fac_Assurance_Bordo(societes,assurance,date1,date2) {

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });

            const pdfFilename = "BORDEREAUX DES FACTURES EMISES du " + formatDate(date1) + " au " + formatDate(date2);
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


