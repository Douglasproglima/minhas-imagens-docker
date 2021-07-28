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