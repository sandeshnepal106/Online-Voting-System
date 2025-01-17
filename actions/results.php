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
</head>
<body class="bg-[#274D60] text-white">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6 text-center">Results</h1>
        <?php
        $disp_poll = "SELECT polls.*, users.username FROM polls JOIN users ON polls.created_by = users.id";
        $stmt = $conn->prepare($disp_poll);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='bg-[#032F30] p-4 rounded-lg mb-4 shadow-lg'>";
                echo "<strong>Topic:</strong> " . htmlspecialchars($row['topic']) . "<br>";
                echo "<strong>Created by:</strong> " . htmlspecialchars($row['username']) . "<br>";
                echo "<strong>Created at:</strong> " . htmlspecialchars($row['created_at']) . "<br>";
                $poll_id = $row['id'];

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
        $stmt->close();
        ?>
    </div>
</body>
</html>