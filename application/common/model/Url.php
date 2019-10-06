<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/28
 * Time: 15:41
 */

namespace app\common\model;


use think\Db;
use think\Exception;
use think\Model;

/**
 * Class Url
 * @package app\common\model
 * @desc url模型，处理与url相关的业务逻辑
 */

class Url extends Model {

    /**
     * @var string
     * @desc 操作的表名
     */
    protected $table = 'urls';

    /**
     * @desc 根据角色获取url信息
     * @param null $arg
     * @return array|bool|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUrlsByRole($arg = null) {
        if(empty($arg)) {
            return false;
        }
        if(is_numeric($arg)) {
            $condition = 'urls.id=?';
        }
        if(is_string($arg)) {
            $condition = 'r_name=?';
        }
        $param = array();
        if(is_numeric($arg)) {
            $param[] = $arg;
        } else {
            $param[] = $arg['r_name'];
        }
        if($res = self::where($condition, $param)
            ->join('role_url', 'urls.id=role_url.url_id')
            ->join('role', 'role.id=role_url.r_id')
            ->select()) {
            return $res;
        }
        return false;
    }

    /**
     * @desc 添加一条url信息
     * @param array|null $args
     * @return bool|int
     */
    public function addUrl(array $args = null) {
        if(empty($args)) {
            return false;
        }
        if(self::save($args)) {
            return 2;
        }
        return false;
    }

    /**
     * @desc 通过url名获取url信息
     * @param string|null $arg
     * @return array|bool|null|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUrlByName(string $arg = null) {
        if(empty($arg)) {
            return false;
        }
        if($res = self::where('url', '=', $arg)->find()) {
            return $res;
        }
        return false;
    }

    /**
     * @desc 获取url列表，分页显示
     * @param int
     * @return bool|\think\Paginator
     * @throws \think\exception\DbException
     */
    public function getUrlList(int $arg =null) {

        if(!empty($arg)) {
            if($arg == 2) {
                if($res = self::order('id', 'asc')->select()) {

                    return $res;
                }
            }
            if($res = self::where('ignore_url', '<>', '2')->order('id', 'asc')->select()) {

                return $res;
            }
        }
        if($res = self::order('id', 'asc')->paginate(5)) {
            return $res;
        }
        return false;
    }

    /**
     * @desc 编辑url信息
     * @param array|null $args
     * @return bool
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function editUrl(array $args = null) {
        if(empty($args)) {
            return false;
        }
        if(self::where('id', '=', $args['id'])->update($args)) {
            return true;
        }
        return false;
    }

    /**
     * @desc 删除url信息
     * @param null $arg
     * @return bool
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function deleteUrl($arg = null) {
        if($arg == null) {
            return false;
        }
        if(is_numeric($arg)) {
            $condition = 'id=?';
        }
        if(is_array($arg)) {
            $condition = 'url=?';
        }
        $param = array();
        if(is_numeric($arg)) {
            $param[] = $arg;
        } else {
            $param[] = $arg['username'];
        }
        if(self::transaction(function() use($condition, $param) {
            if(self::where($condition, $param)->delete()) {
                $arg = $param[0];
                $roleUrl = new RoleUrl();
                if (false !== $res = $roleUrl->getRelationPairs($arg)) {
                    $res = $res->toArray();
                    if(empty($res)) {
                        self::commit();
                        return true;
                    }
                    $flag = 0;
                    $del = array();
                    foreach ($res as $v) {
                        if(false === stripos($v['url_id'], ',') && $v['url_id'] == $arg) {
                            $del[] = $v['id'];
                            continue;
                        }
                        $temp = explode(',', $v['url_id']);
                        foreach($temp as $uk=>$uv) {
                            if($arg == $uv) {
                                array_splice($temp, $uk, 1);
                                break;
                            }
                        }
                        $v['url_id'] = implode(',', $temp);
                        try {
                            if (!$roleUrl->where('id', '=', $v['id'])->update($v)) {
                                throw new Exception('issues');
                                break;
                            }
                        } catch(Exception $e) {
                            return false;
                        }
                    }
                    if(!empty($del)) {
                        if(false === $roleUrl->removeRelationPairs($del)) {
                            $flag = 1;
                        }
                    }
                    if ($flag == 1) {
                        return false;
                    }
                    return true;
                }
            }
        })) {
            return true;
        }
        return false;
    }

    /**
     * @desc 根据url列表获取url信息
     * @param null $args
     * @return array|bool|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUrlsById($args = null) {
        if(empty($args)) {
            return false;
        }
        if(false !== stripos($args['url_id'], ',')) {
            $condition = 'id in (';
            $temp = explode(',', $args['url_id']);
            foreach($temp as $v) {
                $condition .= '?,';
                $param[] = $v;
            }
            $condition = rtrim($condition, ',');
            $condition .= ')';
        } else {
            $condition = 'id=?';
            $param[] = $args['url_id'];
        }
        if($res = self::where($condition, $param)->order('id', 'asc')->select()) {
            return $res;
        }
        return false;
    }

    public function addUrls(array $args = null) {
        if(empty($args)) {
            return false;
        }
        $target = array();
        foreach($args as $v) {
            $target[]['url'] = $v;
        }
        if(self::insertAll($target)) {
            return true;
        }
        return false;
    }

    public function ignoreUrl(int $arg = 0) {
        if(empty($arg)) {
            return false;
        }
        $condition = 'id=?';
        $param[] = $arg;
        if($res = self::where($condition, $param)->field('ignore_url')->find()->toArray()) {

            $res['ignore_url'] = intval($res['ignore_url']) == 1 ? strval(2) : strval(1);

            if(self::where($condition, $param)->update($res)) {
//                dump(self::getLastSql());exit;
                return true;
            }

        }
        return false;
    }

    public static function getIgnoreUrls() {
        $result = array();
        if($res = self::where('ignore_url', '=', '2')->field('url')->select()->toArray()) {

            foreach($res as $v) {
                $temp = explode('/', $v['url']);
                $result[] = $temp[count($temp) - 1];
            }
        }
        return $result;
    }
}