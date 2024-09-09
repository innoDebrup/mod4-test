<?php

namespace Drupal\cart\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\node\Entity\Node;

/**
 * Class to handle Buy Now functionality.
 */
class BuyNowController extends ControllerBase {
  
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
   * Function to process product info and pass on to the ThankYou page.
   *
   * @param RouteMatchInterface $route_match
   *   The RouteMatchInterface object which contains info about selected route. 
   * 
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   Redirects to ThankYou page route.
   */
  public function buyNow(RouteMatchInterface $route_match) {
    $product_id = $route_match->getParameter('product');
    // Load the product entity.
    $product = Node::load($product_id);
    // Store product information in session for the thank you page.
    $this->session->set('buy_now_product', [
      'name' => $product->getTitle(),
      'price' => $product->get('field_price')->value,
      'image' => $product->get('field_image')->entity->createFileUrl(),
      'quantity' => 1, // Set default quantity or retrieve from request.
      'user' => $this->currentUser()->getDisplayName(),
    ]);
    // Redirect to the thank you page.
    return new RedirectResponse('/thank-you');
  }
}
