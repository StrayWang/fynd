using System;
using FyndSharp.Communication.Channels;
using System.Net;
using FyndSharp.Utilities.Common;
using System.Net.Sockets;

namespace FyndSharp.Communication.Clients
{
    public class TcpClient : BaseClient
    {
        private readonly IPEndPoint _ServerEndPoint;

        public TcpClient(IPEndPoint theServerEndPoint)
            : base()
        {
            this._ServerEndPoint = theServerEndPoint;
        }

        protected override IChannel CreateCommunicationChannel()
        {
            return new TcpChannel(ConnectToEndPoint(this._ServerEndPoint, this.ConnectTimeout));
        }

        internal static Socket ConnectToEndPoint(IPEndPoint theEndPoint, int theTimeout)
        {
            Checker.NotNull<IPEndPoint>(theEndPoint);
            Socket theSocket = new Socket(AddressFamily.InterNetwork, SocketType.Stream, ProtocolType.Tcp);
            try
            {
                theSocket.Blocking = false;
                theSocket.Connect(theEndPoint);
                theSocket.Blocking = true;
                return theSocket;
            }
            catch (SocketException socketException)
            {
                if (socketException.ErrorCode != 10035)
                {
                    theSocket.Close();
                    throw;
                }

                if (!theSocket.Poll(theTimeout * 1000, SelectMode.SelectWrite))
                {
                    theSocket.Close();
                    throw new TimeoutException("The host failed to connect. Timeout occured.");
                }

                theSocket.Blocking = true;
                return theSocket;
            }
        }
    }
}
