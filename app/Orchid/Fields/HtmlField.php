<?php
namespace App\Orchid\Fields;

use Orchid\Screen\Field;

class HtmlField extends Field
{
    /**
     * Blade template
     *
     * @var string
     */
    protected $view = 'orchid.html-field';

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
     * Set the label for the field.
     *
     * @param string $label
     * @return self
     */
    public function label($label): self
    {
        $this->set('label', $label);
        return $this;
    }
}
