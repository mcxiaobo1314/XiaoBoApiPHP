<?php
/**
 * 自定义session类
 * @author wave
 */

class FileSessionHandler {

	/**
	 *  session的前缀
	 *  @author wave
	 */
	public $suffix = SESSION_SUFFIX;

	/**
	 * session id
	 * @author wave
	 */
	public $sessId = '';

	/**
	 * 保存session文件路径
	 * @author wave
	 */
	public $cache = 'session';

	/**
	 * 默认文件保存路径
	 * @author wave
	 */
	public $sessionPath ='';

	/**
	 * 加密变量
	 * @author wave
	 */
	protected $encrypt = "base64_encode";


	/**
	 * 解密变量
	 * @author wave
	 */
	protected $decrypt = "base64_decode";

	/**
	 * session生存时间
	 * @author wave
	 */
	protected $litetime = SESSION_LIFETIME;



	/**
	 * 获取路由对象
	 * @author wave
	 */
	private $Route = '';



	/**
	 * 构造函数
	 * @author wave
	 */
	public function __construct(){
		$this->Route = Container::get('Route');
		$this->sessionPath = SESSION_PATH != '' ? SESSION_PATH : 
				$this->Route->controllerPath.ROUTE_DS.'..'.ROUTE_DS.STORAGE.ROUTE_DS.
				$this->cache;

	}

	/**
	 * 获取session id
	 * @return string
	 * @author wave
	 */
	public function getId(){
		return $this->sessId;
	}

	/**
	 * 写入session
	 * @param string $id session key
	 * @param string $data 数据
	 * @return bool
	 * @author wave
	 */
	public function write($id = '',$data = ''){
		$dataAll = array();
		if($data !=''  && $id != ''){
			$dataAll = $this->read();
			$sessionPath = $this->sessionPath.ROUTE_DS.$this->sessId;
			$dataAll[$id] = $data;
			if(function_exists($this->encrypt)){
				$str = call_user_func($this->encrypt, json_encode($dataAll));
			}else {
				$str = json_encode($dataAll);
			}
			return file_put_contents($sessionPath, $str);
		}
		return '';		
	}

	/**
	 * 读取session
	 * @param string $id session key
	 * @return bool
	 * @author wave
	 */
	public function read($id = '') {
		$sessionPath = $this->sessionPath.ROUTE_DS.$this->sessId;
		if(file_exists($sessionPath) && is_file($sessionPath)){
			$data = file_get_contents($sessionPath);
			if(function_exists($this->decrypt)){
				$data = isset($data) ? call_user_func($this->decrypt,$data) : array();
			}
			$data = json_decode($data,true);
			$data = isset($data[$id]) ? $data[$id] : $data;
			return $data;
		}
	}

	/**
	 * 打开session
	 * @author wave
	 */
	public function open($savePath,$sessionName){
		//$this->isPath();
		return true;
	}

	/**
	 * 关闭
	 * @author wave
	 */
	public function close(){
		return true;
	}

	/**
	 * 自动毁销机制
	 * @author wave
	 */
	public function gc(){
		$fileArr = glob($this->sessionPath.ROUTE_DS.$this->suffix.'*');
		foreach ($fileArr as $key => $value) {
			if(filemtime($value) + $this->litetime < Server::get('REQUEST_TIME')){
				@unlink($value);
			}
		}
		return true;
	}

	
	/**
	 * 删除session id
	 * @param string $id
	 * @return bool
	 * @author wave
	 */
	public function remove($id){
		if(isset($id)){
			$dataAll = $this->read();
			if(isset($dataAll[$id])){
				unset($dataAll[$id]);
			}
			if(empty($dataAll)){
				return $this->destroy();
			}
			if(function_exists($this->encrypt)){
				$str = call_user_func($this->encrypt, json_encode($dataAll));
			}else {
				$str = json_encode($dataAll);
			}
			$sessionPath = $this->sessionPath.ROUTE_DS.$this->sessId;
			return file_put_contents($sessionPath, $str);
		}
		
	}

	/**
	 * 删除session
	 * @param string $id session key
	 * @return bool
	 * @author wave
	 */
	public function destroy(){
		$sessionPath = $this->sessionPath.ROUTE_DS.$this->sessId;
		if(file_exists($sessionPath)){
			@chmod($sessionPath,0777);
			return @unlink($sessionPath);
		}
	}

	/**
	 * 判断session文件路径是否存在
	 * @author wave
	 */
	public function isPath(){
		$session = $this->sessionPath.ROUTE_DS;	
		if(!file_exists($session)){
			@mkdir($session,0777,true);
			@chmod($session,0777);
		}
		$session .=  $this->sessId;
		if(!file_exists($session)){ 
			return file_put_contents($session,'');
		}
	}

}

?>