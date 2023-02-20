<?php
@header("Content-type: text/html; charset=utf-8");
class download{
	protected $_filename;
	protected $_filepath;
	protected $_filesize;
	public function __construct($filename){
		$this->_filename=$filename;
		$this->_filepath=dirname(__FILE__).'/temp/'.$filename;
	}
	public function __destruct(){
		if (file_exists($this->_filepath)){
			unlink($this->_filepath);
		}
	}
	public function getfilename(){
		return $this->_filename;
	}
	public function getfilepath(){
		return $this->_filepath;
	}
	public function getfilesize(){
		return $this->_filesize=number_format(filesize($this->_filepath)/(1024*1024),2);
	}
	public function getfiles(){
		if(file_exists($this->_filepath)){
			$file = fopen($this->_filepath,"r");
			Header("Content-type: application/octet-stream");
			Header("Accept-Ranges: bytes");
			Header("Accept-Length: ".filesize($this->_filepath));
			Header("Content-Disposition: attachment; filename=".$this->_filename);
			echo fread($file, filesize($this->_filepath));
			$buffer=1024;
			while(!feof($file)){
				$file_data=fread($file,$buffer);
				echo $file_data;
			}
			fclose($file);
		}else {
			echo "<script>alert('对不起,下载失败！请联系李锦成！');</script>";
		}
	}
}
?>
