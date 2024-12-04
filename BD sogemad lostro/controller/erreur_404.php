<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Erreur_404 extends Admin_Controller {
	
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

			if ($curr_uri_string != 'erreur_404') 
			{
				redirect('home/login','refresh');
			}

			redirect('home/login','refresh');
		}

		if($this->control->check_lc() === FALSE)
		{
			$this->session->set_userdata('user_id','');
			$this->session->set_userdata('user_name','');
			$this->session->set_userdata('logged_in',FALSE);

			$flash_feedback = "La licence d'utilisation du logiciel est inactive pour ce poste. Vous pouvez demander une augmentation du nombre de poste de votre licence.";

			$this->session->set_flashdata('warning', $flash_feedback);

			redirect('home/login','refresh');
		}
		
		/*cache control*/
		$this->output->set_header('Cache-Control: no-store, must-revalidate, post-check=0, pre-check=0');

	}
		
	/* ##################################################################
	----------				PAGE RACINE :: ./Erreur_404.php					  ----------
	################################################################## */
	
	public function index() 
	{

		$page_data['bandeau'] = 'Erreur 404';
		$page_data['title'] = 'Page d\' erreur 404';

		// affichage de la vue
		$this->render_lock_template('erreur/affichage_404', $page_data);
		
	}
	
}


/* End of file Erreur_404.php */
/* Location: ./application/controllers/erreur_404.php */