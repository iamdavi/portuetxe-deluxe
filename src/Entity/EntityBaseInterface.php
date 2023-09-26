<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use DateTimeImmutable;

/**
 * EntityBase Interface
 *
 * @link {https://gist.github.com/lelledaniele/be67e03b51e04ab9f9b04b985ccd94e2}
 * @author Lelle - Daniele Rostellato <lelle.daniele@gmail.com>
 */
interface EntityBaseInterface
{
	/**
	 * @ORM\PrePersist
	 * @ORM\PreUpdate
	 */
	public function updatedTimestamps(): void;

	/**
	 * Get createdAt
	 *
	 * @return null|DateTime
	 */
	public function getCreatedAt(): ?DateTimeImmutable;


	/**
	 * Set createdAt
	 *
	 * @param DateTimeInmutable $createdAt
	 * @return self
	 */
	public function setCreatedAt(DateTimeImmutable $createdAt): self;

	/**
	 * Get updatedAt
	 *
	 * @param DateTime $createdAt
	 * @return self
	 */
	public function getUpdatedAt(): ?DateTime;

	/**
	 * Set updatedAt
	 *
	 * @param DateTime $updatedAt
	 * @return self
	 */
	public function setUpdatedAt(DateTime $updatedAt): self;
}
