<?php
/**
 * 默认接口服务类
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class Api_Default extends Api_Base {

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
	    exit;
        // 判断参数有效性
        $param = $this->params;
        if(!array_key_exists("serial_no", $param)
                || !array_key_exists("key", $param)){
            $this->response = array("ret" => -1, "msg" => "invalide paramter");
            $this->doResponse();
            exit;
        }
        // 创建会话
        $store = new SessionStore;
        $store->sid = $param["serial_no"];
        $store->des_key = $param["key"];
        
        // 查找用户
        global $caller;
        if(!array_key_exists($store->sid, $caller)){
            $json = array("ret" => -1, "msg" => "Serial number not find");
            return;
        }
        // 写入串号的值
        $store->login_flag = $caller[$store->sid];
        // 定入OEM_TYPE
        if(array_key_exists("oem_type", $param) && is_string($param['oem_type'])){
            $store->oemtype = $param['oem_type'];
            $store->package_oem = $param['oem_type'];
        }
        // 检测语言标志
        if (isset($param['lang']) && !empty($param['lang'])) {
            $store->lang = $param['lang'];
        } else {
            $store->lang = 'chs';
        }
        // 把token存入内存
        while(true){
             $token = $this->createToken();
            if($this->session->memcacheCheck($token)){
                $this->session->token = $token;
                break;
            }
        }
        // 构建返回
        $this->session->store = $store;
        
        $this->saveSession();
        $a = array("ret" => 0, "msg" => "ok", 
            "token" => $this->session->token);
        $this->response = array_merge($this->response, $a);
        $this->doResponse();exit;
    }
    
    private function createToken(){
        $token = $_SERVER["REMOTE_ADDR"] . ":" . $_SERVER["REMOTE_PORT"] . "_";
        $token .= time() . "_" . uniqid();
        $token = base64_encode(md5($token, true));
        return $token;
    }
    
    public function saveSession() {
        $value = serialize($this->session->store);
        $a = is_string($this->session->token);
        $b = is_string($value);
        if($a && $b){
            $this->session->save($this->session->token, $value);
        }
        return true;
    }
}
