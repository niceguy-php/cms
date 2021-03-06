<?php
/**
 * Author: lf
 * Blog: https://blog.feehi.com
 * Email: job@feehi.com
 * Created at: 2016-04-07 11:37
 */

namespace backend\models;

use yii;
use yii\web\UploadedFile;
use yii\behaviors\TimestampBehavior;
use yii\helpers\FileHelper;

class FriendlyLink extends \common\models\FriendlyLink
{

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        $upload = UploadedFile::getInstance($this, 'image');
        if ($upload !== null) {
            $uploadPath = yii::getAlias('@friendlylink/');
            if (! FileHelper::createDirectory($uploadPath)) {
                $this->addError('thumb', "Create directory failed " . $uploadPath);
                return false;
            }
            $fullName = $uploadPath . uniqid() . '_' . $upload->baseName . '.' . $upload->extension;
            if (! $upload->saveAs($fullName)) {
                $this->addError('thumb', yii::t('app', 'Upload {attribute} error', ['attribute' => yii::t('app', 'Thumb')]) . ': ' . $fullName);
                return false;
            }
            $this->image = str_replace(yii::getAlias('@frontend/web'), '', $fullName);
        } else {
            $this->image = $this->getOldAttribute('image');
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}