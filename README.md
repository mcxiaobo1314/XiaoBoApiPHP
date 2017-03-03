#小波ApiPHP框架
*Api组件化的优势:*		
1.控制器，路由等模块都能独立运行		
2.可以基于配置文件进行去掉加载其中一个视图或模型		
3.可以加载任何开源程序的api接口		
4.可以根据不同的业务需求进行变更框架		
6.维护方便，可以扩展		
*配置目录*		
1. /lib/conf.php  配置默认访问路径和url访问参数			
2. /lib/core/config.xml 配置加载核心模块和扩展模块			
3. 建议配置虚拟目录建议配置到/app 这个目录下，会更安全			
*控制器语法示例:*		
1.$wp_users = $this->LoadModel("wp_users"); //加载wp_users模型		
2.$wp_users ->find(); //查询wp_users表里的所有数据			
3.数据库查询语法请查看/lib/Model/Api.php 里面的示例		
4.$model = $this->importModel("模型文件名");//不带Model.php		
5.$model->test(); //调用模型文件里面方法test		
*模型*				
1.$table = self::LoadModel("数据库表名"); //加载表名		
2.$table->find(); //查询语句 其余的写法跟控制器写法一致		
*视图语法示例(写法跟smarty基本一致):*		
1.<{$a}> //输出变量		
2.<{include file="路径加文件"}> //引入文件		
3.<{foreach item=$arr key=$k val=$v}><{$k}>---<{$v}><{/foreach}> //便利数据		
4.<{if $a == $b }>123<{else}>2323<{/if}>//判断语句 		
