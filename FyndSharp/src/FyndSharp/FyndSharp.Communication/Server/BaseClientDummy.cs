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
    internal abstract class BaseClientDummy : IClientDummy
    {
        private readonly IChannel _InternalChannel;

        public event EventHandler Disconnected;

        public event EventHandler<MessageEventArgs> MessageReceived;

        public event EventHandler<MessageEventArgs> MessageSent;

        public DateTime LastReceivedTime { get; protected set; }

        public DateTime LastSentTime { get; protected set; }

        public IProtocol Protocol { get; set; }

        public long Id { get; private set; }

        public CommunicationStatus Status { get; protected set; }

        public IPEndPoint RemoteEndPoint { get; private set; }



        public BaseClientDummy(long theId)
        {
            this.Id = theId;
        }

        private void InternalChannel_Disconnected(object sender, EventArgs e)
        {
            throw new NotImplementedException();
        }

        public abstract void Disconnect();

        public abstract void Send(IMessage aMessage);


        
    }
}
