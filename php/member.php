<?php
session_start();
require './functions.php';

// Username
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
}

rentalMember();

returningBooks();


// Get the available books
$availableBooks = bookView();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel='stylesheet' type='text/css' media='screen' href='../css/member.css'>
</head>

<body>

    <!-- LogOut Button -->
    <form method="POST">
        <button type="submit" name="logOutButton" class="tranBack"><img class="homeButton mx-3 mt-3"
                src="../img/home1.gif" alt="Back to Home Page" title="Back to Home Page"
                attribution="https://www.flaticon.com/free-animated-icons/home"></button>
    </form>

    <!-- Show Available books -->
    <div class="container d-flex justify-content-center align-items-center">
        <div class="mt-5 mb-5">

            <form method="POST" name="bookView" class="bookView p-5" action="./rental.php">
                <?php $book1 = <<<DELIMETER
                <h1> Good day $username! </h1>

                <button type="submit" name="viewOrder" class="orderButton mt-3 mx-5 p-3"> View Order</button>

                <button type="submit" name="returningOldBooks" class="orderButton mt-3 mx-5 p-3"> Return Books </button>

                <h2 class="my-5"> View our available book selection below. </h2>
                
                DELIMETER;
                echo $book1;

                // This will make sure that the username is known throughout the website
                userLogin();

                // If there is something in the book
                if (!empty($availableBooks)) {

                    // Table heading
                    $heading = <<<DELIMITER
                            <table>
                            <tr>
                                <th> Book Cover </th>
                                <th> Title </th>
                                <th> Description </th>
                                <th> Author </th>
                                <th> Genre </th>
                                <th> Return Date </th>
                                <th> Price </th>
                            </tr>
                        DELIMITER;
                    $rows = '';

                    // Make sure that all the books are appending to the list
                    foreach ($availableBooks as $book) {

                        // Storing the book_id and return_id to use it again
                        $bookId = $book['book_id'];
                        $returnDate = $book['return_date'];;

                        $row = <<<DELIMITER
                            <form method="POST" name="rentBookForm" class="bookView p-5" action="./rental.php">
                            <tr>
                                <td class="p-3"> <img src="../img/{$book['thumbnail']}" class="bookCover"> </td>
                                <td class="title p-3"> <p> {$book['title']} </p> </td>
                                <td class="description p-4"> <p> {$book['description']} </p> </td> 
                                <td class="p-3"> <p> {$book['author']} </p> </td>
                                <td class="p-3"> <p> {$book['genre']} </p> </td>
                                <td class="return p-4"> <p> {$book['return_date']} </p> </td>
                                <td class="return p-4"> <p> R {$book['price']} </p> </td>
                                <td class="p-3">
                                <input type="hidden" name="book_id" value="{$bookId}">
                                <input type="hidden" name="return_date" value="{$returnDate}">
                                <button name="rent" type="submit" class="logInButton p-2">Rent</button>
                                </td>
                            </tr>
                            </form>
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
                    // Filter out the unavailable books (they are rented out by other users)
                    echo '<p>No available books found. <br> All the books have been rented out! </p>';
                }
                ?>
            </form>
        </div>
    </div>

</body>

</html>