<?php
    include '../../backend/connect_db.php';
    $dataJSON = file_get_contents("php://input");
    $data = json_decode($dataJSON);

    try {
        $connection = connect_db();
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $blocked = $data->blocked;
        $id = $data->id;
        $query = $connection->prepare("UPDATE users SET blocked=:blocked WHERE id=:id;");
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