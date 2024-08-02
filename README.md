# тестове завдання

## 1. Як ви реалізуєте перетин атрибутів Product і Variation для каталогу? Наприклад, вам потрібно як і на скріншоті в каталозі товарів відображати доступні розміри і ціни.

Для реалізації перетину атрибутів Product і Variation, можна створити базову структуру таблиць у базі даних

Було створено міграції до бази даних

шлях до міграцій:

./database/migrations/2024_08_02_055438_create_products_table.php

./database/migrations/2024_08_02_055736_create_variations_table.php

Для отримання перетину атрибутів, можна використовувати Eloquent відносини у Laravel.

шлах до моделей:

./app/Models/Product.php
./app/Models/Variation.php

Для відображення товарів з доступними розмірами та цінами можна зробити функцію в контролері ./Http/Controllers/ProductController.
Також було використано Resource ./Http/Resources/ProductResource. та ./Http/Resources/VariationResource для відображення товарів з розмірами та цінами.

приклад відображення в форматі JSON:

http://localhost:8080/api/products/sizes-prices

"data": [
        {
            "name": "T-Shirt 1",
            "brand": "Brand A",
            "season": "Summer",
            "color": "Red",
            "variations": [
                {
                    "size": "XS",
                    "price": "10.99"
                },
                {
                    "size": "S",
                    "price": "12.99"
                },
                {
                    "size": "M",
                    "price": "14.99"
                },
                {
                    "size": "L",
                    "price": "16.99"
                },
                {
                    "size": "XL",
                    "price": "18.99"
                }
            ]
        },
        {
            "name": "T-Shirt 2",
            "brand": "Brand B",
            "season": "Winter",
            "color": "Blue",
            "variations": [
                {
                    "size": "XS",
                    "price": "11.99"
                },
                {
                    "size": "S",
                    "price": "13.99"
                },
                {
                    "size": "M",
                    "price": "15.99"
                },
                {
                    "size": "L",
                    "price": "17.99"
                }
            ]
        }
    ]


## 2. Як ви реалізуєте наповнення ElasticSearch для фільтрації каталогу?

Для наповнення ElasticSearch даними з каталогу товарів, можна створити спеціальний метод у контролері, який буде відповідати за індексацію продуктів та їх варіацій у ElasticSearch. Цей метод буде брати дані з бази даних та відправляти їх до ElasticSearch.

Приклад методу ./app/Http/Controllers/ProductController.php indexElasticSearch
Також було створено ./app/Services/ElasticSearchService.php для роботи з ElasticSearch
Була використана бібліотека elasticsearch/elasticsearch для роботи з ElasticSearch

## 3. Ціни та Розміри Variation можуть оновлюватись з ERP впродовж дня. Як ви будете оновлювати інформацію про зміну цих параметрів на сайті?

Для оновлення інформації про ціни та розміри Variation з ERP впродовж дня, можна реалізувати наступний процес:

1. Створити API для прийому оновлень з ERP
2. Використовувати черги для асинхронної обробки оновлень
3. Оновлювати базу даних та ElasticSearch при отриманні нових даних

Було створено ./app/Http/Controllers/Api/PriceController.php функція updateVariations для прийому оновлень з ERP
Також було створено ./app/Jobs/UpdateVariationsJob.php для асинхронної обробки оновлень



