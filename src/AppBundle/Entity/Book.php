<?php

namespace AppBundle\Entity;

/**
 * Book
 */
class Book
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var int
     */
    private $publishingYear;

    /**
     * @var string
     */
    private $isbn;

    /**
     * @var int
     */
    private $numberOfPages;


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
     * @param int $publishingYear
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
     * @return int
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
     * @param int $numberOfPages
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
     * @return int
     */
    public function getNumberOfPages()
    {
        return $this->numberOfPages;
    }
}
