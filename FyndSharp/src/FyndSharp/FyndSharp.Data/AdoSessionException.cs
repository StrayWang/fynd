using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace FyndSharp.Data
{
    public class AdoSessionException : Exception
    {
        public AdoSessionException()
            : base()
        {

        }

        public AdoSessionException(string msg)
            : base(msg)
        {
        }

        public AdoSessionException(string msg, Exception inner)
            : base(msg, inner)
        {
        }
    }
}
