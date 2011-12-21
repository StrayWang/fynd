using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using FyndSharp.Communication.Protocols;
using FyndSharp.Communication.Common;

namespace FyndSharp.Communication.Channels
{
    public interface IMessager
    {
        event EventHandler<MessageEventArgs> MessageReceived;
        event EventHandler<MessageEventArgs> MessageSent;

        DateTime LastReceivedTime { get; }
        DateTime LastSentTime { get; }
        IProtocol Protocol { get; set; }

        void Send(IMessage aMessage);

    }
}
