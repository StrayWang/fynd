using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace FyndSharp.BusinessLogic
{
    public class DebuggingUser : IUser
    {
        public string Id { get; set; }
        public string Name { get; set; }

        public DebuggingUser()
        {
            Id = "__DEBUGGING_USER__";
            Name = "__DEBUGGING_USER__";
        }
    }
}
