<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {
	
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

			if ($curr_uri_string != 'admin') 
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

	/*****BACKUP / RESTORE / DELETE DATA PAGE**********/

	function backup_restore($operation = '', $type = '')

	{

		if ($operation == 'create') {

			$this->admin_model->create_backup($type);

		}

		if ($operation == 'restore') {

			$this->admin_model->restore_backup();

			redirect(base_url() . 'admin/backup_restore/', 'refresh');

		}

		if ($operation == 'delete') {

			$this->admin_model->truncate($type);

			redirect(base_url() . 'admin/backup_restore/', 'refresh');

		}

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	    $page_data['page_libprofil'] = $UniqueProfil;

		$page_data['bandeau'] = lang('title_home_page');

		$page_data['title'] = lang('title_home_page');

		$page_data['page_active'] = "SettingsPage";

		$page_data['page_s_title'] = 'Page de gestion de la base de données';

			// affichage de la vue

		$this->render_template('admin/backup_restore', $page_data);

	}


	
	/* ##################################################################
	----------				PAGE :: ./admin/index					  ----------
	################################################################## */
	public function users_online() 
	{
		/*if(!in_array('viewGroupUser', $this->permission)) {

			$flash_feedback = "Désolé ! Vous ne disposez pas des droits requits pour affiche la liste des profils d'utilisateur." ;

			$this->session->set_flashdata('error', $flash_feedback);

			redirect('home', 'refresh');
		}*/

			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

            $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_active'] = 'MouchardPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Liste des utilisateurs en ligne';

			$page_data['infos_session'] = $this->all_model->get_table('session') ;

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Liste des utilisateurs connectés' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			
				// affichage de la vue

			$this->render_template('admin/user_on_line', $page_data);
	}

	/* ##################################################################
	----------				PAGE :: ./admin/user_role					  ----------
	################################################################## */
	public function user_role() 
	{
		if(!in_array('viewGroupUser', $this->permission)) {

			$flash_feedback = "Désolé ! Vous ne disposez pas des droits requits pour affiche la liste des profils d'utilisateur." ;

			$this->session->set_flashdata('error', $flash_feedback);

			redirect('home', 'refresh');
		}

			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

            $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_active'] = 'SecurityPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Lister les profils d\'utilisateurs';

			// acquisition de la liste des utilisateurs
			$page_data['users_profile'] = $this->all_model->get_table('profile');

			// Effectuer la journalisation

		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Liste des profils utilisateurs' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			
				// affichage de la vue

			$this->render_template('user-role/groupe_user_liste', $page_data);

	}

	/* ##################################################################
	----------				PAGE :: ./admin/add_user_group					  ----------
	################################################################## */
	public function add_user_group() 
	{
		if(!in_array('createGroupUser', $this->permission)) {

			$flash_feedback = "Désolé ! Vous ne disposez pas des droits requits pour créer un profil d'utilisateur." ;

			$this->session->set_flashdata('error', $flash_feedback);

			redirect('home', 'refresh');
		}

		 
			$page_data['bandeau'] = 'Users';
			$page_data['title'] = '';

			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

            $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_active'] = 'SecurityPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Créer un profil d\'utilisateurs';

			// acquisition de la liste des utilisateurs
			$page_data['users_group'] = $this->all_model->get_table('profile');

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire d\'ajout de profil utilisateur' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			
				// affichage de la vue

			$this->render_template('user-role/user-role', $page_data);
		
	}

	/* ##################################################################
	----------				PAGE :: ./admin/create_user_group					  ----------
	################################################################## */

	public function create_user_group()
	{

		if(!in_array('createGroupUser', $this->permission)) {

			$flash_feedback = "Désolé ! Vous ne disposez pas des droits requits pour créer un profil d'utilisateur." ;

			$this->session->set_flashdata('error', $flash_feedback);

			redirect('home', 'refresh');
		}

		$this->form_validation->set_rules('userprofile_name', 'Nom du profile', 'required');

        if ($this->form_validation->run() == TRUE) {
            // true case
            $permission = serialize($this->input->post('permission'));
            
        	$data = array(
	        		'libprofile' => $this->input->post('userprofile_name'),
	        		'user_profil_permission' => $permission
	        	);

        	$table = 'profile';

        	$create = $this->all_model->add_ligne_with_return_id($table, $data) ;

        	if($create == true) {

        		// Effectuer la journalisation
			        $type_action = 'Ajout' ;

			        $action_effectuee = 'Profil utilisateur' ;

			        $this->control->journalisation($type_action,$action_effectuee) ;

        		$this->session->set_flashdata('success', 'Enrégistrement effectué avec succès.');
        		redirect('admin/user_role', 'refresh');
        	}
        	else {
        		$this->session->set_flashdata('error', 'Une erreur est survenue !!');
        		redirect('admin/add_user_group', 'refresh');
        	}
        }
        else {
            // false case
            $page_data['bandeau'] = 'User profile';

            $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

            $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_active'] = 'SecurityPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Créer un profil d\'utilisateurs';

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire d\'ajout de profil utilisateur' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			
				// affichage de la vue

			$this->render_template('user-role/user-role', $page_data);
        }	
	}

	/* ##################################################################
	----------				PAGE :: ./admin/edit_user_group					  ----------
	################################################################## */


	public function edit_user_group($id = null)
	{
		if(!in_array('updateGroupUser', $this->permission)) {

			$flash_feedback = "Désolé ! Vous ne disposez pas des droits requits pour modifier un profil d'utilisateur." ;

			$this->session->set_flashdata('error', $flash_feedback);

			redirect('home', 'refresh');
		}

		if($id) {

			$this->form_validation->set_rules('userprofile_name', 'Nom du profil d\'utilisateur', 'required');

			if ($this->form_validation->run() == TRUE) {
	            // true case
	            $permission = serialize($this->input->post('permission'));
	            
	        	$data = array(
	        		'libprofile' => $this->input->post('userprofile_name'),
	        		'user_profil_permission' => $permission
	        	);

	        	$table = 'profile';

	        	$id_name = 'idprofile' ;

	        	$update = $this->all_model->update_ligne($table, $data, $id_name, $id);

	        	if($update == 1) {

	        		// Effectuer la journalisation
			        $type_action = 'Modification' ;

			        $action_effectuee = 'Profil utilisateur' ;

			        $this->control->journalisation($type_action,$action_effectuee) ;

	        		$this->session->set_flashdata('success', 'Modification effectuée avec succès.');
	        		redirect('admin/user_role', 'refresh');
	        	}
	        	else {
	        		$this->session->set_flashdata('warning', 'Aucune donnée n\'a été modifiée avant l\'action sur le bouton << Modifier >>');
	        		redirect('admin/edit_user_group/'.$id, 'refresh');
	        	}
	        }
	        else { 
	            // false case
	            $profil_data = $this->all_model->getProfilData($id);
				$this->data['profil_data'] = $profil_data;

				$this->data['bandeau'] = 'User profil';

				$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	            $this->data['page_libprofil'] = $UniqueProfil;
				$this->data['page_active'] = 'SecurityPage';
				$this->data['page_profil'] = $this->session->userdata('user_profil');
				$this->data['page_title'] = 'Lostro Admin';
				$this->data['page_s_title'] = 'Modifier un profil d\'utilisateurs';

				// Effectuer la journalisation
			        $type_action = 'Consultation' ;

			        $action_effectuee = 'Formulaire de modification de profil utilisateur' ;

			        $this->control->journalisation($type_action,$action_effectuee) ;


				$this->render_template('user-role/edit_user_groupe', $this->data);	
	        }	
		}
	}

	/* ##################################################################
	----------	PAGE :: ./admin/user_groupes_delete ----------
	################################################################## */
	
	public function user_groupes_delete($user_group_id) 
	{

		if(!in_array('deleteGroupUser', $this->permission)) {

			$flash_feedback = "Désolé ! Vous ne disposez pas des droits requits pour supprimer un groupe d'utilisateur." ;

			$this->session->set_flashdata('error', $flash_feedback);

			redirect('home', 'refresh');
		}
		
		$table = 'users' ;

		$id_name = 'user_group_id' ;

		$id = $user_group_id ;

		$infos_user = $this->all_model->get_fullrow($table, $id_name, $id);

		if(empty($infos_user))
		{
			$this->all_model->delete_ligne('users_group', 'user_group_id', $user_group_id) ;

			$flash_feedback = "Le groupe d'utilisateur a été supprimé avec succès." ;

			$this->session->set_flashdata('success', $flash_feedback);
		}
		else
		{
			$flash_feedback = "Désolé ! Au moins un utilisateur de l'application est déjà relié à cet groupe d'utilisateur. Il est donc impossible de le supprimer." ;

			$this->session->set_flashdata('error', $flash_feedback);
		}

		// affichage de la vue

		redirect(base_url() . 'admin/user_role', 'refresh');
	
	}

	/* ##################################################################
	----------				PAGE :: ./admin/info_compte					  ----------
	################################################################## */
	public function info_compte($user_id)
	{
		$page_data['bandeau'] = 'Informations de compte';

		$page_data['title'] = 'Page d\'affichage des informations du compte';

		$page_data['profil'] = $this->all_model->get_fullrow('profile', 'idprofile', $this->session->userdata('user_profil'));

		$page_data['user_infos'] = $this->all_model->get_fullrow('user', 'user_id', $user_id);

		if($this->session->userdata('user_photo') == 1) 
	    {
	        $page_data['photo'] = $this->session->userdata('user_name').'.'.'jpg';
	    }
	    else
	    {
	        $page_data['photo'] = 'default-img.png';
	    }


	    	$id = $user_id;
			
			$Userinfo = $this->UserModel->get_UniqueUser_info($id);

			$UserProfil = $this->UserModel->getUser_Profil();
			
			$User_Action = $this->UserModel->getUser_Action();
			
			$UniqueProfil = $this->UserModel->get_UniqueProfil($id);

			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

			$UpdateProfil = $this->UserModel->get_UniqueProfil($id);


        	$page_data['page_libprofil'] = $UniqueProfil;
			
			$page_data['Userinfo'] = $Userinfo;
			$page_data['UserProfil'] = $UserProfil;
			$page_data['UniqueProfil'] = $UniqueProfil;
			$page_data['UpdateProfil'] = $UpdateProfil;
			$page_data['User_Action'] = $User_Action;
			$page_data['page_name'] = 'UserUpdater';
			$page_data['page_active'] = 'UserPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Modifier les informations d\'un utilisateur';

		// affichage de la vue
			$this->render_template('admin/user_info', $page_data);
	}

	public function user_updater($user_id){
		 
		  if(!empty($_POST))
		{	
			$type = $this->input->post('form_pass');

			switch ($type) 
			{	
				

				case($type == 1) :

				     // initialisation du validateur du formulaire
		            $this->load->library('form_validation');
		            // définition des règles de validation
		            
		            $this->form_validation->set_rules('old_password', 'Ancien mot de passe', 'trim|required|min_length[5]|max_length[16]');
		            $this->form_validation->set_rules('new_password', 'Nouveau Mot de passe', 'trim|required|min_length[5]|max_length[16]');
		            $this->form_validation->set_rules('confirme_password', 'Répéter le mot de passe', 'trim|required|min_length[5]|max_length[16]');

		            if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
		            { 
		                // erreur : retour au formulaire
		                $flash_feedback = validation_errors();

						$this->session->set_flashdata('error', $flash_feedback);
		            } 
		            else
		            {
		            	// récupération des données passées en post
						$old_password_input			= $this->input->post('old_password');
						$new_password			= $this->input->post('new_password');
						$confirme_password			= $this->input->post('confirme_password');

						$old_password_bd = $this->all_model->get_fullrow('user', 'user_id', $user_id);
						
						$old_password_bd = $old_password_bd['user_password']; 

						$old_password_input = $this->control->password_hash($old_password_input);

						/*if($old_password_bd != $old_password_input)
		            	{
		            		// erreur : retour au formulaire

				            $flash_feedback = "Vous avez certainement oublié votre ancien mot de passe.";

							$this->session->set_flashdata('error', $flash_feedback);
		            	}
		            	else*/
		            	{
		            		// récupération des données passées en post
							$new_password = $this->input->post('new_password');

		            		$new_password = $this->control->password_hash($new_password);

			            	if($new_password === $old_password_bd)
			            	{
			            		// erreur : retour au formulaire

					            $flash_feedback = "Le nouveau mot de passe doit impérativement être différent de l'ancien.";

								$this->session->set_flashdata('error', $flash_feedback);
			            	}
			            	else
			            	{
								$data = array('user_password' => $new_password);

								// Requête de modification

								$affected_rows = $this->all_model->update_ligne('user', $data, 'user_id', $user_id);

								if ($affected_rows == 1) 
								{
									// message de warning

							        $flash_feedback = "Modification effectuée avec succès.";

									$this->session->set_flashdata('success', $flash_feedback);
								}
								else 
								{
									// message de warning

							        $flash_feedback = lang('info_no_update');

									$this->session->set_flashdata('success', $flash_feedback);
								}
			            	}

		            	}

						
		            }

		            // redirection vers manage_consultation

						//redirect("/admin/info_compte/".$user_id."");

		            	redirect("/home");
									
				break;

				case($type == 2) :

					 // initialisation du validateur du formulaire
		            $this->load->library('form_validation');
		            // définition des règles de validation
		            
		            $this->form_validation->set_rules('user_first_name', 'Nom', 'trim|required');
		            $this->form_validation->set_rules('user_last_name', 'Prénoms', 'trim|required');
		            $this->form_validation->set_rules('user_email', 'Email', 'trim|required');
		            $this->form_validation->set_rules('user_phone', 'Contact', 'trim|required');

		            if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
		            { 
		                // erreur : retour au formulaire
				        	$output['error'] = TRUE;
				            $output['message'] = validation_errors();

		            } 
		            else
		            {
		            	// récupération des données passées en post
						$user_first_name			= $this->input->post('user_first_name');
						$user_last_name			= $this->input->post('user_last_name');
						$user_phone			= $this->input->post('user_phone');
						$user_email			= $this->input->post('user_email');

						$data = array('user_first_name' => $user_first_name,
									  'user_last_name' => $user_last_name,
									  'user_phone' => $user_phone,
									  'user_email' => $user_email
										);

						// Requête de modification

						$affected_rows = $this->all_model->update_ligne('user', $data, 'user_id', $user_id);

						if ($affected_rows == 1) 
						{
							// message de warning
								$output['error'] = FALSE;
							    $output['message'] = "Modification effectuée avec succès.";
						}
						else 
						{
							// message de warning
								$output['error'] = TRUE;
							    $output['message'] = lang('info_no_update');
						}
		            	
		            }

		             echo json_encode($output);
									
				break;

				case($type == 3) :

					$user_infos = $this->all_model->get_fullrow('user', 'user_id', $user_id);
				   
				   $fichier = base_url()."resources/assets/image-resources/".$user_infos['user_username']."."."png";
				   
				   if( file_exists ( $fichier))
				   {
				     unlink( $fichier ) ;
				   }

					$photo_upload = move_uploaded_file($_FILES['photo']['tmp_name'], 'resources/assets/image-resources/user-photo/' . $user_infos['user_username'] . '.png');

					$data = array('user_photo' => 1);

					// Requête de modification

						$affected_rows = $this->all_model->update_ligne('user', $data, 'user_id', $user_id);

						if ($photo_upload == 1) 
						{
							if ($affected_rows == 1) 
							{
								$flash_feedback = "La photo a été modifiée avec succès. Cette modification sera prise en compte à votre prochaine connexion. Pour voir votre nouvelle photo maintenant, veuillez vous déconnecter et vous reconnecter à nouveau.";

								$this->session->set_flashdata('success', $flash_feedback);
							}
							else
							{
								$flash_feedback = "La photo a été modifiée avec succès.";

								$this->session->set_flashdata('success', $flash_feedback);
							}
						}
						else 
						{
							$flash_feedback = lang('info_no_update');

								$this->session->set_flashdata('error', $flash_feedback);
						}

						// redirection vers manage_consultation

						redirect("/admin/info_compte/".$user_id."");

				break;

				default :
				
			}

		}

	 }

}


/* End of file admin.php */
/* Location: ./application/controllers/admin.php */