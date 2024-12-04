<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hospitalisation extends Admin_Controller {
     
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

			if ($curr_uri_string != 'hospitalisation') 
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

	
	public function ViewUser()
	{
		$data['getUser'] = $this->HospitalisationModel->getUser();	
		
		$this->load->view('view_user',$data);
	}
	
	public function Liste_admission()
    {	
		$UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

        $page_data['page_libprofil'] = $UniqueProfil;
		$page_data['page_active'] = 'AdmissionPage';
		$page_data['page_profil'] = $this->session->userdata('user_profil');
		$page_data['page_title'] = 'Lostro Admin';
		$page_data['page_s_title'] = 'Liste des admissions';

		$page_data['idAns'] = 'admission';

		$page_data['namePg'] = 'fetchAdmissionData';

		// Effectuer la journalisation
		    $type_action = 'Consultation' ;

		    $action_effectuee = 'Liste des admissions' ;

		    $this->control->journalisation($type_action,$action_effectuee) ;
		    
		// affichage de la vue

		$this->render_template('kameleon/liste_admission', $page_data);
    }

	public function HospitalisationRegister()
    {	
		 $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

            $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'HospitalisationRegister';
			$page_data['page_active'] = 'CaissePage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'ENCAISSER UNE FACTURE D\'HOSPITALISATION';
			
			$page_data['solde_caisse'] = $this->all_model->get_solde_caisse(date('Y-m-d')) ;
			
			$page_data['lien'] = base_url() . 'ajax/ouverture_caisse/Hospitalisation' ;

			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire d\'encaissement de facture d\'admission' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;
			
			// affichage de la vue

			$this->render_template('kameleon/HospitalisationRegister', $page_data);
    }

	public function RecuHospitPrint($numfac)
	{
			$date = date('Y-m-d');

			$infoTestPatient = $this->printmodel->get_info_hospit_patient($numfac);

			$libassurance = $this->printmodel->get_patient_assurance($infoTestPatient['codeassurance']);

			//$nommedecin = $this->printmodel->get_medecin($infoTestPatient['codemedecin']);

			$codebare = $this->db->get_where('factures' , array('numfac'=>$numfac))->row_array();
			
			//$page_data['codebare'] = $this->generateur_identifiant->code_barre($codebare['numrecu']);
			
			$page_data['codebare'] = '' ;

			$page_data['print_name'] = 'i_RH';
			$page_data['d'] = $infoTestPatient;
			$page_data['don'] = $libassurance;
			$page_data['Med'] = '';
			//$page_data['Acte'] = $garantiecons;

			// Effectuer la journalisation
		        $type_action = 'Impression' ;

		        $action_effectuee = 'Reçu de d\'encaissement de facture d\'admission' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			$this->load->view('print/infoNet', $page_data);
	}


    public function ReglerFactureHospit()
    { 
        include("assets/inc/fdateheuregmt.php");
        
		if(!empty($_POST))
		{
			// initialisation du validateur du formulaire
            $this->load->library('form_validation');
            // définition des règles de validation
            
            $this->form_validation->set_rules('numfac', '<< Entrer le numero de l\'hospitalisation >>', 'trim|required|alpha_dash|min_length[9]|max_length[9]');

            if ($this->form_validation->run() == FALSE)  // Test du formulaire posté
            { 
                // erreur : retour au formulaire
                
                ?><div role="alert" class="alert alert-danger">
                        <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
                        <?php echo form_error("numfac") ?>
                    </div> <?php ;
                    exit();
            } 
            else
            {
				$numfac = strtoupper($this->input->post('numfac'));

				$deb_facture = substr($numfac, 0,3);

				if($deb_facture != 'FCH' )
				{
					echo '<div role="alert" class="alert alert-danger">
	                        <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
	                        Le numéro saisi n\'est pas celui d\'une facture d\'hospitalisation. Veuillez renseigner un numéro de facture d\'hospitalisation valide.
	                    </div>';
	                    exit();
				}
				
				
				//$hospit_regle = $this->db->get_where('factures' , array('numfac'=>$numfac))->row_array();

				//$facture = $this->db->get_where('testlaboimagerie' , array('numfacbul'=>$numfac))->row_array();
				
				$iformation_utile = '' ;

				$facture = $this->db->get_where('factures' , array('numfac'=>$numfac))->row_array();

				if(!empty($facture))
				{
				    $timbre = 0 ;
				    
				    $ticketmod = 0 ;
				    
					$patient = $this->db->get_where('patient' , array('idenregistremetpatient'=>$facture['idenregistremetpatient']))->row_array();
					
					$tauxpatient = $this->db->get_where('tauxcouvertureassure' , array('idtauxcouv'=>$patient['idtauxcouv']))->row_array();
					
					if(!empty($tauxpatient))
					{
					  $taux_patient = $tauxpatient['valeurtaux'] ;  
					}else{
					  $taux_patient = 0 ; 
					}

					$montanttotal = $facture['montanttotal'];

					$ticketmod = $facture['montant_pat'];
					
					$montant_remise = $facture['remise'];

					if ($facture['montant_pat'] >= 5001) {
						
						$ticketmod = $facture['montant_pat'] + 100;
						
						$timbre = 100 ;

						$iformation_utile = 'Le montant du ticket modérateur a exédé 5000 F, il y a donc un montant supplémentaire de 100 F pour le timbre fiscal. ';
					}
					
					if(($facture['montant_pat'] >= 100001) && ($facture['montant_pat'] <= 500000)) 
    				{
    					$ticketmod = $facture['montant_pat'] + 500 ;
    					
    					$timbre = 500 ;
    					
    					$iformation_utile = 'Le montant du ticket modérateur a exédé 100000 F, il y a donc un montant supplémentaire de 500 F pour le timbre fiscal. ';
    				}
    				
    				if(($facture['montant_pat'] >= 500001) && ($facture['montant_pat'] <= 1000000)) 
    				{
    					$ticketmod = $facture['montant_pat'] + 1000 ;
    					
    					$timbre = 1000 ;
    					
    					$iformation_utile = 'Le montant du ticket modérateur a exédé 500000 F, il y a donc un montant supplémentaire de 1000 F pour le timbre fiscal. ';
    				}
    				
    				if(($facture['montant_pat'] >= 1000001) && ($facture['montant_pat'] <= 5000000)) 
    				{
    					$ticketmod = $facture['montant_pat'] + 2000 ;
    					
    					$timbre = 2000 ;
    					
    					$iformation_utile = 'Le montant du ticket modérateur a exédé 1000000 F, il y a donc un montant supplémentaire de 2000 F pour le timbre fiscal. ';
    				}
    				
    				if($facture['montant_pat'] >= 5000001)
    				{
    					$ticketmod = $facture['montant_pat'] + 5000 ;
    					
    					$timbre = 5000 ;
    					
    					$iformation_utile = 'Le montant du ticket modérateur a exédé 5000000 F, il y a donc un montant supplémentaire de 5000 F pour le timbre fiscal. ';
    				}
				}
				else
				{
				    $ticketmod = 0 ;
				    
					echo '<div role="alert" class="alert alert-danger">
	                        <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
	                        Il n\'existe aucune facture d\'hospitalisation à regler pour le numéro que vous avez saisi. Veuillez renseigner un numéro de facture valide.
	                    </div>';
	                    exit();
				}

				$datebon = date('Y-m-d');

				$moderegle = $this->input->post('moderegle');

				$mtaregle = $this->input->post('mtaregle');

				$mtregle = $this->input->post('mtregle');

				$mttotal = $this->input->post('mttotal');
				
				$mtremise = $this->input->post('mtremise');

				$idpat = $this->input->post('idpat');

				$regfac = $this->input->post('regfac');

				if(($facture['datereglt_pat'] != '0000-00-00')&&($facture['datereglt_pat'] != NULL))
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
									'libelle' => 'Encaissement facture hospitalisation',
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

				  /*$dataconsultation =  array('regle' => 1,
									'numfac' => $this->input->post('numfac')
									);*/

				  //$this->db->insert('caisse_patient',$datacaisse_1);
				  
				  $this->db->insert('journal',$data_journal);

				  $this->db->insert('caisse',$datacaisse_2);

				  $this->db->where('numfac', $numfac);

				  $this->db->update('factures', $datafactures);

				  $this->db->where('numfac', $numfac);

				  //$this->db->update('consultation', $dataconsultation);
				  
				  // Mise à jour du solde en caisse (caisse_resume)
					/***************/
					$solde_caisse = $this->all_model->get_solde_caisse(date('Y-m-d')) ;
					
					$nouveau_solde = $solde_caisse['mtcaisse'] + $mtregle ;
					
					$data_caisse_resume =  array('mtcaisse' => $nouveau_solde);
					
					$this->all_model->update_ligne('caisse_resume', $data_caisse_resume, 'idcaisse', $solde_caisse['idcaisse']) ;
					
					/***************/

				  // Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Encaissement de facture d\'admission' ;

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
				        <h5> <strong> <u> Informations : </u><?php echo $iformation_utile ?></strong></h5>
				    </div>

				    <div class="col-md-12">
				      <hr style="height: 1px;color: #F00;background-color: #028B1F;border: 0;"/>
				      <div class="col-md-1">
				        <div class="form-group">
				            <label style="font-weight:bold; font-size:16px;">Date :</label>
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
				            <label style="font-weight:bold; font-size:16px;">Heure:</label>
				        </div>
				      </div>
				      <div class="col-md-3">
				        <div class="form-group">
				          <input type="text" style="color:black; font-weight:bold; font-size:25px;" readonly class="form-control" id="" value="<?php echo dateheure(5) ; ?>">
				        </div>
				      </div>
				      <div class="col-md-1">
				        <div class="form-group">
				            <label style="font-weight:bold; font-size:16px;">N.I.P. : </label>
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
				            <label style="font-weight:bold; font-size:16px;">Nom & prénoms :</label>
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
				            <label for="" style="font-weight:bold; font-size:16px;">Montant Total :</label>
				        </div>
				      </div>
				      <div class="col-md-4">
				        <div class="form-group">
				          <input type="text" readonly style="color:red; font-weight:bold; font-size:25px; text-align:center"  class="form-control" id="" value="<?php echo $montanttotal  ?>">
				          <input type="hidden" readonly style="color:red; font-weight:bold; font-size:25px; text-align:center"  class="form-control" id="mttotal" value="<?php echo $montanttotal  ?>">
				        </div>
				      </div>
				      <div class="col-md-3">
				        <div class="form-group">
				            <label for="" style="font-weight:bold; font-size:16px;">Montant de Remise : </label>
				        </div>
				      </div>
				      <div class="col-md-3">
				        <div class="form-group">
				          <input type="text" style="color:green; font-weight:bold; font-size:25px; text-align:center"  class="form-control" readonly="readonly" value="<?php echo $montant_remise  ?>" id="mtremise" name="mtremise" onkeypress="return activate(event);">
				        </div>
				      </div>
				    </div>

				    <div class="col-md-12">
				      <div class="col-md-2">
				        <div class="form-group">
				            <label style="font-weight:bold; font-size:16px;">Montant à régler :</label>
				        </div>
				      </div>
				      <div class="col-md-2">
				        <div class="form-group">
				          <div class="form-group">
				            <input type="text" style="color:red; font-weight:bold; font-size:25px; text-align:center" readonly class="form-control" id="mtaregle"  value="<?php echo $ticketmod   ?>">
				          </div>
				        </div>
				      </div>
				      <div class="col-md-2">
				        <div class="form-group">
				            <label style="font-weight:bold; font-size:16px;">Montant réglé :</label>
				        </div>
				      </div>
				      <div class="col-md-2">
				        <div class="form-group">
				          <input type="text"  <?php echo $readonly ?> style="color:red; font-weight:bold; font-size:25px; text-align:center" class="form-control" onkeypress="return activate_regle(event);" id="mtregle"  value="<?php echo $ticketmod ?>">
				        </div>
				      </div>
				      <div class="col-md-2">
				        <div class="form-group">
				            <label style="font-weight:bold; font-size:16px;">Mode reglement: </label>
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
				              <input type="hidden" id="taux_couv"  value="<?php echo $taux_patient ?>">
				              <input type="hidden" id="remise"  value="<?php echo $montant_remise ?>">
				      </div>
				      

				      <div class="col-md-3">
				                                                    
				      </div>
				    </div>
			  		<?php
				}
		  	}
		}
    }
	
}
	
