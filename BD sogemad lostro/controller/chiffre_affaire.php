<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chiffre_affaire extends Admin_Controller {
    
    
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

            if ($curr_uri_string != 'chiffre_affaire') 
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
        $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        
        $page_data['page_libprofil'] = $UniqueProfil;

        $page_data['bandeau'] = 'CA';

        $page_data['title'] = 'Chiffre affaire';

        $page_data['page_active'] = "chiffre_affairePage";

        $page_data['page_s_title'] = 'Visualisation du chiffre d\'affaire';

        // Effectuer la journalisation
                $type_action = 'Consultation' ;

                $action_effectuee = 'Formulaire de visualisation du chiffre d\'affaires' ;

                $this->control->journalisation($type_action,$action_effectuee) ;

        // affichage de la vue

        $this->render_template('statistiques/rech_form_ca', $page_data);
    }

    public function visualisation()
    {   
        // initialisation du validateur du formulaire

          $this->load->library('form_validation');

        // définition des règles de validation
            
           $this->form_validation->set_rules('date_debut', 'Date de debut', 'trim|required|xss_clean');

           $this->form_validation->set_rules('date_fin', 'Date de fin', 'trim|required|xss_clean');

           if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
           {
               // erreur : retour au formulaire

                $flash_feedback = validation_errors();

                $this->session->set_flashdata('ERRORMSG', $flash_feedback);

                redirect('chiffre_affaire');

            }
            else
            {
                $page_data['date_debut'] = $date_debut = $this->input->post('date_debut');

                $page_data['date_fin'] = $date_fin = $this->input->post('date_fin');

                $page_data['chiffre_aff'] =  $this->all_model->get_ca_periodique($date_debut,$date_fin);
                
                $page_data['solde_caisse'] = $this->all_model->get_solde_caisse($page_data['date_fin']) ;

                $page_data['chiffre_aff_bio'] =  $this->all_model->get_ca_periodique_typefac($date_debut,$date_fin,2);

                $page_data['chiffre_aff_img'] =  $this->all_model->get_ca_periodique_typefac($date_debut,$date_fin,4);

                $page_data['chiffre_aff_hospit'] =  $this->all_model->get_ca_periodique_typefac($date_debut,$date_fin,3);

                $page_data['chiffre_aff_cons'] =  $this->all_model->get_ca_periodique_typefac($date_debut,$date_fin,1);
                
                $page_data['chiffre_aff_soins'] =  $this->all_model->get_ca_periodique_typefac($date_debut,$date_fin,5);
                
                $page_data['repartition_cons'] =  $this->all_model->get_repartition_consultation($date_debut,$date_fin);

                $page_data['factures_emises'] =  $this->all_model->get_factures_emises($date_debut,$date_fin);
                
                $page_data['f_non_regle'] =  $this->all_model->get_montantant_factures_non_regle($date_debut,$date_fin);

                $page_data['sortie_caisse'] =  $this->all_model->get_montant_sortie($date_debut,$date_fin);

                $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

                $page_data['new_patient'] =  $this->all_model->get_patient_save($date_debut,$date_fin);

                $consultation =  $this->all_model->get_nbre_consultation_periodique($date_debut,$date_fin);
                
         
                $page_data['nbre_consultation'] = $consultation['nbre'] ;

                $bio =  $this->all_model->get_nbre_biologie_periodique($date_debut,$date_fin);
         
                $page_data['nbre_biologie'] = $bio['nbre'] ;
                
                $img =  $this->all_model->get_nbre_imagerie_periodique($date_debut,$date_fin);
                
                $page_data['nbre_imagerie'] = $img['nbre'] ;
                
                $admission =  $this->all_model->get_nbre_admission_periodique($date_debut,$date_fin);
                 
                $page_data['nbre_admission'] = $admission['nbre'] ;

                
                $page_data['page_libprofil'] = $UniqueProfil;

                $page_data['bandeau'] = 'CA';

                $page_data['title'] = 'Chiffre affaire';

                $page_data['page_active'] = "chiffre_affairePage";

                $page_data['page_s_title'] = 'Visualisation du chiffre d\'affaire';


                // Effectuer la journalisation
                $type_action = 'Consultation' ;

                $action_effectuee = 'Chiffre d\'affaires périodique' ;

                $this->control->journalisation($type_action,$action_effectuee) ;

                // affichage de la vue

                $this->render_template('statistiques/visualisation_ca', $page_data);
            }
    }
}
