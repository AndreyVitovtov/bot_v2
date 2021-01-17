<?php

namespace App\models\buttons;

use App\models\BotUsers;
use App\models\SettingsButtons;

class ButtonsViber {
    private $btnBg;
    private $btnSize;
    private $fontColor;

    public function __construct() {
        $viewButtons = SettingsButtons::getView();
        $this->btnBg = $viewButtons->background;
        $this->fontColor = $viewButtons->color_text;
        $this->btnSize = $viewButtons->size_text;
    }

    private function button($columns, $rows, $actionBody, $text, $silent = "false") {
        return [
            'Columns' => $columns,
            'Rows' => $rows,
            'ActionType' => 'reply',
            'ActionBody' => $actionBody,
            'BgColor' => $this->btnBg,
            'Silent' => $silent,
            'Text' => '<font color="'.$this->fontColor.'" size="'.$this->btnSize.'">'.$text.'</font>',
            'TextSize' => 'large',
            'TextVAlign' => 'middle',
			'TextHAlign' => 'center',
        ];
    }

    private function button_url($columns, $rows, $url, $text, $silent = "true") {
        return [
            'Columns' => $columns,
            'Rows' => $rows,
            'ActionType' => 'open-url',
            'ActionBody' => $url,
            'OpenURLType' => 'internal',
            'BgColor' => $this->btnBg,
            'Silent' => $silent,
            'Text' => '<font color="'.$this->fontColor.'" size="'.$this->btnSize.'">'.$text.'</font>',
            'TextSize' => 'large'
        ];
    }

    private function button_img($columns, $rows, $actionType, $actionBody, $image, $text = "", $params = []) {
        if(isset($params['text-color']) && isset($params['text-size'])) {
            $text = '<font color="'.$params['text-color'].'" size="'.$params['text-size'].'">'.$text.'</font>';
        }
        return [
            'Columns' => $columns,
            'Rows' => $rows,
            'ActionType' => $actionType,
            'ActionBody' => $actionBody,
            'Image' => $image,
            'Text' => $text,
            'TextVAlign' => isset($params['TextVAlign']) ? $params['TextVAlign'] : 'middle',
			'TextHAlign' => isset($params['TextHAlign']) ? $params['TextHAlign'] : 'center',
            'TextSize' => 'large'
        ];
    }

    private function button_location($columns, $rows, $text, $silent = false) {
        return [
            'Columns' => $columns,
            'Rows' => $rows,
            'ActionType' => 'location-picker',
            'ActionBody' => 'jhg',
            'BgColor' => $this->btnBg,
            'Silent' => $silent,
            'Text' => '<font color="'.$this->fontColor.'" size="'.$this->btnSize.'">'.$text.'</font>',
            'TextSize' => 'large',
            'TextVAlign' => 'middle',
            'TextHAlign' => 'center',
        ];
    }

    private function button_phone($columns, $rows, $text, $silent = false) {
        return [
            'Columns' => $columns,
            'Rows' => $rows,
            'ActionType' => 'share-phone',
            'ActionBody' => 'jhg',
            'BgColor' => $this->btnBg,
            'Silent' => $silent,
            'Text' => '<font color="'.$this->fontColor.'" size="'.$this->btnSize.'">'.$text.'</font>',
            'TextSize' => 'large',
            'TextVAlign' => 'middle',
            'TextHAlign' => 'center',
        ];
    }


    public function start() {
        return [
            $this->button(6, 1, 'start', '{start}')
        ];
    }

    public function main_menu($userId) {
//        $user = BotUsers::find($userId);

//        if($user->access == '1') {
           return [
               $this->button(3, 1, 'search_ads', '{search_ads}'),
               $this->button(3, 1, 'create_ad', '{create_ad}'),
               $this->button(3, 1, 'my_ads', '{my_ads}'),
               $this->button(3, 1, 'contacts', '{contacts}'),
               $this->button(6, 1, 'edit_country', '{edit_country}')
           ];
    }

    public function back() {
        return [
            $this->button(6, 1, 'back', '{back}')
        ];
    }

    public function contacts() {
        return [
            $this->button(6, 1, 'general', '{contacts_general}'),
            $this->button(6, 1, 'access', '{contacts_access}'),
            $this->button(6, 1, 'advertising', '{contacts_advertising}'),
            $this->button(6, 1, 'offers', '{contacts_offers}'),
        ];
    }

    private function pagesButtons($res, $method, $name = 'name', $page = '1') {
        $res = array_slice($res, (($page - 1) * 42), 42);
        $buttons = [];
        foreach($res as $r) {
            $buttons[] = $this->button(6, 1, $method.'__'.$r->id, $r->$name);
        }

        return $buttons;
    }

    public function getPhone() {
        return [
            $this->button_phone(6, 1, '{send_phone}'),
            $this->button(6, 1, 'back', '{back}')
        ];
    }

    public function getLocation() {
        return [
            $this->button_location(6, 1, '{send_location}'),
            $this->button(6, 1, 'back', '{back}')
        ];
    }
}
