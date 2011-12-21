using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace FyndSharp.Communication.Channels
{
    /// <summary>
    /// Stores communication channel information to be used by an event.
    /// </summary>
    internal class ChannelEventArgs : EventArgs
    {
        /// <summary>
        /// Communication channel that is associated with this event.
        /// </summary>
        public IChannel Channel { get; private set; }

        /// <summary>
        /// Creates a new CommunicationChannelEventArgs object.
        /// </summary>
        /// <param name="channel">Communication channel that is associated with this event</param>
        public ChannelEventArgs(IChannel theChannel)
        {
            this.Channel = theChannel;
        }
    }
}
