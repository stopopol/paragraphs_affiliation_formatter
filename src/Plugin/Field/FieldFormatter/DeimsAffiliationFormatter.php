<?php

namespace Drupal\deims_affiliation_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'DeimsAffiliationFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "deims_affiliation_formatter",
 *   label = @Translation("DEIMS Affiliation Formatter"),
 *   field_types = {
 *     "entity_reference_revisions"
 *   },
 *   quickedit = {
 *     "editor" = "disabled"
 *   }
 * )
 */
 
class DeimsAffiliationFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
   
 
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t('Formats the affiliation field of Drupal.');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];
	
	// Render each element as markup in case of multi-values.

	foreach ($items as $delta => $item) {
		  
	  // Desired fields: field_network_name, field_network_specific_site_code, field_network_verified;
	  if ($item->entity->field_network) {
		 $network_label = $item->entity->field_network->entity->field_name->value;
		 //$network_label = $item->entity->field_network->entity->getTitle();
	  }
	  else {
		break;
	  }  
	  // add network related site code if existant
	  if ($item->entity->field_network_specific_site_code->value) {
		$network_site_code = " (" . $item->entity->field_network_specific_site_code->value . ")";
	  }
	  else {
		$network_site_code = "";
	  }
	  // add verification status, if it is a verified site
	  if ($item->entity->field_network_verified->value) {
		$network_site_verified = $item->entity->field_network_verified->value;
		$network_site_verified = '<sup class="green-check">✔</sup>';
		$network_element = '<div class="verification-tooltip">' . $network_label . $network_site_verified . $network_site_code . '<span class="verification-tooltiptext">This site is a verified ' .$network_label. ' member</span></div>';
	  
	  }
	  else {
		  $network_site_verified = "";
		  $network_element = '<div class="verification-tooltip">' . $network_label . $network_site_verified . $network_site_code . '</div>';
	  }
	  
	  
	  $element[$delta] = [
		'#markup' => $network_element,
		'#attached' => [
			'library' => [
			  'deims_affiliation_formatter/deims-affiliation-formatter-style',
			],
		]
	  ];
	} 
	// sort elements of result alphabetically
	sort($element);
	return $element;
  }
	
}
