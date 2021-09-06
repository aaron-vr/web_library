<?php

class DatabaseHelper
{
    const renew_incr = 7;
    const return_date = 14;
    const renew_allowed = 2;
    // Since the connection details are constant, define them as const
    // We can refer to constants like e.g. DatabaseHelper::username
    const username = ''; //use your oracle database username
    const password = ''; // use your oracle db password
    const con_string = ''; //use the name of your local oracle instance

    // Since we need only one connection object, it can be stored in a member variable.
    // $conn is set in the constructor.
    protected $conn;

    // Create connection in the constructor
    public function __construct()  {
        try {
            // Create connection with the command oci_connect(String(username), String(password), String(connection_string))
            $this->conn = oci_connect(
                DatabaseHelper::username,
                DatabaseHelper::password,
                DatabaseHelper::con_string
            );

            //check if the connection object is != null
            if (!$this->conn) {
                // die(String(message)): stop PHP script and output message:
                die("DB error: Connection can't be established!");
            }

        } catch (Exception $e) {
            die("DB error: {$e->getMessage()}");
        }
    }
    public function __destruct() {
        // clean up
        oci_close($this->conn);
    }

    public function SearchQuery($author_name, $item_title, $medium_id, $genre_id, $language) {

        $sql = "SELECT
                      *
                  FROM
                      searchquery s

                    WHERE
                        upper(author_name) LIKE upper('%{$author_name}%')
                        AND upper(item_title) LIKE upper('%{$item_title}%')";

        if ($medium_id != 0)
          $sql .= " AND medium_id = {$medium_id}";
        if ($genre_id != 0)
          $sql .= " AND genre_id = {$genre_id}";
        if ($language != 0)
          $sql .= " AND  (ph_lang_id = {$language} OR aud_lang_id = {$language})";

        // oci_parse(...) prepares the Oracle statement for execution
        // notice the reference to the class variable $this->conn (set in the constructor)
        $statement = oci_parse($this->conn, $sql);

        // Executes the statement
        oci_execute($statement);

        // Fetches multiple rows from a query into a two-dimensional array
        // Parameters of oci_fetch_all:
        //   $statement: must be executed before
        //   $res: will hold the result after the execution of oci_fetch_all
        //   $skip: it's null because we don't need to skip rows
        //   $maxrows: it's null because we want to fetch all rows
        //   $flag: defines how the result is structured: 'by rows' or 'by columns'
        //      OCI_FETCHSTATEMENT_BY_ROW (The outer array will contain one sub-array per query row)
        //      OCI_FETCHSTATEMENT_BY_COLUMN (The outer array will contain one sub-array per query column. This is the default.)
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        oci_free_statement($statement);

        return $res;
    }

    public function MediumDropdown() {
        $sql = "SELECT * FROM mediumdropdown";

        $statement = oci_parse($this->conn, $sql);

        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        oci_free_statement($statement);
        return $res;
    }

    public function GenreDropdown() {
        $sql = "SELECT * FROM genredropdown";

        $statement = oci_parse($this->conn, $sql);

        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        oci_free_statement($statement);
        return $res;
    }

    public function LanguageDropdown() {
        $sql = "SELECT * FROM languagedropdown";

        $statement = oci_parse($this->conn, $sql);

        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        oci_free_statement($statement);
        return $res;
    }

    public function Author($author_id) {
        $sql = "SELECT * from selectauthor WHERE author_id = {$author_id}";

        $statement = oci_parse($this->conn, $sql);

        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        oci_free_statement($statement);
        return $res;
    }
    /*fetch all the authors influenced by chosen author denoted by author_id*/
    public function Influencer($author_id) {
        $sql = "SELECT
                influenced,
                author_name
            FROM
                     influenced_by i
                INNER JOIN author a ON i.influenced = a.author_id
            WHERE
                influencer = {$author_id}";

        $statement = oci_parse($this->conn, $sql);

        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        oci_free_statement($statement);
        return $res;
    }

