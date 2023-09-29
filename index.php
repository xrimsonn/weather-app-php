<?php
$apiKey = 'f706043c37cf288ca9e408f9ef66a9c8';
$apiToken = '36ea17a2236a11';
$ip = $_SERVER['REMOTE_ADDR'];

$ipApiUrl = "http://ipinfo.io/{$ip}/json?token={$apiToken}";
$response = file_get_contents($ipApiUrl);
$ipdata = json_decode($response, true);

$latLng = explode(",", $ipdata["loc"]);
$latitude = $latLng[0];
$longitude = $latLng[1];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["cityInput"])) {
    $location = intval($_POST["cityInput"]);
    $weatherApiUrl = "https://api.openweathermap.org/data/2.5/weather?id=$location&appid=$apiKey&units=metric";
  }
} else {
  $weatherApiUrl = "https://api.openweathermap.org/data/2.5/weather?lat=$latitude&lon=$longitude&appid=$apiKey&units=metric";
}


$curl = curl_init($weatherApiUrl);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
curl_close($curl);

$data = json_decode($response, true);

if ($data["cod"] == "200") {
  $name = $data['name'];
  $timezone = intval($data['timezone']) + 14400;
  $temp = $data['main']['temp'];
  $country = $data['sys']['country'];
  $description = ucfirst($data['weather']['0']['description']);
  $lon = $data['coord']['lon'];
  $lat = $data['coord']['lat'];

  $deg = intval($data['wind']['deg']);
  $speed = floatval($data['wind']['speed']) * 3.6;
  $gust = floatval($data['wind']['gust']) * 3.6;

  $feel = $data['main']['feels_like'];
  $max = $data['main']['temp_max'];
  $min = $data['main']['temp_min'];
  $humidity = $data['main']['humidity'];
  $pressure = $data['main']['pressure'];

  $main_desc = $data['weather']['0']['main'];
  $timeUnix = intval($data['dt']);
  $sunriseUnix = intval($data['sys']['sunrise']);
  $susetUnix = intval($data['sys']['sunset']);
  $format = 'H:i';
  $time = date($format, strval($timeUnix + $timezone));
  $sunrise = date($format, strval($sunriseUnix + $timezone));
  $sunset = date($format, strval($susetUnix + $timezone));
} else {
  $name = "unknown";
  $temp = "-.-";
  $country = "?";
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
  <link rel="icon" href="./assets/icon.svg" type="image/png">
  <link rel="stylesheet" href="./assets/style.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
  <meta charset="UTF-8">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>App-Cast</title>
</head>

<body>
  <nav>
    <ul>
      <li id="icon">
        <a href="./index.php" class="contrast" id="title-btn">
          <i class="fa-solid fa-tree fa-2xl"></i>
        </a>
      </li>
      <li id="title">
        <h1>App-Cast</h1>
      </li>
    </ul>
    <ul>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="cityForm" name="cityForm">
        <input type="text" id="cityInput" name="cityInput" placeholder="Search city...">
        <button type="submit" id="submitButton" style="display: none;"></button>
      </form>
      <div role="listbox" id="cityList"></div>
    </ul>

  </nav>

  <main class="container">
    <article id="main-card">

      <header id="temp-header">
        <div class="grid" style="height: 180px;">
          <div id="weather-main">
            <i id="weather-icon" class="fa-solid fa-cloud-sun fa-2xl"></i>
            <hgroup>
              <h2><?php echo $name; ?>, <?php echo $country; ?></h2>
              <h3><?php echo $description; ?> (<?php echo $lon . ", " . $lat; ?>)</h3>
            </hgroup>
            <h2 id="weather-deg"><?php echo $temp; ?>°</h2>
            <i id="weather-icon-media" class="fa-solid fa-cloud-sun fa-2xl"></i>
          </div>
        </div>
      </header>

      <details open>
        <summary>Details</summary>
        <div class="grid">
          <article>
            <header class="card-title">
              <b>Wind & Pressure</b>
            </header>
            <section class="card-body">
              Speed: <?php echo $speed; ?>km/h <br>
              Gust: <?php echo $gust; ?>km/h <br><br>
              <div style="text-align: center;">
                N <br>
                <i style="margin: 1rem;" id="wind-deg" class="fa-solid fa-location-arrow"></i><br>
                S
              </div>
            </section>
          </article>
          <article>
            <header class="card-title">
              <b>Temperature</b>
            </header>
            <section class="card-body">
              Temperature: <?php echo $temp ?>°<br>
              Feels like: <?php echo $feel; ?>°<br>
              Humidity: <?php echo $humidity; ?>%<br>
              Pressure: <?php echo $pressure; ?>hPa <br>
              <br>
              Max Temperature: <?php echo $max; ?>°<br>
              Min Temperature: <?php echo $min; ?>°<br>
            </section>
          </article>
          <article>
            <header class="card-title">
              <b>Info & Time</b>
            </header>
            <section class="card-body">
              City: <?php echo $name . ", " . $country; ?><br>
              Sky: <?php echo $main_desc; ?><br>
              Details: <?php echo $description; ?><br>
              Latitude: <?php echo $lat; ?><br>
              Longitude: <?php echo $lon; ?> <br>
              <br>
              Time: <?php echo $time; ?><br>
              Sunrise: <?php echo $sunrise; ?><br>
              Sunset: <?php echo $sunset; ?>
            </section>
          </article>
        </div>
      </details>
      <footer>
        &copy;2023 José Antonio Rosales <br>
        <a style="margin: 1px;" class="secondary" target="_blank" href="https://www.instagram.com/antonnn_o/"><i class="fa-brands fa-instagram fa-lg"></i></a>
        <a style="margin: 1px;" class="secondary" target="_blank" href="https://github.com/xrimsonn"><i class="fa-brands fa-github fa-lg"></i></a>
        <a style="margin: 1px;" class="secondary" target="_blank" href="https://www.linkedin.com/in/antonio-rosales-207793263/"><i class="fa-brands fa-linkedin fa-lg"></i></a>
      </footer>
    </article>
  </main>

  <article id="loader" aria-busy="true" class="hidden"></article>

  <script src="https://kit.fontawesome.com/a41d3240c2.js" crossorigin="anonymous"></script>
  <script src="./assets/javascript/loader.js"></script>
  <script src="./assets/javascript/cityMenu.js"></script>
  <script>
    const customIcon = document.getElementById('wind-deg');
    const deg = <?php echo ($deg + 135); ?>;

    function rotateIcon(degrees) {
      customIcon.style.transform = `rotate(${degrees}deg)`;
    }

    rotateIcon(deg);
  </script>
</body>

</html>
</body>

</html>