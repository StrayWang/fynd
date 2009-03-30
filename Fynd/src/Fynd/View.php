<?php
require_once 'Fynd/IView.php';
abstract class Fynd_View extends Fynd_Object implements Fynd_IView 
{
    public $useTemplateEngine = false;
    
    protected $_data;
    
    protected $_headers = array();

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
     * @see Fynd_IView::render()
     *
     */
    public function render()
    {
        echo $this->_data;
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