    /*fetch all the authors who have influenced this author denoted by author_id*/
    public function Influenced($author_id) {
        $sql = "SELECT
                influencer,
                author_name
            FROM
                     influenced_by i
                INNER JOIN author a ON i.influencer = a.author_id
            WHERE
                influenced = {$author_id}";

        $statement = oci_parse($this->conn, $sql);

        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        oci_free_statement($statement);
        return $res;
    }

    public function ItemData($item_id) {
        $sql = "SELECT
                it.item_id,
                item_title,
                item_edition,
                item_publisher,
                item_pub_year,
                medium_name,
                a.author_id,
                author_name,
                page_count,
                dig,
                book_qty,
                lang_name
            FROM
                     lib_item it
                INNER JOIN item_medium  m ON it.medium_id = m.medium_id
                LEFT JOIN authored_by  au ON it.item_id = au.item_id
                INNER JOIN author       a ON au.author_id = a.author_id
                INNER JOIN phys_book    b ON it.item_id = b.item_id
                INNER JOIN lang         l ON b.lang_id = l.lang_id
            WHERE
                it.item_id = {$item_id}";

        $statement = oci_parse($this->conn, $sql);

        oci_execute($statement);
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        oci_free_statement($statement);
        return $res;
    }
/*displays the item's genres on the item page*/
    public function ItemGenres($item_id) {
        $sql = "SELECT
                  genre_name
              FROM
                       lib_item it
                  INNER JOIN belongs_to  b ON it.item_id = b.item_id
                  INNER JOIN item_genre  g ON b.genre_id = g.genre_id
              WHERE
                  it.item_id = {$item_id}";

        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        oci_free_statement($statement);
        return $res;
    }
/*informs the user of the location of their book once they've borrowed it, that is, the location of the library where that book is waiting for them to pick it up*/
    public function ItemLocation($item_id, $language) {
        $sql = "SELECT
                  lib_name,
                  lib_street,
                  lib_postcode,
                  lib_city,
                  lib_country,
                  s.section_id AS section_id,
                  s.section_name AS section_name
              FROM
                       phys_book p
                  INNER JOIN lib          l ON p.lib_id = l.lib_id
                  INNER JOIN lib_section  s ON p.section_id = s.section_id AND p.lib_id = s.lib_id
                  WHERE item_id = {$item_id} AND lang_id = {$language}";

        $statement = oci_parse($this->conn, $sql);
        oci_execute($statement);
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        oci_free_statement($statement);
        return $res;
    }

    public function ItemPhysDig($item_id) {
      $sql = "SELECT
                p.item_id,
                dig,
                book_qty,
                la.lang_id as lang_id,
                lang_name
              FROM
                       lib_item l
                  INNER JOIN phys_book p ON l.item_id = p.item_id
                  INNER JOIN lang     la ON p.lang_id = la.lang_id
              WHERE
                  l.item_id = {$item_id}";

      $statement = oci_parse($this->conn, $sql);
      oci_execute($statement);
      oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
      oci_free_statement($statement);
      return $res;
    }
    /*used to notify the user of the existence of the book they're viewing in audio format */
    public function ItemAudio($item_id) {
      $sql = "SELECT
                narrator,
                lang_name
            FROM
                audio_book a
              INNER JOIN lang l on a.lang_id = l.lang_id
            WHERE
                item_id = {$item_id}";

      $statement = oci_parse($this->conn, $sql);
      oci_execute($statement);
      oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
      oci_free_statement($statement);
      return $res;
    }
    //this function registers the user with the credentials they inserted into the corresponding html form

