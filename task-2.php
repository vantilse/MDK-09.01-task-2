<?php
$host = "localhost";
$database = "school_management";
$user = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;database=$database;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Соединение с базой данных успешно.<br>";
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'add_student':
            $name = trim($_POST['name'] ?? '');
            if (!empty($name)) {
                $stmt = $pdo->prepare("INSERT INTO students (name) VALUES (:name)");
                $stmt->execute(['name' => $name]);
                echo "Студент добавлен!<br>";
            } else {
                echo "Ошибка: имя студента не может быть пустым.<br>";
            }
            break;

        case 'add_group':
            $name = trim($_POST['name'] ?? '');
            if (!empty($name)) {
                $stmt = $pdo->prepare("INSERT INTO groups (name) VALUES (:name)");
                $stmt->execute(['name' => $name]);
                echo "Группа добавлена!<br>";
            } else {
                echo "Ошибка: имя группы не может быть пустым.<br>";
            }
            break;

        case 'assign_group':
            $student_id = $_POST['student_id'] ?? '';
            $group_id = $_POST['group_id'] ?? '';
            if (!empty($student_id) && !empty($group_id)) {
                $stmt = $pdo->prepare("UPDATE students SET group_id = :group_id WHERE id = :student_id");
                $stmt->execute(['student_id' => $student_id, 'group_id' => $group_id]);
                echo "Студент привязан к группе!<br>";
            } else {
                echo "Ошибка: идентификаторы студента и группы не могут быть пустыми.<br>";
            }
            break;

        case 'add_course':
            $name = trim($_POST['name'] ?? '');
            $teacher_id = $_POST['teacher_id'] ?? '';
            if (!empty($name) && !empty($teacher_id)) {
                $stmt = $pdo->prepare("INSERT INTO courses (name, teacher_id) VALUES (:name, :teacher_id)");
                $stmt->execute(['name' => $name, 'teacher_id' => $teacher_id]);
                echo "Курс добавлен!<br>";
            } else {
                echo "Ошибка: имя курса и идентификатор преподавателя не могут быть пустыми.<br>";
            }
            break;

        default:
            echo "Ошибка: неизвестное действие.<br>";
            break;
    }}

