<?php

namespace App\Infrastructure\Entity;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class Recipe
{
    public const DEVICE_MC_CONNECT = 1;
    public const DEVICE_MC_PLUS    = 2;

    public const DIFFICULTY_EASY   = 1;
    public const DIFFICULTY_MEDIUM = 2;
    public const DIFFICULTY_HARD   = 3;

    /**
     * @var int
     */
    private $uid;

    /**
     * @var string
     */
    private $img;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $url;

    /**
     * @var int
     */
    private $device;

    /**
     * @var string
     */
    private $portion;

    /**
     * @var int
     */
    private $difficulty;

    /**
     * @var float
     */
    private $rate;

    /**
     * @var string
     */
    private $author;

    /**
     * @var int
     */
    private $rateCount;

    /**
     * @var int|null
     */
    private $id;

    /**
     * @var int
     */
    private $duration;

    /**
     * @var int
     */
    private $durationTotal;

    /**
     * @var DateTimeInterface|null
     */
    private $create;

    /**
     * @var DateTimeInterface|null
     */
    private $update;

    /**
     * @var int
     */
    private $kcal;

    /**
     * @var int
     */
    private $kj;

    /**
     * @var int
     */
    private $protein;

    /**
     * @var int
     */
    private $carbohydrates;

    /**
     * @var int
     */
    private $lipids;

    public function __construct(
        int $uid,
        string $img,
        string $name,
        string $url,
        int $device,
        string $portion,
        int $difficulty,
        float $rate,
        string $author = '',
        int $rateCount = 0,
        int $id = null,
        int $duration = 0,
        int $durationTotal = 0,
        DateTimeInterface $create = null,
        DateTimeInterface $update = null,
        int $kcal = 0,
        int $kj = 0,
        int $protein = 0,
        int $carbohydrates = 0,
        int $lipids = 0
    ) {
        $this->uid           = $uid;
        $this->img           = $img;
        $this->name          = $name;
        $this->url           = $url;
        $this->device        = $device;
        $this->portion       = $portion;
        $this->difficulty    = $difficulty;
        $this->rate          = $rate;
        $this->author        = $author;
        $this->rateCount     = $rateCount;
        $this->id            = $id;
        $this->duration      = $duration;
        $this->durationTotal = $durationTotal;
        $this->create        = $create;
        $this->update        = $update;
        $this->kcal          = $kcal;
        $this->kj            = $kj;
        $this->protein       = $protein;
        $this->carbohydrates = $carbohydrates;
        $this->lipids        = $lipids;
    }

    public function getUid(): int
    {
        return $this->uid;
    }

    public function setUid(int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getImg(): string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getDevice(): int
    {
        return $this->device;
    }

    public function setDevice(int $device): self
    {
        $this->device = $device;

        return $this;
    }

    public function getPortion(): string
    {
        return $this->portion;
    }

    public function setPortion(string $portion): self
    {
        $this->portion = $portion;

        return $this;
    }

    public function getDifficulty(): int
    {
        return $this->difficulty;
    }

    public function setDifficulty(int $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function setRate(float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getRateCount(): int
    {
        return $this->rateCount;
    }

    public function setRateCount(int $rateCount): self
    {
        $this->rateCount = $rateCount;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDurationTotal(): ?int
    {
        return $this->durationTotal;
    }

    public function setDurationTotal(?int $durationTotal): self
    {
        $this->durationTotal = $durationTotal;

        return $this;
    }

    public function getCreate(): ?DateTimeInterface
    {
        return $this->create;
    }

    public function setCreate(?DateTimeInterface $create): self
    {
        $this->create = $create;

        return $this;
    }

    public function getUpdate(): ?DateTimeInterface
    {
        return $this->update;
    }

    public function setUpdate(?DateTimeInterface $update): self
    {
        $this->update = $update;

        return $this;
    }

    public function getKcal(): int
    {
        return $this->kcal;
    }

    public function setKcal(int $kcal): self
    {
        $this->kcal = $kcal;

        return $this;
    }

    public function getKj(): int
    {
        return $this->kj;
    }

    public function setKj(int $kj): self
    {
        $this->kj = $kj;

        return $this;
    }

    public function getProtein(): int
    {
        return $this->protein;
    }

    public function setProtein(int $protein): self
    {
        $this->protein = $protein;

        return $this;
    }

    public function getCarbohydrates(): int
    {
        return $this->carbohydrates;
    }

    public function setCarbohydrates(int $carbohydrates): self
    {
        $this->carbohydrates = $carbohydrates;

        return $this;
    }

    public function getLipids(): int
    {
        return $this->lipids;
    }

    public function setLipids(int $lipids): self
    {
        $this->lipids = $lipids;

        return $this;
    }
}
