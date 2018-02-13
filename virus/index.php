<?php
    
    ini_set('display_errors', true);
    
    if (isset($_GET['login']) && !empty($_GET['login']))
    {
        $connect = new PDO("sqlsrv:server=u8600-app045;database=portal", 'userPortal', 'Rt3&@qWWI885');
        return $connect->exec("insert into p_virusBanner (login_name) values ('{$_GET['login']}')");
        
    }


