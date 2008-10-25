<?php
require_once 'Config/ConfigManager.php';
require_once 'Config/ConfigType.php';
/**
 * Database abstract layer,
 * now it support mysql only,
 * in furture ,it can support other databases,or xml,text file etc.
 *
 */
class Fynd_Db
{
    /**
     * single instance of Fynd_Db
     *
     * @var Fynd_Db
     */
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
    /**
     * Show that database's connection is persistent or not
     *
     * @var unknown_type
     */
    protected $_persistent = true;
    /**
     * 获取DB实例
     *
     * @return Fynd_Db
     */
    public static function getInstance ($config = null,$persistent = true)
    {
        if (! self::$_instance instanceof Fynd_Db)
        {
            if($config == null)
            {
                $config = Fynd_Config_ConfigManager::getConfig(Fynd_Config_ConfigType::DbConfig, Fynd_Application::getConfigPath().'DbConfig.xml')
                        ->getDefaultConnectionConfig();
            }
            self::$_instance = new Fynd_Db($config);
        }
        self::$_instance->setPersistent($persistent);
        return self::$_instance;
    }
    /**
     * Constructor of Fynd_Db,
     * accept Fynd_Config_DbConnectionConfig as datatbase connection configure
     *
     * @param Fynd_Config_DbConnectionConfig $config
     * @param bool $persistent
     */
    protected function __construct (Fynd_Config_DbConnectionConfig $config,$persistent = true)
    {
        $this->_config = $config;
        $this->_persistent = $persistent;
    }
    /**
     * Set database's connection is persistent or not.
     *
     * @param bool $persistent
     */
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
            $this->_pdo->setAttribute(PDO::ATTR_AUTOCOMMIT,true);
            //$this->_pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
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
            throw new Fynd_DbException($this->_stmt->errorCode() . " " . implode(', ',$this->_stmt->errorInfo()));
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
    public function excute ($sql, array $param = array())
    {
        $this->open();
        $this->_createStatement($sql, $param);
        $this->_stmt->execute();
        if ($this->_stmt->errorCode() != '00000')
        {
            include_once 'Db/DbException.php';
            throw new Fynd_DbException($this->_pdo->errorCode() . " " . implode(', ',$this->_pdo->errorInfo()));
        }
        $this->_stmt->closeCursor();
    }
    /**
     * 创建Satatement，如果sql对应的statement被缓存过，则取缓存的Statement
     *
     * @param string $sql
     * @param array $param
     * @return PDOStatement
     */
    protected function _createStatement ($sql, array $param = null)
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
                $this->_stmt->bindValue($p->Name, $p->Value, $p->DbDataType);
            }
        }
        return $this->_stmt;
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
        $result = array();
        switch ($this->_fetchMode)
        {
            case PDO::FETCH_CLASS:
                if (empty($fetchClassName))
                {
                    include_once 'Db/DbException.php';
                    throw new Fynd_DbException('fetchClass parameter can not be null');
                }
                $this->_stmt->setFetchMode(PDO::FETCH_ASSOC);
                $resultSet = $this->_stmt->fetchAll();
                $result = array();
                foreach ($resultSet as $row)
                {
                    $obj = new $fetchClassName();
                    $obj->beginInitializtion();
                    foreach ($row as $field=>$value)
                    {
                        $obj->$field = $value;
                    }
                    $obj->endInitializtion();
                    $result[] = $obj;
                }
                if(count($result) == 1)
                {
                    $result = $result[0];
                }
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
            case Fynd_DB::FETCH_SINGLE_COLUMN:
                while($col = $this->_stmt->fetchColumn(0))
                {
                    $result[] = $col;
                }
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
    /**
     * Get next value of filed from sequence
     *
     * @param string $field
     * @return int
     */
    public function getNextId($field)
    {
        $sql = "Select s.sequence+1 From sequence As s Where s.field_name = :v_field";
        $p1 = new Fynd_DbParameter();
        $p1->Name = ':v_field';
        $p1->Value = $field;
        $p1->DbDataType = PDO::PARAM_STR; 
        $this->open();
        $this->setFetchMode(PDO::FETCH_ASSOC);
        $seq = $this->query($sql,array($p1));
        if($seq)
        {
            $sqlUpdate = "Update `sequence` As s Set s.sequence = :v_sequence Where s.field_name = :v_field";
            $p2 = new Fynd_DbParameter();
            $p2->Name = ':v_sequence';
            $p2->Value = $seq;
            $p2->DbDataType = PDO::PARAM_INT;
            $this->excute($sqlUpdate,array($p1,$p2));
        }
        $this->close();
        return $seq;
    }
    
    const FETCH_SINGLE_COLUMN = 1001;
}
?>