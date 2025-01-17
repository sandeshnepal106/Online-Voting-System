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
</head>
<body class="bg-home text-white">
    <div class="container mx-auto p-4">
        <?php if (!isset($_SESSION['username'])): ?>
            <h1 class="text-4xl font-bold mb-6 text-center">Welcome to the Online Voting System</h1>
            <p class="mb-4">Our online voting system allows you to create and participate in polls easily and securely. Whether you're organizing a small event or a large-scale election, our platform provides the tools you need to manage your voting process efficiently.</p>
            <h2 class="text-3xl font-bold mb-4">History</h2>
            <p class="mb-4">Founded in 2024, our online voting system has been used by thousands of users worldwide. Our mission is to provide a reliable and user-friendly platform for conducting polls and elections.</p>
            <h2 class="text-3xl font-bold mb-4">Features</h2>
            <ul class="list-disc list-inside mb-4">
                <li>Secure and anonymous voting</li>
                <li>Easy results display</li>
                <li>Easy poll creation and management</li>
                <li>Responsive design for all devices</li>
            </ul>
            <h2 class="text-3xl font-bold mb-4">Get Started</h2>
            <p class="mb-4">To get started, please <a href="/WebDev/Online-Voting-System/entry/login.php" class="text-[#0A7075] hover:underline">log in</a> or <a href="/WebDev/Online-Voting-System/entry/register.php" class="text-[#0A7075] hover:underline">register</a> if you don't have an account.</p>
        <?php else: ?>
            <h1 class="text-4xl font-bold mb-6 text-center">Dashboard</h1>
            <?php
            $disp_poll = "SELECT polls.*, users.username FROM polls JOIN users ON polls.created_by = users.id ORDER BY polls.created_at ASC";
            $result = mysqli_query($conn, $disp_poll);
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='bg-[#032F30] p-4 rounded-lg mb-4 shadow-lg'>";
                    echo "<strong>Topic:</strong> " . htmlspecialchars($row['topic']) . "<br>";
                    echo "<strong>Created by:</strong> " . htmlspecialchars($row['username']) . "<br>";
                    echo "<strong>Created at:</strong> " . htmlspecialchars($row['created_at']) . "<br>";
                    $poll_id = $row['id'];
                    $check_vote = "SELECT option_id FROM votes WHERE poll_id = ? AND user_id = ?";
                    $vote_stmt = $conn->prepare($check_vote);
                    $vote_stmt->bind_param("ii", $poll_id, $_SESSION['id']);
                    $vote_stmt->execute();
                    $vote_result = $vote_stmt->get_result();
                    if ($vote_result->num_rows > 0) {
                        $vote_row = $vote_result->fetch_assoc();
                        $voted_option_id = $vote_row['option_id'];
                        $disp_options = "SELECT * FROM poll_options WHERE poll_id = ?";
                        $opt_stmt = $conn->prepare($disp_options);
                        $opt_stmt->bind_param("i", $poll_id);
                        $opt_stmt->execute();
                        $opt_result = $opt_stmt->get_result();
                        if ($opt_result) {
                            while ($opt_row = $opt_result->fetch_assoc()) {
                                if ($opt_row['id'] == $voted_option_id) {
                                    echo "<strong>" . htmlspecialchars($opt_row['option_text']) . " (Your vote)</strong><br>";
                                } else {
                                    echo htmlspecialchars($opt_row['option_text']) . "<br>";
                                }
                            }
                        }
                        $opt_stmt->close();
                    } else {
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
                    }
                    $vote_stmt->close();
                    echo "<h3 class='text-2xl font-bold mt-4'>Results</h3>";
                    $disp_options = "SELECT * FROM poll_options WHERE poll_id = ?";
                    $opt_stmt = $conn->prepare($disp_options);
                    $opt_stmt->bind_param("i", $poll_id);
                    $opt_stmt->execute();
                    $opt_result = $opt_stmt->get_result();
                    if ($opt_result) {
                        while ($opt_row = $opt_result->fetch_assoc()) {
                            echo htmlspecialchars($opt_row['option_text']) . "<br>";

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
                echo "<p>No polls available</p>";
            }
            ?>
        <?php endif; ?>
    </div>
</body>
</html>