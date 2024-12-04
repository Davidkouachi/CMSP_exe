<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Consultation extends Admin_Controller {
    
    
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

			if ($curr_uri_string != 'consultation') 
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
		$data['getUser'] = $this->ConsultationModel->getUser();	
		
		$this->load->view('view_user',$data);
	}

	public function PrintFac($numfac)
	{
			$date = date('Y-m-d');
			
			$codebare = $this->db->get_where('factures' , array('numfac'=>$numfac))->row_array();

			if(!empty($codebare))
			{
				$page_data['print_name'] = 'i_RC';
				$page_data['numfac'] = $numfac;
				//$page_data['codebare'] = $this->generateur_identifiant->code_barre($codebare['numrecu']);
				
				$page_data['codebare'] = '' ;
			}
			else
			{
				$page_data['numfac'] = $numfac;
				$page_data['print_name'] = 'i_RC';
			}

			// Effectuer la journalisation
		        $type_action = 'Impression' ;

		        $action_effectuee = 'Facture consultation clinique' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;

			$this->load->view('print/infoNet', $page_data);
	}
		
    public function ConsultationRegister()
    {	
		 $UniqueProfil = $this->UserModel->get_UniqueProfil($this->session->userdata('user_name'));

            $page_data['page_libprofil'] = $UniqueProfil;
			$page_data['page_name'] = 'ConsultationRegister';
			$page_data['page_active'] = 'CaissePage';
			$page_data['page_profil'] = $this->session->userdata('user_profil');
			$page_data['page_title'] = 'Lostro Admin';
			$page_data['page_s_title'] = 'ENCAISSER UNE FACTURE DE CONSULTATION';
			
			$page_data['solde_caisse'] = $this->all_model->get_solde_caisse(date('Y-m-d')) ;
			
			$page_data['lien'] = base_url() . 'ajax/ouverture_caisse/Consultation' ;
			
			// Effectuer la journalisation
		        $type_action = 'Consultation' ;

		        $action_effectuee = 'Formulaire d\'encaissement de facture de consultation clinique' ;

		        $this->control->journalisation($type_action,$action_effectuee) ;
			
			// affichage de la vue

			$this->render_template('kameleon/ConsultationRegister', $page_data);
    }

    public function ReglerFactureCons()
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
			
			$consultation_reglee = $this->db->get_where('consultation' , array('numfac'=>$numfac,'regle'=>0))->row_array();

			$facture = $this->db->get_where('consultation' , array('numfac'=>$numfac))->row_array();

			if(!empty($facture)) {
			    
			    $timbre = 0 ;
			    
			    $ticketmod = $facture['ticketmod'] ;

				if($facture['ticketmod'] >= 5001)
				{
					$ticketmod = $facture['ticketmod'] + 100 ;
					
					$timbre = 100;
				}
				
				if(($facture['ticketmod'] > 100001) && ($facture['ticketmod'] <= 500000)) 
				{
					$ticketmod = $facture['ticketmod'] + 500 ;
					
					$timbre = 500;
				}
				
				if(($facture['ticketmod'] >= 500001) && ($facture['ticketmod'] <= 1000000)) 
				{
					$ticketmod = $facture['ticketmod'] + 1000 ;
					
					$timbre = 1000;
				}
				
				if(($facture['ticketmod'] >= 1000001) && ($facture['ticketmod'] <= 5000000)) 
				{
					$ticketmod = $facture['ticketmod'] + 2000 ;
					
					$timbre = 2000;
				}
				
				if($facture['ticketmod'] > 5000000)
				{
					$ticketmod = $facture['ticketmod'] + 5000 ;
					
					$timbre = 5000;
				}
			}else{
			    $timbre = 0 ;
			    
			    $ticketmod = 0 ;
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

			if(empty($consultation_reglee))
			{

			  echo '<div class="alert alert-danger">
			                    <button type="button" class="close" data-dismiss="alert">&times;</button>
			                    <strong>Cette facture a déjà été réglée et le reçu a déjà été imprimé. Vous pouvez passer a une autre facture de consultation.</strong>
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


			  $montantreste = trim($mtaregle) - trim($mtregle) ;

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
								'libelle' => 'Encaissement facture consultation',
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

			  $dataconsultation =  array('regle' => 1,
								'numfac' => $this->input->post('numfac')
								);

			  //$this->db->insert('caisse_patient',$datacaisse_1);
			  
			  $this->db->insert('journal',$data_journal);

			  $this->db->insert('caisse',$datacaisse_2);

			  $this->db->where('numfac', $numfac);

			  $this->db->update('factures', $datafactures);

			  $this->db->where('numfac', $numfac);

			  $this->db->update('consultation', $dataconsultation);
			  
			  // Mise à jour du solde en caisse (caisse_resume)
					/***************/
					$solde_caisse = $this->all_model->get_solde_caisse(date('Y-m-d')) ;
					
					$nouveau_solde = $solde_caisse['mtcaisse'] + $mtregle ;
					
					$data_caisse_resume =  array('mtcaisse' => $nouveau_solde);
					
					$this->all_model->update_ligne('caisse_resume', $data_caisse_resume, 'idcaisse', $solde_caisse['idcaisse']) ;
					
					/***************/

			  // Effectuer la journalisation
		        $type_action = 'Ajout' ;

		        $action_effectuee = 'Encaissement de facture de consultation clinique' ;

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
			          <input type="text" readonly style="color:red; font-weight:bold; font-size:25px; text-align:center"  class="form-control" id="" value="<?php echo $facture['montant']  ?>">
			          <input type="hidden" readonly style="color:red; font-weight:bold; font-size:25px; text-align:center"  class="form-control" id="mttotal" value="<?php echo $facture['montant']  ?>">
			            <label for=""></label>
			        </div>
			      </div>
			      <div class="col-md-3">
			        <div class="form-group">
			            <label for="" style="font-weight:bold; font-size:16px;">Montant de Remise : </label>
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
			            <label style="font-weight:bold; font-size:16px;">Montant à régler :</label>
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
			            <label style="font-weight:bold; font-size:16px;">Montant réglé :</label>
			        </div>
			      </div>
			      <div class="col-md-2">
			        <div class="form-group">
			          <input type="text" style="color:red; font-weight:bold; font-size:25px; text-align:center" <?php echo $readonly ?> class="form-control" id="mtregle"  value="<?php echo $ticketmod ?>" onkeypress="return activate_regle(event);">
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
			              <input type="hidden" id="taux_couv"  value="<?php echo $consultation_reglee['taux'] ?>">
			      </div>
			      

			      <div class="col-md-3">
			                                                    
			      </div>
			    </div>
		  <?php
		  	}
		}
    }
	
}
	
