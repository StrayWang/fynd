using System;
using FyndSharp.Communication.Common;
using System.Net;
using FyndSharp.Communication.Protocols;
namespace FyndSharp.Communication.Channels
{
    public interface IChannel : IMessager
    {
        event EventHandler Disconnected;

        CommunicationStatus Status { get; }
        IPEndPoint EndPoint { get; }

        void Start();
        void Disconnect();
    }
}
