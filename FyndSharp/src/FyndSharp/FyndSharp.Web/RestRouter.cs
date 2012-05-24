using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Net;

namespace FyndSharp.Web
{
    public class ErrorOccuredEventArgs : EventArgs
    {
        public Exception Error { get; private set; }

        public ErrorOccuredEventArgs(Exception anException)
            : base()
        {
            Error = anException;
        }
    }
    public class RestRouter
    {
        public static readonly RestRouter Current = new RestRouter();

        public event EventHandler<ErrorOccuredEventArgs> ErrorOccured;

        public void Route(IRestHandler handler, HttpContext ctx)
        {
            RestResponse result = null;
            try
            {
                if (ctx.Request.HttpMethod.Equals("GET", StringComparison.OrdinalIgnoreCase)
                    || ctx.Request.HttpMethod.Equals("HEAD", StringComparison.OrdinalIgnoreCase))
                {
                    if (ctx.Request.RawUrl.IndexOf("?list", StringComparison.OrdinalIgnoreCase) >= 0)
                    {
                        result = handler.List(ctx);
                    }
                    else
                    {
                        result = handler.Get(ctx);
                    }
                }
                else if (ctx.Request.HttpMethod.Equals("POST", StringComparison.OrdinalIgnoreCase))
                {
                    result = handler.Add(ctx);
                }
                else if (ctx.Request.HttpMethod.Equals("PUT", StringComparison.OrdinalIgnoreCase))
                {
                    result = handler.Modify(ctx);
                }
                else if (ctx.Request.HttpMethod.Equals("DELETE", StringComparison.OrdinalIgnoreCase))
                {
                    result = handler.Delete(ctx);
                }
                else
                {
                    ctx.Response.StatusCode = (int)HttpStatusCode.MethodNotAllowed;
                }
                if (null != result)
                {
                    if (result.Success)
                    {
                        if (!String.IsNullOrEmpty(result.MimeType))
                        {
                            ctx.Response.ContentType = result.MimeType;
                        }
                        if (!String.IsNullOrEmpty(result.Data))
                        {
                            ctx.Response.Write(result.Data);
                        }
                    }
                    else
                    {
                        ctx.Response.StatusCode = (int)HttpStatusCode.InternalServerError;
                        ctx.Response.Write(result.ErrorMessage);
                    }
                }
                else
                {
                    ctx.Response.StatusCode = (int)HttpStatusCode.InternalServerError;
                }
            }
            catch (Exception e)
            {

                if (null != this.ErrorOccured)
                {
                    try
                    {
                        this.ErrorOccured(this, new ErrorOccuredEventArgs(e));
                    }
                    catch
                    {

                    }
                }
                ctx.Response.StatusCode = (int)HttpStatusCode.InternalServerError;
                ctx.Response.Write(e.Message);
                ctx.Response.Write("\r\n");
            }
        }
    }
}