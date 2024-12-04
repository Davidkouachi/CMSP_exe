<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class laboratoire extends Admin_Controller {
	
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

			if ($curr_uri_string != 'laboratoire') 
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

	

	public function index()
    {	
		 $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

		 //$page_data['factures'] = $this->all_model->get_table('factures');

         $page_data['page_libprofil'] = $UniqueProfil;
		 $page_data['page_active'] = 'gerer_examenPage';
		 $page_data['page_profil'] = $this->session->userdata('user_profil');
		 $page_data['page_title'] = 'Lostro Admin';
		 $page_data['page_s_title'] = 'Examens demandés';

		$page_data['date_debut'] = '';

        $page_data['date_fin'] = '';

		 $page_data['type_critere'] = 'mois';

		 $page_data['namePg'] = 'fetchFacturesExamensDemandesData';

		 $page_data['option_1'] = date('Ym');

		 $page_data['option_2'] = '';

		 // Effectuer la journalisation

		    $type_action = 'Consultation' ;

		    $action_effectuee = 'Liste des factures du système' ;

		    $this->control->journalisation($type_action,$action_effectuee) ;

		$this->render_template('laboratoire/gestion_factures_examens_demandes', $page_data);
    }

	

	/***MANAGE PRESCRIPTIONS******/

	function view_prescription($prescription_id = '', $param2 = '', $param3 = '')

	{

		/*if ($this->session->userdata('laboratorist_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');*/

		

		$page_data['page_name']           = 'view_prescription';

		$page_data['page_title']          = 'Liste des prescriptions';

		$page_data['prescription_detail'] = $this->db->get_where('prescription', array(

			'prescription_id' => $prescription_id

		))->result_array();

		$page_data['prescriptions']       = $this->db->get('prescription')->result_array();

		$this->load->view('index', $page_data);

	}

	

	/***MANAGE PRESCRIPTIONS*(UPLOAD/DELETE) DIAGNOSIS REPORTS OF A CERTAIN PRESCRIPTION*****/

	function manage_prescription($param1 = '', $param2 = '', $param3 = '')

	{

		/*if ($this->session->userdata('laboratorist_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');*/

			

		if ($param1 == 'create_diagnosis_report') {

			$data['report_type']     = $this->input->post('report_type');

			$data['document_type']   = $this->input->post('document_type');

			$data['prescription_id'] = $this->input->post('prescription_id');

			$data['description']     = $this->input->post('description');

			$data['timestamp']       = strtotime(date('Y-m-d') . ' ' . date('H:i:s'));

			$data['laboratorist_id'] = $this->session->userdata('laboratorist_id');

			move_uploaded_file($_FILES["userfile"]["tmp_name"], "uploads/diagnosis_report/" . $_FILES["userfile"]["name"]);

			$data['file_name'] = $_FILES["userfile"]["name"];

			// Effectuer la journalisation
			$type_action = 'Ajout' ;

			$action_effectuee = 'Rapport diagnostique' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			
			$this->db->insert('diagnosis_report', $data);

			$this->session->set_flashdata('SUCCESSMSG', 'Enrégistrement du rapport diagnostique effectué avec succès.');

			redirect(base_url() . 'laboratoire/manage_prescription/edit/' . $this->input->post('prescription_id'), 'refresh');

		}

		

		if ($param1 == 'delete_diagnosis_report') {

			$this->db->where('diagnosis_report_id', $param2);

			// Effectuer la journalisation
			$type_action = 'Suppression' ;

			$action_effectuee = 'Rapport diagnostique' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$this->db->delete('diagnosis_report');

			$this->session->set_flashdata('SUCCESSMSG', 'Suppression du rapport diagnostique effectuée avec succès.');

			redirect(base_url() . 'laboratoire/manage_prescription/edit/' . $param3, 'refresh');

			

		} else if ($param1 == 'edit') {

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire de modification de rapport diagnostique' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$page_data['edit_profile'] = $this->db->get_where('prescription', array(

				'prescription_id' => $param2

			))->result_array();

		}

		$page_data['page_name']     = 'manage_prescription';

		$page_data['page_title']    = 'Gestion des prescriptions';

		$page_data['prescriptions'] = $this->db->get('prescription')->result_array();


		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	    $page_data['page_libprofil'] = $UniqueProfil;

		$page_data['bandeau'] = lang('title_home_page');

		$page_data['title'] = lang('title_home_page');

		$page_data['page_active'] = "labodiagnosticPage";

		$page_data['page_s_title'] = 'Gestion des prescriptions';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Liste des rapports diagnostiques' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

		$this->render_template('laboratoire/manage_prescription', $page_data);

	}
	

	/****MANAGE EXAMENS*********/

	function manage_examen($param1 = '', $param2 = '', $param3 = '')

	{

		/*if ($this->session->userdata('pharmacist_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');*/

		if ($param1 == 'create') {

			$random_chars="";

 			$characters = array("A","B","C","D","E","F","G","H","J","K","L","M",
					 "N","P","Q","R","S","T","U","V","W","X","Y","Z",
					 "1","2","3","4","5","6","7","8","9");

			$keys = array();

			  while(count($keys) < 7) 
			  {
					$x = mt_rand(0, count($characters)-1);
					if(!in_array($x, $keys)) 
					{
						$keys[] = $x;
					}
			   }
				foreach($keys as $key)
			    {
					$random_chars .= $characters[$key];
				}

				$familleExam = $this->input->post('examen_category_id');

				switch ($familleExam) {

					case "Z":

						$numexam ='Z'.$random_chars;
						
						$data['codgaran']   = '' ;

						$data['cot'] = $this->input->post('cotation');

						// Effectuer la journalisation
						$type_action = 'Ajout' ;

						$action_effectuee = 'Examen radiologique' ;

						$this->control->journalisation($type_action,$action_effectuee) ;
									
					break; // ARRET DU SCRIPT

					case "B":

						$numexam ='B'.$random_chars;
						
						$data['codgaran']   = '' ;

						$data['cot'] = $this->input->post('cotation');

						// Effectuer la journalisation
						$type_action = 'Ajout' ;

						$action_effectuee = 'Examen biologique' ;

						$this->control->journalisation($type_action,$action_effectuee) ;

					break; // ARRET DU SCRIPT

					case "D":

						$numexam ='D'.$random_chars;
						
						$data['codgaran']   = '' ;

						$data['cot'] = $this->input->post('cotation');

						// Effectuer la journalisation
						$type_action = 'Ajout' ;

						$action_effectuee = 'Examen dentaire' ;

						$this->control->journalisation($type_action,$action_effectuee) ;
									
					break; // ARRET DU SCRIPT

					case "Y":

						$numexam ='Y'.$random_chars;

						$data['codgaran']   = $this->input->post('examen_garantie_id');

						$data['cot']          = 0 ;

						// Effectuer la journalisation
						$type_action = 'Ajout' ;

						$action_effectuee = 'Imagerie médicale' ;

						$this->control->journalisation($type_action,$action_effectuee) ;
												
					break; // ARRET DU SCRIPT
				}

			$data['numexam'] = $numexam ;

			$data['denomination'] = $this->input->post('name');

			$data['codfamexam']   = $this->input->post('examen_category_id');

			$data['fam_acte_bio']   = $this->input->post('famille_acte_bio');

			$data['prix']         = $this->input->post('price');

			$this->db->insert('examen', $data);

			$this->session->set_flashdata('SUCCESSMSG', 'Enrégistrement de l\'examen effectué avec succès.');

			redirect(base_url() . 'laboratoire/manage_examen', 'refresh');

		}

		if ($param1 == 'edit' && $param2 == 'do_update') {

				$familleExam = $this->input->post('examen_category_id');

				switch ($familleExam) {

					case "Z":
						
						$data['codgaran']   = '' ;

						$data['cot'] = $this->input->post('cotation');

						// Effectuer la journalisation
						$type_action = 'Modification' ;

						$action_effectuee = 'Acte radiographie' ;

						$this->control->journalisation($type_action,$action_effectuee) ;
									
					break; // ARRET DU SCRIPT

					case "B":
						
						$data['codgaran']   = '' ;

						$data['cot'] = $this->input->post('cotation');

						// Effectuer la journalisation
						$type_action = 'Modification' ;

						$action_effectuee = 'Examen biologique' ;

						$this->control->journalisation($type_action,$action_effectuee) ;

					break; // ARRET DU SCRIPT

					case "D":
						
						$data['codgaran']   = '' ;

						$data['cot'] = $this->input->post('cotation');

						// Effectuer la journalisation
						$type_action = 'Modification' ;

						$action_effectuee = 'Examen dentaire' ;

						$this->control->journalisation($type_action,$action_effectuee) ;
									
					break; // ARRET DU SCRIPT

					case "Y":

						$data['codgaran']   = $this->input->post('examen_garantie_id_update');

						$data['cot']          = 0 ;

						// Effectuer la journalisation
						$type_action = 'Modification' ;

						$action_effectuee = 'Imagerie' ;

						$this->control->journalisation($type_action,$action_effectuee) ;
									
					break; // ARRET DU SCRIPT
				}

			$data['denomination']          = $this->input->post('name');

			$data['codfamexam']  = $this->input->post('examen_category_id');

			$data['fam_acte_bio']   = $this->input->post('famille_acte_bio_update');

			$data['prix']                 = $this->input->post('price');

	
			$this->db->where('numexam', $param3);

			$this->db->update('examen', $data);

			$this->session->set_flashdata('SUCCESSMSG', 'Modification de l\'examen effectuée avec succès.');

			redirect(base_url() . 'laboratoire/manage_examen', 'refresh');

			

		} else if ($param1 == 'edit') {

			$page_data['edit_profile'] = $this->db->get_where('examen', array(

				'numexam' => $param2

			))->result_array();

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire de modification examen/imagerie' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

		}

		if ($param1 == 'delete') {

			$this->db->where('numexam', $param2);

			$this->db->delete('examen');

			// Effectuer la journalisation
			$type_action = 'Suppression' ;

			$action_effectuee = 'Examen biologique/imagerie' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$this->session->set_flashdata('SUCCESSMSG', 'Suppression de l\'examen effectuée avec succès.');

			redirect(base_url() . 'laboratoire/manage_examen', 'refresh');

		}

		$page_data['page_name']  = 'manage_examen';

		$page_data['examens']  = $this->db->get('examen')->result_array();

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        $page_data['page_libprofil'] = $UniqueProfil;
		
		$page_data['page_active'] = 'gerer_examenPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Gestion des examens';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Liste des examens biologiques/imagerie' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue

        $this->render_template('laboratoire/manage_examen', $page_data);

	}
	
	function resultats(string $numero_facture) {
        $page_data = array();

		$numero_facture = trim($numero_facture) ;

		$infos_factures = $this->all_model->get_fullrow('factures', 'numfac', $numero_facture);

		$infos_patient = $this->all_model->get_fullrow('patient', 'idenregistremetpatient', $infos_factures['idenregistremetpatient']);

		$infos_testlabo = $this->all_model->get_fullrow('testlaboimagerie', 'numfacbul', $numero_facture);

		$infos_medecin = $this->all_model->get_fullrow('medecin', 'codemedecin ', $infos_testlabo['codemedecin']);

		$count_total_examens_item = $this->all_model->countExamenItem($infos_testlabo['idtestlaboimagerie']);

		if(!empty($infos_testlabo)){

            $infos_details = $this->all_model->get_fullrow_all('detailtestlaboimagerie','idtestlaboimagerie',$infos_testlabo['idtestlaboimagerie']);
            if(!empty($infos_details)){

                $examens = '';

                $cpteur = 1 ;

                foreach ($infos_details as $row) {

                    $examens .= $cpteur.'- '.$row['denomination'] .' '  ;

                    $cpteur++ ;
                }

				$page_data['infos_details'] = $infos_details ;
            }else{

                $examens = '' ;

				$page_data['infos_details'] = array();
            }
        }else{

            $examens = '' ;

			$page_data['infos_details'] = array();
        }

		$page_data['patient_infos'] = $infos_patient ;

		$page_data['examems_demandes'] = $examens ;

		$page_data['nbre_examems_demandes'] = $count_total_examens_item ;

		$page_data['infos_testlabo'] = $infos_testlabo ;

		$page_data['infos_medecin'] = $infos_medecin ;

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

         $page_data['page_libprofil'] = $UniqueProfil;
		 $page_data['page_active'] = 'resultatPage';
		 $page_data['page_profil'] = $this->session->userdata('user_profil');
		 $page_data['page_title'] = 'Lostro Admin';
		 $page_data['page_s_title'] = 'FICHE DE SAISIE DES RESULTATS D\'ANALYSE';

		 // Effectuer la journalisation

		    $type_action = 'Consultation' ;

		    $action_effectuee = 'Fiche de saisie de resultats' ;

		    $this->control->journalisation($type_action,$action_effectuee) ;

		$this->render_template('laboratoire/saisie_resultats', $page_data);
    }


}