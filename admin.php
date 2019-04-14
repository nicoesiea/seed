<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
		<meta name="viewport" content="width=device-width, initial-scale=1"> 
		<title>Admin KaioGaming</title>
		<meta name="description" content="Gestion du classement ELO dans Kaio" />
		<meta name="keywords" content="private, admin" />
		<meta name="author" content="KaioGaming" />
		<link rel="shortcut icon" href="./fonts/icomoon/cropped-favicon64-32x32.png">
		<link rel="stylesheet" type="text/css" href="css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="css/demo.css" />
		<link rel="stylesheet" type="text/css" href="css/component.css" />
		<script src="js/modernizr.custom.js"></script>
		<script src="js/lodash.core.js"></script>
		<script src="./js/jquery-3.2.1.js"></script>
		<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" crossorigin="anonymous">

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<style>
			#recap{
				font-size: 46px;
			}
			th{
				text-align: center;
			}
			.limited{
			    display:none;
			}
			.limited:nth-child(-n+200){
			    display:table-row;
			}
			.limited:nth-child(odd) {
			    background: #bd1c45;
			}

			.limited:nth-child(even) {
			    background: #a43751;
			}
			.form-group label{
				margin-top: 10px;
			}
		</style>
	</head>
	<body>
		<div style="background: #D48319;">
		<?php
			$id = $_POST['id']; 
			$token = $_POST['token'];

			$connection = mysqli_connect("HOSTNAME_DB","USERBANE_DB","PASSWORD_DB","SCHEMA_DB") or die("Error " . mysqli_error($connection));

			$sql = "SELECT * FROM `eloUser` WHERE `id` = '".$id."' AND `token` = '".$token."' AND `timestampLastConnection` > (NOW() - INTERVAL 60 MINUTE);";
			$query_admin = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));
			$admin = array();
		    while($row =mysqli_fetch_assoc($query_admin))
		    {
		        $admin = $row;
		    }
	        if ($token == "" || $id == "" || $admin["token"] != $token){
	    ?>
	            <div class="codrops-top clearfix">
					<span class="right"><a class="codrops-icon codrops-icon-drop" href="http://www.kaiogaming.fr/elokaio"><span>Retour</span></a></span>
				</div>
				<header class="codrops-header">
					<img width="100px" src="./fonts/icomoon/logo.png" />
					<h1>Vous n'avez pas le droit d'accéder à cette page</h1>
				</header>
	    <?php
	    }
	        else {
	    ?>
				<!-- Top Navigation -->
				<div class="codrops-top clearfix">
					
					<span class="right">
						<a class="codrops-icon codrops-icon-" href="#" id="deco">
							<span>
								<span class="glyphicon glyphicon-off"></span> 
								Déconnexion de 
								<b style="color:black" title="<?php echo $admin["nom"];?> <?php echo $admin["prenom"];?>">
									<?php echo $admin["pseudo"];?>
								</b>
							</span>
						</a> 
						<a href="#addPlayer" title="Ajouter un joueur">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Ajouter un joueur 
						</a>
						<a href="#addRencontre" title="Ajouter un tournoi">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Ajouter un tournoi 
						</a>
					</span>
				</div>
				<header class="codrops-header">
					<img width="100px" src="./fonts/icomoon/logo.png" />
					<h1></h1>
				    <script>
				    	let users = null;
						let history = null;
						let userA = null;
						let userB = null;
					 	let action = "triomphe contre";

					 	let tournois = null;
					 	let tournoi = null;


				    	function cancelMatch(matchId, pseudoA, action, pseudoB){
							$(document).ready(function(){

//////////////////////// DUPLICATION PURE ET SIMPLE ///////////////////////////////

							function getUsers(){
								if (users != null){
									removeAllUsers();
									userA = null;
									userB = null;
									setUserA(0);
									setUserB(0);
								}
								$.get("./getUserOrderByPseudo.php")
								.done(( data )  => {
									users = data;
									var $selectUserA = $('#userA');
									var $selectUserB = $('#userB');
									$.each(users,function(key, value) 
									{
										let option = getOption(value);
										$selectUserA.append(option);
									    $selectUserB.append(option);
									});
								});
							};

							function removeAllUsers($selectUserA, $selectUserB){
							    $('#userA').children('option:not(:first)').remove();
							    $('#userB').children('option:not(:first)').remove();
						 	};

						 	function getOption(user){
						 		return '<option value=' + user.id + '>' + user.pseudo + " - " + user.elo + '</option>';
						 	}

						 	function searchUser(id){
						 		let user = _.find(users,(utilisateur) => {
						 			return utilisateur.id === id;
						 		});
						 		return user;
						 	}

						 	function setUserA(id){
						 		if (id != "0")
						 		{
							 		let user = searchUser(id);
							 		userA = user;
							 		var theString = user.pseudo;
								  	var varTitle = $('<textarea />').html(theString).text();
								  	$( "#selectedUserA" ).text( varTitle );
						 		}
							 	else {
							 		$( "#selectedUserA" ).text( "?");
							 	}
						 	}
						 	function setUserB(id){
						 		if (id != "0")
						 		{
						 			let user = searchUser(id);
							 		userB = user;
							 		var theString = user.pseudo;
								  	var varTitle = $('<textarea />').html(theString).text();
								  	$( "#selectedUserB" ).text( varTitle );
							 	}
							 	else {
							 		$( "#selectedUserB" ).text( "?");
							 	}
						 	}
							function removeAllLines(){
								$('#tableBody').children('tr').remove();
							}

							function getHistory(){
								removeAllLines();
								$.post("./getHistory.php", {
									id: '<?php echo $admin["id"]?>',
									token: '<?php echo $admin["token"] ?>',
								})
								.done(( data ) => {
									history = data;
									var $tableBody = $('#tableBody');
									$.each(history,function(key, value) 
									{
										let line = getLine(value);
										$tableBody.append(line);
									});
								});
							}

							function removeAllLines(){
								$('#tableBody').children('tr').remove();
							}

							function getLine(line){
								return `
									<tr class="limited" match-id="`+ line.id +`">
										<th scope="row">`+ line.date +`</th>
										<td style="font-size: 1.5em;" id-a="`+ line.idA +`">
											`+ line.pseudoA +` (`+ line.previousEloA +` -> `+ line.newEloA +`)
										</td>
										<td style="font-size: 1.5em;">
											`+ line.action +`
										</td>
										<td style="font-size: 1.5em;" id-b="`+ line.idB +`">`
											+ line.pseudoB +` (`+ line.previousEloB +` -> `+ line.newEloB +`)
										</td>
										<td style="font-size: 1.5em;">
											<button type="button" class="btn btn-default" 
	onclick="cancelMatch(`+ line.id +`, '`+ line.pseudoA +`', '`+ line.action +`' , '`+ line.pseudoB +`');">
											  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
											</button>
										</td>
									</tr>`;
							}
///////////////////////////////////////////////////////////////////

					            $('<div></div>').appendTo('body')
								.html('<div><h6>Supprimer le match: '+pseudoA+' '+action+' '+pseudoB+' ?</h6></div>')
								.dialog(
								{
									modal: true, title: 'Annuler le match '+matchId, zIndex: 10000, autoOpen: true,
									width: 'auto', resizable: false,
									buttons: {
										Oui: function () {
											$.post("./cancelMatch.php",
										    {
										    	id: '<?php echo $admin["id"]?>',
												token: '<?php echo $admin["token"] ?>',

										        matchId: matchId
										    })
										    .done( (data, status) => {
										    	$(this).remove();
										    })
										    .fail((error) => {
												alert(error.responseJSON.message);
											})
											.always(() => {
												$(this).remove();
												getHistory();
										    	getUsers();
											});
										},
										Non: function () {
											$(this).remove();
										}
									},
									close: function (event, ui) {
										$(this).remove();
									}
								});
					        });
					        return false;
						}

						$( document ).ready(function() {



						 	function searchUser(id){
						 		let user = _.find(users,(utilisateur) => {
						 			return utilisateur.id === id;
						 		});
						 		return user;
						 	}

						 	function setUserA(id){
						 		if (id != "0")
						 		{
							 		let user = searchUser(id);
							 		userA = user;
							 		var theString = user.pseudo;
								  	var varTitle = $('<textarea />').html(theString).text();
								  	$( "#selectedUserA" ).text( varTitle );
						 		}
							 	else {
							 		$( "#selectedUserA" ).text( "?");
							 	}
						 	}

						 	function setUserB(id){
						 		if (id != "0")
						 		{
						 			let user = searchUser(id);
							 		userB = user;
							 		var theString = user.pseudo;
								  	var varTitle = $('<textarea />').html(theString).text();
								  	$( "#selectedUserB" ).text( varTitle );
							 	}
							 	else {
							 		$( "#selectedUserB" ).text( "?");
							 	}
						 	}

						 	function removeUserA(id){
						 		$("#userA option[value='"+id+"']").remove();
						 	}

						 	function removeUserB(id){
						 		$("#userB option[value='"+id+"']").remove();
						 	}

						 	function mayBeAddUserAInB(id){
						 		if (userA === null){
						 		}
						 		else {
						 			addUserB(userA.id);
						 		}
						 		if (id === "0"){ 
						 			userA = null;
						 			setUserA(id);
						 		}
						 	}
						 	function mayBeAddUserBInA(id){
						 		if (userB === null){
						 		}
						 		else {
						 			addUserA(userB.id);
						 		}
						 		if (id === "0"){ 
						 			userB = null;
						 			setUserB(id);
						 		}
						 	}

						 	function addUserA(id){
						 		let option = getOption(searchUser(id));
								var $selectUserA = $('#userA');
								$selectUserA.append(option);
							}

						 	function addUserB(id){
								let option = getOption(searchUser(id));
								var $selectUserB = $('#userB');
								$selectUserB.append(option);
						 	}

						 	function getOption(user){
						 		return '<option value=' + user.id + '>' + user.pseudo + " - " + user.elo + '</option>';
						 	}

						 	function removeAllUsers($selectUserA, $selectUserB){
							    $('#userA').children('option:not(:first)').remove();
							    $('#userB').children('option:not(:first)').remove();
						 	};

							function getUsers(){
								if (users != null){
									removeAllUsers();
									userA = null;
									userB = null;
									setUserA(0);
									setUserB(0);
								}
								$.get("./getUserOrderByPseudo.php")
								.done(( data )  => {
									users = data;
									var $selectUserA = $('#userA');
									var $selectUserB = $('#userB');
									$.each(users,function(key, value) 
									{
										let option = getOption(value);
										$selectUserA.append(option);
									    $selectUserB.append(option);
									});
								});
							};
							getUsers();

							function getTournois(){
								$.get("./getTournois.php")
								.done(( data )  => {
									tournois = data;
									tournoi = tournois[0];
									var $selectTournoi = $('#selectTournoi');
									$.each(tournois,function(key, value) 
									{
										let option = getOptionTournoi(value);
										$selectTournoi.append(option);
									});
								});
							};
							getTournois();

							function getOptionTournoi(tournoi){
						 		return '<option value=' + tournoi.id + '>' + tournoi.date + " - " + tournoi.jeu + '</option>';
						 	}

							function getLine(line){
								return `
									<tr class="limited" match-id="`+ line.id +`">
										<th scope="row">`+ line.date +`</th>
										<td style="font-size: 1.5em;" id-a="`+ line.idA +`">`+ line.pseudoA +` (`+ line.previousEloA +` -> `+ line.newEloA +`)</td>
										<td style="font-size: 1.5em;">`+ line.action +`</td>
										<td style="font-size: 1.5em;" id-b="`+ line.idB +`">`+ line.pseudoB +` (`+ line.previousEloB +` -> `+ line.newEloB +`)</td>
										<td style="font-size: 1.5em;">
										<button type="button" class="btn btn-default" onclick="cancelMatch(`+ line.id +`, '`+ line.pseudoA +`', '`+ line.action +`' , '`+ line.pseudoB +`');">
											  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
											</button>
										</td>
									</tr>`;
							}

							function removeAllLines(){
								$('#tableBody').children('tr').remove();
							}

							function getHistory(){
								if (history != null){
									removeAllLines();
								}
								$.post("./getHistory.php", {
									id: '<?php echo $admin["id"]?>',
									token: '<?php echo $admin["token"] ?>',
								})
								.done(( data ) => {
									history = data;
									var $tableBody = $('#tableBody');
									$.each(history,function(key, value) 
									{
										let line = getLine(value);
										$tableBody.append(line);
									});
								});
							}
							getHistory();

							$( "#userA" ).change(() => {
							    var id = "";
							    $( "#userA option:selected" ).each(function() {
							      id = $( this ).val();
							      mayBeAddUserAInB(id);
							      if (id != "0"){
							      	setUserA(id);
							      	removeUserB(id);
							      }
							    });
						  	});

						  	$( "#userB" ).change(() => {
							    var id = "";
							    $( "#userB option:selected" ).each(function() {
							      id = $( this ).val();
							      mayBeAddUserBInA(id);
							      if (id != "0"){
									setUserB(id);
									removeUserA(id);
							      }
							    });
						  	});

						  	$( "#action" ).change(() => {
							    $( "#action option:selected" ).each(function() {
							      action = $( this ).text();
							      setAction(action);
							      $( "#selectedAction" ).text( action );
							    });
						  	});

						  	function setAction(nouvelleAction){
						  		action = nouvelleAction;
						  	}

						  	$("#addNewElo").click(function(){
						  		setBtnBlocked(true);
						  		if (userA!= null && userB!= null && action!="" && action!=null){
						  			$.post("./addElo.php",
								    {
								    	id: '<?php echo $admin["id"]?>',
										token: '<?php echo $admin["token"] ?>',

								        pseudoA: userA.pseudo,
										pseudoB: userB.pseudo,
										idA: userA.id,
										idB: userB.id,
										action: action,
										previousEloA: userA.elo,
										previousEloB: userB.elo
								    },
								    function(data, status){
								        addNewHistory(data);
								        addNewEntryInHistory(data);
								        userA = null;
								        userB = null;
								        getUsers();
								        setBtnBlocked(false);
								    }).fail(function (error){
										alert(error.responseText);
								    }).always(function() {
										setBtnBlocked(false);
										getUsers();
										getHistory();
									});
						  		}
						  		else{
						  			alert("Merci de saisir les informations avant de valider");
							        setBtnBlocked(false);
						  		}
							});

							function addNewEntryInHistory(data){
								let entry = {
									action: data[0].action,
									date: data[0].date,
									id: data[0].id,
									idA: data[0].idA,
									idB: data[0].idB,
									newEloA: data[0].newEloA,
									newEloB: data[0].newEloB,
									previousEloA: data[0].previousEloA,
									previousEloB: data[0].previousEloB,
									pseudoA: data[0].pseudoA,
									pseudoB: data[0].pseudoB
								}
						        history.unshift(entry);
							}

							function addNewHistory(data){
								var $tableBody = $('#tableBody');
								let line = getLine(data[0]);
								$tableBody.prepend(line);
							}

							function setBtnBlocked(isBlocked){
	           					$('button').prop('disabled', isBlocked);
							}

							function cleanInputFieldsUser (){
								$('input.addUser_nom').val("");
								$('input.addUser_prenom').val("");
								$('input.addUser_pseudo').val("");
								$('input.addUser_email').val("");
								$('input[type=checkbox][name=isAdmin]').prop('checked', false);
								hidePassword();
							}

							function cleanInputFieldsTournois (){
								$('input.addTournoi_date').val("");
								$('input.addTournoi_lieu').val("");
								$('input.addTournoi_jeu').val("");
							}

						  	function addUser(nom, prenom, pseudo, email, isAdmin, password){
						  		$.post("./addUser.php",
							    {
							    	id: '<?php echo $admin["id"]?>',
									token: '<?php echo $admin["token"] ?>',

							        nom: nom,
									prenom: prenom,
									pseudo: pseudo,
									email: email,
									isAdmin: isAdmin,
									password: password
							    },
							    (data, status) => {
							        userA = null;
							        userB = null;
							        getUsers();
							        alert("Nouveau joueur "+prenom+" "+nom+" (alias "+pseudo+") ajouté");
							        cleanInputFieldsUser();
							    })
							    .fail((error) => {
									alert(error.responseJSON.message);
								})
								.always(() => {
									setBtnBlocked(false);
								});
								
							};

						  	function addTournoi(date, lieu, jeu){
						  		$.post("./addTournoi.php",
							    {
							    	id: '<?php echo $admin["id"]?>',
									token: '<?php echo $admin["token"] ?>',

							        date: date,
									lieu: lieu,
									jeu: jeu
							    },
							    (data, status) => {
							        console.log(data);
							        console.log(status);
							        alert("Nouveau tournoi pour "+jeu+" ajouté");
							        cleanInputFieldsTournois();
							    })
							    .fail((error) => {
									alert(error.responseJSON.message);
								})
								.always(() => {
									setBtnBlocked(false);
								});
								
							};

							function validateEmail($email) {
							  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,6})?$/;
							  return ( $email.length > 0 && emailReg.test($email))
							}

							$("#addUser").click(function(){
								let nom = $('input.addUser_nom').val(); 
								if (nom == "") { 
									alert("Saisir un nom");
									return false;
								}

								let prenom = $('input.addUser_prenom').val(); 
								if (prenom == "") { 
									alert("Saisir un prenom");
									return false;
								}

								let pseudo = $('input.addUser_pseudo').val(); 
								if (pseudo == "") { 
									alert("Saisir un pseudo");
									return false;
								}

								let email = $('input.addUser_email').val();
								if (email != "" && email != null && email != "" ){
									//vérifier que l'email est valide
									if( !validateEmail(email)) { 
										alert("Email non valide"); 
										return false;
									}
								}

								let isAdmin = $('input[type=checkbox][name=isAdmin]:checked').val();
								if (isAdmin === "on"){
									isAdmin = true;
								}
								else {
									isAdmin = false;
								}
								let password = $('input.addUser_password').val();
								if (isAdmin === true && (password === "" || password === null || password === " " || password === undefined) ) { 
									alert("Saisir un password valide pour le nouvel admin"); return false;
								}

						  		setBtnBlocked(true);
						  		$.post("./searchUser.php",
							    {
							    	id: '<?php echo $admin["id"]?>',
									token: '<?php echo $admin["token"] ?>',

							        nom: nom,
									prenom: prenom,
									pseudo: pseudo
							    },
							    (data, status) => {
							    	if (data.length >= 1) {
							    		alert("Joueur déjà présent dans la base");
							        	setBtnBlocked(false);
							    	}
							    	else {
							    		isAdmin01 = isAdmin? 1: 0; 
							    		addUser(nom, prenom, pseudo, email, isAdmin01, password);
							    	}
							    })
							    .fail((error) => {
									alert(error.responseJSON.message);
								})
								.always(() => {
									setBtnBlocked(false);
								});
							});

							$("#addTournoi").click(function(){
								let date = $('input.addTournoi_date').val(); 
								if (date == "") { 
									alert("Saisir une date");
									return false;
								}

								let lieu = $('input.addTournoi_lieu').val(); 
								if (lieu == "") { 
									alert("Saisir un lieu");
									return false;
								}

								let jeu = $('input.addTournoi_jeu').val(); 
								if (jeu == "") { 
									alert("Saisir un jeu");
									return false;
								}

						  		setBtnBlocked(true);
						  		addTournoi(date, lieu, jeu);
							});

						  	$("#annuler").click(function(){
						  		let lastMatch = getLastMatch();
						  		if (lastMatch != undefined){
									ConfirmDialog('Annuler le dernier match:<br/><b>'+lastMatch.pseudoA+" "+lastMatch.action+" "+lastMatch.pseudoB+"</b><br/>le "+lastMatch.date);
						  		}
						  		else{
						  			alert("Aucun match à annuler");
						  		}
						  	});

						  	function getLastMatch(){
						  		return history[0];
						  	}

							function ConfirmDialog(bodyMessage) {
								$('<div></div>').appendTo('body')
								.html('<div><h6>'+bodyMessage+'?</h6></div>')
								.dialog(
								{
									modal: true, title: 'Annuler', zIndex: 10000, autoOpen: true,
									width: 'auto', resizable: false,
									buttons: {
										Oui: function () {
											setBtnBlocked(true);
											let lastMatch = getLastMatch();
											$.post("./cancelMatch.php",
										    {
										    	id: '<?php echo $admin["id"]?>',
												token: '<?php echo $admin["token"] ?>',

										        matchId: lastMatch.id
										    })
										    .done( (data, status) => {
										    	$(this).remove();
										    })
										    .fail((error) => {
												alert(error.responseJSON.message);
											})
											.always(() => {
												$(this).remove();
												setBtnBlocked(false);
												getHistory();
										    	getUsers();
											});
										},
										Non: function () {
											$(this).remove();
										}
									},
									close: function (event, ui) {
										$(this).remove();
									}
								});
							};

							$("#deco").click(function(){
								$.post("./deconnexion.php",
							    {
							        id: '<?php echo $admin["id"]?>',
									token: '<?php echo $admin["token"] ?>'
							    },
							    function(data, status){
							    	redirectTo(data.url);
							    });
						  	});

		  					function redirectTo(url){
							   window.location.href= url;
							}

						    $('input[type=checkbox][name=isAdmin]').change(function() {
						        if (this.checked === true) {
						        	showPassword();
						        }
						        else if (this.checked === false) {
						        	hidePassword();
						        }
						    });

						    

						    function showPassword(){
						    	var $passwordPlace = $('#passwordPlace');
						    	let field = `<label for="addUser_password" class="col-sm-2 control-label">Mot de passe admin</label>
											<div class="col-sm-10">
												<input type="password" class="form-control addUser_password" id="addUser_password" placeholder="Mot de passe pour l'accès administrateur">
											</div>`;
					            $passwordPlace.append(field);
						    }

						    function hidePassword(){
						    	var $passwordPlace = $('#passwordPlace');
								$passwordPlace.text("");
						    }
						});
				    </script>

					<div id="recap">
						<span id="selectedUserA">?</span>
						<span id="selectedAction" style="text-decoration: underline;">triomphe contre</span>
						<span id="selectedUserB">?</span>
					</div>

					<div style="margin-top: 20px;">
						<a href="#addTournoi">Ajouter</a><select id="selectTournoi" style="font-size: medium; margin-right: 10%"></select>
					    <select id="userA" style="font-size: medium;">
						  <option value="0">Joueur A</option>
						</select>
						<select id="action" style="font-size: medium;">
						  <option value="1">triomphe contre</option>
						  <option value="2">est vaincu par</option>
						  <option value="3">match null avec</option>
						</select>
				    	<select id="userB" style="font-size: medium;">
						  <option value="0">Joueur B</option>
						</select>
					</div>

					<div style="margin-top: 30px;">
						<button type="button" id="addNewElo" class="btn btn-default btn-lg">
							<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Valider
						</button>
					<!--
						<span style="margin-right: 30px"></span>
						<button type="button" id="annuler" class="btn btn-default btn-sm">
							<span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Annuler le dernier match
						</button>
					-->
					</div>
				</header>
				<section>
					<h1>Historique</h1>
					<table class="table">
					  <thead>
					    <tr>
					      <th style="font-size: 1.5em;">#</th>
					      <th style="font-size: 1.5em;">User A</th>
					      <th style="font-size: 1.5em;">Action</th>
					      <th style="font-size: 1.5em;">User B</th>
					      <th style="font-size: 1.5em;">Annuler</th>
					    </tr>
					  </thead>
					  <tbody id="tableBody">
					  </tbody>
					</table>
				</section>
				<section class="related" id="addPlayer">
					<h1>Ajouter un joueur (admin)</h1>
					<div class="form-group">
						<label for="addUser_nom" class="col-sm-2 control-label">Nom</label>
						<div class="col-sm-10">
							<input type="text" class="form-control addUser_nom" id="addUser_nom" placeholder="Nom du joueur">
						</div>
					</div>
					<br/>

					<div class="form-group">
						<label for="addUser_prenom" class="col-sm-2 control-label">Prénom</label>
						<div class="col-sm-10">
							<input type="text" class="form-control addUser_prenom" id="addUser_prenom" placeholder="Prénom du joueur">
						</div>
					</div>
					<br/>

					<div class="form-group">
						<label for="addUser_pseudo" class="col-sm-2 control-label">Pseudo</label>
						<div class="col-sm-10">
							<input type="text" class="form-control addUser_pseudo" id="addUser_pseudo" placeholder="Pseudo du joueur">
						</div>
					</div>
					<br/>

					<div class="form-group">
						<label for="addUser_email" class="col-sm-2 control-label">Adresse mail</label>
						<div class="col-sm-10">
							<input type="mail" class="form-control addUser_email" id="addUser_email" placeholder="Email du joueur">
						</div>
					</div>
					<br/>

					<div class="checkbox">
					  <label>
					    <input type="checkbox" class="addUser_isAdmin" name="isAdmin">Le joueur est un <b>Administrateur</b> Kaio
					  </label>
					</div>

					<div class="form-group" id="passwordPlace">
					</div>

					<button type="button" id="addUser" class="btn btn-default btn-lg">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Ajouter
					</button>
				</section>
				<section id="addRencontre">
					<h1>Ajouter un tournoi</h1>
					<div class="form-group">
						<label for="addTournoi_date" class="col-sm-2 control-label">Date</label>
						<div class="col-sm-10">
							<input type="date" class="form-control addTournoi_date" id="addTournoi_date" placeholder="Date de la rencontre">
						</div>
					</div>
					<br/>

					<div class="form-group">
						<label for="addTournoi_lieu" class="col-sm-2 control-label">Lieu</label>
						<div class="col-sm-10">
							<input type="text" class="form-control addTournoi_lieu" id="addTournoi_lieu" placeholder="Lieu de la rencontre">
						</div>
					</div>
					<br/>

					<div class="form-group">
						<label for="addTournoi_jeu" class="col-sm-2 control-label">Jeu</label>
						<div class="col-sm-10">
							<input type="text" class="form-control addTournoi_jeu" id="addTournoi_jeu" placeholder="Jeu">
						</div>
					</div>
					<div style="height: 15px; margin-bottom: 5%"></div>
					<button type="button" id="addTournoi" class="btn btn-default btn-lg">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Ajouter
					</button>
				</section>
			<?php
			    }
			?>
		</div><!-- /container -->
	</body>
</html>