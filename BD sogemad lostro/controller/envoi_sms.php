<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Envoi_sms extends Admin_Controller {
	
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

			if ($curr_uri_string != 'envoi_sms') 
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
	----------				PAGE RACINE :: ./facturation hospit  ----------
	################################################################## */
	
	public function index() 
	{
		

		$page_data['bandeau'] = 'Envoi de SMS';
		$page_data['title'] = 'Envoi de code de validation';

		$page_data['profil'] = $this->all_model->get_fullrow('profile', 'idprofile', $this->session->userdata('user_profil'));

		// Recherche du prestataire

		$page_data['prestataire'] = $this->all_model->get_fullrow('presta', 'codpresta', $this->session->userdata('user_codpresta'));
		
		
		
	
		// affichage de la vue
		$this->render_template('sms_internet/envoi_sms_prise_en_charge', $page_data);
		
	}
	
	
	public function numero($debut,$num) 
	{
		
		@$numpch = $debut.'/'.$num;
		$page_data['numpch'] = $numpch ;
		
		$page_data['bandeau'] = 'Envoi de SMS';
		$page_data['title'] = 'Envoi de code de validation';

		$page_data['profil'] = $this->all_model->get_fullrow('profile', 'idprofile', $this->session->userdata('user_profil'));

		// Recherche du prestataire

		$page_data['prestataire'] = $this->all_model->get_fullrow('presta', 'codpresta', $this->session->userdata('user_codpresta'));
		
		
		$sms='';
		$tel ='';
		
		
		switch($debut){
				
			
		case 'OP':
				
				
				$resultat = $this->all_model->get_fullrow('demande_ophtamo','codedemophtamo',$numpch);
			if($resultat)
			{
				$tel = $resultat['contactpatient'];
				
				if($resultat['etatdemande']=='traite'){
					
					$codeval = $this->all_model->get_field_by_id('codevaldemande','codevalidation','numdemande',$numpch);
					$etat = 'accordée';
					$fin_sms='Code de validation:'.$codeval;
					$sms='Prise en charge ('.$numpch.') '.$etat.'. '.$fin_sms;
					
				}else 
				if($resultat['etatdemande']=='refuse'){
					
					$motif = $this->all_model->get_field_by_id('demande_ophtamo','motifrefus','codedemophtamo',$numpch);
					$etat = 'refusée';
					$fin_sms='Motif:'.$motif;
					$sms='Prise en charge ('.$numpch.') '.$etat.'. '.$fin_sms;
					
				}
				
				$matricule = $resultat['matricule'];
				$page_data['info_assure'] = $this->admin_model->get_infos_assure_habilite($matricule);
				$type = 'imagesogemad';
				$id = ucwords($matricule);
				$page_data['image_link'] = $this->admin_model->get_image_url($type, $id);
				
			}	
				
				$page_data['sms'] = $sms ;
				$page_data['tel'] = $tel ;
				
				
		break;
				
		case 'SD':

				$resultat = $this->all_model->get_fullrow('dentaire','numdemandedent',$numpch);
			if($resultat)
			{
				$tel = $resultat['contactpatient'];
				
				if($resultat['etatdemande']=='traite'){
					
					$codeval = $this->all_model->get_field_by_id('codevaldemande','codevalidation','numdemande',$numpch);
					$etat = 'accordée';
					$fin_sms='Code de validation:'.$codeval;
					$sms='Prise en charge ('.$numpch.') '.$etat.'. '.$fin_sms;
					
				}else 
				if($resultat['etatdemande']=='refuse'){
					
					$motif = $this->all_model->get_field_by_id('dentaire','motifrefus','numdemandedent',$numpch);
					$etat = 'refusée';
					$fin_sms='Motif:'.$motif;
					$sms='Prise en charge ('.$numpch.') '.$etat.'. '.$fin_sms;
					
				}
				
				$matricule = $resultat['matricule'];
				$page_data['info_assure'] = $this->admin_model->get_infos_assure_habilite($matricule);
				$type = 'imagesogemad';
				$id = ucwords($matricule);
				$page_data['image_link'] = $this->admin_model->get_image_url($type, $id);
				
			}	
				
				$page_data['sms'] = $sms ;
				$page_data['tel'] = $tel ;
				
			
					
				
		break;
		
		default :
				
				$resultat = $this->all_model->get_fullrow('demandeprischar','numdemande',$numpch);
			if($resultat)
			{
				
				$tel = $resultat['tel'];
				
				if($resultat['etatdemande']=='traite'){
					
					$codeval = $this->all_model->get_field_by_id('codevaldemande','codevalidation','numdemande',$numpch);
					$etat = 'accordée';
					$fin_sms='Code de validation:'.$codeval;
					$sms='Prise en charge ('.$numpch.') '.$etat.'. '.$fin_sms;
					
				}else 
				if($resultat['etatdemande']=='refuse'){
					
					$motif = $this->all_model->get_field_by_id('demandeprischar','motifrefus','numdemande',$numpch);
					$etat = 'refusée';
					$fin_sms='Motif:'.$motif;
					$sms='Prise en charge ('.$numpch.') '.$etat.'. '.$fin_sms;
					
				}
				
				$matricule = $resultat['matricule'];
				$page_data['info_assure'] = $this->admin_model->get_infos_assure_habilite($matricule);
				$type = 'imagesogemad';
				$id = ucwords($matricule);
				$page_data['image_link'] = $this->admin_model->get_image_url($type, $id);
				
			}	
				
				$page_data['sms'] = $sms ;
				$page_data['tel'] = $tel ;
				
				
		break;
				
		}
		
		
		
		
	
		// affichage de la vue
		$this->render_template('sms_internet/envoi_sms_prise_en_charge', $page_data);
		
	}
	/**/
	public function envoyer() 
	{
		
			
		$page_data['bandeau'] = 'Envoi de SMS';
		$page_data['title'] = 'Envoi de code de validation';

		$page_data['profil'] = $this->all_model->get_fullrow('profile', 'idprofile', $this->session->userdata('user_profil'));
		$page_data['prestataire'] = $this->all_model->get_fullrow('presta', 'codpresta', $this->session->userdata('user_codpresta'));
		
		

		$this->load->library('form_validation');
		
		
		
		$tel = $this->input->post('tel');	
		$sms = $this->input->post('sms');	
		
		
		$nbre = strlen($tel);
		if(($nbre>=11)&&($nbre<=12)){
			
			
			$this->sms_internet->envoyer_sms($tel,$sms,'2'); 
			$output = array('error' => false);
			$output['message'] = "Le message a été envoyé avec succès";
		}else{
			
			$output['error'] = true;
			$output['message'] = "Le numéro de téléphone saisi est incorrect";
			
		}
		 echo json_encode($output);	
		
		// affichage de la vue
		//$this->render_template('visualisation_demandes/liste_demandes', $page_data);
		
	}
	
	
	public function modif_compte($id) 
	{
		
			
		$page_data['bandeau'] = 'Comptes SMS';
		$page_data['title'] = 'Paramètres des comptes SMS';

		$page_data['profil'] = $this->all_model->get_fullrow('profile', 'idprofile', $this->session->userdata('user_profil'));
		$page_data['prestataire'] = $this->all_model->get_fullrow('presta', 'codpresta', $this->session->userdata('user_codpresta'));
		$page_data['idmod'] = $id ;
		
		

		
		// affichage de la vue
		$this->render_template('sms_internet/parametrage_acces', $page_data);
		
	}
	
	public function ajoutcomptes() 
	{
		
			$output = array('error' => false);
		$page_data['bandeau'] = 'Comptes SMS';
		$page_data['title'] = 'Paramètres des comptes SMS';

		$page_data['profil'] = $this->all_model->get_fullrow('profile', 'idprofile', $this->session->userdata('user_profil'));
		$page_data['prestataire'] = $this->all_model->get_fullrow('presta', 'codpresta', $this->session->userdata('user_codpresta'));
		
		
		// initialisation du validateur du formulaire

	  $this->load->library('form_validation');

	// définition des règles de validation

	   $this->form_validation->set_rules('nomcompte', 'Nom du compte', 'trim|required');
	   $this->form_validation->set_rules('fournisseur', 'Nom du fournisseur', 'trim|required|alpha_dash');
	   $this->form_validation->set_rules('idsender', 'Nom d\'expéditeur', 'trim|required|min_length[4]|alpha_dash');
	   $this->form_validation->set_rules('numero', 'Destinataire par defaut', 'trim|required|alpha_dash');
	   $this->form_validation->set_rules('login', 'le login', 'trim|required|min_length[4]');
	   $this->form_validation->set_rules('password', 'le password', 'trim|required|min_length[4]');
	   $this->form_validation->set_rules('url', 'L\' Url de l\'API', 'trim|required|min_length[4]');

	   if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
	   { 
		   // erreur : retour au formulaire

			$flash_feedback = validation_errors();
		   	$output['error'] = true;
			$output['message'] = $flash_feedback;
			// redirection

		}
		else
		{
			$output['error'] = false;	
			$output['message'] = 'Opération effectuée avec succès';
			$var='';
			$choix = $this->input->post('choix');
			$nomcompte = $this->input->post('nomcompte');
			$idsender = $this->input->post('idsender');
			$numero = $this->input->post('numero');
			$login = $this->input->post('login');
			$password = $this->input->post('password');
			$url = $this->input->post('url');
			$methode = $this->input->post('fournisseur');
			
			switch($choix){
				case 'INSERTION':
					
					$this->all_model->save_compte_sms($nomcompte,$idsender,$numero,$login,$password,$url,$methode,'I');
					
					break;
				
				case 'MODIFICATION':
					
					$idmod = $this->input->post('idmod');
					
					$this->all_model->save_compte_sms($nomcompte,$idsender,$numero,$login,$password,$url,$methode,$idmod);
					
					break;
					
					
			}
			
			//$output['message'] = $var;
				
		}
		
		 echo json_encode($output);	
		// affichage de la vue
		//$this->render_template('sms_internet/liste_comptes', $page_data);
		
	}
	
	
	public function changecomptes() 
	{
		
		$output = array('error' => false);
		$page_data['bandeau'] = 'Comptes SMS';
		$page_data['title'] = 'Paramètres des comptes SMS';

		$page_data['profil'] = $this->all_model->get_fullrow('profile', 'idprofile', $this->session->userdata('user_profil'));
		$page_data['prestataire'] = $this->all_model->get_fullrow('presta', 'codpresta', $this->session->userdata('user_codpresta'));
		
		
		// initialisation du validateur du formulaire

	  $this->load->library('form_validation');

	// définition des règles de validation

	    $compte = $this->input->post('compte');
		$part = explode('|',$compte);
	   
		$service = $part[0];
		$choix = $part[1];
		
		$data_verif = array(
			'idcompte' => $choix
		);
		
		$this->all_model->update_ligne('service_envoi_sms',$data_verif , 'id' , $service);
		
		$output['message'] =' Compte SMS changer avec succès ';
		 echo json_encode($output);	
		// affichage de la vue
		//$this->render_template('sms_internet/liste_comptes', $page_data);
		
	}
	
	
	public function listecomptes() 
	{
		
			
		$page_data['bandeau'] = 'Comptes SMS';
		$page_data['title'] = 'Paramètres des comptes SMS';

		$page_data['profil'] = $this->all_model->get_fullrow('profile', 'idprofile', $this->session->userdata('user_profil'));
		$page_data['prestataire'] = $this->all_model->get_fullrow('presta', 'codpresta', $this->session->userdata('user_codpresta'));
		
		
		
		// affichage de la vue
		$this->render_template('sms_internet/liste_comptes', $page_data);
		
	}
	
	
	public function para_acces() 
	{
		
			
		$page_data['bandeau'] = 'Comptes SMS';
		$page_data['title'] = 'Paramètres des comptes SMS';

		$page_data['profil'] = $this->all_model->get_fullrow('profile', 'idprofile', $this->session->userdata('user_profil'));
		$page_data['prestataire'] = $this->all_model->get_fullrow('presta', 'codpresta', $this->session->userdata('user_codpresta'));
		
		

		
		// affichage de la vue
		$this->render_template('sms_internet/parametrage_acces', $page_data);
		
	}
	
	
	public function para_surveillance() 
	{
		
			
		$page_data['bandeau'] = 'Parametres SMS de surveillance';
		$page_data['title'] = 'Parametres de surveillance';

		$page_data['profil'] = $this->all_model->get_fullrow('profile', 'idprofile', $this->session->userdata('user_profil'));
		$page_data['prestataire'] = $this->all_model->get_fullrow('presta', 'codpresta', $this->session->userdata('user_codpresta'));
		
		
		
		$page_data['surveillant'] = $this->all_model->liste_surveillants_reseau();
		
		$result = $this->all_model->get_table('para_sms');
		$page_data['result']=$result[0];
		
		// affichage de la vue
		$this->render_template('sms_internet/parametrage_surveillance', $page_data);
		
	}
	
	
	public function listeferies() 
	{
		
			
		$page_data['bandeau'] = 'Comptes SMS';
		$page_data['title'] = 'Paramètres des comptes SMS';

		$page_data['profil'] = $this->all_model->get_fullrow('profile', 'idprofile', $this->session->userdata('user_profil'));
		$page_data['prestataire'] = $this->all_model->get_fullrow('presta', 'codpresta', $this->session->userdata('user_codpresta'));
		
		
		
		// affichage de la vue
		$this->render_template('sms_internet/liste_ferie', $page_data);
		
	}
	
	public function ajoutferies() 
	{
		
		$output = array('error' => false);
		$page_data['bandeau'] = 'Comptes SMS';
		$page_data['title'] = 'Paramètres des comptes SMS';

		$page_data['profil'] = $this->all_model->get_fullrow('profile', 'idprofile', $this->session->userdata('user_profil'));
		$page_data['prestataire'] = $this->all_model->get_fullrow('presta', 'codpresta', $this->session->userdata('user_codpresta'));
		
		
		// initialisation du validateur du formulaire

	  $this->load->library('form_validation');

	// définition des règles de validation

	   $this->form_validation->set_rules('jferie', 'Jour férié', 'trim|required|alpha_dash');
	   if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
	   { 
		   // erreur : retour au formulaire

			$flash_feedback = validation_errors();
		   	$output['error'] = true;
			$output['message'] = $flash_feedback;
			// redirection

		}
		else
		{
			
			
			$jferie = $this->input->post('jferie');
			
			@$verif = $this->all_model->get_field_by_id('ferie','jferie','jferie', $jferie);
			if(!@$verif){
				
				$data = array(
					'jferie' => $jferie
				); 
			
				$this->all_model->add_ligne('ferie', $data);
				
				$output['error'] = false;	
				$output['message'] = 'Opération effectuée avec succès';	
			}
			
			
				
			
			
			//$output['message'] = $var;
				
		}
		
		 echo json_encode($output);	
		// affichage de la vue
		//$this->render_template('sms_internet/liste_comptes', $page_data);
		
	}
	
	public function ajouter_cible() 
	{
		
		$output = array('error' => false);
		$page_data['bandeau'] = 'Comptes SMS';
		$page_data['title'] = 'Paramètres des comptes SMS';

		$page_data['profil'] = $this->all_model->get_fullrow('profile', 'idprofile', $this->session->userdata('user_profil'));
		$page_data['prestataire'] = $this->all_model->get_fullrow('presta', 'codpresta', $this->session->userdata('user_codpresta'));
		
		
		// initialisation du validateur du formulaire

	  $this->load->library('form_validation');

	// définition des règles de validation

	   $this->form_validation->set_rules('cible', 'Cible', 'trim|required|alpha_dash');
	   $this->form_validation->set_rules('debut', 'Heure début', 'trim|required');
	   $this->form_validation->set_rules('fin', 'Heure fin', 'trim|required');
	   if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
	   { 
		   // erreur : retour au formulaire

			$flash_feedback = validation_errors();
		   	$output['error'] = true;
			$output['message'] = $flash_feedback;
			// redirection

		}
		else
		{
			
			
			$cible = $this->input->post('cible');
			$debut = $this->input->post('debut');
			$fin = $this->input->post('fin');
			
		
				
				$data = array(
					'cible' => $cible,
					'heured' => $debut,
					'heuref' => $fin
				); 
			
				$this->all_model->update_all_ligne('para_sms', $data);
				
				$output['error'] = false;	
				$output['message'] = 'Opération effectuée avec succès';	
			
				
			
			
		}
		
		 echo json_encode($output);	
		// affichage de la vue
		//$this->render_template('sms_internet/liste_comptes', $page_data);
		
	}
	
	public function changer_surveillant() 
	{
		
		$output = array('error' => false);
		$page_data['bandeau'] = 'Comptes SMS';
		$page_data['title'] = 'Paramètres des comptes SMS';

		$page_data['profil'] = $this->all_model->get_fullrow('profile', 'idprofile', $this->session->userdata('user_profil'));
		$page_data['prestataire'] = $this->all_model->get_fullrow('presta', 'codpresta', $this->session->userdata('user_codpresta'));
		
		
		// initialisation du validateur du formulaire

	  $this->load->library('form_validation');

	// définition des règles de validation

	   $this->form_validation->set_rules('num1', 'Numéro agent1', 'trim|required|alpha_dash');
	   $this->form_validation->set_rules('num2', 'Numéro agent2', 'trim|required|alpha_dash');
	   if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
	   { 
		   // erreur : retour au formulaire

			$flash_feedback = validation_errors();
		   	$output['error'] = true;
			$output['message'] = $flash_feedback;
			// redirection

		}
		else
		{
			
			
			$num1 = $this->input->post('num1');
			$num2 = $this->input->post('num2');
			
		
				
				$data = array(
					'num1' => $num1,
					'num2' => $num2
				); 
			
				$this->all_model->update_all_ligne('para_sms', $data);
				
				$output['error'] = false;	
				$output['message'] = 'Opération effectuée avec succès';	
			
				
			
			
		}
		
		 echo json_encode($output);	
		// affichage de la vue
		//$this->render_template('sms_internet/liste_comptes', $page_data);
		
	}
	
	
	
	
	
	
	/* ##################################################################
	----------				PAGE :: ./home/login						  ----------
	################################################################## */
	
	
	
	
}









/* End of file facturation_mensuelle.php */
    /* Location: ./application/controllers/envoi_sms.php */
	
