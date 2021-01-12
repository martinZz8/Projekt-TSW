<?php
//zwrócić: login_status, user_email, error
    include 'connect_db.php';
    $dataJSON = file_get_contents("php://input");
    $data = json_decode($dataJSON);
    $blocked = "0";

    try {
        $connection = connect_db();
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $connection->prepare("SELECT pass_hash FROM users WHERE email=:email AND blocked=:blocked;");
        $query->bindParam(':email', $data->user_email);
        $query->bindParam(':blocked', $blocked);
        $query->execute();
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        
        $row = $query->fetch();
        if($row != NULL) { //Podano dobry email i konto nie jest zablokowane.
            if (password_verify($data->user_password, $row['pass_hash'])) { //Podano dobry email i hasło. Konto nie jest zablokowane. Uzyskano autoryzację do konta.
                $json = array(
                    "login_status" => 'true',
                    "user_email" => $data->user_email,
                    "error" => ''
                );
            }
            else { //Podano dobry email, ale złe hasło. Konto nie jest zablokowane.
                $json = array(
                    "login_status" => 'false',
                    "user_email" => $data->user_email,
                    "error" => 'Podano błędne dane logowania (1).'
                );
            }
        }
        else { //Podano błędny email i/lub konto jest zablokowane. Nie sprawdzono jeszcze hasła.
            $blocked = "1";
            $connection = connect_db();
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = $connection->prepare("SELECT pass_hash FROM users WHERE email=:email AND blocked=:blocked;");
            $query->bindParam(':email', $data->user_email);
            $query->bindParam(':blocked', $blocked);
            $query->execute();
            $result = $query->setFetchMode(PDO::FETCH_ASSOC);
            
            $row = $query->fetch();
            if ($row != NULL) { // Podano dobry email i konto jest zablokowane. Nie sprawdzono jeszcze hasła.
                if (password_verify($data->user_password, $row['pass_hash'])) {// Podano dobry email i hasło, ale konto jest zablokowane.
                    $json = array(
                        "login_status" => 'false',
                        "user_email" => $data->user_email,
                        "error" => 'Twoje konto jest zablokowane. Skontaktuj się z administratorem w celu wyjaśnienia powodu zablokowania konta.'
                    );
                }
                else { //Podano dobry email ale złe hasło. Konto jest zablokowane.
                    $json = array(
                        "login_status" => 'false',
                        "user_email" => $data->user_email,
                        "error" => 'Podano błędne dane logowania (2).'
                    );
                }
            }
            else { //Podano zły adres email.
                $json = array(
                    "login_status" => 'false',
                    "user_email" => $data->user_email,
                    "error" => 'Podano błędne dane logowania (3).'
                );
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $connection = null;

    $data_ret = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    echo $data_ret;
?>