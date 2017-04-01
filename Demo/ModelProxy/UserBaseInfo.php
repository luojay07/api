<?php
/**
 * @author Jay
 */

class ModelProxy_UserBaseInfo extends PhalApi_ModelProxy {

	protected function doGetData($query) {
		$model = new Model_User();

		return $model->getByUserId($query->id);
	}

	protected function getKey($query) {
		return 'userbaseinfo_' . $query->id;
	}

	protected function getExpire($query) {
		return 600;
	}
}
