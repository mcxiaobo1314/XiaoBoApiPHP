#小波ApiPHP框架
*公告*		
1.优化了路由器解析 			
2.伪静态访问，方法里面行参不能用动态参数获取，动态参数只能用$_GET等来获取～		
3.动态和伪静态访问是自动识别，无需修改配置文件			
4.加强了系统自带模版引擎 可以解析多维数组变量，详情看模版引擎数组变量的写法			
5.新增了获取访问的目录分组，控制器名，方法名，跟路径等方法		
6.已经支持在php7运行该代码～		
*Api组件化的优势:*		
1.控制器，路由等模块都是通过Api.php来运行的	
2.可以基于配置文件进行去掉加载其中一个视图或模型		
3.可以加载任何开源程序的api接口		
4.可以根据不同的业务需求进行变更框架		
6.维护方便，可以扩展		
7.控制层只负责传递数据和渲染视图，模型负责校验数据，操作数据库等。控制器和模型传递数据只需要importModel()此函数来完成				
8.只支持pdo操作数据库			
9.url参数太长了，怎么办？赶紧定义路由别名，又可以传入参数也可以缩短url		
路由别名路径:/lib/conf/Route.php
```PHP
RouteApi::aliasRoute('/index.html','home','test','aaa',array(3,5));
```		
路由原伪静态访问http://localhost/XiaoBoApiPHP/index.php/home/test/aaa		
设置路由别名访问http://localhost/XiaoBoApiPHP/index.php/index.html		
*隐藏index.php伪静态访问设置:*		
1.首先到httpd.conf 找到mod_rewrite.so 把前面的#去掉 		
2.找到AllowOverride 把None改成All			
3.重启Apache 就可以访问:http://localhost/XiaoBoApiPHP/home/test/aaa 访问		
*配置目录*		
1. /lib/conf.php  配置默认访问路径和url访问参数			
2. /lib/core/config.xml 配置加载核心模块和自定义模块(还需在index.php配置一下)			
3. 建议配置虚拟目录建议配置到/app 这个目录下，会更安全			
4. 自行在app目录下创建error.log文件并给予0777权限,否则错误日志无法写入			
5. 目前只能对数据库的curd简单的封装，如果需要更详解，请自己行封装进行放到/lib/model/目录下			
*数据校验:*		
```PHP
$user = $this->LoadModel('users');
$user->validate = array(
		 	array(
		 		'name'=>'id',  //字段名称
		 		'reg' => '/^\d+$/',  //正则表达式
		 		'error' => '请输入数字' //错误提示
		 	),
		 	array(
		 		'name'=>'name', 
		 		'reg' => '/^\d+$/',
		 		'error' => '请输入数字'
		 	)

		 );
		 //获取的数据
		 $user->data = array(
		 	'id' =>'aaaa',   
		 	'name' =>'dddd21111'

		 );
		 //对数据进行校验
		if(!$user->validate()){
			//输出错误提示
		 	var_dump($user->validateErr);
		}
```			
*控制器语法示例:*		
```PHP
$this->getUrl();  //获取当前访问url参数		
$this->getGroup()；//获取当前访问分组		
$this->getClass(); //获取当前访问类名		
$this->getAction(): //获取当前访问的方法名		
$this->getPath(); //获取网站的跟目录		
$model = $this->LoadModel(); //PDO原生态操作	
$wpusers = $model->db()->query('select * from wp_users'); //执行原生sql语句写法		
$wpusers->execute();		
$wp_users = $this->LoadModel("wp_users"); //加载wp_users模型		
$wp_users ->find(); //查询wp_users表里的所有数据		
$wp_users->where('`id`=51')			
 	->join('left','xb_user','a=1 or b=2')		
 	->join('right','config','a=3 or b=2')		
 	->limit(1)		
 	->group('id')		
 	->order('id','desc')		
 	->having('id=51')		
 	->count();//查询行数		
 var_dump($a->firstSql); //打印当前sql语句		
 $wp_users->where('`id`=51')		
 	->fields('id')		
 	->join('left','xb_user','a=1 or b=2')		
 	->join('right','config','a=3 or b=2')		
 	->group('id')		
 	->order('id','desc')		
 	->having('id=51')		
 	->first();//查询单行数据			
```		
->mysql数据库语法请查看/lib/Model/Api.php 里面的示例		
*加载模型语法:*			
```PHP	
$model = $this->importModel("WpUsers");//不带Model.php 第二参数初始化参数是数组，第三个参数默认初始化方法名				
$model->test(); //调用模型文件里面方法test		
```		
*模型语法:*				
```PHP
$table = self::LoadModel("wp_users"); //加载表名		
$table->find(); //查询语句 其余的写法跟控制器写法一致	
```			
*带视图系统自带模版引擎语法示例:*		
1.普通变量:<{$a}>  数组变量:<{$a.test}> //输出变量		
2.<{include file="路径加文件"}> //引入文件		
3.<{foreach item=$arr key=$k val=$v}><{$k}>---<{$v}><{/foreach}> //便利数据		
4.<{if $a == $b }>123<{else}>2323<{/if}>//判断语句 		
