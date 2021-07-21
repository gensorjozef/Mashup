<?php
session_start();
require_once "Classes/helper/Database.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$conn = (new Database())->getConnection();
if (!isset($_SESSION["ip"])){
    $ip = $_SERVER['REMOTE_ADDR'];
    $access_key = "07c237255a4130ca59aad95ea5e3581d";
    $url = "http://api.ipstack.com/" . $ip . "?access_key=" . $access_key;


    $ip_stack = json_decode(file_get_contents($url),true);
    $_SESSION["ip"] = $ip_stack;
}

// country da pocet logou pre mesto
// SELECT city, sum(total_visits), country, country_flag FROM (SELECT DISTINCT ip_address, country,country_flag,city, DATE(local_time) as day,COUNT( DISTINCT ip_address) AS total_visits  FROM logs GROUP BY DATE(local_time),ip_address, country,country_flag,city)  AS lala WHERE country = "Slovakia"  GROUP BY city,country,country_flag

$sql_logs = "INSERT INTO logs (ip_address, country, country_flag, local_time, city, web, latitude, longitude ) VALUES (?,?,?,?,?,?,?,?)";
$stm_logs = $conn->prepare($sql_logs);
$timestamp = date('Y-m-d H:i:s', strtotime('2 hour'));
$stm_logs->execute([$_SESSION["ip"]["ip"], $_SESSION["ip"]["country_name"], $_SESSION["ip"]["location"]["country_flag"], $timestamp, $_SESSION["ip"]["city"], "statistika", $_SESSION["ip"]["latitude"], $_SESSION["ip"]["longitude"]]);

?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
          integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
          crossorigin=""/>
    <link rel="stylesheet" type="text/css" href="styles/style.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
            integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
            crossorigin=""></script>


</head>
<body>



<section>
    <p data-item='Genšor'>Genšor</p>
    <nav>
        <ul class="menuItems">
            <li><a href='index.php' data-item='Predpoveď'>Predpoveď</a></li>
            <li><a href='location.php' data-item='Poloha'>Poloha</a></li>
            <li><a href='stats.php' data-item='Štatistika'>Štatistika</a></li>

        </ul>
    </nav>

