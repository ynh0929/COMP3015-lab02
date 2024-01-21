<?php

class Book  implements  JsonSerializable {

    private string $name;
    private string $authorName;
    private string $isbn;

    public function __construct(string $theName = '', string $theAuthor = '', string $theIsbn = '') {
        $this->name = $theName;
        $this->authorName = $theAuthor;
        $this->isbn = $theIsbn;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getAuthor(): string {
        return $this->authorName;
    }

    /**
     * @param string $authorName
     */
    public function setAuthor(string $authorName): void {
        $this->authorName = $authorName;
    }

    /**
     * @return string
     */
    public function getInternationalStandardBookNumber(): string {
        return $this->isbn;
    }

    /**
     * @param string $isbn
     */
    public function setInternationalStandardBookNumber(string $isbn): void {
        $this->isbn = $isbn;
    }

    /**
     * @param $bookData
     *  an associative array of book data e.g.
     *      [
     *          'name' => 'Lord of the Rings',
     *          'author' => 'J.R.R Tolkien',
     *          'isbn' => '9780358653035'
     *      ]
     */
    public function fill(array $bookData): Book {
        foreach ($bookData as $key => $value) {
            $this->{$key} = $value; // dynamically add properties to the Book object
        }
        return $this;
    }
    public function jsonSerialize(): array{
        return [
            'name' => $this->name,
            'authorName' => $this->authorName,
            'isbn' => $this->isbn,
        ];
    }
}
