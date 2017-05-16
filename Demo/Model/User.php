<?php

class Model_User extends PhalApi_Model_NotORM {

    //自定义数据库名 如果没有定义默认是user表
    protected function getTableName($id) {
        return 'com_user_base';
    }

    public function getByUserId($userId) {
        return $this->getORM()
            ->select('*')
            ->where('user_id = ?', $userId)
            ->fetch();
    }

    public function getByUserIdWithCache($userId) {
        $key = 'userbaseinfo_' . $userId;
        $rs = DI()->cache->get($key);
        if ($rs === NULL) {
            $rs = $this->getByUserId($userId);
            DI()->cache->set($key, $rs, 600);
        }
        return $rs;
    }

    /**
     * protected function getTableName($id) {
     * return 'user';
     * }
     */
}
