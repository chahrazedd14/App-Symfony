<?php

// src/Controller/BlogController.php
namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ListingController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    function list()
    {

        return new Response(
            'Hello world',
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/create_user", name="create_user")
     */
    public function createUser(): Response
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setUsername('admin');
        $user->setPassword(md5('123'));

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($user);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Nouvel utilisateur enregistré avec identifiant ' . $user->getId());
    }

    /**
     * @Route("/fetchuser", name="fetch_user")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function fetchUser(Request $request, MailerInterface $mailer)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);

        $post_data = json_decode($request->getContent(), true);

        $username = $post_data['username'];

        $password = md5($post_data['password']);

        $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 6);

        $user = $repository->findOneBy([
            'username' => $username,
            'password' => $password,
        ]);

        $entityManager = $this->getDoctrine()->getManager();
        $user1 = $entityManager->getRepository(User::class)->find(1);

        if (!$user1) {
            throw $this->createNotFoundException(
                'Aucun utilisateur trouvé pour identifiant 1'
            );
        } else {

            $user1->setConfirmationCode($code);
            $entityManager->flush();

            $email = (new Email())
                ->from('shera.mng@gmail.com')
                ->to('shera.mng@gmail.com')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                ->priority(Email::PRIORITY_HIGH)
                ->subject('C’est le code de vérification')
                ->html('<p>Celat juste a chaque fois de se connecter un code Pour entrer dans l’application</p><p>CODE: ' . $code . '</p>');

            $mailer->send($email);
        }

        return new Response(json_encode($user->getUsername()));
    }

    /**
     * @Route("/setconfirmationcode", name="set_confirmation_code")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function setConfirmationCode(Request $request, MailerInterface $mailer)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);

        $post_data = json_decode($request->getContent(), true);

        $confirmation_code = $post_data['confirmation_code'];

        $user = $repository->findOneBy([
            'confirmation_code' => $confirmation_code,

        ]);

        if (!$user) {
            throw $this->createNotFoundException(
                'Aucun utilisateur trouvé pour identifiant 1 ' . $confirmation_code
            );
        }


        return new Response(json_encode($user->getId()));
    }
}