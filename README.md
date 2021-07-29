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

```sh

```

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
  docker network inspect bridge
```

#### Site no Ar
![NewWebite](./assets/images/2.png)

#### Container Python
---
##### Baixando a Imagem Oficial
```sh
  docker pull python
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
docker run -it --name container-fibonacci-go img-fibonacci-go:1.0
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

Feito com ❤️ por Douglas Lima <img src="https://raw.githubusercontent.com/Douglasproglima/douglasproglima/master/gifs/Hi.gif" width="30px"></h2> [Entre em contato!](https://www.linkedin.com/in/douglasproglima)