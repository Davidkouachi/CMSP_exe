<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rapport_divers extends Admin_Controller {
	
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

			redirect('home/login','refresh');			
		}

		$curr_uri_string = $this->uri->segment(1);

		if ($curr_uri_string != 'rapport_divers') 
		{
			$flash_feedback = "Une erreur est survenue dans le processus. Veuillez vous reconnecter.";

			$this->session->set_flashdata('warning', $flash_feedback);

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
		
	/* ##################################################################
	----------				PAGE RACINE :: ./rapport_divers					  ----------
	################################################################## */
	
	public function index() 
	{
	    $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	    $page_data['page_libprofil'] = $UniqueProfil;

		$page_data['bandeau'] = lang('title_home_page');

		$page_data['title'] = lang('title_home_page');

		$page_data['page_active'] = "rapportPage";

		$page_data['page_s_title'] = 'Page de gestion des rapports';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Page de gestion des rapports' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

		$this->render_template('kameleon/rapport_divers', $page_data);

		
	}

	/* ##################################################################
	----------				PAGE RACINE :: ./rapport_divers/rdv				  ----------
	################################################################## */
	
	public function rdv() 
	{
		
	    $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	    $page_data['page_libprofil'] = $UniqueProfil;

		$page_data['bandeau'] = lang('title_home_page');

		$page_data['title'] = lang('title_home_page');

		$page_data['page_active'] = "rdvPage";

		$page_data['page_s_title'] = 'Page de gestion des rendez-vous';

		$page_data['rapports']    = $this->db->get('rapport')->result_array();

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Page de gestion des rendez-vous' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

		$this->render_template('kameleon/rdv_visite', $page_data);

		
	}

	/* ##################################################################
	----------				PAGE RACINE :: ./rapport_divers/rdv				  ----------
	################################################################## */
	
	public function attribution_lit() 
	{
		
	    $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	    $page_data['page_libprofil'] = $UniqueProfil;

		$page_data['bandeau'] = lang('title_home_page');

		$page_data['title'] = lang('title_home_page');

		$page_data['page_active'] = "InfraHospitPage";

		$page_data['page_s_title'] = 'Page de gestion des attributions de lits d\'hospit';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Page de gestion des attributions de lits d\'admission' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

		$this->render_template('kameleon/attribution_lit', $page_data);

		
	}

	/* ##################################################################
	----------				PAGE RACINE :: ./rapport_divers/manage_report				  ----------
	################################################################## */

	public function manage_report($param1 = '',$param2 = '')

	{
/*
		if ($this->session->userdata('nurse_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');

		*/

		//create a new report baby birth,patient death, operation , other types

		if ($param1 == 'create') {

			$data['type']        = $this->input->post('type');

			$data['description'] = $this->input->post('description');

			$data['timestamp']   = strtotime(date('Y-m-d') . ' ' . date('H:i:s'));

			$data['doctor_id']   = $this->input->post('doctor_id');

			$data['patient_id']  = $this->input->post('patient_id');

			$this->db->insert('rapport', $data);

			$flash_feedback = "Opération effectuée avec succès.";

			// Effectuer la journalisation
			$type_action = 'Ajout' ;

			$action_effectuee = 'Rapport' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$this->session->set_flashdata('success', $flash_feedback);

			redirect(base_url() . 'rapport_divers', 'refresh');

		}

		if ($param1 == 'delete') {

			$this->db->where('report_id', $param2);

			$this->db->delete('rapport');

			// Effectuer la journalisation
			$type_action = 'Suppression' ;

			$action_effectuee = 'Rapport' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$flash_feedback = "Suppression effectuée avec succès.";

			$this->session->set_flashdata('success', $flash_feedback);

			redirect(base_url() . 'rapport_divers', 'refresh');

		}
		/*
		$page_data['page_name']  = 'manage_report';

		$page_data['page_title'] = get_phrase('manage_report');

		//$page_data['reports']    = $this->db->get('rapport')->result_array();

		$this->load->view('index', $page_data);*/

	}

	/* ##################################################################
	----------				PAGE RACINE :: ./rapport_divers/manage_appointment				  ----------
	################################################################## */

	public function manage_appointment($param1 = '', $param2 = '', $param3 = '')

	{

		if ($param1 == 'create') {

			$data['doctor_id']             = $this->input->post('doctor_id');

			$data['description']             = $this->input->post('description');

			$data['patient_id']            = $this->input->post('patient_id');

			$data['appointment_timestamp'] = $this->input->post('appointment_timestamp');

			$this->db->insert('rendez_vous', $data);

			// Effectuer la journalisation
			$type_action = 'Ajout' ;

			$action_effectuee = 'rendez_vous' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			//$this->session->set_flashdata('flash_message', get_phrase('appointment_created'));

			redirect(base_url() . 'rapport_divers/rdv', 'refresh');

		}

		if ($param1 == 'edit' && $param2 == 'do_update') {

			$data['doctor_id']             = $this->input->post('doctor_id');

			$data['patient_id']            = $this->input->post('patient_id');

			$data['appointment_timestamp'] = $this->input->post('appointment_timestamp');

			$this->db->where('appointment_id', $param3);

			$this->db->update('rendez_vous', $data);

			// Effectuer la journalisation
			$type_action = 'Modification' ;

			$action_effectuee = 'Rendez-vous' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			//$this->session->set_flashdata('flash_message', get_phrase('appointment_updated'));

			redirect(base_url() . 'rapport_divers/rdv', 'refresh');

			

		} else if ($param1 == 'edit') {

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire de modification de rendez-vous' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$page_data['edit_profile'] = $this->db->get_where('rendez_vous', array(

				'appointment_id' => $param2

			))->result_array();

		}

		if ($param1 == 'delete') {

			$this->db->where('appointment_id', $param2);

			$this->db->delete('rendez_vous');

			// Effectuer la journalisation
			$type_action = 'Suppression' ;

			$action_effectuee = 'Rendez-vous' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			//$this->session->set_flashdata('flash_message', get_phrase('appointment_deleted'));

			redirect(base_url() . 'rapport_divers/rdv', 'refresh');

		}

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	    $page_data['page_libprofil'] = $UniqueProfil;

		$page_data['bandeau'] = lang('title_home_page');

		$page_data['title'] = lang('title_home_page');

		$page_data['page_active'] = "rdvPage";

		$page_data['page_s_title'] = 'Page de gestion des rendez-vous';

		$page_data['rapports']    = $this->db->get('rapport')->result_array();

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Page de gestion des rendez-vous' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

		$this->render_template('kameleon/rdv_visite', $page_data);

	}

	/* ##################################################################
	----------				PAGE RACINE :: ./rapport_divers/manage_bed_allotment				  ----------
	################################################################## */

	public function manage_bed_allotment($param1 = '', $param2 = '', $param3 = '')

	{
		//create a new allotment only in available / unalloted beds. beds can be ward,cabin,icu,other types

		if ($param1 == 'create') {

			$data['bed_id']              = $this->input->post('bed_id');

			$data['patient_id']          = $this->input->post('patient_id');

			$data['allotment_timestamp'] = $this->input->post('allotment_timestamp');

			$data['discharge_timestamp'] = $this->input->post('discharge_timestamp');

			$this->db->insert('bed_allotment', $data);

			// Effectuer la journalisation
			$type_action = 'Ajout' ;

			$action_effectuee = 'Attribution de lits d\'admission' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			//$this->session->set_flashdata('flash_message', get_phrase('bed_alloted'));

			redirect(base_url() . 'rapport_divers/attribution_lit', 'refresh');

		}

		if ($param1 == 'edit' && $param2 == 'do_update') {

			$data['bed_id']              = $this->input->post('bed_id');

			$data['patient_id']          = $this->input->post('patient_id');

			$data['allotment_timestamp'] = $this->input->post('allotment_timestamp');

			$data['discharge_timestamp'] = $this->input->post('discharge_timestamp');

			$this->db->where('bed_id', $param3);

			$this->db->update('bed_allotment', $data);

			// Effectuer la journalisation
			$type_action = 'Modification' ;

			$action_effectuee = 'Attribution de lit d\'admission' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			//$this->session->set_flashdata('flash_message', get_phrase('bed_allotment_updated'));

			redirect(base_url() . 'rapport_divers/attribution_lit', 'refresh');

			

		} else if ($param1 == 'edit') {

			// Effectuer la journalisation
			$type_action = 'Formulaire' ;

			$action_effectuee = 'Modification d\'attribution de lits d\'admission' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$page_data['edit_profile'] = $this->db->get_where('bed_allotment', array(

				'bed_allotment_id' => $param2

			))->result_array();

		}

		if ($param1 == 'delete') {

			// Effectuer la journalisation
			$type_action = 'Suppression' ;

			$action_effectuee = 'Attribution de lit d\'admission' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$this->db->where('bed_allotment_id', $param2);

			$this->db->delete('bed_allotment');

			//$this->session->set_flashdata('flash_message', get_phrase('bed_allotment_deleted'));

			redirect(base_url() . 'rapport_divers/attribution_lit', 'refresh');

		}

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	    $page_data['page_libprofil'] = $UniqueProfil;

		$page_data['bandeau'] = lang('title_home_page');

		$page_data['title'] = lang('title_home_page');

		$page_data['page_active'] = "litPage";

		$page_data['page_s_title'] = 'Page de gestion des attributions de lits d\'admission';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Page de gestion des attributions de lits d\'admission' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

		$this->render_template('kameleon/attribution_lit', $page_data);

	}

	/*****LIST OF BED, MANAGE THIER TYPES********/

	function manage_bed($param1 = '', $param2 = '', $param3 = '')

	{

		if ($param1 == 'create') {

			$data['bed_number']  = $this->input->post('bed_number');

			$data['type']        = $this->input->post('type');

			$data['description'] = $this->input->post('description');

			$data['chambre_id'] = $this->input->post('chambre_id');

			$this->db->insert('bed', $data);

			// Effectuer la journalisation
			$type_action = 'Ajout' ;

			$action_effectuee = 'Lit d\'admission' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$this->session->set_flashdata('flash_message', '');

			redirect(base_url() . 'rapport_divers/manage_bed', 'refresh');

		}

		if ($param1 == 'edit' && $param2 == 'do_update') {

			$data['bed_number']  = $this->input->post('bed_number');

			$data['type']        = $this->input->post('type');

			$data['status']      = $this->input->post('status');

			$data['description'] = $this->input->post('description');

			$data['chambre_id'] = $this->input->post('chambre_id');


			$this->db->where('bed_id', $param3);

			$this->db->update('bed', $data);

			// Effectuer la journalisation
			$type_action = 'Modification' ;

			$action_effectuee = 'Lit d\'admission' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$this->session->set_flashdata('flash_message', 'account_updated');

			redirect(base_url() . 'rapport_divers/manage_bed', 'refresh');

			

		} else if ($param1 == 'edit') {

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire de modification de lit d\'admission' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$page_data['edit_profile'] = $this->db->get_where('bed', array(

				'bed_id' => $param2

			))->result_array();

		}

		if ($param1 == 'view_bed_history') {

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Historique lit d\'admission' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$page_data['view_bed_history_id'] = $this->db->get_where('bed_allotment', array(

				'bed_id' => $param2

			))->result_array();

		}

		if ($param1 == 'delete') {

			$this->db->where('bed_id', $param2);

			$this->db->delete('bed');

			// Effectuer la journalisation
			$type_action = 'Suppression' ;

			$action_effectuee = 'Lit d\'admission' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$this->session->set_flashdata('flash_message', 'account_deleted');

			redirect(base_url() . 'rapport_divers/manage_bed', 'refresh');

		}

		$page_data['page_name']  = 'manage_bed';

		$page_data['page_title'] = 'manage_bed';

		$page_data['beds']       = $this->db->get('bed')->result_array();

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	    $page_data['page_libprofil'] = $UniqueProfil;

		$page_data['bandeau'] = lang('title_home_page');

		$page_data['title'] = lang('title_home_page');

		$page_data['page_active'] = "InfraHospitPage";

		$page_data['page_s_title'] = 'Page de gestion des lits d\'hospitalisation';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Page de gestion des lit d\'admission' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

		$this->render_template('kameleon/manage_bed', $page_data);

	}

	/*****LIST OF BED, MANAGE THIER TYPES********/

	function manage_bedroom($param1 = '', $param2 = '', $param3 = '')

	{

		if ($param1 == 'create') {

			$data['nomchambre']  = $this->input->post('nomchambre');

			$data['prixchambre']        = $this->input->post('prixchambre');

			$data['nbredelit'] = $this->input->post('nbredelit');

			$this->db->insert('chambrehospit', $data);

			// Effectuer la journalisation
			$type_action = 'Ajout' ;

			$action_effectuee = 'Chambre d\'admission' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$this->session->set_flashdata('flash_message', '');

			redirect(base_url() . 'rapport_divers/manage_bedroom', 'refresh');

		}

		if ($param1 == 'edit' && $param2 == 'do_update') {

			$data['nomchambre']  = $this->input->post('nomchambre');

			$data['prixchambre']        = $this->input->post('prixchambre');

			$data['nbredelit']      = $this->input->post('nbredelit');

			// Effectuer la journalisation
			$type_action = 'Modification' ;

			$action_effectuee = 'Chambre d\'admission' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$this->db->where('codechbre', $param3);

			$this->db->update('chambrehospit', $data);

			$this->session->set_flashdata('flash_message', 'account_updated');

			redirect(base_url() . 'rapport_divers/manage_bedroom', 'refresh');

			

		} else if ($param1 == 'edit') {

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire de modification de chambre d\'admission' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$page_data['edit_profile'] = $this->db->get_where('chambrehospit', array(

				'codechbre' => $param2

			))->result_array();

		}

		if ($param1 == 'view_bed_history') {

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Histrorique chambre d\'admission' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$page_data['view_bed_history_id'] = $this->db->get_where('bed_allotment', array(

				'bed_id' => $param2

			))->result_array();

		}

		if ($param1 == 'delete') {

			$this->db->where('codechbre', $param2);

			$this->db->delete('chambrehospit');

			// Effectuer la journalisation
			$type_action = 'Suppression' ;

			$action_effectuee = 'Chambre d\'admission' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$this->session->set_flashdata('flash_message', 'account_deleted');

			redirect(base_url() . 'rapport_divers/manage_bed', 'refresh');

		}

		$page_data['page_name']  = 'manage_bedroom';

		$page_data['page_title'] = 'manage_bedroom';

		$page_data['bedroom']       = $this->db->get('chambrehospit')->result_array();

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	    $page_data['page_libprofil'] = $UniqueProfil;

		$page_data['bandeau'] = lang('title_home_page');

		$page_data['title'] = lang('title_home_page');

		$page_data['page_active'] = "InfraHospitPage";

		$page_data['page_s_title'] = 'Page de gestion des chambres d\'hospitalisation';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Page de gestion des chambres d\'admission' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

		$this->render_template('kameleon/manage_bedroom', $page_data);

	}
		
}

/* End of file rapport_divers.php */
/* Location: ./application/controllers/rapport_divers.php */