using System;
using FyndSharp.Communication.Common;
using FyndSharp.Communication.Protocols;
using FyndSharp.Utilities.Common;
using System.Net;

namespace FyndSharp.Communication.Channels
{
    internal abstract class BaseChannel : IChannel
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

        public CommunicationStatus Status { get; protected set; }

        public BaseChannel()
        {
            this.Status = CommunicationStatus.Disconnected;
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
            this.LastSentTime = DateTime.Now;
            this.FireMessageSentEvent(aMessage);
        }

        protected abstract void SendImpl(IMessage aMessage);

        public void Start()
        {
            this.StartImpl();
            this.Status = CommunicationStatus.Connected;
        }

        protected abstract void StartImpl();

        public void Disconnect()
        {
            try
            {
                this.DisconnectImpl();
                FireDisconnectedEvent();
            }
#if TRACE
            catch (Exception e)
            {

                System.Diagnostics.Trace.WriteLine(e.ToString());

            }
#else
            catch { }
#endif
            this.Status = CommunicationStatus.Disconnected;
        }

        protected abstract void DisconnectImpl();

        protected virtual void FireDisconnectedEvent()
        {
            if (null != this.Disconnected)
            {
                this.Disconnected.Invoke(this, EventArgs.Empty);
            }
        }

        protected virtual void FireMessageSentEvent(IMessage theMessage)
        {
            if (null != this.MessageSent)
            {
                this.MessageSent.Invoke(this, new MessageEventArgs(theMessage));
            }
        }

        protected virtual void FireMessageReceivedEvent(IMessage theMessage)
        {
            if (null != this.MessageReceived)
            {
                this.MessageReceived.Invoke(this, new MessageEventArgs(theMessage));
            }
        }
    }
}
