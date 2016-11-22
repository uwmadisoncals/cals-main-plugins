<?php 
///FIELD DEFINITIONS

//Variables
$prefix = 'calsteams_';

//////////////////////////////////////////// Fields /////////////////////////////////////////////////////////////////////////
// Naming Conventions: Be sure to namespace field variables by prefixing 'field_'


///////////// TEXT Fields //////////////////////////////

$field_first_name = array(
  'name'=>'First Name',
  'desc'=>'',
  'id'=>$prefix . 'first_name', //corresponds to input field name & id
  'type'=>'text',
  'std'=>'',//default value
  );

$field_last_name = array(
  'name'=>'Last Name',
  'desc'=>'',
  'id'=>$prefix . 'last_name', //corresponds to input field name & id
  'type'=>'text',
  'std'=>'',//default value
  );

$field_phone = array(
  'name'=>'Phone',
  'desc'=>'',
  'id'=>$prefix . 'phone', //corresponds to input field name & id
  'type'=>'text',
  'std'=>'',//default value
  );

$field_fax = array(
  'name'=>'Fax',
  'desc'=>'',
  'id'=>$prefix . 'fax', //corresponds to input field name & id
  'type'=>'text',
  'std'=>'',//default value
  );

$field_email = array(
  'name'=>'Email',
  'desc'=>'',
  'id'=>$prefix . 'email', //corresponds to input field name & id
  'type'=>'text',
  'std'=>'',//default value
  );


///////////// TEXTAREA Fields //////////////////////////////

$field_professional_title = array(
  'name'=>'Professional Title',
  'desc'=>'',
  'id'=>$prefix . 'professional_title', //corresponds to input field name & id
  'type'=>'text',
  'std'=>'',//default value
  );


///////////// SELECTBOX Fields //////////////////////////////

$field_name_prefix = array(
  'name'=>'Name Prefix',
  'desc'=>'This is where the room is.',
  'id'=>$prefix . 'name_prefix', //corresponds to input field name & id
  'type'=>'select',
  'options'=>array('','Mr.','Mrs.','Ms.','Dr.')
  );


///////////// RADIO Fields //////////////////////////////
$field_radio = array(
    'name' => 'Radio',
    'id' => $prefix . 'radio',
    'type' => 'radio',
    'options' => array(
        array('name' => 'Label 1', 'value' => 'Value 1'),

        array('name' => 'Label 2', 'value' => 'Value 2')
    )
  );


///////////// CHECKBOX Fields ///////////////////////////

$field_checkbox = array(
      'name' => 'Checkbox',
      'id' => $prefix . 'checkbox',
      'type' => 'checkbox'
  );


///////////// WYSIWYG Fields //////////////////////////////

$field_office_location = array(
  'name'=>'Office Location',
  'desc'=>'',
  'id'=>'calsteamswysiwygol', //corresponds to input field name & id
  'type'=>'wysiwyg',
  'std'=>'',//default value
  );

$field_wsyiwyg_description = array(
  'name'=>'Description',
  'desc'=>'',
  'id'=>'calsteamswysiwygdesc', //only lowercase letters per http://codex.wordpress.org/Function_Reference/wp_editor
  'type'=>'wysiwyg',
  'std'=>'',//default value
  );

$field_wsyiwyg_specialty = array(
  'name'=>'Specialty',
  'desc'=>'',
  'id'=>'calsteamswysiwygspec', //only lowercase letters per http://codex.wordpress.org/Function_Reference/wp_editor
  'type'=>'wysiwyg',
  'std'=>'',//default value
  );

$field_wsyiwyg_education = array(
  'name'=>'Education',
  'desc'=>'',
  'id'=>'calsteamswysiwygedu', //only lowercase letters per http://codex.wordpress.org/Function_Reference/wp_editor
  'type'=>'wysiwyg',
  'std'=>'',//default value
  );

$field_wsyiwyg_current_proj = array(
  'name'=>'Current Projects',
  'desc'=>'',
  'id'=>'calsteamswysiwygcproj', //only lowercase letters per http://codex.wordpress.org/Function_Reference/wp_editor
  'type'=>'wysiwyg',
  'std'=>'',//default value
  );

$field_wsyiwyg_research = array(
  'name'=>'Research',
  'desc'=>'',
  'id'=>'calsteamswysiwygresearch', //only lowercase letters per http://codex.wordpress.org/Function_Reference/wp_editor
  'type'=>'wysiwyg',
  'std'=>'',//default value
  );

$field_wsyiwyg_manuscripts = array(
  'name'=>'Manuscripts',
  'desc'=>'',
  'id'=>'calsteamswysiwygmanu', //only lowercase letters per http://codex.wordpress.org/Function_Reference/wp_editor
  'type'=>'wysiwyg',
  'std'=>'',//default value
  );



//////////////////////////////////////////// METABOX  /////////////////////////////////////////////////////////////////////////
$mbox = array(
  'id'=>'cals_teams_mbox_0',//HTML ID
  'title'=>'Team Member Data',//MetaBox Title
  'screen'=>'team',//custom post type slug
  'context'=>'normal',//display location
  'priority'=>'default',
  'fields'=>array($field_name_prefix, //Order determines output on edit-post screen
                  $field_first_name,
                  $field_last_name,
                  $field_professional_title,
                  $field_office_location,
                  $field_phone,
                  $field_fax,
                  $field_email,
                  $field_wsyiwyg_description,
                  $field_wsyiwyg_specialty,
                  $field_wsyiwyg_education,
                  $field_wsyiwyg_current_proj,
                  $field_wsyiwyg_research,
                  $field_wsyiwyg_manuscripts,
                  $field_radio,
                  $field_checkbox),
  );


 ?>