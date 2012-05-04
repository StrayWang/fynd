using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using FyndSharp.Utilities.Common;

namespace FyndSharp.Data
{
    public class AdoSessionFactory
    {
        public static AdoSession CreateAdoSession(DatabaseConfig theConfig)
        {
            Checker.Assert<ArgumentNullException>(null == theConfig);
            Checker.Assert<ArgumentException>(String.IsNullOrEmpty(theConfig.ConnectionString));
            switch (theConfig.DatabaseType)
            {
                case DatabaseType.Sqlite:
                    return new SqliteAdoSession(theConfig.ConnectionString);
                case DatabaseType.SqlServer:
                default:
                    return new SqlAdoSession(theConfig.ConnectionString);
            }
            
        }
    }
}
