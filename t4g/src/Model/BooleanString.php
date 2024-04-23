<?php
declare(strict_types = 1);
namespace Baton\T4g\Model;
class BooleanString extends Base {
  public function __construct(protected string $value) {}
  public function getValue(): string {
    return $this->value;
  }
  public function setValue(string $value): BooleanString {
    $this->value = $value;
    return $this;
  }
  public function getBoolean(): bool {
    return Constant::FLAG_YES == $this->value ? true : false;
  }
  public function __toString(): string {
    $output = parent::__toString();
    $output .= ", value = '";
    $output .= $this->value;
    $output .= "'";
    return $output;
  }
}