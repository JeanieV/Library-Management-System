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
    public function updateBook($title, $description, $thumbnail, $author, $genre, $return_date, $availability, $price, $book_id)
    {
        $query = "UPDATE books SET title=?, description=?, thumbnail=?, author=?, genre=?, return_date=?, availability=?, price=? WHERE book_id=?";
        $stmt = mysqli_prepare($this->mysqli, $query);

        // Assuming availability and book_id are integers (i for integer type)
        mysqli_stmt_bind_param($stmt, "ssbsssiii", $title, $description, $thumbnail, $author, $genre, $return_date, $availability, $price, $book_id);

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