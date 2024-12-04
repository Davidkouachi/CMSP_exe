<?php
class Ajax extends Admin_Controller {
    // constructeur
	public function __construct() 
	{
		parent::__construct();
		// chargement divers
		$this->lang->load('sogemad');

		// contrôle d'accès
		/*if (!$this->control->ask_access()) 
		{
			// utilisateur NON authentifié

			$flash_feedback = "Vous n'êtes pas authentifié.";

			$this->session->set_flashdata('warning', $flash_feedback);

			//$curr_uri_string = uri_string();

			$curr_uri_string = $this->uri->segment(1);

			if ($curr_uri_string != 'ajax') 
			{
				redirect('home/login','refresh');
			}

			redirect('home/login','refresh');
		}*/

		/*if($this->control->check_lc() === FALSE)
		{
			$this->session->set_userdata('user_id','');
			$this->session->set_userdata('user_name','');
			$this->session->set_userdata('logged_in',FALSE);

			$flash_feedback = "La licence d'utilisation du logiciel est inactive pour ce poste. Vous pouvez demander une augmentation du nombre de poste de votre licence.";

			$this->session->set_flashdata('warning', $flash_feedback);

			redirect('home/login','refresh');
		}*/

		// Cache control
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');

	}
	
	public function kima(){
	    
	    $infos_compteur = $this->all_model->get_compteur() ;

			  echo $compteur = $infos_compteur['cptnref']; echo '<br/>' ;
			   
			   echo   $date_jour = date('Y-m-d') ;  echo '<br/>' ;
			   
			   $decoupe = explode("-", $date_jour);
			   $annee = $decoupe[0];
			    
			 echo  $annee_coup = substr($annee, 2, 3); echo '<br/>' ;
			   
			   $mois = $decoupe[1];
			   
			  echo $dern_nombre = substr($compteur, 4, 4);  echo '<br/>' ;
			    
			   echo $mois_cpteur = substr($compteur, 2, 2);  echo '<br/>' ;
			   
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
			     
			   echo  $numrecu = "RCE".$annee_coup.$mois.$matn; echo '<br/>' ;
			     
			  echo   $compteur = $annee_coup.$mois.$matn;
	}


	//Connexion
	public function Frmlogin(){
		$Ok = 0;
		$fichier = 'home';
		$msg = 'Une erreur s’est produite, veuillez réessayer plutard !';
		//Requete Read

	$username = $this->input->post('user_username');
	$password = $this->input->post('user_password');


		$user_array = $this->admin_model->get_user_by_name($username);

	    $hash_password = password_verify($password, $user_array[0]->user_password);

	    if (count($user_array)==1 and $hash_password === true) 
		{
			// Voir si l'utilisateur n'est pas connecté ailleur

			$infos_session = $this->all_model->get_table('session') ;

			$user_logged_in = 0 ;

			foreach ($infos_session as $key => $value) {

			    $infos_userdata = unserialize($value['user_data']) ; 

			    if(!empty($infos_userdata['user_name']) && ($infos_userdata['user_name'] == $username) && isset($infos_userdata['logged_in']) && ($infos_userdata['logged_in'] == 1))
			    {
			        $user_logged_in ++ ;
			    }

			}



			//if($user_logged_in > 0) // Utilisateur connecté ailleur
			//{

				/*$Ok = 0;

				$msg = "Votre compte utilisateur est actuellement connecté sur un autre poste. Veuillez trouver ce poste pour vous deconnecter ou contactez votre administrateur pour le faire.";*/
				
				if(!empty($username))
			    	{
			    		$infos_session = $this->all_model->get_table('session') ;

						foreach ($infos_session as $key => $value) {

						        $infos_userdata = unserialize($value['user_data']) ; 

						        if(!empty($infos_userdata['user_name']) && $infos_userdata['user_name'] == $username)
						        {
						        	$infos_userdata['user_name'] = '' ;

						        	$infos_userdata['user_id'] = '' ;

						        	$infos_userdata['logged_out'] = TRUE ;

						        	$infos_userdata['logged_in'] = FALSE ;

						        	$infos_userdata = serialize($infos_userdata) ;

						        	$data = array('user_data' => $infos_userdata);

						        	// Effectuer la journalisation
									    $type_action = 'Suppression' ;

									    $action_effectuee = 'Connexion'.' '.$username ;

									    $donnees_sup = $infos_userdata ;

									    $this->control->journalisation($type_action,$action_effectuee,$donnees_sup) ;

           							$query = $this->all_model->update_ligne('session', $data, 'session_id', $value['session_id']);

						        }
						}
						
						
			    	}

			//}else{ // Utilisateur non connecté ailleur

				$user_status = $user_array[0]->user_actif ;

				if($user_status == 1)
				{
					$infos_profil = $this->all_model->get_fullrow('profile','idprofile',$user_array[0]->user_profil_id);

					if(empty($infos_profil))
					{
						$Ok = 0;

						$msg = "Votre profil n'est pas correctement paramétré. Veuillez contacter votre administrateur.";

					}else{

							/*if($this->control->check_lc() === FALSE)
							{
								$Ok = 0;

								$msg = "Erreur au niveau de la licence d'utilisation du logiciel.";

							}else{*/

									// écriture des infos user dans sa ligne de la table session

									$this->session->set_userdata('user_id', $user_array[0]->user_id);
									$this->session->set_userdata('user_name', $user_array[0]->user_username);
									$this->session->set_userdata('user_profil', $user_array[0]->user_profil_id);
									$this->session->set_userdata('user_photo', $user_array[0]->user_photo);
									$this->session->set_userdata('user_first_name', $user_array[0]->user_first_name);
									$this->session->set_userdata('user_last_name', $user_array[0]->user_last_name);
									$this->session->set_userdata('user_email', $user_array[0]->user_email);
									$this->session->set_userdata('user_rights', $user_array[0]->user_rights);
									
									$this->session->set_userdata('user_phone', $user_array[0]->user_phone);
									$this->session->set_userdata('code_user_perso', $user_array[0]->code_personnel);
									$this->session->set_userdata('tab_active', 1);

									$this->session->set_userdata('profil_photo',$user_array[0]->photo) ;

									// incrémentation du champ user_logs de la table user

									$this->all_model->increment_field('user', 'user_logs', 'user_id', $user_array[0]->user_id, 1);

									// limite de temps

									$just_now = date ("U");
									$time_limit = $just_now + $this->config->item('sess_expiration') ;

									$this->session->set_userdata('time_limit', $time_limit);

									$session_data = array('logged_in' => TRUE);

									$this->session->set_userdata($session_data);

									$this->session->set_userdata('logged_in', 1) ;

									// Effectuer la journalisation
							        $type_action = 'Connexion' ;

							        $action_effectuee = '' ;

							        $this->control->journalisation($type_action,$action_effectuee) ;

									$Ok = 1;
//exit();
							//}
					}
					
				}
				elseif($user_status == 0)
				{
					$Ok = 0;

					$msg = "Votre compte utilisateur a été désactivé.";
				}

			//}

			

		} 
		else 
		{
			$Ok = 0;
		 	$msg = 'Paramètres de connexion incorrects.';
		}

		echo $Ok.'|'.$msg.'|'.$fichier;
	}

	//Change photo profil
	public function Imgprofil(){
		$id = $this->input->post('id');
		//Requete Read
		$infos_user = $this->all_model->get_fullrow('user','user_username',$id) ;

		if(!empty($infos_user))
		{
			if($infos_user['photo'] == '')
			{
				$photo = 'default-img.png'; 
			}else{
				$photo = $infos_user['photo'];
			} 
		}
		
		$this->data['id'] = $id;
		$this->data['photo'] = $photo;
		$this->load->view('templates/modal-form/imgprofil', $this->data);
	}

	//Change photo profil
	public function Reinitialise(){
		$id = $this->input->post('id');
		
		$this->data['id'] = $id;
		$this->load->view('templates/modal-form/reset_password', $this->data);
	}

	//Change fiche consultation
	public function Addfiche(){

		$id = $this->input->post('id');

		if($id != 0){
			
			$infos_consultation = $this->all_model->get_fullrow('consultation','idconsexterne',$id) ;

			if(!empty($infos_consultation))
			{
				$photo = $infos_consultation['fiche'];
			}else{

				$photo == '' ;
			}
			
		}
		

		if($photo == '') 
		{
			$photo = 'fiche_consultation/default.jpg';
			$this->data['photo'] = $photo;
			$title = 'Ajout';
			$value = 'Valider';

		}else{

		 $photo = 'fiche_consultation/'.$photo;
		 $this->data['photo'] = $photo;

		 $value = 'Modifier';
		 $title = 'Modification';

		}

		$this->data['id'] = $id;
		$this->data['title'] = $title;
		$this->data['value'] = $value;

		//$this->data['addvalue'] = 'imgfichecons';
		
		$this->load->view('templates/ficheconsshow', $this->data);
	}
	
	public function Addconstante(){

		$id = $this->input->post('id');

		$infos_consultation = $this->all_model->get_fullrow('consultation','idconsexterne',$id) ; 

	      if(!empty($infos_consultation))
	      {
	        $numfac = $infos_consultation['numfac'] ;

	        $infos_constante = $this->all_model->get_fullrow('constante','numfac',$numfac) ;

	        if(!empty($infos_constante))
	        {
	          $value = 'Modifier';
		 	  $title = 'Modification';

	        }else{

	          $value = 'Valider';
		 	  $title = 'Ajout';
	        }

	      }else{

	      	$value = 'Valider';
		 	  $title = 'Ajout';
	        
	      }

		$this->data['id'] = $id;
		$this->data['title'] = $title;
		$this->data['value'] = $value;
		
		$this->load->view('templates/ficheconstantes', $this->data);
	}
	
	public function Addencaissecons(){

		$id = $this->input->post('id');

	    $value = 'Encaisser';
		$title = 'Encaisser';
	        
		$this->data['id'] = $id;
		$this->data['title'] = $title;
		$this->data['value'] = $value;
		
		$this->load->view('templates/encaisserconsultation', $this->data);
	}

	public function Addencaissebiologie(){

		$id = $this->input->post('id');

	    $value = 'Encaisser';
		$title = 'Encaisser';
	        
		$this->data['id'] = $id;
		$this->data['title'] = $title;
		$this->data['value'] = $value;
		
		$this->load->view('templates/encaisserbiologie', $this->data);
	}

	public function Addencaissesoin(){

		$id = $this->input->post('id');

	    $value = 'Encaisser';
		$title = 'Encaisser';
	        
		$this->data['id'] = $id;
		$this->data['title'] = $title;
		$this->data['value'] = $value;
		
		$this->load->view('templates/encaissersoin', $this->data);
	}

	public function Addencaisseadmission(){

		$id = $this->input->post('id');

	    $value = 'Encaisser';
		$title = 'Encaisser';
	        
		$this->data['id'] = $id;
		$this->data['title'] = $title;
		$this->data['value'] = $value;
		
		$this->load->view('templates/encaisseradmission', $this->data);
	}

	
	
	public function AddMedecinVisite(){

		$id = $this->input->post('id');

	    $value = 'Valider';
		$title = 'Ajout';

		$this->data['medecins'] = $this->all_model->get_table('medecin') ;

		$this->data['id'] = $id;
		$this->data['title'] = $title;
		$this->data['value'] = $value;
		
		$this->load->view('templates/fichemedecinvisite', $this->data);
	}


