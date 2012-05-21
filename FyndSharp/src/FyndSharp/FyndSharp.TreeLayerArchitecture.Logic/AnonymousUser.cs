using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace FyndSharp.TreeLayerArchitecture.Logic
{
    public class AnonymousUser : IUser
    {
        public string Id { get; set; }
        public string Name { get; set; }

        public AnonymousUser()
        {
            Id = "__ANONYMOUS_USER__";
            Name = "__ANONYMOUS_USER__";
        }
    }
}
