using System;
using System.IO;
using FyndSharp.Utilities.Common;

namespace FyndSharp.Utilities.Serialization
{
    public class BaseTypeSerializer :
        ISerializer<string>,
        ISerializer<bool>,
        ISerializer<byte>,
        ISerializer<sbyte>,
        ISerializer<byte[]>,
        ISerializer<char>,
        ISerializer<DateTime>,
        ISerializer<TimeSpan>,
        ISerializer<short>,
        ISerializer<ushort>,
        ISerializer<int>,
        ISerializer<uint>,
        ISerializer<long>,
        ISerializer<ulong>,
        ISerializer<double>,
        ISerializer<float>,
        ISerializer<Guid>,
        ISerializer<IntPtr>,
        ISerializer<UIntPtr>
    {
        #region Singleton
        /// <summary> Gets a singleton of the PrimitiveSerializer </summary>
        public static readonly BaseTypeSerializer Instance = new BaseTypeSerializer();
        /// <summary> Gets a typed version of the PrimitiveSerializer </summary>
        public static readonly ISerializer<string> String = LimitedSerializer.Unlimited;
        /// <summary> Gets a typed version of the PrimitiveSerializer </summary>
        public static readonly ISerializer<bool> Boolean = Instance;
        /// <summary> Gets a typed version of the PrimitiveSerializer </summary>
        public static readonly ISerializer<byte> Byte = Instance;
        /// <summary> Gets a typed version of the PrimitiveSerializer </summary>
        public static readonly ISerializer<sbyte> SByte = Instance;
        /// <summary> Gets a typed version of the PrimitiveSerializer </summary>
        public static readonly ISerializer<byte[]> Bytes = LimitedSerializer.Unlimited;
        /// <summary> Gets a typed version of the PrimitiveSerializer </summary>
        public static readonly ISerializer<char> Char = Instance;
        /// <summary> Gets a typed version of the PrimitiveSerializer </summary>
        public static readonly ISerializer<DateTime> DateTime = Instance;
        /// <summary> Gets a typed version of the PrimitiveSerializer </summary>
        public static readonly ISerializer<TimeSpan> TimeSpan = Instance;
        /// <summary> Gets a typed version of the PrimitiveSerializer </summary>
        public static readonly ISerializer<short> Int16 = Instance;
        /// <summary> Gets a typed version of the PrimitiveSerializer </summary>
        public static readonly ISerializer<ushort> UInt16 = Instance;
        /// <summary> Gets a typed version of the PrimitiveSerializer </summary>
        public static readonly ISerializer<int> Int32 = Instance;
        /// <summary> Gets a typed version of the PrimitiveSerializer </summary>
        public static readonly ISerializer<uint> UInt32 = Instance;
        /// <summary> Gets a typed version of the PrimitiveSerializer </summary>
        public static readonly ISerializer<long> Int64 = Instance;
        /// <summary> Gets a typed version of the PrimitiveSerializer </summary>
        public static readonly ISerializer<ulong> UInt64 = Instance;
        /// <summary> Gets a typed version of the PrimitiveSerializer </summary>
        public static readonly ISerializer<double> Double = Instance;
        /// <summary> Gets a typed version of the PrimitiveSerializer </summary>
        public static readonly ISerializer<float> Float = Instance;
        /// <summary> Gets a typed version of the PrimitiveSerializer </summary>
        public static readonly ISerializer<Guid> Guid = Instance;
        /// <summary> Gets a typed version of the PrimitiveSerializer </summary>
        public static readonly ISerializer<IntPtr> IntPtr = Instance;
        /// <summary> Gets a typed version of the PrimitiveSerializer </summary>
        public static readonly ISerializer<UIntPtr> UIntPtr = Instance;
        #endregion  

        #region ISerializer<string>

        void ISerializer<string>.Write(string value, Stream stream)
        {
            String.Write(value, stream);
        }

        string ISerializer<string>.Read(Stream stream)
        {
            return String.Read(stream);
        }

        #endregion
        #region ISerializer<bool>

        void ISerializer<bool>.Write(bool value, Stream stream)
        {
            const byte bTrue = 1;
            const byte bFalse = 0;
            stream.WriteByte(value ? bTrue : bFalse);
        }

        bool ISerializer<bool>.Read(Stream stream)
        {
            int result = stream.ReadByte();
            Checker.Assert<InvalidDataException>(result != -1);
            return result == 1;
        }

        #endregion
        #region ISerializer<byte>

        void ISerializer<byte>.Write(byte value, Stream stream)
        {
            stream.WriteByte(value);
        }

        byte ISerializer<byte>.Read(Stream stream)
        {
            int result = stream.ReadByte();
            Checker.Assert<InvalidDataException>(result != -1);
            return unchecked((byte)result);
        }

        #endregion
        #region ISerializer<sbyte>

        void ISerializer<sbyte>.Write(sbyte value, Stream stream)
        {
            stream.WriteByte(unchecked((byte)value));
        }

        sbyte ISerializer<sbyte>.Read(Stream stream)
        {
            int result = stream.ReadByte();
            Checker.Assert<InvalidDataException>(result != -1);
            return unchecked((sbyte)result);
        }

        #endregion
        #region ISerializer<byte[]>

        void ISerializer<byte[]>.Write(byte[] value, Stream stream)
        {
            Bytes.Write(value, stream);
        }
        byte[] ISerializer<byte[]>.Read(Stream stream)
        {
            return Bytes.Read(stream);
        }

        #endregion
        #region ISerializer<char>

        void ISerializer<char>.Write(char value, Stream stream)
        {
            VariantNumberSerializer.Int32.Write(value, stream);
        }

        char ISerializer<char>.Read(Stream stream)
        {
            return unchecked((char)VariantNumberSerializer.Int32.Read(stream));
        }

        #endregion
        #region ISerializer<DateTime>

        void ISerializer<DateTime>.Write(DateTime value, Stream stream)
        {
            ((ISerializer<long>)this).Write(value.ToBinary(), stream);
        }

        DateTime ISerializer<DateTime>.Read(Stream stream)
        {
            return System.DateTime.FromBinary(((ISerializer<long>)this).Read(stream));
        }

        #endregion
        #region ISerializer<TimeSpan>

        void ISerializer<TimeSpan>.Write(TimeSpan value, Stream stream)
        {
            ((ISerializer<long>)this).Write(value.Ticks, stream);
        }

        TimeSpan ISerializer<TimeSpan>.Read(Stream stream)
        {
            return new TimeSpan(((ISerializer<long>)this).Read(stream));
        }

        #endregion
        #region ISerializer<short>

        void ISerializer<short>.Write(short value, Stream stream)
        {
            ((ISerializer<ushort>)this).Write(unchecked((ushort)value), stream);
        }

        short ISerializer<short>.Read(Stream stream)
        {
            return unchecked((short)((ISerializer<ushort>)this).Read(stream));
        }

        #endregion
        #region ISerializer<ushort>

        void ISerializer<ushort>.Write(ushort value, Stream stream)
        {
            unchecked
            {
                stream.WriteByte((byte)(value >> 8));
                stream.WriteByte((byte)value);
            }
        }

        ushort ISerializer<ushort>.Read(Stream stream)
        {
            unchecked
            {
                int b1 = stream.ReadByte();
                int b2 = stream.ReadByte();
                Checker.Assert<InvalidDataException>(b2 != -1);
                return (ushort)((b1 << 8) | b2);
            }
        }

        #endregion
        #region ISerializer<int>

        void ISerializer<int>.Write(int value, Stream stream)
        {
            ((ISerializer<uint>)this).Write(unchecked((uint)value), stream);
        }

        int ISerializer<int>.Read(Stream stream)
        {
            return unchecked((int)((ISerializer<uint>)this).Read(stream));
        }

        #endregion
        #region ISerializer<uint>

        void ISerializer<uint>.Write(uint value, Stream stream)
        {
            unchecked
            {
                stream.WriteByte((byte)(value >> 24));
                stream.WriteByte((byte)(value >> 16));
                stream.WriteByte((byte)(value >> 8));
                stream.WriteByte((byte)value);
            }
        }

        uint ISerializer<uint>.Read(Stream stream)
        {
            unchecked
            {
                int b1 = stream.ReadByte();
                int b2 = stream.ReadByte();
                int b3 = stream.ReadByte();
                int b4 = stream.ReadByte();

                Checker.Assert<InvalidDataException>(b4 != -1);
                return (
                    (((uint)b1) << 24) |
                    (((uint)b2) << 16) |
                    (((uint)b3) << 8) |
                    (((uint)b4) << 0)
                    );
            }
        }

        #endregion
        #region ISerializer<long>

        void ISerializer<long>.Write(long value, Stream stream)
        {
            ((ISerializer<ulong>)this).Write(unchecked((ulong)value), stream);
        }

        long ISerializer<long>.Read(Stream stream)
        {
            return unchecked((long)((ISerializer<ulong>)this).Read(stream));
        }

        #endregion
        #region ISerializer<ulong>

        void ISerializer<ulong>.Write(ulong value, Stream stream)
        {
            unchecked
            {
                stream.WriteByte((byte)(value >> 56));
                stream.WriteByte((byte)(value >> 48));
                stream.WriteByte((byte)(value >> 40));
                stream.WriteByte((byte)(value >> 32));
                stream.WriteByte((byte)(value >> 24));
                stream.WriteByte((byte)(value >> 16));
                stream.WriteByte((byte)(value >> 8));
                stream.WriteByte((byte)value);
            }
        }

        ulong ISerializer<ulong>.Read(Stream stream)
        {
            unchecked
            {
                int b1 = stream.ReadByte();
                int b2 = stream.ReadByte();
                int b3 = stream.ReadByte();
                int b4 = stream.ReadByte();
                int b5 = stream.ReadByte();
                int b6 = stream.ReadByte();
                int b7 = stream.ReadByte();
                int b8 = stream.ReadByte();
                Checker.Assert<InvalidDataException>(b8 != -1);
                return (
                    (((ulong)b1) << 56) |
                    (((ulong)b2) << 48) |
                    (((ulong)b3) << 40) |
                    (((ulong)b4) << 32) |
                    (((ulong)b5) << 24) |
                    (((ulong)b6) << 16) |
                    (((ulong)b7) << 8) |
                    (((ulong)b8) << 0)
                    );
            }
        }

        #endregion
        #region ISerializer<double>

        void ISerializer<double>.Write(double value, Stream stream)
        {
            ((ISerializer<long>)this).Write(BitConverter.DoubleToInt64Bits(value), stream);
        }

        double ISerializer<double>.Read(Stream stream)
        {
            return BitConverter.Int64BitsToDouble(((ISerializer<long>)this).Read(stream));
        }

        #endregion
        #region ISerializer<float>

        void ISerializer<float>.Write(float value, Stream stream)
        {
            ((ISerializer<long>)this).Write(BitConverter.DoubleToInt64Bits(value), stream);
        }

        float ISerializer<float>.Read(Stream stream)
        {
            return unchecked((float)BitConverter.Int64BitsToDouble(((ISerializer<long>)this).Read(stream)));
        }

        #endregion
        #region ISerializer<Guid>

        void ISerializer<Guid>.Write(Guid value, Stream stream)
        {
            stream.Write(value.ToByteArray(), 0, 16);
        }

        Guid ISerializer<Guid>.Read(Stream stream)
        {
            byte[] tmp = new byte[16];

            int len, bytesRead = 0;
            while (bytesRead < 16 && 0 != (len = stream.Read(tmp, bytesRead, 16 - bytesRead)))
                bytesRead += len;

            Checker.Assert<InvalidDataException>(16 == bytesRead);
            return new Guid(tmp);
        }

        #endregion
        #region ISerializer<IntPtr>

        void ISerializer<IntPtr>.Write(IntPtr value, Stream stream)
        {
            ((ISerializer<long>)this).Write(value.ToInt64(), stream);
        }

        IntPtr ISerializer<IntPtr>.Read(Stream stream)
        {
            return new IntPtr(((ISerializer<long>)this).Read(stream));
        }

        #endregion
        #region ISerializer<UIntPtr>

        void ISerializer<UIntPtr>.Write(UIntPtr value, Stream stream)
        {
            ((ISerializer<ulong>)this).Write(value.ToUInt64(), stream);
        }

        UIntPtr ISerializer<UIntPtr>.Read(Stream stream)
        {
            return new UIntPtr(((ISerializer<ulong>)this).Read(stream));
        }

        #endregion
    }
}
