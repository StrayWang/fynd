using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace FyndSharp.Communication.Common
{
    public interface IMessage
    {
        string Id { get; set; }
        string RepliedId { get; set; }
    }
}
