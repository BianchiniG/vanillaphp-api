-- drop database zipdev_test;
create database zipdev_test;
use zipdev_test;

create table phonebooks (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

create table phonebook_entries (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(256) NOT NULL,
  `last_name` varchar(256) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `phonebook_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY(phonebook_id) REFERENCES phonebooks(id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

create table phones (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone_number` text NOT NULL,
  `phonebook_entry_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY(phonebook_entry_id) REFERENCES phonebook_entries(id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

create table emails (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` text NOT NULL,
  `phonebook_entry_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY(phonebook_entry_id) REFERENCES phonebook_entries(id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

insert into phonebooks (`name`, `description`) values ('Test Phonebook 1', 'This is a test phonebook');
insert into phonebooks (`name`, `description`) values ('Test Phonebook 2', 'This is another test phonebook');
insert into phonebooks (`name`, `description`) values ('Test Phonebook 3', 'This is another test phonebook');

insert into phonebook_entries (`first_name`, `last_name`, `phonebook_id`) values ('Firstname 1', 'Lastname 1', 1);
insert into phonebook_entries (`first_name`, `last_name`, `phonebook_id`) values ('Firstname 2', 'Lastname 2', 2);
insert into phonebook_entries (`first_name`, `last_name`, `phonebook_id`) values ('Firstname 3', 'Lastname 3', 2);
insert into phonebook_entries (`first_name`, `last_name`, `phonebook_id`) values ('Firstname 4', 'Lastname 4', 3);

insert into phones (`phone_number`, `phonebook_entry_id`) values ('1111111111', 1);
insert into phones (`phone_number`, `phonebook_entry_id`) values ('2222222222', 1);
insert into phones (`phone_number`, `phonebook_entry_id`) values ('3333333333', 2);
insert into phones (`phone_number`, `phonebook_entry_id`) values ('4444444444', 2);
insert into phones (`phone_number`, `phonebook_entry_id`) values ('5555555555', 2);
insert into phones (`phone_number`, `phonebook_entry_id`) values ('6666666666', 3);

insert into emails (`email`, `phonebook_entry_id`) values ('test1@test.com', 1);
insert into emails (`email`, `phonebook_entry_id`) values ('test2@test.com', 2);
insert into emails (`email`, `phonebook_entry_id`) values ('test3@test.com', 3);
insert into emails (`email`, `phonebook_entry_id`) values ('test4@test.com', 4);