using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace FyndSharp.TreeLayerArchitecture.Logic
{
    public interface ILogicFactory
    {
        T CreateLogic<T>(IUser theUser) where T : BaseLogic;
    }
}
