<?php
    include '../../backend/connect_db.php';
    $json = array();
    try {
        $connection = connect_db();
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $connection->prepare("SELECT s.id, s.email, s.first_name, s.second_name, s.project_id, p.topic FROM submitted_projects s,project_list p WHERE s.project_id=p.id;");
        $query->execute();
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        $i = 0;
        while ($row = $query->fetch()) {
            $json[$i] = array(
                "id" => $row['id'],
                "email" => $row['email'],
                "first_name" => $row['first_name'],
                "second_name" => $row['second_name'],
                "project_id" => $row['project_id'],
                "topic" => $row['topic']
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