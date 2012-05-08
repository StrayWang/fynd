using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using FyndSharp.Data;

namespace FyndSharp.TreeLayerArchitecture.Logic
{
    public class LogicContext : IDictionary<object, object>
    {
        public IUser User { get; set; }

        public AdoSessionManager AdoSessionManager { get; set; }

        private IDictionary<object, object> _InnerDict = new Dictionary<object, object>();

        public void Add(object key, object value)
        {
            _InnerDict.Add(key, value);
        }

        public bool ContainsKey(object key)
        {
            return _InnerDict.ContainsKey(key);
        }

        public ICollection<object> Keys
        {
            get { return _InnerDict.Keys; }
        }

        public bool Remove(object key)
        {
            return _InnerDict.Remove(key);
        }

        public bool TryGetValue(object key, out object value)
        {
            return _InnerDict.TryGetValue(key, out value);
        }

        public ICollection<object> Values
        {
            get { return _InnerDict.Values; }
        }

        public object this[object key]
        {
            get
            {
                return _InnerDict[key];
            }
            set
            {
                _InnerDict[key] = value;
            }
        }

        public void Add(KeyValuePair<object, object> item)
        {
            _InnerDict.Add(item);
        }

        public void Clear()
        {
            _InnerDict.Clear();
        }

        public bool Contains(KeyValuePair<object, object> item)
        {
            return _InnerDict.Contains(item);
        }

        public void CopyTo(KeyValuePair<object, object>[] array, int arrayIndex)
        {
            _InnerDict.CopyTo(array, arrayIndex);
        }

        public int Count
        {
            get { return _InnerDict.Count; }
        }

        public bool IsReadOnly
        {
            get { return _InnerDict.IsReadOnly; }
        }

        public bool Remove(KeyValuePair<object, object> item)
        {
            return _InnerDict.Remove(item);
        }

        public IEnumerator<KeyValuePair<object, object>> GetEnumerator()
        {
            return _InnerDict.GetEnumerator();
        }

        System.Collections.IEnumerator System.Collections.IEnumerable.GetEnumerator()
        {
            return _InnerDict.GetEnumerator();
        }
    }
}
