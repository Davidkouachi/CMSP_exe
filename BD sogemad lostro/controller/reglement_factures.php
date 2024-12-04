<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reglement_factures extends Admin_Controller {
	
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

			if ($curr_uri_string != 'reglement_factures') 
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
	----------				PAGE RACINE :: ./reglement_factures  ----------
	################################################################## */
	
	public function index() 
	{
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        $page_data['page_libprofil'] = $UniqueProfil;

        $Assurances = $this->PatientModel->getAssurancePatient();

        $page_data['Assurances'] = $Assurances;

        $page_data['page_debut'] = 'oui';

        $page_data['page_active'] = "RecouvrementPage";

		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Règlement de factures';
		$page_data['page_s_title'] = 'Page de reglement de factures';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Page de reglement de factures' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue
		$this->render_template('reglement_factures/regler_factures', $page_data);
		
	}
	
	public function regler() 
	{
		
		$this->load->library('form_validation');

	// définition des règles de validation

	   $this->form_validation->set_rules('etablissementPayeur', 'Etablissement payeur', 'trim|required');
		
		 if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
	   { 
		   // erreur : retour au formulaire

			$flash_feedback = validation_errors();
			$this->session->set_flashdata('error', $flash_feedback);

			// redirection

			redirect('reglement_factures');
			return FALSE ;
		}
		else
		{
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	        $page_data['page_libprofil'] = $UniqueProfil;

	        $Assurances = $this->PatientModel->getAssurancePatient();

	        $page_data['Assurances'] = $Assurances;

	        $page_data['page_debut'] = '';

	        $page_data['page_active'] = "RecouvrementPage";

			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Règlement de factures';
			$page_data['page_s_title'] = 'Page de reglement de factures';

			$etablissementPayeur = $this->input->post('etablissementPayeur');
	
			$page_data['result'] = $this->all_model->factures_non_regler($etablissementPayeur);
		
			$page_data['infos_assurance'] = $this->all_model->get_fullrow('assurance','codeassurance',$etablissementPayeur);

			// affichage de la vue
			$this->render_template('reglement_factures/regler_factures', $page_data);
			
		}
		
	}

	public function search_facture()
	{
		$numregle = $this->input->post('numregle');

		$societe = $this->input->post('societe');

		$numfac_tp = $this->input->post('numfac_tp');

		$page_data['page_debut'] = '';

		$page_data['result'] = $this->all_model->get_fullrow_bis('factures','numfac_tp',$numfac_tp);

		$this->render_template('reglement_factures/detail_facture', $page_data);
	}

	public function save()
	{

		$output = array('error' => false);


		$this->load->library('form_validation');

	// définition des règles de validation
		
	   $this->form_validation->set_rules('mtcheq', 'Le montant à payer', 'trim|required');
	   $this->form_validation->set_rules('numcheq', 'Le numéro de chèque', 'trim|required');

		 if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
	   {
		   // erreur : retour au formulaire
			$output['error'] = true;
	        $output['message'] = validation_errors();
		}
		else
		{
			$nbre_ligne = $this->input->post('nbre_ligne');
			$numcheq = $this->input->post('numcheq');
			$datecheque = $this->input->post('datecheque');
			$choix = $this->input->post('choix');
			$factures_tiers = explode(',',$choix);

			$verif_numcheq = $this->all_model->get_table_where('factures_tierspayant','num_cheque', $numcheq);

			if(!$verif_numcheq)
			{
				foreach($factures_tiers as $checkoptions)
				{
					$check = explode("|", $checkoptions);

					$numregle = $check[0];

					$infos_facture = $this->all_model->get_fullrow('factures_tierspayant', 'numfac_tp', $numregle);

					$data_verif = array(
								'num_cheque' => $numcheq,
								'date_reglement' => $datecheque,
								'montant_regle' => $infos_facture['montant_facture'],
								'regle' => '1'
							);

					$this->all_model->update_ligne('factures_tierspayant', $data_verif, 'numfac_tp' , $numregle);
				}

				for ($i=1; $i < $nbre_ligne+1 ; $i++) { 
				
					$montant_rejet = $this->input->post('rejet_'.$i);

					$numfactp = $this->input->post('numfactp_'.$i);

					$motif_rejet = $this->input->post('motif_rejet_'.$i);

					$montant_facture = $this->input->post('montant_facture_'.$i);

					$montant_regle = $montant_facture - $montant_rejet ;

					$data_infos = array('montant_regle' => $montant_regle,'montant_rejete' => $montant_rejet,'motif_rejet' =>$motif_rejet);

					if(($montant_rejet !='') && ($motif_rejet !=''))
					{
						$this->all_model->update_ligne('factures_tierspayant', $data_infos, 'numfac_tp' , $numfactp);
					}

				
				}

				// Effectuer la journalisation
				$type_action = 'Ajout' ;

				$action_effectuee = 'Reglement de factures tiers payant' ;

				$this->control->journalisation($type_action,$action_effectuee) ;

				$flash_feedback = "<p><h4>Enrégistrement effectué avec succès.</p></h4>";

				$this->session->set_flashdata('success', $flash_feedback);

				$output['numcheq'] =$numcheq;
			}
			else
			{
				$output['error'] = true;
				$output['message'] = 'Ce numéro de chèque existe déjà dans la base de données.';
			}


	}

		 echo json_encode($output);
		 exit();

	}

}









/* End of file facturation_mensuelle.php */
    /* Location: ./application/controllers/envoi_sms.php */
	
