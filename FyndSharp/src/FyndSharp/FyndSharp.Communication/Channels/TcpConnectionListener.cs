using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Net;
using System.Threading;
using FyndSharp.Utilities.Common;
using System.Net.Sockets;

namespace FyndSharp.Communication.Channels
{
    internal class TcpConnectionListener : BaseListener
    {
        /// <summary>
        /// The endpoint address of the server to listen incoming connections.
        /// </summary>
        private readonly IPEndPoint _EndPoint;

        /// <summary>
        /// Server socket to listen incoming connection requests.
        /// </summary>
        private TcpListener _ListenerSocket;

        /// <summary>
        /// The thread to listen socket
        /// </summary>
        private Thread _Thread;

        /// <summary>
        /// A flag to control thread's running
        /// </summary>
        private volatile bool _IsRunning;

        /// <summary>
        /// Creates a new TcpConnectionListener for given endpoint.
        /// </summary>
        /// <param name="theEndPoint">The endpoint address of the server to listen incoming connections</param>
        public TcpConnectionListener(IPEndPoint theEndPoint)
        {
            Checker.NotNull<IPEndPoint>(theEndPoint);
            this._EndPoint = theEndPoint;
        }
        public override void Start()
        {
            this.StartSocket();
            this._IsRunning = true;
            this._Thread = new Thread(this.AcceptConnection);
            this._Thread.Start();
        }

        public override void Stop()
        {
            this._IsRunning = false;
            this.StopSocket();
        }

        /// <summary>
        /// Starts listening socket.
        /// </summary>
        private void StartSocket()
        {
            this._ListenerSocket = new TcpListener(this._EndPoint);
            this._ListenerSocket.Start();
        }

        /// <summary>
        /// Stops listening socket.
        /// </summary>
        private void StopSocket()
        {
            try
            {
                this._ListenerSocket.Stop();
            }
            catch { }
        }

        /// <summary>
        /// Entrance point of the thread.
        /// This method is used by the thread to listen incoming requests.
        /// </summary>
        private void AcceptConnection()
        {
            while (this._IsRunning)
            {
                try
                {
                    Socket aClientSocket = this._ListenerSocket.AcceptSocket();
                    if (aClientSocket.Connected)
                    {
                        this.FireChannelConnectedEvent(new TcpChannel(aClientSocket));
                    }
                }
                catch
                {
                    //Disconnect, wait for a while and connect again.
                    this.StopSocket();
                    Thread.Sleep(1000);
                    if (!this._IsRunning)
                    {
                        return;
                    }

                    try
                    {
                        this.StartSocket();
                    }
                    catch { }
                }
            }
        }
    }
}
