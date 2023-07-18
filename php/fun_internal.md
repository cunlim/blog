```php


# error msg 출력
error_reporting(E_ALL);
ini_set('display_errors', '1');

# json 처리
json_decode( $ret, true );
json_encode( $ret, JSON_UNESCAPED_UNICODE );
addslashes( json_encode( $ret, JSON_UNESCAPED_UNICODE ) );



```