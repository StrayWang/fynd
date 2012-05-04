using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace FyndSharp.Data
{
    public class DatabaseConfig
    {
        public string ConnectionString { get; set; }
        public DatabaseType DatabaseType { get; set; }
    }
}
