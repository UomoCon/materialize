<?php
/**
 * @link https://github.com/UomoCon/materialize
 * @license https://github.com/UomoCon/materialize/blob/master/LICENSE
 */

namespace uomocon\materialize\widgets;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\web\View;

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
     * @var array the HTML attributes for the carousel container tag.
     * @see [yii\helpers\BaseHtml::renderTagAttributes()](http://www.yiiframework.com/doc-2.0/yii-helpers-basehtml.html#renderTagAttributes()-detail)
     * for details on how attributes are being rendered.
     */
    public $carouselOptions = [];

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
     * @var int transition duration in milliseconds.
     */
    public $duration = 200;

    /**
     * @var int if 0, all items are the same size.
     */
    public $dist = -100;

    /**
     * @var int sets the spacing of the center item.
     */
    public $shift = 0;

    /**
     * @var int sets the padding between non center items.
     */
    public $padding = 0;

    /**
     * @var int sets the number of items visible.
     */
    public $numVisible = 5;

    /**
     * @var boolean whether the carousel has full width.
     */
    public $fullWidth = false;

    /**
     * @var boolean whether to show navigation indicators.
     */
    public $indicators = false;

    /**
     * @var boolean whether to start with first slide at the end.
     */
    public $noWrap = false;

    /**
     * @var function Callback for when a new slide is cycled to..
     */
    public $onCycleTo = null;

        /**
     * @var boolean whether to show navigation.
     */
    public $nav = false;

        /**
     * @var array options to navigation.
     */
    public $navOptions = [
        ['tag' => 'span', 'content' => '<', 'options' => []],
        ['tag' => 'span', 'content' => '>', 'options' => []]
    ];

    /**
     * Initialize the widget.
     */
    public function init()
    {
        parent::init();

        Html::addCssClass($this->carouselOptions, ['plugin' => 'carousel']);
        if ($this->fullWidth) {
            Html::addCssClass($this->carouselOptions, ['fullwidth' => 'carousel-slider']);
        }
        
        $this->pluginOptions = [
            'duration'      => $this->duration,
            'dist'          => $this->dist,
            'shift'         => $this->shift,
            'padding'       => $this->padding,
            'numVisible'    => $this->numVisible,
            'fullWidth'     => $this->fullWidth,
            'indicators'    => $this->indicators,
            'noWrap'        => $this->noWrap,
            'onCycleTo'     => $this->onCycleTo,
        ];

        $this->registerPlugin('Carousel', '.carousel');
    }

    /**
     * Execute the widget.
     */
    public function run()
    {
        $tag = ArrayHelper::remove($this->options, 'tag', 'div');
        $html[] = Html::beginTag($tag, $this->options);
        $html[] = Html::beginTag('div', $this->carouselOptions);
        $html[] = $this->renderFixedItem();
        $html[] = $this->renderItems();
        $html[] = Html::endTag('div');
        $html[] = $this->renderNav();
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

    /**
     * Renders the optional nav
     *
     * @return string the nav's markup
     */
    protected function renderNav()
    {
        if (!$this->nav || count($this->navOptions) != 2) {
            return '';
        }

        $html = [];
        $id = $this->options['id'];

        foreach ($this->navOptions as $count => $nav){
            $tag = ArrayHelper::remove($nav, 'tag', 'span');
            $content = ArrayHelper::remove($nav, 'content', '');
            $options = ArrayHelper::remove($nav, 'options', []);
            $options['id'] = ($count == 0)?"prev-{$id}":"next-{$id}";
            Html::addCssStyle($options, 'cursor:pointer;position:relative');
            $html[] = Html::tag($tag, $content, $options);
        }

        $style  = [
            'width' => '100%', 
            'text-align' => 'center', 
            'bottom' => '5px',
            'z-index' => 100
        ];

        $view = $this->getView();
        $js[] = "document.addEventListener('DOMContentLoaded', function() {";
        $js[] = "var prev = document.getElementById('prev-$id');";
        $js[] = "var next = document.getElementById('next-$id');";
        $js[] = "var elem = document.getElementById('$id').querySelector('.carousel');";
        $js[] = "var instance = M.Carousel.getInstance(elem);";
        $js[] = "prev.addEventListener(\"click\", function() {instance.prev();});";
        $js[] = "next.addEventListener(\"click\", function() {instance.next();});";
        $js[] = "});";
    
    
        $view->registerJs(implode(" ", $js), View::POS_END);

        return Html::tag('div', implode("\n", $html), ['class' => 'carousel-nav', 'style' => $style]);
    }
}