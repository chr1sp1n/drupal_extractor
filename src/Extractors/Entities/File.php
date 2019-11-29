<?php

namespace Drupal\drupal_extractor\Extractors\Entities;

use Drupal\drupal_extractor\Utility;
use Drupal\drupal_extractor\Extractors;


/**
 * File fields extractor
 *
 * @method void get($file)
 *
 * @author chr1sp1n-dev <chr1sp1n.dev@gmail.com>
 */
class File {

  /**
   * Extract all translated fields in current node except the fields in $fieldExcluded array
   *
   * @param string $language_code
   * @param array $fieldsExcluded
   *
   * @author chr1sp1n-dev <chr1sp1n.dev@gmail.com>
   */
  public static function get($entity, string $languageCode = null, array $fieldsExcluded = []){
    if (!$entity instanceof \Drupal\file\Entity\File ) {
      return;
    }

    $languageCode = $languageCode ? $languageCode : Utility\Language::current();
    $entity = Utility\Entities::getTranslated($entity, $languageCode);

    $data = new \stdClass();
    $data->language_code = $languageCode;
    $data->url = $entity->url();

    if($fields = $entity->getFields()){
      foreach ($fields as $fieldName => $field) {
        if(in_array($fieldName, $fieldsExcluded)) continue;
        $typeName = $field->getFieldDefinition()->getType();
        $method = Utility\Normalizer::methodName($typeName);
        if( method_exists( '\Drupal\drupal_extractor\Extractors\Fields', $method ) ){
          $data->{$fieldName} = Extractors\Fields::$method($field, $languageCode);
        }
      }
    }

    return $data;

  }

}
