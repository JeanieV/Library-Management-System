<?php
session_start();
require './functions.php';

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>New Librarian Page</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <link rel='stylesheet' type='text/css' media='screen' href='../css/signUp.css'>
</head>

<body>

    <!-- Return Home Button -->
    <form method="POST">
        <button type="submit" name="returnLibrary" class="tranBack"><img class="homeButton mx-3 mt-3"
                src="../img/home1.gif" alt="Back to Home Page" title="Back to Home Page"
                attribution="https://www.flaticon.com/free-animated-icons/home"></button>
    </form>



    <!-- Register New User -->
    <div class="container d-flex justify-content-center align-items-center">
        <div class="mt-5 mb-5 mx-5">

            <form method="POST" name="signUpForm" class="signUpForm p-5">
                <?php
                // Check if the Sign Up button is clicked
                if (isset($_POST['addNewEmployee'])) {
                    addNewEmployee();
                }
                ?>
                <h1> Register a new Librarian: </h1>

                <div class="d-flex justify-content-center align-items-center my-4">
                    <table>
                        <!-- Fullname -->
                        <tr>
                            <td class="p-4"><label for="fullname" class="labelStyle"> Full Name: </label></td>
                            <td class="p-4"><input type="text" name="fullname" class="inputStyle" required></td>
                        </tr>

                        <!-- Employee Number -->
                        <tr>
                            <td class="p-4"><label for="employee_number" class="labelStyle"> Employee Number: </label>
                            </td>
                            <td class="p-4"><input type="text" name="employee_number" class="inputStyle" required></td>
                        </tr>

                        <!-- Role -->
                        <tr>
                            <td class="p-4"><label for="role" class="labelStyle"> Role: </label></td>
                            <td class="p-4"><input type="text" name="role" class="inputStyle" required></td>
                        </tr>
                    </table>
                </div>

                <!-- Log In Button -->
                <div class="container d-flex justify-content-center align-items-center">
                    <div class="mx-5 mt-3 mb-5">
                        <button name="addNewEmployee" type="submit" class="logInButton p-2">Sign Up</button>
                    </div>
                </div>
                <?php
                // Display error message if set
                if (isset($_SESSION['addEmployee'])) {
                    echo '<div class="container d-flex justify-content-center align-items-center">';
                    echo '<p class="text-danger">' . $_SESSION['addEmployee'] . '</p>';
                    echo '</div>';
                    unset($_SESSION['addEmployee']);
                }
                ?>
            </form>
        </div>
    </div>
</body>

</html>