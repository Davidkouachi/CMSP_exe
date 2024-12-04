@extends('app')

@section('titre', 'Nouvel Utilisateur')

@section('info_page')
<div class="app-hero-header d-flex align-items-center">
    <!-- Breadcrumb starts -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-bar-chart-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{route('index_accueil')}}">Espace Santé</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            Nouveau Médecin
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
                        <h6>MEDECINS</h6>
                        {{-- <h2>{{Auth::user()->sexe.'. '.Auth::user()->name}}</h2> --}}
                        <p>Accueil / Configuration / Nouveau Médécin</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-3" >
        <div class="col-sm-12">
            <div class="card mb-3 mt-3">
                <div class="card-body" style="margin-top: -30px;">
                    <div class="custom-tabs-container">
                        <ul class="nav nav-tabs justify-content-center bg-primary bg-2" id="customTab4" role="tablist" style="background: rgba(0, 0, 0, 0.7);">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active text-white" id="tab-twoAAAN" data-bs-toggle="tab" href="#twoAAAN" role="tab" aria-controls="twoAAAN" aria-selected="false" tabindex="-1">
                                    <i class="ri-user-add-line me-2"></i>
                                    Nouveau Médecin
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-white" id="tab-twoAAA" data-bs-toggle="tab" href="#twoAAA" role="tab" aria-controls="twoAAA" aria-selected="false" tabindex="-1">
                                    <i class="ri-contacts-line me-2"></i>
                                    Liste des Médecins
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane active show fade" id="twoAAAN" role="tabpanel" aria-labelledby="tab-twoAAAN">
                                <div class="card-header">
                                    <h5 class="card-title text-center">Formulaire Nouveau Médecin</h5>
                                </div>
                                <div class="card-header">
                                    <div class="text-center">
                                        <a class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/docteur.png')}}" class="img-7x rounded-circle">
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body" >
                                    <!-- Row starts -->
                                    <div class="row gx-3">
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Nom et Prénoms</label>
                                                <input type="text" class="form-control" id="nom" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Sexe</label>
                                                <select class="form-select" id="sexe">
                                                    <option value="">Selectionner</option>
                                                    <option value="Mr">Homme</option>
                                                    <option value="Mme">Femme</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" placeholder="Saisie Obligatoire">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Contact</label>
                                                <input type="tel" class="form-control" id="tel" placeholder="Saisie Obligatoire" maxlength="10">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Contact 2</label>
                                                <input type="tel" class="form-control" id="tel2" placeholder="facultatif" maxlength="10">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="adresse">Localisation</label>
                                                <input type="text" class="form-control" id="adresse" placeholder="Saisie Obligatoire">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Spécialité</label>
                                                <select class="form-select select2" id="specialite_id">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="mb-3 d-flex gap-2 justify-content-center">
                                                <button id="btn_eng" class="btn btn-success">
                                                    Enregistrer
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-sm-12" >
                                            <div class="mb-3" >
                                                <div id="div_alert" ></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Row ends -->
                                </div>
                            </div>
                            <div class="tab-pane fade" id="twoAAA" role="tabpanel" aria-labelledby="tab-twoAAA">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="card-title">
                                        Liste des Médecins
                                    </h5>
                                    <a id="btn_refresh_table" class="btn btn-outline-info ms-auto">
                                        <i class="ri-loop-left-line"></i>
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="">
                                        <div class="table-responsive">
                                            <table id="Table_day" class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">N°</th>
                                                        <th scope="col">Nom et Prénoms</th>
                                                        <th scope="col">Email</th>
                                                        <th scope="col">Matricule</th>
                                                        <th scope="col">Specialité</th>
                                                        <th scope="col">contact</th>
                                                        <th scope="col">Localisation</th>
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
                Voulez-vous vraiment supprimé ce Médecin
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

<div class="modal fade" id="Mmodif" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mise à jour</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateForm">
                    <div class="mb-3" id="alert_update">
                        
                    </div>
                    <input type="hidden" id="Id">
                    <div class="mb-3">
                        <label class="form-label">Nom et Prénoms</label>
                        <input type="text" class="form-control" id="nomModif" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sexe</label>
                        <select class="form-select" id="sexeModif">
                            <option value="Mr">Homme</option>
                            <option value="Mme">Femme</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="emailModif" placeholder="Saisie Obligatoire">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact</label>
                        <input type="tel" class="form-control" id="telModif" placeholder="Saisie Obligatoire" maxlength="10">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact 2</label>
                        <input type="tel" class="form-control" id="tel2Modif" placeholder="facultatif" maxlength="10">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Adresse</label>
                        <input type="text" class="form-control" id="adresseModif" placeholder="Saisie Obligatoire">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Specialité</label>
                        <select class="form-select select2" id="specialite_idModif"></select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="updateBtn">Mettre à jour</button>
            </div>
        </div>
    </div>
</div>

@include('select2')

