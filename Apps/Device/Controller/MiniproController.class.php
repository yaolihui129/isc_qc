<?php

namespace Device\Controller;

use Think\Controller;

class MiniproController extends Controller
{
    public function books()
    {
        if (IS_POST) {
            $json = file_get_contents('php://input');
            $array = json_decode($json, true);
            $search = $array['search'];
        } else {
            $search = I(search);
        }

        $where['brand|ts|serial|asset_no|sys_version'] = array('like', '%' . $search . '%');
        $arr = M('tp_device')->where($where)->select();
        $arr = arrDate($arr);
        $this->ajaxReturn($arr);
    }

}