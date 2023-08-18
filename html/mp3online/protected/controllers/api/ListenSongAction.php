<?php

class ListenSongAction extends CAction
{
    public function run()
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

        if ($id === '' || $id === null)
        {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'SUCCESS',
                'data' => array(),
                'message' => 'OK',
            )));
        }
        else
        {
            $songs = Song::model()->findByPk($id);

            if ($songs === null) {
                ApiController::sendResponse(404, CJSON::encode(array(
                    'status' => 'ERROR',
                    'message' => 'Song not found',
                )));
                return;
            }

            $songs->listen = $songs->listen + 1;
            $songs->save(false);

            $path = Yii::app()->getBaseUrl(true);
            $song = $path . '/upload/' . $songs->link;

            if (strlen($songs->image) > 0)
            {
                $image = $path . '/images/song/' . $songs->image;
            }
            else
            {
                $image = $path . '/images/www/ic_music_node.png';
            }

$tran = Translattions::model()->find("model_id = :modelId AND table_name = 'song' AND attribute = 'song_name'", array(':modelId' => $id));
if ($tran !== null) {
    $name_vi = $tran->value;
} else {
    $name_vi = '';
}

$trans = Translattions::model()->find("model_id = :modelId AND table_name = 'song' AND attribute = 'lyrics'", array(':modelId' => $id));
if ($trans !== null) {
    $lyrics_vi = $trans->value;
} else {
    $lyrics_vi = '';
}

$sing = Singer::model()->findByPk($songs->singer_id);
if ($sing !== null) {
    $singName = $sing->singer_name;
} else {
    $singName = '';
}


            $data= array(
                'id' => $songs->song_id,
                'name' => isset($songs->song_name) ? $songs->song_name : '',
                'name_vi' => $name_vi,
                'lyrics' => $songs->lyrics,
                'lyrics_vi' => $lyrics_vi,
                'link' => $song,
                'singerName' => $singName,
                'listen' => $songs->listen,
                'download' => $songs->download,
                'album_id' => isset($songs->album_id) ? $songs->album_id : '',
                'singer_id' => isset($songs->singer_id) ? $songs->singer_id : '',
                'author_id' => isset($songs->author_id) ? $songs->author_id : '',
                'category_id' => isset($songs->category_id) ? $songs->category_id : '',
                'link_app' => ($songs->link_app) ? $songs->link_app : '',
                'image' => $image,
                'description' => isset($songs->description) ? $songs->description : '',
            );

            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'SUCCESS',
                'data' => $data,
                'message' => 'OK',
            )));
        }
    }
}
