<?php
//config dosyasını dahil ediyoruz.
require_once "config.php";
 
//değişkenleri tanımlayıp boş değere atıyoruz.
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
//form gönderildiğinde verilerin işlenmesi
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
     //kullanıcı adı doğrulama
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        //sorgu ile veri seçiliyor
        $sql = "SELECT id FROM users WHERE username = :username";
        
        if($stmt = $pdo->prepare($sql)){
           //hazırlanan ifadeye değişkenleri parametre olarak bağlayın
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            
             //parametreyi ayarla
            $param_username = trim($_POST["username"]);
            
            //hazırlanan ifadeyi yerine getirme işlemi
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $username_err = "bu kullanıcı adı coktan alındı.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Tekrar Deneyin.";
            }
        }
         
       //Bildirimi kapat
        unset($stmt);
    }
    
  //Parola kontrolü
    if(empty(trim($_POST["password"]))){
        $password_err = "Şifre girin.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Şife en az 6 karakter olmalı.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    //Şifre tekrar doğrulama
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Şifrenizi giriniz.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Şifreniz eşit değil.";
        }
    }
    
    //database'e eklenmeden kontrolü yapılır.
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
       //ekleme sorgsu
        $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
         
        if($stmt = $pdo->prepare($sql)){
           //hazırlanan ifadeye değişkenleri parametre olarak bağlayın
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            
           //parametreleri ayarla
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
             //hazırlanan ifadeyi yerine getirme işlemi
            if( $stmt->execute() ){
               //login sayfasına yönlendirilir
                header("location: login.php");
            } else{
                echo "Tekrar deneyin.";
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
    <title>Kayıt Ol!</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Kayıt Ol!</h2>
        <p>Lütfen formu doldurun.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Kullanıcı Adı</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Şifre</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Şifre Tekrar</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Üye iseniz link'e tıklayın ? <a href="login.php">Giriş</a>.</p>
        </form>
    </div>    
</body>
</html>