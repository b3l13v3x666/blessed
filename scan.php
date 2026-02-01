<?php
declare(strict_types=1);

session_start();
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("Referrer-Policy: no-referrer");

if (!function_exists("str_contains")) {
    function str_contains(string $haystack, string $needle): bool {
        if ($needle === "") return true;
        return strpos($haystack, $needle) !== false;
    }
}
if (!function_exists("str_starts_with")) {
    function str_starts_with(string $haystack, string $needle): bool {
        if ($needle === "") return true;
        return substr($haystack, 0, strlen($needle)) === $needle;
    }
}
if (!function_exists("str_ends_with")) {
    function str_ends_with(string $haystack, string $needle): bool {
        if ($needle === "") return true;
        $n = strlen($needle);
        if ($n === 0) return true;
        return substr($haystack, -$n) === $needle;
    }
}

const PASSWORD_SHA256 = "dd9636cbde87e3cdfcce9d33e0497c438bfbe177aac668845a88c11955a5f63e";
const MAX_LOGIN_ATTEMPTS = 5;
const IP_ALLOWLIST = [];
const ALLOWED_ROOTS = ["/home", "/var/www", "/usr/share/nginx/html"];
const MAX_SCAN_BYTES = 5000000;
const CHUNK_BYTES = 1000000;
const PREVIEW_MAX_BYTES = 4000000;
const PREVIEW_MAX_LINES = 8000;

$EXCLUDE_DIRS = ["wp-includes","wp-admin","node_modules",".git",".svn","vendor","__pycache__",".venv","venv","scanner_env"];

$PHP_EXT = ["php","phtml","php3","php4","php5","phar"];
$TEXT_EXT = ["js","py","pl","sh","txt","htaccess","cgi"];

$WEBSHELL_PATTERNS = [
    ["/eval\\s*\\(\\s*(?:base64_decode|gzinflate|str_rot13|gzuncompress)\\s*\\(/is","eval+decode"],
    ["/base64_decode\\s*\\(\\s*.*\\)\\s*\\)\\s*;/is","base64_decode chain"],
    ["/\\$_(?:GET|POST|REQUEST|COOKIE)\\s*\\[[^\\]]+\\].{0,50}(?:@?eval|@?assert)\\b/is","input + eval/assert"],
    ["/\\b(?:system|shell_exec|passthru|popen|proc_open|pcntl_exec)\\s*\\(/i","code_exec"],
    ["/\\bexec\\s*\\(/i","exec"],
    ["/\\bassert\\s*\\(/i","assert"],
    ["/\\bcreate_function\\s*\\(/i","create_function"],
    ["/preg_replace\\s*\\([^)]*\\/e\\s*[,\\)]/i","preg_replace /e"],
    ["/file_get_contents\\s*\\(\\s*['\\\"]?(?:https?:\\/\\/|php:\\/\\/)/i","remote_include"],
    ["/\\bcurl_exec\\s*\\(/i","curl_exec"],
    ["/\\bfsockopen\\s*\\(/i","fsockopen"],
    ["/\\\\x[0-9a-fA-F]{2}/","hex_obfuscation"],
    ["/\\b(?:strrev|chr|pack)\\s*\\(/i","string/byte obfuscation"],
    ["/\\b(?:preg_replace_callback|array_map)\\s*\\(\\s*['\\\"][^'\\\"]+['\\\"]\\s*,\\s*\\$_(?:GET|POST|REQUEST|COOKIE)\\b/i","callback + user input"]
];

$GSOCKET_PATTERNS = [
    ["/\\b(?:gs-netcat|gsocket)\\b/i","gs-netcat/gsocket"],
    ["/\\bVLb1xgKagZH1KGc87icmUD\\b/","known_secret"],
    ["/\\b(?:GS_ARGS|GSOCKET)\\b/","gsocket_ref"],
    ["/\\b_usr_bin_python\\.dat\\b/","gsocket_dat"]
];

