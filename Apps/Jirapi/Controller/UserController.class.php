<?php
namespace Jirapi\Controller;
class UserController extends BasicController
{
    function init()
    {
        $data = array(
            'table' => 'ao_2d3bea_user_index',
            'where' => array('USER_KEY' => $_GET['USER_KEY']),
            'map' => '',
            'order' => '',
            'field' => '',
        );
        return $data;
    }


}