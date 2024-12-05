<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient extends Admin_Controller {
    
    
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

			if ($curr_uri_string != 'patient') 
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

	
	public function ViewPatient()
	{
		//$page_data['Patient_list'] = $this->patientmodel->getPatient();

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_name'] = 'PatientList';
		$page_data['page_active'] = 'PatientPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Liste des Patients';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Liste des patients' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

		$page_data['idAns'] = 'patient';

		$page_data['namePg'] = 'fetchPatientData';

		// affichage de la vue

        $this->render_template('kameleon/PatientList', $page_data);
	}
	
	
	public function ViewTodayPatient()
	{
		//$page_data['Patient_list'] = $this->all_model->get_fullrow_bis('patient','dateenregistrement',date('Y-m-d'));

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_name'] = 'PatientList';
		$page_data['page_active'] = 'PatientPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Liste des Patients';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Liste des patient du jour' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
			
	    $page_data['idAns'] = 'patient';

		$page_data['namePg'] = 'fetchPatientTodayData';

		// affichage de la vue

        $this->render_template('kameleon/PatientList', $page_data);
	}
		
    public function PatientRegister()
    {
		if(!empty($_POST))
		{
			
			$nom = $this->input->post('nomPatient');
			$prenoms = $this->input->post('prenomsPatient');
			
			$nomprenoms = $nom.' '.$prenoms;
			
			$codeassurance = $this->input->post('assurance');
			$matriculeassure = $this->input->post('matriculeAssurance');
			$codefiliation = $this->input->post('filiationPatient');
			$datenaispatient = $this->input->post('datenaiss');
			$assure = $this->input->post('typePatient'); 
			$lieuderesidencepat = $this->input->post('residencePatient');
			$telpatient = $this->input->post('contactPatient');
			$adressepatient = $this->input->post('emailPatient');
			$idtauxcouv = $this->input->post('tauxCouverture');
			$codesocieteassure = $this->input->post('societeAssurePrincipal');
			$sexe = $this->input->post('sexePatient');
			$dateenregistrement = $this->input->post('dateEnregistrement');
			
			$maxMat = $this->PatientModel->getPatient_MaxMat();
			
			$compteur_patient = $this->all_model->get_fullrow('tnumref','libref','COMPTEUR PATIENT');
			 
			if(!empty($compteur_patient))
			{
			    $max_num = $compteur_patient['cptnref'] ;
			}
			else
			{
			  $max_num = '' ;  
			}
			
			$annee = substr(date('Y'),-2);
			
			if ($max_num == '') {

				$idPatient = 'C'.$annee.'001';
				
				$lastNumber = '001';
			}
			else
			{
				$lastNumber = $max_num + 1 ;

				$lastNumber ;

				$zeroadd = "".$lastNumber ;

				while (strlen($zeroadd) < 3) {
					
					$zeroadd = "0" . $zeroadd ;
				}
				
				$lastNumber = $zeroadd ; 
				
				$idPatient = 'C'.$annee.$lastNumber;
			}
			
			$codeproduit = $this->input->post('codeproduit');

			if(empty($codeproduit))
			{
				$codeproduit = '';
			}
			
			if($assure == 0)
			{
			    $codeassurance = 'NONAS' ;
			    
			    $codefiliation = 0 ;
			    
			    $matriculeassure = '';
			    
			    $idtauxcouv = 0 ;
			    
			    $codesocieteassure = 0 ;
			}
			
			$data =  array('idenregistremetpatient' => $idPatient,
						   'idenregistrementhopital' => 1,
						   'numeroregistre' => 1,
					'codeassurance' => $codeassurance,
					'matriculeassure' =>$matriculeassure,
					'codefiliation' => $codefiliation,
					'nompatient' => $nom,
					'prenomspatient' => $prenoms,
				    'nomprenomspatient' => $nomprenoms,
					'datenaispatient ' => $datenaispatient,
					'assure' => $assure, 
					'lieuderesidencepat ' => $lieuderesidencepat,
					'telpatient' => $telpatient,
					'adressepatient' => $adressepatient,
					'idtauxcouv' => $idtauxcouv,
					'codesocieteassure' => $codesocieteassure,
					'sexe' => $sexe,
					'dateenregistrement ' => $dateenregistrement,
					'codeproduit ' => $codeproduit
					);

			// Effectuer la journalisation
			$type_action = 'Ajout' ;

			$action_effectuee = 'Patient' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
	


			$this->PatientModel->PatientRegister($data);
			$this->session->set_flashdata('SUCCESSMSG', "Enregistrement effectué avec succès!!");
			
			$data_up = array('cptnref' => $lastNumber);

		    $query_up = $this->all_model->update_ligne('tnumref', $data_up, 'libref', 'COMPTEUR PATIENT');
		
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'PatientRegister';
			$page_data['page_active'] = 'PatientPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter un Patient';

			// affichage de la vue

        	//$this->render_template('kameleon/PatientRegister', $page_data);
        	redirect('Patient/PatientRegister/');
		}
		else
		{	
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
			
			$PatientFiliation = $this->PatientModel->getFiliationPatient();
			
			$PatientAssurance = $this->PatientModel->getAssurancePatient();
			
			$TauxAssurance = $this->PatientModel->getTauxAssurance();

			$PatientSociete = $this->PatientModel->get_all_societe_assure();

			$page_data['produit_assurance'] =  $this->all_model->get_table('produit_assurance');

        	$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['PatientFiliation'] = $PatientFiliation;
			$page_data['PatientAssurance'] = $PatientAssurance;
			$page_data['TauxAssurance'] = $TauxAssurance;
			$page_data['PatientSociete'] = $PatientSociete;
			$page_data['page_name'] = 'PatientRegister';
			$page_data['page_active'] = 'PatientPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter un Patient';

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire d\'ajout de patient' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        	$this->render_template('kameleon/PatientRegister', $page_data);

        	
		}
    }

    public function PatientUpdater($param1 = '', $param2 = '', $param3 = '')
    {
    	if ($param1 == 'edit' && $param2 == 'do_update') {

    		$nom = $this->input->post('nomPatient');
			$prenoms = $this->input->post('prenomsPatient');
			
			$nomprenoms = $nom.' '.$prenoms;
			
			$codeassurance = $this->input->post('assurance');
			$matriculeassure = $this->input->post('matriculeAssurance');
			$codefiliation = $this->input->post('filiationPatient');
			$datenaispatient = $this->input->post('datenaiss');
			$assure = $this->input->post('typePatient'); 
			$lieuderesidencepat = $this->input->post('residencePatient');
			$telpatient = $this->input->post('contactPatient');
			$adressepatient = $this->input->post('emailPatient');
			$idtauxcouv = $this->input->post('tauxCouverture');
			$codesocieteassure = $this->input->post('societeAssurePrincipal');
			$sexe = $this->input->post('sexePatient');
			$dateenregistrement = $this->input->post('dateEnregistrement');
			$codeproduit = $this->input->post('codeproduit');
			
			if($assure == 0)
			{
			    $codeassurance = 'NONAS' ;
			    
			    $codefiliation = 0 ;
			    
			    $matriculeassure = '';
			    
			    $idtauxcouv = 0 ;
			    
			    $codesocieteassure = 0 ;
			}

			$data =  array('codeassurance' => $codeassurance,
					'matriculeassure' => $matriculeassure,
					'codefiliation' => $codefiliation,
					'nompatient' => $nom,
					'prenomspatient' => $prenoms,
				    'nomprenomspatient' => $nomprenoms,
					'datenaispatient ' => $datenaispatient,
					'assure' => $assure, 
					'lieuderesidencepat ' => $lieuderesidencepat,
					'telpatient' => $telpatient,
					'adressepatient' => $adressepatient,
					'idtauxcouv' => $idtauxcouv,
					'codesocieteassure' => $codesocieteassure,
					'sexe' => $sexe,
					'dateenregistrement ' => $dateenregistrement,
					'codeproduit ' => $codeproduit
					);

			// Effectuer la journalisation
			$type_action = 'Modification' ;

			$action_effectuee = 'Patient'.' '.$param3 ;

			$this->control->journalisation($type_action,$action_effectuee) ;
			

			$this->db->where('idenregistremetpatient', $param3);

			$this->db->update('patient', $data);

			$this->session->set_flashdata('SUCCESSMSG', 'Modification de l\'examen effectuée avec succès.');

			redirect(base_url() . 'Patient/ViewPatient', 'refresh');

			

		} else if ($param1 == 'edit') {

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire de modification du patient'.' '.$param2 ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$page_data['edit_profile'] = $this->db->get_where('patient', array(

				'idenregistremetpatient' => $param2

			))->result_array();

		}
		
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
			
			$PatientFiliation = $this->PatientModel->getFiliationPatient();
			
			$PatientAssurance = $this->PatientModel->getAssurancePatient();
			
			$TauxAssurance = $this->PatientModel->getTauxAssurance();

			$PatientSociete = $this->PatientModel->get_all_societe_assure();

			$page_data['produit_assurance'] =  $this->all_model->get_table('produit_assurance');

        	$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['PatientFiliation'] = $PatientFiliation;
			$page_data['PatientAssurance'] = $PatientAssurance;
			$page_data['TauxAssurance'] = $TauxAssurance;
			$page_data['PatientSociete'] = $PatientSociete;
			$page_data['page_name'] = 'PatientRegister';
			$page_data['page_active'] = 'PatientPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter un Patient';

			// Effectuer la journalisation
			$type_action = 'Modification' ;

			$action_effectuee = 'Patient'.' '.$param3 ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        	$this->render_template('kameleon/PatientUpdater', $page_data);
    }

    public function PatientDetail($param = '')
    {
    	if(isset($param))
    	{
    		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
			
			$PatientFiliation = $this->PatientModel->getFiliationPatient();
			
			$PatientAssurance = $this->PatientModel->getAssurancePatient();
			
			$TauxAssurance = $this->PatientModel->getTauxAssurance();

			$PatientSociete = $this->PatientModel->get_all_societe_assure();

			$page_data['produit_assurance'] =  $this->all_model->get_table('produit_assurance');

			$page_data['PatientCivilite'] =  $this->all_model->get_table('civilite');

			$page_data['patient_infos'] = $this->db->get_where('patient', array('idenregistremetpatient' => $param))->row_array();

        	$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['PatientFiliation'] = $PatientFiliation;
			$page_data['PatientAssurance'] = $PatientAssurance;
			$page_data['TauxAssurance'] = $TauxAssurance;
			$page_data['PatientSociete'] = $PatientSociete;
			$page_data['page_name'] = 'PatientRegister';
			$page_data['page_active'] = 'PatientPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Détail Patient';
			$page_data['tab_active'] = 1;

			$page_data['idAns'] = 'antecedents';

		 	$page_data['namePg'] = 'fetchAntecedentsData';

		 	$page_data['table_1'] = 'consultation';

		 	$page_data['namePg_1'] = 'fetchConsData';

		 	$page_data['table_2'] = 'rendez_vous';

		 	$page_data['namePg_2'] = 'fetchRdvData';

		 	$page_data['table_3'] = 'testlaboimagerie';

		 	$page_data['namePg_3'] = 'fetchBioImgData';
		 	
		 	$page_data['table_4'] = 'admission';

		 	$page_data['namePg_4'] = 'fetchAdmissionDataUnique';

		 	$page_data['table_5'] = 'factures';

		 	$page_data['namePg_5'] = 'fetchFacturesDataUnique';

		 	$page_data['code_patient'] = $param;

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Détail du Patient'.' '.$param ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        	$this->render_template('kameleon/PatientDetail', $page_data);
    	}
    }

    public function PatientDeleter($id)
	{
		$id_name = 'idenregistremetpatient' ;

		$infos_consultation =  $this->all_model->get_fullrow('consultation', $id_name, $id) ;

		$infos_labo =  $this->all_model->get_fullrow('testlaboimagerie', $id_name, $id) ;

		$infos_admission =  $this->all_model->get_fullrow('admission', $id_name, $id) ;

		$infos_factures =  $this->all_model->get_fullrow('factures', $id_name, $id) ;

		if(!empty($infos_consultation))
		{
			$this->session->set_flashdata('DANGERMSG', "Désolé ! Suppression impossible car le numéro d'identification du patient a été déjà utilisé pour faire une facture de consultation.");

			redirect('patient/ViewPatient');
		}

		if(!empty($infos_labo))
		{
			$this->session->set_flashdata('DANGERMSG', "Désolé ! Suppression impossible car le numéro d'identification du patient a été déjà utilisé pour faire une facture d'imagerie ou de biologie.");

			redirect('patient/ViewPatient');
		}

		if(!empty($infos_admission))
		{
			$this->session->set_flashdata('DANGERMSG', "Désolé ! Suppression impossible car le numéro d'identification du patient a été déjà utilisé pour faire une facture d'admission.");

			redirect('patient/ViewPatient');
		}

		if(!empty($infos_consultation))
		{
			$this->session->set_flashdata('DANGERMSG', "Désolé ! Suppression impossible car le numéro d'identification du patient a été déjà utilisé pour faire un reçu.");

			redirect('patient/ViewPatient');
		}

		if(!empty($infos_factures))
		{
			$this->session->set_flashdata('DANGERMSG', "Désolé ! Suppression impossible car le numéro d'identification du patient a été déjà utilisé pour faire une facture.");

			redirect('patient/ViewPatient');
		}

		// Effectuer la journalisation
			$type_action = 'Suppression' ;

			$action_effectuee = 'Patient'.' '.$id ;

			$this->control->journalisation($type_action,$action_effectuee) ;

		// APPEL DU MODEL ADEQUAT POUR LA SUPPRESSION

			$this->PatientModel->PatientDeleter($id);

			$page_data['Patient_list'] = $this->PatientModel->getPatient();

		// UTILISATION DU RESULTAT PROVENANT DE LA REQUETE DE SUPPRESSION

			$this->session->set_flashdata('SUCCESSMSG', "Suppression effectuée avec succès !!!");

        	redirect('patient/ViewPatient');
	}
	
	public function AdmissionRegister()
    {
		if(!empty($_POST))
		{
		    $this->load->library('form_validation');

			// définition des règles de validation
			
		  $this->form_validation->set_rules('idenregistremetpatient', '<< Nom du patient >>', 'trim|required');
		  $this->form_validation->set_rules('codemedecin', '<< Nom du Medecin >>', 'trim|required');
		  $this->form_validation->set_rules('codetypehospit', '<< Type de l\'hospitalitation >>', 'trim|required');
		  $this->form_validation->set_rules('codenaturehospit', '<< Nature de l\'hospitalitation >>', 'trim|required');
		  $this->form_validation->set_rules('dateentree', '<< Date d\'entrée >>', 'trim|required');
		  $this->form_validation->set_rules('datesortie', '<< Date sortie >>', 'trim|required');
		  $this->form_validation->set_rules('motifhospit', '<< Motif Hospitalisation >>', 'trim|required');
		  $this->form_validation->set_rules('codechbre', '<< Chambre à occuper >>', 'trim|required');
		  $this->form_validation->set_rules('idtypelit', '<< Lit à occuper >>', 'trim|required');

			if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
		    {
			   // erreur : retour au formulaire
		        $flash_feedback = validation_errors();

			    $this->session->set_flashdata('DANGERMSG', $flash_feedback);
			    
			    	redirect('Patient/AdmissionRegister/');
			    exit();
			}
			else
			{
			    $dateentree = $this->input->post('dateentree');
    		    $datesortie = $this->input->post('datesortie');
    		    if($dateentree > $datesortie)
    		    {
    		        $flash_feedback = 'La date d\'entrée ne doit pas être supérieur à la date de sortie.';
    
    			    $this->session->set_flashdata('DANGERMSG', $flash_feedback);
    			    
    			    	redirect('Patient/AdmissionRegister/');
    			    exit();
    		    }
			    
			    
    			echo $codenaturehospit = $this->input->post('codenaturehospit');
    
    			$MaxNumHospit = $this->PatientModel->get_MaxNumHospit($codenaturehospit);
    			

    			$annee = substr(date('Y'),-2);
    
    			$debNum = substr($codenaturehospit,0,2);
    
    			if ($MaxNumHospit['numhospit'] == '') {
    
    				$numhospit = $debNum.$annee.'0001';
    			}
    			else
    			{
    				$number = substr($MaxNumHospit['numhospit'],-4);
    				
    				$lastNumber = $number + 1 ;
    
    				$lastNumber ;
    
    				$zeroadd = "".$lastNumber ;
    
    				while (strlen($zeroadd) < 4) {
    					
    					$zeroadd = "0" . $zeroadd ;
    				}
    				
    				$lastNumber = $zeroadd ; 
    				
    				$numhospit = $debNum.$annee.$lastNumber;
    			}
    
    			$dateentree = $this->input->post('dateentree');
    		    $datesortie = $this->input->post('datesortie');
    
    			// On transforme les 2 dates en timestamp
    	        $date1 = strtotime($dateentree);
    	        $date2 = strtotime($datesortie);
    	         
    	        // On récupère la différence de timestamp entre les 2 précédents
    	        $nbJoursTimestamp = $date2 - $date1;
    	         
    	        // ** Pour convertir le timestamp (exprimé en secondes) en jours **
    	        // On sait que 1 heure = 60 secondes * 60 minutes et que 1 jour = 24 heures donc :
    	        $nbredejrs = $nbJoursTimestamp/86400; // 86 400 = 60*60*24 
    
    	        $result_dern_fac = $this->all_model->get_max_facture('FCH');
    			    
    		    $dernier_fac = $result_dern_fac['numfachospit'];
    
    			    //S IL S AGIT DU PREMIER NUMERO DE FAC
    
    			    if($dernier_fac == "")
    			    {
    			        
    			        $decoupe = explode("-", $dateentree);
    
    			        $annee = $decoupe[0];
    			        $annee_coup = substr($annee, 2, 3);
    			        $numfac = "FCH".$annee_coup.'0'.'0'.'0'.'1';
    			        
    			    } 
    			    else 
    			    if($dernier_fac != "")
    			    {//S'IL EXISTE DEJA NUMERO DE FAC
    			                
    			    //CREATION DU NOUVEAU NUMERO DE FAC
    
    			    $decoupe = explode("-", $dateentree);
    			    $annee = $decoupe[0];
    			    $annee_coup = substr($annee, 2, 3);
    			    $dern_nombre = substr($dernier_fac, 5, 4);
    			    $nouv_nombre = $dern_nombre + 1;
    			                    $str = "" . $nouv_nombre;
    			                            while(strlen($str) < 4)
    			                            {
    			                                $str = "0" . $str;
    			                            }
    			                            $matn = $str;
    
    			    $numfac = "FCH".$annee_coup.$matn;
    			    }
    			
    
    			$data =  array('numhospit' => $numhospit,
    					'idenregistremetpatient' => $this->input->post('idenregistremetpatient'),
    					'codemedecin' => $this->input->post('codemedecin'),
    					'codetypehospit' => $this->input->post('codetypehospit'),
    					'codenaturehospit' => $this->input->post('codenaturehospit'),
    					'dateentree' => $this->input->post('dateentree'),
    					'datesortie' => $this->input->post('datesortie'),
    					'nbredejrs' => $nbredejrs,
    					'motifhospit' => $this->input->post('motifhospit'),
    					'codechbre' => $this->input->post('codechbre'),
    					'idtypelit' => $this->input->post('idtypelit'),
    					'numfachospit' => $numfac,
    					);
    			
    					
                $this->all_model->add_ligne_with_return_id('admission', $data);
                
                
    			
    			$infos_patient = $this->all_model->get_fullrow('patient','idenregistremetpatient',$this->input->post('idenregistremetpatient'));
    			
    			if(!empty($infos_patient))
    			{
    			    $codeassurance = $infos_patient['codeassurance'] ;
    			}
    			else
    			{
    			    $codeassurance = '' ;
    			}
    			
    			$data_bed_allotment =  array('bed_id' => $this->input->post('idtypelit'),
    								'patient_id' => $this->input->post('idenregistremetpatient'),
    								'allotment_timestamp' => $this->input->post('dateentree'),
    								'discharge_timestamp' => $this->input->post('datesortie'),
    								'num_hospit' => $numhospit
    								);
    
    			$this->all_model->add_ligne_with_return_id('bed_allotment', $data_bed_allotment);
    			
    			$datafacture =  array('numfac' => $numfac,
    								'idenregistremetpatient' => $this->input->post('idenregistremetpatient'),
    								'montanttotal' => 0,
    								'montant_ass' => 0,
    								'montant_pat' => 0,
    								'codeassurance' => $codeassurance,
    								'datefacture' => date('Y-m-d'),
    								'type_facture' => 3
    								);
    
    			$this->all_model->add_ligne_with_return_id('factures', $datafacture);
    			
    			/*************DOSSIER PATIENT*******************/
			
			    $codpatient = $this->input->post('idenregistremetpatient') ;
			    
			    $codetypedossier = "DH";
			
			    $infos_dossier = $this->all_model->get_dossier_patient($codpatient,$codetypedossier) ;
			    
			    if(empty($infos_dossier))
			    {
			        $table = "dossierpatient";
        			$id_max = "numdossier";
        			$id_where = "codetypedossier";
        			$id_val = "DH";
        
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
        					'datecrea' => date('Y-m-d'),
        					'codetypedossier' => 'DH'
        					);
        
        			$this->all_model->add_ligne_with_return_id("dossierpatient",$data);
    
    			    // Effectuer la journalisation
    		        $type_action = 'Ajout' ;
    
    		        $action_effectuee = 'Dossier' ;
    
    		        $this->control->journalisation($type_action,$action_effectuee) ;
    		        
    		        
			    }
    			
    		/************** DOSSIER PATIENT*************************/
    
    			$this->session->set_flashdata('SUCCESSMSG', "Enregistrement effectué avec succès!!");
    
    			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
    
            	$page_data['page_libprofil'] = $UniqueProfil;
    			$page_data['page_name'] = 'AdmissionRegister';
    			$page_data['page_active'] = 'AdmissionPage';
    			$page_data['page_profil'] = $this->session->userdata('user_profil');
    			$page_data['page_title'] = 'Lostro Admin';
    			$page_data['page_s_title'] = 'Ajouter une Admission';

    			// Effectuer la journalisation
				$type_action = 'Ajout' ;

				$action_effectuee = 'Admission' ;

				$this->control->journalisation($type_action,$action_effectuee) ;
    
    			// affichage de la vue
    
            	//$this->render_template('kameleon/AdmissionRegister', $page_data);
    
            	redirect('Patient/AdmissionRegister/');
			}
		
		}
		else
		{	
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

			$ChbreHospit = $this->PatientModel->getAllChbreHospit();
			
			$TypeHospit = $this->PatientModel->getAllTypeHospit();
			
			$NatureHospit = $this->PatientModel->getAllNatureHospit();

			$TypeLitHospit = $this->all_model->get_table('bed');

        	$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['ChbreHospit'] = $ChbreHospit;
			$page_data['TypeHospit'] = $TypeHospit;
			$page_data['NatureHospit'] = $NatureHospit;
			$page_data['TypeLitHospit'] = $TypeLitHospit;
			$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'AdmissionRegister';
			$page_data['page_active'] = 'AdmissionPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter une Admission';

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire d\'ajout d\'admission';

			$this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        	$this->render_template('kameleon/AdmissionRegister', $page_data);
		}
    }
    
    public function AdmissionUpdater($numhospit=null)
    {
		if(!empty($_POST))
		{
		    $this->load->library('form_validation');

			// définition des règles de validation
			
		  $this->form_validation->set_rules('idenregistremetpatient', '<< Nom du patient >>', 'trim|required');
		  $this->form_validation->set_rules('codemedecin', '<< Nom du Medecin >>', 'trim|required');
		  $this->form_validation->set_rules('codetypehospit', '<< Type de l\'hospitalitation >>', 'trim|required');
		  $this->form_validation->set_rules('codenaturehospit', '<< Nature de l\'hospitalitation >>', 'trim|required');
		  $this->form_validation->set_rules('dateentree', '<< Date d\'entrée >>', 'trim|required');
		  $this->form_validation->set_rules('datesortie', '<< Date sortie >>', 'trim|required');
		  $this->form_validation->set_rules('motifhospit', '<< Motif Hospitalisation >>', 'trim|required');
		  $this->form_validation->set_rules('codechbre', '<< Chambre à occuper >>', 'trim|required');
		  $this->form_validation->set_rules('idtypelit', '<< Lit à occuper >>', 'trim|required');

		  $numhospit = $this->input->post('numero_admission');

			if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
		    {
			   // erreur : retour au formulaire
		        $flash_feedback = validation_errors();

			    $this->session->set_flashdata('DANGERMSG', $flash_feedback);
			    
			    	redirect('Patient/AdmissionUpdater/'.$numhospit.'');
			    exit();
			}
			else
			{
				

			    $dateentree = $this->input->post('dateentree');
    		    $datesortie = $this->input->post('datesortie');
    		    if($dateentree > $datesortie)
    		    {
    		        $flash_feedback = 'La date d\'entrée ne doit pas être supérieur à la date de sortie.';
    
    			    $this->session->set_flashdata('DANGERMSG', $flash_feedback);
    			    
    			    	redirect('Patient/AdmissionUpdater/'.$numhospit.'');
    			    exit();
    		    }
			    
			    
    			$codenaturehospit = $this->input->post('codenaturehospit');
    			
    			$numfac = $this->input->post('numero_facture');
    			
    
    			$dateentree = $this->input->post('dateentree');
    		    $datesortie = $this->input->post('datesortie');
    
    			// On transforme les 2 dates en timestamp
    	        $date1 = strtotime($dateentree);
    	        $date2 = strtotime($datesortie);
    	         
    	        // On récupère la différence de timestamp entre les 2 précédents
    	        $nbJoursTimestamp = $date2 - $date1;
    	         
    	        // ** Pour convertir le timestamp (exprimé en secondes) en jours **
    	        // On sait que 1 heure = 60 secondes * 60 minutes et que 1 jour = 24 heures donc :
    	        $nbredejrs = $nbJoursTimestamp/86400; // 86 400 = 60*60*24 
    
    
    			$data =  array('numhospit' => $numhospit,
    					'idenregistremetpatient' => $this->input->post('idenregistremetpatient'),
    					'codemedecin' => $this->input->post('codemedecin'),
    					'codetypehospit' => $this->input->post('codetypehospit'),
    					'codenaturehospit' => $this->input->post('codenaturehospit'),
    					'dateentree' => $this->input->post('dateentree'),
    					'datesortie' => $this->input->post('datesortie'),
    					'nbredejrs' => $nbredejrs,
    					'motifhospit' => $this->input->post('motifhospit'),
    					'codechbre' => $this->input->post('codechbre'),
    					'idtypelit' => $this->input->post('idtypelit')
    					);
    
    			$this->all_model->update_ligne('admission', $data, 'numhospit', $numhospit);

    			$infos_patient = $this->all_model->get_fullrow('patient','idenregistremetpatient',$this->input->post('idenregistremetpatient'));
    			
    			if(!empty($infos_patient))
    			{
    			    $codeassurance = $infos_patient['codeassurance'] ;
    			}
    			else
    			{
    			    $codeassurance = '' ;
    			}

    			$datafacture =  array('numfac' => $numfac,
    								'idenregistremetpatient' => $this->input->post('idenregistremetpatient'),
    								'codeassurance' => $codeassurance,
    								'datefacture' => $dateentree
    								);
    
    			$this->all_model->update_ligne('factures', $datafacture, 'numfac', $numfac);

    			$datapharmacie =  array('customer_name' => $this->input->post('idenregistremetpatient'),
    								'num_hospit' => $numhospit
    								);
    
    			$this->all_model->update_ligne('orders', $datapharmacie, 'num_hospit', $numhospit);


    			$data_bed_allotment =  array('bed_id' => $this->input->post('idtypelit'),
    								'patient_id' => $this->input->post('idenregistremetpatient'),
    								'allotment_timestamp' => $this->input->post('dateentree'),
    								'discharge_timestamp' => $this->input->post('datesortie')
    								);

    			$this->all_model->update_ligne('bed_allotment', $data_bed_allotment, 'num_hospit', $numhospit);
    
    			$this->session->set_flashdata('SUCCESSMSG', "Modification effectuée avec succès!!");

    			// Effectuer la journalisation
				$type_action = 'Modification' ;

				$action_effectuee = 'Admission' ;

				$this->control->journalisation($type_action,$action_effectuee) ;
    
    			// affichage de la vue
    
            	redirect('Hospitalisation/Liste_admission/');
			}
		
		}
		else
		{	

			$page_data['infos_admission'] = $this->all_model->get_fullrow('admission','numhospit',$numhospit);

			$page_data['infos_patient'] = $this->all_model->get_fullrow('patient','idenregistremetpatient',$page_data['infos_admission']['idenregistremetpatient']);

			$page_data['infos_medecin'] = $this->all_model->get_fullrow('medecin','codemedecin',$page_data['infos_admission']['codemedecin']);

			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

			$ChbreHospit = $this->PatientModel->getAllChbreHospit();
			
			$TypeHospit = $this->PatientModel->getAllTypeHospit();
			
			$NatureHospit = $this->PatientModel->getAllNatureHospit();

			$TypeLitHospit = $this->all_model->get_table('bed');

        	$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['ChbreHospit'] = $ChbreHospit;
			$page_data['TypeHospit'] = $TypeHospit;
			$page_data['NatureHospit'] = $NatureHospit;
			$page_data['TypeLitHospit'] = $TypeLitHospit;
			$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'AdmissionRegister';
			$page_data['page_active'] = 'AdmissionPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Modifier une Admission';

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire de modification d\'admission';

			$this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        	$this->render_template('kameleon/AdmissionUpdater', $page_data);
		}
    }

    public function demande_examen()
	{
		if(!empty($_POST))
		{

		}
		else
		{
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'demande_examen_register';
			$page_data['page_active'] = 'SoinAmbulatoirePage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Enregistrer et imprimer une facture d\'examen';
			// affichage de la vue

        	$this->render_template('kameleon/demande_examen_register', $page_data);
		}
	}
	
	public function getBiologieValueById()
	{
		$biologie_id = $this->input->post('biologie_id');
		if($biologie_id) {
			$biologie_data = $this->all_model->get_fullrow_all('examen','numexam',$biologie_id);
			echo json_encode($biologie_data);
		}
	}

	public function getTableBiologieRow()
	{
		$examens_biologiques = $this->all_model->get_fullrow_all('examen','codfamexam','B');
		echo json_encode($examens_biologiques);
	}

	public function getTableImagerieRow()
	{
		$examens_biologiques = $this->all_model->get_fullrow_all('examen','codfamexam','Y');
		echo json_encode($examens_biologiques);
	}

	public function getTauxCouverture()
	{
		$patient_id = $this->input->post('patient_id');

		if($patient_id) {
			$patient_taux_data = $this->all_model->getTauxCouverture($patient_id);

			echo json_encode($patient_taux_data);
		}
	}




	public function RdvRegister()
	{
		if(!empty($_POST))
		{

			//////////////////////////////
		 	$idenregistremetpatient = $this->input->post('idenregistremetpatient');

			$prestation = $this->input->post('prestation');

			$codeacte = explode("|", $prestation);

			$codeacte = $codeacte[0];

			$datecons = date('Y-m-d') ;

			$resultat = $this->PatientModel->verifier_cons_patient($idenregistremetpatient,$codeacte,$datecons);


			 if(empty($resultat))
			 {
			     /**************************/
			     
			    $compteur_consultation = $this->all_model->get_fullrow('tnumref','libref','COMPTEUR CONSULTATION');
			 
    			if(!empty($compteur_consultation))
    			{
    			    $max_num = $compteur_consultation['cptnref'] ;
    			}
    			else
    			{
    			  $max_num = '' ;  
    			}
    			
    			$annee = substr(date('Y'),-2);
    			
    			if ($max_num == '') {
    
    				$numfac = "FCE".$annee.'0'.'0'.'0'.'1';
    				
    				$lastNumber = '0001';
    				
    				$compteur_cons = 1 ;
    			}
    			else
    			{
    			    
    				$lastNumber = $max_num + 1 ;
    				
    				$compteur_cons = $lastNumber ;
    
    				$lastNumber ;
    
    				$zeroadd = "".$lastNumber ;
    
    				while (strlen($zeroadd) < 4) {
    					
    					$zeroadd = "0" . $zeroadd ;
    				}
    				
    				$lastNumber = $zeroadd ;
    				
    				$numfac = "FCE".$annee.$lastNumber;
    			}
			     
			     
			     /*************************/
			 	/*$result_dern_fac = $this->PatientModel->get_max_facture('FCE');
			    
			    $dernier_fac = $result_dern_fac['numfac'];

			    //S IL S AGIT DU PREMIER NUMERO DE FAC

			    if($dernier_fac == "")
			    {
			        
			        $decoupe = explode("-", $datecons);

			        $annee = $decoupe[0];
			        $annee_coup = substr($annee, 2, 3);
			        $numfac = "FCE".$annee_coup.'0'.'0'.'0'.'1';
			        
			    } 
			    else 
			    if($dernier_fac != "")
			    {//S'IL EXISTE DEJA NUMERO DE FAC
			                
			    //CREATION DU NOUVEAU NUMERO DE FAC

			    $decoupe = explode("-", $datecons);
			    $annee = $decoupe[0];
			    $annee_coup = substr($annee, 2, 3);
			    $dern_nombre = substr($dernier_fac, 5, 4);
			    $nouv_nombre = $dern_nombre + 1;
			                    $str = "" . $nouv_nombre;
			                            while(strlen($str) < 4)
			                            {
			                                $str = "0" . $str;
			                            }
			                            $matn = $str;

			    $numfac = "FCE".$annee_coup.$matn;
			    }*/
			}
			else
			{
				$numfac = '';

				$this->session->set_flashdata('DANGERMSG', "Vous avez déjà enregistré la consultation");       		
			}

			$infos_patient = $this->all_model->get_fullrow('patient', 'idenregistremetpatient', $this->input->post('idenregistremetpatient'));
			

			$dataconsultation =  array(
				'idenregistremetpatient' => $this->input->post('idenregistremetpatient'),
				'numbon' => $this->input->post('numbon'),
				'montant' => $this->input->post('montant_cons'),
				'taux' => $this->input->post('tauxPatient'),
				'ticketmod' => $this->input->post('ticket_moderateur'),
				'partassurance' => $this->input->post('part_assur'),
				'codemedecin' => $this->input->post('codemedecin'),
				'codeacte' => $codeacte,
				'regle' => 0,
				'date' => $datecons,
				'facimprime' => 0,
				'numfac' => $numfac
			);
			
			if($this->input->post('remise') == ''){
				$remise = 0 ;
			}else{
				$remise = $this->input->post('remise');
			}

			$datafacture =  array('numfac' => $numfac,
				'idenregistremetpatient' => $this->input->post('idenregistremetpatient'),
				'remise' => $remise,
				'type_remise' => $this->input->post('type_remise'),
				'calcul_applique' => $this->input->post('type_calcul_montant'),
				'taux_applique' => $this->input->post('tauxPatient'),
				'montanttotal' => $this->input->post('montant_cons'),
				'montant_ass' => $this->input->post('part_assur'),
				'montant_pat' => $this->input->post('ticket_moderateur'),
				'codeassurance' => $infos_patient['codeassurance'],
				'datefacture' => $datecons,
				'type_facture' => 1
			);

			// Effectuer la journalisation
			$type_action = 'Ajout' ;

			$action_effectuee = 'Consulation clinique';

			$this->control->journalisation($type_action,$action_effectuee) ;



			$this->PatientModel->rdv_ambulatoire_Register($dataconsultation);
			
			/*************DOSSIER PATIENT*******************/
			
			    $codpatient = $this->input->post('idenregistremetpatient') ;
			    
			    $codetypedossier = "DC";
			
			    $infos_dossier = $this->all_model->get_dossier_patient($codpatient,$codetypedossier) ;
			    
			    if(empty($infos_dossier))
			    {
			        $table = "dossierpatient";
        			$id_max = "numdossier";
        			$id_where = "codetypedossier";
        			$id_val = "DC";
        
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
        					'datecrea' => date('Y-m-d'),
        					'codetypedossier' => 'DC'
        					);
        
        			$this->all_model->add_ligne_with_return_id("dossierpatient",$data);
    
    			    // Effectuer la journalisation
    		        $type_action = 'Ajout' ;
    
    		        $action_effectuee = 'Dossier' ;
    
    		        $this->control->journalisation($type_action,$action_effectuee) ;
    		        
    		        
			    }
    			
    		/************** DOSSIER PATIENT*************************/	

			if(empty($resultat))
			{
				$this->PatientModel->facture_ambulatoire_Register($datafacture);
				
				if($compteur_cons > 0)
			    {
			        $data_up = array('cptnref' => $compteur_cons);

		            $query_up = $this->all_model->update_ligne('tnumref', $data_up, 'libref', 'COMPTEUR CONSULTATION');
			    }

				$this->session->set_flashdata('SUCCESSMSG', "Enregistrement effectué avec succès!!");

				$this->session->set_userdata('tab_active', 2);
			}


			

        	redirect('Patient/RdvRegister/');
		}
		else
		{
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'RdvRegister';
			$page_data['page_active'] = 'SoinAmbulatoirePage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Processus de gestion des consultations';

			$page_data['tab_active'] = $this->session->userdata('tab_active') ;
			
			$page_data['patient_and_code'] =  "";
			
			$page_data['taux'] = $this->all_model->get_table('tauxcouvertureassure') ;

			// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire d\'ajout de consultation clinique' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        	$this->render_template('kameleon/RdvRegister', $page_data);
		}
	}
	
	function rdvcons($codepatient)
	{
		if(isset($codepatient))
		{
			$infos_patient =  $this->all_model->get_fullrow('patient','idenregistremetpatient',$codepatient);

			if(!empty($infos_patient))
			{
				$page_data['patient_and_code'] =  trim($infos_patient['nomprenomspatient'].'|'.$infos_patient['idenregistremetpatient']);
			}else{
				$page_data['patient_and_code'] =  "";
				
				redirect('Patient/recherche_patient/');
			}
			
		}else{
		    $page_data['patient_and_code'] =  "";
		    
		    redirect('Patient/recherche_patient/');
		}
		
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

		$page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_name'] = 'RdvRegister';
		$page_data['page_active'] = 'SoinAmbulatoirePage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Processus de gestion des consultations';

		$page_data['tab_active'] = $this->session->userdata('tab_active') ;

		// Effectuer la journalisation
		$type_action = 'Consultation' ;

		$action_effectuee = 'Formulaire d\'ajout de consultation clinique' ;

		$this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue

		$this->render_template('kameleon/RdvRegister', $page_data);
	}

	function get_autocomplete($table){

        if (isset($_GET['term'])) 
        {
            $result = $this->patientmodel->search_patient($_GET['term'],$table);
            
            if (count($result) > 0) 
            {
            	if($table == 'patient')
				{
		            foreach ($result as $row)
		                //$arr_result[] = $row->blog_title;
		                $arr_result[] = $row['nomprenomspatient'].'|'.$row['idenregistremetpatient'];
		                echo json_encode($arr_result);
		        }

		        if($table == 'medecin')
				{
		            foreach ($result as $row)
		                $arr_result[] = $row['nomprenomsmed'].'|'.$row['codemedecin'];
		                echo json_encode($arr_result);
		        }
		    }
        }
    }
    
    function get_produitPatient(){

        if (!empty($_POST)) 
        {
        	$codeassur = $this->input->post('codeassur');

        	$codeassur = trim($codeassur);

			if($codeassur == '')
			{
				?>
			      <option value="" selected="seleted">---</option>
			 <?php   
			        
			      
			}


			/****************************************/
			if($codeassur != '')
			{ 
				$infos_produit= $this->all_model->get_fullrow_bis('produit_assurance','codeassurance',$codeassur); 
			
			 ?>
			      <option value="" selected="seleted">---</option>
			 <?php
			      foreach ($infos_produit as $res) 
			      { 
			 ?>
			      		<option value="<?php echo $res['codeproduit'] ?>"><?php echo $res['libelleproduit'] ; ?></option>";
			  <?php    
			        
			      }           
			 
			}		

        }
    }

    function get_typePatient(){

        if (!empty($_POST)) 
        {
        	$codepatient = $this->input->post('codepatient');

        	if($codepatient == '')
			{
				echo '<div class="alert alert-danger">
			                                <button type="button" class="close" data-dismiss="alert">&times;</button>
			                                <strong>Veuillez sélectionner le type d\'hospitalisation.</strong>
			                              </div>';
			                        exit();
			}

            if($codepatient != '')
			{
			  $result = $this->PatientModel->get_UniquePatient_assurance($codepatient);

			    if(!empty($result['codeassurance']))
			    {
			    	if($result['codeassurance'] == 'NONAS')
			    	{
			    		echo '<input type="hidden" class="form-control" id="patientassure" value="0">
			                      <input type="hidden" class="form-control" id="valeurcodeass" value="'.$result['codeassurance'].'">
			                      <input type="hidden" class="form-control" id="valeurlibass" value="'.$result['libelleassurance'].'">';
			                            exit();  
			    	}
			    	else
			    	{
			    		echo '<input type="hidden" class="form-control" id="patientassure" value="1">
			                      <input type="hidden" class="form-control" id="valeurcodeass" value="'.$result['codeassurance'].'">
			                      <input type="hidden" class="form-control" id="valeurlibass" value="'.$result['libelleassurance'].'">';
			                            exit();  
			    	}
			    	
			    }    
			            
			}
        }
    }

    function get_tarifPatient(){

        if (!empty($_POST)) 
        {
        	$codeassur = $this->input->post('codeassur');

        	$codepatient = $this->input->post('codepatient');

        	$codeassur = trim($codeassur);

        	$codepatient = trim($codepatient);

			if($codeassur == '')
			{
				echo '<div class="alert alert-danger">
			                                <button type="button" class="close" data-dismiss="alert">&times;</button>
			                                <strong>Veuillez sélectionner le type d\'hospitalisation.</strong>
			                              </div>';
			                        exit();
			}


			/****************************************/
			if($codeassur != '')
			{ 
				$infos_patient = $this->all_model->get_fullrow('patient','idenregistremetpatient',$codepatient); 

			
				if(!empty($infos_patient))
				{
					$codeproduit = $infos_patient['codeproduit'] ;
				}
				else
				{
					$codeproduit = "" ;
				}

				

				$resultat = $this->PatientModel->get_tarif_assurance($codeassur,$codeproduit);


			 ?>
			      <option selected="seleted">---</option>
			 <?php
			      foreach ($resultat as $res) 
			      { 
			 ?>
			      		<option value="<?php echo $res['codgaran']."|".$res['montjour']."|".$res['montnuit']."|".$res['libgaran']."|".$res['forfait']."|".$res['montferie'] ?>"><?php echo $res['libgaran'] ; ?></option>";
			  <?php    
			        
			      }           
			 
			}		

        }
    }

    function get_uniktarifPatient(){

        if (!empty($_POST)) 
        {
        	$codepatient = $this->input->post('codepatient');

        	$codeacte = $this->input->post('codeacte');

        	if($codeacte)
			{
				$rech = explode("|", $codeacte);
			}

			/*********************************************************************************/
			
            $infos_patient = $this->all_model->get_fullrow('patient','idenregistremetpatient',$codepatient);
            
			$resultat = $this->PatientModel->get_taux_patient($codepatient); 
			
			if(!empty($resultat))
			{
			    $valeur_taux = $resultat['valeurtaux'] ;
			}else{
			    $valeur_taux = 0 ;
			}
			
			if(!empty($infos_patient))
			{
			    $patient_assure = $infos_patient['assure'] ;
			    
			    if(($infos_patient['assure'] == 0) && ($codeacte == 'CONSCG')) 
    			{
    				$verifcontrol = $this->PatientModel->get_cons_control_hebdo($rech[0],$codepatient);
    			}
    			else
    			{
    				$verifcontrol = $this->PatientModel->get_cons_control($rech[0],$codepatient); 
    			} 
    
    
    			if(isset($verifcontrol['idenregistremetpatient']))
    			{
    				$control = 'yes' ;
    			}
    			else
    			{
    				$control = 'no' ;
    			}
			}else{
			    $patient_assure = 0 ;
			    
			    $control = 'no' ;
			}

			

			echo '<input type="hidden" id="val_codeacteprest" value="'.$codeacte.'">
				  <input type="hidden" id="tauxpolAssure" value="'.$valeur_taux.'">
				 <input type="hidden" id="verifcontrol" value="'.$control.'">
				 <input type="hidden" id="type_patient" value="'.$patient_assure.'">
				 ' ;
				 exit();
			/*********************************************************************************/

        }
    }

    function RdvAmbulPrint(){

        if (!empty($_POST)) 
        {
        	$codepatient = $this->input->post('idpatientfac');

        	$date = date('Y-m-d');

			echo'<hr style="height: 1px;color: #F00;background-color: #07AEBC;border: 0;">
			<center><div style="width: 100%; padding-top: 0px; padding-bottom: 1px; border: 1px solid #005CFF; text-align: center;border-radius: 10px;"><strong> <h4>LISTE DES FACTURES DE CONSULTATION EN ATTENTE</h4> </strong></div>
			</center>
			<br/>
			<div class="affichage">

			  <div class="box-content">
			    <table id="example1" class="table responsive datatable">
			    <thead style="background-color:#07AEBC; color: #fff">
			    <tr>
			        <th><center>N.I.P.</center></th>
			        <th><center>Nom & prénoms</center></th>
			        <th><center>Montant</center></th>
			        <th><center>Date Consultation</center></th>
			        <th><center>Ticket Mod.</center></th>
			        <th><center>Part Assurance</center></th>
			        <th><center>Action</center></th>
			    </tr>
			    </thead>
			    <tbody>';

			    $factureCons = $this->PatientModel->get_facture_cons($codepatient,$date);
								
												foreach($factureCons as $row)
												{
													$rowAssure = $this->PatientModel->get_UniquePatient_info($row['idenregistremetpatient']);
													?>
												 <tr>
													<td><center><?= $row['idenregistremetpatient'] ?></center></td>
													<td><center><?= $rowAssure['nompatient'].' '.$rowAssure['prenomspatient'] ?></center></td>
													<td><center><?= $row['montant'] ?></center></td>
													<td><center><?= $this->fdateheuregmt->date_fr($row['date']) ?></center></td>
													<td><center><?= $row['ticketmod'] ?></center></td>
													<td><center><?= $row['partassurance'] ?></center></td>
													<?php if($row['montant'] == 0) { ?>
													<td><center>Aucune facture (Control medical)</center></td>
												  	<?php }else{ ?>
													<td><center><a href="<?php echo base_url() ?>PrintC/FactureConsPrint/<?= $row['numfac'] ?>" target=”_blank”; title="Imprimer"><i class="fa fa-print"></i></a></center></td>
												  	<?php } ?>
												  </tr>
											  <?php
												}
											  ?>
					
			    </tbody>
			    </table>
			    </div>
			    </div>
<?php
        }
    }

    //////////////////////////////////

    function FicheConsPrint(){

        if (!empty($_POST)) 
        {
        	$codepatient = $this->input->post('idpatientfac');

        	$date = date('Y-m-d');

			echo'<hr style="height: 1px;color: #F00;background-color: #07AEBC;border: 0;">
			<center><div style="width: 100%; padding-top: 0px; padding-bottom: 1px; border: 1px solid #005CFF; text-align: center;border-radius: 10px;"><strong> <h4>LISTE DES FICHES DE CONSULTATION EN ATTENTE</h4> </strong></div>
			</center>
			<br/>
			<div class="affichage">

			  <div class="box-content">
			    <table id="example1" class="table responsive datatable">
			    <thead style="background-color:#07AEBC; color: #fff">
			    <tr>
			        <th><center>N.I.P.</center></th>
			        <th><center>Nom & prénoms</center></th>
			        <th><center>Montant</center></th>
			        <th><center>Date Consultation</center></th>
			        <th><center>Ticket Mod.</center></th>
			        <th><center>Part Assurance</center></th>
			        <th><center>Action</center></th>
			    </tr>
			    </thead>
			    <tbody>';

			    $factureCons = $this->PatientModel->get_facture_cons($codepatient,$date);
								
												foreach($factureCons as $row)
												{
													$rowAssure = $this->PatientModel->get_UniquePatient_info($row['idenregistremetpatient']);
													?>
												 <tr>
													<td><center><?= $row['idenregistremetpatient'] ?></center></td>
													<td><center><?= $rowAssure['nompatient'].' '.$rowAssure['prenomspatient'] ?></center></td>
													<td><center><?= $row['montant'] ?></center></td>
													<td><center><?= $row['date'] ?></center></td>
													<td><center><?= $row['ticketmod'] ?></center></td>
													<td><center><?= $row['partassurance'] ?></center></td>
													<td><center><a href="<?php echo base_url() ?>index.php/PrintC/FicheConsPrint/<?= $row['numfac'] ?>" target=”_blank”; title="Imprimer"><i class="fa fa-print"></i></a></center></td>
												  </tr>
											  <?php
												}
											  ?>
					
			    </tbody>
			    </table>
			    </div>
			    </div>
<?php
        }
    }

