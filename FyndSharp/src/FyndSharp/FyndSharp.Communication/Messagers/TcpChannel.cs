using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using FyndSharp.Communication.Common;
using System.Net.Sockets;
using FyndSharp.Utilities.Common;
using System.Net;

namespace FyndSharp.Communication.Messagers
{
    internal class TcpChannel : BaseChannel
    {
        private const int ReceivingBufferSize = 4096;//4KB
        private readonly byte[] _ReceivingBuffer;
        private readonly Socket _ClientSocket;
        private readonly Object _LockObject;

        private volatile bool _IsRunning;

        public TcpChannel(Socket theClientSocket)
            : base(theClientSocket == null ? null : (IPEndPoint)theClientSocket.RemoteEndPoint)
        {
            Checker.NotNull<Socket>(theClientSocket);

            this._ReceivingBuffer = new byte[ReceivingBufferSize];
            this._LockObject = new Object();
        }

        protected override void SendImpl(IMessage aMessage)
        {
            throw new NotImplementedException();
        }

        protected override void StartImpl()
        {
            this._IsRunning = true;
            this._ClientSocket.BeginReceive(this._ReceivingBuffer
                , 0
                , this._ReceivingBuffer.Length
                , 0
                , new AsyncCallback(ReceiveCallback)
                , null);
        }

        protected override void StopImpl()
        {
            if (this.CommunicationState != CommunicationStatus.Connected)
            {
                return;
            }

            this._IsRunning = false;
            try
            {
                this._ClientSocket.Close();
            }
            catch { }
        }


        private void ReceiveCallback(IAsyncResult ar)
        {
            if (!this._IsRunning)
            {
                return;
            }

            try
            {
                //Get received bytes count
                var bytesRead = this._ClientSocket.EndReceive(ar);
                if (bytesRead > 0)
                {
                    this.LastReceivedTime = DateTime.Now;

                    //Copy received bytes to a new byte array
                    var receivedBytes = new byte[bytesRead];
                    Array.Copy(this._ReceivingBuffer, 0, receivedBytes, 0, bytesRead);

                    //Read messages according to current wire protocol
                    var messages = Protocol.BuildMessages(receivedBytes);

                    //Raise MessageReceived event for all received messages
                    foreach (var message in messages)
                    {
                        OnMessageReceived(message);
                    }
                }
                else
                {
                    //TODO: 使用特定的异常类型
                    throw new Exception("Tcp socket is closed");
                }

                //Read more bytes if still running
                if (this._IsRunning)
                {
                    this._ClientSocket.BeginReceive(this._ReceivingBuffer
                        , 0
                        , this._ReceivingBuffer.Length
                        , 0
                        , new AsyncCallback(ReceiveCallback)
                        , null);
                }
            }
            catch
            {
                this.Stop();
            }
        }
    }
}
