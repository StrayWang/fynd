using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.IO;

namespace FyndSharp.Utilities.IO
{
    public static class StreamHelper
    {
        public static byte[] ReadAllBytes(Stream io)
        {
            using (MemoryStream ms = new MemoryStream())
            {
                CopyStream(io, ms);
                return ms.ToArray();
            }
        }

        public static long CopyStream(Stream input, Stream output)
        {
            return CopyStream(input, output, long.MaxValue);
        }

        public static long CopyStream(Stream input, Stream output, long stopAfter)
        {
            byte[] bytes = new byte[ushort.MaxValue];
            long bytesRead = 0;
            int len = 0;
            while (0 != (len = input.Read(bytes, 0, Math.Min(bytes.Length, (int)Math.Min(int.MaxValue, stopAfter - bytesRead)))))
            {
                output.Write(bytes, 0, len);
                bytesRead = bytesRead + len;
            }
            output.Flush();
            return bytesRead;
        }
    }
}
