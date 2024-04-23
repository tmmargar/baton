<?php
declare(strict_types = 1);
namespace Baton\T4g\Model;
abstract class HtmlBase extends Base {
    public function __construct(protected ?string $accessKey, protected ?array $class, protected bool $debug, protected string|int|NULL $id, protected int $tabIndex, protected ?string $title) {
        parent::__construct(debug: $debug, id: $id);
    }
    public function getAccessKey(): ?string {
        return $this->accessKey;
    }
    public function getClass(): ?array {
        return $this->class;
    }
    public function getClassAsString(): string {
        return is_array($this->class) ? implode(separator: " ", array: array_filter($this->class)) : "";
    }
    public function getTabIndex(): int {
        return $this->tabIndex;
    }
    public function getTitle(): ?string {
        return $this->title;
    }
    public function setAccessKey(string $acccessKey) {
        $this->accessKey = $acccessKey;
        return $this;
    }
    public function setClass(array $class) {
        $this->class = $class;
        return $this;
    }
    public function setTabIndex(int $tabIndex) {
        $this->tabIndex = $tabIndex;
        return $this;
    }
    public function setTitle(string $title) {
        $this->title = $title;
        return $this;
    }
    public function __toString(): string {
        $output = parent::__toString();
        $output .= ", accessKey = '";
        $output .= $this->accessKey;
        $output .= "', class = ";
        $output .= print_r(value: $this->class, return: true);
        $output .= ", tabIndex = ";
        $output .= $this->tabIndex;
        $output .= ", title = '";
        $output .= $this->title;
        $output .= "'";
        return $output;
    }
}