<?php
      $deviceToken = 'ecb4fcd8 cedc81ed b4784c44 2c64ad40 3d951a6f 22e9b1c7 d59308e9 d76da8d8'; // token dell'iPhone a cui inviare la notifica

      // Passphrase for the private key (ck.pem file)
      // $pass = ”;
      // Get the parameters from http get or from command line
      $message = "testo";
      $badge = 1;
      $sound = 'default';

      // Construct the notification payload
      $body = array();
      $body['aps'] = array('alert' => $message);
      
      if ($badge)
            $body['aps']['badge'] = $badge;
      if ($sound)
            $body['aps']['sound'] = $sound;
     
      /* End of Configurable Items */
      $ctx = stream_context_create();
      stream_context_set_option($ctx, 'ssl', 'local_cert', 'apns-dev.pem');

      // assume the private key passphase was removed.
      // stream_context_set_option($ctx, 'ssl', 'passphrase', $pass);
      $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);

      if (!$fp) {
            print "Failed to connect $err $errstrn";
            return;
      } else {
            print "Connection OK\n";
      }

      $payload = json_encode($body);
      $msg = chr(0) . pack('n',32) . pack('H*', str_replace(' ', ”, $deviceToken)) . pack('n',strlen($payload)) . $payload;
      print "sending message :" . $payload . "\n";
      fwrite($fp, $msg);
      fclose($fp);
?>