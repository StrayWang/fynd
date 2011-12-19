using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.IO;
using FyndSharp.Utilities.Common;

namespace FyndSharp.Utilities.Serialization
{
    /// <summary>
    /// Provides numeric serializers for packed int/long values.
    /// </summary>
    public class VariantNumberSerializer :
        ISerializer<int>,
        ISerializer<uint>,
        ISerializer<long>,
        ISerializer<ulong>
    {
        /// <summary> Gets a singleton of the VariantNumberSerializer </summary>
        public static readonly VariantNumberSerializer Instance = new VariantNumberSerializer();
        /// <summary> Gets a typed version of the VariantNumberSerializer </summary>
        public static readonly ISerializer<int> Int32 = Instance;
        /// <summary> Gets a typed version of the VariantNumberSerializer </summary>
        public static readonly ISerializer<uint> UInt32 = Instance;
        /// <summary> Gets a typed version of the VariantNumberSerializer </summary>
        public static readonly ISerializer<long> Int64 = Instance;
        /// <summary> Gets a typed version of the VariantNumberSerializer </summary>
        public static readonly ISerializer<ulong> UInt64 = Instance;

        #region ISerializer<int> Members

        void ISerializer<int>.Write(int value, Stream stream)
        {
            ((ISerializer<uint>)this).Write(unchecked((uint)value), stream);
        }

        int ISerializer<int>.Read(Stream stream)
        {
            return unchecked((int)((ISerializer<uint>)this).Read(stream));
        }

        #endregion
        #region ISerializer<uint> Members

        void ISerializer<uint>.Write(uint value, Stream stream)
        {
            unchecked
            {
                while (value > 0x7F)
                {
                    stream.WriteByte((byte)(value | 0x80));
                    value >>= 7;
                }
                stream.WriteByte((byte)value);
            }
        }

        uint ISerializer<uint>.Read(Stream stream)
        {
            const uint mask = 0x7f;
            int last;
            uint value = 0;
            int shift = 0;
            do
            {
                last = stream.ReadByte();
                Checker.Assert<InvalidDataException>(last != -1);

                value = (value & ~(mask << shift)) + ((uint)last << shift);
                shift += 7;
            } while ((last & 0x080) != 0);
            return value;
        }

        #endregion
        #region ISerializer<long> Members

        void ISerializer<long>.Write(long value, Stream stream)
        {
            ((ISerializer<ulong>)this).Write(unchecked((ulong)value), stream);
        }

        long ISerializer<long>.Read(Stream stream)
        {
            return unchecked((long)((ISerializer<ulong>)this).Read(stream));
        }

        #endregion
        #region ISerializer<ulong> Members

        /// <summary> Writes the object to the stream </summary>
        void ISerializer<ulong>.Write(ulong value, Stream stream)
        {
            unchecked
            {
                while (value > 0x7F)
                {
                    stream.WriteByte((byte)(value | 0x80));
                    value >>= 7;
                }
                stream.WriteByte((byte)value);
            }
        }

        /// <summary> Reads the object from a stream </summary>
        ulong ISerializer<ulong>.Read(Stream stream)
        {
            const ulong mask = 0x7f;
            int last;
            ulong value = 0;
            int shift = 0;
            do
            {
                last = stream.ReadByte();
                Checker.Assert<InvalidDataException>(last != -1);

                value = (value & ~(mask << shift)) + ((ulong)last << shift);
                shift += 7;
            } while ((last & 0x080) != 0);
            return value;
        }

        #endregion
    }
}
