<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/28
 * Time: 15:53
 */

namespace app\common\model;


use think\Exception;
use think\Model;
use think\Db;

/**
 * Class Role
 * @package app\common\model
 * @desc 角色模型，处理角色相关业务逻辑
 */
class Role extends Model {

    /**
     * @desc 向系统中添加一个角色
     * @param array|null $args
     * @return bool
     */
    public function addRole(array $args = null) {
        if(empty($args)) {
            return false;
        }
        $args['c_time'] = $args['e_time'] = time();
        if(self::save($args)) {
            return true;
        }
        return false;
    }

    /**
     * @desc 获取角色列表
     * @return bool|\think\Paginator
     * @throws \think\exception\DbException
     */
    public function getRoleList($arg = null) {

        if(!empty($arg)) {
            if($res = self::field(['id', 'r_name',
                'FROM_UNIXTIME(c_time, \'%Y-%m-%d\') AS c_time'])
                ->where('r_name', '<>', '管理员')
                ->where('r_name', '<>', config('title.guest'))
                ->order('id', 'asc')
                ->select()) {
                return $res;
            }
        }
        if($res = self::field(['id', 'r_name',
            'FROM_UNIXTIME(c_time, \'%Y-%m-%d\') AS c_time'])
            ->where('r_name', '<>', '管理员')
            ->where('r_name', '<>', config('title.guest'))
            ->order('id', 'asc')
            ->paginate(5)) {
            return $res;
        }
        return false;
    }

    /**
     * @desc 编辑已存在的角色
     * @param null $args
     * @return array|bool|null|\PDOStatement|string|Model
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function editRole($args = null) {
        if(empty($args)) {
            return false;
        }
        if(is_array($args)) {
            if(self::where('id', '=', $args['id'])->update($args)) {
                return true;
            }
            return false;
        }
        if(is_numeric($args)) {
            if($res = self::field(['id', 'r_name',
                'FROM_UNIXTIME(c_time, \'%Y-%m-%d\') AS c_time',
                'FROM_UNIXTIME(e_time, \'%Y-%m-%d\') AS e_time'])
                ->find($args)) {
                return $res;
            }
            return false;
        }
        return false;
    }

    /**
     * @desc 删除已存在的角色
     * @param int|null $arg
     * @return bool
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function delRole(int $arg = null) {
        if(empty($arg)) {
            return false;
        }
        self::startTrans();
        try {
            if(self::where('id', '=', $arg)->delete()) {
                $userRole = new UserRole();
                $roleUrl = new RoleUrl();
                $res = $userRole->getRelationPairs($arg);
                $roleUrlPairs = $roleUrl->where('r_id', '=', $arg)->find();
                if(!empty($res)) {
                    $res = $res->toArray();
                }
                if(!empty($roleUrlPairs)) {
                    $roleUrlPairs = $roleUrlPairs->toArray();
                }

                if (!empty($res) || !empty($roleUrlPairs)) {
                    $needDel = 1;
                    if(empty($res)) {
                        $needDel = 0;
                        if(empty($roleUrlPairs)) {
                            self::commit();
                            return true;
                        }
                    }

                    $flag = 0;
                    $del = array();
                    if($needDel) {
                        foreach ($res as $v) {
                            if(false === stripos($v['r_id'], ',') && $v['r_id'] == $arg) {
                                $del[] = $v['id'];
                                continue;
                            }
                            $temp = explode(',', $v['r_id']);
                            foreach($temp as $rk=>$rv) {
                                if($rv == $arg) {
                                    array_splice($temp, $rk, 1);
                                    break;
                                }
                            }
                            $v['r_id'] = implode(',', $temp);
                            if (!$userRole->where('id', '=', $v['id'])->update($v)) {
                                $flag = 1;
                                break;
                            }
                        }
                    }

                    if(!empty($del)) {
                        if(false === $userRole->removeRelationPairs($del)) {
                            $flag = 1;
                        }
                    }
                    if(empty($roleUrlPairs)) {
                        self::commit();
                        return true;
                    }
                    if(false === $roleUrl->removeRelationPairs($arg)) {
                        $flag = 1;
                    }
                    if ($flag == 1) {
                        throw new Exception('deleted fail');
                    }
                }
                self::commit();
                return true;
            }
        } catch(Exception $e) {
            echo $e->getMessage();exit;
            self::rollback();
        }
        return false;
    }

    /**
     * @desc 根据角色id获取角色信息
     * @param null $args
     * @return array|bool|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRoleById($args = null) {
        if(empty($args)) {
            return false;
        }
        if(false !== stripos($args['r_id'], ',')) {
            $condition = 'id in (' . trim($args['r_id'], ',') . ')';
        } else {
            $condition = 'id=' . $args['r_id'];
        }
        if($res = self::where($condition)->select()) {
            return $res;
        }
        return false;
    }

    /**
     * @desc 根据角色信息获取绑定的url信息
     * @param null $arg
     * @return array|bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUrlByRole($arg = null) {
        if(empty($arg)) {
            return false;
        }
        if(is_numeric($arg)) {
            $condition = 'r.id=?';
            $param[] = $arg;
        }
        if(false !== stripos($arg, ',')) {
            $temp = explode(',', $arg);
            $condition = 'r.id in (';
            foreach($temp as $v) {
                $condition .= '?,';
                $param[] = $v;
            }
            $condition = rtrim($condition, ',');
            $condition .= ')';
        }
        if($res = self::where($condition, $param)
                ->alias('r')
                ->field('ru.url_id')
                ->join('role_url ru', 'r.id=ru.r_id')
                ->select()) {
            $res = $res->toArray();
            $result = array();
            foreach($res as $k=>$v) {
                $result = array_merge($result, $v);
            }
            $res = $result;
            if(!empty($res)) {
                $url = new Url();
                if(false !== ($res = $url->getUrlsById($res)->toArray())) {
                    $result = array();
                    foreach($res as $v) {
                        $result[] = $v['url'];
                    }
                    return $result;
                }
            }
        }
        return false;
    }

    /**
     * @desc 根据角色名获取绑定的url信息
     * @param string|null $arg
     * @return array|bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUrlByRoleName(string $arg = null) {
        if(empty($arg)) {
            return false;
        }
        $condition = 'r.r_name=?';
        $param[] = $arg;

        if($res = self::where($condition, $param)
            ->alias('r')
            ->field('ru.url_id')
            ->join('role_url ru', 'r.id=ru.r_id')
            ->select()) {
            $res = $res->toArray();

            $result = array();
            foreach($res as $k=>$v) {
                $result = array_merge($result, $v);
            }
            $res = $result;
            if(!empty($res)) {
                $url = new Url();
                if(false !== ($res = $url->getUrlsById($res)->toArray())) {
                    $result = array();
                    foreach($res as $v) {
                        $result[] = $v['url'];
                    }
                    return $result;
                }
            }
        }
        return false;
    }

    /**
     * @desc 将url绑定到角色
     * @param array|null $args
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function bindUrl(array $args = null) {
        if(empty($args)) {
            return false;
        }
        $roleId = $args['r_id'];
        unset($args['r_id']);
        $param['r_id'] = $roleId;
        $param['url_id'] = implode(',', $args);
        $param['add_time'] = time();
        $roleUrl = new RoleUrl();
        $urlList = $this->getUrlByRole($roleId);
//        dump($urlList);exit;
        if(false !== $urlList && !empty($urlList)) {
            if(false !== $roleUrl->updateUrlByRole($param)) {
                return true;
            }
            return false;
        }
        if(false !== $roleUrl->addUrlByRole($param)) {
            return true;
        }
        return false;
    }
}