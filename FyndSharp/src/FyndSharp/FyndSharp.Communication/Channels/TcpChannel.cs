using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using FyndSharp.Communication.Common;
using System.Net.Sockets;
using FyndSharp.Utilities.Common;
using System.Net;

namespace FyndSharp.Communication.Channels
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
            //Send message
            int totalSent = 0;
            lock (this._LockObject)
            {
                //Create a byte array from message according to current protocol
                byte[] messageBytes = this.Protocol.GetBytes(aMessage);
                //Send all bytes to the remote application
                while (totalSent < messageBytes.Length)
                {
                    int sent = this._ClientSocket.Send(messageBytes, totalSent, messageBytes.Length - totalSent, SocketFlags.None);
                    if (sent <= 0)
                    {
                        throw new CommunicationException("Message could not be sent via TCP socket. Only " + totalSent + " bytes of " + messageBytes.Length + " bytes are sent.");
                    }

                    totalSent += sent;
                }
            }
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

        protected override void DisconnectImpl()
        {
            if (this.Status != CommunicationStatus.Connected)
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
                    throw new CommunicationException("Tcp socket is closed");
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
                this.Disconnect();
            }
        }
    }
}
