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
            Nouveau Dépôt de facture
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    <div class="row justify-content-center">
        <div class="col-xxl-4 col-lg-6 col-md-8 col-sm-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title text-center">Formulaire Nouveau Dépôt de facture</h5>
                </div>
                <div class="card-header">
                    <div class="text-center">
                        <a class="d-flex align-items-center flex-column">
                            <img src="{{asset('assets/images/depot_fac.jpg')}}" class="img-7x rounded-circle">
                        </a>
                    </div>
                </div>
                <div class="card-body" >
                    <div class="row gx-3">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Assurance</label>
                                <select class="form-select select2" id="assurance_id"></select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Du</label>
                                <input type="date" class="form-control" id="date1" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Au</label>
                                <input type="date" class="form-control" id="date2" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Date de dépôt</label>
                                <input type="date" class="form-control" id="date_depot" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex gap-2 justify-content-center">
                                <button id="btn_eng_depot" class="btn btn-success">
                                    Enregistrer
                                </button>
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
<script src="{{asset('jsPDF-AutoTable/dist/jspdf.plugin.autotable.min.js')}}"></script>

@include('select2')

<script>
    $(document).ready(function() {

        select_assurance();

        $("#date1").on("change", datechange);
        $("#btn_eng_depot").on("click", eng_depot);

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

        function datechange() 
        {
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

        function eng_depot() 
        {
            const auth_id = {{ Auth::user()->id }};
            const $assurance_id = $('#assurance_id');
            const $date1 = $('#date1');
            const $date2 = $('#date2');
            const $date_depot = $('#date_depot');

            if (!$assurance_id.val().trim() || !$date1.val().trim() || !$date2.val().trim() || !$date_depot.val().trim()) {
                showAlert('Alert', 'Tous les champs sont obligatoires.', 'warning');
                return false;
            }

            if (!isValidDate($date1.val())) {
                showAlert('Erreur', 'La première date est invalide.', 'error');
                return false;
            }

            if (!isValidDate($date2.val())) {
                showAlert('Erreur', 'La deuxième date est invalide.', 'error');
                return false;
            }

            if (!isValidDate($date_depot.val())) {
                showAlert('Erreur', 'La date de dépôt est invalide.', 'error');
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
                showAlert('Erreur', 'La date de début ne peut pas être supérieure à la date de fin.', 'error');
                return false;
            }

            if (endDate > Datedepot) {
                showAlert('Erreur', 'La date du dépôt ne peut pas être supérieure à la date de fin.', 'error');
                return false;
            }

            $('body').append('<div id="preloader_ch"><div class="spinner_preloader_ch"></div></div>');

            $.ajax({
                url: '/api/new_depot_fac',
                method: 'GET',
                data: {
                    assurance_id: $assurance_id.val(),
                    date1: $date1.val(),
                    date2: $date2.val(),
                    date_depot: $date_depot.val(),
                    auth_id: auth_id,
                },
                success: function(response) {
                    $('#preloader_ch').remove();

                    if (response.success) {
                        $assurance_id.val("").trigger('change');
                        $date1.val("");
                        $date2.val("");
                        $date_depot.val("");
                        showAlert('Succès', 'Opération effectuée', 'success');
                    } else if (response.error) {
                        showAlert('Informations', 'Echec de l\'opération', 'info');
                    } else if (response.existe) {
                        showAlert('Informations', 'L\'intervalle de dates choisi se trouve dans l\'intervalle de certaines factures qui ont déjà été déposées.', 'info');
                    } else if (response.montant_inferieur) {
                        showAlert('Informations', 'Opération impossible. Car le montant Total = 0 Fcfa.', 'info');
                    }
                },
                error: function() {
                    $('#preloader_ch').remove();
                    showAlert('Alert', 'Une erreur est survenue.', 'error');
                }
            });
        }

    });
</script>

@endsection


