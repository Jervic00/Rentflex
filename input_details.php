<?php
require_once('partials/dbconn.php');
$car_id = $_GET['cid'];
$query = "SELECT price, model, thumbnail
          FROM car_list
          WHERE car_id LIKE '$car_id'";
$result = mysqli_query($conn,$query);
$row = mysqli_fetch_array($result);

$pickup_loc = $_POST['pickup_loc'];
$model = $row['model'];
$price = number_format($row['price'], 2);
$months_to_pay = 0;
if(empty($_POST['chkbox']) && !empty($_POST['drop_loc']))
{
    $drop_off = $_POST['drop_loc'];
}
else 
{
    $drop_off = $_POST['pickup_loc'];
}
//Mode of payment
if($_POST['payment_method'] == 'CASH')
{
    $payment_method = 'CASH';
}
else if ($_POST['payment_method'] == 'PDU')
{
    $payment_method = 'Pay on Day of Usage';
}
else if($_POST['payment_method'] == 'DIFFER')
{
    $payment_method = 'Monthly Pay';
    $months_to_pay = $_POST['months_to_pay'];
}

 
    
function dateDiffInDays($pickup_date, $drop_date)  
{ 
    // Calculating the difference in timestamps 
    $diff = strtotime($drop_date) - strtotime($pickup_date); 
      
    // 1 day = 24 hours 
    // 24 * 60 * 60 = 86400 seconds 
    return abs(round($diff / 86400)); 
} 

  
// Start date 
$pickup_date = $_POST['pickup_date'] . ' ' . $_POST['pickup_time']; 
  
// End date 
$drop_date = $_POST['drop_date'] . ' ' . $_POST['dropoff_time'];  
  
// Function call to find date difference 
$daysBooked = dateDiffInDays($pickup_date, $drop_date);
if($daysBooked == 0)
{
    $daysBooked++;
}

//total price plus Insurance Status
$insured = "no";
$total_price = $row['price'] * $daysBooked;
if(isset($_POST['insurance_chkbox']))
{
    $total_price += 3500;
    $insured = "YES";
}

if($months_to_pay<=3 && $_POST['payment_method'] == 'DIFFER')
{
    $total_price = number_format($total_price*1.05, 2);
}
else if(($months_to_pay > 3 && $months_to_pay <=6) && $_POST['payment_method'] == 'DIFFER')
{
    $total_price = number_format($total_price*1.1, 2);
}
else if(($months_to_pay>6 && $months_to_pay <=12) && $_POST['payment_method'] == 'DIFFER')
{
    $total_price = number_format($total_price*1.15,2);
}
else
{
    $total_price = number_format($total_price,2);
}

//Down Payment
if($_POST['payment_method'] == 'CASH')
{
   $down_payment = $total_price;
}
else if($_POST['payment_method'] == 'PDU' || $_POST['payment_method'] == 'DIFFER')
{
    $down_payment = number_format(($row['price'] * $daysBooked+3500)*.3,2);
}

//Reference code Generator
$book_code = BookCode();

function BookCode() { 

    $chars = "abcdefghijkmnopqrstuvwxyz023456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
  /*   srand((double)microtime()*7);  */
    $i = 0; 
    $pass = '' ; 

    while ($i <= 6) { 
        $num = rand() % 80; 
        $tmp = substr($chars, $num, 1); 
        $pass = $pass . $tmp; 
        $i++; 
    } 

    return $pass; 

} 
?>


<!DOCTYPE html>
<html>

<head>
    <?php include('partials/head.php') ?>
    <title>Input Details</title>
</head>
<body>
    <?php include('partials/header.php') ?>

    <main>
    <section class="book-main-container">
        <div class="details-container">
            <h3>Input contact details:</h3>
            <form action="submit_book.php" method="post">
            <input type="hidden" name="cid" value="<?php echo $car_id; ?>"/>
            <input type="hidden" name="ref" value="<?php echo $book_code; ?>"/>
            <input type="hidden" name="total_price" value="<?php echo $total_price; ?>"/>
            <input type="hidden" name="pickup_loc" value="<?php echo $pickup_loc; ?>"/>
            <input type="hidden" name="drop_loc" value="<?php echo $drop_off; ?>"/>
            <input type="hidden" name="pickup_date" value="<?php echo $pickup_date; ?>"/>
            <input type="hidden" name="drop_date" value="<?php echo $drop_date; ?>"/>
            <input type="hidden" name="model" value="<?php echo $model ?>"/>
            <input type="hidden" name="insured" value="<?php echo $insured ?>"/>
            <input type="hidden" name="thumbnail" value="<?php echo $row['thumbnail'] ?>"/>
            <input type="hidden" name="payment_method" value="<?php 
                                                                    if($_POST['payment_method'] == 'CASH')
                                                                    {
                                                                        echo 'CASH';
                                                                    }
                                                                    else if($_POST['payment_method'] == 'PDU')
                                                                    {
                                                                        echo 'PDU';
                                                                    }
                                                                    else if($_POST['payment_method'] == 'DIFFER')
                                                                    {
                                                                        echo 'DIFFER';
                                                                    }
                                                                 ?>"/>
            <?php
            if($_POST['payment_method'] == 'DIFFER')            
            {?>                                                                 
            <input type="hidden" name="months_to_pay" value="<?php echo $months_to_pay ?>"/>
      <?php }?>
                    <div class="name-container">
                        <div class="first-name">
                            <p>First Name:</p> 
                            <input type="text" class="inputs" name="firstName" maxlength="50" autocomplete="off" required/>
                        </div>
                        <div class="last-name">
                            <p>Last Name:</p>
                            <input type="text" class="inputs" name="lastName" maxlength="50" autocomplete="off" required/>
                        </div>
                    </div>

                    <div class="age-container">    
                        <p>Age:</p>
                        <input type="number" class="inputs age" name="age" min="18" max="60" required/>
                    </div>

                    <div class="contact-container">
                        <p>Contact No:</p>
                        <input type="tel" class="inputs" name="contactNum" pattern="[0-9]{11}" required/>
                    </div>
                    
                    <div class="email-container">
                        <p>Email:</p>
                        <input type="email" class="inputs" name="email" maxlength="50" autocomplete="off" required/>
                        
                    </div>
                    <div class="credit-container">
                    <div class="credit-header">
                    <h3>Credit Card Payment
                    <i id="visa" class="fab fa-cc-visa"></i>
                    <i id="mastercard" class="fab fa-cc-mastercard"></i>
                    <i id="jcb" class="fab fa-cc-jcb"></i>
                    <i id="amex"class="fab fa-cc-amex"></i>
                    
                    </h3>
                    </div>
                    
                    
                    <div class="card-holder-container">
                        <label for="card_holder">Card Holder's Name:</label>
                        <input type="text" class="inputs" name="card_holder" maxlength="50" autocomplete="off" placeholder="Enter full name" required>
                    </div>
                    <div class="card-number-container">
                        <label for="card_number">Card Number:</label>
                        <input type="tel" class="inputs" id="cc_number" name="card_number" placeholder="XXXX-XXXX-XXXX-XXXX">
                    </div>
