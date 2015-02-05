<?php
    
    // this variable will contain the database connection
    $pdo = null;

    function connect_to_db()
    {
        // I am making my database setting variables local to the function so that 
        // I don't have to worry about calling the function with parameters
        // so I could get the PDO object by calling the function like that: connect_to_db()

        $dbengine   = 'mysql';
        $dbhost     = 'localhost';
        $dbuser     = 'root';
        $dbpassword = '';
        $dbname     = 'news';

        // try to connect to the database
        try{
            $pdo = new PDO("".$dbengine.":host=$dbhost; dbname=$dbname", $dbuser,$dbpassword);
            // This will allows our fetched data to be object by default
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            return $pdo;
        }  
        catch (PDOException $e){
            // Throw an exception if connection to the database is not established
            $e->getMessage();
        }


    }
