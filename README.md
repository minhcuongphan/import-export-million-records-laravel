## Project introduction
```

This is a small project that I created for those who are looking for a sample source code
which can help them to upload a csv file with millions of records.
Please follow the guide that I wrote below to set the project up.
Don't hesitate to ping me if you face any difficulty while installing the project.

link to download large csv files: https://excelbianalytics.com/wp/downloads-18-sample-csv-files-data-sets-for-testing-sales/

```

## Docker setup

```
#make a copy of env file
cp .env.example .env

#build docker
docker-compose up -d

#access php container
docker exec -it {app-name}-php bash
```

## Laravel install and build app

```
root@123456789abcde:/var/www/html#composer install
root@123456789abcde:/var/www/html#php artisan key:generate
root@123456789abcde:/var/www/html#php artisan migrate
root@123456789abcde:/var/www/html#npm i
```
