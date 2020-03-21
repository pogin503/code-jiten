code-jiten
============

[![Join the chat at https://gitter.im/code-jiten/Lobby](https://badges.gitter.im/code-jiten/Lobby.svg)](https://gitter.im/code-jiten/Lobby?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

# Features
- 横断的に各言語の実行例を参照できる
- 言語ごとの処理の比較ができる。

# Description



# Requirement
- PHP7
- PostgreSQL 10.0

# Usage


# Installation
- git clone https://github.com/pogin503/code-jiten

```
nano config/database.php
```

```config/database.php
<?php
define('DB_DRIVER', 'pgsql');
define('DB_DATABASE', 'postgres');
define('DB_USERNAME', 'postgres');
define('DB_PASSWD', 'postgres');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8');
define('DB_PORT', '5432');
define('PDO_DSN', DB_DRIVER . ":host=" . DB_HOST . ";dbname=" . DB_DATABASE . ";port=" . DB_PORT);
?>
```

```docker-compose.yml
version: '3.1'

services:
  db:
    image: postgres
    restart: always
    environment:
      POSTGRES_PASSWORD: postgres
      POSTGRES_USER: postgres
      POSTGRES_DB: db
    ports:
      - 5432:5432
    volumes:
      - /Users/username/var/lib/postgresql/data:/var/lib/postgresql/data
```

# License
MIT

# Contributor

- icon: [@yamahare](https://twitter.com/yamahare)
