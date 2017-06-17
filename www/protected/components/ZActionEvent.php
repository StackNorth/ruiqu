<?php
/**
 * summary: action event
 * author: justin
 * date: 2014.04.01
 */
class ZActionEvent extends CEvent
{
	public $success = true;
	public $message = '';
	public $data = array();
}
