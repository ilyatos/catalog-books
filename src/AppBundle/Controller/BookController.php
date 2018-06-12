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

   /* public function addAction(EntityManagerInterface $em, Request $request)
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

        return $this->render('add_author_book.html.twig', [
            'form' => $form->createView(),
        ]);
    }*/

    /**
     * В своё опрадание за этот говнокод (хардкод) внизу скажу, что то, что наверху,
     * работало только для одного автора. Ну а может я просто ненавижу построители форм? Кто знает...
     *
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addAction(EntityManagerInterface $em, Request $request)
    {
        $data = $request->request->all();

        $authorsRep = $this->getDoctrine()->getRepository(Author::class);
        $authors = $authorsRep->findAll();

        if ($data) {
            $book = new Book();

            $book->setTitle($data['title']);
            $book->setNumberOfPages($data['numberOfPages']);
            $book->setIsbn($data['isbn']);
            $book->setPublishingYear($data['publishingYear']);

            foreach ($data['select'] as $authorId) {
                $author = $authorsRep->find($authorId);
                $author->addBook($book);
                $book->addAuthor($author);

                $em->persist($author);
                $em->persist($book);

                $em->flush();
            }

            return $this->redirectToRoute('homepage');
        }

        return $this->render('add_book.html.twig', [
            'authors' => $authors,
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