<?php
include "header.php";
include "db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="relative text-white">
    <div class="container mx-auto pt-16">
        <?php if (!isset($_SESSION['username'])): ?>
            <div class="sticky top-0 p-4 rounded-lg shadow-lg mb-4 text-[#F0F0F0]">
                <h1 class="text-xl mb-3 md:text-5xl mb-6 lg:text-8xl mb-9 font-bold text-center text-[#FF007F]">
                    <span class="hover:text-[#ffffff] hover:text-9xl">Welcome </span>
                    <span class="hover:text-[#ffffff] hover:text-9xl">to </span>
                    <span class="hover:text-[#ffffff] hover:text-9xl">the </span>
                    <span class="hover:text-[#ffffff] hover:text-9xl">Online </span>
                    <span class="hover:text-[#ffffff] hover:text-9xl">Voting </span>
                    <span class="hover:text-[#ffffff] hover:text-9xl">System </span>
                </h1>
                <p class="text-md mb-2 md:text-xl mb-3 lg:text-2xl mb-5">Our online voting system allows you to create and participate in polls easily and securely. Whether you're organizing a small event or a large-scale election, our platform provides the tools you need to manage your voting process efficiently.</p>
                <h2 class="text-lg mb-2 md:text-2xl mb-3 lg:text-4xl mb-5 font-bold text-[#FFD700]">History</h2>
                <p class="text-md mb-2 md:text-xl mb-3 lg:text-2xl mb-5">Founded in 2024, our online voting system has been used by thousands of users worldwide. Our mission is to provide a reliable and user-friendly platform for conducting polls and elections.</p>
                <h2 class="text-lg mb-2 md:text-2xl mb-3 lg:text-4xl mb-5 font-bold text-[#FFD700]">Features</h2>
                <ul class="list-disc list-inside text-sm mb-2 md:text-xl mb-3 lg:text-2xl mb-5">
                    <li>Secure and anonymous voting</li>
                    <li>Easy results display</li>
                    <li>Easy poll creation and management</li>
                    <li>Responsive design for all devices</li>
                </ul>
                <h2 class="text-3xl font-bold mb-4 text-[#FFD700]">Get Started</h2>
                <p class="mb-4">To get started, please <a href="/WebDev/Online-Voting-System/entry/login.php" class="text-[#FF007F] hover:underline">log in</a> or <a href="/WebDev/Online-Voting-System/entry/register.php" class="text-[#FF007F] hover:underline">register</a> if you don't have an account.</p>
            </div>
        <?php else: ?>
            <div class="sticky top-0 p-4 rounded-lg shadow-lg mb-4 text-[#F0F0F0]">
            <h1 class="text-4xl font-bold mb-6 text-center">Dashboard</h1>
            <div class="mb-8">
                <h2 class="text-2xl font-bold mb-4">Available Polls</h2>
                <div class="w-full flex gap-4 overflow-x-auto scrollbar-hide pb-4">
                    <?php
                    $user_id = $_SESSION['id'];
                    $disp_poll = "SELECT polls.*, users.username 
                                  FROM polls 
                                  JOIN users ON polls.created_by = users.id 
                                  WHERE polls.id NOT IN (SELECT poll_id FROM votes WHERE user_id = ?) 
                                  ORDER BY polls.created_at ASC";
                    $stmt = $conn->prepare($disp_poll);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<div class='bg-black/40 p-4 rounded-lg mb-4 shadow-lg min-w-[300px]'>";
                            echo "<h1 class='text-2xl font-bold text-center'>" . htmlspecialchars($row['topic']) . "</h1><br>";
                            echo "<div class='flex justify-around text-xs'> <h1><strong>Posted by: </strong>" . htmlspecialchars($row['username']) . "</h1>";
                            echo "<h1><strong>At:</strong> " . htmlspecialchars($row['created_at']) . "</h1> </div> <br>";
                            $poll_id = $row['id'];
                            $disp_options = "SELECT * FROM poll_options WHERE poll_id = ?";
                            $opt_stmt = $conn->prepare($disp_options);
                            $opt_stmt->bind_param("i", $poll_id);
                            $opt_stmt->execute();
                            $opt_result = $opt_stmt->get_result();
                            if ($opt_result) {
                                echo "<form action='actions/vote.php' method='POST' class='mt-4'>";
                                while ($opt_row = $opt_result->fetch_assoc()) {
                                    echo "<label class='block'>";
                                    echo "<input type='radio' name='vote' value='" . $opt_row['id'] . "' class='mr-2'>";
                                    echo htmlspecialchars($opt_row['option_text']);
                                    echo "</label>";
                                }
                                echo "<input type='hidden' name='poll_id' value='" . $poll_id . "'>";
                                echo "<button type='submit' name='submit' class='mt-2 bg-[#0A7075] hover:bg-[#0C969C] text-white font-bold py-2 px-4 rounded'>Vote</button>";
                                echo "</form>";
                            }
                            $opt_stmt->close();
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No polls available</p>";
                    }
                    $stmt->close();
                    ?>
                </div>
            </div>
            <div>
                <h2 class="text-2xl font-bold mb-4">Results</h2>
                <div class="w-full flex gap-4 overflow-x-auto scrollbar-hide pb-4">
                    <?php
                    $disp_poll = "SELECT polls.*, users.username FROM polls JOIN users ON polls.created_by = users.id";
                    $stmt = $conn->prepare($disp_poll);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<div class='bg-black/40 p-4 rounded-lg mb-4 shadow-lg min-w-[300px]'>";
                            echo "<h1 class='text-2xl font-bold text-center'>" . htmlspecialchars($row['topic']) . "</h1><br>";
                            echo "<div class='flex justify-around text-xs'> <h1><strong>Posted by: </strong>" . htmlspecialchars($row['username']) . "</h1>";
                            echo "<h1><strong>At:</strong> " . htmlspecialchars($row['created_at']) . "</h1> </div> <br>";
                            $poll_id = $row['id'];

                            $disp_options = "SELECT * FROM poll_options WHERE poll_id = ?";
                            $opt_stmt = $conn->prepare($disp_options);
                            $opt_stmt->bind_param("i", $poll_id);
                            $opt_stmt->execute();
                            $opt_result = $opt_stmt->get_result();
                            if ($opt_result) {
                                while ($opt_row = $opt_result->fetch_assoc()) {
                                    $voted_option_id = null;
                                    $check_vote = "SELECT option_id FROM votes WHERE poll_id = ? AND user_id = ?";
                                    $vote_stmt = $conn->prepare($check_vote);
                                    $vote_stmt->bind_param("ii", $poll_id, $user_id);
                                    $vote_stmt->execute();
                                    $vote_result = $vote_stmt->get_result();
                                    if ($vote_result->num_rows > 0) {
                                        $vote_row = $vote_result->fetch_assoc();
                                        $voted_option_id = $vote_row['option_id'];
                                    }
                                    $vote_stmt->close();

                                    if ($opt_row['id'] == $voted_option_id) {
                                        echo "<strong>" . htmlspecialchars($opt_row['option_text']) . " (Your vote)</strong><br>";
                                    } else {
                                        echo htmlspecialchars($opt_row['option_text']) . "<br>";
                                    }

                                    $disp_votes = "SELECT COUNT(*) as votes FROM votes WHERE poll_id = ? AND option_id = ?";
                                    $vote_stmt = $conn->prepare($disp_votes);
                                    $vote_stmt->bind_param("ii", $poll_id, $opt_row['id']);
                                    $vote_stmt->execute();
                                    $vote_result = $vote_stmt->get_result();
                                    if ($vote_result) {
                                        $vote_row = $vote_result->fetch_assoc();
                                        echo "Votes: " . $vote_row['votes'] . "<br>";
                                    }
                                    $vote_stmt->close();
                                }
                            }
                            $opt_stmt->close();
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No results available</p>";
                    }
                    $stmt->close();
                    ?>
                </div>
            </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>