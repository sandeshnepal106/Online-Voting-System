<?php
include "../header.php";
include "../db.php";

//For fetching the user details
if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
    $fetch_id = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($fetch_id);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_row = $result->fetch_assoc();
    $user_id = $user_row['id'];
    $email = $user_row['email'];
    $contact = $user_row['contact'];
    $fullname = $user_row['fullname'];
    $stmt->close();
}

//For updating the user details
if (isset($_POST['update_user'])) {
    $new_fullname = $_POST['fullname'];
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];
    $new_contact = $_POST['contact'];

    $update_user = "UPDATE users SET fullname = ?, username = ?, email = ?, contact = ? WHERE id = ?";
    $stmt = $conn->prepare($update_user);
    $stmt->bind_param("sssii", $new_fullname, $new_username, $new_email, $new_contact, $user_id);
    $stmt->execute();
    $stmt->close();
    $_SESSION['username'] = $new_username;

    $niches = $_POST['niches'];
    $delete_niches = "DELETE FROM interests WHERE user_id = ?";
    $stmt = $conn->prepare($delete_niches);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
    foreach ($niches as $niche) {
        $insert_niches = "INSERT INTO interests(user_id, niche_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_niches);
        $stmt->bind_param("ii", $user_id, $niche);
        $stmt->execute();
        $stmt->close();
    }
    
}

