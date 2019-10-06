<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/7
 * Time: 15:32
 */

namespace app\service;

use app\common\model\Role as RoleModel;

/**
 * Class Role
 * @package app\service
 * @desc 处理角色相关的服务控制器
 */
class Role {

    /**
     * @desc 获取角色列表
     * @return bool|\think\Paginator
     * @throws \think\exception\DbException
     */
    public function getRoleList($arg = null) {


        $model = new RoleModel();
        if(!empty($arg)) {
            if($res = $model->getRoleList($arg)) {
                return $res;
            }
            return false;
        }
        if($res = $model->getRoleList()) {
            return $res;
        }
        return false;
    }

    public static function initUserRole(int $uid = null) {

        if(empty($uid)) {
            return false;
        }
        if(false !== ($rId = RoleModel::field('id')
                    ->where('r_name', '=', '初级用户')
                    ->find()->toArray())) {
            $user = new \app\common\model\User();
            $param = array('u_id'=>$uid, $rId['id']);
//           var_dump($rId);exit;
            return $user->bindRole($param);
        }
        return false;
    }
}