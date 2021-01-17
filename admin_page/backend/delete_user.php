<?php
    include '../../backend/connect_db.php';
    $dataJSON = file_get_contents("php://input");
    $data = json_decode($dataJSON);

    try {
        $connection = connect_db();
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $connection->prepare("DELETE FROM users WHERE id=:id;");
        $query->bindParam(':id', $data->user_id);
        $status = $query->execute();
        if ($status) {
            echo '';
        }
        else {
            $error = 'Nie udało się usunąć użytkownika. Spróbuj ponownie później.';
            echo $error;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $connection = null;
?>