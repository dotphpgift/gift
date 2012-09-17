<?php defined('ACCESS') or die("No direct script access allowed");

class DLogger extends CLogger
{
	private $_logs=array();
	/**
	 * @var integer number of log messages
	 */
	private $_logCount=0;
	/**
	 * @var array log levels for filtering (used when filtering)
	 */
	private $_levels;
	/**
	 * @var array log categories for filtering (used when filtering)
	 */
	private $_categories;
	/**
	 * @var array the profiling results (category, token => time in seconds)
	 */
	private $_timings;
	/**
	* @var boolean if we are processing the log or still accepting new log messages
	* @since 1.1.9
	*/
	private $_processing = false;
	
	public $realtimelogging = false;
	
	public function count()
	{
		return $this->_logCount;
	}
	
	
	
}