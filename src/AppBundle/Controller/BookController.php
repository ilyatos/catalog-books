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
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{

    public function createAction(EntityManagerInterface $em)
    {
        $author = new Author();
        $author->setName('Lev66');

        $book = new Book();
        $book->setTitle('RRR');
        $book->setPublishingYear(2018);
        $book->setIsbn('2-21-422-41');
        $book->setNumberOfPages(105);

        $author->addBook($book);
        $book->addAuthor($author);

        $em->persist($author);
        $em->persist($book);

        $em->flush();

        return new Response(null);
    }

    public function profileAction($id)
    {
        $bookRep = $this->getDoctrine()->getRepository(Book::class);

        $book = $bookRep->find($id);
        $authors = $book->getAuthors();

        return $this->render('book/profile.html.twig', [
            'book' => $book,
            'authors' => $authors
        ]);
    }

    public function deleteAction(EntityManagerInterface $em, $id)
    {
        $bookRep = $this->getDoctrine()->getRepository(Book::class);

        $book = $bookRep->find($id);

        $em->remove($book);
        $em->flush();

        return $this->redirectToRoute('homepage');
    }

}