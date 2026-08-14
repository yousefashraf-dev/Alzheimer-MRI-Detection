<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" 
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/login.css">
        <title>Login</title>
        <script src="https://kit.fontawesome.com/63f550a101.js" crossorigin="anonymous"></script>


    </head>
    <body>
        <a class="logo" href="home.php" style=" text-decoration: none;">
         <i class="fa-solid fa-brain" style="color: white;"></i>Main </a>
		 <?php
session_start();

if (isset($_POST['submit'])) {
    include 'conn-db.php';
    $name = trim($_POST['name']);
    $password = $_POST['password'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    $errors = [];
    if (empty($name)) {
        $errors[] = "The name must be written";
    } elseif (strlen($name) > 100) {
        $errors[] = "The name must not be more than 100 characters long";
    }

    if (empty($email)) {
        $errors[] = "Email must be written";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
        $errors[] = "The email is not valid";
    }

    $stm = "SELECT email FROM users WHERE email = ?";
    $q = $con->prepare($stm);
    $q->execute([$email]);
    $data = $q->fetch();

    if ($data) {
        $errors[] = "Email already exists";
    }

    if (empty($password)) {
        $errors[] = "Password must be typed";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must not be less than 6 characters";
    }

    if (empty($errors)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $stm = "INSERT INTO users (name,email,password) VALUES (?, ?, ?)";
        $con->prepare($stm)->execute([$name, $email, $password]);

        $_SESSION['user'] = [
            "name" => $name,
            "email" => $email,
        ];
        header('location:loginn.php');
    }
}

?>
<div class="container" id="container">
	<div class="form-container sign-up-container">
		<form  action="loginn.php" method="post">
		<?php 
        if(isset($errors)){
            if(!empty($errors)){
                foreach($errors as $msg){
                    echo $msg . "<br>";
                }
            }
        }
    ?>
			<h1>Create Account</h1>
			<div class="social-container">
				<a href="https://www.facebook.com" class="social"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                    <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                  </svg></a>
				<a href="https://www.gmail.com" class="social"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-google" viewBox="0 0 16 16">
                    <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"/>
                  </svg></a>
				<a href="https://www.linkedin.com" class="social"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
                    <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/>
                  </svg></a>
			</div>
			<span>or use your email for registration</span>
			<input type="text" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" name="name" placeholder="name">
    <input type="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" name="email" placeholder="email">
    <input type="password" name="password" placeholder="password">
    <input type="submit" name="submit" value="Register">
		</form>
	</div>
	

	<?php

if (isset($_POST['login'])) {
    include 'conn-db.php';
    $password = trim($_POST['password']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    $errors = [];

    if (empty($email)) {
        $errors[] = "Email must be written";
    }

    if (empty($password)) {
        $errors[] = "Password must be typed";
    }

    if (empty($errors)) {
        $stm = "SELECT * FROM users WHERE email = ?";
        $q = $con->prepare($stm);
        $q->execute([$email]);
        $data = $q->fetch();

        if (!$data) {
            $errors[] = "login error";
        } elseif (!password_verify($password, $data['password'])) {
            $errors[] = "login error";
        } else {
            $_SESSION['user'] = [
                "name" => $data['name'],
                "email" => $email,
            ];
            header('location:home.php');
        }
    }
}?>
	<div class="form-container sign-in-container">
		<form action="loginn.php" method="post">
		<?php 
        if(isset($errors)){
            if(!empty($errors)){
                foreach($errors as $msg){
                    echo $msg . "<br>";
                }
            }
        }
    ?>
			<h1>Sign in</h1>
			<div class="social-container">
				<a href="https://www.facebook.com" class="social"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                    <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                  </svg></a>
				<a href="https://www.gmail.com" class="social"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-google" viewBox="0 0 16 16">
                    <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"/>
                  </svg></a>
				<a href="https://www.linkedin.com" class="social"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
                    <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/>
                  </svg></i></a>
			</div>
			<span>or use your account</span>
			<input type="text" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" name="email" placeholder="email">
			<input type="password" name="password" placeholder="password">
			<a href="#">Forgot your password?</a><!---------->

            
			<input type="submit" name="login" value="Login">
		</form>
	</div>
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
				<h1>Welcome Back!</h1>
				<p>To keep connected with us please login with your personal info</p>
				<button class="ghost" id="signIn">Sign In</button>
			</div>
			<div class="overlay-panel overlay-right">
				<h1>Hello, Friend!</h1><!---ask--->
				<p>Enter your personal details and start journey with us</p>
				<button class="ghost" id="signUp">Sign Up </button>
			</div>
		</div>
	</div>
</div>
<br>


<script>
    const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');

signUpButton.addEventListener('click', () => {
	container.classList.add("right-panel-active");
});

signInButton.addEventListener('click', () => {
	container.classList.remove("right-panel-active");
});
</script>
       <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" 
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
       <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    </body>
</html>