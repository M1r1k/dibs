<?php

namespace Drupal\dibs\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dibs\DibsTransactionInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Dibs transaction entity.
 *
 * @ingroup dibs
 *
 * @ContentEntityType(
 *   id = "dibs_transaction",
 *   label = @Translation("Dibs transaction"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dibs\DibsTransactionListBuilder",
 *     "views_data" = "Drupal\dibs\Entity\DibsTransactionViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dibs\Form\DibsTransactionForm",
 *       "add" = "Drupal\dibs\Form\DibsTransactionForm",
 *       "edit" = "Drupal\dibs\Form\DibsTransactionForm",
 *       "delete" = "Drupal\dibs\Form\DibsTransactionDeleteForm",
 *     },
 *     "access" = "Drupal\Core\Entity\EntityAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\dibs\DibsTransactionHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "dibs_transactions",
 *   entity_keys = {
 *     "id" = "id",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/dibs_transaction/{dibs_transaction}",
 *     "add-form" = "/admin/structure/dibs_transaction/add",
 *     "edit-form" = "/admin/structure/dibs_transaction/{dibs_transaction}/edit",
 *     "delete-form" = "/admin/structure/dibs_transaction/{dibs_transaction}/delete",
 *     "collection" = "/admin/structure/dibs_transaction",
 *   },
 *   field_ui_base_route = "dibs_transaction.settings"
 * )
 */
class DibsTransaction extends ContentEntityBase implements DibsTransactionInterface {
  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage, array &$values) {
    $private_key = \Drupal::service('private_key')->get();
    $values['hash'] = sha1(microtime() . $values['order_id'] . $private_key);
  }

  /**
   * {@inheritdoc}
   */
  public static function loadByHash($hash) {
    $entity_manager = \Drupal::entityManager();
    $result = $entity_manager->getStorage($entity_manager->getEntityTypeFromClass(get_called_class()))
      ->getQuery()
      ->condition('hash', $hash, '=')
      ->range(0, 1)
      ->execute();

    $id = reset($result);
    if ($id) {
      return self::load($id);
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Dibs transaction entity.'))
      ->setReadOnly(TRUE);

    $fields['amount'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Transaction total amount'));
    $fields['hash'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Transaction HASH'));
    $fields['order_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Order ID'));
    $fields['currency'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Currency code'))
      ->setSettings(array(
        'max_length' => 3,
        'text_processing' => 0,
      ));

    $fields['email'] = BaseFieldDefinition::create('email')
      ->setLabel(t('The customers email'));
    $fields['billing_address'] = BaseFieldDefinition::create('string')
      ->setLabel(t('The customers billing address'));
    $fields['billing_address2'] = BaseFieldDefinition::create('string')
      ->setLabel(t('The customers billing 2 address'));
    $fields['billing_first_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('The customers billing first name'));
    $fields['billing_last_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('The customers billing last name'));
    $fields['billing_postal_code'] = BaseFieldDefinition::create('string')
      ->setLabel(t('The customers billing postal code'));
    $fields['billing_postal_place'] = BaseFieldDefinition::create('string')
      ->setLabel(t('The customers billing postal place(city or town)'));
    $fields['test'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('The test transaction'));

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
