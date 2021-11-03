<?php

// src/Controller/CalendarController.php
namespace App\Controller;

use App\Entity\Calendar;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{

    /**
     * @Route("/addevent123", name="add_event")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function addEvent(Request $request)
    {

        $post_data = json_decode($request->getContent(), true);

        $date = Date("Y-m-d", strtotime($post_data['date']));

        $event_name = $post_data['event_name'];
        $start_time = $post_data['start_time'];

        $end_time = $post_data['end_time'];

        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $calendar = new Calendar();
        $calendar->setDate($date);
        $calendar->setEventName($event_name);
        $calendar->setStartTime($start_time);
        $calendar->setEndTime($end_time);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($calendar);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response(json_encode($calendar->getId()));

    }

    /**
     * @Route("/getevent", name="get_event")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function getEvent(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Calendar::class);

        $post_data = json_decode($request->getContent(), true);

        $date = $post_data['date'];

        $calendar = $repository->findBy([
            'date' => $date,

        ]);

        if (!empty($calendar)) {

            $arr1 = array();

            foreach ($calendar as $key) {

                $arr = array('id' => $key->getId(), 'date' => $key->getDate(), 'event_name' => $key->getEventName(), 'start_time' => $key->getStartTime(), 'end_time' => $key->getEndTime());

                array_push($arr1, $arr);

            }

        } else {
            $arr1 = array();
        }
        return new Response(json_encode($arr1));

    }

}
