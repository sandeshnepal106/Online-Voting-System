<?php
session_start();
include "../db.php";

if (!isset($_SESSION['username'])) {
    header("location: /WebDev/Online-Voting-System/entry/login.php");
    exit();
}

if (isset($_POST['create_poll'])) {
    $topic = $_POST['topic'];
    $tags = $_POST['tags'];
    $options = $_POST['options'];
    $username = $_SESSION['username'];
    $fetch_id = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($fetch_id);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user_row = $result->fetch_assoc();
        $user_id = $user_row['id'];
        $sql = "INSERT INTO polls (topic, created_by) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $topic, $user_id);
        if ($stmt->execute()) {
            $poll_id = $stmt->insert_id;
            if (!empty($tags)) {
                $insertTags = "INSERT INTO tags (poll_id, niche_id) VALUES (?, ?)";
                $tagsStmt = $conn->prepare($insertTags);
                foreach ($tags as $niche) {
                    $tagsStmt->bind_param("ii", $poll_id, $niche);
                    $tagsStmt->execute();
                }
                $tagsStmt->close();
            }
            $options_sql = "INSERT INTO poll_options (poll_id, option_text) VALUES (?, ?)";
            $stmt = $conn->prepare($options_sql);
            foreach ($options as $index => $option) {
                $stmt->bind_param("is", $poll_id, $option);
                $stmt->execute();
            }
            echo "<script>
                    alert('Poll created successfully');
                    location.replace('dashboard.php');
                  </script>";
        } else {
            echo "<script>
                    alert('Error creating poll');
                  </script>";
        }
    } else {
        echo "<script>
                alert('User not found');
              </script>";
    }
    $stmt->close();
}
?> 