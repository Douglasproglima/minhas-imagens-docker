# Versão 3 da imagem Python oficial
FROM python:3

# Cria a pasta app dentro do dir /usr/src/
WORKDIR /usr/src/app

# o app.py será copiado para esse diretório
COPY fibonacci.py /usr/src/app

# Executa o app.py e força a estar no diretório onde esta o arquivo
# Porém como no WORKDIR setei o dir, então no CMD eu já estou no dir onde esta
# o arquivo a ser executado, então bastaria passar no segundo parametro
# fibonacci.pyCMD [ "python", "./fibonacci.py" ]
CMD [ "python", "/usr/src/app/fibonacci.py" ]