using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using FyndSharp.Communication.Common;
using System.Threading;

namespace FyndSharp.Communication.Channels
{
    /// <summary>
    /// This class is a wrapper for IMessager and is used
    /// to synchronize message receiving operation.
    /// It extends RequestReplyMessenger.
    /// It is suitable to use in applications those want to receive
    /// messages by synchronized method calls instead of asynchronous 
    /// MessageReceived event.
    /// </summary>
    public class SynchronizedMessager<T> : RequestReplyMessager<T> where T : IMessager
    {
        #region Public properties

        ///<summary>
        /// Gets/sets capacity of the incoming message queue.
        /// No message is received from remote application if
        /// number of messages in internal queue exceeds this value.
        /// Default value: int.MaxValue (2147483647).
        ///</summary>
        public int IncomingMessageQueueCapacity { get; set; }

        #endregion

        #region Private fields

        /// <summary>
        /// A queue that is used to store receiving messages until Receive(...)
        /// method is called to get them.
        /// </summary>
        private readonly Queue<IMessage> _ReceivingMessageQueue;

        /// <summary>
        /// This object is used to synchronize/wait threads.
        /// </summary>
        private readonly ManualResetEvent _ReceiveWaitEvent;

        /// <summary>
        /// This boolean value indicates the running state of this class.
        /// </summary>
        private volatile bool _IsRunning;

        #endregion

        #region Constructors

        ///<summary>
        /// Creates a new SynchronizedMessenger object.
        ///</summary>
        ///<param name="aMessager">A IMessager object to be used to send/receive messages</param>
        public SynchronizedMessager(T aMessager)
            : this(aMessager, int.MaxValue)
        {

        }

        ///<summary>
        /// Creates a new SynchronizedMessenger object.
        ///</summary>
        ///<param name="aMessager">A IMessager object to be used to send/receive messages</param>
        ///<param name="theIncomingMessageQueueCapacity">capacity of the incoming message queue</param>
        public SynchronizedMessager(T aMessager, int theIncomingMessageQueueCapacity)
            : base(aMessager)
        {
            _ReceiveWaitEvent = new ManualResetEvent(false);
            _ReceivingMessageQueue = new Queue<IMessage>();
            IncomingMessageQueueCapacity = theIncomingMessageQueueCapacity;
        }

        #endregion

        #region Public methods

        /// <summary>
        /// Starts the messenger.
        /// </summary>
        public override void Start()
        {
            lock (_ReceivingMessageQueue)
            {
                _IsRunning = true;
            }

            base.Start();
        }

        /// <summary>
        /// Stops the messenger.
        /// </summary>
        public override void Stop()
        {
            base.Stop();

            lock (_ReceivingMessageQueue)
            {
                _IsRunning = false;
                _ReceiveWaitEvent.Set();
            }
        }

        /// <summary>
        /// This method is used to receive a message from remote application.
        /// It waits until a message is received.
        /// </summary>
        /// <returns>Received message</returns>
        public IMessage ReceiveMessage()
        {
            return ReceiveMessage(System.Threading.Timeout.Infinite);
        }

        /// <summary>
        /// This method is used to receive a message from remote application.
        /// It waits until a message is received or timeout occurs.
        /// </summary>
        /// <param name="theTimeout">
        /// Timeout value to wait if no message is received.
        /// Use -1 to wait indefinitely.
        /// </param>
        /// <returns>Received message</returns>
        /// <exception cref="TimeoutException">Throws TimeoutException if timeout occurs</exception>
        /// <exception cref="Exception">Throws Exception if SynchronizedMessenger stops before a message is received</exception>
        public IMessage ReceiveMessage(int theTimeout)
        {
            while (_IsRunning)
            {
                lock (_ReceivingMessageQueue)
                {
                    //Check if SynchronizedMessenger is running
                    if (!_IsRunning)
                    {
                        throw new Exception("SynchronizedMessenger is stopped. Can not receive message.");
                    }

                    //Get a message immediately if any message does exists
                    if (_ReceivingMessageQueue.Count > 0)
                    {
                        return _ReceivingMessageQueue.Dequeue();
                    }

                    _ReceiveWaitEvent.Reset();
                }

                //Wait for a message
                bool signalled = _ReceiveWaitEvent.WaitOne(theTimeout);

                //If not signalled, throw exception
                if (!signalled)
                {
                    throw new TimeoutException("Timeout occured. Can not received any message");
                }
            }

            throw new Exception("SynchronizedMessenger is stopped. Can not receive message.");
        }

        /// <summary>
        /// This method is used to receive a specific type of message from remote application.
        /// It waits until a message is received.
        /// </summary>
        /// <returns>Received message</returns>
        public TMessage ReceiveMessage<TMessage>() where TMessage : IMessage
        {
            return ReceiveMessage<TMessage>(System.Threading.Timeout.Infinite);
        }

        /// <summary>
        /// This method is used to receive a specific type of message from remote application.
        /// It waits until a message is received or timeout occurs.
        /// </summary>
        /// <param name="theTimeout">
        /// Timeout value to wait if no message is received.
        /// Use -1 to wait indefinitely.
        /// </param>
        /// <returns>Received message</returns>
        public TMessage ReceiveMessage<TMessage>(int theTimeout) where TMessage : IMessage
        {
            var receivedMessage = ReceiveMessage(theTimeout);
            if (!(receivedMessage is TMessage))
            {
                throw new Exception("Unexpected message received." +
                                    " Expected type: " + typeof(TMessage).Name +
                                    ". Received message type: " + receivedMessage.GetType().Name);
            }

            return (TMessage)receivedMessage;
        }

        #endregion

        #region Protected methods

        /// <summary>
        /// Overrides
        /// </summary>
        /// <param name="theMessage"></param>
        protected override void FireMessageReceivedEvent(IMessage theMessage)
        {
            lock (_ReceivingMessageQueue)
            {
                if (_ReceivingMessageQueue.Count < IncomingMessageQueueCapacity)
                {
                    _ReceivingMessageQueue.Enqueue(theMessage);
                }

                _ReceiveWaitEvent.Set();
            }
        }
        
        #endregion
    }
}
