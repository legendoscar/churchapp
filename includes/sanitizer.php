<?php 
function SanitizeTEXT($userInput) {
    $userInput = filter_var($userInput, FILTER_SANITIZE_STRING);
    // $userInput = filter_var($userInput, FILTER_FLAG_STRIP_HIGH);
    return $userInput;
}

function SanitizeEMAIL($userInput) {
    $userInput = filter_var($userInput, FILTER_SANITIZE_EMAIL);
}

function ValidateEMAIL($userInput) {
    $userInput = filter_var($userInput, FILTER_VALIDATE_EMAIL);
    if(!$userInput) {
        return "<strong>Warning!</strong> Please Input a valid Email Address.";
    } else {
    return $userInput;
    }   
}

function SanitizeURL($userInput) {
    $userInput = filter_var($userInput, FILTER_SANITIZE_URL);
}

function ValidateURL($userInput) {
    $userInput = filter_var($userInput, FILTER_VALIDATE_URL);
    if(!$userInput) {
        echo "Input a valid URL. Must begin with <i>http:// or https://)";
    } else {
    return $userInput;
    }
}

function SanitizeIP($userInput) {
    $userInput = filter_var($userInput, FILTER_VALIDATE_IP);
    if(!$userInput) {
        echo "Input a valid IP Address.";
    } else {
    return $userInput;
    }
}

function ValidateInt($userInput) {
    $userInput = filter_var($userInput, FILTER_VALIDATE_INT);
    if(!$userInput) {
        echo "Input a valid number.";
    } else {
    return $userInput;
    }
}

function escapeStrings($input){
    // $input = mysqli_real_escape_string($conn, $input);
    $input = trim($input);
    $input = htmlspecialchars($input);
    $input = stripslashes($input);
    $input = strip_tags($input);
    return $input;
}
?>