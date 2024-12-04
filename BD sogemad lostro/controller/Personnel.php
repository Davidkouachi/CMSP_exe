<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Personnel extends Admin_Controller {
    
    
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

			if ($curr_uri_string != 'personnel') 
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
	
	// OBTENTION DE LA LISTE DE TOUS LES PERSONNELS

	public function PersonnelList()
	{
		// APPEL DU MODEL ADEQUAT POUR LA LISTE DU PERSONNEL

			$PersonnelList = $this->PersonnelModel->getPersonnel();			
			
		
			// UTILISATION DU RESULTAT PROVENANT DE LA REQUETE D'OBTENTION DE LA LISTE
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'PersonnelList';
			$page_data['page_active'] = 'PersonnelPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Liste du Personnel';
			$page_data['Personnel_list'] = $PersonnelList ;

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Liste du personnel' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
			
			// affichage de la vue

        	$this->render_template('kameleon/PersonnelList', $page_data);
	}
	
	// ENREGISTREMENT D'UN PERSONNEL	
    public function PersonnelRegister()
    {
		if(!empty($_POST))
		{

			$nomprenom = $this->input->post('nom').' '.$this->input->post('prenom') ;

			$maxMat = $this->PersonnelModel->getPersonnel_MaxMat();

			$maxMat['matricule'] ;

			if ($maxMat['matricule'] == '') {

				$matricule = 'P001';
			}
			else
			{
				$number = substr($maxMat['matricule'],-3);
				
				$lastNumber = $number + 1 ;

				$lastNumber ;

				$zeroadd = "".$lastNumber ;

				while (strlen($zeroadd) < 3) {
					
					$zeroadd = "0" . $zeroadd ;
				}
				
				$lastNumber = $zeroadd ; 
				
				$matricule = 'P'.$lastNumber;
			}


			$data =  array('matricule' => $matricule,
					'typepiece' => $this->input->post('typepiece'),
					'nopiece' => $this->input->post('nopiece'),
					'civilite' => $this->input->post('civilite'),
					'nom' => $this->input->post('nom'),
					'prenom' => $this->input->post('prenom'),
					'nomprenom' => $nomprenom,
					'datenais' => $this->input->post('datenais'),
					'profession' => $this->input->post('profession'),
					'niveau' => $this->input->post('niveau'),
					'diplome' => $this->input->post('diplome'),
					'residence' => $this->input->post('residence'),
					'dateenregistre' => date('Y-m-d'),
					'cel' => $this->input->post('cel'),
					'contacturgence' => $this->input->post('contacturgence'),
					'email' => $this->input->post('email'),
					'service' => $this->input->post('service'),
					'typecontrat' => $this->input->post('typecontrat'),
					'datecontrat' => $this->input->post('datecontrat'),
					'datefincontrat' => $this->input->post('datefincontrat'),
					'paye' => $this->input->post('paye')
					);
			$this->PersonnelModel->PersonnelRegister($data);
			$this->session->set_flashdata('SUCCESSMSG', "Enregistrement effectué avec succès!!");
			
			$ServiceList = $this->PersonnelModel->getService();
			$ContratList = $this->PersonnelModel->getContrat();
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['page_libprofil'] = $UniqueProfil;	
			$page_data['page_name'] = 'PersonnelRegister';
			$page_data['page_active'] = 'PersonnelPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter un Personnel';
			$page_data['Service_list'] = $ServiceList ;
			$page_data['Contrat_list'] = $ContratList ;

			// Effectuer la journalisation
			$type_action = 'Ajout' ;

			$action_effectuee = 'Membre du personnel';

			$this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        	redirect('Personnel/PersonnelList','refresh');
		}
		else
		{	
		    $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
			$ServiceList = $this->PersonnelModel->getService();
			$ContratList = $this->PersonnelModel->getContrat();
			
            $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'PersonnelRegister';
			$page_data['page_active'] = 'PersonnelPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter un Personnel';
			$page_data['Service_list'] = $ServiceList ;
			$page_data['Contrat_list'] = $ContratList ;

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire d\'ajout de membres du personnel';

			$this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        	$this->render_template('kameleon/PersonnelRegister', $page_data);
		}
    }
	
	// MODIFICATION D'UN PERSONNEL

    public function PersonnelUpdater($id = ''){
		 
		  if(!empty($_POST))
		{
			$data =  array('matricule' => $this->input->post('matricule'),
					'typepiece' => $this->input->post('typepiece'),
					'nopiece' => $this->input->post('nopiece'),
					'civilite' => $this->input->post('civilite'),
					'nom' => $this->input->post('nom'),
					'prenom' => $this->input->post('prenom'),
					'nomprenom' => $this->input->post('nom').' '.$this->input->post('prenom'),
					'datenais' => $this->input->post('datenais'),
					'profession' => $this->input->post('profession'),
					'niveau' => $this->input->post('niveau'),
					'diplome' => $this->input->post('diplome'),
					'residence' => $this->input->post('residence'),
					'cel' => $this->input->post('cel'),
					'contacturgence' => $this->input->post('contacturgence'),
					'email' => $this->input->post('email'),
					'service' => $this->input->post('service'),
					'typecontrat' => $this->input->post('typecontrat'),
					'datecontrat' => $this->input->post('datecontrat'),
					'datefincontrat' => $this->input->post('datefincontrat'),
					'paye' => $this->input->post('paye')
					);

			// Effectuer la journalisation
			$type_action = 'Modification' ;

			$action_effectuee = 'Membre du personnel'.' '.$matricule;

			$this->control->journalisation($type_action,$action_effectuee) ;
			  
			// RECUPERATION DE L'IDENTIFIANT DE L'UTILISATEUR A MODIFIER

			$matricule = $this->input->post('matricule');

			// APPEL DU MODEL ADEQUAT POUR LA MODIFICATION

			 $this->all_model->update_ligne('employes', $data, 'matricule', $matricule);

			// UTILISATION DU RESULTAT PROVENANT DE LA REQUETE DE MODIFICATION

			$this->session->set_flashdata('SUCCESSMSG', "Modification effectuée avec succès !!!");
			
			redirect('Personnel/PersonnelList','refresh');

		}
		else
		{	
			//$this->session->set_flashdata('ECHECMSG', "Veuillez renseigner les valeurs à modifier avant de soumettre le formulaire.");
			
			// APPEL DU MODEL ADEQUAT POUR LA MODIFICATION
			
			$id = $id;
			
			$Personnelinfo = $this->all_model->get_fullrow('employes', 'matricule', $id);


			$page_data['Service_list'] = $this->PersonnelModel->getService();
			$page_data['Contrat_list'] = $this->PersonnelModel->getContrat();
			
			
			$UniqueProfil = $this->UserModel->get_UniqueProfil($id);

			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['page_libprofil'] = $UniqueProfil;
			
			$page_data['Personnelinfo'] = $Personnelinfo;
			$page_data['UniqueProfil'] = $UniqueProfil;
			$page_data['page_name'] = 'PersonnelUpdater';
			$page_data['page_active'] = 'PersonnelPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Modifier les informations d\'un employé';

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire de modification d\'un membre du personnel';

			$this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        	$this->render_template('kameleon/PersonnelUpdater', $page_data);
		}

	 }
	 
	  public function PersonnelDeleter($id)
	{
		$PersonnelDeleteId = $id ;

		// Effectuer la journalisation
			$type_action = 'Suppression' ;

			$action_effectuee = 'Membre du personnel'.' '.$id;

			$this->control->journalisation($type_action,$action_effectuee) ;
		// APPEL DU MODEL ADEQUAT POUR LA SUPPRESSION

			$this->PersonnelModel->PersonnelDeleter($id);

			$PersonnelList = $this->PersonnelModel->getPersonnel();

		// UTILISATION DU RESULTAT PROVENANT DE LA REQUETE DE SUPPRESSION

			$this->session->set_flashdata('SUCCESSMSG', "Suppression effectuée avec succès !!!");

			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'PersonnelList';
			$page_data['Personnel_list'] = $PersonnelList ;
			$page_data['page_active'] = 'PersonnelPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Liste du Personnel';
			
			// affichage de la vue

        	redirect('Personnel/PersonnelList','refresh');
	}


   }
	
