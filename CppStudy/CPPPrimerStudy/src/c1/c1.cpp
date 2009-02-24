/**
 * C++ Primer 4th edition practice code,chapter 1
 * created by Fishtrees at 2009.2.24
 */

#include "stdafx.h"

#include <iostream>

using namespace std;

void SimpleCppProgram()
{
}
void UsingIO()
{
    std::cout << "Enter two numbers: " << std::endl;
    
    int v1,
        v2;
    std::cin >> v1 >> v2;
    std::cout << "The sum of " 
              << v1 
              << " and " 
              << v2 
              << " is " 
              << v1 + v2 
              << std::endl;
}

void HelloWorld()
{
    std::cout << "Hello World!" << std::endl;
}

//Compute product of two numbers
void CalcProduct()
{
    std::cout << "Enter two numbers: " << std::endl;
    int v1,
        v2;
    std::cin >> v1 >> v2;
    std::cout << "The product of " << v1 
              << " and " << v2
              << " is " << v1 * v2;
}

void PraticeLoop()
{
    int n1 = 0;
    int sum1 = 0;
    while(n1 <= 10)
    {
        sum1 += n1;
        ++n1;
    }
    std::cout << "The sum1 of using \"while\" loop to compute is " << sum1 << std::endl;
    
    
    int sum2 = 0;
    for(int i=0; i<=10;i++)
    {
        sum2 += i;
    }
    std::cout << "The sum2 of using \"for\" loop to compute is " << sum2 << std::endl;
}

