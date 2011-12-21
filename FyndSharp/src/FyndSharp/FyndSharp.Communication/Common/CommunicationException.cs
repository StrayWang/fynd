using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Runtime.Serialization;

namespace FyndSharp.Communication.Common
{
    [Serializable]
    public class CommunicationException : Exception
    {
        /// <summary>
        /// Contstructor.
        /// </summary>
        public CommunicationException()
        {

        }

        /// <summary>
        /// Contstructor for serializing.
        /// </summary>
        public CommunicationException(SerializationInfo serializationInfo, StreamingContext context)
            : base(serializationInfo, context)
        {

        }

        /// <summary>
        /// Contstructor.
        /// </summary>
        /// <param name="message">Exception message</param>
        public CommunicationException(string message)
            : base(message)
        {

        }

        /// <summary>
        /// Contstructor.
        /// </summary>
        /// <param name="message">Exception message</param>
        /// <param name="innerException">Inner exception</param>
        public CommunicationException(string message, Exception innerException)
            : base(message, innerException)
        {

        }
    }
}
