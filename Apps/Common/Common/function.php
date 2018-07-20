<?php    
    function getState($key){// 根据id获取状态
        $where=array('type'=>'state','k'=>$key);
        $data=M('tp_dict')->where($where)->find();
        return $data['v'];
    }

    //根据pid获取分类数
    function countCate($pidCateId){
        $where=array('pidCateId'=>$pidCateId);
        $data=M('tp_cate')->where($where)->count();
        return $data;
    }
    //获取分类名字
    function getCatname($cateid){
        if ($cateid){
            $data=M('tp_cate')->find($cateid);
            $str=getCatname($data['pidcateid'])."-".$data['catname'];
            return $str;
        }else {
            return "|-";
        }
    }

    //获取父级分类ID
    function getCatePid($cateId){
        $data=M('tp_cate')->find($cateId);
        return $data['pidcateid'];
    }
    //获取页面信息
    function getWebInfo($qz){
        $data=M('xl_web')->where(array('qz'=>$qz))->field('id,web,adress,desc,phone,tel,qq,qz,url,record')->find();
        $_SESSION[$qz]=$data;
        $_SESSION['ip']=get_client_ip();
        $_SESSION['browser']=GetBrowser();
        $_SESSION['os']=GetOs();
    }
    //获取征信电话
    function getCreditidPhone($creditId){
        $data=M('tp_credit')->find($creditId);
        $str = substr_replace($data['phone'],'****',3,4);
        return $str;
    }


    //登录
    function login($phone,$password){
        $where=array('phone'=>$phone,['password']=>md5($password));
        $data=M('tp_credit')->where($where)->find();
        if ($data){
            $_SESSION['isCLogin']= C(PRODUCT);
            $_SESSION['realname']= $data['realname'];
            $m=M('tp_customer');
            $where=array('creditid'=>$data['creditid'],'prodid'=>C(PRODID));
            $arr=$m->where($where)->find();
            if($arr){
            $_SESSION['userid'] =   $arr['id'];
            $_POST['id']        =   $arr['id'];
            $_POST['lastLoginTime']=date("Y-m-d H:i:s",time());
            $_POST['lastLoginIP']=get_client_ip();
            $m->save($_POST);//更新最后登录信息
            }else {
            $_POST['prodid']=C(PRODID);
            $_POST['creditid']=$data['id'];
            $_POST['name']=$data['realname'];
            $_POST['type']=0;
            $_POST['lastLoginTime']=date("Y-m-d H:i:s",time());
            $_POST['lastLoginIP']=get_client_ip();
            $_POST['adder']='客户登录';
            $_POST['moder']='客户登录';
            $_POST['ctime']=time();
            $m->create();
            $_SESSION['userid']=$m->add();
        }
            return $data;
        }else{
            return 0;
        }
    }

    //注销
    function logout(){
        $_SESSION = array();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(),'',time()-3600,'/');
        }
        session_destroy();// 销毁sesstion
    }


    //获得访客浏览器类型
    function GetBrowser(){
        if(!empty($_SERVER['HTTP_USER_AGENT'])){
            $br = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/MSIE/i',$br)) {
            $br = 'MSIE';
        }elseif (preg_match('/Firefox/i',$br)) {
            $br = 'Firefox';
        }elseif (preg_match('/Chrome/i',$br)) {
            $br = 'Chrome';
        }elseif (preg_match('/Safari/i',$br)) {
            $br = 'Safari';
        }elseif (preg_match('/Opera/i',$br)) {
            $br = 'Opera';
        }else {
            $br = 'Other';
        }

            return $br;
        }else{
            return "获取浏览器信息失败！";
        }
    }

    //获取访客操作系统
    function GetOs(){
        if(!empty($_SERVER['HTTP_USER_AGENT'])){
            $OS = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/win/i',$OS)) {
            $OS = 'Windows';
        }elseif (preg_match('/mac/i',$OS)) {
            $OS = 'MAC';
        }elseif (preg_match('/linux/i',$OS)) {
            $OS = 'Linux';
        }elseif (preg_match('/unix/i',$OS)) {
            $OS = 'Unix';
        }elseif (preg_match('/bsd/i',$OS)) {
            $OS = 'BSD';
        }else {
            $OS = 'Other';
        }
            return $OS;
        }else{
            return "获取访客操作系统信息失败！";
        }
    }

    //获取随机码
    function getRandCode($length){
        $array = array(
            'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
            'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
            '0','1','2','3','4','5','6','8','9'
        );
        $tmpstr ='';
        $max =count($array);
        for($i=1;$i<=$length;$i++){
            $key =rand(0,$max-1);
            $tmpstr.=$array[$key];
        }
        return $tmpstr;
    }

    // CURL_GET操作
    function httpGet($url){
        $ch=curl_init(); //1.获取初始化URL
        //2.设置curl的参数
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 500);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        $res = curl_exec($ch);//3.采集
        curl_close($ch);//4.关闭
        if(curl_errno($ch)){
            $res=curl_errno($ch);
        }
        return $res;
    }

