<?php
include "../header.php";
include "../db.php";
if (isset($_SESSION['id'])){
    $user_id = $_SESSION['id'];
}
if (isset($_POST['submit'])) {
    $niches = $_POST['niches'];
    foreach ($niches as $niche) {
        $sql = "INSERT INTO interests(user_id, niche_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $niche);
        if ($stmt->execute()) {
            echo "<script>
                    alert('Interests added successfully');
                    location.replace('/WebDev/Online-Voting-System/index.php');
                  </script>";
        } else {
            echo "<script>
                    alert('Interests addition failed');
                  </script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body class="bg-[#274D60] text-white">
    <div class="sticky top-0 flex items-center justify-center min-h-screen text-white">
        <div class="bg-black/40 p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Choose Your Interests:</h2>
            <form action="" method="POST">
                <div>
                    <div>
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
                </div>
                <div class="mb-4">
                    <button type="submit" name="submit" class="w-full p-2 mt-1 rounded bg-[#8A2BE2] text-white">Submit</button>
                </div>
            </form>
            
        </div>
    </div>
</body>
</html>