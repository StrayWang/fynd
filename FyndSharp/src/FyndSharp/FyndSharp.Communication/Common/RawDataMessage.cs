using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace FyndSharp.Communication.Common
{
    [Serializable]
    public class RawDataMessage : BaseMessage
    {
        public byte[] Data { get; set; }

        public RawDataMessage() : base()
        {
        }
        public RawDataMessage(byte[] theData) : base()
        {
            this.Data = theData;
        }
        public RawDataMessage(string theRepliedId, byte[] theData)
            : base(theRepliedId)
        {
            this.Data = theData;
        }
    }
}
