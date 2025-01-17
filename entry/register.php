<?php
include "../header.php";
include "../db.php";

if (isset($_POST['submit'])) {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (fullname, username, contact, email, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $fullname, $username, $contact, $email, $password);
    if ($stmt->execute()) {
        echo "<script>
                alert('Registration successful');
                location.replace('login.php');
              </script>";
    } else {
        echo "<script>
                alert('Registration failed');
              </script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#274D60] text-white">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-[#032F30] p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>
            <form action="" method="POST">
                <div class="mb-4">
                    <label for="fullname" class="block text-sm font-medium">Full Name</label>
                    <input type="text" name="fullname" id="fullname" class="w-full p-2 mt-1 rounded bg-[#031716] text-white" required>
                </div>
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium">Username</label>
                    <input type="text" name="username" id="username" class="w-full p-2 mt-1 rounded bg-[#031716] text-white" required>
                </div>
                <div class="mb-4">
                    <label for="contact" class="block text-sm font-medium">Contact No.</label>
                    <input type="tel" name="contact" id="contact" class="w-full p-2 mt-1 rounded bg-[#031716] text-white" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input type="email" name="email" id="email" class="w-full p-2 mt-1 rounded bg-[#031716] text-white" required>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium">Password</label>
                    <input type="password" name="password" id="password" class="w-full p-2 mt-1 rounded bg-[#031716] text-white" required>
                </div>
                <button type="submit" name="submit" class="w-full bg-[#0A7075] hover:bg-[#0C969C] text-white font-bold py-2 px-4 rounded">Register</button>
            </form>
            <div class="mt-4 text-center">
                <a href="login.php" class="text-[var(--color-muted)] hover:underline">Already have an account? Login here</a>
            </div>
        </div>
    </div>
</body>
</html>