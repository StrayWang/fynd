using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using MySql.Data.MySqlClient;

namespace FyndSharp.Data
{
    class MySQLAdoSession : AdoSession
    {
        public MySQLAdoSession(string theConnectionString)
            : base(theConnectionString)
        {
        }

        protected override System.Data.Common.DbConnection CreateDbConnection()
        {
            return new MySqlConnection();
        }

        protected override System.Data.Common.DbCommand CreateDbCommand()
        {
            return new MySqlCommand();
        }

        protected override System.Data.Common.DbParameter CreateDbParameter()
        {
            return new MySqlParameter();
        }

        protected override System.Data.IDataAdapter CreateDataAdapter(System.Data.Common.DbCommand cmd)
        {
            return new MySqlDataAdapter((MySqlCommand)cmd);
        }

        public override string GetLastAutoIncrementValueSql()
        {
            // MySQL 中的SQL语句以分号结尾，但SQL Server中的分号的含义与MySQL不同，所以在这个SQL语句前加一个分号，可以保证MySQL执行时不会出错。
            return ";SELECT LAST_INSERT_ID();";
        }
    }
}
