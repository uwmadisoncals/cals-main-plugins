<?php
/*
Plugin Name: WPDM - Box.com Explorer
Description: Box.com Explorer for WordPress Download Manager
Plugin URI: http://www.wpdownloadmanager.com/
Author: Shaon
Version: 1.1.0
Author URI: http://www.wpdownloadmanager.com/
*/

if (defined('WPDM_Version')) {

    if (!defined('WPDM_CLOUD_STORAGE'))
        define('WPDM_CLOUD_STORAGE', 1);


    class WPDMBoxCom
    {
        function __construct()
        {

            //add_action("wpdm_cloud_storage_settings", array($this, "Settings"));
            add_action('wpdm_attach_file_metabox', array($this, 'BrowseButton'));


        }


        function Settings()
        {
            global $current_user;
            if (isset($_POST['__wpdm_boxcom']) && count($_POST['__wpdm_boxcom']) > 0) {
                update_option('__wpdm_boxcom', $_POST['__wpdm_boxcom']);
                die('Settings Saves Successfully!');
            }
            $wpdm_box_com = maybe_unserialize(get_option('__wpdm_boxcom', array()));
            ?>
            <div class="panel panel-default">
                <div class="panel-heading"><b><?php _e('Box.com API Credentials', 'wpdmpro'); ?></b></div>

                <table class="table">



                    <tr>
                        <td>Client ID</td>
                        <td><input type="text" name="__wpdm_boxcom[client_id]" class="form-control"
                                   value="<?php echo isset($wpdm_box_com['client_id']) ? $wpdm_box_com['client_id'] : ''; ?>"/>
                        </td>
                    </tr>
                    <!--tr>
                        <td>Client Secret</td>
                        <td><input type="text" name="__wpdm_boxcom[client_secret]" class="form-control"
                                   value="<?php echo isset($wpdm_box_com['client_secret']) ? $wpdm_box_com['client_secret'] : ''; ?>"/>
                        </td>
                    </tr-->

                </table>
                <!--div class="panel-footer">
                    <b>Redirect URI:</b> &nbsp; <input onclick="this.select()" type="text" class="form-control" style="background: #fff;cursor: copy;display: inline;width: 400px" readonly="readonly" value="<?php echo admin_url('?page=wpdm-google-drive'); ?>" />
                </div-->
            </div>


            </div>

        <?php
        }



        function BrowseButton()
        {
            ?>
            <div class="w3eden">

                <a href="#" id="btn-box-com" style="margin-top: 10px" title="Box.com" onclick="return false;" class="btn btn-primary btn-block"><span class="left-icon"><i class="fa fa-cube"></i></span> Select From Box.com</a>
                <script type="text/javascript" src="https://app.box.com/js/static/select.js"></script>
                <!-- div id="box-select" data-link-type="direct" data-multiselect="false" data-client-id="q1ijh0n6o4ukcr4yf48n6ipc4ikvhk7m"></div -->
                <script>
                    var box;

                    function InsertBXLink(file, id, name) {
                        <?php if(version_compare(WPDM_Version, '4.0.0', '>')){  ?>
                        var html = jQuery('#wpdm-file-entry-box').html();
                        var ext = 'png'; //response.split('.');
                        //ext = ext[ext.length-1];
                        name = file.substring(0, 80)+"...";
                        var icon = "<?php echo WPDM_BASE_URL; ?>file-type-icons/48x48/" + ext + ".png";
                        html = html.replace(/##filepath##/g, file);
                        html = html.replace(/##filename##/g, name);
                        html = html.replace(/##fileindex##/g, id);
                        html = html.replace(/##preview##/g, icon);
                        jQuery('#currentfiles').prepend(html);

                        <?php } else { ?>
                        jQuery('#wpdmfile').val(file+"#"+name);
                        jQuery('#cfl').html('<div><strong>'+name+'</strong>').slideDown();
                        <?php } ?>
                    }

                    function popupwindow(url, title, w, h) {
                        var left = (screen.width/2)-(w/2);
                        var top = (screen.height/2)-(h/2);
                        return window.open(url, title, 'toolbar=0, location=0, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
                    }

                    jQuery(function () {
                        jQuery('#btn-box-com').click(function () {
                            box = popupwindow('https://app.box.com/index.php?rm=box_select_view&link_type=shared&multiselect=false',"Box.com",700, 500);
                            return false;
                        });

                        var options = {
                            clientId: 'q1ijh0n6o4ukcr4yf48n6ipc4ikvhk7m',
                            linkType: 'shared',
                            multiselect: false
                        };
                        var boxSelect = new BoxSelect(options);

                        // Register a success callback handler
                        boxSelect.success(function(response) {
                            InsertBXLink(response[0].url, response[0].id, response[0].name);
                            console.log(response);
                            box.close();
                        });
                        // Register a cancel callback handler
                        boxSelect.cancel(function() {
                            console.log("The user clicked cancel or closed the popup");
                        });

                    });


                </script>
            </div>

            <script type="text/html" id="wpdm-file-entry-box">
                <div class="cfile">
                    <input class="faz" type="hidden" value="##filepath##" name="file[files][]">
                    <div class="panel panel-default">
                        <div class="panel-heading"><button type="button" class="btn btn-xs btn-default pull-right" rel="del"><i class="fa fa-times text-danger"></i></button> <span title="##filepath##">##filename##</span></div>
                        <div class="panel-body">
                            <div class="media">
                                <div class="pull-left">

                                    <img class="file-ico" src="##preview##" />
                                </div>
                                <div class="media-body">
                                    <input placeholder="<?php _e('File Title','wpdmpro'); ?>" title="<?php _e('File Title','wpdmpro'); ?>" class="form-control" type="text" name='file[fileinfo][##filepath##][title]' value="" /><br/>
                                    <div class="input-group">
                                        <input placeholder="<?php _e('File Password','wpdmpro'); ?>"  title="<?php _e('File Password','wpdmpro'); ?>" class="form-control inline" type="text" id="indpass_##fileindex##" name='file[fileinfo][##filepath##][password]' value="">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" class="genpass" title='Generate Password' onclick="return generatepass('indpass_##fileindex##')"><i class="fa fa-ellipsis-h"></i></button>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </script>



        <?php
        }




    }

    new WPDMBoxCom();

}
 

