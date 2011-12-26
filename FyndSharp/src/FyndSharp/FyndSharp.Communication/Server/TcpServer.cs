using FyndSharp.Communication.Channels;
using System.Net;
using FyndSharp.Utilities.Common;

namespace FyndSharp.Communication.Server
{
    internal class TcpServer : BaseServer
    {
        private readonly IPEndPoint _EndPoint;

        public TcpServer(IPEndPoint theEndPoint)
            : base()
        {
            Checker.NotNull<IPEndPoint>(theEndPoint);
            this._EndPoint = theEndPoint;
        }
        protected override IListener CreateListener()
        {
            return new TcpConnectionListener(this._EndPoint);
        }
    }
}
