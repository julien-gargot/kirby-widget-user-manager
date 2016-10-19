<?php

$kirby->set('route', array(
  'pattern' => c::get('management.import', 'import'),
  'method' => 'POST',
  'action'  => function() {

    $_token = get('csrf');
    $response = array(
      'counterSuccess' => 0,
      'counterUpdate' => 0,
      'counterFailure' => 0,
      'message' => array()
    );

    echo "\n", $_token, " / " , csrf($_token) , "\n";

    if(!r::ajax()) {
      go('error');
    }

    if( !csrf($_token) || !kirby()->site()->user() )
    {
      // echo "NO WAY!";
      // return false;
      // return response::error('Something went wrong', 400, $data = array('foo'=>'bar'));
      go('login');
    }


    // Convert JSON to Kirby users
    $_users = array();
    foreach ( json_decode(get('datas'), true) as $key => $value ) {
      $_users[] =  array(
        'username'  => str::lower( $value['firstName'] .'-'. $value['lastName'] ),
        'email'     => $value['email'],
        'password'  => c::get('management.users.pwd'),
        'firstName' => $value['firstName'],
        'lastName'  => $value['lastName'],
        'role'      => c::get('management.users.role'),
      );
    }

    // Create or update each users
    foreach ($_users as $key => $u) {

      try {

        $user = kirby()->site()->users()->create($u);
        $response['message'][$key] = 'User “'. $u['username'] .'” has been created.';
        $response['counterSuccess'] ++;

      } catch(Exception $e) {

        try {

          $user = kirby()->site()->user($u['username'])->update($u);
          $response['message'][$key] = 'User “'. $u['username'] .'” has been updated.';
          $response['counterUpdate'] ++;

        } catch(Exception $e) {

          $response['message'][$key] = 'User “'. $u['username'] .'” could not be created nor updated:' . "\n" . $e->getMessage();
          $response['counterFailure'] ++;

        }

      }

    }

    // return response::json($response);
    // return response::success('Everything went fine', $response, 200);

    // echo json_encode($response);
    return true;
  }

));
