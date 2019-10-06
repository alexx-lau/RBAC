<?php


namespace app\admin\controller;


use app\common\controller\Common;
use app\common\model\Role as RoleModel;
use app\common\model\UserRole;

/**
 * Class Role
 * @package app\admin\controller
 * @desc 角色控制器，处理角色相关业务逻辑
 */
class Role extends Common {

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
        parent::initialize(); // TODO: Change the autogenerated stub
        if(!($this->model instanceof RoleModel)) {
            $this->model = new RoleModel();
        }
        if(session($this->prefix . 'userinfo')['type'] != 2) {
           echo $this->notFound();
           exit();
        }
    }

    /**
     * @desc 向系统中添加角色
     * @param \think\facade\Request::post()
     * @return mixed
     */
    public function addRole() {
        if(!empty($this->request->post())) {
            $param['r_name'] = $this->request->post()['r_name'];

            if(true !== ($result = $this->validate($this->request->post(),
                        '\app\admin\validate\Role',
                                [],
                            true))) {
                $prompt = '';
                foreach($result as $v) {
                    $prompt .= $v . ',';
                }
                $prompt = rtrim($prompt, ',');
                $this->error($prompt);
            }
            if(false !== ($res = $this->model->addRole($this->request->post()))) {
                $this->success(config('prompt.add_role_success'));
            }
            $this->error(config('prompt.add_role_failure'));
        }
        return $this->fetch();
    }

    /**
     * @desc 获取角色列表
     * @param null
     * @return array
     */
    public function getRoleList() {
        $roleList = [];
        if(false !== $res = $this->model->getRoleList()) {
            $roleList = $res;
        }
        session('method_name', 'getrolelist');
        $count = count($roleList);
        if($count == 0) {
            $this->error(config('prompt.empty_role_list'), 'Main/index');
        }

        $this->assign('count', $count);
        $this->assign('page', $roleList->toArray()['current_page']);
        $this->assign('role_list', $roleList);
        return $this->fetch();
    }

    /**
     * @desc 对角色信息进行编辑
     * @param int|null $id
     * @return mixed
     */
    public function editRole(int $id = null) {

        if(empty($id)) {
            $this->error(config('prompt.invalid_role_info'));
        }
        if(false ===($roleInfo = $this->model->editRole($id))) {
            $this->error(config('prompt.invalid_role_info'));
        }

        if(!empty($this->request->post())) {

            $param = array();
            $info = $roleInfo->toArray();
            foreach($info as $k=>$v) {
                if(isset($this->request->post()[$k]) &&
                    $this->request->post()[$k] != $v &&
                    !empty($this->request->post()[$k])) {
                    $param[$k] = $this->request->post()[$k];
                }
            }
            if(!empty($param)) {
                $param['id'] = $id;
                $param['e_time'] = time();
                if(true !== ($result = $this->validate($this->request->post(),
                        '\app\admin\validate\Role',
                        [],
                        true))) {
                    $prompt = '';
                    foreach($result as $v) {
                        $prompt .= $v . ',';
                    }
                    $prompt = rtrim($prompt, ',');
                    $this->error($prompt);
                }
                if(false !== $this->model->editRole($param)) {
                    $this->success(config('prompt.edit_role_success'));
                }
                $this->error(config('prompt.edit_role_failure'));
            }
            $this->error(config('prompt.same_role'));
        }
        $this->assign('role_info', $roleInfo);
        return $this->fetch();
    }

    /**
     * @desc 删除角色信息，可考虑做软删除
     * @param int|null $id
     * @return bool
     */
    public function delRole(int $id = null, int $count = null, int $page = null) {
        if(empty($id)) {
            return false;
        }
        if(false !== $this->model->delRole($id)) {
            if(!empty($count)) {
                $this->success(config('prompt.delete_role_success'));
            }
            if($page != 1) {
                $this->success(config('prompt.delete_role_success'), 'getRoleList?page=' . $page);
            }
            $this->success(config('prompt.delete_role_success'), 'getRoleList');
        }
        $this->error(config('prompt.delete_role_failure'));
    }

    /**
     * @desc 为角色绑定授权的访问url
     * @param \think\facade\Request::post()
     * @return mixed|\think\response\Json
     */
    public function bindUrl() {
        if(!empty($this->request->post())) {
            if(empty($this->request->post()['url'])) {
                $this->error(config('prompt.invalid_url_info'));
            }
            $roleId = $this->request->post()['r_name'];
            $param = $this->request->post()['url'];
            $param['r_id'] = $roleId;
            if(false !== $this->model->bindUrl($param)) {
                session('bind_url_rid', $roleId);
                $this->success(config('prompt.bind_url_to_role_success'));
            }
            $this->error(config('prompt.bind_url_to_role_failure'));
        }
        if(!empty($this->request->get()) && !empty($this->request->get()['r_id'])) {
            $response = array();
            $param = $this->request->get()['r_id'];
            $url = new \app\common\model\Url();
            $type = 1;
            if(false === ($urlList = $url->getUrlList($type))) {
                $response['code'] = 3;
                $response['msg'] = 'not yet';
                $response['data'] = null;
                return json($response);
            }
            $urlList = $urlList->toArray();
            if(false !== ($roleUrlList = $this->model->getUrlByRole($param))) {
                if(!empty($roleUrlList)) {
                    foreach($urlList as $k=>$v) {
                        if(in_array($v['url'], $roleUrlList)) {
                            $urlList[$k]['checked'] = 1;
                        }
                    }
                }
            }
            $response['code'] = 1;
            $response['msg'] = 'get data';
            $response['data'] = $urlList;
            return json($response);
        }
        $roleList = [];
        $type = 1;
        if(false !== $res = $this->model->getRoleList($type)) {
            $roleList = $res;
        }
        if(count($roleList) == 0) {
            $this->error(config('prompt.empty_role_list'), 'addRole');
        }
        $this->assign('role_list', $roleList);
        return $this->fetch();
    }

    public function getAllRole() {
        dump($this->model->select());exit;
    }
}