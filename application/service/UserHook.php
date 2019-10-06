<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/9
 * Time: 11:58
 */

namespace app\service;


use think\Request;
use app\common\controller\Common;

/**
 * Class UserHook
 * @package app\service
 * @desc 用户行为定义类
 */
class UserHook {
    /**
     * @var string
     * @desc 日志保存目录
     */
    private $dirname;

    /**
     * UserHook constructor.
     * @desc 构造方法
     */
    public function __construct() {
        $this->dirname = 'public/logs';
        if(!is_dir($this->dirname)) {
            mkdir($this->dirname, 0777, true);
        }
    }

    /**
     * @desc 登录前置行为
     * @param Request $request
     */
    public function beforeLogin(Request $request) {
        $userinfo = array();
        if(!empty($request->post())) {
            $userinfo = $request->post();
        }
        $userinfo = json_encode($userinfo, JSON_UNESCAPED_UNICODE);
        Common::saveLogs($this->dirname . '/before_login', $userinfo);
    }

    /**
     * @desc 登录成功行为
     */
    public function loginSuccess() {
        $userinfo = session(session('prefix') . 'userinfo');
        $userinfo = json_encode($userinfo, JSON_UNESCAPED_UNICODE);
        Common::saveLogs($this->dirname . '/login_success', $userinfo);
    }

    /**
     * @desc 登出前置行为
     */
    public function beforeLogout() {
        $userinfo = session(session('prefix') . 'userinfo');
        $userinfo = json_encode($userinfo, JSON_UNESCAPED_UNICODE);
        Common::saveLogs($this->dirname . '/before_logout', $userinfo);
    }

    /**
     * @desc 登出后置行为
     */
    public function afterLogout() {
        $userinfo = session(session('prefix') . 'userinfo');
        $userinfo = json_encode($userinfo, JSON_UNESCAPED_UNICODE);
        Common::saveLogs($this->dirname . '/after_logout', $userinfo);
    }
}