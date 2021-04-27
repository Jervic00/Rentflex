<?php
    require_once('dbconn.php');
//To check connection
if(!$conn){
    echo 'Disconnected';
}//to check database if selected
if(!mysqli_select_db($conn, 'rentflex')){
    echo 'Select error';
}
    $email = $_POST['eemail'];
    $msg = $_POST['teext'];
    $query = "INSERT INTO feedback (email_address, feedbacks) 
              VALUES ('$email' , '$msg')";
    //execute query command
    if(!mysqli_query($conn, $query))
    {
        echo 'unSuccess';
    }
    //get back to the homepage
    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>

