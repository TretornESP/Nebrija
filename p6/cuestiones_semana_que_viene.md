# Ejercicios breves

1. Dibuja un diagrama de la siguiente función lógica con transistores
(Puedes añadier algunos componentes básicos a tu elección, tales cómo
resistencias, fuentes de voltaje, tierras, etc.)

```
1. f(a, b, c) = (a AND b) OR (NOT c)
```

2. Dibuja un diagrama de la función lógica que representa la siguiente tabla de verdad:

A B S C
0 0 0 0
0 1 1 0
1 0 1 0
1 1 0 1

Siendo A y B las entradas, S la salida y C el acarreo.

¿Qué componente lógico representa esta función? Nota esta función
suele emplear una puerta XOR, simplifícala en términos de puertas lógicas básicas (AND, OR, NOT) y dibuja el diagrama tal y como se ha pedido en el ejercicio anterior.

3. Diseña una máquina de turing que reconozca el lenguaje de todas las cadenas binarias que contienen un número par de ceros. Describe su alfabeto, estados, estado inicial, estados de aceptación y función de transición.

4. Convierte los siguientes números decimales a binario, hexadecimal y octal:

a) 25
b) 100
c) 255

5. Calcula el complemento a dos de los siguientes números binarios:

a) 1010
b) 1101
c) 0110

¿Para que sirve el complemento a dos?

7. Desensambla la instrucción 0x123 en arquitectura SIMPLEZ según el
documento de referencia proporcionado en este repositorio. Indica el
campo de operación, selector de registro y el campo de datos además
de explicar qué operación realiza esta instrucción.

Ahora desensambla la misma instrucción en SIMPLEZ+I3.

8. Crea un programa sencillo en simplez que sume dos números enteros y almacene el resultado en memoria. Explica cada línea de tu código.

9. Indica que mecanismo de entrada salida utiliza simplez
(MMIO/PIO) y que diferencias hay entre ambos.

10. Explica que diferencia existe entre una PILA (STACK), una COLA (QUEUE) y una LISTA ENLAZADA (LINKED LIST). Describe un caso de uso típico para cada una de estas estructuras de datos.

Además en el caso de la PILA, explica a que hacen referencia los 
registros de PUNTERO DE PILA (SP) y BASE DE PILA (BP). (Brevemente)

## Puntos extra:

Hasta ahora para saltar de una subrutina a otra hemos utilizado la instrucción
de salto (Sea condicional bz o incondicional br).

Sin embargo, no hemos establecido un mecanismo para volver a la subrutina 
original después de ejecutar la subrutina llamada. ¿Cómo podríamos implementar 
un mecanismo de retorno a la subrutina original? Describe tu solución.