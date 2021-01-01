<?php
/**
 * @link https://github.com/UomoCon/materialize
 * @license https://github.com/UomoCon/materialize/blob/master/LICENSE
 */


namespace uomocon\materialize\widgets;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * Slider renders a Materialize image slider with optional captions.
 *
 * Simply provide the [[slides]] as an array of items.
 * For each item you must define the `image` key with the image's `src`. Additionally you can define and align a caption
 * for every slide individually. Caption content can be HTML and will <strong>not</strong> be encoded.
 *
 * ```php
 * 'itemOptions' => [
 *      'class' => 'slide-item' // this class will be used for all slide elements (<li>)
 * ],
 * 'items' => [
 *      [
 *          'image' => ['src' => '/source/of/image'],
 *      ],
 *      [
 *          'image' => ['src' => '/source/of/image'],
 *          'caption' => [
 *              'content' => '<p>Caption content</p>',
 *              'align' => Slider::CAPTION_ALIGN_RIGHT
 *          ],
 *          'options' => ['class' => 'slide-item-override'] // overrides $slideOptions
 *      ]
 * ]
 * ```
 * @author Robson Soares <robson_drs@hotmail.com>
 * @package widgets
 *
 * @see http://nextmaterializecss.com/media.html#slider
 */
class Slider extends BaseWidget
{
    /**
     * Sets the caption alignment to `left`, `center` and `right`.
     */
    const CAPTION_ALIGN_LEFT = 'left-align';
    const CAPTION_ALIGN_CENTER = 'center-align';
    const CAPTION_ALIGN_RIGHT = 'right-align';

    /**
     * @var array the HTML attributes for the slider container tag.
     * @see [yii\helpers\BaseHtml::renderTagAttributes()](http://www.yiiframework.com/doc-2.0/yii-helpers-basehtml.html#renderTagAttributes()-detail)
     * for details on how attributes are being rendered.
     */
    public $sliderOptions = [];

    /**
     * @var array the HTML attributes for each slider's `<li>` tag.
     * These options will be merged with the individual slide options.
     *
     * @see [yii\helpers\BaseHtml::renderTagAttributes()](http://www.yiiframework.com/doc-2.0/yii-helpers-basehtml.html#renderTagAttributes()-detail)
     * for details on how attributes are being rendered.
     */
    public $itemOptions = [];

    /**
     * @var array the HTML attributes for each caption.
     * These options will be merged with the individual caption options.
     *
     * @see [yii\helpers\BaseHtml::renderTagAttributes()](http://www.yiiframework.com/doc-2.0/yii-helpers-basehtml.html#renderTagAttributes()-detail)
     * for details on how attributes are being rendered.
     */
    public $captionOptions = [];

    /**
     * @var array the slide items.
     * Provide a sub-array for each slide which contains at least the `image` key for the image options. Every image must
     * have a `src` with the image's URL.
     */
    public $items = [];

    /**
     * @var boolean whether to show the slider's navigation indicators.
     */
    public $indicators = true;

    /**
     * @var boolean whether this is a fullscreen slider.
     */
    public $fullscreen = false;

    /**
     * @var int the slider height.
     */
    public $height = 400;

    /**
     * @var int the duration of the transition animation in ms.
     */
    public $duration = 500;

    /**
     * @var int the duration each slide is shown in ms.
     */
    public $interval = 6000;

    /**
     * Initialize the widget.
     */
    public function init()
    {
        parent::init();

        Html::addCssClass($this->sliderOptions, ['plugin' => 'slider']);

        if ($this->fullscreen === true) {
            Html::addCssClass($this->sliderOptions, ['fullscreen' => 'fullscreen']);
        }

        $this->pluginOptions['indicators'] = $this->indicators;
        $this->pluginOptions['height'] = $this->height;
        $this->pluginOptions['duration'] = $this->duration;
        $this->pluginOptions['interval'] = $this->interval;

        $this->registerPlugin('Slider', '.slider');
    }

    /**
     * Execute the widget.
     * @return string the rendered markup
     */
    public function run()
    {
        $tag = ArrayHelper::remove($this->options, 'tag', 'div');
        $html[] = Html::beginTag($tag, $this->options);
        $html[] = Html::beginTag('div', $this->sliderOptions);
        $html[] = $this->renderSlides();
        $html[] = Html::endTag('div');
        $html[] = Html::endTag($tag);

        return implode("\n", $html);
    }

    /**
     * Parses all [[slides]] and generates the slide list.
     * @return string the list markup
     */
    protected function renderSlides()
    {
        $slides = [];
        foreach ($this->items as $slide) {
            $slides[] = $this->renderSlide($slide);
        }
        $html[] = Html::tag('ul', implode("\n", $slides), ['class' => 'slides']);
        return implode("\n", $html);
    }

    /**
     * Renders a single slide.
     *
     * @param array $slide the configuration for the slide
     * @return string the slide's markup
     */
    protected function renderSlide($slide = [])
    {
        $imageOptions = ArrayHelper::getValue($slide, 'image', []);
        $imageSrc = ArrayHelper::remove($imageOptions, 'src', null);
        if (!$imageSrc) {
            return '';
        }

        $caption = $this->renderCaption(ArrayHelper::getValue($slide, 'caption', false));
        $options = ArrayHelper::getValue($slide, 'options', []);
        $options = ArrayHelper::merge($this->itemOptions, $options);

        $html[] = Html::beginTag('li', $options);
        $html[] = Html::img($imageSrc, $imageOptions);
        $html[] = $caption;
        $html[] = Html::endTag('li');
        return implode("\n", $html);
    }

    /**
     * Renders the caption markup.
     * @param false|array $caption the caption configuration data
     * @return string the markup of the caption
     */
    protected function renderCaption($caption)
    {
        if ($caption === false) {
            return '';
        }

        $content = ArrayHelper::getValue($caption, 'content', '');
        $alignment = ArrayHelper::getValue($caption, 'align', null);
        $options = ArrayHelper::getValue($caption, 'options', []);
        $options = ArrayHelper::merge($this->captionOptions, $options);

        Html::addCssClass($options, ['caption' => 'caption']);
        if ($alignment) {
            Html::addCssClass($options, ['align' => $alignment]);
        }

        return Html::tag('div', $content, $options);
    }
}