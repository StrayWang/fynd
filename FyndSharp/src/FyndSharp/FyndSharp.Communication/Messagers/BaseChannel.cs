using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using FyndSharp.Communication.Common;
using FyndSharp.Communication.Protocols;
using FyndSharp.Utilities.Common;
using System.Net;

namespace FyndSharp.Communication.Messagers
{
    internal abstract class BaseChannel : IMessager
    {
        public event EventHandler<MessageEventArgs> MessageReceived;

        public event EventHandler<MessageEventArgs> MessageSent;

        public event EventHandler Disconnected;

        public DateTime LastReceivedTime { get; protected set; }

        public DateTime LastSentTime { get; protected set; }

        public IProtocol Protocol { get; set; }

        private readonly IPEndPoint _EndPoint;
        public IPEndPoint EndPoint
        {
            get
            {
                return this._EndPoint;
            }
        }

        public CommunicationStatus CommunicationState { get; protected set; }

        public BaseChannel()
        {
            this.CommunicationState = CommunicationStatus.Disconnected;
            this.LastReceivedTime = DateTime.MinValue;
            this.LastSentTime = DateTime.MinValue;
        }
        public BaseChannel(IPEndPoint theEndPoint) : this()
        {
            Checker.NotNull<IPEndPoint>(theEndPoint);
            this._EndPoint = new IPEndPoint(theEndPoint.Address, theEndPoint.Port);
        }

        public void Send(IMessage aMessage)
        {
            Checker.NotNull<IMessage>(aMessage);
            this.SendImpl(aMessage);
        }

        protected abstract void SendImpl(IMessage aMessage);

        public void Start()
        {
            this.StartImpl();
            this.CommunicationState = CommunicationStatus.Connected;
        }

        protected abstract void StartImpl();

        public void Stop()
        {
            try
            {
                this.StopImpl();
            }
            catch { }
            this.CommunicationState = CommunicationStatus.Disconnected;
        }

        protected abstract void StopImpl();

        protected virtual void OnDisconnected()
        {
            if (null != this.Disconnected)
            {
                this.Disconnected.Invoke(this, EventArgs.Empty);
            }
        }

        protected virtual void OnMessageSent(IMessage theMessage)
        {
            if (null != this.MessageSent)
            {
                this.MessageSent.Invoke(this, new MessageEventArgs(theMessage));
            }
        }

        protected virtual void OnMessageReceived(IMessage theMessage)
        {
            if (null != this.MessageReceived)
            {
                this.MessageReceived.Invoke(this, new MessageEventArgs(theMessage));
            }
        }
    }
}
