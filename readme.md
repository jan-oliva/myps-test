#Zprovoznění



#1. pomocí php CLI (console) (OS Debian)

composer install

zalozeni  DB a a uzivatele
konfigurace src/console/config/config.neon
konfigurace src/app/config/config.neon

struktura DB pomoci Doctrine console
cd src/console/doctrine
sudo -u www-data php doctrine.php orm:schema-tool:create

zakladni data - user,role, resources ACL
cd src/console/build
sudo -u www-data php role.php

#2. pomocí phing CLI console (OS Debian)

konfigurace src/app/config/config.neon
konfigurace src/console/config/config.neon

konfigurace phing  build.properties

**struktura DB pomoci Doctrine console
phing orm-create-schema
cd src/console/build
sudo -u www-data php role.php

#3. spusteni vyvojoveho prostredi pomocí vagrant a virtual box

Vagrant se staví virtuální stroj na Virtualboxu.
Na něm je pak kompletní vývojové prostředí.

konfigurace src/app/config/config.neon

konfigurace src/console/config/config.neon

konfigurace phing  build.properties

** prechod do rootu projektu
cd project dir
** sestaveni stroje s OS Debian vcetne apache, mysql vcetne usera a zalozeni DB,  zkopirovani projektu na virtual do /home/vagrant/project

vagrant up

vagrant ssh #plati pro Linux na win pouzit napr. putty, user vagrant, heslo vagrant

** root dir projektu. Absolutní cesta je /home/vagrant/project
cd project
** struktura DB pomoci Doctrine console

phing orm-create-schema

** zavedeni výchozích rolí a uživatelů
cd src/console/build

sudo -u www-data php role.php

Stroj je na pristupny na adrese 10.99.0.204
www - http://10.99.0.204
utils - http://10.99.0.204:8080 - mysql adminer apod.

#Nastaveni Netbeans

project  / propreties / Run configuration

Run as remote (FTP SFTP)
Project url http://10.99.0.204

Remote connection 10.99.0.204
Upload directory /project
Upload files On Save

Vytvoreni spojeni

remote connection tlacitko manage / ADD

name 10.99.0.204
host 10.99.0.204
User name vagrant
password vagrant
initial directory /home/vagrant

Po tomto nastaveni projektu se zacne pravým tlačitkem myši ve stromu projektu nabízet upload,
který přesune změněné soubory do virtualniho stroje do /home/vagrant/project



