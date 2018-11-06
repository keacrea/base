<?php

namespace App\View\Helper;

use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Utility\Text;
use Cake\View\Helper;
use Intervention\Image\ImageManager;

class ImageHelper extends Helper
{

    public $helpers = ['Html'];

    public function resize($image, $width, $height, $options = array())
    {

        if (file_exists(WWW_ROOT . $image) && is_file(WWW_ROOT . $image)) {
            return $this->Html->image($this->resizedUrl($image, $width, $height), $options);
        }
    }

    public function resizedUrl($file, $width, $height)
    {

	    if (file_exists(WWW_ROOT . $file) && is_file(WWW_ROOT . $file)) {
		    try {
			    $ext = pathinfo($file, PATHINFO_EXTENSION);
			    $filename = strtolower(Text::slug(pathinfo($file, PATHINFO_FILENAME)));
			    $dirname = pathinfo($file, PATHINFO_DIRNAME);

			    if (!is_dir(Configure::read('App.imageBaseUrl') . 'min/' . $dirname)) {
				    mkdir(Configure::read('App.imageBaseUrl') . 'min/' . $dirname, 0755, true);
			    }

			    if (!file_exists(Configure::read('App.imageBaseUrl') . 'min/' . $dirname . '/' . $filename . '_' . $width . 'x' . $height . '.' . $ext)) {
				    $imageManger = new ImageManager();
				    $imageManger->make(Configure::read('App.wwwRoot') . $file)
				                ->fit($width, $height)
				                ->save(Configure::read('App.imageBaseUrl') . 'min/' . $dirname . '/' . $filename . '_' . $width . 'x' . $height . '.' . $ext, 100);
			    }
			    return 'min/' . $dirname . '/' . $filename . '_' . $width . 'x' . $height . '.' . $ext;
		    } catch (Exception $e) {
			    echo $e->getMessage();
		    }
	    }


    }

    public function resizeConstraint($image, $width, $height, $options = array())
    {

        if (file_exists(WWW_ROOT . $image) && is_file(WWW_ROOT . $image)) {
            return $this->Html->image($this->resizedUrlConstraint($image, $width, $height), $options);
        }
    }

    public function resizedUrlConstraint($file, $width, $height)
    {
	    if (file_exists(WWW_ROOT . $file) && is_file(WWW_ROOT . $file)) {
		    try {

			    $ext      = pathinfo( $file, PATHINFO_EXTENSION );
			    $filename = strtolower( Text::slug( pathinfo( $file, PATHINFO_FILENAME ) ) );
			    $dirname  = pathinfo( $file, PATHINFO_DIRNAME );

			    if ( ! is_dir( Configure::read( 'App.imageBaseUrl' ) . 'min/' . $dirname ) ) {
				    mkdir( Configure::read( 'App.imageBaseUrl' ) . 'min/' . $dirname, 0755, true );
			    }

			    if ( ! file_exists( Configure::read( 'App.imageBaseUrl' ) . 'min/' . $dirname . '/' . $filename . '_' . $width . 'x' . $height . '.' . $ext ) ) {
				    $imageManger = new ImageManager();
				    $imageManger->make( Configure::read( 'App.wwwRoot' ) . $file )
				                ->resize( $width, $height, function ( $constraint ) {
					                $constraint->aspectRatio();
					                $constraint->upsize();

				                } )
				                ->save( Configure::read( 'App.imageBaseUrl' ) . 'min/' . $dirname . '/' . $filename . '_' . $width . 'x' . $height . '.' . $ext, 100 );
			    }

			    return 'min/' . $dirname . '/' . $filename . '_' . $width . 'x' . $height . '.' . $ext;
		    } catch ( Exception $e ) {
			    echo $e->getMessage();
		    }
	    }
    }
}
