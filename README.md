# idx-pages

[![buddy pipeline](https://app.buddy.works/idx-boost/idxpages/pipelines/pipeline/281287/badge.svg?token=40ece3491b5fe7adfb1f636f051fa5ddbe679a1ba5f792f86fcde49a27e676ac "buddy pipeline")](https://app.buddy.works/idx-boost/idxpages/pipelines/pipeline/281287)

[GitHub](https://github.com/dgtalliance/IDXPages.git)

Technologies:
Framework: Symphony 5
Database: Mysql

# How To Install
cp .env .env.local

export APP_ENV=prod

export APP_DEBUG=0

composer install --no-dev --optimize-autoloader

# How To Configure Doctrine dbal
[GitHub](https://github.com/doctrine/dbal)

# Indications
* Configure the following in the config/packages/service.yml file:
 parameters:
    DB_HOST=host
    DB_USER=user
    DB_PASS=password
    DB_PORT=port
    DB_NAME=dbname
    
 services: 
   _defaults:
     autowire: true   
     autoconfigure: true 
     bind:
        $dbname: '%env(resolve:DB_NAME)%'
        $user: '%env(resolve:DB_USER)%'
        $password: '%env(resolve:DB_PASS)%'
        $host: '%env(resolve:DB_HOST)%'
        $driver: 'pdo_mysql'
        $port: '%env(resolve:DB_PORT)%'  
                
# Description of endpoints 
# 1-Endpoint-adAccountCreation: 
    #url: 
    #input: 
    #output: 
    
# 2-Endpoint-adAccountRetrieval:
    #url:  
    #input:  
    #output:  
    
