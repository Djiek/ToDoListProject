<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class TaskController extends AbstractController
{

     private $repo;

    public function __construct(TaskRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/tasks", name="task_list")
     */
    public function listAction(Request $request, CacheInterface $cache, TaskRepository $repo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $limit = 6;
        $page = (int)$request->query->get("page", 1);
        $tasks = $this->repo->pagination($page, $limit);
        $total = $cache->get('task_list', function (ItemInterface $item) use ($repo) {
            return $repo->getTotalTask();
        });

        return $this->render('task/list.html.twig', [
            'tasks' => $tasks,
            'total' => $total,
            'limit' => $limit,
            'page' => $page
        ]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(
        Request $request,
        CacheInterface $cache
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $task->setCreatedAt(new \DateTimeImmutable());
            $task->setUser($this->getUser());
            $task->setIsDone(0);
            $manager->persist($task);
            $manager->flush();
            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }
        $cache->delete('task_list');
        
        return $this->render(
            'task/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function editAction(Task $task, Request $request, CacheInterface $cache)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }
        $cache->delete('task_list');
        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(Task $task, CacheInterface $cache)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $task,CacheInterface $cache)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if ($task->getUser()->getusername() === "anonyme") {
            $this->denyAccessUnlessGranted(
                'ROLE_ADMIN',
                null,
                'Le rôle admin est obligatoire pour supprimer une tache anonyme'
            );
        } elseif ($task->getUser()->getId() != $this->getUser()->getId()) {
            throw $this->createAccessDeniedException();
        }
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($task);
        $manager->flush();
        $cache->delete('task_list');
        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
