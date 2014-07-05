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
namespace WellCommerce\Plugin\Category\Form;

use WellCommerce\Core\Component\Form\AbstractForm;
use WellCommerce\Core\Component\Form\FormBuilderInterface;
use WellCommerce\Core\Component\Form\FormInterface;

/**
 * Class CategoryTree
 *
 * @package WellCommerce\Plugin\Category\Form
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class CategoryTree extends AbstractForm implements FormInterface
{
    /**
     * Registers functions needed in categories tree
     */
    private function registerFunctions()
    {
        $this->getXajax()->registerFunction(Array(
            'DuplicateCategory',
            $this->get('category.repository'),
            'duplicateCategory'
        ));

        $this->getXajaxManager()->registerFunction([
            'AddCategory',
            $this->get('category.repository'),
            'quickAddCategory'
        ]);

        $this->getXajaxManager()->registerFunction([
            'DeleteCategory',
            $this->get('category.repository'),
            'delete'
        ]);

        $this->getXajaxManager()->registerFunction([
            'ChangeCategoryOrder',
            $this->get('category.repository'),
            'changeCategoryOrder'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->registerFunctions();

        $form = $builder->addForm($options);

        $form->addChild($builder->addTree([
            'name'                             => 'categories',
            'label'                            => $this->trans('Categories'),
            'add_item_prompt'                  => $this->trans('Category name'),
            'addLabel'                         => $this->trans('Add category'),
            'sortable'                         => true,
            'selectable'                       => false,
            'clickable'                        => true,
            'deletable'                        => true,
            'addable'                          => true,
            'prevent_duplicates'               => true,
            'items'                            => $this->get('category.repository')->getCategoriesTree(),
            'onClick'                          => 'openCategoryEditor',
            'onDuplicate'                      => 'xajax_DuplicateCategory',
            'onSaveOrder'                      => 'xajax_ChangeCategoryOrder',
            'onAdd'                            => 'xajax_AddCategory',
            'onAfterAdd'                       => 'openCategoryEditor',
            'onDelete'                         => 'xajax_DeleteCategory',
            'onAfterDelete'                    => 'openCategoryEditor',
            'active'                           => (int)$this->getParam('id')
        ]));

        $form->addFilters([
            $builder->addFilterTrim(),
            $builder->addFilterNoCode(),
            $builder->addFilterSecure()
        ]);

        return $form;
    }
}