</section>
<div class="card bg-light">
    <article class="card-body mx-auto" style="max-width: 1000px;">
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Vlajka</th>
                <th scope="col">Krajina</th>
                <th scope="col">Unikátne návštevy</th>
            </tr>
            </thead>
            <tbody>
        <?php
        $sql_country = "SELECT country,COUNT(total_visits) FROM (SELECT DISTINCT country,ip_address,country_flag,city, DATE(local_time) as day,COUNT(DISTINCT country) AS total_visits FROM logs GROUP BY DATE(local_time),ip_address, country,country_flag,city) as trala GROUP BY country";
        $stm_country = $conn->prepare($sql_country);
        $stm_country->execute();
        $countries = $stm_country->fetchAll(PDO::FETCH_ASSOC);
        foreach ($countries as $country){
            $sql_flag = "SELECT country_flag FROM logs WHERE country = '{$country["country"]}' LIMIT 1";
            $stm_flag = $conn->prepare($sql_flag);
            $stm_flag->execute();
            $flag = $stm_flag->fetch(PDO::FETCH_ASSOC);
            echo '<tr>';
            echo ' <th scope="row">'. '<img src=' . $flag["country_flag"] . ' height="100"' . '>' .'</th>';

            echo '<td>' . '<button type="button" class="btn btn-link" data-toggle="modal"  data-target=' . '#' .  'modal' .str_replace(' ', '', $country["country"]) . '>'
                . $country["country"] . '</button>';

            ?>

            <div class="modal" id="<?php echo "modal" . str_replace(' ', '', $country["country"]) ;?>">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title"><?php echo $country["country"];?></h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Miesto</th>
                                        <th scope="col">Počet návštev</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sql_city = "SELECT city, sum(total_visits), country, country_flag FROM (SELECT DISTINCT ip_address, country,country_flag,city, DATE(local_time) as day,COUNT( DISTINCT ip_address) AS total_visits  FROM logs GROUP BY DATE(local_time),ip_address, country,country_flag,city)  AS lala WHERE country = '{$country["country"]}'  GROUP BY city,country,country_flag";
                                $stm_city = $conn->prepare($sql_city);
                                $stm_city->execute();
                                $cities = $stm_city->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($cities as $city) {
                                    echo '<tr>';
                                    echo '<th scope="row">' . $city["city"] .'</th>';
                                    echo '<td>' . $city["sum(total_visits)"] .'</td>';
                                    echo '</tr>';
                                }

                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            echo '</td>';
            echo '<td>' . $country["COUNT(total_visits)"] . '</td>';
            echo '</tr>';
        }


       ?>
            </tbody>
        </table>


        <div id="mapid" style="height: 500px; width: 800px">
        </div>
        <br>
        <?php
        echo '<h2 >' . "Časy návštev"  .  '</h2>';
        echo '<br>';
        echo '<div class="center">';

        echo '<dl class="row">';
        $sql_time = "SELECT COUNT(local_time) FROM logs where hour(local_time) >= 0 and hour(local_time) <= 5";
        $stm_time = $conn->prepare($sql_time);
        $stm_time->execute();
        $time = $stm_time->fetch(PDO::FETCH_ASSOC);
        echo '<dt class="col-sm-3">00:00:00 - 05:59:59</dt>';
        echo '<dd class="col-sm-9">'.$time["COUNT(local_time)"] .'</dd>';
        $sql_time = "SELECT COUNT(local_time) FROM logs where hour(local_time) >= 6 and hour(local_time) <= 14";
        $stm_time = $conn->prepare($sql_time);
        $stm_time->execute();
        $time = $stm_time->fetch(PDO::FETCH_ASSOC);
        echo '<dt class="col-sm-3">06:00:00 - 14:59:59</dt>';
        echo '<dd class="col-sm-9">'.$time["COUNT(local_time)"] .'</dd>';
        $sql_time = "SELECT COUNT(local_time) FROM logs where hour(local_time) >= 15 and hour(local_time) <= 20";
        $stm_time = $conn->prepare($sql_time);
        $stm_time->execute();
        $time = $stm_time->fetch(PDO::FETCH_ASSOC);
        echo '<dt class="col-sm-3">15:00:00 - 20:59:59</dt>';
        echo '<dd class="col-sm-9">'.$time["COUNT(local_time)"] .'</dd>';
        $sql_time = "SELECT COUNT(local_time) FROM logs where hour(local_time) >= 21 and hour(local_time) <= 23";
        $stm_time = $conn->prepare($sql_time);
        $stm_time->execute();
        $time = $stm_time->fetch(PDO::FETCH_ASSOC);
        echo '<dt class="col-sm-3">21:00:00 - 23:59:59</dt>';
        echo '<dd class="col-sm-9">'.$time["COUNT(local_time)"] .'</dd>';
        echo '</dl>';
        echo '</div>';
        echo '<br>';

        echo '<h2 >' . "Návštevy podstránok"  .  '</h2>';
        echo '<br>';


        echo '<dl class="row">';
        $sql_web = "SELECT COUNT(web) FROM logs where web = 'predpoved'";
        $stm_web = $conn->prepare($sql_web);
        $stm_web->execute();
        $web = $stm_web->fetch(PDO::FETCH_ASSOC);
        echo '<dt class="col-sm-3">Predpoveď</dt>';
        echo '<dd class="col-sm-9">'.$web["COUNT(web)"] .'</dd>';
        $sql_web = "SELECT COUNT(web) FROM logs where web = 'poloha'";
        $stm_web = $conn->prepare($sql_web);
        $stm_web->execute();
        $web = $stm_web->fetch(PDO::FETCH_ASSOC);
        echo '<dt class="col-sm-3">Poloha</dt>';
        echo '<dd class="col-sm-9">'.$web["COUNT(web)"] .'</dd>';
        $sql_web = "SELECT COUNT(web) FROM logs where web = 'statistika'";
        $stm_web = $conn->prepare($sql_web);
        $stm_web->execute();
        $web = $stm_web->fetch(PDO::FETCH_ASSOC);
        echo '<dt class="col-sm-3">Štatistika</dt>';
        echo '<dd class="col-sm-9">'.$web["COUNT(web)"] .'</dd>';
        echo '</dl>';
        

        ?>
    </article>
</div>


<script>
    document.addEventListener('DOMContentLoaded' , (e) => {

        var mymap = L.map('mapid').setView([48.181999206543, 17.093669891357], 4);
        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 18,
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
            accessToken: 'pk.eyJ1Ijoiam96a29hbWF0ZXIiLCJhIjoiY2tndzc4b2o5MDVhYzJ4cWMycXo4anpsaiJ9.kua06mfqnp4LVsWI-hGVvg'
        }).addTo(mymap);
        var myMarker;
        <?php

        $sql_points = "SELECT DISTINCT city,latitude,longitude FROM logs";
        $stm_points = $conn->prepare($sql_points);
        $stm_points->execute();
        $points = $stm_points->fetchALL(PDO::FETCH_ASSOC);
        foreach ($points as $point) {



        ?>
        myMarker = L.marker(["<?php echo $point["latitude"]; ?>", "<?php echo $point["longitude"]; ?>"]).bindPopup("<?php echo $point["city"]; ?>").addTo(mymap);
        <?php
        }
        ?>

    });


</script>
</body>
</html>
