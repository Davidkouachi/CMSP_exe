<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends Admin_Controller {
    
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

			if ($curr_uri_string != 'settings') 
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

	// ENREGISTREMENT D'UN UTILISATEUR

    public function SettingsRegister()
    {
    	

		if(!empty($_POST))
		{

			$data['description'] = $this->input->post('nom_hopital');
            $this->db->where('type' , 'nom_hopital');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('adresse_geo_hopital');
            $this->db->where('type' , 'adresse_geo_hopital');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('adresse_bp_hopital');
            $this->db->where('type' , 'adresse_bp_hopital');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('adresse_email_hopital');
            $this->db->where('type' , 'adresse_email_hopital');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('tel_hopital');
            $this->db->where('type' , 'tel_hopital');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('cel1_hopital');
            $this->db->where('type' , 'cel1_hopital');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('cel2_hopital');
            $this->db->where('type' , 'cel2_hopital');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('monnaie');
            $this->db->where('type' , 'monnaie');
            $this->db->update('settings' , $data);

            /*$data['description'] = $this->input->post('nomlogo');
            $this->db->where('type' , 'logo_hopital');
            $this->db->update('settings' , $data);*/

            $data['description'] = $this->input->post('slogan_hopital');
            $this->db->where('type' , 'slogan_hopital');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('fax_hopital');
            $this->db->where('type' , 'fax_hopital');
            $this->db->update('settings' , $data);
            
            $data['description'] = $this->input->post('disposition_gle');
            $this->db->where('type' , 'layout_select');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('menu_gle');
            $this->db->where('type' , 'sidebar_select');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('couleur_gle');
            $this->db->where('type' , 'skin_colour');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('licence');
            $this->db->where('type' , 'licence');
            $this->db->update('settings' , $data);
        

			$nom_hopital = $this->db->get_where('settings' , array('type'=>'nom_hopital'))->row()->description;

		     $adresse_geo_hopital = $this->db->get_where('settings' , array('type'=>'adresse_geo_hopital'))->row()->description;

		     $adresse_bp_hopital = $this->db->get_where('settings' , array('type'=>'adresse_bp_hopital'))->row()->description;

		     $adresse_email_hopital = $this->db->get_where('settings' , array('type'=>'adresse_email_hopital'))->row()->description;

		     $tel_hopital = $this->db->get_where('settings' , array('type'=>'tel_hopital'))->row()->description;

		     $cel1_hopital = $this->db->get_where('settings' , array('type'=>'cel1_hopital'))->row()->description;

		     $cel2_hopital = $this->db->get_where('settings' , array('type'=>'cel2_hopital'))->row()->description;

		    $monnaie = $this->db->get_where('settings' , array('type'=>'monnaie'))->row()->description;

		    $nomlogo = $this->db->get_where('settings' , array('type'=>'logo_hopital'))->row()->description;

		    $slogan_hopital = $this->db->get_where('settings' , array('type'=>'slogan_hopital'))->row()->description;

		    $fax_hopital = $this->db->get_where('settings' , array('type'=>'fax_hopital'))->row()->description;
		    
		    $layout_select = $this->db->get_where('settings' , array('type'=>'layout_select'))->row()->description;

		    $sidebar_select = $this->db->get_where('settings' , array('type'=>'sidebar_select'))->row()->description;

		    $skin_colour = $this->db->get_where('settings' , array('type'=>'skin_colour'))->row()->description;

		    $licence = $this->db->get_where('settings' , array('type'=>'licence'))->row()->description;

		    $settings_data =  array('nom_hopital' => $nom_hopital,
					'adresse_geo_hopital' => $adresse_geo_hopital,
					'adresse_bp_hopital' => $adresse_bp_hopital,
					'adresse_email_hopital' => $adresse_email_hopital,
					'tel_hopital' => $tel_hopital,
					'cel1_hopital' => $cel1_hopital,
					'cel2_hopital' => $cel2_hopital,
					'monnaie' => $monnaie,
					'nomlogo' => $nomlogo,
					'fax_hopital' => $fax_hopital,
					'slogan_hopital' => $slogan_hopital,
					'layout_select' => $layout_select,
					'sidebar_select' => $sidebar_select,
					'skin_colour' => $skin_colour,
					'licence' => $licence
					);

		    // Effectuer la journalisation
			$type_action = 'Modification' ;

			$action_effectuee = 'Paramètres du système' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$this->session->set_flashdata('SUCCESSMSG', "<h4>Modification des paramètres du système effectuée avec succès !! </h4>");


	        redirect('Settings/SettingsRegister','refresh');
		}
		else
		{	
			$nom_hopital = $this->db->get_where('settings' , array('type'=>'nom_hopital'))->row()->description;

		     $adresse_geo_hopital = $this->db->get_where('settings' , array('type'=>'adresse_geo_hopital'))->row()->description;

		     $adresse_bp_hopital = $this->db->get_where('settings' , array('type'=>'adresse_bp_hopital'))->row()->description;

		     $adresse_email_hopital = $this->db->get_where('settings' , array('type'=>'adresse_email_hopital'))->row()->description;

		     $tel_hopital = $this->db->get_where('settings' , array('type'=>'tel_hopital'))->row()->description;

		     $cel1_hopital = $this->db->get_where('settings' , array('type'=>'cel1_hopital'))->row()->description;

		     $cel2_hopital = $this->db->get_where('settings' , array('type'=>'cel2_hopital'))->row()->description;

		    $monnaie = $this->db->get_where('settings' , array('type'=>'monnaie'))->row()->description;

		    $nomlogo = $this->db->get_where('settings' , array('type'=>'logo_hopital'))->row()->description;

		    $slogan_hopital = $this->db->get_where('settings' , array('type'=>'slogan_hopital'))->row()->description;

		    $fax_hopital = $this->db->get_where('settings' , array('type'=>'fax_hopital'))->row()->description;
		    
		    $layout_select = $this->db->get_where('settings' , array('type'=>'layout_select'))->row()->description;

		    $sidebar_select = $this->db->get_where('settings' , array('type'=>'sidebar_select'))->row()->description;

		    $skin_colour = $this->db->get_where('settings' , array('type'=>'skin_colour'))->row()->description;

		    $licence = $this->db->get_where('settings' , array('type'=>'licence'))->row()->description;

		    $settings_data =  array('nom_hopital' => $nom_hopital,
					'adresse_geo_hopital' => $adresse_geo_hopital,
					'adresse_bp_hopital' => $adresse_bp_hopital,
					'adresse_email_hopital' => $adresse_email_hopital,
					'tel_hopital' => $tel_hopital,
					'cel1_hopital' => $cel1_hopital,
					'cel2_hopital' => $cel2_hopital,
					'monnaie' => $monnaie,
					'nomlogo' => $nomlogo,
					'fax_hopital' => $fax_hopital,
					'slogan_hopital' => $slogan_hopital,
					'layout_select' => $layout_select,
					'sidebar_select' => $sidebar_select,
					'skin_colour' => $skin_colour,
					'licence' => $licence
					);

			$UserProfil = $this->UserModel->getUser_Profil();
			$User_Action = $this->UserModel->getUser_Action();
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['page_libprofil'] = $UniqueProfil;
			
			$page_data['UserProfil'] = $UserProfil;
			$page_data['settings_data'] = $settings_data;
			$page_data['User_Action'] = $User_Action;
			$page_data['page_name'] = 'SettingsRegister';
			$page_data['page_active'] = 'SettingsPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro';
			$page_data['page_s_title'] = 'Modifier les paramettres du système';

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire de modification des paramètres du système' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

	        $this->render_template('kameleon/SettingsRegister', $page_data);
		}
    }

    /***MANAGE NOTICEBOARD, WILL BE SEEN BY ALL ACCOUNTS DASHBOARD**/

	function manage_noticeboard($param1 = '', $param2 = '', $param3 = '')

	{

		/*if ($this->session->userdata('admin_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');*/

		

		if ($param1 == 'create') {

			$data['notice_title']     = $this->input->post('notice_title');

			$data['notice']           = $this->input->post('notice');

			$data['create_timestamp'] = $this->input->post('create_timestamp');

			$this->db->insert('noticeboard', $data);

			$this->session->set_flashdata('SUCCESSMSG', 'Enrégistrement effectué avec succès.');

			

			redirect(base_url() . 'settings/manage_noticeboard', 'refresh');

		}

		if ($param1 == 'edit' && $param2 == 'do_update') {

			$data['notice_title']     = $this->input->post('notice_title');

			$data['notice']           = $this->input->post('notice');

			$data['create_timestamp'] = $this->input->post('create_timestamp');

			$this->db->where('notice_id', $param3);

			$this->db->update('noticeboard', $data);

			$this->session->set_flashdata('SUCCESSMSG', 'Modification effectuée avec succès.');

			

			redirect(base_url() . 'settings/manage_noticeboard', 'refresh');

		} else if ($param1 == 'edit') {

			$page_data['edit_profile'] = $this->db->get_where('noticeboard', array(

				'notice_id' => $param2

			))->result_array();

		}

		if ($param1 == 'delete') {

			$this->db->where('notice_id', $param2);

			$this->db->delete('noticeboard');

			$this->session->set_flashdata('SUCCESSMSG', 'Suppression effectuée avec succès.');

			redirect(base_url() . 'settings/manage_noticeboard', 'refresh');

		}


		$page_data['notices']    = $this->db->get('noticeboard')->result_array();


		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	    $page_data['page_libprofil'] = $UniqueProfil;

		$page_data['page_active'] = "noticeboardPage";

		$page_data['page_s_title'] = 'Page de gestion des notes d\'information';

		$page_data['rapports']    = $this->db->get('rapport')->result_array();

			// affichage de la vue

		$this->render_template('informations/manage_noticeboard', $page_data);

	}
	
}//end of file
	
