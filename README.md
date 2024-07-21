Задание
Сделать форму GeoIP поиска. Поиск будет осуществляться с помощью запроса в публичный веб-сервис (был выбран sypexgeo.net).

Сценарий
Пользователь вводит валидный IP, отправляется запрос в HL блок, если в HL блоке присутствует запись с данным IP, то данные отображаются из базы, если в базе нет нужного ip, то запрос отправляется на один из сервисов, пользователю показываются данные из сервиса и записываются в базу.

Требования

Оформить в виде компонента Битрикс используя D7;

Использовать стандартный http или soap клиент битрикс (использовал  \Bitrix\Main\Web\HttpClient() );

Создание HL блока реализовать через миграцию (https://marketplace.1c-bitrix.ru/solutions/sprint.migration/) (файл для создании базы лежит в php_interface/migrashion/Version20240720083439.php );

Валидация должна присутствовать как минимум на серверной стороне;

Обработка ошибок и исключений;

Оформить страницу презентабельно (использовал Bootstrap);

Выполнить задание, используя ajax-запросы;

Производить комментирование кода.

![image](https://github.com/user-attachments/assets/3249a580-595b-4ed3-b3a3-efd51787f737)
![image](https://github.com/user-attachments/assets/42050fe2-642d-4f07-adb3-3ff132ae053d)
