<?php


namespace Bitrix\Openid\Client;


class User
{
  /**
   * @var int
   */
  public $id;
  /**
   * @var string
   */
  public $name;
  /**
   * @var string
   */
  public $lastName;
  /**
   * @var string
   */
  public $secondName;
  /**
   * @var string
   */
  public $email;
  /**
   * @var bool
   */
  public $isActive;
    /**
     * @var string
     */
  public $login;

  public function __construct(array $data = [])
  {
    $this->id = (int)$data['id'];
    $this->login = (string)$data['login'];
    $this->name = (string)$data['name'];
    $this->lastName = (string)$data['last_name'];
    $this->secondName = (string)$data['second_name'];
    $this->email = (string)$data['email'];
    $this->isActive = (bool)$data['active'];
  }
}
