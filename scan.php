<?php
set_time_limit(0);
error_reporting(0);
@ini_set('zlib.output_compression', 0);
@ini_set('implicit_flush', 1);
for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
ob_implicit_flush(1);

$path = getcwd();
if (isset($_GET['dir'])) {
    $path = $_GET['dir'];
}

echo '<style>body {background-color:#000;color:green;} body,td,th { font: 9pt Courier New;margin:0;vertical-align:top; } span,h1,a { color:#00ff00} span { font-weight: bolder; } h1 { border:1px solid #00ff00;padding: 2px 5px;font: 14pt Courier New;margin:0px; } div.content { padding: 5px;margin-left:5px;} a { text-decoration:none; } a:hover { background:#ff0000; } .ml1 { border:1px solid #444;padding:5px;margin:0;overflow: auto; } .bigarea { width:100%;height:250px; } input, textarea, select { margin:0;color:#00ff00;background-color:#000;border:1px solid #00ff00; font: 9pt Monospace,"Courier New"; } form { margin:0px; } #toolsTbl { text-align:center; } .toolsInp { width: 80%; } .main th {text-align:left;} .main tr:hover{background-color:#5e5e5e;} .main td, th{vertical-align:middle;} pre {font-family:Courier,Monospace;} .style2 {color: #00FF00} .style3 {color: #009900} .style4 {color: #006600} .style5 {color: #00CC00} .style6 {color: #003300} .style8 {color: #33CC00}</style>';

echo '<form action="" method="get"> <input type="text" name="dir" value="' . htmlspecialchars($path) . '" style="width: 548px;"> <input type="submit" value="scan"></form><br>';

echo "CURRENT DIR: <font color='yellow'>" . htmlspecialchars($path) . "</font><br>";

if (isset($_GET['delete'])) {
    unlink($_GET['delete']);
    $status = "<font color='red'>FAILED</font>";
    if (!file_exists($_GET['delete'])) {
        $status = "<font color='yellow'>Success</font>";
    }
    echo "TRY TO DELETE: " . htmlspecialchars($_GET['delete']) . " $status <br>";
    exit;
}

scanBackdoor($path);

function save($fname, $value) {
    $file = fopen($fname, "a");
    fwrite($file, $value);
    fclose($file);
}

function checkBackdoor($file_location) {
    global $path;
    $patern = "#exec\(|gzinflate\(|file_put_contents\(|file_get_contents\(|system\(|passthru\(|shell_exec\(|move_uploaded_file\(|eval\(|base64#";
    $contents = @file_get_contents($file_location);
    if ($contents !== false && strlen($contents) > 0) {
        if (preg_match($patern, strtolower($contents))) {
            $safe_path = htmlspecialchars($path);
            echo "[+] Suspect file -> <a href='?delete=" . urlencode($file_location) . "&dir=" . urlencode($safe_path) . "'><font color='yellow'>[DELETE]</font></a> <font color='red'>" . htmlspecialchars($file_location) . "</font> <br>";
            save("shell-found.txt", "$file_location\n");
            echo '<textarea name="content" cols="80" rows="15">' . htmlspecialchars($contents) . '</textarea><br><br>';
        }
    }
}

function scanBackdoor($current_dir) {
    if (is_readable($current_dir)) {
        $dir_location = @scandir($current_dir);
        if ($dir_location === false) return;
        foreach ($dir_location as $file) {
            if ($file === "." || $file === "..") {
                continue;
            }
            $file_location = str_replace("//", "/", $current_dir . '/' . $file);
            $nFile = substr($file, -4, 4);
            if ($nFile == ".php") {
                checkBackdoor($file_location);
            } else if (is_dir($file_location)) {
                scanBackdoor($file_location);
            }
        }
    }
}
