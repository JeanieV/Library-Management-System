<?php

// Direct to SignUp Page
if (isset($_POST['existButton'])) {
    header("Location: ./signUp.php");
}

// Return to Home
if (isset($_POST['returnHome'])) {
    header("Location: ./index.php");
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
        // Get the user data from the POST request
        $username = $_POST['newUsername'];
        $fullName = $_POST['newFullName'];
        $address = $_POST['newAddress'];
        $password = $_POST['newPassword'];
        $email = $_POST['newEmail'];

        // Connect to the database
        $mysqli = db_connect();
        if (!$mysqli) {
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
            echo '<h2>Success: User created successfully! <br> Head back to Home Page for Login</h2>';
        } else {
            echo 'Error creating user: ' . mysqli_error($mysqli);
        }

        // Close the statement and the database connection
        mysqli_stmt_close($stmt);
        mysqli_close($mysqli);
    }
}

function userLogin()
{
    if (isset($_POST['logInButton'])) { // Check if the 'logInButton' is clicked

        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Connect to the database
            $mysqli = db_connect();
            if (!$mysqli) {
                return;
            }

            // Prepare the query to fetch user data by email
            $query = "SELECT * FROM users WHERE email = ?";
            $stmt = mysqli_prepare($mysqli, $query);

            // Bind the parameter to the statement
            mysqli_stmt_bind_param($stmt, "s", $email);

            // Execute the statement
            mysqli_stmt_execute($stmt);

            // Get the result
            $result = mysqli_stmt_get_result($stmt);

            // Check if a user with the provided email exists
            if ($row = mysqli_fetch_assoc($result)) {
                // Verify the password
                if (password_verify($password, $row['password'])) {
                    // Passwords match, login successful
                    // Redirect to the desired page
                    header("Location: ./member.php");
                    exit();
                } else {
                    // Passwords don't match, show error message
                    echo 'Invalid email or password';
                }
            } else {
                // User with the provided email does not exist, redirect to signUp.php
                header("Location: ./signUp.php");
                exit();
            }

            // Close the statement and the database connection
            mysqli_stmt_close($stmt);
            mysqli_close($mysqli);
        }
    }
}


?>