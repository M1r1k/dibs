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
    $transaction = DibsTransaction::loadByHash($transaction_hash);
    $transaction->set('status', 'ACCEPTED')->save();
    // @todo fire event about transaction accepting.
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
