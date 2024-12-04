<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cautions extends Admin_Controller {
    
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

			if ($curr_uri_string != 'cautions') 
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
	public function index()
	{
		$table = 'cautions';
		$page_data['cautions'] = $this->all_model->get_table($table);
		$page_data['patients'] = $this->all_model->get_table('patient');
		$page_data['page_s_title'] = 'Ajouter une caution';
		$page_data['bandeau'] = 'Gestion des cautions';
		$page_data['titre'] = 'Ajouter une caution';
		$page_data['titre_2'] = 'Liste des cautions';
		$page_data['page_active'] = 'CautionsPage';
		$page_data['profil'] = $this->session->userdata('user_profil');

		// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire d\'ajout de cautions' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;
		
		 $this->render_template('cautions/cautionRegister', $page_data);
	}



	

	public function SaveCaution()
	{
		$output = array('error' => false);

		$table = 'cautions';
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('patient', 'Patient', 'trim|required');
			$this->form_validation->set_rules('montant_verse', 'Montant versé', 'trim|required');
			$this->form_validation->set_rules('date_operation', 'Date de l\'opération (Versement)', 'trim|required');
			$this->form_validation->set_rules('motif_caution', 'Motif de la caution', 'trim|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {
        	// succès : récupération des données passées en _POST

        	$patient = $this->input->post('patient') ;

        	$montant_verse = $this->input->post('montant_verse') ;

        	$date_operation = $this->input->post('date_operation') ;

        	$user = $this->session->userdata('user_name') ;

        	$motif_caution = $this->input->post('motif_caution') ;


        	//SCRIPT DE GENERATION DU CODE DE L'ACTE ***
						do {
								$random_chars="";
								$characters = array(
									"A","B","C","D","E","F","G","H","J","K","L","M",
									"N","P","Q","R","S","T","U","V","W","X","Y","Z",
									"1","2","3","4","5","6","7","8","9");
								$keys = array();
								while(count($keys) < 4) {
									$x = mt_rand(0, count($characters)-1);
									if(!in_array($x, $keys)) 
									{
										$keys[] = $x;
									}
								}

								foreach($keys as $key){
									$random_chars .= $characters[$key];
								}

								$id_caution = 'C'.$random_chars;

								$nbr_res = $this->all_model->get_fullrow('cautions','id_caution',$id_caution);

							} while ($nbr_res);
						///FIN DU SCRIPT/***

			  
			$dernier_journal = $this->all_model->get_max_numjournal();

			if($dernier_journal['numjournal'] == "")
			{
			  	$numjournal = 1 ;
			}
			else
			{
			  	$numjournal = $dernier_journal['numjournal'] + 1 ;
			}


				$data = array('id_caution' => $id_caution,
						'idenregistremetpatient' => $patient,
						'date_versement' => $date_operation,
						'montant_verse' => $montant_verse,	
						'motif_caution' => $motif_caution,
						'user_versement' => $user
						);

				$data_caisse = array('nopiece' => $numjournal,
						'type' => 'entree',
						'libelle' => $motif_caution,
						'montant' => $montant_verse,
						'dateop' => $date_operation,
						'datecreat' => date('Y-m-d'),
						'login' => $this->session->userdata('user_name'),
						'beneficiaire' => $patient
						);

				$data_journal =  array('idenregistremetpatient' => $patient,
								'date' => $date_operation,
								'numrecu' => $id_caution,
								'montant_recu' => $montant_verse,
								'numjournal' => $numjournal,
								'type_action' => 0
								);

            $query = $this->all_model->add_ligne($table,$data);

            $query_1 = $this->all_model->add_ligne('journal',$data_journal);

            $query_2 = $this->all_model->add_ligne('caisse',$data_caisse);

            // Mise à jour du solde en caisse (caisse_resume)
			/***************/
				$solde_caisse = $this->all_model->get_solde_caisse(date('Y-m-d')) ;
								
				$nouveau_solde = $solde_caisse['mtcaisse'] + $montant_verse ;
								
				$data_caisse_resume =  array('mtcaisse' => $nouveau_solde);
								
				$this->all_model->update_ligne('caisse_resume', $data_caisse_resume, 'idcaisse', $solde_caisse['idcaisse']) ;
								
			/***************/

            if($query)
            {
            	// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Caution' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'La caution a été enrégistrée avec succès dans la base de données.';

            	$this->session->set_flashdata('success', "La caution a été enrégistrée avec succès dans la base de données");
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

	public function UpdateCaution()
	{
		$output = array('error' => false);

		$table = 'cautions';
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('patient', 'Patient', 'trim|required');
			$this->form_validation->set_rules('montant_verse', 'Montant versé', 'trim|required');
			$this->form_validation->set_rules('date_operation', 'Date de l\'opération (Versement)', 'trim|required');
			$this->form_validation->set_rules('motif_caution', 'Motif de la caution', 'trim|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {
        	// succès : récupération des données passées en _POST

        	$patient = $this->input->post('patient') ;

        	$montant_verse = $this->input->post('montant_verse') ;

        	$date_operation = $this->input->post('date_operation') ;

        	$id_caution = $this->input->post('caution') ;

        	$motif_caution = $this->input->post('motif_caution') ;

        	$user = $this->session->userdata('user_name') ;

        	$infos_cation = $this->all_model->get_fullrow('cautions','id_caution',$id_caution);



			$data = array('id_caution' => $id_caution,
						'idenregistremetpatient' => $patient,
						'date_versement' => $date_operation,
						'montant_verse' => $montant_verse,
						'motif_caution' => $motif_caution
						);

			$data_caisse = array(
						'libelle' => $motif_caution,
						'montant' => $montant_verse,
						'dateop' => $date_operation,
						'datecreat' => date('Y-m-d'),
						'login' => $this->session->userdata('user_name'),
						'beneficiaire' => $patient
						);

				$data_journal =  array('idenregistremetpatient' => $patient,
								'date' => $date_operation,
								'numrecu' => $id_caution,
								'montant_recu' => $montant_verse
								);

            $query = $this->all_model->update_ligne($table, $data, 'id_caution', $id_caution);

            if(!empty($infos_cation))
        	{
        		$infos_journal = $this->all_model->get_fullrow('journal','numrecu',$infos_cation['id_caution']);

        		if(!empty($infos_journal))
        		{
	        		$query_1 = $this->all_model->update_ligne('journal', $data_journal, 'numjournal', $infos_journal['numjournal']);

	            	$query_2 = $this->all_model->update_ligne('caisse', $data_caisse, 'nopiece', $infos_journal['numjournal']);
            	}
        	}

            if($query)
            {
            	// Effectuer la journalisation
		        $type_action = 'Modification' ;

		        $action_effectuee = 'Caution' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'La caution a été modifiée avec succès dans la base de données.';

            	$this->session->set_flashdata('success', "La caution a été modifiée avec succès dans la base de données");
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'Aucune modification n\'a été effectuée.';

            	$this->session->set_flashdata('error', "Aucune modification n'a été effectuée.");
            }
        }

        echo json_encode($output);
	}

	
	//////////////////////
	public function cautionUpdater($id)
	{
        $table_1 = 'cautions';

		$table_2 = 'patient';

		$page_data['cautions'] = $this->all_model->get_table($table_1);

		$page_data['patients'] = $this->all_model->get_table($table_2);

		$page_data['caution_update'] = $this->all_model->get_fullrow($table_1, 'id_caution', $id);

		$page_data['page_s_title'] = 'Modifier une caution';
		$page_data['bandeau'] = 'Gestion des cautions';
		$page_data['titre'] = 'Modifier une caution';
		$page_data['titre_2'] = 'Liste des cautions';
		$page_data['page_active'] = 'CautionsPage';

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	    $page_data['page_libprofil'] = $UniqueProfil;
	    $page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';

		// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire de modification d\'une caution' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue

        $this->render_template('cautions/cautionUpdater', $page_data);
	}
}
	