	//Delete
	public function Status(){
		$id = $this->input->post('id');
        $typ = $this->input->post('typ');
        $val = $this->input->post('val');

		$msg = 'Une erreur s’est produite, veuillez réessayer plutard !';
			
        switch($typ){
    		case 'Facture':
				
    				if(!empty($id))
			    	{
			    		$numfac = $id ;

			    		$infos_caisse = $this->all_model->get_fullrow('caisse','nopiece',$numfac);

			    		if(!empty($infos_caisse))
			    		{
			    			$msg = "La facture que vous voulez supprimer a déjà été encaissée, il est donc impossible de faire cette opération.";

			    			$val = 0 ;
			    		}
			    		else
			    		{
			    			$infos_facture = $this->all_model->get_fullrow('factures','numfac',$numfac);

			    			if(!empty($infos_facture))
			    			{
			    				$table = 'factures';

				    			$id_name = 'numfac';

				    			$id = $numfac;

				    			// Effectuer la journalisation
							        $type_action = 'Suppression' ;

							        $action_effectuee = 'Facture'.' '.$numfac ;

							        $donnees_sup = serialize($infos_facture) ;

							        $this->control->journalisation($type_action,$action_effectuee,$donnees_sup) ;

							    // Suppression

				    			$this->all_model->delete_ligne($table, $id_name, $id);

				    			if($infos_facture['type_facture'] == 1)
				    			{
				    				$table_1 = 'consultation';

					    			$id_name_1 = 'numfac';

					    			$id_1 = $numfac;

					    			$infos_cons = $this->all_model->get_fullrow('consultation','numfac',$numfac);

					    			// Effectuer la journalisation
							        $type_action = 'Suppression'.' '.$id ;

							        $action_effectuee = 'Consultation clinique' ;

							        $donnees_sup = serialize($infos_cons) ;

							        $this->control->journalisation($type_action,$action_effectuee,$donnees_sup) ;


				    				$this->all_model->delete_ligne($table_1, $id_name_1, $id_1);

				    				$msg = "La facture de consultation a été supprimée avec succès dans la base de données.";

				    				$val = 3 ;
				    			}

				    			if($infos_facture['type_facture'] == 2)
				    			{
				    				$table_2 = 'testlaboimagerie';

					    			$id_name_2 = 'numfacbul';

					    			$id_2 = $numfac;

					    			$infos_testlabo = $this->all_model->get_fullrow($table_2,$id_name_2,$id_2);

					    			// Effectuer la journalisation
							        $type_action = 'Suppression' ;

							        $action_effectuee = 'Biologie/Imagerie'.' '.$id_2 ;

							        $donnees_sup = serialize($infos_testlabo) ;

							        $this->control->journalisation($type_action,$action_effectuee,$donnees_sup) ;

									$this->all_model->delete_lignes('detailtestlaboimagerie', 'idtestlaboimagerie', $infos_testlabo['idtestlaboimagerie']);
									
									$this->all_model->delete_ligne($table_2, $id_name_2, $id_2);

									$msg = "La facture de biologie ou d'imagerie a été supprimée avec succès dans la base de données.";

									$val = 3 ;

				    			}

				    			if($infos_facture['type_facture'] == 3)
				    			{
				    				$table_3 = 'admission';

					    			$id_name_3 = 'numfachospit';

					    			$id_3 = $numfac;

					    			$infos_admission = $this->all_model->get_fullrow($table_3,$id_name_3,$id_3);

					    			// Effectuer la journalisation
							        $type_action = 'Suppression' ;

							        $action_effectuee = 'Admission'.' '.$id_3 ;

							        $donnees_sup = serialize($infos_admission) ;

							        $this->control->journalisation($type_action,$action_effectuee,$donnees_sup) ;

							        // Suppression

					    			$this->all_model->delete_ligne($table_3, $id_name_3, $id_3);

									$this->all_model->delete_lignes('facturation_hospit', 'numpchr', $infos_admission['numhospit']);

									$msg = "La facture d'admission a été supprimée avec succès dans la base de données.";

									$val = 3 ;

				    			}

				    			if($infos_facture['type_facture'] == 4)
				    			{
				    				$table_4 = 'soins_medicaux';

					    			$id_name_4 = 'numfac_soins';

					    			$id_4 = $numfac;

					    			$infos_soins = $this->all_model->get_fullrow($table_4,$id_name_4,$id_4);
					    			
					    			$infos_facture = $this->all_model->get_fullrow('factures','numfac',$id_4);
					    			
					    			if(!empty($infos_facture))
					    			{
					    			    if($infos_facture(['montantregle_pat']) >= $infos_facture(['montant_pat']))
					    			    {
					    			        $msg = "La facture que vous voulez supprimer a déjà été encaissée, il est donc impossible de faire cette opération.";

			    			                $val = 0 ;
					    			        
					    			    }
					    			    else{
					    			        
    					    			   // Effectuer la journalisation
        							        $type_action = 'Suppression' ;
        
        							        $action_effectuee = 'Soins infirmier'.' '.$id_4 ;
        
        							        $donnees_sup = serialize($infos_soins) ;
        
        							        $this->control->journalisation($type_action,$action_effectuee,$donnees_sup) ;
        
        							        //Suppression
        
        					    			$this->all_model->delete_ligne($table_4, $id_name_4, $id_4);
        
        									$this->all_model->delete_lignes('soins_medicaux_itemmedics', 'id_soins', $infos_soins['id_soins']);
        
        									$this->all_model->delete_lignes('soins_medicaux_itemsoins', 'id_soins', $infos_soins['id_soins']);
        
        									$msg = "La facture de soins infirmier a été supprimée avec succès dans la base de données.";
        
        									$val = 3 ; 
					    			    }
					    			}

					    			

				    			}
			    			}
			    			
			    		}

			    	}
			    	else
			    	{
			    		// Effectuer la journalisation
							$type_action = 'Echec suppression' ;

							$action_effectuee = 'Facture' ;

							$this->control->journalisation($type_action,$action_effectuee) ;

			    		$msg = "Aucune facture n'a été sélectionnée.";

			    		$val = 0 ;
			    	}

    		break;
    		case 'Patient':
				
    				if(!empty($id))
			    	{
			    		$id_name = 'idenregistremetpatient' ;

						$infos_consultation =  $this->all_model->get_fullrow('consultation', $id_name, $id) ;

						$infos_labo =  $this->all_model->get_fullrow('testlaboimagerie', $id_name, $id) ;

						$infos_admission =  $this->all_model->get_fullrow('admission', $id_name, $id) ;

						$infos_factures =  $this->all_model->get_fullrow('factures', $id_name, $id) ;

						$msg = '';

						if(!empty($infos_consultation))
						{
							
							$msg .= "Désolé ! Suppression impossible car le numéro d'identification du patient a été déjà utilisé pour faire une facture de consultation.";

							$val = 0 ;
						}

						if(!empty($infos_labo))
						{	
							
							$msg .= "Désolé ! Suppression impossible car le numéro d'identification du patient a été déjà utilisé pour faire une facture d'imagerie ou de biologie.";

							$val = 0 ;
						}

						if(!empty($infos_admission))
						{
							$msg .= "Désolé ! Suppression impossible car le numéro d'identification du patient a été déjà utilisé pour faire une facture d'admission.";

							$val = 0 ;
						}

						if(!empty($infos_factures))
						{
							$msg .= "Désolé ! Suppression impossible car le numéro d'identification du patient a été déjà utilisé pour faire une facture." ;

							$val = 0 ;
						}

						if($val == 0){

							// Effectuer la journalisation
							$type_action = 'Echec suppression' ;

							$action_effectuee = 'Patient' ;

							$this->control->journalisation($type_action,$action_effectuee) ;
						}

						// APPEL DU MODEL ADEQUAT POUR LA SUPPRESSION
						if(empty($infos_consultation) && empty($infos_labo) && empty($infos_admission) && empty($infos_factures))
						{
							$infos_patient =  $this->all_model->get_fullrow('patient', $id_name, $id) ;

							// Effectuer la journalisation
							    $type_action = 'Suppression' ;

							    $action_effectuee = 'Patient'.' '.$id ;

							    $donnees_sup = serialize($infos_patient) ;

							    $this->control->journalisation($type_action,$action_effectuee,$donnees_sup) ;

							$this->PatientModel->PatientDeleter($id);

							$msg = "Le patient a été supprimé avec succès." ;

							$val = 1 ;
						}

			    	}
			    	else
			    	{
			    		// Effectuer la journalisation
							$type_action = 'Echec suppression' ;

							$action_effectuee = 'Patient' ;

							$this->control->journalisation($type_action,$action_effectuee) ;

			    		$msg = "Aucune facture n'a été sélectionnée.";

			    		$val = 0 ;
			    	}

    		break;

    		case 'Profile':
				
    				if(!empty($id))
			    	{
			    		$id_name = 'user_profil_id' ;

						$infos_users =  $this->all_model->get_fullrow('user', $id_name, $id) ;

						$msg = '';

						if(!empty($infos_users))
						{
							// Effectuer la journalisation
							$type_action = 'Echec suppression' ;

							$action_effectuee = 'Profil Utilisateur' ;

							$this->control->journalisation($type_action,$action_effectuee) ;

							$msg .= "Désolé ! Suppression impossible car ce profil a été déjà attribué à un utilisateur.";

							$val = 0 ;
						}

						// APPEL DU MODEL ADEQUAT POUR LA SUPPRESSION
						if(empty($infos_users))
						{
							$table = 'profile' ;

							$id_name = 'idprofile' ;

							$infos_profil =  $this->all_model->get_fullrow($table, $id_name, $id) ;

							// Effectuer la journalisation
							    $type_action = 'Suppression' ;

							    $action_effectuee = 'Profil'.' '.$id ;

							    $donnees_sup = serialize($infos_profil) ;

							    $this->control->journalisation($type_action,$action_effectuee,$donnees_sup) ;

							$this->all_model->delete_ligne($table, $id_name, $id);

							$msg = "Le profil a été supprimé avec succès." ;

							$val = 1 ;
						}

			    	}

    		break;

    		case 'Connexion':
				
    				if(!empty($id))
			    	{
			    		$infos_session = $this->all_model->get_table('session') ;

						foreach ($infos_session as $key => $value) {

						        $infos_userdata = unserialize($value['user_data']) ; 

						        if(!empty($infos_userdata['user_name']) && $infos_userdata['user_name'] == $id)
						        {
						        	$infos_userdata['user_name'] = '' ;

						        	$infos_userdata['user_id'] = '' ;

						        	$infos_userdata['logged_out'] = TRUE ;

						        	$infos_userdata['logged_in'] = FALSE ;

						        	$infos_userdata = serialize($infos_userdata) ;

						        	$data = array('user_data' => $infos_userdata);

						        	// Effectuer la journalisation
									    $type_action = 'Suppression' ;

									    $action_effectuee = 'Connexion'.' '.$id ;

									    $donnees_sup = $infos_userdata ;

									    $this->control->journalisation($type_action,$action_effectuee,$donnees_sup) ;

           							$query = $this->all_model->update_ligne('session', $data, 'session_id', $value['session_id']);

						        }
						}

						$msg = "L'utilisateur a été déconnecté avec succès." ;

						$val = 1 ;
			    	}

    		break;

    		case 'Historique':
				
    				if(!empty($id))
			    	{
			    		$infos_journalisation = $this->all_model->get_fullrow('journalisation_actions','user_username',$id) ;


						        $infos_historique = serialize($infos_journalisation) ;

						        	$table = 'journalisation_actions' ;

						        	$id_name = 'user_username' ;

           							$this->all_model->delete_lignes($table, $id_name, $id);

           							$this->session->set_userdata('tab_active',4) ;

           							// Effectuer la journalisation
									    $type_action = 'Suppression' ;

									    $action_effectuee = 'Historique de l\'utilisateur'.' '.$id ;

									    $donnees_sup = $infos_historique ;

									    $this->control->journalisation($type_action,$action_effectuee,$donnees_sup) ;
						

						$msg = "Historique de l'utilisateur"." ".$id." a été supprimée avec succès" ;

						$val = 1 ;
			    	}

    		break;

    		case 'AllHistorique':
				
    				if(!empty($id))
			    	{
			    		$infos_journalisation = $this->all_model->get_table('journalisation_actions') ;


						        $infos_historique = serialize($infos_journalisation) ;

						        	$table = 'journalisation_actions' ;

						        	$id_name = 'user_username' ;

           							$this->db->truncate($table);

           							// Effectuer la journalisation
									    $type_action = 'Suppression' ;

									    $action_effectuee = 'Historique de tous les utilisateurs' ;

									    $donnees_sup = $infos_historique ;

									    $this->control->journalisation($type_action,$action_effectuee,$donnees_sup) ;
						

						$msg = "L'historique de tous les utilisateurs a été supprimée avec succès" ;

						$val = 1 ;
			    	}

    		break;

    		case 'User':
				
    				if(!empty($id))
			    	{
			    		$table = 'user';

						$id_name = 'user_id' ;

						$infos_user = $this->all_model->get_fullrow($table,$id_name,$id);

						$retour = $this->all_model->delete_ligne($table, $id_name, $id);

						if($retour == 1)
						{
							// Effectuer la journalisation
							$type_action = 'Suppression' ;

							$action_effectuee = 'Utilisateur'.' '.$id ;

							$donnees_sup = serialize($infos_user) ;

							$this->control->journalisation($type_action,$action_effectuee,$donnees_sup) ;

							$msg = "L'utilisateur"." ".$id." "."a été supprimé avec succès.";

							$val = 1 ;
						}
						else
						{
							$msg = "Impossible de supprimer cet utilisateur.";

							$val = 3 ;
						}

						
			    	}

    		break;

    		case 'Antecedent':
				
    				if(!empty($id))
			    	{
			    		$table = 'antecedents';

						$id_name = 'id' ;

						$infos_antecedent = $this->all_model->get_fullrow($table,$id_name,$id);

						$retour = $this->all_model->delete_ligne($table, $id_name, $id);

						if($retour == 1)
						{
							// Effectuer la journalisation
							$type_action = 'Suppression' ;

							$action_effectuee = 'Antecedent'.' '.$id ;

							$donnees_sup = serialize($infos_antecedent) ;

							$this->control->journalisation($type_action,$action_effectuee,$donnees_sup) ;

							$msg = "L'antécédent"." ".$id." "."a été supprimé avec succès.";

							$val = 1 ;
						}
						else
						{
							$msg = "Impossible de supprimer cet utilisateur.";

							$val = 3 ;
						}
			    	}

    		break;

    		case 'Appel':
				
    				if(!empty($id))
			    	{
			    		$table = 'appels_telephonique';

						$id_name = 'id' ;

						$infos_appel = $this->all_model->get_fullrow($table,$id_name,$id);

						$retour = $this->all_model->delete_ligne($table, $id_name, $id);

						if($retour == 1)
						{
							// Effectuer la journalisation
							$type_action = 'Suppression' ;

							$action_effectuee = 'Appel'.' '.$id ;

							$donnees_sup = serialize($infos_appel) ;

							$this->control->journalisation($type_action,$action_effectuee,$donnees_sup) ;

							$msg = "L'appel"." ".$id." "."a été supprimé avec succès.";

							$val = 1 ;
						}
						else
						{
							$msg = "Impossible de supprimer cet appel.";

							$val = 3 ;
						}
			    	}

    		break;
    		
    		case 'Specialite':
			
    				if(!empty($id))
			    	{
			    		$id_name = 'codespecialitemed' ;

			    		$table = 'specialitemed';

						$infos_medecin =  $this->all_model->get_fullrow('medecin', $id_name, $id) ;

						$msg = '';

						if(!empty($infos_medecin))
						{
							
							$msg .= "Désolé ! Suppression impossible car cette spécialité a été déjà rattachée à un médecin.";

							$val = 0 ;
						}

						if($val == 0){

							// Effectuer la journalisation
							$type_action = 'Echec suppression' ;

							$action_effectuee = 'Spécialité' ;

							$this->control->journalisation($type_action,$action_effectuee) ;
						}

						// APPEL DU MODEL ADEQUAT POUR LA SUPPRESSION
						if(empty($infos_medecin))
						{
							$infos_specialite =  $this->all_model->get_fullrow('specialitemed', $id_name, $id) ;

							// Effectuer la journalisation
							    $type_action = 'Suppression' ;

							    $action_effectuee = 'Spécialité'.' '.$id ;

							    $donnees_sup = serialize($infos_specialite) ;

							    $this->control->journalisation($type_action,$action_effectuee,$donnees_sup) ;

							$retour = $this->all_model->delete_ligne($table, $id_name, $id);

							$msg = "La spécialité a été supprimée avec succès." ;

							$val = 1 ;
						}

			    	}
			    	else
			    	{

			    		// Effectuer la journalisation
							$type_action = 'Echec suppression' ;

							$action_effectuee = 'Spécialité' ;

							$this->control->journalisation($type_action,$action_effectuee) ;

			    		$msg = "Aucune spécialité n'a été sélectionnée.";

			    		$val = 0 ;
			    	}

    		break;
    		
    		case 'Garantie':
				
    				if(!empty($id))
			    	{
			    		$id_name = 'codgaran' ;
			    		
			    		$table = 'garantie';
			    	
						$infos_consultation =  $this->all_model->get_fullrow('consultation', 'codeacte', $id) ;
						
						$infos_tarifs =  $this->all_model->get_fullrow('tarifs', $id_name, $id) ;

						$msg = '';

						if(!empty($infos_consultation))
						{
							
							$msg .= "Désolé ! Suppression impossible car le code de l'acte a été déjà utilisé pour faire une facture de consultation.";

							$val = 0 ;
						}
						
						if(!empty($infos_tarifs))
						{
							
							$msg .= "Désolé ! Suppression impossible car le code de l'acte a été déjà utilisé pour faire le paramétrage des tarifs.";

							$val = 0 ;
						}

						if($val == 0){

							// Effectuer la journalisation
							$type_action = 'Echec suppression' ;

							$action_effectuee = 'Garantie' ;

							$this->control->journalisation($type_action,$action_effectuee) ;
						}

						// APPEL DU MODEL ADEQUAT POUR LA SUPPRESSION
						if(empty($infos_consultation) && empty($infos_tarifs))
						{
							$infos_garantie =  $this->all_model->get_fullrow('garantie', $id_name, $id) ;

							// Effectuer la journalisation
							    $type_action = 'Suppression' ;

							    $action_effectuee = 'Garantie'.' '.$id ;

							    $donnees_sup = serialize($infos_garantie) ;

							    $this->control->journalisation($type_action,$action_effectuee,$donnees_sup) ;

								$retour = $this->all_model->delete_ligne($table, $id_name, $id);

							$msg = "L'acte a été supprimé avec succès." ;

							$val = 1 ;
						}

			    	}
			    	else
			    	{
			    		// Effectuer la journalisation
							$type_action = 'Echec suppression' ;

							$action_effectuee = 'Garantie' ;

							$this->control->journalisation($type_action,$action_effectuee) ;

			    		$msg = "Aucune facture n'a été sélectionnée.";

			    		$val = 0 ;
			    	}

    		break;
    		
    		case 'Admission':
				
    				if(!empty($id))
			    	{
			    		$numhospit = $id ;

			    		$infos_admission = $this->all_model->get_fullrow('admission','numhospit',$numhospit);

			    		if(!empty($infos_admission))
			    		{
			    			$msg = "L'admission que vous voulez supprimer est liée a une facture qui a déjà été encaissée, il est donc impossible de faire cette opération.";

			    			$val = 0 ;
			    		}
			    		else
			    		{
			    			$infos_facture = $this->all_model->get_fullrow('factures','numfac',$infos_admission['numfachospit']);

			    			if(!empty($infos_facture))
			    			{
			    				$table = 'factures';

				    			$id_name = 'numfac';

				    			$id = $numfac;

				    			// Effectuer la journalisation
							        $type_action = 'Suppression' ;

							        $action_effectuee = 'Facture'.' '.$numfac ;

							        $donnees_sup = serialize($infos_facture) ;

							        $this->control->journalisation($type_action,$action_effectuee,$donnees_sup) ;

							    // Suppression

				    			$this->all_model->delete_ligne($table, $id_name, $id);

				    			$table_2 = 'orders';

					    		$id_name_2 = 'num_hospit';

					    		$id_2 = $numhospit;

					    		$infos_orders = $this->all_model->get_fullrow($table_2,$id_name_2,$id_2);

					    			// Effectuer la journalisation
							    $type_action = 'Suppression' ;

							    $action_effectuee = 'Orders'.' '.$id_2 ;

							    $donnees_sup = serialize($infos_admission) ;

							    $this->control->journalisation($type_action,$action_effectuee,$donnees_sup) ;

							        // Suppression

					    		$this->all_model->delete_ligne($table_2, $id_name_2, $id_2);


				    			$table_3 = 'admission';

					    		$id_name_3 = 'numfachospit';

					    		$id_3 = $numfac;

					    		$infos_admission = $this->all_model->get_fullrow($table_3,$id_name_3,$id_3);

					    			// Effectuer la journalisation
							    $type_action = 'Suppression' ;

							    $action_effectuee = 'Admission'.' '.$id_3 ;

							    $donnees_sup = serialize($infos_admission) ;

							    $this->control->journalisation($type_action,$action_effectuee,$donnees_sup) ;

							        // Suppression

					    		$this->all_model->delete_ligne($table_3, $id_name_3, $id_3);

								$this->all_model->delete_lignes('facturation_hospit', 'numpchr', $infos_admission['numhospit']);

								$table_4 = 'bed_allotment';

					    		$id_name_4 = 'num_hospit';

					    		$id_4 = $numhospit;

					    		$infos_bed_allotment = $this->all_model->get_fullrow($table_4,$id_name_4,$id_4);

					    			// Effectuer la journalisation
							    $type_action = 'Suppression' ;

							    $action_effectuee = 'Bed allotment'.' '.$id_4 ;

							    $donnees_sup = serialize($infos_bed_allotment) ;

							    $this->control->journalisation($type_action,$action_effectuee,$donnees_sup) ;

							        // Suppression

					    		$this->all_model->delete_ligne($table_4, $id_name_4, $id_4);


								$msg = "L'admission a été supprimée avec succès dans la base de données.";

								$val = 3 ;
			    			}else{

			    				$msg = "L'admission a été supprimée avec succès dans la base de données.";

								$val = 3 ;
			    			}
			    			
			    		}

			    	}
			    	else
			    	{
			    		// Effectuer la journalisation
							$type_action = 'Echec suppression' ;

							$action_effectuee = 'Admission' ;

							$this->control->journalisation($type_action,$action_effectuee) ;

			    		$msg = "Aucune Admission n'a été sélectionnée.";

			    		$val = 0 ;
			    	}

    		break;
    		
    		case 'FactureExamen':
				
    				if(!empty($id))
			    	{
			    		$infos_testlabo = $this->all_model->get_fullrow('testlaboimagerie','idtestlaboimagerie',$id);

			    		if(!empty($infos_testlabo))
			    		{
			    			$numfac = $infos_testlabo['numfacbul'] ;
			    		
			    			$infos_caisse = $this->all_model->get_fullrow('caisse','nopiece',$numfac);

				    		if(!empty($infos_caisse))
				    		{
				    			$msg = "La facture que vous voulez supprimer a déjà été encaissée, il est donc impossible de faire cette opération.";

				    			$val = 0 ;
				    		}
				    		else
				    		{
				    			$infos_facture = $this->all_model->get_fullrow('factures','numfac',$numfac);

				    			if(!empty($infos_facture))
				    			{
				    				$table = 'factures';

					    			$id_name = 'numfac';

					    			$id = $numfac;

					    			// Effectuer la journalisation
								        $type_action = 'Suppression' ;

								        $action_effectuee = 'Facture'.' '.$numfac ;

								        $donnees_sup = serialize($infos_facture) ;

								        $this->control->journalisation($type_action,$action_effectuee,$donnees_sup) ;

								    // Suppression

					    			$this->all_model->delete_ligne($table, $id_name, $id);

					    			if($infos_facture['type_facture'] == 2)
					    			{
					    				$table_2 = 'testlaboimagerie';

						    			$id_name_2 = 'numfacbul';

						    			$id_2 = $numfac;

						    			$infos_testlabo = $this->all_model->get_fullrow($table_2,$id_name_2,$id_2);

						    			// Effectuer la journalisation
								        $type_action = 'Suppression' ;

								        $action_effectuee = 'Biologie/Imagerie'.' '.$id_2 ;

								        $donnees_sup = serialize($infos_testlabo) ;

								        $this->control->journalisation($type_action,$action_effectuee,$donnees_sup) ;

										$this->all_model->delete_lignes('detailtestlaboimagerie', 'idtestlaboimagerie', $infos_testlabo['idtestlaboimagerie']);
										
										$this->all_model->delete_ligne($table_2, $id_name_2, $id_2);

										$msg = "La facture de biologie ou d'imagerie a été supprimée avec succès dans la base de données.";

										$val = 3 ;

					    			}

				    			}
				    			
				    		}
				    		
			    		}else{

					    		// Effectuer la journalisation
							$type_action = 'Echec suppression' ;

							$action_effectuee = 'Facture' ;

							$this->control->journalisation($type_action,$action_effectuee) ;

					    	$msg = "Aucune facture n'a été sélectionnée.";

					    	$val = 0 ;
			    		}
			    	}
			    	else
			    	{
			    		// Effectuer la journalisation
							$type_action = 'Echec suppression' ;

							$action_effectuee = 'Facture' ;

							$this->control->journalisation($type_action,$action_effectuee) ;

			    		$msg = "Aucune facture n'a été sélectionnée.";

			    		$val = 0 ;
			    	}

    		break;



        	default: break;
		}
        if($val == 2) $msg = 'Donnée supprimée avec succès';
        echo $val.'|'.$msg;
    }
	//Modification
	public function AddModData(){
		//print_r($_POST);
		 //exit();
	  	$i = $j = $Ok = 0;
	  	$class = '';
		$date = date('Y-m-d H:i:s');
		$id = $this->input->post('id');
        $btn = $this->input->post('btn');
		$delfile = $filename = '';
		$msg = 'Une erreur s’est produite, veuillez réessayer plutard !';
      	if(isset($_FILES['file']['name'])){
      		$tmp = $_FILES['file']['tmp_name'];
      		$filename = $_FILES['file']['name'];
      		$ext = pathinfo(strtolower($filename), PATHINFO_EXTENSION);

			//$file = date('YmdHis').'.'.$ext;

			$file = $id.'.'.$ext;

			$config = array(
			    'overwrite' => true,
			    'file_name' => $file
			);
      	}

      	
        switch($btn){

    		case 'imgfichecons':
				//Librairies
		    	$config['allowed_types'] = 'png|jpg|jpeg|PNG|JPG|JPEG';
				$config['upload_path'] = 'uploads/fiche_consultation/';
				$this->load->library('upload', $config);
				if($this->upload->do_upload('file')){

					// Effectuer la journalisation
						$type_action = 'Téléchargement' ;

						$action_effectuee = 'Fiche de consultation' ;

						$this->control->journalisation($type_action,$action_effectuee) ;

	  	  			$Ok = 1;

	  	  			$data = array('fiche' => $file);

           			$query = $this->all_model->update_ligne('consultation', $data, 'idconsexterne', $id);

					$msg = 'Image de la fiche de consultation enregistrée avec succès';

					$session_data = array('fiche_ajoute' => $file);
					$this->session->set_userdata($session_data);

				}else $msg = $this->upload->display_errors('<span>', '<span>');

    		break;

    		case 'password':

        		$class = '.password';

				$Ok = 1;

				$user_password = $this->control->password_hash($this->input->post('new_password'));

		        $data = array('user_password' => $user_password);

		        $table = 'user' ;

		        $id_name = "user_username";

		        $query = $this->all_model->update_ligne($table, $data, $id_name, $id) ;

		        if($query > 0)
		        {
		        	// Effectuer la journalisation
					$type_action = 'Modification' ;

					$action_effectuee = 'Mot de passe' ;

					$this->control->journalisation($type_action,$action_effectuee) ;

		        	$msg = 'Mot de passe modifié avec succès.';

		        }else{

		        	$msg = 'Aucune modification n\'a été effectuée.';
		        }
				
    		break;

    		case 'password_mod':

        		$class = '.password';

        		//$id = $this->input->post('id_2');

				$user_array = $this->admin_model->get_user_by_name($id);

				$old_password = $this->input->post('old_password') ;

				$hash_password = password_verify($old_password, $user_array[0]->user_password);

				if(count($user_array)==1 and $hash_password === true)  
				{
					$user_password = $this->control->password_hash($this->input->post('new_password'));

			        $data = array('user_password' => $user_password);

			        $table = 'user' ;

			        $id_name = "user_username";

			        $query = $this->all_model->update_ligne($table, $data, $id_name, $id) ;

			        $this->session->set_userdata('tab_active',3) ;

			        if($query > 0)
			        {
			        	// Effectuer la journalisation
							$type_action = 'Modification' ;

							$action_effectuee = 'Mot de passe' ;

							$this->control->journalisation($type_action,$action_effectuee) ;

			        	$Ok = 1;

			        	$msg = 'Mot de passe modifié avec succès.';

			        }else{

			        	$Ok = 0;
			        	$msg = 'Aucune modification n\'a été effectuée.';
			        }

				}else{

					// Effectuer la journalisation
						$type_action = 'Echec Modification' ;

						$action_effectuee = 'Mot de passe' ;

						$this->control->journalisation($type_action,$action_effectuee) ;

					$Ok = 0 ;
					$msg = 'Vous avez certainement oublié votre ancien mot de passe.';
				}

				
				
    		break;

    		case 'imgprofil':
				//Librairies
		    	$config['allowed_types'] = 'png|jpg|jpeg|PNG|JPG|JPEG';
				$config['upload_path'] = 'uploads/user-photo/';
				$this->load->library('upload', $config);
				if($this->upload->do_upload('file')){
	  	  			$Ok = 1;

	  	  			$data = array('user_photo' => 1,'photo' =>$file);

					// Requête de modification

					$affected_rows = $this->all_model->update_ligne('user', $data, 'user_username', $id);

					$this->session->set_userdata('tab_active',2) ;

					if($this->session->userdata('user_username') == $id)
					{
						$this->session->set_userdata('user_photo',1) ;
						$this->session->set_userdata('profil_photo',$file) ;
					}

					$msg = 'Photo de profil modifiée avec succès';

					// Effectuer la journalisation
						$type_action = 'Modification' ;

						$action_effectuee = 'Photo de profil' ;

						$this->control->journalisation($type_action,$action_effectuee) ;

				}else $msg = $this->upload->display_errors('<span>', '<span>');
    		break;

    		case 'Password_reset':
				
    				if(!empty($id))
			    	{
			    		$user_password		= $this->control->password_hash($this->config->item('appli_provisory_password'));

						$data = array('user_password' => $user_password);

						// première requête de modification

						$affected_rows = $this->all_model->update_ligne('user', $data, 'user_id', $id);

							if ($affected_rows == 1) 
							{
								// message de confirmation
						        $msg = 'Le mot de passe du compte'.' '.$id .' '.'a été réinitialisé avec succès. Son nouveau mot de passe est :'.' '. $this->config->item('appli_provisory_password');
							} 
							else
							{
						        $msg = lang('info_no_update');
							}
						$Ok = 1;

						// Effectuer la journalisation
							$type_action = 'Réinitialisation' ;

							$action_effectuee = 'Mot de passe de l\'utilisateur'.' '.$id ;

							$this->control->journalisation($type_action,$action_effectuee) ;
			    	}

    		break;

    		case 'imglogo':
				//Librairies
		    	$config['allowed_types'] = 'png|jpg|jpeg|PNG|JPG|JPEG';
				$config['upload_path'] = 'assets/resources/img/icons/';
				$this->load->library('upload', $config);
				if($this->upload->do_upload('file')){
	  	  			$Ok = 1;

					// Requête de modification

					$data['description'] = $file;
		            $this->db->where('type' , 'logo_hopital');
		            $this->db->update('settings' , $data);

					$msg = 'L\'image du logo a été modifiée avec succès';

					// Effectuer la journalisation
						$type_action = 'Modification' ;

						$action_effectuee = 'Logo' ;

						$this->control->journalisation($type_action,$action_effectuee) ;

				}else $msg = $this->upload->display_errors('<span>', '<span>');
    		break;

    		case 'patient':
								$nom = $this->input->post('nomPatient');
								$prenoms = $this->input->post('prenomsPatient');
								
								$nomprenoms = $nom.' '.$prenoms;
								
								$codeassurance = $this->input->post('assurance');
								$matriculeassure = $this->input->post('matriculeAssurance');
								$codefiliation = $this->input->post('filiationPatient');
								$datenaispatient = $this->input->post('datenaiss');
								$assure = $this->input->post('typePatient'); 
								$lieuderesidencepat = $this->input->post('residencePatient');
								$telpatient = $this->input->post('contactPatient');
								$adressepatient = $this->input->post('emailPatient');
								$idtauxcouv = $this->input->post('tauxCouverture');
								$codesocieteassure = $this->input->post('societeAssurePrincipal');
								$sexe = $this->input->post('sexePatient');
								$dateenregistrement = $this->input->post('dateEnregistrement');
								$codeproduit = $this->input->post('codeproduit');
								$civilite = $this->input->post('civilitePatient');
								$contactPatient_2 = $this->input->post('contactPatient_2');
								$contactUrgence_1 = $this->input->post('contactUrgence_1');
								$contactUrgence_2 = $this->input->post('contactUrgence_2');
								$nomurgence = $this->input->post('nomurgence');
								$details = $this->input->post('details');
								
								if($assure == 0)
								{
								    $codeassurance = 'NONAS' ;
								    
								    $codefiliation = 0 ;
								    
								    $matriculeassure = '';
								    
								    $idtauxcouv = 0 ;
								    
								    $codesocieteassure = 0 ;
								}

								if(!isset($civilite))
								{
									$civilite = 0 ;
								}

								if(!isset($contactPatient_2))
								{
									$contactPatient_2 = '' ;
								}

								if(!isset($contactUrgence_1))
								{
									$contactUrgence_1 = '' ;
								}

								if(!isset($contactUrgence_2))
								{
									$contactUrgence_2 = '' ;
								}

								if(!isset($nomurgence))
								{
									$nomurgence = '' ;
								}

								if(!isset($details))
								{
									$details = '' ;
								}


								$data =  array('codeassurance' => $codeassurance,
										'matriculeassure' => $matriculeassure,
										'codefiliation' => $codefiliation,
										'nompatient' => $nom,
										'prenomspatient' => $prenoms,
									    'nomprenomspatient' => $nomprenoms,
										'datenaispatient ' => $datenaispatient,
										'assure' => $assure, 
										'lieuderesidencepat ' => $lieuderesidencepat,
										'telpatient' => $telpatient,
										'adressepatient' => $adressepatient,
										'idtauxcouv' => $idtauxcouv,
										'codesocieteassure' => $codesocieteassure,
										'sexe' => $sexe,
										'dateenregistrement' => $dateenregistrement,
										'codeproduit' => $codeproduit,
										'civilite' => $civilite,
										'telpatient_2' => $contactPatient_2,
										'telurgence_1' => $contactUrgence_1,
										'telurgence_2' => $contactUrgence_2,
										'nomurgence' => $nomurgence,
										'details' => $details
										);


								$affected_rows = $this->all_model->update_ligne('patient', $data, 'idenregistremetpatient', $id);

								if ($affected_rows == 1) 
								{
									// message de confirmation
							        $msg = 'Les informations du patient ont été modifiées avec succès';
								} 
								else
								{
							        $msg = lang('info_no_update');
								}
								
								$Ok =1;

								// Effectuer la journalisation
								$type_action = 'Modification' ;

								$action_effectuee = 'Patient'.' '.$id ;

								$this->control->journalisation($type_action,$action_effectuee) ;

								
    		break;

    		case 'antecedent':

							$antecedent = $this->input->post('antecedent');
								
							$data =  array('idenregistremetpatient' => $id,'codpat' => $antecedent);


							$this->all_model->add_ligne_with_return_id('antecedents', $data);

							// message de confirmation
							$msg = 'L\' antécédent '.$antecedent.' a été ajouté avec succès pour le patient '.$id.'';
								
								
							$Ok =1;

							// Effectuer la journalisation
							$type_action = 'Ajout' ;

							$action_effectuee = 'Antécédent du Patient'.' '.$id ;

							$this->control->journalisation($type_action,$action_effectuee) ;

								
    		break;

    		case 'appel':

    					
    						$civilite = $this->input->post('civilite');

							$nom = $this->input->post('nom');

							$urgence = $this->input->post('urgence');

							$date = $this->input->post('date');

							$contact = $this->input->post('contact');

							$time = $this->input->post('time');

							$details = $this->input->post('details');
								
							$data =  array('civilite' => $civilite,
								           'nom_prenoms' => $nom,
								           'contact' => $contact,
								           'urgence' => $urgence,
								           'date' => $date,
								           'heure' => $time,
								           'details' => $details
								        );

							if(empty($id))
			    			{
								$this->all_model->add_ligne('appels_telephonique', $data);

								// message de confirmation
								$msg = 'L\' appel téléphonique a été ajouté avec succès.';
									
									
								$Ok =1;

								// Effectuer la journalisation
								$type_action = 'Ajout' ;

								$action_effectuee = 'Appel téléphonique';

								$this->control->journalisation($type_action,$action_effectuee) ;
							}else{

								$affected_rows = $this->all_model->update_ligne('appels_telephonique', $data, 'id', $id);

								if ($affected_rows == 1) 
								{
									// message de confirmation
							        $msg = 'Les informations de l\'appel téléphonique ont été modifiées avec succès';
								} 
								else
								{
							        $msg = lang('info_no_update');
								}
								
								$Ok =1;

								// Effectuer la journalisation
								$type_action = 'Modification' ;

								$action_effectuee = 'Appel'.' '.$id ;

								$this->control->journalisation($type_action,$action_effectuee) ;

							}

    					
		
    		break;

    		case 'courrier':

    					
    						$civilite = $this->input->post('civilite');

							$nom = $this->input->post('nom');

							$urgence = $this->input->post('urgence');

							$date = $this->input->post('date');

							$time = $this->input->post('time');

							$details = $this->input->post('details');
								
							$data =  array('civilite' => $civilite,
								           'nom_prenoms' => $nom,
								           'urgence' => $urgence,
								           'date' => $date,
								           'heure' => $time,
								           'details' => $details
								        );

							//print_r($_POST); 


							if(empty($id))
			    			{

								$this->all_model->add_ligne('courriers', $data);

					
								// message de confirmation
								$msg = 'Le courrier a été ajouté avec succès.';
									
									
								$Ok =1;

								// Effectuer la journalisation
								$type_action = 'Ajout' ;

								$action_effectuee = 'Courrier';

								$this->control->journalisation($type_action,$action_effectuee) ;
							}else{

								$affected_rows = $this->all_model->update_ligne('courriers', $data, 'id', $id);

								if ($affected_rows == 1) 
								{
									// message de confirmation
							        $msg = 'Les informations du courrier ont été modifiés avec succès';
								} 
								else
								{
							        $msg = lang('info_no_update');
								}
								
								$Ok =1;

								// Effectuer la journalisation
								$type_action = 'Modification' ;

								$action_effectuee = 'Courrier'.' '.$id ;

								$this->control->journalisation($type_action,$action_effectuee) ;

							}

    					
		
    		break;
    		
    		case 'constantes':

							$tension_arterielle = $this->input->post('tension_arterielle');

							$temperature = $this->input->post('temperature');

							$poids = $this->input->post('poids');

							$pouls = $this->input->post('pouls');

							$taille = $this->input->post('taille');

							$bras_droit = $this->input->post('bras_droit');

							$bras_gauche = $this->input->post('bras_gauche');

							$infos_consultation = $this->all_model->get_fullrow('consultation','idconsexterne',$id) ;

							$date = date('Y-m-d') ;

							if(!empty($infos_consultation))
							{
								$numfac = $infos_consultation['numfac'] ;

								$idenregistremetpatient = $infos_consultation['idenregistremetpatient'] ;

								$data =  array('numfac' => $numfac,
											'idenregistremetpatient' => $idenregistremetpatient,
											'date' => $date,
											'tension_arterielle' => $tension_arterielle,
								           'temperature' => $temperature,
								           'poids' => $poids,
								           'pouls' => $pouls,
								           'taille' => $taille,
								           'bras_droit' => $bras_droit,
								           'bras_gauche' => $bras_gauche
								        );

								$infos_constante = $this->all_model->get_fullrow('constante','numfac',$numfac) ;

								if(empty($infos_constante))
				    			{
									$this->all_model->add_ligne('constante', $data);

									// message de confirmation
									$msg = 'Les constantes pour la consultation ont été ajoutées avec succès.';
										
										
									$Ok =1;

									// Effectuer la journalisation
									$type_action = 'Ajout' ;

									$action_effectuee = 'Constantes';

									$this->control->journalisation($type_action,$action_effectuee) ;
								}else{

									$affected_rows = $this->all_model->update_ligne('constante', $data, 'numfac',$numfac);

									if ($affected_rows == 1) 
									{
										// message de confirmation
								        $msg = 'Les constantes pour la consultation ont été modifiées avec succès';
									} 
									else
									{
								        $msg = lang('info_no_update');
									}
									
									$Ok =1;

									// Effectuer la journalisation
									$type_action = 'Modification' ;

									$action_effectuee = 'Constantes'.' '.$numfac ;

									$this->control->journalisation($type_action,$action_effectuee) ;

								}
							}else{

								// message de confirmation
									$msg = 'La consultation pour laquelle vous souhaitez ajouter les constantes a été certainement supprimée.';
										
										
									$Ok = 0;
							}
								
							

								
    		break;
    		
    		
    		case 'medecin_visite':

					
					$medecin = $this->input->post('medecin');

					$date_viste = $this->input->post('date_viste');

					$heure_visite = $this->input->post('heure_visite');

					$infos_medecin_visite = $this->all_model->get_medecin_visite($medecin,$id);

					if(empty($infos_medecin_visite))
					{
						$infos_admission = $this->all_model->get_fullrow('admission','numhospit',$id) ;

							if(!empty($infos_admission))
		                    {
		                        $nbre_jours = $infos_admission['nbredejrs'];
		                        
		                        $infos_patient = $this->all_model->get_fullrow('patient','idenregistremetpatient',$infos_admission['idenregistremetpatient']);
		                        
		                        $infos_medecin = $this->all_model->get_fullrow('medecin','codemedecin',$medecin);
		                        
		                        if(!empty($infos_patient))
		                        {
		                            $codeassurance = $infos_patient['codeassurance'];
		                            
		                            $codeproduit = $infos_patient['codeproduit'];
		                            
		                            if(!empty($infos_medecin))
		                            {
		                                switch($infos_medecin['codespecialitemed']){
		            
		                        		case 'SP002': // MEDCINE GENERALE
		                        		    
		                        		           $codgaran = 'A1375M';
		                        		break;
		                        		
		                        		case 'SP008': // VISITE MEDECIN PSYCHIATRE
		                        		    
		                        		           $codgaran = 'AHM5D9';
		                        		break;
		                    
		                            	default: // MEDECIN SPECIALISTE ET PROFESSEUR AGREGE
		                            	
		                            	       $codgaran = 'A4MDKA';
		                            	break;
		                                }
		                                
		                                
		                                $infos_tarifs = $this->all_model->get_tarif_double_bis($codgaran,$codeassurance,$codeproduit);
		                        
		                                if(!empty($infos_tarifs))
		                                {
		                                    $montant_visite = $infos_tarifs['montjour'];
		                                }else{
		                                    $montant_visite = 0 ;
		                                }
		                                
		                            }else{
		                                
		                                $montant_visite = 0 ;
		                                
		                            }

		                        }else{
		                            
		                            $montant_visite = 0 ;
		                        }

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

										$id_visite = 'V'.$random_chars;

										$nbr_res = $this->all_model->get_fullrow('visites_medicales','id_visite',$id_visite);

									} while ($nbr_res);
								///FIN DU SCRIPT/***

								$data =  array('id_visite' => $id_visite,
											'numhospit' => $id,
											'date_viste' => $date_viste,
											'heure_viste' => $heure_visite,
								           'codemedecin' => $medecin,
								           'montant_visite' => $montant_visite
								        );

								$infos_visite = $this->all_model->get_fullrow('visites_medicales','id_visite',$id_visite) ;

								if(empty($infos_visite))
				    			{
									$this->all_model->add_ligne('visites_medicales', $data);

									// message de confirmation
									$msg = 'La visite médicale a été rattachée au medecin avec succès.';
										
										
									$Ok =1;

									// Effectuer la journalisation
									$type_action = 'Ajout' ;

									$action_effectuee = 'Medecin de visite';

									$this->control->journalisation($type_action,$action_effectuee) ;
								}else{

									// message de confirmation
									$msg = 'Désolé ! La visite du medecin que vous avez choisi a déjà été ratachée à cette admission.';
				
									$Ok = 0;
											
								}
							}

						}else{

							// message de confirmation
							$msg = 'Désolé ! La visite du medecin que vous avez choisi a déjà été ratachée à cette admission.';
		
							$Ok = 0;
						}

								
    		break;

        	default: break;
        }