$MALWARE_NAMES = [
    "tmp.php","shell.php","c99.php","r57.php","wso.php","b374k",
    "cmd.php","eval.php","cgi.php","alfa0.php","kral.php",
    "filesman","wso_","bypasser","backdoor","minishell"
];

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

function sub_s(string $s, int $start, int $len): string {
    if (function_exists("mb_substr")) return (string)mb_substr($s, $start, $len, "UTF-8");
    return (string)substr($s, $start, $len);
}

function isAllowedIp(): bool {
    if (!IP_ALLOWLIST) return true;
    $ip = $_SERVER["REMOTE_ADDR"] ?? "";
    return in_array($ip, IP_ALLOWLIST, true);
}

function normalizePath(string $p): string {
    $p = str_replace("\\", "/", $p);
    $p = preg_replace("#/+#", "/", $p) ?? $p;
    $p = rtrim($p, "/");
    return $p === "" ? "/" : $p;
}

function pathWithin(string $file, string $root): bool {
    $file = normalizePath($file);
    $root = normalizePath($root);
    if ($root === "") return false;
    if ($file === $root) return true;
    return str_starts_with($file, $root . "/");
}

function isAllowedRoot(string $root): bool {
    $root = normalizePath($root);
    $real = realpath($root);
    if ($real === false || !is_dir($real)) return false;
    $real = normalizePath($real);
    if (count(ALLOWED_ROOTS) === 0) return true;
    foreach (ALLOWED_ROOTS as $base) {
        $baseReal = realpath($base);
        if ($baseReal === false) continue;
        $baseReal = normalizePath($baseReal);
        if ($real === $baseReal || str_starts_with($real, $baseReal . "/")) return true;
    }
    return false;
}

function authOk(): bool {
    return !empty($_SESSION["scanner_auth"]) && $_SESSION["scanner_auth"] === true;
}

function loginAttemptOk(): bool {
    $n = (int)($_SESSION["scanner_attempts"] ?? 0);
    return $n < MAX_LOGIN_ATTEMPTS;
}

function login(string $pass): bool {
    $hash = hash("sha256", $pass);
    if (hash_equals(PASSWORD_SHA256, $hash)) {
        $_SESSION["scanner_auth"] = true;
        $_SESSION["scanner_attempts"] = 0;
        return true;
    }
    $_SESSION["scanner_attempts"] = ((int)($_SESSION["scanner_attempts"] ?? 0)) + 1;
    return false;
}

function logout(): void {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $p = session_get_cookie_params();
        setcookie(session_name(), "", time() - 42000, $p["path"], $p["domain"], (bool)$p["secure"], (bool)$p["httponly"]);
    }
    session_destroy();
}

function shouldSkip(string $path, array $excludeDirs): bool {
    $p = normalizePath($path);
    foreach ($excludeDirs as $d) {
        $d = trim((string)$d);
        if ($d === "") continue;
        if (str_contains($p, "/" . $d . "/") || str_ends_with($p, "/" . $d)) return true;
    }
    return false;
}

function bytesHuman(int $b): string {
    if ($b <= 0) return "-";
    $u = ["B","KB","MB","GB","TB"];
    $i = 0;
    $v = (float)$b;
    while ($v >= 1024 && $i < count($u) - 1) { $v /= 1024; $i++; }
    if ($i === 0) return (string)$b . " B";
    return sprintf("%.2f %s", $v, $u[$i]);
}

function readTextSample(string $file): string {
    $size = @filesize($file);
    if ($size === false) $size = 0;
    if ($size <= MAX_SCAN_BYTES) {
        $raw = @file_get_contents($file);
        return is_string($raw) ? $raw : "";
    }
    $fh = @fopen($file, "rb");
    if (!$fh) return "";
    $start = fread($fh, CHUNK_BYTES) ?: "";
    $end = "";
    if ($size > CHUNK_BYTES) {
        @fseek($fh, max(0, $size - CHUNK_BYTES));
        $end = fread($fh, CHUNK_BYTES) ?: "";
    }
    fclose($fh);
    return $start . "\n[TRUNCATED]\n" . $end;
}

