<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/16
 * Time: 10:26
 */

namespace app\service;
use app\common\model\Role;

/**
 * Class GetRoleUrls
 * @package app\service
 * @desc 获取角色url对应关系的服务控制器
 */
class GetRoleUrls {


    /**
     * @desc 根据用户信息获取角色url对应关系
     * @return array|bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRoleUrls(string $arg = '') {
        $username = session($arg . 'userinfo')['username'];
        $user = new \app\common\model\User();
        $param['username'] = $username;
        $param['need_id'] = 1;
        if(false !== ($roles = $user->getRoleByUser($param))) {
            $role = new Role();
            $param = $roles['r_id'];
            if(false !== ($urls = $role->getUrlByRole($param))) {
                return $urls;
            }
        }
        return false;
    }

    /**
     * @desc 根据角色名称获取角色url对应关系
     * @param string $arg
     * @return array|bool
     */
    public function getUrlByRoleName(string $arg = '') {
        if(empty($arg)) {
            return false;
        }

        $role = new Role();
        $param = $arg;
        if(false !== ($urls = $role->getUrlByRoleName($param))) {
            return $urls;
        }
        return false;

    }
}