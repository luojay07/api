<?php
/**
 * 会话存储类
 */
class SessionStore{
    public $sid;                // 访问者的串号
    public $user;               // 系统用户名
    public $login_flag;         // 登录标识
    public $device;             // 访问设备ID
    public $des_key;            // des密要
    public $seq = 0x0;          // 调用编号
    public $oemtype;            // 所属产品
    public $user_id;            //当前登录者用户id
    public $school_id;          //校园版:当前登录者（教师,父母,学生）所在学校id
    public $is_admin;           //校园版:当前登录教师是否超管
    public $sch_admin_level;    // 学校管理员级别 1 普通  9超管
    public $real_user;          // 真实用户名（新绑定关系允许使用绑定手机登陆）
    public $real_users;         // 真实用户名
    public $login_oem;          // 登陆OEM字符串
    public $package_oem;        // 登录包OEM
    public $lang;               // 语言类型： chs,cht,enu
    public $synchronize_flag=0;  //是否允许同步数据，为了保证旧数据可以使用，0-默认允许  1-不允许
    public $verify_code;  //验证码
}