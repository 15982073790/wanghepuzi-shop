<?php
namespace MrStock\Business\ServiceSdk\ALiDaYu\Top;

class TopLogger
{
	public $conf = array(
		"separator" => "\t",
		"log_file" => ""
	);

	private $fileHandle;

	protected function getFileHandle()
	{
		if (null === $this->fileHandle)
		{
			if (empty($this->conf["log_file"]))
			{
				trigger_error("no log file spcified.");
			}
			$logDir = dirname($this->conf["log_file"]);
			if (!is_dir($logDir))
			{
				mkdir($logDir, 0777, true);
			}
			$this->fileHandle = fopen($this->conf["log_file"], "a");
		}
		return $this->fileHandle;
	}

	public function log($logData)
	{
		/*
		if ("" == $logData || array() == $logData)
		{
			return false;
		}
		if (is_array($logData))
		{
			$logData = implode($this->conf["separator"], $logData);
		}
		$logData = $logData. "\n";
		fwrite($this->getFileHandle(), $logData);
		*/
		$level='alisms';
		$log_file = APP_DATA_PATH.'/log/'.date('Ymd',time())."_".$level.".log";
		$url = $_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'];
		$url .= " ( c={$_REQUEST['c']}&a={$_REQUEST['a']} ) ";
		
		$message = print_r($logData,true);
		$now = time();
		$content = "[{$now}] {$url}\r\n{$level}: {$message}\r\n";
		file_put_contents($log_file,$content, FILE_APPEND);
	}
}
?>