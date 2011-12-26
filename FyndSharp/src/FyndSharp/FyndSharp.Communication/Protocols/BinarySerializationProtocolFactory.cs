using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace FyndSharp.Communication.Protocols
{
    internal class BinarySerializationProtocolFactory : IProtocolFactory
    {
        public IProtocol CreateProtocol()
        {
            return new BinarySerializationProtocol();
        }
    }
}
