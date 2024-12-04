<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comptabilite extends Admin_Controller {
    
    
    function __construct()
    {
        parent::__construct();				
        // chargement divers
		$this->lang->load('sogemad');

        //include("assets/inc/fdateheuregmt.php");

        // contrôle d'accès
		if (!$this->control->ask_access()) 
		{
			// utilisateur NON authentifié

			$flash_feedback = "Vous n'êtes pas authentifié.";

			$this->session->set_flashdata('warning', $flash_feedback);

			//$curr_uri_string = uri_string();

			$curr_uri_string = $this->uri->segment(1);

			if ($curr_uri_string != 'comptabilite') 
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
		
    public function HonoraireRegister()
    {
		if(!empty($_POST))
		{
			$data =  array('CodUser' => $this->input->post('coduser'),
					'motpasse' => $this->input->post('motpasse'),
					'nom' => $this->input->post('nom'),
					'datpassconnexion' => $this->input->post('datpassconnexion'),
					'profil' => $this->input->post('profil'),
					'acces' => $this->input->post('acces'),
					'actif' => $this->input->post('actif'),
					'contact' => $this->input->post('contact'),
					'id' => $this->input->post('id'),
					'mdpadmin' => $this->input->post('mdpadmin'),
					'activite' => $this->input->post('activite'),
					'datedelaiactivation' => date('Y-m-d H:i:s'),
					);
			$this->HonnoraireModel->HonnoraireRegister($data);
			$this->session->set_flashdata('SUCCESSMSG', "Enregistrement effectué avec succès!!");
			
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'HonoraireRegister';
			$page_data['page_active'] = 'ComptabilitePage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter un Honnoraire';
			
			// affichage de la vue

			$this->render_template('kameleon/HonoraireRegister', $page_data);
		}
		else
		{	
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        	$page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'HonoraireRegister';
			$page_data['page_active'] = 'ComptabilitePage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Ajouter un Honoraire';
			
			// affichage de la vue

			$this->render_template('kameleon/HonoraireRegister', $page_data);
		}
    }

    public function GenerationFacturesMensuelle()
    {
			
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

			$Assurances = $this->PatientModel->getAssurancePatient();

			//$TypePrestations = $this->ParametresModel->getTypePrestations();

			$page_data['societes'] = $this->all_model->get_table('societeassure');

			$page_data['assureurs'] = $this->all_model->get_table('assureur');

        	$page_data['page_libprofil'] = $UniqueProfil;
        	$page_data['Assurances'] = $Assurances;
        	//$page_data['TypePrestations'] = $TypePrestations;
			$page_data['page_name'] = 'FacturesMensuelles';
			$page_data['page_active'] = 'ComptabilitePage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Générer les factures mensuelles';

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire de génération des factures mensuelles' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;
			
			// affichage de la vue

			$this->render_template('kameleon/FacturesMensuelles', $page_data);
    }

    function get_assureurs(){

        if (!empty($_POST)) 
        {
        	$codeassurance = $this->input->post('codeassurance');

        	$codeassurance = trim($codeassurance);

        	$infos_assurance = $this->all_model->get_fullrow('assurance','codeassurance',$codeassurance);

			if($codeassurance == '')
			{
				echo '<div class="alert alert-danger">
			                                <button type="button" class="close" data-dismiss="alert">&times;</button>
			                                <strong>Veuillez sélectionner un établissement payeur.</strong>
			                              </div>';
			                        exit();
			}


			/****************************************/
			if($codeassurance != '')
			{ 
				$infos_societes = $this->all_model->get_diff_assureur($codeassurance);

				if(!empty($infos_societes))
				{

			 ?>
			 <select class="form-control select2" id='assureur' name="assureur">
																	
			      <option selected="seleted" value="">---</option>
			 <?php

			      foreach ($infos_societes as $value) 
			      { 

			      	$infos_assureur = $this->all_model->get_fullrow('assureur','codeassureur',$value['codeassureur']);
			 		
			 		if(!empty($infos_assureur))
			 		{
			 ?>
			      		<option value="<?php echo $infos_assureur['codeassureur'] ?>"><?php echo $infos_assureur['libelle_assureur'] ?></option>";
			  <?php  
			  		}  
			      }
			   ?>
               </select>
               <?php
			    } 
			    else
			    {
			    	?>
			    <select class="form-control select2" id='assureur' name="assureur">
					<option selected="seleted" value="">---</option>
                </select>
					<?php
			    }          
			 
			}
			else
			{
				?>
				<select class="form-control select2" id='assureur' name="assureur">
				    <option selected="seleted" value="">---</option>
				</select>
				<?php
			}		

        }
    }

    function get_societes(){

        if (!empty($_POST)) 
        {
        	$codeassureur = $this->input->post('codeassureur');
        	
        	$codeassurance = $this->input->post('codeassurance');

        	$codeassureur = trim($codeassureur);
        	
        	$codeassurance = trim($codeassurance);

			if($codeassureur == '')
			{
				echo '<div class="alert alert-danger">
			                                <button type="button" class="close" data-dismiss="alert">&times;</button>
			                                <strong>Veuillez sélectionner un assureur.</strong>
			                              </div>';
			                        exit();
			}
			
			if($codeassurance == '')
			{
				echo '<div class="alert alert-danger">
			                                <button type="button" class="close" data-dismiss="alert">&times;</button>
			                                <strong>Veuillez sélectionner une assurance.</strong>
			                              </div>';
			                        exit();
			}


			/****************************************/
			if($codeassureur != '')
			{ 
				//$infos_societes = $this->all_model->get_fullrow_bis('societeassure','codeassureur',$codeassureur);
				
				$infos_societes = $this->all_model->get_societes_facture($codeassureur,$codeassurance);

				if(!empty($infos_societes))
				{

			 ?>
			 <select class="form-control select2" id='societe'  name="societe" >
																	
			      <option selected="seleted" value="">---</option>
			 <?php

			      foreach ($infos_societes as $value) 
			      { 
			 ?>
			      		<option value="<?php echo $value['codesocieteassure'] ?>"><?php echo $value['nomsocieteassure'] ; ?></option>";
			  <?php  
			  		
			        
			      } 
			   ?>
             </select>
             <?php 
			    } 
			    else
			    {
			    	?>
			        <select class="form-control select2" id='societe'  name="societe" >
					<option selected="seleted" value="">---</option>
					</select>
					<?php
			    }         
			 
			}
			else
			{
				?>
				<select class="form-control select2" id='societe'  name="societe" >
					<option selected="seleted" value="">---</option>
				</select>
				<?php
			}		

        }
    }


    function get_societes_bis(){

        if (!empty($_POST)) 
        {
        	$codeassureur = $this->input->post('codeassureur');

        	$codeassureur = trim($codeassureur);

			
				?>
				<option selected="seleted" value="">---</option>
				<?php
			

        }
    }
    
    function get_societes_vide(){

	?>
	
	    <select class="form-control select2" id='societe'  name="societe" >
			<option selected="seleted" value="">---</option>
		</select>
				
    <?php

    }

    public function AffichageFacturesMensuelles()
    {

    	$datedebut = $this->input->post('datedebut');
		$datefin = $this->input->post('datefin');
		$etablissementPayeur = $this->input->post('etablissementPayeur');
		$assureur = $this->input->post('assureur');
		$codesocieteassure = $this->input->post('societe');


		$infos_assurance = $this->all_model->get_fullrow('assurance','codeassurance',$etablissementPayeur);

		if(!empty($infos_assurance))
		{
			if($infos_assurance['mode_impression'] == 1)
			{
				$somme_facture = $this->all_model->get_somme_facture_2($datedebut,$datefin,$etablissementPayeur,$assureur);
			}
			else
			{
				$somme_facture = $this->all_model->get_somme_facture_1($datedebut,$datefin,$codesocieteassure);
			}
		

		if(($somme_facture['somme'] == NULL)||($somme_facture['somme'] == ''))
		{
		    echo '<div role="alert" class="alert alert-danger">
                        <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
                        Aucune facture n\'a été encaissée pour la période spécifiée.
                    </div>';
            echo '*' ;
            echo '0' ;
            exit();
		}

		}

		if($datedebut == '')
		{
			echo '<div role="alert" class="alert alert-danger">
                        <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
                        Vous n\'avez pas sélectionné la date de debut pour la période à facturer.
                    </div>';
            echo '*' ;
            echo '0' ;
                    exit();
		}

		if($datefin == '')
		{
			echo '<div role="alert" class="alert alert-danger">
                        <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
                        Vous n\'avez pas sélectionné la date de fin pour la période à facturer.
                    </div>';
            echo '*' ;
            echo '0' ;
                    exit();
		}


		if($etablissementPayeur == '--')
		{
			echo '<div role="alert" class="alert alert-danger">
                        <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
                        Vous n\'avez pas sélectionné l\'établissement payeur.
                    </div>';
            echo '*' ;
            echo '0' ;
                    exit();
		}

		$etablissement = $this->db->get_where('assurance', array('codeassurance' => $etablissementPayeur))->row_array();

		if($infos_assurance['mode_impression'] == 1)
		{
			$societe_client = $this->db->get_where('assureur', array('codeassureur' => $assureur))->row_array();

			$proprietaire = $societe_client['libelle_assureur'];
		}
		else
		{
			$societe_client = $this->db->get_where('societeassure', array('codesocieteassure' => $codesocieteassure))->row_array();

			$proprietaire = $societe_client['nomsocieteassure'];
		}

		

		$periode_conso = substr($datedebut,0,7);

		$factures_tierspayant = $this->db->get_where('factures_tierspayant', array('idassurance' => $etablissementPayeur,'codesocieteassure' => $codesocieteassure,'periode_conso' => $periode_conso))->row_array();

		if(empty($factures_tierspayant))
		{

			if($infos_assurance['mode_impression'] == 1)
			{
				$somme_facture = $this->all_model->get_somme_facture_2($datedebut,$datefin,$etablissementPayeur,$assureur);
			}
			else
			{
				$somme_facture = $this->all_model->get_somme_facture_1($datedebut,$datefin,$codesocieteassure);
			}

			if(!empty($somme_facture))
			{
				$max_numfac = $this->all_model->get_max_numfac($periode_conso);

				if(empty($max_numfac))
				{
					$numfac_tp = 'F'.$periode_conso.'01' ;
				}
				else
				{
					$partie_numerique = substr($max_numfac['maximum'],-2);

					$lastNumber = $partie_numerique + 1 ;

					$lastNumber ;

					$zeroadd = "".$lastNumber ;

					while (strlen($zeroadd) < 2) {
										
						$zeroadd = "0" . $zeroadd ;
					}
									
					$lastNumber = $zeroadd ;

					$numfac_tp = 'F'.$periode_conso.$lastNumber ;
				}

				$table = 'factures_tierspayant';

				$data = array('numfac_tp' => $numfac_tp,
							'date_crea' => date('Y-m-d'),
							'periode_conso' => $periode_conso,
							'montant_facture' => $somme_facture['somme'],
							'idassurance' => $etablissementPayeur,
							'codesocieteassure' => $codesocieteassure
							);

				$querry = $this->all_model->add_ligne($table, $data);

				// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Facturation mensuelle' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;
			}
			else
			{
				echo '<div role="alert" class="alert alert-danger">
			            <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
			                Il n\'existe pas de facture pour la période choisie.
			        </div>';
			    echo '*' ;
           		echo '0' ;
			    exit();
			}

							
		}
									
				
	  ?>		
	<hr style="height: 1px;color: #F00;background-color: #07AEBC;border: 0;">
		<center><div style="width: 100%; padding-top: 0px; padding-bottom: 1px; border: 1px solid #005CFF; text-align: center;border-radius: 10px;"><strong> <h4>LISTE DES FACTURES A REGLER PAR <?= $etablissement['libelleassurance'].' / '.$proprietaire ; ?></h4> </strong></div>
		</center>
		<br/>
		<div class="affichage">

		  <div class="box-content">
		    <table id="example1" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
		    <thead style="background-color:#07AEBC; color: #fff">
		    <tr>
		        <th><center>Date</center></th>
		        <th><center>N° Facture</center></th>
		        <th><center>Patient</center></th>
		        <th><center>Matricule</center></th>
		        <th><center>Montant Facture</center></th> 
		        <th><center>Ticket Mod.</center></th>
		        <th><center>Net à payer</center></th> 
		    </tr>
		    </thead>
		    <tbody>
		    <?php

	            if($infos_assurance['mode_impression'] == 1)
				{
					$factures = $this->all_model->get_factures_2($datedebut,$datefin,$etablissementPayeur,$assureur);
				}
				else
				{
					$factures = $this->all_model->get_factures_1($datedebut,$datefin,$codesocieteassure);
				}

		    	$montantTotal = 0;
		    	$partAssuranceTotal = 0;
		    	$montantTotalTicket = 0 ;
		    	
		    	$societeassure_1 = '' ;

                $societeassure = '' ;
                
                $tot="";
                
                $mont=0;
                
                $mont_fact = 0 ;
		    	$mont_pat = 0 ;
		    	$mont_ass = 0 ;

				foreach($factures as $row)
				{
					$infoPatient = $this->db->get_where('patient', array('idenregistremetpatient' => $row['idenregistremetpatient']))->row_array();

					$tauxPatient = $this->db->get_where('tauxcouvertureassure', array('idtauxcouv' => $infoPatient['idtauxcouv']))->row_array();
					
					$societeassure_1 = $infoPatient['codesocieteassure'] ;

	                if($societeassure != $societeassure_1) ///INSERTION DE LA PREMIERE LIGNE DANS LE TABLEAU
	                {
	                    $infos_societeassure = $this->all_model->get_table_where('societeassure','codesocieteassure', $societeassure_1);

	                    if(!empty($infos_societeassure))
	                    {
	                        $nom_societe = $infos_societeassure[0]['nomsocieteassure'] ;
	                    }else{
	                        $nom_societe = '' ;
	                    }
	                    
	                    echo $tot;

	                    echo '<tr>
		                          <td colspan="7">'.$nom_societe.'</td>
		                      </tr>';
		                      
		                $tot ="";
		                $mont_fact = 0 ;
        		    	$mont_pat = 0 ;
        		    	$mont_ass = 0 ;
		            }
                  		
                  		
						echo   '<tr>
								    <td><center>'.$this->fdateheuregmt->date_fr($row['datefacture']).'</center></td>
								    <td><center>'.$row['numfac'].'</center></td>
									<td><center>'.$infoPatient['nomprenomspatient'].'</center></td>
									<td><center>'.$infoPatient['matriculeassure'].'</center></td>
									<td><center>'.number_format($row['montanttotal'],0,',',' ').'</center></td>
									<td><center>'.number_format($row['montant_pat'],0,',',' ').'</center></td>
									<td><center>'.number_format($row['montant_ass'],0,',',' ').'</center></td>
								</tr>';
									$montantTotal = $montantTotal + $row['montanttotal'] ;
									$montantTotalTicket = $montantTotalTicket + $row['montant_pat'] ;
		    						$partAssuranceTotal = $partAssuranceTotal + $row['montant_ass'] ;
		    						
		    	        $mont_fact = $mont_fact+$row['montanttotal'];
		    	        $mont_pat = $mont_pat+$row['montant_pat'];
		    	        $mont_ass = $mont_ass+$row['montant_ass'];

		            	$tot = '<tr>
		            	    <td colspan="4"><center>SOUS TOTAL [ '.$nom_societe.' ] </center></td>
		                    <td><center>'.number_format($mont_fact,0,',',' ').'</center></td>
		                    <td><center>'.number_format($mont_pat,0,',',' ').'</center></td>
		                    <td><center>'.number_format($mont_ass,0,',',' ').'</center></td>
		                </tr>';
		                
		                $societeassure = $societeassure_1 ;

		            
						
				}
				
				        
				
				echo $tot ;
			
						echo   '<tr>
									<td colspan=4><center>TOTAL RELEVE [ '.$etablissement['libelleassurance'].' / '.$proprietaire.' ]</center></td>
									<td><center>'.number_format($montantTotal,0,',',' ').'</center></td>
									<td><center>'.number_format($montantTotalTicket,0,',',' ').'</center></td>
									<td><center>'.number_format($partAssuranceTotal,0,',',' ').'</center></td>
								</tr>						
		    				</tbody>
		    			</table>
		    		</div>
		    	</div>';

		    	echo '*' ;
            echo '1' ;
    }
    
    public function Honoraire_medecin_form()
    {
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

		$page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_active'] = 'StatistiqueListingPage';
		$page_data['page_profil'] =  $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Listing des actes des medecins';
		
		$page_data['medecins'] = $this->all_model->get_table('medecin') ;

		$page_data['prestations'] = $this->all_model->get_table('prestation_honoraire') ;

		// Effectuer la journalisation
			$type_action = 'Consultation' ;

			$action_effectuee = 'Formulaire de visualisation des actes effectués par medecin.' ;

			$this->control->journalisation($type_action,$action_effectuee) ;
        
		// affichage de la vue

        $this->render_template('statistiques/honoraire_medecin_form', $page_data);
    }
    
    public function liste_honoraires()
    {	
		 $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

		 //$page_data['factures'] = $this->all_model->get_table('factures');

         $page_data['page_libprofil'] = $UniqueProfil;
		 $page_data['page_active'] = 'liste_honorairePage';
		 $page_data['page_profil'] = $this->session->userdata('user_profil');
		 $page_data['page_title'] = 'Lostro Admin';
		 $page_data['page_s_title'] = 'Liste des honoraires validés';

		$page_data['date_debut'] = '';

        $page_data['date_fin'] = '';

		 $page_data['type_critere'] = 'mois';

		 $page_data['namePg'] = 'fetchListeHonoraireData';

		 $page_data['option_1'] = date('Ym');

		 $page_data['option_2'] = '';

		 // Effectuer la journalisation

		    $type_action = 'Consultation' ;

		    $action_effectuee = 'Liste des honoraires validés' ;

		    $this->control->journalisation($type_action,$action_effectuee) ;

		$this->render_template('comptabilite/liste_honoraires', $page_data);
    }

	public function honoraire_periode()
    {	
    	// initialisation du validateur du formulaire

          $this->load->library('form_validation');

        // définition des règles de validation
            
           $this->form_validation->set_rules('date_debut', 'Date de debut', 'trim|required|xss_clean');

           $this->form_validation->set_rules('date_fin', 'Date de fin', 'trim|required|xss_clean');

           if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
           {
               // erreur : retour au formulaire

                $flash_feedback = validation_errors();

                $this->session->set_flashdata('error', $flash_feedback);

                redirect('Comptabilite/liste_honoraires');

            }
            else
            {
				 $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

		         $page_data['page_libprofil'] = $UniqueProfil;
				 $page_data['page_active'] = 'liste_honorairePage';
				 $page_data['page_profil'] = $this->session->userdata('user_profil');
				 $page_data['page_title'] = 'Lostro Admin';
				 $page_data['page_s_title'] = 'Liste des honoraires';

				 $page_data['type_critere'] = 'periode';

				 $page_data['namePg'] = 'fetchListeHonoraireData';

				 $page_data['date_debut'] = $date_debut = $this->input->post('date_debut');

                 $page_data['date_fin'] = $date_fin = $this->input->post('date_fin');

				 $page_data['option_1'] = $date_debut ;

				 $page_data['option_2'] = $date_fin;

				 // Effectuer la journalisation
				        $type_action = 'Consultation' ;

				        $action_effectuee = 'Liste des honoraires validés' ;

				        $this->control->journalisation($type_action,$action_effectuee) ;
					
					// affichage de la vue

				$this->render_template('comptabilite/liste_honoraires', $page_data);
			}
    }

	public function etat_honoraires_payes_form()
    {	
		 $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

		 //$page_data['factures'] = $this->all_model->get_table('factures');

         $page_data['page_libprofil'] = $UniqueProfil;
		 $page_data['page_active'] = 'etat_honoraires_payesPage';
		 $page_data['page_profil'] = $this->session->userdata('user_profil');
		 $page_data['page_title'] = 'Lostro Admin';
		 $page_data['page_s_title'] = 'Visu. etat des honoraires payés';

		$page_data['date_debut'] = '';

        $page_data['date_fin'] = '';

		 // Effectuer la journalisation

		    $type_action = 'Consultation' ;

		    $action_effectuee = 'Formulaire pour visualiser l\'état des honoraires payés' ;

		    $this->control->journalisation($type_action,$action_effectuee) ;

		$this->render_template('comptabilite/etat_honoraires_payes_form', $page_data);
    }

}
	
