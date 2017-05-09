<?php
/*
 * 这是一个系统固定配置文件
 */
define("TOKEN_TIME_OUT", 60 * 60 * 3);
// 在这里添加调用都名串号名单
// 0 账号被停用, 1系统管理账号, 2 web账号, 3 android账号,
// 4 PC账号, 5 iphone账号, 6 windowsphone账号
define("LOGIN_FLAG_DISABLE", 0x0);
define("LOGIN_FLAG_SYSTEM", 0x1);
define("LOGIN_FLAG_WEB", 0x2);
define("LOGIN_FLAG_ANDROID", 0x4);
define("LOGIN_FLAG_PC", 0x8);
define("LOGIN_FLAG_IPHONE", 0x10);
define("LOGIN_FLAG_WINDOWSPHONE", 0x20);
define("LOGIN_FLAG_ANDROID_TEACHER", 0x40);
define("LOGIN_FLAG_ANDROID_PARENT", 0x80);
define("LOGIN_FLAG_WEB_SCHSTUDENT", 0x100);
define("LOGIN_FLAG_WEB_SCHTEACHER", 0x200);
define("LOGIN_FLAG_WEB_SCHPARENT", 0x400);
define("LOGIN_FLAG_WEB_BMS", 0x800);
define("LOGIN_FLAG_WEB_WX", 0x1000);
define("LOGIN_FLAG_PC_TEACHER", 0x2000);

$caller = array(
    "sn-pc-client" => LOGIN_FLAG_PC,
    "sn-pc-teacher" => LOGIN_FLAG_PC_TEACHER,
    "sn-iphone-client" => LOGIN_FLAG_IPHONE,
    "sn-windowsphone-client" => LOGIN_FLAG_WINDOWSPHONE,
    "sn-android-client" => LOGIN_FLAG_ANDROID,
    "sn-android-teacher" => LOGIN_FLAG_ANDROID_TEACHER,
    "sn-android-parent" => LOGIN_FLAG_ANDROID_PARENT,
    "sn-web-server" => LOGIN_FLAG_WEB,
    "sn-web-schstudent" => LOGIN_FLAG_WEB_SCHSTUDENT,
    "sn-web-schteacher" => LOGIN_FLAG_WEB_SCHTEACHER,
    "sn-web-schparent" => LOGIN_FLAG_WEB_SCHPARENT,
    "sn-web-bms" => LOGIN_FLAG_WEB_BMS,
    "sn-web-wx" => LOGIN_FLAG_WEB_WX
    );

// rsa 私要
define("RSA_PRIVATE_KEY", <<<eof
-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQDbA9Qt9LlBDHEfazO/WuGJYahtuOJihMmLcBlJwW22EJaIQUt3
ZLkiKEdp+gB5nFgBqCBqaXO3ppbyeGZ/xFEVTwPygR5eD71gfmT0xLuOrogxLO/g
PdF8C/Y8KZL3gUXM2VMscENIYicFcHD9kZ2Uddu/tM+xgScMb2vUkMH/ZwIDAQAB
AoGBAMUjoianpOUffOfKCC9Tb7XImOICzIvfeMcxZSHVoZqDPexx0asrl9VeKkID
TdApYbJEEsGWIBoMMs0YgTK6lDwOMBPpwLHsBzS2UdAJ2TOmllxJX9Wh2rH0MCpn
Ps+/5m4vyuIX51eTAjlY6LPZeD+oUXSOT68c7quRMeuv6KHhAkEA+OMGuvLNBpQ9
D6i3gaLM0YPkfMQC6vEMRhVVU5gp5RWfSwW/Uze0O9NzaDIYsTtoJW+mTWBm2tKt
93vlkm/kMQJBAOFGPu96BAZJjEoD6mk7guZ9fTGLWGtLYjdnMriwxNLWKQqq7cAJ
O7TLJLqfSqhkmTGBkoUsXAKNY8j/69xnrxcCQB1EvXbgtMGwTxn3UlU5avK8mvMd
LeapIDNhsN4ax9p713bAH0bPBy/95tV7BrJY9R9p6Nmym4XiPrka5d15sRECQDqU
W7VGU1mXMoXkssYelZF+PFnv1FRCTA4AJe5k+SmiSxXdEOoI/J+s26Yz2eQkFLoM
6Z77xAld3v7bnee4ho0CQQDRC+u6QvGkdqEzPVxJetKMcahekNqKGe/VF39wJtK2
W/deT3ZWFnezV662pobrAkgpwGVQjxZCYh+8jzmM3vUs
-----END RSA PRIVATE KEY-----
eof
);

// 用户登录次数统计内存标识
define('LOGIN_COUNT_HEAD', 'login_count_prefix::');

// 设置默认分页大小
define('DEFAULT_PAGE_SIZE', 200);

// 命令
define('CMD_REQ_RAND', 0x1);
define('CMD_RES_PUSH', 0x7);
