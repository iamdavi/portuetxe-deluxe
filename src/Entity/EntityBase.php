<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;

/**
 * Class EntityBase
 * 
 * @link {https://gist.github.com/lelledaniele/be67e03b51e04ab9f9b04b985ccd94e2}
 */
#[ORM\HasLifecycleCallbacks]
class EntityBase implements EntityBaseInterface
{
	/**
	 * @var DateTimeInmutable $created
	 */
	#[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: "created_at", nullable: false)]
	protected $createdAt;

	/**
	 * @var DateTime $updated
	 */
	#[ORM\Column(type: Types::DATETIME_MUTABLE, name: "updated_at", nullable: false)]
	protected $updatedAt;

	#[ORM\PrePersist]
	#[ORM\PreUpdate]
	public function updatedTimestamps(): void
	{
		$dateTimeNow = new DateTime('now');

		$this->setUpdatedAt($dateTimeNow);

		if ($this->getCreatedAt() === null) {
			$dateInmutable = new DateTimeImmutable();
			$this->setCreatedAt($dateInmutable);
		}
	}

	public function getCreatedAt(): ?DateTimeImmutable
	{
		return $this->createdAt;
	}

	public function setCreatedAt(DateTimeImmutable $createdAt): self
	{
		$this->createdAt = $createdAt;

		return $this;
	}

	public function getUpdatedAt(): ?DateTime
	{
		return $this->updatedAt;
	}

	public function setUpdatedAt(DateTime $updatedAt): self
	{
		$this->updatedAt = $updatedAt;

		return $this;
	}
}
