<?php
/**
 * 核心配置文件
 * @author wave
 */

define('ROUTE_DS','/'); 

define("DEBUG", true); //true开启debug false关闭debug

define("IS_AILAS",true); //true设置了别名url就不能用伪静态和动态访问,只能用别名url访问

define("BINDURLPARAM",true); //开启url参数绑定，函数如:aaa($test,$test2),URL如:&test=1&test2=333 才可以获取到

define('DEFAULT_ROUTE','/index.html'); //默认访问,写法: 分组文件夹名/控制器/方法名 如果定义别名url请写别名url

define('CONTROLLER','controller'); //控制器的目录

define('CON_SUFFOIX','Controller.php'); //控制器的后缀名字

define('MODEL','model'); //模型的目录

define('VIEW','view'); //视图的目录

define('CACHE','cache'); //缓存的目录

define('MOD_SUFFOIX','Model.php'); //模型的后缀名字

define('STORAGE', 'storage');  //web存储文件

define('LOG', 'log'); //日志目录

define('ERROR_PATH', dirname(__FILE__). '/../../'.STORAGE.ROUTE_DS.LOG.ROUTE_DS); //错误日志路径

define('SESSION_PATH', ''); //设置session文件目录，绝对路径

define('SESSION_SUFFIX','xb_'); //session文件的前缀

define('SESSION_LIFETIME','3600'); //session生存时间  单位秒

define('C','c'); //控制器

define('G','g'); //目录

define('A','a'); //方法
