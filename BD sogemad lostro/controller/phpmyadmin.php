<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Phpmyadmin extends Admin_Controller {
	
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

			if ($curr_uri_string != 'phpmyadmin') 
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
	----------				PAGE RACINE :: ./facturation hospit  ----------
	################################################################## */
	
	public function index() 
	{
		

		$page_data['bandeau'] = 'PhpMyAdmin Web';
		$page_data['title'] = 'Acces au phpmyadmin';

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_active'] = 'requetePage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Phpmyadmin';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire de recherche avancée PhpMyAdmin' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        $this->render_template('phpmyadmin/phpmyadmin', $page_data);
		
	}
	
	private function _modifier_ligne_phpmyadmin() 
	{
		$table = $this->input->post('table1');
		$table = strtolower($table);
		$id = $this->input->post('id');
		
		$colonnes = $this->all_model->describe_table($table);
		$key_name = $this->all_model->get_primary_key($table);
		
		foreach($colonnes as $champ){
			
			@$$champ['Field'] = $this->input->post($champ['Field']);
			
			/*$data = array(
					'item' => '%'.$title.'%'
			);*/ 
			
			@$data[$champ['Field']]=$$champ['Field'];
			
		}
		//var_dump($data);exit;
		$this->all_model->update_ligne($table, $data, $key_name, $id);
	}
	
	
	
	public function search() 
	{
		

		$page_data['bandeau'] = 'PhpMyAdmin Web';
		$page_data['title'] = 'Acces au phpmyadmin';
		
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_active'] = 'SettingsPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_s_title'] = 'PhpMyAdmin Web';
		
		
		
		// initialisation du validateur du formulaire

	  $this->load->library('form_validation');

	// définition des règles de validation

	   $this->form_validation->set_rules('choix', 'Le choix', 'trim|required|alpha_dash');

	   if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
	   { 
			$flash_feedback = validation_errors();
			$this->session->set_flashdata('error', $flash_feedback);
			redirect('phpmyadmin');
			return FALSE ;
		}
		else
		{
			
			
			//***SCRIPT D ENREGISTREMENT ***//////////////
			@$modif = $this->input->post('modif');
			if(@$modif){
				
				$this->_modifier_ligne_phpmyadmin();
			}
			////************************************//////
			
			
			$choix = $this->input->post('choix');$page_data['choix'] = $choix;	
			switch($choix){
				case '1':
						
				$this->form_validation->set_rules('table1', 'Le nom de la table', 'trim|required|alpha_dash');
				$this->form_validation->set_rules('cle1', 'La valeur à rechercher', 'trim|required');
				if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
				{ 
					$flash_feedback = validation_errors();
					$this->session->set_flashdata('error', $flash_feedback);
					redirect('phpmyadmin');
					return FALSE ;
				}
				else
				{
					
					$table = $this->input->post('table1');
					$table = strtolower($table);
					$page_data['table'] = $table;
					$cle = $this->input->post('cle1');$page_data['cle'] = $cle;
					
					$page_data['colonnes'] = $this->all_model->describe_table($table);

					// Effectuer la journalisation
					$type_action = 'Consultation' ;

					$action_effectuee = 'Résultat d\'une recherche de la valeur'.' '.$cle.' '.'dans la table'.' '.$table ;

					$this->control->journalisation($type_action,$action_effectuee) ;
					
					$result = $this->all_model->phpmyadmin_search_id($table,$cle );
					$page_data['result'] = $result;
				}
					
					break;
				case '2':
						
				$this->form_validation->set_rules('table2', 'Le nom de la table', 'trim|required|alpha_dash');
				$this->form_validation->set_rules('cle2', 'La valeur à rechercher', 'trim|required');
				if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
				{ 
					$flash_feedback = validation_errors();
					$this->session->set_flashdata('error', $flash_feedback);
					redirect('phpmyadmin');
					return FALSE ;
				}
				else
				{
					$table = $this->input->post('table2');
					$table = strtolower($table);
					$page_data['table'] = $table;
					$cle = $this->input->post('cle2');$page_data['cle'] = $cle;
					
					$columns = $this->all_model->describe_table($table);
					$page_data['colonnes']=$columns;
					$first_column = $page_data['colonnes'][0]['Field'];

					// Effectuer la journalisation
					$type_action = 'Consultation' ;

					$action_effectuee = 'Résultat d\'une recherche de la valeur'.' '.$cle.' '.'dans la table'.' '.$table ;

					$this->control->journalisation($type_action,$action_effectuee) ;
					
					$result = $this->all_model->phpmyadmin_search_in_table($table,$first_column,$columns,$cle );
					$page_data['result'] = $result;
					
				}
					
					break;
				case '3':
						
					
				$this->form_validation->set_rules('cle3', 'La valeur à rechercher', 'trim|required');
				if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
				{ 
					$flash_feedback = validation_errors();
					$this->session->set_flashdata('error', $flash_feedback);
					redirect('phpmyadmin');
					return FALSE ;
				}
				else
				{
					
					$cle = $this->input->post('cle3');$page_data['cle'] = $cle;

					// Effectuer la journalisation
					$type_action = 'Consultation' ;

					$action_effectuee = 'Résultat d\'une recherche de la valeur'.' '.$cle.' '.'dans la base de données';

					$this->control->journalisation($type_action,$action_effectuee) ;
					
					$result = $this->all_model->phpmyadmin_search_database($cle);
					$page_data['result'] = $result;
					
					//var_dump($result['colonne']['adherent']);exit;
				}
					
					break;
				case '4':
					
						
					
					break;
					
			}
			
			
			
		}
	
		// affichage de la vue
		$this->render_template('phpmyadmin/phpmyadmin', $page_data);
		
	}
	
	

	public function modifier() 
	{
		
		$page_data['bandeau'] = 'PhpMyAdmin Web';
		$page_data['title'] = 'acces au phpmyadmin';

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_active'] = 'SettingsPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_s_title'] = 'Modification';
		
		$this->load->library('form_validation');
		
		
		$table = $this->input->post('table');$page_data['table'] = $table;
		$cle = $this->input->post('cle');$page_data['cle'] = $cle;
		$choix = $this->input->post('choix');$page_data['choix'] = $choix;
		$id = $this->input->post('id');$page_data['id'] = $id;
		
		
		$page_data['colonnes'] = $this->all_model->describe_table($table);
		$key_name = $this->all_model->get_primary_key($table);
		$page_data['key_name']=$key_name ;
		$result = $this->all_model->get_table_where($table,$key_name, $id);
		$page_data['result'] = $result[0];


		// Effectuer la journalisation
			$type_action = 'Modification' ;

			$action_effectuee = 'Résultat d\'une ligne de données dans la table'.' '.$table ;

			$this->control->journalisation($type_action,$action_effectuee) ;
		
		//$data['page_data'] =$page_data;
		// affichage de la vue
		$this->render_template('phpmyadmin/modifier', $page_data);
		
	}
	
	
	public function supprimer() 
	{
		$output = array('error' => false);
			 
		$this->load->library('form_validation');

		$table_cible = $this->input->post('table_cible');
		$idname = $this->input->post('idname');
		$id = $this->input->post('id');

		// Effectuer la journalisation
		$type_action = 'Suppression' ;

		$action_effectuee = 'Résultat d\'une ligne de données dans la table'.' '.$table ;

		$this->control->journalisation($type_action,$action_effectuee) ;
		
		$this->all_model->delete_lignes($table_cible,$idname, $id);
		$output['message'] = "La ligne a été supprimée avec succès";

		echo json_encode($output);	
	}
	
	
	
	
	
	/* ##################################################################
	----------				PAGE :: ./home/login						  ----------
	################################################################## */
	
	
	
	
}









/* End of file calcul_majoration.php */
    /* Location: ./application/controllers/calcul_majoration.php */
	