function httpAuthGet($url, $user = 'ylh', $password = '123456')
{
    $ch = curl_init(); //1.获取初始化URL
    //2.设置curl的参数
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 500);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "$user:$password");
    curl_setopt($ch, CURLOPT_URL, $url);
    $res = curl_exec($ch);//3.采集
    curl_close($ch);//4.关闭
    if (curl_errno($ch)) {
        $res = curl_errno($ch);
    }
    return $res;
}

    function httpPost($url,$postJson){
        //1.获取初始化URL
        $ch=curl_init();
        //2.设置curl的参数
        curl_setopt($ch, CURLOPT_TIMEOUT, 500);       //设置超时时间
        curl_setopt($ch, CURLOPT_URL, $url);          //设置抓取的url
        curl_setopt($curl, CURLOPT_HEADER, 1);        //设置头文件的信息作为数据流输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_POST, 1);            //设置post方式提交
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postJson);//post变量
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);//3.采集
        curl_close($ch);//4.关闭
        if(curl_errno($ch)){
            $res=curl_errno($ch);
        }
        return $res;
    }

function httpAuthPost($url, $postJson, $user = 'ylh', $password = '123456')
{
    //1.获取初始化URL
    $ch = curl_init();
    //2.设置curl的参数
    curl_setopt($ch, CURLOPT_TIMEOUT, 500);       //设置超时时间
    curl_setopt($ch, CURLOPT_URL, $url);          //设置抓取的url
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "$user:$password");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, "cookiefile");
    curl_setopt($ch, CURLOPT_COOKIEJAR, "cookiefile");
    curl_setopt($ch, CURLOPT_COOKIE, session_name() . '=' . session_id);
    curl_setopt($ch, CURLOPT_HEADER, 0);        //设置头文件的信息作为数据流输出
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($ch, CURLOPT_POST, 1);            //设置post方式提交
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postJson);//post变量
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $res = curl_exec($ch);//3.采集
    curl_close($ch);//4.关闭
    if (curl_errno($ch)) {
        $res = curl_errno($ch);
    }
    return $res;
}


    //微信纯文本回复
    function wxReplyText($toUser,$fromUser,$content){
        //回复用户消息(纯文本格式)
        $msgType   = 'text';
        $time      = time();
        $template  ="<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[%s]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        </xml>";
        echo sprintf($template,$toUser,$fromUser,$time,$msgType,$content);
    }

    //微信图文回复
    function wxReplyNews($toUser,$fromUser,$arr){
        $msgType   = 'news';
        $time      = time();
        $template = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <ArticleCount>".count($arr)."</ArticleCount>
            <Articles>";
                foreach($arr as $v){
                    $template .="<item>
                    <Title><![CDATA[".$v['title']."]]></Title>
                    <Description><![CDATA[".$v['description']."]]></Description>
                    <PicUrl><![CDATA[".$v['picUrl']."]]></PicUrl>
                    <Url><![CDATA[".$v['url']."]]></Url>
                    </item>";
                }
            $template .="
            </Articles>
        </xml> ";
        echo sprintf($template, $toUser, $fromUser, $time, $msgType);
    }

    //微信图片回复
    function wxReplyImage($toUser,$fromUser,$mediaId){
        $msgType   = 'image';
        $time      = time();
        $template  = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Image><MediaId><![CDATA[%s]]></MediaId></Image>
        </xml>";
        echo sprintf($template, $toUser, $fromUser, $time, $msgType,$mediaId);
    }

    //微信语音回复
    function wxReplyVoice($toUser,$fromUser,$mediaId){
        $msgType   = 'voice';
        $time      = time();
        $template  = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Voice><MediaId><![CDATA[%s]]></MediaId></Voice>
        </xml>";
        echo sprintf($template, $toUser, $fromUser, $time, $msgType,$mediaId);
    }

    //微信视频回复
    function wxReplyVideo($toUser,$fromUser,$mediaId,$title,$description){
        $msgType   = 'video';
        $time      = time();
        $template = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Video>
                <MediaId><![CDATA[%s]]></MediaId>
                <Title><![CDATA[%s]]></Title>
                <Description><![CDATA[%s]]></Description>
            </Video>                
        </xml>";
        echo sprintf($template, $toUser, $fromUser, $time, $msgType,$mediaId,$title,$description);
    }

    //微信音乐回复
    function wxReplyMusic($toUser,$fromUser,$mediaId,$title,$description,$musicUrl,$HQMusicUrl,$thumbMediaId){
        $msgType   = 'music';
        $time      = time();
        $template = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Music>
                <Title><![CDATA[%s]]></Title>
                <Description><![CDATA[%s]]></Description>
                <MusicUrl><![CDATA[%s]]></MusicUrl>
                <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
            </Music>
        </xml>";
        echo sprintf($template, $toUser, $fromUser, $time, $msgType,$title,$description,$musicUrl,$HQMusicUrl,$thumbMediaId);
    }

    function wxNewsArr($size){
        $str=array();
        for ($x=0; $x<=$size; $x++) {
        $str.="array(
            'title'        =>  ".'$data['.$x."]['name'],
            'description'  =>  ".'$data['.$x."]['content'],
            'picUrl'       =>  C(WEBSERVER).'/Upload/'.".'$data['.$x."]['productimg'],
            'url'          =>  C(WEBSERVER).'/index.php/'.C(PRODUCT).'/Service/index/id/'.".'$data['.$x."]['productid']".".'/wxOpenId/'".'.$toUser.'."'/wxAppId/'".'.$fromUser,'."
            ),";
        }
        return $str;
    }

    //换取微信短链接
    function getShortUrl($token,$long_url){
    $url='https://api.weixin.qq.com/cgi-bin/shorturl?access_token='.$token;
    $array=array('action'=>'long2short','long_url'=>$long_url );                        //组装数组
    $postJson = json_encode($array);                                                    //封装json
    $res = httpPost($url, $postJson);
    $res = json_decode($res,true);
    return $res['short_url'];
    }

    //获取临时二维码
    function getTimeQrCode($token,$scene_id,$expire=30){
        $url='https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$token;
        $expire = $expire*24*60*60;
        $postArr =array(                                                                //组装数组
            'expire_seconds'=>$expire,
            'action_name'=>"QR_SCENE",
            'action_info'=>array('scene'=>array('scene_id'=>$scene_id) )
        );
        $postJson = json_encode($postArr);                                              //封装json
        $res = httpPost($url, $postJson);                                               //获取 $ticket
        $res = json_decode($res,true);                                          //转换成数组
        $long_url='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$res['ticket']; //使用$ticket换去二维码图片
        return getShortUrl($token,$long_url);
    }

