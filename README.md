# Тестове завдання

## Опис проекту
Цей проект є реалізацією eCommerce платформи на базі Laravel 8 з використанням MySQL, Redis для кешування та Elasticsearch для покращеної фільтрації та пошуку.

## 1. Реалізація перетину атрибутів Product і Variation для каталогу

Для реалізації перетину атрибутів `Product` і `Variation` була створена базова структура таблиць у базі даних.

### Міграції до бази даних
- **Продукти:** 
  - `./database/migrations/2024_08_02_055438_create_products_table.php`
- **Варіації:**
  - `./database/migrations/2024_08_02_055736_create_variations_table.php`

### Моделі
- **Product:** 
  - `./app/Models/Product.php`
- **Variation:** 
  - `./app/Models/Variation.php`

### Контролер
Для відображення товарів з доступними розмірами та цінами була створена функція у контролері:
- `./Http/Controllers/ProductController.php`

### Ресурси
Використано ресурси для форматування відповіді:
- `./Http/Resources/ProductResource.php`
- `./Http/Resources/VariationResource.php`

### Приклад відповіді
При запиті до `http://localhost:8080/api/products/sizes-prices` ви отримаєте дані у форматі JSON:

```json
{
    "data": [
        {
            "name": "T-Shirt 1",
            "brand": "Brand A",
            "season": "Summer",
            "color": "Red",
            "variations": [
                {"size": "XS", "price": "10.99"},
                {"size": "S", "price": "12.99"},
                {"size": "M", "price": "14.99"},
                {"size": "L", "price": "16.99"},
                {"size": "XL", "price": "18.99"}
            ]
        },
        {
            "name": "T-Shirt 2",
            "brand": "Brand B",
            "season": "Winter",
            "color": "Blue",
            "variations": [
                {"size": "XS", "price": "11.99"},
                {"size": "S", "price": "13.99"},
                {"size": "M", "price": "15.99"},
                {"size": "L", "price": "17.99"}
            ]
        }
    ]
}
```

## 2. Наповнення ElasticSearch для фільтрації каталогу

Для ефективної фільтрації каталогу товарів за допомогою ElasticSearch, реалізовано метод, що відповідає за індексацію продуктів та їх варіацій. Цей метод виконує наступні ключові завдання:

1. **Збір даних:** Метод отримує інформацію про товари та їх варіації безпосередньо з бази даних.
2. **Індексація:** Зібрані дані відправляються до ElasticSearch для створення індексу, що дозволяє здійснювати швидкий і ефективний пошук.
3. **Оновлення:** При внесенні змін до товарів, дані у ElasticSearch також оновлюються, що забезпечує їхню актуальність.

### Реалізація

#### Контролер
Метод індексації реалізовано у контролері:
- **Файл:** `./app/Http/Controllers/ProductController.php`
- **Метод:** `indexElasticSearch`

#### Сервіс
Для взаємодії з ElasticSearch був створений спеціальний сервіс, що спрощує роботу з API ElasticSearch:
- **Файл:** `./app/Services/ElasticSearchService.php`

#### Бібліотека
У проекті використовується бібліотека [elasticsearch/elasticsearch](https://github.com/elastic/elasticsearch-php) для роботи з ElasticSearch, що дозволяє ефективно реалізувати запити та управляти індексами.

## 3. Оновлення цін та розмірів Variation з ERP

Для забезпечення актуальності інформації про ціни та розміри Variation, які можуть змінюватись з ERP протягом дня, було реалізовано наступний процес:

### Кроки реалізації

1. **Створення API для прийому оновлень з ERP:**
   - Розроблено API, який приймає оновлення цін і розмірів Variation, що надходять від ERP-системи.
   - Це дозволяє автоматизувати процес оновлення даних без ручного втручання.

2. **Використання черг для асинхронної обробки оновлень:**
   - Використовуються черги для обробки оновлень асинхронно, що забезпечує високу продуктивність системи та зменшує навантаження на сервер.
   - Це дозволяє обробляти кілька оновлень одночасно, не блокуючи основний потік запитів.

3. **Оновлення бази даних та ElasticSearch:**
   - Після отримання нових даних з ERP, система автоматично оновлює інформацію в базі даних.
   - Окрім цього, дані в ElasticSearch також оновлюються, щоб зберегти їхню актуальність для фільтрації та пошуку.

### Реалізація

#### Контролер
Функція для прийому оновлень реалізована у контролері:
- **Файл:** `./app/Http/Controllers/Api/ProductController.php`
- **Метод:** `updateVariations`

#### Job
Для асинхронної обробки оновлень створено Job:
- **Файл:** `./app/Jobs/UpdateVariationJob.php`

