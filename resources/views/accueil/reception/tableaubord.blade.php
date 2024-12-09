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
        <li class="breadcrumb-item">
            Tableau de bord
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    <div class="row gx-3">
        <div class="col-xxl-9 col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="card mb-3 bg-2 rounded-2">
                <div class="card-body rounded-2" style="background: rgba(0, 0, 0, 0.7);">
                    <div class="mh-230">
                        <div class="text-white">
                            <h6>Bienvenue,</h6>
                            <h2>Mr/Mme {{Auth::user()->login}}</h2>
                            <h5>Les statistiques d'aujourd'hui.</h5>
                            <div class="mt-4 row gx-3">
                                <div class="d-flex align-items-center col-xxl-3 col-lg-3 col-md-6 col-sm-6 col-12 mb-3 ">
                                    <div class="icon-box md bg-info rounded-5 me-3">
                                        <i class="ri-walk-line fs-4"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h4 id="nbre_patient_day" class="m-0 lh-1"></h4>
                                        <p class="m-0">Patients</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center col-xxl-3 col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                                    <div class="icon-box md bg-success rounded-5 me-3">
                                        <i class="ri-walk-line fs-4"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h4 id="nbre_patient_assurer_day" class="m-0 lh-1"></h4>
                                        <p class="m-0">assurer</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center col-xxl-3 col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                                    <div class="icon-box md bg-danger rounded-5 me-3">
                                        <i class="ri-walk-line fs-4"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h4 id="nbre_patient_nassurer_day" class="m-0 lh-1"></h4>
                                        <p class="m-0">non-assurer</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center col-xxl-3 col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                                    <div class="icon-box md bg-warning rounded-5 me-3">
                                        <i class="ri-cash-line fs-4"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h4 id="prix_cons_day" class="m-0 lh-1"></h4>
                                        <p class="m-0">Total</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="card mb-3 bg-lime">
                <div class="card-body">
                    <div class="mh-230 text-white">
                        <h5>Activités de la semaine</h5>
                        <div class="text-body chart-height-md" id="docActivity" style="margin-top: -30px;">
                        </div>
                        <div id="consultationComparison" style="margin-top: -10px;" ></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <div class="card-body ">
                    <ol class="breadcrumb justify-content-center align-items-center">
                        <li class="" style="display: block;" id="div_btn_affiche_stat">
                            <a class="btn btn-sm btn-warning" id="btn_affiche_stat">
                                Afficher les Statstiques
                                <i class="ri-eye-line" ></i>
                            </a>
                        </li>
                        <li class="" style="display: none;" id="div_btn_cache_stat">
                            <a class="btn btn-sm btn-danger" id="btn_cache_stat">
                                Cacher les Statstiques
                                <i class="ri-eye-off-line" ></i>
                            </a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row gx-3 mb-3" id="stat_consultation"></div>

    <div class="row gx-3" >
        <div class="col-sm-12">
            <div class="card mb-3">
                <div class="card-body" style="margin-top: -32px;">
                    <div class="custom-tabs-container">
                        <ul class="nav nav-tabs justify-content-center bg-primary bg-2" id="customTab4" role="tablist" style="background: rgba(0, 0, 0, 0.7);">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-white" id="tab-twoAAA" data-bs-toggle="tab" href="#twoAAA" role="tab" aria-controls="twoAAA" aria-selected="false" tabindex="-1">
                                    <i class="ri-file-user-line me-2"></i>
                                    Nouveau patient
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active text-white" id="tab-oneAAA" data-bs-toggle="tab" href="#oneAAA" role="tab" aria-controls="oneAAA" aria-selected="false" tabindex="-1">
                                    <i class="ri-first-aid-kit-line me-2"></i>
                                    Nouvelle consultation
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link  text-white" id="tab-threeAAAL" data-bs-toggle="tab" href="#threeAAAL" role="tab" aria-controls="threeAAAL" aria-selected="true">
                                    <i class="ri-calendar-check-line me-2"></i>
                                    Rendez-Vous
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link  text-white" id="tab-threeAAA" data-bs-toggle="tab" href="#threeAAA" role="tab" aria-controls="threeAAA" aria-selected="true">
                                    <i class="ri-sticky-note-add-line me-2"></i>
                                    Nouvelle societe
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link  text-white" id="tab-frewAAA" data-bs-toggle="tab" href="#frewAAA" role="tab" aria-controls="frewAAA" aria-selected="true">
                                    <i class="ri-folder-add-line me-2"></i>
                                    Nouvelle Assurance
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane fade active show" id="oneAAA" role="tabpanel" aria-labelledby="tab-oneAAA">
                                <div class="card-header">
                                    <h5 class="card-title text-center">Recherche du Patient</h5>
                                </div>
                                <div class="row gx-3">
                                    <div class="row gx-3 justify-content-center align-items-center" >
                                        <div class="col-12">
                                            <div class=" mb-0">
                                                <div class="card-body">
                                                    <div class="text-center">
                                                        <a class="d-flex align-items-center flex-column">
                                                            <img src="{{asset('assets/images/user8.png')}}" class="img-7x rounded-circle border border-3">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-4 col-lg-4 col-sm-6">
                                            <div class="mb-3 text-center">
                                                <label class="form-label">
                                                    Nom du patient
                                                </label>
                                                <select class="form-select select2" id="id_patient"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12" id="div_info_patient">
                                    </div>
                                    <div class="col-sm-12" id="div_info_consul" style="display: none;">
                                        <div class="card-header">
                                            <h5 class="card-title text-center">
                                                ACTE A EFFECTUER
                                            </h5>
                                        </div>
                                        <div class="row gx-3">
                                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Période</label>
                                                    <select class="form-select select2" id="periode">
                                                        <option value=""></option>
                                                        <option value="0">Jour</option>
                                                        <option value="1">Nuit</option>
                                                        <option value="2">Férier</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xxl-3 col-lg-4 col-sm-6" id="div_typeacteS" style="display: block;">
                                                <div class="mb-3">
                                                    <label class="form-label">Acte</label>
                                                    <select class="form-select select2" id="typeacte_idS">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xxl-3 col-lg-4 col-sm-6" id="div_medecin" style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label">Medecin</label>
                                                    <select class="form-select select2" id="medecin_id">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xxl-3 col-lg-4 col-sm-6" id="div_numcode" style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label">Numéro de bon</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            N°
                                                        </span>
                                                        <input type="tel" class="form-control" id="mumcode">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xxl-3 col-lg-4 col-sm-6" id="div_assurance_utiliser" style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label">Utilisé l'assurance</label>
                                                    <select class="form-select" id="assurance_utiliser">
                                                        <option selected value="oui">Oui</option>
                                                        <option value="non">Non</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="card-header text-center">
                                                <h5 class="card-title">Information Caisse</h5>
                                            </div>
                                            <div class="row gx-3">
                                                <div class="col-xxl-3 col-lg-4 col-sm-6" id="input_part_assurance" style="display: none;">
                                                    <div class="mb-3">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Part Assurance</span>
                                                            <input readonly type="tel" class="form-control" id="montant_assurance">
                                                            <input type="hidden" class="form-control" id="montant_assurance_hidden">
                                                            <span class="input-group-text">Fcfa</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                    <div class="mb-3">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Part Patient</span>
                                                            <input readonly type="tel" class="form-control" id="montant_patient">
                                                            <input type="hidden" class="form-control" id="montant_patient_hidden">
                                                            <span class="input-group-text">Fcfa</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                    <div class="mb-3">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Montant Total</span>
                                                            <input readonly type="tel" class="form-control" id="montant_total">
                                                            <span class="input-group-text">Fcfa</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xxl-3 col-lg-4 col-sm-6" id="div_remise" style="display: none;">
                                                    <div class="mb-3">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Remise</span>
                                                            <input type="tel" class="form-control" id="taux_remise" value="0">
                                                            <span class="input-group-text">Fcfa</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xxl-3 col-lg-4 col-sm-6" id="div_remise_appliq" style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label">Application de la remise</label>
                                                    <select class="form-select" id="appliq_remise">
                                                        <option selected value="patient">Patient</option>
                                                        <option value="assurance">Assurance</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 mb-3">
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <a href="javascript:location.reload();" class="btn btn-outline-danger">
                                                        Rémise à zéro
                                                    </a>
                                                    <button id="btn_eng_consultation" class="btn btn-success">
                                                        Enregistrer
                                                    </button>
                                                </div>
                                            </div>
                                            <div id="div_alert_consultation" class="mb-3"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="twoAAA" role="tabpanel" aria-labelledby="tab-twoAAA">
                                <div class="card-header">
                                    <h5 class="card-title text-center">Formulaire Nouveau Patient</h5>
                                </div>
                                <div class="card-header">
                                    <div class="text-center">
                                        <a class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/user8.png')}}" class="img-7x rounded-circle border border-3">
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body border border-1 rounded-2 mb-3">
                                    <div class="row gx-3">
                                        <div class="card-header">
                                            <h5 class="card-title text-center">Informations personnelles</h5>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Sexe</label>
                                                <select class="form-select select2" id="patient_sexe_new">
                                                    <option value=""></option>
                                                    <option value="M">Masculin</option>
                                                    <option value="F">Féminin</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Nom</label>
                                                <input type="text" class="form-control" id="patient_nom_new" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Prénoms</label>
                                                <input type="text" class="form-control" id="patient_prenom_new" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Date de naissance
                                                </label>
                                                <input type="date" class="form-control" placeholder="Selectionner une date" id="patient_datenaiss_new" max="{{ date('Y-m-d') }}">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Contact 1</label>
                                                <input type="tel" class="form-control" id="patient_tel_new" placeholder="Saisie Obligatoire" maxlength="10">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Contact 2</label>
                                                <input type="tel" class="form-control" id="patient_tel2_new" placeholder="facultatif" maxlength="10">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Résidence</label>
                                                <input type="text" class="form-control" id="patient_residence_new" placeholder="Saisie obligatoire">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body border border-1 rounded-2 mb-3">
                                    <div class="row gx-3 align-items-center justify-content-center">
                                        <div class="card-header">
                                            <h5 class="card-title text-center">Informations Assurance</h5>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Assurer</label>
                                                <select class="form-select" id="assure">
                                                    <option value="">Selectionner</option>
                                                    <option value="0">Non</option>
                                                    <option value="1">Oui</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row gx-3" id="div_assurer" style="display: none;">
                                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Filiation</label>
                                                    <select class="form-select select2" id="patient_codefiliation_new">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Assurance</label>
                                                    <select class="form-select select2" id="patient_codeassurance_new">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Matricule assurance</label>
                                                    <input type="text" class="form-control" id="patient_matriculeA_new" placeholder="Saisie Obligatoire">
                                                </div>
                                            </div>
                                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Taux</label>
                                                    <select class="form-select select2" id="patient_idtauxcouv_new">
                                                        <option value="">Sélectionner un taux</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Société</label>
                                                    <select class="form-select select2" id="patient_codesocieteassure_new">
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body border border-1 rounded-2 mb-3">
                                    <div class="row gx-3">
                                        <div class="card-header">
                                            <h5 class="card-title text-center">En Cas d'urgence</h5>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Nom</label>
                                                <input type="text" class="form-control" id="patient_nomu_new" placeholder="facultatif" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Contact 1</label>
                                                <input type="tel" class="form-control" id="patient_telu_new" placeholder="facultatif" maxlength="10">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Contact 2</label>
                                                <input type="tel" class="form-control" id="patient_telu2_new" placeholder="facultatif" maxlength="10">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body mb-3">
                                    <div class="row gx-3">
                                        <div class="col-sm-12 mb-3">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button id="btn_eng_patient" class="btn btn-success">
                                                    Enregistrer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="threeAAAL" role="tabpanel" aria-labelledby="tab-threeAAAL">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="card-title">Listes de Rendez-Vous du jour</h5>
                                    <div class="d-flex">
                                        <a id="btn_refresh_table_rdv" class="btn btn-outline-info ms-auto">
                                            <i class="ri-loop-left-line"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="">
                                        <div class="table-responsive">
                                            <table id="Table_day" class="table table-hover table-sm Table_day_rdv">
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
                            <div class="tab-pane fade" id="threeAAA" role="tabpanel" aria-labelledby="tab-threeAAA">
                                <div class="card-header">
                                    <h5 class="card-title text-center">Formulaire Nouvelle Societe</h5>
                                </div>
                                <div class="card-header">
                                    <div class="text-center">
                                        <a class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/batiment.avif')}}" class="img-7x rounded-circle border border-3">
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body" >
                                    <div class="row gx-3 alig-items-center justify-content-center">
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Nom de la société</label>
                                                <input type="text" class="form-control" id="nom_societe" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Assurance</label>
                                                <select class="form-select select2" id="codeassurance_societe">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Assureur</label>
                                                <select class="form-select select2" id="assureur_id_societe">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 mb-3 ">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button id="btn_eng_societe" class="btn btn-outline-success">
                                                    Enregistrer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="frewAAA" role="tabpanel" aria-labelledby="tab-frewAAA">
                                <div class="card-header">
                                    <h5 class="card-title text-center">
                                        Formulaire Nouvelle Assurance
                                    </h5>
                                </div>
                                <div class="card-header">
                                    <div class="text-center">
                                        <a class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/assurance3.jpg')}}" class="img-7x rounded-circle border border-3">
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body" >
                                    <div class="row gx-3">
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Nom</label>
                                                <input type="text" class="form-control" id="nom_assurance_new" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input required type="email" class="form-control" id="email_assurance_new" placeholder="Saisie Obligatoire">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Contact</label>
                                                <input type="tel" class="form-control" id="tel_assurance_new" placeholder="Saisie Obligatoire" maxlength="10">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" >Fax</label>
                                                <input type="text" class="form-control" id="fax_assurance_new" placeholder="Facultatif">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Adresse</label>
                                                <input type="text" class="form-control" id="adresse_assurance_new" placeholder="Saisie Obligatoire">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Localisation</label>
                                                <input type="text" class="form-control" id="carte_assurance_new" placeholder="Saisie Obligatoire">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <input type="text" class="form-control" id="desc_assurance_new" placeholder="Facultatif">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button id="btn_eng_assurance" class="btn btn-success">
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
            </div>
        </div>
    </div>

    <div class="row gx-3" >
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-center">
                        Patient recu Aujourd'hui
                    </h5>
                    <div class="d-flex" >
                        <a id="btn_refresh_table" class="btn btn-outline-info ms-auto">
                            <i class="ri-loop-left-line"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="">
                        <div class="table-responsive">
                            <table id="Table_day" class="table align-middle table-hover m-0 truncate Table_day_cons">
                                <thead>
                                    <tr>
                                        <th scope="col">N°</th>
                                        <th scope="col">N° Consultation</th>
                                        <th scope="col">N° dossier</th>
                                        <th scope="col">Nom et Prénoms</th>
                                        <th scope="col">Contact</th>
                                        <th scope="col">Médecin</th>
                                        <th scope="col">Spécialité</th>
                                        <th scope="col">Prix</th>
                                        <th scope="col">N° Facture</th>
                                        <th scope="col">Date</th>
                                        <th scope="col"></th>
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

