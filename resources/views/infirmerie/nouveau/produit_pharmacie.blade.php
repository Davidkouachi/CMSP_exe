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
            Nouveau Produit Pharmacie
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
                        <h5>Produits Pharmacies</h5>
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
                                    <i class="ri-add-line me-2"></i>
                                    Nouveau Produit
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-white" id="tab-twoAAA" data-bs-toggle="tab" href="#twoAAA" role="tab" aria-controls="twoAAA" aria-selected="false" tabindex="-1">
                                    <i class="ri-archive-drawer-line me-2"></i>
                                    Liste des Produits
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane active show fade" id="twoAAAN" role="tabpanel" aria-labelledby="tab-twoAAAN">
                                <div class="card-header">
                                    <h5 class="card-title text-center">Formulaire Nouveau Produit Pharmacie</h5>
                                </div>
                                <div class="card-header">
                                    <div class="text-center">
                                        <a class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/produit2.jpg')}}" class="img-7x rounded-circle border border-1 ">
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body" >
                                    <div class="row gx-3 align-items-center justify-content-center">
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Nom du Produit
                                                </label>
                                                <input type="text" class="form-control" id="nom" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="prix">Prix</label>
                                                <div class="input-group">
                                                    <input type="tel" class="form-control" id="prix" placeholder="Saisie Obligatoire">
                                                    <span class="input-group-text">Fcfa</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="prix">Quantité</label>
                                                <input type="tel" class="form-control" id="quantite" placeholder="Saisie Obligatoire">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="mb-3 d-flex gap-2 justify-content-center">
                                                <button id="btn_eng" class="btn btn-success">
                                                    Enregistrer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="twoAAA" role="tabpanel" aria-labelledby="tab-twoAAA">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="card-title">
                                        Liste des Produits Pharmacie
                                    </h5>
                                    <div class="d-flex" >
                                        <a id="btn_refresh_table" class="btn btn-outline-info ms-auto">
                                            <i class="ri-loop-left-line"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="" >
                                        <div class="table-responsive">
                                            <table id="Table_day" class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">N°</th>
                                                        <th scope="col" colspan="2">Nom du medicament</th>
                                                        <th scope="col">Prix</th>
                                                        <th scope="col">Qté Restante</th>
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

<div class="modal fade" id="Mdelete" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delRowLabel">
                    Confirmation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Voulez-vous vraiment supprimé ce Produit
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

<div class="modal fade" id="Mmodif" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mise à jour</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateChambreForm">
                    <input type="hidden" id="Id">
                    <div class="mb-3">
                        <label class="form-label">Nom du Produit</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="nomModif" oninput="this.value = this.value.toUpperCase()">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="prix">Prix</label>
                        <div class="input-group">
                            <input type="tel" class="form-control" id="prixModif" placeholder="Saisie Obligatoire">
                            <span class="input-group-text">Fcfa</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="prix">Quantité</label>
                        <input type="tel" class="form-control" id="quantiteModif" placeholder="Saisie Obligatoire">
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