function isBinaryLikely(string $data): bool {
    $probe = substr($data, 0, 8192);
    return str_contains($probe, "\0");
}

function scanOneFile(string $file, array $MALWARE_NAMES, array $WEBSHELL_PATTERNS, array $GSOCKET_PATTERNS, array $PHP_EXT, array $TEXT_EXT): ?array {
    $name = strtolower(basename($file));
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $size = (int)@filesize($file);

    foreach ($MALWARE_NAMES as $m) {
        $m = strtolower((string)$m);
        if ($m !== "" && str_contains($name, $m)) {
            return ["category"=>"malware","reason"=>"suspicious name: ".$name,"path"=>$file,"size"=>$size,"preview"=>""];
        }
    }

    $isPhp = in_array($ext, $PHP_EXT, true);
    $isText = in_array($ext, $TEXT_EXT, true);

    if (!$isPhp && !$isText) return null;

    $text = readTextSample($file);
    if ($text === "") return null;
    if (isBinaryLikely($text)) return null;

    if ($isPhp) {
        foreach ($WEBSHELL_PATTERNS as $pr) {
            $re = $pr[0];
            $reason = $pr[1];
            if (@preg_match($re, $text)) {
                $lines = preg_split("/\\R/", $text) ?: [];
                $pv = "";
                foreach ($lines as $ln) {
                    if (@preg_match($re, $ln)) { $pv = trim((string)$ln); break; }
                }
                $pv = sub_s($pv, 0, 160);
                return ["category"=>"webshell","reason"=>$reason,"path"=>$file,"size"=>$size,"preview"=>$pv];
            }
        }
    }

    foreach ($GSOCKET_PATTERNS as $pr) {
        $re = $pr[0];
        $reason = $pr[1];
        if (@preg_match($re, $text)) {
            $lines = preg_split("/\\R/", $text) ?: [];
            $pv = "";
            foreach ($lines as $ln) {
                if (@preg_match($re, $ln)) { $pv = trim((string)$ln); break; }
            }
            $pv = sub_s($pv, 0, 160);
            return ["category"=>"gsocket","reason"=>$reason,"path"=>$file,"size"=>$size,"preview"=>$pv];
        }
    }

    return null;
}

function scanTree(string $root, array $excludeDirs, array $MALWARE_NAMES, array $WEBSHELL_PATTERNS, array $GSOCKET_PATTERNS, array $PHP_EXT, array $TEXT_EXT): array {
    $rootReal = realpath($root);
    if ($rootReal === false) return ["findings"=>[],"counts"=>["webshell"=>0,"gsocket"=>0,"malware"=>0,"total"=>0],"root"=>"","error"=>"Path not found / not accessible."];
    $rootReal = normalizePath($rootReal);

    @set_time_limit(0);

    $findings = [];
    $counts = ["webshell"=>0,"gsocket"=>0,"malware"=>0,"total"=>0];

    $stack = [$rootReal];

    while ($stack) {
        $dir = array_pop($stack);
        if ($dir === null || $dir === "") continue;
        if (shouldSkip($dir, $excludeDirs)) continue;

        $items = @scandir($dir);
        if ($items === false) continue;

        foreach ($items as $it) {
            if ($it === "." || $it === "..") continue;
            $path = $dir . "/" . $it;
            $pathNorm = normalizePath($path);

            if (shouldSkip($pathNorm, $excludeDirs)) continue;
            if (is_link($pathNorm)) continue;

            if (is_dir($pathNorm)) {
                $stack[] = $pathNorm;
                continue;
            }
            if (!is_file($pathNorm)) continue;

            $res = scanOneFile($pathNorm, $MALWARE_NAMES, $WEBSHELL_PATTERNS, $GSOCKET_PATTERNS, $PHP_EXT, $TEXT_EXT);
            if ($res) {
                $findings[] = $res;
                $cat = (string)$res["category"];
                $counts[$cat] = ($counts[$cat] ?? 0) + 1;
                $counts["total"]++;
            }
        }
    }

    return ["findings"=>$findings,"counts"=>$counts,"root"=>$rootReal,"error"=>""];
}

