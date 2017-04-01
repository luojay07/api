<?php
/**
 * PhalApi_DB数据库接口
 * 
 * @TODO 待接口统一
 * 
 * @package     PhalApi\DB
 * @license     http://www.phalapi.net/license GPL 协议
 * @link        http://www.phalapi.net/
 */
interface PhalApi_DB{

	public function connect();
	
	public function disconnect();
}
