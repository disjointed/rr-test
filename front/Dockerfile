FROM node:16

RUN git config --global user.email "test@rr.org"
RUN git config --global user.name "rr-test"

USER node

WORKDIR /home/node/app

CMD npm run serve

EXPOSE 8080
