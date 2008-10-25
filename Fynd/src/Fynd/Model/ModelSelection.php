<?php
class Fynd_Model_ModelSelection
{
	/**
	 * 在属性前面是否有左括号？
	 *
	 * @var bool
	 */
	public $LeftBrackets = false;
	/**
	 * 属性名
	 *
	 * @var string
	 */
	public $Property;
	/**
	 * 操作符 ,包括标准SQL操作符，如=,<>,>,<,IS等
	 * 默认为“=”
	 *
	 * @var string
	 */
	public $Operation = self::EQUAL;
	/**
	 * 条件值,如果条件值是数组即有多个值，将使用OR连接
	 *
	 * @var mixed
	 */
	public $ConditionValue;
	/**
	 * 与下一个条件的逻辑操作，如果有，逻辑操作符包括AND OR XOR
	 *
	 * @var string
	 */
	public $NextLogicOperater;
	/**
	 * 右括号，如果有
	 *
	 * @var string
	 */
	public $RightBrackets = false;
	
	const LB = '(';
	const RB = ')';
	const EQUAL = '=';
	const NOT_EQUAL = '<>';
	const IS = 'Is';
	const GREATER = '>';
	const GREATER_OR_EQUAL = '>=';
	const LESS = '<';
	const LESS_OR_EQUAL = '<=';
	const LIKE = 'Like';
	const LOGIC_AND = 'And';
	const LOGIC_OR = 'Or';
	const LOGIC_XOR = 'Xor';
}
?>