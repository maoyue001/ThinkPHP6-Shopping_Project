<?PHP
    /*
     | Submail mobiledata/package API demo
     | SUBMAIL SDK Version 2.6 --PHP
     | copyright 2011 - 2017 SUBMAIL
     |--------------------------------------------------------------------------
     */
    
    /*
     |载入 app_config 文件
     |--------------------------------------------------------------------------
     */
    require '../app_config.php';
    
    /*
     |载入 SUBMAILAutoload 文件
     |--------------------------------------------------------------------------
     */
    
    require_once('../SUBMAILAutoload.php');
    
    /*
     |初始化 MOBILEDATAPackage 类
     |--------------------------------------------------------------------------
     */
    
    $submail=new MOBILEDATAPackage($mobiledata_configs);
    
    
    /*
     |调用 Package 方法获取手机流量包单价和请求标示
     |--------------------------------------------------------------------------
     */
    
    $package=$submail->package();
    
    
    /*
     |打印服务器返回值
     |--------------------------------------------------------------------------
     */
    
    print_r($package);