		echo $Ok.'|'.$msg.'|'.$class;
	}

	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchOrdersData($critere,$option)
	{
		$result = array('data' => array());

		$data = $this->all_model->get_archive($critere,$option);

		$i = 1 ;

		$cpt = 1 ;

		foreach ($data as $key => $value) {

			// fiche

			$fiche = '';

			if(!empty($value['fiche'])) { 
	           $fiche .= '<a href="'.base_url('uploads/fiche_consultation/'.$value['fiche']).'" data-fancybox="usr'.$i++.'">
	              <img src="'.base_url('uploads/fiche_consultation/'.$value['fiche']).'" style="height:50px" alt="">
	            </a>';
	            }else{ 
	            $fiche .= '<span class="fa-stack fa-lg">
					  <i class="fa fa-camera fa-stack-1x"></i>
					  <i class="fa fa-ban fa-stack-2x text-danger"></i>
					</span>';
	            $i++ ; }

			// button

			$buttons = '';

				if(!empty($value['fiche'])) {
	            
		        $buttons .='<a href="'.base_url('uploads/fiche_consultation/'.$value['fiche']).'" target="_blank"><i class="fa fa-2x fa-print text-default"></i></a>
					<span data-tip="Détail de l\'image"><a href="#" data-toggle="modal" data-target=".popresult"  class="addmod" data-h="'.$value['idconsexterne'].'|Addfiche"><i class="fa fa-2x fa-edit text-success"></i></a></span>';
		         }else{
		        $buttons .= '<span data-tip="Ajouter l\'image"><a href="#" data-toggle="modal" data-target=".popresult"  class="addmod" data-h="'.$value['idconsexterne'].'|Addfiche"><i class="fa fa-2x fa-save text-danger"></i></a></span>';
		        }

			$result['data'][$key] = array(
				$cpt,
				$value['idenregistremetpatient'],
				$value['nomprenomspatient'],
				$this->fdateheuregmt->date_fr($value['date']),
				$value['nomprenomsmed'],
				$value['libgaran'],
				$fiche,
				$buttons
			);

			$cpt++ ;
		} // /foreach

		echo json_encode($result);
	}

	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchFacturesData($option_1,$option_2 = null)
	{
		$result = array('data' => array());

		$profils_autorises = array('1','2','7','10');

		if($option_2 == '')
		{
			$type_critere = 'mois' ;
		}else{
			$type_critere = 'interval_date' ;
		}


		$data = $this->all_model->get_factures($option_1,$option_2,$type_critere);

		$i = 1 ;

		$cpt = 1 ;

		foreach ($data as $key => $row) {

			if(!empty($row['idenregistremetpatient']))
			{
				$infos_patient = $this->all_model->get_fullrow('patient','idenregistremetpatient',$row['idenregistremetpatient']);
				
				$numero_facture = substr($row['numfac'],0,3);
				
				if($numero_facture == 'FCB')
				{
				  $infos_demande = $this->all_model->get_fullrow('testlaboimagerie','numfacbul',$row['numfac']);
				  
				  $infos_facture_actuelle = $this->all_model->get_fullrow('factures','numfac',$row['numfac']);
				  
				    if(!empty($infos_demande))
    				{
    					$numbulletin = $infos_demande['idtestlaboimagerie'] ;
    				}
    				else{
    
    					$numbulletin = '' ;
    				}
				  
				  if(!empty($infos_facture_actuelle))
				  {$lien = base_url()."Patient/PrintFacBulletin/$numbulletin/0" ;
				      /*if(($infos_facture_actuelle[codeassurance]) == 'NONAS')
				      {
				           $lien = "Patient/PrintFacBulletin/$numbulletin/1" ;
				      }else{
				      
				       $lien = "Patient/PrintFacBulletin/$numbulletin/0" ;
				     }*/
				  }else{
				      $lien = base_url()."Patient/PrintFacBulletin/$numbulletin/1" ;
				  }
				 
				}
				
				if($numero_facture == 'FCH')
				{
				   $infos_admission = $this->all_model->get_fullrow('admission','numfachospit',$row['numfac']);
				  
				    if(!empty($infos_admission))
    				{
    					$numhospit = $infos_admission['numhospit'] ;
    				}
    				else{
    
    					$numhospit = '' ;
    				}
				  
				  $lien = base_url()."PrintC/FactureHospitPrint/$numhospit/1" ;
				}
				
				if($numero_facture == 'FCS')
				{
				   $infos_soins = $this->all_model->get_fullrow('soins_medicaux','numfac_soins',$row['numfac']);
				  
				    if(!empty($infos_soins))
    				{
    					$id_soins = $infos_soins['id_soins'] ;
    				}
    				else{
    
    					$id_soins = '' ;
    				}
				  
				  $lien = base_url()."infirmerie/printDiv/$id_soins" ;
				}
				
				if($numero_facture == 'FCE')
				{
				    $infos_cons = $this->all_model->get_fullrow('consultation','numfac',$row['numfac']);
				  
				    if(!empty($infos_cons))
    				{
    					$date = $infos_cons['date'] ;
    				}
    				else{
    
    					$date = date('Y-m-d') ;
    				}
    				
    				$numfac = $row['numfac'] ;
    				
				  $lien = base_url()."PrintC/FactureConsPrint/$numfac/$date" ;
				}
				
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

			

			// button

			$buttons = '';

			if(in_array('deleteFactures', $this->permission)){

	           $buttons .= '<a href="'.$lien.'" class="btn btn-default" target="_blank"><i class="fa fa-2x fa-print text-default"></i></a>
	               <a href="#" data-toggle="modal" data-target=".popstat" class="btn btn-default delete" data-h="'.$row['numfac'].'|Facture"><i class="fa fa-2x fa-trash-o text-danger">';
	            }else{ 
	            $buttons .= '<a href="'.$lien.'" class="btn btn-default" target="_blank"><i class="fa fa-2x fa-print text-default"></i></a>';
	            }


			$result['data'][$key] = array(
				$row['numfac'],
				$this->fdateheuregmt->date_fr($row['datefacture']),
				$nompatient,
				$row['montanttotal'],
				$row['montant_pat'],
				$row['montant_ass'],
				$row['montantregle_pat'],
				$buttons
			);

		} // /foreach

		echo json_encode($result);
	}

	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchAntecedentsData($critere,$option)
	{
		$result = array('data' => array());

		$id_name = 'idenregistremetpatient';	

		$id = $option ;

		$table = $critere ;

		$data = $this->all_model->get_fullrow_all($table, $id_name, $id);

		$i = 1 ;

		$cpt = 1 ;

		foreach ($data as $key => $row) {

			if(!empty($row['idenregistremetpatient']))
			{
				$infos_pathologie = $this->all_model->get_fullrow('pathologie','codpat',$row['codpat']);
				if(!empty($infos_pathologie))
				{
					$libelle_pathologie = $infos_pathologie['libelle'] ;
				}
				else{

					$libelle_pathologie = '' ;
				}
				

			}else{

				$libelle_pathologie = '' ;
			}

			

			// button

			$buttons = '';

			//if(in_array($this->session->userdata('user_profil'),$profils_autorises )){

	           $buttons .= '<a href="#" data-toggle="modal" data-target=".popstat" class="btn btn-default delete" data-h="'.$row['id'].'|Antecedent"><i class="fa fa-2x fa-trash-o text-danger">';
	            /*}else{ 
	            $buttons .= 'Voir l\'admin pour toute action';
	            }*/


			$result['data'][$key] = array(
				$libelle_pathologie,
				$buttons
			);

		} // /foreach

		echo json_encode($result);
	}

	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchConsData($critere,$option)
	{
		$result = array('data' => array());

		$id_name = 'idenregistremetpatient';	

		$id = $option ;

		$table = $critere ;

		$data = $this->all_model->get_fullrow_all($table, $id_name, $id);

		$i = 1 ;

		$cpt = 1 ;

		foreach ($data as $key => $row) {

			

			if($row['regle'] == 1)
			{
				$status = '<i class="fa fa-circle text-success"></i> Réglé' ;
			}else{
				$status = '<i class="fa fa-circle text-danger"></i> Non Réglé' ;
			}

			

			// button

			$buttons = '';

			if(in_array('viewMenuInformatique', $this->permission) || in_array('viewMenuGestionnaire', $this->permission)){

	          $buttons .= '<a href="#" data-toggle="modal" data-target=".popstat" class="btn btn-default delete" data-h="'.$row['idconsexterne'].'|Antecedent1"><i class="fa fa-2x fa-trash-o text-danger">';
	            }else{ 
	            $buttons .= 'Voir le gestionnaire pour toute action';
	            }

			$result['data'][$key] = array(
				$row['codeacte'],
				$this->fdateheuregmt->date_fr($row['date']),
				$row['numbon'],
				$row['montant'],
				$row['ticketmod'],
				$row['numfac'],
				$status,
				$buttons
			);

		} // /foreach


		echo json_encode($result);
	}

	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchRdvData($critere,$option)
	{
		$result = array('data' => array());

		$id_name = 'patient_id';	

		$id = $option ;

		$table = $critere ;

		$data = $this->all_model->get_fullrow_all($table, $id_name, $id);



		$i = 1 ;

		$cpt = 1 ;

		foreach ($data as $key => $row) {

			if(!empty($row['doctor_id']))
			{
				$infos_med = $this->all_model->get_fullrow('medecin','codemedecin',$row['doctor_id']);
				if(!empty($infos_med))
				{
					$nom_medecin = $infos_med['nomprenomsmed'] ;
				}
				else{

					$nom_medecin = '' ;
				}
				

			}else{

				$nom_medecin = '' ;
			}

			

			// button

			$buttons = '';

			if(in_array('viewMenuInformatique', $this->permission) || in_array('viewMenuGestionnaire', $this->permission) || in_array('viewMenuAccueil', $this->permission)){

	          $buttons .= '<a href="#" data-toggle="modal" data-target=".popstat" class="btn btn-default delete" data-h="'.$row['appointment_id'].'|Rdv"><i class="fa fa-2x fa-trash-o text-danger">';
	            }else{ 
	            $buttons .= 'Voir l\'administrateur pour toute action';
	            }

			$result['data'][$key] = array(
				$row['description'],
				$this->fdateheuregmt->date_fr($row['appointment_timestamp']),
				$row['heure'],
				$nom_medecin,
				$buttons
			);

		} // /foreach


		echo json_encode($result);
	}


	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchBioImgData($critere,$option)
	{
		$result = array('data' => array());

		$id_name = 'idenregistremetpatient';	

		$id = $option ;

		$table = $critere ;

		$data = $this->all_model->get_fullrow_all($table, $id_name, $id);



		$i = 1 ;

		$cpt = 1 ;

		foreach ($data as $key => $row) {

			$status = '' ;

			if(!empty($row['codemedecin']))
			{
				$infos_med = $this->all_model->get_fullrow('medecin','codemedecin',$row['codemedecin']);
				if(!empty($infos_med))
				{
					$nom_medecin = $infos_med['nomprenomsmed'] ;
				}
				else{
					$nom_medecin = '' ;
				}
			}else{
				$nom_medecin = '' ;
			}

			if($row['typedemande'] == 'analyse')
			{
				$type = 'Biologie' ;
			}else{
				$type = 'Imagerie' ;
			}

			if(!empty($row['numfacbul']))
			{
				$infos_facture = $this->all_model->get_fullrow('factures','numfac',$row['numfacbul']);
				if(!empty($infos_facture))
				{
					$montant = $infos_facture['montanttotal'] ;

					$ticketmod = $infos_facture['montant_pat'] ;

					$regle = $infos_facture['solde_pat'] ;

					if($regle == 1)
					{
						$status = '<i class="fa fa-circle text-success"></i> Réglé' ;
					}else{
						$status = '<i class="fa fa-circle text-danger"></i> Non Réglé' ;
					}
				}
				else{
					$montant = '' ;

					$ticketmod = '' ;
				}
			}else{
				$montant = '' ;

				$ticketmod = '' ;
			}

			if(!empty($row['idtestlaboimagerie']))
			{
				$infos_details = $this->all_model->get_fullrow_all('detailtestlaboimagerie','idtestlaboimagerie',$row['idtestlaboimagerie']);
				if(!empty($infos_details))
				{
					$examens = '';

					$cpteur = 1 ;

					foreach ($infos_details as $value) {

						$examens .= $cpteur.'-'.$value['denomination'] .'<br/>'  ;

						$cpteur++ ;
					}
				}
				else{

					$examens = '' ;
				}
			}else{
				$examens = '' ;
			}

			

			// button

			$buttons = '';

			if(in_array('viewMenuInformatique', $this->permission) || in_array('viewMenuGestionnaire', $this->permission) || in_array('viewMenuAccueil', $this->permission)){

	          $buttons .= '<a href="#" data-toggle="modal" data-target=".popstat" class="btn btn-default delete" data-h="'.$row['idtestlaboimagerie'].'|BioImg"><i class="fa fa-2x fa-trash-o text-danger">';
	            }else{ 
	            $buttons .= 'Voir l\'administrateur pour toute action';
	            }

			$result['data'][$key] = array(
				//$nom_medecin,
				$examens,
				$this->fdateheuregmt->date_fr($row['date']),
				$type,
				$row['numfacbul'],
				$montant,
				$ticketmod,
				$status,
				$buttons
			);

		} // /foreach


		echo json_encode($result);
	}

	
	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchPatientData($critere,$option)
	{
		$result = array('data' => array());

		$data = $this->all_model->get_table($critere);

		$i = 1 ;

		$cpt = 1 ;

		foreach ($data as $key => $row) {

			if ($row['assure']==0)
            {
                $assure="NON";
                $libelle_assurance = "NEANT";
                $matricule = "NEANT";
            }
            else
            {
                $assure="OUI";
                $assurance = $this->PatientModel->get_UniquePatient_assurance($row['idenregistremetpatient']);
                                                
                if(!empty($assurance))
                {
                    $libelle_assurance = $assurance['libelleassurance'];
                }
                else
                {
                    $libelle_assurance = "";
                }
                                                
                $matricule = $row['matriculeassure'];                                  
                                                
            }

			
			// button

			$buttons = '';
			
				if(in_array('updatePatient', $this->permission)){

			$buttons .= '<a href="'.base_url('Patient/PatientUpdater/edit/'.$row['idenregistremetpatient']).'" ><i class="fa fa-2x fa-pencil text-default"></i></a> | ';
			
				}
				
				if(in_array('viewPatient', $this->permission)){

			$buttons .= '<a href="'.base_url('Patient/PatientDetail/'.$row['idenregistremetpatient']).'" ><i class="fa fa-2x fa-eye text-primary"></i></a> | ';
			
				}
				
				if(in_array('deletePatient', $this->permission)){

	        $buttons .= '<a href="#" data-toggle="modal" data-target=".popstat" class="delete" data-h="'.$row['idenregistremetpatient'].'|Patient"><i class="fa fa-2x fa-trash-o text-danger"></i></a>';
	           
				}


			$result['data'][$key] = array(
				$row['idenregistremetpatient'],
				$row['nomprenomspatient'],
				$this->fdateheuregmt->date_fr($row['datenaispatient']),
				$row['telpatient'],
				$this->fdateheuregmt->date_fr($row['dateenregistrement']),
				$assure,
				$libelle_assurance,
				//$matricule,
				$buttons
			);

		} // /foreach

		echo json_encode($result);
	}
	
		public function fetchPatientTodayData($critere,$option)
	{
		$result = array('data' => array());

		$data = $this->all_model->get_fullrow_all($critere,'dateenregistrement',date('Y-m-d'));

		$i = 1 ;

		$cpt = 1 ;

		foreach ($data as $key => $row) {

			if ($row['assure']==0)
            {
                $assure="NON";
                $libelle_assurance = "NEANT";
                $matricule = "NEANT";
            }
            else
            {
                $assure="OUI";
                $assurance = $this->PatientModel->get_UniquePatient_assurance($row['idenregistremetpatient']);
                                                
                if(!empty($assurance))
                {
                    $libelle_assurance = $assurance['libelleassurance'];
                }
                else
                {
                    $libelle_assurance = "";
                }
                                                
                $matricule = $row['matriculeassure'];                                  
                                                
            }

			
			// button

			$buttons = '';
			
				if(in_array('updatePatient', $this->permission)){

			$buttons .= '<a href="'.base_url('Patient/PatientUpdater/edit/'.$row['idenregistremetpatient']).'" ><i class="fa fa-2x fa-pencil text-default"></i></a> | ';
			
				}
				
				if(in_array('viewPatient', $this->permission)){

			$buttons .= '<a href="'.base_url('Patient/PatientDetail/'.$row['idenregistremetpatient']).'" ><i class="fa fa-2x fa-eye text-primary"></i></a> | ';
			
				}
				
				if(in_array('deletePatient', $this->permission)){

	        $buttons .= '<a href="#" data-toggle="modal" data-target=".popstat" class="delete" data-h="'.$row['idenregistremetpatient'].'|Patient"><i class="fa fa-2x fa-trash-o text-danger"></i></a>';
	           
				}


			$result['data'][$key] = array(
				$row['idenregistremetpatient'],
				$row['nomprenomspatient'],
				$this->fdateheuregmt->date_fr($row['datenaispatient']),
				$row['telpatient'],
				$this->fdateheuregmt->date_fr($row['dateenregistrement']),
				$assure,
				$libelle_assurance,
				//$matricule,
				$buttons
			);

		} // /foreach

		echo json_encode($result);
	}

	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchAppelsData($critere,$option)
	{
		$result = array('data' => array());

		$data = $this->all_model->get_table($critere);

		$i = 1 ;

		$cpt = 1 ;

		foreach ($data as $key => $row) {

			if (!empty($row['civilite']))
            {
                $infos_civilite = $this->all_model->get_fullrow('civilite','code_civilite',$row['civilite']) ;

                if(!empty($infos_civilite))
                {
                	$civilite = $infos_civilite['abreviation'] ;
                }else{
                	$civilite = '' ;
                }
            }
            else
            {
                $civilite = '' ;                                        
            }

            $nom_prenoms = $civilite.' '.$row['nom_prenoms'] ;

            switch ($row['urgence']) {
            	case 'faible':
            				
            				$urgence = 'Faible' ;
            		break;
            	case 'moyenne':
            				
            				$urgence = 'Moyenne' ;
            		break;
            	case 'extreme':
            				
            				$urgence = 'Extrème' ;
            		break;
            	
            	default:
            			    $urgence = 'Non signalée' ;
            		break;
            }

			
			// button

			$buttons = '';
			
			if(in_array('viewMenuInformatique', $this->permission) || in_array('viewMenuGestionnaire', $this->permission) || in_array('viewMenuAccueil', $this->permission)) {

			$buttons .= '<a href="'.base_url('secretariat/appel_upd/'.$row['id']).'" ><i class="fa fa-2x fa-pencil text-default"></i></a> | ';

	        $buttons .= '<a href="#" data-toggle="modal" data-target=".popstat" class="delete" data-h="'.$row['id'].'|Appel"><i class="fa fa-2x fa-trash-o text-danger"></i></a>';
	        
			}
	           

			$result['data'][$key] = array(
				$nom_prenoms,
				$row['contact'],
				$row['details'],
				$this->fdateheuregmt->date_fr($row['date']),
				$row['heure'],
				$urgence,
				$buttons
			);

		} // /foreach

		echo json_encode($result);
	}

	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchCourriersData($critere,$option)
	{
		$result = array('data' => array());

		$data = $this->all_model->get_table($critere);

		$i = 1 ;

		$cpt = 1 ;

		foreach ($data as $key => $row) {

			if (!empty($row['civilite']))
            {
                $infos_civilite = $this->all_model->get_fullrow('civilite','code_civilite',$row['civilite']) ;

                if(!empty($infos_civilite))
                {
                	$civilite = $infos_civilite['abreviation'] ;
                }else{
                	$civilite = '' ;
                }
            }
            else
            {
                $civilite = '' ;                                        
            }

            $nom_prenoms = $civilite.' '.$row['nom_prenoms'] ;

            switch ($row['urgence']) {
            	case 'faible':
            				
            				$urgence = 'Faible' ;
            		break;
            	case 'moyenne':
            				
            				$urgence = 'Moyenne' ;
            		break;
            	case 'extreme':
            				
            				$urgence = 'Extrème' ;
            		break;
            	
            	default:
            			    $urgence = 'Non signalée' ;
            		break;
            }

			
			// button

			$buttons = '';
			
			if(in_array('viewMenuInformatique', $this->permission) || in_array('viewMenuGestionnaire', $this->permission) || in_array('viewMenuAccueil', $this->permission)) {

			$buttons .= '<a href="'.base_url('secretariat/courrier_upd/'.$row['id']).'" ><i class="fa fa-2x fa-pencil text-default"></i></a> | ';

	        $buttons .= '<a href="#" data-toggle="modal" data-target=".popstat" class="delete" data-h="'.$row['id'].'|Appel"><i class="fa fa-2x fa-trash-o text-danger"></i></a>';
	        
			}
	           

			$result['data'][$key] = array(
				$nom_prenoms,
				$row['details'],
				$this->fdateheuregmt->date_fr($row['date']),
				$row['heure'],
				$urgence,
				$buttons
			);

		} // /foreach

		echo json_encode($result);
	}

	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchTarifsData($critere,$option)
	{
		$result = array('data' => array());

		$data = $this->all_model->get_fullrow_bis('tarifs','codeassurance',$critere);

		foreach ($data as $key => $value) {

			$infos_garantie = $this->all_model->get_fullrow('garantie', 'codgaran', $value['codgaran']);

			$infos_assurance = $this->all_model->get_fullrow('assurance', 'codeassurance', $value['codeassurance']);

			if($value['codeproduit'] != '')
			{
				$infos_produit = $this->all_model->get_fullrow('produit_assurance', 'codeproduit', $value['codeproduit']);

				$libelleproduit = $infos_produit['libelleproduit'];
			}
			else
			{
				$libelleproduit = '' ;
			}

			

			// button
			$buttons = '';

			if(in_array('updateTarifsPrestation', $this->permission)) {
				$buttons .= '<a href="'.base_url('Tarifs/update_tarif/'.$value['idtarif']).'"><i class="fa fa-pencil"></i></a>';
			}

			if(in_array('deleteTarifsPrestation', $this->permission)) {
				$buttons .= ' | <a href="#" onclick="removeFunc('.$value['idtarif'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></a>';
			}

			$result['data'][$key] = array(
				$value['codgaran'],
				$infos_garantie['libgaran'],
				$value['montjour'],
				$value['montnuit'],
				$value['montferie'],
				$infos_assurance['libelleassurance'],
				$libelleproduit,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchHistoriqueData($critere,$option)
	{
		$result = array('data' => array());

		$id_name = 'user_username' ;

		$data = $this->all_model->get_table_where($critere,$id_name, $option);

		$i = 1 ;

		$cpt = 1 ;

		foreach ($data as $key => $row) {

			if(!empty($row['user_username']))
			{
				$infos_user = $this->all_model->get_fullrow('user','user_username',$row['user_username']);
				if(!empty($infos_user))
				{
					$nomutilisateur = $infos_user['user_first_name'].' '.$infos_user['user_last_name'] ;
				}
				else{

					$nomutilisateur = '' ;
				}
				

			}else{

				$nomutilisateur = '' ;
			}

			// Action

			$type_action = $row['type_action'];

			$action = '';

			switch ($type_action) {
				case 'Consultation':
							$action .= '<i class="fa fa-eye text-success">'.' '.$row['type_action'].' '.$row['action'];
					break;
				case 'Modification':
							$action .= '<i class="fa fa-pencil text-warning">'.' '.$row['type_action'].' '.$row['action'];
					break;
				case 'Suppression':
						    $action .= '<i class="fa fa-trash-o text-danger">'.' '.$row['type_action'].' '.$row['action'];
					break;
				case 'Impression':
						    $action .= '<i class="fa fa-print text-primary">'.' '.$row['type_action'].' '.$row['action'];
					break;
				case 'Ajout':
							$action .= '<i class="fa fa-plus-square text-default">'.' '.$row['type_action'].' '.$row['action'];
					break;
				case 'Connexion':
						    $action .= '<i class="fa fa-plus-square text-success">'.' '.$row['type_action'].' '.$row['action'];
					break;
				case 'Déconnexion':
						   $action .= '<i class="fa fa-plus-square text-danger">'.' '.$row['type_action'].' '.$row['action'];
					break;
				
				default:
						   $action .= '<i class="fa-plus-square text-danger">'.' '.$row['type_action'].' '.$row['action'];
					break;
			}

			//$details

			$details = unserialize($row['details']);

			$detail = $details['url'].'<br/>'.$details['user_agent'].'<br/>'.$details['ip_address'].'<br/>'.$details['host_name'];

			$result['data'][$key] = array(
				$nomutilisateur,
				$this->fdateheuregmt->date_fr($row['date']).' '.$row['heure'],
				$action,
				$detail
			);

			$i++ ;

		} // /foreach

		echo json_encode($result);
	}

	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchAllHistoriqueData($critere,$option)
	{
		$result = array('data' => array());

		$data = $this->all_model->get_table($critere);

		$i = 1 ;

		$cpt = 1 ;

		foreach ($data as $key => $row) {

			if(!empty($row['user_username']))
			{
				$infos_user = $this->all_model->get_fullrow('user','user_username',$row['user_username']);
				if(!empty($infos_user))
				{
					$nomutilisateur = $infos_user['user_first_name'].' '.$infos_user['user_last_name'] ;
				}
				else{

					$nomutilisateur = '' ;
				}
				

			}else{

				$nomutilisateur = '' ;
			}

			// Action

			$type_action = $row['type_action'];

			$action = '';

			switch ($type_action) {
				case 'Consultation':
							$action .= '<i class="fa fa-eye text-success">'.' '.$row['type_action'].' '.$row['action'];
					break;
				case 'Modification':
							$action .= '<i class="fa fa-pencil text-warning">'.' '.$row['type_action'].' '.$row['action'];
					break;
				case 'Suppression':
						    $action .= '<i class="fa fa-trash-o text-danger">'.' '.$row['type_action'].' '.$row['action'];
					break;
				case 'Impression':
						    $action .= '<i class="fa fa-print text-primary">'.' '.$row['type_action'].' '.$row['action'];
					break;
				case 'Ajout':
							$action .= '<i class="fa fa-plus-square text-default">'.' '.$row['type_action'].' '.$row['action'];
					break;
				case 'Connexion':
						    $action .= '<i class="fa fa-plus-square text-success">'.' '.$row['type_action'].' '.$row['action'];
					break;
				case 'Déconnexion':
						   $action .= '<i class="fa fa-plus-square text-danger">'.' '.$row['type_action'].' '.$row['action'];
					break;
				
				default:
						   $action .= '<i class="fa-plus-square text-danger">'.' '.$row['type_action'].' '.$row['action'];
					break;
			}

			//$details

			$details = unserialize($row['details']);

			$detail = $details['url'].'<br/>'.$details['user_agent'].'<br/>'.$details['ip_address'].'<br/>'.$details['host_name'];

			$result['data'][$key] = array(
				$nomutilisateur,
				$this->fdateheuregmt->date_fr($row['date']).' '.$row['heure'],
				$action,
				$detail
			);

			$i++ ;

		} // /foreach

		echo json_encode($result);
	}

	public function get_liste($table)
	{
		//$table = $this->input->post('id');

		$data = $this->all_model->get_table($table);
		
				if(!empty($data))
				{

			 ?>
			      <option selected="seleted" value="">Liste des patients</option>
			 <?php

			      foreach ($data as $value) 
			      { 
			 ?>
			      	<option value="<?php echo $value['idenregistremetpatient'] ?>"><?php echo $value['nomprenomspatient'].' | '.$value['idenregistremetpatient'] ; ?></option>
			  <?php  
			  		
			        
			      } 

			    } 
			    else
			    {
			    	?>
					<option selected="seleted" value="">---</option>
					<?php
			    }        	
    }
    
    public function fetchConsultationData($critere,$option)
	{

		$result = array('data' => array());

		$data = $this->all_model->get_consutaltions($critere,$option);

		$i = 1 ;

		$cpt = 1 ;

		foreach ($data as $key => $row) {

			$infos_patient = $this->all_model->get_fullrow('patient', 'idenregistremetpatient', $row['idenregistremetpatient']);

			$infos_medecin = $this->all_model->get_fullrow('medecin', 'codemedecin', $row['codemedecin']);

			$infos_garantie = $this->all_model->get_fullrow('garantie', 'codgaran', $row['codeacte']);

			if($row['regle'] == 1)
			{
				$status = '<i class="fa fa-circle text-success"></i> Facture Réglée' ;
			}else{
				$status = '<i class="fa fa-circle text-danger"></i> Facture non Réglé' ;
			}

			$infos_constante = $this->all_model->get_fullrow('constante','numfac',$row['numfac']) ;

	        if(empty($infos_constante))
	        {
	        	$class = 'fa-save text-danger' ;

	        }else{

	        	$class = 'fa-eye text-default' ;
	        }
			
			// button

			$buttons = '';

	          $buttons .= '<span data-tip="Ajouter les constantes"><a href="#" data-toggle="modal" data-target=".popresult"  class="addmod" data-h="'.$row['idconsexterne'].'|Addconstante"><i class="fa fa-2x '.$class.'"></i></a></span>';

			$result['data'][$key] = array(
				$i,
				$row['idenregistremetpatient'],
				$infos_patient['nomprenomspatient'],
				$this->fdateheuregmt->date_fr($row['date']),
				$infos_medecin['nomprenomsmed'],
				$infos_garantie['libgaran'],
				$status,
				$buttons
			);

			$i++;

		} // /foreach


		echo json_encode($result);
	}
	
	
	public function ouverture_caisse(){
	    
	   $curr_uri_string = $this->uri->segment(3);

		if($curr_uri_string == 'Consultation') {
			    
		   $infos_solde = $this->all_model->get_dernier_solde_caisse() ;
		   
		   if(!empty($infos_solde) && $infos_solde['action'] == 1)
		   {
		        $datecaisse = $infos_solde['datecaisse'] ;
		    
    		    $mtcaisse = $infos_solde['mtcaisse'] ;
    		    
    		    $action = 2 ;
    		    
    		    $user = $this->session->userdata('user_name') ;
    		    
    		    $heurecaisse = '23:59:59' ;
    		    
    		    
    			$data_1 =  array('datecaisse' => $datecaisse,
    			                    'mtcaisse' => $mtcaisse,
    								'action' => $action,
    								'user' => $user,
    								'heurecaisse' => $heurecaisse
    								);
    								
    			$this->db->insert('caisse_resume', $data_1);
    			
    				/***************************/				
    			$datecaisse = date('Y-m-d') ;
    		    
    		    $mtcaisse = $infos_solde['mtcaisse'] ;
    		    
    		    $action = 0 ;
    		    
    		    $user = $this->session->userdata('user_name') ;
    		    
    		    $heurecaisse = $this->fdateheuregmt->dateheure(5) ;
    		    
    		    
    			$data_2 =  array('datecaisse' => $datecaisse,
    			                    'mtcaisse' => $mtcaisse,
    								'action' => $action,
    								'user' => $user,
    								'heurecaisse' => $heurecaisse
    								);
    								
    			$this->db->insert('caisse_resume', $data_2);
    								
    				/************************/
    			$datecaisse = date('Y-m-d') ;
    		    
    		    $mtcaisse = $infos_solde['mtcaisse'] ;
    		    
    		    $action = 1 ;
    		    
    		    $user = $this->session->userdata('user_name') ;
    		    
    		    $heurecaisse = $this->fdateheuregmt->dateheure(5) ;
    		    
    		    
    			$data_3 =  array('datecaisse' => $datecaisse,
    			                    'mtcaisse' => $mtcaisse,
    								'action' => $action,
    								'user' => $user,
    								'heurecaisse' => $heurecaisse
    								);
    								
    			$this->db->insert('caisse_resume', $data_3); 
		   }
				
				redirect('Consultation/ConsultationRegister');
				exit();
		}
			
		if($curr_uri_string == 'Bulletin') {
		    
		   $infos_solde = $this->all_model->get_dernier_solde_caisse() ;
		    

		   if(!empty($infos_solde) && $infos_solde['action'] == 1)
		   {
		        $datecaisse = $infos_solde['datecaisse'] ;
		    
    		    $mtcaisse = $infos_solde['mtcaisse'] ;
    		    
    		    $action = 2 ;
    		    
    		    $user = $this->session->userdata('user_name') ;
    		    
    		    $heurecaisse = '23:59:59' ;
    		    
    		    
    			$data_1 =  array('datecaisse' => $datecaisse,
    			                    'mtcaisse' => $mtcaisse,
    								'action' => $action,
    								'user' => $user,
    								'heurecaisse' => $heurecaisse
    								);
    								
    			$this->db->insert('caisse_resume', $data_1);
    			
    				/***************************/				
    			$datecaisse = date('Y-m-d') ;
    		    
    		    $mtcaisse = $infos_solde['mtcaisse'] ;
    		    
    		    $action = 0 ;
    		    
    		    $user = $this->session->userdata('user_name') ;
    		    
    		    $heurecaisse = $this->fdateheuregmt->dateheure(5) ;
    		    
    		    
    			$data_2 =  array('datecaisse' => $datecaisse,
    			                    'mtcaisse' => $mtcaisse,
    								'action' => $action,
    								'user' => $user,
    								'heurecaisse' => $heurecaisse
    								);
    								
    			$this->db->insert('caisse_resume', $data_2);
    								
    				/************************/
    			$datecaisse = date('Y-m-d') ;
    		    
    		    $mtcaisse = $infos_solde['mtcaisse'] ;
    		    
    		    $action = 1 ;
    		    
    		    $user = $this->session->userdata('user_name') ;
    		    
    		    $heurecaisse = $this->fdateheuregmt->dateheure(5) ;
    		    
    		    
    			$data_3 =  array('datecaisse' => $datecaisse,
    			                    'mtcaisse' => $mtcaisse,
    								'action' => $action,
    								'user' => $user,
    								'heurecaisse' => $heurecaisse
    								);
    								
    			$this->db->insert('caisse_resume', $data_3); 
		   }
		    
		    
				
				redirect('Bulletin/BulletinRegister');
				exit();
			}
			
		if($curr_uri_string == 'Hospitalisation') {
			    
		    $infos_solde = $this->all_model->get_dernier_solde_caisse() ;
		   
		   if(!empty($infos_solde) && $infos_solde['action'] == 1)
		   {
		        $datecaisse = $infos_solde['datecaisse'] ;
		    
    		    $mtcaisse = $infos_solde['mtcaisse'] ;
    		    
    		    $action = 2 ;
    		    
    		    $user = $this->session->userdata('user_name') ;
    		    
    		    $heurecaisse = '23:59:59' ;
    		    
    		    
    			$data_1 =  array('datecaisse' => $datecaisse,
    			                    'mtcaisse' => $mtcaisse,
    								'action' => $action,
    								'user' => $user,
    								'heurecaisse' => $heurecaisse
    								);
    								
    			$this->db->insert('caisse_resume', $data_1);
    			
    				/***************************/				
    			$datecaisse = date('Y-m-d') ;
    		    
    		    $mtcaisse = $infos_solde['mtcaisse'] ;
    		    
    		    $action = 0 ;
    		    
    		    $user = $this->session->userdata('user_name') ;
    		    
    		    $heurecaisse = $this->fdateheuregmt->dateheure(5) ;
    		    
    		    
    			$data_2 =  array('datecaisse' => $datecaisse,
    			                    'mtcaisse' => $mtcaisse,
    								'action' => $action,
    								'user' => $user,
    								'heurecaisse' => $heurecaisse
    								);
    								
    			$this->db->insert('caisse_resume', $data_2);
    								
    				/************************/
    			$datecaisse = date('Y-m-d') ;
    		    
    		    $mtcaisse = $infos_solde['mtcaisse'] ;
    		    
    		    $action = 1 ;
    		    
    		    $user = $this->session->userdata('user_name') ;
    		    
    		    $heurecaisse = $this->fdateheuregmt->dateheure(5) ;
    		    
    		    
    			$data_3 =  array('datecaisse' => $datecaisse,
    			                    'mtcaisse' => $mtcaisse,
    								'action' => $action,
    								'user' => $user,
    								'heurecaisse' => $heurecaisse
    								);
    								
    			$this->db->insert('caisse_resume', $data_3); 
		   }
				
				redirect('Hospitalisation/HospitalisationRegister');
				exit();
			}
			
		if($curr_uri_string == 'infirmerie') {
			    
		$infos_solde = $this->all_model->get_dernier_solde_caisse() ;
		   
		   if(!empty($infos_solde) && $infos_solde['action'] == 1)
		   {
		        $datecaisse = $infos_solde['datecaisse'] ;
		    
    		    $mtcaisse = $infos_solde['mtcaisse'] ;
    		    
    		    $action = 2 ;
    		    
    		    $user = $this->session->userdata('user_name') ;
    		    
    		    $heurecaisse = '23:59:59' ;
    		    
    		    
    			$data_1 =  array('datecaisse' => $datecaisse,
    			                    'mtcaisse' => $mtcaisse,
    								'action' => $action,
    								'user' => $user,
    								'heurecaisse' => $heurecaisse
    								);
    								
    			$this->db->insert('caisse_resume', $data_1);
    			
    				/***************************/				
    			$datecaisse = date('Y-m-d') ;
    		    
    		    $mtcaisse = $infos_solde['mtcaisse'] ;
    		    
    		    $action = 0 ;
    		    
    		    $user = $this->session->userdata('user_name') ;
    		    
    		    $heurecaisse = $this->fdateheuregmt->dateheure(5) ;
    		    
    		    
    			$data_2 =  array('datecaisse' => $datecaisse,
    			                    'mtcaisse' => $mtcaisse,
    								'action' => $action,
    								'user' => $user,
    								'heurecaisse' => $heurecaisse
    								);
    								
    			$this->db->insert('caisse_resume', $data_2);
    								
    				/************************/
    			$datecaisse = date('Y-m-d') ;
    		    
    		    $mtcaisse = $infos_solde['mtcaisse'] ;
    		    
    		    $action = 1 ;
    		    
    		    $user = $this->session->userdata('user_name') ;
    		    
    		    $heurecaisse = $this->fdateheuregmt->dateheure(5) ;
    		    
    		    
    			$data_3 =  array('datecaisse' => $datecaisse,
    			                    'mtcaisse' => $mtcaisse,
    								'action' => $action,
    								'user' => $user,
    								'heurecaisse' => $heurecaisse
    								);
    								
    			$this->db->insert('caisse_resume', $data_3); 
		   }
				
				redirect('infirmerie/Encaisser_soins');
				exit();
		}
		
		if($curr_uri_string == 'caisse') {
			    
		$infos_solde = $this->all_model->get_dernier_solde_caisse() ;
		   
		   if(!empty($infos_solde) && $infos_solde['action'] == 1)
		   {
		        $datecaisse = $infos_solde['datecaisse'] ;
		    
    		    $mtcaisse = $infos_solde['mtcaisse'] ;
    		    
    		    $action = 2 ;
    		    
    		    $user = $this->session->userdata('user_name') ;
    		    
    		    $heurecaisse = '23:59:59' ;
    		    
    		    
    			$data_1 =  array('datecaisse' => $datecaisse,
    			                    'mtcaisse' => $mtcaisse,
    								'action' => $action,
    								'user' => $user,
    								'heurecaisse' => $heurecaisse
    								);
    								
    			$this->db->insert('caisse_resume', $data_1);
    			
    				/***************************/				
    			$datecaisse = date('Y-m-d') ;
    		    
    		    $mtcaisse = $infos_solde['mtcaisse'] ;
    		    
    		    $action = 0 ;
    		    
    		    $user = $this->session->userdata('user_name') ;
    		    
    		    $heurecaisse = $this->fdateheuregmt->dateheure(5) ;
    		    
    		    
    			$data_2 =  array('datecaisse' => $datecaisse,
    			                    'mtcaisse' => $mtcaisse,
    								'action' => $action,
    								'user' => $user,
    								'heurecaisse' => $heurecaisse
    								);
    								
    			$this->db->insert('caisse_resume', $data_2);
    								
    				/************************/
    			$datecaisse = date('Y-m-d') ;
    		    
    		    $mtcaisse = $infos_solde['mtcaisse'] ;
    		    
    		    $action = 1 ;
    		    
    		    $user = $this->session->userdata('user_name') ;
    		    
    		    $heurecaisse = $this->fdateheuregmt->dateheure(5) ;
    		    
    		    
    			$data_3 =  array('datecaisse' => $datecaisse,
    			                    'mtcaisse' => $mtcaisse,
    								'action' => $action,
    								'user' => $user,
    								'heurecaisse' => $heurecaisse
    								);
    								
    			$this->db->insert('caisse_resume', $data_3); 
		   }
				
				redirect('caisse/CaisseRegister');
				exit();
		}
		
		
	}
	
	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchAdmissionData($critere,$option)
	{
		$result = array('data' => array());

		$profils_autorises = array('1','2','7');

		$data = $this->all_model->get_table($critere);

		$i = 1 ;

		$cpt = 1 ;

		foreach ($data as $key => $row) {

			if(!empty($row['idenregistremetpatient']))
			{
				$infos_patient = $this->all_model->get_fullrow('patient','idenregistremetpatient',$row['idenregistremetpatient']);

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

			$infos_naturehospit = $this->all_model->get_fullrow('naturehospit','idnathospit',$row['codenaturehospit']);

				if(!empty($infos_naturehospit))
				{
					$nature_hospit = $infos_naturehospit['nomnaturehospit'] ;
				}else{
					$nature_hospit = '' ;
				}

			$infos_chambre = $this->all_model->get_fullrow('chambrehospit','codechbre',$row['codechbre']);

				if(!empty($infos_chambre))
				{
					$chambre = $infos_chambre['nomchambre'] ;
				}else{
					$chambre = '' ;
				}

			$infos_lit = $this->all_model->get_fullrow('bed','bed_id',$row['idtypelit']);

				if(!empty($infos_lit))
				{
					$lit = $infos_lit['bed_number'] ;
				}else{
					$lit = '' ;
				}

			

			// button

			$buttons = '';

			$print_patient = '';

			$print_assurance = '';

			if(in_array('deleteFactures', $this->permission)){

	           $buttons .= '<a href="'.base_url('Patient/AdmissionUpdater/'.$row['numhospit']).'" class="btn btn-default"><i class="fa fa-2x fa-pencil text-default"></i></a>
	               <a href="#" data-toggle="modal" data-target=".popstat" class="btn btn-default delete" data-h="'.$row['numhospit'].'|Admission"><i class="fa fa-2x fa-trash-o text-danger">';
	            }else{ 
	            $buttons .= 'Voir le gestionnaire pour toute action';
	            }

	        $print_patient .= '<a href="'.base_url('PrintC/FactureHospitPrint/'.$row['numhospit']).'/1'.'" class="btn btn-default" target="_blank"><i class="fa fa-2x fa-print text-default"></i></a>';

	        $print_assurance .= '<a href="'.base_url('PrintC/FactureHospitPrint/'.$row['numhospit']).'/2'.'" class="btn btn-default" target="_blank"><i class="fa fa-2x fa-print text-default"></i></a>';


			$result['data'][$key] = array(
				$row['numhospit'],
				$row['numfachospit'],
				$this->fdateheuregmt->date_fr($row['dateentree']),
				$this->fdateheuregmt->date_fr($row['datesortie']),
				$row['nbredejrs'],
				$nompatient,
				$row['motifhospit'],
				$chambre,
				$lit,
				$print_patient,
				$print_assurance,
				$buttons
			);

		} // /foreach

		echo json_encode($result);
	}
	
	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchAdmissionDataUnique($critere,$option)
	{
		$result = array('data' => array());

		$id_name = 'idenregistremetpatient';	

		$id = $option ;

		$table = $critere ;

		$data = $this->all_model->get_fullrow_all($table, $id_name, $id);



		$i = 1 ;

		$cpt = 1 ;

		foreach ($data as $key => $row) {

			if(!empty($row['idenregistremetpatient']))
			{
				//$infos_patient = $this->all_model->get_fullrow('patient','idenregistremetpatient',$row['idenregistremetpatient']);
			}

			$infos_naturehospit = $this->all_model->get_fullrow('naturehospit','idnathospit',$row['codenaturehospit']);

				if(!empty($infos_naturehospit))
				{
					$nature_hospit = $infos_naturehospit['nomnaturehospit'] ;
				}else{
					$nature_hospit = '' ;
				}

			$infos_chambre = $this->all_model->get_fullrow('chambrehospit','codechbre',$row['codechbre']);

				if(!empty($infos_chambre))
				{
					$chambre = $infos_chambre['nomchambre'] ;
				}else{
					$chambre = '' ;
				}

			$infos_lit = $this->all_model->get_fullrow('bed','bed_id',$row['idtypelit']);

				if(!empty($infos_lit))
				{
					$lit = $infos_lit['bed_number'] ;
				}else{
					$lit = '' ;
				}

			

			// button

			$buttons = '';

			$print_patient = '';

			$print_assurance = '';

			if(in_array('deleteFactures', $this->permission)){

	           $buttons .= '<a href="'.base_url('Patient/AdmissionUpdater/'.$row['numhospit']).'" class="btn btn-default"><i class="fa fa-2x fa-pencil text-default"></i></a>
	               <a href="#" data-toggle="modal" data-target=".popstat" class="btn btn-default delete" data-h="'.$row['numhospit'].'|Admission"><i class="fa fa-2x fa-trash-o text-danger">';
	            }else{ 
	            $buttons .= 'Voir le gestionnaire pour toute action';
	            }

	        $print_patient .= '<a href="'.base_url('PrintC/FactureHospitPrint/'.$row['numhospit']).'/1'.'" class="btn btn-default" target="_blank"><i class="fa fa-2x fa-print text-default"></i></a>';

	        $print_assurance .= '<a href="'.base_url('PrintC/FactureHospitPrint/'.$row['numhospit']).'/2'.'" class="btn btn-default" target="_blank"><i class="fa fa-2x fa-print text-default"></i></a>';


			$result['data'][$key] = array(
				$row['numhospit'],
				$row['numfachospit'],
				$this->fdateheuregmt->date_fr($row['dateentree']),
				$this->fdateheuregmt->date_fr($row['datesortie']),
				$row['nbredejrs'],
				$row['motifhospit'],
				$chambre,
				$lit,
				$print_patient,
				$print_assurance,
				$buttons
			);

		} // /foreach

		echo json_encode($result);
	}

	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchFacturesDataUnique($critere,$option)
	{
		$result = array('data' => array());

		$id_name = 'idenregistremetpatient';	

		$id = $option ;

		$table = $critere ;

		$data = $this->all_model->get_fullrow_all($table, $id_name, $id);

		$i = 1 ;

		$cpt = 1 ;

		foreach ($data as $key => $row) {

			if(!empty($row['idenregistremetpatient']))
			{
				$infos_patient = $this->all_model->get_fullrow('patient','idenregistremetpatient',$row['idenregistremetpatient']);
				
				$numero_facture = substr($row['numfac'],0,3);
				
				if($numero_facture == 'FCB')
				{
				  $infos_demande = $this->all_model->get_fullrow('testlaboimagerie','numfacbul',$row['numfac']);
				  
				    if(!empty($infos_demande))
    				{
    					$numbulletin = $infos_demande['idtestlaboimagerie'] ;
    				}
    				else{
    
    					$numbulletin = '' ;
    				}
				  
				  $lien = base_url()."Patient/PrintFacBulletin/$numbulletin/0" ;
				}
				
				if($numero_facture == 'FCH')
				{
				   $infos_admission = $this->all_model->get_fullrow('admission','numfachospit',$row['numfac']);
				  
				    if(!empty($infos_admission))
    				{
    					$numhospit = $infos_admission['numhospit'] ;
    				}
    				else{
    
    					$numhospit = '' ;
    				}
				  
				  $lien = base_url()."PrintC/FactureHospitPrint/$numhospit/1" ;
				}
				
				if($numero_facture == 'FCE')
				{
				    $infos_cons = $this->all_model->get_fullrow('consultation','numfac',$row['numfac']);
				  
				    if(!empty($infos_cons))
    				{
    					$date = $infos_cons['date'] ;
    				}
    				else{
    
    					$date = date('Y-m-d') ;
    				}
    				
    				$numfac = $row['numfac'] ;
    				
				  $lien = base_url()."PrintC/FactureConsPrint/$numfac/$date" ;
				}
				

			}

			

			// button

			$buttons = '';

			if(in_array('deleteFactures', $this->permission)){

	           $buttons .= '<a href="'.$lien.'" class="btn btn-default" target="_blank"><i class="fa fa-2x fa-print text-default"></i></a>
	               <a href="#" data-toggle="modal" data-target=".popstat" class="btn btn-default delete" data-h="'.$row['numfac'].'|Facture"><i class="fa fa-2x fa-trash-o text-danger">';
	            }else{ 
	            $buttons .= 'Voir le gestionnaire pour toute action';
	            }


			$result['data'][$key] = array(
				$row['numfac'],
				$this->fdateheuregmt->date_fr($row['datefacture']),
				$row['montanttotal'],
				$row['montant_pat'],
				$row['montant_ass'],
				$row['montantregle_pat'],
				$buttons
			);

		} // /foreach

		echo json_encode($result);
	}
	
	public function fetchHistoriquePatientData($option_1,$option_2 = null)
	{
		$result = array('data' => array());


		$date_debut = $option_1 ;

		$date_fin = $option_2 ;



		$data = $this->all_model->get_factures_patient($type_facture,$date_debut,$date_fin,$patient_id);

		$i = 1 ;

		$cpt = 1 ;

		foreach ($data as $key => $row) {

			if(!empty($row['idenregistremetpatient']))
			{
				$infos_patient = $this->all_model->get_fullrow('patient','idenregistremetpatient',$row['idenregistremetpatient']);
				
				$numero_facture = substr($row['numfac'],0,3);
				
				if($numero_facture == 'FCB')
				{
				  $infos_demande = $this->all_model->get_fullrow('testlaboimagerie','numfacbul',$row['numfac']);
				  
				  $infos_facture_actuelle = $this->all_model->get_fullrow('factures','numfac',$row['numfac']);
				  
				    if(!empty($infos_demande))
    				{
    					$numbulletin = $infos_demande['idtestlaboimagerie'] ;
    				}
    				else{
    
    					$numbulletin = '' ;
    				}
				  
				  if(!empty($infos_facture_actuelle))
				  {$lien = "Patient/PrintFacBulletin/$numbulletin/0" ;
				      /*if(($infos_facture_actuelle[codeassurance]) == 'NONAS')
				      {
				           $lien = "Patient/PrintFacBulletin/$numbulletin/1" ;
				      }else{
				      
				       $lien = "Patient/PrintFacBulletin/$numbulletin/0" ;
				     }*/
				  }else{
				      $lien = "Patient/PrintFacBulletin/$numbulletin/1" ;
				  }
				 
				}
				
				if($numero_facture == 'FCH')
				{
				   $infos_admission = $this->all_model->get_fullrow('admission','numfachospit',$row['numfac']);
				  
				    if(!empty($infos_admission))
    				{
    					$numhospit = $infos_admission['numhospit'] ;
    				}
    				else{
    
    					$numhospit = '' ;
    				}
				  
				  $lien = "PrintC/FactureHospitPrint/$numhospit/1" ;
				}
				
				if($numero_facture == 'FCS')
				{
				   $infos_soins = $this->all_model->get_fullrow('soins_medicaux','numfac_soins',$row['numfac']);
				  
				    if(!empty($infos_soins))
    				{
    					$id_soins = $infos_soins['id_soins'] ;
    				}
    				else{
    
    					$id_soins = '' ;
    				}
				  
				  $lien = "infirmerie/printDiv/$id_soins" ;
				}
				
				if($numero_facture == 'FCE')
				{
				    $infos_cons = $this->all_model->get_fullrow('consultation','numfac',$row['numfac']);
				  
				    if(!empty($infos_cons))
    				{
    					$date = $infos_cons['date'] ;
    				}
    				else{
    
    					$date = date('Y-m-d') ;
    				}
    				
    				$numfac = $row['numfac'] ;
    				
				  $lien = "PrintC/FactureConsPrint/$numfac/$date" ;
				}
				
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

			

			// button

			$buttons = '';

			if(in_array('deleteFactures', $this->permission)){

	           $buttons .= '<a href="'.$lien.'" class="btn btn-default" target="_blank"><i class="fa fa-2x fa-print text-default"></i></a>
	               <a href="#" data-toggle="modal" data-target=".popstat" class="btn btn-default delete" data-h="'.$row['numfac'].'|Facture"><i class="fa fa-2x fa-trash-o text-danger">';
	            }else{ 
	            $buttons .= '<a href="'.$lien.'" class="btn btn-default" target="_blank"><i class="fa fa-2x fa-print text-default"></i></a>';
	            }


			$result['data'][$key] = array(
				$row['numfac'],
				$this->fdateheuregmt->date_fr($row['datefacture']),
				$nompatient,
				$row['montanttotal'],
				$row['montant_pat'],
				$row['montant_ass'],
				$row['montantregle_pat'],
				$buttons
			);

		} // /foreach

		echo json_encode($result);
	}

	public function fetchFacturesExamensDemandesData($option_1,$option_2 = null)
	{
		$result = array('data' => array());

		$profils_autorises = array('1','2','7','10');

		if($option_2 == '')
		{
			$type_critere = 'mois' ;
		}else{
			$type_critere = 'interval_date' ;
		}


		$data = $this->all_model->get_factures_examens($option_1,$option_2,$type_critere);

		//$data = $this->all_model->get_table('testlaboimagerie');

		foreach ($data as $key => $value) {

			$count_total_examens_item = $this->all_model->countExamenItem($value['idtestlaboimagerie']);

			$date = $this->fdateheuregmt->date_fr($value['date']);

			$infos_patient = $this->all_model->get_fullrow('patient', 'idenregistremetpatient', $value['idenregistremetpatient']);

			$infos_factures = $this->all_model->get_fullrow('factures', 'numfac', $value['numfacbul']);

			if(!empty($infos_factures))
			{
				$montant_total = $infos_factures['montanttotal'] ;
				$regle = $infos_factures['solde_pat'] ;

                if($regle == 1)
                {
                    $status = '<i class="fa fa-circle text-success"></i> Facture Réglée' ;
                }else{
                    $status = '<i class="fa fa-circle text-danger"></i> Facture Non Réglée' ;
                }
			}else{
				$montant_total = 0 ;

				$status = '<i class="fa fa-circle text-danger"></i> Facture Inexistante' ;
			}

			if($value['typedemande'] == 'analyse')
			{
				$type_facture = 'bio' ;
			}

			if($value['typedemande'] == 'imagerie')
			{
				$type_facture = 'img' ;
			}

			if(!empty($value['idtestlaboimagerie']))
            {
                $infos_details = $this->all_model->get_fullrow_all('detailtestlaboimagerie','idtestlaboimagerie',$value['idtestlaboimagerie']);
                if(!empty($infos_details))
                {
                    $examens = '';

                    $cpteur = 1 ;

                    foreach ($infos_details as $row) {

                        $examens .= $cpteur.'- '.$row['denomination'] .'<br/>'  ;

                        $cpteur++ ;
                    }
                }
                else{

                    $examens = '' ;
                }
            }else{

                $examens = '' ;
            }

			

			// button
			$buttons = '';
/*
            if((in_array('deleteFacExam', $this->permission)) && (in_array('updateFacExam', $this->permission)) && (in_array('viewFacExam', $this->permission))){

            	$buttons .= '<a href="'.base_url('ImagerieBiologie/printDiv/'.$value['idtestlaboimagerie']).'/'.$value['mode_patient'].'"  target="_blank"><i class="fa fa-2x fa-print text-default"></i></a> | 
            	<a href="'.base_url('ImagerieBiologie/demande_examen/'.$type_facture.'/'.$value['idtestlaboimagerie']).'"><i class="fa fa-2x fa-pencil text-default"></i></a> | 
	               <a href="#" data-toggle="modal" data-target=".popstat" class="delete" data-h="'.$value['idtestlaboimagerie'].'|FactureExamen"><i class="fa fa-2x fa-trash-o text-danger">';
	            }else
	       if((in_array('deleteFacExam', $this->permission)) && (in_array('updateFacExam', $this->permission))){ 
	            $buttons .= '<a href="#" data-toggle="modal" data-target=".popstat" class="delete" data-h="'.$value['idtestlaboimagerie'].'|FactureExamen"><i class="fa fa-2x fa-trash-o text-danger"> | 
            	<a href="'.base_url('ImagerieBiologie/demande_examen/'.$type_facture.'/'.$value['idtestlaboimagerie']).'"><i class="fa fa-2x fa-pencil text-default"></i></a>';
	            }else
	            
	       if((in_array('updateFacExam', $this->permission)) && (in_array('viewFacExam', $this->permission))){ 
	            $buttons .= '<a href="'.base_url('ImagerieBiologie/demande_examen/'.$type_facture.'/'.$value['idtestlaboimagerie']).'"><i class="fa fa-2x fa-pencil text-default"></i></a> | <a href="'.base_url('ImagerieBiologie/printDiv/'.$value['idtestlaboimagerie']).'/'.$value['mode_patient'].'"  target="_blank"><i class="fa fa-2x fa-print text-default"></i></a>';
	            }else
	            
	       if((in_array('viewFacExam', $this->permission)) && (in_array('deleteFacExam', $this->permission))){ 
	            $buttons .= '<a href="'.base_url('ImagerieBiologie/printDiv/'.$value['idtestlaboimagerie']).'/'.$value['mode_patient'].'"  target="_blank"><i class="fa fa-2x fa-print text-default"></i></a> | 
	               <a href="#" data-toggle="modal" data-target=".popstat" class="delete" data-h="'.$value['idtestlaboimagerie'].'|FactureExamen"><i class="fa fa-2x fa-trash-o text-danger">';
	            }
	            
	       else
	            
	       if((in_array('viewFacExam', $this->permission))){ 
	            $buttons .= '<a href="'.base_url('ImagerieBiologie/printDiv/'.$value['idtestlaboimagerie']).'/'.$value['mode_patient'].'"  target="_blank"><i class="fa fa-2x fa-print text-default"></i></a>';
	            }
	            
	       else
	            
	       if((in_array('updateFacExam', $this->permission))){ 
	            $buttons .= '<a href="'.base_url('ImagerieBiologie/demande_examen/'.$type_facture.'/'.$value['idtestlaboimagerie']).'"><i class="fa fa-2x fa-pencil text-default"></i></a>';
	            }
	       else
	       if((in_array('deleteFacExam', $this->permission))){ 
	            $buttons .= '<a href="#" data-toggle="modal" data-target=".popstat" class="delete" data-h="'.$value['idtestlaboimagerie'].'|FactureExamen"><i class="fa fa-2x fa-trash-o text-danger"></a>';
	            }
	            
	            else{ 
	            $buttons .= 'Voir le gestionnaire pour toute action';
	            }
*/

$buttons .= '<a class="btn btn-success" href="'.base_url('laboratoire/resultats/'.$value['numfacbul']).'"><i class="fa ti-save"> Resultats</i></a>';

			$result['data'][$key] = array(
				$date,
				$value['numfacbul'],
				strtoupper($value['typedemande']),
				$value['idenregistremetpatient'],
				$infos_patient['nomprenomspatient'],
				$infos_patient['telpatient'],
				$count_total_examens_item,
				$examens,
				$montant_total,
				$status,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	public function fetchFicheSoinsData($option_1,$option_2 = null)
	{
		$result = array('data' => array());

		$profils_autorises = array('1','2','7','10');

		if($option_2 == '')
		{
			$type_critere = 'mois' ;
		}else{
			$type_critere = 'interval_date' ;
		}

		$data = $this->all_model->get_fiche_soins($option_1,$option_2,$type_critere);

		$i = 1 ;

		$cpt = 1 ;

		foreach ($data as $key => $row) {

			if(!empty($row['idenregistremetpatient']))
			{
				$infos_patient = $this->all_model->get_fullrow('patient','idenregistremetpatient',$row['idenregistremetpatient']);
				
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

			if(!empty($row['id']))
			{
				$infos_historique_soins = $this->all_model->get_fullrow_all('fiche_soins_historique','id_fiche_soin',$row['id']);

				$users = '';

				$cpteur = 1 ;

				foreach ($infos_historique_soins as $historique) {
					
					$infos_user = $this->all_model->get_fullrow('user','user_username',$historique['user']);

					$users .= $cpteur.'-'.$infos_user['user_first_name'].' '.$infos_user['user_last_name'] .'<br/>'  ;

					$cpteur++ ;
				}

			}else{
				$users = '' ;
			}

			if(!empty($row['soins_effectues']))
			{
				$infos_details = unserialize($row['soins_effectues']) ;

			    $nbre_soins = count($infos_details) ;

				$soins_matin = '';
				$soins_soir = '';
				$soins_nuit = '';

				for ($i=0; $i < $nbre_soins; $i++) { 

					$arr2 = explode("_", $infos_details[$i]);

					$infos_soins = $this->all_model->get_fullrow('actes_as','code_acte',$arr2[1]) ;

					if(in_array('Matin_'.$arr2[1].'', $infos_details))
					{
						$soins_matin .= '[ '.$infos_soins['libelle_acte'] .' ]'  ;
					}

					if(in_array('Soir_'.$arr2[1].'', $infos_details))
					{
						$soins_soir .= '[ '.$infos_soins['libelle_acte'] .' ]'  ;
					}

					if(in_array('Nuit_'.$arr2[1].'', $infos_details))
					{
						$soins_nuit .= '[ '.$infos_soins['libelle_acte'] .' ]'  ;
					}
								
				}

			}else{
				$soins_matin = '';
				$soins_soir = '';
				$soins_nuit = '';
			}

			if(!empty($row['type_agent']))
			{
				if($row['type_agent'] == 'AS')
				{
					$type_agent = 'AIDE SOIGNANT (AS)' ;
				}
				else{
					$type_agent = 'INFIRMIER' ;
				}
			}else{
				$type_agent = '' ;
			}

			$ensemble_soins = '<h4><b>1- Soins du matin : </b></h4>'.$soins_matin.'<br/><br/>'. '<h4><b>2- Soins du soir : </b></h4>'.$soins_soir.'<br/><br/>'. '<h4><b>3- Soins de nuit : </b></h4>'.$soins_nuit ;

			$id_soins = $row['id'] ;

			$lien = base_url()."PrintC/fiche_soins/".$id_soins ;
			
			// button

			$buttons = '';

			if(in_array('deleteFicheSoins', $this->permission)){
	           $buttons .= '<a href="#" data-toggle="modal" data-target=".popstat" class="btn btn-default delete" data-h="'.$row['id'].'|FicheSoins"><i class="fa fa-2x fa-trash-o text-danger"></i></a>';
	        }

			if(in_array('viewFicheSoins', $this->permission))
			{ 
	            $buttons .= '<a href="'.$lien.'" class="btn btn-default" target="_blank"><i class="fa fa-2x fa-print text-default"></i></a>';
	        }

			$result['data'][$key] = array(
				$row['id'],
				$this->fdateheuregmt->date_fr($row['date']),
				$nompatient,
				$ensemble_soins,
				$type_agent,
				$users,
				$buttons
			);

		} // /foreach

		echo json_encode($result);
	}
	
	public function fetchOrdonnanceData($option_1,$option_2 = null)
	{
		$result = array('data' => array());

		$profils_autorises = array('1','2','7','10');

		if($option_2 == '')
		{
			$type_critere = 'mois' ;
		}else{
			$type_critere = 'interval_date' ;
		}


		$data = $this->all_model->get_ordonnance($option_1,$option_2,$type_critere);

		$i = 1 ;

		$cpt = 1 ;

		foreach ($data as $key => $row) {

			if(!empty($row['idenregistremetpatient']))
			{
				$infos_patient = $this->all_model->get_fullrow('patient','idenregistremetpatient',$row['idenregistremetpatient']);
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

			if(!empty($row['codemedecin']))
			{
				$infos_medicin = $this->all_model->get_fullrow('medecin','codemedecin',$row['codemedecin']);
				
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


			if(!empty($row['medicament']) ) {

				$medicine = explode('###', $row['medicament']);

				$medicine_name_with_dosage_1 = '';
				$i = 1 ;

				foreach ($medicine as $value) {
					$medicine_id = explode('***', $value);
	
					$medicine_name_with_dosage = $this->all_model->getMedicineById($medicine_id[0])->pr_nom . ' -' . $medicine_id[1];
					$medicine_name_with_dosage = $medicine_name_with_dosage . ' | ' . $medicine_id[3] . '<br>';
					rtrim($medicine_name_with_dosage, ',');
					$medicine_name_with_dosage_1 = $medicine_name_with_dosage_1.'<p>'.$i.'- ' . $medicine_name_with_dosage . '</p>';

					//$medicine_name_with_dosage_1 = $medicine_name_with_dosage_1.$medicine_name_with_dosage;
					$i++;
				}

			}else{
				$medicine_name_with_dosage_1 = '';
			}

		

			// button

			$buttons = '';

			$lien = base_url()."PrintC/ordonnance/".$row['id'] ;

			if(in_array('deleteOrdonnance', $this->permission)){

	           $buttons .= '<a href="#" data-toggle="modal" data-target=".popstat" class="btn btn-default delete" data-h="'.$row['id'].'|Ordonnance"><i class="fa fa-2x fa-trash-o text-danger"></i></a>';
	        }

			if(in_array('viewOrdonnance', $this->permission)){
	            $buttons .= '<a href="'.$lien.'" class="btn btn-default" target="_blank"><i class="fa fa-2x fa-print text-default"></i></a>';
	        }

		    if(in_array('updateOrdonnance', $this->permission)){
				$buttons .= '<a href="'.base_url('prescriptions/fiche_ordonnance/'.$row['id']).'" class="btn btn-default" ><i class="fa fa-2x fa-pencil text-default"></i></a>';
			}

			$result['data'][$key] = array(
				$row['id'],
				$this->fdateheuregmt->date_fr($row['date_ordonnance']),
				$this->fdateheuregmt->date_fr($row['date_rdv']),
				$nompatient,
				$medicine_name_with_dosage_1,
				$nommedecin,
				$buttons
			);


		} 

		echo json_encode($result);

	}
	
	public function fetchConsultationForDiagnosisData($critere,$option)
	{

		$result = array('data' => array());

		$data = $this->all_model->get_consutaltions_diagonsis($critere,$option);

		$i = 1 ;

		$cpt = 1 ;

		foreach ($data as $key => $row) {

			$infos_patient = $this->all_model->get_fullrow('patient', 'idenregistremetpatient', $row['idenregistremetpatient']);

			$infos_medecin = $this->all_model->get_fullrow('medecin', 'codemedecin', $row['codemedecin']);

			$infos_garantie = $this->all_model->get_fullrow('garantie', 'codgaran', $row['codeacte']);

			if($row['regle'] == 1)
			{
				$status = '<i class="fa fa-circle text-success"></i> Facture Réglée' ;
			}else{
				$status = '<i class="fa fa-circle text-danger"></i> Facture non Réglé' ;
			}

			$infos_constante = $this->all_model->get_fullrow('constante','numfac',$row['numfac']) ;

	        $lien = base_url()."Docteur/diagnostique/".$row['idconsexterne'] ;
			
			// button

			$buttons = '';

	          $buttons .= '<a href="'.$lien.'"  class="btn btn-success"><i class="fa fa-user-md"> Consulter Mon Patient</i></a>';

			$result['data'][$key] = array(
				$i,
				$row['idenregistremetpatient'],
				$infos_patient['nomprenomspatient'],
				$this->fdateheuregmt->date_fr($row['date']),
				$infos_medecin['nomprenomsmed'],
				$infos_garantie['libgaran'],
				$status,
				$buttons
			);

			$i++;

		} // /foreach


		echo json_encode($result);
	}

	public function fetchArchivesData($option_1,$option_2 = null)
	{
		$result = array('data' => array());

		$profils_autorises = array('1','2','7','10');

		if($option_2 == '')
		{
			$type_critere = 'mois' ;
		}else{
			$type_critere = 'interval_date' ;
		}


		$data = $this->all_model->get_archives($option_1,$option_2,$type_critere);

		$i = 1 ;

		$cpt = 1 ;

		foreach ($data as $key => $row) {

			if(!empty($row['idenregistremetpatient']))
			{
				$infos_patient = $this->all_model->get_fullrow('patient','idenregistremetpatient',$row['idenregistremetpatient']);
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

			if(!empty($row['medecin_traitant']))
			{
				$infos_medicin = $this->all_model->get_fullrow('medecin','codemedecin',$row['medecin_traitant']);
				
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

		

			// fiche

			$fiche = '';

			if(!empty($row['fichier'])) {
			    
			    $file = $row['fichier'];
                $parts = explode('.', $file);
                $extension = end($parts);
                
                $extension_image = array("jpg", "jpeg", "JPG", "JPEG", "png", "PNG");
			    
			    if(in_array($extension, $extension_image)){
			        $fiche .= '<a href="'.base_url('uploads/archives/'.$row['idenregistremetpatient'].'/'.$row['fichier']).'" data-fancybox="usr'.$i++.'">
	              <img src="'.base_url('uploads/archives/'.$row['idenregistremetpatient'].'/'.$row['fichier']).'" style="height:50px" alt="">
	            </a>';
			    }else{
			        $fiche .= '<a href="'.base_url('uploads/archives/'.$row['idenregistremetpatient'].'/'.$row['fichier']).'" target="_blank" class="btn btn-primary">Visualiser l\'archive</a>';
			    }
	       }

			$result['data'][$key] = array(
				$row['id'],
				$this->fdateheuregmt->date_fr($row['date_archivage']),
				$this->fdateheuregmt->date_fr($row['date']),
				$nompatient,
				$nommedecin,
				$fiche
			);


		} 

		echo json_encode($result);

	}

	public function fetchListeHonoraireData($option_1,$option_2 = null)
	{
		$result = array('data' => array());

		$profils_autorises = array('1','2','7','10');

		if($option_2 == '')
		{
			$type_critere = 'mois' ;
		}else{
			$type_critere = 'interval_date' ;
		}


		$data = $this->all_model->get_liste_honoraire($option_1,$option_2,$type_critere);

		$i = 1 ;

		$cpt = 1 ;

		foreach ($data as $key => $row) {

			if(!empty($row['codemedecin']))
			{
				$infos_medecin = $this->all_model->get_fullrow('medecin','codemedecin',$row['codemedecin']);

				if(!empty($infos_medecin)){
					$nom_medecin = $infos_medecin['nomprenomsmed'];
				}
		
			}

			if(!empty($row['type_honoraire']))
			{
				$infos_prestation = $this->all_model->get_fullrow('prestation_honoraire','id',$row['type_honoraire']);

				if(!empty($infos_prestation)){
					$prestation = $infos_prestation['prestation'];
				}
		
			}

			// button

			$buttons = '';

			if($row['regle'] == 1)
			{
				$status = '<i class="fa fa-circle text-success"></i> Payé' ;
				$buttons = 'Aucune action';
			}else{
				$status = '<i class="fa fa-circle text-danger"></i> Non Payé' ;
				$lien = base_url()."caisse/SortieComptableRegister/".$row['code_honoraire'] ;
				$buttons .= '<a href="'.$lien.'"  class="btn btn-success"><i class="fa fa-money"> Effectuer le paiement </i></a>';
			}

			$result['data'][$key] = array(
				$row['code_honoraire'],
				$prestation,
				$this->fdateheuregmt->date_fr($row['date_execution']),
				$this->fdateheuregmt->date_fr($row['date_debut']),
				$this->fdateheuregmt->date_fr($row['date_fin']),
				$nom_medecin,
				number_format($row['montant_honoraire'],0,',',' ').' F.CFA',
				number_format($row['montant_bnc'],0,',',' ').' F.CFA',
				$status,
				$buttons
			);

		} // /foreach

		echo json_encode($result);
	}
	
	
}
