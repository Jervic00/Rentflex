<?php         header('Content-Type: text/html; charset=ISO-8859-1');
$conn = mysqli_connect('localhost','bscs','','rentflex');
$query = "SELECT * 
          FROM car_specs
          INNER JOIN car_list
          ON info_id = car_id
          WHERE info_id LIKE '$_GET[cid]'";
$result = mysqli_query($conn,$query);
$row = mysqli_fetch_array($result);
$counter = 1;
?>
<!DOCTYPE html>
<html>
<head>
    <?php include('partials/head.php') ?>
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
                    <p>Rent for $<?php echo $row['price']?>/day</p>
                </div>

                <div class="form">
                <p>Pick-up Location:</p>
                <!-- <input type="textarea" class="pickup-loc" name="pickup_loc"> --><textarea rows="4" cols="25" name="pickup_loc"></textarea>
                <p>Pick-up Date:</p>
                <input type="date" class="pickup-date" name="pickup_date">
                <p>Drop-off Date:</p>
                <input type="date" class="drop-date" name="drop_date"><br>
                <input type="submit" class="book-btn" name="book" value="Book Now!">
                </div>

                <div class="side-specs">

                </div>
            </div>
        </section>


    </main>

    <?php include('partials/footer.php') ?>

</body>
</html>