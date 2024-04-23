<?php
declare(strict_types=1);
namespace Baton\T4g\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use DateTime;
#[Table(name: "baton_invoices_history")]
#[Entity(repositoryClass: InvoicesHistoryRepository::class)]
class InvoicesHistory extends BaseHistory
{
    #[Column(name: "invoice_id", nullable: false)]
    #[Id]
    private int $invoiceId;

    #[Column(name: "invoice_date", nullable: false)]
    private DateTime $invoiceDate;

    #[Column(name: "invoice_due_date", nullable: false)]
    private DateTime $invoiceDueDate;

    #[Column(name: "invoice_amount", precision: 4, scale: 2, nullable: false)]
    private float $invoiceAmount;

    #[Column(name: "invoice_comment", length: 200, nullable: true)]
    private ?string $invoiceComment;

    #[ManyToOne(targetEntity: Members::class, inversedBy: "invoices")]
    #[JoinColumn(name: "member_id", referencedColumnName:"member_id", nullable: false)]
    private Members $members;

    #[OneToMany(mappedBy: "invoices", targetEntity: InvoiceLinesHistory::class)]
    #[JoinColumn(name: "invoice_id", referencedColumnName: "invoice_id", nullable: false)]
    private Collection $invoiceLines;

    #[OneToMany(mappedBy: "invoices", targetEntity: InvoicePaymentsHistory::class)]
    #[JoinColumn(name: "invoice_id", referencedColumnName: "invoice_id", nullable: false)]
    private Collection $invoicePayments;

    public function __construct() {
        $this->invoiceLines = new ArrayCollection();
        $this->invoicePayments = new ArrayCollection();
    }

    /**
     * @return number
     */
    public function getInvoiceId(): int {
        return $this->invoiceId;
    }

    /**
     * @return DateTime
     */
    public function getInvoiceDate(): DateTime {
        return $this->invoiceDate;
    }

    /**
     * @return DateTime
     */
    public function getInvoiceDueDate(): DateTime {
        return $this->invoiceDueDate;
    }
    /**
     * @return float
     */
    public function getInvoiceAmount(): float {
        return $this->invoiceAmount;
    }

    /**
     * @return string
     */
    public function getInvoiceComment(): ?string {
        return $this->invoiceComment;
    }

    /**
     * @return Members
     */
    public function getMembers(): Members {
        return $this->members;
    }

    /**
     * @return Collection
     */
    public function getInvoiceLines(): Collection {
        return $this->invoiceLines;
    }

    /**
     * @return Collection
     */
    public function getInvoicePayments(): Collection {
        return $this->invoicePayments;
    }

    /**
     * @param number $invoiceId
     * @return self
     */
    public function setInvoiceId(int $invoiceId): self {
        $this->invoiceId = $invoiceId;
        return $this;
    }

    /**
     * @param DateTime $invoiceDate
     * @return self
     */
    public function setInvoiceDate(DateTime $invoiceDate): self {
        $this->invoiceDate = $invoiceDate;
        return $this;
    }

    /**
     * @param DateTime $invoiceDueDate
     * @return self
     */
    public function setInvoiceDueDate(DateTime $invoiceDueDate): self {
        $this->invoiceDueDate = $invoiceDueDate;
        return $this;
    }

    /**
     * @param float $invoiceAmount
     * @return self
     */
    public function setInvoiceAmount(float $invoiceAmount): self {
        $this->invoiceAmount = $invoiceAmount;
        return $this;
    }

    /**
     * @param string $invoiceComment
     * @return self
     */
    public function setInvoiceComment(?string $invoiceComment): self {
        $this->invoiceComment = $invoiceComment;
        return $this;
    }

    /**
     * @param Members $members
     * @return self
     */
    public function setMembers(Members $members): self {
        $this->members = $members;
        return $this;
    }

    /**
     * @param Collection $invoiceLines
     * @return self
     */
    public function setInvoiceLines(Collection $invoiceLines): self {
        $this->invoiceLines = $invoiceLines;
        return $this;
    }

    /**
     * @param Collection $invoicePayments
     * @return self
     */
    public function setInvoicePayments(Collection $invoicePayments): self {
        $this->invoicePayments = $invoicePayments;
        return $this;
    }

}