function showStudents($pdo) {
    try {
        $students = $pdo->query("SELECT * FROM students")->fetchAll(PDO::FETCH_ASSOC);
        echo "<h3>Список студентов</h3>";
        echo "<table border='1'><tr><th>ID</th><th>Имя</th><th>ID группы</th></tr>";
        foreach ($students as $student) {
            echo "<tr><td>{$student['id']}</td><td>{$student['name']}</td><td>{$student['group_id']}</td></tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo "Ошибка при получении списка студентов: " . $e->getMessage();
    }
}

function showGroups($pdo) {
    try {
        $groups = $pdo->query("SELECT * FROM groups")->fetchAll(PDO::FETCH_ASSOC);
        echo "<h3>Список групп</h3>";
        echo "<table border='1'><tr><th>ID</th><th>Название</th></tr>";
        foreach ($groups as $group) {
            echo "<tr><td>{$group['id']}</td><td>{$group['name']}</td></tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo "Ошибка при получении списка групп: " . $e->getMessage();
    }}

function showStudentsWithGroups($pdo) {
    try {
        $sql = "SELECT students.name AS student_name, groups.name AS group_name 
                FROM students 
                LEFT JOIN groups ON students.group_id = groups.id";
        $result = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        echo "<h3>Студенты с их группами</h3>";
        echo "<table border='1'><tr><th>Студент</th><th>Группа</th></tr>";
        foreach ($result as $row) {
            echo "<tr><td>{$row['student_name']}</td><td>{$row['group_name']}</td></tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo "Ошибка при получении студентов с группами: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';}

    switch ($action) {
        case 'register_course':
            $student_id = $_POST['student_id'] ?? '';
            $course_id = $_POST['course_id'] ?? '';
            if ($student_id && $course_id) {
                $stmt = $pdo->prepare("INSERT INTO student_courses (student_id, course_id) VALUES (:student_id, :course_id)");
                $stmt->execute(['student_id' => $student_id, 'course_id' => $course_id]);
                echo "Студент зарегистрирован на курс!";
            } else {
                echo "Ошибка: ID студента и ID курса не могут быть пустыми.";
            }
            break;

        case 'add_course':
            $name = $_POST['name'] ?? '';
            $teacher_id = $_POST['teacher_id'] ?? '';
            if ($name && $teacher_id) {
                $stmt = $pdo->prepare("INSERT INTO courses (name, teacher_id) VALUES (:name, :teacher_id)");
                $stmt->execute(['name' => $name, 'teacher_id' => $teacher_id]);
                echo "Курс добавлен!
";
            } else {
                echo "Ошибка: Название курса и ID преподавателя не могут быть пустыми.
";
            }
            break;
        
        case 'add_teacher':
            $name = $_POST['name'] ?? '';
            if ($name) {
                $stmt = $pdo->prepare("INSERT INTO teachers (name) VALUES (:name)");
                $stmt->execute(['name' => $name]);
                echo "Преподаватель добавлен!
";
            } else {
                echo "Ошибка: Имя преподавателя не может быть пустым.
";
            }
            break;

        case 'delete_student':
            $student_id = $_POST['student_id'] ?? '';
            if ($student_id) {
                $stmt = $pdo->prepare("SELECT * FROM students WHERE id = :id");
                $stmt->execute(['id' => $student_id]);
                $student = $stmt->fetch();

                if ($student) {
                    $stmt = $pdo->prepare("DELETE FROM students WHERE id = :id");
                    $stmt->execute(['id' => $student_id]);
                    echo "Студент удален!
";
                } else {
                    echo "Ошибка: Студент с таким ID не найден.
";
                }
            } else {
                echo "Ошибка: ID студента не может быть пустым.
";
            }
            break;

        case 'update_student_name':
            $student_id = $_POST['student_id'] ?? '';
            $new_name = $_POST['new_name'] ?? '';

            if ($student_id && $new_name) {
                $stmt = $pdo->prepare("SELECT * FROM students WHERE id = :id");
                $stmt->execute(['id' => $student_id]);
                $student = $stmt->fetch();

                if ($student) {
                    $stmt = $pdo->prepare("UPDATE students SET name = :name WHERE id = :id");
                    $stmt->execute(['name' => $new_name, 'id' => $student_id]);
                    echo "Имя студента обновлено!
";
                } else {
                    echo "Ошибка: Студент с таким ID не найден.
";
                }
            } else {
                echo "Ошибка: ID студента и новое имя не могут быть пустыми.
";
            }
            break;

        case 'search_student_by_name':
            $student_name = $_POST['student_name'] ?? '';
            
            if ($student_name) {
                $stmt = $pdo->prepare("SELECT students.name AS student_name, groups.name AS group_name 
                                        FROM students 
                                        LEFT JOIN groups ON students.group_id = groups.id 
                                        WHERE students.name LIKE :name");
                $stmt->execute(['name' => "%" . $student_name . "%"]);
                $students = $stmt->fetchAll();

                if ($students) {
                    echo "<h3>Результаты поиска:</h3>";
                    echo "<table border='1'>
                            <tr>
                                <th>Имя студента</th>
                                <th>Группа</th>
                            </tr>";
                    foreach ($students as $student) {
                        echo "<tr>
                                <td>{$student['student_name']}</td>
                                  <td>" . ($student['group_name'] ?: 'Без группы') . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "Студент с таким именем не найден.<br>";
    }
    break;
    }

    case 'search_student_by_name':
        $course_name = trim($_POST['course_name'] ?? '');
    
        if (empty($course_name)) {
            echo "Ошибка: Название курса не может быть пустым.<br>";
            break;
        }
    
        $stmt = $pdo->prepare("SELECT courses.id AS course_id, courses.name AS course_name, students.name AS student_name
                               FROM courses
                               LEFT JOIN student_courses ON courses.id = student_courses.course_id
                               LEFT JOIN students ON student_courses.student_id = students.id
                               WHERE courses.name LIKE :course_name");
        $stmt->execute(['course_name' => "%" . $course_name . "%"]);
        $students_in_course = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        if ($students_in_course) {
            echo "<h3>Студенты, зарегистрированные на курс:</h3>";
            echo "<table border='1'>
                    <tr>
                        <th>Курс</th>
                        <th>Студенты</th>
                    </tr>";
            foreach ($students_in_course as $row) {
                echo "<tr>
                        <td>{$row['course_name']}</td>
                        <td>" . ($row['student_name'] ?: 'Нет студентов') . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "Курс с таким названием не найден или на курс не зарегистрированы студенты.<br>";
        }
        break;

        case 'delete_course':
            $course_id = trim($_POST['course_id'] ?? '');
        
            if (empty($course_id)) {
                echo "Ошибка: Выберите курс для удаления.<br>";
                break;
            }
        
            $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = :course_id");
            $stmt->execute(['course_id' => $course_id]);
            $course = $stmt->fetch(PDO::FETCH_ASSOC);
        
            if ($course) {
                $stmt = $pdo->prepare("DELETE FROM courses WHERE id = :course_id");
                $stmt->execute(['course_id' => $course_id]);
                echo "Курс успешно удален вместе с его регистрациями.<br>";
            } else {
                echo "Ошибка: Курс с таким ID не найден.<br>";
            }
            break;
        }

            function showCoursesWithStudentCount($pdo) {
                $sql = "SELECT courses.name AS course_name, COUNT(student_courses.student_id) AS student_count 
                        FROM courses 
                        LEFT JOIN student_courses ON courses.id = student_courses.course_id 
                        GROUP BY courses.name";
                
                $result = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
                displayTable($result, ['Курс', 'Количество студентов'], 'Курсы и количество студентов');
            }
            
            function showTeachersWithCourses($pdo) {
                $sql = "SELECT teachers.name AS teacher_name, courses.name AS course_name 
                        FROM teachers 
                        LEFT JOIN courses ON teachers.id = courses.teacher_id";
                
                $result = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
                displayTable($result, ['Преподаватель', 'Курс'], 'Преподаватели и их курсы');
            }
            
            function showStudentsWithoutGroups($pdo) {
                $sql = "SELECT id, name FROM students WHERE group_id IS NULL";
                
                $result = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
                displayTable($result, ['ID', 'Имя'], 'Студенты без группы');
            }
            
            function displayTable($data, $headers, $title) {
                echo "<h3>{$title}</h3>";
                echo "<table border='1'><tr>";
                
                foreach ($headers as $header) {
                    echo "<th>{$header}</th>";
                }
                echo "</tr>";
                
                if ($data) {
                    foreach ($data as $row) {
                        echo "<tr>";
                        foreach ($row as $cell) {
                            echo "<td>{$cell}</td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='" . count($headers) . "'>Нет данных для отображения.</td></tr>";
                }
                
                echo "</table>";
            }

            function getOptions($pdo, $table, $id_field, $name_field) {
                try {
                    $stmt = $pdo->prepare("SELECT $id_field, $name_field FROM $table");
                    $stmt->execute();
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    echo "Ошибка при получении данных: " . $e->getMessage();
                    return [];
                }
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'filter_students_by_group') {
                $group_id = trim($_POST['group_id'] ?? '');
            
                if ($group_id) {
                    try {
                        $stmt = $pdo->prepare("SELECT students.name AS student_name, groups.name AS group_name 
                                                FROM students 
                                                LEFT JOIN groups ON students.group_id = groups.id
                                                WHERE students.group_id = :group_id");
                        $stmt->execute(['group_id' => $group_id]);
                        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
                        if ($students) {
                            echo "<h3>Студенты из группы: {$group_id}</h3>";
                            echo "<table border='1'>
                                    <tr>
                                        <th>Имя студента</th>
                                        <th>Группа</th>
                                    </tr>";
            
                            foreach ($students as $student) {
                                echo "<tr>
                                        <td>{$student['student_name']}</td>
                                        <td>{$student['group_name']}</td>
                                      </tr>";
                            }
            
                            echo "</table>";
                        } else {
                            echo "Нет студентов в выбранной группе.";
                        }
                    } catch (PDOException $e) {
                        echo "Ошибка при фильтрации студентов: " . $e->getMessage();
                    }
                } else {
                    echo "Пожалуйста, выберите группу.";
                }
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'filter_students_multiple_courses') {
                try {
                    $sql = "SELECT students.name AS student_name, COUNT(student_courses.course_id) AS course_count 
                            FROM students 
                            JOIN student_courses ON students.id = student_courses.student_id 
                            GROUP BY students.id 
                            HAVING course_count > 1";
                    
                    $stmt = $pdo->query($sql);
                    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
                    if ($students) {
                        echo "<h3>Студенты, зарегистрированные на несколько курсов:</h3>";
                        echo "<table border='1'>
                                <tr>
                                    <th>Имя студента</th>
                                    <th>Количество курсов</th>
                                </tr>";
            
                        foreach ($students as $student) {
                            echo "<tr>
                                    <td>{$student['student_name']}</td>
                                    <td>{$student['course_count']}</td>
                                  </tr>";
                        }
            
                        echo "</table>";
                    } else {
                        echo "Нет студентов, зарегистрированных на несколько курсов.";
                    }
                } catch (PDOException $e) {
                    echo "Ошибка при получении данных: " . $e->getMessage();
                }}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление школой</title>
    <style>
        * {
            font-family: Helvetica, sans-serif;
            box-sizing: border-box;
        }
        body {
            margin: 20px;
            padding: 20px;
            background-color: #f9f9f9;
        }
        h2 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

    <h2>Поиск студента по имени</h2>
    <form method="POST">
        <input type="hidden" name="action" value="search_student_by_name">
        <label for="student_name">Имя студента:</label>
        <input type="text" id="student_name" name="student_name" required>
        <button type="submit">Найти студента</button>
    </form>

    <h2>Поиск курса по названию</h2>
    <form method="POST">
        <input type="hidden" name="action" value="search_course_by_name">
        <label for="course_name">Название курса:</label>
        <input type="text" id="course_name" name="course_name" required>
        <button type="submit">Найти курс</button>
    </form>

    <h2>Добавить студента</h2>
    <form method="POST">
        <input type="hidden" name="action" value="add_student">
        <input type="text" name="name" placeholder="Имя студента" required>
        <button type="submit">Добавить</button>
    </form>

    <h2>Удалить студента</h2>
    <form method="POST">
        <input type="hidden" name="action" value="delete_student">
        <label for="student_id">ID студента:</label>
        <input type="number" id="student_id" name="student_id" required>
        <button type="submit">Удалить студента</button>
    </form>

    <h2>Обновить имя студента</h2>
    <form method="POST">
        <input type="hidden" name="action" value="update_student_name">
        <label for="update_student_id">ID студента:</label>
        <input type="number" id="update_student_id" name="student_id" required>
        <label for="new_name">Новое имя студента:</label>
        <input type="text" id="new_name" name="new_name" required>
        <button type="submit">Обновить имя</button>
    </form>

    <h2>Добавить нового преподавателя</h2>
    <form method="POST">
        <input type="hidden" name="action" value="add_teacher">
        <label for="teacher_name">Имя преподавателя:</label>
        <input type="text" id="teacher_name" name="name" required>
        <button type="submit">Добавить преподавателя</button>
    </form>

    <h2>Добавить студента</h2>
    <form method="POST">
        <input type="hidden" name="action" value="add_student">
        <input type="text" name="name" placeholder="Имя студента" required>
        <button type="submit">Добавить</button>
    </form>

    <h2>Удалить студента</h2>
    <form method="POST">
        <input type="hidden" name="action" value="delete_student">
        <label for="student_id">ID студента:</label>
        <input type="number" id="student_id" name="student_id" required>
        <button type="submit">Удалить студента</button>
    </form>

    <h2>Обновить имя студента</h2>
    <form method="POST">
        <input type="hidden" name="action" value="update_student_name">
        <label for="update_student_id">ID студента:</label>
        <input type="number" id="update_student_id" name="student_id" required>
        <label for="new_name">Новое имя студента:</label>
        <input type="text" id="new_name" name="new_name" required>
        <button type="submit">Обновить имя</button>
    </form>

    <h2>Добавить нового преподавателя</h2>
    <form method="POST">
        <input type="hidden" name="action" value="add_teacher">
        <label for="teacher_name">Имя преподавателя:</label>
        <input type="text" id="teacher_name" name="name" required>
        <button type="submit">Добавить преподавателя</button>
    </form>

    <h2>Добавить студента</h2>
    <form method="POST">
        <input type="hidden" name="action" value="add_student">
        <input type="text" name="name" placeholder="Имя студента" required>
        <button type="submit">Добавить</button>
    </form>

    <h2>Удалить студента</h2>
    <form method="POST">
        <input type="hidden" name="action" value="delete_student">
        <label for="student_id">ID студента:</label>
        <input type="number" id="student_id" name="student_id" required>
        <button type="submit">Удалить студента</button>
    </form>

    <h2>Обновить имя студента</h2>
    <form method="POST">
        <input type="hidden" name="action" value="update_student_name">
        <label for="update_student_id">ID студента:</label>
        <input type="number" id="update_student_id" name="student_id" required>
        <label for="new_name">Новое имя студента:</label>
        <input type="text" id="new_name" name="new_name" required>
        <button type="submit">Обновить имя</button>
    </form>

    <h2>Добавить нового преподавателя</h2>
    <form method="POST">
        <input type="hidden" name="action" value="add_teacher">
        <label for="teacher_name">Имя преподавателя:</label>
        <input type="text" id="teacher_name" name="name" required>
        <button type="submit">Добавить преподавателя</button>
    </form>
    
    <h2>Регистрация студента на курс</h2>
    <form method="POST">
        <input type="hidden" name="action" value="register_course">
        <label for="student_id">Студент:</label>
        <select name="student_id" id="student_id" required>
            <option value="" disabled selected>Выберите студента</option>

            <?php
            $students = getOptions($pdo, 'students', 'id', 'name');
            foreach ($students as $student) {
                echo "<option value=\"{$student['id']}\">{$student['name']}</option>";
            }
            ?>

        </select>
        
        <label for="course_id">Курс:</label>
        <select name="course_id" id="course_id" required>
            <option value="" disabled selected>Выберите курс</option>

            <?php
            $courses = getOptions($pdo, 'courses', 'id', 'name');
            foreach ($courses as $course) {
                echo "<option value=\"{$course['id']}\">{$course['name']}</option>";
            }
            ?>

        </select>
        
        <button type="submit">Зарегистрировать</button>
    </form>

    <h2>Информация о курсах и студентах</h2>
    <?php
    showCoursesWithStudentCount($pdo);
    showTeachersWithCourses($pdo);
    showStudentsWithoutGroups($pdo);
    showTeachersWithStudentCount($pdo);
    showStudents($pdo);
    showGroups($pdo);
    showStudentsWithGroups($pdo);
    ?>

</body>
</html>
