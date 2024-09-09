<?php

namespace Drupal\cart\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class to handle ThankYou page display.
 */
class ThankYouController extends ControllerBase {
  
  /**
   * Session object stored for use.
   *
   * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
   */
  protected $session;
  
  /**
   * {@inheritdoc}
   */
  public function __construct(SessionInterface $session) {
    $this->session = $session;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('session')
    );
  }

  /**
   * Function to process received info and display on the Thank You page. 
   * 
   * @return array
   *   Returns a render_array to display the required info.
   */
  public function thankYou() {
    // Retrieve product information from session.
    $product_info = $this->session->get('buy_now_product');
    // Remove or Unset the product information from session.
    $this->session->remove('buy_now_product');
    // If product information is not set, redirect to home page.
    if (!$product_info) {
      return $this->redirect('<front>');
    }
    // Build the thank you message.
    return [
      '#theme' => 'thank_you_page',
      '#product' => $product_info,
      '#attached'=> ['library'=> 'cart/cart-css'],
    ];
  }
}
