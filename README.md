# Vanilla PHP REST API
Welcome! This is a short termed project (3 days part-time, juggling between work, life and coding this little project!).

The app implements a simple phonebook where you can create entries inside of each of them (With N phones and N emails inside of each phonebook entry).

## Files and Directories
The following directories and files are present on the repository and are part of the app.

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

## TODO List
This is what I think it's missing from this project and will be soon added (Or maybe someday in the future!):

* GET endpoint receiving an id and returning a single record (This is an important one!).
* User authentication.
* Phonebook entries with images (Maybe through a service that encapsulates the images on a private folder).
* Migrate the database credentials to os env variables.
* Implement the backend on a non public folder so that the entire project is not scrappable.
