<?php
    include '../../backend/connect_db.php';
    $json = array();
    try {
        $connection = connect_db();
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $connection->prepare("SELECT p.id, p.topic, p.description, p.technologies, p.blocked, COUNT(s.id) FROM project_list p LEFT JOIN submitted_projects s ON p.id=s.project_id GROUP BY p.id");
        $query->execute();
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        $i = 0;
        while ($row = $query->fetch()) {
            $json[$i] = array(
                "id" => $row['id'],
                "topic" => $row['topic'],
                "description" => $row['description'],
                "technologies" => $row['technologies'],
                "blocked" => $row['blocked'],
                "select_counter" => $row['COUNT(s.id)']
            );
            $i = $i + 1;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $connection = null;
    
    $data = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    echo $data;
?>