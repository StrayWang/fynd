using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using FyndSharp.Utilities.Common;

namespace FyndSharp.Web
{
    public class PathHelper
    {
        public static string ConvertToVirtualPath (string path)
        {
            Checker.Assert<ArgumentNullException>(!String.IsNullOrEmpty(path));
            string appPath = HttpContext.Current.Request.ApplicationPath;
            if (appPath.EndsWith("/"))
            {
                return appPath + path;
            }
            return appPath + "/" + path;
        }
    }
}