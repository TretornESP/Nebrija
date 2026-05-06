<<<<<<< HEAD
# EJERCICIOS

1. Compila el .c y parchealo con pwninit para que emplee la libc y el cargador dinámico proporcionados. ¿Porqué se hace esto?
Puedes usar este link como guia: https://www.cyberwiredtraining.net/blog/pwntools
2. Explica las siguientes cadenas rop (que hacen etapa por etapa). En el contexto del exploit ejemplo
    ret, pop rdi, alarm@got, puts@plt, main_function
    ret, pop rdi, bin_sh, ret, system
3. Dibuja un diagrama del proceso de carga dinámina (en concreto el uso de la PLT y la GOT).
4. ¿Porqué es importante la dirección base de la libc? ¿Cómo se puede obtener esta dirección base?
5. ¿Qué mitigaciones bypassea el exploit ejemplo? Explica cómo lo hace con cada una (ASLR, NX, RELRO, Stack Canaries) si procede.
6. Ahora edita el exploit para emplear Sigreturn Oriented Programming (SROP). Explica qué es SROP y en que casos es útil.
7. Edita el payload para que en lugar de ejecutar id ejecute una reverse shell (o bind shell por el 80% de la nota)
8. Crea una cadena rop que lea el fichero /etc/passwd y otra que lo imprima por pantalla.
9. Según se comentó en clase ¿Qué mitigación fue muy relevante para el ataque de XZ? explíca el porqué.
10. Explica en detalle, con tus palabras, el funcionamiento de una (a elegir) de las siguientes mitigaciones de seguridad modernas:
    - Control Flow Guard (CFG)
    - Shadow Stack
    - CET (Control-flow Enforcement Technology)
    - CFI (Control Flow Integrity)
    - SafeStack
    - Code Pointer Integrity (CPI)

# Puntos extra.

Ve al ejemplo de la semana pasada y trata de ejecutar una reverse shell (o bind shell por el 80% de los puntos extra) de
msfvenom. Para ello deberás antes de nada identificar y anotar los badchars, y luego generar tu payload con msfvenom
codificando con shikata_ga_nai y evitando los badchars. (Muestra todo el proceso con capturas)

# Puntos extra extra.

Entrega una breve clase (15 minutos max) grabada con tus palabras en las que expliques el funcionamiento básico de ROP.
Esta grabación deberá incluir un apoyo visual y representar correctamente el proceso paso a paso de la ejecución de la misma.
=======
# Ejercicio 1. 

Crea un programa en ensamblador para linux de 64 bits que ejecute el comando
/bin/sh mediante la syscall execve. Despues extrae el shellcode y entrega un fichero
.c que la ejecute. 

# Ejercicio 2.

Explica que es un stack canary y como se puede saltar esta protección en un servidor de forks.

# Ejercicio 3.

¿Que es el ASLR? ¿Cual es el problema de ASLR en arquitecturas de 32 bits?

# Ejercicio 4.

Edita el exploit que quieras para usar cadenas ciclicas y encontrar el offset de forma semi automatica.
>>>>>>> 9df8361b4f93af02c2831e83b0d1f0750165fdbb
