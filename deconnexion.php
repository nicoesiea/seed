<?php
header('Content-Type: application/json');
    if(	isset($_POST['token']) && 
		isset($_POST['id'])
		){

    		$id = $_POST['id'];
			$token = $_POST['token'];

        	//On se connecte d'abord à MySQL :
	    	$connection = mysqli_connect("HOSTNAME_DB","USERBANE_DB","PASSWORD_DB","SCHEMA_DB") or die("Error " . mysqli_error($connection));

	    	//Controle joueurA
			$sql = "SELECT * FROM `users` WHERE id = '".$id."' AND token = '".$token."';";
			$query_user = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));
			$user = null;
			while($row =mysqli_fetch_assoc($query_user))
		    {
		        $user = $row;
		    }
		    if($user["id"] == $id){
		    	$sql = "UPDATE `users` SET `token` = 'deco' WHERE `users`.`id` = ".$id.";";
		    	if ($connection->query($sql) === TRUE) {
		    		$res = '{"url": "YOUR CONNECTION PAGE"}';
		    	}
		    }
		    else {
		    	$res = '{"message": "wrong authentification"}';
		    }

		  	echo $res;
		    //close the db connection
		    mysqli_close($connection);

    }
    else {
        echo "PROBLEME 1 : missing parameter to use this service";
    }
?>