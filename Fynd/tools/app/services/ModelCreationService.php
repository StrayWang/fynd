<?php
require_once ('Fynd/Service.php');
require_once ('Fynd/Object.php');
class ModelCreationService extends Fynd_Service
{
    /**
     * @var Fynd_Log
     */
    private static $_log = null;
    /**
     * @see Fynd_Service::init()
     *
     */
    public function init()
    {
        if(is_null(self::$_log))
        {
            self::$_log = Fynd_Application::getLogger('ModelCreationService');
        }
        self::$_log->logInfo("Logger initialized.");
        
    }
    /**
     * Gets the table list in the database decribed by $dbModel.
     *
     * @param DatabaseModel $dbModel
     * @return Fynd_List
     */
    public function getTableList(DatabaseModel $dbModel)
    {
        $conn = $this->_createDbConnectionObject($dbModel);
        $tables = new Fynd_List();
        $isConnOpen = false;
        try
        {
            $sql = "SHOW TABLES";
            $cmd = $conn->createCommand($sql);
            $isConnOpen = $conn->open();
            $rows = $cmd->execute();
            
            self::$_log->logInfo(var_export($rows,true));
            
            if(is_array($rows))
            {
                foreach($rows as $row)
                {
                    $row = array_values($row);
                    $table = Fynd_Model_Factory::createModel(new Fynd_Type("TableModel"));
                    self::$_log->logInfo($row[0]);                   
                    $table->setTableName($row[0]);
                    $tables->add($table);
                }
            }
            if($isConnOpen)
            {
                $conn->close();
            }
        }
        catch(Exception $e)
        {
            if($isConnOpen)
            {
                $conn->close();
            }
            throw $e;
        }
        return $tables;
    }
    public function getFieldList(TableModel $tableModel, DatabaseModel $dbModel, FieldModel &$primary = null)
    {
        $conn = $this->_createDbConnectionObject($dbModel);
        $fields = new Fynd_List();
        $isConnOpen = false;
        try
        {
            $sql = 'SHOW FIELDS FROM `' . $tableModel->getTableName() . '`';
            $cmd = $conn->createCommand($sql);
            $isConnOpen = $conn->open();
            $rows = $cmd->execute();
            if($isConnOpen)
            {
                $conn->close();
            }
            if(! is_array($rows))
            {
                return $fields;
            }

            foreach($rows as $row)
            {
                $feild = new FieldModel();
                $feild->setName($row['Field']);
                $mathes = array();
                if(preg_match_all('/(\w)+(\((\d)\))*/', $row['Type'], $mathes))
                {
                    if($mathes[0][0] == 'int')
                    {
                        $feild->setDataType(Fynd_Db_DataType::NUMBER);
                        $feild->setLength(strval($mathes[0][1]));
                    }
                    else if($mathes[0][0] == 'datetime')
                    {
                        $feild->setDataType(Fynd_Db_DataType::DATETIME);
                    }
                    else
                    {
                        $feild->setDataType(Fynd_Db_DataType::STRING);
                        $feild->setLength(strval($mathes[0][1]));
                    }
                }
                
                if($row['Null'] == 'NO')
                {
                    $feild->setIsNullable(false);
                    if(! is_null($primary))
                    {
                        $primary = $feild;
                    }
                }
                else
                {
                    $feild->setIsNullable(true);
                }
                if($row['Key'] == 'PRI')
                {
                    $feild->setIsPrimaryKey(true);
                }
                $feild->setKey($row['Key']);
                $feild->setDefault($row['Default']);
                $feild->setExtra($row['Extra']);
                $fields->add($feild);
            }
        }
        catch(Exception $e)
        {
            if($isConnOpen)
            {
                $conn->close();
            }
            throw $e;
        }
        return $fields;
    }
    /**
     * Create Fynd models of all tables of database.
     *
     * @param DatabaseModel $dbModel
     * @return array
     */
    public function createAllFyndModelMap(DatabaseModel $dbModel)
    {
        $tables = $this->getTableList($dbModel);
        $result = array();
        foreach($tables as $table)
        {
            $singleResult = $this->createFyndModelMap($table,$dbModel);
            unset($singleResult['DownloadFileName']);
            foreach ($singleResult as $key=>$value)
            {
                $result[$key] = $value;    
            }
        }
        $result['DownloadFileName'] = $dbModel->getDatabaseName() . ".zip";
        
        return $result;
    }
    /**
     * Create data mapping xml string used by Fynd framework 
     *
     * @param TableModel $tableModel
     * @param DatabaseModel $dbModel
     * @return string The temp zip file's path
     */
    public function createFyndModelMap(TableModel $tableModel, DatabaseModel $dbModel)
    {
        $tableName          = $tableModel->getTableName();
        $className          = $this->_convertTableOrFieldName($tableName) . "Model";
        $primayField        = new FieldModel();
        $fields             = $this->getFieldList($tableModel, $dbModel, $primayField);
        
        $primayPropertyName = $this->_convertTableOrFieldName($primayField->getName());
        
        $xmlDoc   = new DOMDocument('1.0', 'utf-8');
        $rootNode = $xmlDoc->createElement('Root');
        $rootNode->setAttribute('Class', $className);
        $rootNode->setAttribute('Table', $tableName);
        $rootNode->setAttribute('PrimaryProperty', $primayPropertyName);
        
        $classTplXml        = simplexml_load_file(Fynd_Env::getServicePath() . 'ModelClassTemplate.xml');
        $includeTpl         = trim((string)$classTplXml->IncludeDefinition->Include);
        $classCommentTpl    = trim((string)$classTplXml->ClassDefinition->Comment);
        $classTpl           = trim((string)$classTplXml->ClassDefinition->Class);
        $getterCommentTpl   = trim((string)$classTplXml->GetterDefinition->Comment);
        $getterTpl          = trim((string)$classTplXml->GetterDefinition->Getter);
        $setterCommentTpl   = trim((string)$classTplXml->SetterDefinition->Comment);
        $setterTpl          = trim((string)$classTplXml->SetterDefinition->Setter);
        $privatePropertyTpl = trim((string)$classTplXml->PrivatePropertyDefinition->PrivateProperty);
        
        $includeImpl          = str_replace('{IncludeFilePath}','Fynd/Model.php',$includeTpl);
        $classCommentImpl     = str_replace('{CreationTool}','Created by Fynd model creation tool.',$classCommentTpl);
        $classImpl            = str_replace('{ClassName}',$className,$classTpl);
        $classImpl            = $classCommentImpl . "\n" . $classImpl;
        $classImpl            = $includeImpl . "\n" . $classImpl;
        $privatePropertyImpls = array();
        $methodImpls          = array();
        foreach($fields as $field)
        {
            $entryNode = $xmlDoc->createElement('Entry');

            $privateVar = $this->_convertTableOrFieldName($field->getName());
            $propertyNode = $xmlDoc->createElement('Property', $privateVar);
            $fieldNode = $xmlDoc->createElement('Field', $field->getName());
            
            $dataTypeString = "";
            if($field->getDataType() == Fynd_Db_DataType::NUMBER)
            {
                $dataTypeString = 'number';
            }
            else if($field->getDataType() == Fynd_Db_DataType::DATETIME)
            {
                $dataTypeString = 'datettime';
            }
            else
            {
                $dataTypeString = 'string';
            }
            $dataTypeNode   = $xmlDoc->createElement('DataType', $dataTypeString);
            $dataLengthNode = $xmlDoc->createElement('DataLength', $field->getLength());

            $entryNode->appendChild($propertyNode);
            $entryNode->appendChild($fieldNode);
            $entryNode->appendChild($dataTypeNode);
            $entryNode->appendChild($dataLengthNode);
            $rootNode ->appendChild($entryNode);
            
            $privateProperty     = Fynd_StringUtil::cancelCapitalize($privateVar);
            $privatePropertyImpl = str_replace('{PrivateProperty}',$privateProperty,$privatePropertyTpl);
            $getterCommentImpl   = str_replace('{ReturnType}',$dataTypeString,$getterCommentTpl);
            $getterImpl          = str_replace('{PrivateProperty}',$privateProperty,$getterTpl);
            $getterImpl          = str_replace('{Property}',$privateVar,$getterImpl);
            $getterImpl          = $getterCommentImpl . "\n" . str_repeat(" ",4) . $getterImpl;
            $setterCommentImpl   = str_replace('{ParamemterType}',$dataTypeString,$setterCommentTpl);
            $setterImpl          = str_replace('{PrivateProperty}',$privateProperty,$setterTpl);
            $setterImpl          = str_replace('{Property}',$privateVar,$setterImpl);
            $setterImpl          = $setterCommentImpl . "\n" . str_repeat(" ",4) . $setterImpl;
            
            $privatePropertyImpls[] = $privatePropertyImpl;
            $methodImpls[]          = $getterImpl;
            $methodImpls[]          = $setterImpl;
        }
        
        $xmlDoc->appendChild($rootNode);
        
        $classImpl = str_replace('{PrivateProperties}',implode("\n" . str_repeat(" ",4),$privatePropertyImpls),$classImpl);
        $classImpl = str_replace('{Methods}',implode("\n" . str_repeat(" ",4),$methodImpls),$classImpl);
        
        $xmlDoc->formatOutput = true;
        $modelMapXml = $xmlDoc->saveXML();
        
        $modelMapXmlFileName     = $className . ".xml";
        $modelDefinitionFileName = $className . ".php";
        
        $result = array("DownloadFileName"       => $className.".zip",
                        $modelDefinitionFileName => $classImpl,
                        $modelMapXmlFileName     => $modelMapXml);
        
        return $result;
    }
    /**
     * Create the database connection object
     * @return Fynd_Db_IConnection
     *
     */
    private function _createDbConnectionObject(DatabaseModel $dbModel)
    {
        $config = new Fynd_Config_DbConnectionConfig($dbModel->getHost(), $dbModel->getPort(), $dbModel->getUser(), $dbModel->getPassword(), $dbModel->getDatabaseName(), $dbModel->getDatabaseType());
        $db = Fynd_Db_Factory::getConnection($config);
        return $db;
    }
    private function _convertTableOrFieldName($fieldName)
    {
        $parts = explode("_", $fieldName);
        if(! (is_array($parts) && count($parts) > 1))
        {
            return Fynd_StringUtil::capitalize($fieldName);
        }
        $propertyName = "";
        foreach($parts as $part)
        {
            $propertyName .= Fynd_StringUtil::capitalize($part);
        }
        return $propertyName;
    }
}
?>