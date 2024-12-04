<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class factHospit extends Admin_Controller {
    
    
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

			if ($curr_uri_string != 'factHospit') 
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
		
    public function factHospitRegister()
    {
		 $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

            $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'factHospitRegister';
			$page_data['page_active'] = 'factHospitPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter une fature d\'hospitalisation';
			
			$page_data['numpch'] = '' ;
		
		    $page_data['resum'] = '' ;

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire d\'ajout de facture d\'admission' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        	$this->render_template('kameleon/factHospitRegister', $page_data);
    }


    public function enreg_facture($num) 
	{
		@$numpch = $num;

		$modif_dispo = 'oui' ;

		$page_data['numpch'] = $numpch ;
		
		$page_data['resum'] = '' ;

		$page_data['bandeau'] = 'Facturation des hospitalisations';

		$page_data['title'] = 'Page de facturation des hospitalisations';

		$page_data['profil'] = $this->all_model->get_fullrow('profile', 'idprofile', $this->session->userdata('user_profil'));
		
		$page_data['infos_admission'] = $admission = $this->all_model->get_fullrow('admission','numhospit', $numpch);
		
		if(!empty($admission))
		{
		    $page_data['infos_patient'] = $this->all_model->get_fullrow('patient', 'idenregistremetpatient', $admission['idenregistremetpatient']);
		}else{
		    
		    $page_data['infos_patient'] = array() ;
		}
		

		if (empty($admission)) {

			$flash_feedback = "Le numéro d'hospitalisation que vous avez saisi est innexistant dans le système.";

			$this->session->set_flashdata('error', $flash_feedback);

			redirect('factHospit/factHospitRegister','refresh');
			exit();
		}

		$taux = $this->patientmodel->get_taux_patient($admission['idenregistremetpatient']);
		
		if(empty($taux))
		{
		   $page_data['taux_applique'] = 0 ; 
		   
		}else{
		    
		    $page_data['taux_applique'] = $taux['valeurtaux'];
		}

		
		

		$etat = 'facturer';

		$result = $this->all_model->get_fullrow('facturation_hospit','numpchr', $numpch);

		if($result)
		{
			$d = $this->all_model->get_infos_assure_hospit($numpch) ;

			$result_retour = $this->all_model->get_fullrow('factures','numfac', $d['numfachospit']);

			if(!empty($result_retour))
			{
				if($result_retour['montantregle_pat'] > 0)
				{
					$modif_dispo = 'non' ;
				}
			}
			
			$etat = 'modifier';
		}
		
		
		
		$infos_montant = $this->all_model->get_montant_pharmacie($numpch);
		
		if(!empty($infos_montant))
		{
		   $page_data['montant_pharmacie'] = $infos_montant['montant_pharmacie'] ; 
		}else{
		   $page_data['montant_pharmacie'] = 0 ;  
		}
		
		
		$page_data['etat'] = $etat ;

		$page_data['modif_dispo'] = $modif_dispo ;

		$page_data['info_assure'] =  "";
	
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_name'] = 'factHospitRegister';
		$page_data['page_active'] = 'factHospitPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Page de facturation des hospitalisations';

		// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire de facturation des admissions' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        	$this->render_template('kameleon/factHospitRegister', $page_data);
		
	}

	public function valider() 
	{
		$page_data['bandeau'] = 'Facturation des hospitalisations';
		$page_data['title'] = 'Page de facturation des hospitalisations';

		
		// initialisation du validateur du formulaire

	  $this->load->library('form_validation');

	// définition des règles de validation
		
		$choix = $this->input->post('choix');		
		$numpch = $this->input->post('numpch');
		$taux_applique = $this->input->post('taux_applique');
		
		$remise = $this->input->post('remise');
		
	switch ($choix) {
			case "SAVE":
			case "MOD":
			
					$garantie = $this->all_model->get_table('garanties_hospit');

					$montanttotal = 0;

					$montant_ass = 0 ;

					$montant_pat = 0 ;

					foreach($garantie as $value ) { 
						$codgaran = $value['id'];
						$montgaran = $this->input->post('codgaran_'.$codgaran);
						$qte = $this->input->post('qte_'.$codgaran);
						$pu = $this->input->post('pu_'.$codgaran);
						$montextra = $this->input->post('montextra_'.$codgaran);
						if($montgaran){
							$taux = $taux_applique;

							if(($montextra != '') && ($montextra > 0))
							{
								$part_S = (($montgaran - $montextra) * $taux ) / 100 ;

							    $part_A = ((($montgaran - $montextra) * (100 - $taux) ) / 100) + $montextra ;
							}
							else
							{
								$part_S = ($montgaran * $taux ) / 100 ;
								$part_A = $montgaran - $part_S ;
							}
							

							$this->all_model->save_facture_hospit($numpch,$codgaran,$montgaran,$part_S,$part_A,$qte,$pu,$montextra);
							
							$montanttotal = $montanttotal + $montgaran ;

							$montant_ass = $montant_ass + $part_S ;

							$montant_pat = $montant_pat + $part_A;
						
						}
					}
					//exit();

			

			$d = $this->all_model->get_infos_assure_hospit($numpch) ;

			$datafacture =  array('numfac' => $d['numfachospit'],
								'idenregistremetpatient' => $d['idenregistremetpatient'],
								'remise' => $remise,
								'montanttotal' => $montanttotal,
								'montant_ass' => $montant_ass,
								'montant_pat' => $montant_pat,
								'codeassurance' => $d['codeassurance'],
								'datefacture' => date('Y-m-d')
								);
			
			$this->all_model->delete_ligne('factures', 'numfac', $d['numfachospit']);

			$this->all_model->add_ligne_with_return_id('factures', $datafacture);
					
			$page_data['resum'] = $numpch ;
				
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

            $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'factHospitRegister';
			$page_data['page_active'] = 'factHospitPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter une fature d\'hospitalisation';
			
			

			// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Facture d\'admission' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

        	$this->render_template('kameleon/factHospitRegister', $page_data);
			
			break;

			case "DEL":

				$d = $this->all_model->get_infos_assure_hospit($numpch) ;

				$datafacture =  array('numfac' => $d['numfachospit'],
								'idenregistremetpatient' => $d['idenregistremetpatient'],
								'remise' => $remise,
								'montanttotal' => $montanttotal,
								'montant_ass' => $montant_ass,
								'montant_pat' => $montant_pat,
								'codeassurance' => $d['codeassurance'],
								'datefacture' => date('Y-m-d')
								);

				$this->all_model->delete_lignes('facturation_hospit', 'numpchr', $numpch);

				$this->all_model->delete_ligne('factures', 'numfac', $d['numfachospit']);

				// Effectuer la journalisation
		        $type_action = 'Suppression' ;

		        $action_effectuee = 'Facture d\'admission' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

				redirect('factHospit/factHospitRegister');
			
			
			break;
		}

	}

}
	