//For deleting a poll
if (isset($_POST['delete_poll'])) {
    $poll_id = $_POST['poll_id'];
    $delete_poll = "DELETE FROM polls WHERE id = ? AND created_by = ?";
    $stmt = $conn->prepare($delete_poll);
    $stmt->bind_param("ii", $poll_id, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: profile.php");
    exit();
}

// For updating the niches a user had selected during registration. First delete the all the previous niches of the current user and then insert the new niches.
if (isset($_POST['edit_niches'])) {
    $user_id = $_SESSION['user_id'];
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /*For hiding the scroll bar*/
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    <script>
        //Function to show Edit Information form
        function editInfo() {
            var button = document.getElementById('editButton');
            var edit =  document.getElementById('editForm');
            var info =  document.getElementById('userInfo');
            if (edit.style.display === 'none' || edit.style.display === '') {
                edit.style.display = 'block';
                info.style.display = 'none';
                button.style.display = 'none';
                window.scrollTo(0, document.body.scrollHeight);
            } else {
                edit.style.display = 'none';
            }
        }
    </script>
</head>
<body class="relative text-white flex flex-col items-center justify-center">
    <div class="container mx-auto p-4 relative text-white bg-black/40 p-6 rounded-lg mb-4 shadow-lg max-w-md mx-auto">
        <h1 class="text-4xl font-bold mb-6 text-center text-[#FF007F]">Profile</h1>

        <!-- For displaying the user information -->
        <div id="userInfo">
            <h2 class="text-2xl font-bold mb-4">User Information</h2>
            <p><strong>Fullname:</strong> <?php echo htmlspecialchars($fullname); ?></p>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p><strong>Contact:</strong> <?php echo htmlspecialchars($contact); ?></p>

            <!-- Display the niches selected by the user -->
            <p><strong>Niches:</strong></p>
            <?php
            $disp_niches = "SELECT niches.niche 
                           FROM interests 
                           JOIN niches ON interests.niche_id = niches.id 
                           WHERE interests.user_id = ?";
            $stmt = $conn->prepare($disp_niches);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                echo "<div class='flex flex-wrap gap-2'>";
                while ($niche_row = $result->fetch_assoc()) {
                    echo "<span class='bg-[#8A2BE2] text-white px-2 py-1 rounded'>" . htmlspecialchars($niche_row['niche']) . "</span>";
                }
                echo "</div>";
            }
            $stmt->close();
            ?>
        </div>
        
        <!-- For editing the user info -->
        <div>
            <button onclick="editInfo()" id="editButton" class="w-1/2 bg-[#FF007F] hover:bg-[#0C969C] text-white font-bold py-2 px-4 rounded mt-2 justify-self-center">Edit Information</button>
            <div id="editForm" style="display: none;">
                <form action="profile.php" method="POST">
                    <div class="mb-4">
                        <label for="fullname" class="block text-sm font-medium">Fullname</label>
                        <input type="text" name="fullname" id="fullname" value="<?php echo htmlspecialchars($fullname); ?>" class="w-full p-2 mt-1 rounded bg-[#8A2BE2] text-white" required>
                    </div>
                    <div class="mb-4">
                        <label for="username" class="block text-sm font-medium">Username</label>
                        <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>" class="w-full p-2 mt-1 rounded bg-[#8A2BE2] text-white" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium">Email</label>
                        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" class="w-full p-2 mt-1 rounded bg-[#8A2BE2] text-white" required>
                    </div>
                    <div class="mb-4">
                        <label for="contact" class="block text-sm font-medium">Contact</label>
                        <input type="text" name="contact" id="contact" value="<?php echo htmlspecialchars($contact); ?>" class="w-full p-2 mt-1 rounded bg-[#8A2BE2] text-white" required>

                    </div>
                    <!-- Edit the interests -->
                    <div>
                        <h2 class="text-2xl font-bold mb-6 text-center">Choose Your Interests:</h2>
                        <?php
                        $disp_niches = "SELECT * FROM niches";
                        $stmt = $conn->prepare($disp_niches);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result) {
                            while ($niche_row = $result->fetch_assoc()) {
                                echo "<input type='checkbox' name='niches[]' value='" . $niche_row['id'] . "'>";
                                echo "<label for='niches[]'>" . $niche_row['niche'] . "</label><br>";
                            }
                        }
                        ?>
                    </div>
                    <button type="submit" name="update_user" class="w-full bg-[#FF007F] hover:bg-[#0C969C] text-white font-bold py-2 px-4 rounded">Update Information</button>
                </form>
            </div>  
        </div>

    </div>

    <div class="container mx-auto p-4 relative text-white">
        <h2 class="text-2xl font-bold mb-4 text-center text-[#FF007F]">Your Polls</h2>
        <!-- Display the polls created by the present user -->
        <?php
        $disp_poll = "SELECT * FROM polls WHERE created_by = ?";
        $stmt = $conn->prepare($disp_poll);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            echo "<div class='w-full flex gap-4 overflow-x-auto scrollbar-hide pb-4'>";
            while ($row = mysqli_fetch_assoc($result)) {
                // Display topic, created_at, and the user who created the poll
                echo "<div class='bg-black/40 p-4 rounded-lg mb-4 shadow-lg min-w-[400px]'>";
                echo "<h1 class='text-2xl font-bold text-center'>" . htmlspecialchars($row['topic']) . "</h1><br>";
                echo "<div class='flex justify-around text-xs'> <h1><strong>Posted by: </strong>" . htmlspecialchars($username) . "</h1>";
                echo "<h1><strong>At:</strong> " . htmlspecialchars($row['created_at']) . "</h1> </div> <br>";
                $poll_id = $row['id'];

                // Display the tags of the poll
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

                // Display the options of the poll and the vote button
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

                // Display the results of the poll with the options and the vote count
                echo "<h3 class='text-xl font-bold mt-4'>Results</h3>";
                $disp_results = "SELECT poll_options.option_text, COUNT(votes.id) as vote_count 
                                 FROM poll_options 
                                 LEFT JOIN votes ON poll_options.id = votes.option_id 
                                 WHERE poll_options.poll_id = ? 
                                 GROUP BY poll_options.id";
                $res_stmt = $conn->prepare($disp_results);
                $res_stmt->bind_param("i", $poll_id);
                $res_stmt->execute();
                $res_result = $res_stmt->get_result();
                if ($res_result) {
                    echo "<div class='mt-4'>";
                    while ($res_row = $res_result->fetch_assoc()) {
                        echo "<p>" . htmlspecialchars($res_row['option_text']) . ": " . $res_row['vote_count'] . " votes</p>";
                    }
                    echo "</div>";
                }
                $res_stmt->close();

                //Option for deleting the poll along with the confirm delete message
                echo "<form action='profile.php' method='POST' onsubmit='return confirm(\"Are you sure you want to delete this poll?\");'>";
                echo "<input type='hidden' name='poll_id' value='" . $poll_id . "'>";
                echo "<button type='submit' name='delete_poll' class='bg-red-600 text-white p-2 rounded block text-center mt-4'>Delete Poll</button>";
                echo "</form>";
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