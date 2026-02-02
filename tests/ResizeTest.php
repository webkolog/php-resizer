<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../resizer.php';

class ResizeTest extends TestCase {
    private $testImagePath;
    private $outputImagePath;

    protected function setUp(): void {
        // Test için geçici bir görsel oluştur (100x200 portrait bir görsel)
        $this->testImagePath = __DIR__ . '/test_image.jpg';
        $this->outputImagePath = __DIR__ . '/output_image.jpg';
        
        $img = imagecreatetruecolor(100, 200);
        imagejpeg($img, $this->testImagePath);
        imagedestroy($img);
    }

    protected function tearDown(): void {
        // Test sonrası dosyaları temizle
        if (file_exists($this->testImagePath)) unlink($this->testImagePath);
        if (file_exists($this->outputImagePath)) unlink($this->outputImagePath);
    }

    public function testImageLoading() {
        $resize = new resize($this->testImagePath);
        $this->assertInstanceOf(resize::class, $resize);
    }

    public function testExactResize() {
        $resize = new resize($this->testImagePath);
        $resize->resizeImage(50, 50, 'exact');
        $resize->saveImage($this->outputImagePath);

        list($width, $height) = getimagesize($this->outputImagePath);
        $this->assertEquals(50, $width);
        $this->assertEquals(50, $height);
    }

    public function testPortraitResize() {
        // Kaynak: 100x200. Yüksekliği 100 yaparsak, oran korunarak genişlik 50 olmalı.
        $resize = new resize($this->testImagePath);
        $resize->resizeImage(0, 100, 'portrait');
        $resize->saveImage($this->outputImagePath);

        list($width, $height) = getimagesize($this->outputImagePath);
        $this->assertEquals(100, $height);
        $this->assertEquals(50, $width);
    }

    public function testLandscapeResize() {
        // Kaynak: 100x200. Genişliği 200 yaparsak, oran korunarak yükseklik 400 olmalı.
        $resize = new resize($this->testImagePath);
        $resize->resizeImage(200, 0, 'landscape');
        $resize->saveImage($this->outputImagePath);

        list($width, $height) = getimagesize($this->outputImagePath);
        $this->assertEquals(200, $width);
        $this->assertEquals(400, $height);
    }
	

	
	public function testAutoResize() {
		$resize = new resize($this->testImagePath); // Kaynak: 100x200
		
		// Auto modunda: Görsel Portrait olduğu için yüksekliği (50) baz alır.
		// Genişlik ise oran korunarak 25 olur.
		$resize->resizeImage(50, 50, 'auto');
		$resize->saveImage($this->outputImagePath);

		list($width, $height) = getimagesize($this->outputImagePath);
		
		$this->assertEquals(50, $height); // Sabitlenen değer
		$this->assertEquals(25, $width);  // Orantılı değer
	}

	public function testCropResize() {
		$resize = new resize($this->testImagePath); // Kaynak: 100x200
		
		// Crop modunda sonuç tam olarak istenen değerlerde olmalı
		$resize->resizeImage(40, 40, 'crop');
		$resize->saveImage($this->outputImagePath);

		list($width, $height) = getimagesize($this->outputImagePath);
		$this->assertEquals(40, $width);
		$this->assertEquals(40, $height);
	}
	
	public function testInvalidFile() {
		$this->expectException(Exception::class);
		$resize = new resize('yok_boyle_bir_dosya.jpg');
	}

	public function testUnsupportedExtension() {
		$txtFile = __DIR__ . '/test.txt';
		file_put_contents($txtFile, 'not an image');
		
		$this->expectException(Exception::class);
		try {
			$resize = new resize($txtFile);
		} finally {
			unlink($txtFile); // Hata fırlatsa bile dosyayı siler
		}
	}
	
	public function testZeroDimensions() {
		$resize = new resize($this->testImagePath);
		
		// PHP 8+ sürümlerinde 0'a bölme hatası Fatal Error veya Warning verebilir.
		// Kodunda: $ratio = $this->width / $this->height; satırı riskli.
		
		try {
			$resize->resizeImage(0, 0, 'exact');
			$this->assertTrue(true); // Eğer buraya kadar geldiyse çökmemiştir
		} catch (\DivisionByZeroError $e) {
			$this->fail('Kod sıfıra bölme hatası verdi!');
		}
	}
	
	
	
	
	

	
	
	
	
}