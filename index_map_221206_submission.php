<?php

date_default_timezone_set('Asia/Tokyo');

$comment_array = array();
$pdo = null;
$stmt = null;
$error_messages = array();

// DB接続
try{
    $pdo = new PDO('mysql:host=localhost;dbname=gs_db', 'root', '');
}catch (PDOException $e){
    echo $e->getMessage();
}

//フォームを打ち込んだとき
if (!empty($_POST["submitButton"])){
    // 名前のチェック
    if(empty($_POST["username"])){
        // ①$alertにjavascriptのalert関数を代入する。
        $alert = "<script type='text/javascript'>alert('名前を入力してください。');</script>";
        // ②echoで①を表示する
        echo $alert;
        // echo "名前を入力してください";
        $error_messages["username"] = "名前を入力してください";
    }
    // コメントのチェック
    if(empty($_POST["comment"])){
        // ①$alertにjavascriptのalert関数を代入する。
        $alert = "<script type='text/javascript'>alert('コメントを入力してください。');</script>";
        // ②echoで①を表示する
        echo $alert;
        // echo "コメントを入力してください";
        $error_messages["comment"]="コメントを入力してください";
    }
}

// echo $_POST["submitbutton"];
if (!empty($_POST["submitButton"])) {
    // echo $_POST["username"];
    // echo $_POST["comment"];

    //名前のチェック
    if (empty($_POST["username"])){
        echo "名前を入力してください";
    }
    //コメントのチェック
    if (empty($_POST["comment"])){
        echo "コメントを入力してください";
    }
    
    if(empty($error_messages)){
        $current_date = date("Y-m-d H:i:s");
    
        try {
            $stmt = $pdo->prepare("INSERT INTO tabisaki_table ( username, tabisaki,comment, post_date) VALUES (:username, :tabisaki, :comment, :current_date);");
            $stmt->bindParam(':username', $_POST["username"],PDO::PARAM_STR);
            $stmt->bindParam(':tabisaki', $_POST["tabisaki"],PDO::PARAM_STR);
            $stmt->bindParam(':comment', $_POST["comment"],PDO::PARAM_STR);
            $stmt->bindParam(':current_date', $current_date,PDO::PARAM_STR);
    
            $stmt->execute();
    
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

    }
}

// DBからコメントデータを取得する
$sql = "SELECT `id`, `username`, `tabisaki`,`comment`, `post_date` FROM `tabisaki_table`";
// $sql = "SELECT  `username`, `comment`, `postDate` FROM `tabisaki_table`";
$element_array=$pdo->query($sql);

// DBの接続を閉じる
$pdo = null;

?>



<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G's掲示板</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1 class="title">G's各校舎に思いをはせる掲示板</h1>
    <div class="boardWrapper">
        <section>
            <?php foreach ($element_array as $element):?>
                <article id="hensu[i]">
                    <div class="wrapper">
                        <div class="nameArea">
                            <span>名前：</span>
                            <p class="username"><?php echo $element["username"];?></p>
                            <span>　旅先：</span>
                            <p class="tabisaki"><?php echo $element["tabisaki"];?></p>
                            
                            
                            <span>　</span>
                            <time><?php echo $element["post_date"];?></time>
                        </div>
                        <p class="comment"><?php echo $element["comment"];?></p>
                        <div id=`GSV${i}`>リンク先：
                            <?php  if ($element["tabisaki"]==1){
                                        echo '<a href="GoogleStreetView_01_221204.html" target="_blank">旅先を探索（原宿）</a>'; 
                                    }else if($element["tabisaki"]=2){
                                        echo '<a href="GoogleStreetView_02_221204.html" target="_blank">旅先を探索（札幌）</a>';
                                    }else if($element["tabisaki"]==3){   
                                        echo '<a href="GoogleStreetView_03_221204.html" target="_blank">旅先を探索（福岡）</a>';
                                    }else if($element["tabisaki"]==4){  
                                        echo '<a href="GoogleStreetView_04_221204.html" target="_blank">旅先を探索（新山口）</a>';
                                    } else if ($element["tabisaki"] == 5) {
                                        echo '<a href="GoogleStreetView_05_221204.html" target="_blank">旅先を探索（難波）</a>';
                                    }; ?></div>                       
                    </div>
                </article>  
            <?php endforeach; ?>
        </section>
        <form class="formWrapper" method="POST">
            <div>
                <input type="submit" value="書き込む" name="submitButton" id="id_button">
                <label form="">名前</label>
                <input type="text" name="username">
                <label form="">たびさき</label>
                <!-- <select option="text" name="icon"> -->
                <select name="tabisaki">
                    <option value="01_harajuku">01_原宿</option>
                    <option value="02_sapporo">02_札幌</option>
                    <option value="03_hakata">03_博多</option>
                    <option value="04_yamaguchi">04_新山口</option>
                    <option value="05_itaewhon">05_難波</option>
                </select>
            </div>
            <div>
                <textarea class="commentTextArea" name="comment" cols="30" rows="10"></textarea>
            </div>
    </div>
</body>
</html>