<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Espace Santé</title>
    <link rel="shortcut icon" href="{{asset('assets/images/logo.png')}}">
    <link rel="stylesheet" href="{{asset('assets/fonts/remix/remixicon.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/main.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendor/overlay-scroll/OverlayScrollbars.min.css')}}">
    <script src="{{asset('sweetalert.js')}}"></script>

    <script src="{{asset('jquery.min.js')}}"></script>
    <link href="{{asset('assets/vendor/select2/dist/css/select2.min.css')}}" rel="stylesheet" />
    <script src="{{asset('assets/vendor/select2/dist/js/select2.min.js')}}"></script>

    <link rel="stylesheet" href="{{asset('assets/vendor/dataTable2/datatables.css')}}" />
    <script src="{{asset('assets/vendor/dataTable2/datatables.js')}}"></script>
    <script src="{{asset('assets/vendor/dataTable2/datatables_lang_config.js')}}"></script>
    <script src="{{asset('assets/vendor/dataTable2/datatables_lang_config_init.js')}}"></script>

    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables/dataTables.bs5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables/dataTables.bs5-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables/buttons/dataTables.bs5-custom.css') }}">

</head>

<body>
    <!-- Loading starts -->
    <div id="loading-wrapper" class="bg-white">
        {{-- <div class='spin-wrapper'>
            <div class='spin'>
                <div class='inner'></div>
            </div>
            <div class='spin'>
                <div class='inner'></div>
            </div>
            <div class='spin'>
                <div class='inner'></div>
            </div>
            <div class='spin'>
                <div class='inner'></div>
            </div>
            <div class='spin'>
                <div class='inner'></div>
            </div>
            <div class='spin'>
                <div class='inner'></div>
            </div>
        </div> --}}
        <img src="{{asset('assets/images/logo.png')}}" height="200" width="200" class="mb-3">
        {{-- <div class="d-flex justify-content-center align-items-center">
            <div class="spinner-border text-primary me-3" role="status" aria-hidden="true"></div>
            <strong>Un instant, s'il vous plaît...</strong>
        </div> --}}
    </div>
    <div class="page-wrapper">
        <div class="app-header d-flex align-items-center">
            <div class="d-flex" >
                <button class="toggle-sidebar">
                    <i class="ri-menu-line"></i>
                </button>
                <button class="pin-sidebar">
                    <i class="ri-menu-line"></i>
                </button>
            </div>
            <div class="app-brand ms-3 me-2">
                {{-- <a href="" class="d-lg-block d-none">
                    <img src="{{asset('assets/images/logo.png')}}" height="40" width="40" class="" style="border-radius: 10%;">
                </a>
                <a href="" class="d-lg-none d-md-block">
                    <img src="{{asset('assets/images/logo.png')}}" height="40" width="40" class="" style="border-radius: 10%;">
                </a> --}}
                <a href="" class="">
                    <img src="{{asset('assets/images/logo.png')}}" height="40" width="40" class="" style="border-radius: 5%;">
                </a>
            </div>
            {{-- <marquee>
                Liste des Rendez-Vous : 
            </marquee> --}}
            <div class="d-flex justify-content-center align-items-center text-center w-100" >
                <marquee>
                    <h4 class="text-white" >ESPACE MEDICO - SOCIAL LA PYRAMIDE</h4>
                </marquee>
            </div>
            <div class="header-actions">
                <div class="dropdown ms-2">
                    <a id="userSettings" class="dropdown-toggle d-flex align-items-center" href="#!" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar-box bg-white text-primary">
                            <i class="ri-user-3-line"></i>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg">
                        {{-- <div class="px-3 py-2">
                            <span class="small">{{Auth::user()->role}}</span>
                        </div> --}}
                        <div class="px-3 py-2 text-center" >
                            <img src="{{asset('assets/images/user8.png')}}" class="img-shadow img-3x rounded-5 mb-3">
                            <h6 class="mb-1 profile-name text-nowrap text-truncate ">
                                {{session('user_role')}}
                            </h6>
                        </div>
                        <div class="mx-3 my-2 d-grid">
                            <a class="btn btn-primary d-flex align-items-center justify-content-between" data-bs-toggle="modal" data-bs-target="#DetailProfil" id="detailprofil">
                                <span class="me-3" >Mon Profil</span>
                                <i class="ri-user-3-line"></i>
                            </a>
                        </div>
                        <div class="mx-3 my-2 d-grid">
                            <a class="btn btn-warning d-flex align-items-center justify-content-between" data-bs-toggle="modal" data-bs-target="#Parametrage" id="parametrage">
                                <span class="me-3" >Parametre</span>
                                <i class="ri-settings-4-line"></i>
                            </a>
                        </div>
                        <div class="mx-3 my-2 d-grid">
                            <a href="{{route('deconnecter')}}" class="btn btn-danger d-flex align-items-center justify-content-between">
                                <span class="me-3" >Déconnexion</span>
                                <i class="ri-contract-right-fill"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-container">
            <nav id="sidebar" class="sidebar-wrapper">
                <a class="sidebar-profile" >
                    <img src="{{asset('assets/images/user8.png')}}" class="img-shadow img-3x me-3 rounded-5">
                    <div class="m-0">
                        <h6 class="mb-1 profile-name text-nowrap text-truncate ">
                            {{session('user_role')}}
                        </h6>
                        <p class="m-0 small profile-name text-nowrap text-truncate">
                            {{-- {{ explode(' ', Auth::user()->user_last_name)[0] }}
                            {{ isset(explode(' ', Auth::user()->name)[1]) ? 
                            explode(' ', Auth::user()->name)[1] : '' }} --}}
                            {{ Auth::user()->user_first_name }}
                        </p>
                        {{-- <p class="m-0 small profile-name text-nowrap text-truncate">
                            {{Auth::user()->email}}
                        </p> --}}
                    </div>
                </a>
                <div class="sidebarMenuScroll">
                    <ul class="sidebar-menu">
                        <li @if(request()->routeIs('index_accueil','consultation_liste','patient_liste','societe_liste','horaire_medecin','assurance_liste')) class="active current-page treeview" @else class="treeview" @endif >
                            <a href="#!">
                                <i class="ri-computer-line"></i>
                                <span class="menu-text text-primary">
                                    <b>Réception</b>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a @if(request()->routeIs('index_accueil')) class="text-primary" @endif href="{{route('index_accueil')}}">
                                        <b>Tableau de bord</b>
                                    </a>
                                </li>
                                <li>
                                    <a @if(request()->routeIs('consultation_liste')) class="text-primary" @endif href="{{route('consultation_liste')}}">
                                        <b>Consultations</b>
                                    </a>
                                </li>
                                <li>
                                    <a @if(request()->routeIs('assurance_liste')) class="text-primary" @endif href="{{route('assurance_liste')}}">
                                        <b>Assurances</b>
                                    </a>
                                </li>
                                <li>
                                    <a @if(request()->routeIs('patient_liste')) class="text-primary" @endif href="{{route('patient_liste')}}">
                                        <b>Patients</b>
                                    </a>
                                </li>
                                <li>
                                    <a @if(request()->routeIs('societe_liste')) class="text-primary" @endif href="{{route('societe_liste')}}">
                                        <b>Sociétés</b>
                                    </a>
                                </li>
                                <li>
                                    <a @if(request()->routeIs('horaire_medecin')) class="text-primary" @endif href="{{route('horaire_medecin')}}">
                                        <b>Horaires Médecins</b>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li @if(request()->routeIs('hospitalisation','examen','soinsam')) class="active current-page treeview" @else class="treeview" @endif>
                            <a href="#!">
                                <i class="ri-first-aid-kit-fill"></i>
                                <span class="menu-text text-primary">
                                    <b>Services</b>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a @if(request()->routeIs('examen')) class="text-primary" @endif href="{{route('examen')}}">
                                        <b>Examens</b>
                                    </a>
                                </li>
                                <li>
                                    <a @if(request()->routeIs('hospitalisation')) class="text-primary" @endif href="{{route('hospitalisation')}}">
                                        <b>Hospitalisations</b>
                                    </a>
                                </li>
                                <li>
                                    <a @if(request()->routeIs('soinsam')) class="text-primary" @endif href="{{route('soinsam')}}">
                                        <b>Soins Ambulantoires</b>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li @if(request()->routeIs('comptable','facture_impayer','facture_liste','caisse','operation_caisse')) class="active current-page treeview" @else class="treeview" @endif >
                            <a href="#!">
                                <i class="ri-line-chart-fill"></i>
                                <span class="menu-text text-primary">
                                    <b>Comptabilité</b>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a @if(request()->routeIs('comptable')) class="text-primary" @endif href="{{route('comptable')}}">
                                        <b>Tableau de Bord</b>
                                    </a>
                                </li>
                                <li>
                                    <a @if(request()->routeIs('operation_caisse')) class="text-primary" @endif href="{{route('operation_caisse')}}">
                                        <b>Opération de caisse</b>
                                    </a>
                                </li>
                                {{-- <li>
                                    <a @if(request()->routeIs('caisse')) class="text-primary" @endif href="{{route('caisse')}}">
                                        <b>Caisse</b>
                                    </a>
                                </li> --}}
                                <li>
                                    <a @if(request()->routeIs('facture_impayer')) class="text-primary" @endif href="{{route('facture_impayer')}}">
                                        <b>Facture Impayer</b>
                                    </a>
                                </li>
                                <li>
                                    <a @if(request()->routeIs('facture_liste')) class="text-primary" @endif href="{{route('facture_liste')}}">
                                        <b>Liste des Factures</b>
                                    </a>
                                </li>
                                {{-- <li @if(request()->routeIs('encaissement_cons','liste_caisse_cons','encaissement_hos','liste_caisse_hos','encaissement_soinsam','liste_caisse_soinsam','encaissement_examen','liste_caisse_examen')) class="active" @endif>
                                    <a href="#!">
                                        <span class="menu-text">
                                            <b>Caisse</b>
                                        </span>
                                        <i class="ri-arrow-right-s-line"></i>
                                    </a>
                                    <ul class="treeview-menu">
                                        <li @if(request()->routeIs('encaissement_cons','liste_caisse_cons')) class="active" @endif >
                                            <a href="#!">
                                                <b>Consultations</b>
                                                <i class="ri-arrow-right-s-line"></i>
                                            </a>
                                            <ul class="treeview-menu">
                                                <li>
                                                    <a @if(request()->routeIs('encaissement_cons')) class="text-primary" @endif href="{{route('encaissement_cons')}}">Facture non-réglée</a>
                                                </li>
                                                <li>
                                                    <a @if(request()->routeIs('liste_caisse_cons')) class="text-primary" @endif href="{{route('liste_caisse_cons')}}">Liste facture</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li @if(request()->routeIs('encaissement_hos','liste_caisse_hos')) class="active" @endif >
                                            <a href="#!">
                                                <b>Hospitalisations</b>
                                                <i class="ri-arrow-right-s-line"></i>
                                            </a>
                                            <ul class="treeview-menu">
                                                <li>
                                                    <a @if(request()->routeIs('encaissement_hos')) class="text-primary" @endif href="{{route('encaissement_hos')}}">Facture non-réglée</a>
                                                </li>
                                                <li>
                                                    <a @if(request()->routeIs('liste_caisse_hos')) class="text-primary" @endif href="{{route('liste_caisse_hos')}}">Liste facture</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li @if(request()->routeIs('encaissement_soinsam','liste_caisse_soinsam')) class="active" @endif >
                                            <a href="#!">
                                                <b>Soins Ambulatoires</b>
                                                <i class="ri-arrow-right-s-line"></i>
                                            </a>
                                            <ul class="treeview-menu">
                                                <li>
                                                    <a @if(request()->routeIs('encaissement_soinsam')) class="text-primary" @endif href="{{route('encaissement_soinsam')}}">Facture non-réglée</a>
                                                </li>
                                                <li>
                                                    <a @if(request()->routeIs('liste_caisse_soinsam')) class="text-primary" @endif href="{{route('liste_caisse_soinsam')}}">Liste facture</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li @if(request()->routeIs('encaissement_examen','liste_caisse_examen')) class="active" @endif >
                                            <a href="#!">
                                                <b>Examens</b>
                                                <i class="ri-arrow-right-s-line"></i>
                                            </a>
                                            <ul class="treeview-menu">
                                                <li>
                                                    <a @if(request()->routeIs('encaissement_examen')) class="text-primary" @endif href="{{route('encaissement_examen')}}">Facture non-réglée</a>
                                                </li>
                                                <li>
                                                    <a @if(request()->routeIs('liste_caisse_examen')) class="text-primary" @endif href="{{route('liste_caisse_examen')}}">Liste facture</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li> --}}
                            </ul>
                        </li>
                        <li @if(request()->routeIs('facture_emise','facture_depot','facture_deposer')) class="active current-page treeview" @else class="treeview" @endif >
                            <a href="#!">
                                <i class="ri-archive-fill"></i>
                                <span class="menu-text text-primary">
                                    <b>Gestions Factures</b>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a @if(request()->routeIs('facture_emise')) class="text-primary" @endif href="{{route('facture_emise')}}">
                                        <b>Emissions Factures</b>
                                    </a>
                                </li>
                                <li>
                                    <a @if(request()->routeIs('facture_depot')) class="text-primary" @endif href="{{route('facture_depot')}}">
                                        <b>Depôts de factures</b>
                                    </a>
                                </li>
                                <li>
                                    <a @if(request()->routeIs('facture_deposer')) class="text-primary" @endif href="{{route('facture_deposer')}}">
                                        <b>Liste des Factures</b>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li @if(request()->routeIs('medecin_new','assurance_new','taux_new','societe_new','acte_new','typeacte_new','chambre_new','lit_new','typeadmission_new','natureadmission_new','produit_new','typesoins_new','soinsinfirmier_new','specialite','utilisateur')) class="active current-page treeview" @else class="treeview" @endif>
                            <a href="#!">
                                <i class="ri-settings-5-line"></i>
                                <span class="menu-text text-primary">
                                    <b>Configuration</b>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li @if(request()->routeIs('chambre_new','lit_new','typeadmission_new','natureadmission_new','produit_new')) class="active" @endif >
                                    <a href="#!">
                                        <b>Hospitalisations</b>
                                        <i class="ri-arrow-right-s-line"></i>
                                    </a>
                                    <ul class="treeview-menu">
                                        <li>
                                            <a @if(request()->routeIs('chambre_new')) class="text-primary" @endif href="{{route('chambre_new')}}">Chambre</a>
                                        </li>
                                        <li>
                                            <a @if(request()->routeIs('lit_new')) class="text-primary" @endif href="{{route('lit_new')}}">lit</a>
                                        </li>
                                        <li>
                                            <a @if(request()->routeIs('typeadmission_new')) class="text-primary" @endif href="{{route('typeadmission_new')}}">Type admission</a>
                                        </li>
                                        <li>
                                            <a @if(request()->routeIs('natureadmission_new')) class="text-primary" @endif href="{{route('natureadmission_new')}}">Nature admission</a>
                                        </li>
                                        <li>
                                            <a @if(request()->routeIs('produit_new')) class="text-primary" @endif href="{{route('produit_new')}}">Produit Pharmacie</a>
                                        </li>
                                    </ul>
                                </li>
                                <li @if(request()->routeIs('typesoins_new','soinsinfirmier_new')) class="active" @endif >
                                    <a href="#!">
                                        <b>Infirmerie</b>
                                        <i class="ri-arrow-right-s-line"></i>
                                    </a>
                                    <ul class="treeview-menu">
                                        <li>
                                            <a @if(request()->routeIs('typesoins_new')) class="text-primary" @endif  href="{{route('typesoins_new')}}">Type Soins</a>
                                        </li>
                                        <li>
                                            <a @if(request()->routeIs('soinsinfirmier_new')) class="text-primary" @endif href="{{route('soinsinfirmier_new')}}">Soins Infirmier</a>
                                        </li>
                                    </ul>
                                </li>
                                <li @if(request()->routeIs('medecin_new','acte_new','typeacte_new','specialite')) class="active" @endif >
                                    <a href="#!">
                                        <b>Medecin</b>
                                        <i class="ri-arrow-right-s-line"></i>
                                    </a>
                                    <ul class="treeview-menu">
                                        <li>
                                            <a @if(request()->routeIs('medecin_new')) class="text-primary" @endif  href="{{route('medecin_new')}}">Nouveau</a>
                                        </li>
                                        {{-- <li>
                                            <a @if(request()->routeIs('acte_new')) class="text-primary" @endif  href="{{route('acte_new')}}">Acte</a>
                                        </li>
                                        <li>
                                            <a @if(request()->routeIs('typeacte_new')) class="text-primary" @endif href="{{route('typeacte_new')}}">Type acte</a>
                                        </li> --}}
                                        <li>
                                            <a @if(request()->routeIs('specialite')) class="text-primary" @endif href="{{route('specialite')}}">
                                                Spécialité
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a @if(request()->routeIs('utilisateur')) class="text-primary" @endif href="{{route('utilisateur')}}">
                                        <b>Utilisateurs</b>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li @if(request()->routeIs('etat_facture','etat_caisse','etat_acte')) class="active current-page treeview" @else class="treeview" @endif >
                            <a href="#!">
                                <i class="ri-file-pdf-2-line"></i>
                                <span class="menu-text text-primary">
                                    <b>Etats</b>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a @if(request()->routeIs('etat_acte')) class="text-primary" @endif href="{{route('etat_acte')}}">
                                        <b>Actes</b>
                                    </a>
                                </li>
                                <li>
                                    <a @if(request()->routeIs('etat_caisse')) class="text-primary" @endif href="{{route('etat_caisse')}}">
                                        <b>Caisse</b>
                                    </a>
                                </li>
                                <li>
                                    <a @if(request()->routeIs('etat_facture')) class="text-primary" @endif href="{{route('etat_facture')}}">
                                        <b>Factures</b>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li @if(request()->routeIs('index_propos')) class="active current-page" @endif>
                            <a href="{{route('index_propos')}}">
                                <i class="ri-question-fill"></i>
                                <span class="menu-text text-primary">
                                    <b>A propos</b>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
                {{-- <div class="sidebarMenuScroll">
                    <ul class="sidebar-menu">
                        <li>
                            <div class="mb-3">
                                <div class="card-header text-center mb-3">
                                    <h5 class="card-title">Rendez-Vous</h5>
                                </div>
                                <div class="card-body">
                                    <div class="scroll350">
                                        <div class="activity-feed">
                                            <div class="feed-item">
                                                <span class="feed-date pb-1" data-bs-toggle="tooltip" data-bs-title="Today 05:32:35">An
                                                    Hour Ago</span>
                                                <div class="mb-1">
                                                    <a href="#" class="text-primary">Dr. Janie Mcdonald</a> - sent a new prescription.
                                                </div>
                                                <div class="mb-1">Medecine Name - <a href="#" class="text-danger">Amocvmillin</a></div>
                                                <a href="#!" class="text-dark">Payment Link <i class="ri-arrow-right-up-line"></i> </a>
                                            </div>
                                            <div class="feed-item">
                                                <span class="feed-date pb-1" data-bs-toggle="tooltip" data-bs-title="Today 05:32:35">An
                                                    Hour Ago</span>
                                                <div class="mb-1">
                                                    <a href="#" class="text-primary">Dr. Hector Banks</a> - uploaded a report.
                                                </div>
                                                <div class="mb-1">Report Name - <a href="#" class="text-danger">Lisymorpril</a></div>
                                                <a href="#!" class="text-dark">Payment Link <i class="ri-arrow-right-up-line"></i> </a>
                                            </div>
                                            <div class="feed-item">
                                                <span class="feed-date pb-1" data-bs-toggle="tooltip" data-bs-title="Today 05:32:35">An
                                                    Hour Ago</span>
                                                <div class="mb-1">
                                                    <a href="#" class="text-primary">Dr. Deena Cooley</a> - sent medecine details.
                                                </div>
                                                <div class="mb-1">Medecine Name - <a href="#" class="text-danger">Predeymsone</a></div>
                                                <a href="#!" class="text-dark">Payment Link <i class="ri-arrow-right-up-line"></i> </a>
                                            </div>
                                            <div class="feed-item">
                                                <span class="feed-date pb-1" data-bs-toggle="tooltip" data-bs-title="Today 05:32:35">An
                                                    Hour Ago</span>
                                                <div class="mb-1">
                                                    <a href="#" class="text-primary">Dr. Mitchel Alvarez</a> - added import files.
                                                </div>
                                                <div class="mb-1">File Name - <a href="#" class="text-danger">Naverreone</a></div>
                                                <a href="#!" class="text-dark">Payment Link <i class="ri-arrow-right-up-line"></i> </a>
                                            </div>
                                            <div class="feed-item">
                                                <span class="feed-date pb-1" data-bs-toggle="tooltip" data-bs-title="Today 05:32:35">An
                                                    Hour Ago</span>
                                                <div class="mb-1">
                                                    <a href="#" class="text-primary">Dr. Owen Scott</a> - reviewed your file.
                                                </div>
                                                <div class="mb-1">File Name - <a href="#" class="text-danger">Gabateyntin</a></div>
                                                <a href="#!" class="text-dark">Payment Link <i class="ri-arrow-right-up-line"></i> </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div> --}}
                <div id="div_two_rdv"></div>
            </nav>
            <div class="app-container">
                @yield('info_page')
                @yield('content')
                <div class="app-footer bg-primary text-white text-center">
                    <span class="fs-6" >Copyright © Espace Santé 2024 - Tous droits réservés.</span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="DetailProfil" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Profil
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal_detailProfil">
                    
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="Parametrage" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        Paramétrage
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal_parametrage">
                    
                </div>
            </div>
        </div>
    </div>

    <script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/js/moment.min.js')}}"></script>
    <script src="{{asset('assets/vendor/overlay-scroll/jquery.overlayScrollbars.min.js')}}"></script>
    <script src="{{asset('assets/vendor/overlay-scroll/custom-scrollbar.js')}}"></script>
    <script src="{{asset('assets/vendor/apex/apexcharts.min.js')}}"></script>
    <script src="{{asset('assets/js/custom.js')}}"></script>

    <script src="{{ asset('assets/vendor/datatables/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/custom/custom-datatables.js') }}"></script>

    {{-- <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        console.log("Token CSRF:", csrfToken);
    </script> --}}

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            count_rdv_two_day();

            function showAlert(title, message, type) {
                Swal.fire({
                    title: title,
                    text: message,
                    icon: type,
                });
            }

            function formatDate(dateString) {

                const date = new Date(dateString);
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
                const year = date.getFullYear();

                return `${day}/${month}/${year}`; // Format as dd/mm/yyyy
            }

            function formatDateHeure(dateString) {

                const date = new Date(dateString);
                    
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();

                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                const seconds = String(date.getSeconds()).padStart(2, '0');

                return `${day}/${month}/${year} à ${hours}:${minutes}:${seconds}`;
            }

            document.getElementById(`detailprofil`).addEventListener('click', () =>
            {
                const modal = document.getElementById('modal_detailProfil');
                modal.innerHTML = '';
                const div = document.createElement('div');
                div.innerHTML = `
                <div class="row gx-3">
                    <div class="col-12">
                        <div class=" mb-3">
                            <div class="card-body">
                                <div class="text-center">
                                    <a class="d-flex align-items-center flex-column">
                                        <img src="{{asset('assets/images/user8.png')}}" class="img-7x rounded-circle mb-3 border border-3">
                                        <h5>{{Auth::user()->sexe}}. {{Auth::user()->name}}</h5>
                                        <h6 class="text-truncate">
                                            {{Auth::user()->role}}
                                        </h6>
                                        <?php \Carbon\Carbon::setLocale('fr'); ?>
                                        <p>Date création : {{ \Carbon\Carbon::parse(Auth::user()->created_at)->isoFormat('dddd DD MMMM YYYY à HH:mm:ss') }} </p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class=" mb-3">
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item active text-center" aria-current="true">
                                        Informations personnelles
                                    </li>
                                    <li class="list-group-item">
                                        Matricule : {{Auth::user()->matricule}}
                                    </li>
                                    <li class="list-group-item">
                                        Rôle : {{Auth::user()->role}}
                                    </li>
                                    <li class="list-group-item">
                                        Nom et Prénoms : {{Auth::user()->sexe}}. {{Auth::user()->name}}
                                    </li>
                                    <li class="list-group-item">
                                        Email : {{Auth::user()->email}}
                                    </li>
                                    <li class="list-group-item">
                                        Genre : {{Auth::user()->sexe == 'Mme' ? 'Femme' : 'Homme' }}
                                    </li>
                                    <li class="list-group-item">
                                        Contact 1 : {{ Auth::user()->tel ? '+225 '.Auth::user()->tel : 'Néant' }}
                                    </li>
                                    <li class="list-group-item">
                                        Contact 2 : {{ Auth::user()->tel2 ? '+225 '.Auth::user()->tel2 : 'Néant' }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                `;
                modal.appendChild(div);
            });

            document.getElementById(`parametrage`).addEventListener('click', () =>
            {
                const modal = document.getElementById('modal_parametrage');
                modal.innerHTML = '';

                const div = document.createElement('div');
                div.className = 'border rounded-2 p-2 mb-3 justify-content-center';
                div.innerHTML = `
                    <div class="card-body mt-2">
                        <div class="text-center">
                            <a class="d-flex align-items-center flex-column">
                                <img src="{{asset('assets/images/info_user.png')}}" class="img-7x">
                            </a>
                        </div>
                    </div>
                    <div class="card-header mb-4 text-center mt-3">
                        <h5 class="card-title">Modification des Informations</h5>
                    </div>
                    <div class="card-body">
                        <div class="row gx-3">
                            <div class="col-lg-6 col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label">Nom et Prénoms</label>
                                    <input type="text" class="form-control" id="nom_para" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()" value="{{Auth::user()->name ? Auth::user()->name : '' }}">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label">Sexe</label>
                                    <select class="form-select" id="sexe_para">
                                        <option {{Auth::user()->sexe == 'Mr' ? 'selected' : '' }} value="Mr">Homme</option>
                                        <option {{Auth::user()->sexe == 'Mme' ? 'selected' : '' }} value="Mme">Femme</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email_para" placeholder="Saisie Obligatoire" value="{{Auth::user()->email ? Auth::user()->email : '' }}">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label">Contact</label>
                                    <input type="tel" class="form-control" id="tel_para" placeholder="Saisie Obligatoire" maxlength="10" value="{{Auth::user()->tel ? Auth::user()->tel : '' }}">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label">Contact 2</label>
                                    <input type="tel" class="form-control" id="tel2_para" placeholder="facultatif" maxlength="10" value="{{Auth::user()->tel2 ? Auth::user()->tel2 : '' }}">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label" for="adresse">Localisation</label>
                                    <input type="text" class="form-control" id="adresse_para" placeholder="Saisie Obligatoire" value="{{Auth::user()->adresse ? Auth::user()->adresse : '' }}">
                                </div>
                            </div>
                            <div class="col-sm-12 mt-3">
                                <div class="mb-3 d-flex gap-2 justify-content-center">
                                    <button id="btn_update_info" class="btn btn-outline-primary">
                                        <i class="ri-edit-line me-2"></i>
                                        Mise à jour
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                const div2 = document.createElement('div');
                div2.className = 'border rounded-2 p-2 mb-3 justify-content-center';
                div2.innerHTML = `
                    <div class="card-body mt-2">
                        <div class="text-center">
                            <a class="d-flex align-items-center flex-column">
                                <img src="{{asset('assets/images/password.png')}}" class="img-7x">
                            </a>
                        </div>
                    </div>
                    <div class="card-header mb-4 text-center mt-3">
                        <h5 class="card-title">Modification du mot de passe</h5>
                    </div>
                    <div class="card-body">
                        <div class="row gx-3">
                            <div class="col-lg-6 col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label">Nouveau mot de passe</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password1_para" placeholder="Saisie Obligatoire" autocomplete="off">
                                        <a class="btn btn-white" id="btn_hidden_mpd1">
                                            <i id="toggleIcon1" class="ri-eye-line text-primary"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label">Confirmer le mot de passe</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password2_para" placeholder="Saisie Obligatoire" autocomplete="off">
                                        <a class="btn btn-white" id="btn_hidden_mpd2">
                                            <i id="toggleIcon2" class="ri-eye-line text-primary"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 mt-3">
                                <div class="mb-3 d-flex gap-2 justify-content-center">
                                    <button id="btn_update_mdp" class="btn btn-outline-primary">
                                        <i class="ri-edit-line me-2"></i>
                                        Mise à jour
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                modal.appendChild(div);
                modal.appendChild(div2);

                var inputs = ['tel_para', 'tel2_para'];
                inputs.forEach(function(id) {
                    var inputElement = document.getElementById(id);
                    inputElement.addEventListener('input', function() {
                        this.value = this.value.replace(/[^0-9]/g, '');
                    });
                });

                const btn_info = document.getElementById("btn_update_info");
                if (btn_info) {
                    btn_info.addEventListener("click", update_info);
                }

                const btn_mdp = document.getElementById("btn_update_mdp");
                if (btn_mdp) {
                    btn_mdp.addEventListener("click", update_mdp);
                }

                const btn_hidden_mdp1 = document.getElementById("btn_hidden_mpd1");
                if (btn_hidden_mdp1) {

                    btn_hidden_mdp1.addEventListener("click", function(event) {
                        event.preventDefault();
                        const passwordField = document.getElementById('password1_para');
                        const toggleIcon = document.getElementById('toggleIcon1');
                        
                        // Toggle the type attribute
                        if (passwordField.type === 'password') {
                            passwordField.type = 'text';
                            toggleIcon.classList.remove('ri-eye-line');
                            toggleIcon.classList.add('ri-eye-off-line');
                        } else {
                            passwordField.type = 'password';
                            toggleIcon.classList.remove('ri-eye-off-line');
                            toggleIcon.classList.add('ri-eye-line');
                        }
                    });
                }

                const btn_hidden_mdp2 = document.getElementById("btn_hidden_mpd2");
                if (btn_hidden_mdp2) {

                    btn_hidden_mdp2.addEventListener("click", function(event) {
                        event.preventDefault();
                        const passwordField = document.getElementById('password2_para');
                        const toggleIcon = document.getElementById('toggleIcon2');
                        
                        // Toggle the type attribute
                        if (passwordField.type === 'password') {
                            passwordField.type = 'text';
                            toggleIcon.classList.remove('ri-eye-line');
                            toggleIcon.classList.add('ri-eye-off-line');
                        } else {
                            passwordField.type = 'password';
                            toggleIcon.classList.remove('ri-eye-off-line');
                            toggleIcon.classList.add('ri-eye-line');
                        }
                    });
                }
            });

            function update_info() {

                const id = {{Auth::user()->id}};
                const nom = document.getElementById("nom_para");
                const email = document.getElementById("email_para");
                const tel = document.getElementById("tel_para");
                const tel2 = document.getElementById("tel2_para");
                const sexe = document.getElementById("sexe_para");
                const adresse = document.getElementById("adresse_para");
                const role_id = {{Auth::user()->role_id}};

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Field validation
                if (!nom.value.trim() || !email.value.trim() || !tel.value.trim() || !sexe.value.trim() || !adresse.value.trim()) {
                    showAlert('Alert', 'Veuillez remplir tous les champs SVP.','warning');
                    return false;
                }

                // Email validation
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email.value.trim())) {
                    showAlert('Alert', 'Email incorrect.','warning');
                    return false;
                }

                // Phone validation
                if (tel.value.length !== 10 || (tel2.value !== '' && tel2.value.length !== 10)) {
                    showAlert('Alert', 'Contact incomplet.','warning');
                    return false;
                }

                var modal = bootstrap.Modal.getInstance(document.getElementById('Parametrage'));
                modal.hide();

                var preloader_ch = `
                    <div id="preloader_ch">
                        <div class="spinner_preloader_ch"></div>
                    </div>
                `;
                document.body.insertAdjacentHTML('beforeend', preloader_ch);

                $.ajax({
                    url: '/refresh-csrf',
                    method: 'GET',
                    success: function(response_crsf) {
                        // Met à jour la balise <meta> avec le nouveau token
                        document.querySelector('meta[name="csrf-token"]').setAttribute('content', response_crsf.csrf_token);
                        
                        // console.log("Nouveau token CSRF:", response_crsf.csrf_token);

                        $.ajax({
                            url: '/api/update_user/' + id,
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': response_crsf.csrf_token,
                            },
                            data: {
                                nom: nom.value, 
                                email: email.value, 
                                tel: tel.value, 
                                tel2: tel2.value || null, 
                                adresse: adresse.value || null, 
                                sexe: sexe.value, 
                                role_id: role_id,
                            },
                            success: function(response) {

                                document.getElementById('preloader_ch').remove();

                                if (response.tel_existe) {

                                    showAlert('Alert', 'Veuillez saisir autre numéro de téléphone s\'il vous plaît','warning');

                                }else if (response.email_existe) {

                                    showAlert('Alert', 'Veuillez saisir autre email s\'il vous plaît','warning');

                                }else if (response.nom_existe) {

                                    showAlert('Alert', 'Cet Utilisateur existe déjà.','warning');

                                } else if (response.success) {

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

                                    showAlert('Erreur', 'Une erreur est survenue lors de l\'enregistrement.','error');

                                }
                            },
                            error: function() {
                                document.getElementById('preloader_ch').remove();
                                showAlert('Erreur', 'Erreur lors de la mise à jour.','error');
                            }
                        });

                    },
                    error: function() {
                        console.log("Erreur lors du rafraîchissement du token CSRF");
                        document.getElementById('preloader_ch').remove();
                        showAlert('Erreur', 'Erreur lors de la mise à jour.','error');
                    }
                });
            }

            function update_mdp() {

                const id = {{Auth::user()->id}};
                const mdp1 = document.getElementById("password1_para");
                const mdp2 = document.getElementById("password2_para");

                // Field validation
                if (!mdp1.value.trim() || !mdp2.value.trim()) {
                    showAlert('Alert', 'Veuillez remplir tous les champs SVP.','warning');
                    return false;
                }

                // Phone validation
                if (mdp1.value !== mdp2.value) {
                    showAlert('Alert', 'Mot de passe incorrecte.','warning');
                    return false;
                }

                var modal = bootstrap.Modal.getInstance(document.getElementById('Parametrage'));
                modal.hide();

                var preloader_ch = `
                    <div id="preloader_ch">
                        <div class="spinner_preloader_ch"></div>
                    </div>
                `;
                document.body.insertAdjacentHTML('beforeend', preloader_ch);

                $.ajax({
                    url: '/refresh-csrf',
                    method: 'GET',
                    success: function(response_crsf) {
                        // Met à jour la balise <meta> avec le nouveau token
                        document.querySelector('meta[name="csrf-token"]').setAttribute('content', response_crsf.csrf_token);

                        $.ajax({
                            url: '/api/update_mdp/' + id,
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': response_crsf.csrf_token,
                            },
                            data: {
                                mdp1: mdp1.value,
                            },
                            success: function(response) {

                                document.getElementById('preloader_ch').remove();

                                if (response.success) {

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

                                    showAlert('Erreur', 'Echec de l\'opération.','error');

                                }
                            },
                            error: function() {
                                document.getElementById('preloader_ch').remove();
                                showAlert('Erreur', 'Erreur lors de la mise à jour.','error');
                            }
                        });
                    },
                    error: function() {
                        console.log("Erreur lors du rafraîchissement du token CSRF");
                        document.getElementById('preloader_ch').remove();
                        showAlert('Erreur', 'Erreur lors de la mise à jour.','error');
                    }
                });
            }

            function count_rdv_two_day() {

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

</body>
</html>