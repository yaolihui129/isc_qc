<?php

namespace Jirapi\Controller;

use Think\Controller\RestController;

class BasicController extends RestController
{

    //查询数据
    function query($table, $where, $order = 'id')
    {
        $where['deleted'] = '0';
        $data = M($table)->where($where)->order($order)->select();
        return $data;
    }

    //查找数据
    function find($table, $id)
    {
        $data = M($table)->find($id);
        return $data;
    }

    //插入数据
    function insert($var, $table)
    {
        $m = D($table);
        if (!$m->create($var)) {
            return $m->getError();
        } else {
            $id = $m->add($var);
            if ($id) {
                return $id;
            } else {
                return 0;
            }
        }
    }

    //更新数据
    function update($var, $table)
    {
        if (D($table)->save($var)) {
            return 1;
        } else {
            return 0;
        }
    }

}