//////////////////////////////////

    function Historique_consultation(){

        if (!empty($_POST)) 
        {
        	$codepatient = $this->input->post('idpatientfac');

        	$infos_historique = $this->all_model->get_historique_cons($codepatient);

        	if(!empty($infos_historique))
        	{
        		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Historique de consultation clinique de patient' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
        	?>
				<hr style="height: 1px;color: #F00;background-color: #07AEBC;border: 0;">
				<center><div style="width: 100%; padding-top: 0px; padding-bottom: 1px; border: 1px solid #005CFF; text-align: center;border-radius: 10px;"><strong> <h4>LISTE DES CONSULTATIONS EFFECTUEES PAR LE PATIENT IL Y A MOINS DE 30 JOURS</h4> </strong></div>
				</center>
				<br/>
				<div class="affichage">

					<div class="box-content">
					    <table id="example1" class="table responsive datatable">
						    <thead style="background-color:#07AEBC; color: #fff">
							    <tr>
							    	<th><center>Date Consultation</center></th>
							    	<th><center>Nature acte</center></th>
							        <th><center>N.I.P.</center></th>
							        <th><center>Nom & prénoms</center></th>
							        <th><center>Medecin consultant</center></th>
							        <th><center>Montant</center></th>
							        <th><center>Ticket Mod.</center></th>
							        <th><center>Part Assurance</center></th>
							    </tr>
						    </thead>
						    <tbody>

						    <?php
										
								foreach($infos_historique as $row)
								{
									$rowAssure = $this->PatientModel->get_UniquePatient_info($row['idenregistremetpatient']);
																
									$infos_garantie = $this->all_model->get_fullrow('garantie','codgaran',$row['codeacte']);

									$infos_medecin = $this->all_model->get_fullrow('medecin','codemedecin',$row['codemedecin']);

							?>
								<tr>
									<td><center><?= $this->fdateheuregmt->date_fr($row['date']) ?></center></td>
									<td><center><?= $infos_garantie['libgaran'] ?></center></td>
									<td><center><?= $row['idenregistremetpatient'] ?></center></td>
									<td><center><?= $rowAssure['nompatient'].' '.$rowAssure['prenomspatient'] ?></center></td>
									<td><center><?= $infos_medecin['nomprenomsmed'] ?></center></td>
									<td><center><?= $row['montant'] ?></center></td>													<td><center><?= $row['ticketmod'] ?></center></td>
									<td><center><?= $row['partassurance'] ?></center></td>
								</tr>
						<?php   } ?>
					
						    </tbody>
						</table>
			        </div>
			    </div>
		<?php
		    }
		    else
		    {
		?>
		    	<hr style="height: 1px;color: #F00;background-color: #07AEBC;border: 0;">
				<center><div style="width: 100%; padding-top: 0px; padding-bottom: 1px; border: 1px solid #005CFF; text-align: center;border-radius: 10px;"><strong> <h4>AUCUNE CONSULTATION N'A ETE EFFECTUEE POUR CE PATIENT IL Y A MOINS DE 30 JOURS</h4> </strong></div>
				</center
		<?php	
		    }
		}
	}