<!-- inputmode="numeric" pattern="[0-9\s]{13,19}" maxlength="19" -->
                    <div class="credit-expiry-cvv">
                    <div class="card-expiry">
                    <label for="card_expiry">Card Expiry: </label>
                    <input type="tel" class="inputs" id="cc_expiry" name="card_expiry" placeholder="MM/YY" required>
                    </div>
                    <div class="card-cvv">
                    <label for="card_cvv">CVV</label>
                    <input id="cvv" type="tel" class="inputs" name="card_cvv" placeholder="XXX" required>
                    </div>
                    </div>
                    
                    </div>
                <div class="submit-container">
                    <button id="submit_btn" class="submit-btn" name="checkout">Submit</button>
                    <h4 id="error_txt">Credit Card Invalid/Unsupported.</h4> 
                </div>    
               
            </form>
        </div>

        <div class="book-details">
            <div class="book-details-container">
                <div class="car-img">
                    <img src="img/cars/<?php echo "{$row['thumbnail']}" ?>" alt="" class="">
                </div>
                <div class="book-date">
                    <table>
                        <tr><th>Book Details</th></tr>
                        <tr>
                            <td>Pick up on: </td>
                            <td><?php echo str_replace('T',' ', $pickup_date)?></td>
                        </tr>

                        <tr>
                            <td>Drop-off on:</td>
                            <td> <?php echo str_replace('T',' ', $drop_date)?></td>
                        </tr>
                        <tr>
                            <td>Pick up location: </td>
                            <td><?php echo $pickup_loc ?></td>
                        </tr>
                        <tr>
                            <td>Drop off location:</td> 
                            <td> <?php echo $drop_off?></td>
                        </tr>
                        <tr>
                            <td>Car booked:</td>
                            <td> <?php echo $model?></td>
                        </tr>

                        <tr>
                            <td>Price:</td> 
                            <td>₱<?php echo $price ?> / day</td>
                        </tr>    

                        <tr>
                            <td>Mode of Payment:</td> 
                            <td><?php echo $payment_method ?> </td>
                        </tr>    

                        <tr>
                            <td>Days:</td>
                            <td><?php echo $daysBooked ?></td>
                        </tr>

                        <tr>
                            <td>Insurance:</td>
                            <td><?php 
                            if(isset($_POST['insurance_chkbox']))
                            {
                                echo "₱3,500";
                            }
                            else
                            {
                                echo "No insurance";
                            }
                            
                            
                            ?></td>
                        
                        </tr>
                        <tr>
                            <td>Total Price:</td> 
                            <td>₱<?php echo $total_price?></td>
                        </tr>   
                        <!-- Supposedly Down Payment -->

                        <tr>
                            <td>Pay upon booking:</td>
                            <td>₱<?php echo $down_payment;?> </td>
                        </tr>

                        <?php
                        if($_POST['payment_method'] == 'DIFFER')
                        {
                            $book_date = strtotime('now');
                            $text_total_price = str_replace(",", "", $total_price);
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
                        <!-- Supposedly Down Payment -->



                        
                        <tr>
                            <td>Reference Code: </td>
                            <td><?php echo $book_code ?></td>
                        </tr>
                    </table>
                </div>
                        
                            
                
            </div>
        </div>
    </section>
    </main>

    <?php include('partials/footer.php') ?>
    <script src="javascript/cleave.min.js"></script>
    <script>
        new Cleave("#cc_number", {
        creditCard: true,
        onCreditCardTypeChanged: function (type) {
        console.log(type);

        var x = document.getElementsByClassName("fab");
        var i;
        for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";

        }
        if(type != "unknown"){
        document.getElementById(type).style.display = "block";
        document.getElementById("error_txt").style.display = "none";
        document.getElementById("submit_btn").disabled = false;
        }
        else if (type == "unknown" || type == "uatp")
        {
            document.getElementById("error_txt").style.display = "block";
            document.getElementById("submit_btn").disabled = true;
        }
        if(type == "amex")
        document.getElementById("cvv").setAttribute("maxlength", "4");
        else
        document.getElementById("cvv").setAttribute("maxlength", "3");
        document.getElementById("cvv").value = "";
        }
        });
        new Cleave('#cc_expiry', {
        date: true,
        delimeter: '/',
        datePattern: ['m', 'y']
        });
</script>
</body>
</html>