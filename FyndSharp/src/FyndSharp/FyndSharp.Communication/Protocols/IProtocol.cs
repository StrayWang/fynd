using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using FyndSharp.Communication.Common;

namespace FyndSharp.Communication.Protocols
{
    public interface IProtocol
    {
        byte[] GetBytes(IMessage theMsg);
        IEnumerable<IMessage> BuildMessages(byte[] theBytes);
        void Reset();
    }
}
