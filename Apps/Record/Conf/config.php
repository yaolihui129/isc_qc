<?php
return array(
    //'配置项'=>'配置值'
    'TMPL_PARSE_STRING' => array(
        '__CSS__' => 'https://xiuliguanggao.com/Public/css',
        '__JS__' => 'https://xiuliguanggao.com/Public/js',
        '__IMG__' => 'https://xiuliguanggao.com/Public/images',
        '__UPLOAD__' => 'https://xiuliguanggao.com/Upload'
    ),
    //默认错误跳转对应的模板文件
    'TMPL_ACTION_ERROR' => 'Public:error',
    //默认成功跳转对应的模板文件
    'TMPL_ACTION_SUCCESS' => 'Public:success',
    'KH_QUARTER' => '2018Q3-Q4',//考核周期
    'DEFAULT_THEME'     => 'Amaze',//开启模板
    'PRODUCT'           => 'Record',//定义产品编号
    'QC_TESTER' => array('yaolihui', 'fanqiao', 'wangchenzi', 'menghuihui', 'lixm'),
    'DRAW' => array('2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A', 'JOKER'),
);