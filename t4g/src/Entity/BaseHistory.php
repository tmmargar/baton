<?php
declare(strict_types=1);
namespace Baton\T4g\Entity;
use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\MappedSuperclass;
#[MappedSuperclass]
class BaseHistory
{
    #[Column(name: "action", length: 8, nullable: false)]
    protected string $action;

    #[Column(name: "dt_datetime", nullable: false)]
    protected DateTime $changeDate;

    #[Id]
    #[Column(name: "revision", nullable: false)]
    protected int $revision;

    /**
     * @return string
     */
    public function getAction(): string {
        return $this->action;
    }

    /**
     * @return DateTime
     */
    public function getChangeDate(): DateTime {
        return $this->changeDate;
    }

    /**
     * @return int
     */
    public function getRevision(): int {
        return $this->revision;
    }

    /**
     * @param string $action
     * @return \Baton\T4g\Entity\BaseHistory
     */
    public function setAction(string $action): self {
        $this->action = $action;
        return $this;
    }

    /**
     * @param DateTime $changeDate
     * @return self
     */
    public function setChangeDate(DateTime $changeDate): self {
        $this->changeDate = $changeDate;
        return $this;
    }

    /**
     * @param int $revision
     * @return self
     */
    public function setRevision(int $revision): self {
        $this->revision = $revision;
        return $this;
    }
}