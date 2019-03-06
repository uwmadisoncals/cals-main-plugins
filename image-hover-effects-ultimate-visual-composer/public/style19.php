<?php
if (!defined('ABSPATH'))
    exit;

function oxilab_flip_box_shortcode_function_style19($styleid, $userdata, $styledata, $listdata) {
    ?>
    <div class="oxilab-flip-box-wrapper">
        <?php
        foreach ($listdata as $value) {
            if ($userdata == 'admin') {
                $admindata = 'oxilab-ab-id';
            } else {
                $admindata = '';
            }
            $filesdata = explode("{#}|{#}", $value['files']);
            ?>
            <div class="<?php echo $styledata[43]; ?> oxilab-flip-box-padding-<?php echo $styleid; ?> <?php echo $admindata; ?> oxilab-animation" oxilab-animation="<?php echo $styledata[55]; ?>">
                <div class="oxilab-flip-box-body-<?php echo $styleid; ?> oxilab-flip-box-body-<?php echo $styleid; ?>-<?php echo $value['id']; ?>">
                    <?php
                    if ($filesdata[3] == '' && $filesdata[11] != '') {
                        echo '<a href="' . $filesdata[11] . '" target="' . $styledata[53] . '">';
                        $fileslinkend = '</a>';
                    } else {
                        $fileslinkend = '';
                    }
                    ?>
                    <div class="oxilab-flip-box-body-absulote">
                        <div class="<?php echo $styledata[1]; ?>">
                            <div class="oxilab-flip-box-style-data <?php echo $styledata[3]; ?>">
                                <div class="oxilab-flip-box-style">
                                    <div class="oxilab-flip-box-front">
                                        <div class="oxilab-flip-box-<?php echo $styleid; ?>">
                                            <img src="<?php echo $filesdata[5]; ?>">
                                        </div>
                                    </div>
                                    <div class="oxilab-flip-box-back">
                                        <div class="oxilab-flip-box-back-<?php echo $styleid; ?>">
                                            <div class="oxilab-flip-box-back-<?php echo $styleid; ?>-data">
                                                <div class="oxilab-heading">
                                                    <?php echo oxilab_flip_box_special_charecter($filesdata[17]); ?>
                                                </div>
                                                <div class="oxilab-info">
                                                    <?php echo oxilab_flip_box_special_charecter($filesdata[7]); ?>
                                                </div>  
                                            </div>
                                            <?php
                                            if ($filesdata[3] != '') {
                                                echo '<a href="' . $filesdata[11] . '" target="' . $styledata[53] . '">';
                                                echo '<div class="oxilab-button">
                                                                    '.oxilab_flip_box_font_icon($filesdata[3]).'
                                                                </div>';
                                                echo '</a>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo $fileslinkend; ?>
                </div>
                <style>
        <?php
        if ($filesdata[13] != '') {
            echo '.oxilab-flip-box-body-' . $styleid . '-' . $value['id'] . ' .oxilab-flip-box-back-' . $styleid . '{
background: linear-gradient(' . $styledata[5] . ', ' . $styledata[5] . '), url("' . $filesdata[13] . '");
-moz-background-size: 100% 100%;
-o-background-size: 100% 100%;
background-size: 100% 100%;
}';
        }
        ?>
                </style>
                <?php
                if ($userdata == 'admin') {
                    ?>
                    <div class="oxilab-admin-absulote">
                        <div class="oxilab-style-absulate-edit">
                            <form method="post"> 
                                <input type="hidden" name="item-id" value="<?php echo $value['id']; ?>">
                                <button class="btn btn-primary" type="submit" value="edit" name="edit" title="Edit">Edit</button>
                                <?php echo wp_nonce_field("oxilab_flip_box_edit_data"); ?>
                            </form>
                        </div>
                        <div class="oxilab-style-absulate-delete">
                            <form method="post">
                                <input type="hidden" name="item-id" value="<?php echo $value['id']; ?>">
                                <button class="btn btn-danger" type="submit" value="delete" name="delete" title="Delete">Delete</button>
                                <?php echo wp_nonce_field("oxilab_flip_box_delete_data"); ?>
                            </form>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
        }
        ?>
    </div>
    <style>
        .oxilab-flip-box-padding-<?php echo $styleid; ?>{
            padding: <?php echo $styledata[49]; ?>px <?php echo $styledata[51]; ?>px;
            -webkit-transition:  opacity <?php echo $styledata[57]; ?>s linear;
            -moz-transition:  opacity <?php echo $styledata[57]; ?>s linear;
            -ms-transition:  opacity <?php echo $styledata[57]; ?>s linear;
            -o-transition:  opacity <?php echo $styledata[57]; ?>s linear;
            transition:  opacity <?php echo $styledata[57]; ?>s linear;
            -webkit-animation-duration: <?php echo $styledata[57]; ?>s;
            -moz-animation-duration: <?php echo $styledata[57]; ?>s;
            -ms-animation-duration: <?php echo $styledata[57]; ?>s;
            -o-animation-duration: <?php echo $styledata[57]; ?>s;
            animation-duration: <?php echo $styledata[57]; ?>s;
        }
        .oxilab-flip-box-body-<?php echo $styleid; ?>{
            max-width: <?php echo $styledata[45]; ?>px;
            width: 100%;
            margin: 0 auto;
            position: relative;   
        }
        .oxilab-flip-box-body-<?php echo $styleid; ?>:after {
            padding-bottom: <?php echo $styledata[47] / $styledata[45] * 100; ?>%;
            content: "";
            display: block;
        }
        .oxilab-flip-box-<?php echo $styleid; ?>{
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: block;
            -webkit-border-radius: <?php echo $styledata[153]; ?>px;
            -moz-border-radius: <?php echo $styledata[153]; ?>px;
            -ms-border-radius: <?php echo $styledata[153]; ?>px;
            -o-border-radius: <?php echo $styledata[153]; ?>px;
            border-radius: <?php echo $styledata[153]; ?>px;
            overflow: hidden;
            -webkit-box-shadow: <?php echo $styledata[61]; ?>px <?php echo $styledata[63]; ?>px <?php echo $styledata[65]; ?>px <?php echo $styledata[67]; ?>px <?php echo $styledata[59]; ?>;
            -moz-box-shadow: <?php echo $styledata[61]; ?>px <?php echo $styledata[63]; ?>px <?php echo $styledata[65]; ?>px <?php echo $styledata[67]; ?>px <?php echo $styledata[59]; ?>;
            -ms-box-shadow: <?php echo $styledata[61]; ?>px <?php echo $styledata[63]; ?>px <?php echo $styledata[65]; ?>px <?php echo $styledata[67]; ?>px <?php echo $styledata[59]; ?>;
            -o-box-shadow: <?php echo $styledata[61]; ?>px <?php echo $styledata[63]; ?>px <?php echo $styledata[65]; ?>px <?php echo $styledata[67]; ?>px <?php echo $styledata[59]; ?>;
            box-shadow: <?php echo $styledata[61]; ?>px <?php echo $styledata[63]; ?>px <?php echo $styledata[65]; ?>px <?php echo $styledata[67]; ?>px <?php echo $styledata[59]; ?>;
        }
        .oxilab-flip-box-<?php echo $styleid; ?> img{
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: block;
            -webkit-border-radius: <?php echo $styledata[153]; ?>px;
            -moz-border-radius: <?php echo $styledata[153]; ?>px;
            -ms-border-radius: <?php echo $styledata[153]; ?>px;
            -o-border-radius: <?php echo $styledata[153]; ?>px;
            border-radius: <?php echo $styledata[153]; ?>px;
            overflow: hidden;
            width: 100%;
            height: 100%;
        }
        .oxilab-flip-box-back-<?php echo $styleid; ?>{
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: block;
            background-color: <?php echo $styledata[5]; ?>;
            -webkit-border-radius: <?php echo $styledata[153]; ?>px;
            -moz-border-radius: <?php echo $styledata[153]; ?>px;
            -ms-border-radius: <?php echo $styledata[153]; ?>px;
            -o-border-radius: <?php echo $styledata[153]; ?>px;
            border-radius: <?php echo $styledata[153]; ?>px;
            overflow: hidden;
            -webkit-box-shadow: <?php echo $styledata[61]; ?>px <?php echo $styledata[63]; ?>px <?php echo $styledata[65]; ?>px <?php echo $styledata[67]; ?>px <?php echo $styledata[59]; ?>;
            -moz-box-shadow: <?php echo $styledata[61]; ?>px <?php echo $styledata[63]; ?>px <?php echo $styledata[65]; ?>px <?php echo $styledata[67]; ?>px <?php echo $styledata[59]; ?>;
            -ms-box-shadow: <?php echo $styledata[61]; ?>px <?php echo $styledata[63]; ?>px <?php echo $styledata[65]; ?>px <?php echo $styledata[67]; ?>px <?php echo $styledata[59]; ?>;
            -o-box-shadow: <?php echo $styledata[61]; ?>px <?php echo $styledata[63]; ?>px <?php echo $styledata[65]; ?>px <?php echo $styledata[67]; ?>px <?php echo $styledata[59]; ?>;
            box-shadow: <?php echo $styledata[61]; ?>px <?php echo $styledata[63]; ?>px <?php echo $styledata[65]; ?>px <?php echo $styledata[67]; ?>px <?php echo $styledata[59]; ?>;
        }
        .oxilab-flip-box-back-<?php echo $styleid; ?>-data{           
            position: absolute;
            left: 0%;
            right: 0;
            top: 50%;            
            padding: <?php echo $styledata[101]; ?>px <?php echo $styledata[103]; ?>px;
            -webkit-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            -moz-transform: translateY(-50%);
            -o-transform: translateY(-50%);
            transform: translateY(-50%);           
        }
        .oxilab-flip-box-back-<?php echo $styleid; ?>-data .oxilab-heading{
            display: block;
            color:  <?php echo $styledata[13]; ?>;
            text-align: <?php echo $styledata[189]; ?>;            
            font-size: <?php echo $styledata[181]; ?>px;          
            font-family: <?php echo oxilab_flip_box_font_familly_charecter($styledata[183]); ?>;
            font-weight: <?php echo $styledata[187]; ?>;
            font-style:<?php echo $styledata[185]; ?>;
            padding:<?php echo $styledata[191]; ?>px <?php echo $styledata[197]; ?>px <?php echo $styledata[193]; ?>px <?php echo $styledata[195]; ?>px;
        }
        .oxilab-flip-box-back-<?php echo $styleid; ?>-data .oxilab-info{
            display: block;
            color: <?php echo $styledata[7]; ?>;
            text-align: <?php echo $styledata[115]; ?>;            
            font-size: <?php echo $styledata[107]; ?>px;          
            font-family: <?php echo oxilab_flip_box_font_familly_charecter($styledata[109]); ?>;
            font-weight: <?php echo $styledata[113]; ?>;
            font-style:<?php echo $styledata[111]; ?>;
            padding:<?php echo $styledata[117]; ?>px <?php echo $styledata[123]; ?>px <?php echo $styledata[119]; ?>px <?php echo $styledata[121]; ?>px;           
        }
        .oxilab-flip-box-back-<?php echo $styleid; ?> .oxilab-button{
            display: block;
            position: absolute;
            right: 0;
            bottom: 0;
            padding: <?php echo $styledata[141]; ?>px <?php echo $styledata[147]; ?>px <?php echo $styledata[143]; ?>px <?php echo $styledata[145]; ?>px;  
        }
        .oxilab-flip-box-back-<?php echo $styleid; ?> .oxilab-button .oxi-icons{
            display: inline-block;     
            color: <?php echo $styledata[9]; ?>;
            font-size: <?php echo $styledata[125]; ?>px;
            padding: <?php echo $styledata[133]; ?>px <?php echo $styledata[135]; ?>px;  
        }
        .oxilab-flip-box-back-<?php echo $styleid; ?> .oxilab-button .oxi-icons:hover{
            color:  <?php echo $styledata[11]; ?>;
        }
        <?php echo $styledata[199]; ?>;
    </style>
    <?php
}
