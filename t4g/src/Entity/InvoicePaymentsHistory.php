<?php
declare(strict_types=1);
namespace Baton\T4g\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use DateTime;

#[Table(name: "baton_invoice_payments_history")]
#[Entity(repositoryClass: InvoicePaymentsHistoryRepository::class)]
class InvoicePaymentsHistory extends BaseHistory
{
    #[ManyToOne(targetEntity: InvoicesHistory::class, inversedBy: "invoices")]
    #[JoinColumn(name: "invoice_id", referencedColumnName: "invoice_id")]
    private InvoicesHistory $invoices;

    #[Column(name: "invoice_payment_id", nullable: false)]
    #[Id]
    private string $invoicePaymentId;

    #[Column(name: "invoice_payment_date", nullable: false)]
    private DateTime $invoicePaymentDate;

    #[Column(name: "invoice_payment_amount", precision: 4, scale: 2, nullable: false)]
    private float $invoicePaymentAmount;

    #[Column(name: "invoice_payment_comment", length: 200, nullable: true)]
    private ?string $invoicePaymentComment;

    /**
     * @return InvoicesHistory
     */
    public function getInvoices(): InvoicesHistory {
        return $this->invoices;
    }

    /**
     * @return string
     */
    public function getInvoicePaymentId(): string {
        return $this->invoicePaymentId;
    }

    /**
     * @return DateTime
     */
    public function getInvoicePaymentDate(): DateTime {
        return $this->invoicePaymentDate;
    }

    /**
     * @return float
     */
    public function getInvoicePaymentAmount(): float {
        return $this->invoicePaymentAmount;
    }

    /**
     * @return string
     */
    public function getInvoicePaymentComment(): ?string {
        return $this->invoicePaymentComment;
    }

    /**
     * @param InvoicesHistory $invoices
     * @return self
     */
    public function setInvoices(InvoicesHistory $invoices): self {
        $this->invoices = $invoices;
        return $this;
    }

    /**
     * @param string $invoicePaymentId
     * @return self
     */
    public function setInvoicePaymentId(string $invoicePaymentId): self {
        $this->invoicePaymentId = $invoicePaymentId;
        return $this;
    }

    /**
     * @param DateTime $invoicePaymentDate
     * @return \Baton\T4g\Entity\InvoicePayments
     */
    public function setInvoicePaymentDate(DateTime $invoicePaymentDate): self {
        $this->invoicePaymentDate = $invoicePaymentDate;
        return $this;
    }

    /**
     * @param float $invoicePaymentAmount
     * @return self
     */
    public function setInvoicePaymentAmount(float $invoicePaymentAmount): self {
        $this->invoicePaymentAmount = $invoicePaymentAmount;
        return $this;
    }

    /**
     * @param string $invoicePaymentComment
     * @return self
     */
    public function setInvoicePaymentComment(?string $invoicePaymentComment): self {
        $this->invoicePaymentComment = $invoicePaymentComment;
        return $this;
    }
}