<?php
    function connect_db()
    {
        $dbserver = "localhost";
        $dbuser = "root";
        $dbpassword = "";
        $dbname = "project_reservation";
        return new PDO("mysql:host=$dbserver;dbname=$dbname", $dbuser, $dbpassword);
    }
?>