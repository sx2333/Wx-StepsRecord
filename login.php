<?php
$code = $_REQUEST['code'];
$url ='https://api.weixin.qq.com/sns/jscode2session?appid=wx5ae850173812258d&secret=f677b1d77ce5c8bb12087e29f910cce9&js_code='.$code.'&grant_type=authorization_code';
$content = file_get_contents($url);
$content = json_decode($content);
echo $content->session_key;