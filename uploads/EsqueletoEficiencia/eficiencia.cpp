#include <iostream>
#include <algorithm>
#include <vector>
#include <list>         // Bibliotecas necesarias para la implementacion
#include "cronometro.h" // Incluimos el modulo cronometro

using namespace std;
int main()
{
  srand(time(0));
  int n=0;
  while(n<20000||n==20000)
    {
      
      vector <double> v;                  // Declaramos v de elementos de tipo double
      for (int i=0;i<n;i++)
	{
	  v.push_back((double)i);         // Añadimos i al final del vector
	}     
      random_shuffle(v.begin(),v.end());  // Intercambio de valores
      cronometro c;
      c.activar();                        // Activamos cronometro
      medireficiencia(v.begin(),v.end()); // Llamada a la funcion
      c.parar();                          // Paramos cronometro
      double t=c.tiempo();                // Asignamos el tiempo del cronometro a la varible t
      cout<< n <<'\t'<<t<<endl;
     
      n=n+1000;
    }
} 
