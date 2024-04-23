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
#[Table(name: "baton_invoice_lines")]
#[Entity(repositoryClass: InvoiceLinesRepository::class)]
class InvoiceLines
{
    #[ManyToOne(targetEntity: Invoices::class, inversedBy: "invoices")]
    #[JoinColumn(name: "invoice_id", referencedColumnName: "invoice_id", nullable: false)]
    private Invoices $invoices;

    #[Column(name: "invoice_line_id", nullable: false)]
    #[Id]
    #[GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: InvoiceLinesIdGenerator::class)]
    private string $invoiceLineId;

    #[Column(name: "invoice_line_amount", precision: 4, scale: 2, nullable: false)]
    private float $invoiceLineAmount;

    #[ManyToOne(targetEntity: Students::class, inversedBy: "invoices")]
    #[JoinColumn(name: "student_id", referencedColumnName:"student_id", nullable: false)]
    private Students $students;

    #[Column(name: "invoice_line_comment", length: 200, nullable: true)]
    private ?string $invoiceLineComment;

    #[ManyToOne(targetEntity: EventTypes::class, inversedBy: "invoiceLines")]
    #[JoinColumn(name: "event_type_id", referencedColumnName: "event_type_id", nullable: false)]
    private EventTypes $eventTypes;

    public function initialize() {
        $this->setInvoiceLineAmount(0);
        $this->setInvoiceLineComment(NULL);
        $et = new EventTypes();
        $et->setEventTypeId(0);
        $this->setEventTypes($et);
        $this->setInvoiceLineId("0-1");
        $st = new Students();
        $st->setStudentId(0);
        $this->setStudents($st);
    }

    /**
     * @return Invoices
     */
    public function getInvoices(): Invoices {
        return $this->invoices;
    }

    /**
     * @return string
     */
    public function getInvoiceLineId(): string {
        return $this->invoiceLineId;
    }

    /**
     * @return float
     */
    public function getInvoiceLineAmount(): float {
        return $this->invoiceLineAmount;
    }

    /**
     * @return Students
     */
    public function getStudents(): Students {
        return $this->students;
    }

    /**
     * @return string
     */
    public function getInvoiceLineComment(): ?string {
        return $this->invoiceLineComment;
    }

    /**
     * @return EventTypes
     */
    public function getEventTypes(): EventTypes {
        return $this->eventTypes;
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
     * @param string $invoiceLineId
     * @return self
     */
    public function setInvoiceLineId(string $invoiceLineId): self {
        $this->invoiceLineId = $invoiceLineId;
        return $this;
    }

    /**
     * @param int $invoiceLineAmount
     * @return self
     */
    public function setInvoiceLineAmount(int $invoiceLineAmount): self {
        $this->invoiceLineAmount = $invoiceLineAmount;
        return $this;
    }

    /**
     * @param Students $students
     * @return self
     */
    public function setStudents(Students $students): self {
        $this->students = $students;
        return $this;
    }

    /**
     * @param string $invoiceLineComment
     * @return self
     */
    public function setInvoiceLineComment(?string $invoiceLineComment): self {
        $this->invoiceLineComment = $invoiceLineComment;
        return $this;
    }

    /**
     * @param EventTypes $eventTypes
     * @return self
     */
    public function setEventTypes(EventTypes $eventTypes): self {
        $this->eventTypes = $eventTypes;
        return $this;
    }
}