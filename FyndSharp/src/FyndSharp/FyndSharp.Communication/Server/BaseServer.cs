using System;
using FyndSharp.Communication.Channels;
using FyndSharp.Utilities.Collections;
using FyndSharp.Communication.Protocols;
using System.Threading;

namespace FyndSharp.Communication.Server
{
    internal abstract class BaseServer : IServer
    {
        /// <summary>
        /// Used to set an auto incremential unique identifier to clients.
        /// </summary>
        private static long _LastClientId;
        /// <summary>
        /// Gets an unique number to be used as idenfitier of a client.
        /// </summary>
        /// <returns></returns>
        public static long GetClientId()
        {
            return Interlocked.Increment(ref _LastClientId);
        }

        public event EventHandler<ClientDummyEventArgs> ClientConnected;
        public event EventHandler<ClientDummyEventArgs> ClientDisconnected;

        private IListener _Listener;

        public SynchronizedSortedList<long, IClientDummy> ClientDummies { get; private set; }
        public IProtocolFactory ProtocolFactory { get; set; }

        public BaseServer()
        {
            this.ClientDummies = new SynchronizedSortedList<long, IClientDummy>();
            //TODO: Set the default protocol factory object
            this.ProtocolFactory = ProtocolManager.GetDefaultProtocolFactory();
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
            IClientDummy clientDummy = new ClientDummy(GetClientId(), e.Channel);
            clientDummy.Protocol = this.ProtocolFactory.CreateProtocol();
            clientDummy.Disconnected += new EventHandler(Client_Disconnected);
            this.ClientDummies[clientDummy.Id] = clientDummy;

            FireClientConnectedEvent(clientDummy);
            e.Channel.Start();
        }

        private void Client_Disconnected(object sender, EventArgs e)
        {
            IClientDummy client = (IClientDummy)sender;
            ClientDummies.Remove(client.Id);
            FireClientDisconnectedEvent(client);
        }

        private void FireClientConnectedEvent(IClientDummy theClient)
        {
            if (null != this.ClientConnected)
            {
                this.ClientConnected.Invoke(this, new ClientDummyEventArgs(theClient));
            }
        }

        private void FireClientDisconnectedEvent(IClientDummy theClient)
        {
            if (null != this.ClientDisconnected)
            {
                this.ClientDisconnected.Invoke(this, new ClientDummyEventArgs(theClient));
            }
        }

        
    }
}
