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
#[Table(name: "baton_invoice_lines_history")]
#[Entity(repositoryClass: InvoiceLinesHistoryRepository::class)]
class InvoiceLinesHistory extends BaseHistory
{
    #[ManyToOne(targetEntity: InvoicesHistory::class, inversedBy: "invoices")]
    #[JoinColumn(name: "invoice_id", referencedColumnName: "invoice_id", nullable: false)]
    private InvoicesHistory $invoices;

    #[Column(name: "invoice_line_id", nullable: false)]
    #[Id]
    private string $invoiceLineId;

    #[Column(name: "invoice_line_amount", precision: 4, scale: 2, nullable: false)]
    private float $invoiceLineAmount;

    #[ManyToOne(targetEntity: Students::class, inversedBy: "invoices")]
    #[JoinColumn(name: "student_id", referencedColumnName:"student_id", nullable: false)]
    private Students $students;

    #[Column(name: "invoice_line_comment", length: 200, nullable: true)]
    private ?string $invoiceLineComment;

    #[Column(name: "invoice_line_event_date", nullable: false)]
    private DateTime $invoiceLineEventDate;

    #[ManyToOne(targetEntity: EventTypes::class, inversedBy: "invoiceLines")]
    #[JoinColumn(name: "event_type_id", referencedColumnName: "event_type_id", nullable: false)]
    private EventTypes $eventTypes;

    #[Column(name: "event_type_time_length", precision: 5, scale: 0, nullable: false)]
    #[Id]
    private int $eventTypeTimeLength;

    #[Column(name: "event_type_student_count", precision: 3, scale: 0, nullable: false)]
    #[Id]
    private int $eventTypeStudentCount;

    public function initialize() {
        $this->setInvoiceLineAmount(0);
        $this->setInvoiceLineComment(NULL);
        $et = new EventTypes();
        $et->setEventTypeId(0);
        $this->setEventTypes($et);
        $this->setEventTypeTimeLength(0);
        $this->setEventTypeStudentCount(0);
        $this->setInvoiceLineId("0-1");
        $st = new Students();
        $st->setStudentId(0);
        $this->setStudents($st);
        $this->setInvoiceLineEventDate(new DateTime());
    }

    /**
     * @return Invoices
     */
    public function getInvoices(): InvoicesHistory {
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
     * @return DateTime
     */
    public function getInvoiceLineEventDate(): DateTime {
        return $this->invoiceLineEventDate;
    }

    /**
     * @return EventTypes
     */
    public function getEventTypes(): EventTypes {
        return $this->eventTypes;
    }

    /**
     * @return int
     */
    public function getEventTypeTimeLength(): int {
        return $this->eventTypeTimeLength;
    }

    /**
     * @return int
     */
    public function getEventTypeStudentCount(): int {
        return $this->eventTypeStudentCount;
    }

    /**
     * @param Invoices $invoices
     * @return self
     */
    public function setInvoices(InvoicesHistory $invoices): self {
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
     * @param DateTime $invoiceLineEventDate
     * @return self
     */
    public function setInvoiceLineEventDate(DateTime $invoiceLineEventDate): self {
        $this->invoiceLineEventDate = $invoiceLineEventDate;
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

    /**
     * @param int $eventTypeTimeLength
     * @return self
     */
    public function setEventTypeTimeLength(int $eventTypeTimeLength): self {
        $this->eventTypeTimeLength = $eventTypeTimeLength;
        return $this;
    }

    /**
     * @param int $eventTypeStudentCount
     * @return self
     */
    public function setEventTypeStudentCount(int $eventTypeStudentCount): self {
        $this->eventTypeStudentCount = $eventTypeStudentCount;
        return $this;
    }
}