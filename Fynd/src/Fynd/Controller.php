<?php
require_once 'Fynd/Object.php';
/**
 * Controller base class in MVC
 *
 */
class Fynd_Controller extends Fynd_Object
{
    public function __construct()
    {}
    /**
     * Redirect to anothor controller
     *
     * @param string $ctrl
     * @param string $act
     */
    protected function _redirect($ctrl, $act)
    {
        $act = empty($act) ? 'index' : $act;
        $header = "location:index.php?c=$ctrl&a=$act";
        header($header);
    }
    /**
     * Choose a view
     *
     * @param string $view
     * @param mixed $data
     */
    protected function _selectView($view, $data = null, $mime = null)
    {
        ob_start();
        $objType = new Fynd_Type($view);
        $view = $objType->createInstance();
        $view->setData($data);
        $responseData = $view->render();
        if(! empty($mime))
        {
            $view->setMimeType($mime);
        }
        if(! empty($responseData))
        {
            echo $responseData;
        }
        ob_end_flush();
    }
    public function setView(Fynd_IView $view)
    {
        ob_start();
        $view->render();
        ob_end_flush();
    }
    /**
     * Default method will be called when request method does not exsisted 
     *
     * @param string $act
     * @param array $param
     */
    public function __call($act, $param)
    {
        echo "$act has nerver been defined";
        var_dump($param);
    }
    const JSON_VIEW = 1;
    const HTML_VIEW = 2;
    const XML_VIEW = 3;
}
?>