<?php
/**
 * @link https://github.com/UomoCon/materialize
 * @license https://github.com/UomoCon/materialize/blob/master/LICENSE
 */

namespace uomocon\materialize\widgets;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;

/**
 * MaterialBox creates a lightweight lightbox variant to present images.
 *
 * @author Robson Soares <robson_drs@hotmail.com>
 * @package widgets
 *
 * @see https://materializecss.com/media.html#materialbox
 */
class MaterialBox extends BaseWidget
{
    /**
     * @var string the source of the image.
     * You must either specify this option or provide an image source via [[$imageOptions]].
     */
    public $image;

    /**
     * @var array the HTML attributes for the image tag.
     * @see [yii\helpers\BaseHtml::renderTagAttributes()](http://www.yiiframework.com/doc-2.0/yii-helpers-basehtml.html#renderTagAttributes()-detail)
     * for details on how attributes are being rendered.
     */
    public $imageOptions = [];

    /**
     * @var string|false the caption of the image.
     * If you do not want a caption to be rendered, set this option to `false`.
     */
    public $caption = false;

    /**
     * @var boolean whether the image caption shall be HTML-encoded. This defaults to `true`.
     */
    public $encodeCaption = true;

    /**
     * Initialize the widget.
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (!$this->image) {
            $image = ArrayHelper::remove($this->imageOptions, 'src', null);
            if (!$image) {
                throw new InvalidConfigException("Image src must be defined.");
            }

            $this->image = $image;
        }

        Html::addCssClass($this->imageOptions, ['plugin' => 'materialboxed']);
        if ($this->caption !== false) {
            $this->imageOptions['data-caption'] = $this->encodeCaption ? Html::encode($this->caption) : $this->caption;
        }

        $this->registerPlugin('Materialbox', '.materialboxed');

    }

    /**
     * Execute the widget.
     * @return string the widget's markup.
     */
    public function run()
    {

        $tag = ArrayHelper::remove($this->options, 'tag', 'div');
        $html[] = Html::beginTag($tag, $this->options);
        $html[] = Html::img($this->image, $this->imageOptions);
        $html[] = Html::endTag($tag);

        return implode("\n", $html);
    }
}
