using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace FyndSharp.Communication.Protocols
{
    public interface IProtocolFactory
    {
        IProtocol CreateProtocol();
    }
}
