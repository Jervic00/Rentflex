<?php        /*  header('Content-Type: text/html; charset=ISO-8859-1'); */
require_once('partials/dbconn.php');
$query = "SELECT * FROM car_specs
LEFT JOIN car_list
ON car_specs.info_id = car_list.car_id
LEFT JOIN booking
ON booking.car_id = car_list.car_id
WHERE info_id LIKE '$_GET[cid]'";
/* $query = "SELECT * 
          FROM car_specs
          INNER JOIN car_list
          ON info_id = car_id
          WHERE info_id LIKE '$_GET[cid]'"; */
$result = mysqli_query($conn,$query);
$row = mysqli_fetch_array($result);
$counter = 1;
$price = number_format($row['price'], 2);
$status = $row['car_status'];
?>
                                                            <!-- HTML5 -->
<!DOCTYPE html>
<html>
<head>

    <?php include('partials/head.php') ?>
    <!-- Google API DONT TOUCH -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDQ4LrK00W9IKU-JQr0LiK2RIxrgzWwH1Q&libraries=places&region=PH"></script>
    <script>
                var searchInput = 'search_input';
                var searchInput2 = 'search_input2';

            $(document).ready(function () {
                
                var autocomplete;
                autocomplete = new google.maps.places.Autocomplete((document.getElementById(searchInput)), {//options
                types: ['address'],
                componentRestrictions: {
                country: "PH"
                }
                });//autocomplete semi-colon

                 google.maps.event.addListener(autocomplete, 'place_changed', function(results, status) {
                    var data = $("#search_input").val();
                    if(data === '')
                    $(".book-btn").prop('disabled', true);
                    else
                    $(".book-btn").prop('disabled', false);
                }); 
            });
/* 
                $(document).ready(function () {
                $location_input = $("#searchInput");
                var options = {
                types: ['address'],
                componentRestrictions: {
                country: 'PH'
                }
                };
                autocomplete = new google.maps.places.Autocomplete($location_input.get(0), options);    
                google.maps.event.addListener(autocomplete, 'place_changed', function() {
                var data = $("#search_form").serialize();
                console.log('blah')
                show_submit_data(data);
                return false;
                });
                });

                function show_submit_data(data) {
                alert(data);
                } */

            $(document).ready(function () {
                var autocomplete;
                autocomplete = new google.maps.places.Autocomplete((document.getElementById(searchInput2)), {
                types: ['address'],
                componentRestrictions: {
                country: "PH"
                }
                });//autocomplete semi colon
                
                $("#addr-check").change(function(){

                    if($("#addr-check").is(":checked"))
                    {
                        $(".drop-loc-container").hide().animate({opacity: '0'}, 500);
                        $("#search_input2").prop('required',false);
                    }
                    else
                    {
                        $(".drop-loc-container").show().animate({opacity: '1'}, 500);
                        $("#search_input2").prop('required',true);
                    }
                });
            });
            


    </script>

    <title>Car Details</title>
</head>
<body>
    <?php include('partials/header.php') ?>




    <main>
        <section class="main-container">
            <div class="left-container">
                <div class="car-model">
                    <div class="vertical-car-model">
                        <h1><?php echo $row['model'] ?></h1>
                    </div>
                </div>
                
                <i class="fa fa-arrow-left" id="prvButton" aria-hidden="true"></i>
                <i class="fa fa-arrow-right" id="nxtButton" aria-hidden="true"></i>
                <div class="car-slideshow">
                
                    <div class="slider">
                        <?php
                            while($counter<=4)
                            {
                            if(!($row["car_img{$counter}"] === NULL))
                            {
                                echo <<<XYZ
                                    <img src="img/cars/{$row["car_img{$counter}"]}" />
                                    XYZ;
                            }
                            $counter++;
                            }
                                ?>
                    </div>

                </div>
                    
                <div class="car-info">
                    <h3><?php echo $row['model'] ?></h3>
                    <p><?php echo $row['car_overview']?></p>

                </div>

                <div class="comment-section">
                    <div class="add-comment">
                        <form>
                        </form>
                    </div>
                    <div class="comments">

                    </div>
                </div>
            </div>

            <div class="right-container">
                <div class="price">
                    <p>Rent for <?php echo "₱" . $price ?>/day</p>
                </div>
                <?php
                    if($status == 'unavailable')
                    {?>
                        <div class="price">
                            <h3>Car Reserved until<br><?php echo $row['dropoff_date']?></h3>
                        </div>
                    <?php } 
                    else if($status == 'available')
                    {?>
                        <div class="form-group">
                            <form action="<?php echo "input_details.php?cid={$row['info_id']}"?>" method="post">
                                        <p>Pick-up Location:</p>
                                        <input type="text" class="form-control" id="search_input" name="pickup_loc" onchange="validatee()" placeholder="Type address..." required /><br>
                                    <div class="chkBox">
                                            <input type="checkbox" id="addr-check" name="chkbox" checked/>
                                            <label for="chkbox">Same drop-off location</label>
                                            
                                        <div class="drop-loc-container" style="display: none">
                                            <p>Drop-off Location:</p>
                                            <input type="text" class="form-control" id="search_input2" name="drop_loc" placeholder="Type address..."/>
                                    </div>
                                    </div>
                                    <p>Pick-up Date:</p>
                                    <input id="datetime" class="pickup-date" name="pickup_date" autocomplete="off" placeholder="mm/dd/yyyy" required/>
                                    <input type="text" class="timepick" name="pickup_time" id="time" required/>
                                    <div class="ddate" ><!-- style="display:none;" -->
                                    <p>Drop-off Date:</p>
                                    <input id="datetime1" class="drop-date" name="drop_date" autocomplete="off" placeholder="mm/dd/yyyy" required/>
                                    <input type="text" class="timepick" name="dropoff_time" id="time1" required/>
                                    </div>
                                    
                                    <input type="checkbox" id="insurance-check" name="insurance_chkbox" checked/>
                                    <label for="insurance_chkbox">Add insurance</label>

                                    <h3>Payment method</h3>
                                    <input type="radio" id="CASH" name="payment_method" value="CASH" checked>
                                    <label for="male">Cash (Full Payment)</label><br>
                                    <input type="radio" id="PDU" name="payment_method" value="PDU">
                                    <label for="female">Pay on day of usage(30% Down Payment)</label><br>
                                    <input type="radio" id="DIFFER" name="payment_method" value="DIFFER" disabled>
                                    <label for="female1">Monthly pay until pick up day<br>(30% Down Payment, 60days minimum)</label><br>

                                    <input type="submit" class="book-btn" name="book" value="Book Now" >
                            </form>
                        </div>
                <?php } ?>

                <div class="insurance">
                    <div class="insurance-info">
                        <h3>Add insurance for ₱3,500 only</h3>
                        <p><i class="fas fa-check"></i> Pays up to ₱80,000. $0 deductible</p>
                        <p><i class="fas fa-check"></i> Receive primary coverage without having to go through your own insurance</p>
                        <p><i class="fas fa-check"></i> Covers damage to rental due to collision, theft, vandalism, fire, or hail</p>
                        <br>
                        <p><i style="color:grey; font-size:10px;">"Failing to plan for insurance is one of the biggest car-rental errors you can make..." – The Washington Post, Aug 2018</i></p>    
                    </div>
                </div>
            </div>
        </section>


    </main>

    <?php include('partials/footer.php') ?>
 
   <!-- JQUERY UI -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script><!-- IDIK -->
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- <script src="jquery.js"></script>
<script src="jquery.datetimepicker.full.js"></script> --> 
 <!-- jQuery timepicker library -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
</body>
</html>