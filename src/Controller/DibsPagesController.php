<?php

namespace Drupal\dibs\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\dibs\Entity\DibsTransaction;
use Drupal\dibs\Form\DibsRedirectForm;

/**
 * Class DibsPagesController.
 *
 * @package Drupal\dibs\Controller
 */
class DibsPagesController extends ControllerBase {
  /**
   * Accept.
   *
   * @return string
   *   Return Hello string.
   */
  public function accept($transaction_hash) {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: accept with parameter(s): $transaction'),
    ];
  }
  /**
   * Decline.
   *
   * @return string
   *   Return Hello string.
   */
  public function decline($transaction_hash) {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: decline with parameter(s): $transaction'),
    ];
  }
  /**
   * Cancel.
   *
   * @return string
   *   Return Hello string.
   */
  public function cancel($transaction_hash) {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: cancel with parameter(s): $transaction'),
    ];
  }
  /**
   * Callback.
   *
   * @return string
   *   Return Hello string.
   */
  public function callback($transaction_hash) {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: callback with parameter(s): $transaction'),
    ];
  }

  public function redirectForm($transaction_hash) {
//    DibsTransaction::create([
//      'amount' => 123123123,
//      'order_id' => '123123a123sd',
//      'currency' => '978',
//      'email' => 'example123@example.com',
//      'billing_address' => 'address line 1',
//      'billing_first_name' => 'first name',
//      'test' => 1
//    ])->save();
    $transaction = DibsTransaction::loadByHash($transaction_hash);

    $form = \Drupal::service('form_builder')->getForm(DibsRedirectForm::class, ['transaction' => $transaction]);

    return [
      $form,
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

}
