<?php
/**
 * summary: 自定义request组件
 * author: justin
 * date: 2014-4-30
 */
class ZHttpRequest extends CHttpRequest
{
	/**
	 * 将用户输入的\r\n转为\n
	 */
	public function getParam($name,$defaultValue=null)
	{
		$str = isset($_GET[$name]) ? $_GET[$name] : (isset($_POST[$name]) ? $_POST[$name] : $defaultValue);
		if ($str){
			$str = str_replace("\r\n", "\n", $str);
			$str = str_replace("\t", "  ", $str);
		}		
		return $str;
	}
}