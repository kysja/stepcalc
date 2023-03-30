<?php 

class ShoreStepForm
{
    public $fields = [];
    public $run;
    public $rise;
    public $pf_count;

    public function __construct()
    {
        $this->fields['railings'] = [ 'label' => 'Railings', 'value' => '' ];
        $this->fields['step_color'] = [ 'label' => 'Step Color', 'value' => '' ];
        $this->fields['rise_ft'] = [ 'label' => 'Rise ft', 'value' => '' ];
        $this->fields['rise_in'] = [ 'label' => 'Rise in', 'value' => '' ];
        $this->fields['run_ft'] = [ 'label' => 'Run ft', 'value' => '' ];
        $this->fields['run_in'] = [ 'label' => 'Run in', 'value' => '' ];
        $this->fields['platforms'] = [ 'label' => 'Platforms', 'value' => '' ];
        $this->fields['platforms_4'] = [ 'label' => '4 ft', 'value' => 0 ];
        $this->fields['platforms_6'] = [ 'label' => '6 ft', 'value' => 0 ];
        $this->fields['platforms_8'] = [ 'label' => '8 ft', 'value' => 0 ];

    }

    public function showLabel($key, $class='')
    {
        return '<label class="'.$class.'" for="'.$key.'">' . $this->fields[$key]['label'] . '</label>';
    }

    public function showInput($key, $class='', $style='', $placeholder='')
    {
        return '<input class="'.$class.'" type="text" name="'.$key.'" id="'.$key.'" value="' . $this->fields[$key]['value'] . '" style="'.$style.'" placeholder="'.$placeholder.'" required>';
    }

    public function showTextarea($key, $class='')
    {
        return '<textarea class="' . $class .'" name="'.$key.'" id="'.$key.'" cols="30" rows="4" required>' . $this->fields[$key]['value'] . '</textarea>';
    }

    public function showRadio($key, $variants, $class='', $req=true)
    {
        $html = '';
        foreach ($variants as $v) {
            $checked = $this->fields[$key]['value'] === $v ? 'checked' : '';
            $required = $req ? 'required' : '';
            $html .= '<input class="'.$class.'" type="radio" name="'.$key.'" id="' . $key . '_' . str_replace(' ', '_', strtolower($v)) . '" value="'.$v.'" '.$checked.' '.$required.'> ';
            $html .= '<label class="me-4" for="'.$key.'">'.$v.'</label>';
        }
        return $html;
    }

    public function showInputPlatforms($key)
    {
        $html = '';
        $html .= '<div>';
        $html .= '<div class="k5_platf_wdth">'.$this->fields[$key]['label'].'</div>';
        $html .= '<span class="k5_addsub" onclick="plaformsNum(\''.$key.'\', \'sub\');"><img src="./resources/images/minus.png"></span>';
        $html .= '<input type="text" class="k5_inp_num" id="'.$key.'" name="'.$key.'" value="'.$this->fields[$key]['value'].'">';
        $html .= '<span class="k5_addsub" onclick="plaformsNum(\''.$key.'\', \'add\');"><img src="./resources/images/plus.png"></span>';
        $html .= '</div>';

        return $html;
    }

    public function process($post)
    {
        foreach ($this->fields as $key => $field) {
            $this->fields[$key]['value'] = (isset($post[$key])) ? trim(htmlspecialchars($post[$key])) : null;
        }
        
        $this->rise = [ 'ft' => (int) $this->fields['rise_ft']['value'], 'in' => (int) $this->fields['rise_in']['value'] ];
        $this->run  = [ 'ft' => (int) $this->fields['run_ft']['value'], 'in' => (int) $this->fields['run_in']['value'] ];
        $this->pf_count = [ (int) $this->fields['platforms_4']['value'], (int) $this->fields['platforms_6']['value'], (int) $this->fields['platforms_8']['value'] ];

    }

}
