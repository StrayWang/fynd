<?php
require_once ('Fynd/UI/IContainer.php');
require_once ('Fynd/UI/Component.php');
abstract class Fynd_UI_Container extends Fynd_UI_Component implements Fynd_UI_IContainer
{
    /**
     * @var Fynd_Dictionary
     */
    private $_components;
    /**
     * @var Fynd_UI_Factory
     */
    protected $_cmpFactory = null;
    protected $_preParsedHtml = '';
    public function __construct(Fynd_View_Html $view)
    {
        parent::__construct($view);
        $this->_components = new Fynd_Dictionary();
        $this->_cmpFactory = $view->getComponentFactory();
    }
    public function addComponent(Fynd_UI_IComponent $cmp)
    {
        $id = $cmp->getID();
        if($this->_components->containsKey($id))
        {
            Fynd_Object::throwException('Fynd_UI_Exception', 'The id of UI component has exsitent.');
        }
        $this->_components->add($id, $cmp);
    }
    /**
     * @see Fynd_UI_IComponent::initialize()
     *
     */
    public function initialize()
    {
        $this->_preParsedHtml = $this->_cmpFactory->preParse($this->getInnerHtml(), $this);
    }

    /**
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
        return $this->_view;
    }
}
?>