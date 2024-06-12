# Тестовое задание для компании Nemo.Travel

Необходимо разработать сервис для автозаполнения при поиске аэропортов.

## Требования
- Сервис должен предоставлять как минимум один метод — поиск аэропортов по части названия.
- Сервис должен быть готов к работе в условиях высокой нагрузки.
- Протокол API может быть любым - REST, GraphQL, Soap и т.д.
- Дополнительные методы сервиса, использование готовых библиотек, фреймворков, способ запуска сервиса, документирование, наличие авто-тестов — на усмотрение исполнителя.
- Решение необходимо предоставить в виде ссылки на git-репозиторий.

## Road Map
1. Развернуть Laravel проект, установить необходимые зависимости и настроить конфигурации.
   Буду использовать Laravel Sail для контейнеризации проекта, MySQL для базы данных, Redis для кэширования, Elasticsearch для поиска, Laravel Octane для повышения производительности.
   Время: **1 час**
2. Создать миграции для таблиц и соответствующие модели для них.
   Так как в одном файле находятся данные об аэропортах, городах и странах, я нормализую структуру и создам три отдельных таблицы и модели для данных сущностей.
   Время: **30 минут**
3. Импортировать данные из JSON файла и проиндексировать их.
   Получу данные из JSON файла, сделав запрос, и добавлю их в таблицы. После этого проиндексирую данные.
   Время: **2 часа**
4. Создание контроллера и метода в нём для поиска аэропортов.
   Для метода буду использовать GET запрос с query параметром для поиска. В самом методе поиск будет осуществляться с помощью Elasticsearch, также буду кэшировать запросы для уменьшения нагрузки на Elasticsearch.
   Время: **2 часа**
5. Проверка метода на высокие нагрузки. Дальнейшая оптимизация.
   Буду симулировать большое количество запросов для проверки метода. Если время ответа будет неудовлетворительным, то буду оптимизировать метод.  
   Время: **2 часа**
6. Написание документации и тестов для проверки работы метода. Исправление ошибок в случае обнаружения.
   Время: **2 часа**

Общее время в днях: **2 дня**