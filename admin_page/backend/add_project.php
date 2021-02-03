<?php
    include '../../backend/connect_db.php';
    $dataJSON = file_get_contents("php://input");
    $data = json_decode($dataJSON);

    try {
        $connection = connect_db();
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $topic = $data->topic;
        $description = "Brak opisu.";
        $technologies = "Brak wybranych technologii.";
        $blocked = $data->blocked;
        if ($data->description != '') {
            $description = $data->description;
        }
        if ($data->technologies != '') {
            $technologies = $data->technologies;
        }
        $query = $connection->prepare("INSERT INTO project_list (topic, description, technologies, blocked) VALUES (:topic,:description,:technologies,:blocked);");
        $query->bindParam(':topic', $topic);
        $query->bindParam(':description', $description);
        $query->bindParam(':technologies', $technologies);
        $query->bindParam(':blocked', $blocked);
        $status = $query->execute();
        $project_id = $connection->lastInsertId();
        if ($status) {
            $json = array(
                "added_project_id" => $project_id,
                "error" => ''
            );
        }
        else {
            $json = array(
                "added_project_id" => '',
                "error" => 'Wystąpił nieoczekiwany problem (KOD: 1). Spróbuj ponownie później.'
            );
        }
    } catch (PDOException $e) {
        $json = array(
            "exeption_error" => "Error: " . $e->getMessage(),
        );
    }
    $connection = null;

    $ret_data = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    echo $ret_data;
?>