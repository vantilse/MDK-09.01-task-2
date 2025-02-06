<?php
$jsonData = file_get_contents('weather.json');

$weatherData = json_decode($jsonData, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die('Ошибка при декодировании JSON: ' . json_last_error_msg());
}

$hotCities = array_filter($weatherData, function($weather) {
    return $weather['temperature'] > 20;
});

foreach ($hotCities as $city) {
    echo 'Город: ' . $city['city'] . ', Температура: ' . $city['temperature'] . '°C, Состояние: ' . $city['condition'] . PHP_EOL;
}
?>