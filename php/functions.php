<?php
session_start();
require './class.php';

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

// Return the book
if (isset($_POST['returnRentedBook'])) {

}

// Return Old books
if (isset($_POST['returningOldBooks'])) {
    header("Location: ./returnBooks.php");
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

        // Prepare the statement to bind the parameters (email)
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        // If there is information in the table that matches the input value
        if (mysqli_stmt_num_rows($stmt) > 0) {
            echo '<h2 class="p-3">Email already exists. Please use a different email address.</h2>';
            mysqli_stmt_close($stmt);
            mysqli_close($mysqli);
            return;
        }

        // SQL Statement
        $query = "INSERT INTO member (`username`, `fullname`, `address`, `password`, `email`) VALUES (?, ?, ?, ?, ?)";

        // Prepare the statement
        $stmt = mysqli_prepare($mysqli, $query);

        // Bind the parameters to the statement (username, fullname, address, password and email)
        mysqli_stmt_bind_param($stmt, "sssss", $username, $fullName, $address, $password, $email);

        // If the user has successfully been added to the database
        if (mysqli_stmt_execute($stmt)) {
            echo '<h2 class="p-3">Success: User created successfully! <br> Head back to Home Page for Login</h2>';
            exit();
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

        // Prepare the statement to bind the parameters (email and password)
        $stmt = mysqli_prepare($mysqli, $query);
        mysqli_stmt_bind_param($stmt, "ss", $email, $password);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // If there is information in the table, find the username that match
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
        $books[] = $row;
    }

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

    // If the user clicks on the "rent" button
    if (isset($_POST['rent'])) {

        // Get the book ID and return date from the form submission
        $bookId = $_POST['book_id'];

        // Find the member_id that matches the username
        $username = $_SESSION['username'];
        $query = "SELECT member_id FROM member WHERE username = ?";

        // Bind the parameters (username) to the statement
        $stmt = mysqli_prepare($mysqli, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);

        // Creating a new parameter
        $result = mysqli_stmt_get_result($stmt);

        // If there is information in the rental table
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

            // Storing the rental_count as a variable to use further
            $rentalCount = $row['rental_count'];

            // If the user reached the limit of 5 books
            if ($rentalCount >= 5) {
                echo "<h3 class='my-5'>You have already reached the rental limit of 5 books.<br> Book has not been added!</h3>";
            } else {
                // Insert the rental information into the rentals table
                $query = "INSERT INTO rental (member_id, book_id, return_date) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 WEEK))";
                $stmt = mysqli_prepare($mysqli, $query);

                if (!$stmt) {
                    // Error handling if the query preparation fails
                    echo "Error in query: " . mysqli_error($mysqli);
                    return;
                }

                // Bind the parameters (member_id and book_id)
                mysqli_stmt_bind_param($stmt, "ii", $memberId, $bookId);
                mysqli_stmt_execute($stmt);

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

    // Find the username that matches the member_id
    $username = $_SESSION['username'];
    $query = "SELECT member_id FROM member WHERE username = ?";
    $stmt = mysqli_prepare($mysqli, $query);

    // Bind the parameter (username)
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // If there is information in the rental table
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $memberId = $row['member_id'];

        // Find the id's for the specific user
        $query = "SELECT rental_id, book_id, return_date FROM rental WHERE member_id = ?";

        $stmt2 = mysqli_prepare($mysqli, $query);

        // Bind the member_id to the statement
        mysqli_stmt_bind_param($stmt2, "i", $memberId);
        mysqli_stmt_execute($stmt2);
        $result2 = mysqli_stmt_get_result($stmt2);

        // If there is information in the books table
        if (mysqli_num_rows($result2) > 0) {

            // Set the price at a starting amount of 0
            $totalPrice = 0;

            $rows = '';

            // Get the information from the books table
            while ($row2 = mysqli_fetch_assoc($result2)) {
                $bookId = $row2['book_id'];
                $rentalId = $row2['rental_id'];

                // Get the necessary information from the books table
                $query = "SELECT title, thumbnail, return_date, price FROM books WHERE book_id = ?";

                $stmt = mysqli_prepare($mysqli, $query);

                // Bind the book_id parameter
                mysqli_stmt_bind_param($stmt, "i", $bookId);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                // If the table has information in it
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);

                    // Storing the price as a variable to use again
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
                            <td class="p-5"><p> {$row['return_date']} </p></td>
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

            $finalOutput = <<<DELIMETER
            <div class="d-flex justify-content-center align-items-center">
                <h2 class='my-5'>Total Price for all rented books: R $totalPrice </h2>
            </div>
            DELIMETER;
            echo $finalOutput;
        } else {
            echo "<p>Order is currently empty!</p>";
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($mysqli);
}


// Function with a button to clear the book selected for rent
function clearRow()
{

    // If there is a rental_id present
    if (isset($_POST['rental_id'])) {
        $rentalId = $_POST['rental_id'];

        // Connect to the database
        $mysqli = db_connect();
        if (!$mysqli) {
            return;
        }

        // SQL Statement to delete the entire row from the table
        $query = "DELETE FROM rental WHERE rental_id = ?";

        $stmt = mysqli_prepare($mysqli, $query);
        mysqli_stmt_bind_param($stmt, "i", $rentalId);

        // Where should the user go when a row has been deleted?
        if (mysqli_stmt_execute($stmt)) {
            header("Location: ./rental.php");
            exit();
        }

        mysqli_stmt_close($stmt);
        mysqli_close($mysqli);
    }
}


// Librarian Section

// Directs the user to the member or sign-up page depending on whether they are on the database
function employeeLogin()
{
    if (isset($_POST['employee_number'])) {
        $employee_number = $_POST['employee_number'];

        // Connect to the database
        $mysqli = db_connect();
        if (!$mysqli) {
            return;
        }

        // Check if the email and password match in the database
        $query = "SELECT * FROM librarian WHERE employee_number = ?";

        // Prepare the statement to bind the parameters (employee_number)
        $stmt = mysqli_prepare($mysqli, $query);
        mysqli_stmt_bind_param($stmt, "s", $employee_number);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // If there is information in the table, find the employee_number that match
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['fullname'] = $row['fullname'];

            mysqli_stmt_close($stmt);
            mysqli_close($mysqli);
            header("Location: ./library.php");
            exit();
        } else {
            // User does not exist or wrong employee_number, redirect to index.php
            $_SESSION['loginError'] = "Employee does not exist! <br> Kindly enter correct employee_number";

            mysqli_stmt_close($stmt);
            mysqli_close($mysqli);
            header("Location: ./index.php");
            exit();
        }
    }
}

// Direct to SignUp Page for Employee
if (isset($_POST['newEmployee'])) {
    header("Location: ./newLibrarian.php");
}

// Direct to Library Page
if (isset($_POST['returnLibrary'])) {
    header("Location: ./library.php");
}

// Direct to Book changes page
if (isset($_POST['bookChangesButton'])) {
    header("Location: ./bookChanges.php");
}

// Direct to Add Book Page
if (isset($_POST['addBookNew'])) {
    header("Location: ./addBook.php");
}

// Return to Book Changes Page
if (isset($_POST['returnBookChanges'])) {
    header("Location: ./bookChanges.php");
}

// Update Books Page
if (isset($_POST['updateBook'])) {
    header("Location: ./updateBook.php");
}



// Function to add a new Employee to the website
function addNewEmployee()
{
    // If the sign-up button is clicked
    if (isset($_POST['addNewEmployee'])) {

        // Store the input fields as variables
        $employee_number = $_POST['employee_number'];
        $fullname = $_POST['fullname'];
        $role = $_POST['role'];

        // Connect to the database
        $mysqli = db_connect();
        if (!$mysqli) {
            return;
        }

        // Create a new instance of the Librarian class
        $librarian = new Librarian($mysqli);

        // Call the method from the Librarian class
        $result = $librarian->addLibrarian($employee_number, $fullname, $role);

        // If the new employee has been added to the librarian table
        if ($result) {
            echo '<h2 class="p-3">Success: User created successfully! <br> Head back to Library Page </h2>';

            mysqli_close($mysqli);
            exit();
        } else {
            // Failed to add librarian
            $_SESSION['addEmployee'] = "<p> New Employee has not been added </p>";

            header("Location: ./index.php");
            mysqli_close($mysqli);
            exit();
        }
    }
}


// Add new Book to the system
function addBook()
{
    // If the addNewBook button is clicked
    if (isset($_POST['addnewBook'])) {

        // Store the input fields as variables
        $title = $_POST['title'];
        $description = $_POST['description'];
        $thumbnail = $_POST['thumbnail'];
        $author = $_POST['author'];
        $genre = $_POST['genre'];
        $return_date = $_POST['return_date'];
        $availability = 1;
        $price = $_POST['price'];

        // Connect to the database
        $mysqli = db_connect();
        if (!$mysqli) {
            return;
        }

        // Create a new instance of the Librarian class
        $librarian = new Librarian($mysqli);

        // Call the method from the Librarian class
        $result = $librarian->addBook($title, $description, $thumbnail, $author, $genre, $return_date, $availability, $price);

        // If the new employee has been added to the librarian table
        if ($result) {
            echo '<h2 class="p-3">Success: Book added successfully! <br> Head back to Library Page </h2>';
            mysqli_close($mysqli);
            exit();
        } else {
            // Failed to add librarian
            header("Location: ./index.php");

            mysqli_close($mysqli);
            exit();
        }
    }
}

// View all books on the system to edit
function viewAllBooks()
{

    // Connect to the database
    $mysqli = db_connect();
    if (!$mysqli) {
        return;
    }

    $query = "SELECT * FROM books";
    $result = mysqli_query($mysqli, $query);

    if (mysqli_num_rows($result) > 0) {
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

        // While there is something in the table
        while ($row = mysqli_fetch_assoc($result)) {
            $bookId = $row['book_id'];

            $rowHTML = <<<DELIMITER
                            <tr>
                            
                                <td class="p-3"> <img src="../img/{$row['thumbnail']}" class="bookCover"> </td>
                                <td class="title p-3"> <p> {$row['title']} </p> </td>
                                <td class="description p-4"> <p> {$row['description']} </p> </td> 
                                <td class="p-3"> <p> {$row['author']} </p> </td>
                                <td class="p-3"> <p> {$row['genre']} </p> </td>
                                <td class="return p-4"> <p> {$row['return_date']} </p> </td>
                                <td class="return p-4"> <p> R {$row['price']} </p> </td>
                                <td class="p-4">
                                    <input type="hidden" name="book_id" value="$bookId">
                                    <button type="submit" name="editBooks" class="logInButton p-3"> Edit </button>
                                </td> 
                            </tr>
                            DELIMITER;
            $rows .= $rowHTML;
        }

        $table = <<<DELIMITER
                        {$heading}
                        {$rows}
                        </table>
                        DELIMITER;
        echo $table;

    } else {
        echo 'No books found.';
    }

    mysqli_free_result($result);
    mysqli_close($mysqli);
}


function editBookFinal(){

     // If there is a rental_id present
     if (isset($_POST['book_id'])) {
        
        header("Location: ./editBook.php");
     }
}


// Function to update books
function bookUpdate()
{
   
    if(isset($_POST['book_id'])){
        // Connect to the database
        $mysqli = db_connect();
        if (!$mysqli) {
            return;
        }

        // Store the input fields as variables
        $bookId = $_POST['book_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $thumbnail = $_POST['thumbnail'];
        $author = $_POST['author'];
        $genre = $_POST['genre'];
        $return_date = $_POST['return_date'];
        $availability = 1;
        $price = $_POST['price'];

        // Create a new instance of the Librarian class
        $librarian = new Librarian($mysqli);

        // Call the method from the Librarian class
        $result = $librarian->updateBook($bookId, $title, $description, $thumbnail, $author, $genre, $return_date, $availability, $price);

        // If the book has been updated
        if ($result) {
            echo "Successful!";
        }
        else {
            echo "could not update";
        }
    }
}





function returnBooks()
{
    // Connect to the database
    $mysqli = db_connect();
    if (!$mysqli) {
        return;
    }

    // Find the username that matches the member_id
    $username = $_SESSION['username'];
    $query = "SELECT member_id FROM member WHERE username = ?";
    $stmt = mysqli_prepare($mysqli, $query);

    // Bind the parameter (username)
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // If there is information in the rental table
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $memberId = $row['member_id'];

        // Find the id's for the specific user
        $query = "SELECT rental_id, book_id, return_date FROM rental WHERE member_id = ?";

        $stmt2 = mysqli_prepare($mysqli, $query);

        // Bind the member_id to the statement
        mysqli_stmt_bind_param($stmt2, "i", $memberId);
        mysqli_stmt_execute($stmt2);
        $result2 = mysqli_stmt_get_result($stmt2);

        // If there is information in the books table
        if (mysqli_num_rows($result2) > 0) {

            $rows = '';

            // Get the information from the books table
            while ($row2 = mysqli_fetch_assoc($result2)) {
                $bookId = $row2['book_id'];
                $rentalId = $row2['rental_id'];

                // Get the necessary information from the books table
                $query = "SELECT title, thumbnail, return_date, price FROM books WHERE book_id = ?";

                $stmt = mysqli_prepare($mysqli, $query);

                // Bind the book_id parameter
                mysqli_stmt_bind_param($stmt, "i", $bookId);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                // If the table has information in it
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);

                    // Display the rented books
                    $heading = <<<DELIMITER
                    <div class="d-flex justify-content-center align-items-center">
                    <table>
                        <tr>
                            <th> Book Cover </th>
                            <th> Title </th>
                            <th> Return Date </th>
                        </tr>
                    DELIMITER;

                    // Concatenate the HTML of each book to $rows
                    $rowHTML = <<<DELIMETER
                        <tr>
                        <div class="d-flex justify-content-center align-items-center">
                        <form method="POST" action="./member.php">
                            <td class="p-5"><img src="../img/{$row['thumbnail']}" alt="Book Thumbnail" class="bookCover"></td>
                            <td class="title p-4"><p> {$row['title']} </p></td>
                            <td class="p-5"><p> {$row['return_date']} </p></td>
                            <input type="hidden" name="rental_id" value="$rentalId">
                            <td class="p-5"><button type="submit" name="returnRentedBook" class="logInButton p-3"> Return </button></td> 
                        </form>
                        </div>
                        </tr>
                    DELIMETER;
                    $rows .= $rowHTML;

                } else {
                    echo "Book not found in the books table.";
                }
            }

            $table = <<<DELIMETER
                {$heading}
                {$rows}
                </table>
                </div>
            DELIMETER;
            echo $table;

        } else {
            echo "<p>You have no books to return!</p>";
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($mysqli);
}


function bookReturn($rentalId)
{
    // Connect to the database
    $mysqli = db_connect();
    if (!$mysqli) {
        return false;
    }

    // Get the book_id associated with the rental_id
    $query = "SELECT book_id, return_date FROM rental WHERE rental_id = ?";
    $stmt = mysqli_prepare($mysqli, $query);
    mysqli_stmt_bind_param($stmt, "i", $rentalId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result || mysqli_num_rows($result) === 0) {
        mysqli_stmt_close($stmt);
        mysqli_close($mysqli);
        return false;
    }

    $row = mysqli_fetch_assoc($result);
    $bookId = $row['book_id'];
    $returnDate = $row['return_date'];
    mysqli_stmt_close($stmt);

    // Remove the book entry from the rental table
    $query = "DELETE FROM rental WHERE rental_id = ?";
    $stmt = mysqli_prepare($mysqli, $query);
    mysqli_stmt_bind_param($stmt, "i", $rentalId);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if (!$result) {
        mysqli_close($mysqli);
        return false;
    }

    $timestamp = strtotime($returnDate);

    // Add one week to the return date
    $newTimestamp = $timestamp + (7 * 24 * 60 * 60);

    // Convert the new timestamp back to the desired date string format
    $newReturnDate = date("Y-m-d", $newTimestamp);

    // Update the return_date in the books table
    $query = "UPDATE books SET return_date = ? WHERE book_id = ?";
    $stmt = mysqli_prepare($mysqli, $query);
    mysqli_stmt_bind_param($stmt, "si", $newReturnDate, $bookId);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    mysqli_close($mysqli);

    return $result;
}


// View Books Page
if (isset($_POST['readBook'])) {
    header("Location: ./viewBooks.php");
}


// Function that will be used in the View All Books section
function readAllBooks()
{

    // Connect to the database
    $mysqli = db_connect();
    if (!$mysqli) {
        return;
    }

    $query = "SELECT * FROM books";
    $result = mysqli_query($mysqli, $query);

    if (mysqli_num_rows($result) > 0) {
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

        while ($row = mysqli_fetch_assoc($result)) {

            $rowHTML = <<<DELIMITER
                            <tr>
                            
                                <td class="p-3"> <img src="../img/{$row['thumbnail']}" class="bookCover"> </td>
                                <td class="title p-3"> <p> {$row['title']} </p> </td>
                                <td class="description p-4"> <p> {$row['description']} </p> </td> 
                                <td class="p-3"> <p> {$row['author']} </p> </td>
                                <td class="p-3"> <p> {$row['genre']} </p> </td>
                                <td class="return p-4"> <p> {$row['return_date']} </p> </td>
                                <td class="return p-4"> <p> R {$row['price']} </p> </td>
                            </tr>
                            DELIMITER;
            $rows .= $rowHTML;
        }

        $table = <<<DELIMITER
                        {$heading}
                        {$rows}
                        </table>
                        DELIMITER;
        echo $table;

    } else {
        echo 'No books found.';
    }

    mysqli_free_result($result);
    mysqli_close($mysqli);
}

function returningBooks()
{

    // Check if the returnRentedBook button is clicked
    if (isset($_POST['returnRentedBook'])) {
        $rentalId = $_POST['rental_id'];
        $result = bookReturn($rentalId);

        if ($result) {
            echo 'Successfully returned the book';
        }
    }
}

if (isset($_POST['deleteBook'])) {
    header("Location: ./deleteBooks.php");
}


function viewBooksToDelete()
{

    // Connect to the database
    $mysqli = db_connect();
    if (!$mysqli) {
        return;
    }

    $query = "SELECT * FROM books";
    $result = mysqli_query($mysqli, $query);

    if (mysqli_num_rows($result) > 0) {

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

        while ($row = mysqli_fetch_assoc($result)) {

            $bookId = $row['book_id'];

            $rowHTML = <<<DELIMITER
                            <tr>
                            
                                <td class="p-3"> <img src="../img/{$row['thumbnail']}" class="bookCover"> </td>
                                <td class="title p-3"> <p> {$row['title']} </p> </td>
                                <td class="description p-4"> <p> {$row['description']} </p> </td> 
                                <td class="p-3"> <p> {$row['author']} </p> </td>
                                <td class="p-3"> <p> {$row['genre']} </p> </td>
                                <td class="return p-4"> <p> {$row['return_date']} </p> </td>
                                <td class="return p-4"> <p> R {$row['price']} </p> </td>
                                <td class="p-4">
                                <input type="hidden" name="book_id" value="$bookId">
                                <button type="submit" name="deleteBookFinalButton" class="tranBack"><img class="homeButton"
                                    src="../img/bin.gif" alt="Delete Order" title="Delete Order"
                                    attribution="https://www.flaticon.com/free-animated-icons/document"></button>
                                </td> 
                            </tr>
                            DELIMITER;
            $rows .= $rowHTML;
        }

        $table = <<<DELIMITER
                        {$heading}
                        {$rows}
                        </table>
                        DELIMITER;
        echo $table;

    } else {
        echo 'No books found.';
    }

    mysqli_free_result($result);
    mysqli_close($mysqli);
}

function deleteFinalBooks()
{

    // If the deleteBookFinalButton is clicked
    if (isset($_POST['deleteBookFinalButton'])) {

        // If there is a rental_id present
        if (isset($_POST['book_id'])) {
            $bookId = $_POST['book_id'];

            // Connect to the database
            $mysqli = db_connect();
            if (!$mysqli) {
                return;
            }

            // Create a new instance of the Librarian class
            $librarian = new Librarian($mysqli);

            // Call the method from the Librarian class
            $result = $librarian->deleteBook($bookId);

            // If the new employee has been added to the librarian table
            if ($result) {
                echo '<h2 class="p-3">Success: Book Deleted successfully! <br> Head back to Library Page </h2>';

                mysqli_close($mysqli);
                exit();
            } else {
                // Failed to add librarian
                $_SESSION['notDeleted'] = "<p> Book has not been deleted </p>";

                header("Location: ./index.php");
                mysqli_close($mysqli);
                exit();
            }
        }
    }
}

if (isset($_POST['viewRegisteredMembers'])) {
    header("Location: ./registeredMembers.php");
}

function registeredMembersFinal()
{
    // Connect to the database
    $mysqli = db_connect();
    if (!$mysqli) {
        return;
    }

    $query = "SELECT * FROM member";
    $result = mysqli_query($mysqli, $query);

    if (mysqli_num_rows($result) > 0) {

        $heading = <<<DELIMITER
                            <table>
                            <tr>
                                <th> Username </th>
                                <th> Fullname </th>
                                <th> Address </th>
                                <th> Password </th>
                                <th> Email </th>
                            </tr>
                        DELIMITER;
        $rows = '';

        while ($row = mysqli_fetch_assoc($result)) {
            $memberId = $row['member_id'];

            $rowHTML = <<<DELIMITER
                            <tr>
                                <td class="username p-4"> <p> {$row['username']} </td>
                                <td class="username p-4"> <p> {$row['fullname']} </td>
                                <td class="username p-4"> <p> {$row['address']} </td>
                                <td class="username p-4"> <p> {$row['password']} </td>
                                <td class="username p-4"> <p> {$row['email']} </td>
                                <td class="username p-4">
                                <form method="POST" action="./viewRentedBooks.php">
                                    <input type="hidden" name="member_id" value="$memberId">
                                    <button type="submit" name="viewRentedMemberBooks" class="logInButton p-2"> View Rentals </button>
                                </form>
                                </td> 
                            </tr>
                            DELIMITER;
            $rows .= $rowHTML;
        }

        $table = <<<DELIMITER
                        {$heading}
                        {$rows}
                        </table>
                        DELIMITER;
        echo $table;

    } else {
        echo 'No members found.';
    }

    mysqli_free_result($result);
    mysqli_close($mysqli);
}

// Return back to the page where the librarian can view the members registered
if (isset($_POST['returnMemberViewPage'])) {
    header("Location: ./registeredMembers.php");
}

// Go to the rented page if the user clicks on viewRentedMemberBooks
if (isset($_POST['viewRentedMemberBooks'])) {
    if (isset($_POST['member_id'])) {
        $_SESSION['selected_member_id'] = $_POST['member_id'];
    }
    header("Location: ./viewRentedBooks.php");
}

function viewRentedBooks($memberId)
{
    // Connect to the database
    $mysqli = db_connect();
    if (!$mysqli) {
        return;
    }

    // Use the SQL JOIN to fetch rental information along with associated member and book details
    $query = "SELECT r.*, m.fullname, b.title, b.thumbnail, b.price
              FROM rental r
              INNER JOIN member m ON r.member_id = m.member_id
              INNER JOIN books b ON r.book_id = b.book_id
              WHERE r.member_id = ?";
    $stmt = mysqli_prepare($mysqli, $query);
    mysqli_stmt_bind_param($stmt, "i", $memberId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $heading = <<<DELIMITER
            <table>
            <tr>
                <th> Book Cover </th>
                <th> Title </th>
                <th> Member Name </th>
                <th> Return Date </th>
                <th> Price </th>
                <th> Status </th>
            </tr>
        DELIMITER;
        $rows = '';

        while ($row = mysqli_fetch_assoc($result)) {

            // Check if the return date has passed for each rental
            $status = ($row['return_date'] < date("Y-m-d")) ? "Returned" : "Outstanding";

            $rowHTML = <<<DELIMITER
                <tr>
                    <td class="username p-4"><img src="../img/{$row['thumbnail']}" alt="Book Thumbnail" class="bookCover"></td>
                    <td class="username p-4"> <p> {$row['title']} </p></td>
                    <td class="username p-4"> <p> {$row['fullname']} </p></td>
                    <td class="username p-4"> <p> {$row['return_date']} </p></td>
                    <td class="username p-4"> <p> R {$row['price']} </p></td>
                    <td class="username p-4"> <p> {$status} </p></td>
                </tr>
            DELIMITER;
            $rows .= $rowHTML;
        }

        $table = <<<DELIMITER
            {$heading}
            {$rows}
            </table>
        DELIMITER;
        echo $table;
    } else {
        echo 'No rentals found.';
    }

    mysqli_free_result($result);
    mysqli_close($mysqli);
}

if (isset($_POST['suspendGo'])) {
    header("Location: ./suspend.php");
}


function suspendAccount()
{
    // Connect to the database
    $mysqli = db_connect();
    if (!$mysqli) {
        return;
    }

    // Check if the form is submitted (suspendButton is clicked)
    if (isset($_POST['suspendButton']) && isset($_POST['rental_id'])) {
        $rentalId = $_POST['rental_id'];

        // Call the function to suspend the rental
        suspendRental($mysqli, $rentalId);
    }

    // Use the SQL JOIN to fetch rental information along with associated member and book details
    $query = "SELECT r.*, m.fullname, b.title, b.thumbnail, b.price
              FROM rental r
              INNER JOIN member m ON r.member_id = m.member_id
              INNER JOIN books b ON r.book_id = b.book_id";
    $result = mysqli_query($mysqli, $query);

    if (mysqli_num_rows($result) > 0) {
        $heading = <<<DELIMITER
            <table>
            <tr>
                <th> Book Cover </th>
                <th> Title </th>
                <th> Member Name </th>
                <th> Return Date </th>
                <th> Price </th>
                <th> Status </th>
                <th> Action </th>
            </tr>
        DELIMITER;
        $rows = '';

        while ($row = mysqli_fetch_assoc($result)) {
            // Check if the return date has passed for each rental
            $status = ($row['return_date'] < date("Y-m-d")) ? "Returned" : "Outstanding";

            $rowHTML = <<<DELIMITER
                <tr>
                    <td class="username p-4"><img src="../img/{$row['thumbnail']}" alt="Book Thumbnail" class="bookCover"></td>
                    <td class="username p-4"> <p> {$row['title']} </p></td>
                    <td class="username p-4"> <p> {$row['fullname']} </p></td>
                    <td class="username p-4"> <p> {$row['return_date']} </p></td>
                    <td class="username p-4"> <p> R {$row['price']} </p></td>
                    <td class="username p-4"> <p> {$status} </p></td>
                    <td class="username p-4"> 
                        <form action="" method="post">
                            <input type="hidden" name="rental_id" value="{$row['rental_id']}">
                            <button type="submit" name="suspendButton" class="logInButton p-2">Suspend Account</button>
                        </form>
                    </td>
                </tr>
            DELIMITER;
            $rows .= $rowHTML;
        }

        $table = <<<DELIMITER
            {$heading}
            {$rows}
            </table>
        DELIMITER;
        echo $table;
    } else {
        echo 'No rentals found.';
    }

    mysqli_free_result($result);
    mysqli_close($mysqli);
}

function suspendRental($mysqli, $rentalId)
{
    // Use the rental_id to remove the rental record from the rental table
    $query = "DELETE FROM rental WHERE rental_id = ?";
    $stmt = mysqli_prepare($mysqli, $query);
    mysqli_stmt_bind_param($stmt, "i", $rentalId);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if($result){
        echo '<h3 class="mb-5">You suspended the member! <br> Their Rental has been removed </h3>';
    }
}



?>