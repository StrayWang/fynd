<?php
require_once ('Fynd/UI/Component.php');
class Fynd_UI_Head extends Fynd_UI_Component
{
    /**
     * @var Fynd_IList
     */
    protected $_links;
    /**
     * @var Fynd_Dictionary
     */
    protected $_resources;
    /**
     * @var string
     */
    protected $_title;
    /**
     * @var string
     */
    protected $_charset = 'UTF-8';
    protected $_linkedCsses;
    protected $_linkedScripts;
    function __construct(Fynd_View $view)
    {
        parent::__construct($view);
        $this->_resources = $view->getResources();
        $this->initialize();
    }
    /**
     * @return Fynd_IList
     */
    public function getLinks()
    {
        return $this->_links;
    }
    /**
     * @param Fynd_IList $_links
     */
    public function setLinks(Fynd_IList $_links)
    {
        $this->_links = $_links;
    }

    /**
     * @return string
     */
    public function getCharset()
    {
        return $this->_charset;
    }
    /**
     * @param string $_charset
     */
    public function setCharset($_charset)
    {
        if(! empty($_charset))
        {
            $this->_charset = $_charset;
        }
    }
    /**
     * @return Fynd_Dictionary
     */
    public function getCsses()
    {
        return $this->_csses;
    }
    /**
     * @param Fynd_Dictionary $_csses
     */
    public function setCsses(Fynd_Dictionary $_csses)
    {
        $this->_csses = $_csses;
    }
    /**
     * @return Fynd_Dictionary
     */
    public function getScripts()
    {
        return $this->_scripts;
    }
    /**
     * @param Fynd_Dictionary $_scripts
     */
    public function setScripts(Fynd_Dictionary $_scripts)
    {
        $this->_scripts = $_scripts;
    }
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }
    /**
     * @param string $_title
     */
    public function setTitle($_title)
    {
        $this->_title = $_title;
    }
    /**
     * @see Fynd_UI_Component::initialize()
     *
     */
    public function initialize()
    {}
    /**
     * @see Fynd_UI_Component::render()
     *
     * @return string
     */
    public function render()
    {
        foreach ($this->_links as $link)
        {
            $this->_linkedCsses .= $link->render();
        }
        foreach($this->_resources as $res)
        {
            $res = strtolower($res);
            if(Fynd_StringUtil::endWith($res, 'css'))
            {
                $this->_linkedCsses .= '<link rel="stylesheet" type="text/css" href="' . $res . '">' . "\n";
            }
            else if(Fynd_StringUtil::endWith($res, 'js'))
            {
                $this->_linkedScripts .= '<script type="text/javascript" src="' . $res . '"></script>' . "\n";
            }
        }
        $tpl = file_get_contents(dirname(__FILE__) . '/Head.html');
        $tpl = str_replace('{:charset}', $this->_charset, $tpl);
        $tpl = str_replace('{:title}', $this->_title, $tpl);
        $tpl = str_replace('{:linkedCsses}', $this->_linkedCsses, $tpl);
        $tpl = str_replace('{:linkedScripts}', $this->_linkedScripts, $tpl);
        return $tpl;
    }
}
?>