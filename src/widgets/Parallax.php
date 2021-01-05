<?php
/**
 * @link https://github.com/UomoCon/materialize
 * @license https://github.com/UomoCon/materialize/blob/master/LICENSE
 */


namespace uomocon\materialize\widgets;

use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/**
 * Parallax renders an image container where the background image is scrolled at another speed than the foreground.
 *
 * @author Robson Soares <robson_drs@hotmail.com>
 * @package widgets
 *
 * @see https://materializecss.com/parallax.html
 */
class Parallax extends BaseWidget
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
     * Initialize the widget.
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (!$this->image) {
            $imageSrc = ArrayHelper::remove($this->imageOptions, 'src', null);
            if (!$imageSrc) {
                throw new InvalidConfigException("Image src must be defined.");
            }

            $this->image = $imageSrc;
        }

        $this->registerPlugin('Parallax', '.parallax');
    }

    /**
     * Execute the widget.
     * @return string the widget's markup.
     */
    public function run()
    {
        $tag = ArrayHelper::remove($this->options, 'tag', 'div');
        $html[] = Html::beginTag($tag, $this->options);
        $html[] = Html::beginTag('div', ['class' => 'parallax-container']);
        $html[] = Html::beginTag('div', ['class' => 'parallax']);
        $html[] = Html::img($this->image, $this->imageOptions);
        $html[] = Html::endTag('div');
        $html[] = Html::endTag('div');
        $html[] = Html::endTag($tag);

        return implode("\n", $html);
    }
}