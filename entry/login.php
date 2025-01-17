<?php
include "../header.php";
include "../db.php";

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $res = $row['password'];
        $passcheck = password_verify($password, $res);
        if ($passcheck) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['id'] = $row['id'];
            echo "<script>
                    alert('Login successful');
                    location.replace('/WebDev/Online-Voting-System/actions/dashboard.php');
                  </script>";
        } else {
            echo "<script>
                    alert('Login failed');
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#274D60] text-white">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-[#032F30] p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
            <form action="" method="POST">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input type="email" name="email" id="email" class="w-full p-2 mt-1 rounded bg-[#031716] text-white" required>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium">Password</label>
                    <input type="password" name="password" id="password" class="w-full p-2 mt-1 rounded bg-[#031716] text-white" required>
                </div>
                <button type="submit" name="submit" class="w-full bg-[#0A7075] hover:bg-[#0C969C] text-white font-bold py-2 px-4 rounded">Login</button>
            </form>
            <div class="mt-4 text-center">
                <a href="register.php" class="text-[var(--color-muted)] hover:underline">Not a user yet? Register here</a>
            </div>
        </div>
    </div>
</body>
</html>