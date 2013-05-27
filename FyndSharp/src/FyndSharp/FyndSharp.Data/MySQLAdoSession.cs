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
            return "SELECT LAST_INSERT_ID();";
        }
    }
}
