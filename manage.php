<?php
    require_once('partials/dbconn.php');

if(isset($_POST['ref']) || isset($_POST['email']))
{
    $ref = $_POST['ref'];
    $email = strtoupper($_POST['email']);
    $query = "SELECT * FROM booking
    LEFT JOIN car_list
    ON booking.car_id = car_list.car_id
    WHERE booking_id = '$ref' AND booking.email = '$email'";
}

if(isset($query))
{
    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_array($result);
    $count = mysqli_num_rows($result);
}
else
{
    $count = 0;
}

/* DELETE RECORD */
if(isset($_POST['cancel-btn']))
{
    $query = "DELETE FROM booking WHERE booking_id = '$_POST[del_ref]' AND email = '$_POST[del_email]'";
    $result = mysqli_query($conn, $query);
    if(isset($result))
    {
        $query = "UPDATE `car_list` SET `car_status`='available' WHERE car_id = '$_POST[cid]'";
        $result = mysqli_query($conn, $query);
        if(!isset($result))
        echo "car still not available";
    }
    else
    {
        echo "Error Occured $ref $email ";
    }
}

//Calculating Dates and Penalties
//Unfinished
if(isset($result))
{
    $price_paid = 0;
    $price_paid_DB = $row['price_paid'];
    $drop_off = strtotime($row['dropoff_date']);
    $pickup_date = strtotime($row['pickup_date']);
    $TodayDate = strtotime('now');
    $penalty = 0;
    $status = 'Unknown';
    $car_price = $row['price'];
    //for penalties
    if($drop_off<$TodayDate)
    {
        $timeDiff = abs($TodayDate - $drop_off);//TodayDate - dropoff date

        $numberDays = $timeDiff/86400;  // 86400 seconds in one day

        $numberDays = intval($numberDays);//number of penalty Days

        $penalty = 1.3 * ($numberDays * $car_price);//compute the penalty

        $price_paid = $penalty;//automatically pay the system for the penalties. 

        $new_total_price = $penalty - $price_paid;// Change the total price due according to price paid by the customer
    
        //add the penalty payment in the price_paid DB
        $price_paid_DB = $row['price_paid'] + $price_paid;

        if(isset($_POST['ref']) || isset($_POST['email']))
        {
        $query = "UPDATE `booking` SET `price_paid` = '$price_paid_DB', `price_to_pay` = '$new_total_price' WHERE `booking_id` = '$_POST[ref]' ";
        $result = mysqli_query($conn, $query);
        if(!isset($result))
        {
            echo "Error Occured $query  numberdays: $numberDays $timeDiff";
        }

        }

    }

    
    //for PDUs, Differ 
    if($pickup_date<=$TodayDate && $drop_off >= $TodayDate && $row['mode_of_payment'] == 'PDU')//     $row['price']; is car price per day.
    {
        $timeDiff = abs($TodayDate - $pickup_date);
        $numberDays = $timeDiff/86400;
        if($numberDays < 1)
        $numberDays = 1;
        else
        $numberDays = intval($numberDays);//Days rented 

            $price_paid = $numberDays * $car_price;

        $new_total_price = $row['price_to_pay'] - $price_paid;
        $price_paid_DB += $price_paid;

        //Adding customer payment(price_paid in SQL) to the database
        if(isset($_POST['ref']) || isset($_POST['email']))
        {
        $query = "UPDATE `booking` SET `price_paid` = '$price_paid_DB', `price_to_pay` = '$new_total_price' WHERE `booking_id` = '$_POST[ref]' ";
        $result = mysqli_query($conn, $query);
        if(!isset($result))
        {
            echo "Error Occured $query  numberdays: $numberDays $timeDiff";
        }

        }

    }
    $book_date = strtotime($row['book_date']);
    /* $book_date=date('dd-mm-YYYY',strtotime($row['book_date'])); */
    if ($pickup_date>=$TodayDate && $drop_off >= $TodayDate && $row['mode_of_payment'] == 'DIFFER')
    {   
        $timeDiff = abs($pickup_date - $book_date);
        $daysBefore = $timeDiff/86400;  // 86400 seconds in one day
        $monthsBefore = intval($daysBefore / 30);// how many months

        $monthly_pay = 0;
        $new_total_price = 0;

        $timeDiff2 = abs($TodayDate - $book_date);
        $daysPast = $timeDiff2/86400;  // 86400 seconds in one day
        $daysPast = intval($daysPast / 30);

        if($daysPast>=1)
        {
            $monthly_pay = ($row['price_to_pay'] / $monthsBefore); // 33946.50
            $price_paid = ($monthly_pay * $daysPast);  
        }
        else if($daysPast<=0)
        {
            $price_paid = 0;
        }
            
                $new_total_price = $row['price_to_pay'] - $price_paid;
                $price_paid_DB += $price_paid;
            if(isset($_POST['ref']) || isset($_POST['email']))
            {
            $query = "UPDATE `booking` SET `price_paid` = '$price_paid_DB', `price_to_pay` = '$new_total_price' WHERE `booking_id` = '$_POST[ref]' ";
            $result = mysqli_query($conn, $query);
            if(!isset($result))
            {
                echo "Error Occured $query  numberdays: $numberDays $timeDiff";
            }
    
            }
    }
    

    if($pickup_date<$TodayDate && $drop_off>$TodayDate)
    {
        $status = 'Ongoing';
    }
    else if($pickup_date>$TodayDate && $drop_off>$TodayDate)
    {
        $status = 'Pending';
    }
    else if($pickup_date<$TodayDate && $drop_off<$TodayDate)
    {
        $status = 'Overdue';
    }

    if($status != 'Unknown')
    {
        $query = "UPDATE `booking` SET `status`='$status' WHERE `booking_id` = '$_POST[ref]'";
        $result = mysqli_query($conn, $query);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('partials/head.php') ?>
    <title>Manage Booking</title>
</head>
<body>
    <?php
        include('partials/header.php')
    ?>
    
    <main>
        <section class="manager-container">
            <form action="manage.php" class="search-form" method="post">
                <div class="ref-container">
                    <p>Reference Code:</p>
                    <input type="text" class="inputs" name="ref" maxlength="7" placeholder="Reference Code" required/>
                </div>
                
                <div class="e-mail-container">
                    <p>E-mail Address:</p>
                    <input type="email" class="inputs" name="email" maxlength="50" placeholder="Enter email address" required/>
                </div>
                
                <button class="submit-btn" name="checkout">Submit</button>
            </form>
            
                <?php 
                
                if($count>0)
                {?>
            <div class="search-container">
                    <div class="booking-details">
                        
                        <table>
                            <tr>
                                <td>Reference Code:</td>
                                <td><?php echo $row['booking_id']?></td>
                            </tr>

                            <tr>
                                <td>Status:</td>
                                <td><?php echo $status ?></td>
                            </tr>

                            <tr>
                                <td>Reservation By:</td>
                                <td><?php echo $row['firstName'] . ' ' . $row['lastName']?></td>
                            </tr>

                            <tr>
                                <td>Contact No.:</td>
                                <td><?php echo $row['contactNumber']?></td>
                            </tr>                     
    

                            <td>Car booked:</td>
                                <td><?php echo $row['model']?></td>
                            </tr>

                            <tr>
                                <td>Pick up on: </td>
                                <td><?php echo $row['pickup_date']?></td>
                            </tr>

                            <tr>
                                <td>Drop-off on:</td>
                                <td><?php echo $row['dropoff_date']?></td>
                            </tr>
                            <tr>
                                <td>Pick up location: </td>
                                <td><?php echo $row['pickup_loc']?></td>
                            </tr>
                            <tr>
                                <td>Drop off location:</td> 
                                <td><?php echo $row['drop_loc']?></td>
                            </tr>
                            <tr>

                            <tr>
                                <td>Day/s Booked:</td>
                                <td><?php 
                                        $TodayDate = strtotime('now');
                                        $drop_off = strtotime($row['dropoff_date']);
                                        $pickup_date = strtotime($row['pickup_date']);
                                    if($status == 'Pending' || $status == 'Ongoing')
                                    {
                                        
                                        $timeDiff = abs($pickup_date - $drop_off);
                                        $numberDays = $timeDiff/86400;  // 86400 seconds in one day
                                        $numberDays = intval($numberDays);//number of Days rent

                                        echo $numberDays . " Day/s";

                                    }
                                    else if($status == 'Overdue')
                                    {

                                        $timeDiff = abs($pickup_date - $drop_off);
                                        $numberDays = $timeDiff/86400;  // 86400 seconds in one day
                                        $numberDays = intval($numberDays);//number of Days rent

                                        $timeDiff = abs($TodayDate - $drop_off);
                                        $overdue = $timeDiff/86400;
                                        $overdue = intval($overdue);

                                        echo $numberDays . ' Day/s' . '(' . $overdue . ' day/s late)';
                                    }
                                      ?></td>
                            </tr>

                            <tr>
                                <td>Price:</td> 
                                <td>₱<?php echo number_format($row['price'], 2)?> / day</td>
                            </tr>
                            <tr>
                                <td>With Insurance:</td> 
                                <td><?php echo $row['insured'] ?></td>
                            </tr>

                            <tr>
                                <td>Price paid: </td>
                                <td>₱<?php 
                                    
                                    if($row['mode_of_payment'] == 'PDU' || $row['mode_of_payment'] == 'DIFFER')
                                    {
                                        echo number_format($price_paid_DB, 2);
                                    }
                                    else if($row['mode_of_payment'] == 'CASH')
                                    {
                                        echo number_format($price_paid_DB,2);
                                    }
                                ?></td>
                            </tr>
                        <?php //penalties
                        
                        if(isset($penalty) && $penalty !=0)
                        {?>
                            <tr>
                                <td>Penalty:</td> 
                                <td>₱<?php echo number_format($penalty, 2);?></td>
                            </tr>

                            <tr>
                                <td>Total Price due(Penalties included):</td> 
                                <td>₱<?php 
                                if($row['mode_of_payment'] == 'PDU' || $row['mode_of_payment'] == 'DIFFER')
                                {
                                $price_due = $new_total_price ;
                                $query = "UPDATE `booking` SET `penalty`='$penalty' WHERE `booking_id` = '$_POST[ref]'";
                                $result = mysqli_query($conn, $query);
                                echo number_format($price_due, 2);
                                }
                                else if ($row['mode_of_payment'] == 'CASH')
                                {
                                    if($drop_off<$TodayDate)
                                    {
                                        $price_due = $new_total_price ;
                                        $query = "UPDATE `booking` SET `penalty`='$penalty' WHERE `booking_id` = '$_POST[ref]'";
                                        $result = mysqli_query($conn, $query);
                                        echo number_format($price_due, 2);
                                    } 
                                }
                                ?></td>
                            </tr>

                <?php   } //penalties end
                    else{ //No penalties
                    ?>
                            <tr>
                                <td>Total Price due(No penalty):</td> 
                                <td>₱<?php 
                                if($row['mode_of_payment'] == 'PDU')
                                {
                                $price_to_pay = $row['price_to_pay'];
                                $price_due = $price_to_pay - $price_paid;
                                echo number_format($price_due, 2);
                                }
                                else if($row['mode_of_payment'] == 'CASH')
                                {
                                    echo number_format(0,2);
                                }
                                else if($row['mode_of_payment'] == 'DIFFER')
                                {
                                    echo number_format($new_total_price,2);
                                }
                                ?>
                                
                                </td>
                            </tr>  

                            <?php
                        if($row['mode_of_payment'] == 'DIFFER')
                        {
                            $months_to_pay = $row['months_to_pay'];
                            $book_date = strtotime($row['book_date']);
                            $text_total_price = str_replace(",", "", $row['price_to_pay']);
                            $monthly_payment = $text_total_price / $months_to_pay;
                            
                            for($i=1; $i<=$months_to_pay; $i++ )
                            {?>
                            <tr>
                                <td><?php echo date("m/d/Y",strtotime("+". $i ."month",$book_date))?></td>
                                <td><?php echo '₱' . number_format($monthly_payment, 2)?></td>
                            </tr>
                            <?php
                            }
                        }?>

                <?php   } //No penalties  ?>        
                        </table>
                        <?php
                        if($status == 'Pending')
                        { //you can cancel if it's pending?>
                        <form action="manage.php"onsubmit="return confirm('Do you really want to cancel your reservation?');" class="cancel-form" method="post">
                            <input type="hidden" name="del_ref" value="<?php echo $row['booking_id']; ?>"/>
                            <input type="hidden" name="cid" value="<?php echo $row['car_id']; ?>"/>
                            <input type="hidden" name="del_email" value="<?php echo $row['email']; ?>"/>
                            <input type="submit" class="cancel-btn"  name="cancel-btn" value="Cancel Booking" ></input>
                        </form>
                <?php   }// you cannot if it's ongoing
                        else if($status == 'Overdue' || $status == 'Ongoing')
                        {
                            echo '<input type="submit" class="cancel-btn" style="cursor: default;" name="cancel-btn" value="Cancel Booking" disabled></input>';
                            echo '<span style="color: red;"> &nbsp; Cannot cancel reservation this time.</span>';
                        }
                        ?>
                    </div>

                    <div class="car-img-container">
                        <img src="img/cars/<?php echo $row['thumbnail']?>" alt="thumbnail" > 
                    </div>
            </div>        
                <?php } 
                else if(isset($_POST['ref']) && $count<=0){
                    echo "Reference code or Email address doesn't match.";
                }
                else if(isset($_POST['del_ref'])){
                    echo "Reservation deleted successfully!";
                }
                
                ?>
            
        </section>
    </main>



    <?php
        include('partials/footer.php')
    ?>
</body>
</html>