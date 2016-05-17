<?php

namespace Drupal\dibs\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DibsSettingsForm.
 *
 * @package Drupal\dibs\Form
 */
class DibsSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'dibs.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dibs_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('dibs.settings');

    $form['general'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('General DIBS settings'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
      '#tree' => TRUE,
    ];
    $form['general']['merchant_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Merchant ID'),
      '#max_length' => 30,
      '#required' => TRUE,
      '#description' => $this->t('DIBS Merchant ID'),
      '#default_value' => $config->get('general.merchant_id'),
    ];
    $form['general']['account'] = [
      '#type' => 'textfield',
      '#title' => t('Account'),
      '#default_value' => $config->get('general.account'),
      '#description' => $this->t('DIBS Account ID. Only used if the DIBS gateway is running multiple accounts.'),
    ];
    $form['general']['test_mode'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Test mode'),
      '#default_value' => $config->get('general.test_mode'),
      '#description' => $this->t('Is the gateway running in test mode'),
    ];
    $form['general']['type'] = [
      '#type' => 'select',
      '#title' => $this->t('Window type'),
      '#required' => TRUE,
      '#options' => [
        'pay' => $this->t('Pay window'),
        'flex' => $this->t('Flex window'),
        'mobile' => $this->t('Mobile window'),
      ],
      '#default_value' => $config->get('general.type'),
      '#description' => $this->t('If enabled, DIBS will make some extra checks on the sent data, to be sure that no one manipulated it. If enabled should the keys below be filled in!'),
    ];

    $form['general']['lang'] = [
      '#type' => 'select',
      '#title' => $this->t('Language'),
      '#options' => [
        'da' => 'Danish',
        'sv' => 'Swedish',
        'no' => 'Norwegian',
        'en' => 'English',
        'nl' => 'Dutch',
        'de' => 'German',
        'fr' => 'French',
        'fi' => 'Finnish',
        'es' => 'Spanish',
        'it' => 'Italian',
        'pl' => 'Polish'
      ],
      '#required' => TRUE,
      '#default_value' => $config->get('general.lang'),
      '#description' => $this->t('Language code for the language used on the DIBS payment window'),
    ];
    $form['general']['currency'] = [
      '#type' => 'select',
      '#title' => $this->t('Currency'),
      '#options' => [
        '208' => 'Danish Kroner (DKK)',
        '978' => 'Euro (EUR)',
        '840' => 'US Dollar $ (USD)',
        '826' => 'English Pound £ (GBP)',
        '752' => 'Swedish Kronor (SEK)',
        '036' => 'Australian Dollar (AUD',
        '124' => 'Canadian Dollar (CAD)',
        '352' => 'Icelandic Króna (ISK)',
        '392' => 'Japanese Yen (JPY)',
        '554' => 'New Zealand Dollar (NZD)',
        '578' => 'Norwegian Kroner (NOK)',
        '756' => 'Swiss Franc (CHF)',
        '949' => 'Turkish Lire (TRY)',
      ],
      '#required' => TRUE,
      '#default_value' => $config->get('general.currency'),
      '#description' => $this->t('Currency code for the currency used when paying.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('dibs.settings')
      ->set('general.merchant_id', $form_state->getValue('general')['merchant_id'])
      ->set('general.account', $form_state->getValue('general')['account'])
      ->set('general.test_mode', $form_state->getValue('general')['test_mode'])
      ->set('general.type', $form_state->getValue('general')['type'])
      ->set('general.lang', $form_state->getValue('general')['lang'])
      ->set('general.currency', $form_state->getValue('general')['currency'])
      ->save();
  }

}
