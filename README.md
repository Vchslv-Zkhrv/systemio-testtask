# Тестовое задание для systemeio

Как развернуть проект:
```bash
git clone git@github.com:Vchslv-Zkhrv/systemio-testtask.git zakharov  # клонируем репозиторий в папку 'zakharov'
cd zakharov  # переходим в папку
cp .env.example .env  # создаем .env
docker compose build  # собираем образы
make up  # поднимаем проект
make migrate  # запускаем миграции
make fixtures  # загружаем фикстуры
```

В ходе выполнения команды `make fixtures` будет выведен авторизационный токен с правами админа.
Используйте его в качестве значения заголовка `Authorization`, когда будете делать запросы
