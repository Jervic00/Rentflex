<?php
    include('partials/dbconn.php');
    $ref = $_POST['ref'];
    $cid = $_POST['cid'];
    $fname = strtoupper($_POST['firstName']);  
    $lname = strtoupper($_POST['lastName']);
    $email = strtoupper($_POST['email']);
    $price_to_pay = str_replace( ',', '', $_POST['total_price']);
    $pickup_loc = $_POST['pickup_loc'];
    $drop_loc = $_POST['drop_loc'];
    $pickup_date = $_POST['pickup_date'];
    $drop_date = $_POST['drop_date'];
    $contactNo = $_POST['contactNum'];
    $payment_method = $_POST['payment_method'];
    $insured = $_POST['insured'];
    if($payment_method == 'PDU' || $payment_method == 'DIFFER')
    {
    $price_paid = $price_to_pay*.3;
    $price_to_pay = $price_to_pay - $price_paid;
    }
    else if($payment_method == 'CASH')
    {
        $price_paid = $price_to_pay;
        $price_to_pay -= $price_paid;
    }
/* NEXT TASK : Deduct the Downpayment into the Total price/ price to pay */

    $query = "INSERT INTO `booking`(`booking_id`, `firstName`, `lastName`, `contactNumber` , `email`, `car_id`, `pickup_date`, `dropoff_date`, `pickup_loc`, `drop_loc`, `price_paid` , `price_to_pay`, `mode_of_payment` , `insured`) 
    VALUES ('$ref', '$fname', '$lname', '$contactNo', '$email', '$cid', '$pickup_date', '$drop_date', '$pickup_loc', '$drop_loc', '$price_paid' , '$price_to_pay', '$payment_method' , '$insured')";
    
    if(!mysqli_query($conn, $query))
    {
        //PRINT ERROR CLUE
        echo 'Error Occured' . $query = "INSERT INTO `booking`(`booking_id`, `firstName`, `lastName`, `contactNumber` , `email`, `car_id`, `pickup_date`, `dropoff_date`, `pickup_loc`, `drop_loc`, `price_paid` , `price_to_pay`, `mode_of_payment` , `insured`) 
        VALUES ('$ref', '$fname', '$lname', '$contactNo', '$email', '$cid', '$pickup_date', '$drop_date', '$pickup_loc', '$drop_loc', '$price_paid' , '$price_to_pay', '$payment_method' , '$insured')";
    }
    else 
    {
        //UPDATE CAR STATUS
    $query = "UPDATE car_list SET `car_status`= 'unavailable' WHERE car_id ='$cid'"; 
    if(!mysqli_query($conn, $query))
    {
        echo 'unSuccess2' . $cid;
    }

    $card_holder = $_POST['card_holder'];
    $card_number = $_POST['card_number'];
    $card_expiry = $_POST['card_expiry'];
    $card_cvv = $_POST['card_cvv'];

    $query = "INSERT INTO `credit_list` (`booking_id`, `card_holder`, `cvv`, `card_number`, `expiration`)
    VALUES ('$ref', '$card_holder', '$card_cvv', '$card_number', '$card_expiry')";
    if(!mysqli_query($conn, $query))
    {
        echo "Credit card error!" . $query ;
    }




}
?>


<!DOCTYPE html>
<html>
<head>
    <?php include('partials/head.php')?>
    <title>Booked</title>
</head>
<body>
    <?php include('partials/header.php') ?>
    
    <main>
        <section class="submitted-details">
            <div class="container">
            <div class="submitted-info">
                <h3>Success!</h3>
                <p>You have successfully booked <?php echo $_POST['model'] ?>. We'll verify your payment. Check your Booking status using</p> 
                <p>Reference Code: <?php echo $ref ?></p>            
            <a class="homepage-btn" href="index.php">Go back to homepage</a>
            <form action="manage.php" method="post">
            <input type="hidden" name="ref" value="<?php echo $ref; ?>"/>
            <input type="hidden" name="email" value="<?php echo $email; ?>"/>
            <button class="homepage-btn">Manage booking</button>
            </form>
            </div>
                <div class="thumbnail">
                    <div class="container-img">
                        <img src="img/cars/<?php echo "{$_POST['thumbnail']}" ?>" alt="" class="">
                    </div>
                    
                </div>
            </div>
        </section>
    </main>

    <?php include('partials/footer.php') ?>
</body>
</html>