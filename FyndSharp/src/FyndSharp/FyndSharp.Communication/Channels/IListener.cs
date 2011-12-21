using System;
namespace FyndSharp.Communication.Channels
{
    internal interface IListener
    {
        event EventHandler<ChannelEventArgs> ChannelConnected;
        void Start();
        void Stop();
    }
}
