<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Погода в городе</title>
</head>
<body>
    <h1>Узнайте погоду в вашем городе</h1>
    <form method="POST" action="">
        <input type="text" name="city" placeholder="Введите название города" required>
        <button type="submit">Показать погоду</button>
    </form>

    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $city = htmlspecialchars($_POST['city']);
            $cityucode = urlencode(htmlspecialchars($_POST['city']));
            $apiKey = '1e2d5af59600cd403b0abac8b65603fc';
            $url = "http://api.openweathermap.org/data/2.5/weather?q={$cityucode}&appid={$apiKey}&lang=ru&units=metric";
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
        
            if ($httpCode == 200) {
                $weatherData = json_decode($response, true);
                $temperature = $weatherData['main']['temp'];
                $condition = $weatherData['weather'][0]['description'];
                echo "<h2>Погода в городе {$city}:</h2>";
                echo "<p>Температура: {$temperature}°C</p>";
                echo "<p>Состояние: {$condition}</p>";
            } else {
                echo "<p>Ошибка: {$httpCode}. Проверьте название города или API ключ.</p>";
            }
        }
    ?>
</body>
</html>