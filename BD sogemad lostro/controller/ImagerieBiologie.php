<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ImagerieBiologie extends Admin_Controller {
    
    
    function __construct()
    {
          parent::__construct();
		// chargement divers
		$this->lang->load('sogemad');

        //include("assets/inc/fdateheuregmt.php");
    	//include("assets/inc/conversion.php");

    	// contrôle d'accès
		if (!$this->control->ask_access()) 
		{
			// utilisateur NON authentifié

			$flash_feedback = "Vous n'êtes pas authentifié.";

			$this->session->set_flashdata('warning', $flash_feedback);

			//$curr_uri_string = uri_string();

			$curr_uri_string = $this->uri->segment(1);

			if ($curr_uri_string != 'imagerieBiologie') 
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

public function Save_img_bio()
{
    $this->load->library('form_validation');

	// définition des règles de validation
			
	$this->form_validation->set_rules('idenregistremetpatientBio', '<< Nom du patient >>', 'trim|required');
	$this->form_validation->set_rules('codemedecinBio', '<< Nom du Medecin >>', 'trim|required');
	$this->form_validation->set_rules('codemedecinTraitant', '<< Nom du Medecin traitant >>', 'trim');
	$this->form_validation->set_rules('numbon', '<< Bon de prise en charge >>', 'trim');
	$this->form_validation->set_rules('valeurB', '<< Valeur du B >>', 'trim|required');
	$this->form_validation->set_rules('valeurZ', '<< Valeur du Z >>', 'trim|required');
	$this->form_validation->set_rules('renseiclinik', '<< Renseignements Cliniques >>', 'trim|required');
	$this->form_validation->set_rules('voit', '<< Type Examen >>', 'trim|required');

	if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
    {
		// erreur : retour au formulaire
		$flash_feedback = validation_errors();

		$this->session->set_flashdata('DANGERMSG', $flash_feedback);
			    
		echo '<div class="alert alert-danger">
		      <button type="button" class="close" data-dismiss="alert">&times;</button>
		      <strong>'.validation_errors().'</strong>
		      </div>';
		exit();
	}
	else
	{
		 $voit         = $this->input->post('voit');

		 $valeurB      = $this->input->post('valeurB');

		 $valeurZ      = $this->input->post('valeurZ');

		 $idpatientfac = $this->input->post('idenregistremetpatientBio');
		 
		 $medecin      = $this->input->post('codemedecinBio');
		 
		 $medecin      = $this->input->post('codemedecinBio');

		 $medecin_traitant      = $this->input->post('codemedecinTraitant');
		  
		 $renseiclinik = $this->input->post('renseiclinik');

		 $mode_patient = $this->input->post('mode_patient');

		 $typeExam     = $voit ;

		 $numexam = $this->input->post('numexam');

		 $typedemande = $typeExam ;
		
		$date = $this->fdateheuregmt->dateheure(3);    

		$heure = $this->fdateheuregmt->dateheure(5);

		//TEST DE LA VALIDITE DES VALEURS RECUPEREES

		if($idpatientfac == '')
		{
		    echo '<div class="alert alert-danger">
		                                <button type="button" class="close" data-dismiss="alert">&times;</button>
		                                <strong>Aucun matricule n\'a été sélectionné.</strong><br/>
		                                <strong>Veuillez reprendre l\'oprération.</strong>
		                              </div>';
		                        exit();
		}


		if($medecin == '')
		{
		    echo '<div class="alert alert-danger">
		                                <button type="button" class="close" data-dismiss="alert">&times;</button>
		                                <strong>Le champ du médecin n\'a pas été renseigné.</strong><br/>
		                                <strong>Veuillez le renseigner avant d\'envoyer la demande.</strong>
		                              </div>';
		                        exit();
		}

		if($typeExam == '')
		{
		    echo '<div class="alert alert-danger">
		                                <button type="button" class="close" data-dismiss="alert">&times;</button>
		                                <strong>Vous n\'avez pas choisi le type de l\'examen.</strong><br/>
		                                <strong>Veuillez bien le faire avant d\'imprimer la facture.</strong>
		                              </div>';
		                        exit();
		}



		//TRAITEMENTS POUR INTERAGIR AVEC LA BD

		/****************************************/

		if(($typeExam == 'rad') || ($typeExam == 'exam'))
		{
		    if($renseiclinik == '')
		    {
		        echo '<div class="alert alert-danger">
		                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
		                                    <strong>Vous n\'avez pas mentionné de rensignements cliniques.</strong><br/>
		                                    <strong>Veuillez bien le faire avant d\'envoyer la demande.</strong>
		                                  </div>';
		                            exit();
		    }

		}

		$typedemande = $typeExam ;

		if($typedemande == 'rad')
		{
		    $typedemande = 'imagerie' ;
		}

		if($typedemande == 'exam')
		{
		    $typedemande = 'analyse' ;   
		}

		// SCRIPT INSERTION DANS LA BASE DE DONNEES

		/**********************************************************************************************************/

		if(empty($numexam))
		{

			echo '<div class="alert alert-danger">
		                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
		                                    <strongAucun examen n\'a été sélectioné pour ce bulletin. Veuillez donc sélectionner les examens demandés par le médecin au niveau de la zone prévue à cet effet "Liste des examens biologiques".</strong>
		                                  </div>';
		                            exit();

		}
		else
		{

			if($voit=='rad')		 
			{
				$this->db->select_max('idtestlaboimagerie');
				$this->db->like('idtestlaboimagerie', 'IMG-' , 'both');
				$query = $this->db->get('testlaboimagerie');

				$res = $query->row_array();

				$extrac = substr($res['idtestlaboimagerie'], 4, 8);
				                        
				                        
				$numero = $extrac + 1 ;

				$str = "" . $numero ;
				                        
				while(strlen($str) < 8)
				                        
				{
				                            
				    $str = "0" . $str;
				                        
				}
				$bar = '-' ;    

				$numero = 'IMG'.$bar. $str ;

				    $date = $this->fdateheuregmt->dateheure(3);    

				    $heure = $this->fdateheuregmt->dateheure(5);

				    $this->db->select_max('numfacbul');
					$query = $this->db->get('testlaboimagerie');

					$dernier_fac = $query->row_array();

				    //S IL S AGIT DU PREMIER NUMERO DE FAC

				    if($dernier_fac['numfacbul'] == "")
				    {
				        
				        $decoupe = explode("-", $date);

				        $annee = $decoupe[0];
				        $annee_coup = substr($annee, 3, 2);
				        $numfac = "FCB".$annee_coup.'0'.'0'.'0'.'1';
				        
				    } 
				    else 
				    if($dernier_fac['numfacbul'] != "")
				    {//S'IL EXISTE DEJA NUMERO DE FAC
				                
				    //CREATION DU NOUVEAU NUMERO DE FAC

				    $decoupe = explode("-", $date);
				    $annee = $decoupe[0];
				    $annee_coup = substr($annee, 3, 2);
				    $dern_nombre = substr($dernier_fac['numfacbul'], 5, 4);
				    $nouv_nombre = $dern_nombre + 1;
				    $str = "" . $nouv_nombre;
				    while(strlen($str) < 4)
				    {
				        $str = "0" . $str;
				    }

				    $matn = $str;

				    $numfac = "FCB".$annee_coup.$matn;
				    }

				    $data =  array('idtestlaboimagerie' => $numero,
								   'idenregistremetpatient' => $idpatientfac,
								   'codemedecin' => $medecin,
								   'typedemande' => $typedemande,
								   'renseigclini' => $renseiclinik,
								   'date' => $date,
								   'heure' => $heure,
								   'numfacbul' => $numfac,
								   'numbon' => $numbon,
								   'medicin_traitant' => $medecin_traitant
									);

				    $this->db->insert('testlaboimagerie',$data);


				     /////APPEL DE LA FONCTION MOUCHARD
				            // mouchard($connexion,$nombd,'demandeprischar',1,$_SESSION['coduser']

				if($valeurZ != 0)
				{
					if($mode_patient == 0)
    				{
					  $this->db->select('*');
					  $this->db->from('patient');
					  $this->db->join('tarifs', 'patient.codeassurance = tarifs.codeassurance');
					  $this->db->where('patient.idenregistremetpatient', $idpatientfac);
					  $this->db->where('tarifs.codgaran','Z');
					  $resPrix = $this->db->get()->row_array();
					}

				    if($mode_patient == 1)
				    {
				        $this->db->select('*');
				        $this->db->from('tarifs');
				        $this->db->where('tarifs.codgaran','Z');
				        $this->db->where('tarifs.codeassurance','NONAS');
				        $resPrix = $this->db->get()->row_array();
				    }

				}

				$mtn = 0;

				foreach ($numexam as $value) 
				{

					$resultat = $this->all_model->get_fullrow('examen', 'numexam', $value);
				 
				    $numexam = $resultat['numexam'];
				    $cot = $resultat['cot'];
				    $denomination = $resultat['denomination'];  
				    $prix = $resultat['prix']; 
				    $famille = $resultat['codfamexam'];
				         
				    if($famille == 'Z')
				    {
				        if($valeurZ != 0)
				        {
				            //$prix = $resPrix['montjour'] * $res1['cot']; 
				            $prix = $resPrix['montjour'] * $cot; 
				        }
				        else
				        {
				            //$prix = $res1['prix']; 
				            $prix = $resPrix['montjour'] * $cot;
				        }
				    }

				    if($famille == 'Y')
				    {
				        $result_exam = $this->db->get_where('examen' , array('numexam'=>$numexam))->row_array();
            
                        if(!empty($result_exam))
                        {
                            $result_assurance = $this->db->get_where('patient' , array('idenregistremetpatient'=>$idpatientfac))->row_array();
                            
                            if($mode_patient == 0)
                			{
                            	if(!empty($result_assurance))
							   {
							   		if(!empty($result_assurance['codeproduit']))
							   		{
							   			$codeproduit = $result_assurance['codeproduit'] ;
							   		}
							   		else
							   		{
							   			$codeproduit = "" ;
							   		}	
							   }
							   else
							   {
									$codeproduit = "" ;
							   }

							   if(empty($codeproduit))
							   {
							   		$result_exam = $this->db->get_where('tarifs' , array('codgaran'=>$result_exam['codgaran'],'codeassurance'=>$result_assurance['codeassurance']))->row_array();
							   }
							   else
							   {
							   		$result_exam = $this->db->get_where('tarifs' , array('codgaran'=>$result_exam['codgaran'],'codeassurance'=>$result_assurance['codeassurance'],'codeproduit'=>$codeproduit))->row_array();
							   }
                            }

                            if($mode_patient == 1)
			                {
			                    $result_exam = $this->db->get_where('tarifs' , array('codgaran'=>$result_exam['codgaran'],'codeassurance'=>'NONAS'))->row_array();
			                }

                            $prix =  $result_exam['montjour'];
                        }
				    }
				      	 //Recherche de la cotation de l'analyse
				    $table = 'detailtestlaboimagerie';
					$id_name = 'iddetailtestlaboimagerie';

					$resultatId = $this->all_model->getMaxId($table,$id_name);

					if($resultatId)
					{
						$comma_separated = implode(",", $resultatId);

						$resultatId = intval($comma_separated);

						$code_detail = $resultatId + 1 ;
					}
					else
					{
						$code_detail = 1 ;
					}
				         
					$datadetail =  array('iddetailtestlaboimagerie' => $code_detail,
									'idtestlaboimagerie' => $numero,
								   'numexam' => $numexam,
								   'denomination' => $denomination,
								   'cotation' => $cot,
								   'prix' => $prix
									);

				    $this->db->insert('detailtestlaboimagerie',$datadetail); 

				         $mtn = $mtn + $prix ; 
				}

				// Rechercher le taux de couverture du patient

				$info_taux = $this->PatientModel->get_taux_patient($idpatientfac);

				if($mode_patient == 0)
			    {
					$tauxAssurance = $info_taux['valeurtaux'] / 100 ;

					$tauxMod = 100 - $info_taux['valeurtaux'];
				}

			    if($mode_patient == 1)
			    {
			        $tauxMod = 100 ;

			        $tauxAssurance = 0 ;
			    }


			    $ticket_moderateur = $mtn - ($mtn * $tauxAssurance);

				$part_assurance = $mtn * $tauxAssurance ;
	  

				 $datedujour = date('Y-m-d');

				 $datafacture =  array('numfac' => $numfac,
									'idenregistremetpatient' => $idpatientfac,
								   'montanttotal' => $mtn,
								   'montant_ass' => $part_assurance,
								   'montant_pat' => $ticket_moderateur,
								   'codeassurance' => $resPrix['codeassurance'],
								   'datefacture' => $datedujour,
								   'type_facture' => 2
									);
				 
				 	$this->db->insert('factures',$datafacture);

				 	// Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Facture d\'imagerie' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

				    echo '<div class="alert alert-success">
				                                <button type="button" class="close" data-dismiss="alert">&times;</button>
				                                <strong>Bulletin d\'imagerie enrégistré avec succès.</strong>
				                                <input type="hidden" class="form-control" id="numbulletin" value="'.$numero.'">
				                                <input type="hidden" class="form-control" id="bonSaved" value="1">
				                              </div>';
				                        exit();	       
			}

			/**********************************************************************************************************/

			if($voit=='exam')
			{
				$this->db->select_max('idtestlaboimagerie');
				$this->db->like('idtestlaboimagerie', 'BIO-' , 'both');
				$query = $this->db->get('testlaboimagerie');

				$res = $query->row_array();

				$extrac = substr($res['idtestlaboimagerie'], 4, 8);
				                        
				                        
				$numero = $extrac + 1 ;

				$str = "" . $numero ;
				                        
				while(strlen($str) < 8)                       
				{                           
				    $str = "0" . $str;                       
				}
				$bar = '-' ; 

				$numero = 'BIO'.$bar. $str ;

			    $date = $this->fdateheuregmt->dateheure(3);    

			    $heure = $this->fdateheuregmt->dateheure(5);

			    $this->db->select_max('numfacbul');

				$query = $this->db->get('testlaboimagerie');

				$dernier_fac = $query->row_array();

			    //S IL S AGIT DU PREMIER NUMERO DE FAC

			    if($dernier_fac['numfacbul'] == "")
			    {
			        
			        $decoupe = explode("-", $date);

			       $annee = $decoupe[0];
			       $annee_coup = substr($annee, 3, 2);
			       $numfac = "FCB".$annee_coup.'0'.'0'.'0'.'1';
			        
			    } 
			    else 
			    if($dernier_fac['numfacbul'] != "")
			    {//S'IL EXISTE DEJA NUMERO DE FAC
			                
			    //CREATION DU NOUVEAU NUMERO DE FAC

			    $decoupe = explode("-", $date);
			    $annee = $decoupe[0];
			    $annee_coup = substr($annee, 3, 2);
			    $dern_nombre = substr($dernier_fac['numfacbul'], 5, 4);
			    $nouv_nombre = $dern_nombre + 1;
			                    $str = "" . $nouv_nombre;
			                            while(strlen($str) < 4)
			                            {
			                                $str = "0" . $str;
			                            }
			                            $matn = $str;

			    $numfac = "FCB".$annee_coup.$matn;
			    }

			 	$data =  array('idtestlaboimagerie' => $numero,
							   'idenregistremetpatient' => $idpatientfac,
							   'codemedecin' => $medecin,
							   'typedemande' => $typedemande,
							   'renseigclini' => $renseiclinik,
							   'date' => $date,
							   'heure' => $heure,
							   'numfacbul' => $numfac,
							   'numbon' => $numbon,
							    'medicin_traitant' => $medecin_traitant
								);

			 	$this->db->insert('testlaboimagerie',$data);


				if($valeurB != 0)
				{
				  $infos_patient = $this->all_model->get_fullrow('patient','idenregistremetpatient',$idpatientfac);
				    
				    if(!empty($infos_patient))
				    {
				        if(!empty($infos_patient['codeproduit']))
				        {
				            $this->db->select('*');
        				    $this->db->from('patient');
        				    $this->db->join('tarifs', 'patient.codeassurance = tarifs.codeassurance');
        				    $this->db->where('patient.idenregistremetpatient', $idpatientfac);
        				    $this->db->where('tarifs.codgaran','B');
        				    $this->db->where('tarifs.codeproduit',$infos_patient['codeproduit']);
        				    $resPrix = $this->db->get()->row_array();
				        }else{
				            $this->db->select('*');
        				    $this->db->from('patient');
        				    $this->db->join('tarifs', 'patient.codeassurance = tarifs.codeassurance');
        				    $this->db->where('patient.idenregistremetpatient', $idpatientfac);
        				    $this->db->where('tarifs.codgaran','B');
        				    $resPrix = $this->db->get()->row_array();
				        }
				    }else{
				        $this->db->select('*');
        				$this->db->from('patient');
        				$this->db->join('tarifs', 'patient.codeassurance = tarifs.codeassurance');
        				$this->db->where('patient.idenregistremetpatient', $idpatientfac);
        				$this->db->where('tarifs.codgaran','B');
        				$resPrix = $this->db->get()->row_array();
				    }
				}
				else
				{
					$this->db->select('*');
			        $this->db->from('tarifs');
			        $this->db->where('tarifs.codgaran','B');
			        $this->db->where('tarifs.codeassurance','NONAS');
			        $resPrix = $this->db->get()->row_array();
				}

				$mtn = 0;

				foreach ($numexam as $value) 
				{

					$resultat = $this->all_model->get_fullrow('examen', 'numexam', $value);
				 
				    $numexam = $resultat['numexam']; 
				    $cot = $resultat['cot'];   
				    $denomination = $resultat['denomination'];
				    
				    if($mode_patient == 0)
    				{
    					$prix = $resPrix['montjour'] * $resultat['cot'];
    				}

    				if($mode_patient == 1)
    				{
    					$prix = $resultat['prix'];
    				}
				         
				    /*if($valeurB != 0)
				    {
				        $prix = $resPrix['montjour'] * $resultat['cot']; 
				    }
				    else
				    {
				        //$prix = $resultat['prix']; 
				        $prix = $resPrix['montjour'] * $resultat['cot'];
				    }*/  

				        $table = 'detailtestlaboimagerie';
						$id_name = 'iddetailtestlaboimagerie';

						$resultatId = $this->all_model->getMaxId($table,$id_name);

						if($resultatId)
						{
							$comma_separated = implode(",", $resultatId);

							$resultatId = intval($comma_separated);

							$code_detail = $resultatId + 1 ;
						}
						else
						{
							$code_detail = 1 ;
						}

				        $datadetail =  array('iddetailtestlaboimagerie' => $code_detail,
								   'idtestlaboimagerie' => $numero,
								   'numexam' => $numexam,
								   'denomination' => $denomination,
								   'cotation' => $cot,
								   'prix' => $prix
								);

				  $this->db->insert('detailtestlaboimagerie',$datadetail);

				          $mtn = $mtn + $prix ; 
				}
				
				if($mode_patient == 0)
    			{
					$prelevement = $this->db->get_where('tarifs' , array('codgaran'=>'PSANG','codeassurance'=>$resPrix['codeassurance']))->row_array();
					
					$montant_prelevement = $prelevement['montjour'];
				}

				if($mode_patient == 1)
    			{
					$prelevement = $this->db->get_where('tarifs', array('codgaran'=>'PSANG','codeassurance'=>'NONAS'))->row_array();
					
					//$montant_prelevement = $prelevement['montjour'];

					$montant_prelevement = 0;
				}


				if(!empty($prelevement))
				{
				    $mtn = $mtn + $montant_prelevement ;
				}

				// Rechercher le taux de couverture du patient

				$info_taux = $this->PatientModel->get_taux_patient($idpatientfac);

				if($mode_patient == 0)
			    {
					$tauxAssurance = $info_taux['valeurtaux'] / 100 ;

					$tauxMod = 100 - $info_taux['valeurtaux'];
				}

			    if($mode_patient == 1)
			    {
			        $tauxMod = 100 ;

			        $tauxAssurance = 0 ;
			    }


			    $ticket_moderateur = $mtn - ($mtn * $tauxAssurance);

				$part_assurance = $mtn * $tauxAssurance ;

				$datedujour = date('Y-m-d');

				$datafacture =  array('numfac' => $numfac,
									'idenregistremetpatient' => $idpatientfac,
								   'montanttotal' => $mtn,
								   'montant_ass' => $part_assurance,
								   'montant_pat' => $ticket_moderateur,
								   'codeassurance' => $resPrix['codeassurance'],
								   'datefacture' => $datedujour,
								   'type_facture' => 2
									);

				 $this->db->insert('factures',$datafacture);

				 // Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Facture de biologie' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

				 echo '<div class="alert alert-success">
				                                <button type="button" class="close" data-dismiss="alert">&times;</button>
				                                <strong>Bulletin de biologie enrégistré avec succès.</strong>
				                                <input type="hidden" class="form-control" id="numbulletin" value="'.$numero.'">
				                                <input type="hidden" class="form-control" id="bonSaved" value="1">
				                              </div>';
				exit();         
			}
		}
	}
	
}

public function demande_examen($type_facture,$id_demande="")
{
		if($id_demande == "")
		{
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	        $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'demande_examen_register';
			$page_data['page_active'] = 'SoinAmbulatoirePage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Enregistrer et imprimer une facture d\'examen';

			$page_data['list_admission'] =  $this->all_model->get_table('admission');

			$page_data['list_medecins'] =  $this->all_model->get_table('medecin');

			$page_data['list_patients'] =  $this->all_model->get_table('patient');
			
			$page_data['taux'] =  $this->all_model->get_table('tauxcouvertureassure');

			switch ($type_facture) {
				case 'bio':
					$page_data['examens_biologiques'] =  $this->all_model->get_fullrow_all('examen','codfamexam','B');

					$page_data['choix'] = 'save_biologie';

					break;
				case 'img':
					$page_data['examens_biologiques'] =  $this->all_model->get_fullrow_all('examen','codfamexam','Y');

					$page_data['choix'] = 'save_imagerie';

					break;
				
				default:
					# code...
					break;
			}
				// affichage de la vue

	        $this->render_template('kameleon/demande_examen_register_bis', $page_data);
		}else{

			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	        $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'demande_examen_register';
			$page_data['page_active'] = 'SoinAmbulatoirePage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Modifier une facture d\'examen';

			$result = array();
        	$orders_data = $this->all_model->get_fullrow('testlaboimagerie','idtestlaboimagerie',$id_demande);

        	$page_data['infos_facture'] = $this->all_model->get_fullrow('factures','numfac',$orders_data['numfacbul']);

    		$result['order'] = $orders_data;

    		$orders_item = $this->all_model->get_fullrow_all('detailtestlaboimagerie','idtestlaboimagerie',$id_demande);
    		foreach($orders_item as $k => $v) {
    			$result['order_item'][] = $v;
    		}

    		$page_data['order_data'] = $result;

			$page_data['list_admission'] =  $this->all_model->get_table('admission');

			$page_data['list_medecins'] =  $this->all_model->get_table('medecin');

			$page_data['list_patients'] =  $this->all_model->get_table('patient');
			
			$page_data['taux'] =  $this->all_model->get_table('tauxcouvertureassure');
			
			$infos_patient = $this->all_model->get_fullrow('patient','idenregistremetpatient',$orders_data['idenregistremetpatient']);
			
			if(($orders_data['mode_patient'] == 0) && ($infos_patient['assure'] == 1))
			{
			    $infos_patient = $this->all_model->getTauxCouverture($orders_data['idenregistremetpatient']);
			    
			    if(!empty($infos_patient))
			    {
			      $page_data['taux_couverture'] = $infos_patient['valeurtaux'] ;
			    }else{
			        
			        $page_data['taux_couverture'] = 0 ;
			    }
			}
			
			if(($orders_data['mode_patient'] == 1) && ($infos_patient['assure'] == 1))
			{
			    $page_data['taux_couverture'] = 0 ;
			}
			
			if($infos_patient['assure'] == 0)
			{
			    $page_data['taux_couverture'] = 0 ;
			}
			
			
			switch ($type_facture) {
				case 'bio':
					$page_data['examens_biologiques'] =  $this->all_model->get_fullrow_all('examen','codfamexam','B');

					$page_data['choix'] = 'edit_biologie';

					break;
				case 'img':
					$page_data['examens_biologiques'] =  $this->all_model->get_fullrow_all('examen','codfamexam','Y');

					$page_data['choix'] = 'edit_imagerie';

					break;
				
				default:
					# code...
					break;
			}

			
				// affichage de la vue

	        $this->render_template('kameleon/demande_examen_edit', $page_data);

		}
	}
	
	public function demande_examen_patient($type_facture,$id_patient)
{
		if(isset($id_patient))
		{
			$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

	        $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'demande_examen_register';
			$page_data['page_active'] = 'SoinAmbulatoirePage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'Enregistrer et imprimer une facture d\'examen';

			$page_data['list_admission'] =  $this->all_model->get_table('admission');

			$page_data['list_medecins'] =  $this->all_model->get_table('medecin');

			$page_data['list_patients'] =  $this->all_model->get_table('patient');
			
			$page_data['taux'] = $this->all_model->get_table('tauxcouvertureassure') ;

			if(isset($id_patient))
			{
				$infos_patient =  $this->all_model->get_fullrow('patient','idenregistremetpatient',$id_patient);
				if(!empty($infos_patient))
				{
					$page_data['type_patient'] =  $infos_patient['assure'];
				}else{
					$page_data['type_patient'] =  0;
				}
				$page_data['code_patient'] =  $id_patient;
			}else{
				$page_data['code_patient'] = "";

				$page_data['type_patient'] =  0;
			}

			switch ($type_facture) {
				case 'bio':
					$page_data['examens_biologiques'] =  $this->all_model->get_fullrow_all('examen','codfamexam','B');

					$page_data['choix'] = 'save_biologie';

					break;
				case 'img':
					$page_data['examens_biologiques'] =  $this->all_model->get_fullrow_all('examen','codfamexam','Y');

					$page_data['choix'] = 'save_imagerie';

					break;
				
				default:
					# code...
					break;
			}
				// affichage de la vue

	        $this->render_template('kameleon/demande_examen_register_patient', $page_data);
		}
}

	public function getTableProductRow()
	{
		$products = $this->all_model->get_fullrow_all('examen','codfamexam','B');
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

	public function getBiologieValueById()
	{
		$biologie_id = $this->input->post('biologie_id');
		if($biologie_id) {
			$biologie_data = $this->all_model->getBiologieData($biologie_id);
			echo json_encode($biologie_data);
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


	public function create()
	{
		$this->data['page_title'] = 'ENREGISTRER ET IMPRIMER UNE FACTURE D\'EXAMEN ';

		$choix = $this->input->post('choix');

		if($choix == 'save_biologie')
		{
			$this->form_validation->set_rules('biologie[]', 'Nom du produit', 'trim|required');
		}

		$this->form_validation->set_rules('idenregistremetpatientBio', '<< Nom du patient >>', 'trim|required');
		$this->form_validation->set_rules('codemedecinBio', '<< Nom du Medecin >>', 'trim|required');
		$this->form_validation->set_rules('codemedecinTraitant', '<< Nom du Medecin traitant >>', 'trim');
		$this->form_validation->set_rules('numbon', '<< Bon de prise en charge >>', 'trim');

		if($choix == 'save_biologie')
		{
			$this->form_validation->set_rules('valeurB', '<< Valeur du B >>', 'trim|required');
		}
		
		$this->form_validation->set_rules('renseiclinik', '<< Renseignements Cliniques >>', 'trim|required');
		$this->form_validation->set_rules('num_admission', '<< Numéro d\'admission >>', 'trim');
		
	
        if ($this->form_validation->run() == TRUE) {   

			$valeurB      = $this->input->post('valeurB');

			$valeurZ      = $this->input->post('valeurZ');

			$idpatientfac = $this->input->post('idenregistremetpatientBio');
			 
			$medecin      = $this->input->post('codemedecinBio');

			$medecin_traitant      = $this->input->post('codemedecinTraitant');
			  
			$renseiclinik = $this->input->post('renseiclinik');

			$mode_patient = $this->input->post('mode_patient');

			$prelevement_sanguin = $this->input->post('prelevement_sanguin');

			$type_remise = $this->input->post('type_remise');

			$remise = $this->input->post('remise');

			$montant_total = $this->input->post('montant_total');

			$part_assurance = $this->input->post('part_assurance');

			$ticket_moderateur = $this->input->post('ticket_moderateur');

			$montant_prelevement = $this->input->post('montant_prelevement');

			$codeassurance = $this->input->post('codeassurance');

			$num_admission = $this->input->post('num_admission');

			$numbon = $this->input->post('numbon');
			
			$date = $this->fdateheuregmt->dateheure(3);    

			$heure = $this->fdateheuregmt->dateheure(5); 
			
			$calcul_applique = $this->input->post('type_calcul_montant');
			$taux_applique = $this->input->post('taux_couverture');

			switch ($choix) {
				case 'save_biologie':
					
					$this->db->select_max('idtestlaboimagerie');
					$this->db->like('idtestlaboimagerie', 'BIO-' , 'both');
					$query = $this->db->get('testlaboimagerie');

					$res = $query->row_array();

					$extrac = substr($res['idtestlaboimagerie'], 4, 8);
					                        
					                        
					$numero = $extrac + 1 ;

					$str = "" . $numero ;
					                        
					while(strlen($str) < 8)                       
					{                           
					    $str = "0" . $str;                       
					}
					$bar = '-' ; 

					$numero = 'BIO'.$bar. $str ;

				    $date = $this->fdateheuregmt->dateheure(3);    

				    $heure = $this->fdateheuregmt->dateheure(5);

				    $this->db->select_max('numfacbul');

					$query = $this->db->get('testlaboimagerie');

					$dernier_fac = $query->row_array();

				    //S IL S AGIT DU PREMIER NUMERO DE FAC

				    if($dernier_fac['numfacbul'] == "")
				    {
				        
				        $decoupe = explode("-", $date);

				       $annee = $decoupe[0];
				       $annee_coup = substr($annee, 3, 2);
				       $numfac = "FCB".$annee_coup.'0'.'0'.'0'.'1';
				        
				    } 
				    else if($dernier_fac['numfacbul'] != ""){//S'IL EXISTE DEJA NUMERO DE FAC
				                
				    //CREATION DU NOUVEAU NUMERO DE FAC

				    $decoupe = explode("-", $date);
				    $annee = $decoupe[0];
				    $annee_coup = substr($annee, 3, 2);
				    $dern_nombre = substr($dernier_fac['numfacbul'], 5, 4);
				    $nouv_nombre = $dern_nombre + 1;
				                    $str = "" . $nouv_nombre;
				                            while(strlen($str) < 4)
				                            {
				                                $str = "0" . $str;
				                            }
				                            $matn = $str;

				    $numfac = "FCB".$annee_coup.$matn;
				    }

				    $typedemande = 'analyse' ;

				 	$data =  array('idtestlaboimagerie' => $numero,
								   'idenregistremetpatient' => $idpatientfac,
								   'codemedecin' => $medecin,
								   'typedemande' => $typedemande,
								   'renseigclini' => $renseiclinik,
								   'date' => $date,
								   'heure' => $heure,
								   'numfacbul' => $numfac,
								   'numbon' => $numbon,
								   'medicin_traitant' => $medecin_traitant,
								   'numhospit' => $num_admission,
								   'prelevement' => $prelevement_sanguin,
								   'mode_patient' => $mode_patient
									);

				 	$this->db->insert('testlaboimagerie',$data);

					$count_examens = count($this->input->post('biologie'));

			    	for($x = 0; $x < $count_examens; $x++) {

			    		$resultat = $this->all_model->get_fullrow('examen', 'numexam', $this->input->post('biologie')[$x]);
					
					    $numexam = $resultat['numexam']; 

					    $denomination = $resultat['denomination'];

					        $datadetail =  array('idtestlaboimagerie' => $numero,
												   'numexam' => $numexam,
												   'denomination' => $denomination,
												   'cotation' => $this->input->post('cotation')[$x],
												   'prix' => $this->input->post('amount_value')[$x]
												);

			    		$this->db->insert('detailtestlaboimagerie',$datadetail);
			    	}

					$datedujour = date('Y-m-d');

					if($remise == 0)
					{
						$type_remise = 0 ;
					}

					if(!empty($num_admission))
					{
						$a_encaisser = 1 ;
					}else{
						$a_encaisser = 0 ;
					}

					$datafacture =  array('numfac' => $numfac,
										'idenregistremetpatient' => $idpatientfac,
									   'montanttotal' => $montant_total,
									   'remise' => $remise,
									   'type_remise' => $type_remise,
									   'calcul_applique' => $calcul_applique,
									   'taux_applique' => $taux_applique,
									   'montant_ass' => $part_assurance,
									   'montant_pat' => $ticket_moderateur,
									   'codeassurance' => $codeassurance,
									   'datefacture' => $datedujour,
									   'type_facture' => 2,
									   'a_encaisser' => $a_encaisser
										);

					 $this->db->insert('factures',$datafacture);

					 // Effectuer la journalisation
			        $type_action = 'Ajout' ;

			        $action_effectuee = 'Facture de biologie' ;

			        $this->control->journalisation($type_action,$action_effectuee) ;

			        $this->session->set_flashdata('success', 'Facture de biologie enrégistrée avec succès.');


		        	redirect('ImagerieBiologie/demande_examen/bio/'.$numero, 'refresh');

					break;

				case 'save_imagerie':

					$this->db->select_max('idtestlaboimagerie');
					$this->db->like('idtestlaboimagerie', 'IMG-' , 'both');
					$query = $this->db->get('testlaboimagerie');
					$res = $query->row_array();

					$extrac = substr($res['idtestlaboimagerie'], 4, 8);              
					                        
					$numero = $extrac + 1 ;

					$str = "" . $numero ;
					                        
					while(strlen($str) < 8)
					{                   
					    $str = "0" . $str;                 
					}

					$bar = '-' ;    

					$numero = 'IMG'.$bar. $str ;

					$date = $this->fdateheuregmt->dateheure(3);    

					$heure = $this->fdateheuregmt->dateheure(5);

					$this->db->select_max('numfacbul');
					$query = $this->db->get('testlaboimagerie');

					$dernier_fac = $query->row_array();

					//S IL S AGIT DU PREMIER NUMERO DE FAC

					if($dernier_fac['numfacbul'] == "")
					{
					        
					    $decoupe = explode("-", $date);

					    $annee = $decoupe[0];
					    $annee_coup = substr($annee, 3, 2);
					    $numfac = "FCB".$annee_coup.'0'.'0'.'0'.'1';
					        
					}else if($dernier_fac['numfacbul'] != ""){//S'IL EXISTE DEJA NUMERO DE FAC
					                
					    //CREATION DU NOUVEAU NUMERO DE FAC

					    $decoupe = explode("-", $date);
					    $annee = $decoupe[0];
					    $annee_coup = substr($annee, 3, 2);
					    $dern_nombre = substr($dernier_fac['numfacbul'], 5, 4);
					    $nouv_nombre = $dern_nombre + 1;
					    $str = "" . $nouv_nombre;

					    while(strlen($str) < 4)
					    {
					        $str = "0" . $str;
					    }

					    $matn = $str;

					    $numfac = "FCB".$annee_coup.$matn;
					}

					$typedemande = 'imagerie' ;

				 	$data =  array('idtestlaboimagerie' => $numero,
								   'idenregistremetpatient' => $idpatientfac,
								   'codemedecin' => $medecin,
								   'typedemande' => $typedemande,
								   'renseigclini' => $renseiclinik,
								   'date' => $date,
								   'heure' => $heure,
								   'numfacbul' => $numfac,
								   'numbon' => $numbon,
								   'medicin_traitant' => $medecin_traitant,
								   'numhospit' => $num_admission
									);

				 	$this->db->insert('testlaboimagerie',$data);

				 	$count_examens = count($this->input->post('biologie'));

			    	for($x = 0; $x < $count_examens; $x++) {

			    		$resultat = $this->all_model->get_fullrow('examen', 'numexam', $this->input->post('biologie')[$x]);
					
					    $numexam = $resultat['numexam']; 

					    $denomination = $resultat['denomination'];

					        $datadetail =  array('idtestlaboimagerie' => $numero,
												   'numexam' => $numexam,
												   'denomination' => $denomination,
												   'cotation' => $this->input->post('cotation')[$x],
												   'prix' => $this->input->post('amount_value')[$x]
												);

			    		$this->db->insert('detailtestlaboimagerie',$datadetail);
			    	}

					$datedujour = date('Y-m-d');

					if($remise == 0)
					{
						$type_remise = 0 ;
					}

					if(!empty($num_admission))
					{
						$a_encaisser = 1 ;
					}else{
						$a_encaisser = 0 ;
					}

					$datafacture =  array('numfac' => $numfac,
										'idenregistremetpatient' => $idpatientfac,
									   'montanttotal' => $montant_total,
									   'remise' => $remise,
									   'type_remise' => $type_remise,
									   'calcul_applique' => $calcul_applique,
									   'taux_applique' => $taux_applique,
									   'montant_ass' => $part_assurance,
									   'montant_pat' => $ticket_moderateur,
									   'codeassurance' => $codeassurance,
									   'datefacture' => $datedujour,
									   'type_facture' => 2,
									   'a_encaisser' => $a_encaisser
										);

					 $this->db->insert('factures',$datafacture);

					 // Effectuer la journalisation
			        $type_action = 'Ajout' ;

			        $action_effectuee = 'Facture d\'imagerie' ;

			        $this->control->journalisation($type_action,$action_effectuee) ;

			        $this->session->set_flashdata('success', 'Facture d\'imagerie enrégistrée avec succès.');


		        	redirect('ImagerieBiologie/demande_examen/img/'.$numero, 'refresh');

					
					break;
				
				default:
					# code...
					break;
			}
        }else{

        	if($choix == 'save_biologie')
        	{
        		$type_facture = 'bio' ;
        	}else{
        		$type_facture = 'img' ;
        	}
			   // erreur : retour au formulaire
		        $flash_feedback = validation_errors();

				$this->session->set_flashdata('error', $flash_feedback);

		    redirect('ImagerieBiologie/demande_examen/'.$type_facture.'', 'refresh');

		    exit();
        }	
	}


	public function update()
	{
		$this->data['page_title'] = 'ENREGISTRER ET IMPRIMER UNE FACTURE D\'EXAMEN ';

		$choix = $this->input->post('choix');

		if($choix == 'edit_biologie')
		{
			$this->form_validation->set_rules('biologie[]', 'Nom du produit', 'trim|required');
		}

		$this->form_validation->set_rules('idenregistremetpatientBio', '<< Nom du patient >>', 'trim|required');
		$this->form_validation->set_rules('codemedecinBio', '<< Nom du Medecin >>', 'trim|required');
		$this->form_validation->set_rules('codemedecinTraitant', '<< Nom du Medecin traitant >>', 'trim');
		$this->form_validation->set_rules('numbon', '<< Bon de prise en charge >>', 'trim');
		$this->form_validation->set_rules('numero', '<< Numéro demande >>', 'trim');

		if($choix == 'edit_biologie')
		{
			$this->form_validation->set_rules('valeurB', '<< Valeur du B >>', 'trim|required');
		}
		
		$this->form_validation->set_rules('renseiclinik', '<< Renseignements Cliniques >>', 'trim|required');
		$this->form_validation->set_rules('num_admission', '<< Numéro d\'admission >>', 'trim');
		
	
        if ($this->form_validation->run() == TRUE) {   

			$valeurB      = $this->input->post('valeurB');

			$valeurZ      = $this->input->post('valeurZ');

			$idpatientfac = $this->input->post('idenregistremetpatientBio');
			 
			$medecin      = $this->input->post('codemedecinBio');

			$medecin_traitant      = $this->input->post('codemedecinTraitant');
			  
			$renseiclinik = $this->input->post('renseiclinik');

			$mode_patient = $this->input->post('mode_patient');

			$prelevement_sanguin = $this->input->post('prelevement_sanguin');

			$type_remise = $this->input->post('type_remise');

			$remise = $this->input->post('remise');

			$montant_total = $this->input->post('montant_total');

			$part_assurance = $this->input->post('part_assurance');

			$ticket_moderateur = $this->input->post('ticket_moderateur');

			$montant_prelevement = $this->input->post('montant_prelevement');

			$codeassurance = $this->input->post('codeassurance');

			$num_admission = $this->input->post('num_admission');

			$numbon = $this->input->post('numbon');

			$numero = $this->input->post('numero');
			
			$date = $this->fdateheuregmt->dateheure(3);    

			$heure = $this->fdateheuregmt->dateheure(5); 

			switch ($choix) {
				case 'edit_biologie':
					
				    $typedemande = 'analyse' ;

				 	$data =  array('idtestlaboimagerie' => $numero,
								   'idenregistremetpatient' => $idpatientfac,
								   'codemedecin' => $medecin,
								   'typedemande' => $typedemande,
								   'renseigclini' => $renseiclinik,
								   'date' => $date,
								   'heure' => $heure,
								   'numbon' => $numbon,
								   'medicin_traitant' => $medecin_traitant,
								   'numhospit' => $num_admission,
								   'prelevement' => $prelevement_sanguin,
								   'mode_patient' => $mode_patient
									);

				 	$this->db->insert('testlaboimagerie',$data);

				 	// now remove the order item data 
					$this->db->where('idtestlaboimagerie', $numero);
					$this->db->delete('detailtestlaboimagerie');

					$count_examens = count($this->input->post('biologie'));

			    	for($x = 0; $x < $count_examens; $x++) {

			    		$resultat = $this->all_model->get_fullrow('examen', 'numexam', $this->input->post('biologie')[$x]);
					
					    $numexam = $resultat['numexam']; 

					    $denomination = $resultat['denomination'];

					        $datadetail =  array('idtestlaboimagerie' => $numero,
												   'numexam' => $numexam,
												   'denomination' => $denomination,
												   'cotation' => $this->input->post('cotation')[$x],
												   'prix' => $this->input->post('amount_value')[$x]
												);

			    		$this->db->insert('detailtestlaboimagerie',$datadetail);
			    	}

					$datedujour = date('Y-m-d');

					if($remise == 0)
					{
						$type_remise = 0 ;
					}

					if((!empty($num_admission)) || ($num_admission != ''))
					{
						$a_encaisser = 1 ;
					}else{
						$a_encaisser = 0 ;
					}
					

					$infos_testlabo = $this->all_model->get_fullrow('testlaboimagerie', 'idtestlaboimagerie', $numero);

					if(!empty($infos_testlabo))
					{
						$numfac = $infos_testlabo['numfacbul'] ;

						$datafacture =  array('numfac' => $numfac,
											'idenregistremetpatient' => $idpatientfac,
										   'montanttotal' => $montant_total,
										   'remise' => $remise,
										   'type_remise' => $type_remise,
										   'montant_ass' => $part_assurance,
										   'montant_pat' => $ticket_moderateur,
										   'codeassurance' => $codeassurance,
										   'datefacture' => $datedujour,
										   'type_facture' => 2,
										   'a_encaisser' => $a_encaisser
											);

						$this->db->insert('factures',$datafacture);
					}

					

					 // Effectuer la journalisation
			        $type_action = 'Modification' ;

			        $action_effectuee = 'Facture de biologie' ;

			        $this->control->journalisation($type_action,$action_effectuee) ;

			        $this->session->set_flashdata('success', 'Facture de biologie modifiée avec succès.');


		        	redirect('ImagerieBiologie/demande_examen/bio/'.$numero, 'refresh');

					break;

				case 'edit_imagerie':

					$typedemande = 'imagerie' ;

				 	$data =  array('idtestlaboimagerie' => $numero,
								   'idenregistremetpatient' => $idpatientfac,
								   'codemedecin' => $medecin,
								   'typedemande' => $typedemande,
								   'renseigclini' => $renseiclinik,
								   'date' => $date,
								   'heure' => $heure,
								   'numbon' => $numbon,
								   'medicin_traitant' => $medecin_traitant,
								   'numhospit' => $num_admission
									);

				 	$this->all_model->update_ligne('testlaboimagerie', $data, 'idtestlaboimagerie', $numero) ;

				 	// now remove the order item data 
					$this->db->where('idtestlaboimagerie', $numero);
					$this->db->delete('detailtestlaboimagerie');

				 	$count_examens = count($this->input->post('biologie'));

			    	for($x = 0; $x < $count_examens; $x++) {

			    		$resultat = $this->all_model->get_fullrow('examen', 'numexam', $this->input->post('biologie')[$x]);
					
					    $numexam = $resultat['numexam']; 

					    $denomination = $resultat['denomination'];

					        $datadetail =  array('idtestlaboimagerie' => $numero,
												   'numexam' => $numexam,
												   'denomination' => $denomination,
												   'cotation' => $this->input->post('cotation')[$x],
												   'prix' => $this->input->post('amount_value')[$x]
												);

			    		$this->db->insert('detailtestlaboimagerie',$datadetail);
			    	}


					$datedujour = date('Y-m-d');

					if($remise == 0)
					{
						$type_remise = 0 ;
					}

					if((!empty($num_admission)) || ($num_admission != ''))
					{
						$a_encaisser = 1 ;
					}else{
						$a_encaisser = 0 ;
					}

					$infos_testlabo = $this->all_model->get_fullrow('testlaboimagerie', 'idtestlaboimagerie', $numero);

					if(!empty($infos_testlabo))
					{
						$numfac = $infos_testlabo['numfacbul'] ;

						$datafacture =  array('numfac' => $numfac,
											'idenregistremetpatient' => $idpatientfac,
										   'montanttotal' => $montant_total,
										   'remise' => $remise,
										   'type_remise' => $type_remise,
										   'montant_ass' => $part_assurance,
										   'montant_pat' => $ticket_moderateur,
										   'codeassurance' => $codeassurance,
										   'datefacture' => $datedujour,
										   'type_facture' => 2,
										   'a_encaisser' => $a_encaisser
											);

						 $this->all_model->update_ligne('factures', $datafacture, 'numfac', $numfac) ;
					}

					 // Effectuer la journalisation
			        $type_action = 'Modification' ;

			        $action_effectuee = 'Facture d\'imagerie' ;

			        $this->control->journalisation($type_action,$action_effectuee) ;

			        $this->session->set_flashdata('success', 'Facture d\'imagerie modifiée avec succès.');


		        	redirect('ImagerieBiologie/demande_examen/img/'.$numero, 'refresh');

					
					break;
				
				default:
					# code...
					break;
			}
        }else{

        	if($choix == 'edit_biologie')
        	{
        		$type_facture = 'bio' ;
        	}else{
        		$type_facture = 'img' ;
        	}
			   // erreur : retour au formulaire
		        $flash_feedback = validation_errors();

				$this->session->set_flashdata('error', $flash_feedback);

		    redirect('ImagerieBiologie/demande_examen/'.$type_facture.'', 'refresh');

		    exit();
        }	
	}


	

	public function get_valeur_prelevement()
	{
		$patient_id = $this->input->post('patient_id');
		
		if($patient_id) {

			$infos_patient = $this->all_model->get_fullrow('patient','idenregistremetpatient',$patient_id);
				    
			if(!empty($infos_patient))
			{
				if($infos_patient['assure'] == 1)
				{
				    if(!empty($infos_patient['codeproduit']))
					{
					    /*$this->db->select('*');
	        			$this->db->from('patient');
	        			$this->db->join('tarifs', 'patient.codeassurance = tarifs.codeassurance');
	        			$this->db->where('patient.idenregistremetpatient', $patient_id);
	        			$this->db->where('tarifs.codgaran','PSANG');
	        			$this->db->where('tarifs.codeproduit',$infos_patient['codeproduit']);
	        			$resPrix = $this->db->get()->row_array();*/
	        			
	        			$codgaran = 'PSANG';
	        			
	        			$codeassurance = $infos_patient['codeassurance'];
	        			
	        			$codeproduit = $infos_patient['codeproduit'];
	        			
	        			$resPrix = $this->all_model->get_tarif_double_bis($codgaran,$codeassurance,$codeproduit);
					}else{
					    /*$this->db->select('*');
	        			$this->db->from('patient');
	        			$this->db->join('tarifs', 'patient.codeassurance = tarifs.codeassurance');
	        			$this->db->where('patient.idenregistremetpatient', $patient_id);
	        			$this->db->where('tarifs.codgaran','PSANG');
	        			$resPrix = $this->db->get()->row_array();*/
	        			
	        			$codgaran = 'PSANG';
	        			
	        			$codeassurance = $infos_patient['codeassurance'];
	        			
	        			$resPrix = $this->all_model->get_tarif_double($codgaran,$codeassurance);
					}
				}else{

				    $this->db->select('*');
					$this->db->from('tarifs');
					$this->db->where('tarifs.codgaran','PSANG');
					$this->db->where('tarifs.codeassurance','NONAS');
					$resPrix = $this->db->get()->row_array();

				}
				        
			}else{
				$this->db->select('*');
        		$this->db->from('patient');
        		$this->db->join('tarifs', 'patient.codeassurance = tarifs.codeassurance');
        		$this->db->where('patient.idenregistremetpatient', $patient_id);
        		$this->db->where('tarifs.codgaran','PSANG');
        		$resPrix = $this->db->get()->row_array();
			}

			echo json_encode($resPrix);
		}
	}

	public function gestion_facture() 
	{
    		$ListePatient = $this->PatientModel->getPatient();

	        $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));


	        $page_data['page_libprofil'] = $UniqueProfil;

	        $page_data['page_liste_patient'] = $ListePatient;

			$page_data['bandeau'] = lang('title_home_page');

			$page_data['title'] = lang('title_home_page');

			$page_data['page_active'] = "gestion_facPage";

			$page_data['page_s_title'] = "Gestion des factures d'examen";

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = "Liste de factures d'examen" ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			// affichage de la vue

			$this->render_template('kameleon/gestion_facture_examen', $page_data);
		
	}


	public function fetchOrdersData()
	{
		$result = array('data' => array());

		$profils_autorises = array('1','2','7','10');

		$data = $this->all_model->get_table('testlaboimagerie');

		foreach ($data as $key => $value) {

			$count_total_examens_item = $this->all_model->countExamenItem($value['idtestlaboimagerie']);

			$date = $this->fdateheuregmt->date_fr($value['date']);

			$infos_patient = $this->all_model->get_fullrow('patient', 'idenregistremetpatient', $value['idenregistremetpatient']);

			$infos_factures = $this->all_model->get_fullrow('factures', 'numfac', $value['numfacbul']);

			if(!empty($infos_factures))
			{
				$montant_total = $infos_factures['montanttotal'] ;
			}else{
				$montant_total = 0 ;
			}

			if($value['typedemande'] == 'analyse')
			{
				$type_facture = 'bio' ;
			}

			if($value['typedemande'] == 'imagerie')
			{
				$type_facture = 'img' ;
			}

			

			// button
			$buttons = '';

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

			$result['data'][$key] = array(
				$value['numfacbul'],
				strtoupper($value['typedemande']),
				$value['idenregistremetpatient'],
				$infos_patient['nomprenomspatient'],
				$infos_patient['telpatient'],
				$date,
				$count_total_examens_item,
				$montant_total,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	/*
	* It gets the product id and fetch the order data. 
	* The order print logic is done here 
	*/
	public function printDiv($id)
	{
		if($id) 
		{

			$page_data['print_name'] = 'i_FBU';

			$page_data['numbulletin'] = $id;

			// Effectuer la journalisation
		        $type_action = 'Impression' ;

		        $action_effectuee = "Facture d'examen" ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			$this->load->view('print/infoNet', $page_data);
		}	
	}

	public function PrintFacBulletin($numbulletin)
	{
		$page_data['print_name'] = 'i_FBU';
		$page_data['numbulletin'] = $numbulletin;

		// Effectuer la journalisation
		    $type_action = 'Impression' ;

		    $action_effectuee = "Facture d'examen" ;

		    $this->control->journalisation($type_action,$action_effectuee) ;

		$this->load->view('print/infoNet', $page_data);
	}
		
    
}
	
