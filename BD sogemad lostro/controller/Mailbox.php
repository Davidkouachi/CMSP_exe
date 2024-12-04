<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mailbox extends Admin_Controller {
    
    
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

			if ($curr_uri_string != 'mailbox') 
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

	
	public function ViewMailbox()
	{
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

		$page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_name'] = 'mailbox';
		$page_data['page_active'] = 'MailboxPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Boite de réception';

		// Effectuer la journalisation
		    $type_action = 'Consultation' ;

		    $action_effectuee = 'Boite de réception des mails' ;

		    $this->control->journalisation($type_action,$action_effectuee) ;
		
		// affichage de la vue

	        $this->render_template('kameleon/mailbox', $page_data);
	}

	public function ViewSendMailbox()
	{
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

		$page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_name'] = 'sendmailbox';
		$page_data['page_active'] = 'MailboxPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Message(s) envoyé(s)';

		// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Liste des mails envoyés' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue

			$this->render_template('kameleon/sendmailbox', $page_data);
	}

	public function ReadMail($message_thread_code)
	{
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

		$this->SettingsModel->mark_thread_messages_read($message_thread_code);

		$page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_name'] = 'readMail';
		$page_data['page_active'] = 'ReadMailPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Lire un message reçu';
		$page_data['page_message_thread_code'] = $message_thread_code;

		// Effectuer la journalisation
		    $type_action = 'Consultation' ;

		    $action_effectuee = 'Message reçu' ;

		    $this->control->journalisation($type_action,$action_effectuee) ;
		
		// affichage de la vue

			$this->render_template('kameleon/readMail', $page_data);
	}

	public function ComposeMail()
	{
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

		$users = $this->UserModel->getUser();

		$page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_users'] = $users;
		$page_data['page_name'] = 'composeMail';
		$page_data['page_active'] = 'ReadMailPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_user'] = $this->session->userdata('user_name');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Ecrire un mail';

		// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Ecrire un mail' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;
		
		// affichage de la vue

			$this->render_template('kameleon/composeMail', $page_data);
	}
		
    public function MailRegister()
    {
		if(!empty($_POST))
		{
			$message    = $this->input->post('message');
	        $timestamp  = strtotime(date("Y-m-d H:i:s"));
  			$attachment    = $this->input->post('attachement');

	        $reciever   = $this->input->post('reciever');
   
	        $sender     = $this->session->userdata('user_name');

			$rep = $this->SettingsModel->send_new_private_message();
			
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

			$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'mailbox';
			$page_data['page_active'] = 'ReadMailPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ecrire un mail';

			// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Envoi Mail' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

			$this->render_template('kameleon/mailbox', $page_data);
		}
		else
		{	
		 $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

            $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'composeMail';
			$page_data['page_active'] = 'ReadMailPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ecrire un mail';

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Ecrire un mail' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;
			
			// affichage de la vue

			$this->render_template('kameleon/composeMail', $page_data);
		}
    }

    /* ##################################################################
	----------		PAGE RACINE :: ./ Mailbox/reponse_mail
	################################################################## */

	public function reponse_mail($message_thread_code) 
	{
		$page_data['bandeau'] = 'Mail intranet';

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_name'] = 'reponse_mail';
		$page_data['page_active'] = 'ReadMailPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Répondre à un mail';

		$this->SettingsModel->mark_thread_messages_read($message_thread_code);

		$page_data['page_message_thread_code'] = $message_thread_code;

		$page_data['reponse_info'] = $this->all_model->get_fullrow('message', 'message_thread_code', $message_thread_code);

		// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Reponse à un mail' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue
		$this->render_template('kameleon/reponse_mail', $page_data);
	}

	/* ##################################################################
	----------		PAGE RACINE :: ./ Mailbox/transfert_mail
	################################################################## */

	public function transfert_mail($message_thread_code) 
	{
		$page_data['bandeau'] = 'Mail intranet';

		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

		$users = $this->UserModel->getUser();

		$page_data['page_users'] = $users;

        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_name'] = 'transfert_mail';
		$page_data['page_active'] = 'ReadMailPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Transférer un mail';

		$this->SettingsModel->mark_thread_messages_read($message_thread_code);

		$page_data['page_message_thread_code'] = $message_thread_code;

		$page_data['users'] = $this->all_model->get_table('user');

		$page_data['reponse_info'] = $this->all_model->get_fullrow('message', 'message_thread_code', $message_thread_code);

		// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Transfert de mail' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

		// affichage de la vue
		$this->render_template('kameleon/transfert_mail', $page_data);
	}

	/* ##################################################################
	----------		PAGE RACINE :: ./ Mailbox/save_reponse_mail
	################################################################## */

	public function save_reponse_mail()
    {
		if(!empty($_POST))
		{
			$message    = $this->input->post('message');
	        $timestamp  = strtotime(date("Y-m-d H:i:s"));
  			$attachment    = $this->input->post('attachement');

	        $reciever   = $this->input->post('reciever');

	        $message_thread_code   = $this->input->post('message_thread_code');
   
	        $sender     = $this->session->userdata('user_name');

			$rep = $this->SettingsModel->send_reply_message($message_thread_code);

			$page_data['bandeau'] = 'Mail intranet';
			$page_data['title'] = 'Page de boîte de reception';
			
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	        $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_active'] = 'ReadMailPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Boîte de reception';

			// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Reponse à un mail' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

			$this->render_template('kameleon/mailbox', $page_data);
		}
		
    }

    /* ##################################################################
	----------		PAGE RACINE :: ./ Mailbox/print_mail
	################################################################## */

	public function print_mail($message_thread_code) 
	{

		$page_data['message'] = $this->all_model->get_fullrow('message', 'message_thread_code', $message_thread_code);

		$page_data['print_name'] = 'i_printMail';

		// Effectuer la journalisation
		        $type_action = 'Impression' ;

		        $action_effectuee = 'Mail' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;
	
		$this->load->view("print/infoNet", $page_data);
	}
	
}
	
