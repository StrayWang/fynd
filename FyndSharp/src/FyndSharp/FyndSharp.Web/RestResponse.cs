using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace FyndSharp.Web
{
    public class RestResponse
    {
        public bool Success;
        public string ErrorMessage;
        public string MimeType { get; set; }
        public string Data { get; set; }
    }
}