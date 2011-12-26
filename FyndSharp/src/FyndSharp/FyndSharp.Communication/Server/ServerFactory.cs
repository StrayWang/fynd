using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Net;

namespace FyndSharp.Communication.Server
{
    public static class ServerFactory
    {
        public static IServer CreateServer(IPEndPoint theEndPoint)
        {
            return new TcpServer(theEndPoint);
        }
    }
}
