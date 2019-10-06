<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/9
 * Time: 9:58
 */

namespace app\admin\controller;
use app\common\controller\Common;
use app\common\model\Url as UrlModel;

/**
 * Class Url
 * @package app\admin\controller
 * @desc url控制器，处理url相关业务逻辑
 */
class Url extends Common {

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
            $this->model = new UrlModel();
        }
        if(session($this->prefix . 'userinfo')['type'] != 2) {
            return $this->notFound();
        }
    }

    /**
     * @desc 向系统中添加访问url
     * @param \think\facade\Request::post()
     * @return mixed
     */
    public function addUrl() {
        if(!empty($this->request->post())) {
            if(true !== ($result = $this->validate($this->request->post(),
                    '\\app\\admin\\validate\\Url'))) {
                $this->error($result);
            }
            if(false !== ($res = $this->model->addUrl($this->request->post()))) {
                $this->success(config('prompt.add_url_success'));
            }
            $this->error(config('prompt.add_url_failure'));
        }
        return $this->fetch();
    }

    /**
     * @desc 获取url列表
     * @return mixed
     */
    public function getUrlList() {
        $urlList = array();
        if(false !== ($res = $this->model->getUrlList())) {
            $urlList = $res;
        }
        if(0 == count($urlList)) {
            $this->error(config('prompt.empty_url_list'));
        }
        session('method_name', 'geturllist');
        $this->assign('count', count($urlList));
        $this->assign('page', $urlList->toArray()['current_page']);
        $this->assign('url_list', $urlList);
        return $this->fetch();
    }

    /**
     * @desc 对保存的url信息进行编辑
     * @param int|null $id
     * @return mixed
     */
    public function editUrl(int $id = null) {
        if(empty($id)) {
            $this->error(config('prompt.invalid_url_info'));
        }
        if(false === $urlInfo = $this->model->getUrlsById(array('url_id' => $id))) {
            $this->error(config('prompt.invalid_url_info'));
        }
        if(!empty($this->request->post())) {

            $info = $urlInfo[0]->toArray();
            $param = array();
            foreach($info as $k=>$v) {
                if($k == 'id') {
                    continue;
                }
                if(isset($this->request->post()[$k]) &&
                    !empty($this->request->post()[$k]) &&
                    $this->request->post()[$k] != $v) {
                    $param[$k] = $this->request->post()[$k];
                }
            }
            if(!empty($param)) {
                if(true !== ($result = $this->validate($this->request->post(),
                        '\\app\\admin\\validate\\Url'))) {
                    $this->error($result);
                }
                $param['id'] = $id;
                if(false !== $this->model->editUrl($param)) {
                    $this->success(config('prompt.edit_url_success'));
                }
                $this->error(config('prompt.edit_url_failure'));
            }
            $this->error(config('prompt.same_url'));
        }
        $this->assign('url_info', $urlInfo[0]);
        return $this->fetch();
    }

    /**
     * @desc 删除保存的url信息，可考虑软删除
     * @param int|null $id
     */
    public function delUrl(int $id = null, int $count = null, int $page = null) {
        if(empty($id)) {
            $this->error(config('prompt.invalid_url_info'));
        }
        if($this->model->deleteUrl($id)) {

            if(!empty($count)) {
                $this->success(config('prompt.delete_url_success'));
            }
            if($page != 1) {
                $this->success(config('prompt.delete_url_success'), 'getUrlList?page=' . $page);
            }
            $this->success(config('prompt.delete_url_success'), 'getUrlList');

//            $this->success(config('prompt.delete_url_success'));
        }
        $this->error(config('prompt.delete_url_failure'));
    }


    /**
     * @desc 将选定的url放入忽略列表，让所有用户均可以访问
     * @param int|null $id
     */
    public function ignoreUrl(int $id = null) {

        if(empty($id)) {
            $this->error(config('prompt.invalid_url_info'));
        }
        if($this->model->ignoreUrl($id)) {
            $this->success(config('prompt.add_ignore_url_success'), 'getUrlList');
        }
        $this->error(config('prompt.add_ignore_url_failure'));
    }


}