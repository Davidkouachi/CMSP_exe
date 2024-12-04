<?php

namespace App\Http\Controllers;

class Controller
{
    public function acte_new()
    {
        return view('grille.acte');
    }

    public function typeacte_new()
    {
        return view('grille.typeacte');
    }

    // public function encaissement_cons()
    // {
    //     return view('finance.consultation.encaissement');
    // }

    // public function liste_caisse_cons()
    // {
    //     return view('finance.consultation.liste_caisse');
    // }

    // public function encaissement_hos()
    // {
    //     return view('finance.hospitalisation.encaissement');
    // }

    // public function liste_caisse_hos()
    // {
    //     return view('finance.hospitalisation.liste_caisse');
    // }

    // --------------------------------------------------------

    public function patient_liste()
    {
        return view('accueil.reception.patient');
    }

    public function consultation_liste()
    {
        return view('accueil.reception.consultation');
    }

    public function hospitalisation()
    {
        return view('soins_medicaux.hospitalisation.index');
    }

    public function societe_liste()
    {
        return view('accueil.reception.societe');
    }

    public function assurance_liste()
    {
        return view('accueil.reception.assurance');
    }

    public function typeadmission_new()
    {
        return view('soins_medicaux.hospitalisation.typeadmission');
    }

    public function natureadmission_new()
    {
        return view('soins_medicaux.hospitalisation.natureadmission');
    }

    // --------------------------------------------------------

    public function produit_new()
    {
        return view('infirmerie.nouveau.produit_pharmacie');
    }

    // --------------------------------------------------------

    public function soinsam()
    {
        return view('soins_medicaux.soinsam.index');
    }

    // public function encaissement_soinsam()
    // {
    //     return view('finance.soinsam.encaissement');
    // }

    // public function liste_caisse_soinsam()
    // {
    //     return view('finance.soinsam.liste_caisse');
    // }

    public function typesoins_new()
    {
        return view('soins_medicaux.soinsam.typesoins');
    }

    public function soinsinfirmier_new()
    {
        return view('soins_medicaux.soinsam.soinsinfirmier');
    }

    // -----------------------------------------------------

    public function examen()
    {
        return view('soins_medicaux.examen.index');
    }

    // public function encaissement_examen()
    // {
    //     return view('finance.examen.encaissement');
    // }

    // public function liste_caisse_examen()
    // {
    //     return view('finance.examen.liste_caisse');
    // }

    // ---------------------------------------------------

    public function horaire_medecin()
    {
        return view('accueil.reception.horairemedecin');
    }

    public function specialite()
    {
        return view('grille.specialite');
    }

    // ---------------------------------------------------

    public function facture_emise()
    {
        return view('finance.comptabilite.facture.facture_emise');
    }

    public function facture_depot()
    {
        return view('finance.comptabilite.facture.facture_depot');
    }

    public function facture_deposer()
    {
        return view('finance.comptabilite.facture.facture_deposer');
    }

    // --------------------------------------------------------

    public function comptable()
    {
        return view('finance.comptabilite.index');
    }

    public function caisse()
    {
        return view('finance.caisse.index');
    }

    public function operation_caisse()
    {
        return view('finance.caisse.operation');
    }

    // --------------------------------------------------------

    public function etat_acte()
    {
        return view('pdf.acte');
    }

    public function etat_facture()
    {
        return view('pdf.facture');
    }

    public function etat_caisse()
    {
        return view('pdf.caisse');
    }

    // -----------------------------------------------------------

    public function utilisateur()
    {
        return view('utilisateur.nouveau.utilisateur');
    }

    public function rdv_two_day()
    {
        return view('accueil.reception.rdv_two_day');
    }

    // -----------------------------------------------------------

    public function facture_impayer()
    {
        return view('finance.facture.impayer');
    }

    public function facture_liste()
    {
        return view('finance.facture.liste');
    }
}
