<?php
/**
 * 组件接口
 * @author wave
 */

define('MODEL_TOKEN','xiaobo_php_model');

class ModelApi {

	/**
	 * 数据库连接
	 * @author wave
	 */
	static $Config;

	/**
	 * 初始化函数
	 *@author wave
	 */
	static function init($type = 'Mysql') {
		load(dirname(__FILE__).'/LoadModel.php');
		$type = ucfirst($type);
		LoadModel::import('/'.$type.'.php',dirname(__FILE__));
	}



	/**
	 * 加载模型
	 * @param String $tableName 表名
	 * @param Array  $conntion 连接数据库
	 * @return OBject
	 * @author wave
	 */
	static function LoadModel($tableName = '',$conntion = array()) {
		self::$Config = self::config();
		$arr = array();
		if(!empty($tableName)){
			$arr = array($tableName);
		}
		if(!empty($conntion)) {
			array_push($arr, $conntion);
		}elseif(self::$Config->dbname) {
			array_push($arr,array(
				'host' => self::$Config->host,
				'dbname' => self::$Config->dbname,
				'user' => self::$Config->username,
				'pwd' => self::$Config->password,
				'charset' => self::$Config->charset,
				'type' => self::$Config->type,
				'port' => self::$Config->port,
				'tablePrefix' => self::$Config->tablePrefix
			));
		}
		return LoadModel::load(self::$Config->type,$arr,'init',$tableName);
	}

	/**
	 * 获取模型路径
	 * @return boolen or String
	 * @author wave
	 */
	static function getModelPath() {
		$defaultPath = isset($_GET[G]) ? $_GET[G] : DEFAULT_PATH;
		$path = self::getPath().APP_ROOT_PATH.ROUTE_DS.$defaultPath.ROUTE_DS.MODEL.ROUTE_DS;
		return $path;
	}



	/**
	 * 获取服务器相对路径目录
	 * @return String 
	 * @author wave
	 */
	static function getPath() {
		$appPath = dirname(dirname(__FILE__));
		$currPath = basename($appPath);
		$searchArr = array('\\',$currPath);
		$replaceArr = array(ROUTE_DS,'');
		$appPath = str_replace($searchArr, $replaceArr, $appPath);
		return $appPath;
	}


	/**
	 * 加载数据库配置
	 * @return OBject
	 * @author wave
	 */
	static function config(){
		Container::instace('config');
		return Container::$app['config'];
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
// $res=$a->db()->query('select * from xb_wengzhang');
// $res->execute();
// $res->setFetchMode(PDO::FETCH_ASSOC);
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
// $a->where(array('id'=>1))
// 	->andWhere(array('xxx like'=> '%xxx%'))
//  ->orWhere(array('name >='=>"xxx"));
// 	->fields('xb_wengzhang.id')
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
