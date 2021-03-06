<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Book
 *
 * @ORM\Table(name="book")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookRepository")
 */
class Book
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=150)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="publishingYear", type="string", length=10)
     */
    private $publishingYear;

    /**
     * @var string
     *
     * @ORM\Column(name="isbn", type="string", length=100, unique=true)
     */
    private $isbn;

    /**
     * @var string
     *
     * @ORM\Column(name="numberOfPages", type="string", length=50)
     */
    private $numberOfPages;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Author", mappedBy="books")
     * @ORM\JoinTable(name="author_book")
     */
    private $authors;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Book
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set publishingYear.
     *
     * @param string $publishingYear
     *
     * @return Book
     */
    public function setPublishingYear($publishingYear)
    {
        $this->publishingYear = $publishingYear;

        return $this;
    }

    /**
     * Get publishingYear.
     *
     * @return string
     */
    public function getPublishingYear()
    {
        return $this->publishingYear;
    }

    /**
     * Set isbn.
     *
     * @param string $isbn
     *
     * @return Book
     */
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * Get isbn.
     *
     * @return string
     */
    public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * Set numberOfPages.
     *
     * @param string $numberOfPages
     *
     * @return Book
     */
    public function setNumberOfPages($numberOfPages)
    {
        $this->numberOfPages = $numberOfPages;

        return $this;
    }

    /**
     * Get numberOfPages.
     *
     * @return string
     */
    public function getNumberOfPages()
    {
        return $this->numberOfPages;
    }

    /**
     * Add author.
     *
     * @param Author $author
     *
     * @return Book
     */
    public function addAuthor(\AppBundle\Entity\Author $author)
    {
        //$author->addBook($this);
        $this->authors[] = $author;

        return $this;
    }

    /**
     * @param Author $author
     * @return $this
     */
    public function setAuthors(\AppBundle\Entity\Author $author)
    {
        $author->addBook($this);
        $this->authors[] = $author;

        return $this;
    }

    /**
     * Remove author.
     *
     * @param \AppBundle\Entity\Author $author
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAuthor(\AppBundle\Entity\Author $author)
    {
        return $this->authors->removeElement($author);
    }

    /**
     * Get authors.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAuthors()
    {
        return $this->authors;
    }
}
