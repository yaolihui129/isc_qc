<?php

    function countFuncRangeId($table,$name,$value){
        $where=array($name=>$value,"deleted"=>'0','type'=>'1');
        $count=M($table)->where($where)->count();
        return $count;
    }

    //获取我的必测点数量
    function myMustTestNum($user){
        $map['testgp'] = 'YX';
        $map['deleted'] = '0';
        $map['status'] = array('in', array('wait', 'doing'));
        $arr = M('project')->where($map)->select();
        foreach ($arr as $k=>$ar){
            $arr[$k]=$ar['id'];
        }
        $where['project']=array('in',$arr);
        $where['owner']=$user;
        $where['deleted'] = '0';
        $count= D("tp_my_scene")->where($where)->count();
        return $count;
    }

    //获取项目下分派给我的场景数量
    function countMyProjectScene($project){
        $where['project']=$project;
        $where['owner']=$_SESSION['account'];
        $where['deleted'] = '0';
        $count= M("tp_my_scene")->where($where)->count();
        return $count;
    }

    //当前项目下分派给指定人员的场景数量
    function countUserProjectScene($user){
        $where['project']=$_SESSION['proid'];
        $where['owner']=$user;
        $where['deleted'] = '0';
        $count= M("tp_my_scene")->where($where)->count();
        return $count;
    }

    //获取我的场景信息
    function getMySceneInfo($id){
        $arr=M('tp_my_scene')->find($id);
        $str='<span class="label label-danger">'.getName('tp_scene',$arr['scene'],'level').'</span>'
            . '<span class="label label-default">' . getName('tp_scene', $arr['scene'], 'swho') . '</span>'
            .'<span class="label label-primary">'.getName('tp_scene',$arr['scene'],'swhen').'</span>'
            .'<small>'.getName('tp_scene',$arr['scene'],'scene').'</small>'
            . '<span class="badge">' . countId('tp_my_scene_func', 'myscene', $id) . '</span>';
        return $str;
    }

    //获取我的场景信息
    function getSceneInfo($id){
        $arr=M('tp_scene')->find($id);
        $str='<span class="label label-danger">'.$arr['level'].'</span>'
            . '<span class="label label-default">' . $arr['swho'] . '</span>'
            .'<span class="label label-primary">'.$arr['swhen'].'</span>'
            .'<small>'.$arr['scene'].'</small>'
            . '<span class="badge pull-right">' . countId('tp_scene_func', 'scene', $id) . '</span>'
        ;
        return $str;
    }


    //获取场景结果
    function getSceneResult($value)
    {
        if($value==1){
            return '通过';
        }elseif ($value==2){
            return '失败';
        }elseif ($value==3){
            return '阻塞';
        }elseif ($value==4){
            return '错误';
        }elseif ($value==0){
            return '未测';
        }else{
            return ;
        }
    }
    //获取功能点信息
    function getFuncInfo($funcId){
        $data=M('tp_func')->find($funcId);
        $str = '<span class="label label-default">' . getName('branch', $data['branch']) . '</span>'
            .'<span class="label label-primary">'.getModuleName($data['module']).'</span>'
            .'<small>'.$data['func'].'</small>';
        return $str;
    }

function getDrawInfo($project)
{
    $where = array('project' => $project, 'deleted' => '0');
    $data = M('tp_project_assigne')->where($where)->select();
    $str = '';
    if ($data) {
        $str .= '<ul class="list-group">';
        foreach ($data as $ar) {
            $str .= '<li class="list-group-item">';
            $str .= getZTUserName($ar['name']) . idRenounce($ar['renounce']);
            $str .= '<span class="badge">' . $ar['draw'] . '</span>';
            $str .= '</li>';
        }
        $str .= '</ul>';
    }
    return $str;
}


function countMyProject($name)
{
    $status = array('wait', 'doing', 'suspended');
    $where['status'] = array('in', $status);
    $where = array('type' => 'waterfall', 'QD' => $name, 'deleted' => '0');
    $count = M('project')->where($where)->count();
    return $count;
}

function countDraw($name)
{
    $where = array('type' => 'waterfall', 'deleted' => '0', 'status' => 'wait');
    $project = M('project')->where($where)->select();
    foreach ($project as $p) {
        $pro[] = $p['id'];
    }
    $map['project'] = array('in', $pro);
    $map['name'] = $name;
    $assigne = M('tp_project_assigne')->where($map)->count();
    $count = sizeof($project) - $assigne;

    return $count;
}

function idRenounce($renounce)
{
    if ($renounce) {
        $str = '放弃';
    } else {
        $str = '';
    }
    return $str;
}
    