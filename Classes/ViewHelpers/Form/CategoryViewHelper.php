<?php
namespace DL\Yag\ViewHelpers\Form;

class CategoryViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Form\SelectViewHelper
{
    /**
     * @var \TYPO3\CMS\Extbase\Domain\Repository\CategoryRepository
     * @inject
     */
    protected $categoryRepository;


    /**
     * @var array
     */
    protected static $categoryDataCache = [];


    /**
     * Initialize arguments.
     *
     * @return void
     * @api
     */
    public function initializeArguments()
    {
        parent::initializeArguments();

        $this->registerTagAttribute('categoryPid', 'integer', 'The Pid, where the categories should be taken from', true);
        $this->overrideArgument('options', 'array', 'Associative array with internal IDs as key, and the values are displayed in the select box', false);
    }


    /**
     * @return string
     */
    public function render()
    {
        $this->arguments['options'] = $this->buildOptions($this->buildCategoryData());

        return parent::render();
    }


    /**
     * @param $categories
     * @return array
     */
    protected function buildOptions($categories)
    {
        $options = [];

        foreach ($categories as $category) { /** @var \TYPO3\CMS\Extbase\Domain\Model\Category $category */
            $options[$category->getUid()] = $category->getTitle();
        }

        return $options;
    }


    /**
     * @return array
     */
    protected function buildCategoryData()
    {
        $pid = (int) $this->arguments['categoryPid'];

        if (!array_key_exists($pid, self::$categoryDataCache)) {
            self::$categoryDataCache[$pid] = $this->categoryRepository->findByPid($pid);
        }

        return self::$categoryDataCache[$pid];
    }
}
