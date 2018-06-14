<?php
/*
 * Api：设备管理
 *
 */

namespace Api\Controller;
class DeviceController extends BasicController
{

    public function index()
    {
        $where=array('type'=>I('leixing'),'deleted'=>'0');
        $data=M('tp_device')->where($where)->order('utime desc')->select();
        $data=$this->jsonEncode(200,'ok',$data);
        echo $data;
    }


    public function chaxun(){
        //接收Json并转换为数组；
        $array= getJsonToArray();
        $token=$array['token'];
//        $token=$this->check_token($token);
        if($token){
            $table=$array['t'];
            $where=$array['w'];
            $order=$array['o'];
            $data=$this->query($table,$where,$order);
            if($data){
                $this->json(200,'ok',$data);
            }else{
                $this->json(0,'null');
            }
        }else{
            $this->json(0,'token Error');
        }
    }

    public function chazhao(){
        $token=$this->check_token(I('token'));
        if($token){
            $table=I('t');
            $id=I('id');;
            $data=$this->find($table,$id);
            if($data){
                $this->json(200,'ok',$data);
            }else{
                $this->json(0,'null');
            }
        }else{
            $this->json(0,'token Error');
        }
    }

    public function test(){
        $table='user';
        $where=array('usergp'=>'YX');
        $data=$this->query($table,$where);
        if($data){
            $this->json(200,'ok',$data);
        }else{
            $this->json(200,'null');
        }
    }


}