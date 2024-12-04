<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prescriptions extends Admin_Controller {
	
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

			if ($curr_uri_string != 'prescriptions') 
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
		
	/* ##################################################################
	----------				PAGE RACINE :: ./home					  ----------
	################################################################## */
	
	public function index() 
	{

    	$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

         $page_data['page_libprofil'] = $UniqueProfil;
		 $page_data['page_active'] = 'ordonnancePage';
		 $page_data['page_profil'] = $this->session->userdata('user_profil');
		 $page_data['page_title'] = 'Lostro Admin';
		 $page_data['page_s_title'] = 'Gestion des ordonnances';

		$page_data['date_debut'] = '';

        $page_data['date_fin'] = '';

		 $page_data['type_critere'] = 'mois';

		 $page_data['namePg'] = 'fetchOrdonnanceData';

		 $page_data['option_1'] = date('Ym');

		 $page_data['option_2'] = '';

		 // Effectuer la journalisation

		    $type_action = 'Consultation' ;

		    $action_effectuee = 'Liste des ordonnances' ;

		    $this->control->journalisation($type_action,$action_effectuee) ;

		$this->render_template('prescriptions/ordonnance', $page_data);
		
	}

	public function ordonnance_periode()
    {	
    	// initialisation du validateur du formulaire

          $this->load->library('form_validation');

        // définition des règles de validation
            
           $this->form_validation->set_rules('date_debut', 'Date de debut', 'trim|required|xss_clean');

           $this->form_validation->set_rules('date_fin', 'Date de fin', 'trim|required|xss_clean');

           if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
           {
               // erreur : retour au formulaire

                $flash_feedback = validation_errors();

                $this->session->set_flashdata('error', $flash_feedback);

                redirect('prescriptions');

            }
            else
            {
				 $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

				 $page_data['page_libprofil'] = $UniqueProfil;
				 $page_data['page_active'] = 'ordonnancePage';
				 $page_data['page_profil'] = $this->session->userdata('user_profil');
				 $page_data['page_title'] = 'Lostro Admin';
				 $page_data['page_s_title'] = 'Gestion des ordonnances';

				 $page_data['type_critere'] = 'periode';

				 $page_data['namePg'] = 'fetchOrdonnanceData';

				 $page_data['date_debut'] = $date_debut = $this->input->post('date_debut');

                 $page_data['date_fin'] = $date_fin = $this->input->post('date_fin');

				 $page_data['option_1'] = $date_debut ;

				 $page_data['option_2'] = $date_fin;

				 // Effectuer la journalisation
				 $type_action = 'Consultation' ;

				 $action_effectuee = 'Liste des ordonnances' ;
	 
				 $this->control->journalisation($type_action,$action_effectuee) ;
	 
			 	$this->render_template('prescriptions/ordonnance', $page_data);
			}
    }

	public function recherche_patient()
	{
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	    $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_name'] = 'recherche_patient';
		$page_data['page_active'] = 'recherche_patientPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Rechercher un patient';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire de recherche patient' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue

	    $this->render_template('prescriptions/recherche_patient', $page_data);	

        
	}

	public function fiche_ordonnance($id='') 
	{
		if(empty($id))
		{
			$patient_id = $this->input->post('idenregistremetpatient');

			$page_data['patient_infos'] = $this->all_model->get_fullrow('patient','idenregistremetpatient',$patient_id) ;

			$ListePatient = $this->PatientModel->getPatient();

			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

			$page_data['medicines'] =  $this->all_model->get_table('pharmaprod');

			/*$page_data['actes_as'] =  $this->all_model->get_table('actes_as');*/

			$page_data['page_libprofil'] = $UniqueProfil;

			$page_data['page_liste_patient'] = $ListePatient;

			$page_data['bandeau'] = lang('title_home_page');

			$page_data['title'] = lang('title_home_page');

			$page_data['page_active'] = "ordonnancePage";

			$page_data['page_s_title'] = 'Ajouter une ordonnance';

			// Effectuer la journalisation
			$type_action = 'Consulation' ;

			$action_effectuee = 'Fiche d\'ordonnance' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

		}else{

			$page_data['ordonnance_infos'] = $ordonnance_infos = $this->all_model->get_fullrow('ordonnance','id',$id) ;

			$page_data['patient_infos'] = $this->all_model->get_fullrow('patient','idenregistremetpatient',$ordonnance_infos['idenregistremetpatient']) ;

			$ListePatient = $this->PatientModel->getPatient();

			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

			$page_data['medicines'] =  $this->all_model->get_table('pharmaprod');

			$page_data['page_libprofil'] = $UniqueProfil;

			$page_data['page_liste_patient'] = $ListePatient;

			$page_data['bandeau'] = lang('title_home_page');

			$page_data['title'] = lang('title_home_page');

			$page_data['page_active'] = "ordonnancePage";

			$page_data['page_s_title'] = 'Ajouter une ordonnance';

			// Effectuer la journalisation
			$type_action = 'Modification' ;

			$action_effectuee = 'Fiche d\'ordonnance' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

		}
		

		// affichage de la vue

		$this->render_template('prescriptions/fiche_ordonnance', $page_data);
		
	}

	public function create_ordonnance()
	{

		$this->form_validation->set_rules('idenregistremetpatient', 'Nom du patient', 'required');
		// Validating Date Field
        $this->form_validation->set_rules('date_rdv', 'Date', 'trim|required|min_length[1]|max_length[100]|xss_clean');
        // Validating Patient Field
        //$this->form_validation->set_rules('patient', 'Patient', 'trim|required|min_length[1]|max_length[100]|xss_clean');
        // Validating Doctor Field
        $this->form_validation->set_rules('doctor', 'Doctor', 'trim|min_length[1]|max_length[100]|xss_clean');
        // Validating Advice Field
        $this->form_validation->set_rules('symptom', 'History', 'trim|min_length[1]|max_length[1000]|xss_clean');
        // Validating Do And Dont Name Field
        $this->form_validation->set_rules('note', 'Note', 'trim|min_length[1]|max_length[1000]|xss_clean');
        // Validating Validity Field
        //$this->form_validation->set_rules('validity', 'Validity', 'trim|min_length[1]|max_length[100]|xss_clean');

        if ($this->form_validation->run() == TRUE) {
            // true case

			$patient = $this->input->post('idenregistremetpatient');
			$doctor = $this->input->post('doctor');
			$symptom = $this->input->post('symptom');
			$medicine = $this->input->post('medicine');
			$dosage = $this->input->post('dosage');
			$frequency = $this->input->post('frequency');
			$days = $this->input->post('days');
			$instruction = $this->input->post('instruction');
			$note = $this->input->post('note');
			$date_rdv = $this->input->post('date_rdv');
			$id_ordonnance = $this->input->post('id');

			$report = array();

			if (!empty($medicine)) {
				foreach ($medicine as $key => $value) {
					$report[$value] = array(
						'dosage' => $dosage[$key],
						'frequency' => $frequency[$key],
						'days' => $days[$key],
						'instruction' => $instruction[$key],
					);

					// }
				}

				foreach ($report as $key1 => $value1) {
					$final[] = $key1 . '***' . implode('***', $value1);
				}

				$final_report = implode('###', $final);
			} else {
				$final_report = '';
			}

        	$data = array(
				    'date_ordonnance' => date('Y-m-d'),
					'date_rdv' => $date_rdv,
	        		'idenregistremetpatient' => $patient ,
	        		'codemedecin' => $doctor,
					'symptome' => $symptom,
					'conseils' => $note,
					'medicament' => $final_report
	        	);

        	$table = 'ordonnance';

			if(!empty($id_ordonnance))
			{
				$this->prescription_model->updatePrescription($id_ordonnance, $data);

				// Effectuer la journalisation
				$type_action = 'Modification' ;
	
				$action_effectuee = 'Fiche d\'ordonnance' ;

				$this->control->journalisation($type_action,$action_effectuee) ;

				$this->session->set_flashdata('success', 'Modification effectuée avec succès.');
				redirect('prescriptions', 'refresh');

			}else{

				$laste_id = $this->all_model->add_ligne_with_return_id($table, $data) ;

				if($laste_id > 0) {

					// Effectuer la journalisation
						$type_action = 'Ajout' ;
	
						$action_effectuee = 'Fiche d\'ordonnance' ;
	
						$this->control->journalisation($type_action,$action_effectuee) ;
	
					$this->session->set_flashdata('success', 'Enrégistrement effectué avec succès.');
					redirect('prescriptions', 'refresh');
				}
					else{
						$this->session->set_flashdata('error', 'Une erreur est survenue !!');
						redirect('prescriptions/recherche_patient', 'refresh');
				}

			}


			

			



        }
        else {
            redirect('prescriptions/recherche_patient','refresh');
        }	
	}

	
}


/* End of file prescriptions.php */
/* Location: ./application/controllers/prescriptions.php */