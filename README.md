Hello JarOfGreed

Redis для кеширования

Swagger (L5-Swagger) для документации API

**Функционал**

CRUD для сущности Категория (id, name, description, parent_id)

Вывод списка категорий в древовидной структуре

Кеширование дерева категорий (используется паттерн Proxy)

Инвалидация кеша при добавлении/редактировании/удалении категорий

Swagger-документация доступна по /api/documentation

Генерация тестовых данных через CategoryFactory

API Endpoints
Method	URL	Описание
GET	/api/categories	Список категорий в древовидном виде
GET	/api/categories/{id}	Получение категории по ID
POST	/api/categories	Создание категории
PUT	/api/categories/{id}	Обновление категории
DELETE	/api/categories/{id}	Удаление категории

Примеры запросов и схемы доступны в Swagger UI.

DTO

Используются DTO для передачи данных между контроллером и сервисом:

CreateCategoryDTO

UpdateCategoryDTO

CategoryResponseDTO

Тесты

CategoryTest проверяет:

CRUD операции

Построение дерева категорий

Кеширование и его инвалидацию

Запуск тестов:

php artisan test --filter=CategoryTest

**Docker**

Пример запуска локально через Docker Compose:

docker-compose up -d
docker exec -it app php artisan migrate --seed
docker exec -it app php artisan test

Контейнеры: app (Laravel), db (MySQL/Postgres), redis

**Примечания**

Кеширование реализовано через CachedCategoryServiceProxy.

Древовидная структура формируется рекурсивно в сервисе CategoryService.

Swagger документация автоматически генерируется через аннотации в контроллере.
