<?php
/**
 * 定义路由别名
 * @author wave
 */

/**
 * 第一个参数:设置访问的url
 * 第二个参数:默认的分组目录
 * 第三个参数:默认的控制器名
 * 第四个参数:默认的方法名
 * 第五个参数:方法名里面的参数
 */
RouteApi::aliasRoute('/index.html','home','test','aaa',array(3,5));

?>