<?php
include "../header.php";
?>
<body>
    <div class="flex justify-center items-center h-screen">
        <form action="" method="POST" class="bg-[#0C969C] flex flex-col items-center justify-center self-center w-1/5 p-10 rounded-lg space-y-4">
            <div>
                <input type="text" name="fullname" placeholder="Full Name" class="w-full border border-gray-300 p-1 bg-white rounded-lg">
            </div>
            <div>
                <input type="text" name="username" placeholder="Username" class="w-full border border-gray-300 p-1 bg-white rounded-lg">
            </div>
            <div>
                <input type="tel" name="contact" placeholder="Contact No." class="w-full border border-gray-300 p-1 bg-white rounded-lg">
            </div>
            <div>
                <input type="email" name="email" placeholder="Email" class="w-full border border-gray-300 p-1 bg-white rounded-lg">
            </div>
            <div>
                <input type="password" name="password" placeholder="Password" class="w-full border border-gray-300 p-1 bg-white rounded-lg">
            </div>
            <input type="submit" name="submit" value="Register" class="bg-[#031716] text-white p-2 rounded-lg">
            <button type="button"><a href="login.php">Already have an account?</a></button>
        </form>
    </div>
      
</body>
<?php
include "../db.php";
if (isset($_POST['submit'])) {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (fullname, username, contact, email, password) VALUES ('$fullname', '$username', '$contact', '$email', '$password')";
    $insert= mysqli_query($conn, $sql);
    if ($insert) {
        ?>
        <script>
            alert("New record created successfully");
        </script>
        <?php
    } else {
        ?>
        <script>
            alert("Error: ");
        </script>
        <?php
    }
}
?>
