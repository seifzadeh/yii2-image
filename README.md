image class for yii2
=========================
image resize yii2

Installation
------------
<iframe src="http://www.aparat.com/video/video/embed/videohash/jeXMD/vt/frame" allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" height="360" width="640" ></iframe>

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist persianyii/yii2-image "dev-master"
```

or add

```
"persianyii/yii2-image": "dev-master"
```

to the require section of your `composer.json` file.


Usage
-----

base class from http://www.paulund.co.uk/resize-image-class-php


Resize Exact Size
To resize an image to an exact size you can use the following code. First pass in the image we want to resize in the class constructor, then define the width and height with the scale option of exact. The class will now have the create dimensions to create the new image, now call the function saveImage() and pass in the new file location to the new image.
```php
$resize = new \persianyii\image\Resize('images/Be-Original.jpg');
$resize->resizeTo(100, 100, 'exact');
$resize->saveImage('images/be-original-exact.jpg');
```



Resize Max Width Size
If you choose to set the image to be an exact size then when the image is resized it could lose it's aspect ratio, which means the image could look stretched. But if you know the max width that you want the image to be you can resize the image to a max width, this will keep the aspect ratio of the image.
```php
$resize = new \persianyii\image\Resize('images/Be-Original.jpg');
$resize->resizeTo(100, 100, 'maxWidth');
$resize->saveImage('images/be-original-maxWidth.jpg');
```

Resize Max Height Size
Just as you can select a max width for the image while keeping aspect ratio you can also select a max height while keeping aspect ratio.
```php
$resize = new \persianyii\image\Resize('images/Be-Original.jpg');
$resize->resizeTo(100, 100, 'maxHeight');
$resize->saveImage('images/be-original-maxHeight.jpg');
```

Resize Auto Size From Given Width And Height
You can also allow the code to work out the best way to resize the image, so if the image height is larger than the width then it will resize the image by using the height and keeping aspect ratio. If the image width is larger than the height then the image will be resized using the width and keeping the aspect ratio.
```php
$resize = new \persianyii\image\Resize('images/Be-Original.jpg');
$resize->resizeTo(100, 100);
$resize->saveImage('images/be-original-default.jpg');
```
Download The Resized Image
The default behaviour for this class is to save the image on the server, but you can easily change this to download by passing in a true parameter to the saveImage method.
```php
$resize = new \persianyii\image\Resize('images/Be-Original.jpg');
$resize->resizeTo(100, 100, 'exact');
$resize->saveImage('images/be-original-exact.jpg', "100", true);
```
