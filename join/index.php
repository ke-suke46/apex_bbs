<?php


session_start();
require('../dbconnect.php');


//名前、email、passwordのエラー確認

if(!empty($_POST)) {
	if ($_POST['name']=== '') {
		$error['name'] = 'blank';
	}
    if ($_POST['email']=== '') {
		$error['email'] = 'blank';
    }
    if (strlen($_POST['password']) < 4) {
		$error['password'] = 'length';
	}
    if ($_POST['password']=== '') {
		$error['password'] = 'blank';
	}
    $fileName = $_FILES['image']['name'];
	if (!empty($fileName)) {
        //拡張子を得てをjpg,gif,pngではない場合エラーを表示する
		$ext = substr($fileName, -3);
		if ($ext != 'jpg' && $ext != 'gif' && $ext != 'png') {
			$error['image'] = 'type';
		}
	}
    

	//アカウントの重複チェック
	if(empty($error)) {
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
		$member->execute(array($_POST['email']));
		$record = $member->fetch();
		if ($record['cnt'] > 0) {
			$error['email'] = 'duplicate';
		}
	}
	

	if (empty($error)){
        //ファイル名をつける
		$image = date('YmdHis') . $_FILES['image']['name'];
		move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);
        //ページを戻っても値を消さない処理
		$_SESSION['join'] = $_POST;
		$_SESSION['join']['image'] = $image;
		header('Location: check.php');
		exit();
		
	}
}
if ($_REQUEST['action'] == 'rewrite' && isset($_SESSION['join'])) {
	$_POST = $_SESSION['join'];
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
    <style>
    </style>
    <title>サインイン</title>
</head>
<body>
<div class="header h-250" style="background-image: url(../picture/images.png)">
            <div class="row">
                <div class="box1 col-12 my-1 bg-gradient ">
                    <h1 class="my-1 p-3 text-danger" style=" text-shadow: 1px 1px 0 rgba(0,0,0,.2);">APEX掲示板</h1>
                </div>
            </div>
        </div>
    <div class="container offset-1 shadow p-3 mb-5 mt-5 bg-white rounded border">
        <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="exampleInputName1" class="form-label">お名前</label>
            <input type="name" name="name" class="form-control shadow-sm mb-2 bg-white rounded" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php print (htmlspecialchars($_POST['name'], ENT_QUOTES)); ?>">
            <?php if ($error['name'] === 'blank'): ?>
            <p class="error">*お名前を入力してください </p>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">メールアドレス</label>
            <input type="email" name="email" class="form-control shadow-sm mb-2 bg-white rounded" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php print (htmlspecialchars($_POST['email'], ENT_QUOTES)); ?>">
            <?php if ($error['email'] === 'blank'): ?>
            <p class="error">*メールアドレスを入力してください </p>
            <?php endif; ?>
            <?php if ($error['email'] === 'duplicate'): ?>
            <p class="error">*指定されたメールアドレスは既に使われています </p>
            <?php endif; ?>
            <div id="emailHelp" class="form-text">あなたのメールを他の人と共有することは決してありません。</div>
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">パスワード</label>
            <input type="password" name="password" class="form-control shadow-sm  mb-2 bg-white rounded" id="exampleInputPassword1" value="<?php print (htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>">
            <?php if ($error['password'] === 'blank'): ?>
			<p class="error">*パスワードを入力してください</p>
			<?php endif; ?>
            <?php if ($error['password'] === 'length'): ?>
			<p class="error">*パスワードは４文字以上で入力してください</p>
			<?php endif; ?>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input " id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">パスワードを保存する</label>
        </div>
        <div class="mb-3">
            <label for="formFileMultiple" name="image" class="form-label"></label>
            <input name="image" class="form-control" type="file" id="formFileMultiple" multiple>
        </div>
			<?php if ($error['image'] === 'type'): ?>
			<p class="error">*写真などは「.gif」または 「.jpg」または 「.png」の画像を指定してください</p>
			<?php endif; ?>
			<?php if (!empty($error)): ?>
			<p class="error">*恐れ入りますが、画像を改めて指定してください</p>
			<?php endif; ?>
        <button type="submit" class="btn btn-primary">送信</button>
        </form>
    </div>
</body>
</html>