using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace FyndSharp.Utilities.Net
{
    public class Mailer
    {
        public string SmtpServerAddress { get; set; }
        public int SmtpServerPort { get; set; }
        public string Passport { get; set; }
        public string Password { get; set; }

        public void SendMail(string from, string to, string subject, string body) { }
    }
}
