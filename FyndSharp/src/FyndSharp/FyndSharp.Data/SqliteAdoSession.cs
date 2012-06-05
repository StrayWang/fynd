using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Data.Common;
using System.Data.SQLite;
using System.Data;

namespace FyndSharp.Data
{
    class SqliteAdoSession : AdoSession
    {
        public SqliteAdoSession(string theConnectionString)
            : base(theConnectionString)
        {
        }
        protected override DbConnection CreateDbConnection()
        {
            return new SQLiteConnection();
        }

        protected override DbCommand CreateDbCommand()
        {
            return new SQLiteCommand();
        }

        protected override DbParameter CreateDbParameter()
        {
            return new SQLiteParameter();
        }

        protected override IDataAdapter CreateDataAdapter(DbCommand cmd)
        {
            return new SQLiteDataAdapter((SQLiteCommand)cmd);
        }

        public override string GetLastAutoIncrementValueSql()
        {
            throw new System.NotSupportedException();
        }
    }
}
