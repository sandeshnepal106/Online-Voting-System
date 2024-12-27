<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-cyan-500">
    <div>
    <header class="bg-[#031716] text-white h-16 flex items-center justify-between px-4">
        <h1 class="text-xl font-bold">ONLINE VOTING SYSTEM</h1>
        <?php
        if(!isset($_SESSION['username'])) {
            ?>
        <nav>
            <ul class="flex space-x-4">
                <li><a href="/WebDev/Online-Voting-System/index.php" class="hover:underline">Home</a></li>
                <li><a href="/WebDev/Online-Voting-System/actions/dashboard.php" class="hover:underline">Dashboard</a></li>
                <li><a href="/WebDev/Online-Voting-System/actions/results.php" class="hover:underline">Results</a></li>
                <li><a href="/WebDev/Online-Voting-System/entry/login.php" class="hover:underline">Login/Register</a></li>
            </ul>
        </nav>
        <?php
        }
        else {
            ?>
            <nav>
            <ul class="flex space-x-4">
                <li><a href="/WebDev/Online-Voting-System/index.php" class="hover:underline">Home</a></li>
                <li><a href="/WebDev/Online-Voting-System/actions/dashboard.php" class="hover:underline">Dashboard</a></li>
                <li><a href="/WebDev/Online-Voting-System/actions/results.php" class="hover:underline">Results</a></li>
                <li><a href="/WebDev/Online-Voting-System/entry/logout.php" class="hover:underline">Logout</a></li>
            </ul>
        </nav>
        <?php
        }
        ?>
    </header>  
    </div>
</body>
</html>