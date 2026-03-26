# Ejercicio evaluable, diseño de un emulador de arquitectura ISA.

El objetivo es diseñar un emulador para la arquitectura SIMPLEZ.
El emulador debe ser capaz de ejecutar un programa escrito en lenguaje ensamblador SIMPLEZ, interpretando las instrucciones y manipulando la memoria y los registros de acuerdo con las especificaciones de la arquitectura.

## Requisitos del emulador:

1. **Soporte para instrucciones**: El emulador debe ser capaz de interpretar y ejecutar todas las instrucciones definidas en la arquitectura SIMPLEZ (Ojo, no es necesario hacer SIMPLEZ+I3).

2. **Gestión de la entrada/salida**: El emulador debe implementar un mecanismo de entrada/salida para permitir la interacción con el programa emulado (esto puede ser a través de la consola, archivos, o cualquier otro método que consideres adecuado).

3. **Carga de programas**: El emulador debe ser capaz de cargar un programa escrito en lenguaje ensamblador SIMPLEZ desde un archivo y ejecutarlo.

Podéis utilizar el lenguaje de programación que prefiráis para implementar el emulador, aunque se recomienda utilizar un lenguaje de bajo nivel como C o Rust.

## Entregables:

Todos los entregables se subirán a un repositorio de GitHub. El repositorio debe incluir:

1. **Código fuente del emulador**: El código completo del emulador, incluyendo cualquier archivo de configuración o dependencias necesarias para su ejecución.

2. **Documentación**: Un archivo README.md que explique cómo compilar y ejecutar el emulador, así como una descripción general de su diseño y funcionamiento.

3. **Ejemplo de programa**: Un programa de ejemplo escrito en lenguaje ensamblador SIMPLEZ que pueda ser ejecutado por el emulador, junto con una explicación de lo que hace el programa.

## Puntos extra, Wozmon:

Si queréis obtener los puntos extra de esta semana, podéis implementar un programa que haga lo siguiente:

1. Va leyendo de la entrada estándar códigos hexadecimales que representen instrucciones en lenguaje ensamblador SIMPLEZ. El programa irá guardándolos en memoria contigua.

2. Cuando lea el código hexadecimal que queráis, el prorgama saltará a la dirección de memoria donde se encuentra el primer código hexadecimal leído, ejecutando las instrucciones almacenadas en memoria.

El objetivo de este programa es simular un proceso de carga y ejecución de instrucciones en memoria, similar a lo que haría un sistema operativo al cargar un programa en memoria y ejecutarlo.