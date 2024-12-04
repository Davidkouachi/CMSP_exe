<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Securite extends Admin_Controller {
    
    
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

			if ($curr_uri_string != 'securite') 
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
			// UTILISATION DU RESULTAT PROVENANT DE LA REQUETE D'OBTENTION DE LA LISTE
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_active'] = 'SecurityPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Sécurité';

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Module de sécurité' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

			$this->render_template('kameleon/securite', $page_data);
	}

	public function journal_actions()
	{
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_active'] = 'SecurityPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Journal des actions effectuées';

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Journal des actions effectuées' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$page_data['idAns'] = 'journalisation_actions';

		 	$page_data['namePg'] = 'fetchAllHistoriqueData';

		 	$page_data['user_username'] = $this->session->userdata('user_name');

		 	$page_data['profils_autorises'] = array('1','2','7');

			// affichage de la vue

			$this->render_template('kameleon/journal_actions', $page_data);
	}


}
	
