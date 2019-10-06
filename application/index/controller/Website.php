<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/13
 * Time: 14:59
 */

namespace app\index\controller;


use app\common\controller\Common;

/**
 * Class Website
 * @package app\index\controller
 * @desc 前端主控制器,用于前端入口业务逻辑
 */
class Website extends Common {

    /**
     * @desc 前端首页
     * @return mixed
     */
    public function index() {
        return $this->fetch();
    }

    /**
     * @desc 前端innerPage1页
     * @return mixed
     */
    public function innerPage1() {
        return $this->fetch();
    }

    /**
     * @desc 前端innerPage2页
     * @return mixed
     */
    public function innerPage2() {
        return $this->fetch();
    }

    /**
     * @desc 前端innerPage3页
     * @return mixed
     */
    public function innerPage3() {
        return $this->fetch();
    }

    /**
     * @desc 前端aboutUs页
     * @return mixed
     */
    public function aboutUs() {
        return $this->fetch();
    }

    /**
     * @desc 前端contactUs页
     * @return mixed
     */
    public function contactUs() {
        return $this->fetch();
    }
}