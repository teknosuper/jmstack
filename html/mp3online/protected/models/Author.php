<?php

/**
 * This is the model class for table "author".
 *
 * The followings are the available columns in table 'author':
 * @property integer $author_id
 * @property string $author_name
 * @property string $author_img
 * @property string $profile
 * @property integer $status
 */
class Author extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
    public $name_vi;
    public $profile_vi;
	public function tableName()
	{
		return 'author';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('author_name, status', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('author_name', 'length', 'max'=>255),
			array('author_img , profile', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('author_id, author_name, status, author_img, profile', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'author_id' => 'ID',
			'author_name' => 'Name',
			'author_img' => 'Image',
			'profile' => 'Profile',
			'status' => 'Status',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('author_id',$this->author_id);
		$criteria->compare('author_name',$this->author_name,true);
		$criteria->compare('author_img',$this->author_img,true);
		$criteria->compare('profile',$this->profile,true);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Author the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    public static function itemAlias($type,$code=NULL) {
        $_items = array(
            'status' => array(
                '0' => 'Inactive',
                '1' => 'Active',
            ),
        );
        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
        else
            return isset($_items[$type]) ? $_items[$type] : false;
    }
}
