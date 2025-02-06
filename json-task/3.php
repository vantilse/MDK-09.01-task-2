<?php
$jsonData = file_get_contents('tasks.json');
$tasks = json_decode($jsonData, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die('Ошибка при декодировании JSON: ' . json_last_error_msg());
}

$incompleteTasks = array_filter($tasks, function($task) {
    return $task['status'] === 'невыполнена';
});

foreach ($incompleteTasks as $task) {
    echo 'Описание: ' . $task['description'] . ', Статус: ' . $task['status'] . PHP_EOL;
}
?>