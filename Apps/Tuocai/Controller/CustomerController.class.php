<?php
namespace Tuocai\Controller;
class CustomerController extends WebInfoController {          
    public function index(){
        getWebInfo(C('PRODUCT'));//获取网页信息
        $this->display();
    }
    public function checked(){
        if($_POST['phone']){
            $where=array('phone'=>$_POST['phone']);
            $data=M('tp_credit')->where($where)->select();
            if($data){
                $this->error("这个号码已经注册，无需重复注册");
            }else{
                $this->redirect(C(PRODUCT).'/Customer/register/phone/'.$_POST['phone']);
            }
        }else{
            $this->error("您没有填写手机号");
        }
    }
    public function register(){
        getWebInfo(C('PRODUCT'));//获取网页信息
        $this->assign('phone',$_GET['phone']);
        $this->display();
    }
    public function insert(){
        $m=M('tp_credit');
        $_POST['password']=md5(123456);
        $_POST['ctime']=time();
        if(!$m->create()){
            $this->error($m->getError());
        }
        $lastId=$m->add();
        if($lastId){
            $this->success("注册成功",U(C(PRODUCT).'/Index'));
        }else{
            $this->error("注册失败");
        }
    }
    
    public function mod(){
        $arr=M('tp_customer')->find($_SESSION['userid']);
        $arr=M('tp_credit')->find($arr['creditid']);
        $this->assign('arr',$arr);
    
        $this->display();
    }
    
    public function update(){
        $_POST['moder']=$_SESSION['realname'];
        if (M('tp_credit')->save($_POST)){
            $this->success("修改成功！");
        }else{
            $this->error("修改失败！");
        }
    }
    public function setpass(){
        $arr=M('tp_customer')->find($_SESSION['userid']);
        $user=M('tp_credit')->find($arr['creditid']);
        $this->assign('user',$user);
        $this->display();
    }
    
    public function set(){
        /* 接收参数*/
        $id = !empty($_POST['id']) ? $_POST['id'] : $_GET['id'];
        $pass1= $_POST['pass1'];
        $pass2= $_POST['pass2'];
        $pass3= $_POST['pass3'];
        $m=M('tp_credit');        /* 实例化模型*/
        $user=$m->find($id);
        if (md5($pass1)==$user['password']) {
            if ($pass2==$pass3) {
                $arr=array('id'=>$id,'password'=>md5($pass2),'moder'=>$_SESSION['realname']);
                if ($m->save($arr)){
                    $this->success("密码修改成功！",U(C(PRODUCT).'/Index/index'));
                }else{
                    $this->error("密码修改失败！");
                }
            }else{
                $this->error('新密码和确认面不一致');
            }
        }else{
            $this->error('原密码错误');
        }
    }
    
    public function personal(){
        getWebInfo(C('PRODUCT'));//获取网页信息
        $arr=M('tp_customer')->find($_SESSION['userid']);
        $arr=M('tp_credit')->find($arr['creditid']);
        $this->assign('arr',$arr);
    
        $this->display();
    }
    
    public function yuyue() {
        $arr=M('tp_customer')->find($_SESSION['userid']);
        $map=array(['phone']=>$arr['creditid']);
        $data=M('tp_serviccar')->where($map)->select();
        $this->assign('data',$data);
    
        $this->display();
    }	
        

        
       
   
    
     
    



    
    public function cusupdate(){
        // 实例化上传类
        $upload = new \Think\Upload();
        $upload->maxSize  =     7145728 ;// 设置附件上传大小
        $upload->exts     =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath =  './Upload/Tuocai/';// 设置附件上传目录
        $upload->savePath = '/Customer/'; // 设置附件上传目录       
        $info  =   $upload->upload();
        /* 实例化模型*/
        $db=D('tc_customer');
        $_POST['moder']=$_SESSION['realname'];      
        if(!$info) {//判定是否有图片上传
            // 没有图片上传时,保存信息             
            $message=$db->save($_POST);
        }else{
            // 上传成功 获取上传文件信息
            $_POST['path']=$info['img']['savepath'];
            $_POST['img']=$info['img']['savename'];
            // 没有图片上传时,保存信息
            $message=$db->save($_POST);
            $image = new \Think\Image();
            //打开图片资源
            $image->open('./Upload/Tuocai'.$info['img']['savepath'].$info['img']['savename']);
            //等比例缩放
            $image->thumb(600, 400)->save('./Upload/Tuocai'.$info['img']['savepath'].$info['img']['savename']);   
        }
        //传递保存信息
        if ($message){
            $this->success("修改成功！");
        }else{
            $this->error("修改失败！");
        }

        
    }
    
    
     
}
 


