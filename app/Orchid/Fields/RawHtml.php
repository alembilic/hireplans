<?php
namespace App\Orchid\Fields;

use Orchid\Screen\Field;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Throwable;

class RawHtml extends Field
{
    /**
     * Blade template
     *
     * @var string
     */
    // protected $view = 'xxxxxxx';

    /**
     * Default attributes value.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Attributes available for a particular tag.
     *
     * @var array
     */
    protected $inlineAttributes = [
        // 'name', 'type', 'placeholder', 'value', 'html'
    ];

    // public function html($html): self
    // {
    //     $this->set('html', $html);
    //     return $this;
    // }
     /**
     * Set the HTML content.
     *
     * @param string|callable $html
     * @return self
     */
    public function html($html): self
    {
        if (is_callable($html)) {
            $html = $html();
        }
        $this->set('html', $html);
        return $this;
    }

    /**
     * @throws Throwable
     *
     * @return Factory|View|mixed
     */
    public function render()
    {
        return $this->get('html');
    }
}
