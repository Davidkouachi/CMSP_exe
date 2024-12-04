<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Infirmerie extends Admin_Controller {
	
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

			if ($curr_uri_string != 'infirmerie') 
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
	{//$this->output->enable_profiler(TRUE); 

    		$ListePatient = $this->PatientModel->getPatient();

	        $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['typesoins'] =  $this->all_model->get_table('typesoinsinfirmiers');

        	$page_data['soins'] =  $this->all_model->get_table('soins_infirmier');

        	$page_data['products'] =  $this->all_model->getActiveProductData();

            $page_data['code_patient'] = "";
            
	        $page_data['page_libprofil'] = $UniqueProfil;

	        $page_data['page_liste_patient'] = $ListePatient;

			$page_data['bandeau'] = lang('title_home_page');

			$page_data['title'] = lang('title_home_page');

			$page_data['page_active'] = "SoinAmbulatoirePage";

			$page_data['page_s_title'] = 'Facture des soins infirmier';

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Facture de soins infirmier' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

			$this->render_template('infirmerie/facture_soins', $page_data);
		
	}
	
	public function facture_soins($codepatient) 
	{
		if(isset($codepatient))
		{
			$infos_patient =  $this->all_model->get_fullrow('patient','idenregistremetpatient',$codepatient);
			if(!empty($infos_patient))
			{
				$page_data['code_patient'] =  $codepatient;
			}else{
				$page_data['code_patient'] =  "";
			}
			
		}else{
			$page_data['code_patient'] = "";
		} 

		$ListePatient = $this->PatientModel->getPatient();

	        $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['typesoins'] =  $this->all_model->get_table('typesoinsinfirmiers');

        	$page_data['soins'] =  $this->all_model->get_table('soins_infirmier');

        	$page_data['products'] =  $this->all_model->getActiveProductData();


	        $page_data['page_libprofil'] = $UniqueProfil;

	        $page_data['page_liste_patient'] = $ListePatient;

			$page_data['bandeau'] = lang('title_home_page');

			$page_data['title'] = lang('title_home_page');

			$page_data['page_active'] = "SoinAmbulatoirePage";

			$page_data['page_s_title'] = 'Facture des soins infirmier';

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Facture de soins infirmier' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

			$this->render_template('infirmerie/facture_soins', $page_data);

		
	}

	public function Encaisser_soins()
    {	
		 $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

            $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'encaisser_soins';
			$page_data['page_active'] = 'CaissePage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'ENCAISSER UNE FACTURE DE SOINS INFIRMIER';
			
			$page_data['solde_caisse'] = $this->all_model->get_solde_caisse(date('Y-m-d')) ;
			
			$page_data['lien'] = base_url() . 'ajax/ouverture_caisse/infirmerie' ;


			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire d\'encaissement de facture de soins infirmier' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;
			
			// affichage de la vue

			$this->render_template('kameleon/encaisser_soins', $page_data);
    }

	/* ##################################################################
	----------				PAGE RACINE :: facture_medicament/gestion_facture					  ----------
	################################################################## */
	
	public function gestion_facture() 
	{
    		$ListePatient = $this->PatientModel->getPatient();

	        $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));


	        $page_data['page_libprofil'] = $UniqueProfil;

	        $page_data['page_liste_patient'] = $ListePatient;

			$page_data['bandeau'] = lang('title_home_page');

			$page_data['title'] = lang('title_home_page');

			$page_data['page_active'] = "gestion_facPage";

			$page_data['page_s_title'] = 'Gestion des factures de soins infirmier';

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Liste de factures de soins infirmier' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

			$this->render_template('infirmerie/gestion_facture_soins', $page_data);
		
	}

	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchOrdersData()
	{
		$result = array('data' => array());

		$data = $this->all_model->get_table('soins_medicaux');

		foreach ($data as $key => $value) {

			$count_total_soins_item = $this->all_model->countSoinsItem($value['id_soins']);

			$count_total_medics_item = $this->all_model->countMedicsItem($value['id_soins']);

			$date = $this->fdateheuregmt->date_fr($value['date_soin']);

			$infos_patient = $this->all_model->get_fullrow('patient', 'idenregistremetpatient', $value['idenregistremetpatient']);

			// button
			$buttons = '';
			
			if((in_array('viewFacSoins', $this->permission)) && (in_array('updateFacSoins', $this->permission)) && (in_array('deleteFacSoins', $this->permission))) {
				$buttons .= '<a target="__blank" href="'.base_url('infirmerie/printDiv/'.$value['id_soins']).'"><i class="fa fa-print"> Imp.</i></a> | <a href="'.base_url('infirmerie/update/'.$value['id_soins']).'"><i class="fa fa-pencil"> Mod.</i></a> | <a href="#"  onclick="removeFunc('.$value['id_soins'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"> Supp.</i></a>';
			}else
			
			if((in_array('updateFacSoins', $this->permission)) && (in_array('viewFacSoins', $this->permission))) {
				$buttons .= '<a href="'.base_url('infirmerie/update/'.$value['id_soins']).'"><i class="fa fa-pencil"> Mod.</i></a> | <a target="__blank" href="'.base_url('infirmerie/printDiv/'.$value['id_soins']).'"><i class="fa fa-print"> Imp.</i></a>';
			}else
			
			if((in_array('deleteFacSoins', $this->permission)) && (in_array('viewFacSoins', $this->permission))) {
				$buttons .= '<a href="#"  onclick="removeFunc('.$value['id_soins'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"> Supp.</i></a> | <a target="__blank" href="'.base_url('infirmerie/printDiv/'.$value['id_soins']).'"><i class="fa fa-print"> Imp.</i></a>';
			}else
			
			if((in_array('deleteFacSoins', $this->permission)) && (in_array('updateFacSoins', $this->permission))) {
				$buttons .= '<a href="#"  onclick="removeFunc('.$value['id_soins'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"> Supp.</i></a> | <a href="'.base_url('infirmerie/update/'.$value['id_soins']).'"><i class="fa fa-pencil"> Mod.</i></a>';
			}else
 
			if(in_array('viewFacSoins', $this->permission)) {
				$buttons .= '<a target="__blank" href="'.base_url('infirmerie/printDiv/'.$value['id_soins']).'"><i class="fa fa-print"> Imp.</i></a>';
			}else

			if(in_array('updateFacSoins', $this->permission)) {
				$buttons .= '<a href="'.base_url('infirmerie/update/'.$value['id_soins']).'"><i class="fa fa-pencil"> Mod.</i></a>';
			}else

			if(in_array('deleteFacSoins', $this->permission)) {
				$buttons .= '<a href="#"  onclick="removeFunc('.$value['id_soins'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"> Supp.</i></a>';
			}
			
			
			
            
			
			


			

			$result['data'][$key] = array(
				$value['numfac_soins'],
				$value['idenregistremetpatient'],
				$infos_patient['nomprenomspatient'],
				$infos_patient['telpatient'],
				$date,
				$count_total_soins_item,
				$count_total_medics_item,
				$value['montant_total'],
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	public function getTableProductRow()
	{
		$products = $this->all_model->getActiveProductData();
		echo json_encode($products);
	}

	public function getTableProductSoinsRow()
	{
		$products = $this->all_model->get_table('soins_infirmier');
		echo json_encode($products);
	}

	/*
	* It gets the product id passed from the ajax method.
	* It checks retrieves the particular product data from the product id 
	* and return the data into the json format.
	*/
	public function getProductValueById()
	{
		$product_id = $this->input->post('product_id');
		if($product_id) {
			$product_data = $this->all_model->getProductData($product_id);
			echo json_encode($product_data);
		}
	}

	public function getProductSoinsValueById()
	{
		$product_id = $this->input->post('product_id');
		if($product_id) {
			$product_data = $this->all_model->getProductsoinsData($product_id);
			echo json_encode($product_data);
		}
	}

	public function getTauxCouverture()
	{
		$patient_id = $this->input->post('patient_id');
		if($patient_id) {
			$patient_taux_data = $this->all_model->getTauxCouverture($patient_id);
			echo json_encode($patient_taux_data);
		}
	}

	/*
	* If the validation is not valid, then it redirects to the create page.
	* If the validation for each input field is valid then it inserts the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function create()
	{

		$this->data['page_title'] = 'Ajouter une commande';

		$this->form_validation->set_rules('product[]', 'Nom du produit', 'trim');
		$this->form_validation->set_rules('productsoins[]', 'Nom du soin', 'trim');
		
	
        if ($this->form_validation->run() == TRUE) {        	
        	
        	$order_id = $this->all_model->create_soin();
        	
        	if($order_id) {

        		// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Facture de soins infirmier' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

        		$this->session->set_flashdata('success', 'Enrégistrement effectué avec succès.');
        		redirect('infirmerie/update/'.$order_id, 'refresh');
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Une erreur est survenue !!');
        		redirect('infirmerie/create/', 'refresh');
        	}

        	
        }
        else {

        	$this->data['products'] = $this->all_model->getActiveProductData();

            // affichage de la vue

			$this->render_template('pharmacie/facture_medicament', $this->data);
        }	
	}

	/*
	* If the validation is not valid, then it redirects to the edit orders page 
	* If the validation is successfully then it updates the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function update($id)
	{
		/*if(!in_array('updateOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }*/

		if(!$id) {
			redirect('dashboard', 'refresh');
		}

		$this->data['page_title'] = 'Modifier une facture';

		$this->form_validation->set_rules('product[]', 'Nom du produit', 'trim|required');
		$this->form_validation->set_rules('productsoins[]', 'Nom du soin', 'trim|required');
		
	
        if ($this->form_validation->run() == TRUE) {        	
        	
        	$update = $this->all_model->update_soins($id);
        	
        	if($update == true) {
        		// Effectuer la journalisation
		        $type_action = 'Modification' ;

		        $action_effectuee = 'Facture de soins infirmier' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

        		$this->session->set_flashdata('success', 'Modification effectuée avec succès.');
        		redirect('infirmerie/update/'.$id, 'refresh');
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Une erreur est survenue !!');
        		redirect('infirmerie/update/'.$id, 'refresh');
        	}
        }
        else {

        	$ListePatient = $this->PatientModel->getPatient();

	        $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['products'] =  $this->all_model->get_table('medicine');

        	$page_data['soins'] =  $this->all_model->get_table('soins_infirmier');

	        $page_data['page_libprofil'] = $UniqueProfil;

	        $page_data['page_liste_patient'] = $ListePatient;

			$page_data['bandeau'] = lang('title_home_page');

			$page_data['title'] = lang('title_home_page');

			$page_data['page_active'] = "facture_medicPage";

			$page_data['page_s_title'] = 'Facture de médicaments servis';
        	/************************/

        	$result = array();
        	$orders_data = $this->all_model->getSoinsMedicauxData($id);

    		$result['order'] = $orders_data;

    		$orders_item = $this->all_model->getMedicsItemData($orders_data['id_soins']);

    		foreach($orders_item as $k => $v) {
    			$result['order_item'][] = $v;
    		}


    		$page_data['order_data'] = $result;

    		$result_soins = array();

    		$orders_soins_item = $this->all_model->getSoinsItemData($orders_data['id_soins']);

    		foreach($orders_soins_item as $k => $v) {
    			$result_soins['order_soins_item'][] = $v;
    		}

    		$page_data['order_soins_data'] = $result_soins;

        	$page_data['products'] = $this->all_model->getActiveProductData();      	
/*var_dump($page_data['order_soins_data']);
exit();*/
            $this->render_template('infirmerie/facture_soins_edit', $page_data);
        }
	}

	/*
	* It removes the data from the database
	* and it returns the response into the json format
	*/
	public function remove()
	{
		/*if(!in_array('deleteOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }*/

		$order_id = $this->input->post('order_id');

        $response = array();
        if($order_id) {
            $delete = $this->all_model->remove_soins($order_id);
            if($delete == true) {

            	// Effectuer la journalisation
		        $type_action = 'Suppression' ;

		        $action_effectuee = 'Facture de soins infirmier' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

                $response['success'] = true;
                $response['messages'] = "Suppression effectuée avec succès"; 
            }
            else {
                $response['success'] = false;
                $response['messages'] = "Erreur dans la base de données lors de la suppression des informations";
            }
        }
        else {
            $response['success'] = false;
            $response['messages'] = "Actualiser à nouveau la page !!";
        }

        echo json_encode($response); 
	}

	/*
	* It gets the product id and fetch the order data. 
	* The order print logic is done here 
	*/
	public function printDiv($id)
	{
		if($id) 
		{
			$page_data['order_data'] = $this->all_model->getSoinsMedicauxData($id);

			$page_data['orders_medicsitems'] = $this->all_model->getMedicsItemData($id);

			$page_data['orders_soinsitems'] = $this->all_model->getSoinsItemData($id);

			$page_data['order_date'] = $page_data['order_data']['date_soin'];
			$page_data['paid_status'] = ($page_data['order_data']['paid_status'] == 1) ? "Payé" : "Non payé";

			$page_data['print_name'] = 'i_FacSoinsMedico';

			// Effectuer la journalisation
		        $type_action = 'Impression' ;

		        $action_effectuee = 'Facture de soins infirmier' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			$this->load->view('print/infoNet', $page_data);
		}	
	}

	public function PrintFac($numfac)
	{
			$date = date('Y-m-d');
			
			$codebare = $this->db->get_where('factures' , array('numfac'=>$numfac))->row_array();

			if(!empty($codebare))
			{
				$page_data['print_name'] = 'i_RS';
				$page_data['numfac'] = $numfac;
				//$page_data['codebare'] = $this->generateur_identifiant->code_barre($codebare['numrecu']);
				
				$page_data['codebare'] = '' ;
			}
			else
			{
				$page_data['numfac'] = $numfac;
				$page_data['print_name'] = 'i_RS';
			}

			// Effectuer la journalisation
		        $type_action = 'Impression' ;

		        $action_effectuee = 'Reçu de soins infirmier' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			$this->load->view('print/infoNet', $page_data);
	}

	public function ReglerFactureSoins()
    { 
        include("assets/inc/fdateheuregmt.php");
        
		if(!empty($_POST))
		{
			$numfac = strtoupper($this->input->post('numfac'));




			$recherche_facture = $this->db->get_where('factures' , array('numfac'=>$numfac))->row_array();



			if(empty($recherche_facture))
			{
				echo '<div role="alert" class="alert alert-danger">
                        <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
                        Veuillez renseigner un numéro de facture valide.
                    </div>';
                    exit();
			}
			
			$soins_reglee = $this->db->get_where('soins_medicaux' , array('numfac_soins'=>$numfac,'paid_status'=>0))->row_array();

			$facture = $this->db->get_where('soins_medicaux' , array('numfac_soins'=>$numfac))->row_array();

			if (!empty($facture)) {
			    
			    $timbre = 0 ;
			    $ticketmod = $facture['ticket_moderateur'] ;

				if($facture['ticket_moderateur'] >= 5001)
				{
					$ticketmod = $facture['ticket_moderateur'] + 100 ;
					
					$timbre = 100 ;
				}
				
				if(($facture['ticket_moderateur'] >= 100001) && ($facture['ticket_moderateur'] <= 500000)) 
				{
					$ticketmod = $facture['ticket_moderateur'] + 500 ;
					
					$timbre = 500 ;
				}
				
				if(($facture['ticket_moderateur'] >= 500001) && ($facture['ticket_moderateur'] <= 1000000)) 
				{
					$ticketmod = $facture['ticket_moderateur'] + 1000 ;
					
					$timbre = 5000 ;
				}
				
				if(($facture['ticket_moderateur'] >= 1000001) && ($facture['ticket_moderateur'] <= 5000000)) 
				{
					$ticketmod = $facture['ticket_moderateur'] + 2000 ;
					
					$timbre = 2000 ;
				}
				
				if($facture['ticket_moderateur'] >= 5000001)
				{
					$ticketmod = $facture['ticket_moderateur'] + 5000 ;
					
					$timbre = 5000 ;
				}
			}else{
			    
			    $ticketmod = 0 ;
			    
			    $timbre = 0 ;
			}

			$patient = $this->db->get_where('patient' , array('idenregistremetpatient'=>$facture['idenregistremetpatient']))->row_array();

			$datebon = date('Y-m-d');

			$moderegle = $this->input->post('moderegle');

			$mtaregle = $this->input->post('mtaregle');

			$mtregle = $this->input->post('mtregle');

			$mttotal = $this->input->post('mttotal');
			
			$mtremise = $this->input->post('mtremise');

			$idpat = $this->input->post('idpat');

			$regfac = $this->input->post('regfac');

			if(empty($soins_reglee))
			{

			  echo '<div class="alert alert-danger">
			                    <button type="button" class="close" data-dismiss="alert">&times;</button>
			                    <strong>Cette facture a déjà été réglée et le reçu a déjà été imprimé. Vous pouvez passer a une autre facture.</strong>
			                  </div>';
			                        exit();
			}

			if(($numfac)&&($regfac == 'yes'))
			{

				/*$this->db->select_max('numrecu');
				$query = $this->db->get('factures');
			    $dernier_fac = $query->row_array();

			    //S IL S AGIT DU PREMIER NUMERO DE FAC

			    if($dernier_fac['numrecu'] == "")
			    {
			        
			        $decoupe = explode("-", $datebon);

			        $annee = $decoupe[0];
			        $annee_coup = substr($annee, 2, 3);
			        $numrecu = "RCE".$annee_coup.'0'.'0'.'0'.'1';
			        
			    } 
			    else 
			    if($dernier_fac != "")
			    {//S'IL EXISTE DEJA NUMERO DE FAC
			                
			    //CREATION DU NOUVEAU NUMERO DE FAC

			    $decoupe = explode("-", $datebon);
			    $annee = $decoupe[0];
			    $annee_coup = substr($annee, 2, 3);
			    $dern_nombre = substr($dernier_fac['numrecu'], 5, 4);
			    $nouv_nombre = $dern_nombre + 1;
			                    $str = "" . $nouv_nombre;
			                            while(strlen($str) < 4)
			                            {
			                                $str = "0" . $str;
			                            }
			                            $matn = $str;

			    $numrecu = "RCE".$annee_coup.$matn;
			    }*/
			    
			  $infos_compteur = $this->all_model->get_compteur() ;

			   $compteur = $infos_compteur['cptnref'];
			   
			   $date_jour = date('Y-m-d') ;
			   
			   $decoupe = explode("-", $date_jour);
			   $annee = $decoupe[0];
			    
			   $annee_coup = substr($annee, 2, 3);
			   
			   $mois = $decoupe[1];
			   
			   $dern_nombre = substr($compteur, 4, 4);
			   
			   $mois_cpteur = substr($compteur, 2, 2);
			   
			   if($mois_cpteur == $mois)
			    {
			       $nouv_nombre = $dern_nombre + 1; 
			    }else{
			        $nouv_nombre = 1; 
			    }
			    
			    $str = "" . $nouv_nombre;
			    
			    while(strlen($str) < 4)
			    {
			       $str = "0" . $str;
			     }
			     
			     $matn = $str;
			     
			     $numrecu = "RCE".$annee_coup.$mois.$matn;
			     
			     $compteur = $annee_coup.$mois.$matn;
			    
			    $this->all_model->set_compteur($compteur) ;

			  $montantreste = $mtaregle - $mtregle ;

			  if($montantreste == 0)
			  {
			    $solde = 1 ;
			  }
			  else
			  {
			    $solde = 0 ;
			  }

			  $date = date('Y-m-d');
			  
			  $infos_journal = $this->db->get_where('journal' , array('idenregistremetpatient'=>$facture['idenregistremetpatient'],'date'=>$date))->row_array();

			  /*$this->db->select_max('numjournal');
			  $query = $this->db->get('journal');
			  $dernier_journal = $query->row_array();*/
			  
			  $dernier_journal = $this->all_model->get_max_numjournal();

			  if(!empty($infos_journal))
			  {
			  	$numjournal = $infos_journal['numjournal'];
			  }
			  else
			  {
			  	if($dernier_journal['numjournal'] == "")
			    {
			  		$numjournal = 1 ;
			  	}
			  	else
			  	{
			  		$numjournal = $dernier_journal['numjournal'] + 1 ;
			  	}
			  }

			  $data_journal =  array('idenregistremetpatient' => $facture['idenregistremetpatient'],
								'date' => $date,
								'numrecu' => $numrecu,
								'montant_recu' => $mtregle,
								'numjournal' => $numjournal,
								'numfac' => $numfac
								);

			  /*$datacaisse_1 =  array('datecaisse' => $date,
								'mtcaisse' => $mtregle,
								'action' => 1
								);*/
			  $datacaisse_2 =  array('nopiece' => $numfac,
								'type' => 'entree',
								'libelle' => 'Encaissement facture soins infirmier',
								'montant' => $mtregle,
								'dateop' => $date,
								'datecreat' => $date,
								'login' => $this->session->userdata('user_name'),
								'reference' => $numfac,
								);

			  $datafactures =  array('remise' => $mtremise,
			                    'montantregle_pat' => $mtregle,
								'montantreste_pat' => $montantreste,
								'modereglt_pat' => $moderegle,
								'solde_pat' => $solde,
								'datereglt_pat' => $date,
								'numrecu' => $numrecu,
								'timbre_fiscal' => $timbre
								);

			  $datasoins_medicaux =  array('paid_status' => 1,
								'numfac_soins' => $this->input->post('numfac')
								);

			  //$this->db->insert('caisse_patient',$datacaisse_1);
			  
			  $this->db->insert('journal',$data_journal);

			  $this->db->insert('caisse',$datacaisse_2);

			  $this->db->where('numfac', $numfac);

			  $this->db->update('factures', $datafactures);

			  $this->db->where('numfac_soins', $numfac);

			  $this->db->update('soins_medicaux', $datasoins_medicaux);
			  
			  // Mise à jour du solde en caisse (caisse_resume)
					/***************/
					$solde_caisse = $this->all_model->get_solde_caisse(date('Y-m-d')) ;
					
					$nouveau_solde = $solde_caisse['mtcaisse'] + $mtregle ;
					
					$data_caisse_resume =  array('mtcaisse' => $nouveau_solde);
					
					$this->all_model->update_ligne('caisse_resume', $data_caisse_resume, 'idcaisse', $solde_caisse['idcaisse']) ;
					
					/***************/

			  // Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Encaissement de facture de soins infirmier' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			  echo '<input type="hidden" class="form-control" id="isLoged" value="1" >';
			exit();
			}
			else
			{
			    $profil_connecte = $this->session->userdata('user_profil') ;
				    
				if($profil_connecte == 4)
				{
				    $readonly = 'readonly';
				}
				else
				{
				    $readonly = '';
				}

			?>
				<hr style="height: 1px;color: #F00;background-color: #028B1F;border: 0;"/>
				<div class="col-md-12">
			        <h5> <strong> <u> Informations : </u></strong></h5>
			    </div>

			    <div class="col-md-12">
			      <hr style="height: 1px;color: #F00;background-color: #028B1F;border: 0;"/>
			      <div class="col-md-1">
			        <div class="form-group">
			            <label style="font-weight:bold; font-size:16px;">Date</label>
			        </div>
			      </div>
			      <div class="col-md-3">
			        <div class="form-group">
			          <div class="form-group">
			            <input type="text" style="color:black; font-weight:bold; font-size:25px;" readonly class="form-control" id="" value="<?php echo date_fr(date('Y-m-d')) ;  ?>">
			          </div>
			        </div>
			      </div>
			      <div class="col-md-1">
			        <div class="form-group">
			            <label style="font-weight:bold; font-size:16px;">Heure</label>
			        </div>
			      </div>
			      <div class="col-md-3">
			        <div class="form-group">
			          <input type="text" style="color:black; font-weight:bold; font-size:25px;" readonly class="form-control" id="" value="<?php echo dateheure(5) ; ?>">
			        </div>
			      </div>
			      <div class="col-md-1">
			        <div class="form-group">
			            <label style="font-weight:bold; font-size:16px;">N.I.P.</label>
			        </div>
			      </div>
			      <div class="col-md-3">
			        <div class="form-group">
			          <input type="text" style="color:black; font-weight:bold; font-size:25px;" readonly class="form-control" id="idpat" value="<?php echo $facture['idenregistremetpatient'] ?>">
			        </div>
			      </div>
			    </div>

			    <div class="col-md-12">
			      <div class="col-md-2">
			        <div class="form-group">
			            <label style="font-weight:bold; font-size:16px;">Nom & prénoms</label>
			        </div>
			      </div>
			      <div class="col-md-10">
			        <div class="form-group">
			          <div class="form-group">
			            <input type="text" style="color:black; font-weight:bold; font-size:25px;" readonly class="form-control" id="" value="<?php echo $patient['nomprenomspatient']  ?>">
			          </div>
			        </div>
			      </div>
			    </div>
			     <div class="col-md-12">
			      <div class="col-md-2">
			        <div class="form-group">
			            <label for="" style="font-weight:bold; font-size:16px;">Montant Total</label>
			        </div>
			      </div>
			      <div class="col-md-4">
			        <div class="form-group">
			          <input type="text" readonly style="color:red; font-weight:bold; font-size:25px; text-align:center"  class="form-control" id="" value="<?php echo $facture['montant_total']  ?>">
			          <input type="hidden" readonly style="color:red; font-weight:bold; font-size:25px; text-align:center"  class="form-control" id="mttotal" value="<?php echo $facture['montant_total']  ?>">
			            <label for=""></label>
			        </div>
			      </div>
			      <div class="col-md-3">
			        <div class="form-group">
			            <label for="" style="font-weight:bold; font-size:16px;">Montant de Remise</label>
			        </div>
			      </div>
			      <div class="col-md-3">
			        <div class="form-group">
			          <input type="text" style="color:green; font-weight:bold; font-size:25px; text-align:center"  class="form-control" <?php echo $readonly ?> value="0" id="mtremise" name="mtremise" onkeypress="return activate(event);">
			        </div>
			      </div>
			    </div>

			    <div class="col-md-12">
			      <div class="col-md-2">
			        <div class="form-group">
			            <label style="font-weight:bold; font-size:16px;">Montant à régler</label>
			        </div>
			      </div>
			      <div class="col-md-2">
			        <div class="form-group">
			          <div class="form-group">
			            <input type="text" style="color:red; font-weight:bold; font-size:25px; text-align:center" readonly class="form-control" id="mtaregle"  value="<?php echo $ticketmod ;  ?>">
			          </div>
			        </div>
			      </div>
			      <div class="col-md-2">
			        <div class="form-group">
			            <label style="font-weight:bold; font-size:16px;">Montant réglé</label>
			        </div>
			      </div>
			      <div class="col-md-2">
			        <div class="form-group">
			          <input type="text" style="color:red; font-weight:bold; font-size:25px; text-align:center" <?php echo $readonly ?> class="form-control" id="mtregle"  value="<?php echo $ticketmod ?>" onkeypress="return activate_regle(event);">
			        </div>
			      </div>
			      <div class="col-md-2">
			        <div class="form-group">
			            <label style="font-weight:bold; font-size:16px;">Mode reglement</label>
			        </div>
			      </div>
			      <div class="col-md-2">
			        <select class="form-control" id="moderegle">
			          <option value="" selected></option>   
			          <option id="espece" value="espece">ESPECE</option>
			          <!--
			          <option id="espece" value="cheque">CHEQUE</option>
			      -->
			        </select>
			      </div>
			    </div>
			    
			    <div class="col-md-12">
			        <hr style="height: 1px;color: #F00;background-color: #028B1F;border: 0;"/>

			      <div class="col-md-3">
			              <input type="hidden" id="afficheBouton"  value="1"> 
			              <input type="hidden" id="taux_couv"  value="<?php echo $soins_reglee['taux_couverture'] ?>">
			      </div>
			      

			      <div class="col-md-3">
			                                                    
			      </div>
			    </div>
		  <?php
		  	}
		}
    }
    
    public function constantes() 
	{
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

            $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_active'] = 'constantePage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Prise de constantes de consultation';

			$page_data['medecins'] = $this->all_model->get_table('medecin');


			$page_data['idAns'] = date('Y-m-d');

			$page_data['namePg'] = 'fetchConsultationData';

			// Effectuer la journalisation
		        $type_action = 'Consultation/constantes' ;

		        $action_effectuee = 'Liste des consultations cliniques' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue

		$this->render_template('infirmerie/liste_consultation', $page_data);	
	}	
}


/* End of file infirmerie.php */
/* Location: ./application/controllers/infirmerie.php */