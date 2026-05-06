# Como probar todo

Antes de nada instalar build-essential gcc, gdb, nasm, qemu-system-x86_i386 y qemu-system-x86_64 en vuestra máquina. En linux suele ser tan fácil como:

sudo apt install build-essential gcc gdb nasm qemu-system


## example_boot

Abrir dos terminales en esta carpeta, en la primera ejecutad:

./debug.sh boot_alpha

O boot_bravo, boot_charlie, etc. Ya pillais la idea...

En la segunda ejecutad:

./connect.sh

Cuidado, tal vez tengáis que hacer chmod +x debug.sh connect.sh

En la segunda terminal podéis saltar línea a línea con 's' y ver el estado de los registros con 'info registers'. Para salir del debug, escribid 'quit' o 'q'.
Para ejecutar hasta el final del bootloader, escribid 'c' (continue) en lugar de 's'. Para poner un breakpoint en una dirección concreta, escribid 'b *direccion' (por ejemplo, 'b *0x7c00' para poner un breakpoint al inicio del bootloader)

## example_kernel

Tenéis documentación completa en el README.md, pero en esencia es:

chmod +x scripts/*.sh
make kernel
make image

Abrir otra terminal y ejecutar:
./scripts/connect-to-debug.sh

Y a partir de entonces ejecutar make debug
Podemos matar el connect-to-debug.sh con ctrl+c y volver a ejecutarlo para reconectar al debug si se nos desconecta por cualquier motivo o si reniciamos qemu.

Finalmente si nos quedamos sin espacio o peta, podemos hacer: make clean para limpiar todo y volver a empezar.

## example_context

compilar con gcc -g a.c y ejecutar con ./a.out

# Ejercicios

## Parte 1:
La semana que viene se hará una prueba en directo en la que se harán 10 de estas preguntas.
Hay que responder sin IA
¡¡¡Las preguntas no serán aleatorias!!! 

## Parte 2:
Id al fichero example_kernel/src/nebrija.c

1. **Dibujad** la traducción de direcciones virtuales a físicas que está haciendo la mmu para los dos mapas presentes en el código
2. ¿Qué componente de un sistema de memoria virtual paginada representa la variable root? Píntalo sobre el dibujo anterior.
3. La segunda escritura (segundo strcpy) falla. ¿Por qué?
4. ¿Qué número de interrupción se genera al hacer la segunda escritura? ¿Y si hiciésemos el primer strcpy en la línea 13? ¿Por qué?

## Parte 3 (PUNTOS EXTRA):

Id al fichero example_context/a.c

1. ¿Que esquema de multitarea se está usando en este código?
2. Modificadlo para que se use un esquema de multitarea apropiativa mediante interrupciones periódicas (cada segundo, por ejemplo)

# Banco de preguntas de examen:

## El bootloader

Recordad, la idea no es que esto se use en el proyecto final (vamos a usar limine porque si no no hay dios que lo haga arrancar)

Que responder:

1. ¿Qué es un bootloader? ¿Qué tipos de bootloaders existen?
1. ¿Qué es el MBR y qué es el GPT?
2. ¿Qué es una interrupción BIOS? ¿Qué servicios ofrece?
3. ¿Que secuencia sigue la máquina al arrancar desde cero hasta que el bootloader toma el control?
4. ¿Cómo detecta la bios que hay un bootloader válido? ¿Donde lo carga y que información le pasa?
5. ¿Que es un second stage bootloader y por qué se usa?
6. ¿Qué es el modo real y el modo protegido en x86? ¿Cómo se pasa de uno a otro?
7. ¿Qué estructuras de datos básicas gestiona la cpu? (GDT/LDT, IDT, tablas de páginas, etc)
8. ¿Cómo se carga un binario ELF en memoria? (Explicar el formato ELF y los pasos necesarios para cargarlo)

Algunas Referencias, no son suficientes pero pueden ayudar:

https://www.youtube.com/watch?v=HEZ-HvrRAI0
https://wiki.osdev.org/Protected_Mode
https://wiki.osdev.org/Global_Descriptor_Table
https://wiki.osdev.org/GDT_Tutorial
https://wiki.osdev.org/Interrupt_Descriptor_Table
https://wiki.osdev.org/Interrupts_Tutorial
https://wiki.osdev.org/8259_PIC

## Device drivers

Que responder:

1. ¿Qué diferencias hay entre un driver y otra pieza de software?
2. ¿Qué tipos de mecanismos de IO existen?
3. ¿Qué estructuras de datos gestionan las interrupciones en x86? (IDT/IVT)
4. ¿Qué tipos de dispositivos existen? (Bloque, carácter, red, etc)
5. ¿Que operaciones básicas debe soportar un driver de dispositivo? (open, close, read, write, ioctl)
6. ¿Qué hace ioctl?
7. ¿Qué es un número mayor y un número menor en un driver de dispositivo?
8. ¿Cómo puede un driver manejar interrupciones?
9. ¿Qué es el polling y qué es el DMA?
10. ¿Qué es el PIC 8259 y qué es el APIC?
11. ¿Que son las MSI y las MSI-X? (Ventajas y desventajas)
12. ¿Cómo se trabaja con dispositivos PCI? Particularidades de los dispositivos PCI y PCIe con respecto al manejo de interrupciones (si no usamos msi/msix)

Algunas Referencias, no son suficientes pero pueden ayudar:

https://github.com/Open-Driver-Interface/odi/blob/main/src/core/device.c
https://github.com/Open-Driver-Interface/odi/blob/main/src/drivers/com/serial/serial.c
https://github.com/Open-Driver-Interface/odi/blob/main/src/drivers/net/e1000/e1000.c
https://wiki.osdev.org/Interrupt_Descriptor_Table
https://wiki.osdev.org/Interrupts_Tutorial
https://f.osdev.org/viewtopic.php?t=37112
El tema de la pregunta 12. https://github.com/TretornESP/bloodmoon/blob/f0ba69b87e719a82f273c418dca1982c16666c2d/src/devices/pci/pci.c#L320 (pci comparte lineas de interrupcion para todos los dispositivos, con lo que tienes que checkear cual ha sido el que ha generado la interrupcion uno a uno!)


## Sistema de Archivos EXT2

Que responder:

1. Que es la buffer cache y que algoritmos asociados hay en UNIX.
2. Que es un sistema de ficheros virtual VFS y cual es su función.
4. Como se organiza el sistema de ficheros EXT2 (superbloque, mapas de bits, tablas de inodos, bloques de datos, etc) explicar cada uno.
5. Que es un symlink y un hard link y en que se diferencian.
6. Que son AHCI y SATA

Algunas Referencias, no son suficientes pero pueden ayudar:

https://github.com/TretornESP/fused
https://abhyass.wordpress.com/wp-content/uploads/2016/09/thedesignofunixoperatingsystem_m_bach.pdf
https://wiki.osdev.org/Ext2
https://www.nongnu.org/ext2-doc/ext2.html

## Memoria

Preguntas a responder:

1. ¿Que diferencia hay entre la memoria física y la memoria virtual?
2. ¿Que es la segmentación? ¿Qué es la segmentación plana?
3. ¿Qué es la paginación? ¿Que hay en una tabla de páginas? ¿Cómo se activa la paginación en x86?
4. Muestra el proceso de traducción de una dirección virtual a una dirección física usando paginación.
5. ¿Qué es un fallo de página (page fault) y cómo lo maneja el sistema operativo?
6. ¿Cómo funciona el SWAP? ¿Y el Copy on Write (COW)?

Algunas Referencias, no son suficientes pero pueden ayudar:

https://wiki.osdev.org/Paging
https://wiki.osdev.org/Exceptions#Page_Fault
https://wiki.osdev.org/Protected_Mode#Entering_Protected_Mode

## Procesos

Que responder:

1. ¿Qué es un proceso? Diferencia entre proceso e hilo.
2. ¿Qué es el PCB (Process Control Block)? ¿Qué es el contexto de un proceso? ¿Qué información contienen ambos?
3. ¿Cómo crea el primer proceso un sistema operativo?
4. ¿Qué hace fork y qué hace exec?
5. ¿Cómo se realiza el cambio de contexto entre procesos? Explicar en máximo detalle para el caso de un único core x86.
6. ¿Qué problemas tiene la planificación round robin?
7. ¿Qué pasa cuando un proceso tiene que esperar con un recurso?
8. ¿Cómo se implementa la comunicación entre procesos (IPC)?
9. ¿Cómo procesa un proceso las señales (signals)?
10. ¿Qué es un deadlock? ¿Cómo se puede evitar o manejar?

Algunas Referencias, no son suficientes pero pueden ayudar:

https://github.com/TretornESP/bec/blob/main/s2/a.c (p1 y p2 son dos procesos haciendo cambio de contexto)
https://github.com/omen-osdev/omen/blob/bc66800c505021e7403f33098d574340ae273150/src/modules/managers/cpu/process.c#L1274C6-L1274C18 (ejemplo de inicializacion de procesos en un OS real, algo lioso)
https://wiki.osdev.org/Brendan%27s_Multi-tasking_Tutorial

## Espacio de usuario

Antes de nada, este punto puede ser un poco lios a primera vista. 
La idea es que habléis de cómo el sistema operativo gestiona las transiciones entre el espacio de usuario y el espacio kernel y sus respectivas idiosincrasias.

Que responder:

1. ¿Qué es el espacio de usuario y el espacio kernel? ¿Que sentido tiene aislarlos?
2. ¿Qué son las syscalls? ¿Cómo se implementan en un sistema operativo típico? ¿Cuales son las más comunes?
3. ¿Cómo se realiza la transición entre espacio de usuario y espacio kernel?
Aclaración: No pregunto el cuando (syscall/sysret/interrupción/etc) si no el cómo (Que hace el hardware cuando se
produce un syscall o un sysret, que registros se usan, etc). Hablad de los registros fs_base, gs_base, etc. de los MSR, etc.
4. ¿Cómo se entra por primera vez en modo usuario desde el kernel?
5. ¿Qué es la multitarea cooperativa? ¿y la apropiativa?
6. ¿Cómo se implementa la protección de memoria entre procesos en espacio de usuario?
7. ¿Qué es un VDSO? ¿Para qué se utiliza?
8. Extra: ¿Qué pasa cuando un proceso hace una syscall que requiere esperar (por ejemplo, leer de disco)?

Algunas Referencias, no son suficientes pero pueden ayudar:

https://wiki.osdev.org/Getting_to_Ring_3

Conceptualmente es muy fácil:
 - El truco es crear un stack en modo usuario y escribirlo a mano (dirección de retorno)
   para que parezca que tenemos que hacer un return a una función en modo usuario, esa función es nuestro entry.
   https://github.com/omen-osdev/omen/blob/bc66800c505021e7403f33098d574340ae273150/src/modules/managers/cpu/process.c#L263
   https://github.com/omen-osdev/omen/blob/bc66800c505021e7403f33098d574340ae273150/src/modules/hal/arch/x86/context.asm#L109
   La implementación real es un puto cristo
