<?php
namespace Baton\T4g\Utility;
use Baton\T4g\Model\Constant;
use DateTime;
use Exception;
abstract class SessionUtility {
    public const OBJECT_NAME_ADMINISTRATOR = "administrator";
    public const OBJECT_NAME_DEBUG = "debug";
    public const OBJECT_NAME_ID = "id";
    public const OBJECT_NAME_NAME = "name";
    public const OBJECT_NAME_MEMBERID = "userid";
    public const OBJECT_NAME_MEMBERNAME = "username";
    public const OBJECT_NAME_SECURITY = "securityObject";
    public static function destroy() {
        self::startSession();
        $_SESSION = array();
        session_destroy();
    }
    public static function destroyAllSessions() {
        $files = glob(pattern: session_save_path() . '/*'); // get all file names
        foreach ($files as $file) {
            if (is_file(filename: $file)) {
                unlink(filename: $file);
            }
        }
    }
    public static function existsSecurity(): bool {
        return !empty($_SESSION[self::OBJECT_NAME_SECURITY]);
    }
    public static function getValue(string $name): string|int|bool|DateTime {
        $value = $name == self::OBJECT_NAME_DEBUG ? false : "";
        if (self::existsSecurity()) {
            $security = unserialize(data: $_SESSION[self::OBJECT_NAME_SECURITY]); // Members object
            switch ($name) {
                case self::OBJECT_NAME_ADMINISTRATOR:
                    $value = $security->getMemberAdministratorFlag();
                    break;
                case self::OBJECT_NAME_DEBUG:
                    $value = false; // $security->isDebug();
                    break;
                case self::OBJECT_NAME_NAME:
                    $value = $security->getMemberName();
                    break;
                case self::OBJECT_NAME_MEMBERID:
                    $value = $security->getMemberId();
                    break;
                case self::OBJECT_NAME_MEMBERNAME:
                    $value = $security->getMemberUsername();
                    break;
            }
        }
        return $value;
    }
    public static function print(): string|bool {
        return print_r(value: $_SESSION, return: true);
    }
    public static function startSession() {
        if (Constant::PATH_SESSION() != session_save_path()) {
            session_save_path(path: Constant::PATH_SESSION());
        }
    session_start();
    // session_regenerate_id(true);
    }
    public static function setValue(string $name, mixed $value) {
        $_SESSION[$name] = serialize(value: $value);
    }
    public static function unserialize($session_data): array {
        $method = ini_get(option: "session.serialize_handler");
        switch ($method) {
            case "php":
                return self::unserialize_php(data: $session_data);
                break;
            case "php_binary":
                return self::unserialize_phpbinary(data: $session_data);
                break;
            default:
                throw new Exception(message: "Unsupported session.serialize_handler: " . $method . ". Supported: php, php_binary");
        }
    }
    private static function unserialize_php($session_data): array {
        $return_data = array();
        $offset = 0;
        while ($offset < strlen(string: $session_data)) {
            if (! strstr(haystack: substr(string: $session_data, offset: $offset), needle: "|")) {
                throw new Exception(message: "invalid data, remaining: " . substr($session_data, $offset));
            }
            $pos = strpos(haystack: $session_data, needle: "|", offset: $offset);
            $num = $pos - $offset;
            $varname = substr(string: $session_data, offset: $offset, length: $num);
            $offset += $num + 1;
            $data = unserialize(substr(string: $session_data, offset: $offset));
            $return_data[$varname] = $data;
            $offset += strlen(string: serialize(value: $data));
        }
        return $return_data;
    }
    private static function unserialize_phpbinary($session_data): array {
        $return_data = array();
        $offset = 0;
        while ($offset < strlen(string: $session_data)) {
            $num = ord(character: $session_data[$offset]);
            $offset += 1;
            $varname = substr(string: $session_data, offset: $offset, length: $num);
            $offset += $num;
            $data = unserialize(data: substr(string: $session_data, offset: $offset));
            $return_data[$varname] = $data;
            $offset += strlen(serialize(value: $data));
        }
        return $return_data;
    }
}