# Ebook Store
Project for "Systems and Network Hacking" course @ University of Pisa

## Setup
First, you need to set up the settings file for the webserver:
```
$ cd website/public_html/includes
$ cp settings.php.sample settings.php
$ gedit settings.php # customize it as you need
```

Then, you can run it using docker-compose:
```
$ docker-compose up --build -d
```
This will start a webserver on port 80 and a mariadb server in a separate 
container.

Otherwise, copy the `website/public_html` folder in `/var/www` (in case of 
Apache).

## Project structure
 - `scripts`: contains the script used to scrape for ebooks
 - `sql`: contains SQL files:
    - `cron`: scripts to be called periodically to clean up DB
    - `initial_scripts`: scripts used to set up the DB (schema and initial values)
    - `migrations`: development-only
 - `website`: website-related files
    - `public_html`: website root
        - `includes`: PHP included files, not directly accessible 
            - `vendor`: external dependencies
        - `ebooks`: ebook storage, not directly accessible
        - `css`: included css files
        - `js`: included javascript files
    - `Dockerfile`: website Dockerfile
    - `startup.sh`: entry point for Docker container

## Authors
 - Zaccaria Essaid [@zaccaria97](https://github.com/zaccaria97)
 - Mirko Laruina [@mirko-laruina](https://github.com/mirko-laruina)
 - Riccardo Mancini [@manciukic](https://github.com/manciukic)
