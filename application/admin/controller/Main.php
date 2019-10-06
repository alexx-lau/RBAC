<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/24
 * Time: 11:29
 */

namespace app\admin\controller;


use app\common\controller\Common;

/**
 * Class Main
 * @package app\admin\controller
 * @desc 后端入口，显示后端的首页信息
 *
 */
class Main extends Common {

    /**
     * @desc 后端首页入口，只有管理员级别用户可以访问
     * @return mixed|void
     */
    public function index() {
        if(session($this->prefix . 'userinfo')['type'] == 1) {
            return $this->notFound();

        }
        $this->assign('prefix', $this->prefix);
        return $this->fetch();
    }
}