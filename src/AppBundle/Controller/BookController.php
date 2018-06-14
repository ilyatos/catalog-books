<?php
/**
 * Created by PhpStorm.
 * User: IlyaGoryachev
 * Date: 09.06.2018
 * Time: 23:18
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Author;
use AppBundle\Entity\AuthorForm;
use AppBundle\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{

    /**
     * Add a new book
     *
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addAction(EntityManagerInterface $em, Request $request)
    {
        $bookForm = new Book();

        $form = $this->createFormBuilder($bookForm)
            ->add('title', TextType::class, ['label' => 'Название книги: '])
            ->add('publishingYear', NumberType::class, ['label' => 'Год издания: '])
            ->add('isbn', TextType::class, ['label' => 'ISBN книги: '])
            ->add('numberOfPages', NumberType::class, ['label' => 'Количество страниц: '])
            ->add('authors',  EntityType::class, [
                'class' => 'AppBundle:Author',
                'choice_label' => 'name',
                'label' => 'Добавить авторов: ',
                'multiple' => 'true'
                ])
            ->add('save', SubmitType::class, ['label' => 'Добавить книгу'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookRep = $this->getDoctrine()->getRepository(Book::class);

            /**
             * @var Book $book
             */
            $book = $form->getData();

            if (!$bookRep->findBy([
                    'title'=> $book->getTitle(),
                    'publishingYear' => $book->getPublishingYear()
                ]) and !$bookRep->findBy(['isbn' => $book->getIsbn()])) {

                $authors = $book->getAuthors();

                foreach ($authors as $author) {
                    $author->addBook($book);
                    $book->addAuthor($author);

                    $em->persist($author);
                    $em->persist($book);

                    $em->flush();
                }
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
     * Get the book page
     *
     * @param $id
     * @return Response
     */
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

    /**
     * Edit the book
     *
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(EntityManagerInterface $em, Request $request, $id)
    {

        $bookForm = $this->getDoctrine()->getRepository(Book::class)->find($id);

        $form = $this->createFormBuilder($bookForm)
            ->add('title', TextType::class, ['label' => 'Название книги: '])
            ->add('publishingYear', NumberType::class, ['label' => 'Год издания: '])
            ->add('isbn', TextType::class, ['label' => 'ISBN книги: '])
            ->add('numberOfPages', NumberType::class, ['label' => 'Количество страниц: '])
            ->add('authors',  EntityType::class, [
                'class' => 'AppBundle:Author',
                'choice_label' => 'name',
                'label' => 'Добавить авторов: ',
                'multiple' => 'true'
            ])
            ->add('save', SubmitType::class, ['label' => 'Добавить книгу'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->remove($bookForm);
            $em->flush();
            /**
             * @var Book $bookForm
             */
            $book = $form->getData();

            $authors = $bookForm->getAuthors();

            foreach ($authors as $author) {
                $author->addBook($book);
                $book->addAuthor($author);

                $em->persist($author);
                $em->persist($book);

                $em->flush();
            }

            return $this->redirectToRoute('homepage');
        }

        return $this->render('add_author_book.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete the book
     *
     * @param EntityManagerInterface $em
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(EntityManagerInterface $em, $id)
    {
        $bookRep = $this->getDoctrine()->getRepository(Book::class);

        $book = $bookRep->find($id);

        $em->remove($book);
        $em->flush();

        return $this->redirectToRoute('homepage');
    }

}