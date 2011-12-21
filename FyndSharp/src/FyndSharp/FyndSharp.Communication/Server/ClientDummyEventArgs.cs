using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace FyndSharp.Communication.Server
{
    public class ClientDummyEventArgs : EventArgs
    {
        public IClientDummy Client { get; private set; }

        public ClientDummyEventArgs(IClientDummy theClientDummy)
        {
            this.Client = theClientDummy;
        }
    }
}
