<?php
session_start();
 
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}

require_once "config.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
        $stmt->execute([$_POST['username']]);
        $user = $stmt->fetch();
    
        if(strlen($user['password']) == 32 && $user['password'] == md5($password)){
            session_start();
            $_SESSION['loggedin']=true;
            $_SESSION['username']=$username;
            header("Location: welcome.php");
        }elseif(strlen($user['password']) == 20){
            $iterations = 1000;
            $salt = 'abcdefghijklmnop';
            if($user['password'] == hash_pbkdf2("sha256", $password, $salt, $iterations, 20)){
                
                session_start();
                $_SESSION['loggedin']=true;
                $_SESSION['username']=$username;
                header("Location: welcome.php");
            }
        }elseif(strlen($user['password']) == 40){
            $salt = 'abcdefghijklmnop';
            if($user['password'] == sha1($password.$salt)){
                session_start();
                $_SESSION['loggedin']=true;
                $_SESSION['username']=$username;
                header("Location: welcome.php");
            }
        }elseif($user['password'] == $password){
            session_start();
            $_SESSION['loggedin']=true;
            $_SESSION['username']=$username;
            header("Location: welcome.php");
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Kyquni</h2>
        <p>Ju lutem jepini te dhenat e tuaja.</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Emri i perdoruesit</label>
                <input type="text" name="username" class="form-control">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Fjalekalimi</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Kyquni">
            </div>
            <p>Nuk keni llogari? <a href="register.php">Regjistrohuni tani</a>.</p>
        </form>
    </div>
</body>
</html>