<?php
include "../header.php";
include "../db.php";
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $fetch_id = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($fetch_id);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_row = $result->fetch_assoc();
    $user_id = $user_row['id'];
    $stmt->close();
}
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
        
        function addTags(){
            var tags = document.getElementById('tags');
            if (tags.style.display === 'none' || tags.style.display === '') {
                tags.style.display = 'block';
                window.scrollTo(0, document.body.scrollHeight);
            }
            else {
                tags.style.display = 'none';
            }
        }
    </script>
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
<body class="relative">
    <div class="container mx-auto p-4 absolute text-white">
        <h1 class="text-6xl font-bold mb-6 text-center text-[#FF007F] mt-6">Dashboard</h1>
        <button onclick="toggleForm()" class="bg-[#FF007F] hover:bg-[#8A2BEE] text-white font-bold py-2 px-4 rounded mb-4">Create Poll</button>
        <div id="createPollForm" style="display: none;" class="bg-black/40 p-4 rounded-lg mb-4 shadow-lg max-w-md mx-auto">
            <form action="addpoll.php" method="POST">
                <button onclick="addTags()" class="bg-[#FA1232] p-2 rounded-lg">Add tags</button>
                <div id="tags" style="display: none;">
                    <?php
                    $disp_tags = "SELECT * FROM niches";
                    $stmt = $conn->prepare($disp_tags);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result) {
                        while ($tag_row = $result->fetch_assoc()) {
                            echo"<input type='checkbox' name='tags[]' value='" . $tag_row['id'] . "'>";
                            echo"<label for tags[]>" . htmlspecialchars($tag_row['niche']) . "</label><br>";
                        }
                    }
                    $stmt->close();
                    ?>
                </div>
                <div class="mb-4">
                    <label for="topic" class="block text-sm font-medium">Topic</label>
                    <input type="text" name="topic" id="topic" class="w-full p-2 mt-1 rounded bg-[#8A2BE2] text-white" required>
                </div>
                <div id="options" class="mb-4">
                    <div>
                        <input type="text" name="options[]" placeholder="Option 1" class="w-full p-2 mt-1 rounded bg-[#8A2BE2] text-white" required>
                    </div>
                    <div>
                        <input type="text" name="options[]" placeholder="Option 2" class="w-full p-2 mt-1 rounded bg-[#8A2BE2] text-white" required>
                    </div>
                </div>
                <button type="button" onclick="addOption()" class="w-full bg-[#FF007F] hover:bg-[#0C969C] text-white font-bold py-2 px-4 rounded mb-4">Add Option</button>
                <button type="submit" name="create_poll" class="w-full bg-[#FF007F] hover:bg-[#0C969C] text-white font-bold py-2 px-4 rounded">Create Poll</button>
            </form>
        </div>
        <?php
        $disp_poll = "SELECT DISTINCT polls.*, users.username 
                      FROM polls 
                      JOIN users ON polls.created_by = users.id
                      JOIN tags ON polls.id = tags.poll_id
                      JOIN interests ON tags.niche_id = interests.niche_id
                      WHERE polls.id NOT IN (SELECT poll_id FROM votes WHERE user_id = ?) 
                      AND interests.user_id = ?
                      ORDER BY polls.created_at ASC";
        $stmt = $conn->prepare($disp_poll);
        $stmt->bind_param("ii", $user_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            echo"<div class='w-full flex gap-4 overflow-x-auto scrollbar-hide pb-4'>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='bg-black/40 p-4 rounded-lg mb-4 shadow-lg min-w-[400px]'>";
                echo "<h1 class='text-2xl font-bold text-center'>" . htmlspecialchars($row['topic']) . "</h1><br>";
                echo "<div class='flex justify-around color-gray text-xs'> <h1><strong>Posted by: </strong>" . htmlspecialchars($row['username']) . "</h1>";
                echo "<h1><strong>At:</strong> " . htmlspecialchars($row['created_at']) . "</h1> </div> <br>";
                $poll_id = $row['id'];

                $disp_tags = "SELECT niches.niche 
                              FROM tags 
                              JOIN niches ON tags.niche_id = niches.id 
                              WHERE tags.poll_id = ?";
                $tag_stmt = $conn->prepare($disp_tags);
                $tag_stmt->bind_param("i", $poll_id);
                $tag_stmt->execute();
                $tag_result = $tag_stmt->get_result();
                if ($tag_result) {
                    echo "<div class='flex flex-wrap gap-2 mb-4'>";
                    while ($tag_row = $tag_result->fetch_assoc()) {
                        echo "<span class='bg-[#8A2BE2] text-white px-2 py-1 rounded'>" . htmlspecialchars($tag_row['niche']) . "</span>";
                    }
                    echo "</div>";
                }
                $tag_stmt->close();

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
                echo "</div>";
            }
            echo"</div>";
        } else {
            echo "<p>No polls available</p>";
        }
        $stmt->close();
        ?>
    </div>
</body>
</html>