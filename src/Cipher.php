<?php


//
// Secure single key cipher, based on a purely random secret.
//
// This cipher is secure, unless:
//
//   1. /dev/urandom has very poor randomness.
//
//   2. AES256 or SHA256 has an exploitable vulnerability.
//
//   3. An attacker knows secret and can reasonably efficiently
//      guess your encryption key.
//


class Cipher
{
  function __construct()
  {
    //
    // Change this to a different random 16-byte secret to 
    // make different categories of ciphers.  
    //
    // No other system can decode or encode the same ciphers without
    // this 128-bit random secret 
    //
    $this->secret="\xd6\xb2\x4c\x05\x73\x8a\x36\xd9\x39\x41\x4e\xd0\x84\7f\x97\xae";

    //
    // Do NOT change anything below unless you really know what you
    // are doing.
    //
    $this->method="aes-256-cbc";
    $this->iv=16;
    $this->pad=16;
    $this->hmac="sha256";
    $this->hmacLength=32;
    $this->base64=TRUE;
  }

  // Encrypt (possibly binary) plain text with (possibly binary) key.
  // Each encrypted string is unique, even if encrypting the same
  // plain text with the same key.
  function encrypt($key,$plain)
  {

    if ($this->iv > 0) {
      // Get random initialization vector.
      //
      // This is public, so it does not have to be unguessable, 
      // just reasonably random (hence reading from the non-
      // blocking random source).
      //
      $devname="/dev/urandom";
      $dev=fopen($devname,"r");
      $iv=fread($dev,$this->iv);
      fclose($dev);
    } else {
      $iv="";
    }

    if ($this->hmac !== "") {
      // Append secure hash of message to message.
      $plain .= openssl_digest($plain,$this->hmac,TRUE);
    }

    if ($this->pad > 0) {
      // Pad to ensure message is a multiple of the
      // block cipher length.
      //
      $pad="$";
      $n=strlen($plain)+1;
      while ($n % $this->pad != 0) {
	$pad .= "-";
	++$n;
      }
      $plain .= $pad;
    }

    // derive unguessable key from secret, iv, and original key
    $key=openssl_digest($this->secret.$iv.$key,$this->hmac,TRUE);

    // cipher is random iv followed by block cipher
    $encrypted=$iv.openssl_encrypt($plain,$this->method,$key,TRUE,$iv);

    if ($this->base64) {
      // convert to printable ascii
       $encrypted=base64_encode($encrypted);
    }

    return $encrypted;
  }

  // decrypt message, or return FALSE if key does not match or 
  // encrypted message was corrupted/changed.
  function decrypt($key,$encrypted)
  {
    if ($this->base64) {
      // unwrap to binary message
      $encrypted=base64_decode($encrypted);
    }

    if ($this->iv > 0) {
      // get iv from front of encrypted message
      if (strlen($encrypted) < $this->iv) {
	return FALSE;
      }
      $iv=substr($encrypted,0,$this->iv);
      $encrypted=substr($encrypted,$this->iv);
    } else {
      $iv="";
    }

    // derive key as in encrypt
    $key=openssl_digest($this->secret.$iv.$key,$this->hmac,TRUE);  

    $plain = openssl_decrypt($encrypted,$this->method,$key,TRUE,$iv);

    if ($this->pad > 0) {
      // remove appended pad
      $end=strrpos($plain,'$');
      if ($end === FALSE) {
	return FALSE;
      }
      $plain=substr($plain,0,$end);
    }

    if ($this->hmacLength > 0) {
      // check if secure hash matches
      if (strlen($plain) < $this->hmacLength) {
	return FALSE;
      }
      $hmac0=substr($plain,strlen($plain)-$this->hmacLength);
      $plain=substr($plain,0,strlen($plain)-$this->hmacLength);
      $hmac1=openssl_digest($plain,$this->hmac,TRUE);
      if (strcmp($hmac0,$hmac1) != 0) {
	return FALSE;
      }
    }
    return $plain;
  }
}
