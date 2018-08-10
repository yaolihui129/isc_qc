<?php
namespace Report\Controller;
class BugController extends WebInfoController {

    function unconfirmed(){

        $where['status']="active";
        $where['confirmed']=0;
        $where['deleted']='0';
        $where['assignedTo']=array('in',C(DEV_USER));//只看这些人员
        $datum=date("Y-m-d",time()-24*3600);
        $datum=strtotime($datum);//将日期转化为时间戳
        $datum=date("Y-m-d H:i",$datum+17.5*3600);
        $where['openedDate']  = array('lt',$datum);
        $data=M("bug")->where($where)->order('openedDate')->select();
        $this->assign('data',$data);
        
        $this->display();
    }
    
    function punish(){
        $where=array("state"=>"0");
        $data=M("tp_punish")->where($where)->order('ctime desc')->select();
        $this->assign('data',$data);
        
        $this->display();
    }
    
    function noregress(){
        $m = M("bug");
        //更改为未关闭的BUG
        $where['product'] = '6';
        $where['status'] = array('not in', 'closed');
        $where['deleted']='0';
        $data = $m->where($where)->order('branch,module')->select();
        $this->assign('data',$data);
        $count = $m->where($where)->count();
        $this->assign('count', $count);
        
        $this->display();
    }
    
    function activated(){
        $where['deleted']='0';
        $where['resolvedBy']=array('in',C(DEV_USER));//只看这些人员
        $where['activatedCount']  = array('gt',0);
        $where['openedDate']  = array('gt',C(BUG_DATE));
        
        $data=M("bug")->where($where)->order('openedDate desc,activatedDate desc')->select();
        $this->assign('data',$data);
        
        $this->display();
    }
    
    function fault(){
        $where['deleted']='0';
        $where['severity']  =1;
        $where['assignedTo']=array('in',C(DEV_USER));//只看这些人员
        $where['openedDate']  = array('gt',C(BUG_DATE));
        $data=M("bug")->where($where)->order('openedDate')->select();
        $this->assign('data',$data);
        
        $this->display();
    }
    
}