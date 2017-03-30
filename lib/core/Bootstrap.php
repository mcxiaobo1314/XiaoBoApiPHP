<?php
/**
 * 初始化核心类文件
 * @author wave
 */

class Bootstrap {

	/**
	 * 初始化函数
	 * @author wave
	 */
	public static function instace(){
		//初始化自由组装组件
		Container::instace('XmlParse');

		$XmlParse = Container::get('XmlParse');

		//初始化XML解析器
		$XmlParse->init();
	}

}

?>