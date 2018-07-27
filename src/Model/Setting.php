<?php

namespace App\Model;

use App\Entity\Setting as SettingEntity;

class Setting
{
    const SETTING_UPLOAD_PROVIDER = 'Upload provider';
    const SETTING_EMAIL_VERIFICATION_PROVIDER = 'Email verification provider';
    const SETTING_EMAIL_NOTIFICATION_PROVIDER = 'Email notification provider';
    /**
     * @var string
     */
    public $value;

    /**
     * @var array
     */
    public $options;

    /**
     * @param SettingEntity $setting
     * @return Setting
     */
    public function fromSetting(SettingEntity $setting): Setting
    {
        $settingModel = new Setting();
        $settingModel->value = $setting->getValue();
        $settingModel->options = $setting->getOptions();

        return $settingModel;
    }
}
