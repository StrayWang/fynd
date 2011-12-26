using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using FyndSharp.Communication.Common;

namespace FyndSharp.Communication.Clients
{
    public interface IClient
    {
        /// <summary>
        /// This event is raised when client connected to server.
        /// </summary>
        event EventHandler Connected;

        /// <summary>
        /// This event is raised when client disconnected from server.
        /// </summary>
        event EventHandler Disconnected;

        /// <summary>
        /// Timeout for connecting to a server (as milliseconds).
        /// Default value: 15 seconds (15000 ms).
        /// </summary>
        int ConnectTimeout { get; set; }

        /// <summary>
        /// Gets the current communication state.
        /// </summary>
        CommunicationStatus CommunicationStatus { get; }

        /// <summary>
        /// Connects to server.
        /// </summary>
        void Connect();

        /// <summary>
        /// Disconnects from server.
        /// Does nothing if already disconnected.
        /// </summary>
        void Disconnect();
    }
}
