using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using Amib.Threading;

namespace FyndSharp.Utilities.Threading
{
    /// <summary>
    /// This class is used to process items sequentially in a multithreaded manner.
    /// </summary>
    /// <typeparam name="T">Type of item to process</typeparam>
    public class SequentialItemProcessor<T>
    {
        /// <summary>
        /// The method delegate that is called to actually process items.
        /// </summary>
        private readonly Action<T> _ProcessMethod;

        /// <summary>
        /// Item queue. Used to process items sequentially.
        /// </summary>
        private readonly Queue<T> _Queue;

        /// <summary>
        /// Indicates state of the item processing.
        /// </summary>
        private bool _IsProcessing;

        /// <summary>
        /// A boolean value to control running of SequentialItemProcessor.
        /// </summary>
        private bool _IsRunning;

        /// <summary>
        /// An object to synchronize threads.
        /// </summary>
        private readonly Object _SyncObject = new Object();

        private readonly SmartThreadPool _ThreadPool;

        private IWorkItemResult _CurrentWork;

        /// <summary>
        /// Creates a new SequentialItemProcessor object.
        /// </summary>
        /// <param name="processMethod">The method delegate that is called to actually process items</param>
        public SequentialItemProcessor(Action<T> processMethod)
        {
            _ProcessMethod = processMethod;
            _Queue = new Queue<T>();
            STPStartInfo theStpStartInfo= new STPStartInfo();
            theStpStartInfo.MinWorkerThreads = 1;
            theStpStartInfo.MaxWorkerThreads = 1;
            _ThreadPool = new SmartThreadPool(theStpStartInfo);
        }

        /// <summary>
        /// Adds an item to queue to process the item.
        /// </summary>
        /// <param name="item">Item to add to the queue</param>
        public void EnqueueMessage(T item)
        {
            //Add the item to the queue and start a new Task if needed
            lock (_SyncObject)
            {
                if (!_IsRunning)
                {
                    return;
                }

                _Queue.Enqueue(item);

                if (!_IsProcessing)
                {
                    _CurrentWork = _ThreadPool.QueueWorkItem(new Amib.Threading.Action(ProcessItem));
                    //_currentProcessTask = Task.Factory.StartNew(ProcessItem);
                }
            }
        }

        /// <summary>
        /// Starts processing of items.
        /// </summary>
        public void Start()
        {
            _IsRunning = true;
        }

        /// <summary>
        /// Stops processing of items and waits stopping of current item.
        /// </summary>
        public void Stop()
        {
            _IsRunning = false;

            //Clear all incoming messages
            lock (_SyncObject)
            {
                _Queue.Clear();
            }

            //Check if is there a message that is being processed now
            if (!_IsProcessing)
            {
                return;
            }

            //Wait current processing task to finish
            try
            {
                _CurrentWork.GetResult();
            }
            catch
            {

            }
        }

        /// <summary>
        /// This method runs on a new seperated Task (thread) to process
        /// items on the queue.
        /// </summary>
        private void ProcessItem()
        {
            //Try to get an item from queue to process it.
            T itemToProcess;
            lock (_SyncObject)
            {
                if (!_IsRunning || _IsProcessing)
                {
                    return;
                }

                if (_Queue.Count <= 0)
                {
                    return;
                }

                _IsProcessing = true;
                itemToProcess = _Queue.Dequeue();
            }

            //Process the item (by calling the _processMethod delegate)
            _ProcessMethod(itemToProcess);

            //Process next item if available
            lock (_SyncObject)
            {
                _IsProcessing = false;
                if (!_IsRunning || _Queue.Count <= 0)
                {
                    return;
                }

                //Start a new task
                _CurrentWork = _ThreadPool.QueueWorkItem(new Amib.Threading.Action(ProcessItem));
            }
        }
    }
}
