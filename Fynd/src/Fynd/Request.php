<?php
require_once 'Fynd/StringUtil.php';
require_once 'Fynd/Object.php';
class Fynd_Request extends Fynd_Object
{
    /**
     * HTTP GET variables.
     *
     * @var Fynd_Dictionary
     */
    protected $_get;
    /**
     * HTTP POST variables.
     *
     * @var Fynd_Dictionary
     */
    protected $_post;
    /**
     * HTTP parameters.
     *
     * @var Fynd_Dictionary
     */
    protected $_params;
    
    public function __construct(Array $get = array(),Array $post = array())
    {
        $this->_get = new Fynd_Dictionary($get);
        $this->_post = new Fynd_Dictionary($post);
        $this->_params = new Fynd_Dictionary(array_merge($get,$post));
    }
	public function getControllerName()
	{
		$ctrlName = trim($this->_params['c']);
		$ctrlName = Fynd_StringUtil::capitalize($ctrlName);
		if(empty($ctrlName))
		{
			$ctrlName = 'Index';
		}	
		$ctrlName .= 'Ctrl';
		return $ctrlName;
	}
	public function getActionName()
	{
		$action = trim($this->_params['a']);
		
		if(empty($action))
		{
			$action = 'Index';
		}
		$action .= "Act";
		return $action;
	}
	public function getHttpGetVar($key)
	{
	    return $this->_get[$key];
	}
	public function getHttpPostVar($key)
	{
	    return $this->_post[$key];
	}
	public function getHttpParameter($key)
	{
	    return $this->_params[$key];
	}
	
}
?>