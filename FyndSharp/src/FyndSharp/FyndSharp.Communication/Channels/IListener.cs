using System;
namespace FyndSharp.Communication.Channels
{
    public interface IListener
    {
        event EventHandler<ChannelEventArgs> ChannelConnected;
        void Start();
        void Stop();
    }
}
