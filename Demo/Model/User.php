<?php

class Model_User extends PhalApi_Model_NotORM {

    protected function getTableName($id) {
        return 'user_base';
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
}
