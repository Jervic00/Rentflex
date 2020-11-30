<?php         
$conn = mysqli_connect('localhost','bscs','','rentflex');
$query = "SELECT * FROM car_list";
$result = mysqli_query($conn,$query);
$count = mysqli_num_rows($result);
?>

<section class="list-presentation">
            <div class="list-container">
                <div class="filter-container">
                    <div class="filter-button">
                        <h2>Filter: </h2>
                        <button class="btn active" id="all"> Show all</button>
                        <button class="btn" id="SUV">SUV</button>
                        <button class="btn" id="STANDARD">STANDARD</button>
                        <button class="btn" id="SUBCOMPACT">SUBCOMPACT</button>
                        <button class="btn" id="LUXURY">LUXURY</button>
                        <p> </p>
                    </div>
                </div>

                <div class="car-list">
                    <ul>
                        <?php 
                        if($count >0){
                            while($row = mysqli_fetch_array($result))
                            {
                                if($row['status'] == 'available')
                             echo <<<XYZ
                                    <div class="car-container {$row['car_type']}" >
                                    <div class="img-container">
                                        <img class="car img" src="img/cars/{$row['thumbnail']}" />
                                    </div>
                                    <div class="car-specs">
                                        <h3> {$row['model']}</h3>
                                        <i class="fa fa-user" aria-hidden="true"> {$row['seats']} Seats</i>
                                        <p>\$ {$row['price']}/ day</p>
                                    </div>
                                    <div class="view-button">
                                        <a class="btn-view" href="product.php?cid={$row['car_id']}">View Details</a>
                                    </div>
                                    </div>
                                    XYZ;
                            } 
                        }
                        else
                        {
                            echo "<p>No car available.</p>";
                        }
                        
                        ?>
                    </ul>

                </div>
            </div>
        </section>