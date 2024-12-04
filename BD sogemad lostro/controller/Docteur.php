<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Docteur extends Admin_Controller {
    
    
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

			if ($curr_uri_string != 'docteur') 
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

// OBTENTION DE LA LISTE DE TOUS LES MEDECIN

	public function DocteurList()
	{
		// APPEL DU MODEL ADEQUAT POUR LA LISTE DU PERSONNEL

			$DocteurList = $this->DocteurModel->getDocteur();			
			
		
			// UTILISATION DU RESULTAT PROVENANT DE LA REQUETE D'OBTENTION DE LA LISTE
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'DocteurList';
			$page_data['page_active'] = 'PersonnelPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Liste des Médecins';
			$page_data['Docteur_list'] = $DocteurList ;

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Liste des médecins' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;
			
			// affichage de la vue

			$this->render_template('kameleon/DocteurList', $page_data);
	}
	
// ENREGISTREMENT D'UN MEDECIN

    public function DocteurRegister()
    {    	

		if(!empty($_POST))
		{
			$maxCodM = $this->DocteurModel->getDocteur_maxCodM();

			$maxCodM['codemedecin'] ;

			if ($maxCodM['codemedecin'] == '') {

				$codemedecin = 'MED001';
			}
			else
			{
				$number = substr($maxCodM['codemedecin'],-3);
				
				$lastNumber = $number + 1 ;

				$lastNumber ;

				$zeroadd = "".$lastNumber ;

				while (strlen($zeroadd) < 3) {
					
					$zeroadd = "0" . $zeroadd ;
				}
				
				$lastNumber = $zeroadd ; 
				
				$codemedecin = 'MED'.$lastNumber;
			}		
						
			$nomprenomsmed = $this->input->post('titremed').' '.$this->input->post('nommedecin').' '.$this->input->post('prenomsmedecin');

    		$Docteur = $this->input->post('codemedecin');
			
			//move_uploaded_file($_FILES['photo']['tmp_name'], 'uploads/Medecin/' . $Docteur . '.jpg');
			
			$data =  array('codemedecin' => $codemedecin,
					'titremed' => $this->input->post('titremed'),
					'nommedecin' => $this->input->post('nommedecin'),
					'prenomsmedecin' => $this->input->post('prenomsmedecin'),
					'nomprenomsmed' => $nomprenomsmed,
					'codespecialitemed' => $this->input->post('codespecialitemed'),
					'numordremed' => $this->input->post('numordremed'),
					'contact' => $this->input->post('contact'),
					'dateservice' => $this->input->post('dateservice'),
					'email' => $this->input->post('email'),
					'actif' => $this->input->post('actif')
					);

			$this->DocteurModel->DocteurRegister($data);

			// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Médecin' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			$this->session->set_flashdata('SUCCESSMSG', "Enregistrement effectué avec succès !!!");

			// affichage de la vue

			redirect('Docteur/DocteurList','refresh');
		}
		else
		{	
			
			$SpecialiteList = $this->DocteurModel->getSpecialite();
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['page_libprofil'] = $UniqueProfil;

			$page_data['page_name'] = 'DocteurRegister';
			$page_data['page_active'] = 'PersonnelPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter un Médecin';
			$page_data['Specialite_list'] = $SpecialiteList ;

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire d\'ajout de médecins' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;
			
			// affichage de la vue

			$this->render_template('kameleon/DocteurRegister', $page_data);
		}
    }	

    // MODIFICATION D'UN PERSONNEL

    public function DocteurUpdater($id = ''){
		 
		  if(!empty($_POST))
		{
			$data =  array('codemedecin' => $this->input->post('codemedecin'),
					'titremed' => $this->input->post('titremed'),
					'nommedecin' => $this->input->post('nommedecin'),
					'prenomsmedecin' => $this->input->post('prenomsmedecin'),
					'nomprenomsmed' => $this->input->post('nommedecin').' '.$this->input->post('prenomsmedecin'),
					'codespecialitemed' => $this->input->post('codespecialitemed'),
					'numordremed' => $this->input->post('numordremed'),
					'contact' => $this->input->post('contact'),
					'fraisdeconsultant' => $this->input->post('fraisdeconsultant'),
					'dateservice' => $this->input->post('dateservice'),
					'email' => $this->input->post('email'),
					'actif' => $this->input->post('actif')
					);
			  
			// RECUPERATION DE L'IDENTIFIANT DE L'UTILISATEUR A MODIFIER

			$codemedecin = $this->input->post('codemedecin');

			// APPEL DU MODEL ADEQUAT POUR LA MODIFICATION

			 $this->all_model->update_ligne('medecin', $data, 'codemedecin', $codemedecin);

			// UTILISATION DU RESULTAT PROVENANT DE LA REQUETE DE MODIFICATION

			 // Effectuer la journalisation
		        $type_action = 'Modification' ;

		        $action_effectuee = 'Médecin' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			$this->session->set_flashdata('SUCCESSMSG', "Modification effectuée avec succès !!!");
			
			redirect('Docteur/DocteurList','refresh');

		}
		else
		{	
			//$this->session->set_flashdata('ECHECMSG', "Veuillez renseigner les valeurs à modifier avant de soumettre le formulaire.");
			
			// APPEL DU MODEL ADEQUAT POUR LA MODIFICATION
			
			$id = $id;
			
			$Docteurinfo = $this->all_model->get_fullrow('medecin', 'codemedecin', $id);


			$page_data['Specialite_list'] = $this->DocteurModel->getSpecialite();
			
			
			$UniqueProfil = $this->UserModel->get_UniqueProfil($id);

			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['page_libprofil'] = $UniqueProfil;
			
			$page_data['Docteurinfo'] = $Docteurinfo;
			$page_data['UniqueProfil'] = $UniqueProfil;
			$page_data['page_name'] = 'PersonnelUpdater';
			$page_data['page_active'] = 'PersonnelPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Modifier les informations d\'un employé';

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire de modification de médecin' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        	$this->render_template('kameleon/DocteurUpdater', $page_data);
		}

	 }
	
	public function DocteurDeleter($id)
	{
		$DocteurDeleteId = $id ;
		// APPEL DU MODEL ADEQUAT POUR LA SUPPRESSION

			$this->DocteurModel->DocteurDeleter($id);

			// Effectuer la journalisation
		        $type_action = 'Suppression' ;

		        $action_effectuee = 'Médecin' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

		// UTILISATION DU RESULTAT PROVENANT DE LA REQUETE DE SUPPRESSION

			$this->session->set_flashdata('SUCCESSMSG', "Suppression effectuée avec succès !!!");

			// affichage de la vue

			redirect('Docteur/DocteurList','refresh');
	}
	
	
   }
	