<script>
    $('#Mmodif').on('shown.bs.modal', function () {
        $('#specialite_idModif').select2({
            theme: 'bootstrap',
            placeholder: 'Selectionner',
            language: {
                noResults: function() {
                    return "Aucun résultat trouvé";
                }
            },
            width: '100%',
            dropdownParent: $('#Mmodif'),
        });
    });
</script>

<script>
    $(document).ready(function() {

        select();
        select_modif();

        $("#btn_eng").on("click", eng);
        $("#updateBtn").on("click", updatee);
        $("#deleteBtn").on("click", deletee);

        $('#btn_refresh_table').on('click', function () {
            $('#Table_day').DataTable().ajax.reload();
        });

        var inputs = ['#tel', '#tel2', '#telModif', '#tel2Modif'];
        inputs.forEach(function(selector) {
            $(selector).on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, ''); // Allow only numbers
            });
        });

        function select() {
            const $selectElement = $('#specialite_id');
            $selectElement.empty();
            $selectElement.append('<option value="">Sélectionner une spécialité</option>');

            const $selectElement2 = $('#specialite_idModif');

            $.ajax({
                url: '/api/select_specialite',
                method: 'GET',
                success: function(response) {
                    const data = response.specialite;

                    data.forEach(item => {
                        $selectElement.append(`
                            <option value="${item.codespecialitemed}">${item.nomspecialite}</option>
                        `);
                        $selectElement2.append(`
                            <option value="${item.codespecialitemed}">${item.nomspecialite}</option>
                        `);
                    });
                },
                error: function() {
                    // showAlert('danger', 'Impossible de generer le code automatiquement');
                }
            });
        }

        function select_modif() {
            const $selectElement = $('#specialite_idModif');

            // Clear existing options
            $selectElement.empty();

            $.ajax({
                url: '/api/select_specialite',
                method: 'GET',
                success: function(response) {
                    const data = response.typeacte;
                    data.forEach(typeacte => {
                        $selectElement.append(`<option value="${typeacte.id}">${typeacte.nom}</option>`);
                    });
                },
                error: function() {
                    // showAlert('danger', 'Impossible de generer le code automatiquement');
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

        function eng() {
            const nom = $('#nom');
            const email = $('#email');
            const tel = $('#tel');
            const tel2 = $('#tel2');
            const sexe = $('#sexe');
            const adresse = $('#adresse');
            const specialite_id = $('#specialite_id');

            if (!nom.val().trim() || !email.val().trim() || !tel.val().trim() || !sexe.val().trim() || !adresse.val().trim() || !specialite_id.val().trim()) {
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.', 'warning');
                return false;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.val().trim())) {
                showAlert('Alert', 'Email incorrect.', 'warning');
                return false;
            }

            if (tel.val().length !== 10 || (tel2.val().trim() && tel2.val().length !== 10)) {
                showAlert('Alert', 'Contact incomplet.', 'warning');
                return false;
            }

            // Add preloader
            const preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $('body').append(preloader_ch);

            $.ajax({
                url: '/api/new_medecin',
                method: 'GET',
                data: {
                    nom: nom.val(),
                    email: email.val(),
                    tel: tel.val(),
                    tel2: tel2.val() || null,
                    adresse: adresse.val(),
                    sexe: sexe.val(),
                    specialite_id: specialite_id.val()
                },
                success: function(response) {
                    $('#preloader_ch').remove();

                    if (response.tel_existe) {
                        showAlert('Alert', 'Veuillez saisir autre numéro de téléphone s\'il vous plaît', 'warning');
                    } else if (response.email_existe) {
                        showAlert('Alert', 'Veuillez saisir autre email s\'il vous plaît', 'warning');
                    } else if (response.nom_existe) {
                        showAlert('Alert', 'Cet Médecin existe déjà.', 'warning');
                    } else if (response.success) {

                        nom.val('');
                        email.val('');
                        tel.val('');
                        tel2.val('');
                        adresse.val('');
                        sexe.val('');
                        specialite_id.val('').trigger('change');
                        
                        $('#Table_day').DataTable().ajax.reload();
                        showAlert('Succès', 'Opération éffectuée.', 'success');
                    } else if (response.error) {
                        showAlert('Erreur', 'Une erreur est survenue lors de l\'enregistrement.', 'error');
                    }
                },
                error: function() {
                    $('#preloader_ch').remove();
                    showAlert('Erreur', 'Une erreur est survenue lors de l\'enregistrement.', 'error');
                }
            });
        }

        $('#Table_day').DataTable({

            processing: true,
            serverSide: false,
            ajax: {
                url: `/api/list_medecin`,
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
                    data: 'name', 
                    render: (data, type, row) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{ asset('/assets/images/docteur.png') }}" class="img-2x rounded-circle border border-1">
                        </a>
                        Dr. ${data}
                    </div>`,
                    searchable: true, 
                },
                { 
                    data: 'email',
                    searchable: true,
                },
                { 
                    data: 'matricule',
                    searchable: true, 
                },
                { 
                    data: 'typeacte',
                    searchable: true, 
                },
                { 
                    data: 'tel', 
                    render: (data) => `+225 ${data}`,
                    searchable: true, 
                },
                { 
                    data: 'adresse',
                    searchable: true, 
                },
                {
                    data: null,
                    render: (data, type, row) => `
                        <div class="d-inline-flex gap-1" style="font-size:10px;">
                            <a class="btn btn-outline-info btn-sm edit-btn" data-id="${row.id}" data-name="${row.name}" data-email="${row.email}" data-tel="${row.tel}" data-tel2="${row.tel2}" data-adresse="${row.adresse}" data-sexe="${row.sexe}" data-specialite_id="${row.specialite_id}" data-bs-toggle="modal" data-bs-target="#Mmodif" id="modif">
                                <i class="ri-edit-box-line"></i>
                            </a>
                            <a class="btn btn-outline-danger btn-sm delete-btn" data-id="${row.id}" data-bs-toggle="modal" data-bs-target="#Mdelete" id="delete">
                                <i class="ri-delete-bin-line"></i>
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

            $('#Table_day').on('click', '#modif', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const email = $(this).data('email');
                const tel = $(this).data('tel');
                const tel2 = $(this).data('tel2');
                const adresse = $(this).data('adresse');
                const sexe = $(this).data('sexe');
                const specialite_id = $(this).data('specialite_id');
                
                $('#Id').val(id);
                $('#nomModif').val(name);
                $('#emailModif').val(email);
                $('#telModif').val(tel);
                $('#tel2Modif').val(tel2);
                $('#adresseModif').val(adresse);
                $('#sexeModif').val(sexe);

                $('#specialite_idModif').val(null).trigger('change');
                $('#specialite_idModif').val(specialite_id).trigger('change');
            });

            $('#Table_day').on('click', '#delete', function() {
                const id = $(this).data('id');
                $('#Iddelete').val(id);
            });
        }

        function updatee() {

            const id = $("#Id").val();
            const nom = $("#nomModif").val().trim();
            const email = $("#emailModif").val().trim();
            const tel = $("#telModif").val().trim();
            const tel2 = $("#tel2Modif").val().trim();
            const sexe = $("#sexeModif").val().trim();
            const adresse = $("#adresseModif").val().trim();
            const specialite_id = $("#specialite_idModif").val().trim();

            // Field validation
            if (!nom || !email || !tel || !sexe || !adresse || !specialite_id) {
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.', 'warning');
                return false;
            }

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showAlert('Alert', 'Email incorrect.', 'warning');
                return false;
            }

            // Phone validation
            if (tel.length !== 10 || (tel2 && tel2.length !== 10)) {
                showAlert('Alert', 'Contact incomplet.', 'warning');
                return false;
            }

            const modal = bootstrap.Modal.getInstance(document.getElementById('Mmodif'));
            modal.hide();

            const preloader = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $("body").append(preloader);

            $.ajax({
                url: '/api/update_medecin/' + id,
                method: 'GET',
                data: {
                    nom: nom,
                    email: email,
                    tel: tel,
                    tel2: tel2 || null,
                    adresse: adresse,
                    sexe: sexe,
                    specialite_id: specialite_id
                },
                success: function(response) {
                    $("#preloader_ch").remove();

                    if (response.tel_existe) {
                        showAlert('Alert', 'Veuillez saisir autre numéro de téléphone s\'il vous plaît', 'warning');
                    } else if (response.email_existe) {
                        showAlert('Alert', 'Veuillez saisir autre email s\'il vous plaît', 'warning');
                    } else if (response.nom_existe) {
                        showAlert('Alert', 'Cet Médecin existe déjà.', 'warning');
                    } else if (response.success) {
                        $('#Table_day').DataTable().ajax.reload();
                        showAlert('Succès', 'Opération éffectuée.', 'success');
                    } else if (response.error) {
                        showAlert('Erreur', 'Une erreur est survenue lors de l\'enregistrement.', 'error');
                    }
                },
                error: function() {
                    $("#preloader_ch").remove();
                    showAlert('Erreur', 'Erreur lors de la mise à jour.', 'error');
                }
            });
        }

        function deletee() {
            const id = $("#Iddelete").val();

            const modal = bootstrap.Modal.getInstance(document.getElementById('Mdelete'));
            modal.hide();

            const preloader = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $("body").append(preloader);

            $.ajax({
                url: '/api/delete_medecin/' + id,
                method: 'DELETE',
                success: function(response) {
                    $("#preloader_ch").remove();

                    if (response.success) {
                        $('#Table_day').DataTable().ajax.reload();
                        showAlert('Succès', 'Opération éffectuée.', 'success');
                    }
                },
                error: function() {
                    $("#preloader_ch").remove();
                    showAlert('Erreur', 'Erreur lors de la suppression de la chambre.', 'error');
                }
            });
        }

    });
</script>


@endsection