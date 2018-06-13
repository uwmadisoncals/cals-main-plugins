<?php

function twenty20_shortcode_init( $atts) {
  extract( shortcode_atts(
    array(
      'img1' => '',
      'img2' => '',
      'offset' => '0.5',
      'direction' => 'horizontal',
      'width' => '',
      'align' => '',
    ), $atts )
  );

  $isVertical = "";
  $data_vertical = "";
  $isLeft = "";
  $isRight = "";

  if ($align == "right"){
    $isRight = " float: right; margin-left: 20px;";
    if (empty($width)){ $width = "width: 50%;"; }
  }

  if ($align == "left"){
    $isLeft = " float: left; margin-right: 20px;";
    if (empty($width)){ $width = "width: 50%;"; }
  }

  if (empty($width)){
    $width = "width: 100% !important; clear: both;";
  }else{
    $width = "width: " . $width . ';';
  }

  if($direction == "vertical"){
    $isVertical = ' data-orientation="vertical"';
    $data_vertical = ", orientation: 'vertical'";
  }

if(!empty($img1) && !empty($img2)){

  $output = '<div class="twenty20" style="'. $width . $isLeft . $isRight . '">';
  $output .= '<div class="twentytwenty-container"' . $isVertical . '>';
  $output .= '<img src="'. wp_get_attachment_url( $img1 ) .'" />';
  $output .= '<img src="'. wp_get_attachment_url( $img2 ) .'" />';
  $output .= '</div>';
  $output .= '<script>jQuery(window).load(function(){';
  if($direction == "vertical"){
    $output .= 'jQuery(".twentytwenty-container[data-orientation=\'vertical\']").twentytwenty({default_offset_pct: ' . $offset . $data_vertical .'});';
  }else{
    $output .= 'jQuery(".twentytwenty-container[data-orientation!=\'vertical\']").twentytwenty({default_offset_pct: '.$offset.'});';
  }
  $output .= '});</script></div>';
}else{
  $output = '<div class="twenty20" style="color: red;">Twenty20 need two images.</div>';
}

  return $output;
}
add_shortcode( 'twenty20', 'twenty20_shortcode_init' );