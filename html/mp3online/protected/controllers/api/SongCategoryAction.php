<?php

class SongCategoryAction extends CAction
{
    public function run()
    {
        $categoryId = isset($_REQUEST['categoryId']) ? $_REQUEST['categoryId'] : '';
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';

        $song = Song::model()->findAll(array(
            'condition'=>'category_id = :categoryId and status = 1',
            'params'=>array(':categoryId'=>$categoryId)
        ));
        $rows_per_page = Constants::ITEM_PER_PAGE;
        $numsong= count($song);
        $allpage=ceil($numsong/$rows_per_page);
        $start_index= ($page-1)*$rows_per_page;
        $criteria = new CDbCriteria();
        $criteria->condition='category_id ='.$categoryId;
        $criteria->compare('status',1);
        $criteria->order= 'order_number ASC,song_id DESC';
        $criteria->limit= $rows_per_page;
        $criteria->offset= $start_index;
        $data= array();
        $songs= Song::model()->findAll($criteria);
        // var_dump($song);exit;
        if(count($songs)>0)
        {
            foreach($songs as $item)
            {
                $path= Yii::app()->getBaseUrl(true);
                if(strlen($item->link)>0)
                {
                    if(substr_count($item->link,'http') > 0 )
                    {
                        //echo 123;
                        $url_music= $item->link;
                    }
                    else
                    {
                        //echo 456;exit;
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
                
                $sing = Singer::model()->findByPk($item->singer_id);
                if ($sing !== null) { // Check if $sing is not null before counting
                    $singer_Name = $sing->singer_name;
                } else {
                    $singer_Name = '';
                }

                $tran = Translattions::model()->find("model_id =" . $item->song_id, "and table_name = 'song' and attribute = 'song_name'");
                if ($tran !== null) { // Check if $tran is not null before counting
                    $name_vi = $tran->value;
                } else {
                    $name_vi = '';
                }

                $trans = Translattions::model()->find("model_id =" . $item->song_id, "and table_name = 'song' and attribute = 'lyrics'");
                if ($trans !== null) { // Check if $trans is not null before counting
                    $lyrics_vi = $trans->value;
                } else {
                    $lyrics_vi = '';
                }


                $data[]=array(
                    'id'=>$item->song_id,
                    'number'=>isset($item->stt) ? $item->stt : '',
                    'name'=>$item->song_name,
                    'name_vi'=>$name_vi,
                    'lyric'=>isset($item->lyrics) ? $item->lyrics : '',
                    'lyrics_vi'=>$lyrics_vi,
                    'link'=>$url_music,
                    'singerName'=>$singer_Name,
                    'singerId'=>isset($item->singer_id) ? $item->singer_id : '',
                    'listen'=>isset($item->listen) ? $item->listen : '',
                    'albumId'=>isset($item->album_id) ? $item->album_id : '',
                    'authorId'=>isset($item->author_id) ? $item->author_id : '',
                    'categoryId'=>isset($item->category_id) ? $item->category_id : '',
                    'createDate'=>$item->create_date,
                    'download'=>isset($item->download) ? $item->download : '',
                    'hot'=>isset($item->hot) ? $item->hot : '',
                    'new'=>isset($item->new) ? $item->new : '',
                    'status'=>isset($item->status) ? $item->status : '',
                    'link_app'=>isset($item->link_app) ? $item->link_app : '',
                    'order_number'=>isset($item->order_number) ? $item->order_number : 0,
                    'image'=>$image,
					'description'=>isset($item->description) ? $item->description : '',
                );
            }
            if(count($data) != 0)
            {
                ApiController::sendResponse(200, CJSON::encode(array(
                    'status' => 'SUCCESS',
                    'allpage'=>$allpage,
                    'data' => $data,
                    'message' => 'OK',)));
            }
            else
            {
                ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'SUCCESS',
                    'allpage'=> $allpage,
                    'data' => array(),
                    'message' => 'OK',)));
            }
        }
        else
        {
            ApiController::sendResponse(200, CJSON::encode(array(
                'status' => 'SUCCESS',
                    'allpage'=> 0,
                    'data' => array(),
                    'message' => 'OK',)));
        }
    }
}