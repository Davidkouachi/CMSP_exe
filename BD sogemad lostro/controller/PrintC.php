<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PrintC extends Admin_Controller {
    
    
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

			if ($curr_uri_string != 'printc') 
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

	
	public function FactureConsPrint($numfac,$date=null)
	{
			if($date == null)
    		{
    			$date = date('Y-m-d');
    		}

			$infoConsultationPatient = $this->printmodel->get_info_cons_patient($numfac,$date);

			if(!empty($infoConsultationPatient))
			{
				$libassurance = $this->printmodel->get_patient_assurance($infoConsultationPatient['codeassurance']);

				$nommedecin = $this->printmodel->get_medecin($infoConsultationPatient['codemedecin']);
				
			
				$garantiecons = $this->printmodel->get_garantie($infoConsultationPatient['codeacte']);

				$page_data['print_name'] = 'i_M';
				$page_data['d'] = $infoConsultationPatient;
				$page_data['don'] = $libassurance;
				$page_data['Med'] = $nommedecin;
				$page_data['Acte'] = $garantiecons;

			
			}
			else
			{
				$page_data['d'] = array();
				$page_data['print_name'] = 'i_M';
			}

				// Effectuer la journalisation
			$type_action = 'Impression' ;

			$action_effectuee = 'Facture de consultation clinique' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$this->load->view('print/infoNet', $page_data);
	}
	
	public function PrintRecu()
	{
		$numfac = $this->input->post('numfac');

		$infos_facture = $this->all_model->get_fullrow('factures','numfac',$numfac) ;

		$debut = substr($numfac, 0,3);

		if($debut == 'FCB')
		{
				// Effectuer la journalisation
			$type_action = 'Impression' ;

			$action_effectuee = 'Reçu de biologie / imagerie médicale' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			redirect('Bulletin/RecuBulPrint/'.$numfac.'');
		}

		if($debut == 'FCE')
		{
				// Effectuer la journalisation
			$type_action = 'Impression' ;

			$action_effectuee = 'Reçu de consultation clinique' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			redirect('Consultation/PrintFac/'.$numfac.'');
		}

		if($debut == 'FCH')
		{
				// Effectuer la journalisation
			$type_action = 'Impression' ;

			$action_effectuee = 'Reçu de factures d\'admission' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			redirect('Hospitalisation/RecuHospitPrint/'.$numfac.'');
		}
		
		if($debut == 'FCS')
		{
				// Effectuer la journalisation
			$type_action = 'Impression' ;

			$action_effectuee = 'Reçu de factures de soins infirmiers' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			redirect('infirmerie/PrintFac/'.$numfac.'');
		}
	}

	public function FactureHospitPrint($numhospit,$code)
	{
			$page_data['print_name'] = 'i_FCH';

			$page_data['idfch'] = $numhospit;

			$page_data['code_print'] = $code;

				// Effectuer la journalisation
			$type_action = 'Impression' ;

			$action_effectuee = 'Facture d\'admission' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$this->load->view('print/infoNet', $page_data);
	}

	public function FicheConsPrint($numfac = '')
	{
			$date = date('Y-m-d');

			$infoConsultationPatient = $this->printmodel->get_info_cons_patient($numfac,$date);

			$libassurance = $this->printmodel->get_patient_assurance($infoConsultationPatient['codeassurance']);

			if(empty($libassurance))
			{
				$page_data['don'] = "";
			}
			else
			{
				$page_data['don'] = $libassurance;
			}

			$nommedecin = $this->printmodel->get_medecin($infoConsultationPatient['codemedecin']);

			$garantiecons = $this->printmodel->get_garantie($infoConsultationPatient['codeacte']);

			$page_data['print_name'] = 'i_FC';
			$page_data['d'] = $infoConsultationPatient;
			
			$page_data['Med'] = $nommedecin;
			$page_data['Acte'] = $garantiecons;

			// Effectuer la journalisation
			$type_action = 'Impression' ;

			$action_effectuee = 'Fiche de consultation' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

			$this->load->view('print/infoNet', $page_data);
	}

	public function FactueMensuellePrint($code = "")
	{
			$date = date('Y-m-d');

				$datedebut = $this->input->post('datedebut');
				$datefin = $this->input->post('datefin');
				$etablissementPayeur = $this->input->post('etablissementPayeur');
				$codesocieteassure = $this->input->post('societe');
				$assureur = $this->input->post('assureur');
				
			    $code = $this->input->post('code_print');

				if($code == "")
				{
					$type_impression = 1 ;
				}
				else
				{
					$type_impression = $code ;
				}

				
				
				$periode_conso = substr($datedebut,0,7);

				$factures_tierspayant = $this->db->get_where('factures_tierspayant', array('idassurance' => $etablissementPayeur,'codesocieteassure' => $codesocieteassure,'periode_conso' => $periode_conso))->row_array();

				if(!empty($factures_tierspayant))
				{
					$numero_facture = $factures_tierspayant['numfac_tp'];
									
				}
	
				$page_data['print_name'] = 'i_FacMens';
				$page_data['datedebut'] = $datedebut;
				$page_data['datefin'] = $datefin;
				$page_data['numero_facture'] = $numero_facture;
				$page_data['etablissementPayeur'] = $etablissementPayeur;
				$page_data['codesocieteassure'] = $codesocieteassure;
				$page_data['type_impression'] = $type_impression;
				$page_data['assureur'] = $assureur;

				// Effectuer la journalisation
			$type_action = 'Impression' ;

			$action_effectuee = 'Facture mensuelle' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
				
				$this->load->view('print/infoNet', $page_data);
			
	}
	
	public function PointCaissePrint()
	{

				$date_debut = $this->input->post('date_debut');
				$date_fin = $this->input->post('date_fin');


				$page_data['print_name'] = 'i_PointCaisse';
				$page_data['date_debut'] = $date_debut;
				$page_data['date_fin'] = $date_fin;

				// Effectuer la journalisation
			$type_action = 'Impression' ;

			$action_effectuee = 'Point de caisse' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
				
				$this->load->view('print/infoNet', $page_data);
	
	}
	
	public function RecuSortieCaissePrint($codeop)
	{
		$page_data['print_name'] = 'i_RecuSortieCaisse';
		
		$page_data['infos_sortie'] = $this->all_model->get_fullrow('caisse','codeop',$codeop);

		// Effectuer la journalisation
			$type_action = 'Impression' ;

			$action_effectuee = 'Reçu de sortie de caisse' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
				
		$this->load->view('print/infoNet', $page_data);
	}

	public function JournalComptantPrint()
	{

		$date_debut = $this->input->post('date_debut');
		$date_fin = $this->input->post('date_fin');

		$infos_journalcomptant = $this->admin_model->get_journal_comptant($date_debut,$date_fin);
		
		if(!empty($infos_journalcomptant))
		{
		    $page_data['Journal_comptant'] = $infos_journalcomptant ;
		    
		    $page_data['print_name'] = 'i_JournalComptantPrint';
    		$page_data['date_debut'] = $date_debut;
    		$page_data['date_fin'] = $date_fin;
		}
		else
		{
		    $page_data['Journal_comptant'] = array() ;
		    
		    $page_data['print_name'] = 'i_JournalComptantPrint';
		}

		// Effectuer la journalisation
			$type_action = 'Impression' ;

			$action_effectuee = 'Journal des actes externes au comptant' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
	
		$this->load->view('print/infoNet', $page_data);
	
	}

	public function BonNonEncaissePrint()
	{

		$date_debut = $this->input->post('date_debut');
		$date_fin = $this->input->post('date_fin');

		$infos_nonencaisse = $this->admin_model->get_bon_nonencaisse($date_debut,$date_fin);
		
		if(!empty($infos_nonencaisse))
		{
		    $page_data['Bon_nonencaisse'] = $infos_nonencaisse ;
    		$page_data['print_name'] = 'i_BonNonEncaissePrint';
    		$page_data['date_debut'] = $date_debut;
    		$page_data['date_fin'] = $date_fin;
		}
		else
		{
		    $page_data['Bon_nonencaisse'] = array() ;
    		$page_data['print_name'] = 'i_BonNonEncaissePrint';
		}

		// Effectuer la journalisation
			$type_action = 'Impression' ;

			$action_effectuee = 'Etat des factures non encaissées' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

		$this->load->view('print/infoNet', $page_data);
	
	}



	public function GraphicPrint()
	{

		$critere_1b = $this->input->post('critere_1b');
		$critere_2b = $this->input->post('critere_2b');
		$critere_3b = $this->input->post('critere_3b');
		$critere_4b = $this->input->post('critere_4b');

		if($critere_1b == 1)
		{
			$page_data['donnees_1b'] = $this->admin_model->get_consultation();
		}

		if($critere_2b == 1)
		{
			$page_data['donnees_2b'] = $this->admin_model->donnees_2b();
		}

		if($critere_3b == 1)
		{
			$page_data['donnees_3b'] = $this->admin_model->ventes_par_produits();

			$page_data['max_and_min_date'] = $this->admin_model->max_and_min_date();
		}

		if($critere_4b == 1)
		{
			$page_data['donnees_4b'] = $this->admin_model->stat_type_consultation();
		}

		if(($critere_1b == 0) && ($critere_2b == 0) && ($critere_3b == 0) && ($critere_4b == 0))
		{
			$page_data['donnees_1b'] = '';
			$page_data['donnees_2b'] = '';
			$page_data['donnees_3b'] = '';
			$page_data['donnees_4b'] = '';
		}

		// Effectuer la journalisation
			$type_action = 'Impression' ;

			$action_effectuee = 'Statistiques graphiques' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

		$page_data['print_name'] = 'i_graphic';
				
		$this->load->view('print/infoNet', $page_data);
	
	}
	
	public function liste_actes()
	{

	   $date_debut = $this->input->post('date_debut');

       $date_fin = $this->input->post('date_fin');

       $medecin = $this->input->post('medecin');

       $prestation = $this->input->post('prestation');

		switch ($prestation) {
	        case 1 :
	        		$liste_actes = $this->all_model->get_liste_consultations($date_debut,$date_fin,$medecin);

	        		$page_data['libelle_1'] = 'LISTE DES CONSULTATIONS EFFECTUEES DU ';

	        		$page_data['libelle_2'] = 'MONTANT TOTAL DES CONSULTATIONS EFFECTUEES DU ';

	        		$page_data['libelle_3'] = 'Consultation(s)';
	        		break;

	        case 2 :
	        		$liste_actes = $this->all_model->get_liste_imagerie($date_debut,$date_fin,$medecin);

	        		$page_data['libelle_1'] = 'LISTE DES IMAGERIES PRESCRITES DU ';

	        		$page_data['libelle_2'] = 'MONTANT TOTAL DES IMAGERIES PRESCRITES DU ';

	        		$page_data['libelle_3'] = 'Imagerie(s)';
	        		break;

	        case 3 :
	        		$liste_actes = $this->all_model->get_liste_viste($date_debut,$date_fin,$medecin);

	        		$page_data['libelle_1'] = 'LISTE DES VISITES EFFECTUEES DU ';

	        		$page_data['libelle_2'] = 'MONTANT TOTAL DES VISITES EFFECTUEES DU ';

	        		$page_data['libelle_3'] = 'Visite(s)';
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
		    $page_data['liste_actes'] = array() ;

		    $page_data['prestation'] = $prestation;

		    $page_data['medecin'] = $medecin;
		    
		    $page_data['print_name'] = 'i_Actes_honoraires';
		}

		// Effectuer la journalisation
			$type_action = 'Impression' ;

			$action_effectuee = 'Liste des actes pour honoraires de medecins' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
	
		$this->load->view('print/infoNet', $page_data);
	
	}

	public function Historique_examen_print()
	{

		$date_debut = $this->input->post('date_debut');
		$date_fin = $this->input->post('date_fin');
		$examen = $this->input->post('examen');


		if(!empty($examen)){
			$infos_histo_examen = $this->all_model->get_historique_examen($date_debut,$date_fin,$examen);
		
			if(!empty($infos_histo_examen))
			{
			    $page_data['historique_examen'] = $infos_histo_examen ;
	    		$page_data['print_name'] = 'i_HistoriqueExamenPrint';
	    		$page_data['date_debut'] = $date_debut;
	    		$page_data['date_fin'] = $date_fin;

	    		$infos_examen = $this->all_model->get_fullrow('examen', 'numexam', $examen);

	    		if(!empty($infos_examen))
	    		{
	    			$page_data['denomination_examen'] = $infos_examen['denomination'];
	    		}else{
	    			$page_data['denomination_examen'] = '';
	    		}
			}
			else
			{
			    $page_data['historique_examen'] = array() ;
	    		$page_data['print_name'] = 'i_HistoriqueExamenPrint';
			}
		}else{
			$infos_histo_examen = $this->all_model->get_historique_synthese_examen($date_debut,$date_fin);

			if(!empty($infos_histo_examen))
			{
			    $page_data['historique_examen'] = $infos_histo_examen ;
	    		$page_data['print_name'] = 'i_HistoriqueSyntheseExamenPrint';
	    		$page_data['date_debut'] = $date_debut;
	    		$page_data['date_fin'] = $date_fin;

	    		$infos_examen = $this->all_model->get_fullrow('examen', 'numexam', $examen);

	    		if(!empty($infos_examen))
	    		{
	    			$page_data['denomination_examen'] = $infos_examen['denomination'];
	    		}else{
	    			$page_data['denomination_examen'] = '';
	    		}
			}
			else
			{
			    $page_data['historique_examen'] = array() ;
	    		$page_data['print_name'] = 'i_HistoriqueSyntheseExamenPrint';
			}
		}

		

		// Effectuer la journalisation
			$type_action = 'Impression' ;

			$action_effectuee = 'Etat de l`\'historique d\'un examen donnée.' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

		$this->load->view('print/infoNet', $page_data);
	
	}

	public function fiche_soins($id_soins)
	{
		$page_data['print_name'] = 'i_fiche_soins';

		$page_data['typesoins_as'] =  $this->all_model->get_table('typesoins_as');

        $page_data['actes_as'] =  $this->all_model->get_table('actes_as');

		$page_data['infos_fiche_soins'] = $infos_fiche_soins = $this->all_model->get_fullrow('fiche_soins_infirmiers','id',$id_soins);

		$infos_patient_soins_as = $this->all_model->get_patient_soins_as($infos_fiche_soins['date'],$infos_fiche_soins['idenregistremetpatient']) ;

		$page_data['infos_patient_soins_as'] = $infos_patient_soins_as ;

		// Effectuer la journalisation
			$type_action = 'Impression' ;

			$action_effectuee = 'Fiche de soins' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
				
		$this->load->view('print/infoNet', $page_data);
	}

	public function ordonnance($id_ordonnance)
	{
		$page_data['print_name'] = 'i_ordonnance';

		$page_data['infos_ordonnance'] = $infos_ordonnance = $this->all_model->get_fullrow('ordonnance','id',$id_ordonnance);

		if(!empty($infos_ordonnance['codemedecin']))
			{
				$infos_medicin = $this->all_model->get_fullrow('medecin','codemedecin',$infos_ordonnance['codemedecin']);
				
				if(!empty($infos_medicin))
				{
					$nommedecin = $infos_medicin['nomprenomsmed'] ;
				}
				else{

					$nommedecin = '' ;
				}
				

			}else{

				$nommedecin = '' ;
			}

			$page_data['nommedecin'] = $nommedecin ;

			if(!empty($infos_ordonnance['idenregistremetpatient']))
			{
				$infos_patient = $this->all_model->get_fullrow('patient','idenregistremetpatient',$infos_ordonnance['idenregistremetpatient']);
				
				if(!empty($infos_patient))
				{
					$nompatient = $infos_patient['nomprenomspatient'] ;
				}
				else{

					$nompatient = '' ;
				}
				

			}else{

				$nompatient = '' ;
			}

			$page_data['nompatient'] = $nompatient ;

		// Effectuer la journalisation
			$type_action = 'Impression' ;

			$action_effectuee = 'Ordonnance' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
				
		$this->load->view('print/infoNet', $page_data);
	}
	
	public function RecuSortieComptabilitePrint($nopiece)
	{
		$page_data['print_name'] = 'i_RecuSortieComptabilite';
		
		$page_data['infos_sortie'] = $this->all_model->get_fullrow('transactions_comptables','nopiece',$nopiece);

		// Effectuer la journalisation
			$type_action = 'Impression' ;

			$action_effectuee = 'Reçu de sortie de caisse' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
				
		$this->load->view('print/infoNet', $page_data);
	}

	public function EtatHonorairesPayes()
	{

		$date_debut = $this->input->post('date_debut');
		$date_fin = $this->input->post('date_fin');

		$infos_honoraires = $this->all_model->get_liste_honoraires_payes($date_debut,$date_fin);
		
		if(!empty($infos_honoraires))
		{
		    $page_data['honoraires'] = $infos_honoraires ;
    		$page_data['print_name'] = 'i_HonorairesPayesPrint';
    		$page_data['date_debut'] =  $date_debut;
    		$page_data['date_fin'] = $date_fin;
		}
		else
		{
		    $page_data['honoraires'] = array() ;
    		$page_data['print_name'] = 'i_HonorairesPayesPrint';
		}

		// Effectuer la journalisation
			$type_action = 'Impression' ;

			$action_effectuee = 'Etat des honoraires payés' ;

			$this->control->journalisation($type_action,$action_effectuee) ;

		$this->load->view('print/infoNet', $page_data);
	
	}

}
	
