<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/14
 * Time: 19:16
 */

namespace app\common\model;


use think\Model;

/**
 * Class UserRole
 * @package app\common\model
 * @desc user_role模型，用于处理user_role 关联的业务逻辑
 */
class UserRole extends Model {

    /**
     * @desc 为用户添加角色
     * @param array|null $args
     * @return bool
     */
    public function addRoleByUser(array $args = null) {
        if(empty($args)) {
            return false;
        }

        if(self::save($args)) {
            return true;
        }
        return false;
    }

    /**
     * @desc 修改用户的角色
     * @param array|null $args
     * @return bool
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function saveRoleByUser(array $args = null) {
        if(empty($args)) {
            return false;
        }
        if(self::where('u_id', '=', $args['u_id'])->update($args)) {
            return true;
        }
        return false;
    }

    /**
     * @desc 根据角色id获取关系列表
     * @param int|null $arg
     * @return array|bool|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRelationPairs(int $arg = null) {
        if(empty($arg)) {
            return false;
        }
        $condition = 'r_id like ?';
        $param[] = '%'.$arg.'%';
        if($pairs = self::where($condition, $param)->field('id,r_id')->select()) {
            return $pairs;
        }
        return false;
    }

    /**
     * @desc 移除指定的关系对
     * @param null $arg
     * @return bool
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function removeRelationPairs($arg = null) {
        if(empty($arg)) {
            return false;
        }
        if(is_numeric($arg)) {
            $condition = 'id=?';
            $param[] = $arg;
            if(self::where($condition, $param)->delete()) {
               return true;
            }
            return false;
        }
        if(is_array($arg)) {
            if(self::where('id', 'in', $arg)->delete()) {
                return true;
            }
            return false;
        }

    }
}