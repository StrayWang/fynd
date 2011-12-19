using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.IO;
using FyndSharp.Utilities.IO;

namespace FyndSharp.Utilities.Serialization
{
    public class BinarySerializer : ISerializer<byte[]>
    {
        public static readonly ISerializer<byte[]> Instance = new BinarySerializer();

        public byte[] Read(Stream stream)
        {
            return StreamHelper.ReadAllBytes(stream);
        }

        public void Write(byte[] value, Stream stream)
        {
            stream.Write(value, 0, value.Length);
        }
    }
}
