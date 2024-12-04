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
            Nouvelle Societe
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
                        <div class="mt-4 row gx-3">
                            <div class="d-flex align-items-center col-xxl-3 col-lg-4 col-sm-6 col-12 mb-3 ">
                                <div class="icon-box lg bg-success rounded-5 me-3">
                                    <i class="ri-safe-2-line fs-1"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h4 id="solde_caisse" class="m-0 lh-1"></h4>
                                    <p class="m-0">Solde Actuel</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-3">
        <div class="col-xxl-4 col-sm-6">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title">Historique Journalier</h5>
                    <div class="d-flex">
                        <input type="date" id="date_trace" class="form-control me-1" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" >
                        <a id="btn_refresh_trace" class="btn btn-outline-info ms-auto">
                            <i class="ri-loop-left-line"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="scroll300">
                        <div class="d-flex flex-column gap-2" id="historique_contenu">
                        </div>
                    </div>
                    <div class="mt-3" id="historique_total">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('assets/js/app/js/jspdfinvoicetemplate/dist/index.js')}}" ></script>
<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        solde();
        historique();

        document.getElementById("btn_refresh_trace").addEventListener("click", historique);
        document.getElementById("date_trace").addEventListener("change", historique);

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

        function solde() {

            const solde_caisse = document.getElementById("solde_caisse");

            $.ajax({
                url: '/api/montant_solde',
                method: 'GET',
                success: function(response) {
                    solde_caisse.textContent = response.solde.solde + ' Fcfa';
                },
                error: function() {
                    solde_caisse.textContent = '0 Fcfa';
                }
            });
        }

        function historique() {

            const date = document.getElementById('date_trace').value;

            const contenu_total = document.getElementById('historique_total');

            const contenu = document.getElementById('historique_contenu');
            contenu.innerHTML = '';

            var preloader = `
                <div class="d-flex justify-content-center align-items-center" id="laoder_stat">
                    <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                    <strong>Chargement des données...</strong>
                </div>
            `;
            contenu.innerHTML = preloader;

            const url = `/api/historique_caisse/${date}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const traces = data.trace || [] ;
                    const total = data.total ;

                    contenu.innerHTML = '';

                    if (traces.length > 0) {

                            traces.forEach((item, index) => {
                                const div = document.createElement('div');
                                div.className = "p-3 border rounded-2";
                                div.innerHTML = `
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="border ${item.typemvt === 'Entrer de Caisse' ? `border-success` : `border-danger`} p-1 rounded-3 ${item.typemvt === 'Entrer de Caisse' ? `ri-arrow-right-up-line` : `ri-arrow-right-down-line`} fs-1 ${item.typemvt === 'Entrer de Caisse' ? `text-success` : `text-danger`} lh-1"></i>
                                        <div>
                                            <h5 class="${item.typemvt === 'Entrer de Caisse' ? `text-success` : `text-danger`}" > ${item.typemvt === 'Entrer de Caisse' ? `+ `+item.montant+` Fcfa` : `- `+item.montant+` Fcfa`}</h5>
                                            <p class="opacity-100 m-0">
                                                ${item.motif}.
                                            </p>
                                        </div>
                                    </div>
                                `;
                                contenu.appendChild(div);
                                
                            });

                            contenu_total.innerHTML = '';

                            const divT = `
                                <h5 class="card-title">Montant Total : ${formatPrice(total)} Fcfa</h5>   
                            `;
                            contenu_total.innerHTML = divT;


                    } else {
                        
                        var message = `
                            <div class="d-flex justify-content-center align-items-center" id="historique_message">
                                <strong>Aucune données n'a été trouvées</strong>
                            </div>
                        `;
                        contenu.innerHTML = message;
                        contenu_total.innerHTML = '';

                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données:', error);
                });
        }

    });
</script>

@endsection
