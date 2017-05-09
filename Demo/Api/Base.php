<?php
class Api_Base extends PhalApi_Api{
    /**
     * 时间数值
     * @var float
     */
    protected $t = 0;

    /**
     * 构造方法
     */
    public function __construct()
    {
        $t = gettimeofday();
        $this->t = $t["sec"] * 10000000 + $t["usec"];
    }

    /**
     * 保存Token
     */
    protected function saveToken()
    {
        $value = serialize(DI()->tokenHandler->store);
        $a = is_string(DI()->tokenHandler->token);
        $b = is_string($value);
        if($a && $b){
            DI()->tokenHandler->save(DI()->tokenHandler->token, $value);
        }
        return true;
    }

    /**
     * 返回数据格式
     * @param array $data
     */
    protected function retData($data)
    {
        
        if (isset($data['ret']) && $data['ret'] === 0) {
            $t = gettimeofday();
            $this->t = ($t["sec"] * 10000000 + $t["usec"]) - $this->t;
            $data["_time_"] = $this->t / 10000000.0;
        }
        return $data;
    }
    
}