## Учебный проект «Менеджер задач» в рамках курса Hexlet (PHP-разработчик)

[![Actions Status](https://github.com/MT-cod/php-project-lvl4/workflows/hexlet-check/badge.svg)](https://github.com/MT-cod/php-project-lvl4/actions)
[![PHP%20CI](https://github.com/MT-cod/php-project-lvl4/workflows/PHP%20CI/badge.svg)](https://github.com/MT-cod/php-project-lvl4/actions)
<br>
[![Code Climate](https://codeclimate.com/github/MT-cod/php-project-lvl4/badges/gpa.svg)](https://codeclimate.com/github/MT-cod/php-project-lvl4)
[![Issue Count](https://codeclimate.com/github/MT-cod/php-project-lvl4/badges/issue_count.svg)](https://codeclimate.com/github/MT-cod/php-project-lvl4/issues)
[![Test Coverage](https://codeclimate.com/github/MT-cod/php-project-lvl4/badges/coverage.svg)](https://codeclimate.com/github/MT-cod/php-project-lvl4/coverage)

<h2>Цель</h2>

<p>Большое внимание в этом проекте уделяется созданию сущностей с помощью ORM и описанию связей между ними (o2m, m2m). Предстояло спроектировать модели и их отображение на базу данных. Благодаря этому появилась возможность повысить уровень абстракции и оперировать не сырыми данными, а связанными наборами объектов с удобным (семантическим) доступом к зависимым сущностям.</p>

<p>Наличие сущностей даёт более простую работу с тестами. Теперь тестовые данные создаются не руками, а с помощью механизма фабрик. Фабрики описывают формат данных и по запросу создают сущности, сразу добавляя их в базу.</p>

<p>Для большего уровня автоматизации, в проекте используется ресурсный роутинг, который позволяет унифицировать и упростить работу с типичными CRUD–операциями. Так вырабатывается правильный взгляд на формирование урлов их связь друг с другом.</p>

<p>Как только на сайте появляются пользователи с возможностью что-то создать, тут же возникает авторизация. Авторизация – процесс выдачи прав на действия над ресурсами и контроля их выполнения. Он часто задействуется при попытке изменить запрещенные вещи, например, настройки чужого пользователя. Механизм авторизации в Laravel встроен в сам фреймворк, настолько это важная вещь. В проекте авторизация отрабатывается на 100%.</p>

<p>Одна из важных и типовых задач в веб-разработке – создание форм для фильтрации данных. При неправильном подходе эта задача превращается в большой комок запутанного кода. Проект позволяет отработать этот момент, используя удобные библиотеки, которые показывают правильный путь решения данной задачи.</p>

<h2>Описание</h2>
<p>Менеджер задач – система управления задачами, подобная <a href="http://www.redmine.org/" target="_blank">http://www.redmine.org/</a>. Она позволяет ставить задачи, назначать исполнителей и менять их статусы. Для работы с системой требуется регистрация и аутентификация.</p>

## Развёрнутый проект на Heroku:
<a href="http://mt-cod-php-project-lvl4.herokuapp.com/">mt-cod-php-project-lvl4.herokuapp.com</a>

## Готовый docker-образ с проектом:
<a href="https://hub.docker.com/r/mtcod/php-project-lvl4">mtcod/php-project-lvl4</a>

###### Пример загрузки и запуска контейнера проекта:
<code>docker run -p 80:8000 -d mtcod/php-project-lvl4 php /srv/php-project-lvl4/artisan serve --host 0.0.0.0</code>