//获取永久二维码
    function getForeverQrCode($token,$scene_id){
        $url='https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$token;
        $postArr =array(                                                                //1.组装数组
            'action_name'=>"QR_LIMIT_SCENE",
            'action_info'=>array('scene'=>array('scene_id'=>$scene_id))
        );
        $postJson = json_encode($postArr);                                              //2.封装json
        $res = httpPost($url, $postJson);
        $res = json_decode($res,true);
        $long_url='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$res['ticket']; //3.使用$ticket换去二维码图片
        return getShortUrl($token,$long_url);
    }

    //群发接口
    function wxSendMsgAll($token,$array,$type='preview'){
        if($type=='preview'){           //预览接口
            $url='https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token='.$token;
            $postJson =urldecode(json_encode($array));
            $res = httpPost($url, $postJson);
            return $res;
        }elseif ($type=='send'){        //群发接口
            $url='https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.$token;
            $postJson =urldecode(json_encode($array));
            $res = httpPost($url, $postJson);
            return $res;
        }else {
            return '发送类型不合规！';
        }
    }

    //获取微信服务器IP
    function wxGetServerIp($token){
        if(!$_SESSION['wx_ip_list']){
            $url = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=".$token;
            $res = httpGet($url);
            $_SESSION['wx_ip_list'] =json_decode($res,true);
        }
        return $_SESSION['wx_ip_list'];
    }

    //获取wxGetJsApiTicket全局票据
    function wxGetJsApiTicket($token){
        if ((time()-$_SESSION['jsapi_ticket_expire_time']) < 0){
            //从session中取值  jsapi_ticket
            $jsapi_ticket = $_SESSION['jsapi_ticket'];
        }else {
            //重新获取jsapi_ticket
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$token."&type=jsapi";
            $res =json_decode(httpGet($url),true);
            $_SESSION['jsapi_ticket']=$res['ticket'];
            $_SESSION['jsapi_ticket_expire_time']=time()+7000;
            $jsapi_ticket=$res['ticket'];
        }
        return $jsapi_ticket;
    }

    //发送微信模板消息
    function wxSendTemplateMsg($token,$touser,$template_id,$call_url,$data){
        $url      = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$token;
        $Meg      = array('touser'=>$touser,'template_id'=>$template_id,'url'=>$call_url,'data'=>$data);   //2.组装数组
        $postJson = json_encode($Meg);      //将数组转化成json
        $res      = httpPost($url, $postJson);
        return $res;
    }

    //创建自定义菜单
    function wxMenuCreat($token,$postJson) {
        $url      = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$token;
        $res      = httpPost($url,$postJson);
        return $res;
    }

    //获取自定义菜单
    function wxGetMenu($token) {
        $url   = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token='.$token;
        $res   = httpGet($url);
        $arr   = json_decode($res,true);
        return $arr;
    }

    //截取字符串最后的“。”（不管几个一并截取）用于语音识别结果
    function wxRtrim($arr,$a='。'){
        $arr=rtrim($arr, $a);
        return $arr;
    }

    //根据日期获取星期
    function wk($date) {
        $datearr = explode("-",$date);     //将传来的时间使用“-”分割成数组
        $year = $datearr[0];       //获取年份
        $month = sprintf('%02d',$datearr[1]);  //获取月份
        $day = sprintf('%02d',$datearr[2]);      //获取日期
        $hour = $minute = $second = 0;   //默认时分秒均为0
        $dayofweek = mktime($hour,$minute,$second,$month,$day,$year);    //将时间转换成时间戳
        $shuchu = date("w",$dayofweek);      //获取星期值
        $weekarray=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
        return  $weekarray[$shuchu];
    }

    // 根据id获取项目信息
    function getPro($proid){
        $data=M('project')->find($proid);
        if($data){
            return $data['name']."(".$data['end']."上线)";
        }else{
            return '【未关联】迭代或项目';
        }

    }


