<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pharmacie extends Admin_Controller {
    
    
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

			if ($curr_uri_string != 'pharmacie') 
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
		
    public function ProduitRegister($param1 = '', $param2 = '', $param3 = '')
    {

		/*if ($this->session->userdata('pharmacist_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');*/

		

		if ($param1 == 'create') {

			$data['name']                  = $this->input->post('name');

			$data['medicine_category_id']  = $this->input->post('medicine_category_id');

			$data['description']           = $this->input->post('description');

			$data['price']                 = $this->input->post('price');

			$data['manufacturing_company'] = $this->input->post('manufacturing_company');

			$data['status']                = $this->input->post('status');

			// Effectuer la journalisation
			$type_action = 'Ajout' ;

			$action_effectuee = 'Médicament' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$this->db->insert('medicine', $data);

			$this->session->set_flashdata('SUCCESSMSG', 'Enrégistrement du médicament effectué avec succès.');

			redirect(base_url() . 'Pharmacie/manage_medicine', 'refresh');

		}

		if ($param1 == 'edit' && $param2 == 'do_update') {

			$data['name']                  = $this->input->post('name');

			$data['medicine_category_id']  = $this->input->post('medicine_category_id');

			$data['description']           = $this->input->post('description');

			$data['price']                 = $this->input->post('price');

			$data['manufacturing_company'] = $this->input->post('manufacturing_company');

			$data['status']                = $this->input->post('status');

			// Effectuer la journalisation
			$type_action = 'Modification' ;

			$action_effectuee = 'Médicament' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$this->db->where('medicine_id', $param3);

			$this->db->update('medicine', $data);

			$this->session->set_flashdata('SUCCESSMSG', 'Modification du médicament effectuée avec succès.');

			redirect(base_url() . 'Pharmacie/manage_medicine', 'refresh');

			

		} else if ($param1 == 'edit') {

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire de modification de médicament' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$page_data['edit_profile'] = $this->db->get_where('medicine', array(

				'medicine_id' => $param2

			))->result_array();

		}

		if ($param1 == 'delete') {

			// Effectuer la journalisation
			$type_action = 'Suppression' ;

			$action_effectuee = 'Médicament' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$this->db->where('medicine_id', $param2);

			$this->db->delete('medicine');

			$this->session->set_flashdata('SUCCESSMSG', 'Suppression du médicament effectuée avec succès.');

			redirect(base_url() . 'Pharmacie/manage_medicine', 'refresh');

		}

		$page_data['page_name']  = 'manage_medicine';

		$page_data['medicines']  = $this->db->get('medicine')->result_array();

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        $page_data['page_libprofil'] = $UniqueProfil;
		
		$page_data['page_active'] = 'ProduitPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Gestion des médicaments';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire d\'ajout de médicaments' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue

        $this->render_template('pharmacie/manage_medicine', $page_data);

	
    }
	
	public function CmdproduitRegister()
    {
		if(!empty($_POST))
		{
		//$motpass  = sha1($motpasse);
			$data =  array('CodUser' => $this->input->post('coduser'),
					'motpasse' => $this->input->post('motpasse'),
					'nom' => $this->input->post('nom'),
					'datpassconnexion' => $this->input->post('datpassconnexion'),
					'profil' => $this->input->post('profil'),
					'acces' => $this->input->post('acces'),
					'actif' => $this->input->post('actif'),
					'contact' => $this->input->post('contact'),
					'id' => $this->input->post('id'),
					'mdpadmin' => $this->input->post('mdpadmin'),
					'activite' => $this->input->post('activite'),
					'datedelaiactivation' => date('Y-m-d H:i:s'),
					);
			$this->CmdproduitModel->CmdproduitRegister($data);
			$this->session->set_flashdata('SUCCESSMSG', "Enregistrement effectué avec succès!!");
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'CmdproduitRegister';
			$page_data['page_active'] = 'ProduitPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter une commande';

			// affichage de la vue

        	$this->render_template('kameleon/CmdproduitRegister', $page_data);
		}
		else
		{
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'CmdproduitRegister';
			$page_data['page_active'] = 'ProduitPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter une commande';

			// affichage de la vue

        	$this->render_template('kameleon/CmdproduitRegister', $page_data);
		}
    }

	public function LivproduitRegister()
	{
		if(!empty($_POST))
		{
			//$motpass  = sha1($motpasse);
			$data =  array('CodUser' => $this->input->post('coduser'),
				'motpasse' => $this->input->post('motpasse'),
				'nom' => $this->input->post('nom'),
				'datpassconnexion' => $this->input->post('datpassconnexion'),
				'profil' => $this->input->post('profil'),
				'acces' => $this->input->post('acces'),
				'actif' => $this->input->post('actif'),
				'contact' => $this->input->post('contact'),
				'id' => $this->input->post('id'),
				'mdpadmin' => $this->input->post('mdpadmin'),
				'activite' => $this->input->post('activite'),
				'datedelaiactivation' => date('Y-m-d H:i:s'),
			);
			$this->LivproduitModel->LivproduitRegister($data);
			$this->session->set_flashdata('SUCCESSMSG', "Enregistrement effectué avec succès!!");
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'LivproduitRegister';
			$page_data['page_active'] = 'ProduitPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter une livraison';

			// affichage de la vue

        	$this->render_template('kameleon/LivproduitRegister', $page_data);
		}
		else
		{
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'LivproduitRegister';
			$page_data['page_active'] = 'ProduitPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter une commande';

			// affichage de la vue

        	$this->render_template('kameleon/LivproduitRegister', $page_data);
		}
	}
	public function StockRegister()
	{
		if(!empty($_POST))
		{
			//$motpass  = sha1($motpasse);
			$data =  array('CodUser' => $this->input->post('coduser'),
				'motpasse' => $this->input->post('motpasse'),
				'nom' => $this->input->post('nom'),
				'datpassconnexion' => $this->input->post('datpassconnexion'),
				'profil' => $this->input->post('profil'),
				'acces' => $this->input->post('acces'),
				'actif' => $this->input->post('actif'),
				'contact' => $this->input->post('contact'),
				'id' => $this->input->post('id'),
				'mdpadmin' => $this->input->post('mdpadmin'),
				'activite' => $this->input->post('activite'),
				'datedelaiactivation' => date('Y-m-d H:i:s'),
			);
			$this->StockModel->StockRegister($data);
			$this->session->set_flashdata('SUCCESSMSG', "Enregistrement effectué avec succès!!");
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'StockRegister';
			$page_data['page_active'] = 'ProduitPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'gestion sctock de produits';

			// affichage de la vue

        	$this->render_template('kameleon/StockRegister', $page_data);
		}
		else
		{
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'StockRegister';
			$page_data['page_active'] = 'ProduitPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'gestion sctock de produits';
			
			// affichage de la vue

        	$this->render_template('kameleon/StockRegister', $page_data);
		}
	}

		/****MANAGE MEDICINE CATEGORIES*********/

	function manage_medicine_category($param1 = '', $param2 = '', $param3 = '')

	{

		/*if ($this->session->userdata('pharmacist_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');*/

		

		if ($param1 == 'create') {

			$data['name']        = $this->input->post('name');

			$data['description'] = $this->input->post('description');

			// Effectuer la journalisation
			$type_action = 'Ajout' ;

			$action_effectuee = 'Catégorie de médicament' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$this->db->insert('medicine_category', $data);

			$this->session->set_flashdata('SUCCESSMSG', 'Ajout de la catégorie de médicament effectué avec succès.');

			redirect(base_url() . 'Pharmacie/manage_medicine_category', 'refresh');

		}

		if ($param1 == 'edit' && $param2 == 'do_update') {

			$data['name']        = $this->input->post('name');

			$data['description'] = $this->input->post('description');

			// Effectuer la journalisation
			$type_action = 'Modification' ;

			$action_effectuee = 'Catégorie de médicament'.' '.$param3 ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$this->db->where('medicine_category_id', $param3);

			$this->db->update('medicine_category', $data);

			$this->session->set_flashdata('SUCCESSMSG', 'Modification de la catégorie de médicament effectuée avec succès.');

			redirect(base_url() . 'Pharmacie/manage_medicine_category', 'refresh');

			

		} else if ($param1 == 'edit') {

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire de modification de la catégorie de médicament'.' '.$param2 ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$page_data['edit_profile'] = $this->db->get_where('medicine_category', array(

				'medicine_category_id' => $param2

			))->result_array();

		}

		if ($param1 == 'delete') {

			// Effectuer la journalisation
			$type_action = 'Suppression' ;

			$action_effectuee = 'Catégorie de médicament'.' '.$param2 ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$this->db->where('medicine_category_id', $param2);

			$this->db->delete('medicine_category');

			$this->session->set_flashdata('SUCCESSMSG', 'Suppression de la catégorie de médicament effectuée avec succès.');

			redirect(base_url() . 'Pharmacie/manage_medicine_category', 'refresh');

		}


		$page_data['medicine_categories'] = $this->db->get('medicine_category')->result_array();

		$page_data['page_name']  = 'manage_medicine_category';

		$page_data['medicines']  = $this->db->get('medicine')->result_array();

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        $page_data['page_libprofil'] = $UniqueProfil;
		
		$page_data['page_active'] = 'categorie_medicamentPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Gestion des catégories de médicaments';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire d\'ajout de catégories de médicaments' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue

        $this->render_template('pharmacie/manage_medicine_category', $page_data);

	}

	

	/****MANAGE MEDICINES CATEGORY WISE*********/

	function manage_medicine($param1 = '', $param2 = '', $param3 = '')

	{

		/*if ($this->session->userdata('pharmacist_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');*/

		

		if ($param1 == 'create') {

			$data['name']                  = $this->input->post('name');

			$data['medicine_category_id']  = $this->input->post('medicine_category_id');

			$data['description']           = $this->input->post('description');

			$data['price']                 = $this->input->post('price');

			$data['manufacturing_company'] = $this->input->post('manufacturing_company');

			$data['status']                = $this->input->post('status');

			// Effectuer la journalisation
			$type_action = 'Ajout' ;

			$action_effectuee = 'Médicament' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
			

			$this->db->insert('medicine', $data);

			$this->session->set_flashdata('SUCCESSMSG', 'Enrégistrement du médicament effectué avec succès.');

			redirect(base_url() . 'Pharmacie/manage_medicine', 'refresh');

		}

		if ($param1 == 'edit' && $param2 == 'do_update') {

			$data['name']                  = $this->input->post('name');

			$data['medicine_category_id']  = $this->input->post('medicine_category_id');

			$data['description']           = $this->input->post('description');

			$data['price']                 = $this->input->post('price');

			$data['manufacturing_company'] = $this->input->post('manufacturing_company');

			$data['status']                = $this->input->post('status');

			// Effectuer la journalisation
			$type_action = 'Modification' ;

			$action_effectuee = 'Médicament'.' '.$param3 ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			

			$this->db->where('medicine_id', $param3);

			$this->db->update('medicine', $data);

			$this->session->set_flashdata('SUCCESSMSG', 'Modification du médicament effectuée avec succès.');

			redirect(base_url() . 'Pharmacie/manage_medicine', 'refresh');

			

		} else if ($param1 == 'edit') {

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire de modification du médicament'.' '.$param2 ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$page_data['edit_profile'] = $this->db->get_where('medicine', array(

				'medicine_id' => $param2

			))->result_array();

		}

		if ($param1 == 'delete') {

			// Effectuer la journalisation
			$type_action = 'Suppression' ;

			$action_effectuee = 'Médicament'.' '.$param2 ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$this->db->where('medicine_id', $param2);

			$this->db->delete('medicine');

			$this->session->set_flashdata('SUCCESSMSG', 'Suppression du médicament effectuée avec succès.');

			redirect(base_url() . 'Pharmacie/manage_medicine', 'refresh');

		}

		$page_data['page_name']  = 'manage_medicine';

		$page_data['medicines']  = $this->db->get('medicine')->result_array();

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        $page_data['page_libprofil'] = $UniqueProfil;
		
		$page_data['page_active'] = 'gerer_medicamentPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Gestion des médicaments';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Liste des médicaments' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue

        $this->render_template('pharmacie/manage_medicine', $page_data);

	}

}
	
