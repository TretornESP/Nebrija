# Práctica redes

Completa el siguiente laboratorio en GNS3 y VirtualBox para llevar a cabo los ejercicios propuestos en ejercicio.md

## Creación del laboratorio 

Nota: Si tenéis problemas y queréis usar solo virtualbox, podéis crear una red virtual e ignorar GNS3
(limita a 7.5 la nota)

### Prerequisitos

1. Descarga VirtualBox: https://download.virtualbox.org/virtualbox/7.2.4/VirtualBox-7.2.4-170995-Win.exe
2. Descarga GNS3: https://downloads.solarwinds.com/solarwinds/GNS3/v2.2.55/GNS3-2.2.55-all-in-one-regular.exe
Importante: El instalador pide meter slop en nuestra máquina (de solarwindws), le decimos que no!
3. Descarga la iso de debian: https://gemmei.ftp.acc.umu.se/debian-cd/current/amd64/iso-dvd/debian-13.3.0-amd64-DVD-1.iso
4. Descarga la imagen de VirtualBox de GSN3: https://github.com/GNS3/gns3-gui/releases/download/v2.2.55/GNS3.VM.VirtualBox.2.2.55.zip

### Instalación de las VMs

1. 
Definimos 3 VMs con la iso de debian

Alice (Víctima):  Debe tener gui | 2 gb de ram | 2 cores | 20gb de disco (thin prov)
Bob   (Atacante): Si lo deseáis puede tener gui | 2 gb de ram | 2 cores | 20gb de disco (thin prov)
Router (Router):  Si lo deseáis puede tener gui | 2 gb de ram | 2 cores | 20gb de disco (thin prov)

Cuidado con la instalación desatendida, a veces da problemas con el user root!!!

Una forma rápida de generarlas es instalar una y luego clonarla (¡¡¡pidiendo macs nuevas por vm!!!)


2. Desconectamos los adaptadores de red de cada vm en preparación para ingestarlas en GNS3
(configuración->red->Conectado a: no conectado).

3. Descomprimimos la VM de GNS3 y la instalamos en virtualbox

4. Abrimos GNS y vamos a preferencias -> GNS3 VM
Activamos la VM, seleccionamos el motor: virtualbox y seleccionamos la VM name apropiada (según el paso 3).
Podemos modificar sus recursos y darle a aplicar. La vm debería arrancar automáticamente.

