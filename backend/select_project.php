<?php
    include 'connect_db.php';
    $dataJSON = file_get_contents("php://input");
    $data = json_decode($dataJSON);

    try {
        $connection = connect_db();
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $connection->prepare("SELECT id FROM submitted_projects WHERE email='".$data->email."';");
        $query->execute();
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        
        $row = $query->fetch();
        if ($row == NULL) {
            try {
                $connection = connect_db();
                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $query = $connection->prepare("INSERT INTO submitted_projects (email,first_name,second_name,project_id) VALUES ('".$data->email."','".$data->first_name."','".$data->second_name."','".$data->project_id."');");
                $query->execute();
                echo '';
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
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