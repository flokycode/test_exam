<!DOCTYPE html>
<html lang="ru">
<head>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #e9ecef;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 300px;
        margin: 100px auto;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #333;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #555;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        box-sizing: border-box;
        transition: border-color 0.3s ease;
    }

    input[type="text"]:focus,
    input[type="password"]:focus {
        border-color: #007bff;
        outline: none;
    }

    button {
        width: 100%;
        padding: 10px;
        background-color: #28a745; 
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease; 
    }

    button:hover {
        background-color: #218838; 
    }

    .link-button {
        margin-top: 15px;
        background-color: #007bff; 
        color: white; 
        border: none; 
        cursor: pointer; 
        padding: 10px; 
        border-radius: 5px; 
        text-align: center; 
        display: block; 
        transition: background-color 0.3s ease; 
    }

    .link-button:hover {
        background-color: #0056b3; 
    }
</style>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
</head>
<body>
    <div class="container">
        <h2>Авторизация</h2>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="login">Логин:</label>
                <input type="text" id="login" name="login" required>
            </div>
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Войти</button>
            <button class='link-button' onclick='window.location.href=`register.php`'>Нет аккаунта? Зарегистрироваться</button>
        </form>
    </div>
</body>
</html>
<?php  
$servername = "localhost"; 
$username = "root";
$password = ""; 
$dbname = "ekz"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE login = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $login, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if ($password === $user['password']) {
            header("Location: index.php");
            exit(); 
        } else {
            echo "Неверный логин или пароль.";
        }
    } else {
        echo "Неверный логин или пароль.";
    }

    $stmt->close();
}

$conn->close();
?>
