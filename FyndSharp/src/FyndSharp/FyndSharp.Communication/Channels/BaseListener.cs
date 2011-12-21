using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace FyndSharp.Communication.Channels
{
    /// <summary>
    /// Represents a communication listener.
    /// A connection listener is used to accept incoming client connection requests.
    /// </summary>
    internal abstract class BaseListener : IListener
    {
        /// <summary>
        /// This event is raised when a new communication channel connected.
        /// </summary>
        public event EventHandler<ChannelEventArgs> ChannelConnected;

        /// <summary>
        /// Starts listening incoming connections.
        /// </summary>
        public abstract void Start();

        /// <summary>
        /// Stops listening incoming connections.
        /// </summary>
        public abstract void Stop();

        /// <summary>
        /// Raises CommunicationChannelConnected event.
        /// </summary>
        /// <param name="client"></param>
        protected virtual void FireChannelConnectedEvent(BaseChannel theChannel)
        {
            if (this.ChannelConnected != null)
            {
                this.ChannelConnected.Invoke(this, new ChannelEventArgs(theChannel));
            }
        }
    }
}