<script>
    $(document).ready(function() {

        $('#btn_eng').on('click', eng);
        $('#updateBtn').on('click', updatee);
        // $('#deleteBtn').on('click', deletee);

        $('#prix').on('input', function() {
            this.value = formatPrice(this.value);
        });

        $('#prix').on('keypress', function(event) {
            const key = event.key;
            if (isNaN(key)) {
                event.preventDefault();
            }
        });

        $('#prixModif').on('input', function() {
            this.value = formatPrice(this.value);
        });

        $('#prixModif').on('keypress', function(event) {
            const key = event.key;
            if (isNaN(key)) {
                event.preventDefault();
            }
        });

        $('#quantite').on('keypress', function(event) {
            const key = event.key;
            if (isNaN(key)) {
                event.preventDefault();
            }
        });

        $('#quantiteModif').on('keypress', function(event) {
            const key = event.key;
            if (isNaN(key)) {
                event.preventDefault();
            }
        });

        $('#btn_refresh_table').on('click', function(event) {
            $('#Table_day').DataTable().ajax.reload(null, false);
        });

        function formatPrice(input) {
            // Supprimer tous les points existants
            input = input.replace(/\./g, '');
            // Formater le prix avec des points
            return input.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function showAlert(title, message, type) {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
            });
        }

        function eng() {
            const nom = $("#nom");
            const prix = $("#prix");
            const quantite = $("#quantite");

            if (!nom.val().trim() || !prix.val().trim() || !quantite.val().trim()) {
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.', 'warning');
                return false;
            }

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Ajouter le préchargeur au body
            $('body').append(preloader_ch);

            $.ajax({
                url: '/api/new_produit',
                method: 'GET',
                data: { 
                    nom: nom.val(),
                    prix: prix.val(),
                    quantite: quantite.val(),
                },
                success: function(response) {
                    $('#preloader_ch').remove(); // Retirer le préchargeur

                    if (response.existe) {
                        showAlert('Alert', 'Cet Produit existe déjà.', 'warning');
                    } else if (response.success) {

                        nom.val('');
                        prix.val('');
                        quantite.val('');

                        $('#Table_day').DataTable().ajax.reload(null, false);

                        showAlert('Succès', 'Produit Enregistré.', 'success');
                    } else if (response.error) {
                        showAlert('Erreur', 'Une erreur est survenue lors de l\'enregistrement.', 'error');
                    }

                },
                error: function(xhr, status, error) {
                    $('#preloader_ch').remove(); // Retirer le préchargeur

                    console.log(xhr, status, error);

                    showAlert('Erreur', 'Une erreur est survenue lors de l\'enregistrement.', 'error');

                    // Réinitialiser les champs
                    nom.val('');
                    prix.val('');
                    quantite.val('');
                }
            });
        }

        const table = $('#Table_day').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '/api/list_produit',
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
                    data: 'nom',
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex align-items-center">
                                <a class="d-flex align-items-center flex-column me-2">
                                    <img src="{{asset('assets/images/produit1.png')}}" class="img-2x rounded-circle border border-1">
                                </a>
                            </div>
                        `;
                    },
                    searchable: false,
                },
                {
                    data: 'nom',
                    searchable: true,
                },
                {   
                    data: 'prix', 
                    render: function(data) { return `${data} Fcfa`; },
                    searchable: true,
                },
                { 
                    data: 'quantite', 
                    searchable: true, 
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        return `
                            <div class="d-inline-flex gap-1">
                                <a class="btn btn-outline-info btn-xs" data-bs-toggle="modal" data-bs-target="#Mmodif" id="edit" data-id="${row.id}" data-nom="${row.nom}" data-prix="${row.prix}" data-quantite="${row.quantite}">
                                    <i class="ri-edit-box-line"></i>
                                </a>
                            </div>
                        `;
                    },
                    orderable: false,
                    searchable: false,
                }
            ],
            ...dataTableConfig,
            initComplete: function(settings, json) {
                initializeRowEventListeners();
            },
        });

        function initializeRowEventListeners() {
            $('#Table_day').on('click', '#edit', function() {
                const id = $(this).data('id');
                const nom = $(this).data('nom');
                const prix = $(this).data('prix');
                const quantite = $(this).data('quantite');
                
                $('#Id').val(id);
                $('#nomModif').val(nom);
                $('#prixModif').val(prix);
                $('#quantiteModif').val(quantite);
            });
        }

        

        function updatee() {
            const id = $('#Id').val();
            const nom = $('#nomModif').val();
            const prix = $('#prixModif').val();
            const quantite = $('#quantiteModif').val();

            if(!nom.trim() || !prix.trim() || !quantite.trim()) {
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.','warning');
                return false;
            }

            var modal = bootstrap.Modal.getInstance($('#Mmodif')[0]);
            modal.hide();

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            $('body').append(preloader_ch);

            $.ajax({
                url: '/api/update_produit/' + id,
                method: 'GET',
                data: { 
                    nom: nom,
                    prix: prix,
                    quantite: quantite,
                },
                success: function(response) {
                    $('#preloader_ch').remove();

                    if (response.success) {
                        showAlert('Succès', 'Produit mis à jour avec succès.','success');
                    } else if (response.error) {
                        showAlert('Erreur', 'Erreur lors de la mise à jour du produit.','error');
                    }
                    // Reload the list or update the table row without a full refresh
                    $('#Table_day').DataTable().ajax.reload(null, false); // Call your function to reload the table
                },
                error: function() {
                    $('#preloader_ch').remove();
                    showAlert('Erreur', 'Erreur lors de la mise à jour du produit.','error');
                }
            });
        }

        // function deletee() {
        //     const id = $('#Iddelete').val();

        //     var modal = bootstrap.Modal.getInstance($('#Mdelete')[0]);
        //     modal.hide();

        //     var preloader_ch = `
        //         <div id="preloader_ch">
        //             <div class="spinner_preloader_ch"></div>
        //         </div>
        //     `;
        //     // Add the preloader to the body
        //     $('body').append(preloader_ch);

        //     $.ajax({
        //         url: '/api/delete_produit/' + id,
        //         method: 'GET', // Use 'POST' for data creation
        //         success: function(response) {
        //             $('#preloader_ch').remove();

        //             showAlert('Succès', 'Produit supprimé avec succès.','success');
        //             // Reload the list or update the table row without a full refresh
        //             $('#Table_day').DataTable().ajax.reload(null, false); // Call your function to reload the table
        //         },
        //         error: function() {
        //             $('#preloader_ch').remove();
        //             showAlert('Erreur', 'Erreur lors de la suppression du produit.','error');
        //         }
        //     });
        // }

    });
</script>

@endsection


