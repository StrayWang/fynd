using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using FyndSharp.Communication.Channels;
using FyndSharp.Communication.Common;
using FyndSharp.Communication.Protocols;
using System.Threading;

namespace FyndSharp.Communication.Clients
{
    internal abstract class BaseClient : IClient
    {
        /// <summary>
        /// Default timeout value for connecting a server.
        /// </summary>
        private const int DefaultConnectionAttemptTimeout = 15000; //15 seconds.

        /// <summary>
        /// The communication channel that is used by client to send and receive messages.
        /// </summary>
        private IChannel _CommunicationChannel;

        /// <summary>
        /// This timer is used to send PingMessage messages to server periodically.
        /// </summary>
        private readonly Timer _PingTimer;

        private volatile bool _IsDisposed;


        /// <summary>
        /// This event is raised when a new message is received.
        /// </summary>
        public event EventHandler<MessageEventArgs> MessageReceived;

        /// <summary>
        /// This event is raised when a new message is sent without any error.
        /// It does not guaranties that message is properly handled and processed by remote application.
        /// </summary>
        public event EventHandler<MessageEventArgs> MessageSent;

        /// <summary>
        /// This event is raised when communication channel closed.
        /// </summary>
        public event EventHandler Connected;

        /// <summary>
        /// This event is raised when client disconnected from server.
        /// </summary>
        public event EventHandler Disconnected;




        /// <summary>
        /// Timeout for connecting to a server (as milliseconds).
        /// Default value: 15 seconds (15000 ms).
        /// </summary>
        public int ConnectTimeout { get; set; }

        private IProtocol _Protocol;
        /// <summary>
        /// Gets/sets wire protocol that is used while reading and writing messages.
        /// </summary>
        public IProtocol Protocol
        {
            get { return _Protocol; }
            set
            {
                if (CommunicationStatus == CommunicationStatus.Connected)
                {
                    throw new ApplicationException("The protocol can not be changed while connected to server.");
                }

                _Protocol = value;
            }
        }


        /// <summary>
        /// Gets the communication state of the Client.
        /// </summary>
        public CommunicationStatus CommunicationStatus
        {
            get
            {
                return _CommunicationChannel != null
                           ? _CommunicationChannel.Status
                           : CommunicationStatus.Disconnected;
            }
        }

        /// <summary>
        /// Gets the time of the last succesfully received message.
        /// </summary>
        public DateTime LastReceivedTime
        {
            get
            {
                return _CommunicationChannel != null
                           ? _CommunicationChannel.LastReceivedTime
                           : DateTime.MinValue;
            }
        }

        /// <summary>
        /// Gets the time of the last succesfully received message.
        /// </summary>
        public DateTime LastSentTime
        {
            get
            {
                return _CommunicationChannel != null
                           ? _CommunicationChannel.LastSentTime
                           : DateTime.MinValue;
            }
        }


        /// <summary>
        /// Constructor.
        /// </summary>
        protected BaseClient()
        {
            ConnectTimeout = DefaultConnectionAttemptTimeout;
            Protocol = ProtocolManager.GetDefaultProtocolFactory().CreateProtocol();
            _PingTimer = new Timer(new TimerCallback(HandlePingTimeCallback), null, 30000, 30000);
        }
        ~BaseClient()
        {
            Dispose(false);
        }



        /// <summary>
        /// Connects to server.
        /// </summary>
        public void Connect()
        {
            Protocol.Reset();
            _CommunicationChannel = CreateCommunicationChannel();
            _CommunicationChannel.Protocol = Protocol;
            _CommunicationChannel.Disconnected += CommunicationChannel_Disconnected;
            _CommunicationChannel.MessageReceived += CommunicationChannel_MessageReceived;
            _CommunicationChannel.MessageSent += CommunicationChannel_MessageSent;
            _CommunicationChannel.Start();
            _PingTimer.Change(0, 30000);
            FireConnectedEvent();
        }

        /// <summary>
        /// Disconnects from server.
        /// Does nothing if already disconnected.
        /// </summary>
        public void Disconnect()
        {
            if (CommunicationStatus != CommunicationStatus.Connected)
            {
                return;
            }

            _CommunicationChannel.Disconnect();
        }
        
