<?php


class Librarian
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function addLibrarian($employee_number, $fullname, $role)
    {
        $query = "INSERT INTO librarian (employee_number, fullname, role) VALUES (?, ?, ?)";

        $stmt = mysqli_prepare($this->mysqli, $query);
        mysqli_stmt_bind_param($stmt, "iss", $employee_number, $fullname, $role);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    // Add a new book to the books table
    public function addBook($title, $description, $thumbnail, $author, $genre, $return_date, $availability, $price)
    {
        $query = "INSERT INTO books (title, description, thumbnail, author, genre, return_date, availability, price ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->mysqli, $query);

        mysqli_stmt_bind_param($stmt, "ssbsssii", $title, $description, $thumbnail, $author, $genre, $return_date, $availability, $price);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    // Update book information in the books table
    public function updateBook($title, $description, $thumbnail, $author, $genre, $return_date, $availability, $price)
    {
        $query = "UPDATE books SET title=?, description=?, $thumbnail=?, author=?, genre=?, return_date=?, availability=?, price=? WHERE book_id=?";
        $stmt = mysqli_prepare($this->mysqli, $query);
        mysqli_stmt_bind_param($stmt, "ssbsssii", $title, $description, $thumbnail, $author, $genre, $return_date, $availability, $price);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    // Delete a book from the books table
    public function deleteBook($bookId)
    {
        $query = "DELETE FROM books WHERE book_id=?";
        $stmt = mysqli_prepare($this->mysqli, $query);
        mysqli_stmt_bind_param($stmt, "i", $bookId);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    // Get a list of all members
    public function getAllMembers()
    {
        $query = "SELECT * FROM member";
        $result = mysqli_query($this->mysqli, $query);
        $members = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
        return $members;
    }

    // Get books rented by a specific member
    public function getRentedBooksForMember($memberId)
    {
        $query = "SELECT b.* FROM rental r JOIN books b ON r.book_id = b.book_id WHERE r.member_id=?";
        $stmt = mysqli_prepare($this->mysqli, $query);
        mysqli_stmt_bind_param($stmt, "i", $memberId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $books = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
        return $books;
    }

    // Suspend a member account
    public function suspendMemberAccount($memberId)
    {
        $query = "UPDATE member SET status='suspended' WHERE member_id=?";
        $stmt = mysqli_prepare($this->mysqli, $query);
        mysqli_stmt_bind_param($stmt, "i", $memberId);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

}
?>