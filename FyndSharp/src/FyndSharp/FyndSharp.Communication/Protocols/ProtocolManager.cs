using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace FyndSharp.Communication.Protocols
{
    internal static class ProtocolManager
    {
        public static IProtocolFactory GetDefaultProtocolFactory()
        {
            return new BinarySerializationProtocolFactory();
        }
    }
}
