<?php
final class Fynd_Db_Type
{
    const MYSQL_STR       = "MySQL";
    const ORACLE_STR      = "Oracle";
    const MSSQLSERVER_STR = "MSSQLServer";
    const SQLITE_STR      = "SQLite";
    
    const MYSQL       = 1;
    const ORACLE      = 2;
    const MSSQLSERVER = 3;
    const SQLITE      = 4;
    
    private function __construct()
    {}
}
?>