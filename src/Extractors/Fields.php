<?php

namespace Drupal\drupal_extractor\Extractors;

// use \Drupal\Core\Url;
// use \Drupal\node\Entity\Node;
// use \Drupal\taxonomy\Entity\Term;
// use Drupal\drupal_extractor\Extractors;


class Fields {

  public static function file($field){
    if( $rawValues = self::getRawValues($field) ){
      $return = new \stdClass();
      $return->values = [];
      $return->field = self::getFieldData($field);
      foreach ($rawValues as $value) {
        if(!isset($value['target_id'])) continue;
        $file = \Drupal\file\Entity\File::load($value['target_id']);
        if($fields = Entities\File::get($file)){
          $fields->target_id = $value['target_id'];
          // Files
          if(isset($value['description'])) $fields->description = $value['description'];
          if(isset($value['display'])) $fields->display = (($value['display'] == '1') ? true : false);
          // Images
          if(isset($value['title'])) $fields->title = $value['title'];
          if(isset($value['alt'])) $fields->alt = $value['alt'];
          if(isset($value['width'])) $fields->width = $value['width'];
          if(isset($value['height'])) $fields->height = $value['height'];
          $return->values[] = $fields;
        }
      }
    }
    return empty($values) ? NULL : $return;
  }

  public static function image($field){
    return self::file($field);
  }

  public static function link($field){
    if( $rawValues = self::getRawValues($field) ){
      $return = new \stdClass();
      $return->values = [];
      $return->field = self::getFieldData($field);
    }
    return empty($return->values) ? NULL : $return;
  }

  public static function integer($field){
    if( $rawValues = self::getRawValues($field) ){
      $return = new \stdClass();
      $return->field = self::getFieldData($field);
      $return->values = [];
      foreach ($rawValues as $key => $value) {
        if(isset($value['value'])) $return->values[] = (int) $value['value'];
      }
    }
    return empty($return->values) ? NULL : $return;
  }

  public static function string($field){
    if( $rawValues = self::getRawValues($field) ){
      $return = new \stdClass();
      $return->field = self::getFieldData($field);
      $return->values = [];
      foreach ($rawValues as $key => $value) {
        if(isset($value['value'])) $return->values[] = $value['value'];
      }
    }
    return empty($return->values) ? NULL : $return;
  }

  public static function stringLong($field){
    return self::string($field);
  }

  public static function boolean($field){
    if( $rawValues = self::getRawValues($field) ){
      $return = new \stdClass();
      $return->field = self::getFieldData($field);
      $return->values = [];
      foreach ($rawValues as $key => $value) {
        if(isset($value['value'])) $return->values[] = ( $value['value'] == '1' ? TRUE : FALSE );
      }
    }
    return empty($return->values) ? NULL : $return;
  }

  public static function textLong($field){
    return self::string($field);
  }

  public static function textWithSummary($field){
    if( $rawValues = self::getRawValues($field) ){
      $return = new \stdClass();
      $return->field = self::getFieldData($field);
      $return->values = [];
      foreach ($rawValues as $value) {
        if(isset($value['value']) || isset($value['summary']) || isset($value['format'])){
          $fields = new \stdClass();
          if(isset($value['value'])) $fields->value = $value['value'];
          if(isset($value['summary'])) $fields->summary = $value['summary'];
          if(isset($value['format'])) $fields->format = $value['format'];
        }
        if(isset($fields)) $return->values[] = $fields;
        unset($fields);
      }
    }
    return empty($return->values) ? NULL : $return;
  }

  public static function paragraph($field){
    if( $rawValues = self::getRawValues($field) ){
      $return = new \stdClass();
      $return->field = self::getFieldData($field);
      $return->values = [];
    }
    return empty($return->values) ? NULL : $return;
  }

  public static function entityReference($field){
    if( $rawValues = self::getRawValues($field) ){
      $return = new \stdClass();
      $return->field = self::getFieldData($field);
      $return->values = [];
    }
    return empty($return->values) ? NULL : $return;
  }

  /*********************************************/

  /**
   * Get field raw values
   *
   * @param [type] $field
   * @return array
   *
   * @author chr1sp1n-dev <chr1sp1n.dev@gmail.com>
   */
  private static function getRawValues($field){
    if(!$field instanceof \Drupal\Core\Field\FieldItemList ){
      return null;
    }
    $values = $field->getValue();
    return !is_null($values) && is_array($values) ? $values : NULL;
  }

  /**
   * Get field definitions ed data
   *
   * @param [type] $field
   * @return object
   *
   * @author chr1sp1n-dev <chr1sp1n.dev@gmail.com>
   */
  private static function getFieldData($field){
    $definitions = $field->getFieldDefinition();
    $field = new \stdClass();
    $field->name = $definitions->getName();
    $field->type = $definitions->getType();
    if($return = $definitions->getLabel()){
      if($return instanceof \Drupal\Core\StringTranslation\TranslatableMarkup){
        $field->label = $return->render();
      }else{
        $field->label = $return;
      }
    }
    if($return = $definitions->getDescription()){
      $field->description = $return->render();
    }
    return $field;
  }

}