function countPRules($proid){
        $where=array("zt_projectstory.project"=>$proid, 'zt_story.deleted'=>'0');
        $count=M('story')->where($where)->join('zt_projectstory ON zt_projectstory.story =zt_story.id')->count();
        return $count;
    }

    //根据ModuleId获取功能信息
    function getParentModule($id){
        $data=M("module")->find($id);
        if($data['parent']){
            $str=getModuleName($data['parent']).'-';
        }else{
            $str='';
        }
        return $str;
    }

    //根据$pathid获取模块名
    function getModuleName($pathid){
        $data=M('module')->find($pathid);
        if ($data['parent']){
            $str=getModuleName($data['parent']).'-'.$data['name'];
        }else {
            $str=$data['name'];
        }
        return $str;
    }
    //获取需求详情
    function getStorySpec($storyId){
        $str='';
        $where=array('story'=>$storyId);
        $arr=M('storyspec')->where($where)->order('version desc')->select();
        foreach ($arr as $ar){
            $str.='<li class="list-group-item">#'.$ar['version'].'<hr><p>'.$ar['spec'].'</p></li>';
        }
        return $str;
    }


function getProst($value){
        if($value=='doing'){
            return '进行中';
        }elseif ($value=='done'){
            return '已完成';
        }elseif ($value=='wait'){
            return '未开始';
        }elseif ($value=='suspended'){
            return '已挂起';
        }elseif ($value=='closed'){
            return '已关闭';
        }elseif ($value=='active'){
            return '激活';
        }elseif ($value=='resolved'){
            return '已解决';
        }else{
            return ;
        }
    }

    //获取BUG状态
    function getBugStauts($value){
        if($value=='active'){
            return '激活';
        }elseif ($value=='closed'){
            return '已关闭';
        }elseif ($value=='resolved'){
            return '已解决';
        }elseif ($value=='changed'){
            return '已变更';
        }else{
            return ;
        }
    }

    function countId($table,$name,$value){
        $where=array($name=>$value,"deleted"=>'0');
        $count=M($table)->where($where)->count();
        return $count;
    }

    function getName($table,$id,$name='name'){
        $data=M($table)->find($id);
        return $data[$name];
    }

