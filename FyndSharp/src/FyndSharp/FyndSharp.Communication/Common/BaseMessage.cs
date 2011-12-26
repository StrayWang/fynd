using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace FyndSharp.Communication.Common
{
    [Serializable]
    public class BaseMessage : IMessage
    {
        public string Id { get; set; }

        public string RepliedId { get; set; }

        public BaseMessage()
        {
            this.Id = Guid.NewGuid().ToString();
        }

        public BaseMessage(string theRepliedId)
            : this()
        {
            this.RepliedId = theRepliedId;
        }
    }


}
