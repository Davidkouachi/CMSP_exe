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
            Comptabilité
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title">Solde Caisse</h5>
                    <div class="d-flex">
                        <a id="btn_refresh_soldCaisse" class="btn btn-outline-info ms-auto">
                            <i class="ri-loop-left-line"></i>
                        </a>
                    </div>
                </div>
                <div id="contenu_caisse" class="mt-3 mb-3"></div>
            </div>
        </div>
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title">Tendances des Opérations de caisse</h5>
                    <div class="d-flex">
                        <select class="form-select me-1" id="yearSelect2"></select>
                        <a id="btn_refresh_stat_acte2" class="btn btn-outline-info ms-auto">
                            <i class="ri-loop-left-line"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body" id="contenu_gra2" ></div>
            </div>
        </div>
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title">Statistique des Actes</h5>
                    <div class="d-flex">
                        <select class="form-select me-1" id="yearSelect"></select>
                        <a id="btn_refresh_stat_acte" class="btn btn-outline-info ms-auto me-1">
                            <i class="ri-loop-left-line"></i>
                        </a>
                        <a id="btn_prinft_stat_acte" class="btn btn-outline-warning ms-auto" style="display: none;">
                            <i class="ri-printer-line"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body" id="contenu_gra1"></div>
            </div>
        </div>
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title">Chiffres d'affaires mensuels des Actes</h5>
                    <div class="d-flex">
                        <select class="form-select me-1" id="yearSelect3"></select>
                        <a id="btn_refresh_stat_chiff_acte" class="btn btn-outline-info ms-auto me-1">
                            <i class="ri-loop-left-line"></i>
                        </a>
                        <a id="btn_prinft_chiff_aff" class="btn btn-outline-warning ms-auto" style="display: none;">
                            <i class="ri-printer-line"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body" id="contenu_gra3"></div>
            </div>
        </div>
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body" >
                    <div class="row gx-3 justify-content-center align-items-center">
                        <div class="col-xxl-4 col-lg-6 col-sm-6 col-12">
                            <div class="mb-3 text-center">
                                <label class="form-label">
                                    Période
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        Du
                                    </span>
                                    <input type="date" class="form-control" id="date1" max="{{ date('Y-m-d') }}">
                                    <span class="input-group-text">
                                        Au
                                    </span>
                                    <input type="date" class="form-control" id="date2" max="{{ date('Y-m-d') }}">
                                    <button id="btn_rech" class="btn btn-outline-success">
                                        <i class="ri-search-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12" id="stat_global"></div>
    </div>
</div>