    /*Register() attempts to register a new user with their inserted credentials*/
    //the only unique attribute is the email address which will, if already existent in the db, return an error on attempted duplicate input and that will be passed on to html via the $success variable
    public function Register($username, $email, $pass) {
        $sql = "INSERT INTO
                 user_account (username, email, user_password)
                VALUES ('{$username}', '{$email}', '{$pass}')";

        $statement = oci_parse($this->conn, $sql);

        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }
    /*checks if a user is attempting to register with an existing email address - always called before Register()*/
    public function ExistingUser($email) {
      $sql = "SELECT account_id FROM user_account WHERE email = '{$email}'";

      $statement = oci_parse($this->conn, $sql);
      oci_execute($statement);
      oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
      oci_free_statement($statement);
      return $res;
    }
    /*checks if a user's already borrowed the selected book in the selected language in which case they're not allowed to borrow the same book twice*/
    public function CheckQty($item_id, $language) {
      $sql = "SELECT
                item_id
            FROM
                phys_book
            WHERE
                    item_id = {$item_id}
                AND lang_id = {$language}
                AND book_qty > 0";

      $statement = oci_parse($this->conn, $sql);
      oci_execute($statement);
      oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
      oci_free_statement($statement);
      return $res;
    }
    public function AlreadyHasBookCheck($email, $item_id, $language) {
      $sql = "SELECT
                  COUNT(borrow_id) AS count
            FROM
                     user_account ac
                INNER JOIN borrowed_by  b ON ac.account_id = b.account_id
            WHERE
                    email = '{$email}'
                AND lang_id = {$language}
                AND item_id = {$item_id}
                AND return_date IS NULL";

      $statement = oci_parse($this->conn, $sql);
      oci_execute($statement);
      oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
      oci_free_statement($statement);
      return $res;
    }
    /*checks if the selected user has any overdue books for purposes of preventing them from borrowing new books in case they indeed have overdue books*/
    public function OverdueCheck($email) {
      $sql = "SELECT
                  COUNT(borrow_id) AS count
              FROM
                       user_account ac
                  INNER JOIN borrowed_by  b ON ac.account_id = b.account_id
              WHERE
                      email = '{$email}'
                  AND overdue_date <= current_date
                  AND return_date IS NULL";

      $statement = oci_parse($this->conn, $sql);
      oci_execute($statement);
      oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
      oci_free_statement($statement);
      return $res;
    }
    /*decrements the selected book's quantity in the phys_book relation as the first step in the borrowing process*/
    /*NOTE: QtyDecrement() is only to be executed if all 4 of the previous tests are passed: CheckQty(), Register(), AlreadyHasBookCheck(), OverdueCheck()*/
    public function QtyDecrement ($item_id, $language) {
      $sql = "UPDATE phys_book
                SET
                    book_qty = book_qty - 1
                WHERE
                        item_id = {$item_id}
                    AND lang_id = {$language}
                    AND book_qty > 0";
      $statement = oci_parse($this->conn, $sql);

      $success = oci_execute($statement) && oci_commit($this->conn);
      $affected_rows = oci_num_rows($statement);
      oci_free_statement($statement);
      return $affected_rows;
    }
    /*Borrow() finalizes the two-step borrowing process by adding a copy of the book to the user's borrowed books*/
    /*NOTE: Borrow() is only to be executed if QtyDecrement successfully decremented the corresponding book's quantity in the phys_book relation*/
    public function Borrow($email, $language, $item_id) {
      $sql = "INSERT INTO borrowed_by (
                    account_id,
                    item_id,
                    lang_id,
                    borrow_date,
                    return_date,
                    overdue_date
                ) VALUES (
                    (
                        SELECT
                            account_id
                        FROM
                            user_account
                        WHERE
                            email = '{$email}'
                    ),
                    {$item_id},
                    {$language},
                    CURRENT_DATE,
                    NULL,
                    CURRENT_DATE + " . DatabaseHelper::return_date . ")";

