<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #e9ecef;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 400px;
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
    input[type="password"],
    input[type="date"],
    input[type="tel"] {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        box-sizing: border-box;
        transition: border-color 0.3s ease;
    }

    input[type="text"]:focus,
    input[type="password"]:focus,
    input[type="date"]:focus,
    input[type="tel"]:focus {
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

    .error {
        color: red;
        font-size: 12px;
    }
</style>


    <script>
    function validateForm(event) {
        event.preventDefault();

        let isValid = true;
        let errorMessage = "";

        const familiya = document.getElementById("familiya").value.trim();
        const imya = document.getElementById("imya").value.trim();
        const otchestvo = document.getElementById("otchestvo").value.trim();
        const data_rojd = document.getElementById("data_rojd").value.trim();
        const telephone = document.getElementById("telephone").value.trim();
        const login = document.getElementById("login").value.trim();
        const password = document.getElementById("password").value;

        if (!familiya || !imya || !otchestvo || !data_rojd || !telephone || !login || !password) {
            errorMessage += "Все поля должны быть заполнены.\n";
            isValid = false;
        }

        const nameRegex = /^[A-Za-zА-Яа-яЁё\s]+$/; 
        if (!nameRegex.test(familiya)) {
            errorMessage += "Фамилия не должна содержать цифры или специальные символы.\n";
            isValid = false;
        }
        if (!nameRegex.test(imya)) {
            errorMessage += "Имя не должно содержать цифры или специальные символы.\n";
            isValid = false;
        }
        if (!nameRegex.test(otchestvo)) {
            errorMessage += "Отчество не должно содержать цифры или специальные символы.\n";
            isValid = false;
        }

        const phoneRegex = /^\+?[0-9]{10,15}$/;
        if (!phoneRegex.test(telephone)) {
            errorMessage += "Неверный формат телефона. Должен содержать от 10 до 15 цифр.\n";
            isValid = false;
        }

        if (!isValid) {
            alert(errorMessage);
            return false; 
        }

        document.querySelector('form').submit();
    }
    </script>
</head>
<body>
    <div class="container">
        <h2>Регистрация</h2>
        <form action="register.php" method="POST" onsubmit="validateForm(event)">
            <div class="form-group">
                <label for="familiya">Фамилия:</label>
                <input type="text" id="familiya" name="familiya" required>
                <span class="error"></span>
            </div>
            <div class="form-group">
                <label for="imya">Имя:</label>
                <input type="text" id="imya" name="imya" required>
                <span class="error"></span>
            </div>
            <div class="form-group">
                <label for="otchestvo">Отчество:</label>
                <input type="text" id="otchestvo" name="otchestvo" required>
                <span class="error"></span>
            </div>
            <div class="form-group">
                <label for="data_rojd">Дата рождения:</label>
                <input type="date" id="data_rojd" name="data_rojd" required>
                <span class="error"></span>
            </div>
            <div class="form-group">
                <label for="telephone">Телефон:</label>
                <input type="tel" id="telephone" name="telephone" required pattern="\+?[0-9]{10,15}">
                <span class="error"></span>
            </div>
            <div class="form-group">
                <label for="login">Логин:</label>
                <input type="text" id="login" name="login" required>
                <span class="error"></span>
            </div>
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required minlength="6">
                <span class="error"></span>
            </div>

           <button type="submit">Зарегистрироваться</button> 
        </form>

        <button class="link-button" onclick="window.location.href='login.php'">Уже есть аккаунт? Войти</button> 
    </div>

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
    $familiya = $_POST['familiya'];
    $imya = $_POST['imya'];
    $otchestvo = $_POST['otchestvo'];
    $data_rojd = $_POST['data_rojd'];
    $telephone = $_POST['telephone'];
    $login = $_POST['login'];
    $password = $_POST['password'];

    $sql_check = "SELECT * FROM user WHERE login = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $login);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
       echo "<script>alert('Этот логин уже занят. Пожалуйста, выберите другой.');</script>";
   } else {
       $sql_insert = "INSERT INTO user (familiya, imya, otchestvo, data_rojd, telephone, login, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
       $stmt_insert = $conn->prepare($sql_insert);
       $stmt_insert->bind_param("sssssss", $familiya, $imya, $otchestvo, $data_rojd, $telephone, $login, $password);

       if ($stmt_insert->execute()) {
           echo "<script>alert('Регистрация прошла успешно!'); window.location.href='login.php';</script>";
           exit();
       } else {
           echo "<script>alert('Ошибка при регистрации. Пожалуйста, попробуйте еще раз.');</script>";
       }
       
       $stmt_insert->close();
   }

   $stmt_check->close();
}

$conn->close();
?>
</body>
</html> 
