<?php
    include 'connect_db.php';
    $dataJSON = file_get_contents("php://input");
    $data = json_decode($dataJSON);

    try {
        $connection = connect_db();
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $connection->prepare("DELETE FROM submitted_projects WHERE email=:email;");
        $query->bindParam(':email', $data->email);
        $status = $query->execute();
        if ($status) {
            echo '';
        }
        else {
            $error = 'Nie aplikowałeś/aś wcześniej do żadnego projektu.';
            echo $error;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $connection = null;
?>