//状态选择控件,@param $name 控件name;@param $value 选中值
    function formselect($value="正常",$name="state",$type="state") {
        $where=array("type"=>$type,"state"=>"正常");
        $cats = M('tp_dict')->where($where)->order('k')->select();
        $html = '<select name="'.$name.'" class="form-control">';
        foreach($cats as $v) {
            $selected = ($v['v']==$value) ? "selected" : "";
            $html .= '<option '.$selected.' value="'.$v['v'].'">'.$v['v'].'</option>';
        }
        $html .='</select>';
        return $html;
    }
    //周期积分汇总
    function sumScore($user,$quarter){
        $m=M('tp_my_score');
        $where=array('quarter'=>$quarter,'type'=>'1','user'=>$user,'deleted'=>'0');
        $jiaf=$m->where($where)->sum('score');
        $where=array('quarter'=>$quarter,'type'=>'2','user'=>$user,'deleted'=>'0');
        $jianf=$m->where($where)->sum('score');
        return $jiaf-$jianf;
    }

    function countOwnerProject($user){
        $where['testgp'] = 'YX';
        $where['deleted']='0';
        $status = array('wait', 'doing','suspended');
        $where['status'] = array('in', $status);
        $where['QD']=$user;
        $count =M('project')->where($where)->count();
        return $count;
    }

    //获取禅道用户名
    function getZTUserName($account){
        if($account){
            $where=array('account'=>$account);
            $arr=M('user')->where($where)->find();
            return $arr['realname'];
        }else {
            return 'NoBody';
        }
    }

    //获取加密签名
    function get_sign($array,$_timestamp,$appkey,$type='md5'){
        //判定$array是否为数组
        if(!is_array($array)){
            $array=json_decode($array,TRUE);
        }
        $array['_timestamp']=date('Y-m-d H:i:s',$_timestamp);
        foreach ($array as $key=>$value){
            $arr[$key] = $key;
        }
        sort($arr);//将数组按
        //拼接字符串
        $str = $appkey;
        foreach ($arr as $k => $v) {
            $str = $str.$arr[$k].$array[$v];
        }
        if($type=='md5'){
            //数据加密并转换为大写
            $str=strtoupper(md5($str));
        }elseif ($type=='sha1'){
            //数据加密并转换为大写
            $str=strtoupper(sha1($str));
        }else{
            $str='加密方式暂不支持';
        }
        return $str;
    }

    function verify_sign($array,$appkey,$type='md5')
    {
        $clientSign=$array['_sign'];
        unset($array['_sign']);
        foreach ($array as $key=>$value){
            $arr[$key] = $key;
        }
        sort($arr);//将数组按
        $serverstr = "";
        foreach ($arr as $k => $v) {
            $serverstr = $serverstr.$arr[$k].$array[$v];
        }
        #生成服务端str
        $reserverstr=$appkey.$serverstr;
        if($type=='md5'){
            $reserverSign = strtoupper(md5($reserverstr));
        }elseif ($type=='sha1'){
            $reserverSign = strtoupper(sha1($reserverstr));
        }
        if($clientSign!=$reserverSign){
            return 0;//验证失败
        }else{
            return 1;//验证成功
        }
    }
    //手机端模板
    function ismobile() {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
            return true;
        //此条摘自TPM智能切换模板引擎，适合TPM开发
        if(isset ($_SERVER['HTTP_CLIENT']) &&'PhoneClient'==$_SERVER['HTTP_CLIENT'])
            return true;
        //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA']))
            //找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
        //判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array(
                'nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile'
            );
            //从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        //协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }

    function chaxun($t,$w,$o){
        $token=get_token();
        $postArr=array('token'=>$token,'t'=>$t,'w'=>$w,'o'=>$o);
        $postJson=json_encode($postArr);
        $url=C(WEBSERVER).'/index.php/Api/Device/chaxun';
        $json=httpPost($url,$postJson);
        $data = json_decode(trim($json,chr(239).chr(187).chr(191)),true);
        return $data['data'];
    }

    function get_token(){
        if (!S(C(PRODUCT) . 'access_token')) {//缓存中没有token先获取token，并设置失效时间
            $url=C(WEBSERVER).'/index.php/Api/Oauth/token'.'?grant_type=client_credential'.'&appid='.C(XL_APPID).'&secret='.C(XL_SECRET);
            $data=httpGet($url);
            dump($data);
            $info = json_decode(trim($data,chr(239).chr(187).chr(191)),true);
            S(C(PRODUCT) . 'access_token', $info['data']['access_token'], 7200);
            return S(C(PRODUCT) . 'access_token');
        } else {//缓存中有token直接返回
            return S(C(PRODUCT) . 'access_token');
        }
    }


function arrDate($data, $message = 'ok')
{
    if ($data) {
        $arr = array(
            'code' => 200,
            'data' => $data,
            'message' => $message
        );
    } else {
        $arr = array(
            'code' => 400,
            'message' => 'error'
        );
    }
    return $arr;
}
