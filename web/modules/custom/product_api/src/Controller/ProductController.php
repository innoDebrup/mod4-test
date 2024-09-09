<?php

namespace Drupal\product_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProductController extends ControllerBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a MovieApiController object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   The current user.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
    );
  }

  /**
   * Returns a JSON response with movie nodes data.
   */
  public function getProduct() {
    // Get the node storage
    $node_storage = $this->entityTypeManager->getStorage('node');
    // Load all movie nodes
    $query = $node_storage->getQuery()
      ->condition('type', 'product')
      ->condition('status', 1)
      ->accessCheck(FALSE)
      ->execute();
    $nodes = Node::loadMultiple($query);
    // Prepare data to be returned
    $products = [];
    foreach ($nodes as $node) {
      $products[] = [
        'id' => $node->id(),
        'title' => $node->getTitle(),
        'field_price' => $node->get('field_price')->value,
        'field_description' => $node->get('field_description')->value,
        'field_image' => $node->get('field_image')->entity ? $node->get('field_image')->entity->createFileUrl() : '',
      ];
    }
    // Return JSON response
    return new JsonResponse($products);
  }
}
