<?php
require_once ('Fynd/UI/Component.php');
require_once 'Fynd/UI/Container.php';
class Fynd_UI_Import extends Fynd_UI_Container
{
    protected $_path;
    /**
     * @var Fynd_UI_Fatcory
     */
    protected $_cmpFactory;
    
    protected $_preParsedHtml;
    
    function __construct(Fynd_View_Html $view, $path = '')
    {
        parent::__construct($view);
        $this->_path = $path;
        $this->_cmpFactory = $view->getComponentFactory();
    }
    /**
     * @return string
     */
    public function getPath()
    {
        return $this->_path;
    }
    /**
     * @param string $_path
     */
    public function setPath($_path)
    {
        $this->_path = str_replace('~/', Fynd_Env::getAppPath(), $_path);
    }
    /**
     * @see Fynd_UI_Component::initialize()
     *
     */
    public function initialize()
    {
        if(empty($this->_path) || !file_exists($this->_path))
        {
            Fynd_Object::throwException('Fynd_UI_Exception','The import file does not exsitent.');
        }
        $this->_preParsedHtml = $this->_cmpFactory->preParse(file_get_contents($this->_path),$this);
    }
    /**
     * @see Fynd_UI_Component::render()
     *
     * @return string
     */
    public function render()
    {
        return $this->_cmpFactory->parse($this->_preParsedHtml,$this);
    }

}
?>