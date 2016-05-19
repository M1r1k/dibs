<?php

namespace Drupal\dibs\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\dibs\Entity\DibsTransaction;
use Drupal\dibs\Event\AcceptTransactionEvent;
use Drupal\dibs\Event\DibsEvents;
use Drupal\dibs\Form\DibsRedirectForm;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class DibsPagesController.
 *
 * @package Drupal\dibs\Controller
 */
class DibsPagesController extends ControllerBase {

  /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface  */
  protected $eventDispatcher;

  public function __construct(EventDispatcherInterface $event_dispatcher) {
    $this->eventDispatcher = $event_dispatcher;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('event_dispatcher')
    );
  }

  /**
   * Accept.
   *
   * @return string
   *   Return Hello string.
   */
  public function accept($transaction_hash) {
    $transaction = DibsTransaction::loadByHash($transaction_hash);

    if (!$transaction) {
      throw new NotFoundHttpException($this->t('Transaction with given hash was not found.'));
    }

    $this->eventDispatcher->dispatch(DibsEvents::ACCEPT_TRANSACTION, new AcceptTransactionEvent($transaction));

    return [
      '#theme' => 'dibs_accept_page',
      '#transaction' => $transaction,
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

    if (!$transaction) {
      throw new NotFoundHttpException($this->t('Transaction with given hash was not found.'));
    }

    if ($transaction->status->value != 'CREATED') {
      throw new AccessDeniedException($this->t('Given transaction was already processed.'));
    }

    $form = \Drupal::service('form_builder')->getForm(DibsRedirectForm::class, ['transaction' => $transaction]);

    return [
      '#theme' => 'dibs_redirect_page',
      '#form' => $form,
      '#transaction' => $transaction,
      '#inline_script' => '<script type="text/javascript">document.getElementById("dibs-redirect-form").submit()</script>',
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

}
