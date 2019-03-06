<?php

if (!defined('ABSPATH'))
    exit;
oxilab_flip_box_user_capabilities();

echo '<div class="oxilab-flip-box-col-3">';
$styledata = Array('id' => 21, 'style_name' => 'style2', 'css' => 'oxilab-flip-type |oxilab-flip-box-flip oxilab-flip-box-flip-left-to-right| oxilab-flip-effects |easing_easeInOutCirc| front-background-color |rgba(250, 250, 250, 1)| front-border-color |#39dfa5| front-icon-color |#ffffff| front-icon-background |rgba(102, 81, 237, 1)| front-heading-color |#333333| backend-background-color |rgba(250, 250, 250, 1)| backend-border-color |#39dfa5| backend-info-color |#888888| backend-button-color |#ffffff| backend-button-background |rgba(35, 35, 35, 1)| backend-button-hover-color |#ffffff| backend-button-hover-background |rgba(102, 81, 237, 1)| front-info-color |#888888| backend-title-color |#333333| || || || || || flip-col |oxilab-flip-box-col-1| flip-width |280| flip-height |280| margin-top |10| margin-left |10| flip-open-tabs || oxilab-animation || animation-duration |2| flip-boxshow-color |rgba(217, 217, 217, 0.73)| flip-boxshow-horizontal |0| flip-boxshow-vertical |2| flip-boxshow-blur |8| flip-boxshow-spread |0| || front-padding-top |7| front-padding-left |7| || front-icon-size |26| front-icon-width |60| front-icon-border-radius |40| front-heading-size |20| front-heading-family |Open+Sans| front-heding-style |normal| front-heding-weight |600| front-heding-text-align |Center| front-heding-padding-top |10| front-heding-padding-bottom |10| front-heding-padding-left |10| front-heding-padding-right |10| backend-padding-top |7| backend-padding-left |7| || backend-info-size |14| backend-info-family |Open+Sans| backend-info-style |normal| backend-info-weight |300| backend-info-text-align |Center| backend-info-padding-top |10| backend-info-padding-bottom |10| backend-info-padding-left |10| backend-info-padding-right |10| backend-button-size |14| backend-button-family |Open+Sans| backend-button-style |normal| backend-button-weight |300| backend-button-info-padding-top|10| backend-button-info-padding-left |20| backend-button-border-radius |40| backend-button-text-align |Center| backend-info-margin-top |10| backend-info-margin-bottom |10| backend-info-margin-left |10| backend-info-margin-right |10| flip-col-border-size |2| flip-col-border-style |solid| flip-border-radius |5| flip-backend-border-size |2| flip-backend-border-style |solid| front-icon-padding-top-bottom |10| front-icon-padding-left-right |10| front-info-size |14| front-info-family |Open+Sans| front-info-style |normal| front-info-weight |300| front-info-text-align |Center| front-info-padding-top |5| front-info-padding-bottom |5| front-info-padding-left |5| front-info-padding-right |5| backend-heading-size |18| backend-heading-family |Open+Sans| backend-heading-style |normal| backend-heading-weight |600| backend-heading-text-align |Center| backend-heading-padding-top |10| backend-heading-padding-bottom |10| backend-heading-padding-left |10| backend-heading-padding-right |10| custom-css |||');
$listdata = Array(
    0 => Array('id' => 1, 'styleid' => 21, 'title' => '',
        'files' => 'flip-box-front-title {#}|{#}Consectetuer Adipiscin{#}|{#} flip-box-front-icons {#}|{#}'. FlipBoxesImageAdFAData('fa-balance-scale').'{#}|{#} flip-box-image-upload-url-01 {#}|{#}{#}|{#} flip-box-backend-desc {#}|{#}Far away, behind the word consonantia there live the blind texts n which roasted parts of sentences.{#}|{#} flip-box-backend-button-text {#}|{#}Learn More{#}|{#} flip-box-backend-link {#}|{#}#{#}|{#} flip-box-image-upload-url-02 {#}|{#}{#}|{#} flip-box-font-desc {#}|{#}Far away, behind the word consonantia there live the blind texts n which roasted parts of sentences.{#}|{#} flip-box-backend-title {#}|{#}Consectetuer Adipiscin{#}|{#}',
      ),
);
echo '<input type="hidden" name="oxilab-flip-box-data-2-' . $listdata[0]['id'] . '" id="oxilab-flip-box-data-2-' . $listdata[0]['id'] . '" value="' . $styledata['css'] . '">';
echo '<input type="hidden" name="oxilab-flip-box-files-2-' . $listdata[0]['id'] . '" id="oxilab-flip-box-files-2-' . $listdata[0]['id'] . '" value="' . $listdata[0]['files'] . '">';
echo oxilab_flipbox_admin_style_layouts($styledata, $listdata);
echo '</div>';


