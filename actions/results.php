<?php
include "../header.php";
include "../db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results</title>
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
    <div class="container mx-auto p-4 absolute text-white">
        <h1 class="text-6xl font-bold mb-6 text-center text-[#FF007F] mt-6">Results</h1>
        <?php
        $user_id = $_SESSION['id'];
        $disp_poll = "SELECT polls.*, users.username FROM polls JOIN users ON polls.created_by = users.id";
        $stmt = $conn->prepare($disp_poll);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            echo "<div class='w-full flex gap-4 overflow-x-auto scrollbar-hide pb-4'>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='bg-black/40 p-4 rounded-lg mb-4 shadow-lg min-w-[400px]'>";
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

                        echo "<div class='flex justify-between'>";
                        if ($opt_row['id'] == $voted_option_id) {
                            echo "<strong class='text-[#FF007F]'>" . htmlspecialchars($opt_row['option_text']) . " (Your vote)</strong>";
                        } else {
                            echo htmlspecialchars($opt_row['option_text']);
                        }

                        $disp_votes = "SELECT COUNT(*) as votes FROM votes WHERE poll_id = ? AND option_id = ?";
                        $vote_stmt = $conn->prepare($disp_votes);
                        $vote_stmt->bind_param("ii", $poll_id, $opt_row['id']);
                        $vote_stmt->execute();
                        $vote_result = $vote_stmt->get_result();
                        if ($vote_result) {
                            $vote_row = $vote_result->fetch_assoc();
                            echo "<span>Votes: " . $vote_row['votes'] . "</span>";
                        }
                        $vote_stmt->close();
                        echo "</div><br>";
                    }
                }
                $opt_stmt->close();
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>No polls available</p>";
        }
        $stmt->close();
        ?>
    </div>
</body>
</html>