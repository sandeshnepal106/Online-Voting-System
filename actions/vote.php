<?php
    include "../db.php";
    if(isset($_POST["$submit"])){
    $poll_id = $_POST['poll_id'];
    $option_id = $_POST['option_id'];
    $sql = "INSERT INTO votes (poll_id, user_id, option_id,) VALUES ('$poll_id', '$user_id', '$option_id')";
    $result = mysqli_query($conn, $sql);
    if($result) {
        echo "Voted successfully";
    }
    else {
        echo "Failed to vote";
    }
}
else {
    header("location: /WebDev/Online-Voting-System/index.php");
}