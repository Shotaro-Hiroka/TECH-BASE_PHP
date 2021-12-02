<!DOCTYPE html>
<?php
        
            // DB接続設定
            $dsn = 'mysql:dbname=tb******db;host=localhost';
            $user = 'tb-******';
            $password = 'PASWORD';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            
            //データベース内にテーブルを作成
            $sql = "CREATE TABLE IF NOT EXISTS mission5_1"
            ."("
            ."id INT AUTO_INCREMENT PRIMARY KEY,"
            ."number INT,"
            ."name char(32),"
            ."comment TEXT,"
            ."registry_datetime DATETIME,"
            ."pass TEXT"
            .");";
        ?>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission5-1</title>
    </head>
    <body>
        <?php
        //データの初期化
        $name=NULL;
        $message=NULL;
        $number=0;
        $d_n=0;
        $ED_N= NULL;
        $ED_NAME= NULL;
        $ED_MES= NULL;
        //mainの処理
        
            
            if(!empty($_POST['submit'])){
              //新規データ投稿モード  
              if($_POST['edit_num'] == NULL){
                 //パスワードが記入してあれば、投稿できる
                 if($_POST['pass_input'] != NULL){
                    //テキストボックスのデータを変数に格納
                    //氏名　→ $name　に格納
                    $name= $_POST['name'];
                    //コメント → $messageに格納
                    $message= $_POST['str'];
                    //パスワード → $PASSに格納
                    $PASS= $_POST['pass_input'];
                    
                    //DB内データ読み込み
                        //DBからデータレコードを抽出
                    $sql= '
                        SELECT * FROM mission5_1
                    ';
                    
                    $stmt= $pdo->query($sql);
                    $results= $stmt->fetchALL();
                    
                    //最後尾の投稿番号を取得
                    if((count($results)-1) <0){
                        $number= 1;
                    }else{
                        //最後尾のデータ列を$lineに格納
                        $line= $results[count($results)-1];
                        $number= $line['number'];
                        $number += 1;
                    }
                    
                    //現在時刻を取得
                    $datetime= date("Y/m/d H:i:s");
                    
                    
                    //DBへ書き込む
                    $sql= $pdo->prepare("INSERT INTO mission5_1(number, name, comment, registry_datetime, pass) VALUES(:number, :name, :comment, :registry_datetime, :pass)");
                    $sql->bindParam(':number', $number, PDO::PARAM_INT);
                    $sql->bindParam(':name', $name, PDO::PARAM_STR);
                    $sql->bindParam(':comment', $message, PDO::PARAM_STR);
                    $sql->bindParam(':registry_datetime', $datetime, PDO::PARAM_STR);
                    $sql->bindParam(':pass', $PASS, PDO::PARAM_STR);
                    
                    $sql->execute();
                 }
                    
                }else if($_POST['edit_num'] != NULL){
                   //修正モード
                    $number= $_POST['edit_num'];
                    //入れ替えるデータを準備
                     $comment= $_POST['str'];
                    $name= $_POST['name'];
            
                    //一致したら、元の値と入れ替える
                    $sql='
                        UPDATE mission5_1 SET name= :name, comment= :comment
                        WHERE number=:number
                    ';
                    $stmt= $pdo->prepare($sql);
                    $stmt->bindParam(':name',$name,PDO::PARAM_STR);
                    $stmt->bindParam(':comment',$comment,PDO::PARAM_STR);
                    $stmt->bindParam(':number',$number,PDO::PARAM_INT);
            
                    $stmt->execute();
                    //ファイルを閉じる 
                }
                
            }else if(!empty($_POST['reset'])){
                //消去
              //  if($_POST['pass_drop'] != NULL){
                    $PASS= $_POST['pass_drop'];
                    //消去番号を変数に格納する
                    $drop_num= $_POST['drop'];
                    $drop_num= (int)$drop_num;
                
            
                
            //番号の存在確認
            
                //DBからデータを抜き出す
                $sql='
                    SELECT * FROM mission5_1
                ';
                $stmt= $pdo->query($sql);
                $Lines= $stmt->fetchALL();
                $line= $Lines[$drop_num-1];
                if($line['pass'] == $PASS){
                   //ファイルを更新する
                          $sql='
                            DELETE FROM mission5_1
                            
                            WHERE number=:number
                          ';
                          
                          $stmt= $pdo->prepare($sql);
                          $stmt->bindParam(':number', $drop_num,PDO::PARAM_INT);
                          $stmt->execute();
                          
                          $sql='
                          UPDATE mission5_1 
                          SET number= number - 1
                          WHERE number > :number
                          ';
                           
                          $stmt= $pdo->prepare($sql);
                          $stmt->bindParam(':number', $drop_num,PDO::PARAM_INT);
                          $stmt->execute(); 
                }
                       
                      
                 
                  
              //  }           
                
                   
            }else if(!empty($_POST['edit'])){
               //編集
            //編集番号を変数に格納する
            $edit_num= $_POST['edit_num'];
            $PASS= $_POST['pass_edit'];
            //DBから編集番号の存在を確認する
            $sql= '
                SELECT * from mission5_1
            ';
            
            $stmt= $pdo->query($sql);
            $Lines= $stmt->fetchALL();
            //データの型確認
            if($edit_num <= 0){
                    echo "正の整数を入力してください。<br>";
                }else if($edit_num > count($Lines)){
                    echo "その投稿番号は存在しません。<br>";
                }else{
                    foreach($Lines as $line){
                        if($line['number'] == $edit_num){
                            $ED_N= $line['number'];
                            $ED_NAME= $line['name'];
                            $ED_MES= $line['comment'];
                        
                        if($PASS != $line['pass']){
                            $ED_N= NULL;
                            $ED_NAME= NULL;
                            $ED_MES= NULL;  
                         }
                       
                        }
                        
                    }
                    
                }
                //正ければ、元データを分割　→ 各変数に格納 
            }
                
                    
            
            
            
                
            
                
                
            
        ?>
        
        <!--フォームをブラウザに記載-->
        <form action="" method="post">
            
            <p><?php 
            if($ED_N == NULL){
                echo "＊＊投稿フォーム＊＊";
            }else{
                echo "＊＊配列番号{$ED_N}編集フォーム＊＊";
            }
            
            ?></p>
            <p>氏名　　　　　：<input type="text" name="name" value="<?php echo $ED_NAME; ?>"></p>
            <p>コメント　　　：<input type="text" name="str" value="<?php echo $ED_MES; ?>"></p>
            <p>
                パスワード入力：<input type="password" name="pass_input">
            <input type="submit" name="submit" value="送信">
            </p>
            
            <p>＊＊消去フォーム＊＊</p>
            <!--【入力フォームと並べて「削除番号指定用フォーム」を用意：「削除対象番号」の入力と「削除」ボタンが1つある】-->
            <p>消去対象番号　：<input type="text" name="drop"></p>
            <p>パスワード確認：<input type="password" name="pass_drop">
            <input type="submit" name="reset" value="削除">
            </p>
            
            <p>＊＊編集フォーム＊＊</p>
            <p>編集対象番号　：<input type="text" name="edit_num" value="<?php echo $ED_N;?>"></p>
            <p>パスワード確認：<input type="password" name="pass_edit">
            <input type="submit" name="edit" value="編集">
            </p>
            <p>＊＊Contents＊＊</p>
        </form>
        <?php
            //データレコーダーを読み込み、配列変数に代入する
            $sql='
                SELECT * FROM mission5_1
            ';
            $stmt= $pdo->query($sql);
            $Lines= $stmt->fetchALL();
            
            //DBを揉み込んだ配列を、配列の数（＝行数）だけループさせる
            foreach($Lines as $line){
                echo "配列番号：{$line['number']},　名前：{$line['name']},　コメント：{$line['comment']},　時刻{$line['registry_datetime']}<br>";
            }
  
         
           
          
       
        ?>
        
        
        
        
        
        
        
        
        
        
        
        
        
        
    </body>
</html>