<?php
/**
 * Created by PhpStorm.
 * User: IlyaGoryachev
 * Date: 09.06.2018
 * Time: 23:17
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends Controller
{
    /**
     * Post a new author to the DB
     *
     * @param EntityManagerInterface $em
     * @param $name
     * @return Response
     */
    public function addAction(EntityManagerInterface $em, $name)
    {
        $author = new Author();
        $author->setName($name);

        $em->persist($author);
        $em->flush();

        return $this->render('author/add_message.html.twig', [
            'id' => $author->getId(),
        ]);
    }

    public function deleteAction()
    {
        return new Response('null');
    }

    /**
     * Get all authors from the `author` table
     *
     * @return Response
     */
    public function listAction()
    {
        $authorRep = $this->getDoctrine()->getRepository(Author::class);

        $authors = $authorRep->findAll();

        return $this->render('author/list_of_authors.html.twig', [
          'authors' => $authors
        ]);
    }
}