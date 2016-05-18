<?php

namespace Drupal\dibs\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DibsRedirectForm.
 *
 * @package Drupal\dibs\Form
 */
class DibsRedirectForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dibs_redirect_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('dibs.settings');
    $form['#action'] = 'https://payment.architrade.com/paymentweb/start.action';
    $transaction = $form_state->getBuildInfo()['args'][0]['transaction'];
    $form['amount'] = [
      '#type' => 'hidden',
      '#value' => $transaction->amount->value,
    ];
    $form['accepturl'] = [
      '#type' => 'hidden',
      '#value' => $this->getUrlGenerator()->generateFromRoute('dibs.dibs_pages_controller_accept', ['transaction_hash' => $transaction->hash->value], ['absolute' => TRUE])
    ];
    $form['callbackurl'] = [
      '#type' => 'hidden',
      '#value' => $this->getUrlGenerator()->generateFromRoute('dibs.dibs_pages_controller_callback', ['transaction_hash' => $transaction->hash->value], ['absolute' => TRUE]),
    ];
    $form['windowtype'] = [
      '#type' => 'hidden',
      '#value' => 'mobile',
    ];
    $form['currency'] = [
      '#type' => 'hidden',
      '#value' => $config->get('general.currency'),
    ];
    $form['merchant'] = [
      '#type' => 'hidden',
      '#value' => $config->get('general.merchant_id'),
    ];
    $form['orderid'] = [
      '#type' => 'hidden',
      '#value' => $transaction->order_id->value,
    ];
    $form['test'] = [
      '#type' => 'hidden',
      '#value' => $config->get('general.test_mode'),
    ];
    $form['lang'] = [
      '#type' => 'hidden',
      '#value' => 'en',
    ];
    $form['paytype'] = [
      '#type' => 'hidden',
      '#value' => 'VISA',
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'submit',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
