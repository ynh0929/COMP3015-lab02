<?php

require_once 'Book.php';

class BookRepository {

    private string $filename;

    /**
     * @param string $theFilename
     */
    public function __construct(string $theFilename) {
        $this->filename = $theFilename;
    }

    /**
     * @return array of Book objects
     */
    public function getAllBooks(): array {
        // If the file doesn't exist, we have no books to read!
        if (!file_exists($this->filename)) {
            return [];
        }

        // If we get a falsy value back from file_get_contents, we won't have anything to parse to JSON
        $fileContents = file_get_contents($this->filename);
        if (!$fileContents) {
            return [];
        }

        // The string happens to be in JSON format, so pass it to json_decode
        // The 2nd parameter requests an associative array return value
        $decodedBooks = json_decode($fileContents, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return []; // A JSON error occurred in our parsing attempt -- return empty to the caller
        }

        // Filter out empty objects in the decoded array
        $decodedBooks = array_filter($decodedBooks);

        // Create an empty list and fill the Book objects with the JSON
        $books = [];
        foreach ($decodedBooks as $bookData) {
            $books[] = (new Book())->fill($bookData);
        }

        // Return the array of Books back to the caller
        return $books;
    }

    /**
     * @param Book $book
     */
    public function saveBook(Book $book): void {
        // Get existing books
        $books = $this->getAllBooks();

        // Check if the book already exists based on ISBN
        foreach ($books as $key => $existingBook)
        {
            if ($existingBook->getInternationalStandardBookNumber() === $book->getInternationalStandardBookNumber())
            {
                // Update the existing book with the new details
                $books[$key] = $book;

                // Encode and save to file
                $encodedBooks = json_encode(array_values($books), JSON_PRETTY_PRINT);
                file_put_contents($this->filename, $encodedBooks);

                return;
            }
        }

        // If the book doesn't exist, add it to the array
        $books[] = $book;

        // Encode and save to file
        $encodedBooks = json_encode(array_values($books), JSON_PRETTY_PRINT);
        file_put_contents($this->filename, $encodedBooks);
    }

    /**
     * Retrieves the book with the given ISBN, or null if no book with the specified ISBN is found.
     * Note: for the purposes of this lab you may return the first occurrence if there are multiple books with the same ISBN in the file.
     *
     * @param string $isbn
     * @return Book|null
     */
    public function getBookByISBN(string $isbn): Book|null {
        // Get all books
        $books = $this->getAllBooks();

        // Find the book by ISBN
        foreach($books as $book){
            if($book->getInternationalStandardBookNumber() === $isbn){
                return $book;
            }
        }
        return null;
    }

    /**
     * Retrieves the book with the given title, or null if no book of that title is found.
     * Note: for the purposes of this lab you may return the first occurrence if there are multiple books of the same title.
     *
     * @param string $title
     * @return Book|null
     */
    public function getBookByTitle(string $title): Book|null {
        // Get all books
        $books = $this->getAllBooks();

        //Find the book by Title
        foreach($books as $book){
            if($book->getName() === $title){
                return $book;
            }
        }
        return null;
    }

    /**
     * Updates the book in the file with the given $isbn (the contents of that book is replaced by $newBook in the file)
     * Hint: are you seeing the file have indexes added to the JSON? Look into https://www.php.net/manual/en/function.array-values.php
     * @param string $isbn
     * @param Book $newBook
     */
    public function updateBook(string $isbn, Book $newBook): void {
        // Get all books
        $books = $this->getAllBooks();

        //Find the book by ISBN
        foreach($books as $key => $book){
            if($book->getInternationalStandardBookNumber() === $isbn){
                // Update the book in the array
                $books[$key] = $newBook;

                // Encode and save to file
                $encodedBooks = json_encode(array_values($books), JSON_PRETTY_PRINT);
                file_put_contents($this->filename, $encodedBooks);

                return;
            }
        }
    }

    /**
     * Deletes the book in the file with the given $isbn.
     * Seeing indexes be added to the JSON? Look into https://www.php.net/manual/en/function.array-values.php
     * @param string $isbn
     */
    public function deleteBookByISBN(string $isbn): void {
        // Get all books
        $books = $this->getAllBooks();

        // Find the book by ISBN
        foreach($books as $key => $book){
            if($book->getInternationalStandardBookNumber() === $isbn){
                // Remove the book from the array
                unset($books[$key]);

                //Encode and save to the file
                $encodedBooks = json_encode(array_values($books), JSON_PRETTY_PRINT);
                file_put_contents($this->filename, $encodedBooks);

                return;
            }
        }
    }

}
