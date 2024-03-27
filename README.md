
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
php bin/console ckeditor:install --tag=4.22.1
        // --> Répondre "drop" pour re-installer le package ckeditor
php bin/console assets:install public
```

