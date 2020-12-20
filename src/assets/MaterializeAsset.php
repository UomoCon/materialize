<?php
/**
 * @link https://github.com/UomoCon/materialize
 * @license https://github.com/UomoCon/materialize/blob/master/LICENSE
 */

namespace uomocon\materialize\assets;

use yii\web\AssetBundle;

/**
 * MaterializeAsset provides the required Materialize CSS and JS files.
 *
 * @author Robson Soares <robson_drs@hotmail.com>
 * @package assets
 */
class MaterializeAsset extends AssetBundle
{
    /**
     * @var string the directory that contains the source asset files for this asset bundle.
     */
    public $sourcePath = '@npm/materialize/dist';

    /**
     * @var array list of CSS files that this bundle contains.
     */
    public $css = [
        'css/materialize.min.css'
    ];

    /**
     * @var array list of JS files that this bundle contains.
     */
    public $js = [
        'js/materialize.min.js'
    ];
}
