
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kota Randang</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <div class="login">
        <form action="login.php" method="post">
            <h1>Login</h1>
            <hr>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="example@gmail.com" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Password" autocomplete="off" required>
            <button type="submit" name="login">Login</button>
            <p>
                <a href="#">Forgot Password?</a>
            </p>
        </form>

        </div>
        <div class="right">
            <img src="konser.jpg" alt="">
        </div>
    </div>
</body>
</html>