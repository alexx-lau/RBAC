<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/9
 * Time: 16:01
 */

namespace app\service;


use app\common\controller\Common;
use think\Request;

class UrlHook {
    public function __construct() {
        $this->dirname = 'public/logs';
        if(!is_dir($this->dirname)) {
            mkdir($this->dirname, 0777, true);
        }
    }

    public function addUrl(Request $request) {
        $data = session('userinfo');
        $data['url'] = $request->post()['url'];
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        Common::saveLogs($this->dirname . '/add_url', $data);
    }



}