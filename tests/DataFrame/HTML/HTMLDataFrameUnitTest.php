<?php namespace Archon\Tests\DataFrame\HTML;

use Archon\DataFrame;

class HTMLDataFrameUnitTest extends \PHPUnit_Framework_TestCase {

    public function testToHTML() {
        $df = DataFrame::fromArray([
            ['a' => 1, 'b' => 2, 'c' => 3],
            ['a' => 4, 'b' => 5, 'c' => 6],
            ['a' => 7, 'b' => 8, 'c' => 9],
        ]);

        $html = $df->toHTML();
    }

}
