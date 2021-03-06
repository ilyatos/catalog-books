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

        $form = $form = $this->createAuthorForm($authorForm, 'Добавить автора');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $authorForm = $form->getData();
            $name = $authorForm->getSecondName().' '.$authorForm->getFirstName().' '.$authorForm->getPatronymic();

            $author = new Author();

            if (!$this->getDoctrine()->getRepository(Author::class)->findBy(['name' => $name])) {
                $author->setName($name);

                $em->persist($author);
                $em->flush();

                return $this->redirectToRoute('homepage');
            } else {
                return $this->render('errors/403.html.twig');
            }
        }

        return $this->render('add_author_book.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit the author
     *
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(EntityManagerInterface $em, Request $request, $id)
    {
        $author = $this->getDoctrine()->getRepository(Author::class)->find($id);
        $authorForm = new AuthorForm();

        $authorName  = explode(' ', $author->getName());

        $authorForm->setFirstName($authorName[1]);
        $authorForm->setSecondName($authorName[0]);

        if (array_key_exists(2, $authorName)) {
            $authorForm->setPatronymic($authorName[2]);
        }

        $form = $this->createAuthorForm($authorForm, 'Отредактировать автора');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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

    /**
     * @param $authorForm
     * @param $submitLabel
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createAuthorForm($authorForm, $submitLabel) {
        return $this->createFormBuilder($authorForm)
            ->add('firstName', TextType::class, ['label' => 'Имя автора'])
            ->add('secondName', TextType::class, ['label' => 'Фамилия'])
            ->add('patronymic', TextType::class, ['label' => 'Отчество (при наличии)',
                'required' => false])
            ->add('save', SubmitType::class, ['label' => $submitLabel])
            ->getForm();
    }

}