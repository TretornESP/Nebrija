# Práctica 3: Explotación

## Preparación del lab: 

1. Descarga y crea una máquina virtual con metasploitable
[link](https://sourceforge.net/projects/metasploitable/)

2. Conectala con la red del laboratorio de la semana pasada (solo requerido para los ejercicios 2.4 y 2.6)

3. Instalamos metasploit framework en bob (o si no haces el punto 2, en cualquier máquina atacante):

```bash
#Puede hacerse de varias formas, una de ellas es:
sudo snap install metasploit-framework
#Comprobamos que funciona con el comando msfconsole

bob@bob:/$ msfconsole
       =[ metasploit v6.4.88-dev-                               ]
+ -- --=[ 2,556 exploits - 1,310 auxiliary - 1,680 payloads     ]
+ -- --=[ 431 post - 49 encoders - 13 nops - 9 evasion          ]

Created by Rapid7 LLC

Metasploit Documentation: https://docs.metasploit.com/
The Metasploit Framework is a Rapid7 Open Source Project

msf >
```

Algunos comandos útiles de metasploit:

```bash
#Buscar exploits
search nombre_exploit_o_CVE
#Usar un exploit
use ruta/exploit
#Configurar opciones
show options # Las opciones dependen del exploit seleccionado, pero algunas comunes son:
#RHOSTS: IP de la máquina víctima
#RPORT: Puerto del servicio vulnerable
#LHOST: IP de la máquina atacante
#LPORT: Puerto de la máquina atacante
set opcion valor
#Ver payloads disponibles
show payloads 
#Ejecutar exploit
exploit
#Ver sesiones abiertas
sessions -l
#Conectar a una sesión
sessions -i id_sesion
```

4. Comprobar que llegamos tanto a metasploitable cómo a alice desde bob con un ping, comprobamos tambien por ssh:

```bash
#Usuario: msfadmin
#Contraseña: msfadmin
ssh -o HostKeyAlgorithms=+ssh-rsa -o PubkeyAcceptedAlgorithms=+ssh-rsa msfadmin@IP_METASPLOITABLE
```

## Ejercicios fase 1:

Nota importante: El formato de esta fase debe ser el de un reporte
de pentesting profesional. Por tanto, cada ejercicio debe estar documentado
con capturas de pantalla y explicaciones detalladas de cada paso realizado.
La idea es que sea **reproducible**.

Un reporte ideal debería incluir los siguientes apartados:

- Portada (Nombre del proyecto, autor, fecha, etc)
- Índice
- Resumen ejecutivo (Descripción breve de lo que se ha hecho y resultados obtenidos)
- Metodología (Alcance, Reglas del enfrentamiento, Descripción de las herramientas y técnicas utilizadas)
- Resultados (Descripción detallada de cada ejercicio realizado, con capturas de pantalla, comandos copiables y explicaciones)
- Conclusiones (Resumen de los hallazgos y recomendaciones)

1. Realizar un escaneo con nmap para descubrir los servicios que tiene abiertos
la máquina víctima. Guardar el resultado en un fichero de texto que adjuntarás
junto con las capturas de pantalla a modo de evidencia.

2. Utilizando la herramienta OpenVAS encuentra alguna vulnerabilidad apropiada 
(Puede tardar un buen rato...) indica su categoría, CVE asociado así cómo su CVSS
(explica punto a punto los criterios de cómputo del CVSS).


```bash
docker run -d -p 443:443 --name openvas mikesplain/openvas
#Acceder a https://localhost con usuario admin y la contraseña admin
```
Nota: La vulnerabilidad elegida debe ser explotable mediante algún módulo de Metasploit.
Nota2: Podemos ejecutar OpenVAS desde otra máquina si no queremos instalar docker en bob!

3. Utilizando Metasploit, explotar la vulnerabilidad encontrada en el servicio
anterior usando un payload de tipo bind. Indicar cuál es el exploit utilizado y
adjuntar capturas de pantalla que evidencien la explotación correcta del servicio.

4. Repetir el ejercicio anterior pero utilizando un payload de tipo reverse meterpreter.
Explica las diferencias entre bind y reverse y justifica la elección del tipo de payload.

Nota: Si el exploit que has seleccionado no soporta meterpreter siempre puedes hacer esto:

```bash
#Presiona ctrl+z y pon la session en segundo plano
use post/multi/manage/shell_to_meterpreter
#configura el lhost y la sesion
#exploit
```

### Aqui termina el reporte

5. Explica los siguientes conceptos con tus palabras:
- Exploit
- Payload
- Shellcode
- SETUID
- STAGED vs STAGELESS
- C2

6. (OPCIONAL) Utilizando la sesión de meterpreter conseguida en el ejercicio anterior, eleva
privilegios a root, finalmente obtén persistencia agregando tu clave pública al fichero
.ssh/authorized_keys de msfadmin. Adjuntar capturas de pantalla que evidencien la correcta elevación
de privilegios. Pista: Puedes usar una herramienta presente en la maquina victima que también hemos
usado en la máquina atacante.

Nota: Este ejercicio es de la semana que viene, si lo haces ahora ahorrarás trabajo en la siguiente práctica.

## Ejercicios fase 2:

1. Usando msfvenom crea un payload de tipo reverse shell meterpreter en formato exe para Windows.

2. Una vez establecida la conexión con meterpreter, migra a otro proceso y eleva privilegios a SYSTEM.

3. Súbe tu payload a virustotal y comprueba cuántos antivirus lo detectan. Modificalo usando el encoder
shikata ga nai y vuelve a subirlo a virustotal. ¿Cuántos antivirus lo detectan ahora? Juega con diferentes
técnicas de ofuscación y trata de conseguir que ningún antivirus lo detecte (no tienes porque conseguir
detección cero, pero sí reducir mucho el número de detecciones) (tienes que demostrar que sigue funcionando)

4. Lanza un ataque de tipo browser autopwn sobre alice de la práctica 1. Si no tienes éxito (caso probable)
puedes utilizar la técnica de sustitución de descargas que vimos en la práctica 1 para forzar la descarga
de un payload creado con msfvenom.

Nota: No tener éxito es, logré lanzar el ataque, pero la explotación falló porque no había ninguna vulnerabilidad

6. Si tuvieses que hacer tu propio canal de comunicaciones para controlar máquinas infectadas, ¿Cómo podrías
evitar ser detectado por un IDS/IPS/NGFW?

7. (OPCIONAL) Ahora utiliza cualquiera de las máquinas comprometidas cómo bouncer para navegar por internet de forma anónima.
Nota: Este ejercicio es de la semana que viene, si lo haces ahora ahorrarás trabajo en la siguiente práctica.
