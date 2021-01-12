<?php
    include 'connect_db.php';
    $dataJSON = file_get_contents("php://input");
    $data = json_decode($dataJSON);

    try {
        $connection = connect_db();
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $connection->prepare("SELECT id FROM submitted_projects WHERE email=:email;");
        $query->bindParam(':email', $data->email);
        $query->execute();
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        
        $row = $query->fetch();
        if ($row == NULL) {
            $connection = connect_db();
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $topic = $data->topic;
            $description = "Brak opisu.";
            $technologies = "Brak wybranych technologii.";
            $blocked = "1";
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
            $status1 = $query->execute();
            $project_id = $connection->lastInsertId();
            if ($status1) {
                $connection = connect_db();
                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $query = $connection->prepare("INSERT INTO submitted_projects (email,first_name,second_name,project_id) VALUES (:email,:first_name,:second_name,:project_id);");
                $query->bindParam(':email', $data->email);
                $query->bindParam(':first_name', $data->first_name);
                $query->bindParam(':second_name', $data->second_name);
                $query->bindParam(':project_id', $project_id);
                $status2 = $query->execute();
                if ($status2) {
                    echo '';
                }
                else {
                    $error = "Wystąpił nieoczekiwany problem (KOD: 2). Spróbuj ponownie później.";
                    echo $error;
                }
            }
            else {
                $error = "Wystąpił nieoczekiwany problem (KOD: 1). Spróbuj ponownie później.";
                echo $error;
            }
        }
        else {
            $error = "Aplikowałeś/aś już do jednego projektu.";
            echo $error;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $connection = null;

?>