//////////////////////////////////

    function Liste_factures_consultation(){

        if (!empty($_POST)) 
        {
        	$codepatient = $this->input->post('idpatientfac');

        	$infos_historique = $this->all_model->get_facture_cons($codepatient);

        	if(!empty($infos_historique))
        	{
        		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Factures de consultation clinique de patient non encaissées' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
        	?>
				<hr style="height: 1px;color: #F00;background-color: #07AEBC;border: 0;">
				<center><div style="width: 100%; padding-top: 0px; padding-bottom: 1px; border: 1px solid #005CFF; text-align: center;border-radius: 10px;"><strong> <h4>LISTE DES FACTURES DE CONSULTATIONS NON ENCAISSEES</h4> </strong></div>
				</center>
				<br/>
				<div class="affichage">

					<div class="box-content">
					    <table id="example1" class="table responsive datatable">
						    <thead style="background-color:#07AEBC; color: #fff">
							    <tr>
							    	<th><center>Date Consultation</center></th>
							    	<th><center>N° Facture</center></th>
							    	<th><center>Nature acte</center></th>
							        <th><center>N.I.P.</center></th>
							        <th><center>Nom & prénoms</center></th>
							        <th><center>Medecin consultant</center></th>
							        <th><center>Montant</center></th>
							        <th><center>Ticket Mod.</center></th>
							        <th><center>Part Assurance</center></th>
							        <th><center>Encaisser</center></th>
							    </tr>
						    </thead>
						    <tbody>

						    <?php
										
								foreach($infos_historique as $row)
								{
									$rowAssure = $this->PatientModel->get_UniquePatient_info($row['idenregistremetpatient']);
																
									$infos_garantie = $this->all_model->get_fullrow('garantie','codgaran',$row['codeacte']);

									$infos_medecin = $this->all_model->get_fullrow('medecin','codemedecin',$row['codemedecin']);

							?>
								<tr>
									<td><center><?= $this->fdateheuregmt->date_fr($row['date']) ?></center></td>
									<td><center><?= $row['numfac'] ?></center></td>
									<td><center><?= $infos_garantie['libgaran'] ?></center></td>
									<td><center><?= $row['idenregistremetpatient'] ?></center></td>
									<td><center><?= $rowAssure['nompatient'].' '.$rowAssure['prenomspatient'] ?></center></td>
									<td><center><?= $infos_medecin['nomprenomsmed'] ?></center></td>
									<td><center><?= $row['montant'] ?></center></td>													
									<td><center><?= $row['ticketmod'] ?></center></td>
									<td><center><?= $row['partassurance'] ?></center></td>
									<td><center><span data-tip="Ajouter l\'image"><a href="#" data-toggle="modal" data-target=".popresult"  class="addmod" data-h="<?= $row['idconsexterne'] ?>|Addencaissecons"><i class="fa fa-2x fa-save text-danger"></i></a></span></center></td>
								</tr>
						<?php   } ?>
					
						    </tbody>
						</table>
			        </div>
			    </div>
		<?php
		    }
		    else
		    {
		?>
		    	<hr style="height: 1px;color: #F00;background-color: #07AEBC;border: 0;">
				<center><div style="width: 100%; padding-top: 0px; padding-bottom: 1px; border: 1px solid #005CFF; text-align: center;border-radius: 10px;"><strong> <h4>AUCUNE FACTURE DE CONSULTATION NON REGLEE</h4> </strong></div>
				</center
		<?php	
		    }
		}
	}

