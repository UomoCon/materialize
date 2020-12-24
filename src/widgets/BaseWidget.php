<?php
/**
 * @link https://github.com/UomoCon/materialize
 * @license https://github.com/UomoCon/materialize/blob/master/LICENSE
 */

namespace uomocon\materialize\widgets;

use yii\base\Widget;

/**
 * BaseWidget is the base class for all non-input widgets in this extension.
 *
 * @author Robson Soares <robson_drs@hotmail.com>
 * @package widgets
 */
class BaseWidget extends Widget
{
    use MaterializeWidgetTrait;

    /**
     * @var array the HTML attributes for the widget container tag.
     * @see [yii\helpers\BaseHtml::renderTagAttributes()](http://www.yiiframework.com/doc-2.0/yii-helpers-basehtml.html#renderTagAttributes()-detail) 
     * for details on how attributes are being rendered.
     */
    public $options = [];
}