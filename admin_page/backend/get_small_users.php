<?php
    include '../../backend/connect_db.php';

    try {
        //Pobranie wszystkich użytkowników
        $connection = connect_db();
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $connection->prepare("SELECT id, email FROM users;");
        $query->execute();
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        $all_users = array();
        $i = 0;
        while ($row = $query->fetch()) {
            $all_users[$i] = array(
                "id" => $row['id'],
                "email" => $row['email']
            );
            $i = $i + 1;
        }
        
        //Pobranie użytkowników, którzy już aplikowali do projektu
        $connection = connect_db();
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $connection->prepare("SELECT email FROM submitted_projects;");
        $query->execute();
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        $submitted_users_emails = array();
        $i = 0;
        while ($row = $query->fetch()) {
            $submitted_users_emails[$i] = $row['email'];
            $i = $i + 1;
        }

        //Wyodrębnienie użytkowników, którzy jeszcze nie aplikowali do żadnego projektu
        //tu jest błąd
        $right_users = array();
        $i = 0;
        foreach ($all_users as $user) {
            $flag = true;
            foreach ($submitted_users_emails as $sub_user_email) {
                if ($user['email'] == $sub_user_email) {
                    $flag = false;
                    break;
                }
            }
            if ($flag) {
                $right_users[$i] = $user;
                $i = $i + 1;
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $connection = null;
    
    $data = json_encode($right_users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    echo $data;
?>