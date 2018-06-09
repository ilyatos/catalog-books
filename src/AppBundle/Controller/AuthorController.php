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
    public function addAction(EntityManagerInterface $em, $name)
    {
        $author = new Author();
        $author->setName($name);

        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($author);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return $this->render('author/add_message.html.twig', [
            'id' => $author->getId(),
        ]);
    }

    public function deleteAction()
    {
        return new Response('null');
    }

    public function listAction()
    {
        return new Response('null');
    }
}