<?php
require_once ('Fynd/Object.php');
require_once 'Fynd/Db/ISQLBuilder.php';
class Fynd_Model_Filter extends Fynd_Object implements Fynd_Db_ISQLBuilder
{
    const LB                = '(';
    const RB                = ')';
    const EQUAL             = '=';
    const NOT_EQUAL         = '<>';
    const IS                = 'Is';
    const GREATER           = '>';
    const GREATER_OR_EQUAL  = '>=';
    const LESS              = '<';
    const LESS_OR_EQUAL     = '<=';
    const LIKE              = 'Like';
    const LOGIC_AND         = 'And';
    const LOGIC_OR          = 'Or';
    const LOGIC_XOR         = 'Xor';
    
    /**
     * @var Fynd_Model_Entity
     */
    private $_entity;
    
    /**
     * The selection expression which will be used in having clause
     *
     * @var Fynd_Model_SelectExpr
     */
    private $_selectionExpression;
    /**
     * The operator is specified by Fynd_Model_Filter's const.
     *
     * @var string
     */
    private $_operator;
    /**
     * The value in right side of operater.
     *
     * @var scalar
     */
    private $_value;
    /**
     * The left brackets
     *
     * @var string
     */
    private $_lb;
    /**
     * The right brackets
     *
     * @var string
     */
    private $_rb;
    /**
     * The logic operator at the end of filter.
     *
     * @var string
     */
    private $_logic;
    /**
     * The filter's expression
     *
     * @var string
     */
    private $_expr;
    /**
     * @return string
     */
    public function getExpression()
    {
        return $this->_expr;
    }
    /**
     * @param string $expr
     */
    public function setExpression($expr)
    {
        $this->_expr = $expr;
    }
    /**
     * @return string
     */
    public function getLb()
    {
        return $this->_lb;
    }
    /**
     * @return string
     */
    public function getLogic()
    {
        return $this->_logic;
    }
    /**
     * @return string
     */
    public function getRb()
    {
        return $this->_rb;
    }
    /**
     * @param string $_lb
     */
    public function setLb($_lb)
    {
        $this->_lb = $_lb;
    }
    /**
     * @param string $_logic
     */
    public function setLogic($_logic)
    {
        $this->_logic = $_logic;
    }
    /**
     * @param string $_rb
     */
    public function setRb($_rb)
    {
        $this->_rb = $_rb;
    }
    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->_operator;
    }
    /**
     * @return Fynd_Model_SelectExpr
     */
    public function getSelectionExpression()
    {
        return $this->_selectionExpression;
    }
    /**
     * @return scalar
     */
    public function getValue()
    {
        return $this->_value;
    }
    /**
     * @param string $_operator
     */
    public function setOperator($_operator)
    {
        $this->_operator = $_operator;
    }
    /**
     * @param Fynd_Model_SelectExpr $_selectionExpression
     */
    public function setSelectionExpression(Fynd_Model_SelectExpr $_selectionExpression)
    {
        $this->_selectionExpression = $_selectionExpression;
    }
    /**
     * @param scalar $_value
     */
    public function setValue($_value)
    {
        $this->_value = $_value;
    }
    /**
     * @return Fynd_Model_Entity
     */
    public function getEntity()
    {
        return $this->_entity;
    }
    /**
     * @param Fynd_Model_Entity $_entity
     */
    public function setEntity($_entity)
    {
        $this->_entity = $_entity;
    }

    
    
    
    
    /**
     * @param Fynd_Model_Entity $entity
     */
    public function __construct(Fynd_Model_Entity $entity = null)
    {
        $this->_entity = $entity;
    }
    /**
     * @see Fynd_Db_ISQLBuilder::createSQL()
     * 
     * @return string
     *
     */
    public function createSQL()
    {
        if(empty($this->_expr))
        {
            $this->_expr = "WHERE ";
            $this->_expr .= $this->_lb;
            $field = $this->_entity->getField();
            $this->_expr .= $field;
            $this->_expr .= $this->_operator;
            $this->_expr .= ":p_" . $field;
            $this->_expr .= $this->_rb;
            $this->_expr .= " " . $this->_logic . " ";
        }
        return $this->_expr;
    }
}
?>