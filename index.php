<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="GESTION DE FACTURATION">
    <meta name="author" content="Winny">
    <meta name="theme-color" content="#3e454c">
    <link rel="icon" href="./assets/images/favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" href="./assets/images/favicon.ico" type="image/x-icon"/>
    <title>SORTIE DE MARCHANDISES</title>
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="./assets/css/bootstrap-table.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="./assets/css/datatables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/plugins/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="./assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css">
	<link rel="stylesheet" href="./assets/plugins/bootstrap-datepicker/bootstrap-datepicker.standalone.min.css">
    <link href="./assets/css/styles.css" rel="stylesheet">
</head>

<body>
<div class="container register">
                <div class="row">
                    <div class="col-md-3 register-left">
                        <img id="logo" src="./assets/images/Logo_kibo.png" alt=""/>
                        <h3>SORTIE  <br> DE <br> MARCHANDISES</h3>
                    </div>
                    <div class="col-md-9 register-right">
                        <a class="imprim" href="#Impression" role="button" data-toggle="modal">
                            <img title="Imprimer les mouvements" alt="Imprimer les sorties" class="print" src="assets/images/print.png">
                        </a>
                        <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Entête</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Détail</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <h3 class="register-heading">Recherche Ticket ou Facture</h3>
                                <div class="row register-form">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="text" id="ticket" class="form-control" placeholder="N° Ticket ou Facture" value="" />
                                        </div>
                                    </div>
                                    <div class="col-md-12" id="loading">
                                      <img id="loading-image" width="50%" src="./assets/images/chargement.gif" alt="Chargement..." />
                                    </div>                                

                                        <div class="col-md-6 resultat">
                                            <div class="form-group">
                                                <label for="N°">N°</label>
                                                <input type="text" class="form-control" id="num_facture" readonly/>
                                                <input type="hidden" class="form-control" id="num_ticket" readonly/>
                                            </div>
                                            <div class="form-group">
                                                <label for="Date de la facture">Date de la facture</label>
                                                <input type="text" class="form-control" id="date_facture" readonly/>
                                            </div>
                                            <div class="form-group">
                                                <label for="Ref Client">N° Client</label>
                                                <input type="text" class="form-control" id="num_client_facture" readonly/>
                                            </div>
                                            <div class="form-group">
                                                <label for="Adresse">Adresse</label>
                                                <input type="text" class="form-control" id="adr_client_facture" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-6 resultat">
                                            <div class="form-group">
                                                <label for="Prénom">Prénom</label>
                                                <input type="text" class="form-control" id="prenom_client" readonly/>
                                            </div>
                                            <div class="form-group">
                                                <label for="Nom">Nom</label>
                                                <input type="text" class="form-control" id="nom_client" readonly/>
                                            </div>
                                            <div class="form-group">
                                                <label for="Tel">Tel</label>
                                                <input type="email" class="form-control" id="tel_client" readonly/>
                                            </div>
                                        </div>

                                    <div class="col-md-12">
                                        <input type="submit" id="rechercher" class="btnRegister"  value="Rechercher"/>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="popup" tabindex="-1" role="dialog" aria-labelledby="kibo" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content rounded-0">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="popupid">Alerte</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true"><span class="icon-close2"></span></span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    <p style="text-align: center; color: #f8d13b;">La facture ou le ticket est inexistante !!!</p>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="tab-pane fade show" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <h3  class="register-heading2 detail">Détail du ticket  </h3>
                                    <div class="row table_">
                                        <table id="table"
                                          data-height="500"
                                          data-virtual-scroll="true"
                                          data-show-columns="true">
                                          <thead>
                                            <tr>
                                              <th data-field="id">Réference</th>
                                              <th data-field="price">Description</th>
                                              <th data-field="price">Qté</th>
                                            </tr>
                                          </thead>
                                          <tbody></tbody>
                                        </table> 
                                    </div>
                                    <div class="col-md-12 bas">
                                        <input type="submit" id="valider" class="btnRegister_"  value="Valider pour sortie"/>
                                        <input type="submit" id="retour" class="btnRetour_"  value="Valider pour retour"/>
                                        <span id="message">Le ticket ou la facture a été bien enregistré pour une sortie</span>
                                        <img id="loading_insert" width="50%" src="./assets/images/chargement.gif" alt="Chargement..." />
                                    </div>
                            </div>
                        </div>
                        <div class="modal fade Factmodal-lg" tabindex="-1" role="dialog" aria-labelledby="Détail de la facture KIBO" aria-hidden="true">
                              <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title detail" id="ModalCenterTitle">Détail de la facture  </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <table id="datatable_" class="table table-striped table-bordered dt-responsive nowrap">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Réference</th>
                                                    <th scope="col">Description</th>
                                                    <th scope="col">Qté</th>
                                                    <th scope="col">Qté Rétournée</th>
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="result">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="submit" id="retourner" class="btnRegisterR_"  value="Valider"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
    <div id="Impression" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="Mvt_Sortie" aria-hidden="true">
		<div class="modal-dialog modal-lg">
		    <div class="modal-content">
		    	<div class="modal-header">
				    <h5 class="modal-title" id="Mvt_Sortie">Impression</h5>
				    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				        <span aria-hidden="true">&times;</span>
				    </button>
			    </div>
				  <div class="modal-body">
				  	<div class="container">
		  				<div class="row dateRange">
						  <div class="col-sm-6">
						    <label for="startDate">Début</label>
						    <div class="input-group mb-3">
						      <input type="text" class="form-control dateRangeInput" id="startDate" placeholder="Date de début">
						      <div class="input-group-append input-group-addon">
						        <button class="btn btn-outline-secondary dateRangeTrigger" type="button"><i aria-label="Date de début" class="fa fa-calendar" aria-hidden="true"></i></button>
						      </div>
						    </div>
						  </div>
						  <div class="col-sm-6">
						    <label for="endDate">Fin</label>
						    <div class="input-group mb-3">
						      <input type="text" class="form-control dateRangeInput" id="endDate" placeholder="Date de fin">
						      <div class="input-group-append input-group-addon">
						        <button class="btn btn-outline-secondary dateRangeTrigger" type="button"><i aria-label="Date de fin" class="fa fa-calendar" aria-hidden="true"></i></button>
						      </div>
						    </div>
						  </div>
						</div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="selection" id="sorties" value="0" checked>
                            <label class="form-check-label" for="sorties">
                               Sorties
                            </label>
                            </div>
                            <div class="form-check">
                            <input class="form-check-input" type="radio" name="selection" id="retours" value="1">
                            <label class="form-check-label" for="retours">
                                Retours
                            </label>
                        </div>
					</div>
				  </div>
				<div class="modal-footer">
	                <input type="submit" id="print_" name="print_" class="btn btn-primary" value="Imprimer" >
				</div>
			</div>
		</div>
	</div>
</body>
<script type="text/javascript" src="./assets/js/jquery.min.js"></script>
<script type="text/javascript" src="./assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="./assets/js/bootstrap-table.min.js"></script>
<script type="text/javascript" src="./assets/js/datatables.min.js"></script>
<script type="text/javascript" src="./assets/js/script.js"></script>
<script type="text/javascript" src="./assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="./assets/plugins/bootstrap-datepicker/bootstrap-datepicker.fr.min.js"></script>
</html>