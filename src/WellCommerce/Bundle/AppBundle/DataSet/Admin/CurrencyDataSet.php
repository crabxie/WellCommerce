<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\AppBundle\DataSet\Admin;

use Doctrine\ORM\QueryBuilder;
use WellCommerce\Bundle\AppBundle\Entity\Currency;
use WellCommerce\Bundle\DataSetBundle\DataSet\AbstractDataSet;
use WellCommerce\Component\DataSet\Cache\CacheOptions;
use WellCommerce\Component\DataSet\Configurator\DataSetConfiguratorInterface;

/**
 * Class CurrencyDataSet
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class CurrencyDataSet extends AbstractDataSet
{
    public function getIdentifier(): string
    {
        return 'admin.currency';
    }
    
    public function configureOptions(DataSetConfiguratorInterface $configurator)
    {
        $configurator->setColumns([
            'id'   => 'currency.id',
            'code' => 'currency.code',
        ]);
        
        $configurator->setCacheOptions(new CacheOptions(true, 3600, [
            Currency::class,
        ]));
    }
    
    protected function createQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->repository->getQueryBuilder();
        $queryBuilder->groupBy('currency.id');
        $queryBuilder->orderBy('currency.code', 'asc');
        
        return $queryBuilder;
    }
}
