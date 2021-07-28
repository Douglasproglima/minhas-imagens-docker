FROM ubuntu

RUN apt-get update && apt install -y python3 && apt clean

COPY app.py /opt/app.py

CMD python3 /opt/app.py

