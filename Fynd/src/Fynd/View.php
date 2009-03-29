<?php
require_once 'Fynd/IView.php';
abstract class Fynd_View extends Fynd_Object implements Fynd_IView 
{
    public $useTemplateEngine = false;
    
    protected $_data;
    
    protected $_headers = array();

    public function setMimeType($mime)
    {
        $this->setHttpHeader('Content-Type',$mime);
    }
    public function setHttpHeader($key,$value)
    {
        //TODO:create a response object to decribe the http response.
        if(!empty($key) && !empty($value))
        { 
            header($key . ':' . $value,true);
        }
    }
    /**
     * @see Fynd_IView::Render()
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