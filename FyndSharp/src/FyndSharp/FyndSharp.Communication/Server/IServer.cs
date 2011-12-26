using System;
using FyndSharp.Utilities.Collections;
using FyndSharp.Communication.Protocols;
namespace FyndSharp.Communication.Server
{
    public interface IServer
    {
        event EventHandler<ClientDummyEventArgs> ClientConnected;
        event EventHandler<ClientDummyEventArgs> ClientDisconnected;
        SynchronizedSortedList<long, IClientDummy> ClientDummies { get; }
        IProtocolFactory ProtocolFactory { get; set; }
        void Start();
        void Stop();
    }
}