<script src="{{asset('assets/vendor/apex/apexcharts.min.js')}}"></script>
<script src="{{asset('assets/js/app/js/jspdfinvoicetemplate/dist/index.js')}}" ></script>
<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>
<script src="{{asset('jsPDF-AutoTable/dist/jspdf.plugin.autotable.min.js')}}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        caisse_verf();
        yearSelect();
        stat_acte();
        stat_acte2();
        stat_chiff_acte();
        dateSelect();
        stat_acte_mois();

        document.getElementById("date1").addEventListener("change", datechange);
        document.getElementById("btn_rech").addEventListener("click", stat_acte_mois);
        document.getElementById("btn_refresh_stat_acte").addEventListener("click", stat_acte);
        document.getElementById("btn_refresh_stat_acte2").addEventListener("click", stat_acte2);
        document.getElementById("btn_refresh_stat_chiff_acte").addEventListener("click", stat_chiff_acte);
        document.getElementById("yearSelect").addEventListener("change", stat_acte);
        document.getElementById("yearSelect2").addEventListener("change", stat_acte2);
        document.getElementById("yearSelect3").addEventListener("change", stat_chiff_acte);

        document.getElementById("btn_refresh_soldCaisse").addEventListener("click", caisse_verf);

        function caisse_verf()
        {
            const contenu = document.getElementById("contenu_caisse");
            contenu.innerHTML = '';

            var contenu0 = `
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="mb-3">
                            <div class="card-body row gx-3 d-flex align-items-center justify-content-between">
                                <div class="col-12">
                                    <div class="mb-1 text-center">
                                        <a class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/caisse.jpg')}}" class="img-7x rounded-circle border border-3 border-primary">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="mb-1 text-center">
                                        <h2 class="card-title">
                                            <span class="badge bg-primary" id="h_solde"></span>
                                        </h2>
                                    </div>
                                </div>
                                <div class="col-12" id="btn_ouvert" style="display: none;">
                                    <div class="mb-1 text-center">
                                        <button id="btn_ouvert_C" type="button" class="btn btn-success">
                                            Ouverture de Caisse
                                            <i class="ri-door-open-line"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-12" id="btn_fermer" style="display: none;">
                                    <div class="mb-1 text-center">
                                        <button id="btn_fermer_C" type="button" class="btn btn-danger">
                                            Fermeture de Caisse
                                            <i class="ri-door-close-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            var message = `
                <div id="message_stat_acte">
                    <p class="text-center">
                        Aucune donnée n'a été trouvée
                    </p>
                </div>
            `;

            var loader = `
                <div id="div_Table_loader_stat_acte">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                        <strong>Chargement des données...</strong>
                    </div>
                </div>
            `;

            contenu.innerHTML = loader;

            fetch('/api/verf_caisse')
                .then(response => response.json())
                .then(data => {

                    contenu.innerHTML = "";
                    contenu.innerHTML = contenu0;

                    document.getElementById('h_solde').innerHTML = data.caisse.solde + " Fcfa";
                    
                    if (data.caisse.statut == 'ouvert') {
                        document.getElementById('btn_ouvert').style.display = 'none';
                        document.getElementById('btn_fermer').style.display = 'block';
                    }else{
                        document.getElementById('btn_ouvert').style.display = 'block';
                        document.getElementById('btn_fermer').style.display = 'none';
                    }

                    document.getElementById("btn_ouvert_C").addEventListener("click", caisse_ouvert);
                    document.getElementById("btn_fermer_C").addEventListener("click", caisse_fermer);

                })
                .catch(error => {
                    console.error('Erreur lors du chargement des donnée caisse:', error);

                    contenu.innerHTML = "";
                    contenu.innerHTML = message;
                });
        }

        function caisse_ouvert()
        {
            const auth_id = {{ Auth::user()->id }};

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: '/api/caisse_ouvert',
                method: 'GET',
                data: { 
                    auth_id: auth_id,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.success) {

                        document.getElementById('btn_ouvert').style.display = 'none';
                        document.getElementById('btn_fermer').style.display = 'block';
                        caisse_verf();

                    } else if (response.error) {
                        showAlert('Alert', 'Une erreur est survenue lors de l\'ouverture de la caisse.', 'error');
                        console.log('message erreur controlleur : '+response.message);
                    }

                },
                error: function(xhr, status, error) {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    showAlert('Alert', 'Une erreur est survenue.','error');
                    let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Une erreur est survenue.';
                    console.log('message erreur controlleur : '+ errorMessage);
                }
            });
        }

        function caisse_fermer()
        {
            const auth_id = {{ Auth::user()->id }};

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: '/api/caisse_fermer',
                method: 'GET',
                data: { 
                    auth_id: auth_id,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.success) {

                        document.getElementById('btn_ouvert').style.display = 'block';
                        document.getElementById('btn_fermer').style.display = 'none';
                        caisse_verf();

                    } else if (response.error) {
                        showAlert('Alert','Une erreur est survenue lors de la fermeture de la caisse.','error');
                        console.log('message erreur controlleur : '+ response.message);
                    }

                },
                error: function(xhr, status, error) {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    showAlert('Alert', 'Une erreur est survenue.','error');
                    let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Une erreur est survenue.';
                    console.log('message erreur controlleur : '+ errorMessage);
                }
            });
        }

        function datechange()
        {
            const date1Value = document.getElementById('date1').value;
            const date2 = document.getElementById('date2');

            date2.value = date1Value;

            date2.min = date1Value;
        }

        function showAlert(title, message, type) 
        {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
            });
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

        function yearSelect() 
        {

            var yearSelect = document.getElementById('yearSelect');
            var yearSelect2 = document.getElementById('yearSelect2');
            var yearSelect3 = document.getElementById('yearSelect3');

            var currentYear = new Date().getFullYear();
            var startYear = 2024;

            for (var year = currentYear; year >= startYear; year--) {

                var option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                if (year === currentYear) {
                    option.selected = true;
                }
                yearSelect.appendChild(option);

                var option2 = document.createElement('option');
                option2.value = year;
                option2.textContent = year;
                if (year === currentYear) {
                    option2.selected = true;
                }
                yearSelect2.appendChild(option2);

                var option3 = document.createElement('option');
                option3.value = year;
                option3.textContent = year;
                if (year === currentYear) {
                    option3.selected = true;
                }
                yearSelect3.appendChild(option3);
            }
        }

        function dateSelect() 
        {
            // Obtenir la date actuelle
            const today = new Date();
            
            // Calculer le début du mois (1er jour du mois)
            const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            
            // Utiliser la date actuelle comme fin de la période
            const endOfMonth = today;
            
            // Formater les dates au format YYYY-MM-DD
            const formatDate = (date) => {
                return date.toISOString().split('T')[0];
            };

            // Initialiser les champs de date
            document.getElementById('date1').value = formatDate(startOfMonth);
            document.getElementById('date2').value = formatDate(endOfMonth);
        }

        function generateMonthlyData(stats, defaultMonths) 
        {
            return defaultMonths.map(month => stats[month] || 0);
        }

        function stat_acte() 
        {

            const yearSelect = document.getElementById("yearSelect").value;

            const contenu = document.getElementById("contenu_gra1");
            contenu.innerHTML = '';

            var stat_acte = `
                <div id="stat_acte"></div>
            `;
            var message = `
                <div id="message_stat_acte">
                    <p class="text-center">
                        Aucune donnée n'a été trouvée
                    </p>
                </div>
            `;
            var loader = `
                <div id="div_Table_loader_stat_acte">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                        <strong>Chargement des données...</strong>
                    </div>
                </div>
            `;

            contenu.innerHTML = loader;

            fetch('/api/stat_comp_acte/' + yearSelect)
                .then(response => response.json())
                .then(data => {

                    const monthlyStats = data.monthlyStats;
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',];

                    const consultationsData = generateMonthlyData(monthlyStats.consultations, months);
                    const hospitalisationsData = generateMonthlyData(monthlyStats.hospitalisations, months);
                    const examensData = generateMonthlyData(monthlyStats.examens, months);
                    const soinsAmbulatoiresData = generateMonthlyData(monthlyStats.soins_ambulatoires, months);

                    if (monthlyStats) {

                        contenu.innerHTML = '';
                        contenu.innerHTML = stat_acte;

                        var options = {
                            chart: {
                                height: 300,
                                type: "bar",
                                toolbar: {
                                    show: false,
                                },
                                animations: {
                                    enabled: true,
                                    easing: 'easeinout',
                                    speed: 800,
                                    animateGradually: {
                                        enabled: true,
                                        delay: 150,
                                    },
                                    dynamicAnimation: {
                                        enabled: true,
                                        speed: 350,
                                    }
                                }
                            },
                            dataLabels: {
                                enabled: false,
                            },
                            stroke: {
                                curve: "smooth",
                                width: 3,
                            },
                            series: [
                                { name: "Consultations", data: consultationsData },
                                { name: "Hospitalisations", data: hospitalisationsData },
                                { name: "Examens", data: examensData },
                                { name: "Soins Ambulatoires", data: soinsAmbulatoiresData },
                            ],
                            grid: {
                                borderColor: "#d8dee6",
                                strokeDashArray: 5,
                                xaxis: {
                                    lines: {
                                        show: true,
                                    },
                                },
                                yaxis: {
                                    lines: {
                                        show: true,
                                    },
                                },
                                padding: {
                                    top: 0,
                                    right: 0,
                                    bottom: 10,
                                    left: 0,
                                },
                            },
                            xaxis: {
                                categories: [
                                    "Janvier",
                                    "Février",
                                    "Mars",
                                    "Avril",
                                    "Mai",
                                    "Juin",
                                    "Juillet",
                                    "Aôut",
                                    "Septembre",
                                    "Octobre",
                                    "Novembre",
                                    "Decembre",
                                ],
                            },
                            yaxis: {
                                labels: {
                                    show: true,
                                    offsetX: -10,
                                },
                            },
                            colors: [
                                "#FF5733", // Couleur pour les Consultations
                                "#33FF57", // Couleur pour les Hospitalisations
                                "#3357FF", // Couleur pour les Examens
                                "#FF33B5", // Couleur pour les Soins Ambulatoires
                            ],
                            markers: {
                                size: 0,
                                opacity: 0.3,
                                colors: ["#FF5733", "#33FF57", "#3357FF", "#FF33B5"],
                                strokeColor: "#ffffff",
                                strokeWidth: 1,
                                hover: {
                                    size: 7,
                                },
                            },
                        };

                        var chart = new ApexCharts(document.querySelector("#stat_acte"), options);

                        chart.render();

                        const btnPrintStatActe = document.getElementById("btn_prinft_stat_acte");
                        btnPrintStatActe.style.display = "block";
                        btnPrintStatActe.addEventListener("click", function() {
                            generatePDFStatActe(monthlyStats, yearSelect);
                        });

                    } else {

                        contenu.innerHTML = '';
                        contenu.innerHTML = message;

                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données:', error);

                    contenu.innerHTML = '';
                    contenu.innerHTML = message;

                });
        }

        function stat_acte2() 
        {

            const yearSelect = document.getElementById("yearSelect2").value;

            const contenu = document.getElementById("contenu_gra2");
            contenu.innerHTML = '';

            var stat_acte = `
                <div id="stat_acte2"></div>
                <div id="stat_acte2_bord" class="card-header">
                </div>
            `;
            var message = `
                <div id="message_stat_acte">
                    <p class="text-center">
                        Aucune donnée n'a été trouvée
                    </p>
                </div>
            `;
            var loader = `
                <div id="div_Table_loader_stat_acte">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                        <strong>Chargement des données...</strong>
                    </div>
                </div>
            `;

            contenu.innerHTML = loader;

            fetch('/api/stat_comp_ope/' + yearSelect)
                .then(response => response.json())
                .then(data => {

                    const monthlyStats = data.monthlyStats;
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',];

                    const entrer = generateMonthlyData(monthlyStats.entrer, months);
                    const sortie = generateMonthlyData(monthlyStats.sortie, months);
                    const total = generateMonthlyData(monthlyStats.total, months);

                    if (monthlyStats) {

                        contenu.innerHTML = '';
                        contenu.innerHTML = stat_acte;

                        var options = {
                            chart: {
                                height: 300,
                                type: "line",
                                toolbar: {
                                    show: false,
                                },
                            },
                            dataLabels: {
                                enabled: false,
                            },
                            stroke: {
                                curve: "smooth",
                                width: 3,
                            },
                            series: [{
                                    name: "Entrées",
                                    data: entrer,
                                },
                                {
                                    name: "Sorties",
                                    data: sortie,
                                },
                                {
                                    name: "Total",
                                    data: total,
                                }, 
                            ],
                            grid: {
                                borderColor: "#d8dee6",
                                strokeDashArray: 5,
                                xaxis: {
                                    lines: {
                                        show: true,
                                    },
                                },
                                yaxis: {
                                    lines: {
                                        show: true,
                                    },
                                },
                                padding: {
                                    top: 0,
                                    right: 0,
                                    bottom: 10,
                                    left: 0,
                                },
                            },
                            xaxis: {
                                categories: [
                                    "Janvier",
                                    "Février",
                                    "Mars",
                                    "Avril",
                                    "Mai",
                                    "Juin",
                                    "Juillet",
                                    "Aôut",
                                    "Septembre",
                                    "Octobre",
                                    "Novembre",
                                    "Decembre",
                                ],
                            },
                            yaxis: {
                                labels: {
                                    show: true,
                                    formatter: function(val) {
                                        return formatPrice(val) + " Fcfa"; // Format y-axis labels
                                    },
                                    offsetX: -10,
                                },
                            },
                            colors: ["#0ebb13", "#ff5a39", "#436ccf"],
                            markers: {
                                size: 0,
                                opacity: 0.5,
                                colors: ["#0ebb13", "#ff5a39", "#436ccf"],
                                strokeColor: "#ffffff",
                                strokeWidth: 1,
                                hover: {
                                    size: 7,
                                },
                            },
                            tooltip: {
                                y: {
                                    formatter: function(val) {
                                        return formatPrice(val)+" Fcfa";
                                    },
                                },
                            },
                        };
                        var chart = new ApexCharts(document.querySelector("#stat_acte2"), options);
                        chart.render();

                        var stat = `
                            <div class="d-flex flex-wrap gap-1 justify-content-center align-items-center">
                                <div class="d-flex align-items-center box-shadow px-3 py-1 rounded-2 me-2 mb-2 bg-success text-white">
                                    <i class="ri-pie-chart-2-fill text-white fs-4"></i>
                                    <span class="me-1 text-white ps-1">+ ${formatPrice(data.total_entrer)} Fcfa</span>
                                    <span class="fw-semibold">Entrées</span>
                                </div>
                                <div class="d-flex align-items-center box-shadow px-3 py-1 rounded-2 me-2 mb-2 bg-danger text-white">
                                    <i class="ri-pie-chart-2-fill text-white fs-4"></i>
                                    <span class="me-1 text-white ps-1">- ${formatPrice(data.total_sortie)} Fcfa</span>
                                    <span class="fw-semibold">Sorties</span>
                                </div>
                                <div class="d-flex align-items-center box-shadow px-3 py-1 rounded-2 me-2 mb-2 bg-primary text-white">
                                    <i class="ri-pie-chart-2-fill text-white fs-4"></i>
                                    <span class="me-1 text-white ps-1">${formatPrice(data.total)} Fcfa</span>
                                    <span class="fw-semibold">Total</span>
                                </div>
                            </div>
                        `;
                        document.querySelector("#stat_acte2_bord").innerHTML = stat;


                    } else {

                        contenu.innerHTML = '';
                        contenu.innerHTML = message;

                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données:', error);

                    contenu.innerHTML = '';
                    contenu.innerHTML = message;

                });
        }

        function stat_chiff_acte() 
        {

            const yearSelect = document.getElementById("yearSelect3").value;

            const contenu = document.getElementById("contenu_gra3");
            contenu.innerHTML = '';

            var stat_acte = `
                <div class="table-outer">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover m-0 truncate" id="Table_chiff_acte">
                                <thead>
                                    <tr>
                                        <th scope="col">Acte</th>
                                        <th scope="col">Janvier</th>
                                        <th scope="col">Février</th>
                                        <th scope="col">Mars</th>
                                        <th scope="col">Avril</th>
                                        <th scope="col">Mai</th>
                                        <th scope="col">Juin</th>
                                        <th scope="col">Juillet</th>
                                        <th scope="col">Août</th>
                                        <th scope="col">Septembre</th>
                                        <th scope="col">Octobre</th>
                                        <th scope="col">Novembre</th>
                                        <th scope="col">Décembre</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
            `;
            var message = `
                <div id="message_stat_acte">
                    <p class="text-center">
                        Aucune donnée n'a été trouvée
                    </p>
                </div>
            `;
            var loader = `
                <div id="div_Table_loader_stat_acte">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                        <strong>Chargement des données...</strong>
                    </div>
                </div>
            `;

            contenu.innerHTML = loader;

            fetch('/api/stat_chiff_acte/' + yearSelect)
                .then(response => response.json())
                .then(data => {

                    const monthlyStats = data.monthlyStats;

                    if (monthlyStats) {

                        contenu.innerHTML = '';
                        contenu.innerHTML = stat_acte;
                
                        const tableBody = document.querySelector('#Table_chiff_acte tbody');
                        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',];

                        // Effacer les anciennes données du tableau
                        tableBody.innerHTML = '';

                        // Pré-calculer le min et le max pour chaque mois
                        const minMaxValues = {};

                        months.forEach(month => {
                            const values = Object.keys(monthlyStats).map(acte => parseFloat(monthlyStats[acte][month]) || 0);
                            const max = Math.max(...values);
                            const min = Math.min(...values);
                            minMaxValues[month] = {
                                max: max,
                                min: min,
                                allEqual: max === min,
                            };
                        });

                        // Générer des lignes pour chaque type d'acte
                        Object.keys(monthlyStats).forEach(acte => {
                            const row = document.createElement('tr');

                            // Première colonne avec le nom de l'acte et des couleurs différentes par type d'acte
                            const acteCell = document.createElement('td');

                            // Définir la couleur en fonction de l'acte
                            let colorClass = '';
                            switch (acte) {
                                case 'consultation':
                                    colorClass = 'bg-primary'; // bleu
                                    break;
                                case 'examen':
                                    colorClass = 'bg-warning'; // jaune
                                    break;
                                case 'hospitalisation':
                                    colorClass = 'bg-danger'; // rouge
                                    break;
                                case 'soins ambulatoire':
                                    colorClass = 'bg-success'; // vert
                                    break;
                                default:
                                    colorClass = 'bg-secondary'; // gris par défaut
                            }

                            // Insérer l'acte avec la couleur appropriée
                            acteCell.innerHTML = `
                                <span class="badge ${colorClass}">
                                    ${acte.charAt(0).toUpperCase() + acte.slice(1)}
                                </span>
                            `;
                            row.appendChild(acteCell);

                            let total = 0; // Initialiser le total pour chaque acte

                            // Colonnes pour chaque mois
                            months.forEach(month => {
                                const cell = document.createElement('td');
                                const montant = parseFloat(monthlyStats[acte][month]) || 0;
                                total += montant; // Ajouter le montant au total de l'acte
                                cell.textContent = `${formatPrice(montant)} Fcfa`;

                                // Appliquer la couleur en fonction des valeurs min/max
                                if (!minMaxValues[month].allEqual) {
                                    if (montant === minMaxValues[month].max) {
                                        cell.style.color = 'green';
                                    } else if (montant === minMaxValues[month].min) {
                                        cell.style.color = 'red';
                                    }else{
                                       cell.style.color = 'blue'; 
                                    }
                                }else{
                                   cell.style.color = 'blue'; 
                                }

                                row.appendChild(cell);
                            });

                            // Ajouter la cellule Total pour l'acte
                            const totalCell = document.createElement('td');
                            totalCell.textContent = `${formatPrice(total)} Fcfa`;
                            totalCell.style.fontWeight = 'bold';
                            row.appendChild(totalCell);

                            // Ajouter la ligne au tableau
                            tableBody.appendChild(row);
                        });

                        const btnPrintChiffAff = document.getElementById("btn_prinft_chiff_aff");
                        btnPrintChiffAff.style.display = "block";
                        btnPrintChiffAff.addEventListener("click", function() {
                            generatePDFStatChiffActe(monthlyStats, yearSelect);
                        });

                    } else {

                        contenu.innerHTML = '';
                        contenu.innerHTML = message;

                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données:', error);

                    contenu.innerHTML = '';
                    contenu.innerHTML = message;

                });
        }

        function isValidDate(dateString) 
        {
            const regEx = /^\d{4}-\d{2}-\d{2}$/;
            if (!dateString.match(regEx)) return false;
            const date = new Date(dateString);
            const timestamp = date.getTime();
            if (typeof timestamp !== 'number' || isNaN(timestamp)) return false;
            return dateString === date.toISOString().split('T')[0];
        }

        function stat_acte_mois() 
        {

            const date1 = document.getElementById("date1");
            const date2 = document.getElementById("date2");

            if (!date1.value.trim() || !date2.value.trim()) {
                showAlert('Alert', 'Tous les champs sont obligatoires.','warning');
                return false; 
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

            const stat_global = document.getElementById("stat_global");
            stat_global.innerHTML = "";

            var preloader_ch = `
                <div class="d-flex justify-content-center align-items-center" id="laoder_stat">
                    <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                    <strong>Chargement des données...</strong>
                </div>
            `;
            stat_global.innerHTML = preloader_ch;

            fetch('/api/stat_acte_mois/' + date1.value + '/' + date2.value)
                .then(response => response.json())
                .then(data => {

                    var preloader = document.getElementById('laoder_stat');
                    if (preloader) preloader.remove();

                    if (data.date_invalide) {
                        showAlert('Erreur', 'Les dates sont invalides', 'error');
                        return false;
                    }

                    stat_global.innerHTML = '';

                    const Div1 = `
                        <div class="row gx-3" id="stat_acte_mois"></div>
                    `;
                    const Div2 = `
                        <div class="row gx-3">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="card-title">
                                    Répartition du chiffre d'affaires des consultations : ${formatPrice(data.data.m_cons.total_general)} Fcfa
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3" id="stat_cons"></div>
                                </div>
                            </div>
                        </div>
                    `;
                    const Div3 = `
                        <div class="row gx-3">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="card-title">
                                    Répartition des mouvements de la caisse : Encaissements Factures
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3" id="stat_caisse"></div>
                                </div>
                                <div class="card-header">
                                    <h5 class="card-title">
                                        - Part des Patients
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3" id="stat_caisse_patient"></div>
                                </div>
                            </div>
                        </div>
                    `;
                    // const Div4 = `
                    //     <div class="row gx-3">
                    //         <div class="card mb-3">
                    //             <div class="card-header">
                    //                 <h5 class="card-title">
                    //                     Informations de Bord
                    //                 </h5>
                    //             </div>
                    //             <div class="card-body">
                    //                 <div class="row g-3" id="stat_info"></div>
                    //             </div>
                    //         </div>
                    //     </div>
                    // `;

                    stat_global.innerHTML += Div1;
                    stat_global.innerHTML += Div2;
                    stat_global.innerHTML += Div3;
                    // stat_global.innerHTML += Div4;

                    // -------------------------------------------------------------

                    const stats = data.data;
                    const stat_acte_mois = document.getElementById("stat_acte_mois");
                    stat_acte_mois.innerHTML = '';

                    const cardData_acte_mois = [
                        { label: "Consultations", count: stats.cons, icon: "ri-lungs-line", colorClass: "text-success", borderColor: "border-success", bgColor: "bg-success-subtle", mTotal : formatPrice(stats.m_cons.total_general), pTotal : formatPrice(stats.m_cons.part_patient_payer), ipTotal : formatPrice(stats.m_cons.part_patient_impayer), assurance : formatPrice(stats.m_cons.part_assurance), patient : formatPrice(stats.m_cons.part_patient)},
                        { label: "Hospitalisations", count: stats.hos, icon: "ri-hotel-bed-line", colorClass: "text-primary", borderColor: "border-primary", bgColor: "bg-primary-subtle", mTotal : formatPrice(stats.m_hos.total_general), pTotal : formatPrice(stats.m_hos.part_patient_payer), ipTotal : formatPrice(stats.m_hos.part_patient_impayer), assurance : formatPrice(stats.m_hos.part_assurance), patient : formatPrice(stats.m_hos.part_patient)},
                        { label: "Examens", count: stats.exam, icon: "ri-medicine-bottle-line", colorClass: "text-danger", borderColor: "border-danger", bgColor: "bg-danger-subtle", mTotal : formatPrice(stats.m_exam.total_general), pTotal : formatPrice(stats.m_exam.part_patient_payer), ipTotal : formatPrice(stats.m_exam.part_patient_impayer), assurance : formatPrice(stats.m_exam.part_assurance), patient : formatPrice(stats.m_exam.part_patient)},
                        { label: "Soins Ambulatoires", count: stats.soinsam, icon: "ri-dossier-line", colorClass: "text-warning", borderColor: "border-warning", bgColor: "bg-warning-subtle", mTotal : formatPrice(stats.m_soinsam.total_general), pTotal : formatPrice(stats.m_soinsam.part_patient_payer), ipTotal : formatPrice(stats.m_soinsam.part_patient_impayer), assurance : formatPrice(stats.m_soinsam.part_assurance), patient : formatPrice(stats.m_soinsam.part_patient)},
                    ];

                    cardData_acte_mois.forEach(card => {
                        const div = document.createElement('div');
                        div.className = "col-xl-3 col-sm-6 col-12";
                        div.innerHTML = `
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="p-2 ${card.borderColor} rounded-circle me-3">
                                            <div class="icon-box md ${card.bgColor} rounded-5">
                                                <i class="${card.icon} fs-4 ${card.colorClass}"></i>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h2 class="lh-1">${card.count}</h2>
                                            <p class="m-0">${card.label}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-1">
                                        <a class="${card.colorClass}" href="javascript:void(0);">
                                            <span>Montant Total</span>
                                            <i class="ri-arrow-right-line ${card.colorClass} ms-1"></i>
                                        </a>
                                        <div class="text-end">
                                            <p class="mb-0 ${card.colorClass}">${card.mTotal} Fcfa</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-1">
                                        <a class="${card.colorClass}" href="javascript:void(0);">
                                            <span>Part Assurance</span>
                                            <i class="ri-arrow-right-line ${card.colorClass} ms-1"></i>
                                        </a>
                                        <div class="text-end">
                                            <p class="mb-0 ${card.colorClass}">${card.assurance} Fcfa</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-1">
                                        <a class="${card.colorClass}" href="javascript:void(0);">
                                            <span>Part Patient</span>
                                            <i class="ri-arrow-right-line ${card.colorClass} ms-1"></i>
                                        </a>
                                        <div class="text-end">
                                            <p class="mb-0 ${card.colorClass}">${card.patient} Fcfa</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-1">
                                        <a class="${card.colorClass}" href="javascript:void(0);">
                                            <span>Montant Réglé</span>
                                            <i class="ri-arrow-right-line ${card.colorClass} ms-1"></i>
                                        </a>
                                        <div class="text-end">
                                            <p class="mb-0 ${card.colorClass}">${card.pTotal} Fcfa</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-1">
                                        <a class="${card.colorClass}" href="javascript:void(0);">
                                            <span>Montant Non-Réglé</span>
                                            <i class="ri-arrow-right-line ${card.colorClass} ms-1"></i>
                                        </a>
                                        <div class="text-end">
                                            <p class="mb-0 ${card.colorClass}">${card.ipTotal} Fcfa</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        stat_acte_mois.appendChild(div);
                    });

                    // -------------------------------------------------------------

                    const cons_specialite = data.typeacte;
                    const stat_cons = document.getElementById("stat_cons");
                    stat_cons.innerHTML = '';

                    cons_specialite.forEach(item => {
                        const div = document.createElement('div');
                        div.className = "col-xl-3 col-sm-6 col-12";
                        div.innerHTML = `
                            <div class="border rounded-2 d-flex align-items-center flex-row p-2 mb-3 ">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="p-2 border border-primary rounded-circle me-3">
                                            <div class="icon-box md bg-primary-subtle rounded-5">
                                                <i class="ri-stethoscope-line fs-4 text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h5 class="lh-1">${item.nom}</h5>
                                            <p class="m-0">${item.nbre} Consultation(s)</p>
                                        </div>
                                    </div>
                                    ${createStatRow('Part Assurance', item.part_assurance, 'text-primary')}
                                    ${createStatRow('Part Patient', item.part_patient, 'text-primary')}
                                    ${createStatRow('Remise', item.remise, 'text-primary')}
                                    ${createStatRow('Montant Total', item.total, 'text-primary')}
                                </div>
                            </div>
                        `;
                        stat_cons.appendChild(div);
                    });

                    // -------------------------------------------------------------

                    const statsCaisse = data.dataCaisse;
                    const stat_caisse = document.getElementById("stat_caisse");
                    stat_caisse.innerHTML = '';

                    const cardData_caisse = [
                        { label: "Factures", icon: "ri-bar-chart-2-line", colorClass: "text-primary", borderColor: "border-primary", bgColor: "bg-primary-subtle", data : formatPrice(statsCaisse.fac_nbre),},
                        { label: "Montant Total", icon: "ri-safe-2-line", colorClass: "text-success", borderColor: "border-success", bgColor: "bg-success-subtle", data : formatPrice(statsCaisse.fac_total)+" Fcfa",},
                        // { label: "Montant Impayer", icon: "ri-hand-coin-line", colorClass: "text-danger", borderColor: "border-danger", bgColor: "bg-danger-subtle", data : formatPrice(statsCaisse.fac_impayer)+" Fcfa",},
                        // { label: "Montant Payer", icon: "ri-funds-fill", colorClass: "text-success", borderColor: "border-success", bgColor: "bg-success-subtle", data : formatPrice(statsCaisse.fac_payer)+" Fcfa",},
                        { label: "Part Assurance", icon: "ri-cash-fill", colorClass: "text-warning", borderColor: "border-warning", bgColor: "bg-warning-subtle", data : formatPrice(statsCaisse.fac_assurance)+" Fcfa",},
                        { label: "Total Remise", icon: "ri-increase-decrease-line", colorClass: "text-danger", borderColor: "border-danger", bgColor: "bg-danger-subtle", data : formatPrice(statsCaisse.fac_remise)+" Fcfa",},
                    ];

                    cardData_caisse.forEach(card => {
                        const div = document.createElement('div');
                        div.className = "col-xl-3 col-sm-6 col-12";
                        div.innerHTML = `
                            <div class="border rounded-2 d-flex align-items-center flex-row p-2 mb-3 ">
                                <div class="card-body">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="icon-box md ${card.bgColor} rounded-5 mb-2 no-shadow">
                                            <i class="${card.icon} fs-5 ${card.colorClass}"></i>
                                        </div>
                                        <h5 class="${card.colorClass}">
                                            ${card.data}
                                        </h5>
                                        <h4>${card.label}</h4>
                                    </div>
                                </div>
                            </div>
                        `;
                        stat_caisse.appendChild(div);
                    });

                    const stat_caisse_patient = document.getElementById("stat_caisse_patient");
                    stat_caisse_patient.innerHTML = '';
                    const cardData_caisse_patient = [
                        { label: "Montant Total", icon: "ri-cash-fill", colorClass: "text-warning", borderColor: "border-warning", bgColor: "bg-warning-subtle", data : formatPrice(statsCaisse.fac_patient_total)+" Fcfa",},
                        { label: "Montant Impayer", icon: "ri-hand-coin-line", colorClass: "text-danger", borderColor: "border-danger", bgColor: "bg-danger-subtle", data : formatPrice(statsCaisse.fac_patient_impayer)+" Fcfa",},
                        { label: "Montant Payer", icon: "ri-funds-fill", colorClass: "text-success", borderColor: "border-success", bgColor: "bg-success-subtle", data : formatPrice(statsCaisse.fac_patient_payer)+" Fcfa",},
                    ];

                    cardData_caisse_patient.forEach(card => {
                        const div = document.createElement('div');
                        div.className = "col-xl-3 col-sm-6 col-12";
                        div.innerHTML = `
                            <div class="border rounded-2 d-flex align-items-center flex-row p-2 mb-3 ">
                                <div class="card-body">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="icon-box md ${card.bgColor} rounded-5 mb-2 no-shadow">
                                            <i class="${card.icon} fs-5 ${card.colorClass}"></i>
                                        </div>
                                        <h5 class="${card.colorClass}">
                                            ${card.data}
                                        </h5>
                                        <h4>${card.label}</h4>
                                    </div>
                                </div>
                            </div>
                        `;
                        stat_caisse_patient.appendChild(div);
                    });

                    // -------------------------------------------------------------

                    // const stat_info = document.getElementById("stat_info");
                    // stat_info.innerHTML = '';

                    // const cardData_info = [
                    //     { label: "Consultations", icon: "ri-lungs-line", colorClass: "text-success", borderColor: "border-success", bgColor: "bg-success-subtle"}
                    // ];

                    // cardData_info.forEach(card => {
                    //     const div = document.createElement('div');
                    //     div.className = "col-xl-3 col-sm-6 col-12";
                    //     div.innerHTML = `
                    //         <div class="border rounded-2 d-flex align-items-center flex-row p-2 mb-3">
                    //             <div class="card-body">
                    //                 <div class="d-flex align-items-center">
                    //                     <div class="p-2 ${card.borderColor} rounded-circle me-3">
                    //                         <div class="icon-box md ${card.bgColor} rounded-5">
                    //                             <i class="${card.icon} fs-4 ${card.colorClass}"></i>
                    //                         </div>
                    //                     </div>
                    //                     <div class="d-flex flex-column">
                    //                         <h2 class="lh-1">23</h2>
                    //                         <p class="m-0">${card.label}</p>
                    //                     </div>
                    //                 </div>
                    //             </div>
                    //         </div>
                    //     `;
                    //     stat_info.appendChild(div);
                    // });

                })
                .catch(error => {
                    stat_global.innerHTML = `<p class="text-danger text-center">Erreur lors du chargement des données. Veuillez réessayer plus tard.</p>`;
                    console.error('Erreur lors du chargement des données:', error);
                });
        }

        function createStatRow(label, value, colorClass) 
        {
            return `
                <div class="d-flex align-items-end justify-content-between mt-1">
                    <div class="text-start">
                        <p class="mb-0 ${colorClass}">${label}</p>
                    </div>
                    <div class="text-end">
                        <p class="mb-0 ${colorClass}">${formatPrice(value.toString())} Fcfa</p>
                    </div>
                </div>
            `;
        }

        function generatePDFStatActe(monthlyStats,yearSelect)
        {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'l', unit: 'mm', format: 'a4' });

            const pdfFilename = "Statistiques des actes - Année " +yearSelect;
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

                const titleR = "Statistiques des actes";
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
                const dateText = "Année " +yearSelect; // Assurez-vous que formatDate est une fonction qui formate la date comme vous le souhaitez
                const dateTextWidth = doc.getTextWidth(dateText);
                const dateTextX = (doc.internal.pageSize.getWidth() - dateTextWidth) / 2; // Centrer la date
                // Positionner la date sous le rectangle
                doc.text(dateText, dateTextX, textY + 10);

                yPoss = (yPos + 50);

                // Prepare data for the table
                const tableBody = [];
                const monthsTbale = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',];

                // Custom display names for each acte
                const acteNames = {
                    consultations: "Consultations",
                    hospitalisations: "Hospitalisations",
                    examens: "Examens",
                    soins_ambulatoires: "Soins Ambulatoires"
                };

                // Populate table data with monthly statistics
                Object.keys(monthlyStats).forEach(acte => {
                    // Get the customized name for the acte or default to capitalized acte name
                    const displayName = acteNames[acte] || (acte.charAt(0).toUpperCase() + acte.slice(1));
                    const rowData = [displayName];
                    let total = 0;

                    months.forEach(month => {
                        const count = parseInt(generateMonthlyData(monthlyStats[acte], [month]), 10) || 0;
                        total += count;
                        rowData.push(count.toString());
                    });

                    // Add the total to the end of the row
                    rowData.push(total.toString());

                    // Push the completed row to the table body
                    tableBody.push(rowData);
                });

                // Render the table using autoTable
                doc.autoTable({
                    startY: yPoss,
                    head: [['Acte', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre', 'Total']],
                    body: tableBody,
                    theme: 'striped',
                    headStyles: { fillColor: [100, 100, 255] },
                    bodyStyles: { textColor: 50 },
                    alternateRowStyles: { fillColor: [240, 240, 240] },
                    margin: { top: 10, bottom: 10 },
                    tableWidth: 'auto'
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
                    
                    // Page number centered
                    const pageText = `Page ${i} sur ${pageCount}`;
                    const pageTextWidth = doc.getTextWidth(pageText);
                    const centerX = (doc.internal.pageSize.getWidth() - pageTextWidth) / 2;
                    doc.text(pageText, centerX, footerY); // Centered at the bottom

                    // Date at the left
                    doc.text("Imprimé le : " + new Date().toLocaleDateString() + " à " + new Date().toLocaleTimeString(), 10, footerY); // Left-aligned
                }
            }

            drawSection(yPos);

            addFooter();

            // Open the generated PDF in a new window
            doc.output('dataurlnewwindow');
        }

        function generatePDFStatChiffActe(monthlyStats,yearSelect)
        {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'l', unit: 'mm', format: 'a4' });

            const pdfFilename = "Statistiques du chiffres d'affaire des actes - Année " +yearSelect;
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

                const titleR = "Statistiques du chiffres d'affaire des actes";
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
                const dateText = "Année " +yearSelect; // Assurez-vous que formatDate est une fonction qui formate la date comme vous le souhaitez
                const dateTextWidth = doc.getTextWidth(dateText);
                const dateTextX = (doc.internal.pageSize.getWidth() - dateTextWidth) / 2; // Centrer la date
                // Positionner la date sous le rectangle
                doc.text(dateText, dateTextX, textY + 10);

                yPoss = (yPos + 50);

                // Prepare data for the table
                const tableBody = [];
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                Object.keys(monthlyStats).forEach(acte => {
                    const rowData = [acte.charAt(0).toUpperCase() + acte.slice(1)];

                    let total = 0;
                    months.forEach(month => {
                        const montant = parseFloat(monthlyStats[acte][month]) || 0;
                        total += montant;
                        rowData.push(`${formatPrice(montant)} Fcfa`);
                    });

                    rowData.push(`${formatPrice(total)} Fcfa`);
                    tableBody.push(rowData);
                });

                // Generate table with jsPDF autoTable
                doc.autoTable({
                    startY: yPoss,
                    head: [['Acte', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre', 'Total']],
                    body: tableBody,
                    theme: 'striped',
                    headStyles: { fillColor: [100, 100, 255] },
                    bodyStyles: { textColor: 50 },
                    alternateRowStyles: { fillColor: [240, 240, 240] },
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
                    
                    // Page number centered
                    const pageText = `Page ${i} sur ${pageCount}`;
                    const pageTextWidth = doc.getTextWidth(pageText);
                    const centerX = (doc.internal.pageSize.getWidth() - pageTextWidth) / 2;
                    doc.text(pageText, centerX, footerY); // Centered at the bottom

                    // Date at the left
                    doc.text("Imprimé le : " + new Date().toLocaleDateString() + " à " + new Date().toLocaleTimeString(), 10, footerY); // Left-aligned
                }
            }

            drawSection(yPos);

            addFooter();

            // Open the generated PDF in a new window
            doc.output('dataurlnewwindow');
        }

    });
</script>



@endsection


