using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using FyndSharp.Communication.Clients;
using System.Net;
using FyndSharp.Communication.Channels;
using FyndSharp.Communication.Common;

namespace FyndSharp.Communication.ClientDemo
{
    class Program
    {
        static void Main(string[] args)
        {
            Console.WriteLine("Press enter to connect to the server...");
            Console.ReadLine(); //Wait user to press enter

            //Create a client object to connect a server on 127.0.0.1 (local) IP and listens 10085 TCP port
            using (var client = ClientFactory.CreateClient(new IPEndPoint(IPAddress.Parse("127.0.0.1"),12345)))
            {
                //Create a RequestReplyMessenger that uses the client as internal messenger.
                using (var requestReplyMessenger = new RequestReplyMessager<IClient>(client))
                {
                    requestReplyMessenger.Start(); //Start request/reply messenger
                    client.Connect(); //Connect to the server

                    Console.Write("Write some message to be sent to server: ");
                    var messageText = Console.ReadLine(); //Get a message from user

                    //Send user message to the server and get response
                    var response = requestReplyMessenger.SendMessageAndWaitForResponse(new TextMessage(messageText));

                    Console.WriteLine("Response to message: " + ((TextMessage)response).Text);

                    Console.WriteLine("Press enter to disconnect from server...");
                    Console.ReadLine(); //Wait user to press enter
                }
            }
        }
    }
}
