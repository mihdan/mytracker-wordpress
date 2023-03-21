# About MyTracker

MyTracker — Analytics and attribution system for mobile apps and websites.

## Разработка

Чтобы развернуть плагин у себя, выполните эти простые действия:

```bash
# Перейдите в папку с плагинами
cd wp-content/plugins

# Склонируйте репозиторий в эту папку
git clone git@github.com:mihdan/mytracker.git

# Установите зависимости
composer install
```

После этого зайдите в админку WordPress и активируйте плагин.

## Полезные команды

Для проверки вашего кода на соответствие стандартам WP Coding Standards, выполните команду:

```bash
composer phpcs
```

Для автоматического исправления ошибок Code_Sniffer выполните команду:

```bash
composer phpcbf
```

Для запуска Unit-тестов выполните команду:

```bash
composer unit
```

Для анализа кода при помощи Psalm выполните команду:

```bash
composer psalm
```