        /// <summary>
        /// Sends a message to the server.
        /// </summary>
        /// <param name="aMessage">Message to be sent</param>
        /// <exception cref="CommunicationStateException">Throws a CommunicationStateException if client is not connected to the server.</exception>
        public void Send(IMessage aMessage)
        {
            if (CommunicationStatus != CommunicationStatus.Connected)
            {
                throw new CommunicationException("Client is not connected to the server.");
            }

            _CommunicationChannel.Send(aMessage);
        }



        /// <summary>
        /// This method is implemented by derived classes to create appropriate communication channel.
        /// </summary>
        /// <returns>Ready communication channel to communicate</returns>
        protected abstract IChannel CreateCommunicationChannel();



        /// <summary>
        /// Handles MessageReceived event of _communicationChannel object.
        /// </summary>
        /// <param name="sender">Source of event</param>
        /// <param name="e">Event arguments</param>
        private void CommunicationChannel_MessageReceived(object sender, MessageEventArgs e)
        {
            if (e.Message is PingMessage)
            {
                return;
            }

            FireMessageReceivedEvent(e.Message);
        }

        /// <summary>
        /// Handles MessageSent event of _communicationChannel object.
        /// </summary>
        /// <param name="sender">Source of event</param>
        /// <param name="e">Event arguments</param>
        private void CommunicationChannel_MessageSent(object sender, MessageEventArgs e)
        {
            FireMessageSentEvent(e.Message);
        }

        /// <summary>
        /// Handles Disconnected event of _communicationChannel object.
        /// </summary>
        /// <param name="sender">Source of event</param>
        /// <param name="e">Event arguments</param>
        private void CommunicationChannel_Disconnected(object sender, EventArgs e)
        {
            _PingTimer.Change(System.Threading.Timeout.Infinite, System.Threading.Timeout.Infinite);
            FireDisconnectedEvent();
        }


        private void HandlePingTimeCallback(object state)
        {
            if (_IsDisposed || CommunicationStatus != CommunicationStatus.Connected)
            {
                return;
            }

            try
            {
                DateTime lastMinute = DateTime.Now.AddMinutes(-1);
                if (_CommunicationChannel.LastReceivedTime > lastMinute || _CommunicationChannel.LastSentTime > lastMinute)
                {
                    return;
                }

                _CommunicationChannel.Send(new PingMessage());
            }
#if TRACE
            catch (Exception e)
            {

                System.Diagnostics.Trace.WriteLine(e.ToString());

            }
#else
            catch { }
#endif
        }



        /// <summary>
        /// Raises Connected event.
        /// </summary>
        protected virtual void FireConnectedEvent()
        {
            if (Connected != null)
            {
                Connected.Invoke(this, EventArgs.Empty);
            }
        }

        /// <summary>
        /// Raises Disconnected event.
        /// </summary>
        protected virtual void FireDisconnectedEvent()
        {
            if (Disconnected != null)
            {
                Disconnected.Invoke(this, EventArgs.Empty);
            }
        }

        /// <summary>
        /// Raises MessageReceived event.
        /// </summary>
        /// <param name="theMessage">Received message</param>
        protected virtual void FireMessageReceivedEvent(IMessage theMessage)
        {
            if (MessageReceived != null)
            {
                MessageReceived.Invoke(this, new MessageEventArgs(theMessage));
            }
        }

        /// <summary>
        /// Raises MessageSent event.
        /// </summary>
        /// <param name="theMessage">Received message</param>
        protected virtual void FireMessageSentEvent(IMessage theMessage)
        {
            if (MessageSent != null)
            {
                MessageSent.Invoke(this, new MessageEventArgs(theMessage));
            }
        }

        /// <summary>
        /// Disposes this object and closes underlying connection.
        /// </summary>
        public void Dispose()
        {
            try
            {
                Dispose(true);
            }
#if TRACE
            catch (Exception e)
            {

                System.Diagnostics.Trace.WriteLine(e.ToString());

            }
#else
            catch { }
#endif
            _IsDisposed = true;
            GC.SuppressFinalize(this);
        }

        protected virtual void Dispose(bool disposing)
        {
            Disconnect();
        }

    }
}
