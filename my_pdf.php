<?php
namespace misel228\brickplumber;

use Fpdf\Fpdf;

class MyPdf extends Fpdf
{
    private $show_heading = true;
    public function __construct(
        $colors,
        $with_headings = true,
        $orientation = 'P',
        $unit = 'mm',
        $size = 'A4'
    ) {
        $this->colors = $this->init_colors($colors);
        $this->show_heading = $with_headings;
        parent::__construct($orientation, $unit, $size);
    }

    private function init_colors($colors)
    {
        $color_settings = [];
        foreach ($colors as $name => $hex_code) {
            $short_name = strtoupper(substr($name, 0, 1));
            $color_settings[$short_name] = $this->hex2rgb($hex_code);
        }
        return $color_settings;
    }

    private function hex2rgb($hex_color)
    {
        $color_values_only  = substr($hex_color, 1, 6);
        $channel_values_hex = str_split($color_values_only, 2);
        $channel_values = array_map('hexdec', $channel_values_hex);
        return $channel_values;
    }

    const CODE_WIDTH = 11;
    const CODE_HEIGHT = 9;
    const SQUARE_WIDTH = 14;
    const SQUARE_HEIGHT = 14;

    const DISTANCE = 15;

    const CODES_PER_LINE = 5;

    const CODE_TOP  = 30;
    const CODE_LEFT = 10;

    public function draw_codes($codes)
    {
        $num_codes = count($codes);
        //$rows = (int)ceil($num_codes / static::CODES_PER_LINE);

        $counter = 0;
        $repetitions = 9;
        if ($this->show_heading) {
            $repetitions = 1;
        }
        for ($i = 0; $i < $repetitions; $i+=1) {
            foreach ($codes as $name => $code) {
                $cpl = static::CODES_PER_LINE + 3;
                $cpl = intval(190 / 14);

                if ($this->show_heading) {
                    $cpl =static::CODES_PER_LINE;
                }
                $row = (int)floor($counter / $cpl);
                $col = (int)floor($counter % $cpl);

                $this->draw_square($row, $col);
                $this->write_name($name, $row, $col);
                $this->draw_code($code, $row, $col);
                $counter += 1;
            }
        }
    }

    private function get_top_left($row, $col)
    {

        $offset_left = static::CODE_LEFT;
        $offset_top = static::CODE_LEFT;
        $distance = 0;
        if ($this->show_heading) {
            $offset_top  = static::CODE_TOP;
            $distance    = static::DISTANCE;
        }
        $top = $row * (static::SQUARE_HEIGHT + $distance) + $offset_top;
        $left = $col * (static::SQUARE_WIDTH + $distance) + $offset_left;

        return [$top, $left];
    }

    private function draw_square($row, $col)
    {
        list($top,$left) = $this->get_top_left($row, $col);

        $this->setLineWidth(0.2);
        $this->SetDrawColor(200, 200, 200);

        $this->Rect($left, $top, static::SQUARE_WIDTH, static::SQUARE_HEIGHT);
    }

    private function write_name($name, $row, $col)
    {
        list($top,$left) = $this->get_top_left($row, $col);
        $top += static::SQUARE_HEIGHT;
        $top -= 1;
        $left += 1;
        $this->SetFont('Arial', '', 5);
        $this->Text($left, $top, $name);
    }

    private function draw_code($code, $row, $col)
    {
        list($top,$left) = $this->get_top_left($row, $col);

        $top  += 1.5;
        $left += 1.2;

        $code = str_repeat($code, 6);
        $code = str_split($code);
        foreach ($code as $color) {
            $this->draw_line($color, $top, $left);
            $left += 0.4;
        }
    }

    public function draw_line($color_id, $top, $left)
    {
        $this->setLineWidth(0.2);
        $this->SetDrawColor($this->colors[$color_id][0], $this->colors[$color_id][1], $this->colors[$color_id][2]);
        $this->Line($left, $top, $left, ($top + static::CODE_HEIGHT));
    }
}
