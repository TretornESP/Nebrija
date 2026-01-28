# Práctica 2: Hacking web

Entregar un informe en formato PDF (o .md) que contenga las respuestas
y las evidencias (capturas) de las siguientes preguntas

Nota: Cada respuesta se debe explicar detalladamente, incluyendo los comandos
preferiblemente formatados de la siguiente manera:

```bash
comando --opcion valor
# Explicación del comando
```

## Como lanzar el laboratorio

Como instalar docker en

- [Windows](https://docs.docker.com/desktop/install/windows-install/)
- [Mac](https://docs.docker.com/desktop/setup/install/mac-install/ )
- [Linux](https://docs.docker.com/desktop/setup/install/linux/)

Si queréis un tuto básico de docker compose podéis leer este:
- [Docker Compose Tutorial](https://dev.to/davidsabine/docker-compose-hello-world-1p14)


## Como usar el cookie grabber libre

Las urls son:

- http://tretornesp.com:8080/logs?key=cambiame # Ver logs para la key cambiame
- http://tretornesp.com:8080/push?key=cambiame&msg=pruebapruebaprueba # Guardar un log para la key cambiame

Si quereis levantar el vuestro, podéis usar el comando de python

```bash
python3 -m http.server 8080 #Poned el puerto que queráis
#Y luego usad la ip de vuestra máquina en lugar de tretornesp.com
#¡¡¡Si haceis esto cuidado con CORS, los resultados no serán validos!!!!!
```

```bash
#Clonamos el repositorio con el laboratorio
git clone https://github.com/TretornESP/Nebrija.git
#Nos movemos al directorio del laboratorio
cd Nebrija/p2/cybersecurity_lab
#Levantamos los contenedores con docker compose
docker compose up --build
#Tambien puedes usar docker-compose (con guion medio) si no tienes la version 2
#Si añades el flag -d el laboratorio se ejecutará en segundo plano y habra que parar los contenedores con docker compose down
#Si quieres aplicar algún cambio en el código fuente de la web, recuerda parar y volver a levantar los contenedores con el flag --build
```

Para lanzar el laboratorio debes tener disponibles los puertos 80, 8001-8006 en tu máquina local. (O cambiarlos en el docker compose)
Finalmente accede a la web del laboratorio en http://localhost:80

## Ejercicio 1: XSS

1. ¿Qué es un ataque XSS y cuáles son sus dos tipos principales? Explica sus diferencias.
2. Explota la web del laboratorio para realizar un ataque XSS que robe la cookie de sesión de un usuario.
Incluye capturas de pantalla y el código utilizado.
3. En el punto anterior usamos un cookie grabber hosteado en un servidor externo, ¿Que medidas de seguridad
se pueden implementar para evitar este tipo de ataques?
4. Parchea la vulnerabilidad XSS en la web del laboratorio explicando los cambios realizados en el código.

## Ejercicio 2: File upload

1. ¿Porqué una subida de ficheros tipo .php es más peligrosa que una subida de ficheros tipo .js si ambos ejecutan código?
2. Explota la web del laboratorio para subir un fichero malicioso que te permita ejecutar comandos en el servidor.
3. Parchea la vulnerabilidad de subida de ficheros explicando los cambios realizados en el código.

## Ejercicio 3: SQL Injection

1. Explica con tus palabras que es una ineycción SQL y cómo funciona.
2. Realiza una inyección SQL en la web del laboratorio para saltar el login de un usuario.
3. Introduce la query que se está ejecutando en el servidor al realizar la inyección SQL y explica cómo funciona.
4. Parchea la vulnerabilidad de inyección SQL explicando los cambios realizados en el código.

## Ejercicio 4: SSTI

1. ¿Qué es una vulnerabilidad SSTI y cómo puede ser explotada? ¿Que tipo de SSTI tenemos en el laboratorio?
2. Explota la web del laboratorio para ejecutar código en el servidor a través de una vulnerabilidad SSTI.
3. Parchea la vulnerabilidad SSTI explicando los cambios realizados en el código.

## Ejercicio 5: XXE

1. ¿Qué es una vulnerabilidad XXE y cómo puede ser explotada?
2. Explota la web del laboratorio para ejecutar el comando `id` a través de una vulnerabilidad XXE.
3. Ahora trata de obtener una reverse shell a través de la vulnerabilidad XXE.

## Ejercicio 6: CSRF

1. ¿Qué es una vulnerabilidad CSRF y cómo puede ser explotada?
2. Explota la web del laboratorio para cambiar el email de un usuario a través de una vulnerabilidad CSRF.
3. Parchea la vulnerabilidad CSRF explicando los cambios realizados en el código.

## Ejercicio 7: WAF

1. Seleccionad un WAF ¿Porqué habéis elegido ese?
2. Modifica la máquina nginx para añadir un Web Application Firewall (WAF) y repite las explotaciones anteriores en el codigo sin parches.
3. ¿Qué ataques son detectados y bloqueados por el WAF?