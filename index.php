<?php
session_start();

require('dbconnect.php');

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();

    $members = $db->prepare('SELECT * FROM members WHERE id=?');
    $members->execute(array($_SESSION['id']));
    $member = $members->fetch();
} else {
    header('Location: login.php');
    exit();
}

if (!empty($_POST)) {
    if ($_POST['message'] !== ''){
      if($_POST["reply_post_id"] == "") 
        $_POST["reply_post_id"] = 0;
        $message = $db->prepare('INSERT INTO posts SET member_id=?, message=?, reply_message_id=?, created=NOW()' );
        $message->execute(array(
        $member['id'],
        $_POST['message'],
        $_POST['reply_post_id']
        ));
        header('Location: index.php');
        exit();
        
    } 
} 

$page = $_REQUEST['page'];

if ($page == '') {
    $page = 1;
  }
$page = max($page, 1);

$counts = $db->query('SELECT COUNT(*) AS cnt FROM posts');
$cnt = $counts->fetch();
$maxPage = ceil($cnt['cnt'] / 5);
$page = min($page, $maxPage);


$start = ($page - 1) * 5;

$posts = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC LIMIT ?,5');

$posts->bindParam(1, $start, PDO::PARAM_INT);
$posts->execute();

if (isset($_REQUEST['res'])) {
    $response = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=?');
    $response->execute(array($_REQUEST['res']));
  
    $table = $response->fetch();
    $message = '@' . $table['name'];
} 
  
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <title>apex掲示板</title>
    <style>
        a{
            text-decoration: none;
        }
        
    </style>
</head>
<body style="background-color: ">
    <main>
        <div class="header h-200" style="background-image: url(picture/images.png)">
            <div class="row">
                <div class="box1 col-12 my-1 bg-gradient ">
                    <h1 class="my-1 p-3 text-danger" style=" text-shadow: 1px 1px 0 rgba(0,0,0,.2);">APEX掲示板</h1>
                </div>
            </div>
            <div class="row">
                <div class="box2 col-3 offset-9 my-1">
                    
                <a href="logout.php"><div class="btn btn-secondary" style="text-align: right">ログアウト</div>
                </div></a>
            </div>
        </div>    
        <div class="container mt-2 ">
            <div class="container border border-2 p-4 shadow-sm p-3 mb-5 bg-white rounded">  
                <div class="goriyoukiyaku ">
                    <h2 class="mb-5">ご利用規約</h2>
                    <div class="tyuuigaki">
                        <p class="fw-bolder">安全に楽しく利用していただくために以下の注意点に気を付けてご利用ください</p>
                            <ul>
                                <li class="text-decoration-underline mb-2">他のクランへの誹謗・中傷含む書き込み</li>
                                <li class="text-decoration-underline mb-2">掲示板の趣旨と関係ない書き込み</li>
                                <li class="text-decoration-underline mb-2">売買目的の書き込み</li>
                            </ul>
                    </div>
                </div>
            </div>
            <span class="m-3"></span>
            <div class="container4 border border-2 p-4 bg-light shadow-sm p-3 mb-5 bg-white rounded">
                <div class="message_write">
                    <h2 class="mb-5"><?php print(htmlspecialchars($member['name'],ENT_QUOTES)); ?>さん、書き込んでください</h2>
                        <form action="" method="POST">
                            
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">
                                    本文
                                </label>
                                <textarea name="message" class="form-control" id="exampleFormControlTextarea1" rows="7" placeholder="クラン方針、募集内容など自由にかきこんでね"><?php print(htmlspecialchars($message, ENT_QUOTES)); ?>
                                </textarea>
                                <input type="hidden" name="reply_post_id" value="<?php print(htmlspecialchars($_REQUEST['res'], ENT_QUOTES)); ?>" />
                            </div>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">画像</label>
                                <input name="picture" class="form-control" type="file" id="formFile">
                            </div>
                            <button type="submit" class="btn btn-outline-primary">送信</button>
                        
                        </form>
                </div>
            </div>
            <div class="btn-toolbar my-3 border-top pt-3" role="toolbar" aria-label="Toolbar with button groups">
                <div class="btn-group me-2" role="group" aria-label="First group">

                <?php if ($page > 1): ?>
                    <a href="index.php?page=<?php print(1); ?>"><button type="button" class="btn btn-outline-secondary"><<</button></a>
                    <a href="index.php?page=<?php print($page-1); ?>"><button type="button" class="btn btn-outline-secondary"><</button></a>
                <?php endif; ?> 

               

                <?php if ($page != $maxPage): ?>
                    <a href="index.php?page=<?php print($page+1); ?>"><button type="button" class="btn btn-outline-secondary">></button></a>
                    <a href="index.php?page=<?php print($maxPage); ?>"><button type="button" class="btn btn-outline-secondary">>></button></a>
                <?php endif; ?> 
                </div>
            </div>
            <?php foreach ($posts as $post) : ?>
            <div class="msg">
                <div class="media shadow-lg p-3 mb-5 bg-white rounded">
                    <img class="align-self-start mr-3" src="member_picture/<?php print(htmlspecialchars($post['picture'],ENT_QUOTES)); ?>"width="48" height="48" alt="no image"/>
                    <div class="media-body">
                        <h5 class="mt-0"><span class="name"><?php print(htmlspecialchars($post['name'],ENT_QUOTES)); ?></span></h5><p><a href="view.php?id=<?php print(htmlspecialchars($post['id'],ENT_QUOTES)); ?>" class="text-reset ">
                        <?php print(htmlspecialchars($post['message'],ENT_QUOTES)); ?></p></a>
                        <button type="button" class="btn btn-outline-dark"><a href="index.php?res=<?php print(htmlspecialchars($post['id'],ENT_QUOTES)); ?>">返信</a></button>
                        <p class="text-end"><?php print(htmlspecialchars($post['created'],ENT_QUOTES)); ?>
                        
                        <?php if($_SESSION['id'] == $post['member_id']): ?>
                        （<a href="delete.php?id=<?php print(htmlspecialchars($post['id'])); ?>" class="text-danger">削除</a>）</p>
                        <? endif; ?>
                        
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="btn-toolbar my-3 border-top pt-3" role="toolbar" aria-label="Toolbar with button groups">
                <div class="btn-group me-2" role="group" aria-label="First group">

                <?php if ($page > 1): ?>
                    <a href="index.php?page=<?php print(1); ?>"><button type="button" class="btn btn-outline-secondary"><<</button></a>
                    <a href="index.php?page=<?php print($page-1); ?>"><button type="button" class="btn btn-outline-secondary"><</button></a>
                <?php endif; ?> 

                
                <?php if ($page != $maxPage): ?>
                    <a href="index.php?page=<?php print($page+1); ?>"><button type="button" class="btn btn-outline-secondary">></button></a>
                    <a href="index.php?page=<?php print($maxPage); ?>"><button type="button" class="btn btn-outline-secondary">>></button></a>
                <?php endif; ?> 
                </div>
            </div>
        </div>
    </main>
</body>
</html>