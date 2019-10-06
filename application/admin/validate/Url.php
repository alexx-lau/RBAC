<?php

namespace app\admin\validate;

use think\Validate;
use think\Db;

/**
 * Class Url
 * @package app\admin\validate
 * @desc 在添加或修改url时检查是否已存在
 */
class Url extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'url|url名称' => 'require|checkUrl',
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
     * @return bool|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function checkUrl($value) {
        if(false === stripos($value, '/')) {
            return config('prompt.invalid_url');
        }
        if(Db::table('urls')->where('url', '=', $value)->find()) {
            return config('prompt.url_already_exist');
        }
        return true;
    }
}
