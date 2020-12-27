<?php
/**
 * @link https://github.com/UomoCon/materialize
 * @license https://github.com/UomoCon/materialize/blob/master/LICENSE
 */

namespace uomocon\materialize\widgets;

use uomocon\materialize\assets\MaterializeAsset;
use Yii;
use yii\helpers\Json;
use yii\web\View;

/**
 * MaterializeWidgetTrait provides the basics for all Materialize widgets features.
 *
 * Please note: a class using this trait must declare a public field named `options` with the array default value:
 *
 * ```php
 * class MyWidget extends \yii\base\Widget
 * {
 *     use MaterializeWidgetTrait;
 *
 *     public $options = [];
 * }
 * ```
 *
 * This field is not present in the trait in order to avoid possible PHP Fatal error on definition conflict.
 *
 * @author Robson Soares <robson_drs@hotmail.com>
 * @package widgets
 */
trait MaterializeWidgetTrait
{
    /**
     * @var array the options for the underlying Materialize JS plugin.
     * Please refer to the corresponding Materialize documentation web page.
     *
     * @see http://materializecss.com/
     */
    public $pluginOptions = [];

    /**
     * @var array the event handlers for the underlying Materialize JS plugin.
     * Please refer to the corresponding Materialize documentation web page.
     *
     * @see http://materializecss.com/
     */
    public $pluginEvents = [];

    /**
     * Initializes the widget.
     * This method will register the MaterializeAsset bundle. When overriding this method,
     * make sure to call the parent implementation first.
     */
    public function init()
    {
        parent::init();
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
    }

    /**
     * Registers a specific Materialize plugin and the related events.
     * @param string $name the name of the Materialize plugin
     * @param string|null $selector the name of the selector the plugin shall be attached to
     * @uses [yii\helper\BaseJson::encode()](http://www.yiiframework.com/doc-2.0/yii-helpers-basejson.html#encode()-detail)
     * to encode the [[pluginOptions]]
     * @uses [[MaterializePluginAsset::register()]]
     * @uses [[registerpluginEvents()]]
     */
    protected function registerPlugin($name, $selector = null)
    {
        /** @var View $view */
        $view = $this->getView();

        MaterializeAsset::register($view);

        $id = $this->options['id'];

        if (is_null($selector)) {
            $selector = "#{$id}";
        }

        if ($this->pluginOptions !== false) {
            $options = empty($this->pluginOptions) ? '{}' : Json::htmlEncode($this->pluginOptions);

            $js = "document.addEventListener('DOMContentLoaded', function() {M.$name.init(document.getElementById('$id').querySelectorAll('$selector'), $options);});";
            $view->registerJs($js, View::POS_END);
        }

        $this->registerPluginEvents();
    }

    /**
     * Registers JS event handlers that are listed in [[pluginEvents]].
     */
    protected function registerPluginEvents()
    {
        if (!empty($this->pluginEvents)) {
            /** @var View $view */
            $view = $this->getView();
            $id = $this->options['id'];
            $js[] = "var elem_$id = document.getElementById('$id');";
            foreach ($this->pluginEvents as $event => $handler) {
                $js[] = "elem_$id.addEventListener('$event', $handler);";
            }
            $view->registerJs(implode("\n", $js), View::POS_END);
        }
    }

    /**
     * @return string
     */
    protected function getUniqueId($prefix = 'u_')
    {
        $uniqid = sha1(uniqid($prefix, true));
        return "{$prefix}{$uniqid}";
    }
}