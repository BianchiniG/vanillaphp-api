# Vanilla PHP REST API
Welcome! This is a short termed project (3 days part-time, juggling between work, life and coding this little project!).

The app implements a simple phonebook where you can create entries inside of each of them (With N phones and N emails inside of each phonebook entry).

## Files and Directories
The following directories and files are present on the repository and are part of the app.

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
This is what I think it's missing from this project (And maybe one day I'll add it):

* User authentication.
* Phonebook entries with images (Maybe through a service that encapsulates the images on a private folder).
* Migrate the database credentials to os env variables.
* Implement the backend on a non public folder so that the entire project is not scrappable.
