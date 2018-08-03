<?php

namespace Jirapi\Controller;
class SprintController extends BasicController
{
    //初始化信息
    function init()
    {
        $data = array(
            'table' => 'ao_60db71_sprint',
            'where' => array('RAPID_VIEW_ID' => $_GET['RAPID_VIEW_ID']),
            'map' => '',
            'order' => '',
            'field' => '',

        );
        return $data;
    }


}