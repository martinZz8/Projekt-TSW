<?php
    include 'connect_db.php';
    $dataJSON = file_get_contents("php://input");
    $data = json_decode($dataJSON);

    $json = array();
    try {
        $connection = connect_db();
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $connection->prepare("SELECT id, topic, description, technologies FROM project_list WHERE id=(SELECT project_id FROM submitted_projects WHERE email=:email);");
        $query->bindParam(':email', $data->email);
        $query->execute();
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        $i = 0;
        while ($row = $query->fetch()) {
            $json[$i] = array(
                "id" => $row['id'],
                "topic" => $row['topic'],
                "description" => $row['description'],
                "technologies" => $row['technologies']
            );
            $i = $i + 1;
        }
    } catch (PDOException $e) {
        $json[0] = array(
            "exeption_error" => "Error: " . $e->getMessage(),
        );
    }
    $connection = null;
    
    $ret_data = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    echo $ret_data;
?>