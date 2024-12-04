<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Recherche_autocomplete extends Admin_Controller {
	
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

			if ($curr_uri_string != 'recherche_autocomplete') 
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
	----------				PAGE RACINE :: ./get_autocomplete					  ----------
	################################################################## */
	/**** NB : le champ du id doit etre devant 
	EX: $arr_result[] = $row['id'].'|'.$row['libelle'];*/
	function get_autocomplete($table,$champ1,$champ2){

        if (isset($_GET['term'])) 
        {
            $result = $this->all_model->search_infos($_GET['term'],$table,$champ1);
            
            if (count($result) > 0) 
            {
           
		            foreach ($result as $row)

		                $arr_result[] = $row[$champ1].'|'.$row[$champ2];
		             echo json_encode($arr_result);
				
		    }
        }
    }

   
	
	 function get_autocomplete_param($table,$param){

        if (isset($_GET['term'])) 
        {
            $result = $this->admin_model->search_infos_param($_GET['term'],$table,$param);
            
            if (count($result) > 0) 
            {
            	if($table == 'medecin')
				{
		            foreach ($result as $row)

		                $arr_result[] = $row['nomedecin'].'|'.$row['codmed'];
		                echo json_encode($arr_result);
		        }
				
		    }
        }
    }

	
    function get_autocomplete_lib($table){

        if (isset($_GET['term'])) 
        {
            $result = $this->admin_model->search_infos($_GET['term'],$table);
            
            if (count($result) > 0) 
            {

		        if($table == 'pharmaprod')
				{
		            foreach ($result as $row)

		                $arr_result[] = $row['pr_nom'].'|'.$row['pr_code'];
		                echo json_encode($arr_result);
		        }
				
		    }
        }
    }
	
	function get_autocomplete_table_field($table){
		
		
        if (isset($_GET['term'])) 
        {  
            $result = $this->all_model->search_infos_table_only($_GET['term'],$table);
			
            if (count($result) > 0) 
            {
           
				if($table=='structure_bd'){
					
					foreach ($result as $row)
						
		               $arr_result[] = $row["Tables_in_".$this->config->item("database_name")];
		             echo json_encode($arr_result);
				}
		            
				
		    }
        }
    }

	
	
	
}


/* End of file recherche_autocomplete.php */
/* Location: ./application/controllers/recherche_autocomplete.php */