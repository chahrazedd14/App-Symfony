<?php

// src/Controller/CalendarController.php
namespace App\Controller;

use App\Entity\Todolist;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodolistController extends AbstractController
{

    /**
     * @Route("/addtodolist123", name="add_todo")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function addTodo(Request $request)
    {

        $post_data = json_decode($request->getContent(), true);

        $task_title = $post_data['task_title'];
        $task_description = $post_data['task_description'];
        $task_priority = $post_data['task_priority'];

        $task_status = $post_data['task_status'];

        $task_timestamp = Date("Y-m-d H:i:s");

        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $todo = new Todolist();
        $todo->setTaskTitle($task_title);
        $todo->setTaskDescription($task_description);
        $todo->setTaskPriority($task_priority);
        $todo->setTaskStatus($task_status);
        $todo->setTaskTimestamp($task_timestamp);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($todo);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response(json_encode($todo->getId()));

    }

    /**
     * @Route("/gettodo", name="get_todo")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function getTodo(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Todolist::class);

        $post_data = json_decode($request->getContent(), true);

        $todo = $repository->findAll();

        if (!empty($todo)) {

            $arr1 = array();

            foreach ($todo as $key) {

                $arr = array('id' => $key->getId(), 'task_title' => $key->getTaskTitle(), 'task_description' => $key->getTaskDescription(), 'task_priority' => $key->getTaskPriority(), 'task_status' => $key->getTaskStatus(), 'task_timestamp' => $key->getTaskTimestamp());

                array_push($arr1, $arr);

            }

        } else {
            $arr1 = array();
        }
        return new Response(json_encode($arr1));

    }

    /**
     * @Route("/gettodobyid", name="get_todo_byid")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function getTodoByid(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Todolist::class);

        $post_data = json_decode($request->getContent(), true);

        $todo = $repository->findOneBy([
            'id' => $post_data['id'],

        ]);

        if (!$todo) {
            throw $this->createNotFoundException(
                'No TodoList found for id ' . $post_data['id']
            );
        }

        $arr = array('id' => $todo->getId(), 'task_title' => $todo->getTaskTitle(), 'task_desc' => $todo->getTaskDescription(), 'task_priority' => $todo->getTaskPriority(), 'task_status' => $todo->getTaskStatus());

        return new Response(json_encode($arr));

    }

    /**
     * @Route("/updatetodolist123", name="update_todo")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function updateTodo(Request $request)
    {

        $post_data = json_decode($request->getContent(), true);

        $task_title = $post_data['task_title'];
        $task_description = $post_data['task_description'];
        $task_priority = $post_data['task_priority'];
        $id = $post_data['id'];

        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $todo = $entityManager->getRepository(Todolist::class)->find($id);

        $todo->setTaskTitle($task_title);
        $todo->setTaskDescription($task_description);
        $todo->setTaskPriority($task_priority);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($todo);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response(json_encode($todo->getId()));

    }

    /**
     * @Route("/completetask123", name="complete_task123")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function completeTask123(Request $request)
    {

        $post_data = json_decode($request->getContent(), true);

        $task_status = $post_data['task_status'];
        $id = $post_data['id'];

        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $todo = $entityManager->getRepository(Todolist::class)->find($id);

        $todo->setTaskStatus($task_status);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($todo);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response(json_encode($todo->getId()));

    }

    /**
     * @Route("/deletetask123", name="delete_task123")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function deleteTask123(Request $request)
    {

        $post_data = json_decode($request->getContent(), true);

      
        $id = $post_data['id'];

        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $todo = $entityManager->getRepository(Todolist::class)->find($id);

       

        $entityManager->remove($todo);
        $entityManager->flush();

        return new Response(json_encode($todo->getId()));

    }

}
