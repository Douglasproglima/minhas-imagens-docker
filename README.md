### Minhas Imagens Docker
---

<p align="center">
  <img src="https://blog.geekhunter.com.br/wp-content/uploads/2019/06/docker-na-pratica-como-construir-uma-aplicacao-2.png" width="320" alt="Docker Logo" />
</p>

<h1 align="center">
🚀 Imagens Testes - Learning 🚀
</h1>

<p align="center">
  <img alt="GitHub language count" src="https://img.shields.io/github/languages/count/Douglasproglima/minhas-imagens-docker">

  <img alt="Repository size" src="https://img.shields.io/github/repo-size/Douglasproglima/minhas-imagens-docker">

  <a href="https://github.com/Douglasproglima/minhas-imagens-docker/commits/main">
    <img alt="GitHub last commit" src="https://img.shields.io/github/last-commit/Douglasproglima/minhas-imagens-docker">
  </a>

  <a href="https://github.com/Douglasproglima/minhas-imagens-docker/issues">
    <img alt="Repository issues" src="https://img.shields.io/github/issues/Douglasproglima/minhas-imagens-docker">
  </a>

  <img alt="License" src="https://img.shields.io/badge/license-MIT-brightgreen">
</p>

### CRIANDO A PRÓPRIA IMAGEM
---
```sh
1 - Criar um container ubuntu
		docker -dti --name ubuntu-python ubuntu
	2 - Acessar o container e instalar o nano e o python
		docker exec -it ubuntu-python bash
		apt-get update
		apt-get install -y python3 nano
	3 - Criar um arquivo com extensao .py e abrir com o nano
		nano app.py
	3.1 - Dentro do nano:
		name = input("Whats your name brow? ")
		print (name)
		
	4 - Rodando o app python do lado de fora do container
		docker exec -it ubuntu-python python3 /opt/app.py
		
	/*A partir disso sei que consigo acessar do lado de fora o bash e ainda executar ferramentas que estão dentro do container*/
	Com isso posso criar o arquivo Dockerfile que me possibilita criar uma imagem e instalar ferramentas
```

#### MAO NA MASSA:
---
```sh	
#1 - Criar o diretório /minhas-imagens/app-python/
#2 - Dentro criar o arquivo app.py que irei copiar para dentro do container
$ nano app.py
#2.1 - Dentro do arquivo add
$ name = input("Whats your name brow? ")
$ print (name)
#3 - Criar o arquivo Dockerfile, esse é o arquivo responsável por criar a imagem
$ nano Dockerfile
			
  #OBS: Palavras reservadas do Dockerfile
  #*FROM -> Indica a base do container
  #*COPY -> O que será copiado para dentrodo container
  #*RUN  -> O que será executado dentro docontainer
  #*CMD  -> Comandos que serão executadoscom o bash
			
# 4 - Conteudo do Dockerfile
  #A base da imagem será a partir de uma imagem ubuntu
  FROM ubuntu
  
  # Comando a ser executado dentro da imagem | o && indica que será executado outros comandos
  RUN apt update && apt install -y python && apt clean
  
  # Copia o arquivo app.py para o diretório dentro do container
  COPY /home/douglasproglima/minhas-imagens/app-python/app.py /opt/app.py
  
  # Executa o app.py com o python3
  CMD python /opt/app.py
			
# 5 - Buildar a imagem a partir do Dockerfile
# 5.0 - Baixando a imagem:
$ docker pull python

# 5.1 - Estrutura: docker build diretorio-dockerfile -t nome-imagem
$ docker build -t ubuntu-python-dockerfile .
			
# 6 - Rodar o python a parti da imagem criada
$ docker run -it --name meu-app-py minha-img-ubuntu-py
```
#### EXEMPLO 2 - APACHE PERSONALIZADO
---
```sh
# 1 - Criar a pasta site:
$ mkdir minhas-imagens-docker/debian-apache/site
$ cd debian-apache/site
# 2 - baixar os arquivos:
$ wget http://site1368633667.hospedagemdesites.ws/site1.zip
			
#3 - Para enviar os arquivos compactados através do comando nativo do docker é necessário ter a extensão .tar
#como o arquivo baixado está como .zip, então descompacto e depois compacto com o tar novamente:
$ unzip site1.zip
$ rm -R site1.zip
$ tar -czf site.tar ./
# 4 - Copiar o site.tar para pasta anteriormente
$ cp site.tar ../
# 5 - Criar o arquivo Dockerfile e inserir o conteúdo:
  FROM debian

  RUN apt-get update && apt-get install -y apache2 && apt-get clean

  # Evita mais de uma sessão no mesmo container
  ENV APACHE_LOCK_DIR="/var/lock"
  # Contém o número de identificação do processo
  ENV APACHE_PID_FILE="/var/run/apache2.pid"
  # Usuário que irá executar o apache(www-data) - Não é aconselhavel o uso do usuário root
  ENV APACHE_RUN_USER="www-data"
  # Groupo de Usuário(www-data)
  ENV APACHE_RUN_GROUP="www-data"
  # Diretório de log
  ENV APACHE_LOG="/var/log/apache2"

  # Cópia o arquivo para o dir especificado e descompacta o arquivo
  ADD site.tar /var/www/html

  # Especifica a descrição do container
  LABEL description = "Apache Webserver 1.0"

  # Mapeamento do local dentro do container a onde os arquivos serão salvos
  VOLUME /var/www/html

  # Porta que será exposta o container
  EXPOSE 80

  # Informa qual aplicação irá rodar
  # Como será uma aplicação executada em primero plano, uso o entrypoint para informar o arquivo
  # onde terá o arquivo que será executado
  ENTRYPOINT ["/usr/sbin/apachectl"]

  # No CMD indica os parâmetros da aplicação que será executado em primeiro plano
  CMD ["-D", "FOREGROUND"]

# 6 - Gerar a imagem
# Estrutura: docker image build -t nome-image:versao ./diretorio-do-dockerfile
$ docker image build -t debian-apache:1.0 .
```
#### Imagem Debian-Apache Gerada:
____
Executando o procedimento acima, é gerado a imagem:
![Debian-Apache](./assets/images/1.png)

