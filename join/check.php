<?php
session_start();
require('../dbconnect.php');

if (!isset($_SESSION['join'])) {
	header('Location: index.php');
	exit();
}

if (!empty($_POST)) {
	$statement = $db->prepare('INSERT INTO members SET name=?, email=?, password=?, picture=?, created=NOW()');
	echo $statement->execute(array(
		$_SESSION['join']['name'],
		$_SESSION['join']['email'],
		sha1($_SESSION['join']['password']),
		$_SESSION['join']['image']
	));
	unset($_SESSION['join']);
    
    //sessionが重くなるのを防ぐため値を消す処理
	header('Location: thanks.php');
	exit();
}


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <title>apex掲示板</title>
</head>
<body>
<div class="header h-200" style="background-image: url(../picture/images.png)">
            <div class="row">
                <div class="box1 col-12 my-1 bg-gradient ">
                    <h1 class="my-1 p-3 text-danger" style=" text-shadow: 1px 1px 0 rgba(0,0,0,.2);">APEX掲示板</h1>
                </div>
            </div>
        </div>
    <div class="container">
    <div id="content" class="border p-3 shadow p-3 mb-5 bg-white rounded">
        <p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
        <form action="" method="post">
            <input type="hidden" name="action" value="submit" />
            <dl>
                <dt class="text-decoration-underline">ニックネーム</dt>
                <dd>
                <?php print(htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES)); ?>
                </dd>
                <dt class="text-decoration-underline">メールアドレス</dt>
                <dd>
                <?php print(htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES)); ?>

                </dd>
                <dt class="text-decoration-underline">パスワード</dt>
                <dd>
                【表示されません】
                </dd>
                <dt class="text-decoration-underline">写真など</dt>
                <dd>
                <?php if ($_SESSION['join']['image'] !== ''): ?>
                    <img src="../member_picture/<?php print(htmlspecialchars($_SESSION['join']['image'],ENT_QUOTES)); ?>" alt="">
                <?php endif; ?>

                </dd>
            </dl>
            <div><a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" /></div>
        </form>
    </div>
    </div>  
</body>
</html>