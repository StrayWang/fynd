using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace FyndSharp.Data
{
    public sealed class AdoSessionManager
    {
        private static Object _SyncRoot = new Object();
        private static Dictionary<string, DatabaseConfig> _DbConfigs;
        public static void AddConnectionString(string theName, DatabaseType theDbType, string theConnectionString)
        {
            if (null == _DbConfigs)
            {
                lock (_SyncRoot)
                {
                    if (null == _DbConfigs)
                    {
                        _DbConfigs = new Dictionary<string, DatabaseConfig>();
                    }
                }
            }
            lock (_SyncRoot)
            {
                _DbConfigs.Add(theName, new DatabaseConfig()
                {
                    DatabaseType = theDbType,
                    ConnectionString = theConnectionString
                });
            }
        }

        [ThreadStatic]
        private static AdoSessionManager _Instance;
        public static AdoSessionManager Current
        {
            get
            {
                if (null == _Instance)
                {
                    lock (_SyncRoot)
                    {
                        if (null == _Instance)
                        {
                            _Instance = new AdoSessionManager();
                        }
                    }
                }
                return _Instance;
            }
        }

        private Dictionary<string, AdoSession> _Cache = new Dictionary<string, AdoSession>();

 
        public AdoSession this[string theDatabaseName]
        {
            get
            {
                if (!this._Cache.ContainsKey(theDatabaseName))
                {
                    lock (this._Cache)
                    {
                        if (!this._Cache.ContainsKey(theDatabaseName))
                        {
                            this._Cache.Add(theDatabaseName, AdoSessionFactory.CreateAdoSession(_DbConfigs[theDatabaseName]));
                        }
                    }
                }
                return this._Cache[theDatabaseName];
            }
        }
        private AdoSessionManager()
        {
        }


    }
}
