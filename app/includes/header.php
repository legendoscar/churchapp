<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require "../includes/db-config.php";?>
    <?php require "./includes/check_if_login.php";?>
    <?php require "./includes/global_function.php";?>
    <?php require "./includes/function.php";?>
    <?php require "./includes/additional_function.php";?>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="Church Account App">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <meta name="robots" content="noindex, nofollow">

    <link rel="shortcut icon" type="image/x-icon" href="./assets/img/logo.png">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/css/font-awesome.min.css">

    <link rel="stylesheet" href="assets/css/line-awesome.min.css">

    <script src="assets/js/chartjs/Chart.min.css"></script>

    <link rel="stylesheet" href="assets/plugins/morris/morris.css">

    <link rel="stylesheet" href="assets/css/select2.min.css">

    <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css">

    <link rel="stylesheet" href="../assets/toastr/toastr.min.css" />

    <link rel="stylesheet" href="assets/css/style.css">

    <!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->
</head>
<div id="return_server_msg"></div>