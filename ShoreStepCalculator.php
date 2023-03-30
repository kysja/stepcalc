<?php 

class ShoreStepCalculator
{
    public $step_apart = 11.375;
    private $pf_inches = [48, 77, 96];

    public $rise;
    public $run;
    public $distance;
    public $srun;
    public $sdistance;
    public $steps;
    public $step_run;
    public $step_rise;
    public $slope;
    public $pfs = [];

    public function __construct($rise, $run, $pf_count)
    {
        $this->rise = ($rise['ft'] * 12) + $rise['in'];
        $this->run = ($run['ft'] * 12) + $run['in'];
        $this->pfs = $this->getPlatforms($pf_count);
        $this->srun = $this->run - $this->getPlatformsTotal();
        if ($this->srun < 0) {
            die('Error: The total of the platforms is greater than the run.');
        }
        $this->distance = $this->getDistance($this->rise, $this->run);
        $this->sdistance = $this->getDistance($this->rise, $this->srun);
        
        $this->steps = ceil($this->sdistance / $this->step_apart);
        $this->step_run = $this->srun / $this->steps;
        $this->step_rise = $this->rise / $this->steps;
        $this->slope = $this->getSlope($this->step_rise, $this->step_run);

    }

    public function getPlatforms($pf_count) : array
    {
        $pf = [];
        for ($i=0; $i < count($pf_count); $i++) { 
            $pf[$i] = array_fill(0, $pf_count[$i], $this->pf_inches[$i]);
        }
        $res = array_merge(...$pf);
        rsort($res);
        return $res;
    }

    public function getPlatformsTotal()
    {
        return array_sum($this->pfs);
    }

    public function getDistance($side1, $side2)
    {
        return sqrt(($side1 ** 2) + ($side2 ** 2));
    }

    public function getSlope($rise, $run)
    {
        return round(rad2deg(atan($rise/$run)),2);
    }

    public function showFeetInches($inches)
    {
        $inches = round($inches);
        $ft = floor($inches / 12);
        $in = $inches % 12;
        return $ft ."' " . $in . '"';
    }

    public function showPlatforms($pf)
    {
        if (count($pf) > 0 && array_sum($pf) > 0) {
            $parr = [];
            if ($pf[0] > 0) $parr[] = $pf[0] . " x 4ft";
            if ($pf[1] > 0) $parr[] = $pf[1] . " x 6ft 5in";
            if ($pf[2] > 0) $parr[] = $pf[2] . " x 8ft";
            return 'Yes, ' . implode(", ", $parr);
        } else {
            return "No";
        }
    }

    public function generateSvg()
    {

        $svg_view_x = 840;
        $svg_view_y = 420;
        $koef = ($this->rise/$this->run>($svg_view_y/$svg_view_x)) ? $svg_view_y/$this->rise : $svg_view_x/$this->run;
        
        $svg_run = round($this->run * $koef, 2);
        $svg_rise = round($this->rise * $koef, 2);
        $svg_triangle = implode(" ", ['0,0', '0,'. $svg_rise, $svg_run .','. $svg_rise ]);
        
        $svg_step_run = $this->step_run * $koef;
        $svg_step_rise = $this->step_rise * $koef;
        
        
        if ($this->pfs ?? false) {
            $svg_pfs = [];
            $pf_interval = floor($this->steps / count($this->pfs));
            for ($i=0; $i < count($this->pfs); $i++) { 
                $ind = $pf_interval*$i;
                $svg_pfs[$ind] = $this->pfs[$i] * $koef;
            }
        }  
    

        $html = '';
        $html .= '<div style="max-width:' . ($svg_run + 50) . 'px;height:' .((int) $svg_rise) . 'px;">'."\n";
        $html .= '<svg version="1.1" viewBox="0 0 ' . ($svg_run+50) . ' ' . $svg_rise . '" preserveAspectRatio="xMinYMin meet" xmlns="http://www.w3.org/2000/svg" xmlns:xlink= "http://www.w3.org/1999/xlink">'."\n";
        $html .= '<polygon points="' . $svg_triangle . '" fill="#cfedbb" />'."\n";
        $html .= '<text x="10" y="' . round(($svg_rise/2)*1.15) . '" style="font-weight:bold;">Rise: ' . $this->showFeetInches($this->rise) . '</text>'."\n";
        $html .= '<text x="' . (round($svg_run/2)-90) . '" y="' . ($svg_rise-5) . '" style="font-weight:bold;">Run: ' . $this->showFeetInches($this->run) . '</text>'."\n";

        $curX = 0;
        $curY = 0;

        for ($i=0; $i <$this->steps; $i++) {
            if (isset($svg_pfs[$i])) {
                $html .= '<line x1="'.$curX.'" y1="'.$curY.'" x2="'.($curX + $svg_pfs[$i]).'" y2="'.$curY.'" style="stroke:#009;stroke-width:1" />'."\n";
                $curX += $svg_pfs[$i];
            }
            $html .= '<line x1="'.$curX.'" y1="'.$curY.'" x2="'.($curX + $svg_step_run).'" y2="'.$curY.'" style="stroke:#000;stroke-width:1" />'."\n";
            $html .= '<line x1="'.($curX + $svg_step_run).'" y1="'.$curY.'" x2="'.($curX + $svg_step_run).'" y2="'.($curY + $svg_step_rise).'" style="stroke:#000;stroke-width:1" />'."\n";
            $curX += $svg_step_run;
            $curY += $svg_step_rise;
        }
                
        $html .= '</svg>'."\n";
        $html .= '</div>'."\n";
        

        return $html;
    }

    
    public function getResults($form)
    {
        return [ 
            'rise'          => [ 'Rise', $this->showFeetInches($this->rise) ],
            'run'           => [ 'Hill Run', $this->showFeetInches($this->run) ],
            'distance'      => [ 'Hill Distance', $this->showFeetInches($this->distance) ],
            'srun'          => [ 'Run', $this->showFeetInches($this->srun) ],
            'sdistance'     => [ 'Distance', $this->showFeetInches($this->sdistance) ],
            'slope'         => [ 'Slope', $this->slope ],
            'steps'         => [ '# of Steps', $this->steps ],
            'step_run'      => [ 'Step tread', round($this->step_run, 3) ],
            'step_rise'     => [ 'Step rise', round($this->step_rise, 3) ],
            'step_apart'    => [ 'Step apart', round($this->step_apart, 3) ],
            'platforms'     => [ 'Platforms', $this->showPlatforms($form->pf_count) ],
            'railings'      => [ 'Railings', $form->fields['railings']['value'] . ($form->fields['railings']['value'] == "Yes" ? ', '.$form->fields['railing_side']['value'] : '') ],
            'color'         => [ 'Step Color', $form->fields['step_color']['value'] ],
        ];
    }

}

    
