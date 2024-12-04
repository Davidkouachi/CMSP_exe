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
            Accueil
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    <div class="row gx-3 mb-3">
        <div class="col-xxl-12 col-sm-12">
            <div class="card bg-3">
                <div class="card-body" style="background: rgba(0, 0, 0, 0.7);">
                    <div class="py-4 px-3 text-white">
                        <h6>Bienvenue,</h6>
                        <h2>{{Auth::user()->sexe.'. '.Auth::user()->name}}</h2>
                        <h5>Horaires des Médecins.</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-3 mb-3" >
        <div class="col-sm-12">
            <div class="card mb-3">
                <div class="card-body" style="margin-top: -30px;">
                    <div class="custom-tabs-container">
                        <ul class="nav nav-tabs justify-content-center bg-primary bg-2" id="customTab4" role="tablist" style="background: rgba(0, 0, 0, 0.7);">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active text-white" id="tab-twoAAAN" data-bs-toggle="tab" href="#twoAAAN" role="tab" aria-controls="twoAAAN" aria-selected="false" tabindex="-1">
                                    <i class="ri-dossier-line me-2"></i>
                                    Nouvel horaire
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-white" id="tab-twoAAAL" data-bs-toggle="tab" href="#twoAAAL" role="tab" aria-controls="twoAAAL" aria-selected="false" tabindex="-1">
                                    <i class="ri-dossier-line me-2"></i>
                                    Liste des rendez-vous
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane active show fade" id="twoAAAN" role="tabpanel" aria-labelledby="tab-twoAAAN">
                                <div class="card-header">
                                    <h5 class="card-title text-center">Nouvel Horaire</h5>
                                </div>
                                <div class="row gx-3 justify-content-center align-items-center mb-4">
                                    <div class="col-12">
                                        <div class=" mb-0">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <a class="d-flex align-items-center flex-column">
                                                        <img src="{{asset('assets/images/docteur.png')}}" class="img-7x rounded-circle">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6" id="div_medecin" >
                                        <div class="mb-3 text-center">
                                            <label class="form-label">Medecin</label>
                                            <select class="form-select text-center select2" id="medecin_id"></select>
                                        </div>
                                    </div>
                                </div>
                                <div id="div_Horaire" style="display: none;">
                                    <div class="mb-3 p-2" >
                                        <div class="row gx-3 justify-content-center align-items-center">
                                            <div class="col-12">
                                                <div class="row gx-3 justify-content-center align-items-center">
                                                    <div class="col-12 mb-3 text-center">
                                                        <button type="button" id="add_horaire" class="btn btn-info">
                                                            <i class="ri-sticky-note-add-line"></i>
                                                            Ajouter un horaire
                                                        </button>
                                                    </div>
                                                    <div class="col-12" id="contenu_horaire">

                                                    </div>
                                                    <div class="row gx-3" id="div_btn_horaire" style="display: none;">
                                                        <div class="col-12 mb-3 text-center">
                                                            <button type="button" id="btn_eng" class="btn btn-success">
                                                                Enregistrer
                                                                <i class="ri-send-plane-fill"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="twoAAAL" role="tabpanel" aria-labelledby="tab-twoAAAL">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="card-title">
                                        Liste des Rendez-Vous
                                    </h5>
                                    <div class="d-flex">
                                        <a id="btn_refresh_table_rdv" class="btn btn-outline-info ms-auto">
                                            <i class="ri-loop-left-line"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body row gx-3">
                                    {{-- <div class="col-12">
                                        <div class=" mb-0">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <a class="d-flex align-items-center flex-column">
                                                        <img src="{{asset('assets/images/rdv1.png')}}" class="img-7x rounded-circle border border-2">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table id="Table_day" class="table table-hover table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>N°</th>
                                                        <th>Patient</th>
                                                        <th>Contact</th>
                                                        <th>Médecin</th>
                                                        <th>Spécialité</th>
                                                        <th>Rdv prévu</th>
                                                        <th>Statut</th>
                                                        <th>Date de création</th>
                                                        <th>Actions</th>
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
        </div>
    </div>
    <div class="row gx-3 mb-3">
        <div class="col-sm-12 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row gx-3">
                        <div class="col-12">
                            <div class="mb-3">
                                <button id="btn_refresh" class="btn btn-outline-primary">
                                    <i class="ri-loop-left-line" ></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Médecins</label>
                                <select class="form-select select2" id="rech_medecin">
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Spécialités</label>
                                <select class="form-select select2" id="rech_specialite">
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Jours</label>
                                <select class="form-select select2" id="rech_jour">
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Périodes</label>
                                <select class="form-select" id="rech_periode">
                                    <option selected value="tout">Tout</option>
                                    <option value="Matin">Matin</option>
                                    <option value="Soir">Soir</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 row gx-3 d-flex mb-3" id="table" style="display: none;" >
            
        </div>
        <div id="message" style="display: none;" class="mb-3">
            <p class="text-center">Aucune donnée n'a été trouvée</p>
        </div>
        <div id="loader" style="display: none;" class="mb-3">
            <div class="d-flex justify-content-center align-items-center">
                <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                <strong>Chargement des données...</strong>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Detail_motif" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-body" id="modal_Detail_motif"></div>
    </div>
</div>

<div class="modal fade" id="Rdv_modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nouveau Rendez-Vous</h5>
            </div>
            <div class="modal-body">
                <form id="updateForm">
                    <input type="hidden" id="medecin_id_rdv">
                    <div class="mb-3">
                        <label class="form-label">Médecin</label>
                        <input readonly type="text" class="form-control" id="medecin_rdv">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Spécialité</label>
                        <input readonly type="text" class="form-control" id="specialite_rdv">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Patient</label>
                        <select class="form-select select2" id="patient_id_rdv"></select>
                    </div>
                    <div class="mb-3" id="div_date_rdv" style="display: none;">
                        <label class="form-label">Date</label>
                        <input type="datetime-local" class="form-control" id="date_rdv" placeholder="Saisie Obligatoire" min="{{ date('Y-m-d\TH:i') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motif</label>
                        <textarea class="form-control" id="motif_rdv" rows="3" style="resize: none;"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" id="close_M_rdv" data-bs-dismiss="modal">Fermer</button>
                <button style="display: none;" type="button" class="btn btn-success" id="btn_eng_rdv">Enregistrer</button>
            </div>
        </div>
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
                    <input type="hidden" id="id_rdvM">
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

<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>
<script src="{{asset('jsPDF-AutoTable/dist/jspdf.plugin.autotable.min.js')}}"></script>

@include('select2')

<script>
    $('#Rdv_modal').on('shown.bs.modal', function () {
        $('#patient_id_rdv').select2({
            theme: 'bootstrap',
            placeholder: 'Selectionner',
            language: {
                noResults: function() {
                    return "Aucun résultat trouvé";
                }
            },
            width: '100%',
            dropdownParent: $('#Rdv_modal'),
        });
    });
</script>

<script>
    $(document).ready(function() {

        select_medecin();
        rech_medecin();
        rech_specialite();
        rech_jour();
        list();
        select_patient();

        $("#add_horaire").on("click", add_select);
        $("#btn_eng").on("click", eng);
        $("#btn_eng_rdv").on("click", eng_rdv);
        $("#btn_update_rdv").on("click", update_rdv);
        $("#btn_refresh").on("click", refresh);
        $("#btn_delete_rdv").on("click", delete_rdv);

        $('#btn_refresh_table_rdv').on('click', function () {
            $('#Table_day').DataTable().ajax.reload(null, false);
        });

        ["rech_medecin", "rech_specialite", "rech_jour", "rech_periode"].forEach(id => document.getElementById(id).addEventListener("change", list));

        $('#patient_id_rdv').on('change', function () { 
            const selectedValue = $(this).val(); // Récupère la valeur sélectionnée

            if (selectedValue !== '') {
                $('#div_date_rdv').show(); // Affiche le div si une valeur est sélectionnée
            } else {
                $('#div_date_rdv').hide(); // Cache le div si aucune valeur n'est sélectionnée
            }
        });


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

        function select_patient() {
            const selectElement = $('#patient_id_rdv');
            selectElement.empty(); // Vider le select avant de le remplir

            // Option par défaut
            selectElement.append(new Option('Selectionner', '', true, true));

            // Requête pour charger les patients
            fetch('/api/name_patient_reception')
                .then(response => response.json())
                .then(data => {
                    if (data && Array.isArray(data.name)) {
                        data.name.forEach(item => {
                            const option = new Option(item.np, item.id);
                            selectElement.append(option);
                        });
                    } else {
                        console.error('Données inattendues :', data);
                    }
                })
                .catch(error => console.error('Erreur lors du chargement des patients:', error));
        }

        function select_medecin() {
            const $selectElement = $('#medecin_id'); // Déclarez l'élément en jQuery
            // Clear existing options
            $selectElement.empty();
            const defaultOption = $('<option>', {
                value: '',
                text: 'Sélectionner un medecin'
            });
            $selectElement.append(defaultOption);

            $.ajax({
                url: '/api/select_list_medecin',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    const medecins = data.medecin;
                    medecins.forEach((item) => {
                        const option = $('<option>', {
                            value: item.id, // Ensure 'id' is the correct key
                            text: `Dr. ${item.name}` // Ensure 'name' is the correct key
                        });
                        $selectElement.append(option);
                    });
                },
                error: function(error) {
                    console.error('Erreur lors du chargement des medecins:', error);
                }
            });

            $selectElement.on('change', function() {
                const id = $(this).val();
                if (id) {
                    const url = '/api/select_jours';
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            const jours = data.jour;
                            const $contenuDiv = $('#contenu_horaire');
                            $contenuDiv.empty();

                            addSelectHoraire($contenuDiv, jours);

                            $('#div_Horaire').show();
                        },
                        error: function(error) {
                            console.error('Erreur lors du chargement des horaires:', error);
                        }
                    });
                } else {
                    const $contenuDiv = $('#contenu_horaire');
                    $contenuDiv.empty();
                    $('#div_Horaire').hide();
                }
            });
        }

        function rech_medecin()
        {
            const selectElementRech = document.getElementById('rech_medecin');
            // Clear existing options
            selectElementRech.innerHTML = '';
            const defaultOption2 = document.createElement('option');
            defaultOption2.value = 'tout';
            defaultOption2.textContent = 'Tout';
            selectElementRech.appendChild(defaultOption2);

            fetch('/api/select_list_medecin')
                .then(response => response.json())
                .then(data => {
                    const medecins = data.medecin;
                    medecins.forEach((item, index) => {
                        const option2 = document.createElement('option');
                        option2.value = `${item.id}`; // Ensure 'id' is the correct key
                        option2.textContent = `Dr. ${item.name}`; // Ensure 'nom' is the correct key
                        selectElementRech.appendChild(option2);
                    });
                })
                .catch(error => console.error('Erreur lors du chargement des societes:', error));
        }

        function rech_specialite()
        {
            const selectElementRech = document.getElementById('rech_specialite');
            // Clear existing options
            selectElementRech.innerHTML = '';
            const defaultOption2 = document.createElement('option');
            defaultOption2.value = 'tout';
            defaultOption2.textContent = 'Tout';
            selectElementRech.appendChild(defaultOption2);

            fetch('/api/select_specialite')
                .then(response => response.json())
                .then(data => {
                    const rechs = data.typeacte;
                    rechs.forEach((item, index) => {
                        const option2 = document.createElement('option');
                        option2.value = `${item.id}`; // Ensure 'id' is the correct key
                        option2.textContent = `${item.nom}`; // Ensure 'nom' is the correct key
                        selectElementRech.appendChild(option2);
                    });
                })
                .catch(error => console.error('Erreur lors du chargement des societes:', error));
        }

        function rech_jour()
        {
            const selectElementRech = document.getElementById('rech_jour');
            // Clear existing options
            selectElementRech.innerHTML = '';
            const defaultOption2 = document.createElement('option');
            defaultOption2.value = 'tout';
            defaultOption2.textContent = 'Tout';
            selectElementRech.appendChild(defaultOption2);

            fetch('/api/select_jour')
                .then(response => response.json())
                .then(data => {
                    const rechs = data.rech;
                    rechs.forEach((item, index) => {
                        const option2 = document.createElement('option');
                        option2.value = `${item.id}`; // Ensure 'id' is the correct key
                        option2.textContent = `${item.jour}`; // Ensure 'nom' is the correct key
                        selectElementRech.appendChild(option2);
                    });
                })
                .catch(error => console.error('Erreur lors du chargement des societes:', error));
        }

        function addSelectHoraire($contenuDiv, jours) {

            // Créer le groupe de contrôle contenant le select et le bouton supprimer
            const div = $('<div>', { class: 'mb-3' });

            div.html(`
                <div class="input-group">
                    <select class="form-select jour-select w-15">
                        <option value="">Jour</option>
                        ${jours.map(item => 
                            `<option value="${item.id}">${item.jour}</option>`
                        ).join('')}
                    </select>
                    <select class="form-select periode-select w-15">
                        <option value="">Période</option>
                        <option value="Matin">Matin</option>
                        <option value="Soir">Soir</option>
                    </select>
                    <span class="input-group-text">Heure debut : </span>
                    <input type="time" class="form-control heure-debut-input">
                    <span class="input-group-text">Heure Fin : </span>
                    <input type="time" class="form-control heure-fin-input">
                    <button class="btn btn-outline-danger suppr-btn">Supprimer</button>
                </div>
            `);

            // Ajouter l'élément dans le parent (contenu div)
            $contenuDiv.append(div);

            // Vérifier si le contenu horaire est présent
            checkContenuHoraire();

            // Ajouter un event listener pour le bouton supprimer
            div.find('.suppr-btn').on('click', function() {
                div.remove(); // Supprimer l'élément div parent
                checkContenuHoraire();
            });
        }

        function checkContenuHoraire() {
            const $contenuDiv = $('#contenu_horaire');
            const $divBtn = $('#div_btn_horaire');

            // Afficher le bouton si #contenu_horaire contient des éléments, sinon le cacher
            if ($contenuDiv.html().trim() !== "") {
                $divBtn.show();
            } else {
                $divBtn.hide();
            }
        }

        function add_select() {
            const $contenuDiv = $('#contenu_horaire');
            const id = $('#medecin_id').val();

            if (id === '') {
                showAlert("ALERT", "Sélectionner un médecin SVP.", "warning");
                return false;
            }

            const url = '/api/select_jours';
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    const jours = data.jour;
                    addSelectHoraire($contenuDiv, jours); // Ajouter le premier select
                },
                error: function(error) {
                    console.error('Erreur lors du chargement des données:', error);
                }
            });
        }

        function Verification() {

            let formIsValid = true;
            const medecin_id = document.getElementById('medecin_id').value;

            if (medecin_id === '') {
                showAlert("ALERT", "Veuillez sélectionner un médecin SVP.", "warning");
                formIsValid = false;
                return false;
            }

            const contenuDiv = document.getElementById('contenu_horaire');
            if (contenuDiv.innerHTML.trim() === "") {
                showAlert("ALERT", 'Aucun horaire n\'a été ajouté.', "warning");
                formIsValid = false;
                return false;
            }

            const jourSelects = document.querySelectorAll('.jour-select');
            const periodeSelects = document.querySelectorAll('.periode-select');
            const heureDebutInputs = document.querySelectorAll('.heure-debut-input');
            const heureFinInputs = document.querySelectorAll('.heure-fin-input');

            jourSelects.forEach((item, index) => {
                const jourValue = item.value;
                const periodeValue = periodeSelects[index].value;
                const heureDebut = heureDebutInputs[index].value;
                const heureFin = heureFinInputs[index].value;

                if (!jourValue) {
                    showAlert("ALERT", 'Veuillez sélectionner un jour pour chaque horaire.', "warning");
                    formIsValid = false;
                    return false;
                }

                if (!periodeValue) {
                    showAlert("ALERT", 'Veuillez sélectionner une période (Matin ou Soir).', "warning");
                    formIsValid = false;
                    return false;
                }

                if (!heureDebut) {
                    showAlert("ALERT", 'Veuillez sélectionner une heure de début.', "warning");
                    formIsValid = false;
                    return false;
                }

                if (!heureFin) {
                    showAlert("ALERT", 'Veuillez sélectionner une heure de fin.', "warning");
                    formIsValid = false;
                    return false;
                }

                // Vérification que l'heure de fin est bien après l'heure de début
                if (heureDebut >= heureFin) {
                    showAlert("ALERT", 'L\'heure de fin doit être supérieure à l\'heure de début.', "warning");
                    formIsValid = false;
                    return false;
                }
            });

            return formIsValid;
        }

        function eng()
        {

            try {
                const isValid = Verification();
                if (!isValid) {
                    return false;
                }
            } catch (error) {
                showAlert("ALERT", 'Veuillez bien definir tous les horaires SVP', "warning");
                return false;
            }
            
            const selectionsHoraire = [];
            const jourSelects = document.querySelectorAll('.jour-select');
            const periodeSelects = document.querySelectorAll('.periode-select');
            const heureDebutInputs = document.querySelectorAll('.heure-debut-input');
            const heureFinInputs = document.querySelectorAll('.heure-fin-input');

            jourSelects.forEach((item, index) => {
                const selectedJourOption = item.options[item.selectedIndex];
                const jourId = selectedJourOption.value; // ID du jour sélectionné

                const selectedPeriodeOption = periodeSelects[index].options[periodeSelects[index].selectedIndex];
                const periode = selectedPeriodeOption.value; // Période sélectionnée

                const heureDebut = heureDebutInputs[index].value; // Heure de début sélectionnée
                const heureFin = heureFinInputs[index].value;     // Heure de fin sélectionnée

                selectionsHoraire.push({
                    jour_id: jourId,
                    periode: periode,
                    heure_debut: heureDebut,
                    heure_fin: heureFin,
                });
            });

            const medecin_id = document.getElementById('medecin_id').value;

            if (medecin_id == '') {
                showAlert("ALERT", 'Veuillez selectionner un médecin SVP.', "warning");
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
                url: '/api/new_horaire',
                method: 'GET',
                data:{
                    selections: selectionsHoraire,
                    medecin_id: medecin_id,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    
                    if (response.success) {

                        $('#medecin_id').val('').trigger('change');
                        document.getElementById('div_Horaire').style.display = "none";

                        list(); 
                        $('#Table_day').DataTable().ajax.reload(null, false);  

                        // showAlert("ALERT", 'Enregistrement éffectué', "success");

                        let timerInterval;
                        Swal.fire({
                            title: "Opération éffectuée, Veuillez patienter un instant s'il vous plaît",
                            timer: 2000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                                const timer = Swal.getPopup().querySelector("b");
                                timerInterval = setInterval(() => {
                                    timer.textContent = `${Swal.getTimerLeft()}`;
                                }, 100);
                            },
                            willClose: () => {
                                clearInterval(timerInterval);
                            }
                        }).then((result) => {
                            if (result.dismiss === Swal.DismissReason.timer) {
                                location.reload(); // Rafraîchir la page après le timer
                            }
                        });

                    } else if (response.error) {
                        showAlert("ERREUR", 'Une erreur est survenue', "error");
                    } else if (response.json) {
                        showAlert("ERREUR", 'Invalid selections format', "error");
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
        };

        function refresh()
        {
            document.getElementById('rech_medecin').value = "tout";
            document.getElementById('rech_specialite').value = "tout";
            document.getElementById('rech_jour').value = "tout";
            document.getElementById('rech_periode').value = "tout";

            list();
        }

        function list() 
        {
            const tableBody = document.getElementById('table');
            const messageDiv = document.getElementById('message');
            const loaderDiv = document.getElementById('loader');

            // Récupération des filtres
            const medecin = document.getElementById('rech_medecin').value;
            const specialite = document.getElementById('rech_specialite').value;
            const jour = document.getElementById('rech_jour').value;
            const periode = document.getElementById('rech_periode').value;

            const url = `/api/list_horaire/${medecin}/${specialite}/${jour}/${periode}`;

            // Afficher le loader
            messageDiv.style.display = 'none';
            tableBody.style.display = 'none';
            loaderDiv.style.display = 'block';

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const medecins = data.medecins;
                    tableBody.innerHTML = '';

                    if (medecins.length > 0) {

                        messageDiv.style.display = 'none';
                        loaderDiv.style.display = 'none';
                        tableBody.style.display = 'block';

                        medecins.forEach((medecin) => {

                            let horairesList = '';

                            if (medecin.horaires.length > 0) {
                                horairesList = medecin.horaires.map(horaire => `
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>${horaire.jour} ${horaire.periode}</div>
                                        <div>${horaire.heure_debut} à ${horaire.heure_fin}</div>
                                        <div>
                                            <a id="deleteH-${horaire.id}" class="btn btn-sm btn-outline-danger rounded-5">
                                                <i class="ri-close-fill"></i>
                                            </a>
                                        </div>
                                    </li>
                                `).join('');
                            } else {
                                // Si aucun horaire n'est trouvé, afficher ce message
                                horairesList = `
                                    <li class="list-group-item text-center">
                                        Aucun horaire n'a été trouvé.
                                    </li>
                                `;
                            }

                            const div = document.createElement('div');
                            div.classList.add('col-xxl-3', 'col-sm-6', 'col-12', 'mb-3');

                            let buttonRdv = '';

                            if (medecin.horaires.length > 0) {
                                buttonRdv = `
                                    <a class="btn btn-outline-success mb-3" data-bs-toggle="modal" data-bs-target="#Rdv_modal" id="rdv-${medecin.id}">
                                        <i class="ri-calendar-schedule-line"></i>
                                        Prendre Rendez-Vous
                                    </a>
                                `;
                            }

                            div.innerHTML = `
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <a class="d-flex align-items-center flex-column">
                                                <img src="{{asset('assets/images/docteur.png')}}" class="img-7x rounded-circle mb-3">
                                                <h6>Dr. ${medecin.name}</h6>
                                                <h6 class="text-primary">${medecin.specialité}</h6>
                                                ${buttonRdv}
                                                <ul class="list-group">
                                                    ${horairesList}
                                                </ul>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            `;

                            tableBody.appendChild(div);

                            medecin.horaires.forEach(horaire => {
                                const boutonModif = document.getElementById(`deleteH-${horaire.id}`);
                                boutonModif.addEventListener('click', () => {

                                    var preloader_ch = `
                                        <div id="preloader_ch">
                                            <div class="spinner_preloader_ch"></div>
                                        </div>
                                    `;
                                    // Add the preloader to the body
                                    document.body.insertAdjacentHTML('beforeend', preloader_ch);
                                    
                                    $.ajax({
                                        url: `/api/update_horaire/${horaire.id}`,
                                        method: 'GET',
                                        success: function(response) {

                                            var preloader = document.getElementById('preloader_ch');
                                            if (preloader) {
                                                preloader.remove();
                                            }
                                            
                                            if (response.success) {
                                                refresh();   
                                                showAlert("ALERT", 'Mise à jour éffectué', "success");

                                            } else if (response.error) {
                                                showAlert("ERREUR", 'Une erreur est survenue', "error");
                                            }

                                        },
                                        error: function() {
                                            var preloader = document.getElementById('preloader_ch');
                                            if (preloader) {
                                                preloader.remove();
                                            }

                                            showAlert("ERREUR", 'Une erreur est survenue lors de la mise à jour', "error");
                                        }
                                    });
                                });
                            });

                            const rdvButton = document.getElementById(`rdv-${medecin.id}`);
                            if (rdvButton) {
                                rdvButton.addEventListener('click', () => {

                                    document.getElementById('medecin_id_rdv').value = `${medecin.id}`;
                                    document.getElementById('medecin_rdv').value = `Dr. ${medecin.name}`;
                                    document.getElementById('specialite_rdv').value = `${medecin.specialité}`;

                                    document.getElementById('btn_eng_rdv').style.display = "none";

                                    $('#date_rdv').val('');
                                    $('#patient_id_rdv').val('').trigger('change');
                                    $('#patient_rdv').val('');
                                    $('#motif_rdv').val('');

                                    {{-- const allowedDays = medecin.horaires.map(horaire => horaire.jour);

                                    const dateInput = document.getElementById('date_rdv');
                                    let previousDate = dateInput.value; // Track previous date

                                    dateInput.addEventListener('change', function(event) {

                                        const selectedDate = new Date(event.target.value);
                                        const selectedDay = selectedDate.getDay();

                                        const dayMapping = {
                                            'DIMANCHE': 0,
                                            'LUNDI': 1,
                                            'MARDI': 2,
                                            'MERCREDI': 3,
                                            'JEUDI': 4,
                                            'VENDREDI': 5,
                                            'SAMEDI': 6
                                        };

                                        const isValidDay = allowedDays.some(day => dayMapping[day] === selectedDay);

                                        if (!isValidDay) {
                                            dateInput.value = previousDate;
                                            showAlert("ALERT", 'Veuillez sélectionner un jour valide selon les horaires du médecin.', "info");
                                        } else {
                                            previousDate = dateInput.value;
                                        }
                                    }); --}}

                                    const allowedDays = medecin.horaires.map(horaire => horaire.jour);
                                    const heureDays = medecin.horaires.reduce((map, horaire) => {
                                        const dayMapping = {
                                            'DIMANCHE': 0,
                                            'LUNDI': 1,
                                            'MARDI': 2,
                                            'MERCREDI': 3,
                                            'JEUDI': 4,
                                            'VENDREDI': 5,
                                            'SAMEDI': 6
                                        };
                                        map[dayMapping[horaire.jour]] = horaire.heure_debut;
                                        return map;
                                    }, {});

                                    const dateInput = document.getElementById('date_rdv');
                                    let previousDate = dateInput.value; // Track previous date

                                    dateInput.addEventListener('change', function(event) {
                                        const selectedDate = new Date(event.target.value);
                                        const selectedDay = selectedDate.getDay(); // Numeric day of the week

                                        // Check if the selected day is valid
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
                                            dateInput.value = "";
                                            showAlert("ALERT", 'Veuillez sélectionner un jour valide selon les horaires du médecin.', "info");
                                            document.getElementById('btn_eng_rdv').style.display = "none";
                                        } else {
                                            // Add the hour to the selected date if valid
                                            const selectedHour = heureDays[selectedDay];
                                            const dateString = selectedDate.toISOString().split('T')[0];
                                            dateInput.value = `${dateString} ${selectedHour}`;
                                            previousDate = dateInput.value; // Update previous date

                                            document.getElementById('btn_eng_rdv').style.display = "block";
                                        }
                                    });

                                });
                            }

                        });

                    } else {
                        messageDiv.style.display = 'block';
                        tableBody.style.display = 'none';
                        loaderDiv.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données:', error);
                    messageDiv.style.display = 'block';
                    tableBody.style.display = 'none';
                    loaderDiv.style.display = 'none';
                });
        }

        function eng_rdv() {
            const medecin_id = $('#medecin_id_rdv').val();
            const patient_id = $('#patient_id_rdv').val();
            const date_rdv = $('#date_rdv').val();
            const motif_rdv = $('#motif_rdv').val();

            // Vérifier que tous les champs sont remplis
            if (!medecin_id.trim() || !patient_id.trim() || !date_rdv.trim() || !motif_rdv.trim()) {
                showAlert("ALERT", 'Veuillez remplir tous les champs.', "warning");
                return false;
            }

            // Fermer le modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('Rdv_modal'));
            modal.hide();

            // Ajouter le préchargeur
            const preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $('body').append(preloader_ch);

            // Envoyer la requête AJAX
            $.ajax({
                url: '/api/new_rdv',
                method: 'GET',
                data: {
                    medecin_id: medecin_id,
                    patient_id: patient_id,
                    date: date_rdv,
                    motif: motif_rdv,
                },
                success: function(response) {
                    $('#preloader_ch').remove(); // Supprimer le préchargeur

                    if (response.success) {
                        $('#Table_day').DataTable().ajax.reload(null, false);
                        count_rdv_two_day();
                        showAlert("ALERT", 'Enregistrement éffectué', "success");
                    } else if (response.error) {
                        showAlert("ERREUR", 'Une erreur est survenue', "error");
                    } else if (response.existe) {
                        showAlert("Alert", 'Cet patient a déjà pris Rendez-Vous avec ce Médecin a la même Date. veuillez selectionner  une autre date SVP!!!', "info");
                    }
                },
                error: function() {
                    $('#preloader_ch').remove(); // Supprimer le préchargeur en cas d'erreur
                    showAlert("ERREUR", 'Une erreur est survenue lors de l\'enregistrement', "error");
                }
            });
        }

        $('#Table_day').DataTable({

            processing: true,
            serverSide: false,
            ajax: {
                url: `/api/list_rdv`,
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
                    data: 'patient', 
                    render: (data, type, row) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{ asset('/assets/images/rdv1.png') }}" class="img-2x rounded-circle border border-1">
                        </a>
                        ${data}
                    </div>`,
                    searchable: true, 
                },
                {
                    data: 'patient_tel',
                    render: (data, type, row) => {
                        return data ? `+225 ${data}` : 'Néant';
                    },
                    searchable: true,
                },
                {
                    data: 'medecin',
                    render: (data, type, row) => {
                        return data ? `Dr. ${data}` : 'Néant';
                    },
                    searchable: true,
                },
                { 
                    data: 'specialite',
                    searchable: true, 
                },
                { 
                    data: 'date',
                    render: formatDateHeure,
                    searchable: true, 
                },
                {
                    data: 'statut',
                    render: (data, type, row) => `
                        <span class="badge ${data === 'en attente' ? 'bg-danger' : 'bg-success'}">
                            ${data === 'en attente' ? 'En Attente' : 'Terminer'}
                        </span>
                    `,
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
                        <div class="d-inline-flex gap-1" style="font-size:10px;">
                            <a class="btn btn-outline-warning btn-sm edit-btn" data-motif="${row.motif}" data-bs-toggle="modal" data-bs-target="#Detail_motif" id="motif">
                                <i class="ri-eye-line"></i>
                            </a>
                            ${row.statut == 'en attente' ? 
                            `<a class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#Modif_Rdv_modal" id="modif"
                                data-id="${row.id}"
                                data-date="${row.date}"
                                data-patient="${row.patient}"
                                data-motif="${row.motif}"
                                data-medecin="${row.medecin}"
                                data-specialite="${row.specialite}"
                                data-horaires='${JSON.stringify(row.horaires)}'>
                               <i class="ri-edit-line"></i>
                            </a>
                            <a class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#Mdelete" id="delete" data-id="${row.id}">
                                <i class="ri-delete-bin-line"></i>
                            </a>` :
                            `` }
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

            $('#Table_day').on('click', '#modif', function() {
                const id = $(this).data('id');
                const date = $(this).data('date');
                const patient = $(this).data('patient');
                const motif = $(this).data('motif');
                const medecin = $(this).data('medecin');
                const specialite = $(this).data('specialite');

                $('#id_rdvM').val(id);

                const today = new Date();
                const formattedToday = today.toISOString().split('T')[0];
                $('#date_rdvM').val(date).attr('min', formattedToday);

                $('#patient_rdvM').val(patient);
                $('#motif_rdvM').val(motif);
                $('#medecin_rdvM').val(medecin);
                $('#specialite_rdvM').val(specialite);

                const horairesData = $(this).data('horaires') || [];
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

            $('#Table_day').on('click', '#motif', function() {
                const motif = $(this).data('motif');
                // Handle the 'Delete' button click
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
                                                ${motif} 
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>     
                `;

                modal.appendChild(div);
            });

            $('#Table_day').on('click', '#delete', function() {
                const id = $(this).data('id');
                
                $('#Iddelete').val(id);
            });
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
                        $('#Table_day').DataTable().ajax.reload(null, false);
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
            const id = document.getElementById('id_rdvM').value;
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

                        $('#Table_day').DataTable().ajax.reload(null, false);
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

    });
</script>


@endsection
