<?php
return array(
	//'配置项'=>'配置值'
    'TMPL_PARSE_STRING'=>array(
        '__IMAGES__' => 'https://xiuliguanggao.com/Public/images/Xinda',
        '__CSS__' => 'https://xiuliguanggao.com/Public/css',
        '__JS__' => 'https://xiuliguanggao.com/Public/js',
        '__IMG__' => 'https://xiuliguanggao.com/Public/images',
        '__UPLOAD__' => 'https://xiuliguanggao.com/Upload'
    ),
    'DEFAULT_THEME'     =>  'Amaze',//开启模板
    'PRODID'            =>  6,                                       //定义产品编号
    'PRODUCT'           =>  'Xinda',                                 //定义产品编号  
    'maxPageNum'        =>  10,
    'WEBSERVER'         =>  'http://www.zhihuixinda.com',            //定义服务器域名
    'WX_APPID'          =>  'wx0a29084c1bf1b6dc',                    //定义微信APPID
    'WX_APPSECRET'      =>  '3972363f69f4a52d4dc18f919eba499b',
    'DD_CorpID'         =>  'ding2dd7ad30df846b0b35c2f4657eb6378f', //定义钉钉ID
    'DD_CorpSecret'     =>  'cWgbhfurGtBdr2ouCXjpAWD6ZrPUg-PY0ZM3nfKCb9APUhwxahyJHl8WC5uL3nwx',
    'DD_SSOsecret'      =>  'p3cjWwkYuLmSUYYHxuAZ-VMzMLoBNvvjOtIpPSm3mH-mNp3soxfRaOP9a61e7w_F',
    'DD_ChannelSecret'  =>  'Jaim9lqysgvJOTWHwIqHJ7tkzVuT0j-mrsS7A559x7wATOUinJIW5lu-PixLOT7w',
    'txy_AppId'         =>  '1252402623',  //定义腾讯云APPID
    'txy_SecretId'      =>  'AKIDAB1f6LpnzzoTzKeQBkhZIpAJF2eao4Wq',
    'txy_SecretKey'     =>  'HQOAoGSZVFmEammaFi4ExZ7UwLYggWFm',
    'www_key'           =>  '9oS2m7HyLS',
    'apiKey'           =>  'KyHm6jslW4',
    'wx_mume'=>'{
                  	"button": [
                        {
                            "name": "智慧信达", 
                            "sub_button": [
                                {
                                    "type": "view", 
                                    "name": "信达官网", 
                                    "url": "http://www.zhihuixinda.com/index.php/Xinda"
                                }, 
                                {
                                    "type": "miniprogram", 
                                    "name": "双人成团", 
                                    "url": "http://www.zhihuixinda.com/index.php/Xinda",
                                    "appid":"wxe6f24ce3395cb972",
                                    "pagepath":"pages/index/index",
                                }, 
                                {
                                    "type": "click",  
                                    "name": "诚邀加盟", 
                                    "key": "item1"
                                }, 
                                {
                                    "type": "click", 
                                    "name": "最新活动", 
                                    "key": "item2"
                                }, 
                                {
                                    "type": "click", 
                                    "name": "最新政策", 
                                    "key": "item3"
                                }
                            ]
                        }, 
                        {
                            "name": "微信商城", 
                            "sub_button": [
                                {
                                    "type": "view", 
                                    "name": "电脑", 
                                    "url": "http://www.zhihuixinda.com/index.php/Xinda/Servicelist/index/cate/9h0zez"
                                }, 
                                {
                                    "type": "view", 
                                    "name": "手机", 
                                    "url": "http://www.zhihuixinda.com/index.php/Xinda/Servicelist/index/cate/Plxo5q"
                                }, 
                                {
                                    "type": "view", 
                                    "name": "平板", 
                                    "url": "http://www.zhihuixinda.com/index.php/Xinda/Servicelist/index/cate/QaARsI"
                                }, 
                                {
                                    "type": "view", 
                                    "name": "服务器", 
                                    "url": "http://www.zhihuixinda.com/index.php/Xinda/Servicelist/index/cate/ct5y5E"
                                }, 
                                {
                                    "type": "view", 
                                    "name": "相关配件", 
                                    "url": "http://www.zhihuixinda.com/index.php/Xinda/Servicelist/index/cate/DGePKX"
                                }
                            ]
                        }, 
                        {
                            "name": "专属服务", 
                            "sub_button": [
                            	{
                                    "type": "view", 
                                    "name": "个人中心", 
                                    "url": "http://www.zhihuixinda.com/index.php/Xinda/Customer/personal"
                                },
                                {
                                    "type": "click", 
                                    "name": "订单", 
                                    "key": "item5"
                                },
                                {
                                    "type": "click", 
                                    "name": "优惠券", 
                                    "key": "item6"
                                },
                                {
                                    "type": "click", 
                                    "name": "积分商城", 
                                    "key": "item4"
                                }
                            ]
                        }
                    ]
                }',
    
);