using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace FyndSharp.Communication.Common
{
    [Serializable]
    public class TextMessage : BaseMessage
    {
        public string Text { get; set; }

        public TextMessage() : base()
        {
        }
        public TextMessage(string theData)
            : base()
        {
            this.Text = theData;
        }
        public TextMessage(string theRepliedId, string theText)
            : base(theRepliedId)
        {
            this.Text = theText;
        }
    }
}
