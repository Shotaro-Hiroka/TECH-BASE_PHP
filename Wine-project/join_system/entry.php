<?php
    require("dbconnect.php");
    session_start();
    
    
    if(!empty($_POST)){

        /*入力がからでないか確認*/
        if($_POST['email'] == NULL){
            $error['email'] = "blank";
        }
        if($_POST['password'] == NULL){
            $error['password'] == "blank";
        }
        
        /*メアドが重複していないか確認*/
        if(!isset($error)){
            $member = $db->prepare('SELECT COUNT(*) as cnt FROM hello3 WHERE email=?');
            $member->execute(array(
                $_POST['email']
                ));
                $record= $member->fetch();
                if($record['cnt'] > 0){
                    $error['email'] = 'duplicate';
                }
        }
        
        
        /*エラーなければ次のページへ移動する*/
        if(!isset($error)){
            $_SESSION['join'] = $_POST;
            header('Location: check.php');
            exit();
        }
    }

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
    <title>アカウント作成</title>
    <link href="https://unpkg.com/sanitize.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="content">
        <form action="" method="POST">
            <h1>アカウント作成</h1>
            <p>当サービスをご利用するために、次のフォームに必要事項をご記入ください。</p>
            <br>
 
            <div class="control">
                <label for="name">ユーザー名</label>
                <input id="name" type="text" name="name">
            </div>
 
            <div class="control">
                <label for="email">メールアドレス<span class="required">必須</span></label>
                <input id="email" type="email" name="email">
                <?php if (!empty($error["email"]) && $error['email'] === 'blank'): ?>
                    <p class="error">＊メールアドレスを入力してください</p>
                <?php elseif (!empty($error["email"]) && $error['email'] === 'duplicate'): ?>
                    <p class="error">＊このメールアドレスはすでに登録済みです</p>
                <?php endif ?>
            </div>
 
            <div class="control">
                <label for="password">パスワード<span class="required">必須</span></label>
                <input id="password" type="password" name="password">
                <?php if (!empty($error["password"]) && $error['password'] === 'blank'): ?>
                    <p class="error">＊パスワードを入力してください</p>
                <?php endif ?>
            </div>
 
            <div class="control">
                <button type="submit" class="btn">確認する</button>
            </div>
        </form>
    </div>
</body>
</html>