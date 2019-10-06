<?php

namespace app\admin\validate;

use think\Db;
use think\Validate;

/**
 * Class Role
 * @package app\admin\validate
 * @desc 在添加或修改角色时检查是否已存在
 */
class Role extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'r_name|角色名' => 'require|min:1|checkExist'
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [];

    /**
     * @desc 检测是否已存在
     * @param $value
     * @return bool|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function checkExist($value) {
//        if(is_numeric($value)) {
//            $condition = 'id=?';
//        } else {
//            $condition = 'r_name=?';
//
//        }
        $condition = 'r_name=?';
        $param[] = $value;
        if(Db::table('role')->where($condition, $param)->find()) {
            return '该角色已经存在，请检查';
        }
        return true;
    }
}
