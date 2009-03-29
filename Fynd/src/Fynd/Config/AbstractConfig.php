<?php
require_once 'Fynd/Object.php';
/**
 * 配置类的基类,主要完成如何读取配置文件和将配置项设置为类成员的任务
 *
 */
abstract class Fynd_Config_AbstractConfig extends Fynd_Object implements Serializable
{
    /**
     * 原始XML对象
     *
     * @var SimpleXMLElement
     */
    protected $_rawXmlDoc = null;
    /**
     * 构造函数
     *
     * @param string $configXmlPath
     */
    public function __construct($configXmlPath)
    {
        //		if(empty($configXmlPath))
        //		{
        //			Fynd_Object::throwException("Fynd_Config_Exception",'Parameter $configXmlPath can not be empty');
        //		}
        $this->_readXml($configXmlPath);
        $this->_initConfig();
    }
    /**
     * 获取原始配置的SimpleXmlElement对象
     * @return  SimpleXMLElement
     */
    public function getRawXmlDoc()
    {
        return $this->_rawXmlDoc;
    }
    /**
     * 读取XML配置文件
     *
     * @param String $configXmlPath xml configure string
     * @exception Exception
     */
    protected function _readXml($configXmlPath)
    {
        if(! empty($configXmlPath))
        {
            $this->_rawXmlDoc = @simplexml_load_file($configXmlPath);
            if($this->_rawXmlDoc === false || is_null($this->_rawXmlDoc))
            {
                Fynd_Object::throwException("Fynd_Config_Exception", "Unable load xml document object from given configure xml string");
            }
        }
    }
    /**
     * @see Serializable::serialize()
     *
     */
    public function serialize()
    {
        if(!is_null($this->_rawXmlDoc) && false !== $this->_rawXmlDoc)
        {
            return $this->_rawXmlDoc->asXML();
        }
        return serialize($this->_rawXmlDoc);
    }
    /**
     * @see Serializable::unserialize()
     *
     * @param serialized $serialized
     */
    public function unserialize($serialized)
    {
        $this->_rawXmlDoc = @simplexml_load_string($serialized);
        $this->_initConfig();
    }
    /**
     * 初始化配置
     * @return void
     */
    protected abstract function _initConfig();
}
?>