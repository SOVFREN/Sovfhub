<?php
$output = array();
$output['title'] = 'Unknown';
$audio_content_id = 0;


if (isset($data['audio_content_id'])) {

    $audio_content_id = filter_var($data["audio_content_id"], FILTER_SANITIZE_NUMBER_INT);

    if (!empty($audio_content_id)) {
        $columns = [
            'audio_player.audio_content_id', 'audio_player.audio_title',
            'audio_player.audio_description', 'audio_player.audio_type',
            'audio_player.radio_stream_url', 'audio_player.streaming_server',
            'audio_player.now_playing_info_url',
        ];

        $where["audio_player.disabled[!]"] = 1;


        $where["audio_player.audio_content_id"] = $audio_content_id;

        $audio_records = DB::connect()->select('audio_player', $columns, $where);

        if (isset($audio_records[0])) {
            $audio_record = $audio_records[0];
            $output['title'] = $audio_record['audio_description'];
            $streaming_servers = ['shoutcast', 'icecast', 'laut_fm'];

            if (in_array($audio_record['streaming_server'], $streaming_servers)) {
                if (!empty($audio_record['radio_stream_url'])) {

                    $radio_info = parse_url($audio_record['radio_stream_url']);
                    if (isset($radio_info['host'])) {
                        if (!empty($audio_record['now_playing_info_url'])) {
                            $radio_info_url = $audio_record['now_playing_info_url'];
                        } else {
                            $radio_info_url = $radio_info['scheme']."://".$radio_info['host'];

                            if (isset($radio_info['port'])) {
                                $radio_info_url = $radio_info_url.':'.$radio_info['port'];
                            }

                            if ($audio_record['streaming_server'] == 'icecast') {
                                $radio_info_url = $radio_info_url.'/status-json.xsl';
                            } else if ($audio_record['streaming_server'] == 'laut_fm') {
                                $parts = explode('/', trim($radio_info['path'], '/'));
                                $radio_info_url = null;
                                if (count($parts) > 0) {
                                    $stationId = end($parts);
                                    $radio_info_url = "https://api.laut.fm/station/".$stationId."/current_song";
                                }
                            } else {
                                $radio_info_url = $radio_info_url.'/7.html';
                            }
                        }

                        if (!empty($radio_info_url) && filter_var($radio_info_url, FILTER_VALIDATE_URL)) {
                            $content = @file_get_contents($radio_info_url);

                            if ($audio_record['streaming_server'] == 'icecast') {

                                $content = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $content), true);

                                if (!empty($content)) {

                                    if (isset($radio_info['path'])) {
                                        $radio_path = '/'.str_replace('/', '', $radio_info['path']);
                                    }

                                    if (isset($content['icestats']['source']['title'])) {
                                        $output['title'] = $content['icestats']['source']['title'];
                                    } else if (isset($content['icestats']['source'][0]['title'])) {
                                        $output['title'] = $content['icestats']['source'][0]['title'];

                                        if (isset($content['icestats']['source'][0]['artist'])) {
                                            $output['title'] .= ' - '. $content['icestats']['source'][0]['artist'];
                                        }
                                    } else if (isset($content[$radio_path])) {
                                        $output['title'] = $content[$radio_path]['title'];

                                        if (isset($content[$radio_path]['description']) && !empty($content[$radio_path]['description'])) {
                                            $output['title'] .= ' - '. $content[$radio_path]['description'];
                                        }
                                    } else if (isset($content['icestats']['streamtitle'])) {
                                        $content = explode(" - ", $content['icestats']['streamtitle']);

                                        if (isset($content[1])) {
                                            $output['title'] = $content[1];
                                            if (isset($content[0])) {
                                                $output['title'] .= ' - '. $content[0];
                                            }
                                        }
                                    }
                                }
                            } else if ($audio_record['streaming_server'] == 'laut_fm') {
                                if ($content !== false) {
                                    $content = json_decode($content, true);

                                    if (isset($content['title'])) {
                                        
                                        if (is_array($content['title'])) {
                                            $currentTrackTitle = implode(' - ', $content['title']);
                                        } else {
                                            $currentTrackTitle = $content['title'];
                                        }
                                        
                                        if (is_array($content['artist'])) {
                                            $currentArtist = implode(' - ', $content['artist']);
                                        } else {
                                            $currentArtist = $content['title'];
                                        }

                                        $output['title'] = $currentTrackTitle.' - '.$currentArtist;
                                    }
                                }
                            } else {
                                $content = strip_tags($content);
                                $content = explode(',', $content);
                                if (isset($content[6])) {
                                    $content = $content[6];
                                    if (mb_strlen($content) > 3) {
                                        $output['title'] = $content;
                                        $output['title'] = htmlspecialchars_decode($content);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
$output['title'] = strip_tags($output['title']);
?>