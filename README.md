
# INSTALLATION DU PROJET


```sh
git clone https://github.com/ndlaprovidence/fcpro

composer install

bin/console doctrine:database:create
bin/console doctrine:migrations:migrate
bin/console doctrine:fixtures:load
```

----------------------------------------------------

## Inutile depuis l'utilisation de TinyMCE : RESOUDRE LE PROBLEME DE CKEDITOR

```sh
bin/console ckeditor:install --tag=4.22.1
        // --> Répondre "drop" pour re-installer le package ckeditor
bin/console assets:install public

bin/console cache:clear
bin/console cache:clear --env=dev
```

