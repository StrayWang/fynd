using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Web;

namespace FyndSharp.Web
{
    public abstract class BaseRestHandler : IHttpHandler, IRestHandler
    {
        public bool IsReusable
        {
            get { return false; }
        }

        public BaseRestHandler()
        {
            RestRouter.Current.ErrorOccured += new EventHandler<ErrorOccuredEventArgs>(OnRestRouterErrorOccured);
        }

        protected virtual void OnRestRouterErrorOccured(object sender, ErrorOccuredEventArgs e)
        {
            
        }

        public void ProcessRequest(HttpContext context)
        {
            this.Initialize(context);
            RestRouter.Current.Route(this, context);
        }

        protected abstract void Initialize(HttpContext context);

        public virtual RestResponse Add(HttpContext ctx)
        {
            throw new System.NotSupportedException();
        }

        public virtual RestResponse Modify(HttpContext ctx)
        {
            throw new System.NotSupportedException();
        }

        public virtual RestResponse Get(HttpContext ctx)
        {
            throw new System.NotSupportedException();
        }

        public virtual RestResponse List(HttpContext ctx)
        {
            throw new System.NotSupportedException();
        }

        public virtual RestResponse Delete(HttpContext ctx)
        {
            throw new System.NotSupportedException();
        }
    }
}
