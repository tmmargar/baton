<?php
namespace Baton\T4g\Utility;
use Baton\T4g\Model\Constant;
abstract class HtmlUtility {
  public static function buildClasses(array $aryClasses, string|int|float $value): string {
    $class = "";
    for ($idx = 0; $idx < count(value: $aryClasses); $idx ++) {
      if ($class != "") {
        $class .= " ";
      }
      switch ($aryClasses[$idx]) {
        case "currency":
        case "percentage":
        case "number":
          $class .= "number";
          if ($aryClasses[$idx] == "currency" && isset($value)) {
            if (0 > $value) {
              $class .= " negative";
            } else if (0 < $value) {
              $class .= " positive";
            }
          }
          break;
        default:
          $class .= $aryClasses[$idx];
          break;
      }
    }
    return $class;
  }

  public static function buildLink(string $href, ?string $id, ?string $target, string $title, string $text): string {
      $link = "<a href=\"" . $href . (isset($id) ? "\" id=\"" . $id : "") . (isset($target) ? "\" target=\"" . $target . "\"" : "") . "\" title=\"" . $title . "\">" . $text . "</a>\n";
      return $link;
  }

  /**
   * @param string $value
   * @return array
   */
  public static function parseLink(string $value): array {
      //<a href="http://www.google.com/" target="">text</a>
      $values = explode(separator: " ", string: $value);
      //[0] is <a
      //[1] is href="http://www.google.com/"
      //[2] is target="_blank">text</a>
      $vals = explode(separator: "=", string: $values[1]);
      if (1 < count($vals)) {
          //[0] is href
          //[1] is "http://www.google.com/"
          $url = substr(string: $vals[1], offset: 1, length: strlen($vals[1]) - 2);
          $vals2 = explode(separator: ">", string: $values[2]);
          //[0] is target="_blank"
          //[1] is text</a>
          $values2 = explode(separator: "<", string: $vals2[1]);
          //[0] is text
          //[1] is /a>
          $name = $values2[0];
          $returnValue = array($name, $url);
      } else {
          $returnValue = array($value);
      }
      return $returnValue;
  }

  // $format is array of formats (index, type and places)
  // $value is value to format
  // returns formatted value
  // TODO: move to FormBase once change class to array
  public static function formatData(array $format, string|int|float $value): string {
    if (isset($format)) {
      $temp = "";
      switch ($format[1]) {
        case "date":
          $temp = $value->getDisplayFormat();
          break;
        case "time":
          $temp .= $value->getDisplayAmPmFormat();
          break;
        case "currency":
        case "percentage":
        case "number":
          $prefix = "";
          $suffix = "";
          if ("currency" == $format[1]) {
            $prefix = Constant::SYMBOL_CURRENCY_DEFAULT;
          } else if ("percentage" == $format[1]) {
            $suffix = Constant::SYMBOL_PERCENTAGE_DEFAULT;
            $temp .= $value * 100;
          }
          if (- 1 != $format[2]) {
            $temp .= number_format(num: $value, decimals: $format[2]);
          }
          if ($temp != "") {
            $temp = $prefix . $temp . $suffix;
          }
          break;
      }
    } else {
      $temp = $value;
    }
    return $temp;
  }
}