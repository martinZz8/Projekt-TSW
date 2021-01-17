<?php
    include '../../backend/connect_db.php';
    $dataJSON = file_get_contents("php://input");
    $data = json_decode($dataJSON);

    try {
        $connection = connect_db();
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $email = $data->email;
        $first_name = $data->first_name;
        $second_name = $data->second_name;
        $project_id = $data->project_id;
        $id = $data->id;
        $query = $connection->prepare("UPDATE submitted_projects SET email=:email, first_name=:first_name, second_name=:second_name, project_id=:project_id WHERE id=:id;");
        $query->bindParam(':email', $email);
        $query->bindParam(':first_name', $first_name);
        $query->bindParam(':second_name', $second_name);
        $query->bindParam(':project_id', $project_id);
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