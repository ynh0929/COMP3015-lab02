<?php

require_once 'Book.php';
require_once 'BookRepository.php';

function printBook(Book $book) {
    printf("\t- %s (with ISBN: %s) written by %s" . PHP_EOL,
        $book->getName(),
        $book->getInternationalStandardBookNumber(),
        $book->getAuthor()
    );
}

// Create a new BookRepository
$bookRepository = new BookRepository('book_store.json');

// Create some books
$lordOfTheRings = new Book('Lord of the rings', 'j.r.. tol', '9780358653035');
$briefHistoryOfTime = new Book('A Brief History of time', "Stephen Hawking", 'C');
$twentyThousandLeaguesUnderTheSea = new Book('20,000 Leagues Under the Sea', 'Jules Verne', '9781949460575');
$harryPotter1 = new Book("Harry Potter and the Philosopher's Stone", 'J.K. Rowling', '9782149460473');
$harryPotter2 = new Book('Harry Potter and the Chamber of Secret', "J.K. Rowling", "9781349480316");

// Save the new books in the repository
$bookRepository->saveBook($lordOfTheRings);
$bookRepository->saveBook($briefHistoryOfTime);
$bookRepository->saveBook($twentyThousandLeaguesUnderTheSea);
$bookRepository->saveBook($harryPotter1);
$bookRepository->saveBook($harryPotter2);

// Get them and loop over them
$books = $bookRepository->getAllBooks();
printf(PHP_EOL . "There are %d books saved to the store:" . PHP_EOL, count($books));
foreach ($books as $book) {
    printBook($book);
}

// Carry out some operations on the books in the repository
printf(PHP_EOL . "We will now carry out some update and delete operations on the store." . PHP_EOL . PHP_EOL);
echo 'Updating book with ISBN "9780358653035" (Lord of the Rings), to have the correct author and title.' . PHP_EOL;
$bookRepository->updateBook("9780358653035", new Book("The Lord of the Rings", "J.R.R. Tolkien", "9780358653035"));
$bookRepository->updateBook("9780553380163", new Book("A Brief History of Time", "Stephen Hawking", "9780553380163"));
$bookRepository->deleteBookByISBN("9780553380163");
$bookRepository->updateBook("9781349480316", new Book("Harry Potter and the Chamber of Secrets", "J.K. Rowling", "9781349480316"));
$bookRepository->deleteBookByISBN("9782149460473");

// Get the updated books and loop over them
$books = $bookRepository->getAllBooks();
$briefHistoryOfTimeResult = $bookRepository->getBookByTitle('A Brief History of Time');
$harryPotter2 = $bookRepository->getBookByTitle('Harry Potter and the Chamber of Secrets');
if ($briefHistoryOfTimeResult === null) {
    echo '"A Brief History of Time" is not in the repository' . PHP_EOL;
}
else if ($harryPotter2 === null) {
    echo '"Harry Potter and the Chamber of Secrets" is not in the repository' . PHP_EOL;
}

// Get the book by ISBN
$bookByISBN = $bookRepository->getBookByISBN('9780358653035');
if ($bookByISBN === null) {
    echo 'Book with ISBN "9780358653035" not found in the repository' . PHP_EOL;
} else {
    echo 'Book with ISBN "9780358653035" found:' . PHP_EOL;
    printBook($bookByISBN);
}

printf(PHP_EOL . "There are now %d books saved to the store:" . PHP_EOL, count($books));
foreach ($books as $book) {
    printBook($book);
}
