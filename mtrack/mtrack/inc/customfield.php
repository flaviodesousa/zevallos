<?php # vim:ts=2:sw=2:et:

class MTrackTicket_CustomField {
  var $name;
  var $type;
  var $label;
  var $group;
  var $order = 0;
  var $default;
  var $options;

  static function canonName($name) {
    if (!preg_match("/^x_/", $name)) {
      $name = "x_$name";
    }
    return $name;
  }

  /** load the field definition from the configuration file */
  static function load($name) {
    if (!preg_match("/^x_[a-z_]+$/", $name)) {
      throw new Exception("invalid field name $name");
    }

    $field = new MTrackTicket_CustomField;
    $field->name = $name;

    $field->type  = MTrackConfig::get('ticket.custom', "$name.type");
    $field->label = MTrackConfig::get('ticket.custom', "$name.label");
    $field->group = MTrackConfig::get('ticket.custom', "$name.group");
    $field->order   = (int)MTrackConfig::get('ticket.custom', "$name.order");
    $field->default = MTrackConfig::get('ticket.custom', "$name.default");
    $field->options = MTrackConfig::get('ticket.custom', "$name.options");

    return $field;
  }

  function save() {
    if (!preg_match("/^x_[a-z_]+$/", $this->name)) {
      throw new Exception("invalid field name $this->name");
    }
    $name = $this->name;
    MTrackConfig::set('ticket.custom', "$name.type", $this->type);
    MTrackConfig::set('ticket.custom', "$name.label", $this->label);
    MTrackConfig::set('ticket.custom', "$name.group", $this->group);
    MTrackConfig::set('ticket.custom', "$name.order", (int)$this->order);
    MTrackConfig::set('ticket.custom', "$name.default", $this->default);
    MTrackConfig::set('ticket.custom', "$name.options", $this->options);
  }

  function ticketData() {
    /* compatible with the $FIELDSET data used in web/ticket.php */
    $data = array(
      'label' => $this->label,
      'type' => $this->type,
    );

    if (strlen($this->default)) {
      $data['default'] = $this->default;
    }

    switch ($this->type) {
      case 'multi':
      case 'wiki':
      case 'shortwiki':
        $data['ownrow'] = true;
        $data['rows'] = 5;
        $data['cols'] = 78;
        break;
      case 'select':
      case 'multiselect':
        $options = array('' => ' --- ');
        foreach (explode('|', $this->options) as $opt) {
          $options[$opt] = $opt;
        }
        $data['options'] = $options;
        break;
    }
    return $data;
  }
}

class MTrackTicket_CustomFields
  implements IMTrackIssueListener
{
  var $fields = array();

  var $field_types = array(
    'text' => 'Text (single line)',
    'multi' => 'Text (multi-line)',
    'wiki' => 'Wiki',
    'shortwiki' => 'Wiki (shorter height)',
    'select' => 'Select box (choice of one)',
// Don't allow multi-select for now; need a sane way to make the value
// into an array.
//    'multiselect' => 'Multiple select',
  );

  function save() {
    $this->alterSchema();

    $fieldlist = join(',', array_keys($this->fields));
    MTrackConfig::set('ticket', 'customfields', $fieldlist);

    foreach ($this->fields as $field) {
      $field->save();
    }
  }

  function fieldByName($name, $create = false) {
    $name = MTrackTicket_CustomField::canonName($name);
    if (!isset($this->fields[$name]) && $create) {
      $field = new MTrackTicket_CustomField;
      $field->name = $name;
      $this->fields[$name] = $field;
    } else if (!isset($this->fields[$name])) {
      return null;
    }
    return $this->fields[$name];
  }

  function deleteField($field) {
    if (!($field instanceof MTrackTicket_CustomField)) {
      $field = $this->fieldByName($field);
    }
    if (!($field instanceof MTrackTicket_CustomField)) {
      throw new Exception("can't delete an unknown field");
    }
    unset($this->fields[$field->name]);
  }

  function vetoMilestone(MTrackIssue $issue,
      MTrackMilestone $ms, $assoc = true) {
    return true;
  }
  function vetoKeyword(MTrackIssue $issue,
      MTrackKeyword $kw, $assoc = true) {
    return true;
  }
  function vetoComponent(MTrackIssue $issue,
      MTrackComponent $comp, $assoc = true) {
    return true;
  }
  function vetoProject(MTrackIssue $issue,
      MTrackProject $proj, $assoc = true) {
    return true;
  }
  function vetoComment(MTrackIssue $issue, $comment) {
    return true;
  }
  function vetoSave(MTrackIssue $issue, $oldFields) {
    return true;
  }

  function _orderField($a, $b) {
    $diff = $a->order - $b->order;
    if ($diff == 0) {
      return strnatcasecmp($a->label, $b->label);
    }
    return $diff;
  }

  function getGroupedFields() {
    $grouped = array();
    foreach ($this->fields as $field) {
      $grouped[$field->group][$field->name] = $field;
    }
    $result = array();
    $names = array_keys($grouped);
    asort($grouped);
    foreach ($grouped as $name => $group) {
      uasort($group, array($this, '_orderField'));
      $result[$name] = $group;
    }
    return $result;
  }

  function augmentFormFields(MTrackIssue $issue, &$fieldset) {
    $grouped = $this->getGroupedFields();
    foreach ($grouped as $group) {
      foreach ($group as $field) {
        $fieldset[$field->group][$field->name] = $field->ticketData();
      }
    }
  }

  function augmentSaveParams(MTrackIssue $issue, &$params) {
    foreach ($this->fields as $field) {
      $params[$field->name] = $issue->{$field->name};
    }
  }
  function augmentIndexerFields(MTrackIssue $issue, &$idx) {
    foreach ($this->fields as $field) {
      $idx[$field->name] = $issue->{$field->name};
    }
  }

  function applyPOSTData(MTrackIssue $issue, $post) {
    foreach ($this->fields as $field) {
      if ($field->type == 'multiselect') {
        $issue->{$field->name} = join('|', $post[$field->name]);
      } else {
        $issue->{$field->name} = $post[$field->name];
      }
    }
  }

  function alterSchema() {
    $names = array();
    foreach ($this->fields as $field) {
      $names[] = $field->name;
    }
    $db = MTrackDB::get();
    try {
      $db->exec("select " . join(', ', $names) . ' from tickets limit 1');
    } catch (Exception $e) {
      foreach ($names as $name) {
        try {
          $db->exec("ALTER TABLE tickets add column $name text");
        } catch (Exception $e) {
        }
      }
    }
  }

  function __construct() {
    MTrackIssue::registerListener($this);

    /* read in custom fields from ini */
    $fieldlist = MTrackConfig::get('ticket', 'customfields');
    if ($fieldlist) {
      $fieldlist = preg_split("/\s*,\s*/", $fieldlist);
      foreach ($fieldlist as $fieldname) {
        $field = MTrackTicket_CustomField::load($fieldname);
        $this->fields[$field->name] = $field;
      }
    }
  }

  static $me = null;
  static function getInstance() {
    if (self::$me !== null) {
      return self::$me;
    }
    self::$me = new MTrackTicket_CustomFields;
    return self::$me;
  }
}

MTrackTicket_CustomFields::getInstance();


