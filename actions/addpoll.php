<?php
include "../header.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <form action="" method="POST">
            <div>
                <input type="text" name="topic" placeholder="Topic">
            </div>
            <div>
                <input type="text" name="option1" placeholder="Option 1">
            </div>
            <div>
                <input type="text" name="option2" placeholder="Option 2">
            </div>
            <input type="submit" name="create_poll" value="Create Poll">
        </form>
    </div>
</body>
</html>
<?php
include "../db.php";
if (!isset($_SESSION['username'])) {
    header("location: /WebDev/Online-Voting-System/entry/login.php");
}
if(isset($_POST['create_poll'])) {
    $topic = $_POST['topic'];
    $option1 =$_POST['option1'];
    $option2 =$_POST['option2'];
    $username = $_SESSION['username'];

    $fetch_id = "SELECT id FROM users WHERE username = '$username'";
    $fetch = mysqli_query($conn,$fetch_id);
    if($fetch) {
        $user_row = mysqli_fetch_assoc($fetch);
        $user_id = $user_row['id'];
    }

    $sql = "INSERT INTO polls (topic, created_by) VALUES ('$topic','$user_id')";
    $result = mysqli_query($conn, $sql);
    if($result) {
        $fetch_id = "SELECT id FROM polls WHERE topic = '$topic'";
        $fetch = mysqli_query($conn,$fetch_id);
        if($fetch) {
            $poll_row = mysqli_fetch_assoc($fetch);
            $poll_id = $poll_row['id'];
        }
        $options ="INSERT INTO poll_options (poll_id, option_text) VALUES ('$poll_id', '$option1'),
                                                                  ('$poll_id', '$option2')"; 
        $res = mysqli_query($conn, $options);
        if($res) {
            echo "Poll created successfully";
        }
        else {
            echo "Error creating poll";
        }
    }
}
?>