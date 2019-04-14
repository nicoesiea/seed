<?php
    header('Content-Type: application/json; charset=utf-8');

     if(    isset($_POST['id']) ){

        $id = $_POST['id']; 

        $connection = mysqli_connect("HOSTNAME_DB","USERBANE_DB","PASSWORD_DB","SCHEMA_DB") or die("Error " . mysqli_error($connection));
        mysqli_set_charset($connexion, 'utf8_general_ci');
        
       


            //fetch table rows from mysql db
            $sql = "select * from `eloUser` WHERE `eloUser`.`id` = '".$id."'";
            $query_user = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));

            //create an array
            $emparray = array();
            while($row =mysqli_fetch_assoc($query_user))
            {
                $emparray[] = $row;
            }
            $emparray[0]["token"]= "";
            $emparray[0]["password"] = "";
            $emparray[0]["isAdmin"] = "";
            $emparray[0]["email"] = "";
            $emparray[0]["timestampLastConnection"] = "";

            echo json_encode($emparray);
        
        //close the db connection
        mysqli_close($connection);
    }
    else {
        echo "PROBLEME 1 : manque d'informations disponibles pour récupérer ce joueur";
    }
    
?>