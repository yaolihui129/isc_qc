<?php

namespace Device\Controller;

use Think\Controller;

class LoginController extends Controller
{
    public function index()
    {
        layout(false); // 临时关闭当前模板的布局功能
        $this->display();
    }

    public function login()
    {
        $where = array('account' => $_POST['username'], 'password' => md5($_POST['password']));
        $user = D('user')->where($where)->field("id,dept,account,realname")->find();
        if ($user) {
//            session('[start]');
            $_SESSION = $user;
            $_SESSION['isLogin'] = C(PRODUCT);
            cookie('session_name',$user,15*24*60*60);
            cookie('isLogin',C(PRODUCT),15*24*60*60);
            $this->redirect('Device/Index/index');
        } else {
            $this->error('用户或密码错误，请重新登陆！', "index");
        }

    }

    public function logout()
    {
        $username = $_SESSION['realname'];
        $_SESSION = array();

        if (isset($_COOKIE['session_name'])) {
            cookie('session_name',null);
        }
        // 销毁sesstion
        session_destroy();
        $this->success($username . ",再见!", U("Device/Index/index"));

    }


}