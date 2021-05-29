<?php 
session_start();
require('dbconnect.php');

if ($_COOKIE['email'] !== '') {
    $email = $_COOKIE['email'];
  }

if (!empty($_POST)) {
    $email = $_POST['email'];
    if ($_POST['email'] !== '' && $_POST['password'] !== '') {
      $login = $db->prepare('SELECT * FROM members WHERE email=? AND password=?');
      $login->execute(array(
        $_POST['email'],
        sha1($_POST['password'])
      ));
      $member = $login->fetch();

      if($member) {
        $_SESSION['id'] = $member['id'];
        $_SESSION['time'] = time();
  
        if ($_POST['save'] === 'on') {
            setcookie('email', $_POST['email'], time()+60*60*24*14);
          }

        header('Location: index.php');
        exit();
        } else {
            $error['login'] = 'failed';
        } 
    } else {
      $error['login'] = 'blank';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <title>ログイン</title>
</head>
<body>
<div class="header h-200" style="background-image: url(picture/images.png)">
            <div class="row">
                <div class="box1 col-12 my-1 bg-gradient ">
                    <h1 class="my-1 p-3 text-danger" style=" text-shadow: 1px 1px 0 rgba(0,0,0,.2);">APEX掲示板</h1>
                </div>
            </div>
          </div>
    <div class="container border border-2 p-3 shadow p-3 mb-5 bg-white rounded">
    <h1 class="border-bottom">ログインする</h1>
  
  <div id="content">
    <div id="lead">
      <p>メールアドレスとパスワードを記入してログインしてください。</p>
      <p>入会手続きがまだの方はこちらからどうぞ。</p>
      <p>&raquo;<a href="join/">入会手続きをする</a></p>
    </div>
    <form action="" method="post">
      <dl>
        <dt>メールアドレス</dt>
        <dd>
          <input class="form-control shadow-sm mb-2 bg-white rounded" id="exampleInputEmail1" type="text" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($email); ?>" />
          <?php if ($error['login'] === 'blank'): ?>
          <p class="error">*メールアドレスとパスワードをご記入ください</p>
          <?php endif; ?>
          <?php if ($error['login'] === 'failed'): ?>
          <p class="error">*ログインに失敗しました。正しくご記入ください</p>
          <?php endif; ?>
        </dd>
        <dt>パスワード</dt>
        <dd>
          <input class="form-control shadow-sm mb-2 bg-white rounded" id="exampleInputEmail1" type="password" name="password" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['password']); ?>" />

        </dd>
        <dt>ログイン情報の記録</dt>
        <dd>
          <input id="save" type="checkbox" name="save" value="on">
          <label for="save">次回からは自動的にログインする</label>
        </dd>
      </dl>
      <div>
      <button type="submit" class="btn btn-primary">ログイン</button>
      </div>
    </form>
  </div>
  
  </div>
</body>
</html>