<?php

namespace Report\Controller;

class MiniproController extends WebInfoController
{
    public function openid()
    {
        $appid = trim('wxe6f24ce3395cb972');
        $secret = trim('1eb6356e949c6ce165e2cf7ff085e782');
        $code = trim(I('code'));
        $url = trim('https://api.weixin.qq.com/sns/jscode2session?appid=' . $appid . '&secret=' . $secret . '&js_code=' . $code . '&grant_type=authorization_code');
        $arr = json_decode(httpGet($url), true);
        $arr = arrDate($arr);
        $this->ajaxReturn($arr);
    }

    public function getUserInfo()
    {
        if (IS_POST) {
            $json = file_get_contents('php://input');
            $array = json_decode($json, true);
            $map['openid'] = $array['openid'];
        } else {
            $map['openid'] = I(openid);
        }
        $map['deleted'] = '0';
        echo $map;
        $arr = M('tp_user_third_party')->where($map)->field('id,userid')->find();
        $arr = arrDate($arr);
        $this->ajaxReturn($arr);
    }

    public function login()
    {
        $id = 'gh_3e253e0b66bb';
        $appid = trim('wxe6f24ce3395cb972');

    }


    public function program()
    {
        if (IS_POST) {
            $json = file_get_contents('php://input');
            $array = json_decode($json, true);
            $map['status'] = $array['status'];
            $map['type'] = $array['type'];
        } else {
            $map['status'] = I(status);
            $map['type'] = I(type);
        }

        $map['deleted'] = '0';
        $map['QD'] = array('neq', 'admin');
        $arr = M('project')->where($map)->order("status desc,end desc")->field('id,name,end,QD')->select();
        $arr = arrDate($arr);
        $this->ajaxReturn($arr);
    }

    public function risk()
    {
        $map['deleted'] = '0';
        $arr = M('tp_risk')->where($map)->order("state desc,sn")->field('id,risk as name')->select();
        $arr = arrDate($arr);
        $this->ajaxReturn($arr);
    }

    public function func()
    {
        $map['deleted'] = '0';
        $arr = M('tp_func')->where($map)->order("branch,path")->field('id,func as name')->select();
        $arr = arrDate($arr);
        $this->ajaxReturn($arr);
    }

    public function branch()
    {
        if (IS_POST) {
            $json = file_get_contents('php://input');
            $array = json_decode($json, true);
            $map['product'] = $array['product'];
        } else {
            $map['product'] = I(product);
        }
        $map['deleted'] = '0';
        $arr = M('branch')->where($map)->order("`order`")->field('id, name')->select();
        $arr = arrDate($arr);
        $this->ajaxReturn($arr);
    }

    public function module()
    {
        if (IS_POST) {
            $json = file_get_contents('php://input');
            $array = json_decode($json, true);
            $map['branch'] = $array['branch'];
        } else {
            $map['branch'] = I(branch);
        }
        $map['deleted'] = '0';
        $arr = M('module')->where($map)->order("`order`")->field('id, name')->select();
        $arr = arrDate($arr);
        $this->ajaxReturn($arr);
    }


    public function bug()
    {
        $map['deleted'] = '0';
        $arr = M('bug')->where($map)->order("`order`")->field('id, name')->select();
        $arr = arrDate($arr);
        $this->ajaxReturn($arr);
    }

}