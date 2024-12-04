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
            Tableau de bord
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    <!-- Row starts -->
    <div class="row gx-3">
        <div class="col-xxl-12 col-sm-12">
            <div class="card mb-3 bg-2">
                <div class="card-body" style="background: rgba(0, 0, 0, 0.7);">
                    <div class="py-4 px-3 text-white">
                        <h6>Bienvenue,</h6>
                        <h2>Dr. Patrick Kim</h2>
                        <h5>Votre emploi du temps aujourd'hui.</h5>
                        <div class="mt-4 d-flex gap-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-box lg bg-success rounded-5 me-3">
                                    <i class="ri-walk-line fs-1"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h2 class="m-0 lh-1">9</h2>
                                    <p class="m-0">Patients</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Row ends -->
    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#success">
        success
    </a>
    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#error">
        error
    </a>
    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#info">
        info
    </a>
    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#warning">
        alert
    </a>
    <a class="btn btn-primary" onclick="generatePDF()">
        pdf
    </a>
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('success'));
            myModal.show();
        });
    </script> --}}
    <div class="modal fade" id="success" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body p-5 text-center">
                    <h1 class="display-4">
                        <i class="ri-checkbox-circle-line text-success"></i>
                    </h1>
                    <h4 class="text-success">Succès</h4>
                    <p>action éffectué</p>
                    <a data-bs-dismiss="modal" class="btn btn-lg btn-success w-25">
                        ok
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="error" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body p-5 text-center">
                    <h1 class="display-4">
                        <i class="ri-close-circle-line text-danger"></i>
                    </h1>
                    <h4 class="text-danger">Erreur</h4>
                    <p>action non éffectué</p>
                    <a data-bs-dismiss="modal" class="btn btn-lg btn-danger w-25">
                        ok
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="info" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body p-5 text-center">
                    <h1 class="display-4">
                        <i class="ri-question-line text-info"></i>
                    </h1>
                    <h4 class="text-info">Information</h4>
                    <p>action non éffectué</p>
                    <a data-bs-dismiss="modal" class="btn btn-lg btn-info w-25">
                        ok
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="warning" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body p-5 text-center">
                    <h1 class="display-4">
                        <i class="ri-alert-line text-warning"></i>
                    </h1>
                    <h4 class="text-warning">Alert</h4>
                    <p>action non éffectué</p>
                    <a data-bs-dismiss="modal" class="btn btn-lg btn-warning w-25">
                        ok
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Row starts -->
    <div class="row gx-3">
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="p-2 border border-success rounded-circle me-3">
                            <div class="icon-box md bg-success-subtle rounded-5">
                                <i class="ri-surgical-mask-line fs-4 text-success"></i>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <h2 class="lh-1">890</h2>
                            <p class="m-0">New Patients</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-1">
                        <a class="text-success" href="javascript:void(0);">
                            <span>View All</span>
                            <i class="ri-arrow-right-line text-success ms-1"></i>
                        </a>
                        <div class="text-end">
                            <p class="mb-0 text-success">+40%</p>
                            <span class="badge bg-success-subtle text-success small">this month</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="p-2 border border-primary rounded-circle me-3">
                            <div class="icon-box md bg-primary-subtle rounded-5">
                                <i class="ri-lungs-line fs-4 text-primary"></i>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <h2 class="lh-1">360</h2>
                            <p class="m-0">OPD Patients</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-1">
                        <a class="text-primary" href="javascript:void(0);">
                            <span>View All</span>
                            <i class="ri-arrow-right-line ms-1"></i>
                        </a>
                        <div class="text-end">
                            <p class="mb-0 text-primary">+30%</p>
                            <span class="badge bg-primary-subtle text-primary small">this month</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="p-2 border border-danger rounded-circle me-3">
                            <div class="icon-box md bg-danger-subtle rounded-5">
                                <i class="ri-microscope-line fs-4 text-danger"></i>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <h2 class="lh-1">980</h2>
                            <p class="m-0">Lab tests</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-1">
                        <a class="text-danger" href="javascript:void(0);">
                            <span>View All</span>
                            <i class="ri-arrow-right-line ms-1"></i>
                        </a>
                        <div class="text-end">
                            <p class="mb-0 text-danger">+60%</p>
                            <span class="badge bg-danger-subtle text-danger small">this month</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="p-2 border border-warning rounded-circle me-3">
                            <div class="icon-box md bg-warning-subtle rounded-5">
                                <i class="ri-money-dollar-circle-line fs-4 text-warning"></i>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <h2 class="lh-1">$98000</h2>
                            <p class="m-0">Total Earnings</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-1">
                        <a class="text-warning" href="javascript:void(0);">
                            <span>View All</span>
                            <i class="ri-arrow-right-line ms-1"></i>
                        </a>
                        <div class="text-end">
                            <p class="mb-0 text-warning">+20%</p>
                            <span class="badge bg-warning-subtle text-warning small">this month</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Row ends -->
    <!-- Row starts -->
    <div class="row gx-3">
        <div class="col-xl-2 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <div class="icon-box md rounded-5 bg-primary mb-3">
                            <i class="ri-verified-badge-line fs-4 lh-1"></i>
                        </div>
                        <h6>Appointments</h6>
                        <h2 class="text-primary m-0">639</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <div class="icon-box md rounded-5 bg-primary mb-3">
                            <i class="ri-stethoscope-line fs-4 lh-1"></i>
                        </div>
                        <h6>Doctors</h6>
                        <h2 class="text-primary m-0">83</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <div class="icon-box md rounded-5 bg-primary mb-3">
                            <i class="ri-psychotherapy-line fs-4 lh-1"></i>
                        </div>
                        <h6>Staff</h6>
                        <h2 class="text-primary m-0">296</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <div class="icon-box md rounded-5 bg-primary mb-3">
                            <i class="ri-lungs-line fs-4 lh-1"></i>
                        </div>
                        <h6>Operations</h6>
                        <h2 class="text-primary m-0">49</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <div class="icon-box md rounded-5 bg-primary mb-3">
                            <i class="ri-hotel-bed-line fs-4 lh-1"></i>
                        </div>
                        <h6>Admitted</h6>
                        <h2 class="text-primary m-0">372</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <div class="icon-box md rounded-5 bg-primary mb-3">
                            <i class="ri-walk-line fs-4 lh-1"></i>
                        </div>
                        <h6>Discharged</h6>
                        <h2 class="text-primary m-0">253</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Row ends -->

    <!-- Row starts -->
    <div class="row gx-3">
        <div class="col-xxl-12 col-sm-12">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Mes patients</h5>
                </div>
                <div class="card-body">
                    <div id="availableBeds"></div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6 col-sm-12">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Patients</h5>
                </div>
                <div class="card-body">
                    <div id="patients"></div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6 col-sm-12">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Treatment Type</h5>
                </div>
                <div class="card-body">
                    <div id="treatment"></div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6 col-sm-12">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Hospital Earnings</h5>
                </div>
                <div class="card-body">
                    <!-- Row start -->
                    <div class="row g-3">
                        <div class="col-sm-6 col-12">
                            <div class="border rounded-2 d-flex align-items-center flex-row p-2">
                                <div class="me-2">
                                    <div id="sparkline1"></div>
                                </div>
                                <div class="m-0">
                                    <div class="d-flex align-items-center">
                                        <h4 class="m-0 fw-bold">$4900</h4>
                                        <div class="ms-2 text-primary d-flex">
                                            <small>20%</small> <i class="ri-arrow-right-up-line ms-1 fw-bold"></i>
                                        </div>
                                    </div>
                                    <small>Online Consultation</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="border rounded-2 d-flex align-items-center flex-row p-2">
                                <div class="me-2">
                                    <div id="sparkline2"></div>
                                </div>
                                <div class="m-0">
                                    <div class="d-flex align-items-center">
                                        <div class="fs-4 fw-bold">$750</div>
                                        <div class="ms-2 text-danger d-flex">
                                            <small>26%</small> <i class="ri-arrow-right-down-line ms-1 fw-bold"></i>
                                        </div>
                                    </div>
                                    <small class="text-dark">Overall Purchases</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="border rounded-2 d-flex align-items-center flex-row p-2">
                                <div class="me-2">
                                    <div id="sparkline3"></div>
                                </div>
                                <div class="m-0">
                                    <div class="d-flex align-items-center">
                                        <div class="fs-4 fw-bold">$560</div>
                                        <div class="ms-2 text-primary d-flex">
                                            <small>28%</small> <i class="ri-arrow-right-up-line ms-1 fw-bold"></i>
                                        </div>
                                    </div>
                                    <small class="text-dark">Pending Invoices</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="border rounded-2 d-flex align-items-center flex-row p-2">
                                <div class="me-2">
                                    <div id="sparkline4"></div>
                                </div>
                                <div class="m-0">
                                    <div class="d-flex align-items-center">
                                        <div class="fs-4 fw-bold">$390</div>
                                        <div class="ms-2 text-primary d-flex">
                                            <small>30%</small> <i class="ri-arrow-right-up-line ms-1 fw-bold"></i>
                                        </div>
                                    </div>
                                    <small class="text-dark">Monthly Billing</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Row ends -->
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Insurance Claims</h5>
                </div>
                <div class="card-body">
                    <div id="claims"></div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Patients by Gender</h5>
                </div>
                <div class="card-body">
                    <div class="auto-align-graph">
                        <div id="genderAge"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Row ends -->
