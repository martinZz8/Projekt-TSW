<?php
    include '../../backend/connect_db.php';
    $json = array();
    try {
        $connection = connect_db();
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $connection->prepare("SELECT id, email, blocked FROM users;");
        $query->execute();
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        $i = 0;
        while ($row = $query->fetch()) {
            $json[$i] = array(
                "id" => $row['id'],
                "email" => $row['email'],
                "blocked" => $row['blocked']
            );
            $i = $i + 1;
        }
    } catch (PDOException $e) {
        $json[0] = array(
            "exeption_error" => "Error: " . $e->getMessage(),
        );
    }
    $connection = null;
    
    $data = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    echo $data;
?>