#### Criando o container debian-apache-container
```sh
# Subindo o container a partir da imagem criada
$ docker run -dti -p 80:80 --name debian-apache-container -m 900M --cpus 0.3 debian-apache:1.0

# Verificar o container:
$ docker ps

# Verificar o ip do container
$ docker network inspect bridge
```

#### Site no Ar
![NewWebite](./assets/images/2.png)

#### Container Python
---
##### Baixando a Imagem Oficial
```sh
$ docker pull python
```
##### DockerFile
![Dockerfile-python](./assets/images/2.1.png)

#### Criando a imagem
```sh
  $ docker image build -t img-fibonacci-python:1.0 .
```
![Image-Python](./assets/images/3.png)

#### Gerando o container:
```sh
  $ docker run -ti --name container-fibonacci-python img-fibonacci-python:1.0
```
![Container-Python](./assets/images/4.png)

#### Executando a aplicação:
```sh
  $ docker exec -it container-fibonacci-python python3 ./fibonacci.py
```
![App-Fibonacci](./assets/images/5.png)

### Usando Stages Diferentes
---
#### Objetivo:
  Objetivo é ter uma imagem do golang para gerar o binário executavel e outra imagem do ubuntu para executar o app gerado.

#### Baixando as Imagens:
```sh
# Imagem do Golang
$ docker pull golang

# Imagem do Alpine -> 5MB
$ docker pull alpine
```

#### Fibonacci em Golang
![File.go](./assets/images/6.png)

#### Dockerfile
![DockerFile-Go](./assets/images/7.png)

#### Gerando a Imagem
```sh
$ docker image build -t img-fibonacci-go:1.0 .
```
#### Executando o binário gerado
```sh
$ docker run -it --name container-fibonacci-go img-fibonacci-go:1.0
# OU
$ docker start container-fibonacci-go
$ docker exec -it container-fibonacci-go /bin/sh -c ./fibonacci.go
```
![Run-app.go](./assets/images/8.png)

### Subindo a Imagem para DockerHub
---
#### 1 - Criar a Conta no Dockerhub
<a href="https://hub.docker.com/" target="_blank">Dockerhub</a>

#### 2 - Logar na Conta Dockerhub
```sh
# No terminal logar na conta dockerhub
$ docker login
```

