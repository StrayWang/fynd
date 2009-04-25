<?php
interface Fynd_UI_IComponent
{
    /**
     * Gets the unique id of component
     * @return string
     */
    public function getID();
    /**
     * Sets the unique id of component
     * @param string $_id
     */
    public function setID($_id);
    /**
     * Initialize the ui component.
     *
     */
    public function initialize();
    /**
     * Render the component
     * @return string
     *
     */
    public function render();
    /**
     * Sets the inner html of the ui component
     * @param string $html
     *
     */
    public function setInnerHtml($html);
    /**
     * Gets the inner html of the ui component
     * @return string
     *
     */
    public function getInnerHtml();
    /**
     * Sets the outer html of the ui component
     * @param string $html
     *
     */
    public function setOuterHtml($html);
    /**
     * Gets the outer html of the ui component
     * @return string
     *
     */
    public function getOuterHtml();
    /**
     * Sets the class attribute of the xhtml's tag
     *
     * @param string $cssClass
     */
    public function setClass($cssClass);
    /**
     * Gets the class attribute of the xhtml's tag
     * @return string
     */
    public function getClass();
    /**
     * Sets the title attribute of the xhtml's tag
     *
     * @param string $tooltip
     */
    public function setTitle($tooltip);
    /**
     * Gets the title attribute of the xhtml's tag
     * @return string
     */
    public function getTitle();
    /**
     * Sets the style attribute of the xhtml's tag
     *
     * @param string $style
     */
    public function setStyle($style);
    /**
     * Gets the style attribute of the xhtml's tag
     * @return string
     */
    public function getStyle();
    
}
?>