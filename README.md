# W-PHP Resizer

[![PHPUnit Tests](https://github.com/webkolog/php-resizer/actions/workflows/php-test.yml/badge.svg)](https://github.com/webkolog/php-resizer/actions)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

**Version:** 1.0 (Resizer)

**Last Updated:** 2016-04-03

**Compatibility:** PHP 5.4

**Created By:** Ali Candan ([@webkolog](https://github.com/webkolog))

**Website:** [http://webkolog.net](http://webkolog.net)

**Copyright:** (c) 2015 Ali Candan

**License:** MIT License ([http://mit-license.org](http://mit-license.org))

**W-PHP Resizer** is a PHP 5.4+ compatible class that allows you to easily resize images. This class provides various options for resizing, cropping, and saving images.

## Installation

To use this class, simply include the `resizer.php` file in your project.

## Usage

### Initialize the Class

```php
include 'resizer.php';
$resizeObj = new resize('path/to/your/image.jpg');
```

Here, `path/to/your/image.jpg` specifies the path to the image you want to resize.

### Resize Image
The `resizeImage()` method resizes the image to the specified dimensions and option.
```php
$resizeObj->resizeImage($newWidth, $newHeight, $option);
```
- `$newWidth:` Target width (pixels).
- `$newHeight:` Target height (pixels).
- `$option:` Resizing option (see options below).

### Resizing Options
`'exact':` Fits the image exactly to the specified dimensions, compressing the image if necessary.
`'portrait':` Resizes the image based on the specified height, adjusting the width proportionally.
`'landscape':` Resizes the image based on the specified width, adjusting the height proportionally.
`'auto':` Automatically resizes the image to the specified dimensions while maintaining aspect ratio.
`'crop':` Resizes the image by cropping it to the specified dimensions.

### Save Image
The `saveImage()` method saves the resized image to the specified path and quality.
```php
$resizeObj->saveImage($savePath, $imageQuality);
```
- `$savePath:` Path and name of the image to be saved.
- `$imageQuality:` Quality for JPEG and PNG images (0-100, default 100).
## Example Usages

### Landscape Resizing
```php
$resizeObj->resizeImage(200, 200, 'landscape');
$resizeObj->saveImage('sample-resized-landscape.jpg', 100);
echo '<img src="sample-resized-landscape.jpg"></img><p>';
```

### Portrait Resizing
```php
$resizeObj->resizeImage(200, 200, 'portrait');
$resizeObj->saveImage('sample-resized-portrait.jpg', 100);
echo '<img src="sample-resized-portrait.jpg"></img><p>';
```

### Auto Resizing
```php
$resizeObj->resizeImage(200, 200, 'auto');
$resizeObj->saveImage('sample-resized-auto.jpg', 100);
echo '<img src="sample-resized-auto.jpg"></img><p>';
```

### Exact Resizing (Compression)
```php
$resizeObj->resizeImage(200, 200, 'exact');
$resizeObj->saveImage('sample-resized-exact.jpg', 100);
echo '<img src="sample-resized-exact.jpg"></img><p>';
```

### Crop Resizing
```php
$resizeObj->resizeImage(200, 200, 'crop');
$resizeObj->saveImage('sample-resized-crop.jpg', 100);
echo '<img src="sample-resized-crop.jpg"></img><p>';
```

##Detailed Examples

### Saving with Different Qualities
```php
$resizeObj->resizeImage(300, 300, 'auto');
$resizeObj->saveImage('sample-resized-low-quality.jpg', 50); // Low quality
$resizeObj->saveImage('sample-resized-high-quality.jpg', 90); // High quality
```

### Saving to Different File Types
```php
$resizeObj->resizeImage(150, 150, 'crop');
$resizeObj->saveImage('sample-resized.png', 75); // Save as PNG
$resizeObj->saveImage('sample-resized.gif'); // Save as GIF
```

### Resizing While Maintaining Aspect Ratio
```php
$resizeObj->resizeImage(500, 300, 'auto'); // Resize width to 500 while maintaining aspect ratio
$resizeObj->resizeImage(300, 500, 'auto'); // Resize height to 500 while maintaining aspect ratio
```

## Error Handling
The class does not return errors for invalid file types or incorrect file paths. However, the `openImage()` method returns `false` for an invalid file type or path. Therefore, it's recommended to check the file type and path before performing file upload and resizing operations.
```php
$resizeObj = new resize('invalid_file.txt');
// $resizeObj->image is false if file couldn't be opened.
```
## Dependencies
This class depends on PHP's GD library. Make sure the GD library is enabled.

## License
This W-PHP Resizer class is open-source software licensed under the [MIT license](https://mit-license.org/).
```
MIT License

Copyright (c) 2015 Ali Candan

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

## Contributing
Contributions are welcome! If you find any bugs or have suggestions for improvements, please `feel free to open an issue or submit a pull request on the GitHub repository.`

## Support
For any questions or support regarding the W-PHP Resizer, you can refer to the project's GitHub repository or contact the author.
