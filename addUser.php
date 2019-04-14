<?php
	header('Content-Type: application/json; charset=utf-8');
    if(	isset($_POST['id']) && 
		isset($_POST['token']) && 

		isset($_POST['nom']) && 
		isset($_POST['prenom']) && 
		isset($_POST['pseudo']) && 
		isset($_POST['isAdmin']) ){

    		$id = $_POST['id']; 
			$token = $_POST['token'];

			$nom = $_POST['nom']; 
			$prenom = $_POST['prenom'];
			$pseudo = $_POST['pseudo'];
			$email = $_POST['email'];
			$isAdmin = $_POST['isAdmin'];

			$password = "";
			if ($isAdmin == "1" && isset($_POST['password'])){
				$password = $_POST['password'];
			}


        	//On se connecte d'abord à MySQL :
	    	$connection = mysqli_connect("HOSTNAME_DB","USERBANE_DB","PASSWORD_DB","SCHEMA_DB") or die("Error " . mysqli_error($connection));

			$sql = "SELECT * FROM `eloUser` WHERE `id` = '".$id."' AND `token` = '".$token."' AND `timestampLastConnection` > (NOW() - INTERVAL 60 MINUTE);";
			$query_admin = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));
			$admin = array();
		    while($row =mysqli_fetch_assoc($query_admin))
		    {
		        $admin = $row;
		    }

	        if ($admin["token"] == $token){
			    //Remplir la table eloUser
			    $sql = "INSERT INTO `eloUser` (`id`, `nom`, `prenom`, `pseudo`, `email`, `isAdmin`, `password`) VALUES (NULL, '".htmlentities($nom)."', '".htmlentities($prenom)."', '".htmlentities($pseudo)."', '".htmlentities($email)."', '".$isAdmin."', '".htmlentities($password)."');";
			    $result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));

				//Actualisation du timestamp
		    	$sql = "UPDATE `eloUser` SET `timestampLastConnection` = '".date("Y-m-j H:i:s")."' WHERE `eloUser`.`id` = ".$user["id"].";";
	    		$useless = $connection->query($sql);

			    echo json_encode(true);
			}
			else {
				http_response_code(500);
			    echo ('{"message":"Erreur avec votre authentification"}');
			}

		    //close the db connection
		    mysqli_close($connection);

    }
    else {
    	http_response_code(500);
        echo ('{"message":"manque de parametres disponibles pour ajouter un nouveau joueur"}');
    }
?>