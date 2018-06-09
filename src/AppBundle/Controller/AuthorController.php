<?php
/**
 * Created by PhpStorm.
 * User: IlyaGoryachev
 * Date: 09.06.2018
 * Time: 23:17
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends Controller
{
    public function addAction($name)
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to your action: createAction(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $author = new Author();
        $author->setName($name);

        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($author);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return $this->render('author/add_message.html.twig', [
            'id' => $author->getId(),
        ]);
        //return new Response('Saved new author with id '.$author->getId());
    }

    public function deleteAction()
    {

    }

    public function listAction()
    {

    }
}