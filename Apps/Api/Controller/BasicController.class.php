<?php
/*
 * Api：基类
 *
 */
namespace Api\Controller;

use Think\Controller;

class BasicController extends Controller
{
    function _empty()
    {//空方法
        echo '方法不存在';
    }
    function check_token($token){
        $table='';
        $where['token']=$token;
        $where['expires']  = array('egt',time());
        $data=$this->query($table,$where);
        return $data;
    }

    //通用封装方法
    function json($code,$message = '',$data = array())
    {
        if (!is_numeric($code)){
            return '错误';
        }
        if($data){
            $result = array(
                'code' => $code,
                'message' => $message,
                'data' => $data
            );
        }else{
            $result = array(
                'code' => $code,
                'message' => $message,
            );
        }
        $arr=json_encode($result);
        header('Content-type:text/json');
        echo $arr;
        exit;
    }

    function json_public($content,$requestId,$sign='',$_timestamp='',$appId='TOB')
    {
        if($sign){
            $arr=array(
                "appId"=>$appId,
                "requestId"=> $requestId,
                "sign"=>$sign,
                "content"=>$content
            );
        }else{
            $arr=array(
                "appId"=>$appId,
                "requestId"=> $requestId,
                "content"=>$content
            );
        }

        $arr=json_encode($arr,JSON_PRETTY_PRINT);
        header('Content-type:text/json');
        echo $arr;
    }

    //查询数据
    function query($table,$where,$order='id'){
        $where['deleted']='0';
        $data=M($table)->where($where)->order($order)->select();
        return $data;

    }
    //查找数据
    function find($table,$id){
        $data=M($table)->find($id);
        return $data;
    }

    //插入数据
    function insert($var,$table)
    {
        $m = D($table);
        $var['ctime'] = time();
        if (!$m->create($var)) {
            echo $m->getError();
        }
        $id=$m->add($var);
        if ($id) {
            return $id;
        } else {
            return 0;
        }
    }
    //更新数据
    function update($var,$table){
        if (D($table)->save($var)) {
            return 1;
        } else {
            return 0;
        }
    }
    //逻辑删除
    function del($var,$table){
        $var[id] = $var;
        $var['deleted'] = 1;
        if (D($table)->save($var)) {
            return 1;
        } else {
            return 0;
        }
    }
    //物理删除
    function realdel($var,$table)
    {
        $count = D($table)->delete($var);
        if ($count > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    //json
    public static function jsonEncode($code,$message='',$data='')
    {
        if(!is_numeric($code))
        {
            return '';
        }

        $result = array(
            'code' => $code,
            'message' => $message,
            'data' => $data,
        );
        header("Content-type:text/json;chaset=utf-8");
        return json_encode($result);
    }

    //xml接口
    public static function xmlEncode($code,$message,$data=array())
    {
        if(!is_numeric($code))
        {
            return '';
        }

        $result = array(
            'code' => $code,
            'message' => $message,
            'data' => $data,
        );

        header("Content-type:text/xml;chaset=utf-8");
        $xml = "<?xml version='1.0' encoding='utf-8'  ?>\n";
        $xml .= "<root>\n";
        $xml .= self::xmlToEncode($result);
        $xml .=    "</root>\n";

        return $xml;

    }

    //xml内容循环
    public static function xmlToEncode($data)
    {
        if(empty($data))
        {
            return '';
        }
        $xml = $attr = '';
        foreach ($data as $key => $value)
        {
            if(is_numeric($key))
            {
                $attr = "id='{$key}'";
                $key = "item";
            }
            $xml .= "<{$key} {$attr}>";
            $xml .= is_array($value) ? self::xmlToEncode($value) : $value ;
            $xml .= "</{$key}>\n";
        }

        return $xml;
    }


}