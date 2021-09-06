insert into item_medium (medium_name)
values ('Journal');
insert into item_medium (medium_name)
values ('Article');
insert into item_medium (medium_name)
values ('Book');
insert into item_medium (medium_name)
values ('Magazine');
insert into item_medium (medium_name)
values ('Newspaper');
insert into item_medium (medium_name)
values ('Graphic Novel');
insert into item_medium (medium_name)
values ('Patent');

insert into lang (lang_id, lang_name)
values (1, 'English');
insert into lang (lang_id, lang_name)
values (2, 'Spanish');
insert into lang (lang_id, lang_name)
values (3, 'French');
insert into lang (lang_id, lang_name)
values (4, 'Italian');
insert into lang (lang_id, lang_name)
values (5, 'Russian');
insert into lang (lang_id, lang_name)
values (6, 'German');
insert into lang (lang_id, lang_name)
values (7, 'Ukranian');
insert into lang (lang_id, lang_name)
values (8, 'Polish');
insert into lang (lang_id, lang_name)
values (9, 'Dutch');
insert into lang (lang_id, lang_name)
values (10, 'Portuguese');

insert into lib (lib_id, lib_name, lib_street, lib_city, lib_country, lib_postcode)
values (1, 'Journalism and Computer Science', 'Waehringer Strasse 29', 'Vienna', 'AT', '1090');
insert into lib (lib_id, lib_name, lib_street, lib_city, lib_country, lib_postcode)
values (2, 'Biology and Botanics', 'Althan Strasse 14', 'Vienna', 'AT', '1090');
insert into lib (lib_id, lib_name, lib_street, lib_city, lib_country, lib_postcode)
values (3, 'Physics and Chemistry', 'Boltzmanngasse 5', 'Vienna', 'AT', '1090');

insert into lib_section (lib_id, section_id, section_name)
values (1, 1001, 'Books');
insert into lib_section (lib_id, section_id, section_name)
values (1, 1002, 'Magazines');
insert into lib_section (lib_id, section_id, section_name)
values (1, 1003, 'Architecture Textbooks');
insert into lib_section (lib_id, section_id, section_name)
values (2, 1001, 'Books');

insert into item_genre (genre_id, genre_name) 
values (1, 'Physics');
insert into item_genre (genre_id, genre_name) 
values (2, 'Chemistry');
insert into item_genre (genre_id, genre_name) 
values (3, 'Computer Science');
insert into item_genre (genre_id, genre_name) 
values (4, 'Geology');
insert into item_genre (genre_id, genre_name) 
values (5, 'Historical Fiction');
insert into item_genre (genre_id, genre_name) 
values (6, 'Science Fiction');
insert into item_genre (genre_id, genre_name) 
values (7, 'Astronomy');
insert into item_genre (genre_id, genre_name) 
values (8, 'Popular Science');
insert into item_genre (genre_id, genre_name) 
values (9, 'Artificial Intelligence');
insert into item_genre (genre_id, genre_name) 
values (10, 'History');
insert into item_genre (genre_id, genre_name) 
values (11, 'Art History');
insert into item_genre (genre_id, genre_name)
values (12, 'Web Development');

