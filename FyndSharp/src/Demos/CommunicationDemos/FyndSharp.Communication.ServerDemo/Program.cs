using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using FyndSharp.Communication.Server;
using System.Net;
using FyndSharp.Communication.Common;

namespace FyndSharp.Communication.ServerDemo
{
    class Program
    {
        static void Main(string[] args)
        {
            TcpServer server = new TcpServer(new System.Net.IPEndPoint(IPAddress.Any, 12345));
            server.ClientDisconnected += new EventHandler<ClientDummyEventArgs>(server_ClientDisconnected);
            server.ClientConnected += new EventHandler<ClientDummyEventArgs>(server_ClientConnected);
            server.Start();
            Console.WriteLine("Server is started successfully. Press enter to stop...");
            Console.ReadLine(); //Wait user to press enter

            server.Stop(); //Stop the server
        }

        static void server_ClientConnected(object sender, ClientDummyEventArgs e)
        {
            Console.WriteLine("A new client is connected. Client Id = " + e.Client.Id);

            //Register to MessageReceived event to receive messages from new client
            e.Client.MessageReceived += new EventHandler<MessageEventArgs>(Client_MessageReceived);
        }

        static void server_ClientDisconnected(object sender, ClientDummyEventArgs e)
        {
            Console.WriteLine("A client is disconnected! Client Id = " + e.Client.Id);
        }

        static void Client_MessageReceived(object sender, MessageEventArgs e)
        {
            var message = e.Message as TextMessage; //Server only accepts text messages
            if (message == null)
            {
                return;
            }

            //Get a reference to the client
            var client = (IClientDummy)sender;

            Console.WriteLine("Client sent a message: " + message.Text +
                              " (Cliend Id = " + client.Id + ")");

            //Send reply message to the client
            client.Send(
                new TextMessage(
                    "Hello client. I got your message (" + message.Text + ")",
                    message.Id //Set first message's id as replied message id
                    ));
        }
    }
}
