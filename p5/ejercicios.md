# Practica 5: Directorio activo

## Setup del laboratorio

Para esta práctica necesitamos montar un entorno de directorio activo.
Para ello emplearemos Vagrant, Vmware (se puede usar VirtualBox aunque
no está testeado) y la herramienta: https://github.com/Orange-Cyberdefense/GOAD
(Referencia para el alumno que trabaja en Orange jeje)

Nota importante: Os recomiendo usar cómo referencia la guía del ejercicio original
[link](https://mayfly277.github.io/posts/GOADv2-pwning_part1/)

La referencia para el setup completo está en el siguiente enlace (tenéis
versiones para virtualbox y cloud públicas) [link](https://orange-cyberdefense.github.io/GOAD/installation/windows/#__tabbed_2_2)

Lo primero es asegurarnos de que tenemos Vagrant instalado:
[link de descarga](https://developer.hashicorp.com/vagrant/install)

Después instalamos vmware workstation y el plugin de vagrant para vmware:
[link de descarga](https://developer.hashicorp.com/vagrant/install/vmware#windows)

Con esto el comando `vagrant` ya debería estar disponible en la terminal, si no es así, reiniciamos la terminal.

Ahora clonamos el repositorio de GOAD:

```bash
git clone git@github.com:Orange-Cyberdefense/GOAD.git
cd GOAD
python -m venv .env
source .env\Scripts\activate        # Desde CMD: .env\Scripts\activate.bat
pip install -r noansible_requirements.yml
#Ejecutamos:
python goad.py -m vm
```

Esto nos debería abrir GOAD. Antes de nada ejecutad "Check" para asegurarnos
de que todo está correcto. Si no es así, revisad los pasos anteriores y que
tenéis disco suficiente para montar las máquinas virtuales (200GB aprox).

Con el comando `config` podreis revisar que la configuración es correcta, 
finalmente ejecutamos `install` y vamos a tomar un café, esto tardará un par
de horas.

Si falla la instalación, podéis ejecutar varias veces sin miedo el comando `install` hasta que se complete correctamente.

Deberíais ver algo así al finalizar la instalación:
```bash
RUNNING HANDLER [vulns/adcs_esc11 : restart-adcs] **********************************************************************************************************************
changed: [srv03]

PLAY RECAP *************************************************************************************************************************************************************
dc01                       : ok=11   changed=1    unreachable=0    failed=0    skipped=1    rescued=0    ignored=0
dc02                       : ok=16   changed=10   unreachable=0    failed=0    skipped=6    rescued=0    ignored=0
dc03                       : ok=10   changed=4    unreachable=0    failed=0    skipped=0    rescued=0    ignored=0
srv02                      : ok=13   changed=2    unreachable=0    failed=0    skipped=1    rescued=0    ignored=0
srv03                      : ok=13   changed=4    unreachable=0    failed=0    skipped=1    rescued=0    ignored=0

Connection to 192.168.56.3 closed.
[*] Lab successfully provisioned in 01:22:59
```

Finalmente aseguraos que os ha puesto las vms en las tarjetas de red apropiadas, (deberían tener una en NAT y otra en Host-Only) y que podéis hacer ping entre ellas y desde vuestro host/maquina atacante.

La prueba de fuego:

Ejecutad el comando: `nmap -Pn -p- -sC -sV -oA full_scan_goad 192.168.56.10-12,22-23`
(Claro, si habéis usado la 56.X, si no, ajustad las IPs a vuestro rango)
Si todo ha ido bien, deberíais ver información jugosa.

## Ejercicio 1: Enumeración de dominios y controladores de dominio

Enumera los dominios y controladores de dominio de la red (asocia los DCs con los dominios a los que pertenecen).

## Ejercicio 2: Enumeracion de usuarios

Enumera los usuarios mediante el protocolo smb. Hay un usuario que tiene su contraseña expuesta en la descripción
de su cuenta. ¿Cuál es su contraseña?

## Ejercicio 3: Enumeración de usuarios con autenticación

Emplea la cuenta obtenida en el ejercicio anterior para enumerar usuarios con el script de Impacket (GetAdUsers.py)

## Ejercicio 4: Kerberoasting

### 4.1. Explica en que consiste este ataque
### 4.2. Enumera los SPN de la red con el script de Impacket (GetUserSPNs.py)
### 4.2. Rompe el hash obtenido con hashcat y el diccionario rockyou.txt. ¿Cuál es la contraseña del usuario?

## Ejercicio 5: Enumeración de carpetas compartidas

Enumera las carpetas compartidas en cada uno de los controladores de dominio.
¿Hay alguna carpeta con permisos RW para usuarios anonimos? Si es así, ¿cuál es su nombre?

## Ejercicio 6: BloodHound

Extrae la informacion de la red con SharpHound. Para ello

### 6.1. Abre una sesion RDP con el usuario jon.snow obtenido en el ejercicio 4 contra la máquina .22

### 6.2. Descarga SharpHound desde la máquina .22 y ejecútalo para extraer la información de la red

### 6.3. Analiza la información obtenida con BloodHound y ejecuta alguna de las siguientes queries:

[links](https://hausec.com/2019/09/09/bloodhound-cypher-cheatsheet/) (las que más rabia os den)

(con 2 ó 3 llega)

## Ejercicio 7: Responder

Extrae el hash NTLM de un usuario (en teoría el laboratorio genera conexiones cada x minutos, en caso
de que no apareciesen, podéis generar tráfico autenticándoos con las cuentas (robb.stark y eddard.stark))

Capturad ambos hashes y crackead la clave de robb.

## Ejercicio 8: Relay

Emplead el hash de eddard para realizar un relay con ntlmrelayx y obtener acceso a una máquina de la red.
¿Que nivel de acceso habéis conseguido?

## Ejercicio 9: Explica con tus palabras los siguientes términos:

- LDAP
- SMB
- TGT
- SPN
- DCSYNC
- LLMNR
- WPAD

### Ahora explica los siguientes ataques (De forma resumida):

- DCSYNC
- LLMNR poisoning
- WPAD poisoning
- NTLM relay
- Pass the hash
- Golden ticket

### Ahora explica:

- ¿Cómo podemos protegernos de un ataque de tipo Kerberoasting?
- ¿Y de un ataque de tipo DCSYNC?
- ¿Y de un ataque de tipo LLMNR poisoning?

## Ejercicio 10: Dump de credenciales

Emplea una herramienta (La de un kiwi muy educado) y obten los hashes de todas las cuentas que puedas.
(Puedes ejecutarla con un doble click en la VM directamente, prueba con varias vms si quieres)
¿Qué herramientas has empleado? ¿Qué cuentas has conseguido? Muestra los resultados obtenidos.