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
            A Propos
        </li>
    </ol>
</div>
@endsection

@section('content')

    <div class="app-body">
        <!-- Row starts -->
        <div class="row gx-3">
            <div class="col-12">
                <div class="card mb-3 ">
                    <div class="card-body text-center" >
                        <img height="250" height="250" src="{{asset('assets/images/logo.png')}}">
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="accordion mb-3" id="accordionSpecialTitle">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSpecialTitleOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSpecialTitleOne" aria-expanded="true" aria-controls="collapseSpecialTitleOne">
                                <div class="d-flex flex-column">
                                    <h5 class="m-0">Présentation</h5>
                                </div>
                            </button>
                        </h2>
                        <div id="collapseSpecialTitleOne" class="accordion-collapse collapse show" aria-labelledby="headingSpecialTitleOne" data-bs-parent="#accordionSpecialTitle">
                            <div class="accordion-body">
                                <p class="mb-3">
                                    L'Espace Médico-Social "La Pyramide du Complexe" est une clinique moderne et dynamique dédiée à la santé et au bien-être. Située dans un cadre accueillant et accessible, elle offre une large gamme de services médicaux et sociaux afin de répondre aux besoins divers de la population.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSpecialTitleTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSpecialTitleTwo" aria-expanded="false" aria-controls="collapseSpecialTitleTwo">
                                <div class="d-flex flex-column">
                                    <h5 class="m-0">Missions</h5>
                                </div>
                            </button>
                        </h2>
                        <div id="collapseSpecialTitleTwo" class="accordion-collapse collapse" aria-labelledby="headingSpecialTitleTwo" data-bs-parent="#accordionSpecialTitle">
                            <div class="accordion-body">
                                <p class="mb-3">
                                    La mission de La Pyramide du Complexe est d'assurer une prise en charge globale des patients en alliant médecine préventive, curative et sociale. Elle vise à offrir des soins de qualité à travers une approche centrée sur le patient, le tout dans un environnement chaleureux et bienveillant. La clinique s'efforce de promouvoir la santé pour tous, avec un accès équitable aux soins, quel que soit l'âge, le sexe ou les conditions sociales.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSpecialTitleThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSpecialTitleThree" aria-expanded="false" aria-controls="collapseSpecialTitleThree">
                                <div class="d-flex flex-column">
                                    <h5 class="m-0">Services Proposès</h5>
                                </div>
                            </button>
                        </h2>
                        <div id="collapseSpecialTitleThree" class="accordion-collapse collapse" aria-labelledby="headingSpecialTitleThree" data-bs-parent="#accordionSpecialTitle">
                            <div class="accordion-body">
                                L’Espace Médico-Social offre un ensemble de services complets, comprenant :
                                <ul>
                                    <li>Consultations médicales : Médecine générale et spécialisée (pédiatrie, gynécologie, cardiologie, etc.).</li>
                                    <li>Laboratoire d'analyses médicales : Un laboratoire moderne pour des examens fiables et rapides.</li>
                                    <li>Imagerie médicale : Échographie, radiologie et autres examens d'imagerie pour des diagnostics précis.</li>
                                    <li>Soins infirmiers : Administration de soins sur place et suivi des patients.</li>
                                    <li>Services de vaccination : Vaccination pour enfants et adultes.</li>
                                    <li>Services de prévention et sensibilisation : Programme de prévention et d'éducation pour une meilleure gestion de la santé.</li>
                                </ul>
                                <p class="mt-3" >
                                    La clinique dispose d'une équipe pluridisciplinaire composée de médecins spécialistes, d'infirmiers, de techniciens de laboratoire et de personnels administratifs. Tous sont formés pour fournir des soins de haute qualité et accompagner les patients tout au long de leur parcours de santé.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Row ends -->
    </div>


@endsection
