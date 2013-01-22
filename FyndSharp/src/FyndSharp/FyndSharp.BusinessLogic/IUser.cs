using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace FyndSharp.BusinessLogic
{
    public interface IUser
    {
        string Id { get; set; }
        string Name { get; set; }
    }

    public class DebugUser : IUser
    {
        public string Id { get; set; }
        public string Name { get; set; }
    }
}