///////////////////////////////////////////

    function recherche_valeurs(){

        if (!empty($_POST)) 
        {
        	$codepatientbio = $this->input->post('codepatientbio');

        	if($codepatientbio == '')
			{
				echo '<div class="alert alert-danger">
			                                <button type="button" class="close" data-dismiss="alert">&times;</button>
			                                <strong>Veuillez sélectionner le type d\'hospitalisation.</strong>
			                              </div>';
			                        exit();
			}


			/****************************************/
			if($codepatientbio != '')
			{
			   $query = $this->db->get_where('patient', array('idenregistremetpatient' => $codepatientbio));
        	   $resRechAssure = $query->row_array();
        	   
        	   if(!empty($resRechAssure))
			   {
			   		if($resRechAssure['codeproduit'] != '')
			   		{
			   			$codeproduit = $resRechAssure['codeproduit'] ;
			   		}
			   		else
			   		{
			   			$codeproduit = "" ;
			   		}	
			   }
			   else
			   {
					$codeproduit = "" ;
			   }

			  /*if($resRechAssure['assure'] == 1)
			  {*/
			  	$this->db->select('*');
				$this->db->from('patient');
				$this->db->join('tarifs', 'patient.codeassurance = tarifs.codeassurance');
				$this->db->where('patient.idenregistremetpatient', $codepatientbio);
				$this->db->where('tarifs.codgaran', 'B');
				$this->db->where('tarifs.codeproduit', $codeproduit);
				$query = $this->db->get();

		        $resRechvalb = $query->row_array();

			    $this->db->select('*');
				$this->db->from('patient');
				$this->db->join('tarifs', 'patient.codeassurance = tarifs.codeassurance');
				$this->db->where('patient.idenregistremetpatient', $codepatientbio);
				$this->db->where('tarifs.codgaran', 'Z');
				$this->db->where('tarifs.codeproduit', $codeproduit);
				$query = $this->db->get();

		        $resRechvalz = $query->row_array();

		        if(empty($resRechvalb))
			    {
			          echo '<div class="alert alert-danger">
			                <button type="button" class="close" data-dismiss="alert">&times;</button>
			                <strong>Vous n\' avez pas enrégistré la valeur du "B" dans les tarifs. Veuillez donc procéder à l\'enrégistrement de cette valeur dans les tarifs avant de poursuivre.</strong>
			                </div>';
			                            exit();    
			    }

			    if(empty($resRechvalz))
			    {
			          echo '<div class="alert alert-danger">
			                <button type="button" class="close" data-dismiss="alert">&times;</button>
			                <strong>Vous n\' avez pas enrégistré la valeur du "Z" dans les tarifs. Veuillez donc procéder à l\'enrégistrement de cette valeur dans les tarifs avant de poursuivre.</strong>
			                </div>';
			                            exit();    
			    }

			    if(($resRechvalb['idenregistremetpatient'] != '')||($resRechvalz['idenregistremetpatient'] != ''))
			    {
			          echo '<input type="hidden" class="form-control" id="valeurtarifb" value="'.$resRechvalb['montjour'].'">
			                      <input type="hidden" class="form-control" id="valeurtarifz" value="'.$resRechvalz['montjour'].'">';
			                            exit();    
			    } 
			  /*}
			  else if($resRechAssure['assure'] == 0)
			  {
			    echo '<input type="hidden" class="form-control" id="valeurtarifb" value="0">
			                      <input type="hidden" class="form-control" id="valeurtarifz" value="0">';
			                            exit();
			  }*/
			}

        }
        
    }
    
    public function recherche_tarifs_imagerie($numexam){

		$patient_id = $this->input->post('patient_id');

		$mode_patient = $this->input->post('mode_patient');

		/****************************************/
		if($patient_id != '')
		{
			$infos_patient = $this->all_model->get_fullrow('patient', 'idenregistremetpatient',$patient_id);
        	   
        	if(!empty($infos_patient))
			{
			   	if($infos_patient['codeproduit'] != '')
			   	{
			   		$codeproduit = $infos_patient['codeproduit'] ;
			   	}
			   	else
			   	{
			   		$codeproduit = "" ;
			   	}	
			}
			else
			{
				$codeproduit = "" ;
			}

			$infos_examen = $this->all_model->get_fullrow('examen','numexam',$numexam);

			$codgaran = $infos_examen['codgaran'];

			if($mode_patient == 0)
			{
				$codeassurance = $infos_patient['codeassurance'] ;
				$this->db->select('*');
				$this->db->from('patient');
				$this->db->join('tarifs', 'patient.codeassurance = tarifs.codeassurance');
				$this->db->where('patient.idenregistremetpatient', $patient_id);
				$this->db->where('tarifs.codgaran', $codgaran);
				$this->db->where('tarifs.codeproduit', $codeproduit);
				$this->db->where('patient.codeassurance', $codeassurance);
				$query = $this->db->get();
			}else{
				$codeassurance = 'NONAS' ;

				$this->db->select('*');
				$this->db->from('patient');
				$this->db->join('tarifs', 'patient.codeassurance = tarifs.codeassurance');
				$this->db->where('tarifs.codgaran', $codgaran);
				$this->db->where('patient.codeassurance', $codeassurance);
				$query = $this->db->get();
			}

			

			$resRechval = $query->row_array();

			echo json_encode($resRechval);
		}
	}
