<?php

namespace app\admin\validate;

use think\Validate;

/**
 * Class CheckUser
 * @package app\admin\validate
 * @desc 用于用户输入时使用检验规则对用户的输入数据进行检验
 */
class CheckUser extends Validate {
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'username|用户名' => 'require|max:30',
        'password|密码' => 'require|min:6',
        're_password|重复密码' => 'require|confirm:password',
        'code|验证码' => 'require|confirmCode'
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [];

    protected $scene = [
        'loginNormal',
        'loginValid',
        'changePassword'
    ];

    /**
     * $desc 检查验证码是否正确
     * @param $value
     * @return bool|string
     */
    protected function confirmCode($value) {
        if(strtolower($value) != strtolower(session('valid_code'))) {
            return '验证码不正确，请检查';
        }
        return true;
    }

    /**
     * @desc 没有验证码的情况
     * @return CheckUser
     */
    protected function sceneloginNormal() {
        return $this->only(['username', 'password']);
    }

    /**
     * @desc 有验证码的情况
     * @return CheckUser
     */
    protected function sceneloginValid() {
        return $this->only(['username', 'password', 'code']);
    }

    protected function scenechangePassword() {
        return $this->only(['password'])->append('changePassword');
    }

    protected function changePassword($value, $rule, $data) {
        
    }
}