GNS3 quedará esperando (Waiting for http://localhost:3080) a que arranque, una vez desaparezca ese mensaje 
podremos presionar "OK" y así cerrar el menú de preferencias.

5. En GNS presionamos en "+New Template"->manually crate a template->Virtualbox VMs-> Presionamos en NEW->next
En la vm list seleccionamos Router y presionamos finish y finalmente en Apply.

Repetimos el proceso para alice y bob.

6. Editamos la máquina router en GNS para añadir una segunda interfaz de red y creamos un nuevo dispositivo cloud
hosteado en nuestra máquina local (No en gns3 vm), seleccionamos cómo adaptador el que nos de salida a internet.

7. Creamos una topología básica en la que una de las tarjetas de red del router se conectan a la cloud (recuerda
los nombres) y arranca el router.

8. Abre el router y configura la red (si usas ip estática, en caso contrario comprueba que dhcp te haya dado una ip válida)

Algunos comandos interesantes para esto:

```bash
ip addr show          # Muestra las interfaces de red y sus direcciones IP
# Asumiendo que las interfaces se llaman enp0s3 y enp0s8
# Si no es el caso, sustituir enp0s3 por el nombre correcto (s3 es la salida a internet y s8 la interna)
ip link set enp0s3 up          # Activa la interfaz de red
dhclient enp0s3                 # Solicita una dirección IP mediante DHCP
ping 8.8.8.8               # Prueba la conectividad a Internet
ip route show         # Muestra la tabla de rutas
```

Para configurar la red:

```bash
sudo nano /etc/network/interfaces
systemctl restart networking.service
#Si falla algo
journalctl -xeu networking.service
```

Un ejemplo de configuración para DHCP:

```plaintext
auto enp0s3
iface enp0s3 inet dhcp
```

Un ejemplo de configuración para IP estática:

IP: 192.168.89.235/24, Router en: 192.168.89.100, DNS: 192.168.87.201

```plaintext
auto enp0s3
iface enp0s3 inet static
    address 192.168.89.235
    netmask 255.255.255.0
    gateway 192.168.89.100
    dns-nameservers 192.168.87.201 8.8.8.8
```

Hacemos ping a google.com para comprobar que tenemos conexión a internet.

9. Conectamos alice y bob al router (a la interfaz libre) traves de un hub

10. Arrancamos alice y bob, sin tocar nada en su configuración de red (DHCP por defecto)

11. Configuramos el router para que haga de servidor DHCP, DNS y gateway para alice y bob

Editamos el archivo /etc/network/interfaces para añadir una segunda interfaz de red (enp0s8)

```plaintext
auto enp0s8
iface enp0s8 inet static
    address 10.10.0.1
    netmask 255.255.0.0
```

Recargamos la configuración de red

```bash
sudo systemctl restart networking.service
```

Activamos el reenvío de paquetes:

```bash
echo 1 | sudo tee /proc/sys/net/ipv4/ip_forward # Habilitar el reenvío de paquetes IPv4 de forma puntual
sudo nano /etc/sysctl.conf
# Descomentar o añadir la línea: 
net.ipv4.ip_forward=1 # Cambio permanente
sudo sysctl -p # Aplicar los cambios realizados en sysctl.conf
```

Configuramos el natting con iptables:

Si no tienes iptables instalado, instálalo con:

```bash
sudo apt update
sudo apt-get install iptables
```

Si estuviera buscando los paquetes desde el cd, podemos editar el fichero /etc/apt/sources.list y comentar las líneas que hacen referencia al cdrom. Finalmente añadimos los repositorios oficiales de debian:

deb http://deb.debian.org/debian/ trixie main non-free-firmware

Actualizamos la lista de paquetes:

```bash
sudo apt update
#Reintentamos la instalación de iptables si fuera necesario
sudo apt-get install iptables
```



```bash
sudo iptables -t nat -A POSTROUTING -o enp0s3 -j MASQUERADE
sudo iptables -A FORWARD -i enp0s8 -o enp0s3 -j ACCEPT
sudo iptables -A FORWARD -i enp0s3 -o enp0s8 -m state --state RELATED,ESTABLISHED -j ACCEPT
```

Podemos hacer que los cambios en iptables sean permanentes instalando iptables-persistent:

```bash
sudo apt-get install iptables-persistent
sudo netfilter-persistent save
```

Instalamos y configuramos el servidor DHCP y DNS (isc-dhcp-server y bind9):

```bash
sudo apt-get install isc-dhcp-server bind9
```

Editamos el archivo /etc/dhcp/dhcpd.conf para configurar el servidor DHCP:

```plaintext
subnet 10.10.0.0 netmask 255.255.0.0 {
    range 10.10.0.100 10.10.0.200;
    option routers 10.10.0.1;
    option domain-name-servers 10.10.0.1;
    option domain-name "hackme.internal";
}
```

Editamos el archivo /etc/default/isc-dhcp-server para especificar la interfaz en la que escuchará el servidor DHCP:

```plaintext
INTERFACESv4="enp0s8"
```

Reiniciamos el servicio DHCP:

```bash
sudo systemctl restart isc-dhcp-server.service
```

Configuramos bind9 para que haga de servidor DNS recursivo:

Editamos el archivo /etc/bind/named.conf.options:

```plaintext
options {
    directory "/var/cache/bind";
    recursion yes;
    allow-query { any; };
    forwarders {
        8.8.8.8;
        1.1.1.1;
    };
    dnssec-validation auto;
    auth-nxdomain no;    # conform to RFC1035
    listen-on-v6 { any; };
};
```

Reiniciamos el servicio bind9:

```bash
sudo systemctl restart bind9.service
```

12. Comprobamos que alice y bob reciben una IP válida y pueden navegar por internet

Abrimos alice y bob y ejecutamos:

```bash
ip addr show          # Muestra las interfaces de red y sus direcciones IP
ping 8.8.8.8
ping google.com
```

Si todo ha ido bien, alice y bob deberían tener una IP en el rango 10.10.0.100-200 y deberían poder hacer ping a google.com