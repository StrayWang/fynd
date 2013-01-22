using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace FyndSharp.BusinessLogic
{
    public abstract class BaseLogic
    {
        public LogicContext Context { get; set; }

        public abstract void Initialize();
    }
}
