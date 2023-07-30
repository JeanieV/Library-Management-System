<?php
session_start();
require './functions.php';

if (isset($_SESSION['newUsername'])) {
    $username = $_SESSION['newUsername'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel='stylesheet' type='text/css' media='screen' href='../css/member.css'>
</head>

<body>

    <!-- Return Home Button -->
    <form method="POST">
        <button type="submit" name="returnHome" class="tranBack"><img class="homeButton mx-3 mt-3"
                src="../img/home1.gif" alt="Back to Home Page" title="Back to Home Page"
                attribution="https://www.flaticon.com/free-animated-icons/home"></button>
    </form>

    <form method="POST" name="bookView" class="bookView p-5">
        <?php
        $memberRentals = getMemberRentals($memberID);

        // Display the rentals on the dashboard
        foreach ($memberRentals as $rental) {
            $title = $rental['title'];
            $thumbnail = $rental['thumbnail'];
            $returnDate = $rental['return_date'];

            echo "<p>Title: $title</p>";
            echo "<img src='$thumbnail' alt='Book Cover'>";
            echo "<p>Return Date: $returnDate</p>";
            echo "
    <hr>";
        }
        ?>
    </form>


</body>

</html>