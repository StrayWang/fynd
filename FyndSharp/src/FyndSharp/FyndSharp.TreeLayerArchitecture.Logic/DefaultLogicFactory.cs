using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using FyndSharp.Utilities.Common;
using FyndSharp.Data;

namespace FyndSharp.TreeLayerArchitecture.Logic
{
    public class DefaultLogicFactory : ILogicFactory
    {
        public T CreateLogic<T>(IUser theUser) where T : BaseLogic
        {
            Checker.Assert<ArgumentNullException>(null != theUser);

            T obj = default(T);
            Type theType = typeof(T);
            obj = typeof(T).Assembly.CreateInstance(theType.FullName) as T;
            obj.Context = new LogicContext();
            obj.Context.User = theUser;
            obj.Context.AdoSessionManager = AdoSessionManager.Current;
            obj.Initialize();
            return obj;
        }
    }
}
