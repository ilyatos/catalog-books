<?php
/**
 * Created by PhpStorm.
 * User: IlyaGoryachev
 * Date: 09.06.2018
 * Time: 23:17
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Author;
use AppBundle\Entity\AuthorForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends Controller
{
    /**
     * Post a new author using FORM
     *
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function addAction(EntityManagerInterface $em, Request $request)
    {
        $authorForm = new AuthorForm();

        $form = $this->createFormBuilder($authorForm)
            ->add('firstName', TextType::class, ['label' => 'Имя автора'])
            ->add('secondName', TextType::class, ['label' => 'Фамилия'])
            ->add('patronymic', TextType::class, ['label' => 'Отчество (при наличии)',
                'required' => false])
            ->add('save', SubmitType::class, ['label' => 'Опубликовать автора'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $author = new Author();

            $authorForm = $form->getData();

            $name = $authorForm->getSecondName().' '.$authorForm->getFirstName().' '.$authorForm->getPatronymic();
            $author->setName($name);

            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('add_author_book.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function editAction(EntityManagerInterface $em, $id)
    {
        
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

    /**
     * Delete the author
     *
     * @param EntityManagerInterface $em
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(EntityManagerInterface $em, $id)
    {
        $authorRep = $this->getDoctrine()->getRepository(Author::class);

        $author = $authorRep->find($id);

        $em->remove($author);
        $em->flush();

        return $this->redirectToRoute('homepage');
    }



}