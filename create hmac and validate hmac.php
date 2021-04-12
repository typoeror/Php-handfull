<?php

public function verifyHmac($request_data, $app_secret_key) { # shopify hmac verification
      $hmacSource = [];

      foreach ($request_data as $key => $value) {

          if ($key === 'hmac') { continue; } // Skip the hmac key

          // Replace the characters as specified by Shopify in the keys and values
          $valuePatterns = ['&' => '%26', '%' => '%25'];
          $keyPatterns = $valuePatterns + ['=' => '%3D'];
          $key = str_replace(array_keys($keyPatterns), array_values($keyPatterns), $key);
          $value = str_replace(array_keys($valuePatterns), array_values($valuePatterns), $value);

          $hmacSource[] = $key . '=' . $value;
      }

      // Sort the key value pairs lexographically and then generate the HMAC signature of the provided data
      sort($hmacSource);
      $hmacBase = implode('&', $hmacSource);
      $hmacString = hash_hmac('sha256', $hmacBase, $app_secret_key);

      if ($hmacString !== $request_data['hmac']) { // Verify that the signatures match
          return false;
      }

      return true;
  }

public function createHmac($string, $secret) { # https://www.php.net/manual/en/function.hash-hmac.php
  return hash_hmac('sha256', $string, $secret);
}

public function verificationHmac($string, $secret, $hmac) { # https://www.php.net/manual/en/function.hash-equals.php
  $calculated_hash = hash_hmac('sha256', $string, $secret);
  return hash_equals($calculated_hash, $hmac);
}
