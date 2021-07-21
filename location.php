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
$stm_logs->execute([$_SESSION["ip"]["ip"], $_SESSION["ip"]["country_name"], $_SESSION["ip"]["location"]["country_flag"], $timestamp, $_SESSION["ip"]["city"], "poloha", $_SESSION["ip"]["latitude"], $_SESSION["ip"]["longitude"]]);

?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="styles/style.css">
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
echo '<h2 style="text-align:center">' . "Údaje pre vašu IP "  .  '</h2>';
echo '<br>';
echo '<div class="center">';
echo '<div class="">';
echo '<dl class="row">';
echo '<dt class="col-sm-3">IP adresa</dt>';
echo '<dd class="col-sm-9">'. $_SESSION["ip"]["ip"] .'</dd>';
echo '<dt class="col-sm-3">GPS súradnice</dt>';
echo '<dd class="col-sm-9">'. $_SESSION["ip"]["latitude"] . " ;" . $_SESSION["ip"]["longitude"]  .'</dd>';
echo '<dt class="col-sm-3">Miesto</dt>';
echo '<dd class="col-sm-9">'. $_SESSION["ip"]["city"] .'</dd>';
echo '<dt class="col-sm-3">Hlavné mesto lokality</dt>';
echo '<dd class="col-sm-9">'. $_SESSION["ip"]["location"]["capital"]  .'</dd>';
echo '</dl>';
echo '</div>';
echo '</div>';
?>
    </article>
</div>


</body>
</html>

