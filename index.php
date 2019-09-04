<?php
  global $dotenv;
  global $responses;
  global $DAVE_LIMIT_COUNT_DEFAULT;

  require 'bootstrap.php';

  header('Access-Control-Max-Age: 3600');
  header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
  header('Access-Control-Allow-Methods: GET');
  header('Access-Control-Allow-Origin: *');
  header('Cache-Control: must-revalidate');
  header('Content-type: application/json; charset=UTF-8');
  header('Expires: 0');
  header('Pragma: public');

  $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $uri = explode('/', $uri);

  # print_r($uri); die;

  // all requests must start with /api
  if ($uri[1] !== 'api') {
    header('HTTP/1.1 404 Not Found');
    exit();
  }

  // all requests must be GET
  if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit();
  }

  // if error, return error immediately
  if (isset($_GET['error'])) {
    header('HTTP/1.1 400 You want an error? This is how you get an error.');
    exit();
  }

  // slack api call
  if (isset($_GET['slack'])) {
    $dotenv->required('DAVE_SLACK_TOKEN');
    $tokenInt = getenv('DAVE_SLACK_TOKEN');
    $tokenExt = $_GET['token'];

    // did we get a token?
    if (isset($tokenExt)) {
      // token matches
      if ($tokenExt == $tokenInt) {
        echo json_encode(array(
          'response_type' => 'in_channel',
          'text' => $responses[rand(0, count($responses) - 1)]
        ));
      }
      // invalid token
      else {
        echo json_encode(array(
          'text' => 'Dave says: \'What? I didn\'t understand that, dude.\''
        ));
      }
    }
    // missing token
    else {
      echo json_encode(array(
        'text' => 'Dave says: \'Eh? I don\'t think I know you, buddy.\''
      ));
    }
  }
  // regular api call
  else {
    // if file, check type and size, return file, exit
    if (isset($_GET['file'])) {
      if (isset($_GET['type'])) {
        $fileType = $_GET['type'];

        switch ($fileType) {
          case 'data':
            header('Content-Description: File Transfer');
            header('Content-Transfer-Encoding: binary');
            header('Content-Type: application/force-download');

            $size = $_GET['size'];

            switch ($size) {
              case '10':
                $filePath = './assets/data/10mb';
                break;
              case '100':
                $filePath = './assets/data/100mb';
                break;
              case '1000':
                $filePath = './assets/data/1000mb';
                break;
              default:
                $filePath = './assets/data/1mb';
                break;
            }
            break;
          case 'json':
            $size = $_GET['size'];

            switch ($size) {
              case '1000':
                $filePath = './assets/json/1000.json';
                break;
              case '10000':
                $filePath = './assets/json/10000.json';
                break;
              default:
                $filePath = './assets/json/100.json';
                break;
            }
            break;
          case 'text':
            $size = $_GET['size'];

            switch ($size) {
              case '100':
                $filePath = './assets/text/100.txt';
                break;
              case '1000':
                $filePath = './assets/text/1000.txt';
                break;
              case '10000':
                $filePath = './assets/text/10000.txt';
                break;
              default:
                $filePath = './assets/text/10.txt';
                break;
            }
            break;
        }
      } else {
        $filePath = './assets/oops.txt';
      }

      header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
      header('Content-Length: ' . filesize($filePath));
      flush(); // Flush system output buffer
      readfile($filePath);
      exit;
    }

    // else, we are returning a json array of daves
    $daveArray = [];
    $daveCount = (isset($_GET['daves']) && $_GET['daves'] > 0)
      ? $_GET['daves']
      : $DAVE_LIMIT_COUNT_DEFAULT;

    // build dave array
    for($i = 0; $i < $daveCount; $i++) {
      $daveArray[$i] = 'd' . (str_repeat('a', $i + 1)) . 've';
    }

    echo json_encode($daveArray);
  }
?>
