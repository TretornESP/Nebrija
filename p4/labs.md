# Práctica 4: Post-Explotación

## Configuración del entorno

Esta práctica se lleva a cabo en el entorno de laboratorio.

En este entorno solo existen alice, marcus (marcus es una segunda alice) y el router.

Alice: Igual que la práctica pasada, salida a internet y acceso a la red local.
Marcus: **Unicamente** acceso a la red local. (No pasa nada por que tenga salida siempre
que no lo uséis para saltaros pasos de la práctica, a todos los efectos asumid que no tiene)

Ejecutad las siguientes lineas **como root** en alice:

```bash
echo 'Defaults env_keep += "LD_PRELOAD"' | sudo EDITOR='tee -a' /sbin/visudo
echo 'CAMBIAMECAMBIAMECAMBIAME ALL=(ALL) /bin/ls' | sudo EDITOR='tee -a' /sbin/visudo
```

Obviamente cambia CAMBIAMECAMBIAMECAMBIAME por tu nombre de usuario regular en alice.

Comprueba que desde el usuario no privilegiado de alice puedes ejecutar `sudo /bin/ls /root`

FIJAOS EN QUE EN ESTA RED NO ESTÁ PRESENTE BOB

**Muy importante**: 

    - Bob en este laboratorio no debe estar DENTRO de la LAN. Bob no debe tener acceso a la
    red interna y no podrá hacer ssh, ping, etc a alice o marcus. Si esta premisa no se cumple
    la práctica obtendrá una calificación de 0.

Empezamos comprometiendo alice mediante un payload de msfvenom (podemos ejecutarlo directamente:
Asumimos que alice ha abierto un virus, por ejemplo desde un email, descarga web, etc) este payload
se ejecutará con el usuario de alice, que no debe tener privilegios de administrador.

## Fase 1: Compromiso inicial y escalada de privilegios (4 puntos)

1. Describe el proceso de creación del payload y la configuración del listener en metasploit. Es
probable que necesites pelearte con el hecho de que no tienes una IP pública, así que tendrás que
buscar un servicio para exponer tu listener a internet. (pista: el servicio que usé yo tiene un
nombre parecido a la IA de Twitter) (Vale 2 puntos)

2. Una vez tengas acceso a alice, busca un vector de escalada de privilegios con LINPEAS y obten root (Vale 2 puntos)

## Fase 2: Ocultación y persistencia (4 puntos)

En esta fase tendrás que programar un pequeño rootkit, cómo un rootkit es un programa a nivel de kernel,
es muy posible que te cargues la máquina un par de veces. Recomendamos crear un punto de restauración antes
de empezar a programar el rootkit. 

Usad este tutorial como referencia [Link](https://xcellerator.github.io/posts/linux_rootkits_01/)

```bash
# Instalamos las dependencias necesarias para compilar el rootkit
sudo apt install -y build-essential linux-headers-$(uname -r) gcc
```

```c
//Codigo de ejemplo de un rootkit, guardadlo como rk.c
#include <linux/init.h>
#include <linux/module.h>
#include <linux/kernel.h>

MODULE_LICENSE("GPL");
MODULE_AUTHOR("TretornESP");
MODULE_DESCRIPTION("Test Rootkit");
MODULE_VERSION("0.01");

static int __init example_init(void)
{
    printk(KERN_INFO "Hello, world!\n");
    return 0;
}

static void __exit example_exit(void)
{
    printk(KERN_INFO "Goodbye, world!\n");
}

module_init(example_init);
module_exit(example_exit);
```

```c
# Makefile para compilar el rootkit, guardadlo como Makefile
obj-m += rk.o

all:
	make -C /lib/modules/$(shell uname -r)/build M=$(PWD) modules

clean:
	make -C /lib/modules/$(shell uname -r)/build M=$(PWD) clean
```

Comprobar que genera el driver correctamente y que se puede cargar sin problemas con `insmod` y `rmmod`.

```bash
# Nota: Tenemos que ser root!!!
# Compilamos el rootkit
make
# Cargamos el rootkit
/sbin/insmod rk.ko
# Comprobamos que se ha cargado correctamente
/sbin/lsmod | grep rk
# Comprobamos los logs del kernel para ver el mensaje de bienvenida
dmesg | grep "Hello, world!" -A 10 -B 10
# Descargamos el rootkit
/sbin/rmmod rk
# Comprobamos los logs del kernel para ver el mensaje de despedida
dmesg | grep "Goodbye, world!" -A 10 -B 10
```

Yo recomiendo crear otro punto de restauración llegado a este punto

1. Obten persistencia mediante la adición de una clave ssh. (Equivale al 1.6 de la P3) (Vale 1 punto)

2. Probad los siguientes mecanismos de persistencia para lanzar payloads cada vez que se inicie la máquina: (Vale 1 punto)

- MOTD
- Cron
- Systemd unit
- .bashrc

Nota: En este punto puede ser cualquier payload, desde un comando id hasta una reverse shell.

3. Implementad un rootkit en base al ejemplo de la carpeta rk que haga lo siguiente: (Vale 2 punto)

 - Esconda el módulo cuando se haga lsmod (usad el truco del cap 5 para que sys_kill señal 64 lo revele y asi poder descargarlo)
 - Cuando se envíe la señal 65, se debe infectar la máquina con un payload (este sí debe ser una reverse) mediante uno de los mecanismos del punto anterior.


## Fase 3: Movimiento lateral (2 puntos)

1. Usa a Alice como bouncer para comprometer a Marcus mediante un ataque de fuerza bruta con Hydra (usa el user de marcus (no hay que bruteforcearlo) y el wordlist rockyou).(Equivale al 2.6 de la P3) (Vale 1 punto)

2. Cambia el mecanismo para hacer tunneling hacia marcus. Ahora haz port bending con la herramienta socat. (Vale 1 punto)
