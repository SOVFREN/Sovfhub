<?php

include('fns/filters/profanity.php');
include('fns/url_highlight/load.php');

use VStelmakh\UrlHighlight\UrlHighlight;
use VStelmakh\UrlHighlight\Validator\Validator;
use Snipe\BanBuilder\CensorWords;

$result = array();
$result['success'] = false;
$result['error_message'] = Registry::load('strings')->went_wrong;
$result['error_key'] = 'something_went_wrong';

$current_user_id = Registry::load('current_user')->id;

$permission = [
    'edit_message' => false,
    'generate_link_preview' => false,
    'allow_sharing_links' => false,
    'allow_sharing_email_addresses' => false
];

if (isset($data['message_id'])) {

    $message_id = filter_var($data["message_id"], FILTER_SANITIZE_NUMBER_INT);
    $user_id = Registry::load('current_user')->id;
    $super_privileges = false;
    $customURLHighlighter = new CustomURLHighlighter();
    $convert_email_addresses = true;
    $url_validator = new Validator(true, [], [], $convert_email_addresses);
    $urlHighlight = new UrlHighlight($url_validator, $customURLHighlighter);

    if (role(['permissions' => ['private_conversations' => 'super_privileges']])) {
        $super_privileges = true;
    }

    if (!empty($message_id)) {

        $columns = $join = $where = null;
        $columns = [
            'private_chat_messages.private_chat_message_id', 'private_chat_messages.user_id', 'private_chat_messages.filtered_message',
            'private_chat_messages.created_on', 'site_users.display_name', 'private_conversations.recipient_user_id',
            'private_conversations.initiator_user_id', 'private_chat_messages.attachment_type',
        ];

        $join["[>]site_users"] = ["private_chat_messages.user_id" => "user_id"];
        $join["[>]private_conversations"] = ["private_chat_messages.private_conversation_id" => "private_conversation_id"];

        $where["private_chat_messages.private_chat_message_id"] = $message_id;

        $private_chat_message = DB::connect()->select('private_chat_messages', $join, $columns, $where);


        if (isset($private_chat_message[0])) {

            $private_chat_message = $private_chat_message[0];
            $edit_message_time_limit = role(['find' => 'edit_message_time_limit']);

            if ($super_privileges) {
                $permission['edit_message'] = true;
            } else if (role(['permissions' => ['private_conversations' => 'edit_own_message']])) {
                if ((int)$user_id === (int)$private_chat_message['user_id']) {
                    if (!empty($edit_message_time_limit)) {

                        $to_time = strtotime($private_chat_message['created_on']);
                        $from_time = strtotime("now");
                        $time_difference = round(abs($to_time - $from_time) / 60, 2);

                        if ($time_difference < $edit_message_time_limit) {
                            $permission['edit_message'] = true;
                        } else {
                            $result['error_message'] = Registry::load('strings')->time_limit_expired;
                            $result['error_key'] = 'time_limit_expired';
                        }
                    }
                }
            }

            $recipient_id = $private_chat_message['initiator_user_id'];

            if ((int)$user_id === (int)$recipient_id) {
                $recipient_id = $private_chat_message['recipient_user_id'];
            }

            if ($force_request || role(['permissions' => ['private_conversations' => 'allow_sharing_links']])) {
                $permission['allow_sharing_links'] = true;
            }

            if ($force_request || role(['permissions' => ['private_conversations' => 'allow_sharing_email_addresses']])) {
                $permission['allow_sharing_email_addresses'] = true;
            }

            if ($force_request || role(['permissions' => ['private_conversations' => 'generate_link_preview']])) {
                $permission['generate_link_preview'] = true;
            }

            if (isset($data['message']) && !empty($data['message']) && $permission['edit_message']) {

                if (!empty(Registry::load('settings')->maximum_message_length)) {
                    $total_characters = mb_strlen(strip_tags(trim($data['message'])));
                    if ($total_characters > Registry::load('settings')->maximum_message_length) {
                        $data['message'] = mb_substr($data['message'], 0, Registry::load('settings')->maximum_message_length, 'utf8');
                    }
                }

                if (!empty($data['message']) && !$permission['allow_sharing_email_addresses']) {
                    $email_pattern = "/[^@\s]*@[^@\s]*\.[^@\s]*/";
                    $data['message'] = preg_replace($email_pattern, '', $data['message']);
                    $data['message'] = trim($data['message']);
                }

                if (!empty($data['message']) && !$permission['allow_sharing_links']) {
                    $link_pattern = '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#';
                    $data['message'] = preg_replace($link_pattern, '', $data['message']);
                    $data['message'] = preg_replace('/<a href=\"(.*?)\">(.*?)<\/a>/', "\\2", $data['message']);
                    $data['message'] = str_replace("&nbsp;", '', $data['message']);
                    $data['message'] = trim($data['message']);
                } else {
                    $email_pattern = '"<a[^>]+>.+?</a>(*SKIP)(*FAIL)|(\S+@\S+\.\S+?)(?=[.,!?]?(\s|$))"';
                    $data['message'] = $urlHighlight->highlightUrls($data['message']);

                }

                if (!empty($data['message'])) {
                    $emoticons = [':)', ';)', ':(', ':D', ':P', 'XD', '&lt;3'];

                    $replacements = [
                        "<span class='emoji_icon emoji-slightly_smiling_face'>&nbsp;</span>",
                        "<span class='emoji_icon emoji-wink'>&nbsp;</span>",
                        "<span class='emoji_icon emoji-frowning'>&nbsp;</span>",
                        "<span class='emoji_icon emoji-smile'>&nbsp;</span>",
                        "<span class='emoji_icon emoji-stuck_out_tongue_winking_eye'>&nbsp;</span>",
                        "<span class='emoji_icon emoji-joy'>&nbsp;</span>",
                        "<span class='emoji_icon emoji-heart'>&nbsp;</span>",
                    ];

                    $find_emoticons_one = array_map(function($value) {
                        return '#(?<!\S)('.preg_quote($value, "#").')(?!\S)#iu';
                    }, $emoticons);

                    $find_emoticons_two = array_map(function($value) {
                        return '#(?<!\S)(\<span\>'.preg_quote($value, "#").'\</span\>)(?!\S)#iu';
                    }, $emoticons);

                    $replacements = array_merge($replacements, $replacements);
                    $find_emoticons = array_merge($find_emoticons_one, $find_emoticons_two);

                    $data['message'] = preg_replace($find_emoticons, $replacements, $data['message']);
                }

                if (!empty($data['message'])) {
                    $data['message'] = preg_replace('/<([^>\s]+)[^>]*>(?:\s*(?:<br>|<br\/|<br \/>)\s*)*<\/\1>/im', '', $data['message']);
                    $data['message'] = preg_replace('/(?:\s*<br[^>]*>\s*){3,}/s', "<br><br>", $data['message']);
                    $data['message'] = preg_replace('#(\s*<br\s*/?>)*\s*$#i', '', $data['message']);
                }

                if (!empty($data['message'])) {
                    $regex = '#<img.+?class="([^"]*)".*?/?>#i';
                    $replace = '<span class="$1">&nbsp;</span>';
                    $data['message'] = preg_replace($regex, $replace, $data['message']);
                    $data['message'] = rtrim($data['message'], PHP_EOL);
                }

                if (!empty($data['message'])) {

                    include('fns/HTMLPurifier/load.php');
                    $allowed_tags = 'p,span[class],b,em,i,u,strong,s,';
                    $allowed_tags .= 'a[href],ol,ul,li,br';

                    $config = HTMLPurifier_Config::createDefault();
                    $config->set('HTML.Allowed', $allowed_tags);
                    $config->set('Attr.AllowedClasses', array());
                    $config->set('HTML.Nofollow', true);
                    $config->set('HTML.TargetBlank', true);
                    $config->set('AutoFormat.RemoveEmpty', true);

                    $define = $config->getHTMLDefinition(true);
                    $define->addAttribute('span', 'class', new CustomClassDef(array('emoji_icon'), array('emoji-')));

                    $purifier = new HTMLPurifier($config);

                    $message = $purifier->purify(trim($data['message']));

                    if ($permission['generate_link_preview']) {
                        if ($private_chat_message['attachment_type'] === 'url_meta' || empty($private_chat_message['attachment_type'])) {

                            $links = $urlHighlight->getUrls($message);

                            if (isset($links[0])) {
                                include('fns/url_metadata/load.php');
                                $url_meta_data = url_metadata($links[0]);
                                if ($url_meta_data['success']) {
                                    unset($url_meta_data['success']);
                                    $attachments = ['url_meta' => $url_meta_data];
                                }
                            }
                        }
                    }

                    $message_criteria = true;
                    $message = preg_replace('/^\p{Z}+|\p{Z}+$/u', '', $message);

                    $check_message = html_entity_decode(strip_tags($message, '<span>'));
                    $check_message = trim(preg_replace("/\s+/", "", $check_message));
                    $check_message = trim($check_message, " \t\n\r\0\x0B\xC2\xA0");

                    if (empty($check_message)) {
                        $message_criteria = false;
                    }

                    $total_characters = mb_strlen(strip_tags($message));

                    if (empty(Registry::load('settings')->minimum_message_length)) {
                        Registry::load('settings')->minimum_message_length = 1;
                    }

                    if ((int)$total_characters < (int)Registry::load('settings')->minimum_message_length) {
                        $message_criteria = false;
                    }

                    if (!empty(trim($message))) {

                        if (isset(Registry::load('settings')->text_moderation) && Registry::load('settings')->text_moderation !== 'disable') {

                            include_once('fns/text_moderation/load.php');

                            $text_moderation = moderate_text_content($message);

                            if (!$text_moderation['success']) {
                                $alert_message = Registry::load('strings')->detected_forbidden_words_content;
                                $result = array();
                                $result['success'] = false;
                                $result['error_message'] = $alert_message;
                                $result['error_key'] = 'detected_forbidden_words_content';
                                return;
                            }
                        }

                        if (Registry::load('settings')->profanity_filter !== 'disable') {
                            $safe_mode = true;

                            if (Registry::load('settings')->profanity_filter === 'strict_mode') {
                                $safe_mode = false;
                            }

                            $censor = new CensorWords();
                            $message = $censor->censorString($message, $safe_mode);
                            $message = $message['clean'];
                        }
                    }

                    if (!empty(trim($message)) && $message_criteria) {

                        $update_data = [
                            "filtered_message" => $message,
                            "updated_on" => Registry::load('current_user')->time_stamp,
                        ];

                        if ($permission['generate_link_preview']) {
                            if ($private_chat_message['attachment_type'] === 'url_meta' || empty($private_chat_message['attachment_type'])) {
                                if (isset($attachments) && !empty($attachments) && isset($attachments['url_meta'])) {
                                    $attachments = json_encode($attachments['url_meta']);
                                    $update_data["attachments"] = $attachments;
                                    $update_data["attachment_type"] = 'url_meta';
                                } else {
                                    $update_data["attachments"] = '';
                                    $update_data["attachment_type"] = '';
                                }
                            }
                        }

                        DB::connect()->update("private_chat_messages", $update_data, ["private_chat_message_id" => $message_id]);
                    }
                }

                $result = array();
                $result['success'] = true;
                $result['todo'] = 'load_conversation';
                $result['identifier_type'] = 'user_id';
                $result['identifier'] = $recipient_id;
                $result['reload_aside'] = true;

                if (isset($data['monitoring_chat'])) {
                    $result['identifier'] = 'all';
                }
            }
        }

    }
}