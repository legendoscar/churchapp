<?php
    date_default_timezone_set('Africa/Lagos');
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = "churchapp";
    @$conn = mysqli_connect($servername, $username, $password, $db);
    if(!@$conn){
        die("<b>Unable to establish database connection.</b>");
    }
?>