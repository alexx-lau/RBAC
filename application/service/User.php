<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/24
 * Time: 15:08
 */

namespace app\service;
use app\common\model\User as UserModel;

class User {

    public function editUserInfo(array $args = null) {
        if(empty($args)) {
            return false;
        }
        dump($args);
    }
}