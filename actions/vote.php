<?php
session_start();
include "../db.php";

if (isset($_POST['submit'])) {
    $poll_id = $_POST['poll_id'];
    $option_id = $_POST['vote'];
    $username = $_SESSION['username'];
    $fetch_id = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($fetch_id);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user_row = $result->fetch_assoc();
        $user_id = $user_row['id'];
        $check_vote = "SELECT * FROM votes WHERE poll_id = ? AND user_id = ?";
        $vote_stmt = $conn->prepare($check_vote);
        $vote_stmt->bind_param("ii", $poll_id, $user_id);
        $vote_stmt->execute();
        $vote_result = $vote_stmt->get_result();
        if ($vote_result->num_rows == 0) {
            $sql = "INSERT INTO votes (poll_id, user_id, option_id) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $poll_id, $user_id, $option_id);
            if ($stmt->execute()) {
                echo "<script>
                        alert('Voted successfully');
                        location.replace('dashboard.php');
                      </script>";
            } else {
                echo "<script>
                        alert('Failed to vote');
                        location.replace('dashboard.php');
                      </script>";
            }
        } else {
            echo "<script>
                    alert('You have already voted on this poll');
                    location.replace('dashboard.php');
                  </script>";
        }
        $vote_stmt->close();
    } else {
        echo "<script>
                alert('User not found');
                location.replace('dashboard.php');
              </script>";
    }
    $stmt->close();
}
?>