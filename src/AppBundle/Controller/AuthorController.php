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
     * Post a new author
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

    /**
     * Get information about the author and his books
     *
     * @param $id
     * @return Response
     */
    public function profileAction($id)
    {
        $authorRep = $this->getDoctrine()->getRepository(Author::class);

        $author = $authorRep->find($id);
        $books = $author->getBooks();

        return $this->render('author/profile.html.twig', [
            'author' => $author,
            'books' => $books
        ]);
    }

    public function deleteAction(EntityManagerInterface $em, $id)
    {
        $authorRep = $this->getDoctrine()->getRepository(Author::class);

        $author = $authorRep->find($id);

        $em->remove($author);
        $em->flush();

        return $this->redirectToRoute('homepage');
    }

}