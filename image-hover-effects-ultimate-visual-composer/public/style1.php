<?php
if (!defined('ABSPATH'))
    exit;

function oxilab_flip_box_shortcode_function_style1($styleid, $userdata, $styledata, $listdata) {
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
                    if ($filesdata[9] == '' && $filesdata[11] != '') {
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
                                            <div class="oxilab-flip-box-<?php echo $styleid; ?>-data">
                                                <div class="oxilab-flip-box-<?php echo $styleid; ?>-image">
                                                    <img src="<?php echo $filesdata[5]; ?>">
                                                    <div class="oxilab-flip-box-<?php echo $styleid; ?>-image-icon">
                                                        <?php echo  oxilab_flip_box_font_icon($filesdata[3]) ?>
                                                    </div>
                                                </div>
                                                <div class="oxilab-flip-box-<?php echo $styleid; ?>-heading">
                                                    <div class="oxilab-flip-box-<?php echo $styleid; ?>-heading-data">
                                                        <?php echo oxilab_flip_box_special_charecter($filesdata[1]); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="oxilab-flip-box-back">
                                        <div class="oxilab-flip-box-back-<?php echo $styleid; ?>">
                                            <div class="oxilab-flip-box-back-<?php echo $styleid; ?>-data">
                                                <div class="oxilab-flip-box-back-<?php echo $styleid; ?>-file">
                                                    <div class="oxilab-info">
                                                        <?php echo oxilab_flip_box_special_charecter($filesdata[7]); ?>
                                                    </div>
                                                    <?php
                                                    if ($filesdata[9] != '') {
                                                        echo '<a href="' . $filesdata[11] . '" target="' . $styledata[53] . '">';
                                                        echo '<div class="oxilab-button">
                                                                    <div class="oxilab-button-data">
                                                                    ' . oxilab_flip_box_special_charecter($filesdata[9]) . '
                                                                    </div>
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
                        </div>
                    </div>
                    <?php echo $fileslinkend; ?>
                </div>
                <style>
        <?php
        if ($filesdata[13] == '') {
            echo '.oxilab-flip-box-body-' . $styleid . '-' . $value['id'] . ' .oxilab-flip-box-back-' . $styleid . '-data{
background-color: ' . $styledata[15] . ';}';
        } else {
            echo '.oxilab-flip-box-body-' . $styleid . '-' . $value['id'] . ' .oxilab-flip-box-back-' . $styleid . '-data{
background: linear-gradient(' . $styledata[15] . ', ' . $styledata[15] . '), url("' . $filesdata[13] . '");
-moz-background-size: 100% 100%;
-o-background-size: 100% 100%;
background-size: 100% 100%;
}';
        }
        ?> </style>

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
            width: 100%;
            height: 100%; 
            display: block;
            border: 1px solid <?php echo $styledata[5]; ?>;
            background-color: <?php echo $styledata[7]; ?>;
            -webkit-box-shadow: <?php echo $styledata[61]; ?>px <?php echo $styledata[63]; ?>px <?php echo $styledata[65]; ?>px <?php echo $styledata[67]; ?>px <?php echo $styledata[59]; ?>;
            -moz-box-shadow: <?php echo $styledata[61]; ?>px <?php echo $styledata[63]; ?>px <?php echo $styledata[65]; ?>px <?php echo $styledata[67]; ?>px <?php echo $styledata[59]; ?>;
            -ms-box-shadow: <?php echo $styledata[61]; ?>px <?php echo $styledata[63]; ?>px <?php echo $styledata[65]; ?>px <?php echo $styledata[67]; ?>px <?php echo $styledata[59]; ?>;
            -o-box-shadow: <?php echo $styledata[61]; ?>px <?php echo $styledata[63]; ?>px <?php echo $styledata[65]; ?>px <?php echo $styledata[67]; ?>px <?php echo $styledata[59]; ?>;
            box-shadow: <?php echo $styledata[61]; ?>px <?php echo $styledata[63]; ?>px <?php echo $styledata[65]; ?>px <?php echo $styledata[67]; ?>px <?php echo $styledata[59]; ?>;
        }
        .oxilab-flip-box-<?php echo $styleid; ?>-data{
            position: absolute;
            top: 0;
            left: 0;            
            background: <?php echo $styledata[5]; ?>;  
            width: calc(100% - <?php echo $styledata[75] * 2; ?>px);
            height: calc(100% - <?php echo $styledata[75] * 2; ?>px);
            margin: <?php echo $styledata[75]; ?>px;
            padding: <?php echo $styledata[71]; ?>px <?php echo $styledata[73]; ?>px;
            display: block;
        }
        .oxilab-flip-box-<?php echo $styleid; ?>-image{
            max-width: 100%;
            width: 100%;
            float: left;            
            position: relative;   
        }
        .oxilab-flip-box-<?php echo $styleid; ?>-image:after {
            padding-bottom: <?php echo $styledata[69]; ?>%;
            content: "";
            display: block;
        }
        .oxilab-flip-box-<?php echo $styleid; ?>-image img{
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: block;
        }
        .oxilab-flip-box-<?php echo $styleid; ?>-image-icon{
            position: absolute;
            left: 50%;
            background: <?php echo $styledata[11]; ?>;   
            border: 1px solid <?php echo $styledata[5]; ?>; 
            height: <?php echo $styledata[79]; ?>px;
            width: <?php echo $styledata[79]; ?>px;
            bottom: -<?php echo $styledata[79] / 2; ?>px; 
            -webkit-transform: translateX(-50%);
            -ms-transform: translateX(-50%);
            -moz-transform: translateX(-50%);
            -ms-transform: translateX(-50%);
            -o-transform: translateX(-50%);
            transform: translateX(-50%);       
            text-align: center;
            -webkit-border-radius:<?php echo $styledata[81]; ?>px;
            -moz-border-radius:<?php echo $styledata[81]; ?>px;
            -ms-border-radius:<?php echo $styledata[81]; ?>px;
            -o-border-radius:<?php echo $styledata[81]; ?>px;
            border-radius:<?php echo $styledata[81]; ?>px;
            -webkit-backface-visibility: hidden;
            -moz-backface-visibility: hidden;
            -ms-backface-visibility: hidden;
            -o-backface-visibility: hidden;
            backface-visibility: hidden;
        }
        .oxilab-flip-box-<?php echo $styleid; ?>-image-icon .oxi-icons{
            line-height: <?php echo $styledata[79]; ?>px;            
            color: <?php echo $styledata[9]; ?>;    
            font-size: <?php echo $styledata[77]; ?>px;
        }
        .oxilab-flip-box-<?php echo $styleid; ?>-heading{
            width: 100%;
            float: left;       
        }
        .oxilab-flip-box-<?php echo $styleid; ?>-heading-data{
            margin-top: <?php echo $styledata[79] / 2; ?>px;
            color: <?php echo $styledata[13]; ?>;
            width: 100%;
            float: left;
            text-align: <?php echo $styledata[91]; ?>;            
            font-size: <?php echo $styledata[83]; ?>px;
            font-family: <?php echo oxilab_flip_box_font_familly_charecter($styledata[85]); ?>;
            font-weight: <?php echo $styledata[89]; ?>;
            font-style:<?php echo $styledata[87]; ?>;
            padding: <?php echo $styledata[93]; ?>px <?php echo $styledata[99]; ?>px <?php echo $styledata[95]; ?>px <?php echo $styledata[97]; ?>px;            
        }
        .oxilab-flip-box-back-<?php echo $styleid; ?>{
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;       
            display: block;
            border: 1px solid;
            border-color: <?php echo $styledata[15]; ?>;
            background-color: <?php echo $styledata[17]; ?>;
            -webkit-box-shadow: <?php echo $styledata[61]; ?>px <?php echo $styledata[63]; ?>px <?php echo $styledata[65]; ?>px <?php echo $styledata[67]; ?>px <?php echo $styledata[59]; ?>;
            -moz-box-shadow: <?php echo $styledata[61]; ?>px <?php echo $styledata[63]; ?>px <?php echo $styledata[65]; ?>px <?php echo $styledata[67]; ?>px <?php echo $styledata[59]; ?>;
            -ms-box-shadow: <?php echo $styledata[61]; ?>px <?php echo $styledata[63]; ?>px <?php echo $styledata[65]; ?>px <?php echo $styledata[67]; ?>px <?php echo $styledata[59]; ?>;
            -o-box-shadow: <?php echo $styledata[61]; ?>px <?php echo $styledata[63]; ?>px <?php echo $styledata[65]; ?>px <?php echo $styledata[67]; ?>px <?php echo $styledata[59]; ?>;
            box-shadow: <?php echo $styledata[61]; ?>px <?php echo $styledata[63]; ?>px <?php echo $styledata[65]; ?>px <?php echo $styledata[67]; ?>px <?php echo $styledata[59]; ?>;

        }
        .oxilab-flip-box-back-<?php echo $styleid; ?>-data{
            position: absolute;
            top: 0;
            left: 0;
            width: calc(100% - <?php echo $styledata[105] * 2; ?>px);
            height: calc(100% - <?php echo $styledata[105] * 2; ?>px);
            margin: <?php echo $styledata[105]; ?>px;
            padding: <?php echo $styledata[101]; ?>px <?php echo $styledata[103]; ?>px;
            display: block;
        }
        .oxilab-flip-box-back-<?php echo $styleid; ?>-file{
            position: absolute;
            left: 0%;
            top: 50%;
            -webkit-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            -moz-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            -o-transform: translateY(-50%);
            transform: translateY(-50%);
            width: 100%;
        }
        .oxilab-flip-box-back-<?php echo $styleid; ?>-data .oxilab-info{
            width: 100%;
            float: left;
            color: <?php echo $styledata[19]; ?>;
            text-align: <?php echo $styledata[115]; ?>;            
            font-size: <?php echo $styledata[107]; ?>px;          
            font-family: <?php echo oxilab_flip_box_font_familly_charecter($styledata[109]); ?>;
            font-weight: <?php echo $styledata[113]; ?>;
            font-style:<?php echo $styledata[111]; ?>;
            padding: <?php echo $styledata[117]; ?>px <?php echo $styledata[123]; ?>px <?php echo $styledata[119]; ?>px <?php echo $styledata[121]; ?>px;            
        }
        .oxilab-flip-box-back-<?php echo $styleid; ?>-data .oxilab-button{
            width: 100%;
            float: left;
            text-align: <?php echo $styledata[139]; ?>; 
            padding: <?php echo $styledata[141]; ?>px <?php echo $styledata[147]; ?>px <?php echo $styledata[143]; ?>px <?php echo $styledata[145]; ?>px;  
        }
        .oxilab-flip-box-back-<?php echo $styleid; ?>-data .oxilab-button-data{
            display: inline-block;     
            color: <?php echo $styledata[21]; ?>;
            background-color:  <?php echo $styledata[23]; ?>;
            font-size: <?php echo $styledata[125]; ?>px;
            font-family: <?php echo oxilab_flip_box_font_familly_charecter($styledata[127]); ?>;
            font-weight: <?php echo $styledata[131]; ?>;
            font-style:<?php echo $styledata[129]; ?>;
            padding: <?php echo $styledata[133]; ?>px <?php echo $styledata[135]; ?>px; 
            -webkit-border-radius:<?php echo $styledata[137]; ?>px;
            -moz-border-radius:<?php echo $styledata[137]; ?>px;
            -ms-border-radius:<?php echo $styledata[137]; ?>px;
            -o-border-radius:<?php echo $styledata[137]; ?>px;
            border-radius: <?php echo $styledata[137]; ?>px;
        }
        .oxilab-flip-box-back-<?php echo $styleid; ?>-data .oxilab-button-data:hover{
            background-color: <?php echo $styledata[27]; ?>;
            color:  <?php echo $styledata[25]; ?>;
        }
        <?php echo $styledata[149]; ?>
    </style>
    <?php
}
