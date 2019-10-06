<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

define('FILENAME', 'public/files/lock');

if(!function_exists('setLock')) {
    function setLock() {
        $dirname = dirname(FILENAME);
        if(!is_dir($dirname)) {
            mkdir($dirname, 0777, true);
        }
        $fp = fopen(FILENAME, 'w');
        flock($fp, LOCK_EX);
        return $fp;
    }
}

if(!function_exists('setUnlock')) {
    function setUnlock($fp) {
        flock($fp, LOCK_UN);
        fclose($fp);
        return true;
    }
}
