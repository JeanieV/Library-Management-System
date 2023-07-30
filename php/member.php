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

    <!-- Show Available books -->
    <div class="container d-flex justify-content-center align-items-center">
        <div class="mt-5 mb-5">

            <form method="POST" name="bookView" class="bookView p-5">
                <?php $book1 = <<<DELIMETER
                <h1> Good day $username! </h1>
                <h2 class="my-5"> View our available book selection below. </h2>
                DELIMETER;
                echo $book1;

                // Call the bookView function to get the available books
                $availableBooks = bookView();

                if (!empty($availableBooks)) {

                    $heading = <<<DELIMITER
                            <table>
                            <tr>
                                <th> Book Cover </th>
                                <th> Title </th>
                                <th> Description </th>
                                <th> Author </th>
                                <th> Genre </th>
                                <th> Return Date </th>
                            </tr>
                        DELIMITER;
                    $rows = '';

                    foreach ($availableBooks as $book) {

                        $row = <<<DELIMITER
                            <tr>
                                <td class="p-4"> <img src="../img/{$book['thumbnail']}" class="bookCover"> </td>
                                <td class="title p-4"> <p> {$book['title']} </p> </td>
                                <td class="description p-4"> <p> {$book['description']} </p> </td> 
                                <td class="p-4"> <p> {$book['author']} </p> </td>
                                <td class="p-4"> <p> {$book['genre']} </p> </td>
                                <td class="return p-4"> <p> {$book['return_date']} </p> </td>
                                <td class="p-4"> <button name="rent" type="submit" class="logInButton p-2">Rent</button></td>
                            </tr>
                            DELIMITER;
                        $rows .= $row;
                    }

                    $table = <<<DELIMITER
                        {$heading}
                        {$rows}
                        </table>
                        DELIMITER;
                    echo $table;

                } else {
                    echo '<p>No available books found.</p>';
                }
                ?>
            </form>
        </div>
    </div>

</body>

</html>