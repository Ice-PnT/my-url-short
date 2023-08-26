<?php

    try {
        $db_servername = "localhost";
        $db_username = "myurl_short";
        $db_password = "myurlshort.19";
        $db_name = 'myurl_short';

        $URL_DB = new PDO("mysql:host=$db_servername;dbname=$db_name;charset=utf8", $db_username, $db_password);
        $URL_DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
        echo 'Connection is : ',  $e->getMessage(), "\n";
    }
?>