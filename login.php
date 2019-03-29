<?php
//oturumu başlat
session_start();
 
//Kullanıcının giriş yapmış olup olmadığını kontrol et, eğer evet ise, onu tekrar hoşgeldiniz sayfasına yönlendir
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
 
//config dosyası dahil ediliyor
require_once "config.php";
 
//Değişkenleri tanımlanıyor ve boş değerlerle başlatılıyor
$username = $password = "";
$username_err = $password_err = "";
 
//form verileri gönderildiği zaman çalıştırılıyor
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Lütfen kullanıcı adı giriniz.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Lütfen şifrenizi giriniz.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    //doğrulama 
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = :username";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                //şifre doğru ise yeni oturum başlat
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $id = $row["id"];
                        $username = $row["username"];
                        $hashed_password = $row["password"];
                        if(password_verify($password, $hashed_password)){
                         //şifre doğru ise yeni oturum başlat
                            session_start();
                            
                            //verileri oturum değişkenlerinde saklanır.
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            //kullanıcıyı welcome sayfasına yönlendirir
                            header("location: welcome.php");
                        } else{
                            
                            $password_err = "Girilen şifre yanlış.";
                        }
                    }
                } else{
                   
                    $username_err = "Kullanıcı adı ile hesap bulunamadı.";
                }
            } else{
                echo "tekrar deneyin.";
            }
        }
        
          //Bildirimi kapat
        unset($stmt);
    }
    
   //bağlantıyı kapat
    unset($pdo);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Giriş</h2>
        <p>Giriş bilgilerini doldurun.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Kullanıcı Adı</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Şifre</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Kayıtlı değil misiniz? <a href="register.php">Kayıt ol!</a>.</p>
        </form>
    </div>
</body>
</html>