<div class="modal fade" id="Detail_motif" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-body" id="modal_Detail_motif"></div>
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
                        <input type="date" class="form-control" id="date_rdvM" placeholder="Saisie Obligatoire" min="{{ date('Y-m-d') }}">
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

<div class="modal fade" id="MdeleteCons" tabindex="-1" aria-labelledby="delRowLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delRowLabel">
                    Confirmation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Voulez-vous vraiment supprimé cette consultation ?
                <input type="hidden" id="IddeleteCons">
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-end gap-2">
                    <a class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Non</a>
                    <button id="deleteBtnCons" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Oui</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('assets/vendor/apex/apexcharts.min.js')}}"></script>
<script src="{{asset('assets/js/app/js/jspdfinvoicetemplate/dist/index.js')}}" ></script>
<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>

@include('select2')

<script>
    $(document).ready(function() {

        Statistique();
        Activity_cons();
        Activity_cons_count();
        select_patient();
        select_list_medecin();
        select_assureur();
        select_assurance();
        select_taux();
        select_societe();
        select_filiation();

        // ------------------------------------------------------------------

        $("#btn_eng_consultation").on("click", eng_consultation);
        $("#acte_id").on("change", select_list_typeacte);
        $("#btn_eng_societe").on("click", eng_societe);
        $("#btn_eng_assurance").on("click", eng_assurance);
        $("#btn_eng_patient").on("click", eng_patient);
        // $("#btn_update_rdv").on("click", update_rdv);
        // $("#btn_delete_rdv").on("click", delete_rdv);
        $("#deleteBtnCons").on("click", delete_cons);

        $('#btn_affiche_stat').on('click', function() {
            $('#div_btn_affiche_stat').hide();
            $('#div_btn_cache_stat').show();

            Statistique_cons();
        });

        $('#btn_cache_stat').on('click', function() {
            $('#div_btn_affiche_stat').show();
            $('#div_btn_cache_stat').hide();

            $('#stat_consultation').empty();
        });

        $('#id_patient').on('change',function(){
            rech_dosier(); 
        });

        // ------------------------------------------------------------------

        var inputs = ['patient_tel_new', 'patient_tel2_new', 'patient_telu_new', 'patient_telu2_new','tel_assurance_new','mumcode'];
        inputs.forEach(function(id) {
            var inputElement = document.getElementById(id); // Get each element by its ID

            // Allow only numeric input (and optionally some special keys like backspace or delete)
            inputElement.addEventListener('keypress', function(event) {
                const key = event.key;
                // Allow numeric keys, backspace, and delete
                if (!/[0-9]/.test(key) && key !== 'Backspace' && key !== 'Delete') {
                    event.preventDefault();
                }
            });
            // Alternatively, for more comprehensive input validation, use input event listener
            inputElement.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, ''); // Allow only numbers
            });
        });

        $('#assure').on('change', function() {
            if ($(this).val() === '1') {
                $('#div_assurer').css('display', 'flex');
            } else {
                $('#div_assurer').css('display', 'none');
            }
        });

        document.getElementById('taux_remise').addEventListener('input', function() {
            this.value = formatPrice(this.value);
            if (this.value !== ''){
                document.getElementById('div_remise_appliq').style.display = 'block';
            }else{
                document.getElementById('div_remise_appliq').style.display = 'none';
            }
        });

        document.getElementById('typeacte_idS').addEventListener('change', function() {
            if (this.value !== ''){
                document.getElementById('div_remise').style.display = 'block';
            }else{
                document.getElementById('div_remise').style.display = 'none';
            }
        });

        document.getElementById('assurance_utiliser').addEventListener('change', function() {
            if (this.value == 'non'){
                document.getElementById('div_numcode').style.display = 'none';
                document.getElementById('mumcode').value = '';
            }else{
                document.getElementById('div_numcode').style.display = 'block';
                document.getElementById('mumcode').value = '';
            }
        });

        document.getElementById('taux_remise').addEventListener('input', function() {
            // Nettoyer la valeur entrée en supprimant les caractères non numériques sauf le point
            const rawValue = this.value.replace(/[^0-9]/g, ''); // Supprimer tous les caractères non numériques
            // Ajouter des points pour les milliers
            const formattedValue = formatPrice(rawValue);
            
            // Mettre à jour la valeur du champ avec la valeur formatée
            this.value = formattedValue;

            const appliq_remise = document.getElementById('appliq_remise').value;
            const assuranceUtiliser = document.getElementById('assurance_utiliser').value; // Récupérer la valeur 'oui' ou 'non'

            if (appliq_remise == 'patient' || assuranceUtiliser == 'non') {
                // Convertir la valeur formatée en nombre pour les calculs
                const montant_patient = parseInt(document.getElementById('montant_patient_hidden').value.replace(/\./g, '')) || 0;
                const remise = parseInt(rawValue) || 0;

                // Calculer le montant remis
                const montantRemis = montant_patient - remise;
                document.getElementById('montant_patient').value = formatPriceT(montantRemis);
            } else if (assuranceUtiliser == 'oui') {

                // Si l'assurance est utilisée (valeur 'oui'), calculer le montant remis pour l'assurance
                const montant_assurance = parseInt(document.getElementById('montant_assurance_hidden').value.replace(/\./g, '')) || 0;
                const remise = parseInt(rawValue) || 0;

                // Calculer le montant remis
                const montantRemis = montant_assurance - remise;
                document.getElementById('montant_assurance').value = formatPriceT(montantRemis);
            }
        });

        document.getElementById('appliq_remise').addEventListener('change', function() {

            document.getElementById('montant_assurance').value = formatPrice(document.getElementById('montant_assurance_hidden').value);
            document.getElementById('montant_patient').value = formatPrice(document.getElementById('montant_patient_hidden').value);

            // Nettoyer la valeur entrée en supprimant les caractères non numériques sauf le point
            const rawValue = document.getElementById('taux_remise').value.replace(/[^0-9]/g, ''); 

            const assuranceUtiliser = document.getElementById('assurance_utiliser').value; // Récupérer la valeur 'oui' ou 'non'

            if (this.value == 'patient' || assuranceUtiliser == 'non') {
                // Convertir la valeur formatée en nombre pour les calculs

                const montant_patient = parseFloat(document.getElementById('montant_patient_hidden').value.replace(/\./g, '')) || 0;
                const remise = parseFloat(rawValue) || 0;

                // Calculer le montant remis
                const montantRemis = montant_patient - remise;
                document.getElementById('montant_patient').value = formatPriceT(montantRemis);
            } else if (assuranceUtiliser == 'oui') {

                // Si l'assurance est utilisée (valeur 'oui'), calculer le montant remis pour l'assurance
                const montant_assurance = parseFloat(document.getElementById('montant_assurance_hidden').value.replace(/\./g, '')) || 0;
                const remise = parseFloat(rawValue) || 0;

                // Calculer le montant remis
                const montantRemis = montant_assurance - remise;
                document.getElementById('montant_assurance').value = formatPriceT(montantRemis);
            }
        });

        function formatPrice(input) {
            // Remove all non-numeric characters except the comma
            input = input.replace(/[^\d,]/g, '');

            // Convert comma to dot for proper float conversion
            input = input.replace(',', '.');

            // Convert to float and round to the nearest whole number
            let number = Math.round(parseFloat(input));
            if (isNaN(number)) {
                return '';
            }

            // Format the number with dot as thousands separator
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function formatPriceT(price) {

            // Convert to float and round to the nearest whole number
            let number = Math.round(parseInt(price));
            if (isNaN(number)) {
                return '';
            }

            // Format the number with dot as thousands separator
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function calculateAge(dateString) 
        {
            const birthDate = new Date(dateString);
            const today = new Date();

            let age = today.getFullYear() - birthDate.getFullYear();

            // Vérifie si l'anniversaire n'est pas encore passé cette année
            const monthDiff = today.getMonth() - birthDate.getMonth();
            const dayDiff = today.getDate() - birthDate.getDate();
            if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
                age--;
            }

            return age;
        }

        // ------------------------------------------------------------------

        function select_patient()
        {
            const selectElement = $('#id_patient');
            selectElement.empty();

            // Ajouter l'option par défaut
            const defaultOption = $('<option>', {
                value: '',
                text: 'Selectionner'
            });
            selectElement.append(defaultOption);

            $.ajax({
                url: '/api/name_patient_reception',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    data.name.forEach(item => {
                        const option = $('<option>', {
                            value: item.idenregistremetpatient,
                            text: item.nomprenomspatient
                        });
                        selectElement.append(option);
                    });
                },
                error: function() {
                    console.error('Erreur lors du chargement des patients');
                }
            });
        }

        function rech_dosier()
        {
            document.getElementById('div_typeacteS').style.display = 'block';
            document.getElementById('div_medecin').style.display = 'block';

            document.getElementById('montant_assurance').value = '0';
            document.getElementById('taux_remise').value = '0';
            document.getElementById('montant_total').value = '0';
            document.getElementById('montant_patient').value = '0';

            const id_patient = document.getElementById("id_patient");

            if(!id_patient.value.trim()){
                showAlert('Alert', 'Veuillez saisie le nom d\'un du patient.', 'warning');
                return false;
            }

            // Créer l'élément de préchargement
            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;

            // Ajouter le préchargeur au body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: '/api/rech_patient',
                method: 'GET',  // Use 'POST' for data creation
                data: { id: id_patient.value },
                success: function(response) {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    if(response.existep) {
                        showAlert('Alert', 'Ce patient n\'existe pas.', 'error');
                        Reset();
                    } else if (response.success) {
                        // showAlert('Succés', 'Patient trouvé.', 'success');

                        $('#medecin_id').val('').trigger('change');

                        addGroup(response.patient);

                        document.getElementById("div_info_consul").style.display = 'block';

                        if (response.patient.assure == 1) {
                            // Le patient a un taux d'assurance
                            document.getElementById("input_part_assurance").style.display = 'block';
                            document.getElementById("div_assurance_utiliser").style.display = 'block';
                            document.getElementById("div_numcode").style.display = 'block';

                            // Afficher le select et inclure l'option 'Assurance'
                            // Assurez-vous que l'option 'Assurance' est visible
                            const assuranceOption = document.querySelector("#appliq_remise option[value='assurance']");
                            if (assuranceOption) {
                                assuranceOption.style.display = 'block';
                            } else {
                                // Si l'option 'Assurance' n'existe pas, la créer et l'ajouter
                                const newAssuranceOption = document.createElement('option');
                                newAssuranceOption.value = 'assurance';
                                newAssuranceOption.text = 'Assurance';
                                document.getElementById("appliq_remise").appendChild(newAssuranceOption);
                            }

                        } else {
                            // Le patient n'a pas d'assurance
                            document.getElementById("input_part_assurance").style.display = 'none';
                            document.getElementById("div_assurance_utiliser").style.display = 'none';
                            document.getElementById("div_numcode").style.display = 'none';

                            // Cacher l'option 'Assurance' dans le select
                            const assuranceOption = document.querySelector("#appliq_remise option[value='assurance']");
                            if (assuranceOption) {
                                assuranceOption.style.display = 'none';
                            }
                        }

                        select_list_typeacte();
                    }
                },
                error: function() {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    showAlert('Alert', 'Une erreur est survenue lors de la recherche.', 'error');
                }
            });
        }

        function addGroup(data) {

            var dynamicFields = document.getElementById("div_info_patient");
            // Remove existing content
            while (dynamicFields.firstChild) {
                dynamicFields.removeChild(dynamicFields.firstChild);
            }

            var groupe = document.createElement("div");
            groupe.className = "row gx-3";
            groupe.innerHTML = `
                <div class="col-12">
                    <div class="card-header">
                        <h5 class="card-title">Information du patient</h5>
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <div class="mb-3">
                        <label class="form-label" for="email">N° dossier</label>
                        <input id="patient_numdossier" value="${data.numdossier}" readonly class="form-control">
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <div class="mb-3">
                        <label class="form-label" for="email">Nom et Prénoms</label>
                        <input value="${data.nomprenomspatient}" readonly class="form-control">
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <div class="mb-3">
                        <label class="form-label" for="tel">Contact</label>
                        <input value="${data.telpatient}" readonly class="form-control">
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <div class="mb-3">
                        <label class="form-label">Assurer</label>
                        <input value="${data.assure == 1 ? `Oui` : `Non`}" readonly class="form-control">
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4 col-sm-6" hidden>
                    <div class="mb-3">
                        <input id="patient_codeassurance" value="${data.codeassurance}" readonly class="form-control">
                    </div>
                </div>
            `;

            // Check if the patient has insurance and add the additional fields
            if (data.assure === 1) {
                groupe.innerHTML += `
                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                        <div class="mb-3">
                            <label class="form-label" for="adresse">Assurance</label>
                            <input value="${data.assurance}" readonly class="form-control" placeholder="Néant">
                        </div>
                    </div>
                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                        <div class="mb-3">
                            <label class="form-label" for="adresse">Matricule assurance</label>
                            <input value="${data.matriculeassure}" readonly class="form-control" placeholder="Néant">
                        </div>
                    </div>
                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                        <div class="mb-3">
                            <label class="form-label">Taux</label>
                            <div class="input-group">      
                                <input id="patient_taux" value="${data.taux}" readonly class="form-control" placeholder="Néant">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                        <div class="mb-3">
                            <label class="form-label" for="adresse">Société</label>
                            <input value="${data.societe}" readonly class="form-control" placeholder="Néant">
                        </div>
                    </div>
                `;
            }else{
                groupe.innerHTML += `
                    <div class="col-xxl-3 col-lg-4 col-sm-6" hidden>
                        <div class="mb-3">
                            <label class="form-label" for="adresse">Assurance</label>
                            <input value="Aucun" readonly class="form-control" placeholder="Néant">
                        </div>
                    </div>
                    <div class="col-xxl-3 col-lg-4 col-sm-6" hidden>
                        <div class="mb-3">
                            <label class="form-label" for="adresse">Taux</label>
                            <div class="input-group">      
                                <input id="patient_taux" value="0" readonly class="form-control" placeholder="Néant">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-lg-4 col-sm-6" hidden>
                        <div class="mb-3">
                            <label class="form-label" for="adresse">Société</label>
                            <input value="Aucun" readonly class="form-control" placeholder="Néant">
                        </div>
                    </div>
                `;
            }

            dynamicFields.appendChild(groupe);
        }

        function showAlert(title, message, type) {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
            });
        }

        function Reset() {
            const dynamicFields = $('#div_info_patient');
            
            // Supprimer le contenu existant
            dynamicFields.empty();
            
            $('#div_info_consul').hide();
            $('#periode').val('').trigger('change');
            $('#typeacte_id').val('').trigger('change');
            $('#id_patient').val('').trigger('change');
            $('#medecin_id').val('').trigger('change');
            select_patient();
        }

        // ------------------------------------------------------------------

        function select_assurance() 
        {
            const selectElement2 = $('#codeassurance_societe');
            selectElement2.empty();
            selectElement2.append($('<option>', {
                value: '',
                text: 'Selectionner',
            }));

            const selectElement3 = $('#patient_codeassurance_new');
            selectElement3.empty();
            selectElement3.append($('<option>', {
                value: '',
                text: 'Selectionner',
            }));

            $.ajax({
                url: '/api/assurance_select_patient_new',
                method: 'GET',
                success: function(response) {
                    const data = response.assurance;

                    data.forEach(function(item) {
                        selectElement2.append($('<option>', {
                            value: item.codeassurance,
                            text: item.libelleassurance,
                        }));

                        selectElement3.append($('<option>', {
                            value: item.codeassurance,
                            text: item.libelleassurance,
                        }));
                    });
                },
                error: function() {
                    // showAlert('danger', 'Impossible de generer le code automatiquement');
                }
            });
        }

        function select_assureur() 
        {
            const selectElement2 = $('#assureur_id_societe');
            selectElement2.empty();
            selectElement2.append($('<option>', {
                value: '',
                text: 'Selectionner',
            }));

            $.ajax({
                url: '/api/select_assureur',
                method: 'GET',
                success: function(response) {
                    const data = response.assureur;

                    data.forEach(function(item) {
                        selectElement2.append($('<option>', {
                            value: item.codeassureur,
                            text: item.libelle_assureur,
                        }));
                    });
                },
                error: function() {
                    // showAlert('danger', 'Impossible de generer le code automatiquement');
                }
            });
        }

        function select_taux() 
        {
            const selectElement2 = $('#patient_idtauxcouv_new');
            selectElement2.empty();
            selectElement2.append($('<option>', {
                value: '',
                text: 'Selectionner',
            }));

            $.ajax({
                url: '/api/taux_select_patient_new',
                method: 'GET',
                success: function(response) {
                    const data = response.taux;

                    data.forEach(function(item) {
                        selectElement2.append($('<option>', {
                            value: item.idtauxcouv,
                            text: item.valeurtaux + '%',
                        }));
                    });
                },
                error: function() {
                    // showAlert('danger', 'Impossible de generer le code automatiquement');
                }
            });
        }

        function select_societe() 
        {
            const selectElement2 = $('#patient_codesocieteassure_new');
            selectElement2.empty();
            selectElement2.append($('<option>', {
                value: '',
                text: 'Selectionner',
            }));

            $.ajax({
                url: '/api/societe_select_patient_new',
                method: 'GET',
                success: function(response) {
                    const data = response.societe;

                    data.forEach(function(item) {
                        selectElement2.append($('<option>', {
                            value: item.codesocieteassure,
                            text: item.nomsocieteassure,
                        }));
                    });
                },
                error: function() {
                    // showAlert('danger', 'Impossible de generer le code automatiquement');
                }
            });
        }

        function select_filiation() 
        {
            const selectElement2 = $('#patient_codefiliation_new');
            selectElement2.empty();
            selectElement2.append($('<option>', {
                value: '',
                text: 'Selectionner',
            }));

            $.ajax({
                url: '/api/filiation_select_patient_new',
                method: 'GET',
                success: function(response) {
                    const data = response.filiation;

                    data.forEach(function(item) {
                        selectElement2.append($('<option>', {
                            value: item.codefiliation,
                            text: item.libellefiliation,
                        }));
                    });
                },
                error: function() {
                    // showAlert('danger', 'Impossible de generer le code automatiquement');
                }
            });
        }

        function select_list_medecin()
        {
            const selectElement = document.getElementById('medecin_id');
            // Clear existing options
            selectElement.innerHTML = '';
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Selectionner';
            selectElement.appendChild(defaultOption);

            fetch('/api/select_list_medecin')
                .then(response => response.json())
                .then(data => {
                    const medecins = data.medecin;
                    medecins.forEach((item, index) => {
                        const option = document.createElement('option');
                        option.value = `${item.codemedecin}`; // Ensure 'id' is the correct key
                        option.textContent = `${item.nomprenomsmed}`; // Ensure 'nom' is the correct key
                        selectElement.appendChild(option);
                    });
                })
                .catch(error => console.error('Erreur lors du chargement des medecins:', error));
        }

        function select_list_typeacte() {
            const divTypeActe = $('#div_typeacteS'); // Le div principal
            const divMedecin = $('#div_medecin');
            const typeActeSelect = $('#typeacte_idS');

            const montant_assurance = $('#montant_assurance');
            const taux_remise = $('#taux_remise');
            const montant_total = $('#montant_total');
            const montant_patient = $('#montant_patient');

            const montant_patient_hidden = $('#montant_patient_hidden');
            const montant_assurance_hidden = $('#montant_assurance_hidden');

            montant_assurance.val('');
            montant_total.val('');
            montant_patient.val('');

            const codeassurance = $('#patient_codeassurance').val();
            const patient_taux = $('#patient_taux');

            typeActeSelect.empty();
            divTypeActe.hide();  // Masquer le div au départ
            divMedecin.hide();

            // Ajouter une option par défaut
            typeActeSelect.append($('<option>', { value: '', text: 'Sélectionner' }));

            // Valider si acteId est valide avant de faire la requête AJAX

            $.ajax({
                url: '/api/select_typeacte/' + codeassurance,
                method: 'GET',
                success: function(response) {
                    const data = response.typeacte;

                    if (data && data.length > 0) {
                        // Remplir le select avec les données de la réponse
                        data.forEach(function(item) {
                            typeActeSelect.append($('<option>', {
                                value: item.codgaran,
                                text: item.libgaran,
                                'data-prixj': item.prixj,
                                'data-prixn': item.prixn,
                                'data-prixf': item.prixf,
                            }));
                        });

                        divTypeActe.show();
                        divMedecin.show();                            

                    } else {
                        // Ajouter une option "Aucun données disponible" si aucune donnée
                        typeActeSelect.append($('<option>', {
                            value: '',
                            text: 'Aucun données disponible'
                        }));
                        divTypeActe.hide();
                    }
                },
                error: function() {
                    console.error("Erreur lors du chargement des types d'actes");
                }
            });

            const periode = $('#periode');

            typeActeSelect.on('change', function() {

                if (periode.val() == '') {
                    showAlert('Alert', 'Veuillez selectionner la période.', 'info');
                    return;
                }

                const selectedOption = $(this).find('option:selected');

                let prix;

                if (periode.val() == 0) {
                    prix = selectedOption.data('prixj');
                } else if (periode.val() == 1) {
                    prix = selectedOption.data('prixn');
                } else if (periode.val() == 2) {
                    prix = selectedOption.data('prixf');
                }

                if (prix) {
                    calculateAndFormatAmounts(prix, patient_taux.val());
                } else {
                    montant_total.val('');
                    montant_assurance.val('');
                    montant_patient.val(''); // Vider le champ si aucun prix valide
                }
            });

            const appliq_remise = $('#appliq_remise');
            const auS = $('#assurance_utiliser');

            auS.on('change', function() {

                if (periode.val() == '') {
                    showAlert('Alert', 'Veuillez selectionner la période.', 'info');
                    return;
                }

                const selectedOption = typeActeSelect.find('option:selected');

                let prix;

                if (periode.val() == 0) {
                    prix = selectedOption.data('prixj');
                } else if (periode.val() == 1) {
                    prix = selectedOption.data('prixn');
                } else if (periode.val() == 2) {
                    prix = selectedOption.data('prixf');
                }

                taux_remise.val(0);

                if (prix) {
                    if (this.value === 'oui') {
                        appliq_remise.find('option[value="assurance"]').show();
                        calculateAndFormatAmounts(prix, patient_taux.val());
                    } else {
                        appliq_remise.val('patient');
                        appliq_remise.find('option[value="assurance"]').hide();
                        calculateAndFormatAmounts(prix, 0); // Calculer sans taux d'assurance
                    }
                } else {
                    montant_total.val('');
                    montant_assurance.val('');
                    montant_patient.val('');
                }
            });

            periode.on('change', function() {

                const selectedOption = typeActeSelect.find('option:selected');

                let prix;

                if (this.value == 0) {
                    prix = selectedOption.data('prixj');
                } else if (this.value == 1) {
                    prix = selectedOption.data('prixn');
                } else if (this.value == 2) {
                    prix = selectedOption.data('prixf');
                }

                taux_remise.val(0);

                if (prix) {
                    if (auS.val() === 'oui') {
                        appliq_remise.find('option[value="assurance"]').show();
                        calculateAndFormatAmounts(prix, patient_taux.val());
                    } else {
                        appliq_remise.val('patient');
                        appliq_remise.find('option[value="assurance"]').hide();
                        calculateAndFormatAmounts(prix, 0); // Calculer sans taux d'assurance
                    }
                } else {
                    montant_total.val('');
                    montant_assurance.val('');
                    montant_patient.val('');
                }
            });

        }

        // function calculateAndFormatAmounts(prix, patient_taux) {
        //     if (prix) {

        //         if (typeof prix !== 'string') {
        //             prix = prix.toString(); // Convertir en chaîne
        //         }

        //         let prixFloat = parseFloat(prix.replace(/[.,]/g, ''));

        //         if (isNaN(prixFloat)) {
        //             console.error('Invalid price value');
        //             $('#montant_total').val(''); // Vider le champ si le prix est invalide
        //             return;
        //         }

        //         $('#montant_total').val(formatPrice(prix));

        //         const au = $('#assurance_utiliser');
        //         let tauxFloat = parseFloat(patient_taux);

        //         if (au.val() === 'non') {
        //             tauxFloat = 0;
        //         } else if (isNaN(tauxFloat)) {
        //             tauxFloat = 0;
        //         }

        //         if (tauxFloat === 0) {
        //             $('#montant_assurance').val('0');
        //             $('#montant_patient').val(formatPrice(prixFloat.toString()));
        //             $('#montant_patient_hidden').val(formatPrice(prixFloat.toString()));
        //             $('#montant_assurance_hidden').val('0');
        //         } else {
        //             let montantAssurance = Math.round((tauxFloat / 100) * prixFloat);
        //             let montantPatient = Math.round(prixFloat - montantAssurance);


        //             $('#montant_assurance').val(formatPrice(montantAssurance.toString()));
        //             $('#montant_patient').val(formatPrice(montantPatient.toString()));

        //             $('#montant_patient_hidden').val(formatPrice(montantPatient.toString()));
        //             $('#montant_assurance_hidden').val(formatPrice(montantAssurance.toString()));
        //         }
        //     } else {
        //         $('#montant_total').val('');
        //     }
        // }

        function calculateAndFormatAmounts(prix, patient_taux) {
            // Vérifiez si le prix est défini et non null
            if (prix) {
                console.log('Prix:', prix);
                // Assurez-vous que prix est une chaîne
                if (typeof prix !== 'string') {
                    prix = prix.toString();
                }

                // Supprimez les séparateurs (ex : 1.000,00 => 100000)
                let prixFloat = parseFloat(prix.replace(/[.,]/g, ''));

                // Vérifiez si la conversion est valide
                if (isNaN(prixFloat)) {
                    console.error('Invalid price value:', prix);
                    $('#montant_total').val(''); // Vider le champ si le prix est invalide
                    return;
                }

                // Formater et afficher le prix total
                $('#montant_total').val(formatPrice(prixFloat.toString()));

                // Vérifiez si l'assurance est utilisée
                const au = $('#assurance_utiliser');
                let tauxFloat = parseFloat(patient_taux);

                if (au.val() === 'non') {
                    tauxFloat = 0; // Pas d'assurance utilisée
                } else if (isNaN(tauxFloat) || tauxFloat < 0 || tauxFloat > 100) {
                    console.warn('Invalid patient_taux value:', patient_taux);
                    tauxFloat = 0; // Défaut : pas de taux
                }

                // Calcul des montants
                let montantAssurance = 0;
                let montantPatient = 0;

                if (tauxFloat === 0) {
                    montantPatient = prixFloat;
                } else {
                    montantAssurance = Math.round((tauxFloat / 100) * prixFloat);
                    montantPatient = Math.round(prixFloat - montantAssurance);
                }

                // Debugging : Affichez les valeurs calculées dans la console
                // console.log('Prix Float:', prixFloat);
                // console.log('Taux:', tauxFloat);
                // console.log('Montant Assurance:', montantAssurance);
                // console.log('Montant Patient:', montantPatient);

                // Mettez à jour les champs correspondants
                $('#montant_assurance').val(formatPrice(montantAssurance.toString()));
                $('#montant_patient').val(formatPrice(montantPatient.toString()));
                $('#montant_patient_hidden').val(formatPrice(montantPatient.toString()));
                $('#montant_assurance_hidden').val(formatPrice(montantAssurance.toString()));
            } else {
                // Si aucun prix n'est défini, vider les champs
                $('#montant_total').val('');
                $('#montant_assurance').val('');
                $('#montant_patient').val('');
                $('#montant_patient_hidden').val('');
                $('#montant_assurance_hidden').val('');
            }
        }


        // ------------------------------------------------------------------

        function eng_societe() 
        {
            const nom = $("#nom_societe");
            const codeassurance= $("#codeassurance_societe");
            const assureur_id = $("#assureur_id_societe");

            if (!codeassurance.val().trim() || !nom.val().trim() || !assureur_id.val().trim()) {
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.', 'warning');
                return false;
            }

            // Show preloader
            const preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $("body").append(preloader_ch);

            // AJAX request to create a new user
            $.ajax({
                url: '/api/societe_new',
                method: 'GET',
                data: {
                    codeassurance: codeassurance.val(),
                    nom: nom.val(),
                    assureur_id: assureur_id.val(),
                },
                success: function(response) {
                    $("#preloader_ch").remove();

                    if (response.existe) {
                        showAlert('Alert', 'Cette société existe déjà', 'warning');
                    } else if (response.success) {

                        nom.val('');
                        codeassurance.val('').trigger('change');
                        assureur_id.val('').trigger('change');

                        select_societe();

                        showAlert('Succès', 'Opération éffectuée.', 'success');
                    } else if (response.error) {
                        showAlert('Erreur', response.message, 'error');
                    }
                },
                error: function() {
                    $("#preloader_ch").remove();
                    showAlert('Erreur', 'Une erreur est survenue', 'error');
                }
            });
        }

        // ------------------------------------------------------------------

        function eng_assurance()
        {
            var nom = document.getElementById("nom_assurance_new");
            var email = document.getElementById("email_assurance_new");
            var phone = document.getElementById("tel_assurance_new");
            var adresse = document.getElementById("adresse_assurance_new");
            var fax = document.getElementById("fax_assurance_new");
            var carte = document.getElementById("carte_assurance_new");
            var desc = document.getElementById("desc_assurance_new");

            if (!nom.value.trim() || !email.value.trim() || !phone.value.trim() || !carte.value.trim() || !carte.value.trim() || !adresse.value.trim()) {
                showAlert('Alert', 'Tous les champs obligatoires n\'ont pas été rempli.','warning');
                return false; 
            }

            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value.trim())) { 
                showAlert('Alert', 'Email incorrect.','warning');
                return false;
            }


            if (phone.value.length !== 10) {
                showAlert('Alert', 'Contact incomplet.','warning');
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
                url: '/api/assurance_new',
                method: 'GET',
                data: { 
                    nom: nom.value, 
                    email: email.value, 
                    tel: phone.value, 
                    desc: desc.value || null, 
                    fax: fax.value || null, 
                    adresse: adresse.value,
                    carte: carte.value,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    
                    if (response.tel_existe) {
                        showAlert('Alert', 'Ce numéro de contact appartient déjà a une assurance.','warning');
                    }else if (response.email_existe) {
                        showAlert('Alert', 'Ce email appartient déjà a une assurance.','warning');
                    }else if (response.nom_existe) {
                        showAlert('Alert', 'Cette assurance existe déjà.','warning');
                    }else if (response.fax_existe) {
                        showAlert('Alert', 'Ce fax appartient déjà a une assurance.','warning');
                    } else if (response.success) {

                        nom.value = '';
                        email.value = '';
                        phone.value = '';
                        desc.value = '';
                        fax.value = '';
                        adresse.value = '';
                        carte.value = '';

                        select_assurance();

                        showAlert('Succès', response.message,'success');
                    } else if (response.error) {
                        showAlert('Alert', 'Une erreur est survenue lors de l\'enregistrement.','error');
                    }

                },
                error: function() {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert('Alert', 'Une erreur est survenue lors de l\'enregistrement.','error');
                }
            });
        }

        // ------------------------------------------------------------------

        function eng_patient() {
            const divAssurer = $("#div_assurer");

            let nom = $("#patient_nom_new");
            let prenom = $("#patient_prenom_new");
            let sexe = $("#patient_sexe_new");
            let datenais = $("#patient_datenaiss_new");
            let phone = $("#patient_tel_new");
            let phone2 = $("#patient_tel2_new");
            let residence = $("#patient_residence_new");
            let assurer = $("#assure");

            let filiation = $("#patient_codefiliation_new");
            let matricule_assurance = $("#patient_matriculeA_new");
            let assurance_id = $("#patient_codeassurance_new");
            let taux_id = $("#patient_idtauxcouv_new");
            let societe_id = $("#patient_codesocieteassure_new");

            let nomu = $("#patient_nomu_new");
            let telu = $("#patient_telu_new");
            let telu2 = $("#patient_telu2_new");

            // Validation des champs obligatoires
            if (!nom.val().trim() || !prenom.val().trim() || !phone.val().trim() || !datenais.val().trim() || !sexe.val().trim() || !residence.val().trim() || !assurer.val().trim()) {
                showAlert("Alert", "Veuillez remplir tous les champs obligatoires.", "warning");
                return false;
            }

            // Validation de l'email
            // const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            // if (email.val().trim() && !emailRegex.test(email.val().trim())) {
            //     showAlert("Alert", "Email incorrect.", "warning");
            //     return false;
            // }

            // Validation des numéros de téléphone
            if (phone.val().length !== 10 ) {
                showAlert("Alert", "Contact 1 incomplet.", "warning");
                return false;
            }

            if (phone2.val() && phone2.val().length !== 10) {
                showAlert("Alert", "Contact 2 incomplet.", "warning");
                return false;
            }

            if (telu.val() && telu.val().length !== 10) {
                showAlert("Alert", "Contact 1 en cas d'urgence incomplet.", "warning");
                return false;
            }

            if (telu2.val() && telu2.val().length !== 10) {
                showAlert("Alert", "Contact 2 en cas d'urgence incomplet.", "warning");
                return false;
            }

            // Validation des champs relatifs à l'assurance
            if (assurer.val() === "1") {
                if (!assurance_id.val() || !taux_id.val() || !societe_id.val() || !filiation.val() || !matricule_assurance.val()) {
                    showAlert("Alert", "Veuillez remplir tous les champs relatifs à l'assurance.", "warning");
                    return false;
                }
            }

            // Préchargement
            const preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $("body").append(preloader_ch);

            // Envoi AJAX
            $.ajax({
                url: "/api/patient_new",
                method: "GET", // POST pour créer les données
                data: {
                    nom: nom.val(),
                    prenom: prenom.val(),
                    tel: phone.val(),
                    tel2: phone2.val() || null,
                    residence: residence.val(),
                    assurer: assurer.val(),
                    assurance_id: assurance_id.val() || null,
                    taux_id: taux_id.val() || null,
                    societe_id: societe_id.val() || null,
                    datenais: datenais.val(),
                    sexe: sexe.val(),
                    filiation: filiation.val() || null,
                    matricule_assurance: matricule_assurance.val() || null,
                    nomu: nomu.val() || null,
                    telu: telu.val() || null,
                    telu2: telu2.val() || null,
                },
                success: function (response) {
                    // Supprimer le préchargement

                    if (response.success) {

                        var newTab = new bootstrap.Tab(document.getElementById('tab-oneAAA'));
                        newTab.show();

                        // Réinitialisation des champs
                        nom.val("");
                        prenom.val("");
                        phone.val("");
                        phone2.val("");
                        residence.val("");
                        datenais.val("");
                        sexe.val("").trigger('change');

                        nomu.val("");
                        telu.val("");
                        telu2.val("");

                        filiation.val("").trigger('change');
                        matricule_assurance.val("");
                        assurance_id.val("").trigger('change');
                        taux_id.val("").trigger('change');
                        societe_id.val("").trigger('change');
                        assurer.val("").trigger('change');

                        divAssurer.hide();

                        const selectElement = $('#id_patient');
                        selectElement.empty();

                        // Ajouter l'option par défaut
                        const defaultOption = $('<option>', {
                            value: '',
                            text: 'Selectionner'
                        });
                        selectElement.append(defaultOption);

                        $.ajax({
                            url: '/api/name_patient_reception',
                            method: 'GET',
                            dataType: 'json',
                            success: function(data) {

                                data.name.forEach(item => {
                                    const option = $('<option>', {
                                        value: item.idenregistremetpatient,
                                        text: item.nomprenomspatient
                                    });
                                    selectElement.append(option);
                                });

                                $("#preloader_ch").remove();

                                $('#id_patient').val(response.id).trigger('changer')

                            },
                            error: function() {
                                console.error('Erreur lors du chargement des patients');
                            }
                        });

                        showAlert("Succès", response.message, "success");
                    } else if (response.error) {
                        showAlert("Alert", response.message, "error");
                    }
                },
                error: function () {

                    $("#preloader_ch").remove();
                    showAlert("Alert", "Une erreur est survenue lors de l'enregistrement.", "error");
                }
            });
        }

        // ------------------------------------------------------------------

        const table_cons = $('.Table_day_cons').DataTable({

            processing: true,
            serverSide: false,
            ajax: {
                url: `/api/list_cons_day`,
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
                    data: 'idconsexterne',
                    searchable: true, 
                },
                {
                    data: 'numdossier',
                    render: (data, type, row) => {
                        return data ? `${data}` : 'Aucun';
                    },
                    searchable: true,
                },
                { 
                    data: 'nom_patient',
                    searchable: true, 
                },
                { 
                    data: 'tel_patient', 
                    render: (data) => `+225 ${data}`,
                    searchable: true, 
                },
                { 
                    data: 'nom_medecin',
                    searchable: true, 
                },
                { 
                    data: 'specialite',
                    searchable: true, 
                },
                { 
                    data: 'montant', 
                    render: (data) => `${formatPriceT(data)} Fcfa`,
                    searchable: true, 
                },
                { 
                    data: 'numfac', 
                    render: (data) => `${data}`,
                    searchable: true, 
                },
                { 
                    data: 'date', 
                    render: (data) => `${formatDate(data)}`,
                    searchable: true, 
                },
                {
                    data: null,
                    render: (data, type, row) => `
                        <div class="d-inline-flex gap-1" style="font-size:10px;">
                            <a class="btn btn-outline-warning btn-sm" id="Cfacture" 
                                data-idconsexterne="${row.idconsexterne}"
                            >
                                <i class="ri-printer-line"></i>
                            </a>
                            <a class="btn btn-outline-info btn-sm" id="Cfiche" 
                                data-idconsexterne="${row.idconsexterne}"
                            >
                                <i class="ri-file-line"></i>
                            </a>
                            ${row.regle == 0 ?  
                            `<a class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#MdeleteCons" id="deleteCons" data-numfac="${row.numfac}">
                                <i class="ri-delete-bin-line"></i>
                            </a>` : ``}
                        </div>
                    `,
                    searchable: false,
                    orderable: false,
                }
            ],
            ...dataTableConfig,
            initComplete: function(settings, json) {
                initializeRowEventListenersCons();
            },
        });

        function initializeRowEventListenersCons() {

            $('.Table_day_cons').on('click', '#Cfacture', function() {
                var preloader_ch = `
                    <div id="preloader_ch">
                        <div class="spinner_preloader_ch"></div>
                    </div>
                `;
                // Add the preloader to the body
                document.body.insertAdjacentHTML('beforeend', preloader_ch);

                const code = $(this).data('idconsexterne');

                fetch(`/api/fiche_consultation/${code}`) // API endpoint
                .then(response => response.json())
                .then(data => {
                    // Access the 'chambre' array from the API response
                    const facture = data.facture;

                    var preloader = document.getElementById('preloader_ch');
                        if (preloader) {
                            preloader.remove();
                        }

                    generatePDFInvoice(facture);

                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données:', error);
                });
            });

            $('.Table_day_cons').on('click', '#Cfiche', function() {
                var preloader_ch = `
                    <div id="preloader_ch">
                        <div class="spinner_preloader_ch"></div>
                    </div>
                `;
                // Add the preloader to the body
                document.body.insertAdjacentHTML('beforeend', preloader_ch);

                const code = $(this).data('idconsexterne');

                fetch(`/api/fiche_consultation/${code}`) // API endpoint
                .then(response => response.json())
                .then(data => {
                    // Access the 'chambre' array from the API response
                    const facture = data.facture;

                    var preloader = document.getElementById('preloader_ch');
                        if (preloader) {
                            preloader.remove();
                        }

                    generatePDFficheCons(facture);

                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données:', error);
                });
            });

            $('.Table_day_cons').on('click', '#deleteCons', function() {
                const numfac = $(this).data('numfac');

                $('#IddeleteCons').val(numfac);
            });
        }

        $('#btn_refresh_table').on('click', function () {
            table_cons.ajax.reload(null, false); 
        });

        function eng_consultation() {

            const login = @json(Auth::user()->login);
            const id_patient = $('#id_patient').val();
            const assurance_utiliser = $('#assurance_utiliser').val();
            const typeacte_idS = $('#typeacte_idS').val();
            const medecin_id = $('#medecin_id').val();
            const periode = $('#periode').val();
            const montant_assurance = $('#montant_assurance').val();
            const montant_patient = $('#montant_patient').val();
            const taux_remise = $('#taux_remise').val() || '0';
            const montant_total = $('#montant_total').val();
            const mumcode = $('#mumcode').val() || null;

            const codeassurance = $('#patient_codeassurance').val() || null;
            const patient_numdossier = $('#patient_numdossier').val() || null;
            const patient_taux = $('#patient_taux').val();

            // Validation des champs obligatoires
            if (!typeacte_idS || !medecin_id || !taux_remise.trim()) {
                showAlert('Alert', 'Tous les champs sont obligatoires.', 'warning');
                return false;
            }

            if (montant_assurance < 0 || montant_patient < 0 || taux_remise < 0) {
                showAlert('Alert', 'Veuillez vérifier le montant de la remise.', 'warning');
                return false;
            }

            // Afficher le préchargeur
            const preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $('body').append(preloader_ch);

            $.ajax({
                url: '/api/new_consultation',
                method: 'GET', // Utiliser 'POST' pour créer des données
                data: {
                    id_patient: id_patient,
                    typeacte_id: typeacte_idS,
                    user_id: medecin_id,
                    periode: periode,
                    montant_assurance: montant_assurance,
                    montant_patient: montant_patient,
                    taux_remise: taux_remise,
                    total: montant_total,
                    appliq_remise: $('#appliq_remise').val(),
                    mumcode: mumcode,
                    assurance_utiliser: assurance_utiliser,
                    login: login,
                    codeassurance: codeassurance,
                    patient_numdossier: patient_numdossier,
                    patient_taux: patient_taux,
                },
                success: function(response) {
                    $('#preloader_ch').remove();
                    
                    if (response.success) {

                        $('#div_info_patient').empty();
                        $('#div_info_consul').hide();
                        $('#mumcode').val('');

                        if ($('#stat_consultation').html().trim() !== "") {
                            Statistique_cons();
                        }

                        table_cons.ajax.reload(null, false);

                        Statistique();
                        Reset();
                        Activity_cons();
                        Activity_cons_count();

                        showAlert('Succès', 'Patient Enregistrée.', 'success');

                    } else if (response.error) {
                        showAlert('Alert', 'Une erreur est survenue lors de l\'enregistrement.', 'error');
                    }
                },
                error: function() {
                    $('#preloader_ch').remove();
                    showAlert('Alert', 'Une erreur est survenue lors de l\'enregistrement.', 'error');
                }
            });
        }

        function delete_cons() {

            const numfac = document.getElementById('IddeleteCons').value;

            var modal = bootstrap.Modal.getInstance(document.getElementById('MdeleteCons'));
            modal.hide();

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: '/api/delete_Cons/'+numfac,
                method: 'GET',
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.success) {
                        table_cons.ajax.reload(null, false);
                        showAlert('Succès', 'Opération éffectuée.','success');
                    } else if (response.error) {
                        showAlert("ERREUR", 'Echec de l\'opération', "error");
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

        // ------------------------------------------------------------------

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

        //-------------------------------------------------------------------

        function Statistique() {

            const nbre_patient_day = document.getElementById("nbre_patient_day");
            const nbre_patient_assurer_day = document.getElementById("nbre_patient_assurer_day");
            const nbre_patient_nassurer_day = document.getElementById("nbre_patient_nassurer_day");
            const prix_cons_day = document.getElementById("prix_cons_day");

            $.ajax({
                url: '/api/statistique_reception',
                method: 'GET',
                success: function(response) {
                    // Set the text content of each element
                    nbre_patient_day.textContent = response.nbre_patient_day;
                    nbre_patient_assurer_day.textContent = response.nbre_patient_assurer_day;
                    nbre_patient_nassurer_day.textContent = response.nbre_patient_nassurer_day;
                    prix_cons_day.textContent = formatPrice(response.prix_cons_day.toString()) + ' Fcfa'; // assuming the currency is XOF
                },
                error: function() {
                    // Set default values in case of an error
                    nbre_patient_day.textContent = '0';
                    nbre_patient_assurer_day.textContent = '0';
                    nbre_patient_nassurer_day.textContent = '0';
                    prix_cons_day.textContent = '0 Fcfa';
                }
            });
        }

        function Statistique_cons() {

            const stat_consultation = document.getElementById("stat_consultation");

            const div = document.createElement('div');
            div.innerHTML = `
                <div class="d-flex justify-content-center align-items-center mb-3">
                    <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                    <strong>Chargement des données...</strong>
                </div>
            `;
            stat_consultation.appendChild(div);


            fetch('/api/statistique_reception_cons') // API endpoint
                .then(response => response.json())
                .then(data => {

                    const typeactes = data.typeacte;
                    stat_consultation.innerHTML = '';

                    if (typeactes.length > 0) {

                        // Loop through each item in the chambre array
                        typeactes.forEach((item, index) => {
                            // Create a new row
                            const row = document.createElement('div');
                            row.className = "col-xl-3 col-sm-6 col-12";
                            // Create and append cells to the row based on your table's structure
                            row.innerHTML = `
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="p-2 border border-primary rounded-circle me-3">
                                                <div class="icon-box md bg-primary-subtle rounded-5">
                                                    <i class="ri-stethoscope-line fs-4 text-primary"></i>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <h5 class="lh-1">
                                                    ${item.libgaran}
                                                </h5>
                                                <p class="m-0">
                                                    ${item.nbre} Consultation(s)
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between mt-1">
                                            <div class="text-start">
                                                <p class="mb-0 text-primary">Part Assurance</p>
                                            </div>
                                            <div class="text-end">
                                                <p class="mb-0 text-primary">
                                                    ${formatPrice(item.part_assurance.toString())} Fcfa
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between mt-1">
                                            <div class="text-start">
                                                <p class="mb-0 text-primary">Part Patient</p>
                                            </div>
                                            <div class="text-end">
                                                <p class="mb-0 text-primary">
                                                    ${formatPrice(item.part_patient.toString())} Fcfa
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between mt-1">
                                            <div class="text-start">
                                                <p class="mb-0 text-primary">Montant Total</p>
                                            </div>
                                            <div class="text-end">
                                                <p class="mb-0 text-primary">
                                                    ${formatPrice(item.total.toString())} Fcfa
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            // Append the row to the table body
                            stat_consultation.appendChild(row);

                        });
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données:', error);
                });
        }

        function Activity_cons() {

            fetch('/api/getWeeklyConsultations')
                .then(response => response.json())
                .then(data => {

                    const page = document.getElementById('docActivity');
                    page.innerHTML = "";

                    var contenu = `
                        <div id="docActivity2"></div>
                    `;

                    page.innerHTML = contenu;
                    
                    var options = {
                        chart: {
                            height: 150,
                            type: "bar",
                            toolbar: {
                                show: false,
                            },
                        },
                        plotOptions: {
                            bar: {
                                columnWidth: "70%",
                                borderRadius: 2,
                                distributed: true,
                                dataLabels: {
                                    position: "center",
                                },
                            },
                        },
                        series: [{
                            name: "Consultations",
                            data: data, // Use the data from the backend
                        }],
                        legend: {
                            show: false,
                        },
                        xaxis: {
                            categories: [
                                "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim"
                            ],
                            axisBorder: {
                                show: false,
                            },
                            labels: {
                                show: true,
                            },
                        },
                        yaxis: {
                            show: false,
                        },
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
                                    show: false,
                                },
                            },
                            padding: {
                                top: 0,
                                right: 0,
                                bottom: 0,
                                left: 0,
                            },
                        },
                        tooltip: {
                            y: {
                                formatter: function(val) {
                                    return val;
                                },
                            },
                        },
                        colors: [
                            "rgba(255, 255, 255, 0.7)", "rgba(255, 255, 255, 0.6)", "rgba(255, 255, 255, 0.5)", "rgba(255, 255, 255, 0.4)", "rgba(255, 255, 255, 0.3)", "rgba(255, 255, 255, 0.2)", "rgba(255, 255, 255, 0.2)"
                        ],
                    };

                    var chart = new ApexCharts(document.querySelector("#docActivity2"), options);
                    chart.render();
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        function Activity_cons_count() {
            fetch('/api/getConsultationComparison')
                .then(response => response.json())
                .then(data => {
                    const percentage = data.percentage || 0;
                    const currentWeek = data.currentWeek || 0;
                    const lastWeek = data.lastWeek || 0;

                    // Afficher le résultat
                    document.getElementById('consultationComparison').innerHTML = `
                        <div class="text-center">
                            <span class="badge bg-danger">${percentage}%</span> des patients sont supérieurs<br>à ceux de la semaine dernière. 
                            (${currentWeek} consultation cette semaine, et ${lastWeek} consultation la semaine dernière).
                        </div>
                    `;
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        // ------------------------------------------------------------------

        function generatePDFInvoice(facture) {

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });

            const pdfFilename = "Consultation Facture N°" + facture.numfac + " du " + formatDate(facture.date);
            doc.setProperties({
                title: pdfFilename,
            });

            yPos = 10;

            function drawConsultationSection(yPos) {
                rightMargin = 15;
                leftMargin = 15;
                pdfWidth = doc.internal.pageSize.getWidth();

                const titlea = "Facture";
                doc.setFontSize(100);
                doc.setTextColor(242, 237, 237); // Gray color for background effect
                doc.setFont("Helvetica", "bold");
                doc.text(titlea, 120, yPos + 120, { align: 'center', angle: 40 });

                const logoSrc = "{{asset('assets/images/logo.png')}}";
                const logoWidth = 22;
                const logoHeight = 22;
                doc.addImage(logoSrc, 'PNG', leftMargin, yPos - 7, logoWidth, logoHeight);

                // Informations de l'entreprise
                doc.setFontSize(10);
                doc.setTextColor(0, 0, 0);
                doc.setFont("Helvetica", "bold");
                // Texte de l'entreprise
                const title = "ESPACE MEDICO SOCIAL LA PYRAMIDE DU COMPLEXE";
                const titleWidth = doc.getTextWidth(title);
                const titleX = (doc.internal.pageSize.getWidth() - titleWidth) / 2;
                doc.text(title, titleX, yPos);
                // Texte de l'adresse
                doc.setFont("Helvetica", "normal");
                const address = "Abidjan Yopougon Selmer, Non loin du complexe sportif Jesse-Jackson - 04 BP 1523";
                const addressWidth = doc.getTextWidth(address);
                const addressX = (doc.internal.pageSize.getWidth() - addressWidth) / 2;
                doc.text(address, addressX, (yPos + 5));
                // Texte du téléphone
                const phone = "Tél.: 20 24 44 70 / 20 21 71 92 - Cel.: 01 01 01 63 43";
                const phoneWidth = doc.getTextWidth(phone);
                const phoneX = (doc.internal.pageSize.getWidth() - phoneWidth) / 2;
                doc.text(phone, phoneX, (yPos + 10));
                doc.setFontSize(10);
                doc.setFont("Helvetica", "normal");
                const consultationDate = new Date(facture.date);
                // Formatter la date et l'heure séparément
                const formattedDate = consultationDate.toLocaleDateString(); // Formater la date
                const formattedTime = consultationDate.toLocaleTimeString();
                doc.text("Date: " + formattedDate, 15, (yPos + 25));
                doc.text("Heure: " + formattedTime, 15, (yPos + 30));
                //Ligne de séparation
                doc.setFontSize(15);
                doc.setFont("Helvetica", "bold");
                doc.setLineWidth(0.5);
                doc.setTextColor(255, 0, 0);
                // doc.line(10, 35, 200, 35); 
                const titleR = "FACTURE DE CONSULTATION";
                const titleRWidth = doc.getTextWidth(titleR);
                const titleRX = (doc.internal.pageSize.getWidth() - titleRWidth) / 2;
                // Définir le padding
                const paddingh = 0; // Padding vertical
                const paddingw = 15; // Padding horizontal
                // Calculer les dimensions du rectangle
                const rectX = titleRX - paddingw; // X du rectangle
                const rectY = (yPos + 18) - paddingh; // Y du rectangle
                const rectWidth = titleRWidth + (paddingw * 2); // Largeur du rectangle
                const rectHeight = 15 + (paddingh * 2); // Hauteur du rectangle
                // Définir la couleur pour le cadre (noir)
                doc.setDrawColor(0, 0, 0);
                doc.rect(rectX, rectY, rectWidth, rectHeight); // Dessiner le rectangle
                // Ajouter le texte centré en gras
                doc.setFontSize(15);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(255, 0, 0); // Couleur du texte rouge
                doc.text(titleR, titleRX, (yPos + 25)); // Positionner le texte
                const titleN = "N° "+ facture.numfac;
                doc.text(titleN, (doc.internal.pageSize.getWidth() - doc.getTextWidth(titleN)) / 2, (yPos + 31));

                doc.setFontSize(10);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(0, 0, 0);
                const numDossier = facture.numdossier ? " N° Dossier : " + facture.numdossier : " N° Dossier : Aucun";
                const numDossierWidth = doc.getTextWidth(numDossier);
                doc.text(numDossier, (pdfWidth - rightMargin - numDossierWidth) + 5, yPos + 28);

                yPoss = (yPos + 50);

                let assurer;

                if (facture.assure == 1) {
                    assurer = 'Oui';
                } else {
                    assurer = 'Non';
                }

                const patientInfo = [
                    { 
                        label: "Nom et Prénoms", 
                        value: facture.nom_patient.length > 25 
                            ? facture.nom_patient.substring(0, 25) + '...' 
                            : facture.nom_patient 
                    },
                    { label: "Assurer", value: assurer },
                    { label: "Age", value: calculateAge(facture.datenais)+" an(s)" },
                    { label: "Contact", value: facture.telpatient }
                ];

                if (facture.assure == 1) {
                    patientInfo.push(
                        { 
                            label: "Assurance", 
                            value: facture.assurance.length > 25 
                                ? facture.assurance.substring(0, 25) + '...' 
                                : facture.assurance 
                        },
                        { label: "Matricule", value: facture.matriculeassure },
                        { label: "N° de Bon", value: facture.numbon || 'Aucun' },
                    );
                }

                patientInfo.forEach(info => {
                    doc.setFontSize(8);
                    doc.setFont("Helvetica", "bold");
                    doc.setTextColor(0, 0, 0);
                    doc.text(info.label, leftMargin, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 35, yPoss);
                    yPoss += 7;
                });

                yPoss = (yPos + 50);

                const medecinInfo = [
                    { label: "N° Consultation", value: facture.idconsexterne},
                    { 
                        label: "Medecin", 
                        value: facture.nom_medecin.length > 20 
                            ? facture.nom_medecin.substring(0, 20) + '...' 
                            : facture.nom_medecin 
                    },
                    { label: "Spécialité", value: facture.specialite },
                    { label: "Prix Consultation", value: formatPriceT(facture.montant)+" Fcfa" },
                ];

                medecinInfo.forEach(info => {
                    doc.setFontSize(8);
                    doc.setFont("Helvetica", "bold");
                    doc.setTextColor(0, 0, 0);
                    doc.text(info.label, leftMargin + 100, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 135, yPoss);
                    yPoss += 7;
                });

                yPoss = (yPos + 90);

                const compteInfo = [
                    { label: "Montant Total", value: formatPriceT(facture.montant)+" Fcfa" },
                    ...(facture.assure == 1 
                        ? [
                            { label: "Part assurance", value: formatPriceT(facture.partassurance)+" Fcfa" },
                            { label: "Taux", value: facture.taux+" %" }
                          ] 
                        : []),
                    { label: "Remise", value: formatPriceT(facture.remise)+" Fcfa" },
                ];

                compteInfo.forEach(info => {
                    doc.setFontSize(9);
                    doc.setFont("Helvetica", "bold");
                    doc.setTextColor(0, 0, 0);
                    doc.text(info.label, leftMargin + 100, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 135, yPoss);
                    yPoss += 7;
                });

                yPoss += 1;

                doc.setFontSize(11);
                doc.setTextColor(0, 0, 0);
                doc.setFont("Helvetica", "bold");
                doc.text('Montant à payer', leftMargin + 100, yPoss);
                doc.setFont("Helvetica", "bold");
                doc.text(": "+formatPriceT(facture.partpatient)+" Fcfa", leftMargin + 135, yPoss);

                if (facture.assure == 1 ) {
                    doc.setFontSize(8);
                    doc.setFont("Helvetica", "bold");
                    doc.setTextColor(0, 0, 0);
                    doc.text("Imprimer le "+new Date().toLocaleDateString()+" à "+new Date().toLocaleTimeString() , 5, yPoss + 16);
                }else{
                    doc.setFontSize(8);
                    doc.setFont("Helvetica", "bold");
                    doc.setTextColor(0, 0, 0);
                    doc.text("Imprimer le "+new Date().toLocaleDateString()+" à "+new Date().toLocaleTimeString() , 5, yPoss + 28);
                }

            }

            drawConsultationSection(yPos);

            doc.setFontSize(30);
            doc.setLineWidth(0.5);
            doc.setLineDashPattern([3, 3], 0);
            doc.line(0, (yPos + 137), 300, (yPos + 137));
            doc.setLineDashPattern([], 0);

            drawConsultationSection(yPos + 150);


            doc.output('dataurlnewwindow');
        }

        function generatePDFficheCons(facture) {

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            const pdfFilename = "Fiche Consultation N°" + facture.numfac + " du " + formatDate(facture.date);
            doc.setProperties({
                title: pdfFilename,
            });

            yPos = 10;

            function drawConsultationSection(yPos) {
                rightMargin = 15;
                leftMargin = 15;
                pdfWidth = doc.internal.pageSize.getWidth();

                const titlea = "Fiche";
                doc.setFontSize(100);
                doc.setTextColor(242, 237, 237); // Gray color for background effect
                doc.setFont("Helvetica", "bold");
                doc.text(titlea, 120, yPos + 150, { align: 'center', angle: 40 });

                const logoSrc = "{{asset('assets/images/logo.png')}}";
                const logoWidth = 22;
                const logoHeight = 22;
                doc.addImage(logoSrc, 'PNG', leftMargin, yPos - 7, logoWidth, logoHeight);

                // Informations de l'entreprise
                doc.setFontSize(10);
                doc.setTextColor(0, 0, 0);
                doc.setFont("Helvetica", "bold");
                // Texte de l'entreprise
                const title = "ESPACE MEDICO SOCIAL LA PYRAMIDE DU COMPLEXE";
                const titleWidth = doc.getTextWidth(title);
                const titleX = (doc.internal.pageSize.getWidth() - titleWidth) / 2;
                doc.text(title, titleX, yPos);
                // Texte de l'adresse
                doc.setFont("Helvetica", "normal");
                const address = "Abidjan Yopougon Selmer, Non loin du complexe sportif Jesse-Jackson - 04 BP 1523";
                const addressWidth = doc.getTextWidth(address);
                const addressX = (doc.internal.pageSize.getWidth() - addressWidth) / 2;
                doc.text(address, addressX, (yPos + 5));
                // Texte du téléphone
                const phone = "Tél.: 20 24 44 70 / 20 21 71 92 - Cel.: 01 01 01 63 43";
                const phoneWidth = doc.getTextWidth(phone);
                const phoneX = (doc.internal.pageSize.getWidth() - phoneWidth) / 2;
                doc.text(phone, phoneX, (yPos + 10));
                doc.setFontSize(10);
                doc.setFont("Helvetica", "normal");
                const consultationDate = new Date(facture.date);
                // Formatter la date et l'heure séparément
                const formattedDate = consultationDate.toLocaleDateString(); // Formater la date
                const formattedTime = consultationDate.toLocaleTimeString();
                doc.text("Date: " + formattedDate, 15, (yPos + 25));
                doc.text("Heure: " + formattedTime, 15, (yPos + 30));

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
                const rectY = (yPos + 18) - paddingh; // Y du rectangle
                const rectWidth = titleRWidth + (paddingw * 2); // Largeur du rectangle
                const rectHeight = 15 + (paddingh * 2); // Hauteur du rectangle
                // Définir la couleur pour le cadre (noir)
                doc.setDrawColor(0, 0, 0);
                doc.rect(rectX, rectY, rectWidth, rectHeight); // Dessiner le rectangle
                // Ajouter le texte centré en gras
                doc.setFontSize(15);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(255, 0, 0); // Couleur du texte rouge
                doc.text(titleR, titleRX, (yPos + 25)); // Positionner le texte
                const titleN = "N° "+ facture.numfac;
                doc.text(titleN, (doc.internal.pageSize.getWidth() - doc.getTextWidth(titleN)) / 2, (yPos + 31));

                doc.setFontSize(10);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(0, 0, 0);
                const numDossier = facture.numdossier ? " N° Dossier : " + facture.numdossier : " N° Dossier : Aucun";
                const numDossierWidth = doc.getTextWidth(numDossier);
                doc.text(numDossier, (pdfWidth - rightMargin - numDossierWidth) + 5, yPos + 25);

                yPoss = (yPos + 45);

                let assurer;

                if (facture.assure == 1) {
                    assurer = 'Oui';
                } else {
                    assurer = 'Non';
                }

                const patientInfo = [
                    { label: "Medecin", value: facture.nom_medecin },
                    { label: "Spécialité", value: facture.specialite },
                    { label: "Nom et Prénoms", value: facture.nom_patient },
                    { label: "Assurer", value: assurer},
                    { label: "Age", value: calculateAge(facture.datenais)+" an(s)" },
                    { label: "Contact", value: facture.telpatient },
                ];

                if (facture.assure == 1) {
                    patientInfo.push(
                        { label: "Assurance", value: facture.assurance },
                        { label: "Filiation", value: facture.filiation },
                        { label: "Matricule", value: facture.matriculeassure },
                        { label: "Sociéte", value: facture.societe },
                    );
                }

                patientInfo.forEach(info => {
                    doc.setFontSize(10);
                    doc.setFont("Helvetica", "bold");
                    doc.text(info.label, leftMargin, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 35, yPoss);
                    yPoss += 7;
                });

                doc.setFontSize(30);
                doc.setLineWidth(0.5);
                doc.line(10, yPoss, 200, yPoss);

                doc.setFontSize(15);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(255, 0, 0);
                const rendu = "Compte Rendu";
                const titleRr = doc.getTextWidth(rendu);
                const titlerendu = (doc.internal.pageSize.getWidth() - titleRr) / 2;
                doc.text(rendu, titlerendu, yPoss + 10);
                // Dessiner une ligne sous le texte pour le souligner
                const underlineY = yPoss + 12; // Ajustez cette valeur selon vos besoins
                doc.setDrawColor(255, 0, 0);
                doc.setLineWidth(0.5); // Épaisseur de la ligne
                doc.line(titlerendu, underlineY, titlerendu + titleRr, underlineY);

                yPoss += 25;
                hPoss = yPoss;
                doc.setTextColor(0, 0, 0);

                const pInfo1 = [
                    { label: "TA", value: "..........." },
                    { label: "Poids", value: "..........." },
                ];

                pInfo1.forEach(info => {
                    doc.setFontSize(10);
                    doc.setFont("Helvetica", "bold");
                    doc.text(info.label, leftMargin, yPoss);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 15, yPoss);
                    yPoss += 10;
                });

                const pInfo2 = [
                    { label: "BD", value: "..........." },
                    { label: "Pouls", value: "..........." },
                ];

                pInfo2.forEach(info => {
                    doc.setFontSize(10);
                    doc.setFont("Helvetica", "bold");
                    doc.text(info.label, leftMargin + 40, yPoss - 20);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 55, yPoss - 20);
                    yPoss += 10;
                });

                const pInfo3 = [
                    { label: "BG", value: "..........." },
                    { label: "Taille", value: "..........." },
                ];

                pInfo3.forEach(info => {
                    doc.setFontSize(10);
                    doc.setFont("Helvetica", "bold");
                    doc.text(info.label, leftMargin + 75, yPoss - 40);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 90, yPoss - 40);
                    yPoss += 10;
                });

                const pInfo4 = [
                    { label: "Temp", value: "..........." },
                ];

                pInfo4.forEach(info => {
                    doc.setFontSize(10);
                    doc.setFont("Helvetica", "bold");
                    doc.text(info.label, leftMargin + 110, yPoss - 60);
                    doc.setFont("Helvetica", "normal");
                    doc.text(": " + info.value, leftMargin + 125, yPoss - 60);
                    yPoss += 10;
                });

                hPoss += 25;

                doc.setFontSize(15);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(225, 0, 0);
                const motif = "Motif";
                const titleRm = doc.getTextWidth(motif);
                const titlemotif = (doc.internal.pageSize.getWidth() - titleRm) / 2;
                doc.text(motif, titlemotif, hPoss);
                // Dessiner une ligne sous le texte pour le souligner
                const underlineYm = hPoss + 2; // Ajustez cette valeur selon vos besoins
                doc.setDrawColor(225, 0, 0);
                doc.setLineWidth(0.5); // Épaisseur de la ligne
                doc.line(titlemotif, underlineYm, titlemotif + titleRm, underlineYm);

                doc.setFontSize(8);
                doc.setFont("Helvetica", "bold");
                doc.setTextColor(0, 0, 0);
                doc.text("Imprimer le "+new Date().toLocaleDateString()+" à "+new Date().toLocaleTimeString() , 5, 295);

            }

            drawConsultationSection(yPos);

            doc.output('dataurlnewwindow');
        }

        // -----------------------------------------------------------------

        // const table_rdv = $('.Table_day_rdv').DataTable({

        //     processing: true,
        //     serverSide: false,
        //     ajax: {
        //         url: `/api/list_rdv_day`,
        //         type: 'GET',
        //         dataSrc: 'data',
        //     },
        //     columns: [
        //         { 
        //             data: null, 
        //             render: (data, type, row, meta) => meta.row + 1,
        //             searchable: false,
        //             orderable: false,
        //         },
        //         { 
        //             data: 'patient', 
        //             render: (data, type, row) => `
        //             <div class="d-flex align-items-center">
        //                 <a class="d-flex align-items-center flex-column me-2">
        //                     <img src="/assets/images/rdv1.png" class="img-2x rounded-circle border border-1">
        //                 </a>
        //                 ${data}
        //             </div>`,
        //             searchable: true, 
        //         },
        //         {
        //             data: 'patient_tel',
        //             render: (data, type, row) => {
        //                 return data ? `+225 ${data}` : 'Néant';
        //             },
        //             searchable: true,
        //         },
        //         {
        //             data: 'medecin',
        //             render: (data, type, row) => {
        //                 return data ? `Dr. ${data}` : 'Néant';
        //             },
        //             searchable: true,
        //         },
        //         { 
        //             data: 'specialite',
        //             searchable: true, 
        //         },
        //         { 
        //             data: 'date',
        //             render: formatDate,
        //             searchable: true, 
        //         },
        //         {
        //             data: 'statut',
        //             render: (data, type, row) => `
        //                 <span class="badge ${data === 'en attente' ? 'bg-danger' : 'bg-success'}">
        //                     ${data === 'en attente' ? 'En Attente' : 'Terminer'}
        //                 </span>
        //             `,
        //             searchable: true,
        //         },
        //         { 
        //             data: 'created_at',
        //             render: formatDateHeure,
        //             searchable: true, 
        //         },
        //         {
        //             data: null,
        //             render: (data, type, row) => `
        //                 <div class="d-inline-flex gap-1" style="font-size:10px;">
        //                     <a class="btn btn-outline-warning btn-sm rounded-5 edit-btn" data-motif="${row.motif}" data-bs-toggle="modal" data-bs-target="#Detail_motif" id="motif">
        //                         <i class="ri-eye-line"></i>
        //                     </a>
        //                     ${row.statut == 'en attente' ? 
        //                     `<a class="btn btn-outline-info btn-sm rounded-5" data-bs-toggle="modal" data-bs-target="#Modif_Rdv_modal" id="modif"
        //                         data-id="${row.id}"
        //                         data-date="${row.date}"
        //                         data-patient="${row.patient}"
        //                         data-motif="${row.motif}"
        //                         data-medecin="${row.medecin}"
        //                         data-specialite="${row.specialite}"
        //                         data-horaires='${JSON.stringify(row.horaires)}'>
        //                        <i class="ri-edit-line"></i>
        //                     </a>
        //                     <a class="btn btn-outline-danger btn-sm rounded-5" data-bs-toggle="modal" data-bs-target="#Mdelete" id="delete" data-id="${row.id}">
        //                         <i class="ri-delete-bin-line"></i>
        //                     </a>` :
        //                     `` }
        //                 </div>
        //             `,
        //             searchable: false,
        //             orderable: false,
        //         }
        //     ],
        //     ...dataTableConfig, 
        //     initComplete: function(settings, json) {
        //         initializeRowEventListenersRdv();
        //     },
        // });

        // function initializeRowEventListenersRdv() {

        //     $('.Table_day_rdv').on('click', '#modif', function() {
        //         const id = $(this).data('id');
        //         const date = $(this).data('date');
        //         const patient = $(this).data('patient');
        //         const motif = $(this).data('motif');
        //         const medecin = $(this).data('medecin');
        //         const specialite = $(this).data('specialite');

        //         $('#id_rdvM').val(id);

        //         const today = new Date();
        //         const formattedToday = today.toISOString().split('T')[0];
        //         $('#date_rdvM').val(date).attr('min', formattedToday);

        //         $('#patient_rdvM').val(patient);
        //         $('#motif_rdvM').val(motif);
        //         $('#medecin_rdvM').val(medecin);
        //         $('#specialite_rdvM').val(specialite);

        //         const horairesData = $(this).data('horaires');
        //         const allowedDays = horairesData ? horairesData.map(horaire => horaire.jour) : [];

        //         $('#date_rdvM').on('change', function(event) {
        //             const selectedDate = new Date(event.target.value);
        //             const selectedDay = selectedDate.getDay();

        //             const dayMapping = {
        //                 'DIMANCHE': 0,
        //                 'LUNDI': 1,
        //                 'MARDI': 2,
        //                 'MERCREDI': 3,
        //                 'JEUDI': 4,
        //                 'VENDREDI': 5,
        //                 'SAMEDI': 6
        //             };

        //             const isValidDay = allowedDays.some(day => dayMapping[day] === selectedDay);

        //             if (!isValidDay) {
        //                 // Vérification si date_rdvM est une valeur valide
        //                 let formattedDate = "";
        //                 if (date_rdvM && !isNaN(new Date(date_rdvM).getTime())) {
        //                     // Si date_rdvM est valide, formater la date
        //                     formattedDate = new Date(date_rdvM).toISOString().split('T')[0];
        //                 } else {
        //                     // Si date_rdvM n'est pas valide, utilisez la date du jour comme fallback
        //                     formattedDate = new Date().toISOString().split('T')[0];
        //                 }

        //                 // Remettre la date dans le champ de saisie
        //                 $('#date_rdvM').val(formattedDate);
                        
        //                 // Afficher le message d'alerte
        //                 showAlert("ALERT", 'Veuillez sélectionner un jour valide selon les horaires du médecin.', "info");
        //             }
        //         });
        //     });

        //     $('.Table_day_rdv').on('click', '#motif', function() {
        //         const motif = $(this).data('motif');
        //         // Handle the 'Delete' button click
        //         const modal = document.getElementById('modal_Detail_motif');
        //         modal.innerHTML = '';

        //         const div = document.createElement('div');
        //         div.innerHTML = `
        //                <div class="row gx-3">
        //                     <div class="col-12">
        //                         <div class=" mb-3">
        //                             <div class="card-body">
        //                                 <ul class="list-group">
        //                                     <li class="list-group-item active text-center" aria-current="true">
        //                                         Motif
        //                                     </li>
        //                                     <li class="list-group-item">
        //                                         ${motif} 
        //                                     </li>
        //                                 </ul>
        //                             </div>
        //                         </div>
        //                     </div>
        //                 </div>     
        //         `;

        //         modal.appendChild(div);
        //     });

        //     $('.Table_day_rdv').on('click', '#delete', function() {
        //         const id = $(this).data('id');
                
        //         $('#Iddelete').val(id);
        //     });
        // }

        // $('#btn_refresh_table_rdv').on('click', function () {
        //     table_rdv.ajax.reload(null, false);
        // });

        // function delete_rdv() {

        //     const id = document.getElementById('Iddelete').value;

        //     var modal = bootstrap.Modal.getInstance(document.getElementById('Mdelete'));
        //     modal.hide();

        //     var preloader_ch = `
        //         <div id="preloader_ch">
        //             <div class="spinner_preloader_ch"></div>
        //         </div>
        //     `;
        //     // Add the preloader to the body
        //     document.body.insertAdjacentHTML('beforeend', preloader_ch);

        //     $.ajax({
        //         url: '/api/delete_rdv/'+id,
        //         method: 'GET',
        //         success: function(response) {

        //             var preloader = document.getElementById('preloader_ch');
        //             if (preloader) {
        //                 preloader.remove();
        //             }

        //             if (response.success) {
        //                 $('.Table_day_rdv').DataTable().ajax.reload(null, false);
        //                 count_rdv_two_day();
        //                 showAlert('Succès', 'Rendez-Vous annulé.','success');
        //             } else if (response.error) {
        //                 showAlert("ERREUR", 'Une erreur est survenue', "error");
        //             }
                
        //         },
        //         error: function() {
        //             var preloader = document.getElementById('preloader_ch');
        //             if (preloader) {
        //                 preloader.remove();
        //             }

        //             showAlert('Erreur', 'Erreur lors de la suppression.','error');
        //         }
        //     });
        // }

        // function update_rdv()
        // {
        //     const id = document.getElementById('id_rdvM').value;
        //     const date_rdv = document.getElementById('date_rdvM');
        //     const motif_rdv = document.getElementById('motif_rdvM');

        //     if (!date_rdv.value.trim() || !motif_rdv.value.trim()) {
        //         showAlert("ALERT", 'Veuillez remplir tous les champs.', "warning");
        //         return false;
        //     }

        //     var modal = bootstrap.Modal.getInstance(document.getElementById('Modif_Rdv_modal'));
        //     modal.hide();

        //     var preloader_ch = `
        //         <div id="preloader_ch">
        //             <div class="spinner_preloader_ch"></div>
        //         </div>
        //     `;
        //     // Add the preloader to the body
        //     document.body.insertAdjacentHTML('beforeend', preloader_ch);

        //     $.ajax({
        //         url: '/api/update_rdv/' + id,
        //         method: 'GET',
        //         data:{
        //             date: date_rdv.value,
        //             motif: motif_rdv.value,
        //         },
        //         success: function(response) {

        //             var preloader = document.getElementById('preloader_ch');
        //             if (preloader) {
        //                 preloader.remove();
        //             }
                    
        //             if (response.success) {

        //                 $('.Table_day_rdv').DataTable().ajax.reload(null, false);
        //                 count_rdv_two_day();
        //                 showAlert("ALERT", 'Mise à jour éffectué', "success");

        //             } else if (response.error) {
        //                 showAlert("ERREUR", 'Une erreur est survenue', "error");
        //             }

        //         },
        //         error: function() {
        //             var preloader = document.getElementById('preloader_ch');
        //             if (preloader) {
        //                 preloader.remove();
        //             }

        //             showAlert("ERREUR", 'Une erreur est survenue lors de l\'enregistrement', "error");
        //         }
        //     });
        // }

        // function count_rdv_two_day() {

        //         fetch('/api/count_rdv_two_day')
        //             .then(response => response.json())
        //             .then(data => {
        //                 const nbre = data.nbre || 0;

        //                 document.getElementById('div_two_rdv').innerHTML = '';

        //                 if (nbre > 0) {

        //                     const div = `
        //                         <div class="sidebar-contact" style="background-color: red;">
        //                             <a class="text-white" href="{{route('rdv_two_day')}}">
        //                                 <p class="fw-light mb-1 text-nowrap text-truncate">Rendez-Vous dans 2 jours</p>
        //                                 <h5 class="m-0 lh-1 text-nowrap text-truncate">${nbre}</h5>
        //                                 <i class="ri-calendar-schedule-line"></i>
        //                             </a>
        //                         </div>
        //                     `;

        //                     document.getElementById('div_two_rdv').innerHTML = div;
        //                 }
        //             })
        //             .catch(error => console.error('Error fetching data:', error));
        // }

    });
</script>

@endsection
