<?php

session_start();

unset($_SESSION['user']); // Unset the user session variable

header('Location: login.php'); // Redirect to the login page
exit; 