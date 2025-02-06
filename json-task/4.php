<?php
$jsonData = file_get_contents('books.json');

$books = json_decode($jsonData, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die('Ошибка при декодировании JSON: ' . json_last_error_msg());
}

$recentBooks = array_filter($books, function($book) {
    return $book['year'] > 2000;
});

foreach ($recentBooks as $book) {
    echo 'Название: ' . $book['title'] . ', Автор: ' . $book['author'] . ', Год издания: ' . $book['year'] . PHP_EOL;
}
?>