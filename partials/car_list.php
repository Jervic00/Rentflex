<?php         
require_once('partials/dbconn.php');

if(!isset($_GET['car_type']) || $_GET['car_type'] == 'all') 
{
    $query = "SELECT * FROM car_list"; 
}
else
{
/* TO DISPLAY ALL THE RESULT ON THE FIRST OPEN OF THE PAGE */
    $query = "SELECT * FROM `car_list` WHERE `car_type` LIKE '$_GET[car_type]'";
}
$result = mysqli_query($conn,$query);
$count = mysqli_num_rows($result);

/* YOU CAN DECIDE HOW MANY RESULTS */
$result_per_page = 5;
/* TO GET THE NUMBER OF PAGE BY DIVIDING ROWS TO RESULTS PER PAGE */
$number_of_pages = ceil($count/$result_per_page);

/* TO GET THE CURRENT PAGE */
if (!isset($_GET['page']))
{
    $page = 1;
}
else
{
    $page = $_GET['page'];
}
$current_page = $page;
/* TO GET STARTING ITEM OF THE PAGE */
$starting_index = ($page-1) * $result_per_page;
/* TO SELECT HOW MANY ITEMS WILL DISPLAY ON THE PAGE */
if(!isset($_GET['car_type']) || $_GET['car_type'] == 'all') 
{
    $query = "SELECT * FROM car_list ORDER BY `car_status` LIMIT $starting_index,$result_per_page";
    
}
else 
{
    $query = "SELECT * FROM `car_list` WHERE `car_type` LIKE '$_GET[car_type]' ORDER BY `car_status` LIMIT $starting_index,$result_per_page";
}
$result = mysqli_query($conn,$query);

/* TO GET CAR TYPE WITHOUT DUPLICATE AND USE AS A FILTER */
$query = "SELECT DISTINCT car_type FROM car_list";
$filter_result = mysqli_query($conn, $query);
if(!isset($_GET['car_type']))
{
    $current_category = 'all';
}
else
{
    $current_category = $_GET['car_type'];
}
?>

<section class="list-presentation">
            <div class="list-container">
                <div class="filter-container">
                    <div class="filter-button">
                        <h2>Filter: </h2>
<!--                     <button class="btn active" id="all"> Show all</button>
                    <button class="btn" id="SUV">SUV</button>
                    <button class="btn" id="STANDARD">STANDARD</button>
                    <button class="btn" id="SUBCOMPACT">SUBCOMPACT</button>
                    <button class="btn" id="COMPACT">COMPACT</button>
                    <button class="btn" id="HATCHBACK">HATCHBACK</button>
                         -->
                        <?php
                        if($current_category == 'all')
                        {
                            echo '<a href="Listing.php?page=1" class="btn active" style="pointer-events: none">Show All</a>';
                        }
                        else
                        {
                            echo '<a href="Listing.php?page=1" class="btn">Show All</a>';
                        }
                        while($row = mysqli_fetch_array($filter_result))
                        {

                            if($row['car_type'] == $current_category)
                                echo '<a href="Listing.php?page=1&car_type=' . $row['car_type'] . '" class="btn active" style="pointer-events: none">' . $row['car_type'] . '</a>';
                            else
                                echo '<a href="Listing.php?page=1&car_type=' . $row['car_type'] . '" class="btn">' . $row['car_type'] . '</a>';

                        }
                        ?> 
                    </div>
                </div>

                <div class="car-list">
                    <ul>
                        <?php 
                        if($count >0){
                            while($row = mysqli_fetch_array($result))
                            {   
                                $price = number_format($row['price'], 2);
                                if($row['car_status'] =="available")
                                { echo <<<XYZ
                                        <div class="car-container {$row['car_type']}" >
                                        <div class="img-container">
                                            <img class="car img" src="img/cars/{$row['thumbnail']}" />
                                        </div>
                                        <div class="car-specs">
                                            <h3> {$row['model']}</h3>
                                            <i class="fa fa-user" aria-hidden="true"> {$row['seats']} Seats</i>
                                            <br><i class="fas fa-gas-pump"> {$row['fuel_type']}</i>
                                            <p>₱$price/ day</p>
                                        </div>
                                        <div class="view-button">
                                            <a class="btn-view" href="product.php?cid={$row['car_id']}">View Details</a>
                                        </div>
                                        </div>
                                        XYZ;
                                }
                                else{
                                    echo <<<XYZ
                                        <div class="car-container {$row['car_type']}" >
                                        <div class="img-container">
                                            <img class="car img" src="img/cars/{$row['thumbnail']}" />
                                        </div>
                                        <div class="car-specs">
                                            <h3> {$row['model']}</h3>
                                            <i class="fa fa-user" aria-hidden="true"> {$row['seats']} Seats</i>
                                            <br><i class="fas fa-gas-pump"> {$row['fuel_type']}</i>
                                            <p>₱$price/ day</p>
                                        </div>
                                        <div class="view-button">
                                            <a class="btn-view disabled" href="product.php?cid={$row['car_id']}" style="background:gray !important;">Reserved</a>
                                        </div>
                                        </div>
                                        XYZ;
                                }
                            } 
                        }
                        else
                        {
                            echo "<p>No car available.</p>";
                            echo $_GET['car_type'];
                        }
                        
                        ?>
                    </ul>

                </div>
                <div class="navigation-page">
                        <div class="navigation-number">
                        <?php
                            for($page=1;$page<=$number_of_pages;$page++)
                            {
                                if(!isset($_GET['car_type']) || $_GET['car_type'] == 'all')
                                {
                                    if($current_page == $page)
                                        echo '<a href="Listing.php?page=' . $page . '&car_type=' . $current_category . '" style="pointer-events: none; background: #00adb5; border: 1px solid #eeeeee;">'. $page .'</a>';
                                    else
                                        echo '<a href="Listing.php?page=' . $page . '&car_type=' . $current_category . '">'. $page .'</a>';
                                    }
                                else
                                {
                                    if($current_page == $page)
                                        echo '<a href="Listing.php?page=' . $page . '&car_type=' . $current_category . '" style="pointer-events: none; background: #00adb5; border: 1px solid #eeeeee;">'. $page .'</a>';
                                    else
                                        echo '<a href="Listing.php?page=' . $page . '&car_type=' . $current_category . '">'. $page .'</a>';
                                }
                            }
                        ?>
                        </div>
                </div>
            </div>
        </section>