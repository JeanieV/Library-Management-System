<?php
session_start();

// Direct to SignUp Page
if (isset($_POST['existButton'])) {
    header("Location: ./signUp.php");
}

// LogOut
if (isset($_POST['logOutButton'])) {
    header("Location: ./index.php");
}

// Return to Book Page
if (isset($_POST['returnBooks'])) {
    header("Location: ./member.php");
}

// View Order
if (isset($_POST['viewOrder'])) {
    header("Location: ./rental.php");
}

// Clear rental
if (isset($_POST['clearRentalButton'])) {
    clearRow();
}

// Return Home
if (isset($_POST['returnHome'])) {
    header("Location: ./index.php");
}

// Thank you Page

if (isset($_POST['checkout'])) {
    header("Location: ./thankYou.php");
}

// Database connection function
function db_connect()
{
    $user = 'root';
    $password = 'root';
    $db = 'library_management_system';
    $host = 'localhost';
    $port = 3306;

    $mysqli = mysqli_init();
    $success = mysqli_real_connect(
        $mysqli,
        $host,
        $user,
        $password,
        $db,
        $port
    );

    if (!$success) {
        echo "Error connecting to the database: " . mysqli_connect_error();
        return null;
    }

    return $mysqli;
}


// Create a user that will show in the database
function creatingUser()
{
    if (isset($_POST['newUsername']) && isset($_POST['newFullName']) && isset($_POST['newAddress']) && isset($_POST['newPassword']) && isset($_POST['newEmail'])) {
        // Saving the username as a session variable
        $_SESSION['username'] = $_POST['newUsername'];
        $username = $_SESSION['username'];
        $fullName = $_POST['newFullName'];
        $address = $_POST['newAddress'];
        $password = $_POST['newPassword'];
        $email = $_POST['newEmail'];

        // Connect to the database
        $mysqli = db_connect();
        if (!$mysqli) {
            return;
        }

        // Check if the email already exists in the database
        $query = "SELECT * FROM member WHERE email = ?";
        $stmt = mysqli_prepare($mysqli, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            echo '<h2 class="p-3">Email already exists. Please use a different email address.</h2>';
            mysqli_stmt_close($stmt);
            mysqli_close($mysqli);
            return;
        }

        // Prepare the insert query with placeholders
        $query = "INSERT INTO member (`username`, `fullname`, `address`, `password`, `email`) VALUES (?, ?, ?, ?, ?)";

        // Prepare the statement
        $stmt = mysqli_prepare($mysqli, $query);

        // Bind the parameters to the statement
        mysqli_stmt_bind_param($stmt, "sssss", $username, $fullName, $address, $password, $email);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            echo '<h2 class="p-3">Success: User created successfully! <br> Head back to Home Page for Login</h2>';
        } else {
            echo 'Error creating user: ' . mysqli_error($mysqli);
        }

        // Close the statement and the database connection
        mysqli_stmt_close($stmt);
        mysqli_close($mysqli);
    }
}

// Directs the user to the member or sign-up page depending on whether they are on the database
function userLogin()
{
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Connect to the database
        $mysqli = db_connect();
        if (!$mysqli) {
            return;
        }

        // Check if the email and password match in the database
        $query = "SELECT * FROM member WHERE email = ? AND password = ?";
        $stmt = mysqli_prepare($mysqli, $query);
        mysqli_stmt_bind_param($stmt, "ss", $email, $password);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['username'] = $row['username'];
            mysqli_stmt_close($stmt);
            mysqli_close($mysqli);
            header("Location: ./member.php");
            exit();
        } else {
            // User does not exist or wrong password, redirect to signUp.php
            mysqli_stmt_close($stmt);
            mysqli_close($mysqli);
            header("Location: ./signUp.php");
            exit();
        }
    }
}

