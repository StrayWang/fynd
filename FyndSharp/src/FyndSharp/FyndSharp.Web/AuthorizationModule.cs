using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Web;

namespace FyndSharp.Web
{
    public class AuthorizationModule : IHttpModule
    {
        public void Dispose()
        {
            throw new NotImplementedException();
        }

        public void Init(HttpApplication context)
        {
            context.PreRequestHandlerExecute += new EventHandler(OnPreRequestHandlerExecute);
        }

        protected virtual void OnPreRequestHandlerExecute(object sender, EventArgs e)
        {
            throw new NotImplementedException();
        }
    }
}
