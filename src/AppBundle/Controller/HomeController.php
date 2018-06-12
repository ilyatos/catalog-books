<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Author;
use AppBundle\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    public function indexAction($page = 1)
    {
        $authorRep = $this->getDoctrine()->getRepository(Author::class);
        $bookRep = $this->getDoctrine()->getRepository(Book::class);
        $limit = 10;

        $authors = $authorRep->getAllByName($page, $limit);
        $books = $bookRep->getAllByTitle($page, $limit);

        $totalAuthors = $authors->count();
        $totalBooks = $books->count();

        if ($totalAuthors > $totalBooks) {
            $totalPages = ceil($totalAuthors / $limit);
        } else {
            $totalPages = ceil($totalBooks / $limit);
        }

        return $this->render('home/index.html.twig', [
            'authors' => $authors,
            'books' => $books,
            'pages' => $totalPages
        ]);
    }
}
