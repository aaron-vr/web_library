CREATE TABLE item_medium (
    medium_id    INT,
    medium_name  VARCHAR(255) NOT NULL,
    CONSTRAINT medium_pk PRIMARY KEY ( medium_id ),
    CONSTRAINT medium_unique UNIQUE ( medium_name )
);

CREATE TABLE lang (
    lang_id       INT ,
    lang_name     VARCHAR(255) NOT NULL,
    CONSTRAINT lang_unique UNIQUE (lang_name),
    CONSTRAINT lang_pk PRIMARY KEY (lang_id)
);

CREATE TABLE lib (
    lib_id        INT,
    lib_name      VARCHAR(255) NOT NULL,
    lib_street    VARCHAR(255) NOT NULL,
    lib_city      VARCHAR(255) NOT NULL,
    lib_country   CHAR(2) NOT NULL,
    lib_postcode  VARCHAR(255) NOT NULL,
    CONSTRAINT lib_pk PRIMARY KEY ( lib_id ),
    CONSTRAINT lib_unique UNIQUE ( lib_street,
                                   lib_postcode,
                                   lib_country )
);

CREATE TABLE lib_section (
    lib_id        INT,
    section_id    INT NOT NULL,
    section_name  VARCHAR(255) NOT NULL,
    CONSTRAINT lib_section_pk PRIMARY KEY ( lib_id,
                                            section_id ),
    CONSTRAINT lib_section_fk FOREIGN KEY ( lib_id )
        REFERENCES lib
            ON DELETE CASCADE
);

CREATE TABLE item_genre (
    genre_id    INT,
    genre_name  VARCHAR(255) NOT NULL,
    CONSTRAINT genre_pk PRIMARY KEY ( genre_id ),
    CONSTRAINT genre_unique UNIQUE ( genre_name )
);

CREATE TABLE author (
    author_id      INT,
    author_name    VARCHAR(255),
    author_birth   DATE,
    birthplace     VARCHAR(2),
    CONSTRAINT author_pk PRIMARY KEY ( author_id ),
    CONSTRAINT author_unique UNIQUE ( author_name )
);

CREATE TABLE user_account (
    account_id     INT
        GENERATED ALWAYS AS IDENTITY,
    username       VARCHAR(255) NOT NULL,
    email          VARCHAR(255) NOT NULL,
    user_password  VARCHAR(255) NOT NULL,
    CONSTRAINT account_pk PRIMARY KEY ( account_id ),
    CONSTRAINT account_unique UNIQUE ( email )
);

CREATE TABLE lib_item (
    item_id         INT,
    isbn            VARCHAR(255) NOT NULL,
    item_title      VARCHAR(255) NOT NULL,
    item_edition    INT NOT NULL,
    item_publisher  VARCHAR(255) NOT NULL,
    item_pub_year   INT NOT NULL,
    item_desc       VARCHAR(255),
    medium_id       INT,
    CONSTRAINT lib_item_fk FOREIGN KEY ( medium_id )
        REFERENCES item_medium
            ON DELETE CASCADE,
    CONSTRAINT lib_item_pk PRIMARY KEY ( item_id ),
    CONSTRAINT lib_item_isbn_unique UNIQUE ( isbn ),
    CONSTRAINT lib_item_edition_check CHECK ( item_edition > 0 ),
    CONSTRAINT lib_item_pub_year_check CHECK ( item_pub_year > 0
                                               AND item_pub_year < 2022 )
);

CREATE TABLE audio_book (
    item_id       INT,
    narrator      VARCHAR(255) NOT NULL,
    lang_id       INT,
    audio_length  INT NOT NULL,
    CONSTRAINT audio_book_pk PRIMARY KEY ( item_id, narrator ),
    CONSTRAINT audio_book_fk1 FOREIGN KEY ( item_id )
        REFERENCES lib_item
            ON DELETE CASCADE,
    CONSTRAINT audio_book_fk2 FOREIGN KEY ( lang_id )
        REFERENCES lang
            ON DELETE CASCADE,
    CONSTRAINT audio_book_check CHECK ( audio_length > 0 )
);

CREATE TABLE phys_book (
    item_id     INT,
    page_count  INT NOT NULL,
    dig         CHAR(1) NOT NULL,
    book_qty    INT NOT NULL,
    lang_id     INT,
    lib_id      INT,
    section_id  INT,
    CONSTRAINT phys_book_pk PRIMARY KEY ( item_id, lang_id ),
    CONSTRAINT phys_book_fk1 FOREIGN KEY ( item_id )
        REFERENCES lib_item
            ON DELETE CASCADE,
    CONSTRAINT phys_book_fk2 FOREIGN KEY ( lib_id,
                                           section_id )
        REFERENCES lib_section ( lib_id,
                                 section_id )
            ON DELETE CASCADE,
    CONSTRAINT phys_book_fk3 FOREIGN KEY ( lang_id )
        REFERENCES lang
            ON DELETE CASCADE,
    CONSTRAINT phys_book_pages_check CHECK ( page_count > 0 ),
    CONSTRAINT phys_book_dig_check CHECK ( dig = 'Y'
                                           OR dig = 'N' ),
    CONSTRAINT phys_book_qty_check CHECK ( book_qty >= 0 )
);

