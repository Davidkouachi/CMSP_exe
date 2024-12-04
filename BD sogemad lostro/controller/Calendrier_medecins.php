<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendrier_medecins extends Admin_Controller {
    
    
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

			if ($curr_uri_string != 'calendrier_medecins') 
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

	
	public function ViewCalendrier($param1,$param2)
	{
		$data['getCalendrier'] = $this->Calendrier_medecinModel->getCalendrier($param1,$param2);	
		
		//$this->load->view('view_user',$data);

	}

	public function ViewCalendrierCons()
	{
		$table = 'calendrier_medecin';

		$page_data['getCalendrierCons'] = $this->all_model->get_table($table);	

            $page_data['page_libprofil'] = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
			$page_data['page_name'] = 'CalendrierConsultation';
			$page_data['page_active'] = 'CalendrierPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Calendrier de consultation';

			// Effectuer la journalisation
			$type_action = 'Consulation' ;

			$action_effectuee = 'Calendrier de consultation des médecins' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
			
			// affichage de la vue

		$this->render_template('kameleon/CalendrierConsultation', $page_data);
	}
		
    public function CalendrierRegister()
    {
		if(!empty($_POST))
		{
			$codespecialitemed = $this->Calendrier_medecinModel->get_UniqueMedecin_info($this->input->post('codemedecincalendrier'));

			if(!empty($codespecialitemed))
			{
				$data =  array('codemedecin' => $this->input->post('codemedecincalendrier'),
					'codespecialitemed' => $codespecialitemed['codespecialitemed'],
					'periode' => $this->input->post('periode'),
					'heuredebut' => $this->input->post('heuredebut'),
					'heurefin' => $this->input->post('heurefin'),
					'jour' => $this->input->post('jour'),
					'mois' => $this->input->post('mois'),
					'annee' => $this->input->post('annee')
					);

				// Effectuer la journalisation
			$type_action = 'Ajout' ;

			$action_effectuee = 'Calendrier de medecin' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

				$this->Calendrier_medecinModel->CalendrierRegister($data);

				$this->session->set_flashdata('SUCCESSMSG', "Enregistrement effectué avec succès!!");

			}
			else
			{
				$this->session->set_flashdata('SUCCESSMSG', "Aucun médecin n'a été sélectionné.");
			}
			
			
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

            $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'CalendrierRegister';
			$page_data['page_active'] = 'CalendrierPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter un programme de medecin';
			
			// affichage de la vue

			$this->render_template('kameleon/CalendrierRegister', $page_data);
		}
		else
		{	
		 $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

            $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'CalendrierRegister';
			$page_data['page_active'] = 'CalendrierPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter un programme de medecin';

			// Effectuer la journalisation
			$type_action = 'Consulation' ;

			$action_effectuee = 'Formulaire d\'ajout de programme de medecin' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
			
			// affichage de la vue

			$this->render_template('kameleon/CalendrierRegister', $page_data);
		}
    }
	
}
	
