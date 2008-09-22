<?php
require_once 'Config/ConfigManager.php';
require_once 'Config/ConfigType.php';
class Fynd_Db
{
    protected static $_instance;
    /**
     * 数据库连接配置
     *
     * @var Fynd_Config_DbConnectionConfig
     */
    protected $_config;
    /**
     * PDO对象
     *
     * @var PDO
     */
    protected $_pdo;
    /**
     * 数据库结果集获取模式，由PDO类常量指定
     *
     * @var int
     */
    protected $_fetchMode = PDO::FETCH_ASSOC;
    /**
     * sql缓存
     *
     * @var string
     */
    protected $_sql;
    /**
     * PDOStatement缓存
     *
     * @var PDOStatement
     */
    protected $_stmt;
    protected $_persistent = true;
    /**
     * 获取DB实例
     *
     * @return Fynd_Db
     */
    public static function getInstance ($persistent = true)
    {
        if (! self::$_instance instanceof Fynd_Db)
        {
            $dbConfig = Fynd_Config_ConfigManager::getConfig(Fynd_Config_ConfigType::DbConfig, Fynd_Application::getConfigPath().'DbConfig.xml');
            $connConfig = $dbConfig->getDefaultConnectionConfig();
            self::$_instance = new Fynd_Db($connConfig);
        }
        self::$_instance->setPersistent($persistent);
        return self::$_instance;
    }
    protected function __construct (Fynd_Config_DbConnectionConfig $config,$persistent = true)
    {
        $this->_config = $config;
        $this->_persistent = $persistent;
    }
    public function setPersistent($persistent = true)
    {
        $this->_persistent = $persistent;
    }
    /**
     * 开启数据库连接
     *
     */
    public function open ()
    {
        if (is_null($this->_pdo))
        {
            $dsn = strtolower($this->_config->getDbType()) . ':host=' . $this->_config->getServer() . ';port=' . $this->_config->getPort() . ';dbname=' . $this->_config->getDatabase();
            $this->_pdo = new PDO($dsn, $this->_config->getUser(), $this->_config->getPassword());
            $this->_pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        }
    }
    /**
     * 关闭数据库连接，
     * 当通过getInstance方法获取实例时指定persistent参数为false，则销毁PDO对象实例
     * 否则不销毁
     *
     */
    public function close ()
    {
        if (! $this->_persistent) $this->_pdo = null;
    }
    /**
     * 设置数据库结果接获取模式，由PDO类常量指定
     *
     * @param int $mode
     */
    public function setFetchMode ($mode)
    {
        $this->_fetchMode = $mode;
    }
    /**
     * 发送查询，获取结果集
     *
     * @param string $sql
     * @param array $param
     * @param $fetchClassName 当FetchMode被设置为FetchClass时，将返回该参数指定的类的实例集合
     * @param $fetchObj 当FetchMode被设置为FetchInto时，将返回该参数指定的对象
     * @return mixed
     */
    public function query ($sql, array $param = null, $fetchClassName = null, $fetchObj = null)
    {
        $this->open();
        $this->_createStatement($sql, $param);
        if (! $this->_stmt->execute())
        {
            include_once 'Db/DbException.php';
            throw new Fynd_DbException($this->_stmt->errorCode() . " " . $this->_stmt->errorInfo());
        }
        $result = $this->_fetchAll($fetchClassName, $fetchObj);
        $this->_stmt->closeCursor();
        return $result;
    }
    /**
     * 执行sql，不获取结果集
     *
     * @param string $sql
     * @param array $param
     */
    public function excute ($sql, array $param = null)
    {
        $this->open();
        $this->_createStatement($sql, $param);
        if (! $this->_stmt->execute())
        {
            include_once 'Db/DbException.php';
            throw new Fynd_DbException($this->_stmt->errorCode() . " " . $this->_stmt->errorInfo());
        }
        $this->_stmt->closeCursor();
    }
    public function _createStatement ($sql, array $param = null)
    {
        if ($this->_sql != $sql)
        {
            $this->_stmt = $this->_pdo->prepare($sql);
            $this->_sql = $sql;
        }
        if (! is_null($param))
        {
            foreach ($param as $p)
            {
                $this->_stmt->bindParam($p->Name, $p->Value, $p->DbDataType);
            }
        }
    }
    /**
     * 获取结果集
     *
     * @param PDOStatement $this->_stmt
     * @param string $fetchClassName
     * @param object $fetchObj
     */
    protected function _fetchAll ($fetchClassName = null, $fetchObj = null)
    {
        $result = null;
        switch ($this->_fetchMode)
        {
            case PDO::FETCH_CLASS:
                if (empty($fetchClassName))
                {
                    include_once 'Db/DbException.php';
                    throw new Fynd_DbException('fetchObject参数不能为null');
                }
                $this->_stmt->setFetchMode($this->_fetchMode, $fetchClassName);
                $result = $this->_stmt->fetchAll();
                break;
            case PDO::FETCH_INTO:
                if (is_null($fetchObj))
                {
                    include_once 'Db/DbException.php';
                    throw new Fynd_DbException('fetchObject参数不能为null');
                }
                $this->_stmt->setFetchMode($this->_fetchMode, $fetchObj);
                $result = $this->_stmt->fetch($this->_fetchMode);
                break;
            default:
                $this->_stmt->setFetchMode($this->_fetchMode);
                $result = $this->_stmt->fetchAll();
                break;
        }
        if (is_array($result) && count($result) == 1)
        {
            if(is_array($result[0]) && count($result[0]) == 1)
            {
                $value = array_values($result[0]);
                $result = $value[0];
            }
        }
        return $result;
    }
}
?>