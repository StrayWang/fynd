<?php
require_once ('Fynd/UI/Component.php');
require_once 'Fynd/UI/IDataBind.php';
class Fynd_UI_Table extends Fynd_UI_Component implements Fynd_UI_IDataBind
{
    /**
     * @var Fynd_IList
     */
    protected $_dataSource = null;
    /**
     * @var string
     */
    protected $_jsArrayDataSource = '';
    /**
     * @var Fynd_IList
     */
    protected $_columns;
    /**
     * @var int
     */
    protected $_width;
    /**
     * @var string
     */
    protected $_jsonDataUrl='';
    /**
     * @var string
     */
    protected $_colDefs;
    public $onSearching;
    public $onEditing;
    public function __construct(Fynd_View_Html $view)
    {
        parent::__construct($view);
        $this->_columns = new Fynd_Dictionary();
    }
    /**
     * @return string
     */
    public function getJsonDataUrl()
    {
        return $this->_jsonDataUrl;
    }
    /**
     * @param string $_jsonDataUrl
     */
    public function setJsonDataUrl($_jsonDataUrl)
    {
        $this->_jsonDataUrl = $_jsonDataUrl;
    }
    /**
     * @return Fynd_IList
     */
    public function getColumns()
    {
        return $this->_columns;
    }
    /**
     * @param Fynd_IList $_columns
     */
    public function setColumns(Fynd_IList $_columns)
    {
        $this->_columns = $_columns;
    }
    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->_width;
    }
    /**
     * @param int $_width
     */
    public function setWidth($_width)
    {
        $this->_width = $_width;
    }
    public function addColumn(Fynd_UI_TableColumn $col)
    {
        if(! $this->_columns->contains($col))
        {
            $this->_columns->add($col);
        }
    }
    /**
     * @see Fynd_UI_Component::initialize()
     *
     */
    public function initialize()
    {
//        $this->_view->addResource('yahoo-sam-autocomplete.css', 'http://yui.yahooapis.com/2.7.0/build/autocomplete/assets/skins/sam/autocomplete.css');
//        $this->_view->addResource('yahoo-sam-paginator.css', 'http://yui.yahooapis.com/2.7.0/build/paginator/assets/skins/sam/paginator.css');
//        $this->_view->addResource('yahoo-sam-datatable.css', 'http://yui.yahooapis.com/2.7.0/build/datatable/assets/skins/sam/datatable.css');
//        $this->_view->addResource("yahoo-yahoo.js", 'http://yui.yahooapis.com/2.7.0/build/yahoo/yahoo.js');
//        $this->_view->addResource('yahoo-event.js', 'http://yui.yahooapis.com/2.7.0/build/event/event.js');
//        $this->_view->addResource('yahoo-connection.js', 'http://yui.yahooapis.com/2.7.0/build/connection/connection.js');
//        $this->_view->addResource('yahoo-json.js', 'http://yui.yahooapis.com/2.7.0/build/json/json.js');
//        $this->_view->addResource('yahoo-datasource.js', 'http://yui.yahooapis.com/2.7.0/build/datasource/datasource.js');
//        $this->_view->addResource('yahoo-dom.js', 'http://yui.yahooapis.com/2.7.0/build/dom/dom.js');
//        $this->_view->addResource('yahoo-autocomplete.js', 'http://yui.yahooapis.com/2.7.0/build/autocomplete/autocomplete.js');
//        $this->_view->addResource('yahoo-element.js', 'http://yui.yahooapis.com/2.7.0/build/element/element.js');
//        $this->_view->addResource('yahoo-paginator.js', 'http://yui.yahooapis.com/2.7.0/build/paginator/paginator.js');
//        $this->_view->addResource('yahoo-datatable.js', 'http://yui.yahooapis.com/2.7.0/build/datatable/datatable.js');
    }
    /**
     * @see Fynd_UI_IDataBind::bindData()
     *
     */
    public function bindData()
    {
        $this->_jsArrayDataSource = Fynd_JSON::encode($this->_dataSource);
    }
    /**
     * @see Fynd_UI_IDataBind::setDataSource()
     *
     * @param Fynd_IList $dataSource
     */
    public function setDataSource(Fynd_IList $dataSource)
    {
        $this->_dataSource = $dataSource;
    }
    /**
     * @see Fynd_UI_Component::render()
     *
     */
    public function render()
    {
        $this->_colDefs = "[";
        foreach($this->_columns as $col)
        {
            $resizeableStr = "false";
            $sortableStr = "false";
            if(true === $col->getResizeable())
            {
                $resizeableStr = 'true';
            }
            if(true === $col->getSortable())
            {
                $sortableStr = 'true';
            }
            $formatter = "\"\"";
            $operationItems = $col->getOperationItems();
            if(count($operationItems) > 0)
            {
                $formatter = "function (el, oRecord, oColumn, oData) {\n";
                $formatter .= "el.innerHTML = '';\n";
                foreach($operationItems as $opItem)
                {
                    if($opItem)
                    {
                        $formatter .= $opItem->render();
                    }
                }
                $formatter .= "}";
            }
            $this->_colDefs .= "{\n";
            $this->_colDefs .= "key        : \"{$col->getKey()}\",\n";
            $this->_colDefs .= "label      : \"{$col->getLabel()}\",\n";
            $this->_colDefs .= "width      : {$col->getWidth()},\n";
            $this->_colDefs .= "resizeable : {$resizeableStr},\n";
            $this->_colDefs .= "sortable   : {$sortableStr},\n";
            $this->_colDefs .= "formatter  : {$formatter}\n";
            $this->_colDefs .= "},\n";
        }
        if(Fynd_StringUtil::endWith($this->_colDefs, ","))
        {
            $this->_colDefs = Fynd_StringUtil::removeEnd($this->_colDefs);
        }
        $this->_colDefs .= ']';
        $tpl = file_get_contents(dirname(__FILE__) . '/Table.html');
        $tpl = str_replace('{:Id}', $this->_id, $tpl);
        $tpl = str_replace('{:ColumDefinitions}', $this->_colDefs, $tpl);
        $yuiTableDataSource = 'null';
        $yuiTableDynamicData = false;
        $yuiTableResponseType = "YAHOO.util.DataSource.TYPE_JSARRAY";
        if(empty($this->_jsArrayDataSource))
        {
            $yuiTableDataSource = "'" . $this->_jsonDataUrl . "'";
            $yuiTableDataSource = true;
            $yuiTableResponseType = "YAHOO.util.DataSource.TYPE_JSON";
        }
        else
        {
            $yuiTableDataSource = "YAHOO.lang.JSON.parse(" . $this->_jsArrayDataSource . ")";
        }
        $tpl = str_replace('{:YuiTableDataSource}', $yuiTableDataSource, $tpl);
        if(true == $yuiTableDynamicData)
        {
            $tpl = str_replace('{:YuiTableDynamicData}', 'true', $tpl);
        }
        else
        {
            $tpl = str_replace('{:YuiTableDynamicData}', 'false', $tpl);
        }
        $tpl = str_replace('{:YuiTableResponseType}',$yuiTableResponseType , $tpl);
        return $tpl;
    }
}
?>