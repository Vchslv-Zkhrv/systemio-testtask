# Тестовое задание для systemeio

Как развернуть проект:
```bash
# клонируем репозиторий в папку 'zakharov'
git clone git@github.com:Vchslv-Zkhrv/systemio-testtask.git zakharov

# переходим в папку
cd zakharov

# создаем .env
cp .env.example .env

# собираем образы
docker compose build

# поднимаем проект
make up

# устанавливаем зависимости
make install

# запускаем миграции
make migrate

# загружаем фикстуры
make fixtures
```

В ходе выполнения команды `make fixtures` будет выведен авторизационный токен с правами админа.
Используйте его в качестве значения заголовка `Authorization`, когда будете делать запросы
