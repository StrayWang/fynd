using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.IO;
using FyndSharp.Utilities.Common;

namespace FyndSharp.Utilities.Serialization
{
    /// <summary>
    /// Reads the same variant prefixed string and byte[] but with a hard-limit on size
    /// </summary>
    public class LimitedSerializer : ISerializer<byte[]>, ISerializer<string>
    {
        private readonly int _maxLength;

        /// <summary>
        /// Constructs a limited length-prefix data reader/writer
        /// </summary>
        public LimitedSerializer(int maxLength)
        {
            _maxLength = maxLength;
        }

        /// <summary> Reads up to 1024 length-prefixed byte array </summary>
        public static readonly ISerializer<byte[]> Bytes1024 = new LimitedSerializer(1024);
        /// <summary> Reads up to 2048 length-prefixed byte array </summary>
        public static readonly ISerializer<byte[]> Bytes2048 = new LimitedSerializer(2048);
        /// <summary> Reads up to 4092 length-prefixed byte array </summary>
        public static readonly ISerializer<byte[]> Bytes4092 = new LimitedSerializer(4092);
        /// <summary> Reads up to 8196 length-prefixed byte array </summary>
        public static readonly ISerializer<byte[]> Bytes8196 = new LimitedSerializer(8196);

        /// <summary> Reads up to 256 length-prefixed string </summary>
        public static readonly ISerializer<string> String256 = new LimitedSerializer(256);
        /// <summary> Reads up to 512 length-prefixed string </summary>
        public static readonly ISerializer<string> String512 = new LimitedSerializer(512);
        /// <summary> Reads up to 1024 length-prefixed string </summary>
        public static readonly ISerializer<string> String1024 = new LimitedSerializer(1024);

        /// <summary> This is the only class with read/write prefixed data </summary>
        internal static readonly LimitedSerializer Unlimited = new LimitedSerializer(int.MaxValue);

        #region ISerializer<string> Members

        void ISerializer<string>.Write(string value, Stream stream)
        {
            if (value == null)
            {
                VariantNumberSerializer.Int32.Write(int.MinValue, stream);
            }
            else
            {
                Checker.Assert<InvalidDataException>(value.Length <= _maxLength);
                VariantNumberSerializer.Int32.Write(value.Length, stream);
                foreach (char ch in value)
                    VariantNumberSerializer.Int32.Write(ch, stream);
            }
        }

        string ISerializer<string>.Read(Stream stream)
        {
            unchecked
            {
                int sz = VariantNumberSerializer.Int32.Read(stream);
                if (sz == 0) return string.Empty;
                if (sz == int.MinValue)
                    return null;

                Checker.Assert<InvalidDataException>(sz >= 0 && sz <= _maxLength);
                char[] chars = new char[sz];
                for (int i = 0; i < sz; i++)
                    chars[i] = (char)VariantNumberSerializer.Int32.Read(stream);
                return new String(chars);
            }
        }

        #endregion
        #region ISerializer<byte[]> Members

        void ISerializer<byte[]>.Write(byte[] value, Stream stream)
        {
            if (value == null)
            {
                VariantNumberSerializer.Int32.Write(int.MinValue, stream);
            }
            else
            {
                Checker.Assert<InvalidDataException>(value.Length <= _maxLength);
                VariantNumberSerializer.Int32.Write(value.Length, stream);
                foreach (byte b in value)
                    stream.WriteByte(b);
            }
        }
        byte[] ISerializer<byte[]>.Read(Stream stream)
        {
            int sz = VariantNumberSerializer.Int32.Read(stream);
            if (sz == int.MinValue)
                return null;

            Checker.Assert<InvalidDataException>(sz >= 0 && sz <= _maxLength);
            byte[] bytes = new byte[sz];
            int pos = 0, len;
            while (0 != (len = stream.Read(bytes, pos, sz - pos)))
                pos += len;
            Checker.Assert<InvalidDataException>(pos == sz);
            return bytes;
        }

        #endregion
    }
}
