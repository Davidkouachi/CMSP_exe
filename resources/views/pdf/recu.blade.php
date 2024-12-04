<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture avec jsPDF</title>
    <script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>
    <style>
        body {
            font-family: sans-serif;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .button-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="button-container">
            <button onclick="generatePDF()" class="button">Télécharger la Facture</button>
        </div>
    </div>

<script>
    function generatePDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Informations de l'entreprise
        doc.setFontSize(10);
        doc.setFont("Helvetica", "bold");
        // Texte de l'entreprise
        const title = "ESPACE MEDICO SOCIAL LA PYRAMIDE DU COMPLEXE";
        const titleWidth = doc.getTextWidth(title);
        const titleX = (doc.internal.pageSize.getWidth() - titleWidth) / 2;
        doc.text(title, titleX, 20);
        // Texte de l'adresse
        doc.setFont("Helvetica", "normal");
        const address = "Abidjan Yopougon Selmer, Non loin du complexe sportif Jesse-Jackson - 04 BP 1523";
        const addressWidth = doc.getTextWidth(address);
        const addressX = (doc.internal.pageSize.getWidth() - addressWidth) / 2;
        doc.text(address, addressX, 25);
        // Texte du téléphone
        const phone = "Tél.: 20 24 44 70 / 20 21 71 92 - Cel.: 01 01 01 63 43 / 01 01 01 63 43";
        const phoneWidth = doc.getTextWidth(phone);
        const phoneX = (doc.internal.pageSize.getWidth() - phoneWidth) / 2;
        doc.text(phone, phoneX, 30);

        doc.setFontSize(10);
        doc.setFont("Helvetica", "normal");
        doc.text("Date: " + new Date().toLocaleDateString(), 15, 47);
        doc.text("Heure: " + new Date().toLocaleTimeString(), 15, 52);

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
        const rectY = 50 - (15 / 2) - paddingh; // Y du rectangle
        const rectWidth = titleRWidth + (paddingw * 2); // Largeur du rectangle
        const rectHeight = 12 + (paddingh * 2); // Hauteur du rectangle
        // Définir la couleur pour le cadre (noir)
        doc.setDrawColor(0, 0, 0);
        doc.rect(rectX, rectY, rectWidth, rectHeight); // Dessiner le rectangle
        // Ajouter le texte centré en gras
        doc.setFontSize(15);
        doc.setFont("Helvetica", "bold");
        doc.setTextColor(255, 0, 0); // Couleur du texte rouge
        doc.text(titleR, titleRX, 50); // Positionner le texte

        doc.setFontSize(10);
        doc.setFont("Helvetica", "bold");
        doc.setTextColor(0, 0, 0);
        doc.text("N° Dossier : P436657", 160, 50);

        // Informations du service
        doc.setFontSize(12);
        doc.setFont("Helvetica", "bold");
        doc.setTextColor(0, 0, 0);
        doc.text("Medecin", 20, 65);

        doc.setFont("Helvetica", "normal");
        doc.setTextColor(0, 0, 0);
        doc.text(": Dr. Kouadio Sara", 60, 65);

        // Informations du service
        doc.setFontSize(12);
        doc.setFont("Helvetica", "bold");
        doc.setTextColor(0, 0, 0);
        doc.text("Service", 20, 72);

        doc.setFont("Helvetica", "normal");
        doc.setTextColor(0, 0, 0);
        doc.text(": Generaliste", 60, 72);

        // Informations du service
        doc.setFontSize(12);
        doc.setFont("Helvetica", "bold");
        doc.setTextColor(0, 0, 0);
        doc.text("Nom et Prénoms", 20, 79);

        doc.setFont("Helvetica", "normal");
        doc.setTextColor(0, 0, 0);
        doc.text(": David kouachi Chris Emmanuel", 60, 79);

        // Informations du service
        doc.setFontSize(12);
        doc.setFont("Helvetica", "bold");
        doc.setTextColor(225, 0, 0);
        doc.text("Matricule", 20, 86);

        doc.setFont("Helvetica", "normal");
        doc.setTextColor(225, 0, 0);
        doc.text(": 6843926448", 60, 86);

        // Informations du service
        doc.setFontSize(12);
        doc.setFont("Helvetica", "bold");
        doc.setTextColor(0, 0, 0);
        doc.text("Age", 20, 93);

        doc.setFont("Helvetica", "normal");
        doc.setTextColor(0, 0, 0);
        doc.text(": 43 an(s)", 60, 93);

        // Informations du service
        doc.setFontSize(12);
        doc.setFont("Helvetica", "bold");
        doc.setTextColor(0, 0, 0);
        doc.text("Domicile", 20, 100);

        doc.setFont("Helvetica", "normal");
        doc.setTextColor(0, 0, 0);
        doc.text(": Cocody, rivera", 60, 100);

        // Informations du service
        doc.setFontSize(12);
        doc.setFont("Helvetica", "bold");
        doc.setTextColor(0, 0, 0);
        doc.text("Contact", 20, 107);

        doc.setFont("Helvetica", "normal");
        doc.setTextColor(0, 0, 0);
        doc.text(": 0585782723", 60, 107);

        // Informations du service
        doc.setFontSize(12);
        doc.setFont("Helvetica", "bold");
        doc.setTextColor(225, 0, 0);
        doc.text("Assurance", 20, 114);

        doc.setFont("Helvetica", "normal");
        doc.setTextColor(225, 0, 0);
        doc.text(": SOGEMAD", 60, 114);

        // Informations du service
        doc.setFontSize(12);
        doc.setFont("Helvetica", "bold");
        doc.setTextColor(225, 0, 0);
        doc.text("Matricule", 20, 121);

        doc.setFont("Helvetica", "normal");
        doc.setTextColor(225, 0, 0);
        doc.text(": S00061001460702", 60, 121);

        doc.setFontSize(30);
        doc.setLineWidth(0.5);
        doc.line(10, 128, 200, 128);

        doc.setFontSize(20);
        doc.setFont("Helvetica", "bold");
        doc.setTextColor(255, 0, 0);
        const rendu = "Compte Rendu";
        const titleRr = doc.getTextWidth(rendu);
        const titlerendu = (doc.internal.pageSize.getWidth() - titleRr) / 2;
        doc.text(rendu, titlerendu, 140);
        // Dessiner une ligne sous le texte pour le souligner
        const underlineY = 142; // Ajustez cette valeur selon vos besoins
        doc.setDrawColor(255, 0, 0);
        doc.setLineWidth(0.5); // Épaisseur de la ligne
        doc.line(titlerendu, underlineY, titlerendu + titleRr, underlineY);

        // Informations du service
        doc.setFontSize(10);
        doc.setFont("Helvetica", "bold");
        doc.setTextColor(0, 0, 0);
        doc.text("TA", 20, 155);

        doc.setFont("Helvetica", "normal");
        doc.setTextColor(0, 0, 0);
        doc.text(": ..........", 35, 155);

        // Informations du service
        doc.setFontSize(10);
        doc.setFont("Helvetica", "bold");
        doc.setTextColor(0, 0, 0);
        doc.text("BD", 60, 155);

        doc.setFont("Helvetica", "normal");
        doc.setTextColor(0, 0, 0);
        doc.text(": ..........", 75, 155);

        // Informations du service
        doc.setFontSize(10);
        doc.setFont("Helvetica", "bold");
        doc.setTextColor(0, 0, 0);
        doc.text("BG", 100, 155);

        doc.setFont("Helvetica", "normal");
        doc.setTextColor(0, 0, 0);
        doc.text(": ..........", 115, 155);

        // Informations du service
        doc.setFontSize(10);
        doc.setFont("Helvetica", "bold");
        doc.setTextColor(0, 0, 0);
        doc.text("Poids", 20, 165);

        doc.setFont("Helvetica", "normal");
        doc.setTextColor(0, 0, 0);
        doc.text(": ..........", 35, 165);

        // Informations du service
        doc.setFontSize(10);
        doc.setFont("Helvetica", "bold");
        doc.setTextColor(0, 0, 0);
        doc.text("Pouls", 60, 165);

        doc.setFont("Helvetica", "normal");
        doc.setTextColor(0, 0, 0);
        doc.text(": ..........", 75, 165);

        // Informations du service
        doc.setFontSize(10);
        doc.setFont("Helvetica", "bold");
        doc.setTextColor(0, 0, 0);
        doc.text("Pouls", 100, 165);

        doc.setFont("Helvetica", "normal");
        doc.setTextColor(0, 0, 0);
        doc.text(": ..........", 115, 165);

        // Informations du service
        doc.setFontSize(10);
        doc.setFont("Helvetica", "bold");
        doc.setTextColor(0, 0, 0);
        doc.text("Pouls", 140, 165);

        doc.setFont("Helvetica", "normal");
        doc.setTextColor(0, 0, 0);
        doc.text(": ..........", 155, 165);

        // Informations du service
        doc.setFontSize(10);
        doc.setFont("Helvetica", "bold");
        doc.setTextColor(0, 0, 0);
        doc.text("Temp", 20, 175);

        doc.setFont("Helvetica", "normal");
        doc.setTextColor(0, 0, 0);
        doc.text(": ..........", 35, 175);

        doc.setFontSize(20);
        doc.setFont("Helvetica", "bold");
        doc.setTextColor(0, 0, 0);
        const motif = "Motif";
        const titleRm = doc.getTextWidth(motif);
        const titlemotif = (doc.internal.pageSize.getWidth() - titleRm) / 2;
        doc.text(motif, titlemotif, 190);
        // Dessiner une ligne sous le texte pour le souligner
        const underlineYm = 192; // Ajustez cette valeur selon vos besoins
        doc.setDrawColor(0, 0, 0);
        doc.setLineWidth(0.5); // Épaisseur de la ligne
        doc.line(titlemotif, underlineYm, titlemotif + titleRm, underlineYm);

        doc.output('dataurlnewwindow');
    }

</script>

</body>
</html>