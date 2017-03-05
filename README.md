#小波ApiPHP框架
*Api组件化的优势:*		
1.控制器，路由等模块都是通过Api.php来运行的	
2.可以基于配置文件进行去掉加载其中一个视图或模型		
3.可以加载任何开源程序的api接口		
4.可以根据不同的业务需求进行变更框架		
6.维护方便，可以扩展		
7.控制层只负责传递数据和渲染视图，模型负责校验数据，操作数据库等。控制器和模型传递数据只需要importModel()此函数来完成				
*配置目录*		
1. /lib/conf.php  配置默认访问路径和url访问参数			
2. /lib/core/config.xml 配置加载核心模块和自定义模块(还需在index.php配置一下)			
3. 建议配置虚拟目录建议配置到/app 这个目录下，会更安全			
4. 自行在app目录下创建error.log文件并给予0777权限,否则错误日志无法写入		
*控制器语法示例:*		
```PHP
1.$wp_users = $this->LoadModel("wp_users"); //加载wp_users模型		
2.$wp_users ->find(); //查询wp_users表里的所有数据			
```		
3.数据库查询语法请查看/lib/Model/Api.php 里面的示例	
```PHP	
4.$model = $this->importModel("WpUsers");//不带Model.php		
5.$model->test(); //调用模型文件里面方法test		
```		
*模型*				
```PHP
1.$table = self::LoadModel("wp_users"); //加载表名		
2.$table->find(); //查询语句 其余的写法跟控制器写法一致	
```			
*视图语法示例(写法跟smarty基本一致):*		
1.普通变量:<{$a}>  数组变量:<{$a.test}> //输出变量		
2.<{include file="路径加文件"}> //引入文件		
3.<{foreach item=$arr key=$k val=$v}><{$k}>---<{$v}><{/foreach}> //便利数据		
4.<{if $a == $b }>123<{else}>2323<{/if}>//判断语句 		
