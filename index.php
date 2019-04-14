<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
		<meta name="viewport" content="width=device-width, initial-scale=1"> 
		<title>HELLO TEST</title>
		<meta name="description" content="Sample page" />
		<meta name="keywords" content="Exemple" />
		<meta name="author" content="nicoesiea" />
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

		<style type="text/css">
			.badge-success{background-color: #28a745; color: white;}
			.badge-info{background-color: #007bff;}
			.badge-warning{background-color: #17a2b8;}
			.badge-danger{background-color: #ffc107;}

			#custom-handle {
				width: 3em;
				height: 1.6em;
				top: 50%;
				margin-top: -.8em;
				text-align: center;
				line-height: 1.6em;
			}

			
		</style>


	</head>
	<body>
		<script>
			$( document ).ready(function() {
/*
.auto-scroll{
			    height:480px;
			    overflow-y:scroll;
			}
var currentPos = 0;
var div = $('.auto-scroll');
setInterval(function(){
    var pos = div.scrollTop();
    div.scrollTop(pos + 5);
    currentPos = currentPos + 5;
    console.log("curent: "+currentPos);
    console.log("innerHeight: "+$(this).innerHeight());

    console.log("scrollTop: "+$(this).scrollTop());
    
    if(currentPos + $(this).scrollTop() >= $(this).innerHeight()){
	   div.scrollTop(pos - (currentPos + $(this).innerHeight() + $(this).scrollTop()));
	   currentPos = 0;
	}
}, 200);
*/

			 	let refreshTime = 20; 
			 	let users = null;
			 	let timeOutIdentifier = null;

			 	function removeAllUsers($selectUserA, $selectUserB){
				    $('.list-group').children('li').remove();
			 	};

			 	function getLiUser(user, position){
			 		position = position +1;//car on commence a l'indice 0 dans la boucle
			 		let color = 'danger';
			 		switch (position) {
			 			case 1:
			 				color = 'success';
			 				break;
			 			case 2:
			 				color = 'info';
			 				break;
			 			case 3:
			 				color = 'warning';
			 				break;
			 			default: 
			 				color = 'danger';

			 		}
			 		let star = (position === 1)? ` <span class="glyphicon glyphicon-star" aria-hidden="true"></span> <span class="glyphicon glyphicon-star" aria-hidden="true"></span> <span class="glyphicon glyphicon-star" aria-hidden="true"></span> ` : (position <= 16 )? ` <span class="glyphicon glyphicon-star" aria-hidden="true"></span> ` : ``;;
			 		
			 		return `<li class="list-group-item justify-content-between" title="`+user.prenom+` `+user.nom+`">
			 					<span class="badge badge-`+color+` badge-pill pull-left">`+(position)+`</span>
			 					`+(star)+`
			 					<span style="font-size: 1.9em;"><a href="./player.php?id=`+user.id+`" title="Profil du joueur">`+user.pseudo+`</a></span>
			 					`+(star)+`
			 					<span class="badge badge-`+color+` badge-pill">`+user.elo+`</span>
			 				</li>`;
			 	}

				function getUsers(){
					$.get("./getUsers.php")
					.done(( data )  => {
						let identiques = true;
						if (users == null){
							identiques = false;
						}
						else {
							if ( JSON.stringify(users) !== JSON.stringify(data) ){
								identiques = false;
							}
						}

						if (!identiques) {
							removeAllUsers();
							users = data;
							var $list = $('.list-group');
							$.each(users,function(key, value) 
							{
								let li = getLiUser(value, key);
								$list.append(li);
							});
						}
						else {
							//console.log("Listes identiques");
						}
					});
				};
				getUsers();

				var handle = $( "#custom-handle" );

				$( "#slider" ).slider({
					value: refreshTime,
					range: "max",
				  	min: 4,
				  	max: 60,
					create: function() {
						handle.text( $( this ).slider( "value" ) );
					},
					slide: function( event, ui ) {
						displayRefreshTime(ui.value);
						startNewTimeout(refreshTime);
						handle.text( ui.value );
					}
				});

				function displayRefreshTime(newRefreshTime){
					let secondeS = "";
					refreshTime = newRefreshTime;
					if (refreshTime > 1){
						secondeS = "secondes";
					}
					else {
						secondeS = "seconde";
					}
					$("#refreshTime").text(refreshTime+" "+ secondeS);
					return refreshTime+" "+ secondeS;
				}
				displayRefreshTime(refreshTime);
				
				function startNewTimeout(refreshTime){
					if (timeOutIdentifier != null){
						clearTimeout(timeOutIdentifier);
					}
					timeOutIdentifier = setInterval(() => {						
						getUsers();
					}, refreshTime * 1000)
				}
				startNewTimeout(refreshTime);

				$("#connexion").click(function(){
					loginDialogBox();
			  	});

				function loginDialogBox() {
					$('<div></div>').appendTo('body')
					.html(`<div>
							  <div class="container">
							    <label><b>pseudo</b></label>
							    <input id="pseudo" type="text" placeholder="pseudo" name="uname" required>
							    <label><b>Password</b></label>
							    <input id="password" type="password" placeholder="Password" name="psw" required>
							  </div>
							</div>`)
					.dialog(
					{
						modal: true, title: 'Connexion', zIndex: 10000, autoOpen: true,
						width: 'auto', resizable: false,
						buttons: {
							Connexion: () => {
								let pseudo = $("#pseudo").val();
								let password = $("#password").val();
								if (pseudo != "" && pseudo != null && password != "" && password != null ) {
									var jqxhr = $.post( "./connexion.php",{
								        pseudo: pseudo,
										password: password
								    },
								    (data) =>  {
										if (data.url != "" && data.url != null && 
								    		data.token != "" && data.token != null &&
								    		data.id != "" && data.id != null){
								        	redirectTo(data.url, data.token, data.id);
								    	}
								    	else{
								    		alert( data.message );
								    	}
									})
									.fail((error) => {
										console.log(error);
									})
									.always(() =>  {
										$(this).remove();
									});
								}
							},
							Annuler: function () {                                                               
								$(this).remove();
							}
						},
						close: function (event, ui) {
							$(this).remove();
						}
					});
				};

				function redirectTo(url, token, id){
					//Initialisation des variables dans le formulaires
					$("#connexion_token").val(token);
					$("#connexion_id").val(id);

					//Mise à jour de action
					$('#connexion_form').attr('action', ''+url);

					//Validation du form
					if (token != undefined && token != "" && id != undefined && id != ""){
						var $form = $( "#connexion_form" );
						$form.submit();	
					}
				}

			});
	    </script>
	    <form id="connexion_form" method="post" action="#" style="display:none!important">
		  <input id="connexion_token" type="token" name="token" required="required">
		  <input id="connexion_id" type="id" name="id" required="required">
		</form>
		<div style="background: #D48319;">
			<div class="codrops-top clearfix">
				<span class="right">
					<a class="codrops-icon codrops-icon-drop" href="#" id="connexion">
						<span>connexion</span>
					</a>
				</span>
				<a class="codrops-icon codrops-icon-prev" href="YOUR OWN PAGE"><span>NAME OR URL TO YOUR SITE</span></a>
			</div>
			<header class="codrops-header">
				<img width="100px" src="./fonts/icomoon/logo.png" />
				<h1>TITLE</h1>
				<h2>SubTitle</h2>
				<br/>
				<p style="font-size: 1.5em;">Actualiser après <b><span id="refreshTime"></span></b></p>
				<!--div class="slider"><div id="slider"></div></div -->
				<div id="slider">
					<div id="custom-handle" class="ui-slider-handle"></div>
				</div>
			</header>
			<section  class="auto-scroll">
				<ul class="list-group">
				</ul>
			</section>
			<section class="related">
				<p>
					See also:
				</p>
				<a target="_blank" href="yopyopyop/">
					<img width="100px" src="yipyipyipyip" />
					<h3>Sample one</h3>
				</a>
				<a target="_blank" href="yopyopyop/">
					<img width="100px" src="yipyipyipyip" />
					<h3>Sample too</h3>
				</a>
			</section>
		</div>
	</body>
</html>