<?php
/**
 * @author Marcin Hubert <>
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\BaselinkerSdk\Handler\Sylius;

use Spinbits\BaselinkerSdk\Handler\HandlerInterface;
use Spinbits\BaselinkerSdk\Rest\Input;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

class ProductsCategoriesActionHandler implements HandlerInterface
{
    private TaxonRepositoryInterface $taxonRepository;
    private const BLACK_LIST = ['MENU_CATEGORY'];

    public function __construct(
        TaxonRepositoryInterface $taxonRepository
    ) {
        $this->taxonRepository = $taxonRepository;
    }

    public function handle(Input $input): array
    {
        /* @var $taxons TaxonInterface[] */
        $taxons = $this->taxonRepository->findAll();

        $return = [];
        foreach ($taxons as $taxon) {
            if ($this->canHandle($taxon)) {
                $return[$taxon->getCode()] = $taxon->getFullname();
            }
        }

        return $return;
    }

    /**
     * @param TaxonInterface $taxon
     * @return bool
     */
    private function canHandle(TaxonInterface $taxon): bool
    {
        return $taxon->isEnabled() && !in_array($taxon->getCode(), self::BLACK_LIST);
    }
}
