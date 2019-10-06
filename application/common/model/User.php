<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/25
 * Time: 16:59
 */

namespace app\common\model;


use think\Model;

/**
 * Class User
 * @package app\common\model
 * @desc user模型，用于处理用户相关业务逻辑
 */
class User extends Model {

    /**
     * @desc 添加用户
     * @param array|null $args
     * @return bool|int
     */
    public function addUser(array $args = null) {
        if(empty($args)) {
            return false;
        }
        $args['username'] = $this->convertStr($args['username']);
        if(false !== $res = $this->getUser($args)) {
            return 1;
        }
        $args['password'] = md5($args['password']);
        $args['reg_time'] = $args['log_time'] = time();
        if(self::save($args)) {
            if(empty(session($args['current_url'] . 'userinfo'))) {
                $userinfo = array();
                $userinfo = $this->getUser($args);
                unset($userinfo['password']);
                session($args['current_url'] . 'userinfo', $res);
            }

            return 2;
        }
        return false;
    }

    /**
     * @desc 获取用户信息
     * @param $arg
     * @return array|bool|null|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUser($arg) {
        if(empty($arg)) {
            return false;
        }
        if(is_array($arg)) {
            $condition = 'username=?';
        }
        if(is_numeric($arg)) {
            $condition = 'id=?';
        }
        $param = array();
        if(is_numeric($arg)) {
            $param[] = $arg;
        } else {
            $param[] = $this->convertStr($arg['username']);

        }


        if($res = self::where($condition, $param)
                        ->field(['id', 'username', 'password',
                                    'FROM_UNIXTIME(reg_time, \'%Y-%m-%d\') AS reg_time',
                                    'FROM_UNIXTIME(log_time, \'%Y-%m-%d\') AS log_time',
                                    'type'])
                        ->find()) {
            return $res;
        }
        return false;
    }

    /**
     * @desc 用户登录
     * @param array|null $args
     * @return bool|int
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login(array $args = null) {
        if(empty($args)) {
            return false;
        }

        if(false !== ($res = $this->getUser($args))) {
            if($res['password'] == md5($args['password'])) {
                unset($res['password']);
                $param['condition']['id'] = $res['id'];
                $param['content']['log_time'] = time();
                $this->editUser($param);
                $res['current_url'] = $args['current_url'];
                session($args['current_url'] . 'userinfo', $res);
                return 2;
            }
            return 1;
        }
        return false;
    }

    /**
     * @desc 编辑用户信息
     * @param array|null $args
     * @return bool|int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function editUser(array &$args = null) {
        if(empty($args)) {
            return false;
        }
        $condition = $args['condition'];
        $content = $args['content'];
        if(self::where($condition)->update($content)) {
            return 1;
        }
        return false;
    }

    /**
     * @desc 获取用户列表
     * @return array|bool|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUserList() {
        if($res = self::where('username', '<>', 'admin')
                        ->where('username', '<>', session('userinfo')['username'])
                        ->select()) {
            return $res;
        }
        return false;
    }

    /**
     * @desc 获取用户绑定的角色列表
     * @param null $arg
     * @return array|bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRoleByUser($arg = null) {
        if(empty($arg)) {
            return false;
        }

        $condition = '';
        $param = array();
        if(is_numeric($arg)) {
            $condition = 'user.id=?';
            $param[] = $arg;
        }
        if(is_array($arg) && isset($arg['username'])){
            $condition = 'username=?';
            $param[] = $this->convertStr($arg['username']);;
        }

        if($res = self::where($condition, $param)
                        ->field('ur.r_id')
                        ->join('user_role ur', 'user.id=ur.u_id')

                        ->find()) {

            if(is_array($arg) && isset($arg['need_id'])) {
                return $res->toArray();
            }
            if(!empty($res->toArray())) {
                if(empty($res->toArray()['r_id'])) {
                    return 'empty';
                } else {
                    $role = new Role();
                    if(false !== $res = $role->getRoleById($res->toArray())) {
                        $result = array();
                        foreach($res as $v) {
                            $result[] = $v['r_name'];
                        }
                        return $result;
                    }
                }

            }
        }
        return false;
    }

    /**
     * @desc 为用户绑定角色
     * @param array|null $args
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function bindRole(array $args = null) {
        if(empty($args)) {
            return false;
        }
        $uId = $args['u_id'];
        unset($args['u_id']);
        $userRole = new UserRole();
        $param['r_id'] = implode(',', $args);
        $param['u_id'] = $uId;
        $param['add_time'] = time();

        $roleList = $this->getRoleByUser($uId);

        if((false !== $roleList || 'empty' == $roleList) && !empty($roleList)) {
            if(false !== $userRole->saveRoleByUser($param)) {
                return true;
            }
            return false;
        }
        if(false !== $userRole->addRoleByUser($param)) {
            return true;
        }
        return false;
    }

    /**
     * @desc 授权用户
     * @param array|null $args
     * @return bool
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function authorizeUser(array $args = null) {
        if(empty($args)) {
            return false;
        }
        $args = implode(',', $args);
        $param['type'] = '2';
        $fp = setLock();

        if(self::where('id', 'in', $args)->update($param)) {
            setUnlock($fp);
            return true;
        }
        setUnlock($fp);
        return false;
    }

    /**
     * @desc 撤销授权
     * @param array|null $args
     * @return bool
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function unauthorizeUser(array $args = null) {
        if(empty($args)) {
            return false;
        }
        $args = implode(',', $args);
        $param['type'] = '1';
        $fp = setLock();
        if(self::where('id', 'in', $args)->update($param)) {
            setUnlock($fp);
            return true;
        }
        setUnlock($fp);
        return false;
    }

    /**
     * @desc 字符串整体转换为大写或小写
     * @param $str
     * @param int $type
     * @return string
     */
    private function convertStr($str, $type = 1) {
        if($type == 1) {
            return strtolower($str);
        }
        return strtoupper($str);
    }
}