</div>

<script src="assets/vendor/apex/apexcharts.min.js"></script>
<script>
    var options = {
        chart: {
            height: 400,
            type: "bar",
            toolbar: {
                show: false,
            },
        },
        plotOptions: {
            bar: {
                borderRadius: 2,     // Pour arrondir les coins des barres
            }
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            curve: "smooth",
            width: 3,
        },
        series: [{
                name: "Homme",
                data: [10, 40, 15, 40, 20, 35, 20, 10, 31, 43, 56, 29],
            },
            {
                name: "Femme",
                data: [9, 20, 30, 51, 8, 25, 7, 35, 42, 20, 18, 35],
            },
            {
                name: "Enfant",
                data: [29, 8, 71, 35, 42, 20, 33, 67, 25, 7, 10, 20],
            },
        ],
        grid: {
            borderColor: "#d8dee6",
            strokeDashArray: 3,
            xaxis: {
                lines: {
                    show: true,
                },
            },
            yaxis: {
                lines: {
                    show: false,
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
                "Jan",
                "Fev",
                "Mar",
                "Avr",
                "Mai",
                "Jun",
                "Jul",
                "Aôu",
                "Sep",
                "Oct",
                "Nov",
                "Dec",
            ],
        },
        yaxis: {
            labels: {
                show: false,
            },
        },
         colors: ["#FF0000", "#008000", "#0000FF"],
        markers: {
            size: 0,
            opacity: 0.3,
            colors: ["red", "green", "blue"],
            strokeColor: "#ffffff",
            strokeWidth: 1,
            hover: {
                size: 7,
            },
        },
    };

    var chart = new ApexCharts(document.querySelector("#availableBeds"), options);

    chart.render();

</script>

<script src="https://unpkg.com/jspdf-invoice-template@1.4.4/dist/index.js"></script>


@endsection
