using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using FyndSharp.Communication.Channels;
using FyndSharp.Utilities.Collections;
using FyndSharp.Communication.Protocols;

namespace FyndSharp.Communication.Server
{
    internal abstract class BaseServer
    {
        public event EventHandler<ClientDummyEventArgs> ClientConnected;
        public event EventHandler<ClientDummyEventArgs> ClientDisconnected;

        private IListener _Listener;

        public SynchronizedSortedList<long, IClientDummy> ClientChannels { get; private set; }
        public IProtocolFactory ProtocolFactory { get; set; }

        public BaseServer()
        {
            this.ClientChannels = new SynchronizedSortedList<long, IClientDummy>();
            //TODO: Set the default protocol factory object
        }

        public virtual void Start()
        {
            this._Listener = this.CreateListener();
            this._Listener.ChannelConnected += new EventHandler<ChannelEventArgs>(Listener_ChannelConnected);
            this._Listener.Start();
        }

        public virtual void Stop()
        {
            if (null != this._Listener)
            {
                this._Listener.Stop();
            }
        }

        protected abstract IListener CreateListener();

        private void Listener_ChannelConnected(object sender, ChannelEventArgs e)
        {
            //e.Channel.Disconnected += new EventHandler(Channel_Disconnected);
            //this.ClientChannels[e.Channel.Id] = e.Channel;
            
            //OnClientConnected(client);
            //e.Channel.Start();
        }

        private void FireClientConnectedEvent(IClientDummy theClient)
        {
            if (null != this.ClientConnected)
            {
                this.ClientConnected.Invoke(this, new ClientDummyEventArgs(theClient));
            }
        }

        private void FireClientDisconnected(IClientDummy theClient)
        {
            if (null != this.ClientDisconnected)
            {
                this.ClientDisconnected.Invoke(this, new ClientDummyEventArgs(theClient));
            }
        }
    }
}
