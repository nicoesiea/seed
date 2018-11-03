<?php
    header('Content-Type: application/json; charset=utf-8');


        $connection = mysqli_connect("kaiogamionkgdb.mysql.db","kaiogamionkgdb","Eaqw2zsx","kaiogamionkgdb") or die("Error " . mysqli_error($connection));
        mysqli_set_charset($connexion, 'utf8_general_ci');
        
       


            //fetch table rows from mysql db
            $sql = "select * from `eloUser` ORDER BY `elo` DESC";
            $query_histo = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));

            //create an array
            $emparray = array();
            while($row =mysqli_fetch_assoc($query_histo))
            {
                $emparray[] = $row;
            }
            echo json_encode($emparray);
        
        //close the db connection
        mysqli_close($connection);
    
?>