echo '<div class="oxilab-flip-box-col-3">';
$styledata = Array('id' => 22, 'style_name' => 'style2', 'css' => 'oxilab-flip-type |oxilab-flip-box-flip oxilab-flip-box-flip-top-to-bottom| oxilab-flip-effects |easing_easeInOutCirc| front-background-color |rgba(37, 42, 48, 0.41)| front-border-color |#7d8185| front-icon-color |#ffffff| front-icon-background |rgba(102, 81, 237, 1)| front-heading-color |#ffffff| backend-background-color |rgba(37, 42, 48, 0.41)| backend-border-color |#7d8185| backend-info-color |#ffffff| backend-button-color |#ffffff| backend-button-background |rgba(214, 0, 203, 1)| backend-button-hover-color |#d600cb| backend-button-hover-background |rgba(255, 255, 255, 1)| front-info-color |#ffffff| backend-title-color |#ffffff| || || || || || flip-col |oxilab-flip-box-col-1| flip-width |280| flip-height |280| margin-top |10| margin-left |10| flip-open-tabs || oxilab-animation || animation-duration |2| flip-boxshow-color |rgba(217, 217, 217, 0.73)| flip-boxshow-horizontal |0| flip-boxshow-vertical |2| flip-boxshow-blur |8| flip-boxshow-spread |0| || front-padding-top |7| front-padding-left |7| || front-icon-size |26| front-icon-width |60| front-icon-border-radius |40| front-heading-size |20| front-heading-family |Open+Sans| front-heding-style |normal| front-heding-weight |600| front-heding-text-align |Center| front-heding-padding-top |10| front-heding-padding-bottom |10| front-heding-padding-left |10| front-heding-padding-right |10| backend-padding-top |7| backend-padding-left |7| || backend-info-size |14| backend-info-family |Open+Sans| backend-info-style |normal| backend-info-weight |300| backend-info-text-align |Center| backend-info-padding-top |10| backend-info-padding-bottom |10| backend-info-padding-left |10| backend-info-padding-right |10| backend-button-size |14| backend-button-family |Open+Sans| backend-button-style |normal| backend-button-weight |300| backend-button-info-padding-top|10| backend-button-info-padding-left |20| backend-button-border-radius |40| backend-button-text-align |Center| backend-info-margin-top |10| backend-info-margin-bottom |10| backend-info-margin-left |10| backend-info-margin-right |10| flip-col-border-size |2| flip-col-border-style |solid| flip-border-radius |5| flip-backend-border-size |2| flip-backend-border-style |solid| front-icon-padding-top-bottom |10| front-icon-padding-left-right |10| front-info-size |14| front-info-family |Open+Sans| front-info-style |normal| front-info-weight |300| front-info-text-align |Center| front-info-padding-top |5| front-info-padding-bottom |5| front-info-padding-left |5| front-info-padding-right |5| backend-heading-size |18| backend-heading-family |Open+Sans| backend-heading-style |normal| backend-heading-weight |600| backend-heading-text-align |Center| backend-heading-padding-top |10| backend-heading-padding-bottom |10| backend-heading-padding-left |10| backend-heading-padding-right |10| custom-css |||');
$listdata = Array(
    0 => Array('id' => 2, 'styleid' => 22, 'title' => '',
        'files' => 'flip-box-front-title {#}|{#}Consectetuer Adipiscin{#}|{#} flip-box-front-icons {#}|{#}'. FlipBoxesImageAdFAData('facebook').'{#}|{#} flip-box-image-upload-url-01 {#}|{#}' . oxilab_flip_box_admin_image('watch.jpg') . '{#}|{#} flip-box-backend-desc {#}|{#}Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live.{#}|{#} flip-box-backend-button-text {#}|{#}Learn More{#}|{#} flip-box-backend-link {#}|{#}#{#}|{#} flip-box-image-upload-url-02 {#}|{#}' . oxilab_flip_box_admin_image('watch.jpg') . '{#}|{#} flip-box-font-desc {#}|{#}Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live.{#}|{#} flip-box-backend-title {#}|{#}Image Title{#}|{#}',
      ),
);
echo '<input type="hidden" name="oxilab-flip-box-data-2-' . $listdata[0]['id'] . '" id="oxilab-flip-box-data-2-' . $listdata[0]['id'] . '" value="' . $styledata['css'] . '">';
echo '<input type="hidden" name="oxilab-flip-box-files-2-' . $listdata[0]['id'] . '" id="oxilab-flip-box-files-2-' . $listdata[0]['id'] . '" value="' . $listdata[0]['files'] . '">';
echo oxilab_flipbox_admin_style_layouts($styledata, $listdata);


