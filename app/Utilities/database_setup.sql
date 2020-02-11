create database zipdev_test;

create table phonebook (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

create table phone (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(256) NOT NULL,
  `last_name` varchar(256) NOT NULL,
  `phone_number` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `phonebook_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
  FOREIGN KEY(phonebook_id) REFERENCES phonebook(id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


insert into phonebook ('name', 'description') values ('Test Phonebook 1', 'This is a test phonebook');
insert into phonebook ('name', 'description') values ('Test Phonebook 2', 'This is another test phonebook');
insert into phonebook ('name', 'description') values ('Test Phonebook 3', 'This is another test phonebook');

insert into phone ('first_name', 'last_name', 'phone_number', 'phonebook_id') values ('Firstname 1', 'Lastname 1', '1111111111', 1);
insert into phone ('first_name', 'last_name', 'phone_number', 'phonebook_id') values ('Firstname 2', 'Lastname 2', '2222222222', 1);
insert into phone ('first_name', 'last_name', 'phone_number', 'phonebook_id') values ('Firstname 3', 'Lastname 3', '3333333333', 1);
insert into phone ('first_name', 'last_name', 'phone_number', 'phonebook_id') values ('Firstname 4', 'Lastname 4', '4444444444', 1);
insert into phone ('first_name', 'last_name', 'phone_number', 'phonebook_id') values ('Firstname 5', 'Lastname 5', '5555555555', 2);
insert into phone ('first_name', 'last_name', 'phone_number', 'phonebook_id') values ('Firstname 6', 'Lastname 6', '6666666666', 2);
insert into phone ('first_name', 'last_name', 'phone_number', 'phonebook_id') values ('Firstname 7', 'Lastname 7', '7777777777', 3);
