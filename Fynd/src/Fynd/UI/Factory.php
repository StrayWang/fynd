<?php
require_once ('Fynd/Object.php');
class Fynd_UI_Factory extends Fynd_Object
{
    /**
     * @var Fynd_Log
     */
    private $_log = null;
    private $_idSeq = 0;
    public function __construct()
    {
        $this->_log = Fynd_Application::getLogger('Fynd_UI_Factory');
    }
    public function initialize()
    {
        $this->_idSeq = 0;
    }
    public function createUIComponent($tag, Fynd_UI_IContainer $container)
    {
        $xmlDoc = @simplexml_load_string($tag);
        if(false === $xmlDoc)
        {
            $this->_log->logError($tag);
            Fynd_Object::throwException("Fynd_UI_Exception", "UI component tag is not valid");
        }
        $uiClassName = 'Fynd_UI_' . Fynd_StringUtil::capitalize($xmlDoc->getName());
        $uiType = new Fynd_Type($uiClassName);
        $uiType->includeTypeDefinition();
        $view = $container->getHtmlView();
        $uiObject = new $uiClassName($view);
        $hasSetID = false;
        foreach($xmlDoc->attributes() as $attrName => $attrValue)
        {
            $setterName = 'set' . Fynd_StringUtil::capitalize($attrName);
            $uiObject->$setterName((string)$attrValue);
            if('ID' == $attrName)
            {
                $hasSetID = true;
            }
        }        
        if(!$uiObject instanceof Fynd_UI_IContainer)
        {
            foreach($xmlDoc as $childName => $child)
            {
                $setterName = 'set' . Fynd_StringUtil::capitalize($childName);
                $uiObject->$setterName($this->_createChildComponent($child,$uiObject));
            }
        }
        if(! $hasSetID && $uiObject instanceof Fynd_UI_IComponent)
        {
            $uiObject->setID('Fynd$Component$' . $this->_idSeq);
            $this->_idSeq += 1;
        }
        return $uiObject;
    }
    /**
     * Create object of ui component'children
     *
     * @param SimpleXmlElement $xmlNode
     * @param Fynd_View_Html $view
     * @return Fynd_Object|string
     */
    protected function _createChildComponent($xmlNode,Fynd_UI_Component $parent)
    {
        $childName = $xmlNode->getName();
        $setterName = 'set' . $childName;
        $nodeObjectTypeName = 'Fynd_UI_' . Fynd_StringUtil::capitalize($childName);
        $nodeObjectType = null;
        $nodeObject = null;
        if(! Fynd_Type::isTypeExistent($nodeObjectTypeName) && $xmlNode instanceof SimpleXMLElement && count($xmlNode->children()) > 0)
        {
            $nodeObject = new Fynd_List();
        }
        else
        {
            $nodeObjectType = new Fynd_Type($nodeObjectTypeName);
            $nodeObjectType->includeTypeDefinition();
            $nodeObject = new $nodeObjectTypeName($parent->getView());
        }
        foreach($xmlNode->attributes() as $attrName => $attrValue)
        {
            $setterName = 'set' . Fynd_StringUtil::capitalize($attrName);
            $nodeObject->$setterName((string)$attrValue);
        }
        $currentParent = ($nodeObject instanceof Fynd_UI_Component) ? $nodeObject : $parent;
        foreach($xmlNode as $childName => $child)
        {
            if($child instanceof SimpleXMLElement)
            {
                $compiledChild = $this->_createChildComponent($child,$currentParent);
                if($nodeObject instanceof Fynd_IList)
                {
                    $nodeObject->add($compiledChild);
                }
                else
                {
                    $setterName = 'set' . Fynd_StringUtil::capitalize($childName);
                    $nodeObject->$setterName($compiledChild);
                }
            }
        }
        if($nodeObject instanceof Fynd_UI_Component)
        {
            $nodeObject->initialize();
        }
        return $nodeObject;
    }
    public function parse($preParsedHtml, Fynd_UI_IContainer $container)
    {
        $componentObjs = $container->getComponents();
        foreach($componentObjs as $obj)
        {
            if($obj)
            {
                $placeHolder = '{:Fynd_' . $obj->getID() . '}';
                if(strpos($preParsedHtml,$placeHolder) === false)
                {
                    $preParsedHtml .= $obj->render();
                }
                else 
                {
                    $preParsedHtml = str_replace($placeHolder, $obj->render(), $preParsedHtml);
                }
            }
        }
        return $preParsedHtml;
    }
    public function preParse($str, Fynd_UI_IContainer $container)
    {
        $components = array();
        $componentNum = preg_match_all('%(<(Fynd:[a-zA-Z]+)[^>]*>(.*?)</\2>)%is', $str, $components, (PREG_OFFSET_CAPTURE + PREG_SET_ORDER));
        for($i = 0; $i < $componentNum; $i ++)
        {
            $componentString = $components[$i][1][0];
            $innerHtml = $components[$i][3][0];
            //$replaceOffset = $components[$i][1][1];
            //$replaceLength = strlen($componentString);
            $componentObj = $this->createUIComponent($componentString, $container);
            $componentObj->setInnerHtml($innerHtml);
            $componentObj->setOuterHtml($componentString);
            $componentObj->initialize();
            //将当前控件在页面所在位置替换为占位符,在调用控件render方法后将其替换
            $id = $componentObj->getID();
            $placeHolder = "{:Fynd_" . $id . "}";
            $view = $container->getHtmlView();
            if($componentObj instanceof Fynd_UI_Head)
            {
                $view->setHeadComponent($componentObj);
            }
            $str = str_replace($componentString, $placeHolder, $str);
            $container->addComponent($componentObj);
        }
        return $str;
    }
}
?>