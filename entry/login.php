<?php
include "../header.php";
?>
<body>
    <form action="" method="POST">
        <div>
            <input type="text" name="email" placeholder="Email">
        </div>
        <div>
            <input type="password" name="password" placeholder="Password">
        </div>
        
        <input type="submit" name="submit" class="" value="Login">
        <button type="button"><a href="register.php">Not a user yet?</a></button>
    </form>
</body>
<?php
include "../db.php";

    if (isset($_POST['submit'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result)) {
            $row = mysqli_fetch_assoc($result);
            $res = $row['password'];
            $passcheck = password_verify($password, $res);
            if ($passcheck) {
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['fullname'] = $row['fullname'];
                $_SESSION['id'] = $row['id'];
                ?>
                <script>
                    alert("Login successful");
                    location.replace("/WebDev/Online-Voting-System/actions/dashboard.php");
                </script>
                <?php
            } 
            else {
                ?>
                <script>
                    alert("Login failed");
                </script>
                <?php
            }
        }
        else {
            ?>
            <script>
                alert("User not found");
            </script>
            <?php
        }
    }
?>
