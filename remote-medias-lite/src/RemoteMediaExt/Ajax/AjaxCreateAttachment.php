<?php
namespace WPRemoteMediaExt\RemoteMediaExt\Ajax;

use WPRemoteMediaExt\WPCore\WPajaxCall;
use WPRemoteMediaExt\RemoteMediaExt\Accounts\RemoteMediaFactory;

class AjaxCreateAttachment extends WPajaxCall
{
    public function __construct()
    {
        parent::__construct('create-remote-attachment', 'media-remote-ext', true, true);
        $this->jsvar = 'rmlCreateAttachParams';
        $this->nonceQueryVar = 'nonce';
    }

    public function callback($data)
    {
        $remotedata = wp_unslash($_POST['attachment']);
        $accountId = absint($_POST['accountId']);

        $html = array();

        if (empty($accountId) ||
            empty($remotedata)
        ) {
            wp_send_json_error();
        }
        
        $media = RemoteMediaFactory::createFromAccountid($accountId, $remotedata);

        if (!is_null($media)) {
            $html = $media->toMediaManagerAttachment();
        }

        wp_send_json_success($html);
    }
}
