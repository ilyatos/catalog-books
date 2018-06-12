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

            $bookForm = $form->getData();

            $em->persist($bookForm);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }
        /*$book = new Book();
        $author = $this->getDoctrine()->getRepository(Author::class)->find(29);

        $book->setTitle('fff');
        $book->setIsbn('23232323');
        $book->setPublishingYear('2222');
        $book->setNumberOfPages('222');
        $book->addAuthor($author);
        $author->addBook($book);

        $em->persist($author);
        $em->persist($book);

        $em->flush();*/

        return $this->render('add_author_book.html.twig', [
            'form' => $form->createView(),
        ]);
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