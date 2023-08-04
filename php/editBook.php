<?php
session_start();
require './functions.php';


// Public username
if (isset($_SESSION['fullname'])) {
    $fullname = $_SESSION['fullname'];
}

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
    <!-- Return Home Button -->
    <form method="POST">
        <button type="submit" name="returnBookChanges" class="tranBack"><img class="homeButton mx-3 mt-3"
                src="../img/home1.gif" alt="Back to Home Page" title="Back to Home Page"
                attribution="https://www.flaticon.com/free-animated-icons/home"></button>
    </form>

    <div class="container d-flex justify-content-center align-items-center">
        <div class="mt-5 mb-5 mx-5">
            <form method="POST" class="bookView p-5" action="updateBook.php">

            <?php
                // Check if the Add Book button is clicked
                if (isset($_POST['updateFinalBook'])) {
                    bookUpdate();
                }
                ?>

                <?php echo "<h1 class='mb-4'> $fullname, make a book update: </h1>;" ?>

                <div class="d-flex justify-content-center align-items-center my-4">
                    <table>
                        <!-- Title -->
                        <tr>
                            <td class="p-4"><label for="title" class="labelStyle"> Title: </label></td>
                            <td class="p-4"><input type="text" name="title" class="inputStyle"></td>
                        </tr>

                        <!-- Description-->
                        <tr>
                            <td class="p-4"><label for="description" class="labelStyle"> Description: </label>
                            </td>
                            <td class="p-4"><input type="text" name="description" class="inputStyle"></td>
                        </tr>

                        <!-- Thumbnail -->
                        <tr>
                            <td class="p-4"><label for="thumbnail" class="labelStyle"> Thumbnail (optional): </label>
                            </td>
                            <td class="p-4"><input type="text" name="thumbnail" class="inputStyle"></td>
                        </tr>

                        <!-- Author -->
                        <tr>
                            <td class="p-4"><label for="author" class="labelStyle"> Author: </label></td>
                            <td class="p-4"><input type="text" name="author" class="inputStyle"></td>
                        </tr>

                        <!-- Genre -->
                        <tr>
                            <td class="p-4"><label for="genre" class="labelStyle"> Genre: </label></td>
                            <td class="p-4"><input type="text" name="genre" class="inputStyle"></td>
                        </tr>

                        <!-- Return Date -->
                        <tr>
                            <td class="p-4"><label for="return_date" class="labelStyle"> Return Date: </label></td>
                            <td class="p-4"><input type="date" name="return_date" class="inputStyle"></td>
                        </tr>

                        <!-- Price -->

                        <tr>
                            <td class="p-4"><label for="price" class="labelStyle"> Price: </label></td>
                            <td class="p-4"><input type="text" name="price" class="inputStyle"></td>
                        </tr>
                    </table>
                </div>

                <button type="submit" name="updateFinalBook" class="logInButton p-3">Update Book</button>
            </form>
        </div>
    </div>
</body>

</html>