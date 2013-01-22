using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace FyndSharp.BusinessLogic
{
    public interface ILogicFactory
    {
        T CreateLogic<T>(IUser theUser) where T : BaseLogic;
        T CreateLogic<T>(BaseLogic theParent) where T : BaseLogic;
    }
}
