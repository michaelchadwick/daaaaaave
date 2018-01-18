<?php
  header('Access-Control-Allow-Origin: *');
  // make sure it's a GET
  if($_SERVER['REQUEST_METHOD'] == 'GET') {
    // plain API call
    if(isset($_GET['api'])) {
      header('Content-type: application/json');

      $data = array('dave', 'daave', 'daaave', 'daaaave', 'daaaaave', 'daaaaaave', 'daaaaaaave');

      // random dave
      if(isset($_GET['random'])) {
        // random dave?
        echo $data[rand(0, count($data) - 1)];
      }
      // all dave
      else {
        echo json_encode($data);
      }
    }
    // slack api call
    else if(isset($_GET['slack'])) {
      // got token
      if(isset($_GET['token']) && $_GET['token'] == $_ENV['DAVE_SLACK_TOKEN']) {
        $songs = array(
          'Docking',
          'Road Trip',
          'Charlotte Said',
          'Wondering Why',
          'The Ladder',
          'Fudge and Jam',
          'Tattoo Fetish',
          'Pinto',
          'Scenes From My Window',
          'Overjoyed',
          'Beyond Today',
          'Even Tide',
          'Presto Change-o',
          'SoCal Switchblade',
          'Unwound String',
          'Slow Boat',
          'Down and Out',
          'Humanity'
        );
        $responses = array(
          'Uh, that\'s *David* to you. Heh. Just kidding! Dave is fine.',
          'Dave\'s not here right now.',
          'You called?',
          'Yeah, Dave is with you, man.',
          'Damn right Dave is down.',
          '*You got it!*',
          '*Hell yes!*',
          '*Woo!*',
          'Dave is caught in an interdimensional rock warp, but will be back soon.',
          'Alive and all that jive.',
          'Just reeling from how badass ya\'ll are.',
          'Dave has got your back.',
          'I\'m on my way.',
          'Keep *rockin\'*, and Dave will be there.',
          'Just call me _Daaaaaaave_.',
          'I\'m feeling just the right amount of *Dave* today, buddy.',
          'You rang?',
          'Hey, just listening to _' . $songs[rand(0,count($songs)-1)] . '_ right now. It *rules*!'
        );
        $json = array(
          'response_type' => 'in_channel',
          'text' => $responses[rand(0,count($responses)-1)]
        );
        header('Content-type: application/json');
        echo json_encode($json);
      }
      // no token
      else {
        echo '<p>API token missing</p>';
      }
    }
    // no api or slack, so html
    else {
      echo '<p>Try the <a href="/?api=1">API</a> instead</p>';
    }
  }
?>