function getPreviewLines(string $file): array {
    $raw = @file_get_contents($file, false, null, 0, PREVIEW_MAX_BYTES);
    if (!is_string($raw)) return [[], true, 0];
    $truncated = ((int)@filesize($file) > strlen($raw));
    if (isBinaryLikely($raw)) return [[], true, 0];
    $lines = preg_split("/\\R/", $raw) ?: [];
    if (count($lines) > PREVIEW_MAX_LINES) {
        $lines = array_slice($lines, 0, PREVIEW_MAX_LINES);
        $truncated = true;
    }
    return [$lines, $truncated, strlen($raw)];
}

function css(): string {
    return ":root{--bg:#070b10;--panel:#0b1220;--panel2:#0c1628;--text:#d7e0ee;--muted:#8aa0bf;--green:#2efc8a;--cyan:#53e3ff;--red:#ff4d6d;--yellow:#ffd166;--border:rgba(83,227,255,.18)}*{box-sizing:border-box}body{margin:0;background:radial-gradient(1200px 600px at 30% 0%, rgba(83,227,255,.10), transparent 60%),radial-gradient(900px 500px at 80% 20%, rgba(46,252,138,.08), transparent 60%),var(--bg);color:var(--text);font-family:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,\"Liberation Mono\",\"Courier New\",monospace}a{color:var(--cyan);text-decoration:none}a:hover{text-decoration:underline}.container{max-width:1200px;margin:0 auto;padding:22px}.topbar{display:flex;gap:14px;flex-wrap:wrap;align-items:center;justify-content:space-between;padding:14px 16px;border:1px solid var(--border);background:rgba(11,18,32,.75);border-radius:16px;backdrop-filter: blur(6px);box-shadow:0 10px 30px rgba(0,0,0,.35)}.brand{display:flex;flex-direction:column;gap:2px}.brand .title{font-weight:800;letter-spacing:.08em;color:var(--cyan)}.brand .sub{font-size:12px;color:var(--muted)}.pill{padding:8px 10px;border:1px solid var(--border);border-radius:999px;background:rgba(12,22,40,.7);color:var(--muted);font-size:12px}.grid{display:grid;grid-template-columns:1.2fr .8fr;gap:14px;margin-top:14px}@media (max-width:980px){.grid{grid-template-columns:1fr}}.card{border:1px solid var(--border);background:rgba(11,18,32,.70);border-radius:16px;padding:14px 16px;box-shadow:0 10px 30px rgba(0,0,0,.35)}.card h3{margin:0 0 8px 0;color:var(--cyan);font-size:14px;letter-spacing:.08em}label{display:block;color:var(--muted);font-size:12px;margin-bottom:6px}input[type=text],input[type=password]{width:100%;padding:10px 12px;border-radius:12px;border:1px solid var(--border);background:rgba(7,11,16,.6);color:var(--text);outline:none}input[type=text]:focus,input[type=password]:focus{border-color:rgba(46,252,138,.55);box-shadow:0 0 0 3px rgba(46,252,138,.12)}.btn{cursor:pointer;border:1px solid var(--border);background:linear-gradient(180deg, rgba(83,227,255,.16), rgba(11,18,32,.2));color:var(--text);padding:10px 12px;border-radius:12px;font-weight:700;letter-spacing:.04em;display:inline-block}.btn:hover{border-color:rgba(83,227,255,.45)}.btn-danger{background:linear-gradient(180deg, rgba(255,77,109,.18), rgba(11,18,32,.2))}.btn-green{background:linear-gradient(180deg, rgba(46,252,138,.18), rgba(11,18,32,.2))}.row{display:flex;gap:10px;flex-wrap:wrap;align-items:center}.notice{padding:10px 12px;border-radius:12px;border:1px dashed rgba(255,209,102,.45);color:var(--yellow);background:rgba(255,209,102,.06)}.err{padding:10px 12px;border-radius:12px;border:1px dashed rgba(255,77,109,.45);color:var(--red);background:rgba(255,77,109,.07)}.ok{padding:10px 12px;border-radius:12px;border:1px dashed rgba(46,252,138,.45);color:var(--green);background:rgba(46,252,138,.06)}.table{width:100%;border-collapse:separate;border-spacing:0;margin-top:12px;overflow:hidden;border-radius:16px;border:1px solid var(--border);table-layout:fixed}.table th,.table td{padding:10px 12px;border-bottom:1px solid rgba(83,227,255,.12);vertical-align:top}.table th{color:var(--muted);font-size:12px;text-transform:uppercase;letter-spacing:.12em;background:rgba(12,22,40,.8)}.table td{overflow-wrap:anywhere}.tag{display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:999px;border:1px solid var(--border);font-size:12px}.tag.webshell{color:var(--red)}.tag.gsocket{color:var(--yellow)}.tag.malware{color:#ff79ff}.small{font-size:12px;color:var(--muted)}.codewrap{border:1px solid var(--border);border-radius:16px;overflow:auto;background:rgba(7,11,16,.65)}.codetable{width:100%;border-collapse:collapse}.codetable td{padding:0}.ln{width:1%;white-space:nowrap;color:rgba(138,160,191,.75);padding:0 10px;border-right:1px solid rgba(83,227,255,.10);user-select:none}.code{padding:0 12px}.line{white-space:pre;tab-size:2}.hit{background:rgba(255,77,109,.12)}.footer{margin-top:16px;color:var(--muted);font-size:12px}.pth{word-break:break-all}.pv{white-space:pre-wrap;word-break:break-all}.modal{position:fixed;inset:0;display:none;z-index:9999;background:rgba(0,0,0,.68);padding:18px;overflow:auto}.modalbox{max-width:1200px;margin:0 auto;border:1px solid var(--border);background:rgba(11,18,32,.96);border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,.6);padding:14px 16px}.modalhead{display:flex;gap:10px;flex-wrap:wrap;align-items:center;justify-content:space-between}.modaltitle{color:var(--cyan);font-weight:900;letter-spacing:.08em;font-size:13px;overflow-wrap:anywhere}.mutebar{display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-top:10px}.loading{opacity:.85}";
}

