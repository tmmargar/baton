<?php
declare(strict_types=1);
namespace Baton\T4g\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use DateTime;

#[Table(name: "baton_invoice_payments")]
#[Entity(repositoryClass: InvoicePaymentsRepository::class)]
class InvoicePayments
{
    #[ManyToOne(targetEntity: Invoices::class, inversedBy: "invoices")]
    #[JoinColumn(name: "invoice_id", referencedColumnName: "invoice_id")]
    private Invoices $invoices;

    #[Column(name: "invoice_payment_id", nullable: false)]
    #[Id]
    #[GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: InvoicePaymentsIdGenerator::class)]
    private string $invoicePaymentId;

    #[Column(name: "invoice_payment_date", nullable: false)]
    private DateTime $invoicePaymentDate;

    #[Column(name: "invoice_payment_amount", precision: 4, scale: 2, nullable: false)]
    private float $invoicePaymentAmount;

    #[Column(name: "invoice_payment_comment", length: 200, nullable: true)]
    private ?string $invoicePaymentComment;

    /**
     * @return Invoices
     */
    public function getInvoices(): Invoices {
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
     * @param Invoices $invoices
     * @return self
     */
    public function setInvoices(Invoices $invoices): self {
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