<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/15
 * Time: 11:22
 */

namespace app\common\model;


use think\Model;

/**
 * Class RoleUrl
 * @package app\common\model
 * @desc role_url模型，处理角色与url关联的业务逻辑
 */
class RoleUrl extends Model {

    /**
     * @desc 为角色添加url
     * @param array|null $args
     * @return bool
     */
    public function addUrlByRole(array $args = null) {
        if(empty($args)) {
            return false;
        }
        if(self::save($args)) {
            return true;
        }
        return false;
    }

    /**
     * @desc 修改角色绑定的url信息
     * @param array|null $args
     * @return bool
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function updateUrlByRole(array $args = null) {
        if(empty($args)) {
            return false;
        }
        if(self::where('r_id', '=', $args['r_id'])->update($args)) {
            return true;
        }
        return false;
    }

    /**
     * 根据url id获取关系列表
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
        $condition = 'url_id like ?';
        $param[] = '%'.$arg.'%';
        if($pairs = self::where($condition, $param)->field('id,url_id')->select()) {
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
            $condition = 'r_id=?';
            $param[] = $arg;
            if(!self::where($condition, $param)->find()) {
                return true;
            }
            if(self::where($condition, $param)->delete()) {
                return true;
            }
            return false;
        }
        if(is_array($arg)) {
            if(self::where('id', 'in', $arg)->delete()) {
                return true;
            }
            echo self::getLastSql();
            return false;
        }
    }
}