<?php
/**
 * @author Marcin Hubert <>
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\BaselinkerSdk\Handler\Sylius\Repository;

use Pagerfanta\PagerfantaInterface;
use Spinbits\BaselinkerSdk\Filter\PageOnlyFilter;
use Spinbits\BaselinkerSdk\Filter\ProductDetailsFilter;
use Spinbits\BaselinkerSdk\Filter\ProductListFilter;

interface BaseLinkerProductRepositoryInterface
{
    public function fetchBaseLinkerData(ProductListFilter $filter): PagerfantaInterface;

    public function fetchBaseLinkerPriceData(PageOnlyFilter $filter): PagerfantaInterface;

    public function fetchBaseLinkerQuantityData(PageOnlyFilter $filter): PagerfantaInterface;

    public function fetchBaseLinkerDetailedData(ProductDetailsFilter $filter): PagerfantaInterface;
}