insert into lib_item (item_id, isbn, item_title, item_edition, item_publisher, item_pub_year, item_desc, medium_id)
values (1, '0-8053-5340-20', 'Object-Oriented Analysis and Design with Applications', 2, 'Benjamin/Cummings', 1994, NULL, 3);
insert into lib_item (item_id, isbn, item_title, item_edition, item_publisher, item_pub_year, item_desc, medium_id)
values (2, '978-007-128959-3', 'Database Systems Concepts', 6, 'McGraw-Hill', 2011, NULL, 3);
insert into lib_item (item_id, isbn, item_title, item_edition, item_publisher, item_pub_year, item_desc, medium_id)
values (3, '978-1-119-36644-7', 'JavaScript for Web Developers', 4, 'John Wiley ' || chr(38) ||' Sons', 2020, 'The ultimate JavaScript guide, updated to ECMAScript 2019', 3);
insert into lib_item (item_id, isbn, item_title, item_edition, item_publisher, item_pub_year, item_desc, medium_id)
values (4, '978-1-5093-0697-8', 'The Definitive Guide to Dax', 2, 'Pearson Education', 2020, 'Business intelligence with Microsoft Power BI, SQL Server Analysis Services, and Excel', 3);
insert into lib_item (item_id, isbn, item_title, item_edition, item_publisher, item_pub_year, item_desc, medium_id)
values (5, '978-1-119-46838-7', 'PHP, MySQL '|| chr(38) ||' JavaScipt', 10, 'John Wiley ' || chr(38) ||' Sons', 2018,'Learn the languages that run the web!', 3);

insert into author (author_id, author_name, author_birth, birthplace)
values (1, 'Grady Booch', date '1955-02-27', 'US');
insert into author (author_id, author_name, author_birth, birthplace)
values (2, 'Abraham Avi Silberschatz', date '1947-03-01', 'IL');
insert into author (author_id, author_name, author_birth, birthplace)
values (3, 'Henry F. Korth', date '1000-10-10', 'US');
insert into author (author_id, author_name, author_birth, birthplace)
values (4, 'S. Sudarshan', date '1000-10-10', 'IN');
insert into author (author_id, author_name, author_birth, birthplace)
values (5, 'Matt Frisbie', date '1000-10-10', 'US');
insert into author (author_id, author_name, author_birth, birthplace)
values (6, 'Marco Russo', date '1000-10-10', 'US');
insert into author (author_id, author_name, author_birth, birthplace)
values (7, 'Alberto Ferrari', date '1000-10-10', 'US');
insert into author (author_id, author_name, author_birth, birthplace)
values (8, 'Richard Blum', date '1000-10-10', 'US');

insert into phys_book (item_id, page_count, dig, book_qty, lang_id, lib_id, section_id)
VALUES (1, 589, 'Y', 1, 1, 1, 1001);
insert into phys_book (item_id, page_count, dig, book_qty, lang_id, lib_id, section_id)
VALUES (2, 1349, 'Y', 1, 1, 1, 1001);
insert into phys_book (item_id, page_count, dig, book_qty, lang_id, lib_id, section_id)
VALUES (3, 1145, 'Y', 2, 1, 1, 1001);
insert into phys_book (item_id, page_count, dig, book_qty, lang_id, lib_id, section_id)
VALUES (4, 739, 'Y', 3, 1, 1, 1001);
insert into phys_book (item_id, page_count, dig, book_qty, lang_id, lib_id, section_id)
VALUES (5, 774, 'Y', 3, 1, 1, 1001);

insert into audio_book (item_id, narrator, lang_id, audio_length)
values (1, 'Jonathan Davies', 1, 780);

insert into influenced_by (influencer, influenced)
values (1,2);

insert into authored_by (item_id, author_id)
values (1,1);
insert into authored_by (item_id, author_id)
values (2,2);
insert into authored_by (item_id, author_id)
values (2,3);
insert into authored_by (item_id, author_id)
values (2,4);
insert into authored_by (item_id, author_id)
values (3,5);
insert into authored_by (item_id, author_id)
values (4,6);
insert into authored_by (item_id, author_id)
values (4,7);
insert into authored_by (item_id, author_id)
values (5,8);

insert into belongs_to (genre_id, item_id) 
values (3, 1);
insert into belongs_to (genre_id, item_id) 
values (3, 2);
insert into belongs_to (genre_id, item_id) 
values (3, 3);
insert into belongs_to (genre_id, item_id) 
values (3, 4);
insert into belongs_to (genre_id, item_id) 
values (3, 5);
insert into belongs_to (genre_id, item_id)
values (12, 3);
