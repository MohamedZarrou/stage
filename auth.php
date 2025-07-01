<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="POST">
        <label >email: </label>
        <input type="email" name="email" placeholder="enter email" required>
        <br>
        <label >password: </label>
        <input type="password" name="password" placeholder="enter password" required >
        <br>
        <input type="submit"  name="confirm" value="confirm">
        <br>
    
    </form>
    
</body>
</html>
<?php 
include "sql/db.php";
if (isset($_POST["confirm"])){
    $email=$_POST["email"];
    $password=$_POST["password"];
    $sql="SELECT * FROM utilisateurs WHERE email=:email AND password=:password";
    $db = Database::getInstance()->getConnection();
    $stmt=$db->prepare($sql);
    $stmt->bindParam(":email",$email);
    $stmt->bindParam(":password",$password);
    $stmt->execute();
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$rows){
        echo "introuvable";
    }
    else{
        
    header("Location: home.php?role={$rows['role']}");
    exit();
    
}
}
  
?>