#### 3 - Gerar a Imagem
```sh
# No diretório Workspace-golang
# Estrutura: 
#docker build dir -t nome-usuario/nome-imagem:target
$ docker build . -t douglasproglima/img-fibonacci-golang:1.0
```
![Imagem-Docker](./assets/images/9.png)

### Criando um Servidor de Imagens Docker
---
#### Baixar a image do registry
```sh
$ docker pull registry
```
#### Criar o Container do Registry
```sh
$ docker run -d -p 5000:5000 --restart:always --name servidor-imagens-docker registry:2
```

#### Deslogar do Dockerhub
```sh
$ docker logout
```
#### Subir Imagem para o Servidor Docker Local
Para enviar a imagem para o servidor criado, é necessário que a imagem
começe com o nome do container criado com o Registry.
Nesse caso irei copiar a imagem e renomear a image do golang
Exemplo:
```sh
# docker image tag id_image ip-servidor:porta/nome-nova-imagem
$ docker image tag 40479ebeb05e localhost:5000/img-fibonacci-golang:2.0
# OU
$ docker image tag 40479ebeb05e ip-servidor:5000/img-fibonacci-golang:2.0

# Validar a copia da imagem:
$ docker images

# Checar se há imagens no servidor docker local e
# curl ip-servidor:porta/v2/_catalog
curl localhost:5000/v2/_catalog
# OU
curl ip-servidor:5000/v2/_catalog

# Habilitar segurança na rede interna para informar para o docker o envio de imgs
$ nano /etc/docker/daemon.json

# Add o conteúdo no arquivo:
{ "insecure-registries":["192.168.1.7:5000"] }

# Após alteração reinicie o docker
$ sudo service docker restart docker

# Subir a imagem para o servidor docker local
$ docker push localhost:5000/img-fibonacci-golang:2.0
#OU
$ docker push ip-servidor:5000/img-fibonacci-golang:2.0

# Conferir se está ok:
curl localhost:5000/v2/_catalog
# OU
curl ip-servidor:5000/v2/_catalog

# Remover e Baixar a Imagem do Servidor
$ docker rmi id-image -f

# Baixar a imagem do Servidor
$ docker pull ip-servidor:5000/img-fibonacci-golang:2.0

# Criar/Executar o Container
$ docker run -it --name container-fibonacci-go 192.168.1.7:5000/img-fibonacci-golang:2.0

# Nas demais vezes basta subir o container e executar o app
$ docker start id-container OU nome-container
$ docker exec -it id-container ou nome-container /bin/sh -c ./fibonacci.go
```
## Docker Compose
---
#### Compatibilidade
---
<a href="https://docs.docker.com/compose/compose-file/compose-versioning/">Versões Docker X Versões Docker-Compose</a>

####  Instalar Docker-Compose
---
```sh
$ sudo apt-get update

$ sudo apt-get install -y docker-compose
```

#### Exemplo YAML
---
![Estrutura-YAML](./assets/images/10.png)

### Criando 2 Containers
---
![Containers](./assets/images/11.png)

```sh
# Subir os dois containers:
$ docker-compose up
```

### NextCloud + Redis + MariaDB
---
```sh
# Criar a pasta nextcloud-redis-mariadb
mkdir nextcloud-redis-mariadb

cd nextcloud-redis-mariadb

touch docker-compose.yaml
```
#### docker-compose.yaml
---
Observação: Não usar a imagem mais recente do mariadb, pois o nextCloud está com BUG na integração com essa imagem.
```sh
version: '3.7'
services:
  nc:
    image: nextcloud:apache
    restart: always
    ports:
      - 80:80
    volumes:
      - nc_data:/var/www/html
    networks:
      - redisnet
      - dbnet
    environment:
      - REDIS_HOST=redis
      - MYSQL_HOST=db
      - MYSQL_DATABASE=nextcloud
      - MYSQL_USER=nextcloud
      - MYSQL_PASSWORD=nextcloud
  redis:
    image: redis:alpine
    restart: always
    networks:
      - redisnet
    expose:
      - 6379
  db:
    image: mariadb:10.5.11
    command: --transaction-isolation=READ-COMMITTED --binlog-format=ROW
    restart: always
    volumes:
      - db_data:/var/lib/mysql
    networks:
      - dbnet
    environment:
      - MYSQL_DATABASE=nextcloud
      - MYSQL_USER=nextcloud
      - MYSQL_ROOT_PASSWORD=nextcloud
      - MYSQL_PASSWORD=nextcloud
    expose:
      - 3306
volumes:
  db_data:
  nc_data:
networks:
  dbnet:
  redisnet:
```
#### NextCloud
![NextCloud](./assets/images/12.png)

