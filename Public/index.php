<?php
/**
 * $APP_NAME 统一入口
 */
echo 1;die;
require_once dirname(__FILE__) . '/init.php';
require_once dirname(__FILE__) . '/define.php';

//装载你的接口
DI()->loader->addDirs('Demo');

/** ---------------- 响应接口请求 ---------------- **/

$api = new PhalApi();
$rs = $api->response();
$rs->output();

