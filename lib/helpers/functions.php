<?php
/**
 * 定义全局方法
 * @author wave
 */

/**
 * url转换
 * @param Array $params 参数
 * @param bool $urlRewrite 是否伪静态
 * @author wave
 */
function urlTo($params = array(),$urlRewrite = false){
	if($urlRewrite){
		$diff  = array();
		if(isset($params[G]) && isset($params[C]) && isset($params[A]) ){
			$diff =  array($params[G],$params[C],$params[A]);
		}	   
		$params = array_diff($params, $diff);
	    $urlStr = '';
	    foreach($params as $key => $value){
	    	$urlStr .= ROUTE_DS.$key.ROUTE_DS.$value;
	    }
		return ROUTE_DS.implode(ROUTE_DS, $diff).$urlStr;
	}
	return '?'.http_build_query($params);
}