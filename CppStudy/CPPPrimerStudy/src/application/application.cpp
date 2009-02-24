/**
 * This is entry of all practice function code.
 * 
 */
#include "stdafx.h"

using namespace std;

int main(int argc, char *argv[])
{
    string command;
    std::cout << ">";
    while(std::cin >> command)
    {
        if(command == string("SimpleCppProgram"))
        {
            SimpleCppProgram();
        }
        else if(command == string("UsingIO"))
        {
            UsingIO();
        }
        else if(command == string("HelloWorld"))
        {
            HelloWorld();
        }
        else if(command == string("CalcProduct"))
        {    
            CalcProduct();
        }
        else if(command == string("PraticeLoop"))
        {
            PraticeLoop();       
        }
        else
        {
            std::cout << "Unkown command!" << std::endl;
        }
        
        std::cout << ">";
    }
}