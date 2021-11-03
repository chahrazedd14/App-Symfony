<?php

namespace App\Entity;

use App\Repository\TodolistRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TodolistRepository::class)
 */
class Todolist
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $task_title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $task_description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $task_priority;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $task_status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $task_timestamp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaskTitle(): ?string
    {
        return $this->task_title;
    }

    public function setTaskTitle(string $task_title): self
    {
        $this->task_title = $task_title;

        return $this;
    }

    public function getTaskDescription(): ?string
    {
        return $this->task_description;
    }

    public function setTaskDescription(?string $task_description): self
    {
        $this->task_description = $task_description;

        return $this;
    }

    public function getTaskPriority(): ?string
    {
        return $this->task_priority;
    }

    public function setTaskPriority(string $task_priority): self
    {
        $this->task_priority = $task_priority;

        return $this;
    }

    public function getTaskStatus(): ?string
    {
        return $this->task_status;
    }

    public function setTaskStatus(string $task_status): self
    {
        $this->task_status = $task_status;

        return $this;
    }

    public function getTaskTimestamp(): ?string
    {
        return $this->task_timestamp;
    }

    public function setTaskTimestamp(string $task_timestamp): self
    {
        $this->task_timestamp = $task_timestamp;

        return $this;
    }
}
