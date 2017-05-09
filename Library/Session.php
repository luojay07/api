<?php
/**
 * 会话类
 */
class Session {
    private $memcache;
    public $token;
    public $store = null;
    public $user_login_count = array();

    /**
     * 构造函数
    */
    function __construct() {
        if (isset($_SERVER['HTTP_TOKEN'])) {
            $this->token = $_SERVER['HTTP_TOKEN'];
            $save = DI()->cache->get($this->token);
            if ($save) {
                $store = unserialize($save);
                if ($store instanceof SessionStore) {
                    $this->store = $store;
                }
            }
        }
        
    }
    /**
     * save
     */
    public function save(&$key, &$value,$timeout = TOKEN_TIME_OUT) {
        DI()->cache->set($key, $value, $timeout);
    }
    
    /**
     * 检测是否已登录
     */
    public function isLogin(){
        return $this->store instanceof SessionStore;
    }
    /**
     * 内存检测
     */
    public function memcacheCheck($key){
        return !DI()->cache->get($key);
        //return !$this->memcache->get($key);
    }
    /**
     * 获取用户的DES密要
     */
    public function getDesKey(){
        if ($this->store instanceof SessionStore && is_string($this->store->des_key)) {
            return $this->store->des_key;
        }
        return null;
    }


    /**
     * 获取键值
     */
    public function getKeyValue($key){
        if (null != $key && is_object($this->memcache)) {
            return $this->memcache->get($key);
        }

        return null;
    }

}