#### Checando os arquivos criados no Volume
```sh
# Navegar na maquina hospedeira
$ cd /var/lib/docker/volumes/nextcloud-redis-mariadb_db_data/
$ ls
```
![Volumes NestCloud](./assets/images/13.png)

#### Docker Machine
---
Com o docker machine é possível realizar o deploy do ambiente local na infra que desejar, como por exemplo: AWS, Digital OCean, Google Cloud, Azure etc...

Com posso gerenciar máquinas na nuvem através da minha máquina local.

#### Instalação
---

<a href="https://github.com/docker/machine/releases/">Avaliar as realeses no resotório oficial do github</a>

<a href="https://docs.docker.com/machine/install-machine/">Doc Oficial</a>

```sh
$ curl -L https://github.com/docker/machine/releases/download/v0.16.2/docker-machine-`uname -s`-`uname -m` >/tmp/docker-machine &&
    chmod +x /tmp/docker-machine &&
    sudo cp /tmp/docker-machine /usr/local/bin/docker-machine
```

##### Principais Comandos
---
```sh
# CREATE: Por default se não for informado nenhum parâmetro, será criado uma máquina com 1024 mb de RAM, 20GB de HD e um core de CPU
$ docker-machine create nome-maquina
# OU
$ docker-machine create --driver virtualbox --virtualbox-disk-size "50000" --virtualbox-memory "2048" nome-maquina  

# LS: Irá listar todos os hosts disponíveis em sua máquina
$ docker-machine ls

# ENV: Irá listar todas as variáveis the ambiente que precisam ser configuradas
$ eval $(docker-machine env nome-maquina)

# IP: Lista todos os IPs do nosso host Docker
$ docker-machine ip nome-maquina

# SSH: Acessar via SSH o host Docker e executar comandos Linux dentro dele
$ docker-machine ssh nome-maquina

# INSPECT: Mostra alguns detalhes do host Docker
$ docker-machine inspect first-host-box

# START/STOP: Irá iniciar/parar o host Docker 
$ docker-machine start nome-maquina
# OU
$ docker-machine stop nome-maquina

# RM: Remove o host Docker
$ docker-machine rm nome-maquina
```

#### Criar Amazon Web Service EC2 Instance
---
https://docs.docker.com/machine/examples/aws/


#### Docker Swarm
Para criar máquina no EC2 pelo docker machine, existe uma série de passo.
1 - Criar o usuário com a permissão admin
Acessar https://console.aws.amazon.com/
Depois vá em IAM -> Novo Usuário

Anota os dados do novo usuário:
  usuário: nome-novo-user
  senha: nova-senha
  Politica: admin
  ID da chave de acesso: chave-de-acesso
  Chave de acesso secreta: chave-de-acesso-secreta

##### Criando a maquina no EC2
```
$ mkdir /home/seu-usuario-local/.aws
$ touch credentials
$ nano credentials
```

##### Corpo do arquivo:
[default]
aws_access_key_id = AKIA6ATJRN7F6N6Q555I
aws_secret_access_key = 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

```sh
$ cd .aws
$ docker-machine create --driver amazonec2 --amazonec2-region "sa-east-1" my-aws-vm
```

Validar a Maquina
```sh
$ docker-machine ls
```

Acessar a máquina:
```sh
$ docker-machine ssh my-aws-vm
```

```sh
#Cria os nodes no EC2
$ docker-machine create --driver amazonec2 --amazonec2-region "sa-east-1" dw1
$ docker-machine create --driver amazonec2 --amazonec2-region "sa-east-1" dw2
$ docker-machine create --driver amazonec2 --amazonec2-region "sa-east-1" dw3
```

Feito com ❤️ por Douglas Lima <img src="https://raw.githubusercontent.com/Douglasproglima/douglasproglima/master/gifs/Hi.gif" width="30px"></h2> [Entre em contato!](https://www.linkedin.com/in/douglasproglima)