<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/28
 * Time: 17:09
 */

namespace app\service;




class searchUrls {

    protected $ignore = ['index', 'login', 'register', 'logou', '', 'logout', 'getValidCode'];


    /**
     * @desc 获取前端模块的所有控制器下的方法名
     * @return array
     */
    public function getAllUrls() {
        $dirname = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'controller');
        $result = glob($dirname . DIRECTORY_SEPARATOR . '*.php');
        $namespace = '\\app\\common\\controller';
        foreach($result as $v) {
            $temp = explode('/', $v);
            $v = rtrim($temp[count($temp) - 1], '.php');
            $commonMethods = get_class_methods($namespace . '\\' . $v);
        }
        $dirname = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'index' . DIRECTORY_SEPARATOR . 'controller');
        $result = glob($dirname . DIRECTORY_SEPARATOR . '*.php');

        $namespace = '\\app\\index\\controller';
        $res = array();
        foreach($result as $k=>$v) {
            if(false !== strpos($v, 'Index')) {
                continue;
            }
            $temp = explode('/', $v);
            $moduleName = $temp[count($temp) - 3];
            $v = rtrim($temp[count($temp) - 1], '.php');
            $methods = get_class_methods($namespace . '\\' . $v);
            $methods = array_diff($methods, $commonMethods);
            foreach($methods as $method) {
                if(!in_array($method, $this->ignore)) {
                    $res['methods'][] = strtolower($moduleName . DIRECTORY_SEPARATOR . $v . DIRECTORY_SEPARATOR . $method);
                }

            }
        }
        return $res;
    }

    public function insertUrlsAutomatic() {
        $urls = $this->getAllUrls();

        $url = new \app\common\model\Url();
        $urlsExist = $url->getUrlList(2);
        $ignore = array();
        foreach($urlsExist as $k=>$v) {
            $ignore[] = $v['url'];
        }
        $urls = array_diff($urls['methods'], $ignore);
        return $url->addUrls($urls);

    }
}