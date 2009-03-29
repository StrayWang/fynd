<?php
interface Fynd_IView
{
    /**
     * Sets the data be used by the view object 
     *
     * @param mixed $data
     */
    public function setData($data);
    /**
     * Render the response data.
     *
     */
	public function render();
}
?>