# prestashop-8.x

# Вимоги до PHP та CMS
- На сайті повинна бути валюта гривня з Код ISO валюти "UAH"
- також повинен бути SSL

# Підготовка файла
- Завантажуєте модуль для вашої версї CMS [тут](https://github.com/rozetkapay/prestashop-8.x/archive/refs/heads/main.zip)
- Розпаковуєте архів
- В папці "prestashop-8.x-main" є тека "rozetkapay", цю папку потрібно заархівувати в ZIP з назвою "rozetkapay.zip"

# Встановлення
- Зайдіть в адмін панель
- Перейдіть "Модулі > Менеджер модулів"
- Натисніть кнопку "Завантажити модуль" та оберіть архів, який ви створили "rozetkapay.zip"
- Натисніть кнопку "Налаштувати"

# Налаштування модуля
На вкладці "Основні"
- потрібно в поля "Логін" та "Пароль" ввести відповідні дані, які ви отримали від RozetkaPay
- "Відправляти дані клієнта" та "і товара" - відповідають за відправку даних на сервера RozetkaPay

На вкладці "Статуси замовлень"
- "Перехід до оплати" та "В очікуванні" - зазвичай не використовуються (не рокомендується використовувати)
- "Успіх" - є обовʼязковими за замовчуванням "Платіж прийнято"
- "Невдача"- є обовʼязвовими за замовчуванням "Помилка оплати"

На вкладці "Вигляд". Тут налаштовується зовнішній вигляд, який буде бачити клієнт вашого сайта при оформленні замовлення
- "Назва(власна)" - відповідає за те, як буде називатися платіжний спосіб
- "Використовувати за замовчуванням назву" - якщо увімкнено, то ігнорується налаштування "Назва(власна)"
- "Відображати лого" - показувати/не показувати лого "RozetkaPay"


# PHP and CMS requirements
- The website must have the hryvnia currency with the ISO currency code "UAH"
- must also be SSL

# Preparing the file
- Download modules for your CMS version [here](https://github.com/rozetkapay/prestashop-8.x/archive/refs/heads/main.zip)
- Unpack the archive
- In the folder "prestashop-8.x-main" there is a package "rozetkapay", this folder must be archived in a ZIP with the name "rozetkapay.zip"

# Installation
- Go to the admin panel
- Go to "Modules > Module Manager"
- Click the "Download module" button and select the archive you created "rozetkapay.zip"
- Click the Customize button


# Module settings
On the "Basic" tab
- you need the relevant data you received from RozetkaPay in the "Login" and "Password" fields
- "Send customer data" and "and product" - correspond to sending data to the RozetkaPay server

On the "Order Statuses" tab
- "Proceed to payment" and "Pending" - usually not used (not recommended)
- "Success" - "Payment Accepted" is mandatory by default
- "Failure" - "Payment error" is mandatory by default

On the Appearance tab. The appearance is configured here. What the client of your website will see when placing an order
- "Name (proper)" - will tell you what the payment method will be called for
- "Use default name" - if enabled, ignores the "Name (own)" setting
- "Display logo" - show/do not show "RozetkaPay" logo