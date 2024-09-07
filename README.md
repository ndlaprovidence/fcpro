
# INSTALLATION DU PROJET


```sh
git clone https://github.com/AlexandrePain1/fcpro.git

composer install


php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

----------------------------------------------------

## RESOUDRE LE PROBLEME DE CKEDITOR

```sh
bin/console ckeditor:install --tag=4.22.1
        // --> Répondre "drop" pour re-installer le package ckeditor
bin/console assets:install public

bin/console cache:clear
bin/console cache:clear --env=dev
```

