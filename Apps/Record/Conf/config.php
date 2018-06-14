<?php
return array(
    //'配置项'=>'配置值'
    'TMPL_PARSE_STRING' => array(
        '__CSS__'       => __ROOT__ . '/Public/css',
        '__JS__'        => __ROOT__ . '/Public/js',
        '__IMG__'       => __ROOT__ . '/Public/images',
        '__UPLOAD__'    => __ROOT__ . '/Upload/'
    ),
    'KH_QUARTER'        =>'2018Q1-Q2',//考核周期
    'DEFAULT_THEME'     => 'Amaze',//开启模板
    'PRODUCT'           => 'Record',//定义产品编号
    'QC_TESTER'         =>array( 'yaolihui','fanqiao','wangchenzi','menghuihui','lixm','qinzx',),
);