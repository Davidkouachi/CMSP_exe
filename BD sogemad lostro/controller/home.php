<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Admin_Controller {
	
	// constructeur
	public function __construct() 
	{
		parent::__construct();
		
		// chargement divers
		$this->lang->load('sogemad');
		
		/*cache control*/
		$this->output->set_header('Cache-Control: no-store, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		
	}
		
	/* ##################################################################
	----------				PAGE RACINE :: ./home					  ----------
	################################################################## */
	
	public function index() 
	{//$this->output->enable_profiler(TRUE); 

		$password		= $this->config->item('appli_provisory_password');

		$user_array = $this->admin_model->get_user_by_name($this->session->userdata('user_name'));

		$hash_password = password_verify($password, $user_array[0]->user_password);

		if (count($user_array)==1 and $hash_password === true) 
		{
			$flash_feedback = "Vous êtes à votre première connexion après la création de votre compte ou la réinitialisation de votre mot de passe. Afin de vous permettre de tirer profit du logiciel, vous devez impérativement modifier le mot de passe par defaut.";

			$this->session->set_flashdata('error', $flash_feedback);

			$user_id = $this->session->userdata('user_name');

			redirect('admin/info_compte/'.$user_id.'');
		}
		else
		{
		    if (!$this->control->ask_access()) 
    		{
    			// utilisateur NON authentifié
    
    			redirect('home/login','refresh');
    		}

    		$ListePatient = $this->PatientModel->getPatient();

	        $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	        $page_data['new_patient'] =  $this->all_model->get_new_patient();

        	$page_data['chiffre_aff'] =  $this->all_model->get_ca_mens();
        	
        	$page_data['chiffre_aff_bio'] =  $this->all_model->get_ca_mens_typefac(2);

        	$page_data['chiffre_aff_img'] =  $this->all_model->get_ca_mens_typefac(4);

        	$page_data['chiffre_aff_hospit'] =  $this->all_model->get_ca_mens_typefac(3);

        	$page_data['chiffre_aff_cons'] =  $this->all_model->get_ca_mens_typefac(1);
        	
        	$page_data['chiffre_aff_soins'] =  $this->all_model->get_ca_mens_typefac(5);
        	
        	$page_data['chiffre_aff_pharmacie'] =  $this->all_model->get_ca_mens_typefac(6);
        	
        	 $bio =  $this->all_model->get_nbre_biologie();
         
            $page_data['nbre_biologie'] = $bio['nbre'] ;
            
            $img =  $this->all_model->get_nbre_imagerie();
            
            $page_data['nbre_imagerie'] = $img['nbre'] ;
            
            $admission =  $this->all_model->get_nbre_admission();
         
       		$page_data['nbre_admission'] = $admission['nbre'] ;
       		
       		$consultation =  $this->all_model->get_nbre_consultation();
         
       		$page_data['nbre_consultation'] = $consultation['nbre'] ;
       		
       		$patient =  $this->all_model->get_nbre_patient();
         
            $page_data['nbre_patient'] = $patient['nbre'] ;
            
            $page_data['date_debut'] = $date_debut = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));

            $page_data['date_fin'] = $date_fin = date('Y-m-d', time());
            
            $page_data['repartition_cons'] =  $this->all_model->get_repartition_consultation($date_debut,$date_fin);

            $page_data['factures_emises'] =  $this->all_model->get_factures_emises($date_debut,$date_fin);
            
            $page_data['f_non_regle'] =  $this->all_model->get_montantant_factures_non_regle($date_debut,$date_fin);

            $page_data['sortie_caisse'] = $this->all_model->get_montant_sortie($date_debut,$date_fin);
            
            $solde_caisse = $this->all_model->get_solde_caisse(date('Y-m-d')) ;
        
            if(empty($solde_caisse))
            {
                $date = date('Y-m-d') ;
                $nombre_jour = 1 ;
                $date_anterieur = $this->fdateheuregmt->date_outil($date,$nombre_jour) ;
                
                $solde_caisse = $this->all_model->get_solde_caisse($date_anterieur) ;
                
                if(empty($solde_caisse))
                {
                    $page_data['solde_caisse'] = 0 ;
                }else{
                    $page_data['solde_caisse'] = $solde_caisse ;
                }
                
            }else{
                
                $page_data['solde_caisse'] = $this->all_model->get_solde_caisse(date('Y-m-d'),2) ;
                
                if(empty($page_data['solde_caisse']))
                {
                    $page_data['solde_caisse'] = $this->all_model->get_dernier_solde_caisse() ;
                }
            }
            
            $heure_systeme = $this->fdateheuregmt->dateheure(5) ;

        
	        $page_data['page_libprofil'] = $UniqueProfil;

	        $page_data['page_liste_patient'] = $ListePatient;

			$page_data['bandeau'] = lang('title_home_page');

			$page_data['title'] = lang('title_home_page');

			$page_data['page_active'] = "dashboardPage";

			$page_data['page_s_title'] = 'Bienvenue sur le tableau de bord';
			
			

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Tableau de bord' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;


			// affichage de la vue

			$this->render_template('home/dashboard', $page_data);
		}
		
	}
	
	/* ##################################################################
	----------				PAGE :: ./home/login						  ----------
	################################################################## */
	
	public function login() 
	{		
		$page_data['bandeau'] = 'Connexion';

		if (!$this->control->check_login()) 
		{
			$page_data['bandeau'] = 'Connexion';
			
			$this->load->view('home/login', $page_data);
		}
	}
	
	/* ##################################################################
	----------				PAGE :: ./home/logout					  ----------
	################################################################## */
	
	public function logout()
	{
		// Effectuer la journalisation
        $type_action = 'Déconnexion' ;

        $action_effectuee = '' ;

        $this->control->journalisation($type_action,$action_effectuee) ;

		$this->control->logout();
		redirect(site_url());
	}

	/* ##################################################################
	----------				PAGE :: ./home/session_lock					  ----------
	################################################################## */
	
	public function session_lock($user_name)
	{
		$page_data['bandeau'] = lang('title_home_page');
		$page_data['title'] = lang('title_dashboard_page');

		// Effectuer la journalisation
        $type_action = 'Verrouille' ;

        $action_effectuee = 'Session' ;

        $this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue
		$this->render_lock_template('home/lock_screen', $page_data);
	}
	
	/* ##################################################################
	----------			PAGE :: ./home/edit_my_account			  ----------
	################################################################## */
	
	public function edit_my_account($user_name) 
	{
		$page_data['bandeau'] = lang('action_edit_my_account');
		$page_data['title'] = $page_data['bandeau'];
		$page_data['username'] = $user_name;
		// affichage de la vue
		$this->load->view('home/form', $page_data);
	}
	
	/* ##################################################################
	----------			PAGE :: ./home/save_my_account			  ----------
	################################################################## */
	
	public function save_my_account() 
	{
		// récupération des données passées en _POST
		$user_id						= $this->input->post('user_id');
		$user_username					= $this->input->post('user_username');
		$page_data['user_first_name']		= $this->input->post('user_first_name');
		$page_data['user_last_name']			= $this->input->post('user_last_name');
		$page_data['user_initial']			= $this->input->post('user_initial');
		$page_data['user_phone']			= $this->input->post('user_phone');
		$page_data['user_email']				= $this->input->post('user_email');
		$user_history						= $this->input->post('user_history');
		// première requête de modification
		$affected_rows = $this->all_model->update_ligne('user', $page_data, 'user_id', $user_id);
		if ($affected_rows == 1) 
		{
			// champs remplis automatiquement
			$page_data['user_revised_date'] = now();
			// mise à jour de l'historique
			$historic = "----- " . lang('info_revised_on') . unix_to_human(now() + 7200, TRUE, 'eu') . " -----\r\n" ;
			$page_data['user_history'] = $historic . "\r\n" . $user_history ;
			// deuxième requête de modification
			if (($this->all_model->update_ligne('user', $page_data, 'user_id', $user_id)) == 1) 
			{
				// message de confirmation
				$flash_feedback = lang('info_your_account') . $user_username . lang('info_has_been_updated') ;
				$this->session->set_flashdata('good', $flash_feedback);
			} 
			else 
			{
				// message d'erreur
				
			}
		} 
		else 
		{
			// message de warning
			$flash_feedback = lang('info_no_update');
			$this->session->set_flashdata('warning', $flash_feedback);
		}
		// redirection vers la page d'accueil
		redirect('home/');
	}
	
	/* ##################################################################
	----------				PAGE :: ./home/password					  ----------
	################################################################## */
	
	public function password($user_id) 
	{
		$page_data['bandeau'] = lang('action_change_password');
		$page_data['titre'] = $this->all_model->add_nav_to_title($page_data['bandeau']);
		$page_data['user_id'] = $user_id;
		// affichage de la vue
		$this->load->view('home/password_form', $page_data);
	}
	
	/* ##################################################################
	----------			PAGE :: ./home/save_password			  ----------
	################################################################## */
	
	public function save_password() 
	{
		$user_id = $this->input->post('user_id');
		// initialisation du validateur du formulaire
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<br /><div class="errorMessage"><span style="font-size: 150%;">&uarr;&nbsp;</span>', '</div>');
		// définition des règles de validation
		$this->form_validation->set_rules('current_password', '« '.lang('label_current_password').' »', 'required');
		$this->form_validation->set_rules('new_password', '« '.lang('label_new_password').' »', 'required|min_length[8]|max_length[16]|alpha_dash|xss_clean');
		$this->form_validation->set_rules('confirm_password', '« '.lang('label_confirm_it').' »', 'required|min_length[8]|max_length[16]|alpha_dash|xss_clean|matches[new_password]');
		// test de validation du formulaire
		if ($this->form_validation->run() == FALSE)
		{
			// échec : retour au formulaire
			$this->password($user_id);
		} 
		else 
		{
			// succès : récupération des données passées en _POST
			$current_password			= $this->input->post('current_password');
			$new_password				= $this->input->post('new_password');
			$confirm_password			= $this->input->post('confirm_password');
			$user = $this->admin_model->get_user_by_id($user_id);
			if (md5($current_password) != $user['user_password_md5']) 
			{
				// message de warning et redirection
				$flash_feedback = lang('wording_current_password_false');
				$this->session->set_flashdata('warning', $flash_feedback);
				redirect('home/password/'.$user_id);
			} 
			elseif ($new_password != $confirm_password) 
			{
				// message de warning
				$flash_feedback = lang('wording_fail_confirm_password');
				$this->session->set_flashdata('warning', $flash_feedback);
				redirect('home/password/'.$user_id);
			} 
			else 
			{
				// cryptage du nouveau mot de passe
				$page_data['user_password_md5']		= md5($new_password);
				// champs remplis automatiquement
				$page_data['user_revised_date'] = now();
				// mise à jour de l'historique
				$user_history = $this->input->post('user_history');
				$historic = lang('info_password_changed_on') . unix_to_human(now() + 7200, TRUE, 'eu') . " #####\r\n" ;
				$page_data['user_history'] = $historic . "\r\n" . $user_history ;
				// requête de modification
				$this->all_model->update_ligne('user', $page_data, 'user_id', $user_id);
				// message de confirmation
				$flash_feedback = lang('info_password_updated_good');
				$this->session->set_flashdata('good', $flash_feedback);
			}
			// redirection vers la page d'accueil
			redirect('home/');
		}
	}
	
	/* ##################################################################
	----------					PAGE :: ./home/about					  ----------
	################################################################## */
	
	public function about() 
	{
		$page_data['bandeau'] = lang('nav_about_sogemadcare');
		$page_data['titre'] = $page_data['bandeau'];
		// affichage de la vue
		$this->load->view('home/about', $page_data);
	}


	/* ##################################################################
	----------					PAGE :: ./home/page_missing					  ----------
	################################################################## */
	
	public function page_missing() 
	{
		$page_data['bandeau'] = lang('error_404');
		// affichage de la vue
		$this->load->view('home/erreur_404', $page_data);
	}
	
	
}











/* End of file home.php */
/* Location: ./application/controllers/home.php */