<?php
session_start();
require './functions.php';

// Get the book_id from the URL parameter
if (isset($_GET['book_id'])) {
    $bookId = $_GET['book_id'];
} else {
    echo 'Invalid book ID.';
    exit();
}

// Fetch the book details from the database
$mysqli = db_connect();
if (!$mysqli) {
    echo 'Database connection error.';
    exit();
}

$query = "SELECT * FROM books WHERE book_id = ?";
$stmt = mysqli_prepare($mysqli, $query);
mysqli_stmt_bind_param($stmt, "i", $bookId);

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$book = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
mysqli_close($mysqli);
?>

<!DOCTYPE html>
<html>

<head>
<meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Edit Book Page</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <link rel='stylesheet' type='text/css' media='screen' href='../css/librarian.css'>
</head>

<body>
    <h1>Edit Book Details</h1>

    <?php
    // Display the book details in a form
    if ($book) {

        

        echo '<form method="POST">';
        echo '<input type="hidden" name="book_id" value="' . $book['book_id'] . '">';
        echo 'Title: <input type="text" name="title" value="' . $book['title'] . '"><br>';
        echo 'Description: <input type="text" name="description" value="' . $book['description'] . '"><br>';
        echo 'Thumbnail: <input type="text" name="thumbnail" value="' . $book['thumbnail'] . '"><br>';
        echo 'Author: <input type="text" name="author" value="' . $book['author'] . '"><br>';
        echo 'Genre: <input type="text" name="genre" value="' . $book['genre'] . '"><br>';
        echo 'Return Date: <input type="date" name="return_date" value="' . $book['return_date'] . '"><br>';
        echo 'Price: <input type="text" name="price" value="' . $book['price'] . '"><br>';
        echo '<input type="submit" name="updateBook" value="Update">';
        
        echo '</form>';
        updateBook();
    } else {
        echo 'Book not found.';
    }
    ?>
</body>

</html>