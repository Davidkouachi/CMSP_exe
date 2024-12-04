<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller {
    
    
    function __construct()
    {
        parent::__construct();
        // chargement divers
        $this->lang->load('sogemad');

        // contrôle d'accès
        if (!$this->control->ask_access()) 
        {
            // utilisateur NON authentifié

            $flash_feedback = "Vous n'êtes pas authentifié.";

            $this->session->set_flashdata('warning', $flash_feedback);

            //$curr_uri_string = uri_string();

            $curr_uri_string = $this->uri->segment(1);

            if ($curr_uri_string != 'dashboard') 
            {
                redirect('home/login','refresh');
            }

            redirect('home/login','refresh');
        }

        /*if($this->control->check_lc() === FALSE)
        {
            $this->session->set_userdata('user_id','');
            $this->session->set_userdata('user_name','');
            $this->session->set_userdata('logged_in',FALSE);

            $flash_feedback = "La licence d'utilisation du logiciel est inactive pour ce poste. Vous pouvez demander une augmentation du nombre de poste de votre licence.";

            $this->session->set_flashdata('warning', $flash_feedback);

            redirect('home/login','refresh');
        }*/
        
        /*cache control*/
		$this->output->set_header('Cache-Control: no-store, must-revalidate, post-check=0, pre-check=0');
    }    
    
    public function index()
    {	

        $ListePatient = $this->PatientModel->getPatient();

        $infos_profil = $this->all_model->get_fullrow('profile','idprofile',$this->session->userdata('user_profil'));

        if(!empty($infos_profil))
        {
            $page_data['page_libprofil'] = $infos_profil['libprofile'];
        }
        else{
            $page_data['page_libprofil'] = '';
        }

        $page_data['new_patient'] =  $this->all_model->get_new_patient();

        $page_data['chiffre_aff'] =  $this->all_model->get_ca_mens();
        
        $page_data['chiffre_aff_bio'] =  $this->all_model->get_ca_mens_typefac(2);

        $page_data['chiffre_aff_img'] =  $this->all_model->get_ca_mens_typefac(4);

        $page_data['chiffre_aff_hospit'] =  $this->all_model->get_ca_mens_typefac(3);

        $page_data['chiffre_aff_cons'] =  $this->all_model->get_ca_mens_typefac(1);
        
        $page_data['chiffre_aff_soins'] =  $this->all_model->get_ca_mens_typefac(5);
        
        $page_data['chiffre_aff_pharmacie'] =  $this->all_model->get_ca_mens_typefac(6);
        
         $bio =  $this->all_model->get_nbre_biologie();
         
        $page_data['nbre_biologie'] = $bio['nbre'] ;
        
        $img =  $this->all_model->get_nbre_imagerie();
        
        $page_data['nbre_imagerie'] = $img['nbre'] ;
        
        $admission =  $this->all_model->get_nbre_admission();
         
       	$page_data['nbre_admission'] = $admission['nbre'] ;
       	
       	$consultation =  $this->all_model->get_nbre_consultation();
         
       	$page_data['nbre_consultation'] = $consultation['nbre'] ;
       	
       	$patient =  $this->all_model->get_nbre_patient();
         
        $page_data['nbre_patient'] = $patient['nbre'] ;
        
        $page_data['date_debut'] = $date_debut = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));

        $page_data['date_fin'] = $date_fin = date('Y-m-d', time());
            
        $page_data['repartition_cons'] =  $this->all_model->get_repartition_consultation($date_debut,$date_fin);
        
        $page_data['factures_emises'] =  $this->all_model->get_factures_emises($date_debut,$date_fin);
        
        $page_data['f_non_regle'] =  $this->all_model->get_montantant_factures_non_regle($date_debut,$date_fin);

        $page_data['sortie_caisse'] =  $this->all_model->get_montant_sortie($date_debut,$date_fin);

        $solde_caisse = $this->all_model->get_solde_caisse(date('Y-m-d')) ;
        
         if(empty($solde_caisse))
        {
            $date = date('Y-m-d') ;
            $nombre_jour = 1 ;
            $date_anterieur = $this->fdateheuregmt->date_outil($date,$nombre_jour) ;
                
            $solde_caisse = $this->all_model->get_solde_caisse($date_anterieur) ;
            
            if(empty($solde_caisse))
            {
                $page_data['solde_caisse'] = 0 ;
            }else{
                
                $page_data['solde_caisse'] = $solde_caisse ;
            }
            
             
                
        }else{
                
            $page_data['solde_caisse'] = $this->all_model->get_solde_caisse(date('Y-m-d'),2) ;
            
            if(empty($page_data['solde_caisse']))
            {
                $page_data['solde_caisse'] = $this->all_model->get_dernier_solde_caisse() ;
            }
        }

        $page_data['page_liste_patient'] = $ListePatient;

        $page_data['bandeau'] = lang('title_home_page');

        $page_data['title'] = lang('title_home_page');

        $page_data['page_active'] = "dashboardPage";

        // Fichier /views/templates/content_int.php

        $page_data['page_s_title'] = 'Bienvenue sur le tableau de bord';

        $page_data['nom_bouton'] = '<li><a href="'.base_url().'dashboard"><i class="fa ti-home"></i>Tableau de bord</a></li>' ;

        // Effectuer la journalisation
        $type_action = 'Consultation' ;

        $action_effectuee = 'Tableau de bord' ;

        $this->control->journalisation($type_action,$action_effectuee) ;

        // afficher la vue

        $this->render_template('home/dashboard', $page_data);
    }
}
