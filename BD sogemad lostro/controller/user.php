<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Admin_Controller {
    
    
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

			if ($curr_uri_string != 'user') 
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

	// OBTENTION DE LA LISTE DE TOUS LES UTILISATEURS
	
	public function UserList()
	{
		// APPEL DU MODEL ADEQUAT POUR LA LISTE DES UTILISATEURS

			$UserList = $this->UserModel->getUser();
		
			// UTILISATION DU RESULTAT PROVENANT DE LA REQUETE D'OBTENTION DE LA LISTE
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'UserList';
			$page_data['page_active'] = 'UserPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Liste des utilisateurs';
			$page_data['user_list'] = $UserList ;

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Liste des utilisateurs' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

			$this->render_template('kameleon/UserList', $page_data);
	}


	// ENREGISTREMENT D'UN UTILISATEUR

    public function UserRegister()
    {
		if(!empty($_POST))
		{
			// initialisation du validateur du formulaire

			$this->load->library('form_validation');

			// définition des règles de validation

			$user_profil_id	= $this->input->post('user_profil_id');

			$this->form_validation->set_rules('user_rights', '<< Droit d\'accès >>', 'trim|required|xss_clean');
			
			$this->form_validation->set_rules('user_phone', '<< Contact >>', 'trim|required|xss_clean');
			$this->form_validation->set_rules('user_username', '<< Login >>', 'trim|required|alpha_dash|xss_clean');
			$this->form_validation->set_rules('user_profil_id', '<< Profil >>', 'trim|required|xss_clean');
			$this->form_validation->set_rules('etat_compte', '<< Etat du compte >>', 'trim|required|xss_clean');

			if($user_profil_id == 3)
			{
				$this->form_validation->set_rules('code_medecin', '<< Actions Autorisées >>', 'trim|required|xss_clean');
				
				$code_medecin	= $this->input->post('code_medecin');

				if(empty($code_medecin))
				{
					$this->form_validation->set_rules('user_first_name', '<< Nom >>', 'trim|required|xss_clean');
					$this->form_validation->set_rules('user_last_name', '<< Prenoms >>', 'trim|required|xss_clean');
				}
				
			}
			else
			{
				$this->form_validation->set_rules('matricule_employe', '<< Actions Autorisées >>', 'trim|xss_clean');
				
				$matricule_employe	= $this->input->post('matricule_employe');

				if(empty($matricule_employe))
				{
					$this->form_validation->set_rules('user_first_name', '<< Nom >>', 'trim|required|xss_clean');
					$this->form_validation->set_rules('user_last_name', '<< Prenoms >>', 'trim|required|xss_clean');
				}
			}

	        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
	        {
	        	// erreur : retour au formulaire
	        	$flash_feedback = validation_errors();

				$this->session->set_flashdata('error', $flash_feedback);

				redirect('user/UserRegister','refresh') ;
	        } 
	        else 
	        {
				// récupération des données passées en post
				$user_rights	= $this->input->post('user_rights');
				$user_first_name= $this->input->post('user_first_name');
				$user_last_name	= $this->input->post('user_last_name');
				$user_phone		= $this->input->post('user_phone');
				$user_username	= $this->input->post('user_username');
				$user_profil_id	= $this->input->post('user_profil_id');
				$etat_compte	= $this->input->post('etat_compte');

				$matricule_employe	= $this->input->post('matricule_employe');

				$code_medecin	= $this->input->post('code_medecin');


				if($user_profil_id == 3)
				{
					$infos_medecin = $this->all_model->get_fullrow('medecin','codemedecin',$code_medecin);

					if(!empty($infos_medecin))
					{
						$user_first_name= $infos_medecin['nommedecin'];
						$user_last_name	= $infos_medecin['prenomsmedecin'];

						$code_personnel = $infos_medecin['codemedecin'] ;
					}
					else
					{
						$code_personnel = '' ;
					}
				}
				else
				{

					$infos_employe = $this->all_model->get_fullrow('employes','matricule',$matricule_employe);

					if(!empty($infos_employe))
					{
						$user_first_name= $infos_employe['nom'];
						$user_last_name	= $infos_employe['prenom'];

						$code_personnel = $infos_employe['matricule'] ;
					}
					else
					{
						$code_personnel = '' ;
					}
				}

				$user_email = '';
					
						// contrôle d'unicité avant création
						if ($this->admin_model->oneness_control($user_username) == TRUE) 
						{
							// champs remplis automatiquement
							$user_password		= $this->control->password_hash($this->config->item('appli_provisory_password'));
							$user_make_date			= now() + 7200;
							$user_history			= "--o-- " . lang('info_maked_on') . unix_to_human(now() + 7200, TRUE, 'eu') . " --o--\r\n" ;


							$data = array('user_username' => $user_username,
										'user_password' => $user_password,
										'user_first_name' => $user_first_name,
										'user_last_name' => $user_last_name,
										'user_phone' => $user_phone,
										'user_profil_id' => $user_profil_id,
									    'user_email' => $user_email,
										'user_rights' => $user_rights,
										'user_make_date' => $user_make_date, 
										'user_revised_date' => NULL,
										'user_ip' => NULL,
										'user_history' => $user_history,
										'user_logs' => 0,
										'user_lang' => 'fr',
										'user_photo' => 0,
										'user_actif' => $etat_compte,
										'code_personnel' => $code_personnel
										);

							// requête d'insertion
							$new_id = $this->all_model->add_ligne_with_return_id('user', $data) ;

							if (is_numeric($new_id)) 
							{
								// Effectuer la journalisation
									$type_action = 'Ajout' ;

									$action_effectuee = 'Utilisateur' ;

									$this->control->journalisation($type_action,$action_effectuee) ;

								$data = $this->all_model->get_fullrow('user', 'user_id', $new_id);

								// message de confirmation
								$message = "Le compte utilisateur ".$user_username." a été crée avec succès. Le mot de passe par defaut est ".$this->config->item('appli_provisory_password')."" ;


								$this->session->set_flashdata('SUCCESSMSG', $message);

								redirect('/user/UserRegister/','refresh');
					            
							} 
							else 
							{
								// message d'erreur

						        $message = "Ce login est déja utilisé par un autre utilisateur." ;

						        $this->session->set_flashdata('SUCCESSMSG', $message);

								redirect('/user/UserRegister/','refresh');
								
							}
							
						} 
						else 
						{
							// message d'erreur

					        $message = "Ce login est déja utilisé par un autre utilisateur." ;

					        $this->session->set_flashdata('SUCCESSMSG', $message);

								redirect('/user/UserRegister/','refresh');

						}
			}

		}
		else
		{	
			$UserProfil = $this->UserModel->getUser_Profil();

			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['page_libprofil'] = $UniqueProfil;

        	$page_data['medecins'] = $this->all_model->get_table('medecin');

        	$page_data['employes'] = $this->all_model->get_table('employes');
			
			$page_data['UserProfil'] = $UserProfil;
			$page_data['page_name'] = 'UserRegister';
			$page_data['page_active'] = 'UserPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter un utilisateur';

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire d\'ajout d\'utilisateur' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
			
			// affichage de la vue

			$this->render_template('kameleon/UserRegister', $page_data);
		}
    }

    // MODIFICATION D'UN UTILISATEUR

    public function UserUpdater($id){
		 
		  if(!empty($_POST))
		{	
			$type = $this->input->post('form_pass');

			switch ($type) 
			{
				case($type == 1) :

					 // initialisation du validateur du formulaire
		            $this->load->library('form_validation');

		            // définition des règles de validation
		            		            
		            $this->form_validation->set_rules('user_first_name', 'Nom', 'trim|required');
		            $this->form_validation->set_rules('user_last_name', 'Prénoms', 'trim|required');
		            $this->form_validation->set_rules('user_username', 'Login', 'trim|required');
		            $this->form_validation->set_rules('user_phone', 'Contact', 'trim|required');
		            $this->form_validation->set_rules('profil', 'Profil', 'trim|required');
		            //$this->form_validation->set_rules('user_rights', 'Droit d\'accès', 'trim|required');
		            $this->form_validation->set_rules('etat_compte', 'Etat du compte', 'trim|required');

		            if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
		            { 
		                // erreur : retour au formulaire

		                $id = $id; 
		                
						$flash_feedback = validation_errors();

						$this->session->set_flashdata('warning', $flash_feedback);

						$this->session->set_userdata('tab_active',1) ;

						redirect('user/UserUpdater/'.$id.'','refresh') ;

		            } 
		            else
		            {
		            	
	            		$id_name = 'user_username';
			            $id = $id;
			            $table = 'user';

			            $data = array('user_first_name' => $this->input->post('user_first_name'),
			            	'user_last_name' => $this->input->post('user_last_name'),
						'user_username' => $this->input->post('user_username'),
						'user_phone' => $this->input->post('user_phone'),
						'user_profil_id' => $this->input->post('profil'),
						'user_actif' => $this->input->post('etat_compte'),
						'user_rights' => $this->input->post('user_rights'),
						'user_email' => $this->input->post('user_email')
						);

			            $query = $this->all_model->update_ligne($table, $data, $id_name, $id);

			            if($query > 0)
			            {
			            	// Effectuer la journalisation
								$type_action = 'Modification' ;

								$action_effectuee = 'Utilisateur' ;

								$this->control->journalisation($type_action,$action_effectuee) ;

			            	$this->session->set_flashdata('SUCCESSMSG', "Informations modifiées avec succès");
			            }else{
			            	$this->session->set_flashdata('SUCCESSMSG', "Aucune donnée n'a été changée avant votre action sur le bouton << Modifier >>");
			            }

						$this->session->set_userdata('tab_active',1) ;

						redirect('dashboard', 'refresh');
		            }
									
				break;

				default :

				break;
				
			}

		}
		else
		{	$infos_profil = $this->all_model->get_fullrow('profile','idprofile',$this->session->userdata('user_profil'));

	        if(!empty($infos_profil))
	        {
	            $page_data['page_libprofil'] = $infos_profil['libprofile'];
	        }
	        else{
	            $page_data['page_libprofil'] = '';
	        }

			$page_data['Userinfo'] = $this->UserModel->get_UniqueUser_info($id);
			$page_data['UserProfil'] = $this->UserModel->getUser_Profil();
			$page_data['UniqueProfil'] = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
			$page_data['UpdateProfil'] = $this->UserModel->get_UniqueProfil($id);

			$page_data['page_name'] = 'UserUpdater';
			$page_data['page_active'] = 'UserPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Modifier les informations d\'un utilisateur';

			$tab_active = $this->session->userdata('tab_active');

			if(!empty($tab_active))
			{
				$page_data['tab_active'] = $this->session->userdata('tab_active');
			}else{
				$page_data['tab_active'] = 1 ;
			}

			$page_data['idAns'] = 'journalisation_actions';

		 	$page_data['namePg'] = 'fetchHistoriqueData';

		 	$page_data['user_username'] = $id;

		 	$page_data['profils_autorises'] = array('1','2','7');

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire de modification d\'utilisateur' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

			$this->render_template('kameleon/UserUpdater', $page_data);
		}

	 }

	
	public function UserPrint()
	{

		$UserList = $this->all_model->get_table('user');

		$page_data['print_name'] = 'i_BLV';
		$page_data['page_name'] = 'UserList';
		$page_data['page_active'] = 'UserPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Liste des utilisateurs';
		$page_data['user_list'] = $UserList ;

		// Effectuer la journalisation
			$type_action = 'Impression' ;

			$action_effectuee = 'Liste des utilisateurs' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

		$this->load->view('print/infoNet', $page_data);
	}

	
	
}
	
