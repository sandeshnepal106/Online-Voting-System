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
<body class="font-serif">
    
    <div >
        <video autoplay muted loop class="fixed object-cover w-full h-full">
            <source src="\WebDev\Online-Voting-System\videos\bg1.mp4" type="video/mp4">
        </video>
    </div>
    <header class="sticky top-0 text-white h-16 flex items-center justify-between px-4 shadow-lg z-10 bg-black/40">
        <h1 class="text-xs md:text-lg lg-text:xl font-bold text-[#D8BFD8]">ONLINE VOTING SYSTEM</h1>
        <nav>
            <ul class="flex space-x-4 text-[#D8BFD8] text-xs md:text-sm lg-text:md font-bold">
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
    