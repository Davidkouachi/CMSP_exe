<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Caisse extends Admin_Controller {
    
    
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

			if ($curr_uri_string != 'caisse') 
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

	public function point_caisse()
	{
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

            $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'point_caisse';
			$page_data['page_active'] = 'PointCaissePage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Visualiser le point de la caisse';
			
			$page_data['caissieres'] = $this->all_model->get_fullrow_all('user','user_profil_id',5);
			
			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Point de caisse' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;
			
			// affichage de la vue

			$this->render_template('kameleon/point_caisse', $page_data);
	}
	
	public function print_recu()
	{
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

            $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_active'] = 'PointCaissePage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Réimprimer un reçu d\'entrée d\'espèce';

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire d\'impression de reçu d\'entrée d\'espèce' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;
			
			// affichage de la vue

			$this->render_template('kameleon/print_recu', $page_data);
	} 
	
	public function SaveSortieEspece()
	{
		$output = array('error' => false);
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
		    $this->form_validation->set_rules('beneficiaire', 'Nom du bénéficiaire', 'trim|required');
			$this->form_validation->set_rules('numpiece', 'Numéro de la pièce', 'trim|required');
			$this->form_validation->set_rules('nature_operation', 'Nature de l\'opération', 'trim|required');
			$this->form_validation->set_rules('montant_operation', 'Montant de l\'opération', 'trim|required');
			$this->form_validation->set_rules('date_operation', 'Date de l\'opération', 'trim|required');
			
			
		    

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
            
            echo json_encode($output);
        	exit();
        } 
        else 
        {

        	// succès : récupération des données passées en _POST
        	
        	$solde_caisse = $this->all_model->get_solde_caisse(date('Y-m-d')) ;
		    
		    
		    if(!empty($solde_caisse))
		    {
		        if($solde_caisse['mtcaisse'] < trim($this->input->post('montant_operation')))
		        {
		            
		            $output['error'] = true;
                    $output['message'] = "Le solde de la caisse est inférieur au montant de la sortie. Impossibile d'effectuer cette sortie d'espèce.";
                    
                    echo json_encode($output);
                	exit();
		        }
		    }

        	$beneficiaire = trim($this->input->post('beneficiaire'));

        	$numpiece = trim($this->input->post('numpiece'));

        	$nature_operation = trim($this->input->post('nature_operation'));

        	$montant_operation = trim($this->input->post('montant_operation'));

        	$date_operation = $this->input->post('date_operation');

        	//SCRIPT DE GENERATION DU CODE DE SORTIE ***
						do {
								$random_chars="";
								$characters = array(
									"A","B","C","D","E","F","G","H","J","K","L","M",
									"N","P","Q","R","S","T","U","V","W","X","Y","Z",
									"1","2","3","4","5","6","7","8","9");
								$keys = array();
								while(count($keys) < 5) {
									$x = mt_rand(0, count($characters)-1);
									if(!in_array($x, $keys)) 
									{
										$keys[] = $x;
									}
								}

								foreach($keys as $key){
									$random_chars .= $characters[$key];
								}

								$codesortie = 'S'.$random_chars;

								$nbr_res = $this->all_model->get_fullrow('journal','numrecu',$codesortie);

							} while ($nbr_res);
						///FIN DU SCRIPT/***

			/*$this->db->select_max('numjournal');
			$query = $this->db->get('journal');
			$dernier_journal = $query->row_array();*/
			
			$dernier_journal = $this->all_model->get_max_numjournal();

			$numjournal = $dernier_journal['numjournal'] + 1 ;
		

			$data_journal =  array('idenregistremetpatient' => $numpiece,
									'date' => $date_operation,
									'numrecu' => $codesortie,
									'montant_recu' => $montant_operation,
									'numjournal' => $numjournal,
									'type_action' => 1
									);

				$data_caisse = array('nopiece' => $numjournal,
						'type' => 'sortie',
						'libelle' => $nature_operation,
						'montant' => $montant_operation,
						'dateop' => $date_operation,
						'datecreat' => date('Y-m-d'),
						'login' => $this->session->userdata('user_name'),
						'beneficiaire' => $beneficiaire
						);

            $query_1 = $this->all_model->add_ligne('journal',$data_journal);

            $query_2 = $this->all_model->add_ligne('caisse',$data_caisse);
            
            // Mise à jour du solde en caisse (caisse_resume)
					/***************/
					$solde_caisse = $this->all_model->get_solde_caisse(date('Y-m-d')) ;
					
					$nouveau_solde = $solde_caisse['mtcaisse'] - $montant_operation ;
					
					$data_caisse_resume =  array('mtcaisse' => $nouveau_solde);
					
					$this->all_model->update_ligne('caisse_resume', $data_caisse_resume, 'idcaisse', $solde_caisse['idcaisse']) ;
					
					/***************/

            if($query_2)
            {
            	// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Sortie d\'espèce' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'Enrégistrement effectué avec succès.';
            	
            	 echo json_encode($output);
            	 exit();
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'Une erreur s\'est produite lors de l\'enrégistrement. Veuillez reprendre.';
            	
            	echo json_encode($output);
            	 exit();
            }
        }

       
	}
	
	public function SortieDeleter($codeop)
	{
	    $infos_caisse = $this->all_model->get_fullrow('caisse','codeop',$codeop);
	    
	    $annee_operation = substr($infos_caisse['dateop'],0,4) ;
	    
	    $infos_journal = $this->all_model->get_journal_exact($infos_caisse['nopiece'],$annee_operation);
	    
	    if(!empty($infos_journal))
	    {
	        
	        // Mise à jour du solde en caisse (caisse_resume)
			/***************/
			$solde_caisse = $this->all_model->get_solde_caisse(date('Y-m-d')) ;
					
			$nouveau_solde = $solde_caisse['mtcaisse'] + $infos_journal['montant_recu'] ;
					
			$data_caisse_resume =  array('mtcaisse' => $nouveau_solde);
					
			$this->all_model->update_ligne('caisse_resume', $data_caisse_resume, 'idcaisse', $solde_caisse['idcaisse']) ;
					
			/***************/
	    }
	    
		$data_caisse = array('annule' => 1,
						'user_annule' => $this->session->userdata('user_name'),
						'date_annule' => date('Y-m-d')
						);

		$query = $this->all_model->update_ligne('caisse', $data_caisse, 'codeop', $codeop);

        if($query > 0)
        {
        	// Effectuer la journalisation
		        $type_action = 'Suppression' ;

		        $action_effectuee = 'Sortie d\'espèce'.' '.$numjournal ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

        	$output['error'] = false;

            $output['message'] = 'Suppression effectuée avec succès.';

            $flash_feedback = "Suppression effectuée avec succès.";

			$this->session->set_flashdata('success', $flash_feedback);

        }
        else
        {
            $output['error'] = true;
            $output['message'] = 'Aucune suppression n\'a été faite.';

            $flash_feedback = "Aucune suppression n\'a été faite.";

			$this->session->set_flashdata('error', $flash_feedback);
        }


		redirect('caisse/CaisseRegister','refresh');
	}

	public function CaisseUpdater($codeop)
	{
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
		
		$infos_caisse = $this->all_model->get_fullrow('caisse','codeop',$codeop);
		
		$numjournal = $infos_caisse['nopiece'] ;

		$page_data['infos_sortie'] = $this->all_model->get_fullrow('caisse','codeop',$codeop);

		$page_data['infos_journal'] = $this->all_model->get_fullrow('journal','numjournal',$numjournal);
		 
		//$page_data['today_sortie'] = $this->all_model->get_today_sortie();
		
		//$page_data['today_sortie'] = $this->all_model->get_sortie_caisse();
		
		$annee = date(Y) ;
		
		$page_data['today_sortie'] = $this->all_model->get_sortie_annuelle($annee);

        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_name'] = 'CaisseRegister';
		$page_data['page_active'] = 'OpeCaissePage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Gestion des sorties d\'espèces';


		$page_data['titre'] = 'Modifier une sortie d\'espèce';
		$page_data['titre_2'] = 'Liste des sorties du jour';


		// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire d\'ajout de sortie d\'espèces' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;
			
			// affichage de la vue

		$this->render_template('kameleon/CaisseUpdater', $page_data);
	}

	public function UpdateSortieEspece()
	{
		$output = array('error' => false);
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('beneficiaire', 'Nom du produit d\'assurance', 'trim|required');
			$this->form_validation->set_rules('numpiece', 'Nom de l\'assurance', 'trim|required');
			$this->form_validation->set_rules('nature_operation', 'Nom de la société', 'trim|required');
			$this->form_validation->set_rules('montant_operation', 'Nom de la société', 'trim|required');
			$this->form_validation->set_rules('date_operation', 'Nom de la société', 'trim|required');

        if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
        { 
        	// erreur : retour au formulaire
        	$output['error'] = true;
            $output['message'] = validation_errors();
        } 
        else 
        {

        	// succès : récupération des données passées en _POST

        	$beneficiaire = trim($this->input->post('beneficiaire'));

        	$numpiece = trim($this->input->post('numpiece'));

        	$nature_operation = trim($this->input->post('nature_operation'));

        	$montant_operation = trim($this->input->post('montant_operation'));

        	$date_operation = $this->input->post('date_operation');

        	$numjournal = $this->input->post('numero_journal');
        	
        	$id_operation = $this->input->post('id_operation');
        	
        	
           $annee_operation = substr($date_operation,0,4) ;
           
           
           $infos_journal_exact = $this->all_model->get_journal_exact($numjournal,$annee_operation);
		

			$data_journal =  array('idenregistremetpatient' => $numpiece,
									'date' => $date_operation,
									'montant_recu' => $montant_operation
									);

				$data_caisse = array('libelle' => $nature_operation,
						'montant' => $montant_operation,
						'dateop' => $date_operation,
						'login' => $this->session->userdata('user_name'),
						'beneficiaire' => $beneficiaire
						);
						
			

            $query_1 = $this->all_model->update_ligne('journal', $data_journal, 'id', $infos_journal_exact['id']);

            $query_2 = $this->all_model->update_ligne('caisse', $data_caisse, 'codeop', $id_operation);
            
            $infos_journal = $this->all_model->get_fullrow('journal','id',$infos_journal_exact['id']);
            
            if(!empty($infos_journal))
    	    {
    	        
    	        // Mise à jour du solde en caisse (caisse_resume)
    			/***************/
    			$solde_caisse = $this->all_model->get_solde_caisse(date('Y-m-d')) ;
    					
    			$nouveau_solde = $solde_caisse['mtcaisse'] - $infos_journal['montant_recu'] ;
    					
    			$data_caisse_resume =  array('mtcaisse' => $nouveau_solde);
    					
    			$this->all_model->update_ligne('caisse_resume', $data_caisse_resume, 'idcaisse', $solde_caisse['idcaisse']) ;
    					
    			/***************/
    			
    			/***************/
    			$solde_caisse = $this->all_model->get_solde_caisse(date('Y-m-d')) ;
    					
    			$nouveau_solde = $solde_caisse['mtcaisse'] + $montant_operation ;
    					
    			$data_caisse_resume =  array('mtcaisse' => $nouveau_solde);
    					
    			$this->all_model->update_ligne('caisse_resume', $data_caisse_resume, 'idcaisse', $solde_caisse['idcaisse']) ;
    					
    			/***************/
    	    }

            if(($query_2 > 0)&&($query_1 > 0))
            {
            	// Effectuer la journalisation
		        $type_action = 'Modification' ;

		        $action_effectuee = 'Sortie d\'espèces' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

            	$output['message'] = 'Modification effectuée avec succès.';
            }
            else
            {
                $output['error'] = true;
            	$output['message'] = 'Aucune modification n\'a été faite.';
            }
        }

        echo json_encode($output);
	}
		
    public function CaisseRegister()
    {
		if(!empty($_POST))
		{
		    
		    $solde_caisse = $this->all_model->get_solde_caisse(date('Y-m-d')) ;
		    
		    
		    if(!empty($solde_caisse))
		    {
		        if($solde_caisse['mtcaisse'] < trim($this->input->post('montant')))
		        {
		            $this->session->set_flashdata('error', "Le solde de la caisse est inférieur au montant de la sortie. Impossibile d'effectuer cette sortie d'espèce.");
		            
		            redirect('caisse/CaisseRegister');
		            
		            exit();
		        }
		    }
		    
			$data =  array('nopiece' => $this->input->post('noPieceJustif'),
					'type' => $this->input->post('typeOperation'),
					'libelle' => $this->input->post('libelle'),
					'montant' => $this->input->post('montant'),
					'dateop' => $this->input->post('date'),
					'datecreat' => $this->input->post('date'),
					'login' => $this->session->userdata('user_name')
					);

			$this->CaisseModel->CaisseRegister($data);
			
			$montant_sortie = trim($this->input->post('montant')) ;
			
			// Mise à jour du solde en caisse (caisse_resume)
					/***************/
					$solde_caisse = $this->all_model->get_solde_caisse(date('Y-m-d')) ;
					
					$nouveau_solde = $solde_caisse['mtcaisse'] - $montant_sortie ;
					
					$data_caisse_resume =  array('mtcaisse' => $nouveau_solde);
					
					$this->all_model->update_ligne('caisse_resume', $data_caisse_resume, 'idcaisse', $solde_caisse['idcaisse']) ;
					
					/***************/
			
			$this->session->set_flashdata('SUCCESSMSG', "Enregistrement effectué avec succès!!");
			
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

            $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'CaisseRegister';
			$page_data['page_active'] = 'OpeCaissePage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter un mouvement de la caisse';
			
			// affichage de la vue

			$this->render_template('kameleon/CaisseRegister', $page_data);
		}
		else
		{	
		 $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
		 
		 //$page_data['today_sortie'] = $this->all_model->get_today_sortie();

		 //$page_data['today_sortie'] = $this->all_model->get_sortie_caisse();
		 
		 $annee = date('Y') ;
		
		 $page_data['today_sortie'] = $this->all_model->get_sortie_annuelle($annee);

            $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'CaisseRegister';
			$page_data['page_active'] = 'OpeCaissePage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Gestion des sorties d\'espèces';

			$page_data['titre'] = 'Ajouter une sortie d\'espèce';
			$page_data['titre_2'] = 'Liste des sorties';
			
			
			$page_data['solde_caisse'] = $this->all_model->get_solde_caisse(date('Y-m-d')) ;
			
			$page_data['lien'] = base_url() . 'ajax/ouverture_caisse/caisse' ;

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire d\'ajout de sortie d\'espèces' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;
			
			// affichage de la vue

			$this->render_template('kameleon/CaisseRegister', $page_data);
		}
    }

    //////////////////////////////////

    function PointDeCaisse(){

        if (!empty($_POST)) 
        {
        	$date = date('Y-m-d');

        	?>
        	<div class="box box-form">
                <div class="box-footer">
			<hr>
			<center><div style="width: 100%; padding-top: 0px; padding-bottom: 1px; border: 1px solid #005CFF; text-align: center;border-radius: 10px;"><strong> <h4>LISTE DES OPERATIONS DE CAISSE EFFECTUEES CE JOUR </h4> </strong></div>
			</center>
			<br/>
			<div class="affichage">

			  <div class="box-content">
			    <table id="example1" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
			    <thead style="background-color:#07AEBC;">
			    <tr>
			        <th><center>Numéro journal</center></th>
			    	<th><center>Nom Caissière</center></th>
			        <th><center>Libelle</center></th>
			        <th><center>Type de mouvement</center></th>
			        <th><center>Montant</center></th>
			        <th><center>Date Opération</center></th>
			        <th><center>Date Saisie</center></th>
			    </tr>
			    </thead>
			    <tbody>
			    <?php 
			    $PointCaisse = $this->all_model->get_point_caisse($date);
			    
			    $solde_caisse = $this->all_model->get_solde_caisse($date);
        
                if(empty($solde_caisse))
                {
                    $nombre_jour = 1 ;
                    $date_anterieur = $this->fdateheuregmt->date_outil($date,$nombre_jour) ;
                    
                    $solde_caisse = $this->all_model->get_solde_caisse($date_anterieur) ;
                    
                    if(empty($solde_caisse))
                    {
                        $solde_caisse = 0 ;
                    }else{
                        $solde_caisse = $solde_caisse['mtcaisse'] ;
                    }
                    
                }else{
                    
                    $solde_caisse = $solde_caisse['mtcaisse'] ;
                }
			    
			    $infos_ouverture_caisse = $this->all_model->get_ouverture_caisse($date_debut);
			    
			    if($solde_caisse < 0)
			    {
			        $couleur = 'red' ;
			    }
			    
			    if($solde_caisse > 0)
			    {
			        $couleur = 'green' ;
			    }
			    
			    if($solde_caisse == 0)
			    {
			        $couleur = 'black' ;
			    }
								$entree = 0;
								$sortie = 0;
												foreach($PointCaisse as $row)
												{
													$caissiere = $this->usermodel->get_UniqueUser_info($row['login']);
													
													if($row['type'] == 'entree')
													{
														$type = 'Entrée de caisse';
														
														$infos_journal = $this->all_model->get_fullrow('journal','numfac',$row['nopiece']);

														if(!empty($infos_journal))
														{
															$numero_journal = $infos_journal['numjournal'];
														}
														else
														{
															$numero_journal = '';
														}
													}

													if($row['type'] == 'sortie')
													{
														$type = 'Sortie de caisse';
														
														$numero_journal = $row['nopiece'];
													}

													?>
												 <tr>
												     <td><center><?= $numero_journal ?></center></td>
													<td><center><?= $caissiere['user_first_name'].' '.$caissiere['user_last_name'] ?></center></td>
													<td><center><?= strtoupper($row['libelle']) ?></center></td>
													<td><center><?= $type ?></center></td>
													<td><center><?= number_format($row['montant'], 0, '', ' ') ?></center></td>
													<td><center><?= $this->fdateheuregmt->date_fr($row['dateop']) ?></center></td>
													<td><center><?= $this->fdateheuregmt->date_fr($row['datecreat']) ?></center></td>
												  </tr>
											  <?php
											  		if($row['type'] == 'entree')
													{
											  		  $entree = $entree + $row['montant'];
											  		}
											  		if($row['type'] == 'sortie')
													{
											  		  $sortie = $sortie + $row['montant'];
											  		}
												}
											  ?>
											  <tr >
												<td>SOLDE INITIAL DE CAISSE LE <?= $this->fdateheuregmt->date_fr($infos_ouverture_caisse['datecaisse']) ?></td>
												<td style="color: #fff;color:red; font-weight:bold; font-size:18px"><center><?= number_format($infos_ouverture_caisse['mtcaisse'], 0, '', ' ')  ?></center></td>
												<td colspan='1'>TOTAL DES ENTREES DE CAISSE DU <?php echo $this->fdateheuregmt->date_fr($date_debut) ?> AU <?php echo $this->fdateheuregmt->date_fr($date_fin)  ?></td>
												<td style="color: #fff;color:red; font-weight:bold; font-size:18px"><center><?= number_format($entree, 0, '', ' ')  ?></center></td>
												<td colspan='2'>TOTAL DES SORTIES DE CAISSE DU <?php echo $this->fdateheuregmt->date_fr($date_debut) ?> AU <?php echo $this->fdateheuregmt->date_fr($date_fin)  ?></td>
												<td style="color: #fff;color:red; font-weight:bold; font-size:18px"><center><?= number_format($sortie, 0, '', ' ') ?></center></td>
											  </tr>
											  <tr >
												<td colspan='5'><center>SOLDE DE CAISSE LE <?php echo $this->fdateheuregmt->date_fr($date_fin)  ?></center></td>
												<td colspan='2' style="color: #fff;color:<?php echo $couleur ?>; font-weight:bold; font-size:18px"><center><?= number_format($solde_caisse, 0, '', ' ') ?></center></td>
											  </tr>
					
			    </tbody>
			    </table>
			    </div>
			    </div>
			    </div>
			    </div>
<?php
        }
    }
    ///////////////////

    function PointPeriodiqueCaisse(){

        if (!empty($_POST)) 
        {
        	$date_debut = $this->input->post('date_debut');

        	$date_fin = $this->input->post('date_fin');
        	
        	$cassiere = $this->input->post('cassiere');
        

        	?>
        	<div class="box box-form">
                <div class="box-footer">
        	<hr>
		        <form id="login_validator" method="post" action="<?php echo base_url() ?>PrintC/PointCaissePrint" target='_blank' class="form-group">
		            
		            <input class="form-control" name="date_debut" type="hidden" value="<?php echo $date_debut  ?>" />
		            
		            <input class="form-control" name="date_fin" type="hidden" value="<?php echo $date_fin  ?>" />
		            <input class="form-control" name="cassiere" type="hidden" value="<?php echo $cassiere  ?>" />
		        	<button type="submit" id="" class="btn btn-warning">Imprimer le point</button>
		        </form>
			<hr>
			<center><div style="width: 100%; padding-top: 0px; padding-bottom: 1px; border: 1px solid #005CFF; text-align: center;border-radius: 10px;"><strong> <h4>LISTE DES OPERATIONS DE CAISSE EFFECTUEES DU <?php echo $this->fdateheuregmt->date_fr($date_debut) ?> AU <?php echo $this->fdateheuregmt->date_fr($date_fin)  ?> </h4> </strong></div>
			</center>
			<br/>
			<div class="affichage">

			  <div class="box-content">
			    <table id="example1" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
			    <thead style="background-color:#07AEBC;">
			    <tr>
			        <th><center>Numéro journal</center></th>
			    	<th><center>Nom Caissière</center></th>
			        <th><center>Libelle</center></th>
			        <th><center>Type de mouvement</center></th>
			        <th><center>Montant</center></th>
			        <th><center>Date Opération</center></th>
			        <th><center>Date Saisie</center></th>
			    </tr>
			    </thead>
			    <tbody>
			    <?php 

			    $PointCaisse = $this->all_model->get_point_caisse($date_debut,$date_fin,$cassiere);
			    
			    $solde_caisse = $this->all_model->get_solde_caisse($date_fin);
        
                if(empty($solde_caisse))
                {
                    $date = $date_fin ;
                    $nombre_jour = 1 ;
                    $date_anterieur = $this->fdateheuregmt->date_outil($date,$nombre_jour) ;
                    
                    $solde_caisse = $this->all_model->get_solde_caisse($date_anterieur) ;
                    
                    if(empty($solde_caisse))
                    {
                        $solde_caisse = 0 ;
                    }else{
                        $solde_caisse = $solde_caisse['mtcaisse'] ;
                    }
                    
                }else{
                    
                    $solde_caisse = $solde_caisse['mtcaisse'] ;
                }
			    
			    $infos_ouverture_caisse = $this->all_model->get_ouverture_caisse($date_debut);
			    
			    if(empty($infos_ouverture_caisse))
			    {
			       $date = $date_fin ;
                   $nombre_jour = 1 ;
                   $date_anterieur = $this->fdateheuregmt->date_outil($date,$nombre_jour) ;
                    
			       $infos_ouverture_caisse = $this->all_model->get_solde_caisse($date_anterieur); 
			    }
			    
			    if($solde_caisse < 0)
			    {
			        $couleur = 'red' ;
			    }
			    
			    if($solde_caisse > 0)
			    {
			        $couleur = 'green' ;
			    }
			    
			    if($solde_caisse == 0)
			    {
			        $couleur = 'black' ;
			    }
			    
			    
					$entree = 0;
					$sortie = 0;
					foreach($PointCaisse as $row)
					{
						$caissiere = $this->usermodel->get_UniqueUser_info($row['login']);
						
						if(empty($caissiere))
						{
						    $nom_caissiere = '' ;
						    $prenom_caissiere = '';
						    
						}else{
						    $nom_caissiere = $caissiere['user_first_name'] ;
						    $prenom_caissiere = $caissiere['user_last_name'] ;
						}
													
						if($row['type'] == 'entree')
						{
							$type = 'Entrée de caisse';
														
							$infos_journal = $this->all_model->get_fullrow('journal','numfac',$row['nopiece']);

							if(!empty($infos_journal))
							{
								$numero_journal = $infos_journal['numjournal'];
							}
							else
							{
								$numero_journal = '';
							}
						}

						if($row['type'] == 'sortie')
						{
							$type = 'Sortie de caisse';
														
							$numero_journal = $row['nopiece'];
						}

				?>
					<tr>
						<td><center><?= $numero_journal ?></center></td>
						<td><?= $nom_caissiere.' '.$prenom_caissiere ?></td>
						<td><?= strtoupper($row['libelle']) ?></td>
						<td><?= $type ?></td>
						<td><center><?= number_format($row['montant'], 0, '', ' ') ?></center></td>
						<td><center><?= $this->fdateheuregmt->date_fr($row['dateop']) ?></center></td>
						<td><center><?= $this->fdateheuregmt->date_fr($row['datecreat']) ?></center></td>
					</tr>
				<?php
					if($row['type'] == 'entree')
					{
						$entree = $entree + $row['montant'];
					}
					
					if($row['type'] == 'sortie')
					{
						$sortie = $sortie + $row['montant'];
					}
				}
				?>
					<tr>
						<td>SOLDE INITIAL DE CAISSE LE <?= $this->fdateheuregmt->date_fr($date_debut) ?></td>
						<td style="color: #fff;color:red; font-weight:bold; font-size:18px"><center><?= number_format($infos_ouverture_caisse['mtcaisse'], 0, '', ' ')  ?></center></td>
						<td colspan='1'>TOTAL DES ENTREES DE CAISSE DU <?php echo $this->fdateheuregmt->date_fr($date_debut) ?> AU <?php echo $this->fdateheuregmt->date_fr($date_fin)  ?></td>
						<td style="color: #fff;color:red; font-weight:bold; font-size:18px"><center><?= number_format($entree, 0, '', ' ')  ?></center></td>
						<td colspan='2'>TOTAL DES SORTIES DE CAISSE DU <?php echo $this->fdateheuregmt->date_fr($date_debut) ?> AU <?php echo $this->fdateheuregmt->date_fr($date_fin)  ?></td>
						<td style="color: #fff;color:red; font-weight:bold; font-size:18px"><center><?= number_format($sortie, 0, '', ' ') ?></center></td>
					</tr>
					<tr>
						<td colspan='5'><center>SOLDE DE CAISSE LE <?php echo $this->fdateheuregmt->date_fr($date_fin)  ?></center></td>
						<td colspan='2' style="color: #fff;color:<?php echo $couleur ?>; font-weight:bold; font-size:18px"><center><?= number_format($solde_caisse, 0, '', ' ') ?></center></td>
					</tr>
					
			    </tbody>
			    </table>
			    </div>
		    </div>
		</div>
	</div>
<?php
        }
    }
    
    public function transfert_fonds()
    {
		if(!empty($_POST))
		{
			$output = array('error' => false);
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
		    $this->form_validation->set_rules('source', 'Source du transfert', 'trim|required');
			$this->form_validation->set_rules('destination', 'Destination du transfert', 'trim|required');
			$this->form_validation->set_rules('motif_transfert', 'Motif du transfert de fonds', 'trim|required');
			$this->form_validation->set_rules('montant_transfert', 'Montant de l\'opération', 'trim|required');
			$this->form_validation->set_rules('date_operation', 'Date de l\'opération', 'trim|required');

			if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
			{ 
				// erreur : retour au formulaire
				$output['error'] = true;
				$output['message'] = validation_errors();
				
				echo json_encode($output);
				exit();
			} 
			else 
			{
				$source = trim($this->input->post('source'));

				$destination = trim($this->input->post('destination'));

				if($source == $destination)
				{
					$output['error'] = true;
					$output['message'] = "La source et la destination du transfert ne doivent pas être identiques.";
						
					echo json_encode($output);
					exit();
				}

				// succès : récupération des données passées en _POST
				
				$solde_caisse = $this->all_model->get_solde_caisse(date('Y-m-d')) ;

				$infos_banque = $this->all_model->get_table('banque_resume');

				if(empty($infos_banque))
				{
					$output['error'] = true;
					$output['message'] = "Le solde de la banque n'est pas renseigné.";
							
					echo json_encode($output);
					exit();
				}

				$infos_caisse_comptabilite = $this->all_model->get_table('comptabilite_resume');

				if(empty($infos_caisse_comptabilite))
				{
					$output['error'] = true;
					$output['message'] = "Le solde de la caisse comptabilité n'est pas renseigné.";
							
					echo json_encode($output);
					exit();
				}

				
				if($source == 'caisse')
				{
					if(!empty($solde_caisse))
					{
						if($solde_caisse['mtcaisse'] < trim($this->input->post('montant_transfert')))
						{
							
							$output['error'] = true;
							$output['message'] = "Le solde de la caisse est inférieur au montant à transférer. Impossibile d'effectuer ce transfert.";
							
							echo json_encode($output);
							exit();
						}
					}
				}

				

				if($source == 'banque')
				{
					if(!empty($infos_banque))
					{
						if($infos_banque[0]['montant'] < trim($this->input->post('montant_transfert')))
						{
							
							$output['error'] = true;
							$output['message'] = "Le solde de la banque est inférieur au montant à transférer. Impossibile d'effectuer ce transfert.";
							
							echo json_encode($output);
							exit();
						}
					}
				}

				

				if($source == 'comptabilite')
				{
					if(!empty($infos_caisse_comptabilite))
					{
						if($infos_caisse_comptabilite[0]['montant'] < trim($this->input->post('montant_transfert')))
						{
							
							$output['error'] = true;
							$output['message'] = "Le solde de la caisse comptabilité est inférieur au montant à transférer. Impossibile d'effectuer ce transfert.";
							
							echo json_encode($output);
							exit();
						}
					}
				}

			
				$motif_transfert = trim($this->input->post('motif_transfert'));

				$montant_transfert = trim($this->input->post('montant_transfert'));

				$date_operation = $this->input->post('date_operation');


				//SCRIPT DE GENERATION DU CODE DE SORTIE ***
							do {
									$random_chars="";
									$characters = array(
										"A","B","C","D","E","F","G","H","J","K","L","M",
										"N","P","Q","R","S","T","U","V","W","X","Y","Z",
										"1","2","3","4","5","6","7","8","9");
									$keys = array();
									while(count($keys) < 5) {
										$x = mt_rand(0, count($characters)-1);
										if(!in_array($x, $keys)) 
										{
											$keys[] = $x;
										}
									}

									foreach($keys as $key){
										$random_chars .= $characters[$key];
									}

									$codesortie = 'S'.$random_chars;

									$nbr_res = $this->all_model->get_fullrow('journal','numrecu',$codesortie);

								} while ($nbr_res);
							///FIN DU SCRIPT/***

				$type_mouvement_banque = '' ;
				$type_mouvement_caisse = '' ;
				$type_mouvement_comptabilite= '' ;

				$dernier_journal = $this->all_model->get_max_numjournal();

				$numjournal = $dernier_journal['numjournal'] + 1 ;

				$type_action = 1 ;	

				if($source == 'caisse'){
					$type_mouvement_caisse = 'sortie' ;
				}

				if($destination == 'caisse'){
					$type_mouvement_caisse = 'entree' ;
				}

				$data_journal =  array('idenregistremetpatient' => $this->session->userdata('user_name'),
										'date' => $date_operation,
										'numrecu' => $codesortie,
										'montant_recu' => $montant_transfert,
										'numjournal' => $numjournal,
										'type_action' => $type_action
										);

				$data_caisse = array('nopiece' => $numjournal,
							'type' => $type_mouvement_caisse,
							'libelle' => $motif_transfert,
							'montant' => $montant_transfert,
							'dateop' => $date_operation,
							'datecreat' => date('Y-m-d'),
							'login' => $this->session->userdata('user_name')
							);

				$data_banque = array('source' => $source,
							'destination' => $destination,
							'libelle' => $motif_transfert,
							'montant' => $montant_transfert,
							'dateop' => $date_operation,
							'datecrea' => date('Y-m-d'),
							'user' => $this->session->userdata('user_name')
							);

							
				$query_1 = $this->all_model->add_ligne('journal',$data_journal);
				
				$query_3 = $this->all_model->add_ligne('transfert_fonds',$data_banque);
				
				if($type_mouvement_caisse != ''){
					$query_2 = $this->all_model->add_ligne('caisse',$data_caisse);
				}

				// Mise à jour du solde en caisse (caisse_resume)
						/***************/
						$solde_caisse = $this->all_model->get_solde_caisse(date('Y-m-d')) ;
						
						if($source == 'caisse')
						{
							$nouveau_solde = $solde_caisse['mtcaisse'] - $montant_transfert ;
						}
						
						if($destination == 'caisse')
						{
							$nouveau_solde = $solde_caisse['mtcaisse'] + $montant_transfert ;
						}

						
						
						if(($source == 'caisse') || ($destination == 'caisse')){
							$data_caisse_resume =  array('mtcaisse' => $nouveau_solde);
						
							$this->all_model->update_ligne('caisse_resume', $data_caisse_resume, 'idcaisse', $solde_caisse['idcaisse']) ;
						}
						
						/***************/

				// Mise à jour du solde en banque (banque_resume)
						/***************/

						if($source == 'banque')
						{
							$nouveau_solde = $infos_banque[0]['montant'] - $montant_transfert ;
						}

						if($destination == 'banque')
						{
							$nouveau_solde = $infos_banque[0]['montant'] + $montant_transfert ;
						}

						if(($source == 'banque') || ($destination == 'banque')){
							$data_banque_resume =  array('montant' => $nouveau_solde,'date_update' => date('Y-m-d'),'user_update' => $this->session->userdata('user_name'));
						
							$this->all_model->update_ligne('banque_resume', $data_banque_resume, 'id', $infos_banque[0]['id']) ;
						}
						
						/***************/

				// Mise à jour du solde en comptabilite (comptabilite_resume)
						/***************/

						if($source == 'comptabilite')
						{
							$nouveau_solde = $infos_caisse_comptabilite[0]['montant'] - $montant_transfert ;
						}

						if($destination == 'comptabilite')
						{
							$nouveau_solde = $infos_caisse_comptabilite[0]['montant'] + $montant_transfert ;
						}

						if(($source == 'comptabilite') || ($destination == 'comptabilite')){
							$data_comptabilite_resume =  array('montant' => $nouveau_solde,'date_update' => date('Y-m-d'),'user_update' => $this->session->userdata('user_name'));
						
							$this->all_model->update_ligne('comptabilite_resume', $data_comptabilite_resume, 'id', $infos_caisse_comptabilite[0]['id']) ;
						}
						
						/***************/

				if($query_3)
				{
					// Effectuer la journalisation
					$type_action = 'Ajout' ;

					$action_effectuee = 'Transfert de fonds' ;

					$this->control->journalisation($type_action,$action_effectuee) ;

					$output['message'] = 'Enrégistrement effectué avec succès.';
					
					echo json_encode($output);
					exit();
				}
				else
				{
					$output['error'] = true;
					$output['message'] = 'Une erreur s\'est produite lors de l\'enrégistrement. Veuillez reprendre.';
					
					echo json_encode($output);
					exit();
				}
			}
		}
		else
		{	
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
			
			$annee = date('Y') ;
			
			$page_data['today_sortie'] = $this->all_model->get_transfert_annuelle($annee);

            $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'transfert_fonds';
			$page_data['page_active'] = 'TransfondsPage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Gestion des transferts de fonds';

			$page_data['titre'] = 'Ajouter un transfert de fonds';
			$page_data['titre_2'] = 'Liste des tranferts';
			
			
			$page_data['solde_caisse'] = $this->all_model->get_solde_caisse(date('Y-m-d')) ;
			
			$page_data['lien'] = base_url() . 'ajax/ouverture_caisse/banque' ;

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire de gestion transferts de fonds' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;
			
			// affichage de la vue

			$this->render_template('kameleon/transfert_fonds', $page_data);
		}
    }

	public function solde_banque()
    {
		if(!empty($_POST))
		{
			$output = array('error' => false);
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('montant_banque', 'Solde de banque', 'trim|required');

			if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
			{ 
				// erreur : retour au formulaire
				$output['error'] = true;
				$output['message'] = validation_errors();
				
				echo json_encode($output);
				exit();
			} 
			else 
			{
				$montant_banque = trim($this->input->post('montant_banque'));

				$data_banque =  array('date_crea' => date('Y-m-d'),'montant' => $montant_banque);

				$query = $this->all_model->add_ligne('banque_resume',$data_banque);

				if($query)
				{
					// Effectuer la journalisation
					$type_action = 'Ajout' ;

					$action_effectuee = 'Solde de banque' ;

					$this->control->journalisation($type_action,$action_effectuee) ;

					$output['message'] = 'Enrégistrement effectué avec succès.';
					
					echo json_encode($output);
					exit();
				}
				else
				{
					$output['error'] = true;
					$output['message'] = 'Une erreur s\'est produite lors de l\'enrégistrement. Veuillez reprendre.';
					
					echo json_encode($output);
					exit();
				}
			}
		}
		else
		{	
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
			
			$page_data['infos_banque'] = $this->all_model->get_table('banque_resume');

            $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'solde_banque';
			$page_data['page_active'] = 'SoldeBanquePage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Gestion du solde de banque';

			$page_data['titre'] = 'Ajouter le solde de banque';
			$page_data['titre_2'] = 'Infos du solde de banque';

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire de gestion du solde de banque' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;
			
			// affichage de la vue

			$this->render_template('kameleon/solde_banque', $page_data);
		}
    }

	public function SoldeBanqueUpdater($id)
	{
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
		
		$page_data['infos_solde_banque'] = $this->all_model->get_fullrow('banque_resume','id',$id);

		$page_data['infos_banque'] = $this->all_model->get_table('banque_resume');

        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_name'] = 'solde_banque';
		$page_data['page_active'] = 'SoldeBanquePage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Gestion du solde de banque';

		$page_data['titre'] = 'Modifier le solde';
		$page_data['titre_2'] = 'Solde de la banque';


		// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire de modification du solde de banque' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;
			
			// affichage de la vue

		$this->render_template('kameleon/solde_banque_update', $page_data);
	}

	public function UpdateSoldeBanque()
	{

		$output = array('error' => false);
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('montant_banque', 'Solde de banque', 'trim|required');

			if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
			{ 
				// erreur : retour au formulaire
				$output['error'] = true;
				$output['message'] = validation_errors();
				
				echo json_encode($output);
				exit();
			} 
			else 
			{
				$montant_banque = trim($this->input->post('montant_banque'));

				$id = trim($this->input->post('id'));

				$data_banque =  array('montant' => $montant_banque,'date_update' => date('Y-m-d'),'user_update' => $this->session->userdata('user_name'));

				$query = $this->all_model->update_ligne('banque_resume', $data_banque, 'id', $id) ;

				if($query > 0)
				{
					// Effectuer la journalisation
					$type_action = 'Modification' ;

					$action_effectuee = 'Solde de la banque' ;

					$this->control->journalisation($type_action,$action_effectuee) ;

					$output['message'] = 'Modification effectuée avec succès.';
				}
				else
				{
					$output['error'] = true;
					$output['message'] = 'Aucune modification n\'a été faite.';
				}
			}

			echo json_encode($output);
		exit();
		
	}

	public function solde_caisse_comptabilite()
    {
		if(!empty($_POST))
		{
			$output = array('error' => false);
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('montant', 'Solde de la caisse de comptabilite', 'trim|required');

			if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
			{ 
				// erreur : retour au formulaire
				$output['error'] = true;
				$output['message'] = validation_errors();
				
				echo json_encode($output);
				exit();
			} 
			else 
			{
				$montant = trim($this->input->post('montant'));

				$data=  array('date_crea' => date('Y-m-d'),'montant' => $montant);

				$query = $this->all_model->add_ligne('comptabilite_resume',$data);

				if($query)
				{
					// Effectuer la journalisation
					$type_action = 'Ajout' ;

					$action_effectuee = 'Solde de la caisse de comptabilite' ;

					$this->control->journalisation($type_action,$action_effectuee) ;

					$output['message'] = 'Enrégistrement effectué avec succès.';
					
					echo json_encode($output);
					exit();
				}
				else
				{
					$output['error'] = true;
					$output['message'] = 'Une erreur s\'est produite lors de l\'enrégistrement. Veuillez reprendre.';
					
					echo json_encode($output);
					exit();
				}
			}
		}
		else
		{	
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
			
			$page_data['infos_caisse_comptabilite'] = $this->all_model->get_table('comptabilite_resume');

            $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'solde_caisse_comptabilite';
			$page_data['page_active'] = 'SoldeCaisseComptabilitePage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Gestion du solde de la caisse de comptabilité';

			$page_data['titre'] = 'Ajouter le solde de la caisse de comptabilité';
			$page_data['titre_2'] = 'Infos du solde de la caisse de comptabilité';

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire de gestion du solde de la caisse de comptabilite' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;
			
			// affichage de la vue

			$this->render_template('comptabilite/solde_caisse_comptabilite', $page_data);
		}
    }

	public function SoldeCaisseComptabiliteUpdater($id)
	{
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
		
		$page_data['infos_solde_caisse_comptabilite'] = $this->all_model->get_fullrow('comptabilite_resume','id',$id);

		$page_data['infos_caisse_comptabilite'] = $this->all_model->get_table('comptabilite_resume');

        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_name'] = 'solde_caisse_comptabilite';
		$page_data['page_active'] = 'SoldeCaisseComptabilitePage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Gestion du solde de la caisse de comptabilité';

		$page_data['titre'] = 'Modifier le solde';
		$page_data['titre_2'] = 'Solde de la caisse de comptabilité';


		// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire de modification du solde de la caisse de comptabilite' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;
			
			// affichage de la vue

		$this->render_template('comptabilite/solde_caisse_comptabilite_update', $page_data);
	}

	public function UpdateSoldeCaisseComptabilite()
	{

		$output = array('error' => false);
			
			// initialisation du validateur du formulaire
			$this->load->library('form_validation');
			// définition des règles de validation
			
			$this->form_validation->set_rules('montant', 'Solde de la caisse de comptabilite', 'trim|required');

			if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
			{ 
				// erreur : retour au formulaire
				$output['error'] = true;
				$output['message'] = validation_errors();
				
				echo json_encode($output);
				exit();
			} 
			else 
			{
				$montant = trim($this->input->post('montant'));

				$id = trim($this->input->post('id'));

				$data =  array('montant' => $montant,'date_update' => date('Y-m-d'),'user_update' => $this->session->userdata('user_name'));

				$query = $this->all_model->update_ligne('comptabilite_resume', $data, 'id', $id) ;

				if($query > 0)
				{
					// Effectuer la journalisation
					$type_action = 'Modification' ;

					$action_effectuee = 'Solde de la caisse de comptabilite' ;

					$this->control->journalisation($type_action,$action_effectuee) ;

					$output['message'] = 'Modification effectuée avec succès.';
				}
				else
				{
					$output['error'] = true;
					$output['message'] = 'Aucune modification n\'a été faite.';
				}
			}

			echo json_encode($output);
		exit();
		
	}

	public function SortieComptableRegister($code = null)
    {
		 $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));
		
		$page_data['code'] = '';
		$page_data['beneficiaire'] = '';
		$page_data['numpiece'] = '';
		$page_data['nature_operation'] = '';
		$page_data['montant_operation'] = '';
		$page_data['readonly'] = '';

		 if(isset($code)){
			$table = 'honoraire';
			$id_name = 'code_honoraire';
			$id = $code;
			$infos_honoraire = $this->all_model->get_fullrow($table, $id_name, $id);

			if(!empty($infos_honoraire)){

				$infos_prestation = $this->all_model->get_fullrow('prestation_honoraire', 'id', $infos_honoraire['type_honoraire']);
				$infos_medecin = $this->all_model->get_fullrow('medecin', 'codemedecin', $infos_honoraire['codemedecin']);

				$page_data['code'] = $infos_honoraire['code_honoraire'];
				$page_data['beneficiaire'] = $infos_medecin['nomprenomsmed'];
				$page_data['numpiece'] = $infos_honoraire['code_honoraire'];
				$page_data['nature_operation'] = $infos_prestation['libelle_paiement'];
				$page_data['montant_operation'] = $infos_honoraire['montant_honoraire'];
				$page_data['readonly'] = 'readonly';
			}
		 }
		 
		 $critere = 'annuelle' ;
		
		 $page_data['today_sortie'] = $this->all_model->get_transactions_comptables($critere);

            $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'SortieComptableRegister';
			$page_data['page_active'] = 'SortieComptablePage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Gestion des sorties comptables';

			$page_data['titre'] = 'Ajouter une sortie comptable';
			$page_data['titre_2'] = 'Liste des sorties comptables';

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire d\'ajout de sortie comptable' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;
			
			// affichage de la vue

			$this->render_template('comptabilite/sortie_caisse_comptable', $page_data);

    }

	public function SortieComptableSave()
    {
		if(!empty($_POST))
		{
			$this->load->library('form_validation');

			// définition des règles de validation
			
		  $this->form_validation->set_rules('beneficiaire', '<< Nom du bénéficiaire >>', 'trim|required');
		  $this->form_validation->set_rules('numpiece', '<< N° pièce d\'identité >>', 'trim|required');
		  $this->form_validation->set_rules('nature_operation', '<< Nature de l\'opération (Dépense) >>', 'trim|required');
		  $this->form_validation->set_rules('montant_operation', '<< Montant de l\'opération (Dépense) >>', 'trim|required');
		  $this->form_validation->set_rules('source', '<< Source de la sortie >>', 'trim|required');
		  $this->form_validation->set_rules('mode', '<< Mode de sortie >>', 'trim|required');
		  if($this->session->userdata('mode') == 'cheque'){
			$this->form_validation->set_rules('numero_cheque', '<< Numéro de chèque >>', 'trim|required');
		  }
		  
		  $this->form_validation->set_rules('date_operation', '<< Date de l\'opération (Dépense)  >>', 'trim|required');

			if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
		    {
			   // erreur : retour au formulaire
		        $flash_feedback = validation_errors();

				$this->session->set_flashdata('error', $flash_feedback);
							
				redirect('caisse/SortieComptableRegister/'.$this->input->post('code'));
							
				exit();
			}
			else
			{
				$source_sortie = $this->input->post('source');

				if($source_sortie == 'banque'){
					$infos_solde_banque = $this->all_model->get_table('banque_resume');

					if(!empty($infos_solde_banque[0]))
					{
						if($infos_solde_banque[0]['montant'] < trim($this->input->post('montant_operation')))
						{
							$this->session->set_flashdata('error', "Le solde en banque est inférieur au montant de la sortie. Impossibile d'effectuer cette sortie.");
							
							redirect('caisse/SortieComptableRegister/'.$this->input->post('code'));
							
							exit();
						}
					}
				}

				if($source_sortie == 'comptabilite'){
					$infos_solde_caisse_comptable = $this->all_model->get_table('comptabilite_resume');

					if(!empty($infos_solde_caisse_comptable[0]))
					{
						if($infos_solde_caisse_comptable[0]['montant'] < trim($this->input->post('montant_operation')))
						{
							$this->session->set_flashdata('error', "Le solde de la caisse comptable est inférieur au montant de la sortie. Impossibile d'effectuer cette sortie.");
							
							redirect('caisse/SortieComptableRegister/'.$this->input->post('code'));
							
							exit();
						}
					}
				}

				//SCRIPT DE GENERATION DU CODE DE SORTIE ***
				do {
					$random_chars="";
					$characters = array(
						"A","B","C","D","E","F","G","H","J","K","L","M",
						"N","P","Q","R","S","T","U","V","W","X","Y","Z",
						"1","2","3","4","5","6","7","8","9");
					$keys = array();
					while(count($keys) < 5) {
						$x = mt_rand(0, count($characters)-1);
						if(!in_array($x, $keys)) 
						{
							$keys[] = $x;
						}
					}

					foreach($keys as $key){
						$random_chars .= $characters[$key];
					}

					$codesortie = 'S'.$random_chars;

					$nbr_res = $this->all_model->get_fullrow('journal','numrecu',$codesortie);

				} while ($nbr_res);
			///FIN DU SCRIPT/***

				$dernier_journal = $this->all_model->get_max_numjournal();

				$numjournal = $dernier_journal['numjournal'] + 1 ;


				$data_journal =  array('idenregistremetpatient' => $this->input->post('numpiece'),
										'date' => $this->input->post('date_operation'),
										'numrecu' => $codesortie,
										'montant_recu' => $this->input->post('montant_operation'),
										'numjournal' => $numjournal,
										'type_action' => 1
										);

				$query_1 = $this->all_model->add_ligne('journal',$data_journal);
				
				$data =  array('nopiece' => $numjournal,
						'type' => 'sortie',
						'source' => $this->input->post('source'),
						'mode' => $this->input->post('mode'),
						'numero_cheque' => $this->input->post('numero_cheque'),
						'libelle' => $this->input->post('nature_operation'),
						'montant' => $this->input->post('montant_operation'),
						'dateop' => $this->input->post('date_operation'),
						'datecreat' => date('Y-m-d'),
						'login' => $this->session->userdata('user_name'),
						'beneficiaire' => $this->input->post('beneficiaire'),
						);

				$this->all_model->add_ligne('transactions_comptables', $data);
				
				$montant_sortie = trim($this->input->post('montant_operation')) ;

				// Mise à jour du solde en banque (banque_resume)
						/***************/

						if($source_sortie == 'banque')
						{
							$nouveau_solde = $infos_solde_banque[0]['montant'] - $montant_sortie ;
						
							$data_banque_resume =  array('montant' => $nouveau_solde,'date_update' => date('Y-m-d'),'user_update' => $this->session->userdata('user_name'));

							$this->all_model->update_ligne('banque_resume', $data_banque_resume, 'id', $infos_solde_banque[0]['id']) ;
						}
						
						/***************/

				// Mise à jour du solde en comptabilite (comptabilite_resume)
						/***************/

						if($source_sortie == 'comptabilite')
						{
							$nouveau_solde = $infos_solde_caisse_comptable[0]['montant'] - $montant_sortie ;
						
							$data_comptabilite_resume =  array('montant' => $nouveau_solde,'date_update' => date('Y-m-d'),'user_update' => $this->session->userdata('user_name'));
						
							$this->all_model->update_ligne('comptabilite_resume', $data_comptabilite_resume, 'id', $infos_solde_caisse_comptable[0]['id']) ;
						}
						
						/***************/

				// Mise à jour des informations du reglement (honoraire)
						/***************/
						$code = $this->input->post('code') ;
						if(isset($code)){
							$mode_reglement = 2;

							if($this->input->post('mode') == 'espece'){
								$mode_reglement = 0 ;
							}

							if($this->input->post('mode') == 'cheque'){
								$mode_reglement = 1 ;
							}

							$data_honoraire =  array('regle' => 1,
							                        'date_reglement' => date('Y-m-d'),
													'mode_reglement' => $mode_reglement,
													'user_reglement' => $this->session->userdata('user_name'),
													'numero_cheque' => $this->input->post('numero_cheque')
												    );

							$this->all_model->update_ligne('honoraire', $data_honoraire, 'code_honoraire', $code) ;
						}
						
						/***************/
				
				$this->session->set_flashdata('SUCCESSMSG', "Enregistrement effectué avec succès!!");


				redirect('caisse/SortieComptableRegister/');
			}
		}
    }




    //////////////////////
	
} // Fin du controleur
	
