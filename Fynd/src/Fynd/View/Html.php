<?php
require_once ('Fynd/View.php');
require_once 'Fynd/UI/IContainer.php';
class Fynd_View_Html extends Fynd_View implements Fynd_UI_IContainer
{
    protected $_htmlFile = '';
    
    private $_preParsedHtml = '';
    
    private $_parsedHtml = '';
    /**
     * @var Fynd_UI_Head
     */
    private $_headCmp;
    /**
     * @var Fynd_Dictionary
     */
    private $_components;
    /**
     * @var Fynd_UI_Factory
     */
    protected $_componentFactory;
    
    
    function __construct($htmlFile = '')
    {
        parent::__construct();
        if(empty($htmlFile))
        {
            $htmlFile = __CLASS__ . ".html";
        }
        $this->_htmlFile = $htmlFile;
        $this->_componentFactory = new Fynd_UI_Factory();
        $this->_components = new Fynd_Dictionary();

        $htmlString = file_get_contents($this->_htmlFile);
        $this->_preParsedHtml = $this->_componentFactory->preParse($htmlString,$this);
    }
    /**
     * @return Fynd_UI_Factory
     */
    public function getComponentFactory()
    {
        return $this->_componentFactory;
    }
    /**
     * @return Fynd_UI_Head
     */
    public function getHeadComponent()
    {
        return $this->_headCmp;
    }
    /**
     * @param Fynd_UI_Head $_headCmp
     */
    public function setHeadComponent(Fynd_UI_Head $_headCmp)
    {
        $this->_headCmp = $_headCmp;
    }

    public function setPreHtml($preParsedHtml)
    {
        $this->_parsedHtml = $preParsedHtml;
    }
    /**
     * @return string
     */
    public function getHtmlFile()
    {
        return $this->_htmlFile;
    }
    /**
     * @param string $_htmlFile
     */
    public function setHtmlFile($_htmlFile)
    {
        $this->_htmlFile = $_htmlFile;
    }
    public function addComponent(Fynd_UI_IComponent $cmp)
    {
        $id = $cmp->getID();
        if($this->_components->containsKey($id))
        {
            Fynd_Object::throwException('Fynd_UI_Exception','The id of UI component has exsitent.');    
        }
        $this->_components->add($id,$cmp);
    }
    /**
     * @param string $id
     * @return Fynd_UI_IComponent
     */
    public function findComponent($id)
    {
        return $this->_components[$id];
    }
    /**
     * @return Fynd_Dictionary
     */
    public function getComponents()
    {
        return $this->_components;    
    }
    /**
     * @see Fynd_UI_IContainer::getHtmlView()
     *
     * @return Fynd_View_Html
     */
    public function getHtmlView()
    {
        return $this;
    }

    /**
     * @see Fynd_View::render()
     *
     */
    public function render()
    {
        //TODO:read from cache first.
        echo $this->_componentFactory->parse($this->_preParsedHtml,$this);
        //TODO:add complied view string to cache.
    }
}
?>