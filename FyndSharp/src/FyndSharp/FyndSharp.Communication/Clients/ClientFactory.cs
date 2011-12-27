using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Net;

namespace FyndSharp.Communication.Clients
{
    public static class ClientFactory
    {
        public static IClient CreateClient(IPEndPoint theServerEndPoint)
        {
            return new TcpClient(theServerEndPoint);
        }

    }
}
