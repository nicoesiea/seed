<?php
header('Content-Type: application/json');
    if(	isset($_POST['pseudo']) && 
		isset($_POST['password'])
		){

    		$pseudo = $_POST['pseudo'];
			$password = $_POST['password'];
			$res = "";

        	//On se connecte d'abord à MySQL :
	    	$connection = mysqli_connect("kaiogamionkgdb.mysql.db","kaiogamionkgdb","Eaqw2zsx","kaiogamionkgdb") or die("Error " . mysqli_error($connection));

			$sql = "SELECT * FROM `eloUser` WHERE (pseudo like '".htmlentities($pseudo)."' OR email like '".htmlentities($pseudo)."') AND password = '".htmlentities($password)."' AND isAdmin = '1';";
			$query_user = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));
			$user = null;
			while($row =mysqli_fetch_assoc($query_user))
		    {
		        $user = $row;
		    }
		    if($user["pseudo"] == $pseudo || $user["email"] == $pseudo){
		    	//Generate a random string.
				$token = openssl_random_pseudo_bytes(16);
				 
				//Convert the binary data into hexadecimal representation.
				$newToken = bin2hex($token);
				$timestampLastConnection = date("Y-m-j H:i:s"); 
				//2017-12-20 17:55:59
		    	$sql = "UPDATE `eloUser` SET `token` = '".$newToken."', `timestampLastConnection` = '".$timestampLastConnection."' WHERE `eloUser`.`id` = ".$user["id"].";";
	    		if ($connection->query($sql) === TRUE) {
	    			echo '{"url": "http://www.kaiogaming.fr/elokaio/admin", "token": "'.$newToken.'", "id": "'.$user["id"].'"}';
	    		}
		    	
		    }
		    else {
		    	http_response_code(500);
		    	echo '{"message": "Identifiants invalides",  "sql": "'.$sql.'"}';

		    }

		  	
		    //close the db connection
		    mysqli_close($connection);

    }
    else {
    	http_response_code(500);
        echo '{"message": "manque de parametres disponibles pour chercher un joueur"}';
    }
?>