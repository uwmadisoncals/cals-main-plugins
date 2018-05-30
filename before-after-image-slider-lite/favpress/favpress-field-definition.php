<?php
return array(
    'modal_title'  => __('Before After Image Slider', 'before-after-images-slider-lite'),
    'button_title' => __('Before After Image Slider', 'before-after-images-slider-lite'),
    'types'        => array('post', 'page'),
    'main_image'   => plugins_url('main_image.jpg', __FILE__),
    'sprite_image' => plugins_url('sprite_image.jpg', __FILE__),
    'name'         => 'image_comparator',
    'template'     => array(
        'Media' => array(
            'elements' => array(
                'image-comparator' => array(
                    'title'      => __('Before After Image Slider Lite', 'before-after-images-slider-lite'),
                    'code'       => '[image-comparator][/image-comparator]',
                    'active'     => true,
                    'attributes' => array(
                        array(
                            'name'        => 'title',
                            'type'        => 'textbox',
                            'label'       => __('Title', 'before-after-images-slider-lite'),
                            'description' => __('An optional title above the images.', 'before-after-images-slider-lite')
                        ),
                        array(
                            'type'        => 'upload',
                            'label'       => __('Left Image', 'before-after-images-slider-lite'),
                            'name'        => 'left',
                            'validation'  => 'required',
                            'description' => __('The left image to use. (Required)', 'before-after-images-slider-lite'),
                            'mode'        => 'url'
                        ),
                        array(
                            'type'        => 'upload',
                            'label'       => __('Right Image', 'before-after-images-slider-lite'),
                            'name'        => 'right',
                            'validation'  => 'required',
                            'description' => __('The right image to use. (Required)', 'before-after-images-slider-lite'),
                            'mode'        => 'url'
                        ),
                        array(
                            'type'        => 'textbox',
                            'label'       => __('Width', 'before-after-images-slider-lite'),
                            'name'        => 'width',
                            'default'     => '100%',
                            'description' => __('An optional width, use px or % (default is 500px)', 'before-after-images-slider-lite')
                        ),
                        array(
                            'type'        => 'textbox',
                            'label'       => __('Left Alt', 'before-after-images-slider-lite'),
                            'name'        => 'left_alt',
                            'description' => __('Specify a value for the <b>"alt"</b> attribute of the left image.', 'before-after-images-slider-lite'),
                        ),
                        array(
                            'type'        => 'textbox',
                            'label'       => __('Right Alt', 'before-after-images-slider-lite'),
                            'name'        => 'right_alt',
                            'description' => __('Specify a value for the <b>"alt"</b> attribute of the right image.', 'before-after-images-slider-lite')
                        ),
                        array(
                            'type'        => 'textbox',
                            'label'       => __('CSS Classes (i.e. hover)', 'before-after-images-slider-lite'),
                            'name'        => 'classes',
                            'description' => __('Additional CSS classes, use <b>"hover"</b> to enable these extras', 'before-after-images-slider-lite'),
                            'default'     => 'hover'
                        ),
                        array(
                            'type'        => 'notebox',
                            'name'        => 'nb_1',
                            'label'       => __('Want to support this plugin or need more features?', 'before-after-images-slider-lite'),
                            'description' => __('There is also a <a href="https://codecanyon.net/item/before-after-imagecontent-slider-for-wordpress/6503930?ref=scrobbleme" target="_blank">pro version</a> available with direct support and additional features like <strong>more modes, Visual Composer support, setting an inital value, linking images</strong> and <strong>more</strong>...<br />'
                                . '<a href="https://codecanyon.net/item/before-after-imagecontent-slider-for-wordpress/6503930?ref=scrobbleme" target="_blank">Get Pro Version</a>', 'before-after-images-slider-lite'),
                            'status'      => 'normal'
                        ),
                        array(
                            'type'        => 'notebox',
                            'name'        => 'nb_2',
                            'label'       => __('Please rate', 'before-after-images-slider-lite') . ' <img style="height: 16px;" src="' . plugins_url('stars.png', __FILE__) . '" />',
                            'description' => __('If you like this plugin, please give it a <a href="https://wordpress.org/support/view/plugin-reviews/before-after-image-slider-lite" target="_blank">good rating</a>. If you think, there is something wrong, please use the support forum first.', 'before-after-images-slider-lite'),
                            'status'      => 'normal'
                        )
                    )
                )
            )
        )
    )
);