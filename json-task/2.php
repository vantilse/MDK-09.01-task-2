<?php
$jsonData = file_get_contents('employees.json');
$employees = json_decode($jsonData, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die('Ошибка при декодировании JSON: ' . json_last_error_msg());
}

$totalSalary = 0;
$count = count($employees);

foreach ($employees as $employee) {
    $totalSalary += $employee['salary'];
}

$averageSalary = $totalSalary / $count;

$aboveAverageEmployees = array_filter($employees, function($employee) use ($averageSalary) {
    return $employee['salary'] > $averageSalary;
});

foreach ($aboveAverageEmployees as $employee) {
    echo 'Имя: ' . $employee['name'] . ', Должность: ' . $employee['position'] . ', Зарплата: ' . $employee['salary'] . PHP_EOL;
}
?>