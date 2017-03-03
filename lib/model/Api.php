<?php
/**
 * 组件接口
 * @author wave
 */

define('MODEL_TOKEN','xiaobo_php_model');

class ModelApi {

	/**
	 * 初始化函数
	 *@author wave
	 */
	static function init($type = 'Mysql') {
		require dirname(__FILE__).'/LoadModel.php';
		LoadModel::import('/'.$type.'.php',dirname(__FILE__));
	}
}

/*
	//注释:如果其他程序需要调用该MOLDE，则只要引入该API文件,然后初始化API就可以调用了
	//假如我使用的不是MYSQL，但想用其他人的封装好的类库，可以自己写入进来，代码规范的写法:

	//首先加入头部这一段代码，这个防止别人直接访问该文件
	if(!defined('MODEL_TOKEN')) {
		header("HTTP/1.1 404 not found");
		exit('404 not found');
	}
	
	//定义文件名，如Access数据库，则命名为:Access.php  定下就是代码:

	//继承代码，必须要实现 find,count,first,insert,save,saveAll,del,query方法代码,如果没有，则只要写一个空方法进去就行了
	class Access extends  Dao {
		
		//自己封装的代码区域
	}

	初始化的时候只要写ModelApi::init('Access') 就可以调用了
*/	


//初始化API入库,并选择数据库的类型
//ModelApi::init('Mysql');

//连接数据,并选择初始化模型(表名)类,使用示例:
// $a = LoadModel::load('mysql',array('xb_wengzhang',array(
// 	'host' => '127.0.0.1',
// 	'dbname' => 'blog',
// 	'user' => 'root',
// 	'pwd' => '',
// 	'charset' => 'utf8',
// 	'type' => 'mysql',
// 	'port' => '3306'
// )),'init');

//原生的PDO操作函数使用示例
// $a = LoadModel::load('mysql',array(array(
// 	'host' => '127.0.0.1',
// 	'dbname' => 'blog',
// 	'user' => 'root',
// 	'pwd' => '',
// 	'charset' => 'utf8',
// 	'type' => 'mysql',
// 	'port' => '3306'
// )),'init');
// $res=$a->db->query('select * from xb_wengzhang');
// var_dump($res->fetchALL());


//echo $a->where('id=52')->del();
//var_dump($a->firstSql);
//不连接数据库
//$b = LoadModel::load('mysql',array(),'init');
//var_dump($b);


//更新示例:
// $a->data = array(
// 	'title' => '222',
// 	'conmet' => '333'
// );
// $a->where('id=52')->save();
// var_dump($a->firstSql);//打印SQL


//新增示例:
// $a->data = array(
// 	'id' => 52,
// 	'title' => '222',
// 	'conmet' => '333'
// );
// echo $a->fileds('id,title,conmet')->insert();
// var_dump($a->firstSql); //打印SQL语句


//查询示例:
// $a->where('`id`=51')
// 	->join('left','xb_user','a=1 or b=2')
// 	->join('right','config','a=3 or b=2')
// 	->limit(1)
// 	->group('id')
// 	->order('id','desc')
// 	->having('id=51')
// 	->count();
// var_dump($a->firstSql);
// $a->where('`id`=51')
// 	//->fields('xb_wengzhang.id')
// 	->join('left','xb_user','a=1 or b=2')
// 	->join('right','config','a=3 or b=2')
// 	->group('id')
// 	->order('id','desc')
// 	->having('id=51')
// 	->first();
// var_dump($a->firstSql);
// $a->fields = 'xb_wengzhang.id';
// $a->where = 'where xb_wengzhang.id=51';
// $a->join = 'left join xb_user on a=1 or b=2';
// $a->limit = 'limit 1,2';
// $a->group = 'group by xb_wengzhang.id';
// $a->order = 'order by xb_wengzhang.id desc';
// $a->having = 'having xb_wengzhang.id = 51';
// $a->count('xb_wengzhang.id');
// var_dump($a->firstSql);
