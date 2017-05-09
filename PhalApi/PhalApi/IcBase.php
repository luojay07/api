<?php
/**
 * Created by Jay.
 * Date: 2017/5/8
 * Time: 18:06
 */
class IcBase {
    protected $is_get = false;
    protected $set_auth = false;
    public $retValue = array("ret" => 0, "msg" => "Success");
    public $lang = 'chs';

    // 所有接口的入口
    public function _enter(&$session, &$request, &$response) {
        if (0 != ENABLE_DEBUG) {
            $request_save = $request;
            $t = gettimeofday();
            $use_time = $t["sec"] * 1000000 + $t["usec"];
        }
        // 调用原来的接口
        if (isset($session->store->lang)) {
            $this->lang = $session->store->lang;
        }

        $this->doEnter($session, $request, $response);
        if (0 == ENABLE_DEBUG || !is_a($session->store, "SessionStore")) {
            return;
        }
        // 计算时间
        $t = gettimeofday();
        $use_time = ($t["sec"] * 1000000 + $t["usec"]) - $use_time;
        $use_time = $use_time / 1000000.0;
        // 打印调试信息
        global $dbg_mysql;
        include_once(dirname(__FILE__) . "/log.php");
        // 写文件日志
        ic_log(strftime("%Y-%m-%d %H:%M:%S"), // 时间
            $session->token, // token
            $session->store->sid,  //
            $session->store->user, // 用户
            $session->store->device, // 设备
            $use_time, // 使用时间
            $_SERVER["REDIRECT_URL"], // 接口
            $_SERVER["REMOTE_ADDR"], // 远程主机
            json_encode($request_save), // 保存请求
            json_encode($response), // 保存退出
            $response["ret"]);
    }

    // 所有接口调用的基类
    public function doEnter(&$session, &$request, &$response) {
        global $error_code;
        // 是否为GET请求
        if ($this->is_get) {
            // 调用处理函数
            if (method_exists($this, "onPageLoad")) {
                $this->{"onPageLoad"}($session->store);
            }
            // 获取返回值
            $response = $this->retValue;
            return;
        }
        // 如果为POST请求判断请求的数据是否为空
        if (!is_array($request)) {
            return;
        }
        // 处理POST请求 TODO:等易强确认加密方式
        if (method_exists($this, "checkPost")) {
            if (false == $this->{"checkPost"}($request)) {
                $response = array("ret" => -5, "msg" => $error_code["-5"][$this->lang]);
                return;
            }
        }
        // 验证授权
        if (method_exists($this, "checkAuthorize")) {
            $store = $session->store;
            if (false == $this->{"checkAuthorize"}($store)) {
                $response = array("ret" => -5, "msg" => $error_code["-5"][$this->lang]);
                return;
            }
        }
        // 检测参数是否合法
        if (method_exists($this, "checkParamter")) {
            $err = $this->{"checkParamter"}($request);
            if (null != $err) {
                // 参数格式不对
                $response = array("ret" => -35, "msg" => $err);
                return;
            }
        }
        // 调用处理函数
        if (!method_exists($this, "onRequest")) {
            $response = array("ret" => -10, "msg" => $error_code["-10"][$this->lang]);
            return;
        }
        // 是否为创建授权
        if ($this->set_auth) {
            $this->{"onRequest"}($request, $session);
            if (method_exists($this, "saveSession")) {
                $this->{"saveSession"}($session);
            }
        } else {
            $store = $session->store;
            try {
                $this->{"onRequest"}($request, $store);
            } catch (SQLConnectException $e) {
                // 数据库连接异常
                $this->retValue = array("ret" => -36,
                                        "msg" => $error_code["-36"][$this->lang]);
            }
        }
        // 获取返回
        $response = $this->retValue;
        if ($response['ret'] === 0) {
            $response['msg'] = $error_code[0][$this->lang];
        }
    }

    // 检测权限-bind_id
    function checkBindId(&$db, &$session, &$bind_id) {
        $sql = new SQLString();
        $sql->format("select count(*) from com_user_devices as a " .
            " left join com_user_base as b using(user_id) " .
            " where bind_id={0} and user_name in ", $bind_id);
        $sql->append($session->real_users);
        $res = $db->query($sql);
        if (1 != count($res) || 1 != $res[0][0]) {
            global $error_code;
            $this->retValue = array("ret" => -20, "msg" => $error_code["-20"][$this->lang]);
            return false;
        }

        return true;
    }

    // 检测权限-用户名
    function checkUserName($session, $user_name) {
        $real_user = $session->real_user;
        if (0 != strcasecmp($real_user, $user_name)) {
            global $error_code;
            $this->retValue = array("ret" => -20, "msg" => $error_code["-20"][$this->lang]);
            return false;
        }

        return true;
    }

    /**
     * 判断用户是否有找回密码的权限
     * @param type $db
     * @param type $username 用户名
     * @return true 有权限可修改   false 没有权限修改
     */
    public function checkModifyPwdRight(&$db, $username) {
        $sql = new SQLString();
        $sql_string = "select advance_setting from sch_school s LEFT JOIN sch_user_detail d ON s.school_id=d.school_id "
            . "LEFT JOIN com_user_base b on d.base_id=b.user_id WHERE b.user_name='{0}'";
        $sql->format($sql_string, $username);
        $res = $db->query($sql);
        if (!empty($res) && isset($res[0][0])) {
            $advance_setting = json_decode($res[0][0], TRUE);
            if (isset($advance_setting['is_modify_pwd']) && $advance_setting['is_modify_pwd'] == 1) {
                return TRUE;
            }
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 获取返回语言数组
     * @param        $code
     * @param array $data
     * @param string $msg
     */
    function setRetValue($code, $data = array(), $msg = '') {
        global $error_code;
        $ret = array('ret' => $code, 'msg' => $msg ? $msg : (isset($error_code[$code]) ? $error_code[$code][$this->lang] : ''));
        $this->retValue = $data ? array_merge($ret, $data) : $ret;
    }
}