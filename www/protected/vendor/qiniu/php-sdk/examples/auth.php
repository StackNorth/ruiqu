<?php
require_once __DIR__ . '/../autoload.php';

use Qiniu\Auth;

// 用于签名的公钥和私钥. http://developer.qiniu.com/docs/v6/api/overview/security.html#aksk
$accessKey = 'Kn8GNMFOLKTNMUaKZ6r1wnjsgTk4ideQifK3umUr';
$secretKey = 'mLtD4GhBjQt_llcgx4rKlhAts9j8iJ0Qa5VmNyi2';

// 初始化签权对象。
$auth = new Auth($accessKey, $secretKey);
