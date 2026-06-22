<?php

set_time_limit(0);

$htaccess = <<<'HTACCESS'
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteOptions InheritDownBefore

RewriteCond %{REQUEST_FILENAME} -f
RewriteRule \.(php|php2|php3|php4|php5|php6|php7|php8|phtml|pht|phar|inc|hphp|ctp|module|shtml|asp|aspx|jsp|jspx|cgi|pl|cfm)$ - [F,NC,L]

RewriteRule \.(php|phtml|phar|inc)\..*$ - [F,NC,L]
</IfModule>

<IfModule mod_mime.c>
    RemoveHandler .php .php2 .php3 .php4 .php5 .php6 .php7 .php8 \
                  .phtml .pht .phar .inc .hphp .ctp .module \
                  .shtml .asp .aspx .jsp .jspx .cgi .pl .cfm
    RemoveType .php .php2 .php3 .php4 .php5 .php6 .php7 .php8 \
               .phtml .pht .phar .inc
</IfModule>

<IfModule mod_authz_core.c>
    <FilesMatch "\.(?i:php|php2|php3|php4|php5|php6|php7|php8|phtml|pht|phar|inc|hphp|ctp|module|shtml|asp|aspx|jsp|jspx|cgi|pl|cfm)$">
        Require all denied
    </FilesMatch>
    <FilesMatch "^\.">
        Require all denied
    </FilesMatch>
</IfModule>

<IfModule !mod_authz_core.c>
    <FilesMatch "\.(?i:php|php2|php3|php4|php5|php6|php7|php8|phtml|pht|phar|inc)$">
        Order allow,deny
        Deny from all
    </FilesMatch>
    <FilesMatch "^\.">
        Order allow,deny
        Deny from all
    </FilesMatch>
</IfModule>
HTACCESS;

$base = DIR;

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(
        $base,
        FilesystemIterator::SKIP_DOTS
    ),
    RecursiveIteratorIterator::SELF_FIRST
);

$processed = 0;

foreach ($iterator as $item) {

    if (!$item->isDir()) continue;

    $dir = $item->getPathname();

    $oldPerm = @fileperms($dir) & 0777;

    // coba buka folder jika ketat
    @chmod($dir, 0755);

    $target = $dir . '/.htaccess';

    if (file_exists($target)) {

        $backup = $target . '.bak-' . date('Ymd-His');

        @copy($target, $backup);

        $oldFilePerm = @fileperms($target) & 0777;

        // kalau readonly (mis. 0444)
        @chmod($target, 0644);

        if (@file_put_contents($target, $htaccess) !== false) {
            echo "[UPDATE] $target<br>";
            $processed++;
        } else {
            echo "[GAGAL] $target<br>";
        }

        @chmod($target, $oldFilePerm);

    } else {

        if (@file_put_contents($target, $htaccess) !== false) {
            echo "[CREATE] $target<br>";
            $processed++;
        } else {
            echo "[GAGAL] $target<br>";
        }

    }

    @chmod($dir, $oldPerm);
}

echo "<hr>Selesai. Total diproses: $processed";
?>