// This is the display for all the books on the database
function bookView()
{
    // Connect to the database
    $mysqli = db_connect();
    if (!$mysqli) {
        return;
    }

    // Calculate the return date (7 days from now)
    if (!isset($_SESSION['return'])) {
        $returnDate = date('Y-m-d', strtotime('+7 days'));
        $_SESSION['return'] = $returnDate;
    }

    // Select books from the database where availability is true and not rented out
    $query = "SELECT * FROM books WHERE availability = true AND book_id NOT IN (SELECT book_id FROM rental)";
    $stmt = mysqli_prepare($mysqli, $query);

    if (!$stmt) {
        // Error handling if the query preparation fails
        echo "Error in query: " . mysqli_error($mysqli);
        return null;
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $books = array();
    while ($row = mysqli_fetch_assoc($result)) {
        // Calculate the return date (7 days from now) for each book
        $returnDate = date('Y-m-d', strtotime('+7 days'));
        $row['return_date'] = $returnDate;
        $books[] = $row;
    }

    // Close the statement and the database connection
    mysqli_stmt_close($stmt);
    mysqli_close($mysqli);

    return $books;
}

// Function to add the data to the Rental table
function rentalMember()
{
    // Connect to the database
    $mysqli = db_connect();
    if (!$mysqli) {
        return;
    }

    if (isset($_POST['rent'])) {
        // Get the book ID and return date from the form submission
        $bookId = $_POST['book_id'];
        $returnDate = $_POST['return_date'];

        // Retrieve the member_id for the logged-in user based on the username
        $username = $_SESSION['username'];
        $query = "SELECT member_id FROM member WHERE username = ?";

        $stmt = mysqli_prepare($mysqli, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $memberId = $row['member_id'];

            // Check if the member has already reached the rental limit of 5 books
            $query = "SELECT COUNT(*) as rental_count FROM rental WHERE member_id = ?";
            $stmt = mysqli_prepare($mysqli, $query);
            mysqli_stmt_bind_param($stmt, "i", $memberId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            $rentalCount = $row['rental_count'];

            if ($rentalCount >= 5) {
                echo "<h3 class='my-5'>You have already reached the rental limit of 5 books.<br> Book has not been added!</h3>";
            } else {
                // Insert the rental information into the rentals table
                $query = "INSERT INTO rental (member_id, book_id, return_date) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($mysqli, $query);

                if (!$stmt) {
                    // Error handling if the query preparation fails
                    echo "Error in query: " . mysqli_error($mysqli);
                    return;
                }

                mysqli_stmt_bind_param($stmt, "iis", $memberId, $bookId, $returnDate);
                mysqli_stmt_execute($stmt);

                // Close the statement
                mysqli_stmt_close($stmt);
            }
        } else {
            echo "Member ID not found for username: $username";
        }

        // Close the database connection
        mysqli_close($mysqli);
    }
}

// Function to display the current rental
function rentalDisplay()
{
    // Connect to the database
    $mysqli = db_connect();
    if (!$mysqli) {
        return;
    }

    // Retrieve the member_id for the logged-in user based on the username
    $username = $_SESSION['username'];
    $query = "SELECT member_id FROM member WHERE username = ?";
    $stmt = mysqli_prepare($mysqli, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $memberId = $row['member_id'];

        // Retrieve the book_id(s) and rental_id(s) from the rental table for the specific member
        $query = "SELECT rental_id, book_id, return_date FROM rental WHERE member_id = ?";

        $stmt2 = mysqli_prepare($mysqli, $query);
        mysqli_stmt_bind_param($stmt2, "i", $memberId);
        mysqli_stmt_execute($stmt2);
        $result2 = mysqli_stmt_get_result($stmt2);

        if (mysqli_num_rows($result2) > 0) {
            // Initialize total price variable
            $totalPrice = 0;

            // Initialize rows variable to store the HTML of all rented books
            $rows = '';

            // Loop through the result set and fetch each book's details from the books table
            while ($row2 = mysqli_fetch_assoc($result2)) {
                $bookId = $row2['book_id'];
                $rentalId = $row2['rental_id'];
                $returnDate = $row2['return_date'];

                // Use the retrieved book_id to fetch the corresponding title and thumbnail from the books table
                $query = "SELECT title, thumbnail, return_date, price FROM books WHERE book_id = ?";
                $stmt = mysqli_prepare($mysqli, $query);
                mysqli_stmt_bind_param($stmt, "i", $bookId);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $price = $row['price'];

                    // Display the rented books
                    $heading = <<<DELIMITER
                    <div class="d-flex justify-content-center align-items-center">
                    <table>
                        <tr>
                            <th> Book Cover </th>
                            <th> Title </th>
                            <th> Return Date </th>
                            <th> Price </th>
                        </tr>
                    DELIMITER;

                    // Concatenate the HTML of each book to $rows
                    $rowHTML = <<<DELIMETER
                        <tr>
                        <div class="d-flex justify-content-center align-items-center">
                        <form method="POST" action="./member.php">
                            <td class="p-5"><img src="../img/{$row['thumbnail']}" alt="Book Thumbnail" class="bookCover"></td>
                            <td class="title p-4"><p> {$row['title']} </p></td>
                            <td class="p-5"><p> {$returnDate} </p></td>
                            <td class="p-5"><p> R {$row['price']} </p></td>
                            <input type="hidden" name="rental_id" value="$rentalId">
                            <td class="p-5"><button type="submit" name="clearRentalButton" class="tranBack"><img class="homeButton mx-3 mt-3"
                                src="../img/bin.gif" alt="Delete Order" title="Delete Order"
                                attribution="https://www.flaticon.com/free-animated-icons/document"></button></td>
                        </form>
                        </div>
                        </tr>
                    DELIMETER;
                    $rows .= $rowHTML;

                    // Update the total price
                    $totalPrice += $price;
                } else {
                    echo "Book not found in the books table.";
                }
            }

            // Store the total price as a session variable after the while loop has finished
            $_SESSION['totalPrice'] = $totalPrice;

            $table = <<<DELIMETER
                {$heading}
                {$rows}
                </table>
                </div>
            DELIMETER;
            echo $table;

            $finalOutput = <<< DELIMETER
            <div class="d-flex justify-content-center align-items-center">
                <h2 class='my-5'>Total Price for all rented books: R $totalPrice </h2>
            </div>
            DELIMETER;
            echo $finalOutput;
        } else {
            echo "<p>Order is currently empty!</p>";
        }
    }

    // Close the statement and the database connection
    mysqli_stmt_close($stmt);
    mysqli_close($mysqli);
}


// Function with a button to clear the book selected for rent
function clearRow()
{

    if (isset($_POST['rental_id'])) {
        $rentalId = $_POST['rental_id'];

        $mysqli = db_connect();
        if (!$mysqli) {
            return;
        }

        // Prepare the SQL query to delete the specific record from the rental table
        $query = "DELETE FROM rental WHERE rental_id = ?";

        // Prepare the statement
        $stmt = mysqli_prepare($mysqli, $query);
        mysqli_stmt_bind_param($stmt, "i", $rentalId);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            header("Location: ./rental.php");
            exit();
        }

        // Close the statement and the database connection
        mysqli_stmt_close($stmt);
        mysqli_close($mysqli);
    }
}


?>