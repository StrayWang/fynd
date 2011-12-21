using System;
using System.Net;
using FyndSharp.Communication.Channels;
using FyndSharp.Communication.Common;

namespace FyndSharp.Communication.Server
{
    public interface IClientDummy : IMessager
    {
        event EventHandler Disconnected;

        long Id { get; }
        IPEndPoint RemoteEndPoint { get; }
        CommunicationStatus Status { get; }

        void Disconnect();

    }
}
