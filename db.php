<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ovs";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    ?>
    <script>
        alert("Connection failed: <?php echo $conn->connect_error; ?>");
    </script>
    <?php
        die("". $conn->connect_error);
}
else {
    ?>
    <!-- <script>
        alert("Connection successful");
    </script> -->
    <?php
}