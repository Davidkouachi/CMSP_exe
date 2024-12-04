<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Secretariat extends Admin_Controller {
	
	// constructeur
	public function __construct() 
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

			if ($curr_uri_string != 'secretariat') 
			{
				redirect('home/login','refresh');
			}

			redirect('home/login','refresh');
		}

	/*	if($this->control->check_lc() === FALSE)
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
		
	/* ##################################################################
	----------				PAGE RACINE :: ./appels  ----------
	################################################################## */
	
	public function appels() 
	{
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_active'] = 'SecretariatPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Liste des appels téléphoniques';

		$page_data['idAns'] = 'appels_telephonique';

		$page_data['namePg'] = 'fetchAppelsData';

		// Effectuer la journalisation
		    $type_action = 'Consultation' ;

		    $action_effectuee = 'Liste des appels téléphoniques' ;

		    $this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue

		$this->render_template('secretariat/appels_list', $page_data);	
	}

	public function appel_add() 
	{
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_active'] = 'SecretariatPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Ajouter un appel téléphoniques';

		$page_data['civilites'] = $this->all_model->get_table('civilite');

		// Effectuer la journalisation
		    $type_action = 'Consultation' ;

		    $action_effectuee = 'Formulaire d\'ajout d\'un appel téléphonique' ;

		    $this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue

		$this->render_template('secretariat/appel_add', $page_data);	
	}

	public function appel_upd($id) 
	{
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_active'] = 'SecretariatPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Ajouter un appel téléphoniques';

		$page_data['civilites'] = $this->all_model->get_table('civilite');

		$page_data['appel_rech'] = $this->all_model->get_fullrow('appels_telephonique','id',$id);

		// Effectuer la journalisation
		    $type_action = 'Consultation' ;

		    $action_effectuee = 'Formulaire de modification de l\'appel téléphonique :'.' '.$id;

		    $this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue

		$this->render_template('secretariat/appel_upd', $page_data);	
	}

	/* ##################################################################
	----------				PAGE RACINE :: ./courriers  ----------
	################################################################## */
	
	public function courriers() 
	{
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_active'] = 'SecretariatPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Liste des courriers';

		$page_data['idAns'] = 'courriers';

		$page_data['namePg'] = 'fetchCourriersData';

		// Effectuer la journalisation
		    $type_action = 'Consultation' ;

		    $action_effectuee = 'Liste des courriers' ;

		    $this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue

		$this->render_template('secretariat/courriers_list', $page_data);	
	}


	/* ##################################################################
	----------				PAGE RACINE :: secretariat/courrier_add  ----------
	################################################################## */
	
	public function courrier_add() 
	{
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_active'] = 'SecretariatPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Ajouter un courrier reçu';

		$page_data['civilites'] = $this->all_model->get_table('civilite');

		// Effectuer la journalisation
		    $type_action = 'Consultation' ;

		    $action_effectuee = 'Formulaire d\'ajout des courriers' ;

		    $this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue

		$this->render_template('secretariat/courrier_add', $page_data);	
	}

	/* ##################################################################
	----------				PAGE RACINE :: secretariat/courrier_upd  ----------
	################################################################## */
	
	public function courrier_upd($id) 
	{
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_active'] = 'SecretariatPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Modifier un courrier reçu';

		$page_data['civilites'] = $this->all_model->get_table('civilite');

		$page_data['courrier_rech'] = $this->all_model->get_fullrow('courriers','id',$id);

		// Effectuer la journalisation
		    $type_action = 'Consultation' ;

		    $action_effectuee = 'Formulaire de modification du courrier :'.' '.$id ;

		    $this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue

		$this->render_template('secretariat/courrier_upd', $page_data);	
	}
	
}


/* End of file archivage_consultation.php */
/* Location: ./application/controllers/archivage_consultation.php */
	
