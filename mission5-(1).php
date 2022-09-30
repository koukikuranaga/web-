<?php
    //DB接続  
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    try{
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        
    }catch(PDOException $e){
        echo "接続失敗".$e->getMessage();
        exit();
    }
    //テーブルの作成
    $sql ="CREATE TABLE IF NOT EXISTS tbtest1"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "password char(32),"
    . "date DATETIME"
    .");";
    $stmt = $pdo->query($sql);
?>

<?php
        $date=date("Y/m/d/H:i:s");
        
        //名前とコメントが空かどうか
        if(!empty($_POST["comment"]) && !empty($_POST["name"])){
            
            $str1=$_POST["name"];
            $str2=$_POST["comment"];
            $str3=$_POST["password"];
            
            //編集か新規投稿か判断
          if(empty($_POST["editNO"])){
            $sql = $pdo -> prepare("INSERT INTO tbtest1 (name, comment,password,date) VALUES (:name, :comment,:password,:date)");
            $sql->bindParam(":name",$name,PDO::PARAM_STR);
            $sql->bindParam(":comment",$comment,PDO::PARAM_STR);
            $sql->bindParam(":password",$password,PDO::PARAM_STR);
            $sql->bindParam(":date",$date,PDO::PARAM_STR);
            
            $name=$str1;
            $comment=$str2;
            $password=$str3;
            
            $sql->execute();
          
            
        
            
            }if(!empty($_POST["editNO"])){
                //編集機能
                $id=$_POST["editNO"];    
                $name=$_POST["name"];
                $comment=$_POST["comment"];
                $password=$_POST["password"];
                //データレコードの更新
            $sql = 'UPDATE tbtest1 SET name=:name,comment=:comment,password=:password,date=:date WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                
                $stmt->execute();
                 }

            }
        
            
                 //削除処理
             
         if(!empty($_POST["deleteNO"])&&!empty($_POST["password_delete"])){
            
            $id=$_POST["deleteNO"];
            $sql = 'SELECT * FROM tbtest1 WHERE id=:id ';
            $stmt=$pdo->prepare($sql);
            $stmt->bindParam(':id',$id,PDO::PARAM_INT);
            $stmt->execute();
            $results=$stmt->fetchAll();
            foreach($results as $row){
                $deletepass=$row['password'];
            }
            
            if($deletepass==$_POST["password_delete"]){
            $str=$_POST["deleteNO"];
            $str=$id;
            $sql = 'delete from tbtest1 where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
                }if($_POST["password_delete"]!=$deletepass){
                echo "パスワードが違います";
            }
         }
        
        
            //ここまで
                  //編集フォームの送信されたかどうか
        if(!empty($_POST["edit"])&&!empty($_POST["password_edit"])){
            $number=$_POST["edit"];
            $id=$number;
            $sql= 'SELECT * FROM tbtest1 WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id',$id,PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            foreach ($results as $row){
                $editpass=$row["password"];
            }if($editpass==$_POST["password_edit"]){
            foreach ($results as $row){
                 $editnumber=$row['id'];
                 $editname=$row['name'];
                 $editcomment=$row['comment'];
                 
                }
            }else{
                echo "パスワードが違います";
            
            }
        }else{
                    $editnumber="";
                    $editname="";
                    $editcomment="";
        }
        ?>       
        
        
        
                    
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charaset="utf-8">
        <title>mission5-1</title>
    </head>
    <body>
        <form acttion="mission5-1.php" method="post">
           
            
            <input type="text" name="name" id="name" placeholder="名前" value="<?php if(isset($editname)) {echo $editname;}?>"><br>
            
            
            <input type="text" name="comment" id="comment"placeholder="コメント" value="<?php if(isset($editcomment)) {echo $editcomment;}?>">
            <br>
            <input type="text" name="password"  id="psaaword" placeholder="パスワード" value="">
            <input type="hidden" name="editNO" id="editNO" value="<?php if(isset($editnumber)){echo $editnumber;}?>">
            <input type="submit" name="submit"><br><br>
            
        </form>  
        
        <form acttion="mission5-1.php" method="post">
        <input type="number" name="deleteNO" placeholder="削除対象番号" value=""><br>
        <input type="text" name="password_delete" id="password_delete" placeholder="パスワード">
        <input type="submit" name="delete" value="削除"><br><br>
        　　
        </form>
        <form action="mission5-1.php" method="post">
        <input type="number" name="edit" id="edit" placeholder="編集対象番号"><br>
        <input type="text" name="password_edit" id="password_edit" placeholder="パスワード">
        <input type="submit" value="編集"><br><br>
        　  
        </form>
        
    
        <?php
        //データレコード表示
        $sql="SELECT*FROM tbtest1";
        $stmt=$pdo->query($sql);
        $results=$stmt->fetchAll();
        foreach($results as $row){
            echo $row["id"].',';
            echo $row["name"].',';
            echo $row["comment"]."";
            echo $row["date"]."<br>";
        }
        ?>
    
        
    </body>
</html>