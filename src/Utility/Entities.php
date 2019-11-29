<?php

namespace Drupal\drupal_extractor\Utility;


/**
 * Entities utility
 *
 * @author chr1sp1n-dev <chr1sp1n.dev@gmail.com>
 */
class Entities {

  /**
   * Get translated entity.
   *
   * @param [type] $node
   * @param string $language_code
   * @author chr1sp1n-dev <chr1sp1n.dev@gmail.com>
   */
  public static function getTranslated($entity, string $language_code = null){
    $language_code = $language_code ? $language_code : Utility\Language::current();
    if($entity->hasTranslation($language_code)) {
      return $entity->getTranslation($language_code);
    }
    return $entity;
  }

}
