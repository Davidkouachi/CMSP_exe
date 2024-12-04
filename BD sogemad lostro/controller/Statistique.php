<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Statistique extends Admin_Controller {
    
    
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

			if ($curr_uri_string != 'statistique') 
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

    public function criteres()
    {
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

		$page_data['results'] = $this->all_model->getOrderData();

		$page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_name'] = 'StatistiqueView';
		$page_data['page_active'] = 'StatistiquePage';
		$page_data['page_profil'] =  $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Visualisation des statistiques';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire de visualisation des statistiques graphiques' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
        
		// affichage de la vue

            $this->render_template('kameleon/statistiques', $page_data);
    }   

		
    public function Stat()
    {
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

		$page_data['results'] = $this->all_model->getOrderData();

		$page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_name'] = 'StatistiqueView';
		$page_data['page_active'] = 'StatistiquePage';
		$page_data['page_profil'] =  $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Visualisation des statistiques';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire de visualisation des statistiques' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
        
		// affichage de la vue

            $this->render_template('kameleon/StatistiqueView', $page_data);
    }

    public function Bon_non_encaisse()
    {
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

		$page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_active'] = 'StatistiqueListingPage';
		$page_data['page_profil'] =  $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Visualisation des statistiques en listing';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire de visualisation de l\'état des factures non encaissées' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
        
		// affichage de la vue

        $this->render_template('statistiques/bon_non_encaisse', $page_data);
    }

     public function Journal_comptant()
    {
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

		$page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_active'] = 'StatistiqueListingPage';
		$page_data['page_profil'] =  $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Visualisation des statistiques en listing';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire de visualisation du journal des actes externes au comptant' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
        
		// affichage de la vue

        $this->render_template('statistiques/journal_comptant', $page_data);
    }

    public function Liste_patient_hospit($datedebut,$datefin)
    {
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

		$page_data['patients_hospit'] = $this->all_model->get_patient_hospit($datedebut,$datefin);
		
		$page_data['datedebut'] = $datedebut;

		$page_data['datefin'] = $datefin;
		
		$page_data['infos_admission'] = $this->all_model->get_admission($datedebut,$datefin);

		$page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_active'] = 'StatistiqueListingPage';
		$page_data['page_profil'] =  $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Liste mensuelle des patients (hospitalisés / mis en observation)';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Liste mensuelle des patients (hospitalisés / mis en observation)' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
        
		// affichage de la vue

        $this->render_template('statistiques/liste_patient_hospit', $page_data);
        
    }
    
    public function Liste_patient_biologie($datedebut,$datefin)
    {
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

		$page_data['patients_biologie'] = $this->all_model->get_patient_biologie($datedebut,$datefin);
	
		$page_data['datedebut'] = $datedebut;

		$page_data['datefin'] = $datefin;
		

		$page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_active'] = 'StatistiqueListingPage';
		$page_data['page_profil'] =  $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Liste des patients (biologie)';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Liste des patients (biologie)' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
        
		// affichage de la vue

        $this->render_template('statistiques/liste_patient_biologie', $page_data);
        
    }

    public function Liste_patient_imagerie($datedebut,$datefin)
    {
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

		$page_data['patients_imagerie'] = $this->all_model->get_patient_imagerie($datedebut,$datefin);
		
		$page_data['datedebut'] = $datedebut;

		$page_data['datefin'] = $datefin;

		$page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_active'] = 'StatistiqueListingPage';
		$page_data['page_profil'] =  $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Liste des patients (imagerie)';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Liste des patients (imagerie)' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
        
		// affichage de la vue

        $this->render_template('statistiques/liste_patient_imagerie', $page_data);
        
    }

    public function Liste_patient_consultation($datedebut,$datefin)
    {
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

		$page_data['patients_consultation'] = $this->all_model->get_patient_consultation();

		$page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_active'] = 'StatistiqueListingPage';
		$page_data['page_profil'] =  $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Liste mensuelle des patients ayant fait une consultation clinique';
		
		$page_data['repartition_cons'] =  $this->all_model->get_repartition_consultation($datedebut,$datefin);
		
		$page_data['date_debut'] = $datedebut;

		$page_data['date_fin'] = $datefin;

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Liste mensuelle des patients ayant fait une consultation clinique' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
        
		// affichage de la vue

        $this->render_template('statistiques/liste_patient_consultation', $page_data);
    }
    
    
    public function Liste_factures_non_encaisses($datedebut,$datefin)
    {
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

		$page_data['factures'] = $this->all_model->get_factures_non_encaisse($datedebut,$datefin);
		
		$page_data['datedebut'] = $datedebut;

		$page_data['datefin'] = $datefin;

		$page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_active'] = 'StatistiqueListingPage';
		$page_data['page_profil'] =  $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Liste des factures non encaissées';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Liste des factures non encaissées' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
        
		// affichage de la vue

        $this->render_template('statistiques/liste_factures_non_encaisse', $page_data);
        
    }
    
    public function Liste_patient_soins($datedebut,$datefin)
    {
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

		$page_data['patients_soins'] = $this->all_model->get_patient_soins($datedebut,$datefin);
		
		$page_data['datedebut'] = $datedebut;

		$page_data['datefin'] = $datefin;

		$page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_active'] = 'StatistiqueListingPage';
		$page_data['page_profil'] =  $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Liste des patients en soins infirmier';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Liste des patients en soins infirmier' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
        
		// affichage de la vue

        $this->render_template('statistiques/liste_patient_soins', $page_data);
        
    }
    
    public function Liste_patient_pharmacie($datedebut,$datefin)
    {
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

		$page_data['patients_pharmacie'] = $this->all_model->get_patient_pharmacie($datedebut,$datefin);
		
		$page_data['datedebut'] = $datedebut;

		$page_data['datefin'] = $datefin;

		$page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_active'] = 'StatistiqueListingPage';
		$page_data['page_profil'] =  $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Liste des patients pharmacie';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Liste des patients en pharmacie' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
        
		// affichage de la vue

        $this->render_template('statistiques/liste_patient_pharmacie', $page_data);
        
    }
    
    public function Historique_patient_form()
    {
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

		$page_data['prestations'] = $this->all_model->get_table('prestation_honoraire');

		$page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_active'] = 'StatistiqueListingPage';
		$page_data['page_profil'] =  $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Historique des patients';


		$page_data['type_critere'] = '';

		$page_data['namePg'] = 'fetchHistoriquePatientData';

		$page_data['date_debut'] = '' ;

        $page_data['date_fin'] = '' ;

		$page_data['option_1'] = '' ;

		$page_data['option_2'] = '';

		$page_data['patient'] = '';

		$page_data['acte'] = '' ;

		$page_data['idenregistremetpatient'] = '';

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire historique patient' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
        
		// affichage de la vue

        $this->render_template('statistiques/historique_patient', $page_data);
        
    }


    public function Historique_patient()
    {
        // initialisation du validateur du formulaire

          $this->load->library('form_validation');

        // définition des règles de validation
            
           $this->form_validation->set_rules('date_debut', 'Date de debut', 'trim|required|xss_clean');

           $this->form_validation->set_rules('date_fin', 'Date de fin', 'trim|required|xss_clean');

           $this->form_validation->set_rules('prestation', 'Prestation effectuée', 'trim|required|xss_clean');

           $this->form_validation->set_rules('idenregistremetpatient', 'Nom & prenom(s) du patient', 'trim|required|xss_clean');

           if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
           {
           		
               // erreur : retour au formulaire

                $flash_feedback = validation_errors();

                $this->session->set_flashdata('error', $flash_feedback);

                redirect('Statistique/Historique_patient_form');

            }
            else
            {
				 $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

				 $page_data['prestations'] = $this->all_model->get_table('prestation_honoraire');

		         $page_data['page_libprofil'] = $UniqueProfil;
				 $page_data['page_active'] = 'StatistiqueListingPage';
				 $page_data['page_profil'] = $this->session->userdata('user_profil');
				 $page_data['page_title'] = 'Lostro Admin';
				 $page_data['page_s_title'] = 'Historique des patients';

				 $page_data['type_critere'] = 'periode';

				 $page_data['namePg'] = 'fetchHistoriquePatientData';

				 $page_data['date_debut'] = $date_debut = $this->input->post('date_debut');

                 $page_data['date_fin'] = $date_fin = $this->input->post('date_fin');

                 $page_data['idenregistremetpatient'] = $idenregistremetpatient = $this->input->post('idenregistremetpatient');

                 $page_data['acte'] = $prestation = $this->input->post('prestation');

				 $page_data['option_1'] = $date_debut ;

				 $page_data['option_2'] = $date_fin;

				 $infos_patient = $this->all_model->get_fullrow('patient','idenregistremetpatient',$idenregistremetpatient);

				 if(!empty($infos_patient))
				 {
				 	$page_data['patient'] = $infos_patient['nomprenomspatient'];
				 }else{

				 	$page_data['patient'] = '';
				 }

				 switch ($prestation) {
				 	case 1:
				 		    
				 		break;
				 	
				 	default:
				 		// code...
				 		break;
				 }

				 // Effectuer la journalisation
					$type_action = 'Consultation' ;

					$action_effectuee = 'Historique patient' ;

					$this->control->journalisation($type_action,$action_effectuee) ;
					
					// affichage de la vue

				        //var_dump($page_data) ;
				        //exit();

				$this->render_template('statistiques/historique_patient', $page_data);
			}
        
    }

    public function Historique_examen_form()
    {
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

		$page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_active'] = 'StatistiqueListingPage';
		$page_data['page_profil'] =  $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Visualisation des statistiques en listing';

		$page_data['examens'] = $this->all_model->get_table('examen') ;

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire de visualisation de l\'historique des examens biologiques.' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
        
		// affichage de la vue

        $this->render_template('statistiques/historique_examen_form', $page_data);
    }

	public function liste_actes_honoraire()
	{

	   $date_debut = $this->input->post('date_debut');

       $date_fin = $this->input->post('date_fin');

       $medecin = $this->input->post('medecin');

       $prestation = $this->input->post('prestation');

	   if(empty($date_debut)){
		$message = "Veuillez renseigner la date de debut de la période pour laquelle vous souhaitez calculer les honoraires.";
	   ?>
	    <table cellspacing="1" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
        <tr>
            <td style="color: #fff;color:red; font-weight:bold; font-size:18px"><strong> <?php echo $message;  ?> </strong></td>
        </tr>
    	</table>
		<?php
		exit();
	   }

	   if($date_debut > $date_fin){
		$message = "La date de debut ne doit pas être supérieure à la date de fin.";
	   
		?>
	    <table cellspacing="1" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
        <tr>
            <td style="color: #fff;color:red; font-weight:bold; font-size:18px"><strong> <?php echo $message;  ?> </strong></td>
        </tr>
    	</table>
		<?php
		exit();
	   }

	   if(empty($date_fin)){
		$message = "Veuillez renseigner la date de fin de la période pour laquelle vous voulez calculer les honoraires.";
		?>
	    <table cellspacing="1" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
        <tr>
            <td style="color: #fff;color:red; font-weight:bold; font-size:18px"><strong> <?php echo $message;  ?> </strong></td>
        </tr>
    	</table>
		<?php
		exit();
	   }

	  if($date_debut > date('Y-m-d')){
		$message = "La date de debut ne doit pas être supérieure à la date du jour.";
	   
		?>
	    <table cellspacing="1" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
        <tr>
            <td style="color: #fff;color:red; font-weight:bold; font-size:18px"><strong> <?php echo $message;  ?> </strong></td>
        </tr>
    	</table>
		<?php
		exit();
	   }

	   if($date_fin > date('Y-m-d')){
		$message = "La date de fin ne doit pas être supérieure à la date du jour.";
	   
		?>
	    <table cellspacing="1" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
        <tr>
            <td style="color: #fff;color:red; font-weight:bold; font-size:18px"><strong> <?php echo $message;  ?> </strong></td>
        </tr>
    	</table>
		<?php
		exit();
	   }

	   if(empty($medecin)){
		$message = "Veuillez choisir le medecin pour lequel vous voulez calculer les honoraires.";

		?>
	    <table cellspacing="1" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
        <tr>
            <td style="color: #fff;color:red; font-weight:bold; font-size:18px"><strong> <?php echo $message;  ?> </strong></td>
        </tr>
    	</table>
		<?php
		exit();
	   }

	   if(empty($prestation)){
		$message = "Veuillez choisir la prestation pour laquelle vous voulez calculer les honoraires.";
		?>
	    <table cellspacing="1" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
        <tr>
            <td style="color: #fff;color:red; font-weight:bold; font-size:18px"><strong> <?php echo $message;  ?> </strong></td>
        </tr>
    	</table>
		<?php
		exit();
	   }

	   $mois_debut = substr($date_debut, 5,2);

	   $annee_debut = substr($date_debut, 0,4);

	   $jour_debut = substr($date_debut, 8,2);

	   $mois_fin = substr($date_fin, 5,2);

	   $annee_fin = substr($date_fin, 0,4);

	   $jour_fin = substr($date_fin, 8,2);
	   
	   
	   if($mois_debut <> $mois_fin){
		$message = "La date de debut et celle de fin doivent être du même mois. A defaut, vous pouvez visualiser l'état.";

		?>
	    <table cellspacing="1" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
        <tr>
            <td style="color: #fff;color:red; font-weight:bold; font-size:18px"><strong> <?php echo $message;  ?> </strong></td>
        </tr>
    	</table>
		<?php
		exit();
	   }

	   if($annee_debut <> $annee_fin){
		$message = "L'année de la date de debut et celle de la date de fin doivent être identiques. A defaut, vous pouvez visualiser l'état.";

		?>
	    <table cellspacing="1" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
        <tr>
            <td style="color: #fff;color:red; font-weight:bold; font-size:18px"><strong> <?php echo $message;  ?> </strong></td>
        </tr>
    	</table>
		<?php
		exit();
	   }

	   // Date de debut du mois
	$debut_du_mois = mktime(0, 0, 0, $mois_debut, 1, $annee_debut);
	$date_debut_obligatoire = date("Y-m-d", $debut_du_mois);

	if($date_debut_obligatoire <> $date_debut){
	  $message = "La date de debut choisie n'est pas la date de debut du mois. Veuillez choisir la bonne date de debut. A defaut, vous pouvez visualiser l'état.";

	  ?>
	  <table cellspacing="1" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
	  <tr>
		  <td style="color: #fff;color:red; font-weight:bold; font-size:18px"><strong> <?php echo $message;  ?> </strong></td>
	  </tr>
	  </table>
	  <?php
	  exit();
	 }

	   // Date de fin du mois
	$fin_du_mois = mktime(23, 59, 59, $mois_fin + 1, 0, $annee_fin);
	$date_fin_obligatoire = date("Y-m-d", $fin_du_mois);

	if($date_fin_obligatoire <> $date_fin){
		$message = "La date de fin choisie n'est pas la date de fin du mois. Veuillez choisir la bonne date de fin. A defaut, vous pouvez visualiser l'état.";

		?>
	    <table cellspacing="1" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
        <tr>
            <td style="color: #fff;color:red; font-weight:bold; font-size:18px"><strong> <?php echo $message;  ?> </strong></td>
        </tr>
    	</table>
		<?php
		exit();
	}

		switch ($prestation) {
	        case 1 :
	        		$liste_actes = $this->all_model->get_liste_consultations($date_debut,$date_fin,$medecin);

	        		$libelle_1 = 'LISTE DES CONSULTATIONS EFFECTUEES DU ';

	        		$libelle_2 = 'MONTANT TOTAL DES CONSULTATIONS EFFECTUEES DU ';

	        		$libelle_3 = 'Consultation(s)';
	        		break;

	        case 2 :
	        		$liste_actes = $this->all_model->get_liste_imagerie($date_debut,$date_fin,$medecin);

	        		$libelle_1 = 'LISTE DES IMAGERIES PRESCRITES DU ';

	        		$libelle_2 = 'MONTANT TOTAL DES IMAGERIES PRESCRITES DU ';

	        		$libelle_3 = 'Imagerie(s)';
	        		break;

	        case 3 :
	        		$liste_actes = $this->all_model->get_liste_viste($date_debut,$date_fin,$medecin);

	        		$libelle_1 = 'LISTE DES VISITES EFFECTUEES DU ';

	        		$libelle_2 = 'MONTANT TOTAL DES VISITES EFFECTUEES DU ';

	        		$libelle_3 = 'Visite(s)';
	        		break;
	        		
	        default:
	        	// code...
	        break;
	    }
		
		if(!empty($liste_actes))
		{
		    $page_data['liste_actes'] = $liste_actes ;
		    
		    $page_data['print_name'] = 'i_Actes_honoraires';
    		$page_data['date_debut'] = $date_debut;
    		$page_data['date_fin'] = $date_fin;
    		$page_data['prestation'] = $prestation;
    		$page_data['medecin'] = $medecin;
		}
		else
		{
			$message = "Aucune donnée disponible pour les critères que vous avez renseignés.";

			?>
			<table cellspacing="1" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
			<tr>
				<td style="color: #fff;color:red; font-weight:bold; font-size:18px"><strong> <?php echo $message;  ?> </strong></td>
			</tr>
			</table>
			<?php
			exit();
	   }

	
		?>
		<div class="affichage">
			<?php if (!empty($liste_actes)) { ?>
			  <div class="box-content">
			  <hr>
		        <form id="" method="post" action="<?php echo base_url() ?>PrintC/liste_actes" target='_blank' class="form-group">
		            
		            <input class="form-control" name="date_debut" type="hidden" value="<?php echo $date_debut  ?>" />
		            
		            <input class="form-control" name="date_fin" type="hidden" value="<?php echo $date_fin  ?>" />
		            <input class="form-control" name="medecin" type="hidden" value="<?php echo $medecin  ?>" />
					<input class="form-control" name="prestation" type="hidden" value="<?php echo $prestation  ?>" />
		        	<button type="submit" id="" class="btn btn-warning"><i class="fa fa-print"></i> Imprimer l'état</button>
		        </form>
			<hr>
			<center><div style="width: 100%; padding-top: 0px; padding-bottom: 1px; border: 1px solid #005CFF; text-align: center;border-radius: 10px;"><strong> <h4><?php echo $libelle_1?> <?php echo $this->fdateheuregmt->date_fr($date_debut) ?> AU <?php echo $this->fdateheuregmt->date_fr($date_fin)  ?> </h4> </strong></div>
			</center>
			<br/>
			    <table id="example1" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
			    <thead style="background-color:#07AEBC;">
			    <tr>
			        <th><center>Date</center></th>
			    	<th><center>Nom Patient</center></th>
			        <th><center>Facture</center></th>
			        <th><center>Montant</center></th>
			    </tr>
			    </thead>
			    <tbody>
                <?php
                
                $cpt_facture = 0 ;
    
                        $cpt_nonfacture = 0 ;
    
                        $cpt = 0 ;
    
                        $montant_total = 0 ;
    
                        foreach($liste_actes as $row)
                        {
                            $cpt ++ ;
    
                            switch ($prestation) {
                                case 1 :
                                        $montant_total = $montant_total + $row['montant'] ;
    
                                        if($row['montant'] > 0)
                                        {
                                            $cpt_facture++ ;
    
                                        }else{
    
                                            $cpt_nonfacture++ ;
                                        }
    
                                        $montant_ligne = $row['montant'] ;
    
    
                                        $date = $this->fdateheuregmt->date_fr($row['date']) ;
    
                                        $numero_facture = $row['numfac'] ;
    
                                        $idenregistremetpatient = $row['idenregistremetpatient'] ;
    
                                    break;
    
                                case 2 :
    
                                        $idenregistremetpatient = $row['idenregistremetpatient'] ;
    
                                        $infos_factures = $this->all_model->get_fullrow('factures','numfac',$row['numfacbul']);
    
                                        if(!empty($infos_factures))
                                        {
                                            $montant_total = $montant_total + $infos_factures['montanttotal'] ;
    
                                            $montant_ligne = $infos_factures['montanttotal'] ;
    
                                            $numero_facture = $infos_factures['numfac'] ;
    
                                        }else{
                                            $montant_total = $montant_total + 0 ;
    
                                            $montant_ligne = 0 ;
    
                                            $numero_facture = '' ;
                                        }
    
                                        
                                        if($montant_ligne > 0)
                                        {
                                            $cpt_facture++ ;
    
                                        }else{
    
                                            $cpt_nonfacture++ ;
                                        }
    
                                        $date = $this->fdateheuregmt->date_fr($row['date']) ;
    
                                    break;
    
                                case 3 :
    
                                        $montant_ligne = $row['montant_visite'] ;
                                        
                                        $montant_total = $montant_total + $row['montant_visite'] ;
    
                                        if($row['montant_visite'] > 0)
                                        {
                                            $cpt_facture++ ;
                                        }else{
                                            $cpt_nonfacture++ ;
                                        }
    
                                        $date = $this->fdateheuregmt->date_fr($row['date_viste']) ;
    
                                        $numero_hospit = $row['numhospit'] ;
    
                                        $infos_admission = $this->all_model->get_fullrow('admission','numhospit',$numero_hospit);
    
                                        if(!empty($infos_admission))
                                        {
                                            $idenregistremetpatient = $infos_admission['idenregistremetpatient'] ;
    
                                            $numero_facture = $infos_admission['numfachospit'] ;
                                        }else{
    
                                            $idenregistremetpatient = '' ;
    
                                            $numero_facture = '' ;
                                        }
    
                                    break;
                                
                                default:
                                    // code...
                                    break;      
                            }
    
                            $infos_patient = $this->all_model->get_fullrow('patient','idenregistremetpatient',$idenregistremetpatient);
    
                            if(!empty($infos_patient))
                            {
                                $nom_assure = $infos_patient['nomprenomspatient'] ;
                            }else{
                                $nom_assure = '' ;
                            }
    
            ?>
					<tr>
						<td><center><?= $date ?></center></td>
						<td><?= $nom_assure ?></td>
						<td><?= $numero_facture ?></td>
						<td><?= number_format($montant_ligne, 0, '', ' ') ?></td>
					</tr>
                    <?php
                    
                }

                $infos_medecins = $this->all_model->get_fullrow('medecin','codemedecin',$medecin);

                switch ($prestation) {
                    case 1 :
                            $montant_bnc = ($montant_total/2)*0.075 ;

                            $montant_honoraire = ($montant_total/2)-$montant_bnc ;

                        break;

                    case 2 :

                            $montant_bnc = ($montant_total*0.6)*0.075 ;

                            $montant_honoraire = ($montant_total*0.6)-$montant_bnc ;
                            
                        break;

                    case 3 :

                            $montant_bnc = $montant_total*0.075 ;

                            $montant_honoraire = $montant_total-$montant_bnc ;
                            
                        break;
                    
                    default:
                        // code...
                        break;
                }

                
            ?>
            </tbody>
			    </table>
                <br>
    <table cellspacing="1" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
        <tr>
            <td colspan='4'><?php echo $infos_medecins['nomprenomsmed']  ?></td>
        </tr>
        <tr>
                    <td colspan='3'><?php echo $libelle_2.$this->fdateheuregmt->date_fr($date_debut) ?> AU <?php echo $this->fdateheuregmt->date_fr($date_fin)  ?> </td>
                    <td style="color: #fff;color:red; font-weight:bold; font-size:18px"><?= number_format($montant_total, 0, '', ' ') ?></td>
                </tr>
                <tr>
                    <td>Montant BNC </td>
                    <td style="color: #fff;color:red; font-weight:bold; font-size:18px"><?= number_format($montant_bnc, 0, '', ' ') ?></td>
                    <td >Honoraire (Montant à payer) </td>
                    <td style="color: #fff;color:red; font-weight:bold; font-size:18px"><?= number_format($montant_honoraire, 0, '', ' ') ?></td>
                </tr>

       
    </table>

    <br>
    <table cellspacing="1" class="table-striped table-bordered bootstrap-datatable datatable responsive">
        <tr>
            <td><strong>* Total consultation </strong></td>
            <td> <?php echo $cpt.' '.$libelle_3 ?></td> 
        </tr>
        <tr>
            <td><strong>* Total consultations facturées </strong></td>
            <td> <?php echo $cpt_facture.' '.$libelle_3 ?></td> 
        </tr>
        <tr style="width: 100%;">
            <td><strong>* Total consultations non facturées </strong></td>
            <td> <?php echo $cpt_nonfacture.' '.$libelle_3 ?></td>   
        </tr>
    </table>
	<br>
	<hr>
	<form id="form_valider_honoraire" method="post" action="<?php echo base_url() ?>Statistique/valider_honoraire" class="form-group">     
		<input class="form-control" name="date_debut" type="hidden" value="<?php echo $date_debut  ?>" />
		<input class="form-control" name="date_fin" type="hidden" value="<?php echo $date_fin  ?>" />
		<input class="form-control" name="medecin" type="hidden" value="<?php echo $medecin  ?>" />
		<input class="form-control" name="prestation" type="hidden" value="<?php echo $prestation  ?>" />
		<input class="form-control" name="montant_bnc" type="hidden" value="<?php echo $montant_bnc  ?>" />
		<input class="form-control" name="montant_honoraire" type="hidden" value="<?php echo $montant_honoraire  ?>" />
		<center><button type="submit"  class="btn btn-info"><i class="fa fa-thumbs-up"></i> Valider pour paiement</button></center>
	</form>
	<hr>



</div>
        <?php }else{ ?> 

	<table cellspacing="1" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
        <tr>
            <td style="color: #fff;color:red; font-weight:bold; font-size:18px"><strong> <?php echo $message;  ?> </strong></td>
        </tr>
    </table>
			<?php } ?>
		    </div>
			<?php  
	}

	public function valider_honoraire()
    {
		if(!empty($_POST))
		{
			 // initialisation du validateur du formulaire
		    $this->load->library('form_validation');
		       // définition des règles de validation
		            
		    $this->form_validation->set_rules('date_debut', 'Date fin', 'trim|required');
		    $this->form_validation->set_rules('date_fin', 'Date debut', 'trim');
		    $this->form_validation->set_rules('medecin', 'Medecin', 'trim|required');
		    $this->form_validation->set_rules('prestation', 'Prestation', 'trim|required');
		    $this->form_validation->set_rules('montant_bnc', 'Montant BNC', 'trim|required');
		    $this->form_validation->set_rules('montant_honoraire', 'Montant Honoraire', 'trim|required');

		    if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
		    { 
		        // erreur : retour au formulaire

		        $flash_feedback = validation_errors();;

				$this->session->set_flashdata('error', $flash_feedback);

				redirect('Comptabilite/Honoraire_medecin_form/', 'refresh');
		    }
		    else
		    {
			    $table = 'honoraire';

			    $date_debut = $this->input->post('date_debut');
			    $date_fin = $this->input->post('date_fin');
			    $medecin = $this->input->post('medecin');
				$prestation = $this->input->post('prestation');
				$montant_bnc = $this->input->post('montant_bnc');
				$montant_honoraire = $this->input->post('montant_honoraire');

				$infos_honoraire = $this->all_model->verifier_honoraire($date_debut, $date_fin, $medecin, $prestation);


				if(empty($infos_honoraire))
			    {

					//SCRIPT DE GENERATION DU CODE DE L'ACTE ***
					do {
						$random_chars="";
						$characters = array(
							"A","B","C","D","E","F","G","H","J","K","L","M",
							"N","P","Q","R","S","T","U","V","W","X","Y","Z",
							"1","2","3","4","5","6","7","8","9");
						$keys = array();
						while(count($keys) < 4) {
							$x = mt_rand(0, count($characters)-1);
							if(!in_array($x, $keys)) 
							{
								$keys[] = $x;
							}
						}

						foreach($keys as $key){
							$random_chars .= $characters[$key];
						}

						$code_honoraire = 'H'.$random_chars;

						$nbr_res = $this->all_model->get_fullrow('honoraire','code_honoraire',$code_honoraire);

					} while ($nbr_res);
				///FIN DU SCRIPT/***
				
					$data = array('code_honoraire' => $code_honoraire,
									'type_honoraire' => $prestation,
									'date_execution' => date('Y-m-d'),
									'user_execution' => $this->session->userdata('user_name'),
									'date_debut' => $date_debut,
									'date_fin' => $date_fin,
									'codemedecin' => $medecin,
									'montant_honoraire' => $montant_honoraire,
									'montant_bnc' => $montant_bnc,
									'regle' => 0
									);

					// Effectuer la journalisation
					$type_action = 'Ajout' ;

					$action_effectuee = 'honoraire' ;

					$this->control->journalisation($type_action,$action_effectuee) ;

					$query = $this->all_model->add_ligne($table, $data);
				
					$flash_feedback = 'Honoraire enregistré avec succès et disponible pour le paiement.';

					$this->session->set_flashdata('success', $flash_feedback);

					redirect('Comptabilite/Honoraire_medecin_form/', 'refresh');
			    }
			    else
			    {
			    	$flash_feedback = 'Désolé cet enregistrement a déjà été fait.';

					$this->session->set_flashdata('error', $flash_feedback);

					redirect('Comptabilite/Honoraire_medecin_form/', 'refresh');
			    }
			}
		}
		else
		{	
			redirect('Comptabilite/Honoraire_medecin_form/', 'refresh');
		}
    }
	
	
}
	
