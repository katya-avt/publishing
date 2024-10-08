<h1>Содержание</h1>
<ol>
<li><a href="#installation">Установка</a></li>
<li>
<a href="#launch">Запуск</a>
<ul style="list-style-type:disc">
<li><a href="#books_index">Получение всех книг (помимо полей книги, возвращать фамилию автора и наименование издательства)</a></li>
<li><a href="#authors_store">Создание нового автора</a></li>
<li><a href="#books_store">Создание книги с привязкой к существующему автору</a></li>
<li><a href="#publishers_update">Редактирование издателя</a></li>
<li><a href="#delete">Удаление книги/автора/издателя</a></li>
<li><a href="#fixture">Команда по наполнению БД тестовыми данными (несколько авторов/книг/издательств)</a></li>
<li><a href="#delete-authors-without-books">Команда по удалению всех авторов, у которых нет книг</a></li>
</ul>
</li>
</ol>

<h1 id="installation">Установка</h1>
<div align="justify">

```
git clone https://github.com/katya-avt/publishing.git
```

```
Запустить Docker Desktop
```

```
docker-compose up -d
```

```
docker-compose run composer install
```

```
docker-compose run php bin/console make:migration
```

```
docker-compose run php bin/console doctrine:migrations:migrate
```

```
docker-compose run php bin/console doctrine:fixtures:load
```

<p>Сайт доступен по адресу http://localhost:8000/</p>
<p>phpMyAdmin доступен по адресу http://localhost:8080/</p>
</div>

<h1 id="launch">Запуск</h1>

<h2 id="books_index">Получение всех книг (помимо полей книги, возвращать фамилию автора и наименование издательства)</h2>

<div align="justify">

```
curl -X GET 'localhost:8000/books'
```
</div>

<h2 id="authors_store">Создание нового автора</h2>

<div align="justify">

```
curl -X POST http://localhost:8000/authors -H 'Content-Type: application/json' -d '{"name": "Author Name","surname":"Author Surname"}'
```

<p>При попытке повторить запрос будет выдана ошибка: 
"Such an author already exists"</p>
</div>

<h2 id="books_store">Создание книги с привязкой к существующему автору</h2>

<div align="justify">

```
curl -X POST http://localhost:8000/books -H 'Content-Type: application/json' -d '{"name": "Book","publication_year": 2020,"publisher_id": 1,"author_id": 1}'
```

<p>При попытке повторить запрос будет выдана ошибка: 
"Such a book already exists"</p>

<p>При попытке указать год издания, 
превышающий текущий, 
будет выдана ошибка: 
"Publication year must not exceed the current year"</p>

<p>При попытке указать несуществующего издателя 
будет выдана ошибка: 
"Such a publisher not found"</p>

<p>При попытке указать несуществующего автора 
будет выдана ошибка: 
"Such an author not found"</p>
</div>

<h2 id="publishers_update">Редактирование издателя</h2>

<div align="justify">

```
curl -X PUT http://localhost:8000/publishers/1 -H 'Content-Type: application/json' -d '{"name": "Publisher","address": "House: House, Street: Street, City: City, Country: Country"}'
```

<p>При попытке повторить запрос будет выдана ошибка: 
"Such a publisher already exists"</p>

<p>При попытке указать адрес в неверном формате 
будет выдана ошибка: 
"Address must look like this (replace the values in {}): House: {House}, Street: {Street}, City: {City}, Country: {Country}"</p>

<p>При попытке обратиться к несуществующему 
издателю будет выдана ошибка 404 
(в метод контроллера передается 
App\Entity\Publisher, а не int).</p>
</div>

<h2 id="delete">Удаление книги/автора/издателя</h2>

<div align="justify">

```
curl -X DELETE http://localhost:8000/books/1
```

```
curl -X DELETE http://localhost:8000/authors/1
```

```
curl -X DELETE http://localhost:8000/publishers/3
```

<p>При попытке обратиться к несуществующим 
книге/автору/издателю будет выдана ошибка 404 
(в метод контроллера передается соответствующая 
Entity, а не int).</p>
</div>

<h2 id="fixture">Команда по наполнению БД тестовыми данными (несколько авторов/книг/издательств)</h2>

<div align="justify">

```
docker-compose run php bin/console doctrine:fixtures:load
```
</div>

<h2 id="delete-authors-without-books">Команда по удалению всех авторов, у которых нет книг</h2>

<div align="justify">

```
docker-compose run php bin/console app:delete-authors-without-books
```
</div>