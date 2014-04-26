<?php

namespace Helper;

use \InvalidArgumentException;

class Cookie
{
    /**
     * @var string
     */
    public $salt = 'dEfAuLt_CoOkIe_SaLt';

    /**
     * @var integer
     */
    public $expiration = 3600;

    /**
     * @var string
     */
    public $path = '/';

    /**
     * @var string
     */
    public $domain = null;

    /**
     * @var boolean
     */
    public $secure = false;

    /**
     * @var boolean
     */
    public $httponly = false;

    /**
     * @param   string  $key        cookie name
     * @param   mixed   $default    default value to return
     * @return  string
     */
    public function get($key, $default = null)
    {
        if (!isset($_COOKIE[$key])) {
            return $default;
        }

        $cookie = $_COOKIE[$key];
        $split = strlen($this->salt($key, null));

        if (isset($cookie[$split]) && $cookie[$split] === '~') {
            list ($hash, $value) = explode('~', $cookie, 2);

            if ($this->salt($key, $value) === $hash) {
                return $value;
            }

            $this->delete($key);
        }

        return $default;
    }

    /**
     * @param   string  $name       name of cookie
     * @param   string  $value      value of cookie
     * @param   integer $expiration lifetime in seconds
     * @return  boolean
     */
    public function set($name, $value, $expiration = null)
    {
        if ($expiration === null) {
            $expiration = $this->expiration;
        }

        if ($expiration !== 0) {
            $expiration += time();
        }

        $value = $this->salt($name, $value).'~'.$value;

        return setcookie($name, $value, $expiration, $this->path, $this->domain, $this->secure, $this->httponly);
    }

    /**
     * @param   string  $name   cookie name
     * @return  boolean
     */
    public function delete($name)
    {
        unset($_COOKIE[$name]);
        return setcookie($name, null, -86400, $this->path, $this->domain, $this->secure, $this->httponly);
    }

    /**
     * @param   string  $name   name of cookie
     * @param   string  $value  value of cookie
     * @return  string
     */
    public function salt($name, $value)
    {
        if (!$this->salt) {
            throw new InvalidArgumentException(
                'A valid cookie salt is required. Please set cookie salt before calling this method.'
            );
        }

        // Determine the user agent
        $agent = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : 'unknown';

        return sha1($agent.$name.$value.$this->salt);
    }
}
