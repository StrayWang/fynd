<?php
require_once 'Fynd/IView.php';
require_once 'Fynd/Object.php';
abstract class Fynd_View extends Fynd_Object implements Fynd_IView 
{
    public $useTemplateEngine = false;
    
    protected $_data;
    
    protected $_headers = array();
    /**
     * @var Fynd_Dictionary
     */
    private $_resources;

    public function __construct()
    {
        $this->_resources = new Fynd_Dictionary();
    }
    
    public function addResource($key,$path)
    {
        if(!$this->_resources->containsKey($key))
        {
            $this->_resources->add($key,$path);
        }
    }
    /**
     * Gets the resource files's path.
     *
     * @return Fynd_Dictionary
     */
    public function getResources()
    {
        return $this->_resources;
    }
    
    
    public function setMimeType($mime,$overwrite = true)
    {
        $this->setHttpHeader('Content-Type',$mime,$overwrite);
    }
    public function setHttpHeader($key,$value,$overwrite = true)
    {
        //TODO:create a response object to decribe the http response.
        if(!empty($key) && !empty($value))
        { 
            header($key . ':' . $value,$overwrite);
        }
    }
    /**
     * @see Fynd_IView::setData()
     *
     */
    public function setData($data)
    {
        //TODO:create a response object to decribe the http response.
        $this->_data = $data;
    }

}
?>