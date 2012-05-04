using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Data.Common;
using System.Data.SqlClient;
using System.Data;

namespace FyndSharp.Data
{
    class SqlAdoSession : AdoSession
    {
        public SqlAdoSession(string theConnectionString)
            : base(theConnectionString)
        {
        }
        protected override DbConnection CreateDbConnection()
        {
            return new SqlConnection();
        }

        protected override DbCommand CreateDbCommand()
        {
            return new SqlCommand();
        }

        protected override DbParameter CreateDbParameter()
        {
            return new SqlParameter();
        }

        protected override IDataAdapter CreateDataAdapter(DbCommand cmd)
        {
            return new SqlDataAdapter((SqlCommand)cmd);
        }
    }
}
