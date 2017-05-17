<?php
/**
 * 生成缩略图 
 * @author wave
 */


class thumb {

	/**
	 * 生成缩略图的图片路径
	 * @author wave
	 */
	public $dstImage = '';

	/**
	 * 保存缩略图的图片路径
	 * @author wave
	 */
	public $srcImage = '';

	/**
	 * 生成缩略图的图片x坐标
	 * @author wave
	 */
	public $dstX = 0;

	/**
	 * 生成缩略图的图片y坐标
	 * @author wave
	 */
	public $dstY = 0;

	/**
	 * 生成缩略图的图片宽度
	 * @author wave
	 */
	public $dstW = 0;

	/**
	 * 生成缩略图的图片高度
	 * @author wave
	 */
	public $dstH = 0;

	/**
	 * 保存缩略图的图片x坐标
	 * @author wave
	 */
	public $srcX = 0;


	/**
	 * 保存缩略图的图片y坐标
	 * @author wave
	 */
	public $srcY = 0;


	/**
	 * 保存缩略图的图片宽度
	 * @author wave
	 */
	public $srcW = 50;

	/**
	 * 保存缩略图的图片高度
	 * @author wave
	 */
	public $srcH = 50;

	/**
	 * 图片类型值
	 * @author wave
	 */
	protected $typeInt = 0;	

	/**
	 * 图片类型的名字
	 * @author wave
	 */
	protected $typeName = '';

	/**
	 * 图片类型
	 * @author wave
	 */
	protected $typeArr = array(
		1=> 'gif',
		2=> 'jpeg',
		3=> 'png'
	);

	/**
	 * 错误消息
	 * @author wave
	 */
	protected $message = '';

	/**
	 * 生成缩略图
	 * @author wave
	 */
	public function build(){
		$this->checkImg();
		$this->getSize();
		$this->checkImgType();
		$blackImg = $this->createBlackImg();
		$this->copyImg($blackImg);
		if($this->outImg($blackImg)){
			return true;
		}else {
			$this->message = '生成失败';
			return false;
		}
	}

	/**
	 * 复制图片
	 * @author wave
	 */
	protected function copyImg($blackImg){
 		return imagecopyresampled(
 				$blackImg, 
 				$this->loadImg(), 
 				$this->dstX,
 				$this->dstY, 
 				$this->srcX,
				$this->srcY, 
				$this->srcW,
				$this->srcH, 
				$this->dstW,
				$this->dstH
			);
	}


	/**
	 * 创建黑色的背景图片
	 * @author wave
	 */
	protected function createBlackImg(){
		return imagecreatetruecolor($this->srcW,$this->srcH);
	}

	/**
	 * 加载图片资源
	 * @author wave
	 */
	protected function loadImg(){
		switch ($this->typeName) {
			case 'gif':
				$infunc = "imagecreatefromgif";
				break;
			case 'jpeg':
				$infunc = "imagecreatefromjpeg";
				break;			
			case 'png':
				$infunc = "imagecreatefrompng";
				break;				
		}
		if(function_exists($infunc)){
			return $infunc($this->dstImage);
		}
	}

	/**
	 * 输出图片资源
	 * @author wave
	 */
	protected function outImg($blackImg){
		switch ($this->typeName) {
			case 'gif':
				$outfunc = "imagegif";
				break;
			case 'jpeg':
				$outfunc = "imagejpeg";
				break;			
			case 'png':
				$outfunc = "imagepng";
				break;				
		}
		if(function_exists($outfunc)){
			return $outfunc($blackImg,$this->srcImage);
		}
	}


	/** 
	 * 检查图片类型
	 * @author wave
	 */
	protected function checkImgType(){
		if(!$this->typeArr[$this->typeInt]){
			$this->message = '生成缩略图的图片类型不存在';
			return false;
		}
		$this->typeName = $this->typeArr[$this->typeInt];
	}

	/** 
	 * 检查图片路径是否存在
	 * @author wave
	 */
	protected function checkImg(){
		if($this->dstImage === '' || file_exists($this->dstImage)){
			$this->message = '生成缩略图的图片不存在';
			return false;
		}
	}

	/**
	 * 获取图片大小
	 * @author wave 
	 */
	protected function getSize(){
		list($this->dstW,$this->dstH,$this->typeInt) = getimagesize($this->dstImage);
	}

	/**
	 * 设置缩略图的比例
	 * @author wave
	 */
	protected function setRatio(){
		if($this->dstW/$this->srcW > $this->dstH/$this->srcH){
			$this->srcW = $this->srcW  * ($this->dstH/$this->srcH);
		}else{
			$this->srcH = $this->srcH * ($this->dstW/$this->srcW);
		}
	}


}