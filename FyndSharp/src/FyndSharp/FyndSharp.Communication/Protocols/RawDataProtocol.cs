using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using FyndSharp.Communication.Common;
using System.IO;

namespace FyndSharp.Communication.Protocols
{
    public class RawDataProtocol : IProtocol
    {
        public byte[] GetBytes(IMessage theMsg)
        {
            if (theMsg is TextMessage)
            {
                return Encoding.UTF8.GetBytes(((TextMessage)theMsg).Text);
            }
            else if (theMsg is RawDataMessage)
            {
                return ((RawDataMessage)theMsg).Data;
            }
            return Encoding.UTF8.GetBytes(theMsg.ToString());
        }

        public IEnumerable<IMessage> BuildMessages(byte[] theBytes)
        {
            return new IMessage[] { new RawDataMessage { Data = theBytes } };
        }

        public void Reset()
        {
            
        }
    }
}
