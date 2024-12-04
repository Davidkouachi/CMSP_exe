<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Parametre extends Admin_Controller {
    
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

			if ($curr_uri_string != 'parametre') 
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

	// Martin
	public function ProfilRegister()
	{
		$table = 'profil';
		$page_data['list_profil'] = $this->all_model->get_table($table);
		$page_data['page_name'] = 'parametre/ProfilRegister';
		$page_data['bandeau'] = 'Gestion des Profils';
		$page_data['titre'] = 'Ajouter un Profil';
		$page_data['titre_2'] = 'Liste des Profils';
		$page_data['page_active'] = 'UserPage';
		$page_data['profil'] = $this->session->userdata('user_profil');

		// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire d\'ajout de profil d\'utilisateur' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;
		
		$this->load->view('parametre/index',$page_data);
	}



	public function AssuranceRegister()
	{


		if(!empty($_POST))
		{
			$table = 'assurance';

			$nomchamp = 'codeassurance';
			
			$maxCode = $this->all_model->get_max_assurance();

			
			if ($maxCode['codeassurance'] == '') {

				$codeassurance = 'ASS01';
			}
			else
			{
				$number = substr($maxCode['codeassurance'],-2);
				
				$lastNumber = $number + 1 ;

				$lastNumber ;

				$zeroadd = "".$lastNumber ;

				while (strlen($zeroadd) < 2) {
					
					$zeroadd = "0" . $zeroadd ;
				}
				
				$lastNumber = $zeroadd ; 
				
				$codeassurance = 'ASS'.$lastNumber;
			}

			
			$data =  array('codeassurance' => $codeassurance,
						   'libelleassurance' => $this->input->post('libelleassurance'),
						   'telassurance' => $this->input->post('telassurance'),
						   'faxassurance' => $this->input->post('faxassurance'),
						   'emailassurance' => $this->input->post('emailassurance'),
						   'adrassurance' => $this->input->post('adrassurance'),
						   'situationgeo' => $this->input->post('situationgeoassurance'),
						   'description' => $this->input->post('desassurance'),
						   'mode_impression' => $this->input->post('mode_impression')
							);

			// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Assurance' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;


			$this->all_model->add_ligne($table, $data);

			$this->session->set_flashdata('SUCCESSMSG', "Enregistrement effectué avec succès!!");


			// redirection

        	redirect('Parametre/AssuranceRegister/');
		}
		else
		{
		
		
			$page_data['page_name'] = 'parametre/AssuranceRegister';
			$page_data['bandeau'] = 'Gestion des Assurances';
			$page_data['titre'] = 'Ajouter une Assurance';
			$page_data['titre_2'] = 'Liste des assurances';
			$page_data['page_active'] = 'AssurancePage';

			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	        $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter une assurance';

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire d\'ajout d\'assurance' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

				// affichage de la vue

	        	$this->render_template('kameleon/AssuranceRegister', $page_data);
        }
	}

	public function ViewAssurance()
	{
		$table_1 = 'assurance';

		$page_data['list_assurance'] = $this->all_model->get_table($table_1);

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_name'] = 'AssuranceList';
		$page_data['page_active'] = 'AssurancePage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Liste des assurances';

		// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Liste des assurances' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue

        $this->render_template('kameleon/AssuranceList', $page_data);
	}


	public function AssuranceDeleter($id)
	{
		$table = 'assurance';

		$id_name = 'codeassurance' ;

		$id = $id ;

		$this->all_model->delete_ligne($table, $id_name, $id);

		// Effectuer la journalisation
		        $type_action = 'Suppression' ;

		        $action_effectuee = 'Assurance' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

		$this->session->set_flashdata('SUCCESSMSG', "Suppression effectuée avec succès!!");

		// redirection
			 
        redirect('Parametre/ViewAssurance/');
	}

	public function AssuranceUpdater($param1 = '', $param2 = '', $param3 = '')
    {
    	if ($param1 == 'edit' && $param2 == 'do_update') {

    		$codeassurance = $this->input->post('codeassurance');

    		$data =  array('codeassurance' => $codeassurance,
						   'libelleassurance' => $this->input->post('libelleassurance'),
						   'telassurance' => $this->input->post('telassurance'),
						   'faxassurance' => $this->input->post('faxassurance'),
						   'emailassurance' => $this->input->post('emailassurance'),
						   'adrassurance' => $this->input->post('adrassurance'),
						   'situationgeo' => $this->input->post('situationgeoassurance'),
						   'description' => $this->input->post('desassurance'),
						   'mode_impression' => $this->input->post('mode_impression')
							);
			

			$this->db->where('codeassurance', $param3);

			$this->db->update('assurance', $data);

			// Effectuer la journalisation
		        $type_action = 'Modification' ;

		        $action_effectuee = 'Assurance' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			$this->session->set_flashdata('SUCCESSMSG', 'Modification de l\'assurance effectuée avec succès.');

			redirect(base_url() . 'Parametre/ViewAssurance', 'refresh');

			

		} else if ($param1 == 'edit') {

			$page_data['edit_profile'] = $this->db->get_where('assurance', array(

				'codeassurance' => $param2

			))->result_array();

		}


			$page_data['page_name'] = 'parametre/AssuranceUpdater';
			$page_data['bandeau'] = 'Gestion des Assurances';
			$page_data['titre'] = 'Modifier une Assurance';
			$page_data['titre_2'] = 'Liste des assurances';
			$page_data['page_active'] = 'AssurancePage';

			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
			
	        $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Modifier une assurance';

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire de modification d\'assurance' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

				// affichage de la vue

	        	$this->render_template('kameleon/AssuranceUpdater', $page_data);
    }

	public function SocieteARegister()
	{

			$table_1 = 'assurance';
			$table_2 = 'societeassure';
			$page_data['list_societe'] = $this->all_model->get_table($table_2);

			$page_data['assurance'] = $this->all_model->get_table($table_1);
			
			$page_data['assureurs'] = $this->all_model->get_table('assureur');
				
			$page_data['page_name'] = 'parametre/SocieteARegister';
			$page_data['bandeau'] = 'Gestion des Sociétés assurées';
			$page_data['titre'] = 'Ajouter une Société d\'assuré';
			$page_data['titre_2'] = 'Liste des Sociétés assurées';
			$page_data['page_active'] = 'parametrePage';

			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
				
			$PatientFiliation = $this->PatientModel->getFiliationPatient();
	        $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter une Société d\'assuré';

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire d\'ajout de société' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        	$this->render_template('kameleon/SocieteARegister', $page_data);
        
	}
	
	
	public function Assureur_register()
	{

			$table_1 = 'assureur';
			$page_data['assureurs'] = $this->all_model->get_table($table_1);

			$page_data['bandeau'] = 'Gestion des assureurs santé';
			$page_data['titre'] = 'Ajouter un assureur';
			$page_data['titre_2'] = 'Liste des assureurs';
			$page_data['page_active'] = 'AssureurPage';

			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
				
	        $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter un assureur';

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire d\'ajout d\'assureur' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        	$this->render_template('kameleon/assureur_register', $page_data);
        
	}
	
	public function Assureur_updater($id)
	{
		$table_1 = 'assureur';

		$page_data['assureurs'] = $this->all_model->get_table($table_1);

		$page_data['assureur_update'] = $this->all_model->get_fullrow($table_1, 'codeassureur', $id);

		$page_data['bandeau'] = 'Gestion des assureurs santé';
		$page_data['titre'] = 'Modifier un assureur';
		$page_data['titre_2'] = 'Liste des assureurs';
		$page_data['page_active'] = 'AssureurPage';

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Modifier un assureur';

		// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire de modification d\'assureur' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        	$this->render_template('kameleon/Assureur_updater', $page_data);
	}

	public function SocieteAUpdater($id)
	{
		$table_1 = 'assurance';
		$table_2 = 'societeassure';
		$page_data['list_societe'] = $this->all_model->get_table($table_2);

		$page_data['assurance'] = $this->all_model->get_table($table_1);
		
		$page_data['assureurs'] = $this->all_model->get_table('assureur');

		$page_data['assurance_update'] = $this->all_model->get_fullrow($table_2, 'codesocieteassure', $id);

		$page_data['bandeau'] = 'Gestion des Sociétés assurées';
		$page_data['titre'] = 'Modifier une Société d\'assuré';
		$page_data['titre_2'] = 'Liste des Sociétés assurées';
		$page_data['page_active'] = 'parametrePage';

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
			
		$PatientFiliation = $this->PatientModel->getFiliationPatient();
        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Modifier une Société d\'assuré';

		// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire de modification de société' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        	$this->render_template('kameleon/SocieteAUpdater', $page_data);
	}

	public function ProduitARegister()
	{

			$table_1 = 'assurance';
			$table_2 = 'societeassure';

			$table_3 = 'produit_assurance';

			$page_data['list_societe'] = $this->all_model->get_table($table_2);

			$page_data['assurance'] = $this->all_model->get_table($table_1);

			$page_data['produit_assurance'] = $this->all_model->get_table($table_3);

			$page_data['page_name'] = 'parametre/ProduitARegister';
			$page_data['bandeau'] = 'Gestion des Produits d\'assurance';
			$page_data['titre'] = 'Ajouter un produit d\'assurance';
			$page_data['titre_2'] = 'Liste des Produits d\'assurance';
			$page_data['page_active'] = 'produitAPage';

			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	        $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter un produit d\'assurance';

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire d\'ajout de produit d\'assurance' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        	$this->render_template('kameleon/ProduitARegister', $page_data);
        
	}

	public function ProduitAUpdater($id)
	{
		$table_1 = 'assurance';
		$table_2 = 'societeassure';

		$table_3 = 'produit_assurance';

		$page_data['list_societe'] = $this->all_model->get_table($table_2);

		$page_data['assurance'] = $this->all_model->get_table($table_1);

		$page_data['produit_assurance'] = $this->all_model->get_table($table_3);

		$page_data['produit_update'] = $this->all_model->get_fullrow($table_3, 'codeproduit', $id);

		$page_data['bandeau'] = 'Gestion des Produits d\'assurance';
		$page_data['titre'] = 'Modifier un produit d\'assurance';
		$page_data['titre_2'] = 'Liste des Produits d\'assurance';
		$page_data['page_active'] = 'produitAPage';

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
			
        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Modifier un produit d\'assurance';

		// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire de modification de produit d\'assurance' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        	$this->render_template('kameleon/ProduitAUpdater', $page_data);
	}	

	public function ActesRegister()
	{

			$table_1 = 'garantie';
			$table_2 = 'typgarantie';

			$page_data['type_garantie'] = $this->all_model->get_table($table_2);

			$page_data['garantie'] = $this->all_model->get_table($table_1);

			$page_data['bandeau'] = 'Gestion des Actes';
			$page_data['titre'] = 'Ajouter un acte';
			$page_data['titre_2'] = 'Liste des Actes';
			$page_data['page_active'] = 'ActesPage';

			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	        $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter un acte';

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire d\'ajout d\'acte medical' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        	$this->render_template('kameleon/ActesRegister', $page_data);
        
	}

	public function ActesUpdater($id)
	{
		$table_1 = 'garantie';
		$table_2 = 'typgarantie';

		$page_data['type_garantie'] = $this->all_model->get_table($table_2);

		$page_data['garantie'] = $this->all_model->get_table($table_1);

		$page_data['actes_update'] = $this->all_model->get_fullrow($table_1, 'codgaran', $id);

		$page_data['bandeau'] = 'Gestion des actes';
		$page_data['titre'] = 'Modifier un acte';
		$page_data['titre_2'] = 'Liste des actes';
		$page_data['page_active'] = 'ActesPage';

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
			
        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = "Modifier l'acte";

		// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire de modification d\'acte médical' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue

        $this->render_template('kameleon/ActesUpdater', $page_data);
	}	

	public function SaveActes()
	{
		$output = array('error' => false);

		$table = 'garantie';
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('libelleacte', 'Nom de l\'acte', 'trim|required');
			$this->form_validation->set_rules('codtypgar', 'Type de l\'acte', 'trim|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {
        	// succès : récupération des données passées en _POST

        	$libelleacte = $this->input->post('libelleacte') ;

        	$codetypegarantie = $this->input->post('codtypgar') ;

        	if($codetypegarantie == 'CONS')
        	{
        		$pratique = 1 ;
        	}
        	else{
        		$pratique = 0 ;
        	}


        	//SCRIPT DE GENERATION DU CODE DE L'ACTE ***
						do {
								$random_chars="";
								$characters = array(
									"A","B","C","D","E","F","G","H","J","K","L","M",
									"N","P","Q","R","S","T","U","V","W","X","Y","Z",
									"1","2","3","4","5","6","7","8","9");
								$keys = array();
								while(count($keys) < 5) {
									$x = mt_rand(0, count($characters)-1);
									if(!in_array($x, $keys)) 
									{
										$keys[] = $x;
									}
								}

								foreach($keys as $key){
									$random_chars .= $characters[$key];
								}

								$codeacte = 'A'.$random_chars;

								$nbr_res = $this->all_model->get_fullrow('garantie','codgaran',$codeacte);

							} while ($nbr_res);
						///FIN DU SCRIPT/***

				$data = array('codgaran' => $codeacte,
						'libgaran' => $libelleacte,
						'codtypgar' => $codetypegarantie,
						'pratique' => $pratique
						);

            $query = $this->all_model->add_ligne($table,$data);

            if($query)
            {
            	// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Acte médical' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'L\'acte a été enrégistré avec succès dans la base de données.';

            	$this->session->set_flashdata('success', "L\'acte a été enrégistré avec succès dans la base de données");
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'cet enrégistrement existe déjà dans la base de données.';

            	$this->session->set_flashdata('error', "cet enrégistrement existe déjà dans la base de données");
            }
        }

        echo json_encode($output);
	}

	public function UpdateActes()
	{
		$output = array('error' => false);

		$table = 'garantie';
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('libelleacte', 'Nom de l\'acte', 'trim|required');
			$this->form_validation->set_rules('codtypgar', 'Type de l\'acte', 'trim|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {
        	// succès : récupération des données passées en _POST

        	$codegarantie = trim($this->input->post('codgaran')) ;

        	$libelleacte = trim($this->input->post('libelleacte')) ;

        	$codetypegarantie = trim($this->input->post('codtypgar')) ;

        	if($codetypegarantie == 'CONS')
        	{
        		$pratique = 1 ;
        	}
        	else{
        		$pratique = 0 ;
        	}

				$data = array('codgaran' => $codegarantie,
						'libgaran' => $libelleacte,
						'codtypgar' => $codetypegarantie,
						'pratique' => $pratique
						);

            $query = $this->all_model->update_ligne($table, $data, 'codgaran', $codegarantie);

            if($query)
            {
            	// Effectuer la journalisation
		        $type_action = 'Modification' ;

		        $action_effectuee = 'Acte médical' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'L\'acte a été modifié avec succès dans la base de données.';

            	$this->session->set_flashdata('success', "L\'acte a été modifié avec succès dans la base de données");
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'Aucune modification n\'a été effectuée.';

            	$this->session->set_flashdata('error', "Aucune modification n\'a été effectuée.");
            }
        }

        echo json_encode($output);
	}
	
   public function SaveSociete()
	{
		$output = array('error' => false);

		$table = 'societeassure';
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('nomsocieteassure', 'Nom de la société', 'trim|required');
			$this->form_validation->set_rules('codeassurance', 'Nom de l\'assurance', 'trim|required');
			$this->form_validation->set_rules('codeassureur', 'Nom de l\'assureur', 'trim|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {
        	// succès : récupération des données passées en _POST

				$data = array('nomsocieteassure' => $this->input->post('nomsocieteassure'),
						'codeassurance' => $this->input->post('codeassurance'),
						'codeassureur' => $this->input->post('codeassureur')
						);

            $query = $this->all_model->add_ligne($table,$data);

            if($query)
            {
            	// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Société assuré' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'La société a été enrégistrée avec succès dans la base de données.';
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'cet enrégistrement existe déjà dans la base de données.';
            }
        }

        echo json_encode($output);
	}
	
	public function update_assureur()
	{
		$output = array('error' => false);

		$table = 'assureur';
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('nomassureur', 'Nom de l\'assureur', 'trim|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {
        	// succès : récupération des données passées en _POST

        	$codeassureur = $this->input->post('codeassureur');

				$data = array('libelle_assureur' => $this->input->post('nomassureur'));

            $query = $this->all_model->update_ligne($table, $data, 'codeassureur', $codeassureur);

            if($query)
            {
            	// Effectuer la journalisation
		        $type_action = 'Modification' ;

		        $action_effectuee = 'Assureur' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'L\'assureur a été modifié avec succès dans la base de données.';
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'Aucune modification n\'a été effectuée.';
            }
        }

        echo json_encode($output);
	}

	public function UpdateSociete()
	{
		$output = array('error' => false);

		$table = 'societeassure';
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('nomsocieteassure', 'Nom de la société', 'trim|required');
			$this->form_validation->set_rules('codeassurance', 'Nom de l\'assurance', 'trim|required');
			$this->form_validation->set_rules('codeassureur', 'Nom de l\'assureur', 'trim|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {
        	// succès : récupération des données passées en _POST

        	$codesocieteassure = $this->input->post('codesocieteassure');

				$data = array('nomsocieteassure' => $this->input->post('nomsocieteassure'),
						'codeassurance' => $this->input->post('codeassurance'),
						'codeassureur' => $this->input->post('codeassureur')
						);

            $query = $this->all_model->update_ligne($table, $data, 'codesocieteassure', $codesocieteassure);

            if($query)
            {
            	// Effectuer la journalisation
		        $type_action = 'Modification' ;

		        $action_effectuee = 'Société' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'La société a été modifiée avec succès dans la base de données.';
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'Aucune modification n\'a été effectuée.';
            }
        }

        echo json_encode($output);
	}

	public function UpdateProduitAssurance()
	{
		$output = array('error' => false);

		$table = 'produit_assurance';
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('libelleproduit', 'Nom du produit d\'assurance', 'trim|required');
			$this->form_validation->set_rules('codeassurance', 'Nom de l\'assurance', 'trim|required');
			$this->form_validation->set_rules('codesocieteassure', 'Nom de la société', 'trim|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {
        	// succès : récupération des données passées en _POST

        	$codeproduit = $this->input->post('codeproduit');

				$data = array('codeproduit' => $codeproduit,
						'libelleproduit' => $this->input->post('libelleproduit'),
						'codeassurance' => $this->input->post('codeassurance'),
						'codesocieteassure' => $this->input->post('codesocieteassure')
						);

            $query = $this->all_model->update_ligne($table, $data, 'codeproduit', $codeproduit);

            if($query)
            {
            	// Effectuer la journalisation
		        $type_action = 'Modification' ;

		        $action_effectuee = 'Produit d\'assurance' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'Le produit d\'assurance a été modifié avec succès dans la base de données.';
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'Aucune modification n\'a été effectuée.';
            }
        }

        echo json_encode($output);
	}


	public function SaveProduitAssurance()
	{
		$output = array('error' => false);

		$table = 'produit_assurance';
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('libelleproduit', 'Nom du produit d\'assurance', 'trim|required');
			$this->form_validation->set_rules('codeassurance', 'Nom de l\'assurance', 'trim|required');
			$this->form_validation->set_rules('codesocieteassure', 'Nom de la société', 'trim|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {

        	//SCRIPT DE GENERATION DU CODE DE SORTIE ***
						do {
								$random_chars="";
								$characters = array(
									"A","B","C","D","E","F","G","H","J","K","L","M",
									"N","P","Q","R","S","T","U","V","W","X","Y","Z",
									"1","2","3","4","5","6","7","8","9");
								$keys = array();
								while(count($keys) < 5) {
									$x = mt_rand(0, count($characters)-1);
									if(!in_array($x, $keys)) 
									{
										$keys[] = $x;
									}
								}

								foreach($keys as $key){
									$random_chars .= $characters[$key];
								}

								$codeproduit = 'P'.$random_chars;

								$nbr_res = $this->all_model->get_fullrow('produit_assurance','codeproduit',$codeproduit);

							} while ($nbr_res);
						///FIN DU SCRIPT/***
        	// succès : récupération des données passées en _POST

				$data = array('codeproduit' => $codeproduit,
						'libelleproduit' => $this->input->post('libelleproduit'),
						'codeassurance' => $this->input->post('codeassurance'),
						'codesocieteassure' => $this->input->post('codesocieteassure')
						);

            $query = $this->all_model->add_ligne($table,$data);

            if($query)
            {
            	// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Produit d\'assurance' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'Le produit d\'assurance a été enrégistré avec succès dans la base de données.';
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'cet enrégistrement existe déjà dans la base de données.';
            }
        }

        echo json_encode($output);
	}
	
	public function SaveAssureur()
	{
		$output = array('error' => false);

		$table = 'assureur';
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('nomassureur', 'Nom de l\'assureur', 'trim|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {
        	// succès : récupération des données passées en _POST


			$data = array('libelle_assureur' => $this->input->post('nomassureur'));

            $query = $this->all_model->add_ligne($table,$data);

            if($query)
            {
            	// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Assureur' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'L\'assureur a été enrégistré avec succès dans la base de données.';
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'cet enrégistrement existe déjà dans la base de données.';
            }
        }

        echo json_encode($output);
	}

	
	public function SaveProfil()
	{
		$output = array('error' => false);

		$table = 'profil';

		$id_name = 'id';
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('libelle_profil', 'Libellé du profil', 'trim|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {
        	// succès : récupération des données passées en _POST

        		$resultatId = $this->all_model->getMaxId($table,$id_name);

				if($resultatId)
				{
					$comma_separated = implode(",", $resultatId);

					$resultatId = intval($comma_separated);

					$code = $resultatId + 1 ;
				}
				else
				{
					$code = 1 ;
				}

				$data = array('id' => $code,
						'libelle_profil' => $this->input->post('libelle_profil')
						);

            $query = $this->all_model->add_ligne($table,$data);

            if($query)
            {
            	// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Profil utilisateur' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'Le nouveau profil a été enrégistré avec succès dans la base de données.';
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'cet enrégistrement existe déjà dans la base de données.';
            }
        }

        echo json_encode($output);
	}
	

	//////////////////////////////////////
	public function SaveTauxCouverture()
	{
		$output = array('error' => false);

		$table = 'tauxcouvertureassure';
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('valeurtaux', 'Valeur du taux', 'integer|trim|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {
        	// succès : récupération des données passées en _POST

        	$resultatId = $this->all_model->getMaxId($table,$id_name);

				if($resultatId)
				{
					$comma_separated = implode(",", $resultatId);

					$resultatId = intval($comma_separated);

					$code = $resultatId + 1 ;
				}
				else
				{
					$code = 1 ;
				}
        	
				$data = array('idtauxcouv' => $code,
						'valeurtaux' => $this->input->post('valeurtaux')
						);

            $query = $this->all_model->add_ligne($table,$data);

            if($query)
            {
            	// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Taux de couverture' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'Le nouveau taux de couverture a été enrégistré avec succès dans la base de données.';
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'cet enrégistrement existe déjà dans la base de données.';
            }
        }

        echo json_encode($output);
	}
	/////////////////////////////////////
	//////////////////////////////////////
	public function SaveService()
	{
		$output = array('error' => false);

		$table = 'service';
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('libelle_service', 'Valeur du taux', 'trim|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {
        	// succès : récupération des données passées en _POST

        	$resultatId = $this->all_model->getMaxId($table,$id_name);

				if($resultatId)
				{
					$comma_separated = implode(",", $resultatId);

					$resultatId = intval($comma_separated);

					$code = $resultatId + 1 ;
				}
				else
				{
					$code = 1 ;
				}
        	
				$data = array('code_service' => $code,
						'libelle_service' => $this->input->post('libelle_service')
						);

            $query = $this->all_model->add_ligne($table,$data);

            if($query)
            {
            	// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Service' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'Le nouveau service a été enrégistré avec succès dans la base de données.';
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'cet enrégistrement existe déjà dans la base de données.';
            }
        }

        echo json_encode($output);
	}
	/////////////////////////////////////
	//////////////////////////////////////
	public function SaveFiliation()
	{
		$output = array('error' => false);

		$table = 'filiation';
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('libelle_filiation', 'Libellé de la filiation', 'trim|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {
        	// succès : récupération des données passées en _POST

        	$resultatId = $this->all_model->getMaxId($table,$id_name);

				if($resultatId)
				{
					$comma_separated = implode(",", $resultatId);

					$resultatId = intval($comma_separated);

					$code = $resultatId + 1 ;
				}
				else
				{
					$code = 1 ;
				}
        	
				$data = array('code_filiation' => $code,
						'libelle_filiation' => $this->input->post('libelle_filiation')
						);

            $query = $this->all_model->add_ligne($table,$data);

            if($query)
            {
            	// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Filiation' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'La nouvelle filiation a été enrégistré avec succès dans la base de données.';
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'cet enrégistrement existe déjà dans la base de données.';
            }
        }

        echo json_encode($output);
	}

	//////////////////////////////////////
	public function SaveMedecin()
	{
		$output = array('error' => false);

		$table = 'medecin';
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('titremed', 'Titre du medecin', 'in_list[Dr,Pr]|trim|required');
			$this->form_validation->set_rules('nommedecin', 'Nom du medecin', 'trim|required');
			$this->form_validation->set_rules('prenomsmedecin', 'Prénoms du medecin', 'trim|required');
			$this->form_validation->set_rules('contact', 'Contact du medecin', 'trim|required');
			$this->form_validation->set_rules('email', 'Email du medecin', 'trim|valid_email|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {
        	// succès : récupération des données passées en _POST

        	$lettreCle = 'MED';

        	$nomchamp = 'codemedecin';
				
			$maxId = $this->all_model->getMaxId($table,$nomchamp);


				//Recherche de la valeur de l'identifiant 
			$idMedecin = $this->generateur_identifiant->identifiant_tube($maxId,$lettreCle);

				$data = array('codemedecin' => $idMedecin,
						'titremed' => $this->input->post('titremed'),
						'nommedecin' => $this->input->post('nommedecin'),
						'prenomsmedecin' => $this->input->post('prenomsmedecin'),
						'contact' => $this->input->post('contact'),
						'email' => $this->input->post('email')
						);

            $query = $this->all_model->add_ligne($table,$data);

            if($query)
            {
            	// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Médecin' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'Le medecin prescripteur a été enrégistré avec succès dans la base de données.';
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'cet enrégistrement existe déjà dans la base de données.';
            }
        }

        echo json_encode($output);
	}
	/////////////////////////////////////
	//////////////////////////////////////
	public function SaveSocieteAssure()
	{
		$output = array('error' => false);

		$table = 'societeassure';
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('nomsocieteassure', 'Nom de la societe assurée', 'trim|required');
			$this->form_validation->set_rules('codeassurance', 'Code assurance', 'trim|required');
			$this->form_validation->set_rules('codeassureur', 'Nom assureur', 'trim|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {
        	// succès : récupération des données passées en _POST

        	$lettreCle = 'SOC';

        	$nomchamp = 'codesocieteassure';
				
			$maxId = $this->all_model->getMaxId($table,$nomchamp);


				//Recherche de la valeur de l'identifiant 
			$idsociete = $this->generateur_identifiant->identifiant_tube($maxId,$lettreCle);
        	
			$data = array('codesocieteassure' => $idsociete,
						'nomsocieteassure' => $this->input->post('nomsocieteassure'),
						'codeassurance' => $this->input->post('codeassurance'),
						'codeassureur' => $this->input->post('codeassureur')
						);

            $query = $this->all_model->add_ligne($table,$data);

            if($query)
            {
            	// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Société' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'La société assurée a été enrégistrée avec succès dans la base de données.';
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'cet enrégistrement existe déjà dans la base de données.';
            }
        }

        echo json_encode($output);
	}
	/////////////////////////////////////
	//////////////////////////////////////
	public function SaveAssurance()
	{
		$output = array('error' => false);

		$table = 'assurance';
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('libelleassurance', 'Nom de l\'assurance', 'trim|required');
			$this->form_validation->set_rules('situationgeo', 'Situation Géographique', 'trim|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {
        	// succès : récupération des données passées en _POST

        	$lettreCle = 'ASS';

        	$nomchamp = 'codeassurance';
				
			$maxId = $this->all_model->getMaxId($table,$nomchamp);


				//Recherche de la valeur de l'identifiant 
			$idAssurance = $this->generateur_identifiant->identifiant_tube($maxId,$lettreCle);
        	
			$data = array('codeassurance' => $idAssurance,
						'libelleassurance' => $this->input->post('libelleassurance'),
						'situationgeo' => $this->input->post('situationgeo')
						);

            $query = $this->all_model->add_ligne($table,$data);

            if($query)
            {
            	// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Assurance' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'L\'assurance a été enrégistrée avec succès dans la base de données.';
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'cet enrégistrement existe déjà dans la base de données.';
            }
        }

        echo json_encode($output);
	}
	/////////////////////////////////////

	/////////////////////////////////////
	public function TauxCouvAssureDeleter($code_taux)
	{
		$table = 'tauxcouvertureassure';
		$id_name = 'idtauxcouv';
		$id = $code_taux ;
		$query = $this->all_model->delete_ligne($table, $id_name, $id);

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['list_tauxCouverture'] = $this->all_model->get_table($table);
		$page_data['page_name'] = 'parametre/TauxCouvAssureRegister';
		$page_data['bandeau'] = 'Gestion des taux de couverture Assure';
		$page_data['titre'] = 'Ajouter un taux de couverture Assure';
		$page_data['titre_2'] = 'Liste des taux de couverture des Assurés';
		$page_data['page_active'] = 'parametrePage';
		$page_data['profil'] = $this->session->userdata('user_profil');

		// Effectuer la journalisation
		        $type_action = 'Suppression' ;

		        $action_effectuee = 'Taux de couverture' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

		$this->session->set_flashdata('good', "Suppression effectuée avec succès!!");
		
		$this->load->view('parametre/index',$page_data);

	}

	/////////////////////////////////////
	public function FiliationDeleter($code_filiation)
	{
		$table = 'filiation';
		$id_name = 'code_filiation';
		$id = $code_filiation ;
		$query = $this->all_model->delete_ligne($table, $id_name, $id);

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
		
        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		
		$page_data['list_filiation'] = $this->all_model->get_table($table);
		$page_data['page_name'] = 'parametre/FiliationRegister';
		$page_data['bandeau'] = 'Gestion des filiations';
		$page_data['titre'] = 'Ajouter une filiation';
		$page_data['titre_2'] = 'Liste des filiations';
		$page_data['page_active'] = 'parametrePage';
		$page_data['profil'] = $this->session->userdata('user_profil');

		// Effectuer la journalisation
		        $type_action = 'Suppression' ;

		        $action_effectuee = 'Filiation' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

		$this->session->set_flashdata('good', "Suppression effectuée avec succès!!");
		
		$this->load->view('parametre/index',$page_data);

	}
	/////////////////////////////////////
	public function ServiceDeleter($code_service)
	{
		$table = 'service';
		$id_name = 'code_service';
		$id = $code_service ;
		$query = $this->all_model->delete_ligne($table, $id_name, $id);

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
		
        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		
		$page_data['list_service'] = $this->all_model->get_table($table);
		$page_data['page_name'] = 'parametre/ServiceRegister';
		$page_data['bandeau'] = 'Gestion des services';
		$page_data['titre'] = 'Ajouter un service';
		$page_data['titre_2'] = 'Liste des services';
		$page_data['page_active'] = 'parametrePage';
		$page_data['profil'] = $this->session->userdata('user_profil');

		$this->session->set_flashdata('good', "Suppression effectuée avec succès!!");
		
		$this->load->view('parametre/index',$page_data);

	}

	/////////////////////////////////////
	public function ActesDeleter($code)
	{
		$table = 'garantie';
		$id_name = 'codgaran';
		$id = $code ;

		$infos_examen = $this->all_model->get_fullrow_bis('examen', 'codgaran', $id);

		$infos_tarifs = $this->all_model->get_fullrow_bis('tarifs', 'codgaran', $id);


		if(empty($infos_examen) && empty($infos_tarifs))
		{
			// Effectuer la journalisation
		        $type_action = 'Suppression' ;

		        $action_effectuee = 'Acte médical' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			$query = $this->all_model->delete_ligne($table, $id_name, $id);

			$flash_feedback = "Suppression effectuée avec succès";

			$this->session->set_flashdata('success', $flash_feedback);
		}
		else
		{
			$flash_feedback = "Suppression impossible, l'acte est déjà utilisé ailleur.";

			$this->session->set_flashdata('error', $flash_feedback);
		}

		redirect('Parametre/ActesRegister','refresh');

	}
	
	/////////////////////////////////////
	public function Assureur_deleter($code)
	{
		$table = 'assureur';
		$id_name = 'codeassureur';
		$id = $code ;

		$infos_societeassure = $this->all_model->get_fullrow_bis('societeassure', 'codeassureur', $id);

		if(empty($infos_societeassure))
		{
			// Effectuer la journalisation
		        $type_action = 'Suppression' ;

		        $action_effectuee = 'Assureur' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			$query = $this->all_model->delete_ligne($table, $id_name, $id);

			$flash_feedback = "Suppression effectuée avec succès";

			$this->session->set_flashdata('success', $flash_feedback);
		}
		else
		{
			$flash_feedback = "Impossible de supprimer cet assueur, il est déjà lié à une société assuré.";

			$this->session->set_flashdata('error', $flash_feedback);
		}

		

		redirect('Parametre/Assureur_register','refresh');

	}
	/////////////////////////////////////
	public function ProfilDeleter($code_profil)
	{
		$table = 'profil';
		$id_name = 'id';
		$id = $code_profil ;
		$query = $this->all_model->delete_ligne($table, $id_name, $id);

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
		
        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		
		$page_data['list_profil'] = $this->all_model->get_table($table);
		$page_data['page_name'] = 'parametre/ProfilRegister';
		$page_data['bandeau'] = 'Gestion des profils';
		$page_data['titre'] = 'Ajouter un profil';
		$page_data['titre_2'] = 'Liste des profils';
		$page_data['page_active'] = 'parametrePage';
		$page_data['profil'] = $this->session->userdata('user_profil');

		$this->session->set_flashdata('good', "Suppression effectuée avec succès!!");
		
		$this->load->view('parametre/index',$page_data);

	}

	

	/////////////////////////////////////
	public function SocieteADeleter($codesocieteassure)
	{
		$table_3 = 'societeassure';
		$id_name = 'codesocieteassure';
		$id = $codesocieteassure ;
		// Effectuer la journalisation
		        $type_action = 'Suppression' ;

		        $action_effectuee = 'Société' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

		$query = $this->all_model->delete_ligne($table_3, $id_name, $id);

		$this->session->set_flashdata('SUCCESSMSG', "Suppression effectuée avec succès!!");

		redirect('Parametre/SocieteARegister','refresh');

	}

	/////////////////////////////////////
	public function ProduitADeleter($codeproduit)
	{
		$table_3 = 'produit_assurance';
		$id_name = 'codeproduit';
		$id = $codeproduit ;

		// Effectuer la journalisation
		        $type_action = 'Suppression' ;

		        $action_effectuee = 'Produit d\'assurance' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

		$query = $this->all_model->delete_ligne($table_3, $id_name, $id);

		$this->session->set_flashdata('SUCCESSMSG', "Suppression effectuée avec succès!!");

		redirect('Parametre/ProduitARegister','refresh');
	}
	
	public function SoinsInfirmierRegister()
	{

			$table_1 = 'typesoinsinfirmiers';

			$table_2 = 'soins_infirmier';

			$page_data['typesoins'] = $this->all_model->get_table($table_1);

			$page_data['list_soins'] = $this->all_model->get_table($table_2);


			$page_data['bandeau'] = 'Gestion des Soins Infirmier';
			$page_data['titre'] = 'Ajouter un soin infirmier';
			$page_data['titre_2'] = 'Liste des Soins Infirmier';
			$page_data['page_active'] = 'soinsInfirmierPage';

			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	        $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Gestion des soins infirmiers';

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Gestion des soins infirmier' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        	$this->render_template('infirmerie/SoinsInfirmierRegister', $page_data);
        
	}

	//////////////////////////////////////
	public function SaveSoinInfirmier()
	{
		$output = array('error' => false);

		$table = 'soins_infirmier';
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('libellesoininfirmier', 'Libellé du soin infirmier', 'trim|required');
			$this->form_validation->set_rules('nomtantsoininfirmier', 'Montant du soin infirmier', 'trim|required');
			$this->form_validation->set_rules('code_typesoins', 'Libellé du type de soin infirmier', 'trim|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {
        	// succès : récupération des données passées en _POST

			$data = array('price' => $this->input->post('nomtantsoininfirmier'),
						'libelle_soins' => $this->input->post('libellesoininfirmier'),
						'code_typesoins' => $this->input->post('code_typesoins')
						);

            $query = $this->all_model->add_ligne($table,$data);

            if($query)
            {
            	// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Soins infirmier' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'Le soin infirmier a été enrégistré avec succès dans la base de données.';
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'cet enrégistrement existe déjà dans la base de données.';
            }
        }

        echo json_encode($output);
	}

	////////////////////////////

	public function UpdateSoinInfirmier()
	{
		$output = array('error' => false);

		$table = 'soins_infirmier';
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('libellesoininfirmier', 'Libellé du soin infirmier', 'trim|required');
			$this->form_validation->set_rules('nomtantsoininfirmier', 'Montant du soin infirmier', 'trim|required');
			$this->form_validation->set_rules('code_typesoins', 'Libellé du type de soin infirmier', 'trim|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {
        	// succès : récupération des données passées en _POST

        	$codesoininfirmier = $this->input->post('codesoininfirmier');

				$data = array('price' => $this->input->post('nomtantsoininfirmier'),
						'libelle_soins' => $this->input->post('libellesoininfirmier'),
						'code_typesoins' => $this->input->post('code_typesoins')
						);

            $query = $this->all_model->update_ligne($table, $data, 'code_soins', $codesoininfirmier);

            if($query)
            {
            	// Effectuer la journalisation
		        $type_action = 'Modification' ;

		        $action_effectuee = 'Soins infirmier' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'Le soin infirmier a été modifié avec succès dans la base de données.';
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'Aucune modification n\'a été effectuée.';
            }
        }

        echo json_encode($output);
	}
	/////////////////////////////////////
	public function SoinInfirmierDeleter($codesoin)
	{
		$table = 'soins_infirmier';
		$id_name = 'code_soins';
		$id = $codesoin ;

		// Effectuer la journalisation
		        $type_action = 'Suppression' ;

		        $action_effectuee = 'Soins infirmier' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

		$query = $this->all_model->delete_ligne($table, $id_name, $id);

		$this->session->set_flashdata('SUCCESSMSG', "Suppression effectuée avec succès!!");

		redirect('Parametre/SoinsInfirmierRegister');
	}
	//////////////////////
	public function SoinInfirmierUpdater($id)
	{
        $table_1 = 'typesoinsinfirmiers';

		$table_2 = 'soins_infirmier';

		$page_data['typesoins'] = $this->all_model->get_table($table_1);

		$page_data['list_soins'] = $this->all_model->get_table($table_2);

		$page_data['soin_update'] = $this->all_model->get_fullrow($table_2, 'code_soins', $id);


		$page_data['bandeau'] = 'Gestion des Soins Infirmier';
		$page_data['titre'] = 'Modifier un soin infirmier';
		$page_data['titre_2'] = 'Liste des Soins Infirmier';
		$page_data['page_active'] = 'soinsInfirmierPage';

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	    $page_data['page_libprofil'] = $UniqueProfil;
	    $page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Gestion des soins infirmiers';

		// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire de modification de soins infirmier' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue

        $this->render_template('infirmerie/SoinsInfirmierUpdater', $page_data);
	}
	
	public function SpecialiteRegister()
	{

			$table_1 = 'specialitemed';

			$page_data['specialite'] = $this->all_model->get_table($table_1);

			$page_data['bandeau'] = 'Gestion des Spécialités';
			$page_data['titre'] = 'Ajouter une spécialité';
			$page_data['titre_2'] = 'Liste des spécialités';
			$page_data['page_active'] = 'SpecialitePage';

			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	        $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter une spécialité';

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire d\'ajout d\'une spécialité' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        	$this->render_template('kameleon/SpecialiteRegister', $page_data);
        
	}

	public function SpecialiteUpdater($id)
	{
		$table_1 = 'specialitemed';

		$page_data['specialite'] = $this->all_model->get_table($table_1);

		$page_data['specialite_update'] = $this->all_model->get_fullrow($table_1, 'codespecialitemed', $id);

		$page_data['bandeau'] = 'Gestion des Spécialités';
		$page_data['titre'] = 'Ajouter une spécialité';
		$page_data['titre_2'] = 'Liste des spécialités';
		$page_data['page_active'] = 'SpecialitePage';

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
			
        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = "Modifier la spécialité";

		// Effectuer la journalisation
		    $type_action = 'Consultation' ;

		    $action_effectuee = 'Formulaire de modification d\'une spécialité' ;

		    $this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue

        $this->render_template('kameleon/SpecialiteUpdater', $page_data);
	}	

	public function SaveSpecialite()
	{
		$output = array('error' => false);

		$table = 'specialitemed';
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('nomspecialite', 'Nom de la spécialité', 'trim|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {
        	// succès : récupération des données passées en _POST

        	$nomspecialite = $this->input->post('nomspecialite') ;

        	$table = 'specialitemed';

			$nomchamp = 'codespecialitemed';
			
			$maxCode = $this->all_model->getMaxId($table,$nomchamp);

			
			if ($maxCode['codespecialitemed'] == '') {

				$codespecialitemed = 'SP001';
			}
			else
			{
				$number = substr($maxCode['codespecialitemed'],-3);
				
				$lastNumber = $number + 1 ;

				$lastNumber ;

				$zeroadd = "".$lastNumber ;

				while (strlen($zeroadd) < 3) {
					
					$zeroadd = "0" . $zeroadd ;
				}
				
				$lastNumber = $zeroadd ; 
				
				$codespecialitemed = 'SP'.$lastNumber;
			}

				$data = array('codespecialitemed' => $codespecialitemed,
						'nomspecialite' => $nomspecialite,
						'libellespecialite' => $nomspecialite
						);

            $query = $this->all_model->add_ligne($table,$data);

            if($query)
            {
            	// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Spécialité' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'La spécialité a été enrégistrée avec succès dans la base de données.';

            	$this->session->set_flashdata('success', "La spécialité a été enrégistrée avec succès dans la base de données");
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'cet enrégistrement existe déjà dans la base de données.';

            	$this->session->set_flashdata('error', "cet enrégistrement existe déjà dans la base de données");
            }
        }

        echo json_encode($output);
	}

	public function UpdateSpecialite()
	{
		$output = array('error' => false);

		$table = 'specialitemed';
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('nomspecialite', 'Nom de la spécialité', 'trim|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {
        	// succès : récupération des données passées en _POST

        	$nomspecialite = $this->input->post('nomspecialite') ;
        	$codespecialitemed = $this->input->post('codespecialitemed') ;

        	$table = 'specialitemed';

			$data = array('nomspecialite' => $nomspecialite,
						'libellespecialite' => $nomspecialite
						);

            $query = $this->all_model->update_ligne($table, $data, 'codespecialitemed', $codespecialitemed);

            if($query)
            {
            	// Effectuer la journalisation
		        $type_action = 'Modification' ;

		        $action_effectuee = 'Spécialité' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'La spécialité a été modifiée avec succès dans la base de données.';

            	$this->session->set_flashdata('success', "La spécialité a été modifiée avec succès dans la base de données");
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'Aucune modification n\'a été effectuée.';

            	$this->session->set_flashdata('error', "Aucune modification n\'a été effectuée.");
            }
        }

        echo json_encode($output);
	}
	
	public function SoinsAsRegister()
	{

			$table_1 = 'typesoins_as';

			$table_2 = 'actes_as';

			$page_data['typesoins'] = $this->all_model->get_table($table_1);

			$page_data['list_soins'] = $this->all_model->get_table($table_2);


			$page_data['bandeau'] = 'Gestion des Soins AS';
			$page_data['titre'] = 'Ajouter un soin AS';
			$page_data['titre_2'] = 'Liste des Soins AS';
			$page_data['page_active'] = 'soinsAsPage';

			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	        $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Gestion des soins AS';

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Gestion des soins AS' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        	$this->render_template('aide_soignant/SoinsAsRegister', $page_data);
        
	}

	//////////////////////////////////////
	public function SaveSoinAs()
	{
		$output = array('error' => false);

		$table = 'actes_as';
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('libellesoinas', 'Libellé du soin AS', 'trim|required');
			$this->form_validation->set_rules('code_typesoins', 'Libellé du type de soin AS', 'trim|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {
        	// succès : récupération des données passées en _POST

			$data = array('libelle_acte' => $this->input->post('libellesoinas'),
						'code_typesoins' => $this->input->post('code_typesoins')
						);

            $query = $this->all_model->add_ligne($table,$data);

            if($query)
            {
            	// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Soins AS' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'Le soin AS a été enrégistré avec succès dans la base de données.';
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'cet enrégistrement existe déjà dans la base de données.';
            }
        }

        echo json_encode($output);
	}

	////////////////////////////

	public function UpdateSoinAs()
	{
		$output = array('error' => false);

		$table = 'actes_as';
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('libellesoinas', 'Libellé du soin AS', 'trim|required');
			$this->form_validation->set_rules('code_typesoins', 'Libellé du type de soin AS', 'trim|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {
        	// succès : récupération des données passées en _POST

        	$codesoinas = $this->input->post('codesoinas');

			$data = array('libelle_acte' => $this->input->post('libellesoinas'),
			'code_typesoins' => $this->input->post('code_typesoins')
			);

            $query = $this->all_model->update_ligne($table, $data, 'code_acte', $codesoinas);

            if($query)
            {
            	// Effectuer la journalisation
		        $type_action = 'Modification' ;

		        $action_effectuee = 'Soins infirmier' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'Le soin AS a été modifié avec succès dans la base de données.';
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'Aucune modification n\'a été effectuée.';
            }
        }

        echo json_encode($output);
	}
	/////////////////////////////////////
	public function SoinAsDeleter($codesoin)
	{
		$table = 'actes_as';
		$id_name = 'code_acte';
		$id = $codesoin ;

		// Effectuer la journalisation
		        $type_action = 'Suppression' ;

		        $action_effectuee = 'Soins AS' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

		$query = $this->all_model->delete_ligne($table, $id_name, $id);

		$this->session->set_flashdata('SUCCESSMSG', "Suppression effectuée avec succès!!");

		redirect('Parametre/SoinsAsRegister');
	}
	//////////////////////
	public function SoinAsUpdater($id)
	{
        $table_1 = 'typesoins_as';

		$table_2 = 'actes_as';

		$page_data['typesoins'] = $this->all_model->get_table($table_1);

		$page_data['list_soins'] = $this->all_model->get_table($table_2);

		$page_data['soin_update'] = $this->all_model->get_fullrow($table_2, 'code_acte', $id);


		$page_data['bandeau'] = 'Gestion des Soins AS';
		$page_data['titre'] = 'Modifier un soin AS';
		$page_data['titre_2'] = 'Liste des Soins AS';
		$page_data['page_active'] = 'soinsAsPage';

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	    $page_data['page_libprofil'] = $UniqueProfil;
	    $page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Gestion des soins AS';

		// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire de modification de soins AS' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue

        $this->render_template('aide_soignant/SoinsAsUpdater', $page_data);
	}

	public function FamilleActesBioRegister()
	{

			$table_1 = 'famille_actes_biologie';

			$page_data['familles'] = $this->all_model->get_table($table_1);

			$page_data['bandeau'] = 'Gestion des Familles d\'Actes de Biologie';
			$page_data['titre'] = 'Ajouter une famille';
			$page_data['titre_2'] = 'Liste des familles d\'Actes de Biologie';
			$page_data['page_active'] = 'FamilleActesBioPage';

			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	        $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter une famille d\'acte de biologie';

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire d\'ajout de famille d\'acte de biologie' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        	$this->render_template('laboratoire/FamilleActesBioRegister', $page_data);
        
	}

	public function FamilleActesBioUpdater($id)
	{
		$table_1 = 'famille_actes_biologie';

		$page_data['familles'] = $this->all_model->get_table($table_1);

		$page_data['famille_actes_update'] = $this->all_model->get_fullrow($table_1, 'id', $id);

		$page_data['bandeau'] = 'Gestion des Familles d\'Actes de Biologie';
		$page_data['titre'] = 'Modifier une famille';
		$page_data['titre_2'] = 'Liste des familles d\'Actes de Biologie';
		$page_data['page_active'] = 'FamilleActesBioPage';

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
			
        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = "Modifier une famille d\'acte de biologie";

		// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire de modification de famille d\'acte de biologie' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue

        $this->render_template('laboratoire/FamilleActesBioUpdater', $page_data);
	}	

	public function SaveFamilleActesBio()
	{
		$output = array('error' => false);

		$table = 'famille_actes_biologie';
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('libelle', 'Nom de la famille', 'trim|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {
        	// succès : récupération des données passées en _POST

        	$libelle = $this->input->post('libelle') ;

        	//SCRIPT DE GENERATION DU CODE DE L'ACTE ***
						do {
								$random_chars="";
								$characters = array(
									"A","B","C","D","E","F","G","H","J","K","L","M",
									"N","P","Q","R","S","T","U","V","W","X","Y","Z",
									"1","2","3","4","5","6","7","8","9");
								$keys = array();
								while(count($keys) < 5) {
									$x = mt_rand(0, count($characters)-1);
									if(!in_array($x, $keys)) 
									{
										$keys[] = $x;
									}
								}

								foreach($keys as $key){
									$random_chars .= $characters[$key];
								}

								$codeacte = 'B'.$random_chars;

								$nbr_res = $this->all_model->get_fullrow($table,'id',$codeacte);

							} while ($nbr_res);
						///FIN DU SCRIPT/***

				$data = array('id' => $codeacte,
						'libelle' => $libelle
						);

            $query = $this->all_model->add_ligne($table,$data);

            if($query)
            {
            	// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Famille Acte de Biologie' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'La famille d\'acte de biologie a été enrégistrée avec succès dans la base de données.';

            	$this->session->set_flashdata('success', "L\'acte a été enrégistréLa famille d\'acte de biologie a été enrégistrée avec succès dans la base de données");
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'cet enrégistrement existe déjà dans la base de données.';

            	$this->session->set_flashdata('error', "cet enrégistrement existe déjà dans la base de données");
            }
        }

        echo json_encode($output);
	}

	public function UpdateFamilleActesBio()
	{
		$output = array('error' => false);

		$table = 'famille_actes_biologie';
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('id', 'Code de la famille', 'trim|required');

			$this->form_validation->set_rules('libelle', 'Nom de la famille', 'trim|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {
        	// succès : récupération des données passées en _POST

        	$id = trim($this->input->post('id')) ;

        	$libelle = trim($this->input->post('libelle')) ;

				$data = array(
						'libelle' => $libelle
						);

            $query = $this->all_model->update_ligne($table, $data, 'id', $id);

            if($query)
            {
            	// Effectuer la journalisation
		        $type_action = 'Modification' ;

		        $action_effectuee = 'Famille Acte de Biologie' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'La famille d\'acte de biologie a été modifiée avec succès dans la base de données.';

            	$this->session->set_flashdata('success', "La famille d\'acte de biologie a été modifiée avec succès dans la base de données");
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'Aucune modification n\'a été effectuée.';

            	$this->session->set_flashdata('error', "Aucune modification n\'a été effectuée.");
            }
        }

        echo json_encode($output);
	}
}
	
