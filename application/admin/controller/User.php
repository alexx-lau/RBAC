<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/24
 * Time: 11:45
 */

namespace app\admin\controller;


use app\common\controller\Common;
use app\common\model\User as UserModel;
use app\service\Role;
use think\facade\Hook;

/**
 * Class User
 * @package app\admin\controller
 * @desc 用户控制器，用于处理用户相关业务逻辑
 */
class User extends Common {

    /**
     * @var null
     * @desc 对应模型实例对象
     */
    protected $model = null;

    /**
     * @desc 初始化方法
     * @param null
     * @return null
     */
    protected function initialize() {
        parent::initialize();
        if(!($this->model instanceof UserModel)) {
            $this->model = new UserModel();
        }
    }

    /**
     * @desc 处理用户注册，定义了两个钩子行为监听，用于记录注册过程
     * @param \think\facade\Request::post()
     * @return mixed
     */
    public function register() {
        if(empty(session($this->prefix . 'userinfo')) || session($this->prefix . 'userinfo')['username'] != 'admin') {
            return $this->notFound();
        }
        $res = parent::register();
        switch($res) {
            case 1:
                Hook::listen('registerFail');
                $this->error(config('prompt.user_already_exist'));
                break;
            case 2:
                Hook::listen('registerSuccess');
                $this->success(config('prompt.register_success'), 'main/index');
                break;
        }
        return $this->fetch();
    }

    /**
     * @desc 处理用户登录，定义了两个钩子行为监听，用于记录登录过程
     * @return mixed
     */
    public function login() {
        $res = parent::login();
        switch($res) {
            case 1:
                Hook::listen('loginFail');
                $this->error(config('prompt.invalid_username_or_password'));
                break;
            case 2:
                Hook::listen('loginSuccess');
                session('times', null);
                $this->success(config('prompt.login_success'), 'main/index');
                break;
        }
        $this->assign('times', session('times'));
        $this->assign('title', config('title.login'));
        return $this->fetch();
    }

    /**
     * @desc 处理用户登出
     */
    public function logout() {
        parent::logout();
        $this->redirect('main/index');

    }

    /**
     * @desc 处理为用户绑定角色
     * @param \think\facade\Request::post()
     * @return mixed|\think\response\Json
     */
    public function bindRole() {
        if(!empty($this->request->post())) {
            if(empty($this->request->post()['role'])) {

                $this->error(config('prompt.invalid_role_info'));
            }
            $userId = $this->request->post()['username'];
            $param = $this->request->post()['role'];

            $param['u_id'] = $userId;

            if(false !== $this->model->bindRole($param)) {
                session('bind_role_uid', $userId);
                $this->success(config('prompt.bind_role_to_user_success'));
            }
            $this->error(config('prompt.bind_role_to_user_failure'));
        }
        if(!empty($this->request->get())) {
            $response = array();
            $param = !empty($this->request->get()['id']) ?
                $this->request->get()['id'] :
                $this->request->get()['username'];
                $role = new Role();
                $type = 1;
                if(false === ($roleList = $role->getRoleList($type))) {
                    $response['code'] = 3;
                    $response['msg'] = 'not yet';
                    $response['data'] = null;
                    return json($response);
                }
                $roleList = $roleList->toArray();
                if(false !== ($userRoleList = $this->getRoleByUser($param))) {

                    if(!empty($userRoleList) && $userRoleList != 'empty') {
                        foreach($roleList as $k=>$v) {
                            if(in_array($v['r_name'], $userRoleList)) {
                                $roleList[$k]['checked'] = 1;
                            }
                        }
                    }
                }
                $response['code'] = 1;
                $response['msg'] = 'get data';
                $response['data'] = $roleList;
                return json($response);
        }
        if(false === ($userList = $this->model->getUserList())) {
            $this->error(config('prompt.empty_user_list'));
        }
        $this->assign('user_list', $userList);
        return $this->fetch();
    }

    /**
     * @desc 根据用户信息获取用户的角色列表
     * @param null $args
     * @return bool
     */
    public function getRoleByUser($args = null) {
        if(empty($args)) {
            return false;
        }
        return $userRoleList = $this->model->getRoleByUser($args);
    }

    /**
     * @desc 处理用户授权，使用户具备后台管理的权力
     * @param \think\facade\Request::post()
     * @return mixed
     */
    public function authorizeUser() {
        if(session($this->prefix . 'userinfo')['username'] != 'admin') {
            $this->error(config('prompt.invalid_authorize'));
        }
        if(false === ($res = $this->model->getUserlist())) {
            $this->error(config('prompt.empty_user_list'));
        }
        $res = $res->toArray();
        for($i = 0, $len = count($res); $i < $len; ) {
            if($res[$i]['type'] == '2') {
                array_splice($res, $i, 1);
                $len --;
                continue;
            }
            $i ++;
        }
        if(empty($res)) {
            $this->error(config('prompt.empty_user_list'));
        }
        $this->assign('user_list', $res);
        if(!empty($this->request->post())) {
            $id = $this->request->post()['username'];
            if(empty($id)) {
                $this->error(config('prompt.invalid_authorize_user'));
            }
            if(false !== $this->model->authorizeUser($id)) {
                $this->success(config('prompt.authorize_success'), 'main/index');
            }
            $this->error(config('prompt.authorize_failure'));
        }
        return $this->fetch();
    }

    /**
     * @desc 解除用户的授权，收回授予的后台管理权力
     * @param \think\facade\Request::post()
     * @return mixed
     */
    public function unauthorizeUser() {
        if(session($this->prefix . 'userinfo')['username'] != 'admin') {
            $this->error(config('prompt.invalid_authorize'));
        }
        if(false === ($res = $this->model->getUserlist())) {
            $this->error(config('prompt.empty_user_list'));
        }
        $res = $res->toArray();
        for($i = 0, $len = count($res); $i < $len; ) {
            if($res[$i]['type'] == '1') {
                array_splice($res, $i, 1);
                $len --;
                continue;
            }
            $i ++;
        }
        if(empty($res)) {
            $this->error(config('prompt.empty_user_list'));
        }
        $this->assign('user_list', $res);
        if(!empty($this->request->post())) {
            $id = $this->request->post()['username'];
            if(empty($id)) {
                $this->error(config('prompt.invalid_authorize_user'));
            }
            if(false !== $this->model->unauthorizeUser($id)) {
                $this->success(config('prompt.unauthorize_success'), 'main/index');
            }
            $this->error(config('prompt.unauthorize_failure'));
        }
        return $this->fetch();
    }



}