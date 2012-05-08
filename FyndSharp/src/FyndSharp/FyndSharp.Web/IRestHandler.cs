using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace FyndSharp.Web
{
    public interface IRestHandler
    {
        RestResponse Add(HttpContext ctx);
        RestResponse Modify(HttpContext ctx);
        RestResponse Get(HttpContext ctx);
        RestResponse List(HttpContext ctx);
        RestResponse Delete(HttpContext ctx);
    }
}