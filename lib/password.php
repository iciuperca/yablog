<?php
/**
 * Class dedicated to the safely storing and verification of the user's password
 *
 * @author Ionut Cristian Ciuperca <ionut.ciuperca@gmail.com>
 */
class Password {
    /**
     * @var string Plain text pasword
     */
    private $password;
    /**
     * @var string Hashed password
     */
    private $hashed_password;

    /**
     * @var string The salt of the password
     */
    private $salt;

    const MIN_PASS_LENGTH = 6;
    const SALT_LENGTH = 24;

    public function __construct($password, $salt = null) {
        $this->setPassword($password);
        $this->setSalt($salt);
    }

    /**
     * Sets the plain text password and then returns it
     * @param string $password
     * @throws Exception is shorter than the min length
     * @return string The password that was set
     */
    public function setPassword($password) {
        if(strlen($password) < self::MIN_PASS_LENGTH) {
            throw new Exception('Min password length is ' . self::MIN_PASS_LENGTH);
        }
        $this->password = $password;

        return $this->password;
    }

    /**
     * Gets the curent password
     *
     * @return string The password
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Gets the hashed password. If it is not already set this will also generate and set it.
     *
     * @return string The hashed password.
     */
    public function getHashedPassword() {
        if(!isset($this->hashed_password)) {
            $this->hashed_password = $this->setHashedPassword($this->password);
        }
        return $this->hashed_password;
    }

    /**
     * Sets the hashed password and then returns it.
     *
     * @param string $password The plain text password.
     * @param string $salt The sald used in hashing the password. If it is not privided one random one will be generated.
     * @return string The hashed password
     */
    public function setHashedPassword($password, $salt = null) {
        $salt = is_null($this->salt) ? $this->setSalt() : $this->getSalt();
        $hashed_pass = hash('sha512', $salt . $password);
        $this->hashed_password = $hashed_pass;
        return $this->hashed_password;
    }

    /**
     * Sets the salt. if none in given a random one will be generated.
     *
     * @param string $salt The optional salt
     * @return string The generated salt
     */
    public function setSalt($salt = null) {
        if(is_null($salt)) {
            $this->salt = $this->generateSalt();
        }else {
            $this->salt = $salt;
        }
        return $this->salt;
    }

    /**
     * Gets the current salt
     *
     * @return string The salt
     */
    public function getSalt() {
        return $this->salt;
    }

    /**
     * Generates a random salt.
     *
     * @return string The generated salt
     */
    public function generateSalt() {
        //transform the generated salt to a usable format
        $salt = bin2hex(mcrypt_create_iv(self::SALT_LENGTH, MCRYPT_DEV_URANDOM));
        return $salt;
    }
}