using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using FyndSharp.Communication.Channels;
using FyndSharp.Communication.Common;
using FyndSharp.Communication.Protocols;
using System.Net;
using FyndSharp.Utilities.Common;

namespace FyndSharp.Communication.Server
{
    internal class ClientDummy : IClientDummy
    {
        private readonly IChannel _InternalChannel;

        public event EventHandler Disconnected;

        public event EventHandler<MessageEventArgs> MessageReceived;

        public event EventHandler<MessageEventArgs> MessageSent;

        public DateTime LastReceivedTime { get; protected set; }

        public DateTime LastSentTime { get; protected set; }

        public IProtocol Protocol
        {
            get
            {
                return _InternalChannel.Protocol;
            }
            set
            {
                _InternalChannel.Protocol = value;
            }
        }

        public long Id { get; private set; }

        public CommunicationStatus Status { get; protected set; }

        public IPEndPoint RemoteEndPoint { get; private set; }



        public ClientDummy(long theId, IChannel theChannel)
        {
            this.Id = theId;
            this._InternalChannel = theChannel;
            this._InternalChannel.MessageReceived += new EventHandler<MessageEventArgs>(InternalChannel_MessageReceived);
            this._InternalChannel.MessageSent += new EventHandler<MessageEventArgs>(InternalChannel_MessageSent);
            this._InternalChannel.Disconnected += new EventHandler(InternalChannel_Disconnected);
        }

        private void InternalChannel_Disconnected(object sender, EventArgs e)
        {
            this.FireDisconnectedEvent();
        }

        private void InternalChannel_MessageSent(object sender, MessageEventArgs e)
        {
            this.FireMessageSentEvent(e.Message);
        }

        private void InternalChannel_MessageReceived(object sender, MessageEventArgs e)
        {
            var theMessage = e.Message;
            if (theMessage is PingMessage)
            {
                this._InternalChannel.Send(new PingMessage(theMessage.Id));
                return;
            }
            this.FireMessageReceivedEvent(theMessage);
        }

        public void Disconnect()
        {
            if (null != this._InternalChannel)
            {
                this._InternalChannel.Disconnect();
            }
        }

        public void Send(IMessage aMessage)
        {
            if (null != this._InternalChannel)
            {
                this._InternalChannel.Send(aMessage);
            }
        }

        protected virtual void FireDisconnectedEvent()
        {
            if (null != this.Disconnected)
            {
                this.Disconnected.Invoke(this, EventArgs.Empty);
            }
        }

        protected virtual void FireMessageReceivedEvent(IMessage theMessage)
        {
            if (null != this.MessageReceived)
            {
                this.MessageReceived.Invoke(this, new MessageEventArgs(theMessage));
            }
        }

        protected virtual void FireMessageSentEvent(IMessage theMessage)
        {
            if (null != this.MessageSent)
            {
                this.MessageSent.Invoke(this, new MessageEventArgs(theMessage));
            }
        }
        
    }
}
