<?php
interface Fynd_UI_IContainer
{
    /**
     * Adds a ui component to container.
     *
     * @param Fynd_UI_IComponent $cmp
     */
    public function addComponent(Fynd_UI_IComponent $cmp);
    /**
     * @param string $id
     * @return Fynd_UI_IComponent
     */
    public function findComponent($id);
    /**
     * @return Fynd_Dictionary
     */
    public function getComponents();
    /**
     * @return Fynd_View_Html
     */
    public function getHtmlView();
}
?>