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
//RouteApi::aliasRoute('/index.html','home','test','aaa');
//一个{:number}绑定一个参数key,不能重复,访问则index.php/index/111/222.html 则会写入对应的参数
RouteApi::aliasRoute('/index/{:1}/{:2}.html','home','test','sss', array('bc','ac'));
?>