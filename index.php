<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Компьютерные аксессуары</title>
    <style>
        body {
            font-family: 'Times New Roman', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e9ecef;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
        }
        h2, h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        input[type="text"]:focus,
        input[type="number"]:focus {
            border-color: #007bff;
            outline: none;
        }
        button {
            padding: 12px 20px;
            background-color: yellow; 
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease; 
        }
        button:hover {
            background-color: darkgreen; 
        }
        .message {
            text-align: center;
            margin-bottom: 20px;
            color: blue;
        }
        .error {
            text-align: center;
            margin-bottom: 20px;
            color: red;
        }
    </style>
</head>
<body>
<?php
session_start();

// Настройки подключения к базе данных
$servername = "localhost"; 
$username = "root"; 
$password = "";
$dbname = "ekz"; 

// Создание подключения к базе данных
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка на наличие ошибок подключения
if ($conn->connect_error) {
    die("<div class='container'><p class='error'>Ошибка подключения: " . htmlspecialchars($conn->connect_error) . "</p></div>");
}

$message = '';
$error = '';

// Обработка формы добавления аксессуара
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_accessory'])) {
    // Получение и экранирование данных из формы
    $name = $conn->real_escape_string(trim($_POST['name']));
    $brand = $conn->real_escape_string(trim($_POST['brand']));
    $category = $conn->real_escape_string(trim($_POST['category']));
    $year_of_manufacture = (int)$_POST['year_of_manufacture'];
    $model_number = $conn->real_escape_string(trim($_POST['model_number']));
    $quantity_in_stock = (int)$_POST['quantity_in_stock'];

    // Подготовка SQL-запроса для вставки данных
    // ** Измените 'accessories' на имя новой таблицы, если необходимо **
    $stmt = $conn->prepare("INSERT INTO accessories (name, brand, category, year_of_manufacture, model_number, quantity_in_stock) VALUES (?, ?, ?, ?, ?, ?)");
    
    if ($stmt) {
        // Привязка параметров к SQL-запросу
        // ** Измените типы и количество параметров в зависимости от новой таблицы **
        $stmt->bind_param("ssssis", $name, $brand, $category, $year_of_manufacture, $model_number, $quantity_in_stock);
        
        // Выполнение запроса
        if ($stmt->execute()) {
            $_SESSION['message'] = "Аксессуар успешно добавлен!";
        } else {
            $_SESSION['error'] = "Ошибка при добавлении аксессуара: " . htmlspecialchars($stmt->error);
        }
        // Закрытие подготовленного запроса
        $stmt->close();
    } else {
        $_SESSION['error'] = "Ошибка подготовки запроса: " . htmlspecialchars($conn->error);
    }

    // Перенаправление на ту же страницу для отображения сообщения
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Обработка сообщений об успехе или ошибках
if (isset($_SESSION['message'])) {
    $message = htmlspecialchars($_SESSION['message']);
    unset($_SESSION['message']);
}

if (isset($_SESSION['error'])) {
    $error = htmlspecialchars($_SESSION['error']);
    unset($_SESSION['error']);
}
?>

<div class="container">
    <h2>Список компьютерных аксессуаров</h2>

    <?php if ($message): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <?php
    // Запрос для получения всех аксессуаров из базы данных
    // ** Измените 'accessories' на имя новой таблицы, если необходимо **
    $result = $conn->query("SELECT * FROM accessories");

    if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                <th>ID</th>
                    <th>Название</th>
                    <th>Бренд</th>
                    <th>Категория</th>
                    <th>Год производства</th>
                    <th>Номер модели</th>
                    <th>Количество на складе</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <!-- Измените имена полей в зависимости от структуры новой таблицы -->
                        <td><?= htmlspecialchars($row['id_accessory']) ?></td> <!-- Изменить 'id_accessory' на новое имя поля -->
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['brand']) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td><?= htmlspecialchars($row['year_of_manufacture']) ?></td>
                        <td><?= htmlspecialchars($row['model_number']) ?></td>
                        <td><?= htmlspecialchars($row['quantity_in_stock']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Нет аксессуаров для отображения.</p>
    <?php endif; ?>

    <?php 
    // Закрытие соединения с базой данных
    $conn->close(); 
    ?>
</div>

<div class="container">
    <h3>Добавить новый аксессуар</h3>
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
        <div class="form-group">
            <label for="name">Название:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="brand">Бренд:</label>
            <input type="text" id="brand" name="brand" required>
        </div>
        <div class="form-group">
            <label for="category">Категория:</label>
            <input type="text" id="category" name="category" required>
        </div>
        <div class="form-group">
            <label for="year_of_manufacture">Год производства:</label>
            <input type="number" id="year_of_manufacture" name="year_of_manufacture" required min="1900" max="<?= date('Y') ?>"> <!--здесь можно поменять min-->
        </div>
        <div class="form-group">
            <label for="model_number">Номер модели:</label>
            <input type="text" id="model_number" name="model_number" required>
        </div>
        <div class="form-group">
            <label for="quantity_in_stock">Количество на складе:</label>
            <input type="number" id="quantity_in_stock" name="quantity_in_stock" required min="0">
        </div>
        <!-- Кнопка отправки формы -->
        <button type="submit" name="add_accessory">Добавить аксессуар</button> <!-- Убедитесь, что имя кнопки соответствует обработчику -->
    </form>
</div>


</body>
</html>