CREATE TABLE influenced_by (
    influencer  INT,
    influenced  INT,
    CONSTRAINT influenced_by_fk1 FOREIGN KEY ( influencer )
        REFERENCES author
            ON DELETE CASCADE,
    CONSTRAINT influenced_by_fk2 FOREIGN KEY ( influenced )
        REFERENCES author
            ON DELETE CASCADE,
    CONSTRAINT influence_unique UNIQUE ( influencer,
                                         influenced )
);

CREATE TABLE authored_by (
    item_id    INT,
    author_id  INT,
    CONSTRAINT auhtored_by_fk1 FOREIGN KEY ( item_id )
        REFERENCES lib_item
            ON DELETE CASCADE,
    CONSTRAINT authored_by_fk2 FOREIGN KEY ( author_id )
        REFERENCES author
            ON DELETE CASCADE,
    CONSTRAINT authored_by_unique UNIQUE ( item_id,
                                        author_id )
);

CREATE TABLE borrowed_by (
    borrow_id     INT
        GENERATED ALWAYS AS IDENTITY,
    account_id    INT NOT NULL,
    item_id       INT,
    lang_id       INT,
    borrow_date   DATE DEFAULT ( current_date ),
    return_date   DATE,
    overdue_date  DATE DEFAULT ( current_date + 7 ),
    CONSTRAINT borrow_pk PRIMARY KEY ( borrow_id ),
    CONSTRAINT borrowed_by_fk1 FOREIGN KEY ( account_id )
        REFERENCES user_account
            ON DELETE CASCADE,
    CONSTRAINT borrowed_by_fk2 FOREIGN KEY ( item_id, lang_id )
        REFERENCES phys_book
            ON DELETE CASCADE,
    --return_date_check should be made into an assertion
    CONSTRAINT return_date_check CHECK (return_date >= borrow_date)
);

CREATE TABLE belongs_to (
    item_id   INT,
    genre_id  INT,
    CONSTRAINT belongs_unique UNIQUE ( item_id,
                                       genre_id ),
    CONSTRAINT belongs_to_fk1 FOREIGN KEY ( genre_id )
        REFERENCES item_genre
            ON DELETE CASCADE,
    CONSTRAINT belongs_to_fk2 FOREIGN KEY ( item_id )
        REFERENCES lib_item
            ON DELETE CASCADE
);
--searchquery facilitates the display of library items to the user based on their search entries; filters are applied in the php function SearchQuery()
CREATE VIEW searchquery AS
    SELECT
    l.item_id       AS item_id,
    item_title,
    item_edition,
    item_pub_year,
    item_desc,
    au.author_id    AS author_id,
    author_name,
    l.medium_id     AS medium_id,
    b.genre_id      AS genre_id,
    ph.lang_id      AS ph_lang_id,
    aud.lang_id     AS aud_lang_id
FROM
         lib_item l
    INNER JOIN authored_by  au ON l.item_id = au.item_id
    INNER JOIN author       a ON au.author_id = a.author_id
    INNER JOIN belongs_to   b ON l.item_id = b.item_id
    INNER JOIN item_genre   g ON b.genre_id = g.genre_id
    INNER JOIN item_medium  m ON l.medium_id = m.medium_id
    LEFT OUTER JOIN phys_book    ph ON l.item_id = ph.item_id
    LEFT OUTER JOIN audio_book   aud ON l.item_id = aud.item_id
ORDER BY
    item_id ASC,
    author_id ASC;
                
CREATE VIEW mediumdropdown AS 
    SELECT DISTINCT
    i.medium_id,
    medium_name
FROM
         lib_item i
    INNER JOIN item_medium m ON i.medium_id = m.medium_id
GROUP BY (
    i.medium_id,
    medium_name
);

CREATE VIEW genredropdown AS
    SELECT
    b.genre_id,
    genre_name
FROM
         belongs_to b
    INNER JOIN item_genre g ON b.genre_id = g.genre_id
GROUP BY (
    b.genre_id,
    genre_name
);

CREATE VIEW selectauthor AS
    SELECT
    a.author_id AS author_id,
    author_name,
    l.item_id,
    item_title,
    author_birth
FROM
         author a
    INNER JOIN authored_by  au ON a.author_id = au.author_id
    INNER JOIN lib_item     l ON au.item_id = l.item_id;
    
CREATE VIEW languagedropdown AS 
        SELECT
        l.lang_id AS lang_id,
        lang_name
    FROM
             lang l
        INNER JOIN (
            SELECT
                lang_id
            FROM
                phys_book
            UNION
            SELECT
                lang_id
            FROM
                audio_book
        ) t ON l.lang_id = t.lang_id;
    


CREATE OR REPLACE PROCEDURE delete_user (user_email IN user_account.email%TYPE)
IS
BEGIN
  DELETE user_account where email = user_email;
  COMMIT;
END;
/







--sequence to support the trigger item_medium_aut_incr
CREATE SEQUENCE item_med_seq START WITH 1 INCREMENT BY 1;
--trigger created on the item_medium relation intended to emulate the effects of: GENERATED ALWAYS AS IDENTITY
CREATE OR REPLACE TRIGGER item_medium_aut_incr BEFORE
    INSERT ON item_medium
    FOR EACH ROW
    WHEN ( new.medium_id IS NULL ) -- IF/ELSE
BEGIN
    :new.medium_id := item_med_seq.nextval;
END;
/

