<?php 
session_start();
require('dbconnect.php');

if (empty($_REQUEST['id'])) {
  header('Location: index.php');
  exit();
}

$posts = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=?');
$posts->execute(array($_REQUEST['id']));
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>apex掲示板</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>

<body>

<div class="header h-200" style="background-image: url(picture/images.png)">
  <div class="row">
    <div class="box1 col-12 my-1 bg-gradient ">
      <h1 class="my-1 p-3 text-danger" style=" text-shadow: 1px 1px 0 rgba(0,0,0,.2);">APEX掲示板</h1>
    </div>
  </div>
</div>
<div class="container mt-2 ">
<div class="container border border-2 p-4 shadow-sm p-3 mb-5 bg-white rounded">
  <div id="content">
  <p>&laquo;<a href="index.php">一覧にもどる</a></p>
 
  <?php if ($post = $posts->fetch()): ?>
    <div class="msg">
    
    
    <p><?php print(htmlspecialchars($post['message'])); ?><span class="name">（<?php print(htmlspecialchars($post['name'])); ?>）</span></p>
    <p class="day"><?php print(htmlspecialchars($post['created'])); ?></p>
    </div>
  <?php else: ?>
	<p>その投稿は削除されたか、URLが間違えています</p>
  <?php endif; ?>
    </div>
  </div>
</div>

</body>
</html>
