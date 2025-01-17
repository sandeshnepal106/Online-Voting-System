<?php
include "../header.php";
include "../db.php";
$username = $_SESSION['username'];
$fetch_id = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($fetch_id);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user_row = $result->fetch_assoc();
$user_id = $user_row['id'];
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        let optionCount = 2;
        function addOption() {
            optionCount++;
            var optionsDiv = document.getElementById('options');
            var newOption = document.createElement('div');
            newOption.innerHTML = '<input type="text" name="options[]" placeholder="Option ' + optionCount + '" class="w-full p-2 mt-1 rounded bg-[#031716] text-white">';
            optionsDiv.appendChild(newOption);
        }

        function toggleForm() {
            var form = document.getElementById('createPollForm');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
                window.scrollTo(0, document.body.scrollHeight);
            } else {
                form.style.display = 'none';
            }
        }
    </script>
</head>
<body class="text-white">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6 text-center">Dashboard</h1>
        <button onclick="toggleForm()" class="bg-[#0A7075] hover:bg-[#0C969C] text-white font-bold py-2 px-4 rounded mb-4">Create Poll</button>
        <div id="createPollForm" style="display: none;" class="bg-[#032F30] p-4 rounded-lg mb-4 shadow-lg max-w-md mx-auto">
            <form action="addpoll.php" method="POST">
                <div class="mb-4">
                    <label for="topic" class="block text-sm font-medium">Topic</label>
                    <input type="text" name="topic" id="topic" class="w-full p-2 mt-1 rounded bg-[#031716] text-white" required>
                </div>
                <div id="options" class="mb-4">
                    <div>
                        <input type="text" name="options[]" placeholder="Option 1" class="w-full p-2 mt-1 rounded bg-[#031716] text-white" required>
                    </div>
                    <div>
                        <input type="text" name="options[]" placeholder="Option 2" class="w-full p-2 mt-1 rounded bg-[#031716] text-white" required>
                    </div>
                </div>
                <button type="button" onclick="addOption()" class="w-full bg-[#0A7075] hover:bg-[#0C969C] text-white font-bold py-2 px-4 rounded mb-4">Add Option</button>
                <button type="submit" name="create_poll" class="w-full bg-[#0A7075] hover:bg-[#0C969C] text-white font-bold py-2 px-4 rounded">Create Poll</button>
            </form>
        </div>
        <?php
        $disp_poll = "SELECT polls.*, users.username FROM polls JOIN users ON polls.created_by = users.id";
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
                $vote_stmt->bind_param("ii", $poll_id, $user_id);
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
                        echo "<form action='vote.php' method='POST' class='mt-4'>";
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
                echo "</div>";
            }
        } else {
            echo "<p>No polls available</p>";
        }
        ?>
    </div>
</body>
</html>