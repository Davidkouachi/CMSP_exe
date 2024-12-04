<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gestion_pharmacie extends Admin_Controller {
	
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

			if ($curr_uri_string != 'gestion_pharmacie') 
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
	----------				PAGE RACINE :: ./gestion_pharmacie/servir_medicament				  ----------
	################################################################## */
	
	public function servir_medicament() 
	{
		
	    $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	    $page_data['page_libprofil'] = $UniqueProfil;

		$page_data['bandeau'] = lang('title_home_page');

		$page_data['title'] = lang('title_home_page');

		$page_data['page_active'] = "servir_medicamentPage";

		$page_data['page_s_title'] = 'Page de gestion des prescriptions';

		// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Page de gestion des prescriptions' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

		$this->render_template('pharmacie/servir_medicament', $page_data);

		
	}

	/* ##################################################################
	----------				PAGE RACINE :: ./gestion_pharmacie/attribution_lit				  ----------
	################################################################## */
	
	public function attribution_lit() 
	{
		
	    $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	    $page_data['page_libprofil'] = $UniqueProfil;

		$page_data['bandeau'] = lang('title_home_page');

		$page_data['title'] = lang('title_home_page');

		$page_data['page_active'] = "litPage";

		$page_data['page_s_title'] = 'Page de gestion des attributions de lits d\'hospit';

		// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Page de gestion des attributions de lits' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

		$this->render_template('kameleon/attribution_lit', $page_data);

		
	}

	/***MANAGE PRESCRIPTIONS******/

	function manage_prescription($param1 = '', $param2 = '', $param3 = '')

	{

		/*if ($this->session->userdata('pharmacist_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');*/

		

		if ($param1 == 'create') {

			$data['doctor_id']                  = $this->input->post('doctor_id');

			$data['patient_id']                 = $this->input->post('patient_id');

			$data['case_history']               = $this->input->post('case_history');

			$data['medication']                 = $this->input->post('medication');

			$data['medication_from_pharmacist'] = $this->input->post('medication_from_pharmacist');

			$data['description']                = $this->input->post('description');

			$data['creation_timestamp']                = $this->input->post('creation_timestamp');

			$this->db->insert('prescription', $data);

			// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Prescription' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			$this->session->set_flashdata('SUCCESSMSG', 'Enrégistrement effectué avec succès.');

			redirect(base_url() . 'gestion_pharmacie/manage_prescription', 'refresh');

		}

		if ($param1 == 'edit' && $param2 == 'do_update') {

			$data['doctor_id']                  = $this->input->post('doctor_id');

			$data['patient_id']                 = $this->input->post('patient_id');

			$data['case_history']               = $this->input->post('case_history');

			$data['medication']                 = $this->input->post('medication');

			$data['medication_from_pharmacist'] = $this->input->post('medication_from_pharmacist');

			$data['description']                = $this->input->post('description');

			$data['creation_timestamp']                = $this->input->post('creation_timestamp');

			

			$this->db->where('prescription_id', $param3);

			$this->db->update('prescription', $data);

			// Effectuer la journalisation
		        $type_action = 'Modification' ;

		        $action_effectuee = 'Prescription' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			$this->session->set_flashdata('SUCCESSMSG', 'Modification effectuée avec succès.');

			redirect(base_url() . 'gestion_pharmacie/manage_prescription', 'refresh');

			

		} else if ($param1 == 'edit') {

			$page_data['edit_profile'] = $this->db->get_where('prescription', array(

				'prescription_id' => $param2

			))->result_array();

		}

		if ($param1 == 'delete') {

			$this->db->where('prescription_id', $param2);

			$this->db->delete('prescription');

			// Effectuer la journalisation
		        $type_action = 'Suppression' ;

		        $action_effectuee = 'Prescription' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			$this->session->set_flashdata('SUCCESSMSG', 'Suppression effectuée avec succès.');

			redirect(base_url() . 'gestion_pharmacie/manage_prescription', 'refresh');

		}

		$user_codeperso = $this->session->userdata('code_user_perso');

        $user_profil = $this->session->userdata('user_profil');

        if($user_profil == 3)
        {
            if(!empty($user_codeperso))
            {
                $page_data['prescriptions'] = $this->all_model->get_fullrow_bis('prescription','doctor_id',$user_codeperso);
            }
            else
            {
                $page_data['prescriptions'] = '';
            }
        }
        else
        {
        	$page_data['prescriptions'] = $this->db->get('prescription')->result_array();
        }

		//$page_data['prescriptions'] = $this->db->get('prescription')->result_array();

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	    $page_data['page_libprofil'] = $UniqueProfil;

		$page_data['page_active'] = "servir_medicamentPage";

		$page_data['page_s_title'] = 'Page de gestion des prescriptions';

		// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Page de gestion des prescriptions' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

		$this->render_template('pharmacie/manage_prescription', $page_data);

	}
		
}

/* End of file rapport_divers.php */
/* Location: ./application/controllers/rapport_divers.php */