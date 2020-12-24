<?php
/**
 * @link https://github.com/UomoCon/materialize
 * @license https://github.com/UomoCon/materialize/blob/master/LICENSE
 */

namespace uomocon\materialize\widgets;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * Carousel is a robust and versatile component that can be an image slider or an item carousel with arbitrary HTML content.
 *
 * Simply provide the [[items]] as an array of items.
 * For each item you must define the `image` key with the image's `src`. Additionally you can define and align a caption
 * for every slide individually. Caption content can be HTML and will <strong>not</strong> be encoded.
 *
 * ```php
 * 'itemOptions' => [
 *      'class' => 'amber white-text' // this class will be used for all carousel elements
 * ],
 * 'items' => [
 *      [
 *          'content' => Html::img('http://lorempixel.com/800/800/sports/2'),
 *      ],
 *      [
 *          'content' => '<h2>Carousel item heading</h2><p>Arbitrary content</p>'
 *          'options' => ['class' => 'carusel-item-override'] // overrides $itemOptions
 *      ]
 * ],
 * 'fixedItemOptions' => [
 *      'tag' => 'p',
 *      'content' => 'Some content',
 * ],
 * ```
 * @author Robson Soares <robson_drs@hotmail.com>
 * @package widgets
 *
 * @see http://materializecss.com/carousel.html
 */
class Carousel extends BaseWidget
{
    /**
     * @var array the HTML attributes for each carousel item's tag.
     * These options will be merged with the individual item options.
     *
     * @see [yii\helpers\BaseHtml::renderTagAttributes()](http://www.yiiframework.com/doc-2.0/yii-helpers-basehtml.html#renderTagAttributes()-detail)
     * for details on how attributes are being rendered.
     */
    public $itemOptions = [];

    /**
     * @var false|array the configuration for the fixed item.
     *
     * The following special options are recognized:
     * - tag: the fixed item's HTML tag name
     * - content: the content of the fixed item. Please note: this can be HTML and will not be encoded.
     * - options: these options of the fixed Item.
     * 
     * If you do not want the fixed item to be rendered, set this option to `false`.
     * @see http://materializecss.com/carousel.html#special
     */
    public $fixedItem = false;

    /**
     * @var array the carousel items.
     * Provide a sub-array for each item which can have the keys `tag`, `content` and `options`.
     */
    public $items = [];

    /**
     * @var array the plugin options
     * @see https://materializecss.com/carousel.html#options
     */
    public $pluginOptions = [
        'duration'      => 200,
        'dist'          => -100,
        'shift'         => 0,
        'padding'       => 0,
        'numVisible'    => 5,
        'fullWidth'     => false,
        'indicators'    => false,
        'noWrap'        => false,
        'onCycleTo'     => null,
    ];

    /**
     * Initialize the widget.
     */
    public function init()
    {
        parent::init();

        Html::addCssClass($this->options, ['plugin' => 'carousel']);
        if ($this->pluginOptions['fullWidth']) {
            Html::addCssClass($this->options, ['fullwidth' => 'carousel-slider']);
        }
        
        $this->registerPlugin('Carousel');
    }

    /**
     * Execute the widget.
     */
    public function run()
    {
        $tag = ArrayHelper::remove($this->options, 'tag', 'div');
        $html[] = Html::beginTag($tag, $this->options);
        $html[] = $this->renderFixedItem();
        $html[] = $this->renderItems();
        $html[] = Html::endTag($tag);

        return implode("\n", $html);
    }

    /**
     * Parses all [[items]] and renders item list.
     *
     * @return string the item list markup
     */
    protected function renderItems()
    {
        if (!$this->items) {
            return '';
        }

        $html = [];

        foreach ($this->items as $item) {
            $html[] = $this->renderItem($item);
        }

        return implode("\n", $html);
    }

    /**
     * Renders a single carousel item.
     *
     * @param array $item the item configuration
     * @return string the item markup
     */
    protected function renderItem($item = [])
    {
        $tag = ArrayHelper::getValue($item, 'tag', 'div');
        $content = ArrayHelper::getValue($item, 'content', '');
        $options = ArrayHelper::getValue($item, 'options', []);
        $options = ArrayHelper::merge($this->itemOptions, $options);

        Html::addCssClass($options, ['item' => 'carousel-item']);

        return Html::tag($tag, $content, $options);
    }

    /**
     * Renders the optional fixed item.
     *
     * @return string the fixed item's markup
     */
    protected function renderFixedItem()
    {
        if ($this->fixedItem === false) {
            return '';
        }

        $tag = ArrayHelper::remove($this->fixedItem, 'tag', 'div');
        $content = ArrayHelper::remove($this->fixedItem, 'content', '');
        $options = ArrayHelper::remove($this->fixedItem, 'options', []);

        Html::addCssClass($options, ['fixed-item' => 'carousel-fixed-item']);

        return Html::tag($tag, $content, $options);
    }
}