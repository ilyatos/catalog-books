<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Author;
use AppBundle\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * Return lists of authors and books
     *
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page = 1)
    {
        $authorRep = $this->getDoctrine()->getRepository(Author::class);
        $bookRep = $this->getDoctrine()->getRepository(Book::class);
        $limit = 5;

        $authors = $authorRep->getAllByName($page, $limit);

        foreach ($authors as $author) {
            $exName = explode(' ', $author->getName());
            $name = $exName[0].' '.mb_substr($exName[1],0, 1).'.';
            if (array_key_exists(2, $exName)) {
                $name .= mb_substr($exName[2],0, 1).'.';
            }
            $author->setName($name);
        }

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
