# Vanilla PHP REST API
Welcome! This is a short termed project (4 days part-time, juggling between work, life and coding this little project!).

The app implements a simple phonebook where you can create entries inside of each of them (With N phones and N emails inside of each phonebook entry).

On this readme you will find all the information about the project itself, files and endpoints first (It's advisable that you read it thoroughly to understand properly the project). And finally you will find two more sections, the "How to test" one and the "TODO List" one (They pretty much explain themselves with the title!).

## Application Class Structure
As you will see on the next section, on the [Entities](./app_files/Entities) folder you can find every Entity that this application needs to run. The class hierarchy is given as follows:
* A parent class called Entity.
* Child classes that inherits from Entity: Phonebook, PhonebookEntity, Phone, Email

Each of the child classes have a relationshil between them on the database:
* Phonebook ```has many``` PhonebookEntity
* PhonebookEntity ```has many``` Phone
* PhonebookEntity ```has many``` Email

## Files and Directories
When you clone this project, you will find the following directories and files:
```
    .
    ├── app_files
    │   ├── Config
    │   │   └── Database.php
    │   ├── Entities
    │   │   ├── Email.php
    │   │   ├── Entity.php
    │   │   ├── Logger.php
    │   │   ├── PhonebookEntry.php
    │   │   ├── Phonebook.php
    │   │   └── Phone.php
    │   ├── public
    │   │   └── api
    │   │       ├── phonebook
    │   │       │   ├── create.php
    │   │       │   ├── delete.php
    │   │       │   ├── read.php
    │   │       │   └── update.php
    │   │       └── phonebook_entry
    │   │           ├── create.php
    │   │           ├── delete.php
    │   │           ├── read.php
    │   │           └── update.php
    │   └── Utilities
    │       └── database_setup.sql
    ├── database_files
    │   ├── ...
    ├── docker-compose.yml
    ├── logs
    ├── README.md
    └── vanillaphp-api.postman_collection.json

```
### Explanation:
* ```app_files```: In here you can find every file that is necessary for the app to work.
    * ```Config```: Every configuration file needed for the app.
        * ```Database.php```: For now, in here you can only find the Database connection class (If you must change any credentials for the database, here's where you look)
    * ```Entities```: The 'backend' of the application is here.
        * ```Email.php```: Email class.
        * ```Entity.php```: Entity class (Parent of Email, Phone, Phonebook and PhonebookEntry).
        * ```Logger.php```: Helper class to log errors on the log files (Maybe this class could be in the Utilities folder!).
        * ```PhonebookEntry.php```: PhonebookEntry class.
        * ```Phonebook.php```: Phonebook class.
        * ```Phone.php```: Phone class.
    * ```public```: This is the folder that will be on the public side of the webserver (The frontend if you like).
        * ```api```: The api folder that brings us the /api/ on the url.
            * ```phonebook```: Phonebook endpoints!
                * ```create.php```
                * ```delete.php``` 
                * ```read.php```
                * ```update.php```
            * ```phonebook_entry```: PhonebookEntry endpoints!
                * ```create.php```
                * ```delete.php```
                * ```read.php```
                * ```update.php```
    * ```Utilities```: On this folder you can find every class or script needed as an utility for the application.
* ```database_files```: This folder is used for the data persistency between the docker container and the local filesystem (So you can restart the containers without losing data, basically).
* ```docker-compose.yml```: This file is used to build the local docker dev/testing environment.
* ```logs```: On this folder you can find the logs of the application (error.log, debug.log, info.log).
* ```README.ms```: The readme that you're reading!
* ```vanillaphp-api.postman_collection.json```: The postman collection to test the api.

## Endpoints
The available endpoints of the API are the following (The complete postman documentation can be found in [here]():
### Phonebook
* CREATE (POST): Create a new phonebook on the address <api_url>/api/phonebook/create.php.
Sample JSON:
```
    {
        "name": "New Phonebook",
        "description": "This is a new Phonebook",
        "phonebook_entries": [
            {
                "first_name": "Test First Name",
                "last_name": "Test Last Name",
                "phonebook_id": 1,
                "phone_numbers": [
                    "123456789",
                    "654321987"
                ],
                "emails": [
                    "test@testemail.com"
                ]
            }
        ]
    }
```
* READ (GET): Get all the phonebooks created on the address <api_url>/api/phonebook/read.php.
* UPDATE (PUT): Update a given phonebook on the address <api_url>/api/phonebook/update.php?id=<phonebook_id>. Sample JSON:
```
    {
        "id": 4,
        "name": "Updated",
        "description": "Updated Phonebook",
        "phonebook_entries": [
            {
                "id": 6,
                "phone_numbers": [
                    {
                        "id": 8,
                        "phone_number": "999666111"
                    }
                ],
                "emails": [
                    {
                        "id": 6,
                        "email": "updated.phonebook.new.user@gmail.com"
                    }
                ]
            }
        ]
    }
```
* DELETE (DELETE): Deletes a phonebook on the address <api_url>/api/phonebook/delete.php. 
The id of the phonebook to delete must be present on the body of the request. You can also specify if you want to delete cascading to the child entities of the phonebook (If not present, it assumes false for the cascade option).
```
    {
        "id": 4,
        "cascade": false
    }
```

### PhonebookEntry
* CREATE (POST): Create a new phonebook entry on the address <api_url>/api/phonebook_entry/create.php.
Sample JSON:
```
    {
        "first_name": "New First Name",
        "last_name": "New Last Name",
        "phonebook_id": 1,
        "phone_numbers": [
            "1234567890",
            "9876543210"
        ],
        "emails": [
            "new.user@gmail.com"
        ]
    }
```
* READ (GET): Get all the phonebooks entries created on the address <api_url>/api/phonebook_entry/read.php.
* UPDATE (PUT): Update a given phonebook entry on the address <api_url>/api/phonebook_entry/update.php?id=<phonebook_id>. Sample JSON:
```
    {
        "id": 5,
        "first_name": "Updated New First Name",
        "last_name": "Updated New Last Name",
        "phonebook_id": 1,
        "phone_numbers": [
            {
                "id": 7,
                "phone_number": "9999999999"
            }
        ],
        "emails": [
            {
                "id": 5,
                "email": "updated.new.user@gmail.com"
            }
        ]
    }
```
* DELETE (DELETE): Deletes a phonebook entry on the address <api_url>/api/phonebook_entry/delete.php. 
The id of the phonebook entry to delete must be present on the body of the request. You can also specify if you want to delete cascading to the child entities of the phonebook entry (If not present, it assumes false for the cascade option).
Sample JSON:
```
    {
        "id": 4,
        "cascade": false
    }
```

## Database
The database technology is MySQL. You can find the .sql file in [here]() and the tables structure is:
```
Phonebooks (
    name: varchar
    description: varchar
)

PhonebookEntries (
    first_name: varchar
    last_name: varchar
    phonebook_id: foreign key to Phonebooks
)

Phones (
    phone_number: varchar
    phonebook_entry_id: foreign key to PhonebookEntries
)

Emails (
    email: varchar
    phonebook_entry_id: foreign key to PhonebookEntries
)
```

## How to test
On this repository you can find a [docker-compose.yml](./docker-compose.yml) file that builds two containers, one for the app (With an apache server and php installed) and another for the database (MySQL) already bound into each other. So you should be able to test this application by only cding into the root repository folder and running:
```
docker-compose up -d
```
Or, if you don't want the terminal to be detached from the containers:
```
docker-compose up
```

## TODO List
This is what I think it's missing from this project and will be soon added (Or maybe someday in the future!):

* User authentication.
* Delete a single phone or email from a phonebook entry.
* Phonebook entries with images (Maybe through a service that encapsulates the images on a private folder).
* Migrate the database credentials to os env variables.
