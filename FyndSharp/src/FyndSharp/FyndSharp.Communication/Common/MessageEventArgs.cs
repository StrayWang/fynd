using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace FyndSharp.Communication.Common
{
    public class MessageEventArgs : EventArgs
    {
        public IMessage Message { get; private set; }

        public MessageEventArgs(IMessage theMsg)
        {
            this.Message = theMsg;
        }
    }
}
