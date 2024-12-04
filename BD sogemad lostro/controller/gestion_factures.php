<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gestion_factures extends Admin_Controller {
    
    
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

			if ($curr_uri_string != 'gestion_factures') 
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

		 //$page_data['factures'] = $this->all_model->get_table('factures');

         $page_data['page_libprofil'] = $UniqueProfil;
		 $page_data['page_active'] = 'gestion_facturesPage';
		 $page_data['page_profil'] = $this->session->userdata('user_profil');
		 $page_data['page_title'] = 'Lostro Admin';
		 $page_data['page_s_title'] = 'Gestion des factures';

		$page_data['date_debut'] = '';

        $page_data['date_fin'] = '';

		 $page_data['type_critere'] = 'mois';

		 $page_data['namePg'] = 'fetchFacturesData';

		 $page_data['option_1'] = date('Ym');

		 $page_data['option_2'] = '';

		 // Effectuer la journalisation

		    $type_action = 'Consultation' ;

		    $action_effectuee = 'Liste des factures du système' ;

		    $this->control->journalisation($type_action,$action_effectuee) ;

		$this->render_template('kameleon/gestion_factures', $page_data);
    }

    public function facture_periode()
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

                $this->session->set_flashdata('error', $flash_feedback);

                redirect('gestion_factures');

            }
            else
            {
				 $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

		         $page_data['page_libprofil'] = $UniqueProfil;
				 $page_data['page_active'] = 'gestion_facturesPage';
				 $page_data['page_profil'] = $this->session->userdata('user_profil');
				 $page_data['page_title'] = 'Lostro Admin';
				 $page_data['page_s_title'] = 'Gestion des factures';

				 $page_data['type_critere'] = 'periode';

				 $page_data['namePg'] = 'fetchFacturesData';

				 $page_data['date_debut'] = $date_debut = $this->input->post('date_debut');

                 $page_data['date_fin'] = $date_fin = $this->input->post('date_fin');

				 $page_data['option_1'] = $date_debut ;

				 $page_data['option_2'] = $date_fin;

				 // Effectuer la journalisation
				        $type_action = 'Consultation' ;

				        $action_effectuee = 'Liste des factures du système' ;

				        $this->control->journalisation($type_action,$action_effectuee) ;
					
					// affichage de la vue

				        //var_dump($page_data) ;
				        //exit();

				$this->render_template('kameleon/gestion_factures', $page_data);
			}
    }

   
	
}
	
