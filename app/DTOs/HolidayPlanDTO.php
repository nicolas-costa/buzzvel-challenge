<?php

declare(strict_types=1);

namespace App\DTOs;

class HolidayPlanDTO
{
    private ?int $id;
    private string $title;
    private ?string $description;
    private string $date;
    private string $location;
    private ?array $participants;
    private ?int $userId;

    public function __construct(
        int $id = null,
        string $title,
        ?string $description,
        string $date,
        string $location,
        ?array $participants,
        ?int $userId
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->date = $date;
        $this->location = $location;
        $this->participants = $participants;
        $this->userId = $userId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getParticipants(): ?array
    {
        return $this->participants;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        return $this->userId = $userId;
    }

}
