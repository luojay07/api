<?php 
/**
 * 以下配置为系统级的配置，通常放置不同环境下的不同配置
 */

return array(
	/**
	 * 默认环境配置
	 */
	'debug' => true,

    'actionName' => 'index',
    
	/**
	 * MC缓存服务器参考配置
	 */
	 'mc' => array(
        'host' => '172.168.50.180',
        'port' => 11211,
	 ),

    /**
     * 加密
     */
    'crypt' => array(
        'mcrypt_iv' => '12345678',      //8位
    ),
);