///////////////////////////////////////	
    public function PrintFacBulletin($numbulletin)
	{
			
			$page_data['print_name'] = 'i_FBU';
			$page_data['numbulletin'] = $numbulletin;

			$this->load->view('print/infoNet', $page_data);
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

	    $this->render_template('kameleon/recherche_patient', $page_data);	

        
	}
	
	public function infos_patient()
	{
			
		$patient_id = $this->input->post('idenregistremetpatient');

		redirect('Patient/PatientDetail/'.$patient_id.'','refresh');
	}
	
	public function PrintFac($numfac)
	{
			$date = date('Y-m-d');
			
			$codebare = $this->db->get_where('factures' , array('numfac'=>$numfac))->row_array();

			if(!empty($codebare))
			{
				$page_data['print_name'] = 'i_RC';
				$page_data['numfac'] = $numfac;
				//$page_data['codebare'] = $this->generateur_identifiant->code_barre($codebare['numrecu']);
				
				$page_data['codebare'] = '' ;
			}
			else
			{
				$page_data['numfac'] = $numfac;
				$page_data['print_name'] = 'i_RC';
			}

			// Effectuer la journalisation
		        $type_action = 'Impression' ;

		        $action_effectuee = 'Facture consultation clinique' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			$this->load->view('print/infoNet', $page_data);
	}
}
	
