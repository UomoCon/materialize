<?php
/**
 * @link https://github.com/UomoCon/materialize
 * @license https://github.com/UomoCon/materialize/blob/master/LICENSE
 */


namespace uomocon\materialize\widgets;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * Collapsibles are accordion elements that expand when clicked on. They allow you to hide content that is not immediately relevant to the user.
 *
 * Simply provide the [[items]] as an array.
 *
 * For each item you can define the `header` and `body` key for header and body configuration.
 * Both `header` and `body` support the following special options:
 * - `tag`: the tag for the container tag, defaults to `div`.
 * - `content`: the content for the respective section. This can be arbitrary HTML.
 *
 * All other options are rendered as HTML attributes.
 *
 * ```php
 * 'type' => Collapsible::TYPE_EXPANDABLE,
 * 'items' => [
 *      [
 *          'header' => '<i class="material-icons">filter_drama</i>First',
 *          'body' => '<span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</span>',
 *          'options' => ['class' => 'class-item-li'] // class to li tag
 *      ],
 *      [
 *          'header' => '<i class="material-icons">place</i>Second',
 *          'headerOptions' => ['class' => 'customHeader'],
 *          'body' => '<span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</span>',
 *          'bodyOptions' =>[
 *              'tag' => 'p',
 *              'data-body-category' => 'example',
 *          ],
 *          'active' => true, // to make this item pre-selected
 *      ],
 * ]
 * ```
 * @author Robson Soares <robson_drs@hotmail.com>
 * @package widgets
 *
 * @see https://materializecss.com/collapsible.html
 */
class Collapsible extends BaseWidget
{
    /**
     * Sets the [[type]] to `accordion`, `expandable` and `popout`.
     */
    const TYPE_ACCORDION = 'accordion';
    const TYPE_EXPANDABLE = 'expandable';
    const TYPE_POPOUT = 'popout';

    /**
     *
     * @var array to options of ul
     */
    public $collapsibleOptions = [];

    /**
     * @var array the list of items. Provide an array for each item. See introductory example for details.
     */
    public $items = [];

    /**
     * @var string the type of the Collapsible.
     * Defaults to `accordion`.
     */
    public $type = self::TYPE_ACCORDION;

    /**
     * @var int Transition in duration in milliseconds.
     */
    public $inDuration = 300;

    /**
     * @var int Transition out duration in milliseconds.
     */
    public $outDuration = 300;

    /**
     * Initialize the widget.
     */
    public function init()
    {
        parent::init();
        Html::addCssClass($this->collapsibleOptions, ['widget' => 'collapsible']);

        if ($this->type == self::TYPE_POPOUT) {
            Html::addCssClass($this->collapsibleOptions, ['popout' => 'popout']);
        }

        if ($this->type == self::TYPE_EXPANDABLE) {
            $this->pluginOptions['accordion'] = false;
        }

        $this->pluginOptions['inDuration'] = $this->inDuration;
        $this->pluginOptions['outDuration'] = $this->outDuration;

        $this->registerPlugin('Collapsible', '.collapsible');
    }

    /**
     * Execute the widget.
     * @return string the widget markup
     */
    public function run()
    {
        $tag = ArrayHelper::remove($this->options, 'tag', 'div');
        $html[] = Html::beginTag($tag, $this->options);
        $html[] = Html::tag('ul', $this->renderItems(), $this->collapsibleOptions);
        $html[] = Html::endTag($tag);

        return implode("\n", $html);

    }

    /**
     * Render the items.
     * @return string the markup for all items
     */
    protected function renderItems()
    {
        $html = [];
        foreach ($this->items as $item) {
            $html[] = $this->renderItem($item);
        }

        return implode("\n", $html);
    }

    /**
     * Render a single item.
     * @param array $item the item configuration
     * @return string the item's markup
     */
    protected function renderItem($item = [])
    {
        $itemOptions = ArrayHelper::getValue($item, 'options', []);
        $active = ArrayHelper::getValue($item, 'active', false);
        if ($active) {
            Html::addCssClass($itemOptions, ['active' => 'active']);
        }

        $headerContent = ArrayHelper::remove($item, 'header');
        $headerOptions = ArrayHelper::getValue($item, 'headerOptions', []);
        $headerTag = ArrayHelper::remove($headerOptions, 'tag', 'div');

        $bodyContent = ArrayHelper::remove($item, 'body');
        $bodyOptions = ArrayHelper::getValue($item, 'bodyOptions', []);
        $bodyTag = ArrayHelper::remove($bodyOptions, 'tag', 'div');

        if (!$headerContent && !$bodyContent) {
            return '';
        }

        $html[] = Html::beginTag('li', $itemOptions);
        if ($headerContent) {
            Html::addCssClass($headerOptions, ['header' => 'collapsible-header']);
            $html[] = Html::tag($headerTag, $headerContent, $headerOptions);
        }
        if ($bodyContent) {
            Html::addCssClass($bodyOptions, ['body' => 'collapsible-body']);
            $html[] = Html::tag($bodyTag, $bodyContent, $bodyOptions);
        }
        $html[] = Html::endTag('li');

        return implode("\n", $html);
    }
}
