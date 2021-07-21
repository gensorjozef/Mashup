

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

$sql_logs = "INSERT INTO logs (ip_address, country, country_flag, local_time, city, web, latitude, longitude ) VALUES (?,?,?,?,?,?,?,?)";
$stm_logs = $conn->prepare($sql_logs);
$timestamp = date('Y-m-d H:i:s', strtotime('2 hour'));
$stm_logs->execute([$_SESSION["ip"]["ip"], $_SESSION["ip"]["country_name"], $_SESSION["ip"]["location"]["country_flag"], $timestamp, $_SESSION["ip"]["city"], "predpoved", $_SESSION["ip"]["latitude"], $_SESSION["ip"]["longitude"]]);

$lat = $_SESSION["ip"]["latitude"];
$lon = $_SESSION["ip"]["longitude"];
$access_key_weather = "bc54d864b4561ecacd7169d040fa3b6d";
$url_weather = "https://api.openweathermap.org/data/2.5/onecall?lat=" . "{$lat}" ."&lon=" . "{$lon}" . "&exclude=minutely&lang=sk&units=metric&appid=" .$access_key_weather;

$weather = json_decode(file_get_contents($url_weather),true);

?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles/style.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

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
    <article class="card-body mx-auto" style="max-width: 1400px;">
<?php
echo '<h2 style="text-align:center">' . "Predpoveď pre " . $_SESSION["ip"]["city"] . ", " . $_SESSION["ip"]["country_name"]. "/" . $_SESSION["ip"]["continent_code"] .  '</h2>';
echo '<br>';
echo '<h4 style="text-align:center">' . "Aktuálna predpoveď" . '</h4>';
echo '<div class="center">';
echo '<div class="col-sm-6 col-md-4 center">';
echo '<dl class="row">';
echo '<dt class="col-sm-3">' . '<img src=' . 'http://openweathermap.org/img/w/' . $weather["current"]["weather"][0]["icon"] . '.png' . '>' .  '</dt>';
echo '<dd class="col-sm-9"></dd>';
echo '<dt class="col-sm-3">Dátum</dt>';
echo '<dd class="col-sm-9">'. gmdate("M d Y H:i:s", $weather["current"]["dt"] + 7200) .'</dd>';
echo '<dt class="col-sm-3">Priemerná Teplota</dt>';
echo '<dd class="col-sm-9">'. $weather["current"]["temp"] . ' °C' .'</dd>';
echo '<dt class="col-sm-3">Pocitová Teplota</dt>';
echo '<dd class="col-sm-9">'. $weather["current"]["feels_like"] . ' °C' .'</dd>';
echo '<dt class="col-sm-3">Rýchlosť vetra</dt>';
echo '<dd class="col-sm-9">'. $weather["current"]["wind_speed"] . ' m/s' .'</dd>';
echo '<dt class="col-sm-3">Zhrnutie</dt>';
echo '<dd class="col-sm-9">'. $weather["current"]["weather"][0]["description"] .'</dd>';
echo '</dl>';
echo '</div>';
echo '</div>';
echo '<br>';
echo '<h4 style="text-align:center">' . "Predpoveď počasia na týždeň" . '</h4>';
echo '<br>';
echo '<dl class="row">';
foreach ($weather["daily"] as $index => $item) {
    if ($index < 7){

        echo '<div class="col-sm-12 col-md-4">';
        echo '<dl class="row">';
        echo '<dt class="col-sm-3">' . '<img src=' . 'http://openweathermap.org/img/w/' . $item["weather"][0]["icon"] . '.png' . '>' .  '</dt>';
        echo '<dd class="col-sm-9"></dd>';
        echo '<dt class="col-sm-3">Dátum</dt>';
        echo '<dd class="col-sm-9">'. gmdate("M d Y H:i:s", $item["dt"] + 7200) .'</dd>';
        echo '<dt class="col-sm-3">Priemerná Teplota</dt>';
        echo '<dd class="col-sm-9">'. $item["temp"]["day"] . ' °C' .'</dd>';
        echo '<dt class="col-sm-3">Pocitová Teplota</dt>';
        echo '<dd class="col-sm-9">'. $item["feels_like"]["day"] . ' °C' .'</dd>';
        echo '<dt class="col-sm-3">Rýchlosť vetra</dt>';
        echo '<dd class="col-sm-9">'. $item["wind_speed"] . ' m/s' .'</dd>';
        echo '<dt class="col-sm-3">Zhrnutie</dt>';
        echo '<dd class="col-sm-9">'. $item["weather"][0]["description"] .'</dd>';
        echo '</dl>';
        echo '</div>';
    }
}
echo '</dl>';

?>
    </article>
</div>

</body>
</html>

