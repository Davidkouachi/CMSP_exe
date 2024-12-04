<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Facture_medicament extends Admin_Controller {
	
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

			if ($curr_uri_string != 'facture_medicament') 
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

	        $page_data['new_patient'] =  $this->all_model->get_new_patient();

        	$page_data['chiffre_aff'] =  $this->all_model->get_ca_mens();

        	//$page_data['products'] =  $this->all_model->get_table('medicine');
        	$page_data['products'] =  $this->all_model->getActiveProductData();
        	
        	$page_data['list_admission'] =  $this->all_model->get_table('admission');


	        $page_data['page_libprofil'] = $UniqueProfil;

	        $page_data['page_liste_patient'] = $ListePatient;

			$page_data['bandeau'] = lang('title_home_page');

			$page_data['title'] = lang('title_home_page');

			$page_data['page_active'] = "facture_medicPage";

			$page_data['page_s_title'] = 'Facture des médicaments à servir';

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire de facture de médicaments à servir' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

			$this->render_template('pharmacie/facture_medicament', $page_data);
		
	}

	/* ##################################################################
	----------				PAGE RACINE :: facture_medicament/gestion_facture					  ----------
	################################################################## */
	
	public function gestion_facture() 
	{//$this->output->enable_profiler(TRUE); 

    		$ListePatient = $this->PatientModel->getPatient();

	        $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));


	        $page_data['page_libprofil'] = $UniqueProfil;

	        $page_data['page_liste_patient'] = $ListePatient;

			$page_data['bandeau'] = lang('title_home_page');

			$page_data['title'] = lang('title_home_page');

			$page_data['page_active'] = "gestion_facPage";

			$page_data['page_s_title'] = 'Gestion des factures de médicaments';

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Gestion des factures de médicaments' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

			$this->render_template('pharmacie/gestion_facture_medicament', $page_data);
		
	}

	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchOrdersData()
	{
		$result = array('data' => array());

		$data = $this->all_model->getOrdersData();

		foreach ($data as $key => $value) {

			$count_total_item = $this->all_model->countOrderItem($value['id']);

			$date = $this->fdateheuregmt->date_fr($value['date']);

			$infos_patient = $this->all_model->get_fullrow('patient', 'idenregistremetpatient', $value['idenregistremetpatient']);

			// button
			$buttons = '';

			
				$buttons .= '<a target="__blank" class="btn btn-success" href="'.base_url('facture_medicament/printDiv/'.$value['id']).'"><i class="fa fa-print"> Imprimer</i></a> | ';
			

				$buttons .= '<a target="__blank" class="btn btn-success" href="'.base_url('facture_medicament/printDetail/'.$value['id']).'"><i class="fa fa-print"> Imprimer detail</i></a> | ';

			
				$buttons .= ' <br><br> <a class="btn btn-warning" href="'.base_url('facture_medicament/update/'.$value['id']).'"><i class="fa fa-pencil"> Modifier</i></a> | ';
			

				$buttons .= ' <br><br> <a href="#" class="btn btn-danger" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"> Supprimer facture</i></a>';
			


			$result['data'][$key] = array(
				$value['numfac'],
				$value['idenregistremetpatient'],
				$infos_patient['nomprenomspatient'],
				$infos_patient['telpatient'],
				$date,
				$count_total_item,
				$value['montant'],
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
		/*if(!in_array('createOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }*/

		$this->data['page_title'] = 'Ajouter une commande';

		$this->form_validation->set_rules('product[]', 'Nom du produit', 'trim|required');
		
	
        if ($this->form_validation->run() == TRUE) {        	
        	
        	$order_id = $this->all_model->create();
        	
        	if($order_id) {
        		$this->session->set_flashdata('success', 'Enrégistrement effectué avec succès.');
        		redirect('facture_medicament/update/'.$order_id, 'refresh');
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Une erreur est survenue !!');
        		redirect('facture_medicament/create/', 'refresh');
        	}
        }
        else {
            // false case
        	/*$company = $this->model_company->getCompanyData(1);
        	$this->data['company_data'] = $company;
        	$this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
        	$this->data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;*/

        	$this->data['products'] = $this->all_model->getActiveProductData();      	

            //$this->render_template('orders/create', $this->data);

            // Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Facture de médicaments' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

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
		
	
        if ($this->form_validation->run() == TRUE) {        	
        	
        	$update = $this->all_model->update($id);
        	
        	if($update == true) {

        		// Effectuer la journalisation
		        $type_action = 'Modification' ;

		        $action_effectuee = 'Facture de médicaments' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

        		$this->session->set_flashdata('success', 'Modification effectuée avec succès.');
        		redirect('facture_medicament/update/'.$id, 'refresh');
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Une erreur est survenue !!');
        		redirect('facture_medicament/update/'.$id, 'refresh');
        	}
        }
        else {
            // false case
        	/*$company = $this->model_company->getCompanyData(1);
        	$this->data['company_data'] = $company;
        	$this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
        	$this->data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;*/

        	/************************/

        	$ListePatient = $this->PatientModel->getPatient();

	        $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	        $page_data['new_patient'] =  $this->all_model->get_new_patient();

        	$page_data['chiffre_aff'] =  $this->all_model->get_ca_mens();

        	$page_data['products'] =  $this->all_model->get_table('medicine');
        	
        	$page_data['list_admission'] =  $this->all_model->get_table('admission');


	        $page_data['page_libprofil'] = $UniqueProfil;

	        $page_data['page_liste_patient'] = $ListePatient;

			$page_data['bandeau'] = lang('title_home_page');

			$page_data['title'] = lang('title_home_page');

			$page_data['page_active'] = "facture_medicPage";

			$page_data['page_s_title'] = 'Facture de médicaments servis';
        	/************************/

        	$result = array();
        	$orders_data = $this->all_model->getOrdersData($id);

    		$result['order'] = $orders_data;
    		$orders_item = $this->all_model->getOrdersItemData($orders_data['id']);

    		foreach($orders_item as $k => $v) {
    			$result['order_item'][] = $v;
    		}

    		$page_data['order_data'] = $result;

        	$page_data['products'] = $this->all_model->getActiveProductData();      	
/*var_dump($page_data['order_data']);
exit();*/
            $this->render_template('pharmacie/facture_medicament_edit', $page_data);
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
            $delete = $this->all_model->remove($order_id);
            if($delete == true) {

            	// Effectuer la journalisation
		        $type_action = 'Suppression' ;

		        $action_effectuee = 'Facture de médicaments' ;

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
			$page_data['order_data'] = $this->all_model->getOrdersData($id);
			$page_data['orders_items'] = $this->all_model->getOrdersItemData($id);

			$page_data['order_date'] = $page_data['order_data']['date'];

			$page_data['print_name'] = 'i_FacMedics';

			// Effectuer la journalisation
		        $type_action = 'Impression' ;

		        $action_effectuee = 'Facture médicaments' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			$this->load->view('print/infoNet', $page_data);
		}	
	}	
	
	public function printDetail($id)
	{
		if($id) 
		{
			$page_data['order_data'] = $this->all_model->getOrdersData($id);
			$page_data['orders_items'] = $this->all_model->getOrdersDetailData($id);

			$page_data['order_date'] = $page_data['order_data']['date'];

			$page_data['print_name'] = 'i_DetailMedics';

			// Effectuer la journalisation
		        $type_action = 'Impression' ;

		        $action_effectuee = 'Facture médicaments' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			$this->load->view('print/infoNet', $page_data);
		}	
	}
}


/* End of file facture_medicament.php */
/* Location: ./application/controllers/facture_medicament.php */