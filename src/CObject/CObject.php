<?php
/**
 * Holding a instance of CMaveric to enable use of $this in subclasses and provide some helpers.
 *
 * @package MavericCore
 */
class CObject {

  /**
   * Members
   */
  protected $ma;
  protected $config;
  protected $request;
  protected $data;
  protected $db;
  protected $views;
  protected $session;
  protected $user;


  /**
   * Constructor, can be instantiated by sending in the $ma reference.
   */
  protected function __construct($ma=null) {
    if(!$ma) {
      $ma = CMaveric::Instance();
    }
    $this->ma       = &$ma;
    $this->config   = &$ma->config;
    $this->request  = &$ma->request;
    $this->data     = &$ma->data;
    $this->db       = &$ma->db;
    $this->views    = &$ma->views;
    $this->session  = &$ma->session;
    $this->user     = &$ma->user;
  }


  /**
   * Wrapper for same method in CMaveric. See there for documentation.
   */
  protected function RedirectTo($urlOrController=null, $method=null, $arguments=null) {
    $this->ma->RedirectTo($urlOrController, $method, $arguments);
  }


  /**
   * Wrapper for same method in CMaveric. See there for documentation.
   */
  protected function RedirectToController($method=null, $arguments=null) {
    $this->ma->RedirectToController($method, $arguments);
  }


  /**
   * Wrapper for same method in CMaveric. See there for documentation.
   */
  protected function RedirectToControllerMethod($controller=null, $method=null, $arguments=null) {
    $this->ma->RedirectToControllerMethod($controller, $method, $arguments);
  }


  /**
   * Wrapper for same method in CMaveric. See there for documentation.
   */
  protected function AddMessage($type, $message, $alternative=null) {
    return $this->ma->AddMessage($type, $message, $alternative);
  }


  /**
   * Wrapper for same method in CMaveric. See there for documentation.
   */
  protected function CreateUrl($urlOrController=null, $method=null, $arguments=null) {
    return $this->ma->CreateUrl($urlOrController, $method, $arguments);
  }


}
   