<?php
session_start();
require './functions.php';

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
    <link rel='stylesheet' type='text/css' media='screen' href='../css/home.css'>
</head>

<body>

    <div class="d-flex justify-content-center align-items-center my-4">
        <h1 class="p-4"> Welcome to Library Books! </h2>
    </div>

    <!-- Login Form -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <div class="mt-5 mb-5 mx-5 loginForm p-5">

                    <form method="POST" action="index.php">

                        <h2> Member Login:</h2>

                        <div class="d-flex justify-content-center align-items-center my-4">
                            <table>
                                <!-- Email -->
                                <tr>
                                    <td class="p-4"><label for="email" class="labelStyle"> Email: </label></td>
                                    <td class="p-4"><input type="email" name="email" class="inputStyle" required></td>
                                </tr>

                                <!-- Password -->
                                <tr>
                                    <td class="p-4"><label for="password" class="labelStyle"> Password: </label></td>
                                    <td class="p-4"><input type="password" name="password" class="inputStyle" required>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!-- Log In Button -->
                        <div class="container d-flex justify-content-center align-items-center">
                            <div class="mx-5 mt-3 mb-5">
                                <button name="logInButton" type="submit" class="logInButton p-2">Log In</button>
                                <?php userLogin(); ?>
                            </div>
                        </div>
                    </form>

                    <!-- Directs to New User Sign Up -->
                    <form method="POST" class="mt-5">

                        <h2> Are you a new user? </h2>

                        <!-- New User Button -->
                        <div class="container d-flex justify-content-center align-items-center">
                            <div class="mx-5 mt-3">
                                <button name="existButton" type="submit" class="logInButton p-2">Sign Up</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>



            <!-- Login Form -->

            <div class="col-sm-6">
                <div class="mt-5 mb-5 mx-5 loginForm p-5">

                    <form method="POST" action="index.php">

                        <h2> Staff Login:</h2>

                        <div class="d-flex justify-content-center align-items-center my-4">
                            <table>
                                <!-- ID -->
                                <tr>
                                    <td class="p-4"><label for="staffId" class="labelStyle"> Staff Id: </label></td>
                                    <td class="p-4"><input type="email" name="staffId" class="inputStyle" required></td>
                                </tr>
                            </table>
                        </div>

                        <!-- Log In Button -->
                        <div class="container d-flex justify-content-center align-items-center">
                            <div class="mx-5 mt-3 mb-5">
                                <button name="logInButton" type="submit" class="logInButton p-2">Log In</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>




</body>

</html>