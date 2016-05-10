## Goal
This simple API framework is developed as candidate task for HolidayExtras : https://github.com/holidayextras/culture/blob/master/recruitment/developer-API-task.md
The only external framework used is Doctrine ORM. Currently using sqllite database.
The idea of the code architecture is to make it flexible for different consumers and different persistence tools (databases, file, InMemory ).
The API consumer protocol can be altered at run time. The request and the response roles are injected in the Controllers in run time, so neither controllers no Interactors (Use cases) depend on them.
The persistence layer is bounded via Application\Persistence\Repository Interface.
The concrete Web delivery mechanism, the API Protocols and the Persistence can be configured

## Things to be improved

The user Interactors for CRUD can be generalized for other Entities as long as basic CRUD is needed.
Component reorganization, as more code builds up, move classes in different components (directories).

#### Example HTTP requests:

get certain user GET http://host1.dev/user/2

delete user DELETE http://host1.dev/user/2

create user POST http://host1.dev/user

{"email":"john@mail.com","forename":"john","surname":"doe"}

update user PUT http://host1.dev/user/2

{"email":"jim@mail.com","forename":"jim","surname":"doeson"}

### Code components
 
The production code is in dir Application/ tests are dir Tests/
Dirs Controller/ and Delivery/ contain components responsible for the REST API
Dirs Interactor/ and Entity/ contain domain components i.e User CRUD
Dir Persistence contain components that provide mechanisms for storing and retrieving Entities.

### Biuld and CI

A build script ./build.sh can be used to run tests and source-code analysis tools: PDepend, PhpMd, phpcpd.
The results for continuous integration can be found in ./build/metrics/ 

### Tests

Interactors have the most code coverage near 100% since the domain logic is there. Entity Validators as well. Entities do not have test coverage since they are only data-structures with no behaviour.

There is e single integration test-case for getting e specific User, which runs all tha Application layers via the ControllerFactory as in index.php.
Integration tests are running without web server, they use only the database server.
The integration test-cases has been used as a tracer-bullets throughout the application development, mostly in refactoring to detect potential regression.
The integration (end-to-end) testing must be considered for future development with respects of set-up and restoring the database, PHPUnit_Extensions_Database_TestCase can be used.
The testing-database should not be tha same as the one used for production code.


### Installation

1. Checkout the repo

2. Database initial setting
   * Navigate to biuld/ 
   * Execute $ php createdb.php 
   * Use $ php selectallusers.php to list the records in user table.
   * Give write permissions to the database file: 
   ````````
   $ sudo chmod a+w holiday.sqlite

   ````````

3. Tests should be executable without web server.
   * cd Tests/
   * phpunit .

4. Set the web Virtual host, with DocumentRoot pointing to the current directory, where the index.php is located.
 
5. Add a rewrite rule that redirects all the requests to index.php. Apache example:
``````
NameVirtualHost *:80

<VirtualHost *:80>
	ServerName host1.dev
	ServerAdmin webmaster@localhost
	DocumentRoot /var/www/host1/

	ErrorLog ${APACHE_LOG_DIR}/host1.dev.error.log
	CustomLog ${APACHE_LOG_DIR}/host1.dev.access.log combined

<Directory "/">
    RewriteEngine on
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule . index.php [L]
</Directory>
</VirtualHost>
``````````


The API should be accessible through http requests, try out the Example HTTP requests above or use try it with swagger Swagger
