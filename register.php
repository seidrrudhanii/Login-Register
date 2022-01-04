<?php

require_once "config.php";

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $user=$_POST['username'];
    
    if(empty(trim($_POST["username"]))){
        $username_err = "Ju lutem jepni nje username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username mund te permbaje vetem shkronja, numra dhe viza.";
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Ju lutem jepni fjalekalimin.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Fjalekalimi duhet te kete se paku 6 karaktere.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Ju lutem konfirmoni fjalekalimin.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Fjalekalimet nuk perputhen.";
        }
    }
    
            if($_POST['flexRadioDefault']== 'plaintext'){
                $param_username = $user;
                $param_password = $password;
            }elseif($_POST['flexRadioDefault']== 'md5'){
                $param_username = $user;
                $param_password = md5($password);
            }elseif($_POST['flexRadioDefault']== 'pbkdf2'){
                $param_username = $user;
                $iterations = 1000;
                $salt = 'abcdefghijklmnop';
                $hash = hash_pbkdf2("sha256", $password, $salt, $iterations, 20);
                $param_password = $hash;

            }elseif($_POST['flexRadioDefault']== 'saltedhash'){
                $param_username = $user;
                $salt = 'abcdefghijklmnop';
                $param_password = sha1($password.$salt);
            }else{
                $param_username = $user;
                $param_password = md5($password);
            }

            $query="INSERT INTO users(username,password) VALUES ('$param_username','$param_password')";
            $conn->exec($query);
        
            header("Location: login.php");
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Regjistrohuni</h2>
        <p>Ju lutemi plotesojeni kete form per te krijuar nje llogari.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Emri i perdoruesit</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Fjalekalimi</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Konfirmojeni fjalekalimin</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Regjistrohuni">
                <input type="reset" class="btn btn-secondary ml-2" value="Rivendos">
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="plaintext" value="plaintext" checked>
                <label class="form-check-label" for="plaintext">
                    Plain Text
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" value="md5" id="md5">
                <label class="form-check-label" for="md5">
                    MD5
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" value="pbkdf2" id="pbkdf2">
                <label class="form-check-label" for="pbkdf2">
                    PBKDF2
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" value="saltedhash" id="saltedhash">
                <label class="form-check-label" for="saltedhash">
                    SaltedHash
                </label>
            </div>
            </br>
            <div>Ju tashme keni llogari? <a href="login.php">Kyquni ketu</a>.</div>
        </form>
    </div>    
</body>
</html>