if (!isAllowedIp()) {
    http_response_code(403);
    echo "Forbidden";
    exit;
}

$action = $_GET["action"] ?? "home";

if ($action === "logout") {
    logout();
    header("Location: ?");
    exit;
}

$loginError = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && $action === "login") {
    if (!loginAttemptOk()) {
        $loginError = "Too many attempts";
    } else {
        $pass = (string)($_POST["password"] ?? "");
        if (login($pass)) {
            header("Location: ?");
            exit;
        }
        $loginError = "Wrong password";
        usleep(250000);
    }
}

if (!authOk() && $action !== "login") {
    $action = "login";
}

if ($action === "api_preview") {
    header("Content-Type: application/json; charset=utf-8");
    if (!authOk()) {
        http_response_code(403);
        echo json_encode(["ok"=>false,"error"=>"forbidden"], JSON_UNESCAPED_UNICODE);
        exit;
    }
    $root = (string)($_SESSION["last_root"] ?? "");
    $file = (string)($_GET["file"] ?? "");
    if ($root === "" || $file === "") {
        http_response_code(400);
        echo json_encode(["ok"=>false,"error"=>"missing"], JSON_UNESCAPED_UNICODE);
        exit;
    }
    $rootReal = realpath($root);
    if ($rootReal === false) {
        http_response_code(400);
        echo json_encode(["ok"=>false,"error"=>"no_root"], JSON_UNESCAPED_UNICODE);
        exit;
    }
    $rootReal = normalizePath($rootReal);

    $fileReal = realpath($file);
    if ($fileReal === false || !is_file($fileReal)) {
        http_response_code(404);
        echo json_encode(["ok"=>false,"error"=>"not_found"], JSON_UNESCAPED_UNICODE);
        exit;
    }
    $fileReal = normalizePath($fileReal);
    if (!pathWithin($fileReal, $rootReal)) {
        http_response_code(403);
        echo json_encode(["ok"=>false,"error"=>"denied"], JSON_UNESCAPED_UNICODE);
        exit;
    }

    [$lines, $trunc, $readBytes] = getPreviewLines($fileReal);
    if (!is_array($lines) || count($lines) === 0) {
        http_response_code(422);
        echo json_encode(["ok"=>false,"error"=>"no_preview"], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $ext = strtolower(pathinfo($fileReal, PATHINFO_EXTENSION));
    $patterns = [];
    if (in_array($ext, $PHP_EXT, true)) {
        $patterns = array_merge($WEBSHELL_PATTERNS, $GSOCKET_PATTERNS);
    } else {
        $patterns = $GSOCKET_PATTERNS;
    }

    $hits = [];
    $ln = 0;
    foreach ($lines as $line) {
        $ln++;
        foreach ($patterns as $pr) {
            $re = $pr[0];
            if (@preg_match($re, (string)$line)) { $hits[] = $ln; break; }
        }
    }

    echo json_encode([
        "ok"=>true,
        "root"=>$rootReal,
        "file"=>$fileReal,
        "ext"=>$ext,
        "truncated"=>$trunc ? 1 : 0,
        "readBytes"=>$readBytes,
        "hits"=>$hits,
        "lines"=>$lines
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

$result = null;
$scanError = "";
$root = (string)($_SESSION["last_root"] ?? "");

if ($action === "scan" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $inputRoot = trim((string)($_POST["root"] ?? ""));
    if ($inputRoot === "") $inputRoot = $root;

    $inputRootReal = realpath($inputRoot);
    if ($inputRootReal === false || !is_dir($inputRootReal)) {
        $scanError = "Path tidak valid atau tidak bisa diakses: " . $inputRoot;
    } else {
        $inputRootReal = normalizePath($inputRootReal);
        if (!isAllowedRoot($inputRootReal)) {
            $scanError = "Path tidak termasuk ALLOWED_ROOTS";
        } else {
            $_SESSION["last_root"] = $inputRootReal;
            $root = $inputRootReal;
            $result = scanTree($root, $EXCLUDE_DIRS, $MALWARE_NAMES, $WEBSHELL_PATTERNS, $GSOCKET_PATTERNS, $PHP_EXT, $TEXT_EXT);
            if (!empty($result["error"])) $scanError = (string)$result["error"];
            $_SESSION["last_scan"] = $result;
        }
    }
}

?><!doctype html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>SCANNER</title>
<style><?= css() ?></style>
</head>
<body>
<div class="container">
<div class="topbar">
  <div class="brand">
    <div class="title">SCANNER</div>
    <div class="sub">Scan webshell · gsocket · suspicious names · preview code</div>
  </div>
  <div class="row">
    <?php if (authOk()): ?>
      <span class="pill">Target: <b><?= h($root ?: "(belum dipilih)") ?></b></span>
      <a class="btn btn-danger" href="?action=logout">Logout</a>
    <?php else: ?>
      <span class="pill">Auth: <b>LOCKED</b></span>
    <?php endif; ?>
  </div>
</div>

<?php if ($action === "login"): ?>
<div class="grid">
  <div class="card">
    <h3>ACCESS GATE</h3>
    <div class="notice">Masukkan password untuk membuka scanner</div>
    <form method="post" action="?action=login" style="margin-top:12px">
      <label>Password</label>
      <input type="password" name="password" required />
      <div class="row" style="margin-top:10px">
        <button class="btn btn-green" type="submit">UNLOCK</button>
        <span class="small">Attempts: <?= (int)($_SESSION["scanner_attempts"] ?? 0) ?> / <?= MAX_LOGIN_ATTEMPTS ?></span>
      </div>
    </form>
    <?php if ($loginError): ?>
      <div class="err" style="margin-top:12px"><?= h($loginError) ?></div>
    <?php endif; ?>
  </div>
  <div class="card">
    <h3>NOTES</h3>
    <div class="small">Saran: isi IP_ALLOWLIST dan hapus file ini setelah selesai</div>
  </div>
</div>
<?php endif; ?>

<?php if (authOk()): ?>
<div class="grid">
  <div class="card">
    <h3>SCAN TARGET</h3>
    <form method="post" action="?action=scan">
      <label>Path yang mau discan</label>
      <input type="text" name="root" value="<?= h($root ?: "") ?>" placeholder="/home/user/public_html" />
      <div class="row" style="margin-top:10px">
        <button class="btn btn-green" type="submit">START SCAN</button>
        <span class="small">Allowed roots: <?= h(implode(", ", ALLOWED_ROOTS)) ?></span>
      </div>
    </form>
    <?php if ($scanError): ?>
      <div class="err" style="margin-top:12px"><?= h($scanError) ?></div>
    <?php endif; ?>
    <div class="footer">Scan per-folder kalau directory besar</div>
  </div>

  <div class="card">
    <h3>FILTER</h3>
    <label>Search</label>
    <input id="q" type="text" placeholder="cari path / reason / preview..." oninput="applyFilter()" />
    <div class="row" style="margin-top:10px">
      <button class="btn" type="button" onclick="setCat('all')">All</button>
      <button class="btn" type="button" onclick="setCat('webshell')">Webshell</button>
      <button class="btn" type="button" onclick="setCat('gsocket')">Gsocket</button>
      <button class="btn" type="button" onclick="setCat('malware')">Name</button>
      <span class="pill" id="stat"></span>
    </div>
  </div>
</div>
<?php endif; ?>

<?php if (authOk() && $action === "scan" && $result && empty($scanError)): ?>
<?php $c = $result["counts"]; ?>
<div class="card" style="margin-top:14px">
  <h3>SUMMARY</h3>
  <div class="row">
    <span class="tag webshell">WEB SHELL: <b><?= (int)$c["webshell"] ?></b></span>
    <span class="tag gsocket">GSOCKET: <b><?= (int)$c["gsocket"] ?></b></span>
    <span class="tag malware">NAME HIT: <b><?= (int)$c["malware"] ?></b></span>
    <span class="pill">TOTAL: <b><?= (int)$c["total"] ?></b></span>
  </div>

  <?php if (empty($result["findings"])): ?>
    <div class="ok" style="margin-top:12px">No findings</div>
  <?php else: ?>
    <table class="table" id="tbl">
      <colgroup>
        <col style="width:10%">
        <col style="width:16%">
        <col style="width:34%">
        <col style="width:8%">
        <col style="width:24%">
        <col style="width:8%">
      </colgroup>
      <thead>
        <tr>
          <th>Category</th>
          <th>Reason</th>
          <th>Path</th>
          <th>Size</th>
          <th>Preview</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($result["findings"] as $f): ?>
        <?php
          $cat = (string)$f["category"];
          $path = (string)$f["path"];
          $reason = (string)$f["reason"];
          $pv = (string)$f["preview"];
        ?>
        <tr data-cat="<?= h($cat) ?>">
          <td><span class="tag <?= h($cat) ?>"><?= h(strtoupper($cat)) ?></span></td>
          <td><?= h($reason) ?></td>
          <td class="small pth"><?= h($path) ?></td>
          <td class="small"><?= h(bytesHuman((int)$f["size"])) ?></td>
          <td class="small pv"><?= h(sub_s($pv, 0, 180)) ?></td>
          <td><button class="btn" type="button" data-file="<?= h($path) ?>" onclick="openPreview(this.getAttribute('data-file'))">Preview</button></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>

    <script>
      let currentCat = "all";
      function setCat(c){ currentCat=c; applyFilter(); }
      function applyFilter(){
        const q = (document.getElementById("q")?.value || "").toLowerCase();
        const rows = document.querySelectorAll("#tbl tbody tr");
        let shown=0, total=0;
        rows.forEach(r=>{
          total++;
          const cat = r.getAttribute("data-cat");
          const txt = r.innerText.toLowerCase();
          const okCat = (currentCat==="all" || cat===currentCat);
          const okQ = (!q || txt.includes(q));
          const show = okCat && okQ;
          r.style.display = show ? "" : "none";
          if (show) shown++;
        });
        const stat = document.getElementById("stat");
        if(stat) stat.textContent = `Showing ${shown}/${total}`;
      }
      applyFilter();
    </script>
  <?php endif; ?>
</div>
<?php endif; ?>

<?php if (authOk() && $action === "home"): ?>
<div class="card" style="margin-top:14px">
  <h3>READY</h3>
  <div class="small">Masukkan path, lalu START SCAN</div>
</div>
<?php endif; ?>

<div id="m" class="modal" role="dialog" aria-modal="true">
  <div class="modalbox" onclick="event.stopPropagation()">
    <div class="modalhead">
      <div class="modaltitle" id="mTitle">PREVIEW</div>
      <div class="row">
        <span class="pill" id="mInfo"></span>
        <button class="btn btn-danger" type="button" onclick="closeModal()">Close</button>
      </div>
    </div>
    <div id="mWarn" class="notice" style="display:none"></div>
    <div class="codewrap" style="margin-top:10px">
      <table class="codetable">
        <tbody id="mBody"></tbody>
      </table>
    </div>
  </div>
</div>

<script>
  function escHtml(s){
    return String(s).replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;").replace(/'/g,"&#39;");
  }
  function closeModal(){
    const m = document.getElementById("m");
    if(m) m.style.display = "none";
    const b = document.getElementById("mBody");
    if(b) b.innerHTML = "";
    const w = document.getElementById("mWarn");
    if(w){ w.style.display="none"; w.textContent=""; }
  }
  async function openPreview(file){
    const m = document.getElementById("m");
    const t = document.getElementById("mTitle");
    const i = document.getElementById("mInfo");
    const b = document.getElementById("mBody");
    const w = document.getElementById("mWarn");
    if(!m || !t || !i || !b || !w) return;

    m.style.display = "block";
    t.textContent = "PREVIEW";
    i.textContent = "Loading...";
    w.style.display = "none";
    w.textContent = "";
    b.innerHTML = "<tr><td class='ln'> </td><td class='code'><div class='line loading'>Loading...</div></td></tr>";

    const url = "?action=api_preview&file=" + encodeURIComponent(file);
    let data = null;
    try{
      const r = await fetch(url, {credentials:"same-origin"});
      data = await r.json();
    }catch(e){
      i.textContent = "Error";
      b.innerHTML = "<tr><td class='ln'> </td><td class='code'><div class='line'>Failed to load</div></td></tr>";
      return;
    }

    if(!data || !data.ok){
      i.textContent = "Error";
      b.innerHTML = "<tr><td class='ln'> </td><td class='code'><div class='line'>No preview</div></td></tr>";
      return;
    }

    t.textContent = data.file || "PREVIEW";
    i.textContent = (data.ext ? data.ext.toUpperCase() : "") + " · " + (data.readBytes || 0) + " bytes";
    const hitSet = new Set((data.hits || []).map(x=>Number(x)));

    if (Number(data.truncated) === 1) {
      w.style.display = "block";
      w.textContent = "Preview dipotong (limit).";
    } else {
      w.style.display = "none";
      w.textContent = "";
    }

    const lines = data.lines || [];
    let out = "";
    for(let idx=0; idx<lines.length; idx++){
      const ln = idx + 1;
      const hit = hitSet.has(ln);
      const cls = hit ? "line hit" : "line";
      out += "<tr><td class='ln'>" + ln + "</td><td class='code'><div class='" + cls + "'>" + escHtml(lines[idx]) + "</div></td></tr>";
    }
    b.innerHTML = out;
  }

  document.getElementById("m")?.addEventListener("click", closeModal);
  document.addEventListener("keydown", (e)=>{ if(e.key==="Escape") closeModal(); });
</script>

</div>
</body>
</html>
