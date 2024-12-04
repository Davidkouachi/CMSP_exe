<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dossier extends Admin_Controller {
    
    
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

			if ($curr_uri_string != 'dossier') 
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

	
	public function ViewDossier()
	{
		$data['getDossier'] = $this->all_model->get_table();

		// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Liste des dossiers' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;
		
		$this->load->view('view_Dossier',$data);
	}
		
    public function DossierRegister()
    {
		if(!empty($_POST))
		{
			$codetypedossier = $this->input->post('codetypedossier');

		        
		        /*************DOSSIER PATIENT*******************/
			
			    $codpatient = $this->input->post('idenregistremetpatient') ;
			
			    $infos_dossier = $this->all_model->get_dossier_patient($codpatient,$codetypedossier) ;
			    
			    if(empty($infos_dossier))
			    {
        			$table = "dossierpatient";
        			$id_max = "numdossier";
        			$id_where = "codetypedossier";
        			$id_val = $codetypedossier;
        
        			$dossier = $this->all_model->getMaxIdWhere($table,$id_max,$id_where,$id_val);
        
        			$deb_annee = substr(date('Y'), 2,3);
        
        
        			if(empty($dossier))
        			{
        				$numdossier = $codetypedossier.$deb_annee.'0001';
        			}
        			else
        			{
        				$annee_doss = substr($dossier[$id_max], 2,2);
        
        				if($deb_annee > $annee_doss)
        				{
        					$annee = $deb_annee ;
        
        					$numdossier = $codetypedossier.$annee.'0001';
        					
        				}
        
        				if($deb_annee == $annee_doss)
        				{
        					$annee = $deb_annee ;
        
        					$num_chrono = substr($dossier[$id_max], 4,7);
        
        					$lastNumber = $num_chrono + 1 ;
        
        					$zeroadd = "".$lastNumber ;
        
        					while (strlen($zeroadd) < 4) {
        						
        						$zeroadd = "0" . $zeroadd ;
        					}
        					
        					$lastNumber = $zeroadd ;
        
        					$numdossier = $codetypedossier.$annee.$lastNumber;
        				}
        			}
        
        
        			$data =  array('numdossier' => $numdossier,
        					'idenregistremetpatient' => $this->input->post('idenregistremetpatient'),
        					'datecrea' => $this->input->post('datecrea'),
        					'codetypedossier' => $this->input->post('codetypedossier')
        					);
        
        			$this->all_model->add_ligne_with_return_id("dossierpatient",$data);
    
    			    // Effectuer la journalisation
    		        $type_action = 'Ajout' ;
    
    		        $action_effectuee = 'Dossier' ;
    
    		        $this->control->journalisation($type_action,$action_effectuee) ;
    		        
    		        $this->session->set_flashdata('SUCCESSMSG', "Enregistrement effectué avec succès!!");

			        redirect('Dossier/ListeRegister/', 'refresh');
    		        
    		        
			    }else{
			        
			        $this->session->set_flashdata('DANGERMSG', "Désolé, ce dossier existe déjà dans le système.");

			        redirect('Dossier/ListeRegister/', 'refresh');
			    }
    			
    		/************** DOSSIER PATIENT*************************/

			
		}
		else
		{	
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_profil'));

			$page_data['typedossier'] = $this->all_model->get_table('typededossier');

        	$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'DossierRegister';
			$page_data['page_active'] = 'DossierPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ouvrir un Dossier';

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire d\'ajout de dossier' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        	$this->render_template('kameleon/DossierRegister', $page_data);
		}
    }
	
	public function ListeRegister()
    {
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_profil'));

			$page_data['getDossier'] = $this->all_model->get_table('dossierpatient');
        	$page_data['page_libprofil'] = $UniqueProfil;	
			$page_data['page_name'] = 'ListeRegister';
			$page_data['page_active'] = 'DossierPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Liste des dossiers ';


			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Liste des dossiers' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;
			
			// affichage de la vue

        	$this->render_template('kameleon/ListeRegister', $page_data);
    }

    public function DossierDeleter($id)
	{
		$DossierDeleteId = $id ;
		// APPEL DU MODEL ADEQUAT POUR LA SUPPRESSION

		$table = "dossierpatient";
		$id_name = "numdossier";
		$id = $DossierDeleteId ;

			$this->all_model->delete_ligne($table, $id_name, $id);

			// Effectuer la journalisation
		        $type_action = 'Suppression' ;

		        $action_effectuee = 'Dossier' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

		// UTILISATION DU RESULTAT PROVENANT DE LA REQUETE DE SUPPRESSION

			$this->session->set_flashdata('SUCCESSMSG', "Suppression effectuée avec succès !!!");

			redirect('Dossier/ListeRegister/', 'refresh');
	}


}
	