echo '</div>';
echo '<div class="oxilab-flip-box-col-3">';
$styledata = Array('id' => 23, 'style_name' => 'style2', 'css' => 'oxilab-flip-type |oxilab-flip-box-flip oxilab-flip-box-flip-right-to-left| oxilab-flip-effects |easing_easeOutBack| front-background-color |rgba(38, 43, 49, 1)| front-border-color |#262b31| front-icon-color |#ffffff| front-icon-background |rgba(235, 42, 42, 1)| front-heading-color |#ffffff| backend-background-color |rgba(38, 43, 49, 1)| backend-border-color |#262b31| backend-info-color |#ffffff| backend-button-color |#ffffff| backend-button-background |rgba(42, 203, 210, 1)| backend-button-hover-color |#2acbd2| backend-button-hover-background |rgba(255, 255, 255, 1)| front-info-color |#ffffff| backend-title-color |#ffffff| || || || || || flip-col |oxilab-flip-box-col-1| flip-width |280| flip-height |280| margin-top |10| margin-left |10| flip-open-tabs || oxilab-animation || animation-duration |2| flip-boxshow-color |rgba(217, 217, 217, 0.73)| flip-boxshow-horizontal |0| flip-boxshow-vertical |2| flip-boxshow-blur |8| flip-boxshow-spread |0| || front-padding-top |7| front-padding-left |7| || front-icon-size |26| front-icon-width |60| front-icon-border-radius |40| front-heading-size |20| front-heading-family |Open+Sans| front-heding-style |normal| front-heding-weight |600| front-heding-text-align |Center| front-heding-padding-top |10| front-heding-padding-bottom |10| front-heding-padding-left |10| front-heding-padding-right |10| backend-padding-top |7| backend-padding-left |7| || backend-info-size |14| backend-info-family |Open+Sans| backend-info-style |normal| backend-info-weight |300| backend-info-text-align |Center| backend-info-padding-top |10| backend-info-padding-bottom |10| backend-info-padding-left |10| backend-info-padding-right |10| backend-button-size |14| backend-button-family |Open+Sans| backend-button-style |normal| backend-button-weight |300| backend-button-info-padding-top|10| backend-button-info-padding-left |20| backend-button-border-radius |40| backend-button-text-align |Center| backend-info-margin-top |10| backend-info-margin-bottom |10| backend-info-margin-left |10| backend-info-margin-right |10| flip-col-border-size |2| flip-col-border-style |solid| flip-border-radius |5| flip-backend-border-size |2| flip-backend-border-style |solid| front-icon-padding-top-bottom |10| front-icon-padding-left-right |10| front-info-size |14| front-info-family |Open+Sans| front-info-style |normal| front-info-weight |300| front-info-text-align |Center| front-info-padding-top |5| front-info-padding-bottom |5| front-info-padding-left |5| front-info-padding-right |5| backend-heading-size |18| backend-heading-family |Open+Sans| backend-heading-style |normal| backend-heading-weight |600| backend-heading-text-align |Center| backend-heading-padding-top |10| backend-heading-padding-bottom |10| backend-heading-padding-left |10| backend-heading-padding-right |10| custom-css |||');
$listdata = Array(
    0 => Array('id' => 3, 'styleid' => 23, 'title' => '',
        'files' => 'flip-box-front-title {#}|{#}Adipiscin{#}|{#} flip-box-front-icons {#}|{#}'. FlipBoxesImageAdFAData('fa-bandcamp').'{#}|{#} flip-box-image-upload-url-01 {#}|{#}{#}|{#} flip-box-backend-desc {#}|{#}Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live.{#}|{#} flip-box-backend-button-text {#}|{#}Buy Now{#}|{#} flip-box-backend-link {#}|{#}#{#}|{#} flip-box-image-upload-url-02 {#}|{#}{#}|{#} flip-box-font-desc {#}|{#}Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live.{#}|{#} flip-box-backend-title {#}|{#}Image Title{#}|{#}',
       )
);
echo '<input type="hidden" name="oxilab-flip-box-data-2-' . $listdata[0]['id'] . '" id="oxilab-flip-box-data-2-' . $listdata[0]['id'] . '" value="' . $styledata['css'] . '">';
echo '<input type="hidden" name="oxilab-flip-box-files-2-' . $listdata[0]['id'] . '" id="oxilab-flip-box-files-2-' . $listdata[0]['id'] . '" value="' . $listdata[0]['files'] . '">';
echo oxilab_flipbox_admin_style_layouts($styledata, $listdata);
echo '</div>';

/*
echo ' || || || || || ';
 * 
 */