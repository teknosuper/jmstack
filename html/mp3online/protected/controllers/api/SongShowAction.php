<?php

class SongShowAction extends CAction
{
    public function run()
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';

        $data= array();
        if($id == 0 || strlen($id) == 0)
        {
            $rows_per_page = Constants::ITEM_PER_PAGE;
            $songs=Song::model()->findAll();
            $numsong= count($songs);
            $allpage=ceil($numsong/$rows_per_page);
            $start_index= ($page-1)*$rows_per_page;
            $criteria = new CDbCriteria();
            if($type == 'listen'){
                $criteria->order= 'listen DESC';
            }
            elseif($type == 'download'){
                $criteria->order= 'download DESC';
            }
            else{
                //$criteria->order= 'order_number ASC,song_id DESC';
                $criteria->order= 'song_id DESC';
            }
            $criteria->compare('status',1);
            $criteria->limit= $rows_per_page;
            $criteria->offset= $start_index;
            $song= Song::model()->findAll($criteria);

            foreach($song as $item)
            {
                $path= Yii::app()->getBaseUrl(true);
                if(strlen($item->link)>0)
                {
                    if(substr_count($item->link,'http') >0 )
                    {
                        $url_music= $item->link;
                    }
                    else
                    {
                        $url_music= $path.'/upload/'.$item->link;
                    }
                }
                else
                {
                    $url_music = '';
                }

                if(strlen($item->image)>0)
                {
                    $image= $path.'/images/song/'.$item->image;
                }
                else
                {
                    $image=$path.'/images/www/ic_music_node.png';
                }


                if(strlen($item->song_name)>0)
                    $songname =$item->song_name ;
                else
                    $songname = '';
                if(strlen($item->lyrics)>0)
                    $lyrics =$item->lyrics ;
                else
                    $lyrics = '';
                if(strlen($item->album_id)>0)
                    $album =$item->album_id ;
                else
                    $album = '';
                if(strlen($item->author_id)>0)
                    $author =$item->author_id ;
                else
                    $author = '';
                if(strlen($item->category_id)>0)
                    $cate =$item->category_id ;
                else
                    $cate = '';
                if(strlen($item->link_app)>0)
                    $linkapp =$item->link_app ;
                else
                    $linkapp = '';

                $tran = Translattions::model()->find("model_id = :song_id AND table_name = 'song' AND attribute = 'song_name'", array(':song_id' => $item->song_id));
                if ($tran !== null) {
                    $name_telugu = $tran->value;
                } else {
                    $name_telugu = '';
                }

                $trans = Translattions::model()->find("model_id = :song_id AND table_name = 'song' AND attribute = 'lyrics'", array(':song_id' => $item->song_id));
                if ($trans !== null) {
                    $lyrics_telugu = $trans->value;
                } else {
                    $lyrics_telugu = '';
                }

                $sing = Singer::model()->findByPk($item->singer_id);
                if ($sing !== null) {
                    $name = $sing->singer_name;
                } else {
                    $name = '';
                }


                $data[]=array(
                    'id'=>$item->song_id,
                    'name'=>$songname,
                    'name_telugu'=>$name_telugu,
                    'lyrics'=>$lyrics,
                    'lyrics_telugu'=>$lyrics_telugu,
                    'link'=>$url_music,
                    'singerName'=>$name,
                    'listen'=>$item->listen,
                    'download'=>$item->download,
                    'album_id'=>$album,
                    'author_id'=>$author,
                    'category_id'=>$cate,
                    'link_app'=>$linkapp,
                    'order_number'=> isset($item->order_number) ? $item->order_number : 0,
                    'image'=>$image,
					'description'=>isset($item->description) ? $item->description : '',
                );
            }

            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'SUCCESS',
                'allpage'=> $allpage,
                'data' => $data,
                'message' => 'OK',)));
        }
        else
        {
            $item = Song::model()->findByPk($id);
            if (isset($item)) {
                $path = Yii::app()->getBaseUrl(true);

                // Process the 'link' attribute
                if (strlen($item->link) > 0) {
                    if (substr_count($item->link, 'http') > 0) {
                        $url_music = $item->link;
                    } else {
                        $url_music = $path . '/upload/' . $item->link;
                    }
                } else {
                    $url_music = '';
                }

                // Process the 'image' attribute
                if (strlen($item->image) > 0) {
                    $image = $path . '/images/song/' . $item->image;
                } else {
                    $image = $path . '/images/www/no_image.jpg';
                }

                // Process other attributes
                $songname = strlen($item->song_name) > 0 ? $item->song_name : '';
                $lyrics = strlen($item->lyrics) > 0 ? $item->lyrics : '';
                $album = strlen($item->album_id) > 0 ? $item->album_id : '';
                $author = strlen($item->author_id) > 0 ? $item->author_id : '';
                $cate = strlen($item->category_id) > 0 ? $item->category_id : '';
                $linkapp = strlen($item->link_app) > 0 ? $item->link_app : '';

                $sing = Singer::model()->findByPk($item->singer_id);
                $name = isset($sing) ? $sing->singer_name : '';

                $tran = Translattions::model()->find("model_id = " . $item->song_id . " and table_name = 'song' and attribute = 'song_name'");
                $name_vi = isset($tran) ? $tran->value : '';

                $trans = Translattions::model()->find("model_id = " . $item->song_id . " and table_name = 'song' and attribute = 'lyrics'");
                $lyrics_vi = isset($trans) ? $trans->value : '';

                // Construct the data array
                $data = array(
                    'id' => $item->song_id,
                    'name' => $songname,
                    'name_vi' => $name_vi,
                    'lyrics' => $lyrics,
                    'lyrics_vi' => $lyrics_vi,
                    'link' => $url_music,
                    'singerName' => $name,
                    'listen' => $item->listen,
                    'download' => $item->download,
                    'album_id' => $album,
                    'author_id' => $author,
                    'category_id' => $cate,
                    'link_app' => $linkapp,
                    'image' => $image,
                    'description' => isset($item->description) ? $item->description : '',
                );

                // Send a JSON response
                ApiController::sendResponse(200, CJSON::encode(array(
                    'status' => 'SUCCESS',
                    'data' => $data,
                    'message' => 'OK',
                )));
            } else {
                // Send an empty JSON response
                ApiController::sendResponse(200, CJSON::encode(array(
                    'status' => 'SUCCESS',
                    'data' => array(),
                    'message' => 'OK',
                )));
            }

        }
    }
}