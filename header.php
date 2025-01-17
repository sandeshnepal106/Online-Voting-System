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
<body class="bg-[#274D60] text-white">
    <header class="bg-[#031716] text-white h-16 flex items-center justify-between px-4 shadow-lg">
        <h1 class="text-xl font-bold">ONLINE VOTING SYSTEM</h1>
        <nav>
            <ul class="flex space-x-4">
                <li><a href="/WebDev/Online-Voting-System/index.php" class="hover:underline">Home</a></li>
                <li><a href="/WebDev/Online-Voting-System/actions/dashboard.php" class="hover:underline">Dashboard</a></li>
                <li><a href="/WebDev/Online-Voting-System/actions/results.php" class="hover:underline">Results</a></li>
                <?php if(!isset($_SESSION['username'])): ?>
                    <li><a href="/WebDev/Online-Voting-System/entry/login.php" class="hover:underline">Login/Register</a></li>
                <?php else: ?>
                    <li><a href="/WebDev/Online-Voting-System/entry/logout.php" class="hover:underline">Logout</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <div class="container mx-auto p-4">