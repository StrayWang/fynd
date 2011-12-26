using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace FyndSharp.Communication.Common
{
    [Serializable]
    public class PingMessage : BaseMessage
    {
        public PingMessage() : base()
        {
        }

        public PingMessage(string theRepliedId)
            : base(theRepliedId)
        {
            
        }
    }
}
