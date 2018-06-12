<?php
/**
 * Created by PhpStorm.
 * User: IlyaGoryachev
 * Date: 09.06.2018
 * Time: 23:18
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Author;
use AppBundle\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BookController extends Controller
{

    public function createAction(EntityManagerInterface $em)
    {
        $author = new Author();
        $author->setName('Lev');

        $book = new Book();
        $book->setTitle('TTT');
        $book->setPublishingYear(1997);
        $book->setIsbn('2-3-44-5');
        $book->setNumberOfPages(222);

        $book->addAuthor($author);
        $author->addBook($book);

        $em->persist($author);
        $em->persist($book);

        $em->flush();

        return null;
    }

}