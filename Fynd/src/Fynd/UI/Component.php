<?php
require_once ('Fynd/Object.php');
require_once 'Fynd/UI/IComponent.php';
abstract class Fynd_UI_Component extends Fynd_Object implements Fynd_UI_IComponent
{
    protected $_id;
    /**
     * The view object which this UI component belong to. 
     *
     * @var Fynd_View_Html
     */
    protected $_view;
    private $_outerHtml = '';
    private $_innerHtml = '';
    private $_cssClass = '';
    private $_title='';
    private $_cssStyle ='';
    public function __construct(Fynd_View_Html $view)
    {
        $this->_view = $view;
    }
    /**
     * @return string
     */
    public function getID()
    {
        return $this->_id;
    }
    /**
     * @see Fynd_UI_IComponent::getClass()
     *
     * @return string
     */
    public function getClass()
    {
        return $this->_cssClass;
    }
    /**
     * @see Fynd_UI_IComponent::getStyle()
     *
     * @return string
     */
    public function getStyle()
    {
        return $this->_cssStyle;
    }
    /**
     * @see Fynd_UI_IComponent::getTitle()
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }
    /**
     * @see Fynd_UI_IComponent::setClass()
     *
     * @param string $cssClass
     */
    public function setClass($cssClass)
    {
        $this->_cssClass = $cssClass;
    }
    /**
     * @see Fynd_UI_IComponent::setStyle()
     *
     * @param string $style
     */
    public function setStyle($style)
    {
        $this->_cssStyle = $style;
    }
    /**
     * @see Fynd_UI_IComponent::setTitle()
     *
     * @param string $tooltip
     */
    public function setTitle($tooltip)
    {
        $this->_title = $tooltip;
    }

    /**
     * @param string $_id
     */
    public function setID($_id)
    {
        $this->_id = $_id;
    }
    /**
     * @see Fynd_UI_IComponent::getOuterHtml()
     *
     * @return string
     */
    public function getOuterHtml()
    {
        return $this->_outerHtml;
    }
    /**
     * @see Fynd_UI_IComponent::setOuterHtml()
     *
     * @param string $html
     */
    public function setOuterHtml($html)
    {
        $this->_outerHtml = $html;
    }
    /**
     * @see Fynd_UI_IComponent::getInnerHtml()
     *
     * @return string
     */
    public function getInnerHtml()
    {
        return $this->_innerHtml;
    }
    /**
     * @see Fynd_UI_IComponent::setInnerHtml()
     *
     * @param string $html
     */
    public function setInnerHtml($html)
    {
        $this->_innerHtml = $html;
    }
    /**
     * @return Fynd_View_Html
     */
    public function getView()
    {
        return $this->_view;
    }
    /**
     * @param Fynd_View_Html $_view
     */
    public function setView($_view)
    {
        $this->_view = $_view;
    }

}
?>