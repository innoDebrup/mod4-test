<?php

namespace Drupal\cart\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Link;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;

/**
 * Provides a 'Add to Cart' Block.
 */
#[Block(
  id: 'cart_and_buy',
  admin_label: new TranslatableMarkup('Cart and Buy')
)]

/**
 * Class to display Cart and Buy block.
 */
class CartBlock extends BlockBase implements ContainerFactoryPluginInterface {
   
  /**
   * The route match service.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * Constructs a new BuyNowBlock instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RouteMatchInterface $route_match) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->routeMatch = $route_match;
  }

  /**
   * {@inheritdoc}
   */
  public static function create($container, $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $render_array['button']['cart'] = [
      '#type' => 'button',
      '#value' => t('Add to Cart'),
      '#attributes' => ['id'=>'add-to-cart'], 
    ];
    $node = $this->routeMatch->getParameter('node');
    if ($node instanceof Node) {
      $node_id = $node->id();
    }
    else {
      $node_id = NULL;
    }
    // Set button link.
    $buy_now_button = Link::fromTextAndUrl($this->t('Buy Now'), Url::fromRoute('cart.buy_now', ['product' => $node_id]))->toRenderable();
    $buy_now_button['#attributes'] = ['class' => ['button', 'buy-now-button']];
    // Render buy_now button.
    $render_array['buy_now'] = [
      '#type' => 'container',
      'content' => [
        'buy_now_button' => $buy_now_button,
      ],
      '#attributes' => ['class' => ['buy-now-block']],
    ];
    $render_array['#attached']['library'][] = 'cart/cart-scripts';
    return $render_array;
  }
}
