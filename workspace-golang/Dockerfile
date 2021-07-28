# Usa a imagem golang e apelido para exec para ser usado na imagem alpine
FROM golang as go-fibonacci

# Copia o arquivo fibonacci para dentro do container no dir /go/src/app/
COPY fibonacci.go /go/src/app/

# Variavel de ambiente - Recomendado para criar o executavel
ENV GO111MODULE=auto

# Diretório onde o app irá ser executado
WORKDIR /go/src/app/

# Pego o código e gera o arquivo executavel, RUN executa uma instrução e
# envia para 1 camada do dockerfile
RUN go build -o fibonacci.go .

# Próximo estágio, usa a imagem apine
FROM alpine

# Crio o diretório de trabalho no container do alpine
WORKDIR /app-exemplo

# Cópia o executavel da imagem exec (golang) para a imagem alpine
# Do diretório do exec(/go/src/app/) para o diretório alpine(/app-exemplo)
COPY --from=go-fibonacci /go/src/app/ /app-exemplo

# Uso o RUN para executar a mudança de permissão no diretório
# para que eu consiga executar o app com o chmod
RUN chmod -R 755 /app-exemplo

# Executa executavel binário com o bash através do entrypoint
ENTRYPOINT ./fibonacci.go