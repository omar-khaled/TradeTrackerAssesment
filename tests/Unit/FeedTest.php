<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FeedTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    // test parsing xml one product
    public function testParseProduct()
	{
		$xml = '<?xml version="1.0" encoding="utf-8"?>
				<!-- Generated on 10/07/17 at 22:04:17 (http://pf.tradetracker.net) -->
				<products>
				<product>
				<productID>targus_pt-f</productID>
				<name>Targus F, Black, 25 pcs (PT-F)</name>
				<price currency="EUR">38.72</price>
				<productURL>http://www.centralpoint.nl/tracker/index.php?tt=534_251713_1_&amp;r=https%3A%2F%2Fwww.centralpoint.nl%2Fvoedingverloopstekkers-notebook%2Ftargus%2Ftargus-power-tip-f-pks-of-25pcs-art-pt-f-num-6345521%2F</productURL>
				<imageURL>https://www02.cp-static.com/objects/low_pic/3/350/1349753599_voedingverloopstekkers-notebook-targus-pt-f-pt-f.jpg</imageURL>
				<description><![CDATA[Targus Laptop Connector - 90W - works with Targus 90 watt adapters to power and charge select laptops]]></description>
				<categories>
				<category path="accessoires">accessoires</category>
				</categories>
				<additional>
				<field name="brand">Targus</field>
				<field name="producttype">F, Black, 25 pcs</field>
				<field name="deliveryCosts">0.00</field>
				<field name="SKU">PT-F</field>
				<field name="brand_and_type">Targus PT-F</field>
				<field name="stock">Niet op voorraad</field>
				<field name="thumbnailURL">https://www02.cp-static.com/objects/thumb_pic/3/350/1349753599_voedingverloopstekkers-notebook-targus-pt-f-pt-f.jpg</field>
				<field name="deliveryTime">Backorder</field>
				<field name="categoryURL">http://www.centralpoint.nl/tracker/index.php?tt=534_251713_1_&amp;r=http%3A%2F%2Fwww.centralpoint.nl%2Fvoedingverloopstekkers-notebook%2F</field>
				<field name="subcategories">voedingverloopstekkers notebook</field>
				<field name="subsubcategories"></field>
				<field name="imageURL_large">https://www02.cp-static.com/objects/high_pic/3/350/1349753599_voedingverloopstekkers-notebook-targus-pt-f-pt-f.jpg</field>
				<field name="ean">5051794020069</field>
				</additional>
				</product>
				</products>';
		$reader = new \XMLReader();
		$reader->XML($xml);
		$doc = new \DOMDocument;
		// move to the first <product /> node
		while ($reader->read() && $reader->name !== 'product');
		$product = simplexml_import_dom($doc->importNode($reader->expand(), true));
		$name = $product->name;
		$id = $product->productID;
		$array_parsed = [$id, $name];
		$array_const = ["targus_pt-f", "Targus F, Black, 25 pcs (PT-F)"];
		$this->assertEquals($array_parsed, $array_const);
	}
}