        $statement = oci_parse($this->conn, $sql);

        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }
    /*verifies the user's login information upon borrowing or logging in to examine borrowed books*/
    public function Login($email, $password) {
      $sql = "SELECT
                  account_id
              FROM
                       user_account ac
              WHERE
                      email = '{$email}'
                  AND user_password = '{$password}'";

      $statement = oci_parse($this->conn, $sql);
      oci_execute($statement);
      oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
      oci_free_statement($statement);
      return $res;
    }
    /*MyBooks() returns all of the data that is presented to the user in their MyBooks section (apart from authors which is obtained using SelectAuthors()*/
    public function MyBooks($email) {
      $sql = "SELECT
                username,
                lang_name,
                b.lang_id AS lang_id,
                b.item_id AS item_id,
                item_title,
                borrow_date,
                overdue_date,
                CASE
                  WHEN ( overdue_date <= current_date ) THEN
                      1
                  ELSE
                      0
              END AS overdue,
                CASE
                  WHEN ( overdue_date <= current_date + " . DatabaseHelper::renew_allowed . " ) THEN
                      1
                  ELSE
                      0
              END AS renew
            FROM
                     borrowed_by b
                INNER JOIN user_account  ac ON b.account_id = ac.account_id
                INNER JOIN lib_item      l ON b.item_id = l.item_id
                INNER JOIN lang          la ON b.lang_id = la.lang_id
            WHERE
                    email = '{$email}'
                AND return_date IS NULL";

      $statement = oci_parse($this->conn, $sql);
      oci_execute($statement);
      oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
      oci_free_statement($statement);
      return $res;
    }
    /*just returns the names of the authors who authored the selected item*/
    public function SelectAuthors ($item_id) {
      $sql = "SELECT
                author_name,
                au.author_id as author_id
            FROM
                     author a
                INNER JOIN authored_by au ON a.author_id = au.author_id
            WHERE
              item_id = {$item_id}";
      $statement = oci_parse($this->conn, $sql);
      oci_execute($statement);
      oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
      oci_free_statement($statement);
      return $res;
    }
    /*renews a borrowed book for a selected user and returns the number of affected rows*/
    public function Renew ($item_id, $language, $email) {
      $sql = "UPDATE borrowed_by
                SET
                    overdue_date = overdue_date + " . DatabaseHelper::renew_incr .
                "WHERE
                        account_id = (
                            SELECT
                                account_id
                            FROM
                                user_account
                            WHERE
                                email = '{$email}'
                        )
                    AND item_id = {$item_id}
                    AND lang_id = {$language}
                    AND return_date IS NULL";

      $statement = oci_parse($this->conn, $sql);
      $success = oci_execute($statement) && oci_commit($this->conn);
      $affected_rows = oci_num_rows($statement);
      oci_free_statement($statement);
      return $affected_rows;

    }
    /*QtyIncrement() and Return() are involved in the two-step process of returning a book. First, the corresponding book's qty in the phys_book relation is incremented (via QtyIncrement() ), and only if that is successful should the user's entry in the borrowed_by relation be timestamped with a valid return date (via Return() )*/
    public function QtyIncrement($item_id, $language) {
      $sql = "UPDATE phys_book
              SET
                  book_qty = book_qty + 1
              WHERE
                      item_id = {$item_id}
                  AND lang_id = {$language}";

      $statement = oci_parse($this->conn, $sql);
      $success = oci_execute($statement) && oci_commit($this->conn);
      $affected_rows = oci_num_rows($statement);
      oci_free_statement($statement);
      return $affected_rows;
    }
    public function Return ($item_id, $language, $email) {
      $sql = "UPDATE borrowed_by
                SET
                    return_date = current_date
                WHERE
                        account_id = (
                            SELECT
                                account_id
                            FROM
                                user_account
                            WHERE
                                email = '{$email}'
                        )
                        AND item_id = {$item_id} AND lang_id = {$language} AND return_date IS NULL";

      $statement = oci_parse($this->conn, $sql);
      $success = oci_execute($statement) && oci_commit($this->conn);
      $affected_rows = oci_num_rows($statement);
      oci_free_statement($statement);
      return $affected_rows;
    }

    // This function uses a SQL procedure to delete an account and automatically cascades constraints in cases where that row was referenced elsewhere
    public function DeleteAccount($email) {
      $sql = "BEGIN delete_user('{$email}'); END;";
      $statement = oci_parse($this->conn, $sql);
      oci_execute($statement);
      oci_free_statement($statement);
    }
}
