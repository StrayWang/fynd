using System;
using System.Collections.Generic;
using System.Linq;
using FyndSharp.Communication.Common;
using System.IO;
using System.Runtime.Serialization.Formatters.Binary;
using FyndSharp.Utilities.Serialization;
using System.Runtime.Serialization;
using FyndSharp.Utilities.IO;

namespace FyndSharp.Communication.Protocols
{
    internal class BinarySerializationProtocol : IProtocol
    {
        /// <summary>
        /// Maximum length of a message.
        /// </summary>
        private const int MaxMessageLength = 128 * 1024 * 1024; //128 Megabytes.

        /// <summary>
        /// This MemoryStream object is used to collect receiving bytes to build messages.
        /// </summary>
        private MemoryStream _ReceiveMemoryStream = new MemoryStream();

        public byte[] GetBytes(IMessage theMsg)
        {
            //Serialize the message to a byte array
            byte[] msgBytes = this.SerializeMessage(theMsg);

            //Check for message length
            int msgLength = msgBytes.Length;
            if (msgLength > MaxMessageLength)
            {
                throw new CommunicationException("Message is too big (" + msgLength + " bytes). Max allowed length is " + MaxMessageLength + " bytes.");
            }

            //Create a byte array including the length of the message (4 bytes) and serialized message content
            MemoryStream theResultStream = new MemoryStream(msgLength + 4);
            BaseTypeSerializer.Int32.Write(msgLength, theResultStream);
            byte[] result = theResultStream.ToArray();
            Array.Copy(msgBytes, 0, result, 4, msgLength);

            //Return serialized message by this protocol
            return result;
        }

        public IEnumerable<IMessage> BuildMessages(byte[] theBytes)
        {
            //Write all received bytes to the _receiveMemoryStream
            _ReceiveMemoryStream.Write(theBytes, 0, theBytes.Length);
            //Create a list to collect messages
            List<IMessage> msgList = new List<IMessage>();
            //Read all available messages and add to messages collection
            while (this.ReadSingleMessage(msgList)) { }
            //Return message list
            return msgList;
        }

        public void Reset()
        {
            if (_ReceiveMemoryStream.Length > 0)
            {
                _ReceiveMemoryStream = new MemoryStream();
            }
        }

        protected virtual byte[] SerializeMessage(IMessage message)
        {
            using (MemoryStream mem = new MemoryStream())
            {
                new BinaryFormatter().Serialize(mem, message);
                return mem.ToArray();
            }
        }

        protected virtual IMessage DeserializeMessage(byte[] bytes)
        {
            //Create a MemoryStream to convert bytes to a stream
            using (MemoryStream mem = new MemoryStream(bytes))
            {
                //Go to head of the stream
                mem.Position = 0;

                //Deserialize the message
                BinaryFormatter theBinaryFormatter = new BinaryFormatter
                {
                    AssemblyFormat = System.Runtime.Serialization.Formatters.FormatterAssemblyStyle.Simple,
                    Binder = new DeserializationAppDomainBinder()
                };

                //Return the deserialized message
                return (IMessage)theBinaryFormatter.Deserialize(mem);
            }
        }

        private bool ReadSingleMessage(ICollection<IMessage> messages)
        {
            //Go to the begining of the stream
            _ReceiveMemoryStream.Position = 0;

            //If stream has less than 4 bytes, that means we can not even read length of the message
            //So, return false to wait more bytes from remore application.
            if (_ReceiveMemoryStream.Length < 4)
            {
                return false;
            }

            //Read length of the message
            int msgLength = BaseTypeSerializer.Int32.Read(_ReceiveMemoryStream);
            if (msgLength > MaxMessageLength)
            {
                throw new Exception("Message is too big (" + msgLength + " bytes). Max allowed length is " + MaxMessageLength + " bytes.");
            }

            //If message is zero-length (It must not be but good approach to check it)
            if (msgLength == 0)
            {
                //if no more bytes, return immediately
                if (_ReceiveMemoryStream.Length == 4)
                {
                    _ReceiveMemoryStream = new MemoryStream(); //Clear the stream
                    return false;
                }

                //Create a new memory stream from current except first 4-bytes.
                byte[] bytes = _ReceiveMemoryStream.ToArray();
                _ReceiveMemoryStream = new MemoryStream();
                _ReceiveMemoryStream.Write(bytes, 4, bytes.Length - 4);
                return true;
            }

            //If all bytes of the message is not received yet, return to wait more bytes
            if (_ReceiveMemoryStream.Length < (4 + msgLength))
            {
                _ReceiveMemoryStream.Position = _ReceiveMemoryStream.Length;
                return false;
            }

            //Read bytes of serialized message and deserialize it
            byte[] theMsgBytes = StreamHelper.ReadBytes(_ReceiveMemoryStream, msgLength);
            messages.Add(DeserializeMessage(theMsgBytes));

            //Read remaining bytes to an array
            byte[] remainingBytes = StreamHelper.ReadBytes(_ReceiveMemoryStream, (int)(_ReceiveMemoryStream.Length - (4 + msgLength)));

            //Re-create the receive memory stream and write remaining bytes
            _ReceiveMemoryStream = new MemoryStream();
            _ReceiveMemoryStream.Write(remainingBytes, 0, remainingBytes.Length);

            //Return true to re-call this method to try to read next message
            return (remainingBytes.Length > 4);
        }

        protected sealed class DeserializationAppDomainBinder : SerializationBinder
        {
            public override Type BindToType(string assemblyName, string typeName)
            {
                var toAssemblyName = assemblyName.Split(',')[0];
                return (from assembly in AppDomain.CurrentDomain.GetAssemblies()
                        where assembly.FullName.Split(',')[0] == toAssemblyName
                        select assembly.GetType(typeName)).FirstOrDefault();
            }
        }
    }
}
