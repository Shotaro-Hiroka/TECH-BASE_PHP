<?php
    require("dbconnect.php");
    session_start();
    
    /*会員登録以外のデータを入れない*/
    if(!isset($_SESSION['join'])){
        header('Location: entry.php');
        exit();
    }
    
    echo "SE SSIONの中身は"."<br>";
    var_dump($_SESSION['join']);
    
    
        echo "POSTの中身は"."<br>";
        var_dump($_POST['check']);
        
    if(!empty($_POST['check'])){
        
        echo "POSTの中身は"."<br>";
        var_dump($_POST['check']);
        //暗号化
        //$hash = password_hash($_SESSION['join']['password'], PASSWORD_BCRYPT);
        
        /*
        //データ登録
        $statement = $db->prepare("INSERT INTO hello2 SET name=?, email=?, password=?");
        $statement->execute(array(
            $_SESSION['join']['name'],
            $_SESSION['join']['email'],
            $hash
        ));
        */
        
        $statement = $db->prepare("INSERT INTO hello3(name, email, pass) VALUES(:name, :email, :pass)");
        $statement->bindParam(':name', $_SESSION['join']['name'], PDO::PARAM_STR);
        $statement->bindParam(':email', $_SESSION['join']['email'], PDO::PARAM_STR);
        $statement->bindParam(':pass', $_SESSION['join']['password'], PDO::PARAM_STR);
        
        $statement->execute();
        unset($_SESSION['join']);
        header('Location: thank.php');
        exit();
            
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
    <title>確認画面</title>
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link href="https://unpkg.com/sanitize.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="content">
        <form action="" method="POST">
            <input type="hidden" name="check" value="checked">
            <h1>入力情報の確認</h1>
            <p>ご入力情報に変更が必要な場合、下のボタンを押し、変更を行ってください。</p>
            <p>登録情報はあとから変更することもできます。</p>
            <?php if (!empty($error) && $error === "error"): ?>
                <p class="error">＊会員登録に失敗しました。</p>
            <?php endif ?>
            <hr>
 
            <div class="control">
                <p>ニックネーム</p>
                <p><span class="fas fa-angle-double-right"></span> <span class="check-info"><?php echo htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES); ?></span></p>
            </div>
 
            <div class="control">
                <p>メールアドレス</p>
                <p><span class="fas fa-angle-double-right"></span> <span class="check-info"><?php echo htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES); ?></span></p>
            </div>
            
            <br>
            <a href="entry.php" class="back-btn">変更する</a>
            <button type="submit" class="btn next-btn">登録する</button>
            <div class="clear"></div>
        </form>
    </div>
</body>
</html>