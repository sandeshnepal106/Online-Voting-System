<?php
include "addpoll.php";
?>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <div>
        <?php
        $disp_poll ="SELECT * FROM polls";
        $result = mysqli_query($conn, $disp_poll);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo $row['topic'] . "<br>";
                $poll_id = $row['id'];
                $disp_options = "SELECT * FROM poll_options WHERE poll_id = $poll_id" ;
                $opt_result = mysqli_query($conn, $disp_options);
                if ($opt_result) {
                    ?>
                    <form action="" method = "POST">
                        <?php
                        while ($opt_row = mysqli_fetch_assoc($opt_result)) {
                            ?>
                            <label>
                                <input type="radio" name="vote[<?php echo $opt_row['id']?>]" value="<?php echo $opt_row['id']; ?>">
                            </label>
                            
                            <?php 
                                echo $opt_row['option_text']; 
                                
                            ?>
                            
                            <?php
                        }
                        
                        ?>
                        <input type="submit" name="submit" value="Vote">
                    </form>
                    <?php
                    if (isset($_POST['submit'])) {
                        $username = $_SESSION['username'];
                        $fetch_id = "SELECT id FROM users WHERE username = '$username'";
                        $fetch = mysqli_query($conn,$fetch_id);
                        if($fetch) {
                        $user_row = mysqli_fetch_assoc($fetch);
                        $user_id = $user_row['id'];
                }
                        foreach ($_POST['vote'] as $option_id) {
                            $sql = "INSERT INTO votes (poll_id, user_id, option_id) VALUES ('$poll_id', '$user_id', '$option_id')";
                            $result = mysqli_query($conn, $sql);
                            if ($result) {
                                ?>
                                <script>
                                    alert("Voted successfully");
                                </script>
                                <?php
                            }
                            else {
                                ?>
                                <script>
                                    alert("Failed to vote");
                                </script>
                                <?php
                            }
                        }
                    }
                }
            }
        } 
        else {
            echo "No polls available";
        }
        
        ?>
    </div>
</body>
</html>