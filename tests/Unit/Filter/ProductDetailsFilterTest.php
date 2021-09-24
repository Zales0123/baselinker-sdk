<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\BaselinkerSdk\Tests\Unit\Filter;

use Spinbits\BaselinkerSdk\Filter\ProductDetailsFilter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Spinbits\BaselinkerSdk\Rest\Input;

/** Class ProductDetailsFilterTest */
class ProductDetailsFilterTest extends TestCase
{
    /** @test */
    public function testGetIds()
    {
        $input = $this->createMock(Input::class);

        $input->expects($this->once())
            ->method('get')
            ->with(...['products_id'])
            ->willReturn('1,2,3');

        $this->sut = new ProductDetailsFilter($input);

        $result = $this->sut->getIds();

        $this->assertSame(['1','2','3'], $result);
    }

    /** @test */
    public function testGetLimitWillReturnAlways50()
    {
        $input = $this->createMock(Input::class);

        $input->expects($this->never())
            ->method('get');

        $this->sut = new ProductDetailsFilter($input);

        $result = $this->sut->getLimit();
        $this->assertEquals(50, $result);
    }

    /** @test */
    public function testGetPage()
    {
        $input = $this->createMock(Input::class);

        $input->expects($this->once())
            ->method('get')
            ->with(...['page'])
            ->willReturn(5);

        $this->sut = new ProductDetailsFilter($input);

        $result = $this->sut->getPage();
        $this->assertEquals(5, $result);
    }

    /** @test */
    public function testGetPageReturnsMinimumOne()
    {
        $input = $this->createMock(Input::class);

        $input->expects($this->once())
            ->method('get')
            ->with(...['page'])
            ->willReturn(-15);

        $this->sut = new ProductDetailsFilter($input);

        $result = $this->sut->getPage();
        $this->assertEquals(1, $result);
    }
}
