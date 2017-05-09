<?php
class Api_User_Authorize_Login extends Api_Base{
    public function index() {
        $param = DI()->request->getAll();
        if (!array_key_exists('oem_type', $param) || null == $param["oem_type"]) {
            return $this->retData(['ret' => -1, 'msg' => 'oem_type is empty or does not exist']);
        }
        // 登陆模式
        if (!array_key_exists('mode', $param)) {
            return $this->retData(['ret' => -1, 'msg' => 'mode is empty or does not exist']);
        }
        
        $res = array("ret"       => 0, "msg" => "sucess", "bind_id" => 0, "first_login" => 0, "user_id" => '150581990',
            "mark"      => 1, "user_name" => '15394469873', "is_guardian" => 0,
            'user_list' => array('15394469873'), 'nick_name' => '', 'service_time' => date('Y-m-d H:i:s'), 'max_equip_num' => 1);
        return $this->retData($res);
    }
    
}