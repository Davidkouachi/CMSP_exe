<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tarifs extends Admin_Controller {
    
    
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

			if ($curr_uri_string != 'tarifs') 
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

	
    public function TarifRegister()
    {
		if(!empty($_POST))
		{

			 // initialisation du validateur du formulaire
		    $this->load->library('form_validation');
		       // définition des règles de validation
		            
		    $this->form_validation->set_rules('codeassurance', 'Assurance', 'trim|required');
		    $this->form_validation->set_rules('codeproduit', 'Produit d\'assurance', 'trim');
		    $this->form_validation->set_rules('codgaran', 'consultation', 'trim|required');
		    $this->form_validation->set_rules('montjour', 'Montant de consultation jour', 'trim|required');
		    $this->form_validation->set_rules('montnuit', 'Montant de consultation nuit', 'trim|required');
		    $this->form_validation->set_rules('montferie', 'Montant de consultation férié', 'trim|required');

		    if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
		    { 
		        // erreur : retour au formulaire

		        $flash_feedback = validation_errors();;

				$this->session->set_flashdata('error', $flash_feedback);

				redirect('Tarifs/TarifRegister/', 'refresh');
		    }
		    else
		    {
			    $table = 'tarifs';

			    $codgaran = $this->input->post('codgaran');

			    $codeassurance = $this->input->post('codeassurance');

			    $codeproduit = $this->input->post('codeproduit');

			    if($codeproduit == "")
			    {
			    	$tarif_double = $this->all_model->get_tarif_double($codgaran,$codeassurance);
			    }
			    else
			    {
			    	$tarif_double = $this->all_model->get_tarif_double_bis($codgaran,$codeassurance,$codeproduit);
			    }

			    

			    if(empty($tarif_double))
			    {
					if($codgaran === 'A4PE1A'){
						$actes_consultations_specialiste = $this->all_model->get_consultations_specialiste();
						if(!empty($actes_consultations_specialiste)){

							foreach ($actes_consultations_specialiste as $cons_specialiste) {
								$infos_tarif = $this->all_model->get_fullrow($table, 'codgaran', $cons_specialiste['codgaran']);

								if(empty($infos_tarif)){
									$data = array('codeassurance' => $this->input->post('codeassurance'),
									'codgaran' => $cons_specialiste['codgaran'],
									'montjour' => $this->input->post('montjour'),
									'montnuit' => $this->input->post('montnuit'),
									'montferie' => $this->input->post('montferie'),
									'forfait' => 0,
									'codeproduit' => $this->input->post('codeproduit')
									);

									// Effectuer la journalisation
									$type_action = 'Ajout' ;

									$action_effectuee = 'Tarifs' ;

									$this->control->journalisation($type_action,$action_effectuee) ;

									$query = $this->all_model->add_ligne($table, $data);
								}
							}

							$flash_feedback = 'Le tarif a été enregistré avec succès';

							$this->session->set_flashdata('success', $flash_feedback);

							redirect('Tarifs/TarifRegister/', 'refresh');

						}
					}else{
						$data = array('codeassurance' => $this->input->post('codeassurance'),
						'codgaran' => $this->input->post('codgaran'),
						'montjour' => $this->input->post('montjour'),
						'montnuit' => $this->input->post('montnuit'),
						'montferie' => $this->input->post('montferie'),
						'forfait' => 0,
						'codeproduit' => $this->input->post('codeproduit')
						);

						// Effectuer la journalisation
						$type_action = 'Ajout' ;

						$action_effectuee = 'Tarifs' ;

						$this->control->journalisation($type_action,$action_effectuee) ;

						$query = $this->all_model->add_ligne($table, $data);
					
						$flash_feedback = 'Le tarif a été enregistré avec succès';

						$this->session->set_flashdata('success', $flash_feedback);

						redirect('Tarifs/TarifRegister/', 'refresh');

					}
			    }
			    else
			    {
			    	$flash_feedback = 'Désolé ce tarif a déjà été enregistré.';

					$this->session->set_flashdata('error', $flash_feedback);

					redirect('Tarifs/TarifRegister/', 'refresh');
			    }

			    

			}
		}
		else
		{	
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
			
            $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'TarifRegister';
			$page_data['page_active'] = 'factHospitPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter un Tarif';
			//$page_data['garantieList'] = $this->all_model->get_fullrow_bis('garantie','codtypgar','CONS');
			$page_data['garantieList'] = $this->all_model->get_table('garantie');
			$page_data['assuranceList'] = $this->all_model->get_table('assurance');

			$page_data['produit'] = $this->all_model->get_table('produit_assurance');

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire d\'ajout de tarifs' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

			$this->render_template('kameleon/TarifRegister', $page_data);
		}
    }

    public function TarifUpdater()
    {
		if(!empty($_POST))
		{

			 // initialisation du validateur du formulaire
		    $this->load->library('form_validation');
		       // définition des règles de validation
		            
		    $this->form_validation->set_rules('codeassurance', 'Assurance', 'trim|required');
		    $this->form_validation->set_rules('codgaran', 'consultation', 'trim|required');
		    $this->form_validation->set_rules('montjour', 'Montant de consultation jour', 'trim|required');
		    $this->form_validation->set_rules('montnuit', 'Montant de consultation nuit', 'trim|required');
		    $this->form_validation->set_rules('montferie', 'Montant de consultation férié', 'trim|required');
		    $this->form_validation->set_rules('codeproduit', 'Produit d\'assurance', 'trim');

		    if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
		    { 
		        // erreur : retour au formulaire

		        $flash_feedback = validation_errors();;

				$this->session->set_flashdata('error', $flash_feedback);

				redirect('Tarifs/TarifRegister/', 'refresh');
		    }
		    else
		    {
			    $table = 'tarifs';

				$codgaran = $this->input->post('codgaran') ;

				if($codgaran === 'A4PE1A'){
					$actes_consultations_specialiste = $this->all_model->get_consultations_specialiste();
					if(!empty($actes_consultations_specialiste)){

						foreach ($actes_consultations_specialiste as $cons_specialiste) {
							$infos_tarif = $this->all_model->get_fullrow($table, 'codgaran', $cons_specialiste['codgaran']);

							if(empty($infos_tarif)){
								$data = array('codeassurance' => $this->input->post('codeassurance'),
								'codgaran' => $cons_specialiste['codgaran'],
								'montjour' => $this->input->post('montjour'),
								'montnuit' => $this->input->post('montnuit'),
								'montferie' => $this->input->post('montferie'),
								'forfait' => 0,
								'codeproduit' => $this->input->post('codeproduit')
								);

								$id_name = 'idtarif' ;

								$id = $cons_specialiste['idtarif'] ;

								$query = $this->all_model->update_ligne($table, $data, $id_name, $id);
								
								$flash_feedback = 'Le tarif a été modifié avec succès';

									// Effectuer la journalisation
								$type_action = 'Modification' ;

								$action_effectuee = 'Tarifs' ;

								$this->control->journalisation($type_action,$action_effectuee) ;

									
							}
						}

						$this->session->set_flashdata('success', $flash_feedback);

						redirect('Tarifs/search_tarifs/', 'refresh');

					}
				}else{

			    	$data = array('codeassurance' => $this->input->post('codeassurance'),
					'codgaran' => $this->input->post('codgaran'),
					'montjour' => $this->input->post('montjour'),
					'montnuit' => $this->input->post('montnuit'),
					'montferie' => $this->input->post('montferie'),
					'forfait' => 0,
					'codeproduit' => $this->input->post('codeproduit')
					);

					$id_name = 'idtarif' ;

					$id = $this->input->post('idtarif') ;

				    $query = $this->all_model->update_ligne($table, $data, $id_name, $id);
				  
					$flash_feedback = 'Le tarif a été modifié avec succès';

					// Effectuer la journalisation
			$type_action = 'Modification' ;

			$action_effectuee = 'Tarifs' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

					$this->session->set_flashdata('success', $flash_feedback);

					redirect('Tarifs/search_tarifs/', 'refresh');
				}
			}
		}
		
    }



    public function search_tarifs()
	{

		$page_data['assurances'] = $this->all_model->get_table('assurance');

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_active'] = 'factHospitPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Visualiser les Tarifs d\'une assurance';

		$page_data['idAns'] = 'tarifs';

		$page_data['namePg'] = 'fetchTarifsData';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Page de visualisation des tarifs' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
		
			
		// affichage de la vue

		$this->render_template('kameleon/search_tarifs', $page_data);
	}

	public function update_tarif($idtarif)
	{

		$page_data['garantieList'] = $this->all_model->get_table('garantie');
		$page_data['assuranceList'] = $this->all_model->get_table('assurance');

		$page_data['produit'] = $this->all_model->get_table('produit_assurance');

		$page_data['infos_tarifs'] = $this->all_model->get_fullrow('tarifs','idtarif',$idtarif);

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_active'] = 'factHospitPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Modifier des tarifs';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire de modification des tarifs' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
		
			
		// affichage de la vue

		$this->render_template('kameleon/TarifUpdater', $page_data);
	}

	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchOrdersData($codeassurance = '')
	{
		$result = array('data' => array());

		$data = $this->all_model->get_fullrow_bis('tarifs','codeassurance',$codeassurance);

		foreach ($data as $key => $value) {

			$infos_garantie = $this->all_model->get_fullrow('garantie', 'codgaran', $value['codgaran']);

			$infos_assurance = $this->all_model->get_fullrow('assurance', 'codeassurance', $value['codeassurance']);

			if($value['codeproduit'] != '')
			{
				$infos_produit = $this->all_model->get_fullrow('produit_assurance', 'codeproduit', $value['codeproduit']);

				$libelleproduit = $infos_produit['libelleproduit'];
			}
			else
			{
				$libelleproduit = '' ;
			}

			

			// button
			$buttons = '';

			//if(in_array('updateOrder', $this->permission)) {
				$buttons .= '<a href="'.base_url('Tarifs/update_tarif/'.$value['idtarif']).'"><i class="fa fa-pencil"></i></a>';
			//}

			//if(in_array('deleteOrder', $this->permission)) {
				$buttons .= ' | <a href="#" onclick="removeFunc('.$value['idtarif'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></a>';
			//}

			$result['data'][$key] = array(
				$value['codgaran'],
				$infos_garantie['libgaran'],
				$value['montjour'],
				$value['montnuit'],
				$value['montferie'],
				$infos_assurance['libelleassurance'],
				$libelleproduit,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	/*
	* It removes the data from the database
	* and it returns the response into the json format
	*/
	public function remove()
	{
		/*if(!in_array('deleteOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }*/

		$order_id = $this->input->post('order_id');

        $response = array();
        if($order_id) {
            $delete = $this->all_model->remove_tarif($order_id);
            if($delete == true) {

            	// Effectuer la journalisation
			$type_action = 'Suppression' ;

			$action_effectuee = 'Tarifs'.' '.$order_id ;

			$this->control->journalisation($type_action,$action_effectuee) ;

                $response['success'] = true;
                $response['messages'] = "Suppression effectuée avec succès"; 
            }
            else {
                $response['success'] = false;
                $response['messages'] = "Erreur dans la base de données lors de la suppression des informations";
            }
        }
        else {
            $response['success'] = false;
            $response['messages'] = "Actualiser à nouveau la page !!";
        }

        echo json_encode($response); 
	}


	/*****LIST OF BED, MANAGE THIER TYPES********/

	function manage_bedroom($param1 = '', $param2 = '', $param3 = '')

	{

		if ($param1 == 'create') {

			$data['nomchambre']  = $this->input->post('nomchambre');

			$data['prixchambre']        = $this->input->post('prixchambre');

			$data['nbredelit'] = $this->input->post('nbredelit');

			$this->db->insert('chambrehospit', $data);

			$this->session->set_flashdata('flash_message', '');

			redirect(base_url() . 'rapport_divers/manage_bedroom', 'refresh');

		}

		if ($param1 == 'edit' && $param2 == 'do_update') {

			$data['nomchambre']  = $this->input->post('nomchambre');

			$data['prixchambre']        = $this->input->post('prixchambre');

			$data['nbredelit']      = $this->input->post('nbredelit');


			$this->db->where('codechbre', $param3);

			$this->db->update('chambrehospit', $data);

			$this->session->set_flashdata('flash_message', 'account_updated');

			redirect(base_url() . 'rapport_divers/manage_bedroom', 'refresh');

			

		} else if ($param1 == 'edit') {

			$page_data['edit_profile'] = $this->db->get_where('chambrehospit', array(

				'codechbre' => $param2

			))->result_array();

		}

		if ($param1 == 'view_bed_history') {

			$page_data['view_bed_history_id'] = $this->db->get_where('bed_allotment', array(

				'bed_id' => $param2

			))->result_array();

		}

		if ($param1 == 'delete') {

			$this->db->where('codechbre', $param2);

			$this->db->delete('chambrehospit');

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

		$page_data['page_active'] = "factHospitPage";

		$page_data['page_s_title'] = 'Page de gestion des chambres d\'hospitalisation';

			// affichage de la vue

		$this->render_template('kameleon/manage_bedroom', $page_data);

	}

}
	
