<?php
/*
Template Name: Cabins PHP Logout Template
Description: Custom logout page.
Version: 1.0
Author: Siren Watcher
*/

// Start session
session_start();

// Destroy session
session_destroy();

// Redirect to the login page
wp_redirect(home_url('/sunny-spot-login/'));
exit;
?>
