```php


# error msg 출력
error_reporting(E_ALL);
ini_set('display_errors', '1');

# json 처리
header('Content-Type: application/json; charset=utf-8');
json_decode( $ret, true );
json_encode( $ret, JSON_UNESCAPED_UNICODE );
addslashes( json_encode( $ret, JSON_UNESCAPED_UNICODE ) );

# CORS
header('Access-Control-Allow-Origin:*');
// header('Content-Type:text/html;charset=utf-8');
// header("Access-Control-Allow-Credentials", "true");
header("Access-Control-Allow-Headers:DNT,X-Mx-ReqToken,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type, Accept-Language, Origin, Accept-Encoding");
// header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE,OPTIONS');
// header('Access-Control-Max-Age: 86400');

```