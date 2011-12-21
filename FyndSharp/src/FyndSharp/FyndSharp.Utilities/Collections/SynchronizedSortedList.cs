using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading;

namespace FyndSharp.Utilities.Collections
{
    /// <summary>
    /// This class is used to store key-value based items in a thread safe manner.
    /// It uses System.Collections.Generic.SortedList internally.
    /// </summary>
    /// <typeparam name="TKey">Key type</typeparam>
    /// <typeparam name="TValue">Value type</typeparam>
    public class SynchronizedSortedList<TKey, TValue>
    {
        /// <summary>
        /// Internal collection to store items.
        /// </summary>
        protected readonly SortedList<TKey, TValue> _Items;

        /// <summary>
        /// Used to synchronize access to _items list.
        /// </summary>
        protected readonly ReaderWriterLockSlim _Lock;

        /// <summary>
        /// Gets/adds/replaces an item by key.
        /// </summary>
        /// <param name="key">Key to get/set value</param>
        /// <returns>Item associated with this key</returns>
        public TValue this[TKey key]
        {
            get
            {
                _Lock.EnterReadLock();
                try
                {
                    return _Items.ContainsKey(key) ? _Items[key] : default(TValue);
                }
                finally
                {
                    _Lock.ExitReadLock();
                }
            }

            set
            {
                _Lock.EnterWriteLock();
                try
                {
                    _Items[key] = value;
                }
                finally
                {
                    _Lock.ExitWriteLock();
                }
            }
        }

        /// <summary>
        /// Gets count of items in the collection.
        /// </summary>
        public int Count
        {
            get
            {
                _Lock.EnterReadLock();
                try
                {
                    return _Items.Count;
                }
                finally
                {
                    _Lock.ExitReadLock();
                }
            }
        }

        /// <summary>
        /// Gets all items in collection.
        /// </summary>
        /// <returns>Item list</returns>
        public List<TValue> Values
        {
            get
            {
                _Lock.EnterReadLock();
                try
                {
                    return new List<TValue>(_Items.Values);
                }
                finally
                {
                    _Lock.ExitReadLock();
                }
            }
        }

        

        /// <summary>
        /// Creates a new ThreadSafeSortedList object.
        /// </summary>
        public SynchronizedSortedList()
        {
            _Items = new SortedList<TKey, TValue>();
            _Lock = new ReaderWriterLockSlim(LockRecursionPolicy.NoRecursion);
        }

        /// <summary>
        /// Checks if collection contains spesified key.
        /// </summary>
        /// <param name="key">Key to check</param>
        /// <returns>True; if collection contains given key</returns>
        public bool ContainsKey(TKey key)
        {
            _Lock.EnterReadLock();
            try
            {
                return _Items.ContainsKey(key);
            }
            finally
            {
                _Lock.ExitReadLock();
            }
        }

        /// <summary>
        /// Checks if collection contains spesified item.
        /// </summary>
        /// <param name="item">Item to check</param>
        /// <returns>True; if collection contains given item</returns>
        public bool ContainsValue(TValue item)
        {
            _Lock.EnterReadLock();
            try
            {
                return _Items.ContainsValue(item);
            }
            finally
            {
                _Lock.ExitReadLock();
            }
        }

        /// <summary>
        /// Removes an item from collection.
        /// </summary>
        /// <param name="key">Key of item to remove</param>
        public bool Remove(TKey key)
        {
            _Lock.EnterWriteLock();
            try
            {
                if (!_Items.ContainsKey(key))
                {
                    return false;
                }

                _Items.Remove(key);
                return true;
            }
            finally
            {
                _Lock.ExitWriteLock();
            }
        }

        

        /// <summary>
        /// Removes all items from list.
        /// </summary>
        public void Clear()
        {
            _Lock.EnterWriteLock();
            try
            {
                _Items.Clear();
            }
            finally
            {
                _Lock.ExitWriteLock();
            }
        }

        /// <summary>
        /// Gets then removes all items in collection.
        /// </summary>
        /// <returns>Item list</returns>
        public List<TValue> GetAndClearAllItems()
        {
            _Lock.EnterWriteLock();
            try
            {
                var list = new List<TValue>(_Items.Values);
                _Items.Clear();
                return list;
            }
            finally
            {
                _Lock.ExitWriteLock();
            }
        }
    }
}
