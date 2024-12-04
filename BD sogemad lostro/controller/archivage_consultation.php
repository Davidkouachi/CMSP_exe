<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class archivage_consultation extends Admin_Controller {
	
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

			if ($curr_uri_string != 'archivage_consultation') 
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
	----------				PAGE RACINE :: ./archivage_consultation  ----------
	################################################################## */
	
	public function index() 
	{
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

            $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_active'] = 'ArchivistePage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Archivage des fiches de consultation';

			$page_data['medecins'] = $this->all_model->get_table('medecin');


			$page_data['idAns'] = date('Ym');

			$page_data['namePg'] = 'fetchOrdersData';

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Liste des consultations cliniques' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue

		$this->render_template('kameleon/archivage_consultation', $page_data);	
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
	    $this->render_template('archivage/recherche_patient', $page_data);	 
	}

	public function archivage_form($id='') 
	{
		if(empty($id))
		{
			$patient_id = $this->input->post('idenregistremetpatient');
			$page_data['patient_infos'] = $this->all_model->get_fullrow('patient','idenregistremetpatient',$patient_id) ;
			$ListePatient = $this->PatientModel->getPatient();
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
			$page_data['medecins'] =  $this->all_model->get_table('medecin');
			$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_liste_patient'] = $ListePatient;
			$page_data['bandeau'] = lang('title_home_page');
			$page_data['title'] = lang('title_home_page');
			$page_data['page_active'] = "ArchivistePage";
			$page_data['page_s_title'] = 'Ajouter une archive';
			// Effectuer la journalisation
			$type_action = 'Consulation' ;
			$action_effectuee = 'Archivage' ;
			$this->control->journalisation($type_action,$action_effectuee) ;

		}else{
			$page_data['archive_infos'] = $archive_infos = $this->all_model->get_fullrow('archives','id',$id) ;
			$page_data['patient_infos'] = $this->all_model->get_fullrow('patient','idenregistremetpatient',$archive_infos['idenregistremetpatient']) ;
			$ListePatient = $this->PatientModel->getPatient();
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
			$page_data['medicines'] =  $this->all_model->get_table('pharmaprod');
			$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_liste_patient'] = $ListePatient;
			$page_data['bandeau'] = lang('title_home_page');
			$page_data['title'] = lang('title_home_page');
			$page_data['page_active'] = "ArchivistePage";
			$page_data['page_s_title'] = 'Ajouter une archive';
			// Effectuer la journalisation
			$type_action = 'Modification' ;
			$action_effectuee = 'Archive' ;
			$this->control->journalisation($type_action,$action_effectuee) ;
		}
		// affichage de la vue
		$this->render_template('archivage/archivage_form', $page_data);
	}

	public function create_archive(){

		$this->form_validation->set_rules('idenregistremetpatient', 'Nom du patient', 'required');
        $this->form_validation->set_rules('date_consultation', 'Date Consultation', 'trim|required|min_length[1]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('medecin_traitant', 'Medecin traitant', 'trim|min_length[1]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('fichier', 'Fichier', 'trim|min_length[1]|max_length[1000]|xss_clean');

        if ($this->form_validation->run() == TRUE) {
            // true case
			$patient = $this->input->post('idenregistremetpatient');
			$date_consultation = $this->input->post('date_consultation');
			$medecin_traitant = $this->input->post('medecin_traitant');
			$fichier = $this->input->post('fichier');
			$id_archive = $this->input->post('id');

			$nouveaunom = '';

			// Vérification si des fichiers ont été soumis
			if(isset($_FILES['fichiers']) && !empty($_FILES['fichiers']['name'][0])) {
				// Création du dossier pour stocker les fichiers si nécessaire
				$upload_directory = 'uploads/archives/'.$patient.'/';
				if (!file_exists($upload_directory)) {
					mkdir($upload_directory, 0777, true); // Crée le dossier et ses parents si nécessaire avec les permissions 0777
				}

				$table = 'archives';
				
				// Boucle à travers chaque fichier
				for($i = 0; $i < count($_FILES['fichiers']['name']); $i++) {
					// Récupération des informations sur le fichier
					$file_name = $_FILES['fichiers']['name'][$i];
					$file_tmp = $_FILES['fichiers']['tmp_name'][$i];
					$file_size = $_FILES['fichiers']['size'][$i];
					$file_type = $_FILES['fichiers']['type'][$i];

					$timestamp_concatene = time();

					$nom_fichier = $timestamp_concatene.'-'.$i.'-'.$this->session->userdata('user_name') ;

					$nouveaunom = $patient.'-'.$nom_fichier ;
					
					// Déplacement du fichier vers le répertoire de destination
					$file_destination = $upload_directory . $nouveaunom;
					move_uploaded_file($file_tmp, $file_destination);

					$data = array(
						'idenregistremetpatient' => $patient,
						'medecin_traitant' => $medecin_traitant,
						'date' => $date_consultation,
						'date_archivage' => date('Y-m-d'),
						'chemin_fichier' => $file_destination,
						'taille_fichier' => $file_size,
						'type_fichier' => $file_type,
						'fichier' => $nouveaunom
					);
					
					// Insertion des informations du fichier dans la base de données
					$laste_id = $this->all_model->add_ligne_with_return_id($table, $data) ;
				}
			}

			if($laste_id > 0) {
			// Effectuer la journalisation
				$type_action = 'Ajout' ;
				$action_effectuee = 'Archive' ;
				$this->control->journalisation($type_action,$action_effectuee) ;
				$this->session->set_flashdata('success', 'Archivage effectué avec succès.');
				redirect('archivage_consultation/liste_archives', 'refresh');
			}else{
				$this->session->set_flashdata('error', 'Une erreur est survenue !!');
				redirect('archivage_consultation/recherche_patient', 'refresh');
			}
		}
	}
	
	public function liste_archives() 
	{

    	$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

         $page_data['page_libprofil'] = $UniqueProfil;
		 $page_data['page_active'] = 'ArchivistePage';
		 $page_data['page_profil'] = $this->session->userdata('user_profil');
		 $page_data['page_title'] = 'Lostro Admin';
		 $page_data['page_s_title'] = 'Gestion des archives';

		$page_data['date_debut'] = '';

        $page_data['date_fin'] = '';

		 $page_data['type_critere'] = 'mois';

		 $page_data['namePg'] = 'fetchArchivesData';

		 $page_data['option_1'] = date('Ym');

		 $page_data['option_2'] = '';

		 // Effectuer la journalisation

		    $type_action = 'Consultation' ;

		    $action_effectuee = 'Liste des archives' ;

		    $this->control->journalisation($type_action,$action_effectuee) ;

		$this->render_template('archivage/archives', $page_data);
		
	}

	public function archive_periode()
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

                redirect('archivage_consultation/liste_archives');

            }
            else
            {
				 $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

				 $page_data['page_libprofil'] = $UniqueProfil;
				 $page_data['page_active'] = 'ArchivistePage';
				 $page_data['page_profil'] = $this->session->userdata('user_profil');
				 $page_data['page_title'] = 'Lostro Admin';
				 $page_data['page_s_title'] = 'Gestion des archives';

				 $page_data['type_critere'] = 'periode';

				 $page_data['namePg'] = 'fetchArchivesData';

				 $page_data['date_debut'] = $date_debut = $this->input->post('date_debut');

                 $page_data['date_fin'] = $date_fin = $this->input->post('date_fin');

				 $page_data['option_1'] = $date_debut ;

				 $page_data['option_2'] = $date_fin;

				 // Effectuer la journalisation
				 $type_action = 'Consultation' ;

				 $action_effectuee = 'Liste des archives' ;
	 
				 $this->control->journalisation($type_action,$action_effectuee) ;
	 
			 	$this->render_template('archivage/archives', $page_data);
			}
    }
	
}


/* End of file archivage_consultation.php */
/* Location: ./application/controllers/archivage_consultation.php */
	
