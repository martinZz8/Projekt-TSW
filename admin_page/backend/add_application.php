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
        $query = $connection->prepare("INSERT INTO submitted_projects (email, first_name, second_name, project_id) VALUES (:email,:first_name,:second_name,:project_id);");
        $query->bindParam(':email', $email);
        $query->bindParam(':first_name', $first_name);
        $query->bindParam(':second_name', $second_name);
        $query->bindParam(':project_id', $project_id);
        $status = $query->execute();
        $application_id = $connection->lastInsertId();
        if ($status) {
            $connection = connect_db();
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = $connection->prepare("SELECT topic FROM project_list WHERE id=:project_id;");
            $query->bindParam(':project_id', $project_id);
            $query->execute();
            if ($row = $query->fetch()) {
                $json = array(
                    "added_application_id" => $application_id,
                    "added_application_p_topic" => $row['topic'],
                    "error" => ''
                );
            }
            else {
                $json = array(
                    "added_application_id" => '',
                    "added_application_p_topic" => '',
                    "error" => 'Wystąpił nieoczekiwany problem (KOD: 2). Spróbuj ponownie później.'
                ); 
            }         
        }
        else {
            $json = array(
                "added_application_id" => '',
                "added_application_p_topic" => '',
                "error" => 'Wystąpił nieoczekiwany problem (KOD: 1). Spróbuj ponownie później.'
            );
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $connection = null;

    $ret_data = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    echo $ret_data;
?>