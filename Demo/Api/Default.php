<?php
/**
 * 默认接口服务类
 */

class Api_Default extends PhalApi_Api {

	public function getRules() {
        return array(
            'index' => array(
                'username' 	=> array('name' => 'username', 'default' => 'PHPer', ),
            ),
        );
	}
	
	/**
	 * 默认接口服务
	 * @return string title 标题
	 * @return string content 内容
	 * @return string version 版本，格式：X.X.X
	 * @return int time 当前时间戳
	 */
	public function index() {
        return array(
            'title' => 'Hello World! -Jay',
            'content' => T('欢迎使用绿网管控接口中心'),
            'time' => $_SERVER['REQUEST_TIME'],
        );
	}
}
