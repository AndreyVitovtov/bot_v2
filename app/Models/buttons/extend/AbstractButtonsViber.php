<?php

namespace App\Models\buttons\extend;

use App\Models\SettingsButtons;

abstract class AbstractButtonsViber {
    private $btnBg;
    private $btnSize;
    private $fontColor;

    public function __construct() {
        $viewButtons = SettingsButtons::getView();
        $this->btnBg = $viewButtons->background;
        $this->fontColor = $viewButtons->color_text;
        $this->btnSize = $viewButtons->size_text;
    }

    public function button($columns, $rows, $actionBody, $text, $silent = "false"): array
    {
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

    public function buttonUrl($columns, $rows, $url, $text, $silent = "true"): array
    {
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

    public function buttonImg($columns, $rows, $actionType, $actionBody, $image, $text = "", $params = []): array
    {
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

    public function buttonLocation($columns, $rows, $text, $silent = false): array
    {
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

    public function buttonPhone($columns, $rows, $text, $silent = false): array
    {
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

    public function start(): array
    {
        return [
            $this->button(6, 1, 'start', '{start}')
        ];
    }

    public function back(): array
    {
        return [
            $this->button(6, 1, 'back', '{back}')
        ];
    }

    protected function getPhone(): array
    {
        return [
            $this->buttonPhone(6, 1, '{send_phone}'),
            $this->button(6, 1, 'back', '{back}')
        ];
    }

    protected function getLocation(): array
    {
        return [
            $this->buttonLocation(6, 1, '{send_location}'),
            $this->button(6, 1, 'back', '{back}')
        ];
    }
}
