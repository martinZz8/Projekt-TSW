<?php
    include 'connect_db.php';
    $dataJSON = file_get_contents("php://input");
    $data = json_decode($dataJSON);

    try {
        $connection = connect_db();
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $connection->prepare("SELECT id FROM users WHERE email=:email;");
        $query->bindParam(':email', $data->user_email);
        $query->execute();
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        
        $row = $query->fetch();
        if ($row == NULL) {
            $connection = connect_db();
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $email = $data->user_email;
            $pass_hash = password_hash($data->user_password, PASSWORD_BCRYPT);
            $blocked = "1";            
            $query = $connection->prepare("INSERT INTO users (email, pass_hash, blocked) VALUES (:email,:pass_hash,:blocked);");
            $query->bindParam(':email', $email);
            $query->bindParam(':pass_hash', $pass_hash);
            $query->bindParam(':blocked', $blocked);
            $status = $query->execute();
            if ($status) {
                echo '';
            }
            else {
                $error = "Wystąpił nieoczekiwany problem. Spróbuj ponownie później.";
                echo $error;
            }
        }
        else {
            $error = "Istnieje już konto o podanym adresie email.";
            echo $error;
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $connection = null;
?>