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
        $id = $data->id;
        if ($data->description != '') {
            $description = $data->description;
        }
        if ($data->technologies != '') {
            $technologies = $data->technologies;
        }
        $query = $connection->prepare("UPDATE project_list SET topic=:topic, description=:description, technologies=:technologies, blocked=:blocked WHERE id=:id;");
        $query->bindParam(':topic', $topic);
        $query->bindParam(':description', $description);
        $query->bindParam(':technologies', $technologies);
        $query->bindParam(':blocked', $blocked);
        $query->bindParam(':id', $id);
        $status = $query->execute();
        if ($status) {
            echo '';
        }
        else {
            $error = 'Wystąpił nieoczekiwany problem (KOD: 1). Spróbuj ponownie później.';
            echo $error;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $connection = null;
?>